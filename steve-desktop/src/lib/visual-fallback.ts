import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';
import { isInteractive, selectorForNode } from './fingerprint';

// Visual fallback (stage 6) — the Tarsier pattern, and deliberately the LAST tier.
//
// Every earlier tier reads structure: the recorded selector, stored candidates, the fingerprint
// ranker, then the model arbitrating a shortlist. Those cover any page whose controls exist in
// the DOM with usable roles. What they cannot see is a control that isn't really there —
// canvas-drawn widgets, image maps, or a page whose a11y is so broken that nothing carries a
// role or name. For those, the only remaining evidence is what the page LOOKS like.
//
// So this tags each candidate with a bracketed number, hands the model a screenshot plus the
// numbered legend, and accepts only a number back. The model never invents a selector — it picks
// a tag we already resolved, which keeps the same "choose, don't invent" contract as the arbiter.
//
// It is gated hard (shouldUseVisualFallback) because it is the most expensive tier we have: a
// screenshot is worth roughly an order of magnitude more tokens than a pruned tree.

export interface VisualTag {
  /** 1-based number drawn over the element and shown in the legend. */
  id: number;
  selector: string;
  label: string;
  bbox?: SnapshotNode['bbox'];
}

/** Number every element a click could plausibly land on, in reading order. */
export function tagCandidates(snapshot: SnapshotResult, max = 30): VisualTag[] {
  return snapshot.nodes
    .filter(isInteractive)
    .filter((n) => !n.bbox || n.bbox.visible !== false)
    .slice(0, max)
    .map((n, i) => ({
      id: i + 1,
      selector: selectorForNode(n),
      label: (n.attrs['aria-label'] || n.attrs['name'] || n.text || n.tag || '').trim().slice(0, 60),
      bbox: n.bbox,
    }));
}

/**
 * Only worth the screenshot when structure has genuinely run out: nothing structural matched, and
 * either the page yielded no usable candidates or its elements carry no distinguishing labels.
 * A page with good structure must never reach here — the cheaper tiers are strictly better.
 */
export function shouldUseVisualFallback(opts: {
  structuralTiersMissed: boolean;
  rankedCandidates: number;
  labelledCandidates: number;
}): boolean {
  if (!opts.structuralTiersMissed) return false;
  if (opts.rankedCandidates === 0) return true; // nothing addressable — vision is all that's left
  return opts.labelledCandidates === 0; // controls exist but are anonymous (canvas-ish / broken a11y)
}

/** The instruction shown beside the screenshot. Answer space is the tag numbers, nothing else. */
export function buildVisualPrompt(intent: string, tags: VisualTag[]): string {
  return [
    'The page could not be matched structurally, so you are looking at a screenshot instead.',
    `Find the element for: "${intent}"`,
    '',
    'Each interactive element is drawn with a bracketed number. The legend:',
    ...tags.map((t) => `[${t.id}] ${t.label || '(no label)'}`),
    '',
    'Reply with ONLY the number of the correct element, or NONE if it is not visible.',
  ].join('\n');
}

/**
 * Resolve the model's reply to a selector. Accepts a bare number or "[3]"; anything else — prose,
 * an invented selector, an out-of-range number — resolves to null so a confused reply becomes a
 * skip rather than a click on the wrong control.
 */
export function resolveVisualChoice(reply: string, tags: VisualTag[]): string | null {
  const cleaned = reply.trim();
  if (/^none$/i.test(cleaned)) return null;
  const m = cleaned.match(/^\[?(\d{1,3})\]?$/);
  if (!m) return null;
  return tags.find((t) => t.id === Number(m[1]))?.selector ?? null;
}

/**
 * Redact the legend against the tree redaction's value map — same dictionary swap, same rule as
 * callModelTree. The model reads tokenized labels; the caller still resolves the reply against the
 * REAL tags, so a token can never end up in a selector.
 */
export function redactTags(tags: VisualTag[], map: Record<string, string>): VisualTag[] {
  const pairs = Object.entries(map).sort((a, b) => b[1].length - a[1].length);
  return tags.map((t) => {
    let label = t.label;
    for (const [token, value] of pairs) if (value.trim().length >= 3) label = label.split(value).join(token);
    return { ...t, label };
  });
}

/**
 * The gate for the visual path. The screenshot itself is masked in-page (it cannot be tokenized
 * after capture), and this refuses the call if any redacted data value survived into the legend —
 * deny-by-default, matching callModelTree rather than bypassing it.
 */
export function assertLegendClean(prompt: string, map: Record<string, string>): void {
  for (const value of Object.values(map)) {
    if (value.trim().length >= 3 && prompt.includes(value)) {
      throw new Error('Refusing visual model call: a redacted data value leaked into the legend.');
    }
  }
}

/**
 * Page script that draws the numbered badges the screenshot is supposed to show. Self-contained
 * and removable: it appends one absolutely-positioned overlay it can delete wholesale, so the
 * page the user is watching is never left marked up. Coordinates come from the live elements
 * rather than stored bboxes, so the badges land correctly even after a reflow.
 *
 * With `mask` (the default, and what the live capture uses) it first makes every glyph and image
 * on the page invisible, so the picture that leaves the machine carries layout and our badges but
 * nothing readable. That is the only redaction a screenshot can get — so it fails CLOSED: if the
 * mask cannot be installed, the script returns false and the caller sends nothing.
 *
 * The mask is a STYLESHEET, not painted boxes. Boxes were the first attempt and they leaked badly
 * on a live page: rects drawn from text-node geometry landed offset from the glyphs, and an
 * <input>'s value is not a text node at all, so a real username sat in plain sight in the capture.
 * Transparent text needs no coordinates, cannot drift, and catches field values and placeholders
 * for free — while layout, which is the only thing this tier actually reads, is untouched.
 *
 * ponytail: hides text and media. Pixels painted into a <canvas> stay visible, because
 * canvas-drawn widgets are the case this tier exists for — do not point it at a canvas that
 * renders student data.
 */
export function overlayScript(tags: { id: number; selector: string }[], opts: { mask?: boolean } = {}): string {
  const pairs = JSON.stringify(tags.map((t) => [t.id, t.selector]));
  const mask = opts.mask !== false;
  // Every text-bearing property blanked, media hidden, then the badges exempted by id so the one
  // thing the model must read survives the blanking.
  const MASK_CSS = [
    '*,*::before,*::after{color:transparent !important;-webkit-text-fill-color:transparent !important;',
    'text-shadow:none !important;caret-color:transparent !important}',
    'input,textarea,select,option{color:transparent !important;-webkit-text-fill-color:transparent !important}',
    'input::placeholder,textarea::placeholder{color:transparent !important}',
    'img,svg,video,picture,iframe,object,embed{visibility:hidden !important}',
    '#__steveVisualTags,#__steveVisualTags *{color:#fff !important;',
    '-webkit-text-fill-color:#fff !important;visibility:visible !important}',
  ].join('');
  return `(function(){
    try{
      var old=document.getElementById('__steveVisualTags'); if(old)old.remove();
      var oldCss=document.getElementById('__steveVisualMask'); if(oldCss)oldCss.remove();
      if(${mask}){
        var st=document.createElement('style'); st.id='__steveVisualMask';
        st.textContent=${JSON.stringify(MASK_CSS)};
        document.documentElement.appendChild(st);
        // Fail closed: no stylesheet, no screenshot.
        if(!document.getElementById('__steveVisualMask'))return false;
      }
      var box=document.createElement('div'); box.id='__steveVisualTags';
      box.style.cssText='position:fixed;inset:0;pointer-events:none;z-index:2147483646';
      var pairs=${pairs}, drawn=[];
      for(var i=0;i<pairs.length;i++){
        var el=null; try{ el=document.querySelector(pairs[i][1]); }catch(e){}
        if(!el||!el.getBoundingClientRect)continue;
        var r=el.getBoundingClientRect(); if(!r.width||!r.height)continue;
        // A rect can be non-empty and still sit outside the captured viewport (scrolled past,
        // off to one side). Those badges never appear in the screenshot, so offering their
        // numbers would invite a pick the model cannot actually see.
        if(r.bottom<=0||r.right<=0||r.top>=innerHeight||r.left>=innerWidth)continue;
        var b=document.createElement('div');
        b.textContent='['+pairs[i][0]+']';
        b.style.cssText='position:absolute;left:'+Math.max(0,r.left)+'px;top:'+Math.max(0,r.top)+'px;'+
          'background:#e11d48;color:#fff;font:700 11px/1.4 monospace;padding:0 4px;border-radius:3px';
        box.appendChild(b);
        drawn.push(pairs[i][0]);
      }
      document.documentElement.appendChild(box);
      // The ids that ACTUALLY got a badge. On a live login page 23 candidates yielded 3 badges —
      // the rest sit in a collapsed menu with no box — so returning plain true would have handed
      // the model a legend describing 20 elements its screenshot does not show.
      return drawn;
    }catch(e){return false;}
  })()`;
}

/** Remove the badges AND the mask stylesheet. Always run this after the capture, including on
 *  failure — the user is looking at this page, and a page left with transparent text is worse
 *  than one left with badges on it. */
export const OVERLAY_REMOVE = `(function(){
  var o=document.getElementById('__steveVisualTags');if(o)o.remove();
  var m=document.getElementById('__steveVisualMask');if(m)m.remove();
  return true;
})()`;

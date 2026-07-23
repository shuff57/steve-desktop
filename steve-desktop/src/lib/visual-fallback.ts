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
 * Page script that draws the numbered badges the screenshot is supposed to show. Self-contained
 * and removable: it appends one absolutely-positioned overlay it can delete wholesale, so the
 * page the user is watching is never left marked up. Coordinates come from the live elements
 * rather than stored bboxes, so the badges land correctly even after a reflow.
 */
export function overlayScript(tags: { id: number; selector: string }[]): string {
  const pairs = JSON.stringify(tags.map((t) => [t.id, t.selector]));
  return `(function(){
    try{
      var old=document.getElementById('__steveVisualTags'); if(old)old.remove();
      var box=document.createElement('div'); box.id='__steveVisualTags';
      box.style.cssText='position:fixed;inset:0;pointer-events:none;z-index:2147483646';
      var pairs=${pairs};
      for(var i=0;i<pairs.length;i++){
        var el=null; try{ el=document.querySelector(pairs[i][1]); }catch(e){}
        if(!el||!el.getBoundingClientRect)continue;
        var r=el.getBoundingClientRect(); if(!r.width||!r.height)continue;
        var b=document.createElement('div');
        b.textContent='['+pairs[i][0]+']';
        b.style.cssText='position:absolute;left:'+Math.max(0,r.left)+'px;top:'+Math.max(0,r.top)+'px;'+
          'background:#e11d48;color:#fff;font:700 11px/1.4 monospace;padding:0 4px;border-radius:3px';
        box.appendChild(b);
      }
      document.documentElement.appendChild(box);
      return true;
    }catch(e){return false;}
  })()`;
}

/** Remove the badges. Always run this after the capture, including on failure. */
export const OVERLAY_REMOVE = `(function(){var o=document.getElementById('__steveVisualTags');if(o)o.remove();return true;})()`;

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

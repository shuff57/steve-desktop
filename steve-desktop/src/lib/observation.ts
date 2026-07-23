import type { SnapshotNode, SnapshotResult } from './dom-snapshot-types';
import { isInteractive } from './fingerprint';

// AX-tree-first observation (stage 4).
//
// Everything downstream — heal ranking, the redacted payload a model sees — was fed the whole
// captured DOM. Most of it is chrome the agent can't act on, and it dominates token cost. The
// accessibility tree already rides along in captureMergedTree (role/name are merged onto each
// node), so the cheap win is to OBSERVE through it: keep what's interactive and visible, drop
// the rest.
//
// This is a view, not a replacement: pruning is skipped when the page's a11y is too poor to
// observe through (see hasUsableAx), because on those pages role/name are absent and pruning
// would throw away the only signal left. Screenshot/vision stays a separate last-tier fallback.

/** A node is observable if it is interactive and not explicitly hidden. */
export function isObservable(node: SnapshotNode): boolean {
  if (!isInteractive(node)) return false;
  if (node.bbox && node.bbox.visible === false) return false;
  const a = node.attrs ?? {};
  if (a['aria-hidden'] === 'true') return false;
  if (a['hidden'] !== undefined && a['hidden'] !== 'false') return false;
  if (a['type'] === 'hidden') return false;
  return true;
}

/**
 * True when enough interactive nodes carry an AX role or accessible name to trust the AX tree as
 * the observation source. Pages that render controls as bare unlabelled divs fail this, and we
 * keep the full DOM for them rather than pruning away the only evidence.
 */
export function hasUsableAx(snapshot: SnapshotResult, minRatio = 0.5): boolean {
  const interactive = snapshot.nodes.filter(isInteractive);
  if (interactive.length === 0) return false;
  const labelled = interactive.filter((n) => {
    const a = n.attrs ?? {};
    return !!(a['role'] || a['aria-label'] || a['name'] || n.text?.trim());
  }).length;
  return labelled / interactive.length >= minRatio;
}

export interface Observation {
  snapshot: SnapshotResult;
  /** False when the page's a11y was too poor to prune through — the full DOM was kept. */
  pruned: boolean;
  /** Nodes dropped by pruning, for the telemetry/debug line. */
  dropped: number;
}

/**
 * Prune a captured snapshot to what an agent can actually act on. Returns the original snapshot
 * untouched (pruned: false) when the page has unusable a11y, so a broken-a11y page degrades to
 * the DOM rather than to nothing.
 */
export function observe(snapshot: SnapshotResult): Observation {
  if (!hasUsableAx(snapshot)) {
    return { snapshot, pruned: false, dropped: 0 };
  }
  const nodes = snapshot.nodes.filter(isObservable);
  // Pruning to zero would blind the caller; treat it as unusable rather than "observed nothing".
  if (nodes.length === 0) {
    return { snapshot, pruned: false, dropped: 0 };
  }
  const charCount = nodes.reduce((n, x) => n + (x.text?.length ?? 0), 0);
  return {
    snapshot: {
      nodes,
      meta: { ...snapshot.meta, nodesIncluded: nodes.length, nodesDropped: snapshot.nodes.length - nodes.length, charCount },
    },
    pruned: true,
    dropped: snapshot.nodes.length - nodes.length,
  };
}

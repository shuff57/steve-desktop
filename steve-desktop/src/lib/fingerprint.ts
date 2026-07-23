import type { SnapshotNode, SnapshotResult } from './dom-snapshot-types';

// Weighted element fingerprints (stage 3).
//
// The old heal chain stopped at the FIRST candidate selector that still matched, and its fuzzy
// tier scored only label-token overlap. Both are brittle: the first match isn't necessarily the
// right element, and a renamed label sinks the fuzzy tier entirely. Instead we record many weak
// signals per element and, on a miss, score EVERY interactive element against the stored
// fingerprint — a renamed id or moved position costs a little, it doesn't disqualify.
//
// Scoring is normalized over the signals the two fingerprints actually share, so an element
// captured without geometry isn't punished against one that has it. Deterministic and pure —
// no model involved in ranking; the model only ever arbitrates the top-k (see rankCandidates).

export interface ElementFingerprint {
  role?: string;
  /** Accessible name (AX) — the most durable anchor we have. */
  name?: string;
  text?: string;
  tag?: string;
  id?: string;
  classes?: string[];
  href?: string;
  /** Text of the nearest labelling sibling — survives an element's own rename. */
  neighborText?: string;
  siblingIndex?: number;
  /** Cheap stand-in for an ancestor path; equal hashes mean "same structural slot". */
  ancestorHash?: string;
  /** Bounding-box centre + size. Present only when the snapshot captured geometry. */
  cx?: number;
  cy?: number;
  w?: number;
  h?: number;
}

/** Relative trust in each signal. Role+name lead because they survive restyling and re-layout;
 *  geometry trails because a responsive page moves things without changing identity. */
const WEIGHTS: Record<keyof ElementFingerprint, number> = {
  role: 18,
  name: 22,
  text: 14,
  tag: 6,
  id: 12,
  classes: 5,
  href: 10,
  neighborText: 8,
  siblingIndex: 4,
  ancestorHash: 6,
  cx: 3,
  cy: 3,
  w: 2,
  h: 2,
};

const STOPWORDS = new Set(['the', 'a', 'an', 'of', 'to', 'for', 'and']);

function tokens(text: string): string[] {
  return text
    .toLowerCase()
    .replace(/([a-z])([A-Z])/g, '$1 $2')
    .split(/[^a-z0-9]+/i)
    .filter((t) => t && !STOPWORDS.has(t));
}

/** Jaccard overlap — order-insensitive so reworded labels still score partial credit. */
function tokenSim(a: string, b: string): number {
  const A = new Set(tokens(a));
  const B = new Set(tokens(b));
  if (!A.size || !B.size) return 0;
  let shared = 0;
  for (const t of A) if (B.has(t)) shared += 1;
  return shared / (A.size + B.size - shared);
}

function stringSim(a: string, b: string): number {
  if (a === b) return 1;
  if (a.toLowerCase() === b.toLowerCase()) return 0.95;
  return tokenSim(a, b);
}

/** Proximity on a pixel axis: identical = 1, fading to 0 by `tolerance` px away. */
function numSim(a: number, b: number, tolerance: number): number {
  const d = Math.abs(a - b);
  return d >= tolerance ? 0 : 1 - d / tolerance;
}

/** Stable non-crypto hash — only ever compared for equality. */
export function hashPath(path: string): string {
  let h = 5381;
  for (let i = 0; i < path.length; i++) h = ((h << 5) + h + path.charCodeAt(i)) | 0;
  return (h >>> 0).toString(36);
}

/** Build a fingerprint from a snapshot node. `neighbors` supplies surrounding nodes so a
 *  labelling sibling can be captured; `index` is the node's position among its peers. */
export function fingerprintOf(node: SnapshotNode, index = 0, neighbors: SnapshotNode[] = []): ElementFingerprint {
  const attrs = node.attrs ?? {};
  const classes = (attrs['class'] ?? '').trim().split(/\s+/).filter(Boolean).slice(0, 6);
  // Nearest preceding node with text is almost always the visible label for a control.
  const label = [...neighbors]
    .slice(0, index)
    .reverse()
    .find((n) => (n.text ?? '').trim());

  const fp: ElementFingerprint = {
    tag: node.tag || undefined,
    role: attrs['role'] || undefined,
    name: attrs['aria-label'] || attrs['name'] || undefined,
    text: node.text?.trim() || undefined,
    id: attrs['id'] || undefined,
    href: attrs['href'] || undefined,
    neighborText: label?.text?.trim() || undefined,
    siblingIndex: index,
    ancestorHash: attrs['data-ancestor'] ? hashPath(attrs['data-ancestor']) : undefined,
  };
  if (classes.length) fp.classes = classes;
  if (node.bbox) {
    fp.cx = node.bbox.x + node.bbox.width / 2;
    fp.cy = node.bbox.y + node.bbox.height / 2;
    fp.w = node.bbox.width;
    fp.h = node.bbox.height;
  }
  return fp;
}

/**
 * Weighted similarity in [0,1]. Only signals present on BOTH sides contribute, and the result is
 * divided by the weight actually compared — so a sparse fingerprint yields a fair score rather
 * than a low one, and two elements sharing nothing score 0.
 */
export function scoreFingerprint(stored: ElementFingerprint, candidate: ElementFingerprint): number {
  let total = 0;
  let earned = 0;

  const cmp = (key: keyof ElementFingerprint, sim: number) => {
    total += WEIGHTS[key];
    earned += WEIGHTS[key] * sim;
  };

  for (const key of ['role', 'tag', 'id', 'href'] as const) {
    const a = stored[key];
    const b = candidate[key];
    if (a && b) cmp(key, a === b ? 1 : a.toLowerCase() === b.toLowerCase() ? 0.95 : 0);
  }
  for (const key of ['name', 'text', 'neighborText'] as const) {
    const a = stored[key];
    const b = candidate[key];
    if (a && b) cmp(key, stringSim(a, b));
  }
  if (stored.classes?.length && candidate.classes?.length) {
    const shared = stored.classes.filter((c) => candidate.classes!.includes(c)).length;
    cmp('classes', shared / new Set([...stored.classes, ...candidate.classes]).size);
  }
  if (stored.ancestorHash && candidate.ancestorHash) {
    cmp('ancestorHash', stored.ancestorHash === candidate.ancestorHash ? 1 : 0);
  }
  if (typeof stored.siblingIndex === 'number' && typeof candidate.siblingIndex === 'number') {
    cmp('siblingIndex', numSim(stored.siblingIndex, candidate.siblingIndex, 8));
  }
  // Geometry: generous tolerances — a responsive reflow shifts things without changing identity.
  if (typeof stored.cx === 'number' && typeof candidate.cx === 'number') cmp('cx', numSim(stored.cx, candidate.cx, 400));
  if (typeof stored.cy === 'number' && typeof candidate.cy === 'number') cmp('cy', numSim(stored.cy, candidate.cy, 400));
  if (typeof stored.w === 'number' && typeof candidate.w === 'number') cmp('w', numSim(stored.w, candidate.w, 200));
  if (typeof stored.h === 'number' && typeof candidate.h === 'number') cmp('h', numSim(stored.h, candidate.h, 100));

  return total === 0 ? 0 : earned / total;
}

const INTERACTIVE_TAGS = new Set(['button', 'a', 'input', 'textarea', 'select', 'option']);
const INTERACTIVE_ROLES = new Set(['button', 'link', 'textbox', 'combobox', 'searchbox', 'checkbox', 'radio', 'tab', 'menuitem']);

export function isInteractive(node: SnapshotNode): boolean {
  const role = (node.attrs?.['role'] ?? '').toLowerCase();
  return INTERACTIVE_TAGS.has((node.tag ?? '').toLowerCase()) || INTERACTIVE_ROLES.has(role);
}

/** A selector that addresses this node, preferring the anchors that survive change. */
export function selectorForNode(node: SnapshotNode): string {
  const a = node.attrs ?? {};
  if (a['data-testid']) return `[data-testid="${a['data-testid']}"]`;
  if (a['id']) return `#${a['id']}`;
  if (a['name']) return `${node.tag}[name="${a['name']}"]`;
  // `aria-label` on a merged node is often the COMPUTED accessible name — merged-tree writes the
  // AX name there when the element carries no literal attribute — so the CSS form matches nothing.
  // Live MyOpenMath produced `a[aria-label="MyOpenMath"]` resolving to 0 elements on the very page
  // it came from, which as a key node reports drift on every single run. role=name resolves by
  // accessible name either way (selector-resolve), so prefer it whenever we know the role.
  if (a['aria-label'] && a['role']) return `role=${a['role']}[name="${a['aria-label']}"]`;
  if (a['aria-label']) return `${node.tag}[aria-label="${a['aria-label']}"]`;
  return node.tag;
}

export interface RankedCandidate {
  selector: string;
  score: number;
  node: SnapshotNode;
}

/**
 * Score EVERY interactive element in the snapshot against the stored fingerprint and return the
 * best `k`, highest first. This is the replacement for stop-at-first-match: the caller tries them
 * in order (each still gated by its postcondition), and only escalates to the model to arbitrate
 * between these few — never over the whole tree.
 */
export function rankCandidates(
  stored: ElementFingerprint,
  snapshot: SnapshotResult,
  k = 5,
  minScore = 0.15,
): RankedCandidate[] {
  const nodes = snapshot.nodes.filter(isInteractive);
  return nodes
    .map((node, i) => ({ selector: selectorForNode(node), score: scoreFingerprint(stored, fingerprintOf(node, i, nodes)), node }))
    .filter((c) => c.score >= minScore)
    .sort((a, b) => b.score - a.score)
    .slice(0, k);
}

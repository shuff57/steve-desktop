/**
 * Derive grading selectors from a DOM snapshot — the bridge that turns a page steve
 * doesn't recognise into a `site_profiles` row the grader can read.
 *
 * FERPA note: this works from STRUCTURE only — tag, class, id, attribute names and node
 * repetition. It never reads a node's text, so no student name or work is examined and
 * nothing here is model-bound. The snapshot passed in may carry `text`; we ignore it.
 *
 * The shape we're solving for (confirmed against MyOpenMath gradeallq2, see
 * load-students.ts): a container that REPEATS once per student, and inside it a name
 * element, the answer, a score input, and a feedback box. Repetition is the whole signal —
 * a grading page is a list of near-identical student rows.
 */
import type { SnapshotNode, SnapshotResult } from '../../lib/dom-snapshot-types';
import type { SiteSelectors } from './load-students';

/** A snapshot node with the index it sat at, so we can walk subtrees in document order. */
interface Indexed {
  node: SnapshotNode;
  i: number;
}

/** Classes carrying a run of digits are per-instance (qscope50000, fb-7150099) — unstable. */
function stableClasses(node: SnapshotNode): string[] {
  return (node.attrs['class'] ?? '')
    .split(/\s+/)
    .filter((c) => c && !/\d/.test(c));
}

/**
 * A structural signature for grouping repeats: tag + its stable classes. Two student rows
 * share this even though their ids and digit-classes differ.
 */
function signature(node: SnapshotNode): string {
  const cls = stableClasses(node).sort().join('.');
  return cls ? `${node.tag}.${cls}` : node.tag;
}

/**
 * Generalise an attribute value that ends in instance-specific digits into a prefix match.
 * `ud-7150099-0-0-0` → `^="ud-"`; a value with no digit run → exact match.
 */
function attrSelector(tag: string, attr: string, value: string): string {
  // Prefix = everything up to the first digit, separator included: `ud-7150099` → `ud-`.
  const m = value.match(/^(\D+)\d/);
  if (m && m[1] && m[1].length >= 2) return `${tag}[${attr}^="${m[1]}"]`;
  return `${tag}[${attr}="${value}"]`;
}

/** A selector for a node, preferring a stable class, then a generalised id/name, then tag. */
function selectorFor(node: SnapshotNode): string {
  const cls = stableClasses(node);
  if (cls.length) return `${node.tag}.${cls[0]}`;
  if (node.attrs['id']) return attrSelector(node.tag, 'id', node.attrs['id']);
  if (node.attrs['name']) return attrSelector(node.tag, 'name', node.attrs['name']);
  return node.tag;
}

/** Tags that are fields, not rows — a container that repeats will not be one of these. */
const LEAF_TAGS = /^(input|textarea|select|option|b|strong|a|span|label|img|svg|br|hr|td|th|li)$/;

/**
 * Candidate student rows, outermost first. Every signature that repeats (≥2) and isn't a
 * leaf field is a candidate; the caller tries them in order and keeps the first whose
 * subtree actually holds a name and an answer.
 *
 * Outermost-first matters because a page's FIELDS repeat as often as its rows (a table with
 * two students has two names, two answers, two score cells — the same count as two rows).
 * Ranking by depth, not by count, lands on the row that wraps those fields rather than on
 * one of the fields. A wrapper holding ALL students appears once, so it never out-ranks the
 * per-student row it can't tie on count.
 */
function candidateContainers(nodes: Indexed[]): Indexed[] {
  const groups = new Map<string, Indexed[]>();
  for (const item of nodes) {
    if (LEAF_TAGS.test(item.node.tag)) continue;
    const sig = signature(item.node);
    (groups.get(sig) ?? groups.set(sig, []).get(sig)!).push(item);
  }

  return [...groups.values()]
    .filter((items) => items.length >= 2)
    .map((items) => items[0]!)
    .sort((a, b) => a.node.depth - b.node.depth); // outermost row first
}

/** The nodes inside one container instance: everything after it, deeper, until depth returns. */
function subtreeOf(container: Indexed, all: Indexed[]): Indexed[] {
  const out: Indexed[] = [];
  for (let k = container.i + 1; k < all.length; k++) {
    const it = all[k]!;
    if (it.node.depth <= container.node.depth) break;
    out.push(it);
  }
  return out;
}

// "name"/"person" only — not "header", which names a region (headerpane) not the name itself.
const NAME_CLASS = /person|name/i;
// Answer-body classes only. Deliberately NOT "submission"/"work" — those name a row or a
// header region (submission-head) as often as the answer itself, and would mis-pick it.
const RESP_CLASS = /introtext|answer|response|essay|qnwrap|solution/i;
const FEEDBACK_CLASS = /fbbox|feedback|comment|note/i;
const SCORE_HINT = /score|grade|point|pts|mark|ud-/i;

function firstMatch(items: Indexed[], pred: (n: SnapshotNode) => boolean): SnapshotNode | null {
  for (const it of items) if (pred(it.node)) return it.node;
  return null;
}

/**
 * Derive the five grading selectors, or null when the page has no repeated container —
 * which is the signal that this is not a roster page (or the snapshot missed it), and the
 * caller should fall back rather than save a guess.
 */
export function deriveGradingSelectors(snapshot: SnapshotResult): SiteSelectors | null {
  const all: Indexed[] = snapshot.nodes.map((node, i) => ({ node, i }));

  // Try each candidate row outermost-first; keep the first that resolves a name AND answer.
  for (const container of candidateContainers(all)) {
    const inside = subtreeOf(container, all);
    if (inside.length === 0) continue;

    // Name: an element whose class names it, else the first heading/bold in the row.
    const nameNode =
      firstMatch(inside, (nd) => NAME_CLASS.test(nd.attrs['class'] ?? '') && /^(b|strong|h[1-6]|span|div)$/.test(nd.tag)) ??
      firstMatch(inside, (nd) => /^(b|strong|h[1-6])$/.test(nd.tag));

    // Response: an element whose class or id names it as the answer.
    const respNode = firstMatch(inside, (nd) => RESP_CLASS.test(nd.attrs['class'] ?? '') || RESP_CLASS.test(nd.attrs['id'] ?? ''));

    // Name and response are the load-bearing pair — without them there's nothing to grade.
    if (!nameNode || !respNode) continue;

    // Score input: one the class/name/id marks as a score, else any real (non-toggle) input.
    const scoreNode =
      firstMatch(inside, (nd) => nd.tag === 'input' && SCORE_HINT.test(`${nd.attrs['name'] ?? ''} ${nd.attrs['id'] ?? ''} ${nd.attrs['aria-label'] ?? ''} ${nd.attrs['class'] ?? ''}`)) ??
      firstMatch(inside, (nd) => nd.tag === 'input' && !['checkbox', 'radio', 'hidden'].includes(nd.attrs['type'] ?? ''));

    // Feedback: a class-named box, else any contenteditable or textarea.
    const fbNode =
      firstMatch(inside, (nd) => FEEDBACK_CLASS.test(nd.attrs['class'] ?? '') || FEEDBACK_CLASS.test(nd.attrs['id'] ?? '')) ??
      firstMatch(inside, (nd) => nd.attrs['contenteditable'] === 'true' || nd.tag === 'textarea');

    return {
      studentSection: selectorFor(container.node),
      studentName: selectorFor(nameNode),
      response: selectorFor(respNode),
      // A missing score/feedback selector is fine — read-only grading needs neither; a
      // harmless default just makes the extractor's querySelector find nothing.
      scoreInput: scoreNode ? selectorFor(scoreNode) : 'input',
      feedbackBox: fbNode ? selectorFor(fbNode) : 'textarea',
    };
  }

  return null;
}

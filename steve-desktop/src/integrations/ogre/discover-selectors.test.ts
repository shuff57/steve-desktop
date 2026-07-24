/**
 * The grading-selector bridge. Built from a snapshot that mirrors MyOpenMath gradeallq2
 * (see load-students.ts): repeated `div.bigquestionwrap` rows, each with a name in
 * `div.headerpane > b`, the answer in `div[id^=qnwrap].introtext`, a score `input[name^=ud-]`,
 * and a `div.fbbox` feedback box. Structure only — no student text is present or read.
 */
import { describe, expect, it } from 'vitest';
import { deriveGradingSelectors } from './discover-selectors';
import type { SnapshotNode, SnapshotResult } from '../../lib/dom-snapshot-types';

let auto = 0;
const n = (tag: string, depth: number, attrs: Record<string, string> = {}): SnapshotNode => ({
  tag,
  depth,
  priority: 'medium',
  attrs,
  selector: `${tag}#${auto++}`,
});

/** Two structurally identical student rows, with per-instance digits in ids/classes. */
function gradeallq2(): SnapshotResult {
  const nodes: SnapshotNode[] = [n('div', 0, { class: 'assessgradermain' })];
  for (const s of [50000, 50001]) {
    nodes.push(n('div', 1, { class: `bigquestionwrap qfilter-nowork qscope${s}` }));
    nodes.push(n('div', 2, { class: 'headerpane' }));
    nodes.push(n('b', 3, {})); // "Last, First" — text deliberately absent
    nodes.push(n('div', 2, { id: `qnwrap${s}000`, class: 'introtext' }));
    nodes.push(n('div', 2, { class: 'scoredetails' }));
    nodes.push(n('input', 3, { name: `ud-71500${s}-0-0-0`, type: 'text' }));
    nodes.push(n('div', 3, { id: `fb-71500${s}`, class: 'fbbox', contenteditable: 'true' }));
  }
  return { nodes, meta: {} as SnapshotResult['meta'] };
}

describe('deriveGradingSelectors', () => {
  it('finds the repeated student row and its fields on a gradeallq2-shaped page', () => {
    const sel = deriveGradingSelectors(gradeallq2());
    expect(sel).not.toBeNull();
    // The row is the class that repeats, with the digit-class dropped.
    expect(sel!.studentSection).toBe('div.bigquestionwrap');
    expect(sel!.studentName).toBe('b'); // bare <b> inside headerpane
    expect(sel!.response).toBe('div.introtext');
    // Instance digits in name/id generalise to a prefix match.
    expect(sel!.scoreInput).toBe('input[name^="ud-"]');
    expect(sel!.feedbackBox).toBe('div.fbbox');
  });

  it('prefers the per-student row over the list wrapper that holds them', () => {
    // Wrap the two rows in a repeated outer section too; the inner (deeper) one must win.
    const base = gradeallq2();
    const sel = deriveGradingSelectors(base);
    expect(sel!.studentSection).toBe('div.bigquestionwrap');
  });

  it('generalises a digit-suffixed name attribute to a prefix selector', () => {
    const sel = deriveGradingSelectors(gradeallq2());
    expect(sel!.scoreInput).toContain('^="ud-"');
    expect(sel!.scoreInput).not.toContain('71500'); // no instance digits leak in
  });

  it('returns null when nothing repeats — not a roster page', () => {
    const nodes = [
      n('div', 0, { class: 'article' }),
      n('h1', 1, {}),
      n('p', 1, {}),
      n('input', 1, { name: 'q' }),
    ];
    expect(deriveGradingSelectors({ nodes, meta: {} as SnapshotResult['meta'] })).toBeNull();
  });

  it('returns null when a repeated row has no name or answer to grade', () => {
    // Repeated nav items repeat, but carry no gradeable content.
    const nodes: SnapshotNode[] = [n('ul', 0, { class: 'menu' })];
    for (let i = 0; i < 3; i++) {
      nodes.push(n('li', 1, { class: 'menu-item' }));
      nodes.push(n('a', 2, { href: `/p${i}` }));
    }
    expect(deriveGradingSelectors({ nodes, meta: {} as SnapshotResult['meta'] })).toBeNull();
  });

  it('falls back to a class-named response even without an obvious heading', () => {
    // Name via a class rather than a <b>; response via its class.
    const nodes: SnapshotNode[] = [n('div', 0, {})];
    for (let i = 0; i < 2; i++) {
      nodes.push(n('section', 1, { class: 'submission-row' }));
      nodes.push(n('span', 2, { class: 'student-name' }));
      nodes.push(n('div', 2, { class: 'response-body' }));
    }
    const sel = deriveGradingSelectors({ nodes, meta: {} as SnapshotResult['meta'] });
    expect(sel!.studentSection).toBe('section.submission-row');
    expect(sel!.studentName).toBe('span.student-name');
    expect(sel!.response).toBe('div.response-body');
  });
});

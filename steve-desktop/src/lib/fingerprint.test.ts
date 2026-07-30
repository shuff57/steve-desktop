import { describe, it, expect } from 'vitest';
import { fingerprintOf, scoreFingerprint, rankCandidates, selectorForNode, isInteractive, isUnanchored } from './fingerprint';
import type { SnapshotNode, SnapshotResult } from './dom-snapshot-types';

function node(tag: string, attrs: Record<string, string> = {}, text = '', bbox?: SnapshotNode['bbox']): SnapshotNode {
  return { tag, depth: 1, priority: 'critical', text, attrs, bbox };
}

function snap(nodes: SnapshotNode[]): SnapshotResult {
  return {
    nodes,
    meta: { totalVisited: nodes.length, nodesIncluded: nodes.length, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
  };
}

describe('scoreFingerprint', () => {
  it('scores an identical element 1', () => {
    const fp = fingerprintOf(node('button', { id: 'save', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'));
    expect(scoreFingerprint(fp, fp)).toBeCloseTo(1, 5);
  });

  it('survives a renamed id — the durable signals still carry it', () => {
    const before = fingerprintOf(node('button', { id: 'save', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'));
    const after = fingerprintOf(node('button', { id: 'btn_9f3a', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'));
    // id disagrees, but role/name/text/tag still match, so it stays a strong match
    expect(scoreFingerprint(before, after)).toBeGreaterThan(0.75);
  });

  it('scores an unrelated element near zero', () => {
    const save = fingerprintOf(node('button', { id: 'save', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'));
    const logout = fingerprintOf(node('a', { id: 'logout', role: 'link', href: '/logout' }, 'Log out'));
    expect(scoreFingerprint(save, logout)).toBeLessThan(0.15);
  });

  it('normalizes over shared signals, so a sparse fingerprint is not unfairly punished', () => {
    const rich = fingerprintOf(node('button', { id: 'save', role: 'button', 'aria-label': 'Save' }, 'Save', { x: 0, y: 0, width: 10, height: 10, visible: true }));
    const sparse = fingerprintOf(node('button', { role: 'button' }, 'Save'));
    // only tag/role/text overlap — all agree, so the score is high despite missing signals
    expect(scoreFingerprint(rich, sparse)).toBeGreaterThan(0.9);
  });

  it('two elements sharing no comparable signal score 0', () => {
    expect(scoreFingerprint({ id: 'a' }, { role: 'button' })).toBe(0);
  });

  it('geometry nudges ranking without deciding it', () => {
    const stored = fingerprintOf(node('button', { role: 'button' }, 'Save', { x: 0, y: 0, width: 80, height: 20, visible: true }));
    const near = fingerprintOf(node('button', { role: 'button' }, 'Save', { x: 5, y: 5, width: 80, height: 20, visible: true }));
    const far = fingerprintOf(node('button', { role: 'button' }, 'Save', { x: 900, y: 900, width: 80, height: 20, visible: true }));
    expect(scoreFingerprint(stored, near)).toBeGreaterThan(scoreFingerprint(stored, far));
  });
});

describe('rankCandidates', () => {
  const page = snap([
    node('a', { id: 'logout', role: 'link', href: '/logout' }, 'Log out'),
    node('input', { id: 'student', name: 'student' }, ''),
    node('button', { id: 'btn_9f3a', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'),
    node('div', { id: 'chrome' }, 'not interactive'),
  ]);

  it('finds the renamed button by ranking every interactive element, not first-match', () => {
    const stored = fingerprintOf(node('button', { id: 'save', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'));
    const ranked = rankCandidates(stored, page);
    // Addressed by accessible name, not by the regenerated id — that is the point of the ladder.
    expect(ranked[0].selector).toBe('role=button[name="Save Grade"]');
    expect(ranked[0].score).toBeGreaterThan(0.7);
  });

  it('never offers a bare-tag candidate — it would address the first tag on the page', () => {
    // Two anonymous links: no testid, id, name, aria-label or role. selectorForNode can only
    // return "a" for either, which resolves to whichever comes first — not the ranked node.
    const anon = snap([node('a', {}, 'Next'), node('a', {}, 'Previous')]);
    const stored = fingerprintOf(node('a', {}, 'Next'));
    const ranked = rankCandidates(stored, anon);
    expect(ranked.every((c) => c.selector !== 'a')).toBe(true);
    expect(ranked).toEqual([]); // nothing addressable, so fall through to the next heal tier
  });

  it('never ranks non-interactive chrome', () => {
    const stored = fingerprintOf(node('div', { id: 'chrome' }, 'not interactive'));
    expect(rankCandidates(stored, page).some((c) => c.selector === '#chrome')).toBe(false);
  });

  it('returns at most k, best first, and drops weak matches', () => {
    const stored = fingerprintOf(node('button', { role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'));
    const ranked = rankCandidates(stored, page, 2);
    expect(ranked.length).toBeLessThanOrEqual(2);
    for (let i = 1; i < ranked.length; i++) expect(ranked[i - 1].score).toBeGreaterThanOrEqual(ranked[i].score);
  });

  it('an unrecognizable element yields no candidates rather than a bad guess', () => {
    expect(rankCandidates({ id: 'nothing-like-this', role: 'slider' }, page)).toEqual([]);
  });
});

describe('selectorForNode / isInteractive', () => {
  it('prefers testid, then role=name, then id, then name, then aria-label', () => {
    expect(selectorForNode(node('button', { 'data-testid': 'save', id: 'x' }))).toBe('[data-testid="save"]');
    expect(selectorForNode(node('button', { id: 'save' }))).toBe('#save');
    expect(selectorForNode(node('input', { name: 'student' }))).toBe('input[name="student"]');
    expect(selectorForNode(node('input', { 'aria-label': 'Student' }))).toBe('input[aria-label="Student"]');
    // A merged node's aria-label is often the COMPUTED accessible name, so the CSS form matches
    // nothing. Live MyOpenMath produced a[aria-label="MyOpenMath"] → 0 elements on its own page.
    expect(selectorForNode(node('a', { 'aria-label': 'MyOpenMath', role: 'link' }))).toBe('role=link[name="MyOpenMath"]');
  });

  it('names by role over id, so a path-addressed student id cannot enter a stored selector', () => {
    // Canvas shape: the id carries identity, the label carries meaning. Take the label.
    const cell = node('a', { id: 'submission_8814327', role: 'link', 'aria-label': 'Grade detail' });
    expect(selectorForNode(cell)).toBe('role=link[name="Grade detail"]');
  });

  it('uses text as the name when a role carries no aria-label', () => {
    expect(selectorForNode(node('button', { role: 'button' }, 'Post grades'))).toBe('role=button[name="Post grades"]');
  });

  it('falls past role=name when the text is prose rather than a label', () => {
    const wordy = node('a', { role: 'link', id: 'tos' }, 'x'.repeat(200));
    expect(selectorForNode(wordy)).toBe('#tos');
  });

  it('reports a node with no anchor at all as unanchored', () => {
    expect(isUnanchored(node('a', {}, ''))).toBe(true);
    expect(isUnanchored(node('button', { id: 'save' }))).toBe(false);
    expect(isUnanchored(node('button', { role: 'button' }, 'Save'))).toBe(false);
  });

  it('treats tags and ARIA roles as interactive', () => {
    expect(isInteractive(node('button'))).toBe(true);
    expect(isInteractive(node('div', { role: 'button' }))).toBe(true);
    expect(isInteractive(node('div'))).toBe(false);
  });
});

import { describe, it, expect } from 'vitest';
import { fingerprintOf, scoreFingerprint, rankCandidates, selectorForNode, isInteractive } from './fingerprint';
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
    expect(ranked[0].selector).toBe('#btn_9f3a');
    expect(ranked[0].score).toBeGreaterThan(0.7);
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
  it('prefers testid, then id, then name, then aria-label', () => {
    expect(selectorForNode(node('button', { 'data-testid': 'save', id: 'x' }))).toBe('[data-testid="save"]');
    expect(selectorForNode(node('button', { id: 'save' }))).toBe('#save');
    expect(selectorForNode(node('input', { name: 'student' }))).toBe('input[name="student"]');
    expect(selectorForNode(node('input', { 'aria-label': 'Student' }))).toBe('input[aria-label="Student"]');
  });

  it('treats tags and ARIA roles as interactive', () => {
    expect(isInteractive(node('button'))).toBe(true);
    expect(isInteractive(node('div', { role: 'button' }))).toBe(true);
    expect(isInteractive(node('div'))).toBe(false);
  });
});

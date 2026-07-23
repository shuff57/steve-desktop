import { describe, it, expect } from 'vitest';
import { observe, isObservable, hasUsableAx } from './observation';
import type { SnapshotNode, SnapshotResult } from './dom-snapshot-types';

function n(tag: string, attrs: Record<string, string> = {}, text = '', bbox?: SnapshotNode['bbox']): SnapshotNode {
  return { tag, depth: 1, priority: 'critical', text, attrs, bbox };
}

function snap(nodes: SnapshotNode[]): SnapshotResult {
  return {
    nodes,
    meta: { totalVisited: nodes.length, nodesIncluded: nodes.length, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
  };
}

describe('isObservable', () => {
  it('keeps interactive, visible controls', () => {
    expect(isObservable(n('button', { role: 'button' }, 'Save'))).toBe(true);
    expect(isObservable(n('input', { name: 'student' }))).toBe(true);
  });

  it('drops chrome and anything explicitly hidden', () => {
    expect(isObservable(n('div', {}, 'just text'))).toBe(false);
    expect(isObservable(n('button', { 'aria-hidden': 'true' }, 'Save'))).toBe(false);
    expect(isObservable(n('input', { type: 'hidden', name: 'csrf' }))).toBe(false);
    expect(isObservable(n('button', {}, 'Save', { x: 0, y: 0, width: 0, height: 0, visible: false }))).toBe(false);
  });
});

describe('observe', () => {
  const page = snap([
    n('h1', {}, 'Grade entry'),
    n('div', {}, 'some prose'),
    n('input', { id: 'student', name: 'student', role: 'textbox' }),
    n('button', { id: 'save', role: 'button', 'aria-label': 'Save Grade' }, 'Save Grade'),
    n('input', { type: 'hidden', name: 'csrf' }),
  ]);

  it('keeps only what the agent can act on, and reports what it dropped', () => {
    const o = observe(page);
    expect(o.pruned).toBe(true);
    expect(o.snapshot.nodes.map((x) => x.attrs['id'] ?? x.attrs['name'])).toEqual(['student', 'save']);
    expect(o.dropped).toBe(3); // h1, div, hidden input
    expect(o.snapshot.meta.nodesDropped).toBe(3);
  });

  it('is a big reduction on a chrome-heavy page', () => {
    const heavy = snap([...Array(50)].map((_, i) => n('div', {}, `filler ${i}`)).concat(n('button', { role: 'button' }, 'Go')));
    const o = observe(heavy);
    expect(o.snapshot.nodes).toHaveLength(1);
    expect(o.dropped).toBe(50);
  });

  it('keeps the full DOM when a11y is too poor to observe through', () => {
    // controls rendered as bare unlabelled divs — role/name absent, so pruning would blind us
    const brokenA11y = snap([
      n('div', { class: 'btn' }),
      n('div', { class: 'btn' }),
      n('span', {}, 'Some label'),
    ]);
    const o = observe(brokenA11y);
    expect(o.pruned).toBe(false);
    expect(o.snapshot.nodes).toHaveLength(3);
  });

  it('never observes nothing — an empty prune falls back to the full snapshot', () => {
    const allHidden = snap([n('button', { role: 'button', 'aria-hidden': 'true' }, 'x')]);
    const o = observe(allHidden);
    expect(o.pruned).toBe(false);
    expect(o.snapshot.nodes).toHaveLength(1);
  });
});

describe('hasUsableAx', () => {
  it('is true when most interactive nodes carry a role or name', () => {
    expect(hasUsableAx(snap([n('button', { role: 'button' }, 'A'), n('input', { name: 'b' })]))).toBe(true);
  });
  it('is false when there is nothing interactive at all', () => {
    expect(hasUsableAx(snap([n('div', {}, 'text')]))).toBe(false);
  });
});

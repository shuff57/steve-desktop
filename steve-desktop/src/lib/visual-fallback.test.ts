import { describe, it, expect } from 'vitest';
import { tagCandidates, shouldUseVisualFallback, buildVisualPrompt, resolveVisualChoice } from './visual-fallback';
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

describe('tagCandidates', () => {
  const page = snap([
    n('div', {}, 'chrome'),
    n('button', { id: 'save' }, 'Save'),
    n('a', { id: 'help', role: 'link' }, 'Help'),
    n('button', { id: 'ghost' }, 'Hidden', { x: 0, y: 0, width: 0, height: 0, visible: false }),
  ]);

  it('numbers only the visible interactive elements, in reading order', () => {
    const tags = tagCandidates(page);
    expect(tags.map((t) => t.id)).toEqual([1, 2]);
    expect(tags[0].selector).toBe('#save');
    expect(tags[1].label).toBe('Help');
  });

  it('caps how many it will tag', () => {
    const many = snap([...Array(50)].map((_, i) => n('button', { id: `b${i}` }, `B${i}`)));
    expect(tagCandidates(many).length).toBeLessThanOrEqual(30);
  });
});

describe('shouldUseVisualFallback — the gate that keeps this last', () => {
  it('never fires while structural tiers still had a chance', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: false, rankedCandidates: 0, labelledCandidates: 0 })).toBe(false);
  });

  it('fires when nothing addressable was found at all', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: true, rankedCandidates: 0, labelledCandidates: 0 })).toBe(true);
  });

  it('fires when controls exist but are anonymous (canvas / broken a11y)', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: true, rankedCandidates: 6, labelledCandidates: 0 })).toBe(true);
  });

  it('does NOT fire when labelled candidates exist — the cheaper tiers are strictly better', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: true, rankedCandidates: 6, labelledCandidates: 4 })).toBe(false);
  });
});

describe('buildVisualPrompt / resolveVisualChoice', () => {
  const tags = [
    { id: 1, selector: '#save', label: 'Save' },
    { id: 2, selector: '#help', label: '' },
  ];

  it('shows a numbered legend and constrains the answer space', () => {
    const p = buildVisualPrompt('save the grade', tags);
    expect(p).toContain('[1] Save');
    expect(p).toContain('[2] (no label)');
    expect(p).toContain('ONLY the number');
    expect(p).toContain('save the grade');
  });

  it('maps a chosen number back to the selector we already resolved', () => {
    expect(resolveVisualChoice('1', tags)).toBe('#save');
    expect(resolveVisualChoice('[2]', tags)).toBe('#help');
    expect(resolveVisualChoice(' 1 ', tags)).toBe('#save');
  });

  it('refuses anything that is not a valid tag — never acts on a confused reply', () => {
    expect(resolveVisualChoice('NONE', tags)).toBeNull();
    expect(resolveVisualChoice('99', tags)).toBeNull();
    expect(resolveVisualChoice('#invented-selector', tags)).toBeNull();
    expect(resolveVisualChoice('I think the second one', tags)).toBeNull();
  });
});

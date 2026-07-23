import { describe, it, expect } from 'vitest';
import {
  deriveKeyNodes,
  verifyKeyNodes,
  emptyDrift,
  recordDrift,
  driftScore,
  shouldRemap,
} from './key-nodes';
import type { SnapshotNode, SnapshotResult } from './dom-snapshot-types';

function n(tag: string, attrs: Record<string, string> = {}, text = ''): SnapshotNode {
  return { tag, depth: 1, priority: 'critical', text, attrs };
}
function snap(nodes: SnapshotNode[]): SnapshotResult {
  return {
    nodes,
    meta: { totalVisited: nodes.length, nodesIncluded: nodes.length, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
  };
}

describe('deriveKeyNodes', () => {
  const page = snap([
    n('div', {}, 'chrome text'),
    n('button', { 'data-testid': 'save-btn' }, 'Save'),
    n('input', { id: 'student', name: 'student' }),
    n('a', { role: 'link', 'aria-label': 'Help' }, 'Help'),
    n('button', {}, 'anonymous'), // no stable anchor
  ]);

  it('picks durable interactive anchors, strongest first', () => {
    const keys = deriveKeyNodes(page);
    expect(keys[0].selector).toBe('[data-testid="save-btn"]');
    expect(keys.map((k) => k.selector)).toContain('#student');
  });

  it('skips chrome and elements with no addressable anchor', () => {
    const keys = deriveKeyNodes(page);
    expect(keys.every((k) => k.selector !== 'button')).toBe(true);
    expect(keys.some((k) => k.label === 'chrome text')).toBe(false);
  });

  it('caps the set — this is a spot check, not a re-map', () => {
    const many = snap([...Array(20)].map((_, i) => n('button', { id: `b${i}` }, `B${i}`)));
    expect(deriveKeyNodes(many).length).toBeLessThanOrEqual(5);
    expect(deriveKeyNodes(many, 3)).toHaveLength(3);
  });
});

describe('verifyKeyNodes', () => {
  const keys = [
    { selector: '#save', label: 'Save' },
    { selector: '#student', label: 'Student' },
  ];

  it('passes when every key node still resolves', async () => {
    const v = await verifyKeyNodes(keys, () => true);
    expect(v.ok).toBe(true);
    expect(v.checked).toBe(2);
    expect(v.missing).toEqual([]);
  });

  it('reports exactly which anchors vanished', async () => {
    const v = await verifyKeyNodes(keys, (s) => s !== '#save');
    expect(v.ok).toBe(false);
    expect(v.missing).toEqual([{ selector: '#save', label: 'Save' }]);
  });

  it('a page with no key nodes is not silently "fine" — it needs a real re-map', async () => {
    const v = await verifyKeyNodes([], () => true);
    expect(v.ok).toBe(false);
    expect(v.checked).toBe(0);
  });
});

describe('drift telemetry', () => {
  it('a clean page scores zero and is never flagged', () => {
    const s = recordDrift(emptyDrift(), [{}, {}, {}, {}]);
    expect(driftScore(s)).toBe(0);
    expect(shouldRemap(s)).toBe(false);
  });

  it('weights later tiers more heavily than a stored candidate', () => {
    const cheap = recordDrift(emptyDrift(), [{ tier: 'candidate' as const }, {}, {}, {}]);
    const dear = recordDrift(emptyDrift(), [{ tier: 'model' as const }, {}, {}, {}]);
    expect(driftScore(dear)).toBeGreaterThan(driftScore(cheap));
  });

  it('flags a page that leans on healing across several steps', () => {
    const s = recordDrift(emptyDrift(), [{ tier: 'model' as const }, { tier: 'fuzzy' as const }, { tier: 'ranked' as const }]);
    expect(shouldRemap(s)).toBe(true);
  });

  it('one unlucky heal on a short run does not trigger a re-map', () => {
    const s = recordDrift(emptyDrift(), [{ tier: 'model' as const }]);
    expect(shouldRemap(s)).toBe(false); // below minSteps
  });

  it('accumulates across replays', () => {
    let s = emptyDrift();
    s = recordDrift(s, [{ tier: 'candidate' as const }, {}]);
    s = recordDrift(s, [{ tier: 'candidate' as const }, {}]);
    expect(s.steps).toBe(4);
    expect(s.tiers.candidate).toBe(2);
  });
});

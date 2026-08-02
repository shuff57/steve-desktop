import { describe, it, expect } from 'vitest';
import { extractBrowserState } from './page-agent-dom';

/**
 * Fake CDP that records every call, so the test can assert on the order of
 * operations rather than on a real page.
 */
function fakeCdp(nodes: unknown[]) {
  const calls: { method: string; params?: Record<string, unknown> }[] = [];
  const send = async (method: string, params?: Record<string, unknown>) => {
    calls.push({ method, params });
    switch (method) {
      case 'Runtime.evaluate':
        return { result: { value: String(params?.expression).includes('location.href') ? 'http://x/' : [] } };
      case 'Accessibility.getFullAXTree':
        return { nodes };
      case 'DOM.pushNodesByBackendIdsToFrontend':
        return { nodeIds: (params?.backendNodeIds as number[]).map((b) => b * 10) };
      default:
        return {};
    }
  };
  return { send, calls };
}

const axNode = (backendDOMNodeId: number, role: string, name: string) => ({
  ignored: false,
  role: { value: role },
  name: { value: name },
  backendDOMNodeId,
});

describe('extractBrowserState', () => {
  it('clears the previous pass stamps before writing new ones', async () => {
    // Regression: indexes are reassigned from scratch each extraction, so a stamp
    // left on an element from an earlier step makes two elements answer to the same
    // index. The tools resolve with querySelector — first in document order wins —
    // so the agent clicks the wrong control and is told it succeeded.
    const { send, calls } = fakeCdp([axNode(1, 'link', 'Help'), axNode(2, 'checkbox', 'Pick me')]);

    await extractBrowserState(send);

    const clearAt = calls.findIndex(
      (c) => c.method === 'Runtime.evaluate' && String(c.params?.expression).includes('removeAttribute'),
    );
    const stampAt = calls.findIndex((c) => c.method === 'DOM.setAttributeValue');
    expect(clearAt, 'stamps are never cleared').toBeGreaterThan(-1);
    expect(stampAt, 'nothing was stamped').toBeGreaterThan(-1);
    expect(clearAt).toBeLessThan(stampAt);
    expect(String(calls[clearAt].params?.expression)).toContain('[data-pa-index]');
  });

  it('indexes interactive elements in tree order', async () => {
    const { send } = fakeCdp([axNode(1, 'link', 'Help'), axNode(2, 'checkbox', 'Pick me')]);
    const state = await extractBrowserState(send);
    expect(state.content).toContain('[0]<link>Help</link>');
    expect(state.content).toContain('[1]<checkbox>Pick me</checkbox>');
  });
});

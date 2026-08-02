import { describe, expect, test, vi } from 'vitest';
import { attachPageAgentOverlay, describeActivity, overlayUpdateScript } from './page-agent-overlay';
import type { ToolContext } from './page-agent-tools';

function ctxWithStop(stop: boolean): { ctx: ToolContext; scripts: string[] } {
  const scripts: string[] = [];
  const ctx: ToolContext = {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async (_m, p) => {
      scripts.push(String((p as { expression?: string })?.expression ?? ''));
      return { result: { value: stop } };
    }),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
  };
  return { ctx, scripts };
}

describe('page-agent-overlay', () => {
  test('activity lines stay short and name the tool', () => {
    expect(describeActivity({ type: 'thinking' })).toBe('Thinking…');
    expect(describeActivity({ type: 'executing', tool: 'click_element_by_index', input: { index: 4 } }))
      .toBe('Running click_element_by_index {"index":4}');
    const long = describeActivity({ type: 'executed', tool: 'input_text', output: 'x'.repeat(200), duration: 1500 });
    expect(long).toContain('input_text →');
    expect(long).toContain('(1.5s)');
    expect(long.length).toBeLessThan(100);
  });

  test('the update script re-asserts the overlay rather than assuming it survived', () => {
    const s = overlayUpdateScript({ state: 'thinking', text: 'hi', history: [], task: 't' });
    // A navigation wipes the DOM, so every update must be able to rebuild it.
    expect(s).toContain('getElementById("page-agent-overlay")');
    expect(s).toContain('createElement');
    // And it must stay invisible to the agent's own DOM extraction.
    expect(s).toContain('data-page-agent-ignore');
  });

  test('pressing Stop in the page aborts the run', async () => {
    const { ctx } = ctxWithStop(true);
    const controller = new AbortController();
    const o = attachPageAgentOverlay(ctx, controller, 'task');
    o.onActivity!({ type: 'thinking' });
    await vi.waitFor(() => expect(controller.signal.aborted).toBe(true));
  });

  test('no Stop press leaves the run alone', async () => {
    const { ctx, scripts } = ctxWithStop(false);
    const controller = new AbortController();
    const o = attachPageAgentOverlay(ctx, controller, 'task');
    o.onActivity!({ type: 'executed', tool: 'navigate', output: 'ok', duration: 100 });
    await vi.waitFor(() => expect(scripts.length).toBe(1));
    expect(controller.signal.aborted).toBe(false);
  });
});

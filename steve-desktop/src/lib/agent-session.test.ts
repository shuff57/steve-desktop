import { describe, it, expect, vi } from 'vitest';
import { beginAgentSession } from './agent-session';
import type { ToolContext } from './page-agent-tools';

/** Capture every overlay script pushed at the page. */
function fakeCtx() {
  const pushes: string[] = [];
  const ctx = {
    cdpSend: vi.fn(async (_m: string, p?: Record<string, unknown>) => {
      pushes.push(String(p?.expression ?? ''));
      return { result: { value: false } };
    }),
  } as unknown as ToolContext;
  return { ctx, pushes };
}

const flush = () => new Promise((r) => setTimeout(r, 0));

describe('beginAgentSession', () => {
  it('paints immediately, before any run has produced activity', async () => {
    // The conductor phase used to draw nothing at all: the tab navigated and
    // changed with no agent visible anywhere, which reads as "it never connected".
    const { ctx, pushes } = fakeCtx();
    beginAgentSession(ctx, new AbortController(), { task: 'demo', heartbeatMs: 0 });
    await flush();
    expect(pushes.length).toBeGreaterThan(0);
    expect(pushes[0]).toContain('page-agent-overlay');
  });

  it('shows who has the wheel and how far along the job is', async () => {
    const { ctx, pushes } = fakeCtx();
    const s = beginAgentSession(ctx, new AbortController(), {
      task: 'demo',
      totalSteps: 15,
      heartbeatMs: 0,
    });
    s.setProgress(6);
    await flush();
    const last = pushes[pushes.length - 1];
    expect(last).toContain('"progress":"6/15"');
    expect(last).toContain('"role":"Claude"');

    s.setRole('driver', 'clicking');
    await flush();
    expect(pushes[pushes.length - 1]).toContain('"role":"Page agent"');
  });

  it('hands the wheel back when a RUN completes instead of ending the session', async () => {
    // Regression: a finished run used to dispose the overlay, so a task list tore
    // its own UI down and rebuilt it between every step — "it runs a bit, then
    // turns off". A completed run is a handover, not a teardown.
    const { ctx, pushes } = fakeCtx();
    const s = beginAgentSession(ctx, new AbortController(), { task: 'demo', heartbeatMs: 0 });
    const joined = s.join();

    joined.onActivity?.({ type: 'executing', tool: 'click_element_by_index', input: {} } as never);
    await flush();
    expect(pushes[pushes.length - 1]).toContain('"role":"Page agent"');

    joined.onStatusChange?.('completed');
    await flush();
    const after = pushes[pushes.length - 1];
    expect(after, 'the overlay was removed on run completion').not.toContain('.remove()');
    expect(after).toContain('"role":"Claude"');
  });

  it('aborts the controller when the page reports Stop pressed', async () => {
    const pushes: string[] = [];
    const ctx = {
      cdpSend: vi.fn(async (_m: string, p?: Record<string, unknown>) => {
        pushes.push(String(p?.expression ?? ''));
        return { result: { value: true } }; // Stop flag set in the page
      }),
    } as unknown as ToolContext;
    const ac = new AbortController();
    beginAgentSession(ctx, ac, { task: 'demo', heartbeatMs: 0 });
    await flush();
    expect(ac.signal.aborted).toBe(true);
  });

  it('removes the overlay only when the session itself ends', async () => {
    const { ctx, pushes } = fakeCtx();
    const s = beginAgentSession(ctx, new AbortController(), { task: 'demo', heartbeatMs: 0 });
    await s.end('done');
    expect(pushes[pushes.length - 1]).toContain('.remove()');
  });
});

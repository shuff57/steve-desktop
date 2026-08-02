import { describe, expect, test, vi, beforeEach } from 'vitest';
import { runTaskList, summariseReport } from './page-agent-tasks';
import type { ToolContext } from './page-agent-tools';

// The loop is stubbed: what is under test here is the conductor contract —
// ordering, who decides pass/fail, and what the report says — not the Re-Act loop.
const loop = vi.hoisted(() => ({ run: vi.fn() }));
vi.mock('./page-agent-loop', () => ({
  runAgentLoop: (config: { task: string }) => loop.run(config),
}));

function ctxWithChecks(checks: Record<string, unknown>): ToolContext {
  return {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async (_m, p) => {
      const expr = String((p as { expression?: string })?.expression ?? '');
      if (expr in checks) {
        const v = checks[expr];
        if (v === 'THROW') return { exceptionDetails: { text: 'boom' }, result: {} };
        return { result: { value: v } };
      }
      return { result: { value: undefined } };
    }),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
  };
}

beforeEach(() => {
  loop.run.mockReset();
  loop.run.mockResolvedValue({ success: true, data: 'did it', history: [] });
});

describe('runTaskList — the conductor contract', () => {
  test('the page decides pass/fail, not the model', async () => {
    // The exact failure this exists to catch: the agent says it worked and the
    // page says otherwise. The page wins.
    loop.run.mockResolvedValue({ success: true, data: 'I filled the form', history: [] });
    const ctx = ctxWithChecks({ 'CHECK_A': JSON.stringify({ ok: false, detail: 'field still empty' }) });
    const report = await runTaskList([{ task: 'fill it', check: 'CHECK_A' }], {} as never, ctx);

    expect(report.ok).toBe(false);
    expect(report.steps[0].agentSuccess).toBe(true);
    expect(report.steps[0].ok).toBe(false);
    expect(report.steps[0].check?.detail).toBe('field still empty');
  });

  test('stops at the first failure so later steps do not run on a broken page', async () => {
    const ctx = ctxWithChecks({
      A: JSON.stringify({ ok: true }),
      B: JSON.stringify({ ok: false, detail: 'nope' }),
      C: JSON.stringify({ ok: true }),
    });
    const report = await runTaskList(
      [{ task: 'one', check: 'A' }, { task: 'two', check: 'B' }, { task: 'three', check: 'C' }],
      {} as never,
      ctx,
    );
    expect(report.failedAt).toBe(1);
    expect(report.steps).toHaveLength(2);
    expect(loop.run).toHaveBeenCalledTimes(2);
  });

  test('continueOnFailure runs the rest and still reports the first failure', async () => {
    const ctx = ctxWithChecks({
      A: JSON.stringify({ ok: false, detail: 'x' }),
      B: JSON.stringify({ ok: true }),
    });
    const report = await runTaskList(
      [{ task: 'one', check: 'A' }, { task: 'two', check: 'B' }],
      { continueOnFailure: true } as never,
      ctx,
    );
    expect(report.steps).toHaveLength(2);
    expect(report.failedAt).toBe(0);
    expect(report.completed).toBe(1);
    expect(report.ok).toBe(false);
  });

  test('a check that throws is a failure, never a pass', async () => {
    const ctx = ctxWithChecks({ A: 'THROW' });
    const report = await runTaskList([{ task: 'one', check: 'A' }], {} as never, ctx);
    expect(report.steps[0].ok).toBe(false);
    expect(report.steps[0].check?.detail).toMatch(/threw/);
  });

  test('an unchecked step is reported as unchecked, not as verified', async () => {
    const report = await runTaskList([{ task: 'one' }], {} as never, ctxWithChecks({}));
    expect(report.steps[0].checked).toBe(false);
    expect(report.steps[0].ok).toBe(true); // nothing else to go on
    expect(summariseReport(report)).toContain('UNCHECKED');
  });

  test('steps run in order and navigate first when given a url', async () => {
    const ctx = ctxWithChecks({ A: true, B: true });
    await runTaskList(
      [{ task: 'one', url: 'https://example.com/1', check: 'A' }, { task: 'two', check: 'B' }],
      {} as never,
      ctx,
    );
    expect(ctx.navigate).toHaveBeenCalledWith('https://example.com/1');
    expect(loop.run.mock.calls.map((c) => c[0].task)).toEqual(['one', 'two']);
  });

  test('the summary names the failing step for the orchestrator', async () => {
    const ctx = ctxWithChecks({ A: JSON.stringify({ ok: false, detail: 'button not found' }) });
    const report = await runTaskList([{ id: 'click-save', task: 'click save', check: 'A' }], {} as never, ctx);
    const text = summariseReport(report);
    expect(text).toContain('Stopped at step 1 of 1');
    expect(text).toContain('FAIL [click-save]');
    expect(text).toContain('button not found');
  });
});

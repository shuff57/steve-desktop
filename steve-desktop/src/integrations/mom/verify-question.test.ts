import { describe, expect, test, vi } from 'vitest';
import { verifyQuestion } from './transfer-via-agent';
import type { ToolContext } from '../../lib/page-agent-tools';

/**
 * Stand in for the page: each entry is one render's probe output, handed back in
 * order. The reseed click always reports success so the loop advances.
 */
function ctxOver(renders: object[]): { ctx: ToolContext; urls: string[] } {
  const urls: string[] = [];
  let i = 0;
  const ctx: ToolContext = {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async (_m, p) => {
      const expr = String((p as { expression?: string })?.expression ?? '');
      if (/New Version/.test(expr)) return { result: { value: true } };
      return { result: { value: JSON.stringify(renders[Math.min(i++, renders.length - 1)]) } };
    }),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async (u: string) => { urls.push(u); }),
    waitForLoad: vi.fn(async () => undefined),
  };
  return { ctx, urls };
}

const ok = (seed: string) => ({ seed, issues: [], widgets: 3, text: 'a question' });

describe('verifyQuestion', () => {
  test('renders on the plain testquestion2 URL, no popup and no assessment', async () => {
    const { ctx, urls } = ctxOver([ok('1'), ok('2'), ok('3')]);
    const v = await verifyQuestion(ctx, { cid: 334243, qsetid: 1868230 });
    expect(urls[0]).toBe('https://www.myopenmath.com/course/testquestion2.php?cid=334243&qsetid=1868230');
    expect(v.clean).toBe(true);
    expect(v.renders).toHaveLength(3);
  });

  test('a break on only ONE seed still fails the question', async () => {
    // The whole reason for re-rolling: a branch that breaks on some values
    // renders perfectly on others, so a single clean render proves nothing.
    const { ctx } = ctxOver([
      ok('1'),
      { seed: '2', issues: ['Eeek! — control block is empty or failed'], widgets: 0, text: 'Eeek!' },
      ok('3'),
    ]);
    const v = await verifyQuestion(ctx, { cid: 334243, qsetid: 1868230, seeds: 3 });
    expect(v.clean).toBe(false);
    expect(v.issues).toContain('Eeek! — control block is empty or failed');
  });

  test('issues are deduplicated across seeds', async () => {
    const bad = { seed: null, issues: ['no answer widget rendered'], widgets: 0, text: '' };
    const { ctx } = ctxOver([bad, bad, bad]);
    const v = await verifyQuestion(ctx, { cid: 1, qsetid: 2 });
    expect(v.issues).toEqual(['no answer widget rendered']);
  });

  test('seeds is clamped to at least one render', async () => {
    const { ctx } = ctxOver([ok('1')]);
    const v = await verifyQuestion(ctx, { cid: 1, qsetid: 2, seeds: 0 });
    expect(v.renders).toHaveLength(1);
    expect(v.clean).toBe(true);
  });
});

import { describe, expect, test, vi } from 'vitest';
import {
  DEFAULT_TOOLS,
  describeTools,
  executeTool,
  doneTool,
  makeAskUserTool,
  waitForConditionTool,
  type ToolContext,
} from './page-agent-tools';

function makeMockCtx(overrides: Partial<ToolContext> = {}): ToolContext {
  return {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async () => ({ result: { value: 'ok' } })),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
    ...overrides,
  };
}

describe('page-agent-tools', () => {
  test('DEFAULT_TOOLS includes the expected set', () => {
    const names = DEFAULT_TOOLS.map((t) => t.name);
    expect(names).toContain('done');
    expect(names).toContain('wait');
    expect(names).toContain('click_element_by_index');
    expect(names).toContain('input_text');
    expect(names).toContain('scroll');
    expect(names).toContain('navigate');
    // execute_javascript must NOT be in defaults
    expect(names).not.toContain('execute_javascript');
  });

  test('unknown tool names list the legal tools', async () => {
    const { output } = await executeTool(DEFAULT_TOOLS, makeMockCtx(), { '0': '{' } as never);
    expect(output).toContain('Unknown tool: 0');
    expect(output).toContain('click_element_by_index');
  });

  test('describeTools produces a readable list', () => {
    const desc = describeTools(DEFAULT_TOOLS);
    expect(desc).toContain('done:');
    expect(desc).toContain('click_element_by_index:');
  });

  test('doneTool returns task completed', async () => {
    const ctx = makeMockCtx();
    const { name, output } = await executeTool(DEFAULT_TOOLS, ctx, {
      done: { text: 'Finished', success: true },
    });
    expect(name).toBe('done');
    expect(output).toBe('Task completed');
  });

  test('waitTool waits the specified time', async () => {
    const ctx = makeMockCtx();
    const { name, output } = await executeTool(DEFAULT_TOOLS, ctx, {
      wait: { seconds: 1 },
    });
    expect(name).toBe('wait');
    expect(output).toContain('Waited for 1 seconds');
  });

  test('DEFAULT_TOOLS includes wait_for_condition', () => {
    expect(DEFAULT_TOOLS.map((t) => t.name)).toContain('wait_for_condition');
  });

  test('executeTool with unknown tool returns error', async () => {
    const ctx = makeMockCtx();
    const { name, output } = await executeTool(DEFAULT_TOOLS, ctx, {
      nonexistent_tool: {},
    });
    expect(name).toBe('nonexistent_tool');
    expect(output).toContain('Unknown tool');
  });

  test('executeTool catches tool errors', async () => {
    const ctx: ToolContext = {
      ...makeMockCtx(),
      cdpSend: vi.fn(async () => {
        throw new Error('CDP disconnected');
      }),
    };
    const { name, output } = await executeTool(DEFAULT_TOOLS, ctx, {
      click_element_by_index: { index: 0 },
    });
    expect(name).toBe('click_element_by_index');
    expect(output).toContain('Tool error');
    expect(output).toContain('CDP disconnected');
  });

  test('click_element_by_index calls CDP Runtime.evaluate', async () => {
    const cdpSend = vi.fn(async () => ({
      result: { value: '✅ Clicked element [0]' },
    }));
    const ctx = makeMockCtx({ cdpSend });
    const { name, output } = await executeTool(DEFAULT_TOOLS, ctx, {
      click_element_by_index: { index: 0 },
    });
    expect(name).toBe('click_element_by_index');
    expect(output).toContain('Clicked');
    expect(cdpSend).toHaveBeenCalledWith('Runtime.evaluate', expect.objectContaining({
      expression: expect.stringContaining("data-pa-index"),
    }));
  });

  test('navigateTool calls ctx.navigate', async () => {
    const navigate = vi.fn(async () => undefined);
    const ctx = makeMockCtx({ navigate });
    const { name, output } = await executeTool(DEFAULT_TOOLS, ctx, {
      navigate: { url: 'https://example.com' },
    });
    expect(name).toBe('navigate');
    expect(output).toContain('Navigated to https://example.com');
    expect(navigate).toHaveBeenCalledWith('https://example.com');
  });
});
describe('ask_user — handing a question back instead of guessing', () => {
  test('the answer comes back as the action result, so the run continues', async () => {
    const asked: string[] = [];
    const tool = makeAskUserTool(async (q) => { asked.push(q); return 'use the second one'; });
    const out = await tool.execute(makeMockCtx(), { question: 'which login?' });
    expect(asked).toEqual(['which login?']);
    expect(out).toBe('Answer: use the second one');
  });

  test('a refused answer never reads as an answer', async () => {
    const tool = makeAskUserTool(async () => { throw new Error('nobody listening'); });
    const out = await tool.execute(makeMockCtx(), { question: 'anything?' });
    expect(out).toContain('No answer available');
    expect(out).not.toContain('Answer:');
  });

  test('an empty question is refused with the shape it wanted', async () => {
    const tool = makeAskUserTool(async () => 'x');
    expect(await tool.execute(makeMockCtx(), { question: '  ' })).toContain('{ question: string }');
  });
});

describe('wait_for_condition — open-ended waits that do not fit in `wait`', () => {
  test('an empty condition is refused with the shape it wanted', async () => {
    const out = await waitForConditionTool.execute(makeMockCtx(), { condition: '  ' });
    expect(out).toContain('{ condition: string }');
  });

  test('returns success as soon as the condition reads true, without waiting out the timeout', async () => {
    const cdpSend = vi.fn(async () => ({ result: { value: true } }));
    const ctx = makeMockCtx({ cdpSend });
    const out = await waitForConditionTool.execute(ctx, {
      condition: "document.querySelector('video')?.ended === true",
      timeoutSeconds: 120,
    });
    expect(out).toContain('✅ Condition became true');
    // Only one poll needed — true on the first check.
    expect(cdpSend).toHaveBeenCalledTimes(1);
    expect(cdpSend).toHaveBeenCalledWith(
      'Runtime.evaluate',
      expect.objectContaining({
        expression: expect.stringContaining("document.querySelector('video')?.ended === true"),
      }),
    );
  });

  test('polls again after a false read, then succeeds once true', async () => {
    vi.useFakeTimers();
    try {
      let calls = 0;
      const cdpSend = vi.fn(async () => ({ result: { value: (++calls) >= 3 } }));
      const ctx = makeMockCtx({ cdpSend });
      const p = waitForConditionTool.execute(ctx, {
        condition: 'window.done',
        timeoutSeconds: 60,
        pollSeconds: 5,
      });
      // Let the poll loop run to completion without real delay.
      await vi.runAllTimersAsync();
      const out = await p;
      expect(out).toContain('✅ Condition became true');
      expect(calls).toBe(3);
    } finally {
      vi.useRealTimers();
    }
  });

  test('times out and says so, rather than reading as a hard failure', async () => {
    vi.useFakeTimers();
    try {
      const cdpSend = vi.fn(async () => ({ result: { value: false } }));
      const ctx = makeMockCtx({ cdpSend });
      const p = waitForConditionTool.execute(ctx, {
        condition: 'window.neverHappens',
        timeoutSeconds: 5,
        pollSeconds: 1,
      });
      await vi.runAllTimersAsync();
      const out = await p;
      expect(out).toContain('⏱️ Timed out after 5s');
      // The stall detector reads a leading ❌/"failed"/"not found" as no-progress —
      // a timeout must not accidentally look like one of those.
      expect(out).not.toMatch(/^❌/);
    } finally {
      vi.useRealTimers();
    }
  });

  test('a condition that throws is reported, not silently swallowed', async () => {
    vi.useFakeTimers();
    try {
      const cdpSend = vi.fn(async () => ({ exceptionDetails: { text: 'ReferenceError: x is not defined' } }));
      const ctx = makeMockCtx({ cdpSend });
      const p = waitForConditionTool.execute(ctx, {
        condition: 'x.y.z',
        timeoutSeconds: 5,
        pollSeconds: 1,
      });
      await vi.runAllTimersAsync();
      const out = await p;
      expect(out).toContain('Timed out');
      expect(out).toContain('condition threw');
    } finally {
      vi.useRealTimers();
    }
  });

  test('timeoutSeconds and pollSeconds are clamped to sane bounds', async () => {
    const cdpSend = vi.fn(async () => ({ result: { value: true } }));
    const ctx = makeMockCtx({ cdpSend });
    // Absurdly large/small requests should not hang the test or throw.
    const out = await waitForConditionTool.execute(ctx, {
      condition: 'true',
      timeoutSeconds: 999999,
      pollSeconds: 0,
    });
    expect(out).toContain('✅ Condition became true');
  });
});

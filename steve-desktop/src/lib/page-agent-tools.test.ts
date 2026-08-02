import { describe, expect, test, vi } from 'vitest';
import {
  DEFAULT_TOOLS,
  describeTools,
  executeTool,
  doneTool,
  makeAskUserTool,
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

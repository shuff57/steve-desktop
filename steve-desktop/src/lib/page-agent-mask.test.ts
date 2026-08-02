import { describe, expect, test, vi, beforeEach, afterEach } from 'vitest';
import { runAgentLoop } from './page-agent-loop';
import type { ToolContext } from './page-agent-tools';

// Real loop, stubbed page + endpoint: the point is what leaves the machine.
vi.mock('./page-agent-dom', () => ({
  extractBrowserState: async () => ({
    header: 'Current URL: https://lms.example/gradebook',
    content: '[0]<link>Jordan Alvarez</link>',
    footer: '\n<page_text>\nJordan Alvarez  ID 90210  score 88\n</page_text>',
  }),
}));

const sent: string[] = [];

beforeEach(() => {
  sent.length = 0;
  vi.stubGlobal('fetch', async (_url: string, init: { body: string }) => {
    sent.push(init.body);
    return {
      ok: true,
      json: async () => ({
        choices: [{ message: { tool_calls: [{ function: { name: 'AgentOutput', arguments: JSON.stringify({ action: { done: { text: 'ok', success: true } } }) } }] } }],
      }),
    };
  });
});

afterEach(() => vi.unstubAllGlobals());

function ctx(): ToolContext {
  return {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async () => ({ result: { value: '' } })),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
  };
}

const base = { baseURL: 'http://x/v1', model: 'm', task: 'read the page', maxSteps: 1 };

describe('transformPageContent keeps page data off the wire', () => {
  test('without the hook, page text reaches the model verbatim', async () => {
    await runAgentLoop({ ...base }, ctx());
    // Establishes the risk the hook exists to remove: this is a real name and id.
    expect(sent.join()).toContain('Jordan Alvarez');
    expect(sent.join()).toContain('90210');
  });

  test('with the hook, only the masked text is sent', async () => {
    await runAgentLoop(
      {
        ...base,
        transformPageContent: (state) => ({
          ...state,
          content: state.content.replace(/Jordan Alvarez/g, '⟦STUDENT_1⟧'),
          footer: '\n<page_text>\n⟦STUDENT_1⟧ ID ⟦ID_1⟧ score 88\n</page_text>',
        }),
      },
      ctx(),
    );
    const body = sent.join();
    expect(body).not.toContain('Jordan Alvarez');
    expect(body).not.toContain('90210');
    expect(body).toContain('⟦STUDENT_1⟧');
    // The non-identifying part of the page must survive, or the agent is blind.
    expect(body).toContain('score 88');
  });
});

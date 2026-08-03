import { describe, expect, test, vi, beforeEach, afterEach } from 'vitest';
import { handlePageAction, handlePageTask, readPage } from './page-tool';
import { endRunMask, maskForRun } from './page-agent-mask';
import type { ToolContext } from './page-agent-tools';

const page = {
  header: 'Current URL: https://lms.example/courses/1/gradebook',
  content: '[0]<combobox>Jump to student</combobox>\n[1]<link>Alvarez, Jordan</link>',
  footer: '\n<page_text>\nAlvarez, Jordan  ID 7158619  score 88\n</page_text>',
};
vi.mock('./page-agent-dom', () => ({ extractBrowserState: async () => ({ ...page }) }));

const sent: string[] = [];
/** What the sub-task model answers, or a transport failure to simulate a dead endpoint. */
let reply: { action?: Record<string, unknown>; httpError?: number } = {};

beforeEach(() => {
  sent.length = 0;
  reply = { action: { done: { text: 'selected the student', success: true } } };
  vi.stubGlobal('fetch', async (_u: string, init: { body: string }) => {
    sent.push(init.body);
    if (reply.httpError) {
      return { ok: false, status: reply.httpError, text: async () => 'rate limit exceeded' };
    }
    return {
      ok: true,
      json: async () => ({
        choices: [{ message: { tool_calls: [{ function: { name: 'AgentOutput', arguments: JSON.stringify({ action: reply.action }) } }] } }],
      }),
    };
  });
});
afterEach(() => {
  vi.unstubAllGlobals();
  endRunMask('run-1');
});

function ctx(): ToolContext {
  return {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async () => ({ result: { value: '✅ Clicked element [1]' } })),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
  };
}

const opts = { baseURL: 'http://x/v1', model: 'm', maxSteps: 1 };

describe('what the orchestrator is allowed to see', () => {
  test('the page comes back masked', async () => {
    const out = await readPage('run-1', ctx());
    expect(out).not.toContain('Alvarez, Jordan');
    expect(out).not.toContain('7158619');
    expect(out).toMatch(/⟦STU\d+⟧/);
    // Structure it needs to act on survives.
    expect(out).toContain('[0]<combobox>');
    expect(out).toContain('score 88');
  });

  test('a sub-task reports back masked, with the page to check it against', async () => {
    const res = await handlePageTask('run-1', 'select the student', ctx(), opts);
    expect(res.ok).toBe(true);
    expect(res.report).toContain('selected the student');
    // The claim is never the only evidence — the state comes with it.
    expect(res.page).toMatch(/⟦STU\d+⟧/);
    expect(res.page).not.toContain('Alvarez, Jordan');
  });

  test('the sub-task model never sees the real page either', async () => {
    await handlePageTask('run-1', 'select the student', ctx(), opts);
    expect(sent.join()).not.toContain('Alvarez, Jordan');
    expect(sent.join()).not.toContain('7158619');
  });
});

describe('when the sub-task model is unavailable', () => {
  test('the task degrades instead of failing', async () => {
    reply = { httpError: 429 };
    const res = await handlePageTask('run-1', 'select the student', ctx(), opts);
    expect(res.degraded).toBeTruthy();
    expect(res.degraded).toContain('page_click');
    // Nothing was attempted, and the orchestrator gets the page to drive from.
    expect(res.degraded).toContain('Nothing was attempted');
    expect(res.page).toMatch(/⟦STU\d+⟧/);
  });

  test('a genuine task failure is NOT treated as a dead endpoint', async () => {
    reply = { action: { done: { text: 'could not find the control', success: false } } };
    const res = await handlePageTask('run-1', 'select the student', ctx(), opts);
    expect(res.degraded).toBeUndefined();
    expect(res.ok).toBe(false);
    expect(res.report).toContain('could not find the control');
  });
});

describe('primitives carry the same boundary', () => {
  test('a token in an action is rehydrated before it touches the page', async () => {
    const mask = maskForRun('run-1');
    const seen = mask.text('Alvarez, Jordan', 'https://lms.example/courses/1/gradebook');
    const token = seen.match(/⟦STU\d+⟧/)![0];

    const c = ctx();
    await handlePageAction('run-1', 'select_dropdown_option', { index: 0, text: token }, c);

    // The real name reached the page; the orchestrator only ever held the token.
    const expr = (c.cdpSend as ReturnType<typeof vi.fn>).mock.calls
      .map((call) => JSON.stringify(call[1]))
      .join();
    expect(expr).toContain('Alvarez, Jordan');
  });

  test("a tool's own output is masked — an option list is a roster", async () => {
    const c = ctx();
    c.cdpSend = vi.fn(async () => ({
      result: { value: 'No option matching "x". Options: Alvarez, Jordan | Nakamura, Yuki' },
    }));
    const out = await handlePageAction('run-1', 'select_dropdown_option', { index: 0, text: 'x' }, c);
    expect(out).not.toContain('Alvarez, Jordan');
    expect(out).not.toContain('Nakamura, Yuki');
  });

  test('the run mask is shared, so a token means one person across calls', async () => {
    const first = await readPage('run-1', ctx());
    const token = first.match(/⟦STU\d+⟧/)![0];
    const second = await readPage('run-1', ctx());
    expect(second).toContain(token);
  });
});

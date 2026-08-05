import { describe, expect, test, vi, beforeEach, afterEach } from 'vitest';
import { handlePageAction, handlePageMap, handlePageTask, mapSliceForQuery, readPage, splitMapSections } from './page-tool';
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

describe('the site map slice', () => {
  const MAP = `# Site map: MyOpenMath
This site hosts a course with a gradebook and forums.

## Gradebook
- gradebook.php?cid=N — list scores; click a name to open the roster row.

## Forums
- msglist.php?cid=N — the inbox.
`;

  test('sections split by heading, keeping prose with its section', () => {
    const s = splitMapSections(MAP);
    expect(s.map((x) => x.heading)).toEqual(['Site map: MyOpenMath', 'Gradebook', 'Forums']);
    expect(s[1].body).toContain('gradebook.php?cid=N');
    expect(s[1].body).not.toContain('msglist.php?cid=N');
  });

  test('a heading match wins over a prose match and carries context around it', () => {
    const { section, text } = mapSliceForQuery(MAP, 'Gradebook')!;
    expect(section).toBe('Gradebook');
    expect(text).toContain('gradebook.php?cid=N');
    expect(text).toContain('roster row');
  });

  test('a prose-only match still lands in the right section', () => {
    const { section, text } = mapSliceForQuery(MAP, 'inbox')!;
    expect(section).toBe('Forums');
    expect(text).toContain('msglist.php?cid=N');
  });

  test('no match returns null; an empty doc returns null', () => {
    expect(mapSliceForQuery(MAP, 'zebra')).toBeNull();
    expect(mapSliceForQuery('   ', 'anything')).toBeNull();
  });

  test('no query returns the whole doc', () => {
    const { section, text } = mapSliceForQuery(MAP, '')!;
    expect(section).toBe('');
    expect(text).toContain('Gradebook');
    expect(text).toContain('Forums');
  });

  test('handlePageMap masks the map in the run mask and never needs a page', async () => {
    const doc = MAP + '\n## Roster\n- view.php?stu=7158619 — the class roster.\n';
    const out = await handlePageMap('run-1', doc, 'roster');
    expect(out).toContain('view.php?stu=');
    expect(out).not.toContain('7158619');
    expect(out).toMatch(/⟦PID\d+⟧/);
  });

  test('handlePageMap with no map says so plainly', async () => {
    const out = await handlePageMap('run-1', '', 'grades');
    expect(out).toContain('No site map is available');
  });
});

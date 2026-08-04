import { describe, expect, test, vi, beforeEach, afterEach } from 'vitest';
import { describePageTool, dispatchPageTool, mcpConfigFor, PAGE_TOOL_NAMES } from './page-tools-bridge';
import { endRunMask, maskForRun } from './page-agent-mask';
import type { ToolContext } from './page-agent-tools';

const page = {
  header: 'Current URL: https://www.myopenmath.com/course/gradebook.php?cid=316341',
  content: '[0]<combobox>Jump to student</combobox>\n[1]<link>Alvarez, Jordan</link>',
  footer: '\n<page_text>\nAlvarez, Jordan  ID 7158619  score 88\n</page_text>',
};
vi.mock('./page-agent-dom', () => ({ extractBrowserState: async () => ({ ...page }) }));

function ctx(): ToolContext {
  return {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async () => ({ result: { value: '✅ Clicked element [1]' } })),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
  };
}

const COURSE = 'https://www.myopenmath.com/course/gradebook.php?cid=316341';

function opts(confine?: { startUrl: string; sameDomainOnly?: boolean }) {
  return {
    runId: 'run-1',
    ctx: ctx(),
    subTask: { baseURL: 'http://x/v1', model: 'm', maxSteps: 1, confine },
  };
}

afterEach(() => endRunMask('run-1'));

describe('what the CLI is handed', () => {
  test('the mcp config is loopback and carries the run token', () => {
    const cfg = JSON.parse(mcpConfigFor({ port: 51234, token: 'tok-abc' }));
    expect(cfg.mcpServers.page.type).toBe('http');
    expect(cfg.mcpServers.page.url).toBe('http://127.0.0.1:51234/mcp');
    expect(cfg.mcpServers.page.headers.Authorization).toBe('Bearer tok-abc');
  });

  test('the tool names match what Claude Code namespaces them as', () => {
    expect(PAGE_TOOL_NAMES.read).toBe('mcp__page__page_read');
    expect(PAGE_TOOL_NAMES.task).toBe('mcp__page__page_task');
  });
});

describe('every reply is masked and carries the page', () => {
  test('page_read hands back the masked page', async () => {
    const out = await dispatchPageTool('page_read', {}, opts());
    expect(out).not.toContain('Alvarez, Jordan');
    expect(out).not.toContain('7158619');
    expect(out).toMatch(/⟦STU\d+⟧/);
    expect(out).toContain('[0]<combobox>');
  });

  test('an action reports what it did AND the page afterwards', async () => {
    // The claim alone is the weakest evidence there is: a run once reported a student selected
    // while the control had not moved. The state comes with it so the orchestrator can check.
    const out = await dispatchPageTool('page_click', { index: 1 }, opts());
    expect(out).toContain('Clicked element [1]');
    expect(out).toContain('the page now');
    expect(out).toMatch(/⟦STU\d+⟧/);
    expect(out).not.toContain('Alvarez, Jordan');
  });

  test('a bad index is refused before it reaches the page', async () => {
    await expect(dispatchPageTool('page_click', {}, opts())).rejects.toThrow(/index/);
  });

  test('an unknown tool name is an error, not a silent no-op', async () => {
    await expect(dispatchPageTool('page_delete_everything', {}, opts())).rejects.toThrow(/Unknown page tool/);
  });
});

describe('confinement on the primitive path', () => {
  test('another site is refused', async () => {
    const out = await dispatchPageTool(
      'page_navigate',
      { url: 'https://evil.example/steal' },
      opts({ startUrl: COURSE, sameDomainOnly: true }),
    );
    expect(out).toContain('Refused');
  });

  test('another course on the same site is refused', async () => {
    const out = await dispatchPageTool(
      'page_navigate',
      { url: 'https://www.myopenmath.com/course/gradebook.php?cid=999999' },
      opts({ startUrl: COURSE }),
    );
    expect(out).toContain('Refused');
  });

  test('a URL the orchestrator only ever saw masked still reaches the real page', async () => {
    // A student id in the path is masked, so the CLI holds /users/⟦PID1⟧ and can only follow that
    // link by handing the token back. The confinement check therefore has to run on the REAL url —
    // testing the masked form would be checking a string that is never navigated to.
    const canvas = 'https://canvas.example/courses/31407/users/127333';
    const mask = maskForRun('run-1');
    const token = mask.text(canvas, canvas).match(/⟦PID\d+⟧/)?.[0];
    expect(token).toBeTruthy();

    const o = opts({ startUrl: 'https://canvas.example/courses/31407/', sameDomainOnly: true });
    const out = await dispatchPageTool(
      'page_navigate',
      { url: `https://canvas.example/courses/31407/users/${token}` },
      o,
    );
    expect(out).not.toContain('Refused');
    expect((o.ctx.navigate as ReturnType<typeof vi.fn>).mock.calls[0][0]).toContain('/users/127333');
  });
});

describe('the activity feed', () => {
  test('names the sub-task rather than saying "using a tool"', () => {
    expect(describePageTool('page_task', { task: 'open the grades tab' })).toBe(
      'page agent: open the grades tab',
    );
    expect(describePageTool('page_read')).toBe('reading the page');
    expect(describePageTool('something_else')).toBeNull();
  });
});

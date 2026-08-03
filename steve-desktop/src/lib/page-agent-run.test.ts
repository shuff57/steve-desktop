import { describe, expect, test, vi, beforeEach, afterEach } from 'vitest';
import {
  assertDrivingTab,
  confinementRefusal,
  confineNavigation,
  planningTools,
  runPageAgentTask,
  withMap,
  MUTATING_TOOLS,
} from './page-agent-run';
import { DEFAULT_TOOLS, type ToolContext } from './page-agent-tools';

vi.mock('./page-agent-dom', () => ({
  extractBrowserState: async () => ({
    header: 'Current URL: https://lms.example/course/course.php?cid=316341',
    content: '[0]<button>Save</button>',
    footer: '',
  }),
}));

function ctx(): ToolContext {
  return {
    signal: new AbortController().signal,
    cdpSend: vi.fn(async () => ({ result: { value: 'ok' } })),
    evalInPage: vi.fn(async () => undefined),
    navigate: vi.fn(async () => undefined),
    waitForLoad: vi.fn(async () => undefined),
  };
}

describe('3 — scope and domain confinement', () => {
  const start = 'https://www.myopenmath.com/course/course.php?cid=316341';

  test('a sibling page in the same course is allowed', () => {
    expect(confinementRefusal('https://www.myopenmath.com/course/gradebook.php?cid=316341', { startUrl: start })).toBeNull();
  });

  test('another course is refused, and the refusal says why', () => {
    const r = confinementRefusal('https://www.myopenmath.com/course/course.php?cid=263001', { startUrl: start });
    expect(r).toContain('outside the course');
    // The model is the only reader — it must be told what to do instead.
    expect(r).toContain('done with success=false');
  });

  test('shared navigation with no course parameter stays allowed', () => {
    expect(confinementRefusal('https://www.myopenmath.com/help.php', { startUrl: start })).toBeNull();
  });

  test('another host is refused only when the run is confined to one', () => {
    const other = 'https://canvas.example.edu/courses/1';
    expect(confinementRefusal(other, { startUrl: start })).toBeNull();
    expect(confinementRefusal(other, { startUrl: start, sameDomainOnly: true })).toContain('different site');
  });

  test('the guard is on the tool, not just the helper', async () => {
    const tools = confineNavigation([...DEFAULT_TOOLS], { startUrl: start });
    const nav = tools.find((t) => t.name === 'navigate')!;
    const c = ctx();
    const out = await nav.execute(c, { url: 'https://www.myopenmath.com/course/course.php?cid=263001' });
    expect(out).toContain('Refused');
    expect(c.navigate).not.toHaveBeenCalled(); // the page never moved
  });

  test('an allowed URL still navigates', async () => {
    const tools = confineNavigation([...DEFAULT_TOOLS], { startUrl: start });
    const nav = tools.find((t) => t.name === 'navigate')!;
    const c = ctx();
    await nav.execute(c, { url: 'https://www.myopenmath.com/course/gradebook.php?cid=316341' });
    expect(c.navigate).toHaveBeenCalled();
  });
});

describe('4 — tab ownership', () => {
  const marker = 'steve-tab-abc';

  test('the right tab passes', async () => {
    const send = vi.fn(async () => ({ result: { value: marker } }));
    await expect(assertDrivingTab(send, marker)).resolves.toBeUndefined();
  });

  test('a stale orphaned tab is refused by name', async () => {
    // The real case: an abandoned webview answered instead of the intended tab, and the run
    // looked successful while reading a completely different site.
    const send = vi.fn(async () => ({ result: { value: 'steve-tab-someone-else' } }));
    await expect(assertDrivingTab(send, marker)).rejects.toThrow(/wrong page/);
  });

  test('an unmarked tab is refused too, and says so', async () => {
    const send = vi.fn(async () => ({ result: { value: '' } }));
    await expect(assertDrivingTab(send, marker)).rejects.toThrow(/unmarked tab/);
  });
});

describe('2 — the planning pass cannot change anything', () => {
  test('every mutating tool is absent while planning', () => {
    const names = planningTools().map((t) => t.name);
    for (const banned of MUTATING_TOOLS) expect(names).not.toContain(banned);
    // …and the read-only ones it needs to look around are still there.
    expect(names).toContain('navigate');
    expect(names).toContain('scroll');
    expect(names).toContain('done');
  });
});

describe('1 — the map travels in the instructions', () => {
  test('an empty map leaves the instructions alone', () => {
    expect(withMap('be careful', '')).toBe('be careful');
  });

  test('a map is wrapped and appended', () => {
    const out = withMap('be careful', 'gradebook.php — the gradebook');
    expect(out).toContain('be careful');
    expect(out).toContain('<site_map>');
    expect(out).toContain('gradebook.php');
  });

  test('a huge map is truncated, and says so', () => {
    const out = withMap(undefined, 'x'.repeat(20000), 500);
    expect(out.length).toBeLessThan(1200);
    expect(out).toContain('truncated');
  });
});

describe('the composer wires the guarantees together', () => {
  const sent: string[] = [];

  beforeEach(() => {
    sent.length = 0;
    vi.stubGlobal('fetch', async (_u: string, init: { body: string }) => {
      sent.push(init.body);
      return {
        ok: true,
        json: async () => ({
          choices: [
            {
              message: {
                tool_calls: [
                  { function: { name: 'AgentOutput', arguments: JSON.stringify({ action: { done: { text: 'ok', success: true } } }) } },
                ],
              },
            },
          ],
        }),
      };
    });
  });
  afterEach(() => vi.unstubAllGlobals());

  const base = { task: 'do it', baseURL: 'http://x/v1', model: 'm', maxSteps: 1 } as const;

  /**
   * The names the model may actually emit. Read from the tool schema, not from the body text:
   * the vendored system prompt mentions input_text in its prose rules, so a substring search
   * over the whole request passes and fails for the wrong reasons.
   */
  const offeredTools = (body: string): string[] =>
    Object.keys(JSON.parse(body).tools[0].function.parameters.properties.action.properties);

  test('a planning run offers no mutating tool to the model', async () => {
    await runPageAgentTask({ ...base, mode: 'plan' }, ctx());
    const offered = offeredTools(sent[0]);
    for (const banned of MUTATING_TOOLS) expect(offered).not.toContain(banned);
    expect(offered).toContain('navigate');
    expect(sent.join()).toContain('You are PLANNING, not doing');
  });

  test('the planning directive is in the TASK, not only the instructions', async () => {
    await runPageAgentTask({ ...base, mode: 'plan', task: 'post an announcement' }, ctx());
    const user = JSON.parse(sent[0]).messages[1].content as string;
    const request = user.slice(user.indexOf('<user_request>'), user.indexOf('</user_request>'));
    // The system prompt calls the user request the highest-priority objective, so an action
    // phrased there gets obeyed however the instructions are worded.
    expect(request).toContain('PLAN ONLY');
    expect(request).toContain('post an announcement');
  });

  test('an execute run leaves the task alone', async () => {
    await runPageAgentTask({ ...base, mode: 'execute', task: 'post an announcement' }, ctx());
    const user = JSON.parse(sent[0]).messages[1].content as string;
    expect(user).not.toContain('PLAN ONLY');
  });

  test('an execute run does offer them', async () => {
    await runPageAgentTask({ ...base, mode: 'execute' }, ctx());
    const offered = offeredTools(sent[0]);
    for (const t of MUTATING_TOOLS) expect(offered).toContain(t);
  });

  test('the approved plan is carried into the execute pass', async () => {
    await runPageAgentTask({ ...base, mode: 'execute', approvedPlan: '1. Click Save' }, ctx());
    const body = sent.join();
    expect(body).toContain('<approved_plan>');
    expect(body).toContain('1. Click Save');
  });

  test('the watchdog runs for exactly the length of the run', async () => {
    const calls: string[] = [];
    await runPageAgentTask(
      { ...base, mode: 'execute', watchdog: { start: () => calls.push('start'), stop: () => calls.push('stop') } },
      ctx(),
    );
    expect(calls).toEqual(['start', 'stop']);
  });

  test('the watchdog is stopped even when the run throws', async () => {
    const calls: string[] = [];
    const broken = ctx();
    broken.cdpSend = vi.fn(async () => { throw new Error('CDP wedged'); });
    await runPageAgentTask(
      { ...base, mode: 'execute', watchdog: { start: () => calls.push('start'), stop: () => calls.push('stop') } },
      broken,
    );
    expect(calls).toEqual(['start', 'stop']);
  });

  test('the mask is not optional — no caller can run unmasked', async () => {
    await runPageAgentTask({ ...base, mode: 'execute', task: 'grade Alvarez, Jordan' }, ctx());
    expect(sent.join()).not.toContain('Alvarez, Jordan');
  });
});

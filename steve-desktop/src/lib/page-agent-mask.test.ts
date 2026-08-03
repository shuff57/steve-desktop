import { describe, expect, test, vi, beforeEach, afterEach } from 'vitest';
import { runAgentLoop } from './page-agent-loop';
import { activeRunMaskCount, createPageMask, endRunMask, maskForRun } from './page-agent-mask';
import type { PageAgentTool, ToolContext } from './page-agent-tools';

// Real loop, stubbed page + endpoint: the point is what leaves the machine.
const page = {
  header: 'Current URL: https://lms.example/courses/1/gradebook',
  content: '[0]<link>Alvarez, Jordan</link>',
  footer: '\n<page_text>\nAlvarez, Jordan  ID 7158619  score 88\n</page_text>',
};

vi.mock('./page-agent-dom', () => ({
  extractBrowserState: async () => ({ ...page }),
}));

const sent: string[] = [];
/** What the model answers with, one entry per step. Defaults to an immediate done. */
let replies: Record<string, unknown>[] = [];

beforeEach(() => {
  sent.length = 0;
  replies = [];
  page.header = 'Current URL: https://lms.example/courses/1/gradebook';
  page.content = '[0]<link>Alvarez, Jordan</link>';
  page.footer = '\n<page_text>\nAlvarez, Jordan  ID 7158619  score 88\n</page_text>';
  vi.stubGlobal('fetch', async (_url: string, init: { body: string }) => {
    const action = replies.shift() ?? { done: { text: 'ok', success: true } };
    sent.push(init.body);
    return {
      ok: true,
      json: async () => ({
        choices: [
          { message: { tool_calls: [{ function: { name: 'AgentOutput', arguments: JSON.stringify({ action }) } }] } },
        ],
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

/** Records what the tool layer actually received — i.e. what would touch the real page. */
function probeTool(seen: Record<string, unknown>[]): PageAgentTool<Record<string, unknown>> {
  return {
    name: 'probe',
    description: 'test probe',
    execute: async (_c, params) => {
      seen.push(params);
      return '✅ probed';
    },
  };
}

const base = { baseURL: 'http://x/v1', model: 'm', task: 'read the page', maxSteps: 1 };

describe('the mask keeps page data off the wire', () => {
  test('without a mask, page text reaches the model verbatim', async () => {
    await runAgentLoop({ ...base }, ctx());
    // Establishes the risk the mask exists to remove: this is a real name and id shape.
    expect(sent.join()).toContain('Alvarez, Jordan');
    expect(sent.join()).toContain('7158619');
  });

  test('with a mask, the name and the id are tokenized and the rest survives', async () => {
    await runAgentLoop({ ...base, mask: createPageMask() }, ctx());
    const body = sent.join();
    expect(body).not.toContain('Alvarez, Jordan');
    expect(body).not.toContain('7158619');
    expect(body).toMatch(/⟦STU\d+⟧/);
    // The non-identifying part of the page must survive, or the agent is blind.
    expect(body).toContain('score 88');
  });

  test('the task sentence is masked too — the operator can type a name into it', async () => {
    await runAgentLoop(
      { ...base, task: 'open the row for Alvarez, Jordan', mask: createPageMask() },
      ctx(),
    );
    expect(sent.join()).not.toContain('Alvarez, Jordan');
  });

  test('skill instructions are NOT masked — they name the controls to click', async () => {
    page.header = 'Current URL: https://lms.example/courses/1/gradebook';
    await runAgentLoop(
      { ...base, instructions: 'Click Teacher Preview to verify.', mask: createPageMask() },
      ctx(),
    );
    expect(sent.join()).toContain('Teacher Preview');
  });
});

describe('what gets masked depends on the surface', () => {
  const contentPage = 'Current URL: https://lms.example/course/course.php?cid=1&folder=0-5';

  test('ordinary UI wording survives on a page that is not about people', async () => {
    page.header = contentPage;
    page.content = '[0]<button>Add Question</button>\n[1]<link>Course Map</link>';
    page.footer = '\n<page_text>\nAdd Question\n</page_text>';
    await runAgentLoop({ ...base, mask: createPageMask() }, ctx());
    expect(sent.join()).toContain('Add Question');
    expect(sent.join()).toContain('Course Map');
  });

  test('but the comma name form is masked anywhere, people surface or not', async () => {
    page.header = contentPage;
    page.content = '[0]<button>Add Question</button>';
    page.footer = '\n<page_text>\nPosted by Nakamura, Yuki\n</page_text>';
    await runAgentLoop({ ...base, mask: createPageMask() }, ctx());
    expect(sent.join()).not.toContain('Nakamura, Yuki');
    expect(sent.join()).toContain('Add Question');
  });

  test('a person id in the path is masked even on a content page', async () => {
    page.header = 'Current URL: https://canvas.example/courses/31407/modules';
    page.content = '[0]<link>Submission</link>';
    page.footer = '\n<page_text>\nsee /courses/31407/users/127333 for detail\n</page_text>';
    await runAgentLoop({ ...base, mask: createPageMask() }, ctx());
    const body = sent.join();
    expect(body).not.toContain('127333');
    // Course structure stays legible — masking it would make the map unnavigable.
    expect(body).toContain('31407');
  });
});

describe('the agent can still act on what it was shown', () => {
  test('a token in an action is rehydrated before it reaches the page', async () => {
    const seen: Record<string, unknown>[] = [];
    replies = [{ probe: { text: '⟦STU1⟧' } }, { done: { text: 'ok', success: true } }];
    await runAgentLoop(
      { ...base, maxSteps: 2, mask: createPageMask(), customTools: { probe: probeTool(seen) } },
      ctx(),
    );
    // The model only ever saw the token; the page gets the real value.
    expect(seen[0]).toEqual({ text: 'Alvarez, Jordan' });
  });

  test('a masked person id round-trips back into a navigate', async () => {
    const seen: Record<string, unknown>[] = [];
    page.header = 'Current URL: https://canvas.example/courses/1/users/127333';
    page.content = '[0]<link>Grades</link>';
    page.footer = '';
    const mask = createPageMask();
    replies = [{ probe: { url: '/courses/1/users/⟦PID1⟧' } }, { done: { text: 'ok', success: true } }];
    await runAgentLoop(
      { ...base, maxSteps: 2, mask, customTools: { probe: probeTool(seen) } },
      ctx(),
    );
    expect(seen[0]).toEqual({ url: '/courses/1/users/127333' });
  });
});

describe('a token means one person for the whole run', () => {
  const gradebook = 'https://lms.example/courses/1/gradebook';

  test('the same run keeps the same token across separate calls', () => {
    const a = maskForRun('run-1').text('Alvarez, Jordan', gradebook);
    // A second page-tool call, later in the same task — the orchestrator is still holding the
    // token from the first one.
    const b = maskForRun('run-1').text('Alvarez, Jordan owes work', gradebook);
    const token = a.match(/⟦STU\d+⟧/)![0];
    expect(b).toContain(token);
    endRunMask('run-1');
  });

  test('and the orchestrator can act on that token later', () => {
    const mask = maskForRun('run-2');
    const seen = mask.text('Nakamura, Yuki', gradebook);
    const token = seen.match(/⟦STU\d+⟧/)![0];
    // What Claude sends back refers to the token; the app puts the person back locally.
    expect(mask.rehydrate(`open ${token}'s row`)).toBe("open Nakamura, Yuki's row");
    endRunMask('run-2');
  });

  test('this is exactly what a per-call mask would get wrong', () => {
    // The failure the registry exists to prevent: two masks hand the SAME token to two
    // different people, so an instruction lands on the wrong student and nothing looks wrong.
    const first = createPageMask().text('Alvarez, Jordan', gradebook);
    const second = createPageMask().text('Nakamura, Yuki', gradebook);
    expect(first.match(/⟦STU\d+⟧/)![0]).toBe(second.match(/⟦STU\d+⟧/)![0]);
  });

  test('separate runs never share a map', () => {
    const a = maskForRun('run-a');
    const b = maskForRun('run-b');
    expect(a).not.toBe(b);
    endRunMask('run-a');
    endRunMask('run-b');
  });

  test('ending a run drops the only copy of its identifiers', () => {
    const before = activeRunMaskCount();
    const mask = maskForRun('run-3');
    mask.text('Okonkwo, Sarah', gradebook);
    expect(Object.values(mask.map)).toContain('Okonkwo, Sarah');
    endRunMask('run-3');
    expect(activeRunMaskCount()).toBe(before);
    // A run id reused after cleanup starts clean, rather than inheriting a stale roster.
    expect(Object.values(maskForRun('run-3').map)).toHaveLength(0);
    endRunMask('run-3');
  });
});

describe('over-masking has its own failure mode', () => {
  // Both of these were measured against a live driven gradebook, not imagined: the first
  // turned "Gradebook\nCourse Home" into a person and then ate gradebook.php, the second left
  // the agent holding a token where its instructions named a button.
  const gradebook = 'https://lms.example/courses/1/gradebook';

  test('adjacent cells and lines do not fuse into a fake person', () => {
    const mask = createPageMask();
    const out = mask.text('Gradebook\nCourse Home\nEmail\tTotal Score\tLast Login', gradebook);
    expect(out).toBe('Gradebook\nCourse Home\nEmail\tTotal Score\tLast Login');
  });

  test('a control label stays readable even on a people surface', () => {
    const mask = createPageMask();
    const out = mask.text('[4]<button>Teacher Preview</button>', gradebook);
    expect(out).toContain('Teacher Preview');
  });

  test('but a roster row, which is a link, is still masked there', () => {
    const mask = createPageMask();
    const out = mask.text('[9]<link>Jordan Alvarez</link>', gradebook);
    expect(out).not.toContain('Jordan Alvarez');
  });
});

describe('a value tokenized once stays tokenized', () => {
  test('history cannot launder a name by carrying it to a content page', () => {
    const mask = createPageMask();
    // Discovered on the gradebook…
    mask.text('Alvarez, Jordan  score 88', 'https://lms.example/courses/1/gradebook');
    // …and still masked when a tool output drags it onto a page that is not about people,
    // where the plain-form tier is deliberately off.
    const later = mask.text(
      'Action Results: options are Alvarez, Jordan | Beck, Sam',
      'https://lms.example/course/course.php?cid=1',
    );
    expect(later).not.toContain('Alvarez');
    expect(later).not.toContain('Jordan');
  });

  test('the reversed form of a discovered name is caught too', () => {
    const mask = createPageMask();
    mask.text('Nakamura, Yuki', 'https://lms.example/courses/1/gradebook');
    const later = mask.text('signed by Yuki Nakamura', 'https://lms.example/course/course.php?cid=1');
    expect(later).not.toContain('Nakamura');
    expect(later).not.toContain('Yuki');
  });

  test('rehydrate is the exact inverse for the values it tokenized', () => {
    const mask = createPageMask();
    const masked = mask.text('Alvarez, Jordan  ID 7158619', 'https://lms.example/courses/1/gradebook');
    expect(mask.rehydrate(masked)).toBe('Alvarez, Jordan  ID 7158619');
  });
});

/** @vitest-environment jsdom */
import { describe, it, expect } from 'vitest';
import { foldStep, buildWorkflow, RECORDER_SCRIPT } from './teach-recorder';
import { buildTeachPolishPrompt, parseTeachPolish, composeTeachSkill, tokenizeWorkflowValues } from './teach-polish';
import { skillToWorkflow } from './workflow-skill';
import type { WorkflowStep } from './types/site-profile';

const click = (sel: string): WorkflowStep => ({ action: 'click', selector: sel, description: sel });
const fill = (sel: string, value: string): WorkflowStep => ({ action: 'fill', selector: sel, value, description: sel });

describe('foldStep', () => {
  it('collapses consecutive fills on the same field to the last value', () => {
    let s: WorkflowStep[] = [];
    s = foldStep(s, fill('#grade', 'A'));
    s = foldStep(s, fill('#grade', 'A-'));
    s = foldStep(s, fill('#grade', 'B'));
    expect(s).toHaveLength(1);
    expect(s[0].value).toBe('B');
  });

  it('appends clicks and fills on different fields', () => {
    let s: WorkflowStep[] = [];
    s = foldStep(s, fill('#name', 'Jane'));
    s = foldStep(s, fill('#grade', 'A'));
    s = foldStep(s, click('#submit'));
    expect(s.map((x) => x.selector)).toEqual(['#name', '#grade', '#submit']);
  });
});

describe('recorder script', () => {
  it('never records password fields and guards against throwing/double-install', () => {
    expect(RECORDER_SCRIPT).toContain("ty==='password'");
    expect(RECORDER_SCRIPT).toContain('__steveTeachOn');
  });
});

describe('polish', () => {
  it('parses a JSON polish object and clamps lengths', () => {
    const p = parseTeachPolish('  {"name":"Enter grade","description":"Fills a grade","summary":"1. do it"}  ');
    expect(p?.name).toBe('Enter grade');
    expect(p?.summary).toBe('1. do it');
  });

  it('returns null when the CLI text has no object', () => {
    expect(parseTeachPolish('sorry, I could not do that')).toBeNull();
  });

  it('prompt embeds the workflow json', () => {
    const wf = buildWorkflow('demo', [click('#go')], 'https://x.test');
    expect(buildTeachPolishPrompt(wf, 'https://x.test')).toContain('"selector": "#go"');
  });

  it('prompt NEVER contains recorded literal values — they are ⟦V n⟧ tokens (FERPA)', () => {
    const wf = buildWorkflow(
      'demo',
      [{ action: 'fill', selector: '#n', value: 'Alice Smith', description: 'Student name' }],
      'https://x.test',
    );
    const p = buildTeachPolishPrompt(wf);
    expect(p).not.toContain('Alice Smith');
    expect(p).toContain('⟦V1⟧');
    expect(p).toContain('Student name'); // the element label carries the intent
  });

  describe('a dropdown label is a roster', () => {
    // Measured on a live recording, not imagined. Recording ONE choice from a student dropdown
    // put the whole roster in front of the model three times: a <select>'s accessible name is
    // its concatenated option list, so it lands in the step description, in the
    // role=combobox[name="…"] candidate selector, and again in the fingerprint. Tokenizing
    // `value` caught the id and missed every name.
    const roster = () => ({
      name: 'Export a student CSV',
      trigger: 'On https://lms.example/courses/1/roster',
      steps: [
        {
          action: 'select' as const,
          selector: '#stupick',
          candidates: [
            'role=combobox[name="-- pick a student -- Alvarez, Jordan Nakamura, Yuki"]',
            '[name="stupick"]',
          ],
          description: '-- pick a student -- Alvarez, Jordan Nakamura, Yuki',
          value: '7158620',
        },
        { action: 'click' as const, selector: '#export', description: 'Export CSV' },
      ],
    });

    it('keeps every name and id out of the prompt, wherever it is hiding', () => {
      const p = buildTeachPolishPrompt(roster(), 'https://lms.example/courses/1/roster');
      expect(p).not.toContain('Alvarez, Jordan');
      expect(p).not.toContain('Nakamura, Yuki');
      expect(p).not.toContain('7158620');
    });

    it('still says enough for the prose to name the control', () => {
      const p = buildTeachPolishPrompt(roster(), 'https://lms.example/courses/1/roster');
      expect(p).toContain('pick a student');
      expect(p).toContain('Export CSV');
    });

    it('leaves the workflow that gets stored and replayed untouched', () => {
      // Masking the stored copy would break the thing it protects: replay needs real selectors.
      const wf = roster();
      buildTeachPolishPrompt(wf, 'https://lms.example/courses/1/roster');
      expect(wf.steps[0].value).toBe('7158620');
      expect(wf.steps[0].description).toContain('Nakamura, Yuki');
    });
  });
});

describe('workflow.values (Finding 1 fix — a sibling data path to step values, not a special case)', () => {
  it('tokenizeWorkflowValues genericizes workflow.values the same way it genericizes step values', () => {
    const wfv = { ...buildWorkflow('demo', [click('#go')], 'https://x.test'), values: { course_url: 'https://lms.example/courses/1' } };
    const t = tokenizeWorkflowValues(wfv);
    expect(t.values?.course_url).toMatch(/^⟦V\d+⟧$/);
    expect(t.values?.course_url).not.toBe('https://lms.example/courses/1');
  });

  it('a promoted value never appears literally in the polish prompt, even a safe one', () => {
    const wfv = {
      ...buildWorkflow('demo', [{ action: 'fill' as const, selector: '#url', value: '{{course_url}}', description: 'course url field' }], 'https://x.test'),
      values: { course_url: 'https://lms.example/courses/9182' },
    };
    const p = buildTeachPolishPrompt(wfv, 'https://x.test');
    expect(p).not.toContain('https://lms.example/courses/9182');
  });

  it('a value that bypassed the promotion gate is still kept out of the prompt (defense in depth)', () => {
    // teach-tokens.ts's promoteToToken refuses to put this here in the first place; this proves
    // the prompt path does not ALSO depend on that refusal ever having run.
    const wfv = {
      ...buildWorkflow('demo', [{ action: 'fill' as const, selector: '#n', value: '{{contact}}', description: 'contact field' }], 'https://x.test'),
      values: { contact: 'sarah.chen@example.edu' },
    };
    const p = buildTeachPolishPrompt(wfv, 'https://x.test');
    expect(p).not.toContain('sarah.chen@example.edu');
  });
});

describe('narration (item 6, FERPA)', () => {
  // The teacher types a free-text note at Stop ("what were you doing?"). It goes to the model, so
  // it must be masked through the same whole-body path as the workflow — with the plain-name form
  // ON, since prose has no UI labels for that tier to protect (page-agent-mask.ts's `plainNames`).
  it('masks a realistic student name out of narration, even off a people-surface start URL', () => {
    const wf = buildWorkflow('demo', [click('#go')], 'https://x.test');
    const p = buildTeachPolishPrompt(wf, 'https://x.test', "grading Sarah Chen's makeup quiz");
    expect(p).not.toContain('Sarah');
    expect(p).not.toContain('Chen');
    expect(p).toMatch(/⟦STU\d+⟧/);
    expect(p).toContain('makeup quiz'); // the non-identifying intent survives
  });

  it('masks the comma form in narration too', () => {
    const wf = buildWorkflow('demo', [click('#go')], 'https://x.test');
    const p = buildTeachPolishPrompt(wf, 'https://x.test', 'grading Chen, Sarah for the makeup quiz');
    expect(p).not.toContain('Chen, Sarah');
  });

  it('shares one token map with the workflow — the same name gets the same token in both', () => {
    // The name lives in `description`, not `value` — `value` is already replaced with a ⟦V n⟧
    // placeholder before masking (tokenizeWorkflowValues), so it never reaches the mask itself.
    const wf = buildWorkflow(
      'demo',
      [{ action: 'click', selector: '#n', description: 'Sarah Chen row' }],
      'https://lms.example/courses/1/gradebook', // a people surface, so the workflow side masks the plain form too
    );
    const p = buildTeachPolishPrompt(wf, 'https://lms.example/courses/1/gradebook', 'grading Sarah Chen');
    const tokens = p.match(/⟦STU\d+⟧/g) ?? [];
    expect(tokens.length).toBeGreaterThan(0);
    expect(new Set(tokens).size).toBe(1); // one person, one token, everywhere it appears
  });

  it('omits the narration line entirely when none was given', () => {
    const wf = buildWorkflow('demo', [click('#go')], 'https://x.test');
    expect(buildTeachPolishPrompt(wf, 'https://x.test')).not.toContain('described what they were doing');
  });
});

describe('composeTeachSkill', () => {
  it('produces a replayable skill even with null polish', () => {
    const wf = buildWorkflow('Enter grade', [fill('#grade', 'A'), click('#submit')], 'https://sis.test');
    const md = composeTeachSkill(wf, null, 'sis.test');
    // the round-trip the replay stack relies on must survive
    expect(skillToWorkflow(md).steps).toHaveLength(2);
  });

  it('splices the polished summary in as prose while keeping the json', () => {
    const wf = buildWorkflow('Enter grade', [click('#submit')]);
    const md = composeTeachSkill(wf, { name: 'Enter grade', description: 'd', summary: '1. Click submit' }, undefined);
    expect(md).toContain('1. Click submit');
    expect(skillToWorkflow(md).steps).toHaveLength(1);
  });
});

describe('recorder captures a heal fingerprint (stage 3 capture side)', () => {
  it('emits the weak signals the ranker scores against', () => {
    // Exercise the injected script in a DOM so the captured shape is real, not asserted by eye.
    document.body.innerHTML = `
      <div><span>Student name</span>
        <button id="save" class="btn primary" data-testid="save-btn" aria-label="Save Grade">Save Grade</button>
      </div>`;
    const sent: string[] = [];
    (window as unknown as Record<string, unknown>).__steveTeach = (s: string) => sent.push(s);
    delete (window as unknown as Record<string, unknown>).__steveTeachOn;
    // eslint-disable-next-line no-eval
    (0, eval)(RECORDER_SCRIPT);

    document.getElementById('save')!.dispatchEvent(new MouseEvent('click', { bubbles: true }));

    expect(sent).toHaveLength(1);
    const step = JSON.parse(sent[0]);
    expect(step.fingerprint).toBeTruthy();
    expect(step.fingerprint.tag).toBe('button');
    expect(step.fingerprint.role).toBe('button');
    expect(step.fingerprint.name).toBe('Save Grade');
    expect(step.fingerprint.id).toBe('save');
    expect(step.fingerprint.classes).toEqual(['btn', 'primary']);
    // the visible label sitting before the control — survives the control's own rename
    expect(step.fingerprint.neighborText).toBe('Student name');
    expect(typeof step.fingerprint.siblingIndex).toBe('number');
  });
});

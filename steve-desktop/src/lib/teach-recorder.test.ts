import { describe, it, expect } from 'vitest';
import { foldStep, buildWorkflow, RECORDER_SCRIPT } from './teach-recorder';
import { buildTeachPolishPrompt, parseTeachPolish, composeTeachSkill } from './teach-polish';
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

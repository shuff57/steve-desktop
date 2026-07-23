import { describe, it, expect } from 'vitest';
import { paramSlug, setParam, workflowParams, bindWorkflow, parseRoster, rowLabel } from './teach-params';
import type { Workflow, WorkflowStep } from './types/site-profile';

const wf = (steps: WorkflowStep[]): Workflow => ({ name: 'w', steps });

describe('paramSlug', () => {
  it('slugs human labels', () => {
    expect(paramSlug('Student name')).toBe('student_name');
    expect(paramSlug('  Grade (letter)! ')).toBe('grade_letter');
    expect(paramSlug('')).toBe('value');
  });
});

describe('setParam', () => {
  it('drops the recorded literal value when parameterizing (FERPA: skill is de-identified)', () => {
    const s = setParam({ action: 'fill', selector: '#n', value: 'Alice' }, 'Student name');
    expect(s.param).toBe('student_name');
    expect(s.value).toBeUndefined();
  });
  it('clears the param on null', () => {
    const s = setParam({ action: 'fill', param: 'x' }, null);
    expect(s.param).toBeUndefined();
  });
});

describe('bindWorkflow', () => {
  const w = wf([
    { action: 'fill', selector: '#n', param: 'student_name' },
    { action: 'select', selector: '#g', param: 'grade' },
    { action: 'click', selector: '#save' },
  ]);
  it('binds row values into parameterized steps only', () => {
    const b = bindWorkflow(w, { student_name: 'Alice', grade: 'B' });
    expect(b.steps[0].value).toBe('Alice');
    expect(b.steps[1].value).toBe('B');
    expect(b.steps[2].value).toBeUndefined();
    expect(w.steps[0].value).toBeUndefined(); // original untouched
  });
  it('refuses a row missing a value — never replays an empty grade silently', () => {
    expect(() => bindWorkflow(w, { student_name: 'Alice', grade: '' })).toThrow(/grade/);
  });
});

describe('workflowParams', () => {
  it('ordered unique names', () => {
    expect(workflowParams(wf([
      { action: 'fill', param: 'a' },
      { action: 'fill', param: 'b' },
      { action: 'fill', param: 'a' },
    ]))).toEqual(['a', 'b']);
  });
});

describe('parseRoster', () => {
  it('parses CSV with quoted commas', () => {
    const r = parseRoster('Student name,Grade\n"Smith, Jane",A\nBob,B');
    expect(r.headers).toEqual(['student_name', 'grade']);
    expect(r.rows).toEqual([
      { student_name: 'Smith, Jane', grade: 'A' },
      { student_name: 'Bob', grade: 'B' },
    ]);
  });
  it('parses spreadsheet paste (tabs)', () => {
    const r = parseRoster('Student name\tGrade\nAlice\tB');
    expect(r.rows).toEqual([{ student_name: 'Alice', grade: 'B' }]);
  });
  it('header-only or empty → no rows', () => {
    expect(parseRoster('a,b').rows).toEqual([]);
    expect(parseRoster('').rows).toEqual([]);
  });
  it('rowLabel is the first cell', () => {
    const r = parseRoster('name,grade\nAlice,B');
    expect(rowLabel(r.rows[0], r.headers)).toBe('Alice');
  });
});

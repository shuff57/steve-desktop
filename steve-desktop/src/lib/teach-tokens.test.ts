import { describe, it, expect } from 'vitest';
import { promoteToToken, setTokenValue, resolveTokens, isSafeToPromote } from './teach-tokens';
import { setParam } from './teach-params';
import type { Workflow, WorkflowStep } from './types/site-profile';

const wf = (steps: WorkflowStep[], values?: Record<string, string>): Workflow => ({ name: 'w', steps, values });

// The specification: this exact table is what a reviewer probed against a miscalibrated first
// cut (isSafeToPromote forcing a people-surface sentinel regardless of origin, and LABEL_WORD
// missing assessment vocabulary). Read top to bottom to see exactly where the boundary sits.
describe('isSafeToPromote (Finding 1, corrected calibration — the mask is the one detector, not the UI)', () => {
  it('a full course URL is safe', () => {
    expect(isSafeToPromote('https://www.myopenmath.com/course/course.php?cid=334243')).toBe(true);
  });
  it('a bare course id is safe when not recorded on a page that lists people', () => {
    expect(isSafeToPromote('334243')).toBe(true);
  });
  it('the SAME bare digits ARE refused when recorded on a page that lists people — there it ' +
    'could be a student id, and the mask only reads that risk from where the value came from', () => {
    expect(isSafeToPromote('334243', 'https://lms.example/courses/1/gradebook')).toBe(false);
  });
  it('an ordinary two-word assignment name is safe (LABEL_WORD now covers assessment vocabulary)', () => {
    expect(isSafeToPromote('Midterm Exam')).toBe(true);
  });
  it('a digit-suffixed, all-caps, or single-word label was always safe — no two-word Title Case shape to match', () => {
    expect(isSafeToPromote('Quiz 3')).toBe(true);
    expect(isSafeToPromote('Chapter 7 Homework')).toBe(true);
    expect(isSafeToPromote('MATH 146')).toBe(true);
    expect(isSafeToPromote('Final')).toBe(true);
  });
  it('a two-word label NOT in the allowlist is still refused — the cost of biasing toward ' +
    'refusing: a false positive here (one promotion the teacher can\'t make), never a leak', () => {
    expect(isSafeToPromote('Weekly Standup')).toBe(false);
  });
  it('a two-part name is refused regardless of where it was recorded', () => {
    expect(isSafeToPromote('Sarah Chen')).toBe(false);
    expect(isSafeToPromote('Sarah Chen', 'https://lms.example/course/course.php?cid=1')).toBe(false);
  });
  it('a comma-form name is refused', () => {
    expect(isSafeToPromote('Chen, Sarah')).toBe(false);
  });
  it('a three-part name is refused — even the restored partial-mask (leading pair only, no ' +
    'particle to justify the whole run) still counts as "the mask changed the string"', () => {
    expect(isSafeToPromote('Mary Jane Watson')).toBe(false);
  });
  it('a particle-joined name is refused — redact-tree.ts\'s name-grammar fix, not just a two-word check', () => {
    expect(isSafeToPromote('Maria de la Cruz')).toBe(false);
  });
  it('an accented name is refused — [A-Z]/[a-z] were ASCII-only and used to allow this through', () => {
    expect(isSafeToPromote('José García')).toBe(false);
  });
  it('an email is refused', () => {
    expect(isSafeToPromote('sarah.chen@example.edu')).toBe(false);
  });
  it('a sentence containing a name is refused', () => {
    expect(isSafeToPromote('Extension granted for Jordan Alvarez')).toBe(false);
  });
  it('a bare first name is allowed — deliberate, accepted gap, not an oversight: PERSON_LABEL ' +
    'requires two capitalized words on purpose (a lone word is indistinguishable from an ' +
    'ordinary short label without a roster), and no single-word detector exists anywhere else ' +
    "on this app's FERPA boundary either. See isSafeToPromote's doc comment.", () => {
    expect(isSafeToPromote('Jordan')).toBe(true);
  });
});

describe('promoteToToken', () => {
  it('refuses a value the mask flags as identifying — the enforcement, not just the UI hint', () => {
    const w = wf([{ action: 'fill', selector: '#n', value: 'Sarah Chen' }]);
    expect(() => promoteToToken(w, 0, 'name')).toThrow(/identifying/);
    expect(w.steps[0].value).toBe('Sarah Chen'); // refused, not silently dropped
  });

  it('refuses a bare student id when the workflow was recorded on a page that lists people', () => {
    const w = { ...wf([{ action: 'fill', selector: '#n', value: '7158619' }]), trigger: 'On https://lms.example/courses/1/gradebook' };
    expect(() => promoteToToken(w, 0, 'id')).toThrow(/identifying/);
  });

  it('allows a bare course id when NOT recorded on a page that lists people', () => {
    const w = wf([{ action: 'fill', selector: '#n', value: '334243' }]);
    const out = promoteToToken(w, 0, 'Course ID');
    expect(out.values).toEqual({ course_id: '334243' });
  });

  it('refuses an email', () => {
    const w = wf([{ action: 'fill', selector: '#n', value: 'sarah.chen@example.edu' }]);
    expect(() => promoteToToken(w, 0, 'contact')).toThrow(/identifying/);
  });

  it('replaces the literal value with a {{key}} placeholder and stores it in workflow.values', () => {
    const w = wf([{ action: 'fill', selector: '#url', value: 'https://lms.example/courses/1' }]);
    const out = promoteToToken(w, 0, 'Course URL');
    expect(out.steps[0].value).toBe('{{course_url}}');
    expect(out.values).toEqual({ course_url: 'https://lms.example/courses/1' });
    // original untouched
    expect(w.steps[0].value).toBe('https://lms.example/courses/1');
  });

  it('reuses an existing token of the same name when the value already matches', () => {
    const w = wf(
      [
        { action: 'fill', selector: '#a', value: 'https://lms.example/courses/1' },
        { action: 'fill', selector: '#b', value: 'https://lms.example/courses/1' },
      ],
      { course_url: 'https://lms.example/courses/1' },
    );
    const out = promoteToToken(w, 1, 'Course URL');
    expect(out.steps[1].value).toBe('{{course_url}}');
    expect(Object.keys(out.values ?? {})).toEqual(['course_url']);
  });

  it('suffixes a fresh key when the name collides with a DIFFERENT existing value', () => {
    const w = wf([{ action: 'fill', selector: '#b', value: 'section-2' }], { course_url: 'https://lms.example/courses/1' });
    const out = promoteToToken(w, 0, 'Course URL');
    expect(out.steps[0].value).toBe('{{course_url_2}}');
    expect(out.values).toEqual({ course_url: 'https://lms.example/courses/1', course_url_2: 'section-2' });
  });

  it('refuses a parameterized step — param and token never coexist on one step', () => {
    const w = wf([setParam({ action: 'fill', selector: '#n', value: 'Alice' }, 'student_name')]);
    expect(() => promoteToToken(w, 0, 'anything')).toThrow(/parameterized/);
  });

  it('no-ops on a step with no literal value', () => {
    const w = wf([{ action: 'click', selector: '#go' }]);
    const out = promoteToToken(w, 0, 'name');
    expect(out).toBe(w);
  });
});

describe('setTokenValue', () => {
  it('edits a token in place without touching steps', () => {
    const w = wf([{ action: 'fill', selector: '#a', value: '{{course_url}}' }], { course_url: 'old' });
    const out = setTokenValue(w, 'course_url', 'new');
    expect(out.values).toEqual({ course_url: 'new' });
    expect(out.steps[0].value).toBe('{{course_url}}');
  });

  it('refuses to turn an already-safe token into identifying data', () => {
    const w = wf([{ action: 'fill', selector: '#a', value: '{{course_url}}' }], { course_url: 'https://lms.example/courses/1' });
    expect(() => setTokenValue(w, 'course_url', 'Sarah Chen')).toThrow(/identifying/);
    expect(w.values?.course_url).toBe('https://lms.example/courses/1');
  });
});

describe('resolveTokens', () => {
  it('round-trips: promote a literal, resolve it, get the original back', () => {
    const w = wf([{ action: 'fill', selector: '#url', value: 'https://lms.example/courses/1' }]);
    const promoted = promoteToToken(w, 0, 'Course URL');
    const resolved = resolveTokens(promoted);
    expect(resolved.steps[0].value).toBe('https://lms.example/courses/1');
  });

  it('leaves non-token steps (params, plain literals) untouched', () => {
    const w = wf([
      setParam({ action: 'fill', selector: '#n' }, 'student_name'),
      { action: 'fill', selector: '#x', value: 'plain literal' },
    ]);
    const resolved = resolveTokens(w);
    expect(resolved.steps[0].param).toBe('student_name');
    expect(resolved.steps[0].value).toBeUndefined();
    expect(resolved.steps[1].value).toBe('plain literal');
  });

  it('throws on an unresolved key rather than replaying an empty value', () => {
    const w = wf([{ action: 'fill', selector: '#url', value: '{{course_url}}' }]);
    expect(() => resolveTokens(w)).toThrow(/course_url/);
  });
});

import { describe, expect, it } from 'vitest';
import { resolveTokens } from './teach-tokens';
import { bindWorkflow } from './teach-params';
import { syncHealedAnchors } from './workflow-heals';
import type { Workflow } from './types/site-profile';

describe('syncHealedAnchors', () => {
  it('persists healed anchors without serializing resolved fixed-token values', () => {
    const master: Workflow = {
      name: 'course task',
      values: { course_url: 'https://lms.example/courses/1' },
      steps: [{ action: 'fill', selector: '#old', candidates: ['#older'], value: '{{course_url}}' }],
    };
    const replayed = resolveTokens(master);
    replayed.steps[0].selector = '#healed';
    replayed.steps[0].candidates = ['#healed-fallback'];

    syncHealedAnchors(master, replayed);

    expect(master.steps[0]).toMatchObject({
      selector: '#healed',
      candidates: ['#healed-fallback'],
      value: '{{course_url}}',
    });
    expect(master.values).toEqual({ course_url: 'https://lms.example/courses/1' });
  });

  // Load-bearing aliasing: resolveTokens and bindWorkflow return the SAME step reference for any
  // step they don't rewrite, and replay's persistHeal mutates that reference in place. That is
  // what propagates a healed selector to later batch rows. If either function ever becomes a
  // deep-copying "pure" clone, this test fails — the heal would stop reaching later rows.
  it('a heal on a tokenized step propagates to later batch rows via step aliasing', () => {
    const master: Workflow = {
      name: 'course task',
      values: { course_url: 'https://lms.example/courses/1' },
      steps: [
        { action: 'navigate', selector: '#course-link', value: '{{course_url}}' },
        { action: 'fill', selector: '#score', param: 'score' },
      ],
    };
    const tokenResolved = resolveTokens(master);
    const row1 = bindWorkflow(tokenResolved, { score: '85' });
    row1.steps[0].selector = '#healed-link'; // replay's persistHeal mutates the shared step in place
    row1.steps[0].candidates = ['#course-link'];

    const row2 = bindWorkflow(tokenResolved, { score: '90' });

    expect(row2.steps[0].selector).toBe('#healed-link');
    expect(row2.steps[0].candidates).toEqual(['#course-link']);
    expect(row2.steps[0].value).toBe('https://lms.example/courses/1');
    expect(row2.steps[1].value).toBe('90');
    expect(master.steps[0].value).toBe('{{course_url}}');
    expect(master.steps[0].selector).toBe('#course-link');
  });
});

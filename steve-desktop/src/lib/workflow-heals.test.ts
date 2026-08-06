import { describe, expect, it } from 'vitest';
import { resolveTokens } from './teach-tokens';
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
});

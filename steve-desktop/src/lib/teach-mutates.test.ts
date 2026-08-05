import { describe, it, expect } from 'vitest';
import { deriveMutates, stepMutates } from './teach-mutates';
import type { WorkflowAction, WorkflowStep } from './types/site-profile';

const ALL_ACTIONS: WorkflowAction[] = ['click', 'fill', 'select', 'wait-for', 'answer-quiz', 'navigate', 'keyboard', 'scroll'];
const EXPECTED: Record<WorkflowAction, boolean> = {
  click: true,
  fill: true,
  select: true,
  navigate: true,
  keyboard: true,
  'answer-quiz': true,
  'wait-for': false,
  scroll: false,
};

describe('deriveMutates', () => {
  for (const action of ALL_ACTIONS) {
    it(`${action} -> ${EXPECTED[action]}`, () => {
      expect(deriveMutates(action)).toBe(EXPECTED[action]);
    });
  }
});

describe('stepMutates', () => {
  it('falls back to the derived default when no explicit override is set', () => {
    expect(stepMutates({ action: 'click', selector: '#go' })).toBe(true);
    expect(stepMutates({ action: 'wait-for', selector: '#go' })).toBe(false);
  });

  it('an explicit `mutates: false` overrides a mutating action', () => {
    const s: WorkflowStep = { action: 'click', selector: '#go', mutates: false };
    expect(stepMutates(s)).toBe(false);
  });

  it('an explicit `mutates: true` overrides a read-only action', () => {
    const s: WorkflowStep = { action: 'scroll', mutates: true };
    expect(stepMutates(s)).toBe(true);
  });

  it('a step recorded before `mutates` existed simply derives it (additive)', () => {
    const s = { action: 'fill', selector: '#n', value: 'x' } as WorkflowStep;
    expect('mutates' in s).toBe(false);
    expect(stepMutates(s)).toBe(true);
  });
});

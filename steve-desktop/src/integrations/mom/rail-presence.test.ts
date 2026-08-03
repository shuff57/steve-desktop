import { describe, it, expect } from 'vitest';
import { railStatusFor } from './rail-presence';

describe('railStatusFor', () => {
  const base = { planning: false, revising: false, writing: false, failed: false, finished: false };

  it('is idle when nothing is happening', () => {
    expect(railStatusFor(base)).toEqual({ status: 'idle', text: '' });
  });

  it('thinks while planning and executes while writing', () => {
    expect(railStatusFor({ ...base, planning: true }).status).toBe('thinking');
    expect(railStatusFor({ ...base, writing: true }).status).toBe('executing');
  });

  it('prefers the live step line to the generic writing text', () => {
    expect(railStatusFor({ ...base, writing: true, slug: 'q1', lastStep: 'Repairing attempt 2' }).text).toBe(
      'Repairing attempt 2',
    );
    expect(railStatusFor({ ...base, writing: true, slug: 'q1' }).text).toBe('Writing q1…');
  });

  it('names what it is revising', () => {
    expect(railStatusFor({ ...base, revising: true, label: 'stats/q7' }).text).toBe('Revising stats/q7…');
  });

  it('reports failure ahead of completion', () => {
    // A run that failed after producing a file must not read as Done.
    expect(railStatusFor({ ...base, failed: true, finished: true }).status).toBe('error');
  });

  it('says what a finished plan produced instead of falling back to Ready', () => {
    // A plan lands no file, so `finished` never sets and the rail snapped straight back
    // to "Ready" the moment a 4-minute plan succeeded — throwing away the one number
    // worth reading. Observed live planning 1.4.
    expect(railStatusFor({ ...base, plannedCount: 15 })).toEqual({
      status: 'completed',
      text: 'Planned 15 questions',
    });
    expect(railStatusFor({ ...base, plannedCount: 1 }).text).toBe('Planned 1 question');
  });

  it('still shows the live phase while a plan is running', () => {
    // plannedCount from a PREVIOUS plan must not mask the run in flight.
    expect(railStatusFor({ ...base, planning: true, plannedCount: 15 }).status).toBe('thinking');
  });

  it('does not treat a zero-question plan as nothing', () => {
    // 0 is a result — the planner ran and produced nothing, which is worth seeing.
    expect(railStatusFor({ ...base, plannedCount: 0 }).text).toBe('Planned 0 questions');
  });
});

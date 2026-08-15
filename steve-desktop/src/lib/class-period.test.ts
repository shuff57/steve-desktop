import { describe, expect, it } from 'vitest';
import {
  isInWindow,
  judgeAttendance,
  periodWindow,
  proposeAbsences,
  type ClassPeriod,
} from './class-period';

const P3: ClassPeriod = { name: 'Period 3', start: '10:15', end: '11:10' };
const DAY = new Date(2026, 7, 14); // 14 Aug 2026, local

describe('periodWindow', () => {
  it('builds the window in local wall-clock time', () => {
    const w = periodWindow(P3, DAY, 0);
    expect(w.start.getHours()).toBe(10);
    expect(w.start.getMinutes()).toBe(15);
    expect(w.end.getHours()).toBe(11);
    expect(w.end.getMinutes()).toBe(10);
    expect(w.start.getDate()).toBe(14);
  });

  it('applies grace to both ends', () => {
    const w = periodWindow(P3, DAY, 5);
    expect(w.start.getHours()).toBe(10);
    expect(w.start.getMinutes()).toBe(10);
    expect(w.end.getMinutes()).toBe(15);
  });

  it('rejects a period that ends before it starts', () => {
    expect(() => periodWindow({ name: 'bad', start: '11:00', end: '10:00' }, DAY)).toThrow(/before it starts/);
  });

  it('rejects times it cannot read rather than guessing one', () => {
    expect(() => periodWindow({ name: 'bad', start: '10.15', end: '11:10' }, DAY)).toThrow(/HH:MM/);
    expect(() => periodWindow({ name: 'bad', start: '25:00', end: '26:00' }, DAY)).toThrow(/not a real time/);
  });
});

describe('isInWindow', () => {
  const w = periodWindow(P3, DAY, 0);

  it('includes both boundaries', () => {
    expect(isInWindow(new Date(2026, 7, 14, 10, 15), w)).toBe(true);
    expect(isInWindow(new Date(2026, 7, 14, 11, 10), w)).toBe(true);
  });

  it('excludes activity on the same clock time a different day', () => {
    expect(isInWindow(new Date(2026, 7, 13, 10, 30), w)).toBe(false);
    expect(isInWindow(new Date(2026, 7, 15, 10, 30), w)).toBe(false);
  });

  it('never counts an unreadable timestamp as inside', () => {
    expect(isInWindow(new Date('nonsense'), w)).toBe(false);
  });
});

describe('judgeAttendance / proposeAbsences', () => {
  const w = periodWindow(P3, DAY, 5);

  it('separates in-window work from work done outside it', () => {
    const [v] = judgeAttendance(
      [{ student: '⟦D1⟧', timestamps: [new Date(2026, 7, 13, 23, 40), new Date(2026, 7, 14, 10, 32)] }],
      w,
    );
    expect(v.active).toBe(true);
    expect(v.inWindow).toHaveLength(1);
    expect(v.outsideWindow).toHaveLength(1);
  });

  // The whole point of the workflow: a student who did plenty of work, but none of it while class
  // was happening, is exactly who the teacher is looking for.
  it('marks a student with only out-of-window activity as not active', () => {
    const [v] = judgeAttendance([{ student: '⟦D2⟧', timestamps: [new Date(2026, 7, 13, 23, 40)] }], w);
    expect(v.active).toBe(false);
    expect(v.outsideWindow).toHaveLength(1);
  });

  // Absence is the destructive direction. A scraper that started returning garbage timestamps
  // would otherwise read as an entire class being absent.
  it('routes unreadable timestamps to unsure, never to absent', () => {
    const verdicts = judgeAttendance(
      [
        { student: '⟦D1⟧', timestamps: [new Date(2026, 7, 14, 10, 32)] },
        { student: '⟦D2⟧', timestamps: [] },
        { student: '⟦D3⟧', timestamps: [new Date('nonsense')] },
      ],
      w,
    );
    const { absent, present, unsure } = proposeAbsences(verdicts);
    expect(present.map((v) => v.student)).toEqual(['⟦D1⟧']);
    expect(absent.map((v) => v.student)).toEqual(['⟦D2⟧']);
    expect(unsure.map((v) => v.student)).toEqual(['⟦D3⟧']);
  });

  it('reports in-window activity most recent first', () => {
    const [v] = judgeAttendance(
      [
        {
          student: '⟦D1⟧',
          timestamps: [new Date(2026, 7, 14, 10, 20), new Date(2026, 7, 14, 11, 0), new Date(2026, 7, 14, 10, 45)],
        },
      ],
      w,
    );
    expect(v.inWindow.map((d) => d.getMinutes())).toEqual([0, 45, 20]);
  });
});

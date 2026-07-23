import { describe, expect, it } from 'vitest';
import { describeLeniency, restoreCategoryWeights, rewriteRubric } from './leniency';
import { detectOutliers } from './outliers';
import type { BatchResult } from './batch';

const RUBRIC = [
  '## IQR & Upper Fence [40%]',
  'Clearly states the correct formula AND explicitly shows the calculation.',
  'Demonstrates understanding of the 1.5(IQR) rule.',
  '',
  '## Interpretation [60%]',
  'Identifies the outlier accurately and explains it thoroughly.',
].join('\n');

describe('rewriteRubric', () => {
  it('returns the rubric untouched at centre', () => {
    expect(rewriteRubric(RUBRIC, 50)).toBe(RUBRIC);
  });

  it('softens requirements below centre', () => {
    const out = rewriteRubric(RUBRIC, 0);
    expect(out).toContain('Mentions'); // "Clearly states" softened
    expect(out).toContain('OR'); // AND relaxed
    expect(out).not.toContain('explicitly');
    expect(out).toContain('Recognizes'); // Identifies
  });

  it('tightens requirements above centre', () => {
    const lenient = 'Mentions the formula. Explains the result. Shows understanding of some cases.';
    const out = rewriteRubric(lenient, 100);
    expect(out).toContain('Precisely states');
    expect(out).toContain('Rigorously demonstrates');
    expect(out).toContain('demonstrates mastery');
  });

  it('scales how much it changes with distance from centre', () => {
    const light = rewriteRubric(RUBRIC, 45);
    const heavy = rewriteRubric(RUBRIC, 0);
    const changed = (s: string) => s.split('\n').filter((l, i) => l !== RUBRIC.split('\n')[i]).length;
    expect(changed(light)).toBeLessThan(changed(heavy));
    expect(changed(heavy)).toBeGreaterThan(0);
  });

  it('never rewrites structural lines — they carry categories and weights', () => {
    const out = rewriteRubric(RUBRIC, 0);
    expect(out).toContain('## IQR & Upper Fence [40%]');
    expect(out).toContain('## Interpretation [60%]');
  });

  it('is deterministic, so a grading run can be reproduced', () => {
    expect(rewriteRubric(RUBRIC, 20)).toBe(rewriteRubric(RUBRIC, 20));
  });

  it('tidies the double spaces left by removed words', () => {
    expect(rewriteRubric('States explicitly the value.', 0)).not.toMatch(/\s{2,}/);
  });

  it('handles empty input', () => {
    expect(rewriteRubric('', 0)).toBe('');
  });
});

describe('describeLeniency', () => {
  it('describes the slider position the way the UI shows it', () => {
    expect(describeLeniency(50)).toBe('Original');
    expect(describeLeniency(20)).toBe('30% more lenient');
    expect(describeLeniency(75)).toBe('25% more strict');
  });
});

describe('restoreCategoryWeights', () => {
  it('puts an altered weight back', () => {
    const original = '## Cat [40%]\nsome text';
    const rewritten = '## Cat [40%]\nall text';
    expect(restoreCategoryWeights(rewritten, original)).toContain('[40%]');
  });

  it('leaves lines without weights alone', () => {
    expect(restoreCategoryWeights('plain line', 'plain line')).toBe('plain line');
  });
});

describe('detectOutliers', () => {
  const mk = (scores: number[]): BatchResult[] =>
    scores.map((score, i) => ({ studentIndex: i, score, feedback: 'x' }));

  it('reports mean and spread', () => {
    const r = detectOutliers(mk([5, 5, 5, 5]));
    expect(r.mean).toBe(5);
    expect(r.stdDev).toBe(0);
  });

  it('flags nothing when a class scores identically', () => {
    // stdDev 0 would divide by zero — every student must not come back "infinitely" deviant.
    expect(detectOutliers(mk([7, 7, 7, 7, 7])).outliers).toEqual([]);
  });

  it('flags a genuine outlier beyond 2 sigma', () => {
    const r = detectOutliers(mk([8, 8, 8, 8, 8, 8, 8, 8, 8, 0]));
    expect(r.outliers.map((o) => o.studentIndex)).toEqual([9]);
  });

  it('does not flag ordinary spread', () => {
    // 1 sigma would flag roughly a third of these; 2 sigma should flag none.
    expect(detectOutliers(mk([6, 7, 7, 8, 8, 8, 9, 9, 10])).outliers).toEqual([]);
  });

  it('sorts the most deviant first', () => {
    const r = detectOutliers(mk([5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 0, 10]));
    const devs = r.outliers.map((o) => o.deviation);
    expect(devs).toEqual([...devs].sort((a, b) => b - a));
  });

  it('handles an empty batch', () => {
    expect(detectOutliers([])).toEqual({ mean: 0, stdDev: 0, outliers: [] });
  });
});

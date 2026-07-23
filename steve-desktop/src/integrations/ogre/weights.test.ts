/**
 * Category weighting. The thing worth protecting is that a half-configured split never
 * reaches the grader: weights summing to anything but 100 rescale the whole class, and
 * the failure looks like harsh marking rather than a misconfiguration.
 */
import { describe, expect, it } from 'vitest';
import {
  autoFillWeights,
  clearWeights,
  equalizeWeights,
  hasWeights,
  validateWeights,
  weightFieldsFor,
} from './weights';
import { parseBatchResponse } from './batch';
import type { Rubric, RubricChecklistItem } from './grading';

const items: RubricChecklistItem[] = [
  { category: 'IQR & Upper Fence', points: 12, items: ['Calculate the IQR.'] },
  { category: 'Outlier Classification', points: 8, items: ['Compare max to fence.'] },
];

describe('validateWeights', () => {
  it('accepts a split that totals 100', () => {
    const r = validateWeights([
      { ...items[0]!, categoryWeight: 60 },
      { ...items[1]!, categoryWeight: 40 },
    ]);
    expect(r).toEqual({ valid: true, sum: 100, error: null });
  });

  it('rejects a partial split and says what it totals', () => {
    const r = validateWeights([
      { ...items[0]!, categoryWeight: 60 },
      { ...items[1]!, categoryWeight: 20 },
    ]);
    expect(r.valid).toBe(false);
    expect(r.sum).toBe(80);
    expect(r.error).toMatch(/80%, must be 100%/);
  });

  it('tolerates a rounding remainder but not a real gap', () => {
    const near = (w: number) => validateWeights([{ ...items[0]!, categoryWeight: w }]);
    expect(near(99.7).valid).toBe(true);
    expect(near(100.4).valid).toBe(true);
    expect(near(99).valid).toBe(false);
  });

  it('treats an empty rubric as valid — there is nothing to weight', () => {
    expect(validateWeights([]).valid).toBe(true);
  });
});

describe('filling weights', () => {
  it('splits evenly and lands exactly on 100 despite the rounding', () => {
    const three = [...items, { category: 'Context', points: 4, items: ['Explain.'] }];
    const out = equalizeWeights(three);
    expect(out.map((c) => c.categoryWeight)).toEqual([33.4, 33.3, 33.3]);
    expect(validateWeights(out).sum).toBe(100);
  });

  it('weights in proportion to declared points', () => {
    // 12 and 8 of 20 — the question's own split is usually the intended weighting.
    const out = autoFillWeights(items);
    expect(out.map((c) => c.categoryWeight)).toEqual([60, 40]);
    expect(validateWeights(out).valid).toBe(true);
  });

  it('falls back to an even split when no category declares points', () => {
    const bare = items.map(({ points: _p, ...c }) => c);
    expect(autoFillWeights(bare).map((c) => c.categoryWeight)).toEqual([50, 50]);
  });

  it('gives the remainder to the last category so an odd split still totals 100', () => {
    const odd = [
      { category: 'A', points: 1, items: ['x'] },
      { category: 'B', points: 1, items: ['x'] },
      { category: 'C', points: 1, items: ['x'] },
    ];
    expect(validateWeights(autoFillWeights(odd)).sum).toBe(100);
  });

  it('round-trips through clear', () => {
    const filled = autoFillWeights(items);
    expect(hasWeights(filled)).toBe(true);
    const cleared = clearWeights(filled);
    expect(hasWeights(cleared)).toBe(false);
    expect(cleared[0]).not.toHaveProperty('categoryWeight');
  });
});

describe('weightFieldsFor', () => {
  const rubricWith = (list: RubricChecklistItem[]): Rubric => ({ maxScore: 20, checklistItems: list });

  it('projects a valid split onto the fields the grading path reads', () => {
    const out = weightFieldsFor(rubricWith(autoFillWeights(items)));
    expect(out.categoryWeights).toEqual({ 'IQR & Upper Fence': 60, 'Outlier Classification': 40 });
    expect(out.weightMode).toBe('category');
    // 10, not the category's own points: the prompt asks the model to score each category
    // 0-10, so dividing by 12 here would silently cap that category's contribution.
    expect(out.categoryMaxPoints).toEqual({ 'IQR & Upper Fence': 10, 'Outlier Classification': 10 });
  });

  it('grades unweighted rather than on a broken split', () => {
    const half = [
      { ...items[0]!, categoryWeight: 60 },
      { ...items[1]!, categoryWeight: 20 },
    ];
    expect(weightFieldsFor(rubricWith(half))).toEqual({});
    expect(weightFieldsFor(rubricWith(items))).toEqual({}); // no weights set at all
  });

  it('actually changes the score the grader computes', () => {
    // Same per-category marks, different weighting: 10/10 on the heavy category and 5/10
    // on the light one is 82% at 60/40, but only 75% at an even split.
    const reply = JSON.stringify([
      {
        studentIndex: 0,
        score: 7,
        feedback: '<p>Hi A,</p><p>ok</p>',
        criterion_scores: { 'IQR & Upper Fence': 10, 'Outlier Classification': 5 },
      },
    ]);
    const students = [{ index: 0, name: 'A', response: 'x' }];

    const weighted = weightFieldsFor(rubricWith(autoFillWeights(items)));
    const skewed = parseBatchResponse(reply, students, 20, weighted.categoryWeights, weighted.categoryMaxPoints);
    expect(skewed[0]!.score).toBe(16); // (1.0*0.6 + 0.5*0.4) * 20 = 16

    const even = weightFieldsFor(rubricWith(equalizeWeights(items)));
    const flat = parseBatchResponse(reply, students, 20, even.categoryWeights, even.categoryMaxPoints);
    expect(flat[0]!.score).toBe(15); // (1.0*0.5 + 0.5*0.5) * 20 = 15
  });
});

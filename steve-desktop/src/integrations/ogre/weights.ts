/**
 * Category weighting for a rubric.
 *
 * Ported from `ogre-desktop/src/lib/rubric-utils.ts` (validateWeights, equalizeWeights,
 * autoFillWeights), reshaped to steve's rubric: O.G.R.E carried a flat list of criterion
 * rows each repeating its category, while here `checklistItems` is already one entry per
 * category. That removes the whole "take the first row of each group" dance — the group
 * IS the entry.
 *
 * What weights actually do: `buildBatchPrompt` tells the model to score each category
 * 0-10 on its own merits, and `validateBatchResults` combines them as
 * `sum(catScore / catMax * weight%) * maxScore`. So a weight is the share of the final
 * grade a category carries, independent of how many points it nominally lists.
 *
 * Weights must total 100%. They are a proportional split; a set summing to 80 silently
 * grades the class out of 80% of the rubric, which looks like harsh marking rather than
 * a configuration error — hence `validateWeights` and the grade button that respects it.
 */
import type { Rubric, RubricChecklistItem } from './grading';

/** Sums within half a point of 100 pass — the display rounds to one decimal. */
const TOLERANCE = 0.5;

export interface WeightValidation {
  valid: boolean;
  sum: number;
  error: string | null;
}

export function validateWeights(items: RubricChecklistItem[]): WeightValidation {
  // No categories means nothing to weight; grading falls back to the plain 0-10 path.
  if (items.length === 0) return { valid: true, sum: 0, error: null };

  const sum = items.reduce((acc, c) => acc + (c.categoryWeight ?? 0), 0);
  const rounded = Math.round(sum * 10) / 10;
  if (Math.abs(sum - 100) <= TOLERANCE) return { valid: true, sum: rounded, error: null };
  return {
    valid: false,
    sum: rounded,
    error: `Category weights sum to ${rounded}%, must be 100%.`,
  };
}

/** Even split. The first category absorbs the rounding remainder so the total is exact. */
export function equalizeWeights(items: RubricChecklistItem[]): RubricChecklistItem[] {
  if (items.length === 0) return items;
  const base = Math.floor((100 / items.length) * 10) / 10;
  const remainder = Math.round((100 - base * items.length) * 10) / 10;
  return items.map((c, i) => ({ ...c, categoryWeight: i === 0 ? base + remainder : base }));
}

/**
 * Weight each category in proportion to the points it already declares — usually what an
 * imported rubric wants, since the question's own point split IS the intended weighting.
 * Falls back to an even split when no category declares points.
 */
export function autoFillWeights(items: RubricChecklistItem[]): RubricChecklistItem[] {
  if (items.length === 0) return items;
  const total = items.reduce((acc, c) => acc + (c.points ?? 0), 0);
  if (total === 0) return equalizeWeights(items);

  let allocated = 0;
  return items.map((c, i) => {
    // Last one takes the remainder, so rounding can never leave the total at 99.9.
    if (i === items.length - 1) {
      return { ...c, categoryWeight: Math.round((100 - allocated) * 10) / 10 };
    }
    const w = Math.round(((c.points ?? 0) / total) * 1000) / 10;
    allocated += w;
    return { ...c, categoryWeight: w };
  });
}

/** Clear every weight, returning the rubric to unweighted grading. */
export function clearWeights(items: RubricChecklistItem[]): RubricChecklistItem[] {
  return items.map(({ categoryWeight: _drop, ...rest }) => rest);
}

export function hasWeights(items: RubricChecklistItem[]): boolean {
  return items.some((c) => (c.categoryWeight ?? 0) !== 0);
}

/**
 * Project the per-category weights onto the two flat maps the grading path reads.
 *
 * `categoryMaxPoints` defaults each category to 10 because that is the scale the prompt
 * asks the model to score each category on — NOT the category's own point value. Using
 * `points` here would divide a 4-point category's 0-10 score by 4 and quietly cap it.
 *
 * Returns `{}` when weights are absent or invalid, so an unfinished configuration grades
 * unweighted rather than on a broken split.
 */
export function weightFieldsFor(
  rubric: Rubric,
): Pick<Rubric, 'categoryWeights' | 'categoryMaxPoints' | 'weightMode'> {
  const items = rubric.checklistItems ?? [];
  if (!hasWeights(items) || !validateWeights(items).valid) return {};

  const categoryWeights: Record<string, number> = {};
  const categoryMaxPoints: Record<string, number> = {};
  for (const c of items) {
    const key = c.category || 'General';
    if (categoryWeights[key] != null) continue; // first entry wins on a duplicate name
    categoryWeights[key] = c.categoryWeight ?? 0;
    categoryMaxPoints[key] = 10;
  }
  return { categoryWeights, categoryMaxPoints, weightMode: 'category' };
}

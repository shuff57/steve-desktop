# Describing Distributions

Auto-graded questions for Wk3 introductory-stats topics: computing mean / median / IQR / range from raw data, comparing two distributions by summary statistics, and choosing a resistant measure for skewed data.

Created 2026-05-19 as part of the Wk1-5 Group Test batch (`books/introduction-to-stats/college/group/wk1-5-group.{json,md}`).

## Files

| File | Topic | Type | qid |
|---|---|---|---|
| `q1-mean-median-iqr-from-data.php` | 4-part compute on a sorted n=9 data set | multipart (numfunc x4) | TBD |
| `q2-compare-two-distributions.php` | Larger center, larger SD, best verbal comparison | multipart (choices x3) | TBD |
| `q3-resistant-measure-choice.php` | Skew → resistant pair (median+IQR vs mean+SD) + reason | multipart (choices x2) | TBD |

## Conventions

- All scenarios pre-compute summary stats in the case array — no on-the-fly sorting in CC (avoids blocked `array_slice`).
- Numeric tolerances: `reltolerance = 0.02`, `abstolerance = 0.05-0.5` depending on quantity scale.
- Choice indices are 0-indexed and match the per-part `$choices[i]` array.

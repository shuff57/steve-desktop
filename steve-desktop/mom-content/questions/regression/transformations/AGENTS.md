# Transformations Questions — Log and Power Transformations for Regression

**Parent:** `../AGENTS.md`
**Files:** 3 autograded questions on transformations for skewed data and nonlinearity

## OVERVIEW

These items test whether a student can choose and interpret the right transformation to fix skewed residuals or a curved scatterplot. Covers: recognizing when a log (or sqrt) transform is warranted, identifying power relationships from a log-log plot, and interpreting the slope of a log-linear model as a multiplicative (percent) effect. Every question is a `multipart` with 2 parts mixing `choices` and `number` / `calculated`.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-log-transform-skew.php` | 2 | choices × 2 | Right-skewed residuals: pick log transform, confirm improvement |
| `q2-transform-linearity.php` | 2 | choices, number | Log-log linearity: identify power relationship, recover exponent from slope |
| `q3-interpret-log-slope.php` | 2 | calculated × 2 | log(y) = a + bx: compute multiplier (e^b) and approximate percent increase |

## CONVENTIONS

1. Each question uses `jointrandfrom` with 3+ parallel arrays to randomize the scenario (variable names, contexts, and precomputed numeric answers). Assign each slot to a named scalar before interpolating in QT.
2. Numeric answers use `$reltolerance[i] = 0.01` (or `0.02` for longer rounding chains).
3. For choice parts, use `$displayformat[i] = "select"` for short dropdown-style options and `$noshuffle[i] = "all"` when order must be fixed.
4. Decision text is precomputed as a scalar in CC (`$transform_name = "log"`, `$useful_text = "Yes, the transform improved the fit"`). Never drop a raw choice index into QT or prose.
5. `$solutionguide` is a single-quoted collapsible `<details>` block matching the family style.
6. Em dashes are forbidden. Use commas, semicolons, or colons.

## ADDING A NEW TRANSFORMATIONS QUESTION

1. Copy the closest sibling: `q1-log-transform-skew.php` for recognizing skew, `q2-transform-linearity.php` for identifying the relationship type, `q3-interpret-log-slope.php` for interpreting a log-model slope.
2. When randomizing a log slope, keep the value in a range where e^slope is a recognizable multiplier (e.g. slope between 0.01 and 0.10 gives 1-10% increases). Avoid slopes near 0 (boring) or above 0.5 (unrealistic for most contexts).
3. Precompute e^slope and (e^slope - 1)*100 in CC, not in QT. IMathAS has no built-in `exp()` macro; use `$multiplier = 2.718281828^$slope` and round appropriately.
4. For q2, keep the exponent b as a small integer (2, 3) or simple fraction (0.5) so the power relationship is recognizable and the numeric answer is clean.
5. Verify across several seeds in MOM preview.

## GOTCHAS

- IMathAS has no `exp()` function. Use `2.718281828^$slope` (Euler's number to sufficient precision for 3-4 decimal places).
- Do not use `pow()` — it is blocked. Use `^` operator.
- For percent-increase parts, the rounding chain: e^slope → subtract 1 → multiply 100 → round. Set `$reltolerance = 0.02` to accommodate different rounding paths.
- Avoid slopes that make e^slope land near 1.0 (slope near 0) because the percent increase rounds to 0%, which is confusing pedagogically.
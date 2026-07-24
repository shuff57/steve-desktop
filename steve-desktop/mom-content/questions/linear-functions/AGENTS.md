# Linear Functions Questions — Slope, Modeling, Interpretation, and Residuals

**Parent:** `../../AGENTS.md`
**Files:** 4 autograded questions covering slope/intercept from points, verbal graph reading, real-world linear models, and best-fit line residuals

## OVERVIEW

Linear functions questions span the core skills: computing slope and equation from two points, reading a verbal linear scenario, building a y = mx + b model from context, and interpreting residuals for a candidate regression line. All items are auto-graded multipart questions with randomized values or context scenarios.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-slope-from-two-points.php` | 3 | number, number, choices (select) | Slope m, y-intercept b, and equation of a line from two points; 10 parallel-array scenarios |
| `q2-linear-graph-interpretation.php` | 3 | number, number, number | Y-intercept, slope, and function value from a verbal description; 3 context types x 3 sub-scenarios |
| `q3-modeling-with-linear.php` | 3 | number, number, number | Slope (rate), y-intercept (initial value), and prediction from gym/taxi/tank contexts |
| `q4-best-fit-line-concept.php` | 3 | number, number, choices (select) | Predicted yhat, residual, and sign interpretation for a candidate regression line; 6 if-block scenarios |

## CONVENTIONS

1. **Auto-graded only.** `number` for numeric parts, `choices` with `displayformat="select"` for equation/sign selection.
2. **Parallel arrays for scenarios with clean arithmetic.** Precompute answers by hand; use `jointrandfrom(...)` for correlated picks. When array-index lookup via `$arr[$var]` is needed after an `if` block, extract values with explicit `if ($ci == n)` guards (IMathAS does not support variable-index array access reliably).
3. **Context variety.** `q2` and `q3` randomize across meaningfully different real-world settings (temperature/distance/account, gym/taxi/tank), not just number swaps.
4. **If-block scenario pattern.** `q4` uses a default-then-`if ($si == n)` pattern to avoid `$arr[$var]` index lookups entirely. Copy this pattern for new scenarios with 6+ cases.
5. **Solution guide.** Each file builds `$solutionguide` as a `.sol-wrap details/summary` collapsible with step-by-step arithmetic. Keep AsciiMath literals inside backticks in solution strings.
6. **Part label chips.** `display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700`.

## ADDING A NEW LINEAR FUNCTIONS QUESTION

1. Pick the file closest to the answer shape: `q1` for two-point scenarios, `q2`/`q3` for verbal context, `q4` for regression/residual.
2. Construct all scenarios from solution first (pick integer slope and intercept, derive data points). This keeps arithmetic clean.
3. Add numbered subsection comments in CC: `/* ---------- 1. ... ---------- */`, `/* ---------- 2. ... ---------- */`, etc.
4. Place `loadlibrary("stats")` and `$anstypes` immediately after `// === COMMON CONTROL ===`.
5. Set question type to **Multipart** in MOM.

## GOTCHAS

- `q2` indexes sub-scenarios via `$si = rand(0,2)` within each context. All 9 sub-scenarios are precomputed in parallel arrays. Never access `$arr[$si]` inside a string; pull values into scalar variables with `if` blocks first.
- `q3` uses separate `jointrandfrom` calls for each context (gym/taxi/tank) regardless of which context is active. This means unused contexts are also randomized -- that is intentional and safe.
- `q4` residuals can be negative; `$abstolerance[1] = 0.01` is set but the answer is exact integer. Keep tolerance narrow.
- Display strings for equations use `$b_str = "+ " . $b` pattern. Always handle `$b == 0` to suppress the intercept term.

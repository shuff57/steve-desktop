# Exponential and Logarithm Questions

**Parent:** `../../AGENTS.md`
**Files:** 3 auto-graded questions covering exponential evaluation, log evaluation/conversion, and exponential growth/decay models

## OVERVIEW

These questions were drafted for a College Algebra-style course. The deployed Applied Finite Math course does not use them -- they are kept here for future-semester use. All three are auto-graded multipart questions. The topics overlap with the test-b FRQs in `questions/frq/test-b/`, which cover interpretation and comparison of these same models at the essay level.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-exponential-evaluation.php` | 3 | number, number, number | Evaluate f(x) = a * b^x at x = 0, 1, 2 for randomized (a, b) pairs |
| `q2-log-evaluation-and-conversion.php` | 3 | number, choices, choices | Evaluate log_b(x), convert to exponential form, convert back to log form |
| `q3-exp-model-growth-decay.php` | 3 | number, choices, number | Evaluate A(t) at a given t, identify growth vs. decay, find target time |

## CONVENTIONS

1. **Auto-graded only.** No essay or FRQ parts.
2. **Exponential notation.** Use `^` for exponentiation in display strings and solution guides (e.g., `'b^t'` not `pow(b, t)`). `pow()` is a blocked function.
3. **Randomization via `jointrandfrom(...)`.** All scenarios and precomputed answers live in parallel arrays. The graded answer is always pulled from the array, never recomputed in Question Text.
4. **Select dropdowns with `$noshuffle = "all"`.** All parts using `$displayformat[n] = "select"` with index-based answers must also set `$noshuffle[n] = "all"`. Without it, MOM shuffles choices and the answer index becomes unreliable.
5. **Solution guide pattern.** Each file builds a `$solutionguide` string in Common Control using the `.sol-wrap` details/summary collapsible. Referenced in the Answer section after `///`.
6. **Card-per-part layout.** Question text uses chip labels (a., b., c.) and `<span style="margin-left:8px;">$answerbox[n]</span>` inline.
7. **`loadlibrary("stats")` at top of Common Control.** Required by house style even when stats functions are not used.
8. **Logarithm display.** Use the notation `log_b(x)` in backtick AsciiMath blocks (renders nicely in MOM). For natural log use `ln(x)`. Base-b logs in code use `log($x, $base)` if evaluation is needed (MOM supports this), but all graded answers in these files are precomputed and stored in arrays.

## ADDING A NEW QUESTION

1. Choose 3-5 scenario rows that keep the arithmetic clean (integer inputs and outputs). Precompute every graded answer before writing any code.
2. Build parallel arrays and use `jointrandfrom(...)` to select one row. Assign picked values to named scalar variables.
3. For choices parts: put the correct answer first (index 0) in the `$questions[n]` array and set `$answer[n] = 0`, then add `$noshuffle[n] = "all"`. Or use a variable answer index with a precomputed correct-index array.
4. Build `$solutionguide` as a string variable in Common Control with the `.sol-wrap` collapsible. Keep the solution step-by-step, showing each substitution.
5. Set question type to **Multipart** in the MOM editor.

## GOTCHAS

- `$answer[n] = 0` with shuffled choices grades wrong. Always pair `$displayformat[n] = "select"` with `$noshuffle[n] = "all"`.
- `pow()` is blocked. Use `$x^$n` for integer exponentiation. Use `$x^0.5` for square roots. Use `log($x, $b)` for base-b logarithms.
- `exp()` is blocked. Use `2.71828^$x` for natural exponential, or pre-bake values into the parallel arrays.
- `number_format()` is blocked. If you need formatted decimals, precompute the display string as a literal in the scenario array (e.g., `"1340.10"`).
- When the base `b` is a fraction like 1/2, store both a display string (`"1/2"`) and a decimal value (`0.5`) as separate array columns indexed together. Use the decimal for arithmetic checks in the solution and the display string for student-facing text.

# Chi-Square Questions — Goodness of Fit and Independence

**Parent:** `../AGENTS.md`
**Files:** 7 auto-graded chi-square questions covering test selection, GoF expected counts, the GoF test statistic + decision, Independence expected counts from a 2×2 table, choose-which-Ch6-test, GoF full workflow with written conclusion, and independence-interpretation in context.

## OVERVIEW

These questions support the chi-square chapter of an introductory stats course. They are designed for randomized reuse on graded quizzes and group tests where students must (1) pick the right chi-square test, (2) compute expected counts under the null, (3) compute the χ² statistic and reach a decision at α = 0.05, and (4) compute expected counts from a 2×2 contingency table.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-when-gof-vs-independence.php` | 1 | choices | Pick the right chi-square test (GoF / Independence / Neither) from 6 randomized scenarios |
| `q2-gof-expected-counts.php` | 3 | numfunc × 3 | Compute `E_i = n · p_i` for three categories under a claimed distribution (5 scenarios) |
| `q3-gof-test-statistic.php` | 3 | numfunc, numfunc, choices | Compute χ², identify df, decide reject/fail-to-reject at α = 0.05 (4 scenarios; 2 reject + 2 FtR) |
| `q4-independence-expected.php` | 3 | numfunc × 3 | Compute three expected cells `E_ij = (R_i · C_j) / n` from a 2×2 observed table (3 scenarios; all integer expecteds) |
| `q5-choose-which-test.php` | 4 | choices × 4 | Pick the right Ch6 test (single-prop / two-prop / GoF / Independence) from 4 randomized scenarios (drawn from 8 via `diffrands`) |
| `q6-gof-full-with-conclusion.php` | 4 | choices × 4 | GoF full workflow: decision, df, reject?, conclusion text — auto-graded multiple-choice drill of the conclusion-writing step |
| `q7-independence-interpretation.php` | 3 | choices × 3 | Interpret a completed independence test in context: decision, reject?, conclusion wording |
| `q9-gof-full-with-conclusion-2.php` | 4 | choices, numfunc, choices, choices | GoF full workflow with written conclusion; fresh contexts (college classes, weekday accidents, banner clicks) for the cumulative wk1-14 test |

## CONVENTIONS

1. **Type picker is Multipart** in MOM, even for single-part questions (matches the convention in `../../probability/AGENTS.md`).
2. **All expected counts are precomputed.** Randomization is via `jointrandfrom(...)` with parallel arrays for context, marginals, observed cells, and the matching expected/χ² answers.
3. **2×2 tables use clean integer expected counts** so students can verify by hand and so tolerance does not have to absorb decimal-place rounding.
4. **Decision MC options** read "Reject H_0" / "Fail to reject H_0" with neutral phrasing — never spoil the verdict in the option order.
5. **Critical values referenced in solutions:** df=3 → 7.815, df=4 → 9.488, df=5 → 11.070, df=6 → 12.592 (α = 0.05).
6. **Tolerances:** χ² uses `reltolerance = 0.02`, df uses `abstolerance = 0.5`, expected-count parts use `abstolerance = 0.05`.
7. **Numeric inputs use `numfunc`** (MathQuill). Students can enter `60`, `60/1`, or `200*0.30` interchangeably — all grade against the precomputed tolerance.

## ADDING A NEW CHI-SQUARE QUESTION

1. Copy `q4-independence-expected.php` for a 2×2 contingency-table problem, or `q2-gof-expected-counts.php` for a one-categorical-variable GoF setup.
2. Add a new scenario by extending the parallel arrays (context, labels, observed cells, marginals, expected counts).
3. Verify integer expected counts by precomputing `(R_i · C_j) / n` for every cell — if any is non-integer, redesign the marginals so `n | R_i · C_j`.
4. For GoF χ² scenarios, precompute the χ² statistic and round to 2 decimals; pick critical values from the table above for the solution narrative.
5. Set the type picker to **Multipart** in MOM and verify the rendered question on `testquestion2.php`.

## GOTCHAS

- For the test-selection MC in `q1`, do not include a "Neither" decoy that is actually correct unless you add a scenario that needs a different test entirely. The current 6 scenarios all map to GoF or Independence.
- For the 2×2 expected-counts question, only ask for 3 of the 4 cells. The fourth is forced by the marginals, so asking for all four is redundant.
- Critical-value-based decisions in `q3` use α = 0.05 implicitly. If you change α, also change the critical value in the solution narrative.
- IMathAS allows `if (...) { ... }` but **not** `else if` with braces — see the conditional usage in `q1-when-gof-vs-independence.php` solutionguide.

# Slope-Inference Questions — CI and Hypothesis Test for the Population Slope

**Parent:** `../AGENTS.md`
**Files:** 6 autograded questions on inference for the regression slope

## OVERVIEW

These items move past the descriptive line and ask what the data say about the **population** slope `beta_1`: building a CI from `b_1 pm t^{star} cdot SE`, running the slope `t`-test, interpreting whether the CI contains 0, picking the right procedure for paired pre/post designs, spotting which LINE condition (Linearity / Independence / Normality / Equal variance) is broken in a residual plot, and a full end-to-end inference workflow that ties conditions, hypotheses, test statistic, CI, and conclusion together. Every question is a single `multipart` with 2-5 parts mixing `choices` and `number`.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-ci-for-slope.php` | 4 | number × 4 | Build a 95% CI for `beta_1` from `b_1`, `SE`, and `t^{star}` (SE, ME, lower, upper) |
| `q2-hypothesis-test-slope.php` | 4 | choices × 3, number | State `H_0` / `H_a`, compute `t = b_1 / SE`, decide at `alpha = 0.05` from the p-value |
| `q3-ci-interpret-slope.php` | 3 | choices × 3 | Given a CI for `beta_1`: contains 0?, what does that mean?, reject `H_0` at `alpha = 0.05`? |
| `q4-paired-vs-regression.php` | 2 | choices × 2 | Pre/post on the SAME subjects: pick paired t over two-sample t / regression with indicator, then justify |
| `q5-line-conditions.php` | 2 | choices × 2 | Diagnose which LINE condition fails from a residual-plot description, then judge whether standard inference is OK |
| `q6-full-regression-workflow.php` | 5 | choices × 3, number × 2 | Full end-to-end: check LINE, state hypotheses, compute t and CI lower bound, decide and conclude |

## CONVENTIONS

1. Pull contexts from the same `$ctx_x` / `$ctx_y` parallel-array shape used by `q1` and `q2` so the wording stays consistent across the family.
2. Pick `(df, t^{star})`, `(target_t, p)`, or `(case, low, high)` triples with `jointrandfrom(...)` so the displayed numbers stay internally consistent across seeds.
3. Every numeric part carries `$reltolerance[i] = 0.01` (sometimes `0.02` when the rounding chain is longer, like CI bounds).
4. For decision-style choices (Reject / Fail to reject, Yes / No), set `$displayformat[i] = "select"` and `$noshuffle[i] = "all"` so the dropdown order matches the worked solution. Long sentence-style choices stay as the default radio list with `$noshuffle[i] = "all"`.
5. Decision text gets precomputed in Common Control as a scalar (`$decision_text = "reject \`H_0\`"`) and interpolated into the solution body; never drop a raw `$answer[i]` index into prose.
6. `$solutionguide` is a single-quoted heredoc-free collapsible `<details>` block (matches `q1` / `q2` exactly). Wrap the body in `\\'Segoe UI\\'` to escape the inner apostrophes.

## ADDING A NEW SLOPE-INFERENCE QUESTION

1. Copy the closest sibling: `q1-ci-for-slope.php` for a numeric-CI computation, `q2-hypothesis-test-slope.php` for an H0/Ha + p-value walk, `q3-ci-interpret-slope.php` for an interpretation-only item, `q4-paired-vs-regression.php` for a "pick the right test" item, `q5-line-conditions.php` for a residual-diagnostic item.
2. Keep `(b_1, SE, t)` consistent: pick `b_1` and `t`, then back out `SE = b_1 / t` (`q14-read-regression-output.php` in `least-squares/` does the same dance) so the printed table reads cleanly.
3. For interpretation parts, build parallel `$cases` / `$ans_idx` / `$why_text` arrays so a single `if / else if / else` block selects both the right `$answer[i]` and the right narrative.
4. Verify in the MOM preview across several seeds; in particular re-check q5 with each of the 4 LINE scenarios to confirm the chosen condition matches the printed plot description.

## GOTCHAS

- A 95% CI matches a two-sided `alpha = 0.05` test; do not let q3's randomization land on a "borderline" CI where the bound is exactly 0 — keep `$lows` and `$highs` away from 0 by at least 0.10.
- For q5, the LINE condition ordering inside `$choices[0]` is locked by `$noshuffle[0] = "all"`. The `$ans_idx` parallel array MUST encode the same ordering (0 = L, 1 = I, 2 = N, 3 = E).
- `jointrandfrom` returns a numerically indexed array; assign each slot to a named scalar (`$xname = $picked[0]`) before interpolation. Do not interpolate `$picked[0]` directly in Question Text.
- Em dashes are forbidden in student-facing text. Use commas, parentheses, or a colon instead.

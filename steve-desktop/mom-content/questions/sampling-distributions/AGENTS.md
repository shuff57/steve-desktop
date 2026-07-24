# Sampling Distributions

Questions for Ch4-style sampling-distribution topics: sampling distribution of `\hat{p}`, sampling distribution of `bar(x)`, and the Central Limit Theorem.

Created 2026-05-12 as part of the Ch4-5 Foundations Practice batch (`books/introduction-to-stats/practice/ch4-5-foundations-practice.{json,md}`). Each file covers a gap or high-miss item identified from the Ch4-5 Individual Test (aid 22035961) and the prior Ch4-5 Practice Test (aid 21979970) on cid 301265.

## Files

| File | Topic | Type | qid |
|---|---|---|---|
| `q1-p-hat-conditions.php` | `\hat{p}` — check `np >= 10` and `n(1-p) >= 10` conditions | multipart (choices x3) | TBD |
| `q2-x-bar-conditions.php` | `bar(x)` — check population-normal OR `n >= 30` (GAP) | multipart (choices x3) | TBD |
| `q3-p-hat-probability.php` | `\hat{p}` — mean, SE, `P(\hat{p} < c)` or `P(\hat{p} > c)` — direction randomized | multipart (number x3) | TBD |
| `q4-x-bar-probability.php` | `bar(x)` — mean, SE, `P(bar(x) < c)` or `P(bar(x) > c)` — direction randomized (GAP) | multipart (number x3) | TBD |
| `q5-clt-single-vs-sample.php` | Compare `P(X < a)` to `P(bar(x) < a)`; choose why the sample mean is more extreme (TOP-miss IND drill) | multipart (number, number, choices) | TBD |
| `q6-clt-when-applies.php` | Multi-select: which CLT statements are true | multans | TBD |

## Common conventions

- All scenarios use `jointrandfrom` for parallel-array randomization across 5-7 contexts.
- All conditions are pre-checked in scenario data (e.g., `q3` ships only with `np >= 10, n(1-p) >= 10` met) unless the question itself is about checking conditions (`q1`, `q2`).
- Standard error formulas: `SE = sqrt(p(1-p)/n)` for `\hat{p}`, `SE = sigma/sqrt(n)` for `bar(x)`.
- Probability computed by standardizing first then calling `normalcdf($z)` (standard-normal CDF). The 4-arg form `normalcdf(mean, sd, lower, upper)` is NOT accepted on this MOM instance — verified 2026-05-12.
- **REQUIRED**: every question that uses `normalcdf`, `invnormalcdf`, `tcdf`, `invtcdf`, or other stats macros MUST start its CC with `loadlibrary("stats")` — without it MOM returns "Eeek.. unallowed macro normalcdf". Pattern: `loadlibrary("stats")\n$anstypes = array(...)\n... $z = ($c - $mu) / $se; $prob_lt = normalcdf($z);`
- Card-per-part HTML layout with blue chip labels matches the house style (see `mom-style-guide`).
- Detailed step-by-step solution lives in `$solutionguide` and is appended to QT via `// === ANSWER ===` block.

## High-miss items targeted

| File | IND avg | Strategy |
|---|---|---|
| `q3` | 71% (Proportion CLT prob < x) | Direction randomized so both `<` and `>` get drilled |
| `q5` | 68% (single vs sample) | New context, direction randomized, explicit "why" multi-choice |
| `q6` | 74% (CLT general) | Conceptual multi-select with common misconceptions as wrong choices |

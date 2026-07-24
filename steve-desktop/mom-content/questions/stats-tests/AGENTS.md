# Stats-Tests Questions — Confidence Intervals & Hypothesis Testing

**Parent:** `../../AGENTS.md`
**Files:** 18 autograded CI / hypothesis-test questions across 5 sub-topics

## OVERVIEW

End-to-end confidence-interval and hypothesis-test items: picking the right test, writing H0 / Ha, computing sample statistics from a randomized dataset, finding the test statistic and p-value, and stating the conclusion. Every question is a single large `multipart` with 8-12 parts mixing `choices`, `number`, and `calculated` answer types. Uses `loadlibrary("stats")` for `mean`, `stdev`, `rrands`, etc.

## QUESTION TYPES

### Confidence Intervals (`confidence-intervals/`) — 4 questions

| File | Focus |
|------|-------|
| `q1-ci-interpretation.php` | What a 95% CI does and does not mean |
| `q2-ci-one-proportion.php` | One-proportion CI |
| `q3-ci-width-factors.php` | How n, s, confidence level affect width |
| `q4-ci-mean-t.php` | One-sample mean CI with t |

### Hypothesis Testing (`hypothesis-testing/`) — 4 questions

| File | Focus |
|------|-------|
| `q5-pvalue-decision.php` | Reject / fail-to-reject from a p-value |
| `q6-null-alt-hypotheses.php` | Write H0 / Ha for a scenario |
| `q7-ht-one-mean.php` | One-sample mean test (t) with computed dataset |
| `q8-ht-one-proportion.php` | One-proportion z-test |

### CI ↔ HT Connection (`ci-ht-connection/`) — 2 questions

| File | Focus |
|------|-------|
| `q9-ht-using-ci-proportion.php` | Decide a test from a proportion CI |
| `q10-ci-to-test-hypothesis.php` | Decide a test from a mean CI |

### Inference for Means (`inference-for-means/`) — 3 questions, with `manifest.json`

| File | Focus |
|------|-------|
| `q1-sleep-hours.php` | Two-sample means (sleep by job status) |
| `q2-reaction-times.php` | Reaction-time comparison |
| `q3-daily-steps.php` | Daily-steps comparison |

### Two-Sample Inference (`two-sample-inference/`) — 5 questions

| File | Focus |
|------|-------|
| `q11-two-prop-test-cholesterol.php` | Two-proportion z-test |
| `q12-two-prop-test-health-field.php` | Two-proportion z-test, different scenario |
| `q13-two-sample-means-energy-drinks.php` | Two-sample means t-test |
| `q14-interpret-ci-two-means.php` | Interpret a two-sample mean CI |
| `q15-distracted-eating.php` | Distracted-eating two-sample test |

## CONVENTIONS

1. Always `loadlibrary("stats")` at the top — gives `mean`, `stdev`, `variance`, `ttest`, `ztest`, `tcdf`, `normalcdf`, etc.
2. Generate raw data with `rrands(min, max, step, n)` — returns a real-valued sample. Compute `$xbar = mean($data)` and `$s = stdev($data)` from it.
3. Render the dataset to the student with `joinarray($data, ", ")` so the prompt shows comma-separated values.
4. Big `$anstypes` arrays — typically 8-12 slots mixing `choices`, `number`, and (rarely) `calculated`. Keep the order locked so answer-box numbering in Question Text matches.
5. Use `choices` + `$displayformat[i] = "select"` for dropdown-style H0 / Ha and conclusion statements. Set `$noshuffle[i] = "all"` on the hypothesis parts so the wording stays aligned with the scenario.
6. Precompute the decision string (`"Reject the Null"` vs `"Fail to Reject"`) with a `where` clause in Common Control and drop the scalar into the solution text. Do NOT interpolate `$answer[i]` directly into prose — it renders as a digit.
7. Set `$reltolerance = 0.01` on `number` answers for test-stat and p-value parts.

## ADDING A NEW STATS-TEST QUESTION

1. Pick the sub-folder that matches the inference type; copy the closest existing file (`q7-ht-one-mean.php` for one-sample t, `q11-two-prop-test-...` for two-proportion, etc.).
2. Replace the scenario text, group labels, measurement, and unit. Keep the data-generation block (`rrands(...)`) intact.
3. Adjust the `$n1`, `$n2`, and `rrands` ranges so the resulting p-value falls in a pedagogically useful range (don't let every seed be an obvious reject).
4. Keep the full 8-12-part `$anstypes` array — truncating breaks the rendered prompt.
5. Verify in MOM preview across several seeds; confirm the decision string matches the computed p-value under each.

## GOTCHAS

- Variable-index lookup inside Question Text (e.g. `$questions[5][$answer[5]]`) raises "Array to string conversion". Precompute scalar strings in Common Control and interpolate those.
- `$answer[i]` as a raw choice index renders as `0` / `1`, not as "<" / "≥" — always map to a labeled scalar first.
- Datasets drawn from `rrands` can produce degenerate samples (e.g. `stdev = 0`) — clamp the ranges so `$s > 0`.
- `loadlibrary("stats")` must come before any call to `mean`, `stdev`, `tcdf`, etc.

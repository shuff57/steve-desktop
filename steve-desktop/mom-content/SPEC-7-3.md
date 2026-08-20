# SPEC — §7.3 A Population Proportion (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/stats-tests/confidence-intervals/`
(read `q2-ci-one-proportion.php` and `q5-ci-proportion-compute-interpret.php` first — the
proportion pattern — plus `questions/stats-tests/AGENTS.md` and `questions/probability/AGENTS.md`).
Match the family shape: white-card UI, blue-chip part labels, `jointrandfrom`/parallel-array
scenarios with precomputed answers, `numfunc` for numeric parts, `choices` with
`$noshuffle[N] = "all"`, no `essay`, type picker Multipart. Every question that calls a normal
macro starts its COMMON CONTROL with `loadlibrary("stats")`.

**Scope note:** §7.3 is the *confidence interval for a population proportion* — the sample
proportion `p' = x/n`, the error bound `EBP = z_(alpha/2) * sqrt(p'q'/n)`, the interval, the
`np' > 5` and `nq' > 5` condition, the plus-four adjustment (`x + 2` and `n + 4`), and the
sample size formula `n = z^2 p'q' / EBP^2` with `p' = q' = 0.5` as the worst case, rounded up.
The two reuses carry the plain proportion interval and its interpretation; the new files carry
the p'/q' reading, the condition check, the plus-four method, the sample size planning, and the
proportion-vs-mean decision.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/confidence-intervals/q2-ci-one-proportion.php` | one-proportion CI construction |
| 2 | `questions/stats-tests/confidence-intervals/q5-ci-proportion-compute-interpret.php` | CI + interpretation |

New files to author (slots 3–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q21-sample-proportion.php` | 3 | 10 |
| 4 | `q22-proportion-conditions.php` | 2 | 10 |
| 5 | `q23-ebp-and-interval.php` | 3 | 10 |
| 6 | `q24-interpret-the-proportion-interval.php` | 3 | 10 |
| 7 | `q25-plus-four.php` | 3 | 10 |
| 8 | `q26-sample-size-proportion.php` | 2 | 10 |
| 9 | `q27-proportion-vs-mean.php` | 3 | 9 |
| 10 | `pre-frq-grade-a-proportion-interval.php` | 3 | 12 |

Points: 10 + 10 + 10 + 10 + 10 + 10 + 10 + 9 + 9 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your eight files (the confidence-intervals family is clean; the
17 pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a
matching `$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article
before an interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count.

## The dialect rules (non-negotiable)

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**. Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (homework carries no free response).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic).
  Never `number`.
- **`loadlibrary("stats")` at the top of COMMON CONTROL** in every question that calls a normal
  macro — without it MOM rejects the call.
- **The 1-arg forms only:** `normalcdf(z)` and `invnormalcdf(p)`. **The 4-arg form is NOT
  accepted on this MOM instance** — standardize first, then call the 1-arg form.
- **Tolerances match the family:** `abstolerance = 0.005` for proportions and error bounds,
  `abstolerance = 0.5` for inverse-normal values.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.
- **Critical values are precomputed constants** (1.645, 1.96, 2.326, 2.576, 2.054, 2.17) —
  never call `invnormalcdf` in the answer path when a constant will do.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q21-sample-proportion.php — 3 parts
A survey with `x` successes out of `n` (contexts in threes, clean ratios). Parts: (a) `numfunc`
— `p' = x/n`; (b) `numfunc` — `q' = 1 - p'`; (c) `numfunc` — the number of failures `nq'`.
**Invariant: (a) = x/n exactly, (b) = 1 - p' exactly, (c) = n - x exactly.** The guide: the
sample proportion is the point estimate for the population proportion, and the four numbers
always come in the same order: p', then q', then z, then EBP. Mirrors Try It Now 7.1 step 1,
Example 7.1 step 2, and problem set 7.3.3/7.3.20.

### q22-proportion-conditions.php — 2 parts
A survey with `x` successes out of `n` (contexts in threes, include scenarios that FAIL the
condition so the answer varies). Parts: (a) `numfunc` — `np'`; (b) `numfunc` — `nq'`; (c)
`choices` — may the normal approximation be used (both > 5)? **Invariant: (a) = x, (b) = n - x
exactly, (c) matches the actual check.** The guide: the condition is not a formality — it is
what makes the normal approximation legal in the first place; if you have 200 people and only
two said yes, the interval will not have the coverage it claims. Mirrors Definition 7.3.3's
condition and problem set 7.3.5.

### q23-ebp-and-interval.php — 3 parts
`x`, `n`, `CL` (contexts in threes, `np' > 5` and `nq' > 5`). Parts: (a) `numfunc` — the error
bound `EBP = z_(alpha/2) * sqrt(p'q'/n)`; (b) `numfunc` — the lower endpoint `p' - EBP`; (c)
`numfunc` — the upper endpoint `p' + EBP`. **Invariant: (a) = z*SE exactly, (b) = p' - EBP, (c)
= p' + EBP exactly.** The guide: the formula wants p and q, the population proportions — the
very numbers being estimated — so the sample proportions stand in, and the plus-four method
exists precisely because that substitution introduces error. Mirrors Try It Now 7.1, Example
7.1, Try It Now 7.2, Example 7.2.

### q24-interpret-the-proportion-interval.php — 3 parts
An interval `(lo, hi)` at `CL` for a context (contexts in threes). Parts: (a) `choices` — the
correct interpretation sentence (We estimate with CL% confidence that between lo% and hi% of
[population] [characteristic]); (b) `choices` — what the confidence level means (the method, in
repeated sampling); (c) `choices` — the wrong reading to avoid (a CL% chance the true proportion
is between lo and hi). **Invariant: all three answers are constant across seeds.** The guide:
the interval is about the parameter, not the sample statistic, and the confidence lives in the
procedure. Mirrors Example 7.1's interpretation and problem set 7.3.30.

### q25-plus-four.php — 3 parts
A small survey with `x` successes out of `n` (contexts in threes, `n >= 10` and `CL >= 90%`).
Parts: (a) `numfunc` — the adjusted proportion `(x + 2)/(n + 4)`; (b) `numfunc` — the error
bound from the adjusted values; (c) `choices` — why the adjustment works (adding two fake yeses
and two fake noes drags any extreme sample proportion back toward 0.5, where the normal
approximation behaves best). **Invariant: (a) = (x+2)/(n+4) exactly, (b) = z*sqrt(p'q'/(n+4))
exactly, (c) is constant.** The guide: both substitutions or neither — changing x to x + 2 and
then using the original n inside the square root produces an interval that is neither the
standard one nor the corrected one. Mirrors Try It Now 7.3, Example 7.3, Try It Now 7.4,
Example 7.4, and problem set 7.3.51.

### q26-sample-size-proportion.php — 2 parts
`EBP`, `CL` (contexts in threes, `z^2(0.25)/EBP^2` non-integer so rounding up matters). Parts:
(a) `numfunc` — the raw `n = z^2(0.25)/EBP^2` using the worst-case `p' = q' = 0.5`; (b) `numfunc`
— the sample size rounded UP. **Invariant: (a) is the precomputed raw value, (b) = ceil(a)
exactly.** The guide: `p'q' = 0.25` is the largest the product can ever be, so using it gives
the largest n — the safe direction to be wrong in; and EBP is squared in the denominator, so
halving the margin multiplies the sample size by four. Mirrors Try It Now 7.5, Example 7.5, and
problem set 7.3.1/7.3.34a/7.3.50.

### q27-proportion-vs-mean.php — 3 parts
One scenario per context, three questions about the SAME study (contexts in threes). Parts: (a)
`choices` — which signal says this is a proportion problem (the data are counts of successes and
failures, and there is no mention of a mean or an average); (b) `choices` — which signal says a
mean problem (measurements with a mean); (c) `numfunc` — the sample proportion from the counts.
**Invariant: (a) and (b) are constant, (c) = x/n exactly.** The guide: two signals — the
underlying distribution is binomial (counts of successes and failures, not measurements), and
there is no mention of a mean or an average anywhere in the question. Mirrors the section's
opening and problem set 7.3.18/7.3.19.

### pre-frq-grade-a-proportion-interval.php — 3 parts
The pre-FRQ mirror of `questions/frq/inference-for-proportions/q9-single-proportion-interpreting-results.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Statistical Decision
(4)**, **Conclusion in Context (3)**, **Interpretation of Evidence (3)**. Drop **"Statistical
Decision"** — a student can write a conclusion in context and interpret the evidence without ever
comparing the p-value to the significance level, and the section's own "the three points are the
whole story" is exactly the step a plausible answer skips. This is DIFFERENT from §7.1's
(dropped: Confidence Level Meaning) and §7.2's (dropped: Assessing the Claim) pre-FRQs — the
three pre-FRQs must not teach the same lesson. Not in the used table (2.3 Percentile, 2.4
Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the
Parameters, 5.1 State the Theoretical Value, 5.2 State the Empirical Value, 6.1 State the
Sum's Parameters, 6.2 Apply the Continuity Correction, 7.1 Confidence Level Meaning, 7.2
Assessing the Claim). **Invariant: read each of the four responses against every rubric line —
no response earns a category it is supposed to be missing (category-purity), and every number
inside a response is generated from the same variables as the scenario.** Scope the CSS with a
`.qscope73` class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eight you did not finish, if any.

# SPEC — §7.1 A Single Population Mean using the Normal Distribution (Intro Stats -SH)

Write **ten** IMathAS question files into `mom-content/questions/stats-tests/confidence-intervals/`
(the files already there are the pattern — **read `q4-ci-mean-t.php`, `q5-ci-proportion-compute-interpret.php`
and `q6-ci-mean-t-story.php` before starting**, plus `questions/stats-tests/AGENTS.md` and
`questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part labels,
`jointrandfrom`/parallel-array scenarios with precomputed answers, `numfunc` for numeric parts,
`choices` with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart. Every question that
calls a normal macro starts its COMMON CONTROL with `loadlibrary("stats")`.

**Scope note:** §7.1 is the *confidence interval for a population mean when sigma is known* —
the point estimate `bar(x)`, the error bound `EBM = z_(alpha/2) * sigma/sqrt(n)`, the interval
`(bar(x) - EBM, bar(x) + EBM)`, the critical value `z_(alpha/2)` (1.645/1.96/2.326/2.576), the
interpretation sentence, the two width dials (confidence level and sample size), working
backwards from an interval to EBM and the sample mean, and the sample size formula
`n = z^2 sigma^2 / EBM^2` rounded up. The two reuses carry the t-interval mechanics (which the
z-interval mirrors) and the width factors; the new files carry the z-specific arithmetic, the
backwards recovery, and the sample size planning.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/confidence-intervals/q4-ci-mean-t.php` | t-interval construction (the z-interval's mirror) |
| 2 | `questions/stats-tests/confidence-intervals/q3-ci-width-factors.php` | n and confidence level vs width |

New files to author (slots 3–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q7-z-critical-value.php` | 2 | 10 |
| 4 | `q8-z-interval-mean.php` | 3 | 10 |
| 5 | `q9-ebm-and-interval.php` | 3 | 10 |
| 6 | `q10-interpret-the-interval.php` | 3 | 10 |
| 7 | `q11-width-dials.php` | 3 | 10 |
| 8 | `q12-backwards-from-interval.php` | 2 | 10 |
| 9 | `q13-sample-size-mean.php` | 2 | 9 |
| 10 | `pre-frq-grade-a-ci-interpretation.php` | 3 | 12 |

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
- **Tolerances match the family:** `abstolerance = 0.005` for probabilities and error bounds,
  `abstolerance = 0.5` for inverse-normal values (see `q4`/`q6`).
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.
- **Critical values are precomputed constants** (1.645, 1.96, 2.326, 2.576) — never call
  `invnormalcdf` in the answer path when a constant will do; the family's `q4`/`q6` compute
  `invtcdf`/`invnormalcdf` live, which is fine, but the constants are cleaner and seed-sweepable.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q7-z-critical-value.php — 2 parts
A confidence level `CL` (contexts in threes: 90%, 95%, 98%, 99%). Parts: (a) `numfunc` — the
critical value `z_(alpha/2)` (1.645, 1.96, 2.326, 2.576); (b) `choices` — the area you feed
`invNorm` (the area to the LEFT, `1 - alpha/2`, not the tail `alpha/2`). **Invariant: (a) is the
precomputed constant for the stated CL, (b) is constant.** The guide: split alpha in half, then
ask for the z-score with that much area above it — and `invNorm` wants the area below. Mirrors
Try It Now 7.2 and the section's "three confidence levels worth recognising on sight".

### q8-z-interval-mean.php — 3 parts
`bar(x)`, `sigma`, `n`, `CL` (contexts in threes, `sqrt(n)` exact so the SE is clean). Parts: (a)
`numfunc` — the standard error `sigma/sqrt(n)`; (b) `numfunc` — the error bound
`EBM = z_(alpha/2) * sigma/sqrt(n)`; (c) `numfunc` — the upper endpoint `bar(x) + EBM`.
**Invariant: (a) = sigma/sqrt(n) exactly, (b) = z*SE exactly, (c) = bar(x) + EBM exactly.** The
guide: the interval is the point estimate plus or minus the error bound, and the standard
deviation has to be the one that applies to the thing being estimated — the standard error, not
sigma by itself. Mirrors Example 7.2, Try It Now 7.3, Example 7.3, Try It Now 7.4.

### q9-ebm-and-interval.php — 3 parts
`bar(x)`, `sigma`, `n`, `CL` (contexts in threes). Parts: (a) `numfunc` — the error bound;
(b) `numfunc` — the lower endpoint; (c) `numfunc` — the upper endpoint. **Invariant: (a) = z*SE,
(b) = bar(x) - EBM, (c) = bar(x) + EBM exactly.** The guide: EBM is exactly half the interval's
total width, and the interval is symmetric around the point estimate. Mirrors Example 7.2/7.3 and
problem set 7.1.4/7.1.6d.

### q10-interpret-the-interval.php — 3 parts
An interval `(lo, hi)` at `CL` for a context (contexts in threes). Parts: (a) `choices` — the
correct interpretation sentence (We estimate with CL% confidence that the true population mean
[context] is between lo and hi); (b) `choices` — what the confidence level actually promises (the
method, not the result: about CL% of intervals built this way contain the true mean); (c)
`choices` — the wrong reading to avoid (there is a CL% chance the true mean is between lo and hi
— the interval either contains it or it does not). **Invariant: all three answers are constant
across seeds.** The guide: the interval moves, not the truth — the confidence lives in the
procedure that generated the interval. Mirrors Example 7.1, the section's interpretation
template, and problem set 7.1.11.

### q11-width-dials.php — 3 parts
One scenario, three questions about the SAME interval (contexts in threes). Parts: (a) `choices`
— what happens to the width when the confidence level rises (it widens); (b) `choices` — what
happens when the sample size rises (it narrows); (c) `numfunc` — the EBM at the original CL for
a quadrupled sample size (half the original EBM). **Invariant: (a) and (b) are constant, (c) =
original EBM / 2 exactly.** The guide: two dials control the width — raising the confidence level
widens the interval, raising the sample size narrows it, and the sqrt(n) makes precision
expensive (nearly tripling n from 36 to 100 only cut the EBM by about 40%). Mirrors Example
7.4/7.5, Try It Now 7.5/7.6, and problem set 7.1.5/7.1.8e.

### q12-backwards-from-interval.php — 2 parts
An interval `(lo, hi)` (contexts in threes, clean arithmetic). Parts: (a) `numfunc` — the error
bound `(hi - lo)/2`; (b) `numfunc` — the sample mean `(lo + hi)/2`. **Invariant: (a) = (hi -
lo)/2 and (b) = (lo + hi)/2 exactly.** The guide: an interval is symmetric around its centre —
the sample mean sits exactly in the middle, and the error bound is exactly half the width; both
routes to each answer agree, which is a useful check. Mirrors Example 7.6, Try It Now 7.7, and
problem set 7.1.13.

### q13-sample-size-mean.php — 2 parts
`sigma`, `EBM`, `CL` (contexts in threes, `z^2 sigma^2 / EBM^2` non-integer so rounding up
matters). Parts: (a) `numfunc` — the raw `n = z^2 sigma^2 / EBM^2`; (b) `numfunc` — the sample
size rounded UP. **Invariant: (a) is the precomputed raw value, (b) = ceil(a) exactly.** The
guide: rounding down would leave the sample slightly too small, which makes the error bound
slightly larger than the one the study promised; rounding up costs one extra observation and
keeps the promise. Mirrors Example 7.7, Try It Now 7.8, and problem set 7.1.14.

### pre-frq-grade-a-ci-interpretation.php — 3 parts
The pre-FRQ mirror of `questions/frq/inference-for-means/q3-single-mean-interpreting-confidence-interval.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **CI Interpretation (4)**,
**Confidence Level Meaning (3)**, **Assessing the Claim (3)**. Drop **"Confidence Level Meaning"**
— a student can interpret the interval and assess the claim without ever explaining what the
confidence level means in repeated sampling, and the section's own "the interval moves, not the
truth" is exactly the step a plausible answer skips. Not in the used table (2.3 Percentile, 2.4
Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the
Parameters, 5.1 State the Theoretical Value, 5.2 State the Empirical Value, 6.1 State the
Sum's Parameters, 6.2 Apply the Continuity Correction). **Invariant: read each of the four
responses against every rubric line — no response earns a category it is supposed to be missing
(category-purity), and every number inside a response is generated from the same variables as
the scenario.** Scope the CSS with a `.qscope71` class since the pre-FRQ shares the assignment
page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eight you did not finish, if any.

# SPEC — §7.2 A Single Population Mean using the Student t Distribution (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/stats-tests/confidence-intervals/`
(read `q4-ci-mean-t.php` and `q6-ci-mean-t-story.php` first — the t-interval pattern — plus
`questions/stats-tests/AGENTS.md` and `questions/probability/AGENTS.md`). Match the family
shape: white-card UI, blue-chip part labels, `jointrandfrom`/parallel-array scenarios with
precomputed answers, `numfunc` for numeric parts, `choices` with `$noshuffle[N] = "all"`, no
`essay`, type picker Multipart. Every question that calls a t macro starts its COMMON CONTROL
with `loadlibrary("stats")`.

**Scope note:** §7.2 is the *confidence interval for a population mean when sigma is unknown* —
the Student t-distribution, degrees of freedom `n - 1`, the t-score
`t = (bar(x) - mu)/(s/sqrt(n))`, the error bound `EBM = t_(alpha/2) * s/sqrt(n)`, the interval,
and the interpretation. The two reuses carry the t-interval construction; the new files carry
the degrees-of-freedom reading, the t-vs-z comparison, the t-critical-value lookup, the
t-interval with interpretation, and the raw-data reduction.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/confidence-intervals/q4-ci-mean-t.php` | t-interval construction |
| 2 | `questions/stats-tests/confidence-intervals/q6-ci-mean-t-story.php` | t-interval with story + interpretation |

New files to author (slots 3–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q14-degrees-of-freedom.php` | 2 | 10 |
| 4 | `q15-t-vs-z.php` | 3 | 10 |
| 5 | `q16-t-critical-value.php` | 2 | 10 |
| 6 | `q17-t-interval-mean.php` | 3 | 10 |
| 7 | `q18-t-interval-interpret.php` | 3 | 10 |
| 8 | `q19-raw-data-to-interval.php` | 3 | 10 |
| 9 | `q20-t-width-dials.php` | 3 | 9 |
| 10 | `pre-frq-grade-a-t-interval.php` | 3 | 12 |

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
- **`loadlibrary("stats")` at the top of COMMON CONTROL** in every question that calls a t macro
  — without it MOM rejects the call.
- **`invtcdf(p, df)` is the inverse-t** (the family's `q4`/`q6` use it live). Precompute the
  t-critical values in the scenario arrays where a constant will do; the family's live
  `invtcdf` is fine too.
- **Tolerances match the family:** `abstolerance = 0.5` for interval endpoints (see `q4`/`q6`),
  `abstolerance = 0.005` for error bounds.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q14-degrees-of-freedom.php — 2 parts
A sample size `n` (contexts in threes). Parts: (a) `numfunc` — the degrees of freedom `n - 1`;
(b) `choices` — why it is `n - 1` (the n deviations from the sample mean must sum to zero, so the
last one is determined once the other n - 1 are known). **Invariant: (a) = n - 1 exactly, (b) is
constant.** The guide: the degrees of freedom pick which member of the t-family you are standing
in — one curve for n = 5, a different one for n = 30. Mirrors Try It Now 7.1a and problem set
7.2.1d.

### q15-t-vs-z.php — 3 parts
A sample size `n` and a confidence level (contexts in threes). Parts: (a) `numfunc` — the
t-critical value `t_(alpha/2)` for `df = n - 1`; (b) `numfunc` — the matching z-critical value;
(c) `choices` — why the t-value is larger (the t-distribution has fatter tails because s is an
estimate, so a bigger multiplier is needed to capture the same confidence). **Invariant: (a) >
(b) on every seed, (c) is constant.** The guide: to capture the middle 95% of a t-curve with 14
df you go out 2.14 standard errors instead of 1.96 — the price of not knowing sigma, and the
price falls as the sample grows. Mirrors Try It Now 7.1b and the section's properties list.

### q16-t-critical-value.php — 2 parts
A sample size `n` and a confidence level (contexts in threes). Parts: (a) `numfunc` — the
t-critical value `t_(alpha/2)` for `df = n - 1`; (b) `choices` — the area you feed `invT` (the
area to the LEFT, `1 - alpha/2`, not the tail `alpha/2`). **Invariant: (a) is the precomputed
t-value, (b) is constant.** The guide: a table indexed by right-tail area wants `alpha/2`, and
`invT` wants `1 - alpha/2` — both give the same t-score; they just ask for it differently, and
the left-versus-right flip is the only place students reliably lose points. Mirrors Try It Now
7.2 step 2 and Example 7.1 step 3.

### q17-t-interval-mean.php — 3 parts
`bar(x)`, `s`, `n`, `CL` (contexts in threes). Parts: (a) `numfunc` — the standard error
`s/sqrt(n)`; (b) `numfunc` — the error bound `EBM = t_(alpha/2) * s/sqrt(n)`; (c) `numfunc` —
the upper endpoint `bar(x) + EBM`. **Invariant: (a) = s/sqrt(n), (b) = t*SE, (c) = bar(x) + EBM
exactly.** The guide: swap the known sigma for the sample's s, swap z for t, and nothing else
changes — the structure carries over. Mirrors Try It Now 7.2, Example 7.1, Try It Now 7.3,
Example 7.2.

### q18-t-interval-interpret.php — 3 parts
An interval `(lo, hi)` at `CL` for a context (contexts in threes). Parts: (a) `choices` — the
correct interpretation sentence; (b) `choices` — what the confidence level means (the method, in
repeated sampling); (c) `choices` — the wrong reading to avoid (a CL% chance the mean is between
the endpoints). **Invariant: all three answers are constant across seeds.** The guide: the
interval is a statement about the population mean, not about individual values, and the
confidence belongs to the method. Mirrors the section's interpretation template and problem set
7.2.5/7.2.22.

### q19-raw-data-to-interval.php — 3 parts
A raw list of `n` values (contexts in threes, small n like 9–15, clean arithmetic). Parts: (a)
`numfunc` — the sample mean `bar(x)`; (b) `numfunc` — the sample standard deviation `s` (divide
by n - 1); (c) `numfunc` — the upper endpoint of the `CL`% t-interval. **Invariant: (a) and (b)
are the precomputed summary statistics, (c) = bar(x) + t*SE exactly.** The guide: before any of
the interval machinery runs, reduce the list to bar(x), s, and n — and be careful which
standard deviation you copy down: this section always wants S_x (dividing by n - 1), not sigma_x.
Mirrors Try It Now 7.2/7.3, Example 7.1/7.2, and problem set 7.2.28.

### q20-t-width-dials.php — 3 parts
One scenario, three questions about the SAME t-interval (contexts in threes). Parts: (a)
`choices` — what happens to the EBM when the confidence level rises (it increases); (b)
`choices` — what happens when the sample size rises (it decreases); (c) `numfunc` — the EBM at
the same CL for a quadrupled sample size (half the original EBM). **Invariant: (a) and (b) are
constant, (c) = original EBM / 2 exactly.** The guide: the standard error is the part of the
error bound that does not change when the confidence level does — everything the confidence
level touches is packed into the single multiplier t_(alpha/2). Mirrors problem set
7.2.23/7.2.24 and the section's properties.

### pre-frq-grade-a-t-interval.php — 3 parts
The pre-FRQ mirror of `questions/frq/inference-for-means/q3-single-mean-interpreting-confidence-interval.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **CI Interpretation (4)**,
**Confidence Level Meaning (3)**, **Assessing the Claim (3)**. Drop **"Assessing the Claim"** — a
student can interpret the interval and explain the confidence level without ever checking whether
a claimed value falls inside it, and the section's own "the interval either contains mu or it
does not" is exactly the step a plausible answer skips. This is DIFFERENT from §7.1's pre-FRQ
(dropped: Confidence Level Meaning) — the two pre-FRQs must not teach the same lesson. Not in
the used table (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further
Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second
Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the
Sum, 4.3 Name the Parameters, 5.1 State the Theoretical Value, 5.2 State the Empirical Value,
6.1 State the Sum's Parameters, 6.2 Apply the Continuity Correction, 7.1 Confidence Level
Meaning). **Invariant: read each of the four responses against every rubric line — no response
earns a category it is supposed to be missing (category-purity), and every number inside a
response is generated from the same variables as the scenario.** Scope the CSS with a `.qscope72`
class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eight you did not finish, if any.

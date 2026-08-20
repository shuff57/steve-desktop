# SPEC — §6.1 The Central Limit Theorem for Sample Means (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **six** IMathAS question files into `mom-content/questions/sampling-distributions/` (the
files already there are the pattern — **read `q2-x-bar-conditions.php`, `q4-x-bar-probability.php`
and `q5-clt-single-vs-sample.php` before starting**, plus `questions/sampling-distributions/AGENTS.md`
and `questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part
labels, `jointrandfrom` parallel arrays with precomputed answers, `numfunc` for numeric parts,
`choices` with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart. Every question that
calls a normal macro starts its COMMON CONTROL with `loadlibrary("stats")`.

**Scope note:** §6.1 is the *central limit theorem for sample means* — the sampling distribution
of `bar(x)`, `bar(X) ~ N(mu, sigma/sqrt(n))`, the standard error `sigma/sqrt(n)`, the 
`z = (bar(x) - mu)/(sigma/sqrt(n))` z-score, probabilities for a sample mean, and percentiles of
the sampling distribution with their average-focused interpretation. The three reuses carry the
conditions check, the plain probability, and the single-vs-sample comparison; the new files carry
the notation reading, the standard error arithmetic, the percentile-with-interpretation, and the
"which distribution" decision.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/sampling-distributions/q2-x-bar-conditions.php` | normal population OR n >= 30 conditions |
| 2 | `questions/sampling-distributions/q4-x-bar-probability.php` | mean, SE, P(bar(x) < c) or P(bar(x) > c) |
| 3 | `questions/sampling-distributions/q5-clt-single-vs-sample.php` | P(X < a) vs P(bar(x) < a), why the mean is more extreme |

New files to author (slots 4–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 4 | `q7-x-bar-notation.php` | 3 | 10 |
| 5 | `q8-standard-error.php` | 3 | 10 |
| 6 | `q9-x-bar-probability-between.php` | 2 | 10 |
| 7 | `q10-x-bar-percentile.php` | 2 | 10 |
| 8 | `q11-se-half-and-quarter.php` | 2 | 10 |
| 9 | `q12-which-distribution.php` | 3 | 9 |
| 10 | `pre-frq-grade-a-clt-reasoning.php` | 3 | 12 |

Points: 10 + 10 + 10 + 10 + 10 + 10 + 10 + 9 + 9 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your seven files (the sampling-distributions family is clean; the
17 pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article`); no marker text quoted inside a comment.

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
- **The 1-arg forms only:** `normalcdf(z)` and `invnormalcdf(p)`. **The 4-arg form
  `normalcdf(mean, sd, lower, upper)` is NOT accepted on this MOM instance** — standardize first,
  then call the 1-arg form, exactly as `q4`/`q5` do.
- **Tolerances match the family:** `abstolerance = 0.005` for probabilities and standard errors
  (see `q4`), `abstolerance = 0.5` for inverse-normal values (see `q3` in normal-distribution).
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The seven new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question), exactly as the family does. Precompute the answers.

### q7-x-bar-notation.php — 3 parts
A population with `mu` and `sigma` and a sample of size `n` (contexts in threes, clean numbers —
pick `sqrt(n)` exact). Parts: (a) `numfunc` — the mean of the sampling distribution `mu`;
(b) `numfunc` — the standard error `sigma/sqrt(n)`; (c) `choices` — what the standard error
measures (how far, on average, a sample mean falls from the population mean in repeated samples
of size n). **Invariant: on every seed, (a) = mu, (b) = sigma/sqrt(n) exactly, (c) is constant.**
The guide: the two parameters say different things — averaging does not move the center, it
tightens the spread. Mirrors Example 6.3a/6.4a and problem set 6.1.1.

### q8-standard-error.php — 3 parts
`mu`, `sigma`, `n` (contexts in threes, `sqrt(n)` exact so the SE is clean). Parts: (a) `numfunc`
— the standard error; (b) `numfunc` — the z-score of a sample mean `bar(x)` (the numerator uses
the standard error, not sigma); (c) `choices` — what happens to the SE when n is quadrupled (it
halves). **Invariant: on every seed, (a) = sigma/sqrt(n) exactly, (b) = (bar(x) - mu)/SE exactly,
(c) is constant.** The guide's warning: dividing by `n` instead of `sqrt(n)` is the single most
common slip in this section, and using sigma where the SE belongs makes a very unusual sample
mean look ordinary. Mirrors Try It Now 6.1 (SE arithmetic) and Definition 6.1.3.

### q9-x-bar-probability-between.php — 2 parts
`mu`, `sigma`, `n`, and two cutoffs `lo` and `hi` (contexts in threes, both cutoffs on the same
side or straddling the mean so the area is meaningful). Parts: (a) `numfunc` — the standard error;
(b) `numfunc` — `P(lo < bar(x) < hi)`. **Invariant: on every seed, (a) = sigma/sqrt(n) and
(b) = normalcdf((hi-mu)/SE) - normalcdf((lo-mu)/SE), computed by standardization.** The guide
walks the standardize-then-normalcdf move and the sketch. Mirrors Try It Now 6.1/6.2, Example
6.1a, Example 6.2, and problem set 6.1.8b.

### q10-x-bar-percentile.php — 2 parts
`mu`, `sigma`, `n`, a percentile `p` (contexts in threes). Parts: (a) `numfunc` — the percentile
`k = mu + invnormalcdf(p) * (sigma/sqrt(n))`; (b) `choices` — the correct interpretation
(p% of samples of size n have a mean below k — a statement about AVERAGES, not individuals).
**Invariant: (a) is the precomputed percentile, (b) is constant.** The guide: a percentile of the
sampling distribution is a statement about averages, and naming that one word — "average" — is
usually the difference between a correct interpretation and a plausible-sounding wrong one.
Mirrors Example 6.3d, Example 6.4c, and problem set 6.1.6.

### q11-se-half-and-quarter.php — 2 parts
`mu`, `sigma`, and one sample size `n` (contexts in threes, `sqrt(n)` and `sqrt(4n)` exact).
Parts: (a) `numfunc` — the SE for samples of size `n`; (b) `numfunc` — the SE for samples of size
`4n`. **Invariant: on every seed, (a) = sigma/sqrt(n), (b) = (a)/2 exactly.** The guide: square
roots grow lazily — four times the data only halves the spread, which is the law of large numbers
showing up in a number. Mirrors Try It Now 6.3 in §6.3 (n = 9, 36, 144) and the Insight Note
"Shrinking, but slowly".

### q12-which-distribution.php — 3 parts
One scenario, three questions about the SAME population (contexts in threes): a question about
one individual, a question about the mean of a sample, and a question about the standard error.
Parts: (a) `choices` — which distribution a single-value question uses (the population
distribution, not the CLT); (b) `choices` — which a sample-mean question uses (the CLT sampling
distribution); (c) `numfunc` — the standard error. **Invariant: (a) and (b) are constant, (c) is
sigma/sqrt(n) exactly.** The guide: the word before the number tells you which tool — "average",
"mean", "total", "sum", or "one randomly selected" decide the whole problem. Mirrors the section's
opening distinction and problem set 6.1.9d/6.1.9e.

### pre-frq-grade-a-clt-reasoning.php — 3 parts
The pre-FRQ mirror of `questions/frq/sampling-distributions/q1-sampling-distribution-reasoning.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Parameter vs Statistic
(3)**, **Conditions for CLT (3)**, **Standard Error (4)**. Drop **"Conditions for CLT"** — a
student can name the parameter and statistic and compute the standard error without ever
verifying that the sampling distribution is approximately normal, and the section's own "word
before the number" rule is exactly the step a plausible answer skips. Not in the used table (2.3
Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7
Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the
Parameters, 5.1 State the Theoretical Value, 5.2 State the Empirical Value). **Invariant: read
each of the four responses against every rubric line — no response earns a category it is
supposed to be missing (category-purity), and every number inside a response is generated from
the same variables as the scenario.** Scope the CSS with a `.qscope61` class since the pre-FRQ
shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the seven you did not finish, if any.

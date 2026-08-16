# SPEC — §5.1 The Standard Normal Distribution (Intro Stats -SH)

Write **eight** IMathAS question files into `mom-content/questions/normal-distribution/` (the
files already there are the pattern — **read `q1-z-score-compute.php`, `q4-empirical-rule.php`
and `q2-normal-probability.php` before starting**, plus `questions/normal-distribution/AGENTS.md`
and `questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part
labels, `jointrandfrom`/parallel-array scenarios with precomputed answers, `numfunc` for numeric
parts, `choices` with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart.

**Scope note:** §5.1 is the *standard normal distribution* — what `Z ~ N(0, 1)` is, the z-score
`z = (x - mu)/sigma` and its sign-and-size reading, the inverse `x = mu + z*sigma`, comparing
values from differently scaled distributions by their z-scores, and the Empirical Rule
(68-95-99.7) in both directions (values from percentages, percentages from values). The two
reuses carry the z-computation and the rule's core; the new files carry the notation reading,
the inverse direction, the sign-and-size reading, the band arithmetic, and the cross-distribution
comparison.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/normal-distribution/q1-z-score-compute.php` | compute z for two values; which is more unusual |
| 2 | `questions/normal-distribution/q4-empirical-rule.php` | 68-95-99.7 within / below / between-mean-and-edge |

New files to author (slots 3–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q6-read-notation.php` | 3 | 10 |
| 4 | `q7-z-score-from-x.php` | 2 | 10 |
| 5 | `q8-x-from-z-score.php` | 2 | 10 |
| 6 | `q9-read-sign-and-size.php` | 3 | 10 |
| 7 | `q10-empirical-rule-bands.php` | 3 | 10 |
| 8 | `q11-empirical-rule-percentages.php` | 3 | 9 |
| 9 | `q12-compare-two-distributions.php` | 2 | 9 |
| 10 | `pre-frq-grade-a-z-score-interpretation.php` | 3 | 12 |

Points: 10 + 10 + 10 + 10 + 10 + 10 + 9 + 9 + 10 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your eight files (the normal-distribution family is clean; the 17
pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself** with a throwaway node script: loop every `rand()` combination
and assert the invariant named per question below. Report the combination count. For the
macro-computed answers (`normalcdf`/`invnormalcdf`), the sweep asserts the **structural**
invariants — the z-score arithmetic, the complement identity, the percentile arithmetic — since the
CDF values themselves are MOM's.

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
- **The 1-arg forms only:** `normalcdf(z)` (standard-normal CDF) and `invnormalcdf(p)` (z with area
  `p` to its left). **The 4-arg form `normalcdf(mean, sd, lower, upper)` is NOT accepted on this
  MOM instance** — standardize first, then call the 1-arg form, exactly as `q2`/`q3` do.
- **Tolerances match the family:** `reltolerance = 0.02` with `abstolerance = 0.003` for
  probabilities, `abstolerance = 0.5` for inverse-normal values (see `q2`/`q3`).
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The eight new

Each is `multipart` and auto-graded. Randomization via parallel-array scenarios (4–6 per
question), exactly as the family does. Precompute the answers.

### q6-read-notation.php — 3 parts
`X ~ N(mu, sigma)` in prose (contexts in threes). Parts: (a) `numfunc` — `mu`; (b) `numfunc` —
`sigma`; (c) `numfunc` — the median. **Invariant: on every seed, (a) and (b) are the stated
parameters and (c) = `mu` exactly (a normal curve is symmetric about its mean, so the median IS
the mean).** The guide reads the notation back and states the median-equals-mean fact. Mirrors
problem set 5.1.2/5.1.3/5.1.5/5.1.6/5.1.7.

### q7-z-score-from-x.php — 2 parts
A value `x` from `N(mu, sigma)` (contexts in threes, clean arithmetic — pick `x - mu` divisible
by `sigma` so `z` is exact or one clean decimal). Parts: (a) `numfunc` — `z = (x - mu)/sigma`;
(b) `choices` — the interpretation: `z` is `|z|` standard deviations to the right/left of the
mean. **Invariant: on every seed, (a) equals the precomputed `(x - mu)/sigma` exactly, and (b)'s
direction matches the sign of `z`.** The guide shows the substitution and reads the sign and
size. Mirrors Try It Now 5.1/5.2 and problem set 5.1.22–5.1.25.

### q8-x-from-z-score.php — 2 parts
A z-score from `N(mu, sigma)` (contexts in threes). Parts: (a) `numfunc` — `x = mu + z*sigma`;
(b) `choices` — the interpretation: `x` is `|z|` standard deviations to the right/left of the
mean. **Invariant: on every seed, (a) equals the precomputed `mu + z*sigma` exactly, and (b)'s
direction matches the sign of `z`.** The guide teaches the rearranged form as the trip back.
Mirrors Try It Now 5.3b and problem set 5.1.15–5.1.21.

### q9-read-sign-and-size.php — 3 parts
A value `x` and its z-score are given (contexts in threes). Parts: (a) `numfunc` — how many
standard deviations `x` sits from the mean (`|z|`); (b) `choices` — right or left of the mean;
(c) `numfunc` — the mean. **Invariant: on every seed, (a) = `|z|`, (b) matches the sign of `z`,
and (c) = `mu`.** The guide: the z-score is a position — sign says direction, size says distance.
Mirrors problem set 5.1.26–5.1.30.

### q10-empirical-rule-bands.php — 3 parts
`N(mu, sigma)` with clean parameters (contexts in threes). Parts: (a) `numfunc` — the 68% band
(`mu - sigma` to `mu + sigma`); (b) `numfunc` — the 95% band; (c) `numfunc` — the 99.7% band.
**Invariant: on every seed, the bands are `mu +/- k*sigma` for k = 1, 2, 3 exactly.** The guide
shows the arithmetic and notes the bands are centered at the mean. Mirrors Try It Now 5.5/5.6 and
Example 5.6.

### q11-empirical-rule-percentages.php — 3 parts
`N(mu, sigma)` (contexts in threes). Parts: (a) `choices` — percent between the mean and one
standard deviation (34%); (b) `choices` — percent between the first and second standard
deviations, both sides (13.5%); (c) `choices` — percent between the second and third standard
deviations, both sides (4.7%). **Invariant: the three answers are constant across seeds (the
percentages are fixed by the rule; only the context varies).** The guide derives each from the
68-95-99.7 bands. Mirrors problem set 5.1.33/5.1.37–5.1.40.

### q12-compare-two-distributions.php — 2 parts
Two distributions `N(mu1, sigma1)` and `N(mu2, sigma2)` with DIFFERENT parameters, and two values
`x` and `y` chosen so their z-scores are EQUAL (contexts in threes — e.g. the section's Chilean
heights: `N(170, 6.28)` with `x = 160.58` and `N(172.36, 6.34)` with `y = 162.85`, both `z = -1.5`).
Parts: (a) `numfunc` — `z` for `x`; (b) `numfunc` — `z` for `y`. **Invariant: on every seed,
(a) = (b) exactly, and the raw values differ.** The guide's point: the raw numbers are not
comparable, but the standardized scores are — the whole reason z-scores exist. Mirrors Example
5.4 and problem set 5.1.50c.

### pre-frq-grade-a-z-score-interpretation.php — 3 parts
The pre-FRQ mirror of `questions/frq/normal-distribution/q10-z-scores-and-normal-probability.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Z-Score Calculation (4)**,
**Z-Score Interpretation (3)**, **Unusual or Typical (3)**. Drop **"Unusual or Typical"** — a
student can compute the z-score and interpret its position without ever judging whether the score
is unusual, and the section's own Context Pause ("the 1 in 20 rule of thumb") is exactly the step
a plausible answer skips. Not in the used table (2.3 Percentile, 2.4 Contextual Interpretation,
2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2
Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1
State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters). **Invariant: read each of the
four responses against every rubric line — no response earns a category it is supposed to be
missing (category-purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with a `.qscope51` class since the pre-FRQ shares the
assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eight you did not finish, if any — an honest short
list beats eight files where two were rushed.

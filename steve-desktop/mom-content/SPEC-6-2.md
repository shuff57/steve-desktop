# SPEC — §6.2 The Central Limit Theorem for Sums (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/sampling-distributions/` (read
`q4-x-bar-probability.php` and `q5-clt-single-vs-sample.php` first — the pattern for
probability-with-standardization — plus `questions/sampling-distributions/AGENTS.md` and
`questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part labels,
`jointrandfrom` parallel arrays with precomputed answers, `numfunc` for numeric parts, `choices`
with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart. Every question that calls a
normal macro starts its COMMON CONTROL with `loadlibrary("stats")`.

**Scope note:** §6.2 is the *central limit theorem for sums* — `Sigma X ~ N(n*mu, sqrt(n)*sigma)`,
the mean multiplies by n while the standard deviation multiplies by sqrt(n), the z-score of a
sum, probabilities for a sum, percentiles of the sum distribution, and the unit-conversion habit
(minutes vs hours). The bank has ZERO sum questions — every slot is authored fresh. The reuses
from §6.1 (q4/q5) are mean questions; the new files are the sum counterparts and must not overlap
with them. No pre-FRQ exists to mirror in the sums direction — the natural mirror is
`frq/normal-distribution/q14-central-limit-theorem.php` (shape, conditions, importance), which is
claimed by §6.1's pre-FRQ; per the pre-FRQ rule this one is authored first and defines the shape
a later sums FRQ should match.

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q13-sum-notation.php` | 3 | 10 |
| 2 | `q14-sum-parameters.php` | 2 | 10 |
| 3 | `q15-sum-z-score.php` | 2 | 10 |
| 4 | `q16-sum-probability-greater.php` | 2 | 10 |
| 5 | `q17-sum-probability-less.php` | 2 | 10 |
| 6 | `q18-sum-probability-between.php` | 2 | 10 |
| 7 | `q19-sum-percentile.php` | 2 | 10 |
| 8 | `q20-sum-z-score-to-value.php` | 2 | 9 |
| 9 | `q21-sum-unit-conversion.php` | 2 | 9 |
| 10 | `pre-frq-grade-a-sum-interpretation.php` | 3 | 12 |

Total: 10+10+10+10+10+10+10+9+9+12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the sampling-distributions family is clean; the
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
- **Tolerances match the family:** `abstolerance = 0.005` for probabilities, `abstolerance = 0.5`
  for inverse-normal values.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.
- **Sums scale cleanly:** pick `mu`, `sigma`, and `n` so that `n*mu` and `sqrt(n)*sigma` are
  clean numbers or one clean decimal. `n*mu` uses `prettyint()` when over 999.

## The ten

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute every answer.

### q13-sum-notation.php — 3 parts
`X ~ N(mu, sigma)` and a sample of size `n` (contexts in threes). Parts: (a) `numfunc` — the mean
of the sums `n*mu`; (b) `numfunc` — the standard deviation of the sums `sqrt(n)*sigma`; (c)
`choices` — why the mean multiplies by n but the spread only by sqrt(n) (high draws and low draws
partly cancel on the way to the total). **Invariant: (a) = n*mu exactly, (b) = sqrt(n)*sigma
exactly, (c) is constant.** The guide: the mean scales by n, the spread by sqrt(n) — adding more
values pushes the total up in a straight line but spreads it out much more slowly. Mirrors Try It
Now 6.1 and problem set 6.2.16/6.2.17.

### q14-sum-parameters.php — 2 parts
`mu`, `sigma`, `n` (contexts in threes). Parts: (a) `numfunc` — `mu_sum = n*mu`; (b) `numfunc` —
`sigma_sum = sqrt(n)*sigma`. **Invariant: (a) = n*mu and (b) = sqrt(n)*sigma exactly on every
seed.** The guide: `Sigma X ~ N(n*mu, sqrt(n)*sigma)` — the sum is just another normal variable,
only the two parameters change. Mirrors Try It Now 6.1 and problem set 6.2.16/6.2.17.

### q15-sum-z-score.php — 2 parts
A sum `s` from a population with `mu`, `sigma`, `n` (contexts in threes, `(s - n*mu)` divisible
by `sqrt(n)*sigma` so the z-score is exact or one clean decimal). Parts: (a) `numfunc` —
`z = (s - n*mu)/(sqrt(n)*sigma)`; (b) `choices` — the interpretation: the sum sits |z| standard
deviations of the sums above/below the mean of the sums. **Invariant: (a) equals the precomputed
z exactly, (b) matches the sign of z.** The guide: the z-score of a sum is exactly like every
other z-score — sign gives direction, size gives distance — only the two parameters change.
Mirrors Definition 6.2.2 and problem set 6.2.25/6.2.26.

### q16-sum-probability-greater.php — 2 parts
`mu`, `sigma`, `n`, a cutoff `c` (contexts in threes). Parts: (a) `numfunc` — the mean of the
sums; (b) `numfunc` — `P(Sigma x > c)`. **Invariant: (a) = n*mu, (b) = 1 - normalcdf((c - n*mu)/(sqrt(n)*sigma))
to 4 decimals.** The guide: shade the right tail, standardize against the sum's OWN parameters —
feeding in the original mu and sigma is the single most common mistake in this section, and
nothing on the screen will warn you. Mirrors Try It Now 6.2, Example 6.1a, and problem set
6.2.1/6.2.5.

### q17-sum-probability-less.php — 2 parts
Same shape as q16, other tail. Parts: (a) `numfunc` — the mean of the sums; (b) `numfunc` —
`P(Sigma x < c)`. **Invariant: (a) = n*mu, (b) = normalcdf((c - n*mu)/(sqrt(n)*sigma)) to 4
decimals.** The guide: the left tail is a direct `normalcdf` — no complement needed. Mirrors
problem set 6.2.2/6.2.6.

### q18-sum-probability-between.php — 2 parts
`mu`, `sigma`, `n`, cutoffs `lo` and `hi` (contexts in threes). Parts: (a) `numfunc` — the
standard deviation of the sums; (b) `numfunc` — `P(lo < Sigma x < hi)`. **Invariant: (a) =
sqrt(n)*sigma, (b) = normalcdf(zhi) - normalcdf(zlo) to 4 decimals.** The guide: a between-
question is the easiest of the three shapes — the thinking happens before you type, deciding
which number is the lower edge and which the upper, because a swapped pair returns a negative
area with no warning. Mirrors Try It Now 6.3b, Example 6.2b, and problem set 6.2.12.

### q19-sum-percentile.php — 2 parts
`mu`, `sigma`, `n`, a percentile `p` (contexts in threes). Parts: (a) `numfunc` — the percentile
`k = n*mu + invnormalcdf(p) * sqrt(n)*sigma`; (b) `choices` — the interpretation (p% of the sums
of size n are at or below k). **Invariant: (a) is the precomputed percentile, (b) is constant.**
The guide: `invNorm` wants the area to the left, and the answer carries the original units —
years, minutes, dollars. Mirrors Try It Now 6.3c, Example 6.2c, and problem set 6.2.24.

### q20-sum-z-score-to-value.php — 2 parts
A z-score `z` for a sum from `mu`, `sigma`, `n` (contexts in threes). Parts: (a) `numfunc` — the
sum `s = n*mu + z*sqrt(n)*sigma`; (b) `choices` — the interpretation (the sum sits |z| standard
deviations of the sums above/below the mean of the sums). **Invariant: (a) equals the precomputed
sum exactly, (b) matches the sign of z.** The guide: solve the z-score equation for the total —
the same equation, run backwards. Mirrors Example 6.1b and problem set 6.2.3/6.2.4/6.2.13/6.2.14.

### q21-sum-unit-conversion.php — 2 parts
A mean in minutes and a total in hours (contexts in threes, e.g. app engagement). Parts: (a)
`numfunc` — `P(Sigma x < 10 hours)` where the cutoff is converted to minutes first; (b) `choices`
— why the unit conversion matters (converting everything to one unit before touching the
calculator is the arithmetic that is trivial to forget, and the answer must carry the right unit).
**Invariant: (a) is computed from the converted cutoff, (b) is constant.** The guide: a problem
hands you a mean in minutes and asks about "ten hours" — convert to one unit first and state which
unit your answer is in. Mirrors Try It Now 6.4 and Example 6.3.

### pre-frq-grade-a-sum-interpretation.php — 3 parts
Authored-first pre-FRQ (no sums FRQ to mirror; §6.1's pre-FRQ claims the CLT-reasoning mirror).
Categories, 12 pts: **State the Sum's Parameters (3)** — `n*mu` and `sqrt(n)*sigma`; **Compute
the Probability or Percentile (5)** — run `normalcdf`/`invNorm` on the sum's own distribution;
**Interpret in Context (4)** — read the result as a statement about totals of size n, in the
original units. Dropped category: **State the Sum's Parameters** (a student can compute the
probability and interpret it without ever writing `Sigma X ~ N(n*mu, sqrt(n)*sigma)` — the
section's own warning, "feeding in the original pair is the single most common mistake", is
exactly the step a plausible answer skips, and the two parameters are the thing the other
categories imply but never demand). Not in the used table (2.3 Percentile, 2.4 Contextual
Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1
Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the
Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters, 5.1 State the
Theoretical Value, 5.2 State the Empirical Value). Invariant structure per `pre-frq-template.md`:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names. **Invariant: no response earns a category it is supposed to be
missing (category purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with `.qscope62`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

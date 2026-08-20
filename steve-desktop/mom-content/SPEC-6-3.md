# SPEC — §6.3 Using the Central Limit Theorem (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/sampling-distributions/` (read
`q4-x-bar-probability.php`, `q5-clt-single-vs-sample.php` and `q2-x-bar-conditions.php` first —
the family pattern — plus `questions/sampling-distributions/AGENTS.md` and
`questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part labels,
`jointrandfrom` parallel arrays with precomputed answers, `numfunc` for numeric parts, `choices`
with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart. Every question that calls a
normal macro starts its COMMON CONTROL with `loadlibrary("stats")`.

**Scope note:** §6.3 is *using the central limit theorem* — deciding from the wording whether a
problem wants a mean, a sum, or a single individual value; the law of large numbers (bigger
samples, tighter sample means, four-times-the-data-for-half-the-spread); and the normal
approximation to the binomial with the continuity correction (`P(X >= a)` becomes `P(Y >= a -
0.5)`, etc.). The six reuses from §6.1/§6.2 carry the mean/sum probability mechanics; the new
files carry the decision-making (mean vs sum vs individual), the law of large numbers, and the
binomial approximation with the correction — the two genuinely new skills of this section.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite; note the
cross-assignment reuse is fine — a question serves any number of assignments):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/sampling-distributions/q5-clt-single-vs-sample.php` | P(X < a) vs P(bar(x) < a), why the mean is more extreme |
| 2 | `questions/sampling-distributions/q4-x-bar-probability.php` | mean, SE, P(bar(x) < c) or P(bar(x) > c) |
| 3 | `questions/sampling-distributions/q2-x-bar-conditions.php` | normal population OR n >= 30 conditions |

New files to author (slots 4–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 4 | `q22-mean-sum-or-individual.php` | 3 | 10 |
| 5 | `q23-law-of-large-numbers.php` | 3 | 10 |
| 6 | `q24-normal-approx-conditions.php` | 2 | 10 |
| 7 | `q25-binomial-at-least.php` | 2 | 10 |
| 8 | `q26-binomial-at-most.php` | 2 | 10 |
| 9 | `q27-binomial-between-and-exact.php` | 3 | 9 |
| 10 | `pre-frq-grade-a-continuity-correction.php` | 3 | 12 |

Points: 10 + 10 + 10 + 10 + 10 + 10 + 9 + 9 + 10 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your seven files (the sampling-distributions family is clean; the
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
- **Binomial approximation:** precompute `mu = n*p` and `sigma = sqrt(n*p*q)` in the scenario
  arrays; never call `binompdf`/`binomcdf`. Precompute the corrected boundary BEFORE
  standardizing: `P(X >= a)` becomes `z = (a - 0.5 - mu)/sigma`, `P(X <= a)` becomes
  `z = (a + 0.5 - mu)/sigma`, `P(X > a)` becomes `z = (a + 0.5 - mu)/sigma`, `P(X < a)` becomes
  `z = (a - 0.5 - mu)/sigma`, `P(X = a)` becomes the strip between `z1 = (a - 0.5 - mu)/sigma`
  and `z2 = (a + 0.5 - mu)/sigma`.

## The seven new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q22-mean-sum-or-individual.php — 3 parts
One scenario per context, three questions about the SAME population: a question about one
individual, a question about the mean of a sample, and a question about the sum of the sample.
Parts: (a) `choices` — which distribution a single-value question uses (the population
distribution, NOT the CLT); (b) `choices` — which a sample-mean question uses (the CLT sampling
distribution of `bar(x)`); (c) `choices` — which a sample-sum question uses (the CLT distribution
of `Sigma x`). **Invariant: all three answers are constant across seeds (only the context
varies).** The guide: the word before the number tells you which tool — "average", "mean",
"total", "sum", or "one randomly selected" decide the whole problem, and the third case is where
most of the lost points in this section live. Mirrors the section's opening and problem set
6.3.39f/6.3.39g.

### q23-law-of-large-numbers.php — 3 parts
`mu`, `sigma`, one sample size `n` (contexts in threes, `sqrt(n)` and `sqrt(4n)` exact). Parts:
(a) `numfunc` — the SE for size `n`; (b) `numfunc` — the SE for size `4n`; (c) `choices` — what
the comparison shows (four times the data only halves the spread of the sample mean — the law of
large numbers). **Invariant: (a) = sigma/sqrt(n), (b) = (a)/2 exactly, (c) is constant.** The
guide: the n sits under a square root in the denominator, so as n grows the curve of sample means
squeezes in tighter around mu — the sample mean gets closer to the population mean, and it gets
closer as you collect more data. Mirrors Try It Now 6.1 (§6.3) and problem set 6.3.36.

### q24-normal-approx-conditions.php — 2 parts
A binomial scenario (contexts in threes: surveys, coin flips, quality control). Parts: (a)
`numfunc` — `np`; (b) `numfunc` — `nq`; (c) `choices` — can the normal approximation be used
(both > 5)? **Invariant: on every seed, (a) = n*p, (b) = n*(1-p) exactly, and (c) matches the
actual check — include scenarios that FAIL the condition (np <= 5 or nq <= 5) so the answer
varies by scenario.** The guide: the quantities `np` and `nq` must both be greater than five;
the approximation is better if both are at least 10. Mirrors Definition 6.3.2's setup and problem
set 6.3.34's preamble.

### q25-binomial-at-least.php — 2 parts
A binomial scenario with `n`, `p`, and an `a` (contexts in threes, `np > 5` and `nq > 5`). Parts:
(a) `numfunc` — the corrected boundary `a - 0.5`; (b) `numfunc` — `P(X >= a)` by the normal
approximation. **Invariant: on every seed, (a) = a - 0.5 exactly and (b) = 1 - normalcdf((a -
0.5 - mu)/sigma) to 4 decimals.** The guide: "at least a" includes a, so the bar over a has to be
inside the shaded region and the boundary moves outward by 0.5 — `P(X >= a)` becomes `P(Y >= a -
0.5)`. Mirrors Example 6.5a and Try It Now 6.6.

### q26-binomial-at-most.php — 2 parts
Same shape as q25, other direction. Parts: (a) `numfunc` — the corrected boundary `a + 0.5`; (b)
`numfunc` — `P(X <= a)` by the normal approximation. **Invariant: (a) = a + 0.5 exactly and (b)
= normalcdf((a + 0.5 - mu)/sigma) to 4 decimals.** The guide: "at most a" includes a, so the
boundary moves outward — `P(X <= a)` becomes `P(Y <= a + 0.5)`. Mirrors Example 6.5b and problem
set 6.3.34c.

### q27-binomial-between-and-exact.php — 3 parts
A binomial scenario with `n`, `p`, and two values `a` and `b` (contexts in threes, `a < b`).
Parts: (a) `numfunc` — `P(a < X < b)` by the normal approximation (strict on both ends: `a + 0.5`
lower boundary, `b - 0.5` upper); (b) `numfunc` — `P(X = c)` for a named exact value (the strip
between `c - 0.5` and `c + 0.5`); (c) `choices` — why the correction matters (a binomial bar has
real width, so the boundary slides half a unit to cover it; without it the answer is wrong by
about half a bar's worth of probability every time). **Invariant: on every seed, (a) and (b) are
the precomputed standardized strip areas, and (c) is constant.** The guide: an exact value
becomes the strip `P(a - 0.5 < Y < a + 0.5)` — the only reason a continuous distribution can
answer an "exactly" question at all. Mirrors Example 6.5c/6.5e and problem set 6.3.34d/6.3.34e.

### pre-frq-grade-a-continuity-correction.php — 3 parts
Authored-first pre-FRQ (no continuity-correction FRQ to mirror; the CLT-reasoning and
sum-interpretation mirrors are claimed by §6.1/§6.2). Categories, 12 pts: **State the Binomial
Setup (3)** — name `X ~ B(n, p)` with `mu = np` and `sigma = sqrt(npq)`, and check the
conditions; **Apply the Continuity Correction (5)** — move the boundary half a unit in the
direction the inequality demands; **Compute and Interpret (4)** — standardize and evaluate,
reading the result in context. Dropped category: **Apply the Continuity Correction** (a student
can set up the binomial and compute a probability without ever moving the boundary — the
section's own warning, "an answer computed without the correction still looks reasonable, still
lands in the right decimal place", is exactly the step a plausible answer skips, and the 
half-unit is the one thing the other categories never demand). Not in the used table (2.3
Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7
Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the
Parameters, 5.1 State the Theoretical Value, 5.2 State the Empirical Value). Invariant structure
per `pre-frq-template.md`: `array("choices", "multans", "choices")`, `$scoremethod[1] =
"allornothing"`, four responses built by concatenating one sentence per category then dropping
one, part (b) grades a DIFFERENT response than part (a) names. **Invariant: no response earns a
category it is supposed to be missing (category purity), and every number inside a response is
generated from the same variables as the scenario.** Scope the CSS with `.qscope63`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the seven you did not finish, if any.

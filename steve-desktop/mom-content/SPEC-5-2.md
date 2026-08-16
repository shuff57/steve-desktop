# SPEC — §5.2 Using the Normal Distribution (Intro Stats -SH)

Write **eight** IMathAS question files into `mom-content/questions/normal-distribution/` (the
files already there are the pattern — **read `q2-normal-probability.php`, `q3-inverse-normal-percentile.php`
and `q5-normal-probability-context.php` before starting**, plus `questions/normal-distribution/AGENTS.md`
and `questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part
labels, parallel-array scenarios with precomputed answers, `numfunc` for numeric parts, `choices`
with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart.

**Scope note:** §5.2 is *using the normal distribution* — reading a shaded area as a probability,
`P(X > x) = 1 - P(X < x)`, `P(x < a)` / `P(x > a)` / `P(a < x < b)` via `normalcdf`, percentiles
and critical values via `invNorm`, the interpretation sentence, quartiles and the IQR, the
"at least" right-tail-to-left-area subtraction, and the middle-P% two-tail split. The three
reuses carry the plain probability and percentile shapes; the new files carry the complement
reading, the at-least trap, the quartile/IQR arithmetic, the middle-P% split, and the
interpretation sentence.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/normal-distribution/q2-normal-probability.php` | P(X<a), P(X>b), P(a<X<b) |
| 2 | `questions/normal-distribution/q5-normal-probability-context.php` | same three shapes, fresh contexts |
| 3 | `questions/normal-distribution/q3-inverse-normal-percentile.php` | pth percentile + middle-c% endpoints |

New files to author (slots 4–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 4 | `q13-complement-tail.php` | 2 | 10 |
| 5 | `q14-at-least-percentile.php` | 2 | 10 |
| 6 | `q15-quartiles-and-iqr.php` | 3 | 10 |
| 7 | `q16-middle-percent.php` | 2 | 10 |
| 8 | `q17-interpret-the-percentile.php` | 3 | 9 |
| 9 | `q18-percentile-vs-probability.php` | 3 | 9 |
| 10 | `pre-frq-grade-a-percentile-interpretation.php` | 3 | 12 |

Points: 10 + 10 + 10 + 10 + 10 + 10 + 9 + 9 + 10 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your seven files (the normal-distribution family is clean; the 17
pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself** with a throwaway node script: loop every `rand()` combination
and assert the invariant named per question below. Report the combination count. For the
macro-computed answers (`normalcdf`/`invnormalcdf`), the sweep asserts the **structural**
invariants — the complement identity, the at-least subtraction, the quartile arithmetic, the
middle-P% tail split — since the CDF values themselves are MOM's.

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
  then call the 1-arg form, exactly as `q2`/`q3` do.
- **Tolerances match the family:** `reltolerance = 0.02` with `abstolerance = 0.003` for
  probabilities, `abstolerance = 0.5` for inverse-normal values (see `q2`/`q3`).
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The seven new

Each is `multipart` and auto-graded. Randomization via parallel-array scenarios (4–6 per
question), exactly as the family does. Precompute the answers.

### q13-complement-tail.php — 2 parts
`N(mu, sigma)` with a cutoff `x` (contexts in threes). Parts: (a) `numfunc` — `P(X < x)`;
(b) `numfunc` — `P(X > x)`. **Invariant: on every seed, (a) + (b) = 1 exactly (the two areas
fill the curve).** The guide: the right tail is never measured directly — it is whatever is left
over. Mirrors Try It Now 5.1/5.2 and problem set 5.2.6/5.2.7.

### q14-at-least-percentile.php — 2 parts
`N(mu, sigma)` and a right-hand percentage `p` (contexts in threes, `p` in {0.30, 0.40, 0.60,
0.70}). Parts: (a) `numfunc` — the value `k` with `P(X >= k) = p`; (b) `choices` — the area you
must feed `invNorm` (the complement `1 - p`, not `p`). **Invariant: on every seed, (a) is
`mu + invnormalcdf(1 - p) * sigma` and (b) is constant — the answer is the complement.** The
guide teaches the subtraction as the section's most common quiet error: `invNorm` only accepts an
area to the left, and skipping the subtraction returns a plausible-looking but wrong value. Mirrors
Example 5.5b and problem set 5.2.22.

### q15-quartiles-and-iqr.php — 3 parts
`N(mu, sigma)` (contexts in threes). Parts: (a) `numfunc` — `Q1 = mu + invnormalcdf(0.25) * sigma`;
(b) `numfunc` — `Q3 = mu + invnormalcdf(0.75) * sigma`; (c) `numfunc` — the IQR = Q3 - Q1.
**Invariant: on every seed, (a) < (b), and (c) = (b) - (a) exactly.** The guide: quartiles are
percentiles in disguise, and the IQR is a width, not a location. Mirrors Try It Now 5.5 and
Example 5.5a.

### q16-middle-percent.php — 2 parts
`N(mu, sigma)` and a middle percentage `c` (contexts in threes, `c` in {0.40, 0.50, 0.80, 0.90}).
Parts: (a) `numfunc` — the lower endpoint `k1`; (b) `numfunc` — the upper endpoint `k2`.
**Invariant: on every seed, the tail area is `(1 - c)/2` on each side, `k1 = mu +
invnormalcdf((1-c)/2) * sigma`, `k2 = mu + invnormalcdf(1 - (1-c)/2) * sigma`, and `k1 < k2`.** The
guide walks the split: subtract the middle from 1, halve it for each tail, then the boundaries are
percentiles. Mirrors Try It Now 5.6a and Example 5.6b.

### q17-interpret-the-percentile.php — 3 parts
`N(mu, sigma)` and a percentile `p` (contexts in threes). Parts: (a) `numfunc` — the value `k`;
(b) `choices` — the correct interpretation sentence (p% of the population are `k` units or less);
(c) `choices` — the complementary reading (the remaining `100 - p`% are more than `k`).
**Invariant: on every seed, (a) is `mu + invnormalcdf(p) * sigma`, (b) and (c) are constant, and
the two sentences describe the same computation from opposite sides.** The guide: a percentile is
a rank, not a score — the interpretation sentence has a fixed shape and the direction matters as
much as the units. Mirrors Try It Now 5.4a and Example 5.4c.

### q18-percentile-vs-probability.php — 3 parts
Conceptual, on one `N(mu, sigma)` (contexts in threes). Parts: (a) `choices` — which question is a
`normalcdf` question (ends in "what is the probability"); (b) `choices` — which is an `invNorm`
question (ends in a unit — points, hours, years); (c) `numfunc` — the answer to the probability
question. **Invariant: (a) and (b) are constant across seeds; (c) is the precomputed
`normalcdf` value.** The guide: the skill is deciding which direction the question runs — value
in, probability out, or probability in, value out — and most errors here are answering the wrong
one of the two. Mirrors the section's "Calculations of Probabilities" framing and problem set
5.2.8–5.2.11.

### pre-frq-grade-a-percentile-interpretation.php — 3 parts
The pre-FRQ mirror of `questions/frq/normal-distribution/q12-normal-distribution-and-percentiles.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Formula Application (4)**,
**Threshold Interpretation (3)**, **Population Count (3)**. Drop **"Threshold Interpretation"** — a
student can apply the formula and report the expected count without ever saying what the cutoff
means in context, and the section's own Insight Note ("a percentile is a rank, not a score") is
exactly the step a plausible answer skips. Not in the used table (2.3 Percentile, 2.4 Contextual
Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1
Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the
Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters). **Invariant: read
each of the four responses against every rubric line — no response earns a category it is supposed
to be missing (category-purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with a `.qscope52` class since the pre-FRQ shares the
assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the seven you did not finish, if any — an honest short
list beats seven files where two were rushed.

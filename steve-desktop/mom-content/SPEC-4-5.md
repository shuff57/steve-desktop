# SPEC — §4.5 Continuous Probability Functions (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/probability/uniform/` (new
folder — read `questions/probability/AGENTS.md` and `questions/probability/expected-value/AGENTS.md`
first for the family conventions: white-card UI, blue-chip part labels, `jointrandfrom` parallel
arrays, `numfunc` with `abstolerance = 0.005`, `$noshuffle[N] = "all"` on every `choices` part,
no `essay`, precompute every answer, type picker Multipart). Also read
`mom-content/reference/pre-frq-template.md` before writing the pre-FRQ.

**Scope note:** §4.5 is *continuous probability functions* — the uniform density `f(x) = 1/(b-a)`
on `a <= x <= b`, the two properties (never negative, total area 1), probability as area
(base × height), `P(x = c) = 0` for a single value, the CDF `P(X <= x) = (x-a)/(b-a)`, the
complement `P(X > x) = 1 - P(X < x)`, and the uniform mean/quartiles. The bank has ZERO uniform
questions — every slot is authored fresh. The 4.6 lab (U(0,1) empirical-vs-theoretical) is a
separate assignment; keep this homework on general `U(a,b)` with varied endpoints so the two do
not overlap. No pre-FRQ exists to mirror; per the pre-FRQ rule this one is authored first.

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q1-pdf-legitimate.php` | 2 | 10 |
| 2 | `q2-probability-is-area.php` | 2 | 10 |
| 3 | `q3-single-value-zero.php` | 2 | 10 |
| 4 | `q4-complement-tail.php` | 2 | 10 |
| 5 | `q5-cdf-left-area.php` | 3 | 10 |
| 6 | `q6-uniform-mean-sd.php` | 2 | 9 |
| 7 | `q7-uniform-quartiles.php` | 3 | 9 |
| 8 | `q8-sanity-checks.php` | 3 | 10 |
| 9 | `q9-height-vs-probability.php` | 3 | 10 |
| 10 | `pre-frq-grade-a-uniform-reasoning.php` | 3 | 12 |

Total: 10+10+10+10+10+9+9+10+10+12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the expected-value and binomial families are
clean; the 17 pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]`
has a matching `$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no
article before an interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count. Precompute every answer — never call
`uniformcdf`/`normalcdf` (a wrong macro call renders an empty box with no error).

## The dialect rules (non-negotiable)

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**. Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (homework carries no free response).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic);
  `abstolerance = 0.005`. Never `number`.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The ten

Contexts in 3–5 parallel-array scenarios per question; the invariant decides the count. Every
probability is a precomputed decimal (`(b-a) * (1/(b-a))` style, or `(x-a)/(b-a)`); every
fraction answer is carried as a display string.

### q1-pdf-legitimate.php — 2 parts
`f(x) = 1/(b-a)` on `[a, b]`, `0` elsewhere, for a few (a, b) pairs. Parts: (a) `choices` — does
it satisfy both properties (never negative; total area 1)? (b) `numfunc` — the total area under
the curve. **Invariant: on every seed the total area is exactly 1 and part (a) is Yes.** The
guide checks both properties like the section's Try It Now 4.5.

### q2-probability-is-area.php — 2 parts
A flat density; find `P(c < x < d)` for two interior cut points. Parts: (a) `numfunc` — the
probability (base × height); (b) `choices` — what the height of the curve represents (a density,
not a probability — it only becomes a probability once multiplied by a width). **Invariant: on
every seed the answer is `(d-c) * (1/(b-a))` and lies in (0, 1); part (b) is constant.** The
guide's point: the region is a rectangle, so area = base × height, and the answer is a
probability only because of the width.

### q3-single-value-zero.php — 2 parts
A flat density. Parts: (a) `numfunc` — `P(x = c)` for a named single value; (b) `choices` — why
(no width, so no area; for ANY continuous variable the probability of one exact value is 0).
**Invariant: part (a) is 0 on every seed; part (b) is constant.** The guide notes that this is
why `P(x < c)` and `P(x <= c)` are the same number.

### q4-complement-tail.php — 2 parts
A flat density. Parts: (a) `numfunc` — `P(X < x)` (the left area); (b) `numfunc` — `P(X > x)`
(the complement, `1 - P(X < x)`). **Invariant: the two sum to exactly 1 on every seed.** The
guide: the right-hand region is never measured directly — it is whatever is left over.

### q5-cdf-left-area.php — 3 parts
A flat density with the CDF `P(X <= x) = (x-a)/(b-a)`. Parts: (a) `numfunc` — `P(X <= x1)`;
(b) `numfunc` — `P(X <= x2)`; (c) `numfunc` — `P(x1 < X < x2)` = (b) − (a). **Invariant: on
every seed (c) = (b) − (a) exactly, and all three lie in [0, 1].**

### q6-uniform-mean-sd.php — 2 parts
`X ~ U(a, b)` with clean endpoints. Parts: (a) `numfunc` — `mu = (a+b)/2`; (b) `numfunc` —
`sigma = sqrt((b-a)^2 / 12)`. **Invariant: on every seed the mean is the precomputed midpoint and
sigma = sqrt((b-a)^2/12) to 4 decimals.** Pick endpoints whose (b−a)²/12 is a clean decimal.

### q7-uniform-quartiles.php — 3 parts
`X ~ U(a, b)`. Parts: (a) `numfunc` — first quartile `a + 0.25(b-a)`; (b) `numfunc` — the median
`a + 0.5(b-a)`; (c) `numfunc` — the third quartile `a + 0.75(b-a)`. **Invariant: on every seed
Q1 < median < Q3, and each is the precomputed percentile value.** The guide: on the uniform
distribution the percentile and the value are the same number for U(0,1) — a coincidence, not a
general rule.

### q8-sanity-checks.php — 3 parts
A flat density. Parts: (a) `choices` — which of three computed probabilities is IMPOSSIBLE
(one > 1, one negative, one correct); (b) `numfunc` — the correct probability; (c) `choices` —
which sanity check catches the mistake (the answer came out bigger than 1 / negative / neither —
the section's two checks). **Invariant: on every seed exactly one listed probability is
impossible, part (b) is the correct value in (0, 1), and part (c) names the check that catches
the wrong one.** The guide: if the answer comes out bigger than 1 you subtracted endpoints
backwards; if negative you subtracted in the wrong order.

### q9-height-vs-probability.php — 3 parts
Conceptual. Parts: (a) `choices` — is the height `f(x)` itself a probability? (b) `choices` —
what must you multiply the height by to get a probability? (c) `numfunc` — the actual probability
for a named strip. **Invariant: (a) and (b) are constant; (c) is base × height in (0, 1).** The
guide: the height is a density, a rate of probability per unit of x; it only becomes a probability
once multiplied by a width.

### pre-frq-grade-a-uniform-reasoning.php — 3 parts
Authored-first pre-FRQ (no mirror). Categories, 12 pts: **State the Two Properties (2)** — the
density is never negative and the total area is exactly 1; **Compute the Probability (5)** — find
`P(c < x < d)` as base × height (or `(d-c)/(b-a)`); **Interpret in Context (5)** — read the area
as the probability of the event, and state `P(x = c) = 0` correctly. Dropped category: **State
the Two Properties** (a student can compute the area and interpret it without ever checking the
curve is a legitimate density — the definition's own check, which the other categories imply but
never demand). Not in the used table (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier
Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the
Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1 State the Values,
4.2 Verify the Sum, 4.3 Name the Parameters). Invariant structure per `pre-frq-template.md`:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names. **Invariant: no response earns a category it is supposed to be
missing (category purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with `.qscope45`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

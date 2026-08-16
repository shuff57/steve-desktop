# SPEC — §4.4 Discrete Distribution (Playing Card Experiment) — LAB (Intro Stats -SH)

Write **ten** IMathAS question files into `mom-content/questions/probability/binomial/` (the
4.3 files are the pattern — read `q3-binomial-exact-prob.php`, `q6-binomial-mean-sd.php` and
`q5-binomial-at-least.php` first; also read `questions/probability/AGENTS.md` and
`mom-content/reference/pre-frq-template.md`). Match the family shape: white-card UI, blue-chip
part labels, `jointrandfrom` parallel arrays, `numfunc` with `abstolerance = 0.005`,
`$noshuffle[N] = "all"` on every `choices` part, no `essay`, precompute every answer, type picker
Multipart.

**Scope note:** §4.4 is a LAB, not a reading — the student draws 10 cards with replacement and
compares the empirical results to the theoretical `X ~ B(10, 0.25)`. The MOM assignment checks the
THEORETICAL half (which is fixed) and gives a FIXED class dataset (the section's own 30-group
example: 2 groups at 0, 5 at 1, 9 at 2, 7 at 3, 4 at 4, 2 at 5, 1 at 6) for the EMPIRICAL half —
so every answer is deterministic and precomputable. The student still does the hands-on drawing
in class; this checks the numbers. Kind is `lab` (3 attempts × 20 versions, no penalty, no
early-finish bonus, GROUP gradebook category, 2 late passes, no time limit — per
`reference/intro-stats-assessment-settings.md`).

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q12-theoretical-probability.php` | 2 | 10 |
| 2 | `q13-with-vs-without-replacement.php` | 2 | 10 |
| 3 | `q14-empirical-rf.php` | 2 | 10 |
| 4 | `q15-empirical-mean.php` | 1 | 10 |
| 5 | `q16-empirical-sd.php` | 1 | 10 |
| 6 | `q17-theoretical-pdf.php` | 2 | 10 |
| 7 | `q18-theoretical-mean-sd.php` | 2 | 10 |
| 8 | `q19-theoretical-probs.php` | 2 | 10 |
| 9 | `q20-expected-count.php` | 2 | 10 |
| 10 | `pre-frq-grade-a-lab-comparison.php` | 3 | 10 |

Total: 10×10 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files. Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count. Precompute every answer.

## The dialect rules (non-negotiable)

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**. Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (labs carry no free response either — the discussion questions
  are answered in class, not graded here).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic);
  `abstolerance = 0.005`. Never `number`.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The ten

The fixed class dataset (the section's Try It Now 4.4/4.5/4.10): 30 groups each ran the
experiment once — 2 groups got 0 diamonds, 5 got 1, 9 got 2, 7 got 3, 4 got 4, 2 got 5, 1 got 6.
Every empirical answer below is computed from THIS dataset, so it is deterministic.

### q12-theoretical-probability.php — 2 parts
The lab's first blank. Parts: (a) `numfunc` — `P(diamond) = 13/52 = 0.25`; (b) `choices` — the
distribution of `X`, the number of diamonds in 10 replaced draws (`X ~ B(10, 0.25)`). **Invariant:
(a) is 0.25 on every seed; (b) is constant.** The guide: 13 diamonds in 52 cards, and the card
goes back before the next draw so every draw faces the same deck — sampling with replacement is
what makes it binomial.

### q13-with-vs-without-replacement.php — 2 parts
The lab's Try It Now 4.3. Parts: (a) `numfunc` — `P(both diamonds)` WITH replacement
`(13/52)(13/52) = 0.0625`; (b) `numfunc` — WITHOUT replacement `(13/52)(12/51) ≈ 0.0588`.
**Invariant: (a) = 0.0625 and (b) ≈ 0.0588 on every seed.** The guide: the gap looks small on two
draws but is not small in principle — without replacement the second draw's probability depends on
the first, and dependence is the one thing the binomial model is not allowed to have.

### q14-empirical-rf.php — 2 parts
The lab's Organize-the-Data step on the fixed dataset. Parts: (a) `numfunc` — `RF(x = 2) =
9/30 = 0.3000`; (b) `numfunc` — `RF(x = 3) = 7/30 ≈ 0.2333`. **Invariant: (a) = 0.3 and
(b) ≈ 0.2333 on every seed.** The guide: relative frequency is empirical — measured, not derived —
and the RF column must sum to 1.0000.

### q15-empirical-mean.php — 1 part
`numfunc` — the sample mean of the fixed dataset: `x̄ = 76/30 ≈ 2.5333`. **Invariant: ≈ 2.5333
on every seed.** The guide: the mean sits within a rounding error of the theoretical 2.5 — what
pooling 30 repetitions buys you; no single group saw 2.5.

### q16-empirical-sd.php — 1 part
`numfunc` — the sample standard deviation of the fixed dataset: `s ≈ 1.4559` (divide by n−1, not
n — this is a sample). **Invariant: ≈ 1.4559 on every seed.** The guide: hold it beside the
theoretical σ ≈ 1.3693 — the sample came out slightly more spread out, the ordinary behavior of
30 repetitions.

### q17-theoretical-pdf.php — 2 parts
The lab's Theoretical-Distribution step. Parts: (a) `numfunc` — `P(X = 2) = C(10,2)(0.25)²(0.75)⁸
≈ 0.2816`; (b) `numfunc` — `P(X = 0) = (0.75)¹⁰ ≈ 0.0563`. **Invariant: ≈ 0.2816 and ≈ 0.0563 on
every seed.** The guide: the eleven entries of the P(x) column must add to exactly 1 — use it as
your check.

### q18-theoretical-mean-sd.php — 2 parts
The lab's part-b blanks. Parts: (a) `numfunc` — `μ = np = 10(0.25) = 2.5`; (b) `numfunc` —
`σ = sqrt(npq) = sqrt(1.875) ≈ 1.3693`. **Invariant: (a) = 2.5 and (b) ≈ 1.3693 on every seed.**
The guide: compare μ to the class's x̄ and σ to s — same question, two directions, formula vs
cards.

### q19-theoretical-probs.php — 2 parts
The lab's Using-the-Data step. Parts: (a) `numfunc` — `P(1 < x < 4) = P(2) + P(3) = 0.2816 +
0.2503 = 0.5319`; (b) `numfunc` — `P(x ≥ 8) = P(8) + P(9) + P(10) ≈ 0.0004`. **Invariant:
(a) ≈ 0.5319 and (b) ≈ 0.0004 on every seed.** The guide: read the inequalities carefully —
`1 < x < 4` is strict on both ends (covers 2 and 3 only); `x ≥ 8` includes 8.

### q20-expected-count.php — 2 parts
The lab's Try It Now 4.8. Parts: (a) `numfunc` — the expected number of groups at exactly 2
diamonds: `30 × 0.2816 = 8.448`; (b) `numfunc` — the observed count from the fixed dataset (9).
**Invariant: (a) ≈ 8.448 and (b) = 9 on every seed.** The guide: the expected count is not a
whole number even though the observed count has to be — and this is the arithmetic that turns
"the graphs look similar" into something you can defend.

### pre-frq-grade-a-lab-comparison.php — 3 parts
Authored-first pre-FRQ (no lab FRQ to mirror). Categories, 10 pts: **State the Theoretical Value
(3)** — the P(x) from the formula; **State the Empirical Value (3)** — the RF(x) from the class
data; **Compare and Explain the Gap (4)** — how close they are and why they are not exact.
Dropped category: **State the Theoretical Value** (a student can report the empirical RF and
compare it without ever stating what the theory predicts — the comparison then has no standard).
Not in the used table (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6
Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3
Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify
the Sum, 4.3 Name the Parameters). Invariant structure per `pre-frq-template.md`:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names. **Invariant: no response earns a category it is supposed to be
missing (category purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with `.qscope44`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

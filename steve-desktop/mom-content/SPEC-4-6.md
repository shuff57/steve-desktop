# SPEC — §4.6 Continuous Distribution — LAB (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/probability/uniform/` (the
4.5 files are the pattern — read `q6-uniform-mean-sd.php`, `q7-uniform-quartiles.php` and
`q4-complement-tail.php` first; also read `questions/probability/AGENTS.md` and
`mom-content/reference/pre-frq-template.md`). Match the family shape: white-card UI, blue-chip
part labels, `jointrandfrom` parallel arrays, `numfunc` with `abstolerance = 0.005`,
`$noshuffle[N] = "all"` on every `choices` part, no `essay`, precompute every answer, type picker
Multipart.

**Scope note:** §4.6 is a LAB — the student generates 50 values from U(0,1) and compares the
empirical statistics to the theoretical ones. The MOM assignment checks the THEORETICAL half
(which is fixed: μ = 0.5, σ ≈ 0.2887, Q1 = 0.25, median = 0.5, Q3 = 0.75, fences −0.5 and 1.5)
and gives a FIXED 12-value pilot dataset (the section's Try It Now 4.10/4.11/4.6: 0.0412, 0.1187,
0.2043, 0.2765, 0.3391, 0.4508, 0.5624, 0.6130, 0.7042, 0.8219, 0.8873, 0.9564) for the
EMPIRICAL half — so every answer is deterministic and precomputable. The student still generates
their own 50 values in class; this checks the numbers. Kind is `lab` (3 attempts × 20 versions,
no penalty, no early-finish bonus, GROUP gradebook category, 2 late passes, no time limit — per
`reference/intro-stats-assessment-settings.md`).

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q10-uniform-theoretical.php` | 2 | 10 |
| 2 | `q11-uniform-quartiles-theoretical.php` | 3 | 10 |
| 3 | `q12-pilot-median-quartiles.php` | 3 | 10 |
| 4 | `q13-pilot-mean.php` | 1 | 10 |
| 5 | `q14-pilot-sd.php` | 1 | 10 |
| 6 | `q15-bar-widths.php` | 2 | 10 |
| 7 | `q16-fences.php` | 2 | 10 |
| 8 | `q17-compare-pilot.php` | 3 | 10 |
| 9 | `q18-500-vs-50.php` | 2 | 10 |
| 10 | `pre-frq-grade-a-uniform-lab-comparison.php` | 3 | 10 |

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

The fixed pilot dataset (the section's Try It Now 4.10/4.11/4.6): twelve sorted values —
0.0412, 0.1187, 0.2043, 0.2765, 0.3391, 0.4508, 0.5624, 0.6130, 0.7042, 0.8219, 0.8873, 0.9564.
Every empirical answer below is computed from THIS dataset, so it is deterministic.

### q10-uniform-theoretical.php — 2 parts
The lab's Theoretical-Distribution blanks. Parts: (a) `numfunc` — `mu = (0+1)/2 = 0.5`;
(b) `numfunc` — `sigma = sqrt(1/12) ≈ 0.2887`. **Invariant: (a) = 0.5 and (b) ≈ 0.2887 on every
seed.** The guide: every number comes out of a = 0 and b = 1, and nothing else — the generator
never gets consulted.

### q11-uniform-quartiles-theoretical.php — 3 parts
The lab's quartile blanks. Parts: (a) `numfunc` — Q1 = 0.25; (b) `numfunc` — median = 0.5;
(c) `numfunc` — Q3 = 0.75. **Invariant: (a) = 0.25, (b) = 0.5, (c) = 0.75 on every seed.** The
guide: on U(0,1) the CDF is P(X ≤ x) = x, so the percentile and the value are the same number —
a coincidence of U(0,1), not a general rule.

### q12-pilot-median-quartiles.php — 3 parts
The lab's Collect-the-Data step on the pilot dataset. Parts: (a) `numfunc` — median =
(0.4508 + 0.5624)/2 = 0.5066; (b) `numfunc` — Q1 = (0.2043 + 0.2765)/2 = 0.2404; (c) `numfunc` —
Q3 = (0.7042 + 0.8219)/2 = 0.7631. **Invariant: ≈ 0.5066, ≈ 0.2404, ≈ 0.7631 on every seed.**
The guide: a quartile is a location in the data, not a formula applied to it — with 12 values the
median is the average of the 6th and 7th, Q1 the median of the lowest 6, Q3 the median of the
highest 6.

### q13-pilot-mean.php — 1 part
`numfunc` — the pilot mean: 5.9758/12 ≈ 0.4980. **Invariant: ≈ 0.4980 on every seed.** The
guide: the theoretical mean is 0.5000, so this sample landed within 0.002 of it — one run, and a
different twelve values could easily have come out at 0.44 or 0.56.

### q14-pilot-sd.php — 1 part
`numfunc` — the pilot sample standard deviation: s ≈ 0.3078 (divide by n−1, not n — a sample).
**Invariant: ≈ 0.3078 on every seed.** The guide: hold it beside the theoretical σ ≈ 0.2887 —
about 7% more spread out, and s drifted further from σ than x̄ did from μ because a standard
deviation is built from squared distances.

### q15-bar-widths.php — 2 parts
The lab's Organize-the-Data step. Parts: (a) `numfunc` — the width of each of 8 bars over [0,1]:
1/8 = 0.125; (b) `numfunc` — the expected count per bar for 50 values: 50 × 0.125 = 6.25.
**Invariant: (a) = 0.125 and (b) = 6.25 on every seed.** The guide: no bar can hold a quarter of
a value — an expected count is an average over many reruns, which is why a bar holding 4 or 9 is
not evidence the generator is broken.

### q16-fences.php — 2 parts
The lab's Plot-the-Data step. Parts: (a) `numfunc` — the lower fence Q1 − 1.5(IQR) = 0.25 −
1.5(0.50) = −0.50; (b) `numfunc` — the upper fence Q3 + 1.5(IQR) = 0.75 + 1.5(0.50) = 1.50.
**Invariant: (a) = −0.50 and (b) = 1.50 on every seed.** The guide: both fences sit outside
[0,1], so no value this generator can produce could ever be flagged as a potential outlier — a
real property of the uniform distribution, not a quirk of the numbers.

### q17-compare-pilot.php — 3 parts
The lab's Compare-the-Data step on the pilot dataset. Parts: (a) `numfunc` — the pilot IQR:
0.7631 − 0.2404 = 0.5227; (b) `numfunc` — the gap between the pilot Q1 and the theoretical 0.25:
0.25 − 0.2404 = 0.0096; (c) `numfunc` — the gap between the pilot Q3 and the theoretical 0.75:
0.7631 − 0.75 = 0.0131. **Invariant: (a) ≈ 0.5227, (b) ≈ 0.0096, (c) ≈ 0.0131 on every seed.**
The guide: both quartiles drifted outward, so of course the IQR grew — reporting the IQR gap as a
separate surprise when it is just the sum of the two quartile gaps is the most common way this
part gets written up wrong.

### q18-500-vs-50.php — 2 parts
The lab's Discussion Question. Parts: (a) `choices` — which column changes when you generate 500
values instead of 50 (the empirical one; the theoretical never looks at the generator);
(b) `numfunc` — the expected count per bar with 8 bars and 500 values: 500 × 0.125 = 62.5.
**Invariant: (a) is constant; (b) = 62.5 on every seed.** The guide: with 500 values every
statistic settles closer to its theoretical partner and the histogram flattens toward a rectangle
— the whole meaning of a continuous distribution's parameters.

### pre-frq-grade-a-uniform-lab-comparison.php — 3 parts
Authored-first pre-FRQ (no lab FRQ to mirror). Categories, 10 pts: **State the Theoretical Value
(3)** — the U(0,1) parameter; **State the Empirical Value (3)** — the pilot statistic;
**Compare and Explain the Gap (4)** — how close they are and why they are not exact. Dropped
category: **State the Theoretical Value** (a student can report the empirical statistic and
compare it without ever stating what the theory predicts — the comparison then has no standard).
This is the SAME dropped category as the 4.4 lab pre-FRQ (State the Theoretical Value) — the two
labs are a matched pair and the lesson is the same (empirical vs theoretical), so the repeat is
deliberate; note it in the manifest `_note`. Invariant structure per `pre-frq-template.md`:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names. **Invariant: no response earns a category it is supposed to be
missing (category purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with `.qscope46`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

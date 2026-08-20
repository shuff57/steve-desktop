# SPEC — §5.3 Normal Distribution (Lap Times) — LAB (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/normal-distribution/` (the
5.1/5.2 files are the pattern — read `q2-normal-probability.php`, `q3-inverse-normal-percentile.php`
and `q1-z-score-compute.php` first; also read `questions/normal-distribution/AGENTS.md`,
`questions/probability/AGENTS.md` and `mom-content/reference/pre-frq-template.md`). Match the
family shape: white-card UI, blue-chip part labels, parallel-array scenarios, `numfunc` with the
family tolerances, `$noshuffle[N] = "all"` on every `choices` part, no `essay`, precompute every
answer, type picker Multipart.

**Scope note:** §5.3 is a LAB, not a reading — the student stratifies a sample of 36 lap times
(six per lap, laps 2–7) from the Appendix C dataset (Terri Vogel's 20-race logbook), builds a
histogram, computes the empirical five-number summary and percentiles by counting, then answers
the SAME questions from a theoretical normal model and compares. The MOM assignment checks the
THEORETICAL half (which is fixed) and gives a FIXED dataset (the section's own Try It Now 5.4
pilot: 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1) for
the EMPIRICAL half — so every answer is deterministic and precomputable. The theoretical model is
the pilot's OWN `N(x-bar, s)` (the lab's Analyze-the-Distribution step says the model comes from
the sample): `x-bar = 129.42`, `s = 2.52`. The student still does the hands-on sampling in class;
this checks the numbers. Kind is `lab` (3 attempts × 20 versions, no penalty, no early-finish
bonus, GROUP gradebook category, 2 late passes, no time limit — per
`reference/intro-stats-assessment-settings.md`).

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q21-empirical-vs-theoretical.php` | 3 | 10 |
| 2 | `q22-pilot-mean-sd.php` | 2 | 10 |
| 3 | `q23-pilot-median-quartiles.php` | 3 | 10 |
| 4 | `q24-pilot-percentiles.php` | 2 | 10 |
| 5 | `q25-pilot-iqr.php` | 1 | 10 |
| 6 | `q26-theoretical-median-quartiles.php` | 3 | 10 |
| 7 | `q27-theoretical-percentiles.php` | 2 | 10 |
| 8 | `q28-theoretical-iqr.php` | 1 | 10 |
| 9 | `q29-compare-empirical-theoretical.php` | 3 | 10 |
| 10 | `pre-frq-grade-a-normal-model-comparison.php` | 3 | 10 |

Total: 10×10 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the normal-distribution family is clean; the 17
pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count. Precompute every answer — the empirical
ones by hand from the fixed dataset, the theoretical ones from `N(129.42, 2.52)`.

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

## The ten

The fixed pilot dataset (the section's Try It Now 5.4): twelve sorted lap times in seconds —
125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1. Every
empirical answer below is computed from THIS dataset, so it is deterministic. The theoretical
model is the pilot's own `N(129.42, 2.52)` (x-bar = 1553.0/12 = 129.4167, s = sqrt(69.896/11) =
2.5207), per the lab's own instruction that the model comes from the sample.

### q21-empirical-vs-theoretical.php — 3 parts
The lab's opening distinction (Try It Now 5.1 shape, on the pilot). Parts: (a) `numfunc` — the
empirical probability that a pilot lap time is more than 130 seconds: 5/12 = 0.4167; (b) `numfunc`
— the theoretical probability from `N(129.42, 2.52)`: z = (130 − 129.4167)/2.5207 ≈ 0.2314,
P(Z > 0.2314) ≈ 0.4085; (c) `choices` — which is which and why they differ (the empirical one is
a fact about the 12 sampled values; the theoretical one is a claim about every lap the model
describes). **Invariant: (a) = 0.4167, (b) ≈ 0.4085, (c) is constant on every seed.** The guide:
the gap between the two answers is the finding, not an error to be explained away.

### q22-pilot-mean-sd.php — 2 parts
The lab's Collect-the-Data step on the pilot. Parts: (a) `numfunc` — x-bar = 1553.0/12 ≈ 129.42;
(b) `numfunc` — s ≈ 2.52 (divide by n−1, not n — a sample). **Invariant: ≈ 129.42 and ≈ 2.52 on
every seed.** The guide: the mean sits near the middle of the sorted list, and the SD is small
next to the mean — a racer that consistent is exactly the kind of process a normal model has a
chance of describing well.

### q23-pilot-median-quartiles.php — 3 parts
The lab's Describe-the-Data step on the pilot. Parts: (a) `numfunc` — median = (128.9 + 129.4)/2
= 129.15; (b) `numfunc` — Q1 = (127.1 + 127.8)/2 = 127.45; (c) `numfunc` — Q3 = (130.8 + 131.5)/2
= 131.15. **Invariant: ≈ 129.15, ≈ 127.45, ≈ 131.15 on every seed.** The guide: with 12 values
the median is the average of the 6th and 7th, Q1 the median of the lowest 6, Q3 the median of the
highest 6.

### q24-pilot-percentiles.php — 2 parts
The lab's percentile blanks on the pilot, by the index rule `i = (k/100)(n + 1)`. Parts: (a)
`numfunc` — P15 = (125.9 + 126.4)/2 = 126.15; (b) `numfunc` — P85 = (132.6 + 134.1)/2 = 133.35.
**Invariant: ≈ 126.15 and ≈ 133.35 on every seed.** The guide: i = 1.95 for P15 and 11.05 for P85,
so each is the average of the two bracketing values.

### q25-pilot-iqr.php — 1 part
`numfunc` — the pilot IQR = 131.15 − 127.45 = 3.70. **Invariant: ≈ 3.70 on every seed.** The
guide: the IQR is a width, not a location — and it is the empirical number the theoretical model
will be held against.

### q26-theoretical-median-quartiles.php — 3 parts
The lab's Theoretical-Distribution step on `N(129.42, 2.52)`. Parts: (a) `numfunc` — the median
= 129.42 (a normal curve is symmetric about its mean); (b) `numfunc` — Q1 = 129.4167 −
0.6745(2.5207) ≈ 127.72; (c) `numfunc` — Q3 = 129.4167 + 0.6745(2.5207) ≈ 131.12. **Invariant:
(a) = 129.42, (b) ≈ 127.72, (c) ≈ 131.12 on every seed.** The guide: the quartiles are the 25th
and 75th percentiles, `mu +/- 0.6745 sigma`.

### q27-theoretical-percentiles.php — 2 parts
The lab's percentile blanks on the model. Parts: (a) `numfunc` — P15 = 129.4167 − 1.0364(2.5207)
≈ 126.80; (b) `numfunc` — P85 = 129.4167 + 1.0364(2.5207) ≈ 132.03. **Invariant: ≈ 126.80 and
≈ 132.03 on every seed.** The guide: the z-score with 15% of the area below it is −1.0364, and by
symmetry the 85th percentile sits at +1.0364.

### q28-theoretical-iqr.php — 1 part
`numfunc` — the theoretical IQR = 131.12 − 127.72 = 3.40. **Invariant: ≈ 3.40 on every seed.**
The guide: the theoretical IQR is exactly 1.349σ — the same multiple of σ for every normal
distribution — so once you commit to the model, the spacing between the quartiles is no longer
something the data gets to decide.

### q29-compare-empirical-theoretical.php — 3 parts
The lab's Discussion Question, on the fixed numbers. Parts: (a) `numfunc` — the gap between the
empirical median (129.15) and the theoretical median (129.42): 0.27; (b) `numfunc` — the gap
between the empirical IQR (3.70) and the theoretical IQR (3.40): 0.30; (c) `choices` — does the
pilot data give a close approximation to the theoretical model (yes — every pair lands within a
few tenths of a second, and the empirical P(X > 130) = 0.4167 sits within 0.01 of the theoretical
0.4085). **Invariant: (a) ≈ 0.27, (b) ≈ 0.30, (c) is constant on every seed.** The guide: a gap
of a tenth of a second is agreement; the specific comparison that convinces is the one you name,
not a general impression.

### pre-frq-grade-a-normal-model-comparison.php — 3 parts
Authored-first pre-FRQ (no lab FRQ to mirror). Categories, 10 pts: **State the Empirical Value
(3)** — the counted statistic from the pilot; **State the Theoretical Value (3)** — the model's
value from `N(129.42, 2.52)`; **Compare and Explain the Gap (4)** — how close they are and why
they are not exact. Dropped category: **State the Empirical Value** (a student can report the
theoretical value and compare it without ever doing the counting — the section's own text says
the theoretical model "genuinely outperforms counting", so the empirical step is the one a
plausible answer skips; the comparison then has no evidence behind it). This is DIFFERENT from
the 4.4/4.6 lab pre-FRQs' dropped category (State the Theoretical Value) — the 4.4/4.6 pair
shares a drop deliberately, but a third repeat would teach the same lesson again; note the
difference in the manifest `_note`. Invariant structure per `pre-frq-template.md`:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names. **Invariant: no response earns a category it is supposed to be
missing (category purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with `.qscope53`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

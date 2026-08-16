# SPEC — §6.4 Central Limit Theorem (Pocket Change) — LAB (Intro Stats -SH)

Write **ten** IMathAS question files into `mom-content/questions/sampling-distributions/` (the
6.1–6.3 files are the pattern — read `q4-x-bar-probability.php` and `q5-clt-single-vs-sample.php`
first; also read `questions/sampling-distributions/AGENTS.md`, `questions/probability/AGENTS.md`
and `mom-content/reference/pre-frq-template.md`). Match the family shape: white-card UI,
blue-chip part labels, `jointrandfrom` parallel arrays, `numfunc` with the family tolerances,
`$noshuffle[N] = "all"` on every `choices` part, no `essay`, precompute every answer, type
picker Multipart.

**Scope note:** §6.4 is a LAB, not a reading — the student collects 30 pocket-change amounts,
sketches the (deliberately lopsided) population histogram, then builds the sampling distribution
of the mean by averaging pairs and groups of five, and watches the shape turn toward a bell. The
MOM assignment checks the numbers on the section's OWN class dataset (Table 6.4.2: 30 sorted
amounts, total $21.00, sum of squares 22.3092, so `x-bar = 0.70` and `s ~ 0.5122`), which is
fixed — so every answer is deterministic and precomputable. The student still does the hands-on
collecting in class; this checks the numbers. Kind is `lab` (3 attempts × 20 versions, no
penalty, no early-finish bonus, GROUP gradebook category, 2 late passes, no time limit — per
`reference/intro-stats-assessment-settings.md`).

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q28-headcount.php` | 1 | 10 |
| 2 | `q29-population-mean-sd.php` | 2 | 10 |
| 3 | `q30-population-median-shape.php` | 3 | 10 |
| 4 | `q31-pair-averages.php` | 2 | 10 |
| 5 | `q32-predicted-pair-se.php` | 1 | 10 |
| 6 | `q33-group-five-average.php` | 1 | 10 |
| 7 | `q34-standard-error-five.php` | 1 | 10 |
| 8 | `q35-z-scores-compared.php` | 3 | 10 |
| 9 | `q36-x-bar-model.php` | 2 | 10 |
| 10 | `pre-frq-grade-a-clt-lab-comparison.php` | 3 | 10 |

Total: 10×10 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the sampling-distributions family is clean; the
17 pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a
matching `$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article
before an interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count. Precompute every answer by hand from the
fixed dataset.

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
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.
- **Money formatting:** answers in dollars are plain decimals (0.70, 0.51); the `&#36;` sign
  lives in the display text, never in the answer.

## The ten

The fixed class dataset (the section's Table 6.4.2): 30 pocket-change amounts in dollars, sorted
— 0.05, 0.11, 0.13, 0.17, 0.22, 0.25, 0.28, 0.31, 0.34, 0.36, 0.42, 0.45, 0.47, 0.53, 0.56,
0.61, 0.63, 0.68, 0.72, 0.79, 0.85, 0.91, 0.97, 1.06, 1.14, 1.23, 1.38, 1.52, 1.76, 2.10.
Total $21.00, sum of squares 22.3092. Every empirical answer below is computed from THIS dataset,
so it is deterministic.

### q28-headcount.php — 1 part
The lab's opening arithmetic (Try It Now 6.1). Part: (a) `numfunc` — 30 singles + 30 pairs + 30
groups of five = 30 + 60 + 150 = **240** people. **Invariant: 240 on every seed.** The guide:
that number is not a detail of the setup, it is the reason the instructions say to combine data
from several classes — a class of 30 cannot produce that on its own.

### q29-population-mean-sd.php — 2 parts
The lab's Collect-the-Data step on the fixed dataset. Parts: (a) `numfunc` — x-bar = 21.00/30 =
**0.70**; (b) `numfunc` — s = sqrt((22.3092 - 30(0.70)^2)/29) = sqrt(7.6092/29) ~= **0.5122**.
**Invariant: ~ 0.70 and ~ 0.5122 on every seed.** The guide: divide by n−1, not n — these 30 are
a sample of a much larger population of pockets, not the whole of it.

### q30-population-median-shape.php — 3 parts
The lab's shape analysis (Try It Now 6.2). Parts: (a) `numfunc` — the median = (0.56 + 0.61)/2 =
**0.585**; (b) `choices` — the mean sits ABOVE the median (the arithmetic signature of a right
skew); (c) `choices` — the shape of the population (right-skewed, no named family — bunched
against zero because nobody carries a negative amount of change, trailing right because there is
no upper limit). **Invariant: ~ 0.585, (b) and (c) constant on every seed.** The guide: pocket
change is lopsided on purpose — a population that was already bell-shaped would prove nothing.

### q31-pair-averages.php — 2 parts
The lab's pair-averaging step (Try It Now 6.3). Parts: (a) `numfunc` — the average of the pair
($0.13, $1.52): (0.13 + 1.52)/2 = **0.825**; (b) `numfunc` — the average of the pair ($0.45,
$0.72): (0.45 + 0.72)/2 = **0.585**. **Invariant: ~ 0.825 and ~ 0.585 on every seed.** The
guide: watch what happened to the $1.52 — on its own it sat well out in the right tail; paired
with a thirteen-cent pocket it produced an average barely above the middle. Averaging pulls the
extremes in.

### q32-predicted-pair-se.php — 1 part
The lab's prediction (Try It Now 6.3 step 3). Part: (a) `numfunc` — the predicted standard
deviation of the pair averages: sigma/sqrt(2) = 0.5122/sqrt(2) ~= **0.3622**. **Invariant: ~
0.3622 on every seed.** The guide: the pair histogram should be about sqrt(2) ~ 1.41 times
narrower than the first one — same center, tighter spread, and the shape already moving toward a
bell.

### q33-group-five-average.php — 1 part
The lab's group-of-five step (Try It Now 6.4 step 1). Part: (a) `numfunc` — the average of the
group ($0.05, $0.28, $0.63, $1.06, $2.10): 4.12/5 = **0.824**. **Invariant: ~ 0.824 on every
seed.** The guide: the $2.10 extreme is still in there — it is just sharing its slot with four
partners.

### q34-standard-error-five.php — 1 part
The lab's standard error for n = 5 (Try It Now 6.4 step 2). Part: (a) `numfunc` —
sigma/sqrt(5) = 0.5122/sqrt(5) ~= **0.2291**. **Invariant: ~ 0.2291 on every seed.** The guide:
five times the work buys less than half the spread — going from single people to groups of five
divides the spread by sqrt(5) ~ 2.24, not by 5.

### q35-z-scores-compared.php — 3 parts
The lab's z-score comparison (Try It Now 6.4 step 3). Parts: (a) `numfunc` — the z-score of the
individual $2.10 against sigma: (2.10 − 0.70)/0.5122 ~= **2.73**; (b) `numfunc` — the z-score of
the group average $0.824 against the standard error: (0.824 − 0.70)/0.2291 ~= **0.54**; (c)
`choices` — which number you divide by says which question you asked (individual vs average).
**Invariant: ~ 2.73 and ~ 0.54, (c) constant on every seed.** The guide: the same group of people
is a genuine outlier when you look at its largest member and thoroughly ordinary when you look at
its average — using sigma where the standard error belongs always makes a result look less
surprising than it really is.

### q36-x-bar-model.php — 2 parts
The lab's discussion questions 2 and 3 (Try It Now 6.5). Parts: (a) `choices` — the population
distribution (right-skewed, no named family — the honest answer is that it does not belong to any
distribution you have a name for); (b) `numfunc` — the model for the averages: N(0.70,
0.5122/sqrt(5)) ~= N(0.70, **0.23**). **Invariant: (a) constant, (b) ~ 0.2291 on every seed.**
The guide: same center, different family, and a spread cut by a factor of sqrt(5) — averaging
changed the shape and the spread without moving the middle, which is the finding the lab was
built to produce.

### pre-frq-grade-a-clt-lab-comparison.php — 3 parts
Authored-first pre-FRQ (no lab FRQ to mirror; the CLT-reasoning, sum-interpretation and
continuity-correction pre-FRQs are claimed by §6.1/§6.2/§6.3). Categories, 10 pts: **State the
Empirical Value (3)** — the statistic from the class's 30 amounts; **State the Theoretical Value
(3)** — what the CLT predicts for the averages; **Compare and Explain the Gap (4)** — how close
the pair/group averages land to the prediction and why they are not exact. Dropped category:
**State the Empirical Value** (a student can state what the CLT predicts and compare it without
ever doing the counting — the section's own text says "the thing you are building is not the
data", so the empirical step is the one a plausible answer skips; the comparison then has no
evidence behind it). This is DIFFERENT from the 4.4/4.6/5.3 lab pre-FRQs' dropped category (State
the Theoretical Value for 4.4/4.6, State the Empirical Value for 5.3) — the 6.4 lab teaches the
CLT mechanism, not the empirical-vs-theoretical pair, so neither repeat is right; note the
difference in the manifest `_note`. Invariant structure per `pre-frq-template.md`:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names. **Invariant: no response earns a category it is supposed to be
missing (category purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with `.qscope64`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

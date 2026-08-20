# SPEC — §7.4 Confidence Interval (Home Costs) — LAB (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/stats-tests/confidence-intervals/`
(read `q4-ci-mean-t.php` and `q6-ci-mean-t-story.php` first — the t-interval pattern — plus
`questions/stats-tests/AGENTS.md`, `questions/probability/AGENTS.md` and
`mom-content/reference/pre-frq-template.md`). Match the family shape: white-card UI, blue-chip
part labels, `jointrandfrom` parallel arrays, `numfunc` with the family tolerances,
`$noshuffle[N] = "all"` on every `choices` part, no `essay`, precompute every answer, type
picker Multipart.

**Scope note:** §7.4 is a LAB, not a reading — the student collects 35 home sale prices from a
newspaper, computes the sample mean and standard deviation, builds a t-interval (sigma is
unknown, so §7.2's machinery applies), interprets it, and fills a table of intervals at four
confidence levels. The MOM assignment checks the numbers on the section's OWN demonstration
dataset (Table 7.4.1: 35 sorted prices, sum $14,350,000, sum of squares 6,317,692 x 10^6, so
`x-bar = $410,000`, `s ~ $113,006`, `n = 35`, `df = 34`), which is fixed — so every answer is
deterministic and precomputable. The student still does the hands-on collecting in class; this
checks the numbers. Kind is `lab` (3 attempts × 20 versions, no penalty, no early-finish bonus,
GROUP gradebook category, 2 late passes, no time limit — per
`reference/intro-stats-assessment-settings.md`).

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q28-name-the-four-pieces.php` | 4 | 10 |
| 2 | `q29-home-summary-stats.php` | 3 | 10 |
| 3 | `q30-home-standard-error.php` | 1 | 10 |
| 4 | `q31-home-90-interval.php` | 3 | 10 |
| 5 | `q32-home-alpha-tails.php` | 2 | 10 |
| 6 | `q33-count-inside-interval.php` | 2 | 10 |
| 7 | `q34-interpret-the-home-interval.php` | 3 | 10 |
| 8 | `q35-four-confidence-levels.php` | 4 | 10 |
| 9 | `q36-ebm-trend.php` | 2 | 10 |
| 10 | `pre-frq-grade-a-home-costs-interval.php` | 3 | 10 |

Total: 10×10 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the confidence-intervals family is clean; the
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
- **Money formatting:** answers in dollars are plain numbers (410000, 113006); the `&#36;` sign
  and commas live in the display text, never in the answer. `prettyint()` for display.

## The ten

The fixed demonstration dataset (the section's Table 7.4.1): 35 home sale prices in dollars,
sorted — 259000, 272000, 285000, 299000, 305000, 312000, 319000, 325000, 330000, 338000,
345000, 349000, 355000, 362000, 368000, 375000, 379000, 385000, 392000, 399000, 405000, 412000,
420000, 429000, 438000, 449000, 462000, 475000, 489000, 505000, 525000, 549000, 585000, 659000,
795000. Sum $14,350,000, sum of squares 6,317,692 x 10^6. Every answer below is computed from
THIS dataset, so it is deterministic.

### q28-name-the-four-pieces.php — 4 parts
The lab's opening naming exercise (Try It Now 7.1). Parts: (a) `choices` — the population (every
home recently listed for sale in Butte County); (b) `choices` — the sample (the 35 prices in
Table 7.4.1); (c) `choices` — the parameter (mu, the true mean sale price over that entire
population); (d) `choices` — the statistic (bar(x), the mean of the 35 prices). **Invariant: all
four answers are constant on every seed.** The guide: the parameter cannot be looked up because
computing it would mean recording the price of every listing in the county on the same day — the
census that sampling exists to avoid.

### q29-home-summary-stats.php — 3 parts
The lab's Describe-the-Data step (Try It Now 7.3). Parts: (a) `numfunc` — x-bar = 14,350,000/35
= **410000**; (b) `numfunc` — s = sqrt(434,192 x 10^6 / 34) ~= **113006**; (c) `numfunc` — n =
**35**. **Invariant: ~ 410000, ~ 113006, 35 on every seed.** The guide: the standard deviation
is more than a quarter of the mean — the county's listings are spread across a very wide range of
prices, and that spread is what will make the interval as wide as it turns out to be.

### q30-home-standard-error.php — 1 part
The lab's standard error (Try It Now 7.5 step 1). Part: (a) `numfunc` — s/sqrt(n) =
113006/sqrt(35) ~= **19101**. **Invariant: ~ 19101 on every seed.** The guide: the standard
error measures how much the sample mean varies from sample to sample, and it depends only on the
data — not on the confidence level. Computing it once first saves arithmetic all the way through
the four-level table.

### q31-home-90-interval.php — 3 parts
The lab's 90% interval (Try It Now 7.5). Parts: (a) `numfunc` — the error bound
EBM = 1.6909 x 19101 ~= **32298**; (b) `numfunc` — the lower endpoint 410000 - 32298 =
**377702**; (c) `numfunc` — the upper endpoint 410000 + 32298 = **442298**. **Invariant: ~
32298, ~ 377702, ~ 442298 on every seed.** The guide: the width came from the $113,006 standard
deviation divided by the square root of only 35 homes — to halve the width the class would need
four times as many listings.

### q32-home-alpha-tails.php — 2 parts
The lab's tail areas (Try It Now 7.6). Parts: (a) `numfunc` — alpha = 1 - 0.90 = **0.10**; (b)
`numfunc` — alpha/2 = **0.05**. **Invariant: 0.10 and 0.05 on every seed.** The guide: the three
areas have to total 1 — 0.05 + 0.90 + 0.05 = 1.00 — and that check catches the single most
common mistake in this step, which is putting the full alpha in each tail instead of half of it.

### q33-count-inside-interval.php — 2 parts
The lab's data-counting step (Try It Now 7.7). Parts: (a) `numfunc` — the number of the 35
prices inside (377702, 442298): **9**; (b) `numfunc` — that as a percent: 9/35 ~= **25.7**.
**Invariant: 9 and ~ 25.7 on every seed.** The guide: the interval is a statement about where
the population mean plausibly sits, not about where individual homes sell — sample means vary
about six times less than individual prices do, so an interval built to capture the mean is far
too narrow to capture most of the data.

### q34-interpret-the-home-interval.php — 3 parts
The lab's interpretation sentences (Try It Now 7.8). Parts: (a) `choices` — the correct specific
sentence (We are 90% confident that the true mean sale price of all homes recently listed in
Butte County lies between $377,702 and $442,298); (b) `choices` — the correct general sentence
(the method captures the true mean 90% of the time in repeated sampling); (c) `choices` — what is
wrong with "there is a 90% chance the mean home price is between $377,702 and $442,298" (mu is a
constant and the endpoints are constants once computed, so no chance remains — the randomness
was in the sampling, not in the population mean). **Invariant: all three answers are constant on
every seed.** The guide: the confidence belongs to the method — think of the procedure as a ring
toss where the peg is fixed and you are the one moving.

### q35-four-confidence-levels.php — 4 parts
The lab's four-level table (Try It Now 7.9). Parts: (a) `numfunc` — the EBM at 50%:
0.6818 x 19101 ~= **13023**; (b) `numfunc` — the EBM at 80%: 1.3070 x 19101 ~= **24965**; (c)
`numfunc` — the EBM at 95%: 2.0322 x 19101 ~= **38817**; (d) `numfunc` — the EBM at 99%:
2.7284 x 19101 ~= **52115**. **Invariant: ~ 13023, ~ 24965, ~ 38817, ~ 52115 on every seed.**
The guide: the four rows share everything except one number — the standard error is the same in
every row, and all that changes down the column is t_(alpha/2). Filling the table is really
just multiplying one fixed number by four different multipliers.

### q36-ebm-trend.php — 2 parts
The lab's trend question (Try It Now 7.9 step 3). Parts: (a) `choices` — what happens to the EBM
as the confidence level increases (it increases); (b) `choices` — what happens to the width (it
increases). **Invariant: both answers are constant on every seed.** The guide: certainty and
precision pull against each other — the 99% interval is very likely to contain mu and spans over
$100,000, while the 50% interval pins the mean to a $26,000 range and is wrong about half the
time. Ninety and ninety-five percent are conventions because they sit in the usable middle.

### pre-frq-grade-a-home-costs-interval.php — 3 parts
Authored-first pre-FRQ (no lab FRQ to mirror; the CI-interpretation, t-interval and
proportion-interval pre-FRQs are claimed by §7.1/§7.2/§7.3). Categories, 10 pts: **State the
Sample Summary (3)** — x-bar, s, and n from the class's 35 prices; **Build the Interval (3)** —
the t-interval at the stated confidence level; **Interpret in Context (4)** — the specific
sentence naming the population, the quantity, and the two endpoints. Dropped category: **Build
the Interval** (a student can report the summary statistics and interpret a given interval
without ever computing the error bound — the lab's own text says "the arithmetic is easy and
the interpretation is where almost everyone slips", so the computation is exactly the step a
plausible answer skips; the interval is the thing the other categories imply but never demand).
This is DIFFERENT from the 4.4/4.6/5.3/6.4 lab pre-FRQs' dropped categories (State the
Theoretical Value / State the Empirical Value / Compare and Explain the Gap) and from §7.1/§7.2/
§7.3's (Confidence Level Meaning / Assessing the Claim / Statistical Decision) — the template
forbids repeating a dropped category; note the difference in the manifest `_note`. Invariant
structure per `pre-frq-template.md`: `array("choices", "multans", "choices")`, `$scoremethod[1]
= "allornothing"`, four responses built by concatenating one sentence per category then
dropping one, part (b) grades a DIFFERENT response than part (a) names. **Invariant: no response
earns a category it is supposed to be missing (category purity), and every number inside a
response is generated from the same variables as the scenario.** Scope the CSS with `.qscope74`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

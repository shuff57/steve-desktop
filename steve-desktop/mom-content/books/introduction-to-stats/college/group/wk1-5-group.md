# Group Test: Weeks 1-5 (Data, Sampling, Displays, Describing Distributions, Normal)

**Status:** transferred 2026-05-19 — aid 22618261
**Course:** MATH 105 Spring26 (cid 314128)
**Block:** Quizzes & Tests (path 0-1)
**Target size:** 17 questions (14 auto-graded + 3 FRQs), ~72 min estimated work, 88-min in-class window
**Assessment URL:** https://www.myopenmath.com/course/addassessment2.php?id=22618261&cid=314128
**Coexists with:** existing "Unit 1 Group Quiz Huff" (aid 22220121) — this is a separate, broader 1st-half group test, not a replacement.

## Final qid map (after 2026-05-19 transfer)

| Slot | Topic | qid |
|---|---|---|
| 1 | Variable type qual/quant + level | 1829206 |
| 2 | Observational vs experiment | 1829210 |
| 3 | Sampling method identify | 1829212 |
| 4 | Population vs sample, parameter vs statistic | 1829213 |
| 5 | Choose display | 1829216 |
| 6 | Histogram rel-freq + shape | 1829217 |
| 7 | Boxplot 5# + outlier | 1829218 |
| 8 | Mean/median/IQR/range from data | 1829219 |
| 9 | Compare two distributions | 1829220 |
| 10 | Resistant measure choice | 1829221 |
| 11 | Z-score compute + compare | 1829222 |
| 12 | Normal probabilities P(X<a), P(X>b), P(a<X<b) | 1829223 |
| 13 | Inverse normal: percentile + middle c% | 1829224 |
| 14 | Empirical rule 68-95-99.7 | 1829225 |
| 15 | FRQ: Sampling design critique | 1829226 |
| 16 | FRQ: Compare distributions essay | 1829227 |
| 17 | FRQ: Normal model in context | 1829228 |

## Coverage

| Wk | Topic | Slots auto | Slot FRQ | Notes |
|---|---|---|---|---|
| Wk1 | Types of Data + Sampling | 1-4 | 15 | 4 new draft autos + 1 new FRQ |
| Wk2 | Graphical Displays | 5-7 | 16 (shared) | 3 new draft autos + shared FRQ on comparing distributions |
| Wk3 | Describing a Distribution | 8-10 | 16 | 3 new draft autos + 1 new FRQ (compare-two-distributions) |
| Wk4-5 | Normal Distribution | 11-14 | 17 | 4 new draft autos + 1 new FRQ (normal model in context) |

All 13 auto-graded slots and all 3 FRQs are **new drafts** — none reuse existing repo files. (Repo had no auto-graded coverage for these weeks; the existing FRQ files under `questions/frq/descriptive-statistics/` and `questions/frq/normal-distribution/` are already used in the "week 1 - 5 frq" assessment aid 22191102.)

## Drafts (16 new files)

### Wk1 Data & Sampling (`questions/data-sampling/` — new folder)
1. `q1-variable-type.php` — qual/quant + nominal/ordinal/interval/ratio
2. `q2-observational-vs-experiment.php` — study type + treatment imposed + causal vs association
3. `q3-sampling-method-identify.php` — SRS / stratified / cluster / systematic / convenience
4. `q4-population-parameter-statistic.php` — identify population, sample, parameter vs statistic

### Wk2 Displays (`questions/displays/` — new folder)
5. `q1-choose-display.php` — pick best display from {bar, histogram, pie, boxplot, time series, scatterplot}
6. `q2-histogram-relative-frequency.php` — read bin counts, compute rel-freq, ID shape
7. `q3-boxplot-five-number.php` — IQR, 1.5*IQR fences, outlier decision

### Wk3 Describing distribution (`questions/descriptive-stats/` — new folder)
8. `q1-mean-median-iqr-from-data.php` — 4-part computation from n=9 sorted data set
9. `q2-compare-two-distributions.php` — compare centers, spreads, verbal summary
10. `q3-resistant-measure-choice.php` — skew → resistant measure (median+IQR vs mean+SD)

### Wk4-5 Normal (`questions/normal-distribution/` — new folder)
11. `q1-z-score-compute.php` — z = (x - mu)/sigma; compare two |z| values
12. `q2-normal-probability.php` — P(X<a), P(X>b), P(a<X<b) (uses normalcdf)
13. `q3-inverse-normal-percentile.php` — pth percentile + middle c% endpoints (invnormalcdf)
14. `q4-empirical-rule.php` — 68-95-99.7 applied for k=1, 2, 3 SDs

### FRQs (`questions/frq/descriptive-statistics/` + `questions/frq/normal-distribution/`)
15. `frq/descriptive-statistics/q10-sampling-design-critique.php` — bias type, direction of skew, improved design
16. `frq/descriptive-statistics/q11-compare-distributions-essay.php` — shape/center/spread/verdict in context
17. `frq/normal-distribution/q15-normal-model-in-context.php` — compute, interpret, assess bell-shape assumption

## Group-work protocol (paste into MOM Intro / Instructions)

> **Group Quiz Rules.** This is a *graded group quiz*. You will work in your assigned group during class today and **each student submits their own work**. You may discuss strategy with your group, but final answers must be entered by you on your own device.
>
> - You have **3 attempts per question with no penalty**. Use them to refine your reasoning.
> - All questions are visible on one page. Move around as needed.
> - The 3 free-response items (last three slots) are essays. Use the rubric checklist that appears under each prompt to make sure you address every required point.
> - The quiz auto-closes at the end of class. Submit before then.

(Teacher to paste this — or revised text — into `textarea#intro` on `addassessment2.php?id={aid}` after MOM creates the assessment.)

## Time budget

| Block | est min |
|---|---|
| Slots 1-4 (Wk1 conceptual / multipart) | 3+3+2+3 = 11 |
| Slots 5-7 (Wk2 displays) | 2+4+5 = 11 |
| Slots 8-10 (Wk3 describing) | 5+4+3 = 12 |
| Slots 11-14 (Wk4-5 normal) | 4+5+5+3 = 17 |
| Slots 15-17 (FRQs) | 10 each = 30 |
| **Total auto + FRQ work** | **~81 min** |

(Group discussion overhead and submission slack are absorbed by the 88-min in-class window.)

## Next steps

1. Hand off to `mom-transfer` with `book/introduction-to-stats/college/group/wk1-5-group.json`.
2. `mom-transfer` creates the assessment in block 0-1, runs the create→verify→add loop for all 17 questions, and persists the `aid` + per-question `qid` back into the JSON.
3. After transfer, teacher: paste protocol into Intro, set `available`/`due` dates, unhide.
4. Distribute group rosters / cards / paper roster outside MOM (MOM has no native group structure).

## Reminders

- Subtype = **Quiz**, NOT Homework.
- `defattempts: 3`, `versions_per_question: 1`, `penalty.type: "none"`.
- `displaymethod: "All questions on one page"` so groups can see and discuss everything.
- `gbcategory: "Tests"` (graded category, weighted with other tests).
- Both this manifest and `wk7-14-group.{json,md}` form the 2-test pair the teacher requested on 2026-05-19; this one covers the 1st half of the course.

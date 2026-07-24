# Group Test: Weeks 7-14 (CI, HT, CI<->HT, 2-Sample, EV, Chi-Square, Regression)

**Status:** drafted 2026-05-19 — pending MOM transfer
**Course:** MATH 105 Spring26 (cid 314128)
**Block:** Quizzes & Tests (path 0-1)
**Target size:** 17 questions (14 auto-graded + 3 FRQs), ~73 min estimated work, 88-min in-class window
**Coexists with:** existing "Unit 2 Group Quiz Huff" (aid 22402957, Wk7-10 focus) and "Unit 3 Group Quiz: EV, Chi, Reg" (aid 22551860, Wk12-14 focus). This is a NEW broader 2nd-half group test that spans the entire 2nd half in a single class period.

## Coverage

| Wk | Topic | Slots auto | Slot FRQ | New vs reuse |
|---|---|---|---|---|
| Wk7 | Confidence Intervals | 1-2 | 15 | 1 reuse + 1 new + 1 new FRQ |
| Wk8 | Hypothesis Testing | 3-5 | (shared) | 2 new + 1 reuse |
| Wk9 | Relating CI & HT | 6-7 | - | 1 new + 1 reuse |
| Wk10 | 2-Sample CI & HT | 8-10 | 16 | 2 reuse + 1 new + 1 new FRQ |
| Wk12 | Expected Value | 11 | - | 1 reuse |
| Wk13 | Chi-Square | 12 | - | 1 reuse |
| Wk14 | Linear Regression | 13-14 | 17 | 1 reuse + 1 new + 1 new FRQ |

**Totals:** 8 reuses (all from repo) + 6 new auto-graded + 3 new FRQ = 17 slots.

## Reuses (8 files, all repo)

| Slot | File | Why fresh |
|---|---|---|
| 1 | `questions/stats-tests/confidence-intervals/q3-ci-width-factors.php` | Same qid 1747676 was used in Unit2 GQ — this is the repo source file; if MOM resolves to a different qid in this course, mom-transfer re-uploads as a fresh copy. |
| 4 | `questions/stats-tests/hypothesis-testing/q13-null-alt-mean-context.php` | Different angle than Unit2 GQ's 1747704. |
| 7 | `questions/stats-tests/hypothesis-testing/q12-stat-significance-interpretation.php` | Concept of statistical vs practical significance not covered in Unit 2 or 3 GQ. |
| 8 | `questions/stats-tests/two-sample-inference/q16-ci-diff-two-proportions.php` | Unit 2 GQ had 2-prop HT but not the CI variant. |
| 10 | `questions/stats-tests/two-sample-inference/q17-ci-two-means-compute.php` | Different from Unit 2 GQ's 25813 / 1807474 (CI vs HT). |
| 11 | `questions/probability/expected-value/q5-ev-game-of-chance-2.php` | Sibling of Unit 3 GQ's `q1-ev-game-of-chance.php` (qid 1825138) but a different scenario set. |
| 12 | `questions/stats-tests/chi-square/q6-gof-full-with-conclusion.php` | Unit 3 GQ used q1-q4 from chi-square folder; q6 has the full-workflow-with-conclusion that the prior set lacked. |
| 14 | `questions/regression/least-squares/q5-full-equation-from-summary.php` | Build-the-equation from summary stats — Unit 3 GQ tested R^2, slope-CI, residuals, but not equation construction. |

## Drafts (9 new files)

### Wk7 CI
1. `questions/stats-tests/confidence-intervals/q6-ci-mean-t-story.php` — slot 2: t-interval from story + interpret

### Wk8 HT
2. `questions/stats-tests/hypothesis-testing/q15-ho-ha-fresh-context.php` — slot 3: write H0/Ha + identify tail
3. `questions/stats-tests/hypothesis-testing/q16-full-ht-one-mean-fresh.php` — slot 5: full one-mean t-test workflow

### Wk9 CI<->HT
4. `questions/stats-tests/ci-ht-connection/q11-ci-to-ht-mean.php` — slot 6: use CI to test H0: mu = mu_0

### Wk10 2-sample
5. `questions/stats-tests/two-sample-inference/q20-two-prop-full-ht-fresh.php` — slot 9: pooled two-prop HT (two-tailed) full workflow

### Wk14 Regression
6. `questions/regression/least-squares/q18-read-regression-output-multipart.php` — slot 13: read software output, compute t + p, decide significance

### FRQs (3 new)
7. `questions/frq/inference-for-means/q12-ci-mean-context-critique.php` — slot 15
8. `questions/frq/inference-for-proportions/q16-two-prop-full-workflow.php` — slot 16
9. `questions/frq/regression/q13-slope-inference-full-interpret.php` — slot 17

## Group-work protocol (paste into MOM Intro)

> **Group Quiz Rules.** This is a *graded group quiz* for the second half of the course. Work in your assigned group during class today; **each student submits their own work**. You may discuss strategy with your group, but final answers must be entered by you on your own device.
>
> - You have **3 attempts per question with no penalty**. Use them to refine your reasoning.
> - All questions are visible on one page. Move around as needed.
> - The 3 free-response items (last three slots) are essays. Use the rubric checklist that appears under each prompt to make sure you address every required point.
> - The quiz auto-closes at the end of class. Submit before then.

## Time budget

| Block | est min |
|---|---|
| Slots 1-2 (Wk7 CI) | 2+5 = 7 |
| Slots 3-5 (Wk8 HT) | 3+2+6 = 11 |
| Slots 6-7 (Wk9 CI<->HT) | 3+3 = 6 |
| Slots 8-10 (Wk10 2-sample) | 4+6+4 = 14 |
| Slot 11 (Wk12 EV) | 5 |
| Slot 12 (Wk13 chi-square) | 6 |
| Slots 13-14 (Wk14 regression) | 5+4 = 9 |
| Slots 15-17 (FRQs) | 10 each = 30 |
| **Total auto + FRQ work** | **~88 min** |

(Tight — keep an eye on time; if students stall, the teacher can collect submissions and award partial credit on FRQs after class.)

## Next steps

1. Hand off to `mom-transfer` with `book/introduction-to-stats/college/group/wk7-14-group.json`.
2. `mom-transfer` creates the assessment in block 0-1, runs the create→verify→add loop for all 17 questions, and persists the `aid` + per-question `qid` back into the JSON.
3. After transfer, teacher: paste protocol into Intro, set `available`/`due` dates, unhide.
4. Distribute group rosters / cards / paper roster outside MOM (MOM has no native group structure).

## Re-upload risk

The wk12-14 transfer (Unit 3 GQ) had 5 repo files re-uploaded as fresh qids because their original library qids were not searchable in this course. Expect similar re-upload tax on:
- `regression/least-squares/q5-full-equation-from-summary.php` (slot 14) — has been pushed before; check searchability
- `stats-tests/two-sample-inference/q16-ci-diff-two-proportions.php` (slot 8) and `q17-ci-two-means-compute.php` (slot 10) — similar
- `stats-tests/chi-square/q6-gof-full-with-conclusion.php` (slot 12) — if pushed before, may need re-upload

mom-transfer will detect and re-upload as needed.

## Reminders

- Subtype = **Quiz**, NOT Homework.
- `defattempts: 3`, `versions_per_question: 1`, `penalty.type: "none"`.
- `displaymethod: "All questions on one page"`.
- `gbcategory: "Tests"`.
- Companion to `wk1-5-group.{json,md}` (the 1st-half group test created in the same 2026-05-19 session).

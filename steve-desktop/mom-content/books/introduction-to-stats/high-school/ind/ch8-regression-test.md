---
name: "Test: Ch8 Introduction to Linear Regression"
book: introduction-to-stats
kind: ind
chapter_section: "8"
slug: ch8-regression-test
created_at: 2026-05-04T12:00:00
cid: "301417"
block: "1"
subtype: Quiz
defattempts: 3
versions_per_question: 1
penalty_type: after_full_score
penalty_reduction_pct: 33
gbcategory: Tests
displaymethod: Full test at once
show_solutions: after_lastattempt
available: 2026-05-06T08:00:00
due: 2026-05-12T23:59:00
default_points: 10
hidden_from_students: true
aid: "22488516"
transferred_at: 2026-05-04T13:00:00
---

# Test: Ch8 Introduction to Linear Regression

**Target:** course 301417, block 1
**Assessment URL:** https://www.myopenmath.com/course/addassessment2.php?id=22488516&cid=301417
**Source coverage:** Subset of `books/introduction-to-stats/group/ch8-regression-group.json` — picked 8 high-impact multiparts spanning 8.1, 8.2, and 8.4, plus 2 FRQs.
**MOM settings:** Quiz subtype, 3 attempts × 1 version per question, MOM per-attempt 33% penalty (best single-field translation of `after_full_score`), Tests category (IND), Full test at once, solutions revealed after the last attempt, hidden from students, intended window May 6 8:00am – May 12 11:59pm.

## Questions

| # | File | Sub-topic | Step | Diff | Role | FRQ | Pts | qid |
|---|------|-----------|------|------|------|-----|-----|-----|
| 1 | `questions/regression/least-squares/q5-full-equation-from-summary.php` | 8.2.2 least-squares | compute slope, intercept, predict | medium | computation | no | 10 | 1808999 |
| 2 | `questions/regression/least-squares/q9-extrapolation-warning.php` | 8.2.4 least-squares | judge prediction reliability | medium | computation | no | 10 | 1809006 |
| 3 | `questions/regression/least-squares/q14-read-regression-output.php` | 8.2.6 least-squares | extract slope and p-value from software output | medium | computation | no | 10 | 1815982 |
| 4 | `questions/regression/slope-inference/q1-ci-for-slope.php` | 8.4.3 slope-inference | SE -> ME -> CI bounds | medium | computation | no | 10 | 1815974 |
| 5 | `questions/regression/slope-inference/q2-hypothesis-test-slope.php` | 8.4.5 slope-inference | H0/Ha -> t -> p-value -> conclusion | medium | computation | no | 10 | 1815976 |
| 6 | `questions/regression/intro/q12-slope-intercept-context.php` | 8.1.1 intro | interpret slope, interpret intercept, predict | medium | computation | no | 10 | 1804882 |
| 7 | `questions/regression/intro/q7-prediction-and-interpret.php` | 8.1.2 intro | predict, judge above/below line | medium | computation | no | 10 | 1804702 |
| 8 | `questions/regression/residuals-correlation/q1-compute-residual.php` | 8.1.3 residuals-correlation | use regression equation to compute residual | medium | computation | no | 10 | 1816273 |
| 9 | `questions/frq/regression/q3-interpret-correlation-coefficient.php` | 8.1.4 residuals-correlation | describe strength and direction of linear association | easy | frq | **yes** | 10 | 1816241 |
| 10 | `questions/frq/regression/q9-slope-inference-concept-and-hypotheses.php` | 8.4 slope-inference | explain the test, state H0/Ha for the slope | easy | frq | **yes** | 10 | 1816243 |

**Total points:** 100 (10 questions × 10 pts)

## Status

- aid: 22488516
- transferred_at: 2026-05-04T13:00:00
- Assessment is currently **hidden from students**.

## Teacher follow-up before unhiding

1. **Set the date window in MOM.** While `avail` is set to *Hide*, MOM resets the start/due fields to the current moment on every save. Switch *Show / Hide* -> *Show by Dates*, set `available = 05/06/2026 8:00 am`, `due = 05/12/2026 11:59 pm`, save, then unhide.
2. **Confirm penalty mapping.** The manifest's `after_full_score 33%` rule was pushed as MOM `defattemptpenalty = 33` (per-attempt 33% reduction). Every retry takes 33% off, not just retries after a perfect first attempt — adjust manually post-close if needed.
3. **Verify gradebook category.** Mapped to `IND` (id 737602).
4. **Preview as student.** Confirm solution panels render after the 3rd attempt, especially for questions whose `$solutionguide` / `$rubricanswerbutton` lives in CC and is referenced after `///`.

## History

- **2026-05-04T13:00:00** — Initial transfer ran the wrong manifest (`ch8-stats-tests-test`) and pushed 10 hypothesis-testing questions. Caught the chapter mismatch, renamed the assessment in MOM to "Test: Ch8 Introduction to Linear Regression," removed the 10 wrong questions, and added the 10 regression questions above (qids reused from the existing `ch8-regression-group` library entries).
- **2026-05-04T13:00:00** — Manifest moved from `assignments/ind/` to `books/introduction-to-stats/ind/` and renamed from `ch8-stats-tests-test` to `ch8-regression-test` to match content and mirror the `books/{book}/group/` and `books/{book}/hw/` convention.
- **2026-05-06** — Cleaned up 11 orphan hypothesis-testing question IDs from the course library that were left behind by the wrong-chapter push (qids `1820577`, `1820580`, `1820581`, `1820583`, `1820585`, `1820587`, `1820589`, `1820592`, `1820594`, `1820597`, `1820598`). Question library is now back to a regression-only state for Ch8.

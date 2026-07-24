---
name: "Practice: Ch8 Introduction to Linear Regression"
book: introduction-to-stats
kind: practice
chapter_section: "8"
slug: ch8-regression-practice
created_at: 2026-04-26
source_url: http://127.0.0.1:4000/docs/introduction-to-stats/Chapter_8_assembled.html
cid: "301417"
block: ""
subtype: Homework
defattempts: 3
versions_per_question: 20
penalty_type: none
penalty_reduction_pct: 0
gbcategory: Practice
displaymethod: One question at a time
show_solutions: after last attempt
hidden_from_students: true
due: null
default_points: 1
target_n: 22
auto_graded_count: 19
frq_count: 3
qids_known: 18
qids_not_in_mom_yet: 1
aid: "22450319"
transferred_at: 2026-04-27
status: transferred
---

# Practice: Ch8 Introduction to Linear Regression

**Status:** **Transferred to MOM** 2026-04-27. Assessment `aid=22450319` in `cid=301417`. All 22 questions added.

**Live link:** [Settings](https://www.myopenmath.com/course/addassessment2.php?id=22450319&cid=301417) | [Add/Remove Questions](https://www.myopenmath.com/course/addquestions2.php?aid=22450319&cid=301417)

## Post-transfer notes (2026-04-27)

1. **Question order in MOM matches manifest slot order.** Reordered 2026-04-27 by directly assigning the page's `itemarray` to the target sequence and calling `submitChanges()` once. (The position-dropdown approach had crashed the playwriter relay during the group test transfer.)

2. **Slots 4, 6, 7 are template copies** of original Private qids that don't surface in MOM search:
   - Slot 4: copy `1816304` (orig `1804880` "Predict from Equation - Advertising")
   - Slot 6: copy `1816305` (orig `1807060` "Compute a Residual" — same source as group test slot 5)
   - Slot 7: copy `1816306` (orig `1807074` "Estimate Residual Std Dev")
   Originals untouched.

3. **Slot 8 newly created in MOM:** `q7-interpret-r-value.php` → qid `1816292`. This was the `not-in-mom` repo file flagged in the original manifest.

4. **3 FRQs newly created in MOM** (different from group test FRQs for variety):
   - Slot 20: q2 Interpret a Residual → qid `1816293` (auto-fixed inline ternary)
   - Slot 21: q6 Interpret Slope/Y-Intercept → qid `1816294` (auto-fixed direction-word ternary)
   - Slot 22: q10 Interpret a P-Value → qid `1816295` (auto-fixed `number_format()` and ternary)
   All repo .php files were patched before transfer and now use round() and if/else.

5. **Gradebook category is HW (id=737600).** Set 2026-04-27 per teacher request. This course only has HW/GROUP/IND/Default categories; HW is the closest match for "Practice" since the kind contract treats practice as homework-shaped.

6. **Settings verified:** subtype=Homework (by_question), 3×20 attempts/versions, no penalty, displaymethod=One at a time, hidden=true.

**Built from these 4 hw assignments:**

| § | hw assignment | aid | source |
|---|---|---|---|
| 8.1 (parts 1+2) | Homework 8.1 Day1 | 21018141 | synced from existing MOM |
| 8.1 (residuals) | Homework 8.1 Day 2 | 22402789 | synced from existing MOM |
| 8.2 | Homework: Ch8.2 (15 questions) | **null — not transferred yet** | repo-built, ready to transfer |
| 8.4 | Homework: Ch8.4 Inference for the Slope | 22449243 | transferred this session |

§8.3 was skipped in instruction; not included.

## To complete this practice manifest

1. ~~Transfer hw 8.2 first~~ — **NOT NEEDED**. The 6 §8.2 questions for this practice were resolved differently:
   - 5 found already in your MOM library by description search (slots 9–13)
   - 1 (slot 14, "Read Regression Output") was missing — created fresh in MOM library this session as qid 1815982
2. **Draft 3 FRQs** via `/mom-frq` (slots 20, 21, 22).
3. **Slot 8 (`q7-interpret-r-value.php`, §8.1.4 correlation)** is in the repo but NOT in any existing MOM library — when this practice transfers, mom-transfer will need to create the question in MOM first.
4. Then run `/mom-transfer 8 book=introduction-to-stats kind=practice` to push.

**Note:** the 6 §8.2 qids being in MOM library does NOT mean hw 8.2 has been transferred. The 15-question hw 8.2 manifest still has `aid: null` and remains separate from this practice. If you want the hw 8.2 assignment created in MOM, that's a separate `/mom-transfer 8.2 ...` step.

## Question slate (22 total)

| # | Sub | File | Title | Role | Diff | qid | Status |
|---|---|---|---|---|---|---|---|
| 1  | 8.1.1 | `intro/q1-explanatory-response.php`           | Explanatory and Response Variables  | multipart   | easy | 1804693 | in-mom |
| 2  | 8.1.1 | `intro/q3-interpret-slope.php`                | Interpret Slope in Context          | conceptual  | easy | 1804697 | in-mom |
| 3  | 8.1.1 | `intro/q12-slope-intercept-context.php`       | Slope and Intercept (plant growth)  | multipart   | mod  | 1804882 | in-mom |
| 4  | 8.1.2 | `intro/q10-predict-advertising.php`           | Predict from Equation (Advertising) | computation | mod  | 1804880 | in-mom |
| 5  | 8.1.2 | `intro/q7-prediction-and-interpret.php`       | Prediction and Interpret (used car) | multipart   | mod  | 1804702 | in-mom |
| 6  | 8.1.3 | `residuals-correlation/q1-compute-residual.php` | Compute a Residual                | multipart   | mod  | 1807060 | in-mom |
| 7  | 8.1.3 | `residuals-correlation/q5-residual-std-dev.php` | Estimate Residual Std Dev         | multipart   | hard | 1807074 | in-mom |
| 8  | 8.1.4 | `residuals-correlation/q7-interpret-r-value.php`| Interpret r Value                 | multipart   | mod  | **null** | not-in-mom (repo only) |
| 9  | 8.2.1 | `least-squares/q1-least-squares-criterion.php`  | Least Squares Criterion           | conceptual  | easy | 1808993 | in-mom-library |
| 10 | 8.2.2 | `least-squares/q5-full-equation-from-summary.php` | Full Equation from Summary Statistics | multipart | mod | 1808999 | in-mom-library |
| 11 | 8.2.3 | `least-squares/q12-association-vs-causation.php`| Association vs Causation          | conceptual  | mod  | 1809011 | in-mom-library |
| 12 | 8.2.4 | `least-squares/q9-extrapolation-warning.php`    | Extrapolation Warning             | multipart   | mod  | 1809006 | in-mom-library |
| 13 | 8.2.5 | `least-squares/q7-r-squared-interpretation.php` | R-squared Interpretation          | conceptual  | easy | 1809002 | in-mom-library |
| 14 | 8.2.6 | `least-squares/q14-read-regression-output.php`  | Read Regression Output            | multipart   | mod  | 1815982 | created-this-session |
| 15 | 8.4.1 | `slope-inference/q7-role-of-inference.php`     | Role of Inference (NEW DRAFT)      | conceptual  | easy | 1815971 | in-mom |
| 16 | 8.4.2 | `slope-inference/q5-line-conditions.php`       | LINE Conditions                    | multipart   | easy | 1815973 | in-mom |
| 17 | 8.4.3 | `slope-inference/q1-ci-for-slope.php`          | CI for the Slope                   | multipart   | mod  | 1815974 | in-mom |
| 18 | 8.4.5 | `slope-inference/q2-hypothesis-test-slope.php` | Hypothesis Test for the Slope      | multipart   | mod  | 1815976 | in-mom |
| 19 | 8.4.6 | `slope-inference/q8-tech-ci-from-output.php`   | CI from Technology Output (NEW)    | multipart   | mod  | 1815972 | in-mom |
| 20 | 8.1+8.2 | _TODO — draft via mom-frq_                  | FRQ 1 (regression interpretation) | multipart   | mod  | null | todo |
| 21 | 8.2+8.4 | _TODO — draft via mom-frq_                  | FRQ 2 (full workflow)             | multipart   | hard | null | todo |
| 22 | 8.4     | _TODO — draft via mom-frq_                  | FRQ 3 (inference + conclusion)    | multipart   | hard | null | todo |

## Coverage check

✓ §8.1.1, §8.1.2, §8.1.3, §8.1.4 (1 each minimum)
✓ §8.2.1, §8.2.2, §8.2.3, §8.2.4, §8.2.5, §8.2.6 (1 each minimum)
✗ §8.2.7 (Outliers), §8.2.8 (Indicator) — not represented; if you want them, swap in `least-squares/q11-leverage-vs-influential.php` or `q13-indicator-variable-interpret.php` for one of the existing slots
✓ §8.4.1, §8.4.2, §8.4.3, §8.4.5, §8.4.6 (1 each minimum)
✗ §8.4.7 (Paired vs Regression) — not represented; could add `slope-inference/q4-paired-vs-regression.php` (qid 1815977)

§8.3 explicitly skipped per instruction.

## High-Miss / High-Attempt HW Topics (to over-weight)

_(none recorded yet — populate after Ch8 hw cycle completes; teacher fills in or `/mom-practice-test` Phase 1 pulls from MOM gradebook)_

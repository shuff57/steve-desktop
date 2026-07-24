---
name: "Practice: Ch7 Inference for Numerical Data (Final-Exam Review)"
book: introduction-to-stats
kind: practice
chapter_section: "7"
slug: ch7-numerical-inference-practice
created_at: 2026-05-13
cid: "301265"
block: "0-7-11"
subtype: Homework
defattempts: 3
versions_per_question: 20
penalty_type: none
gbcategory: HW
displaymethod: All questions on one page
show_solutions: after last attempt
hidden_from_students: true
target_n: 22
frq_count: 3
draft_count: 9
reuse_count: 13
aid: null
status: manifest-only
---

# Practice: Ch7 Inference for Numerical Data — Final-Exam Review

**Status:** Manifest written 2026-05-13. **9 drafts** need writing + approval before `mom-transfer` push. This is the heaviest drafting load of the three chapters because repo coverage for Ch7 is thin (only 3 inference-for-means files in repo).

## Purpose

Final Semester 2 review for Ch7. **IND avg 86.0% is the lowest S2 chapter test** (vs Ch6 90.9%, Ch8 92.2%). FRQs dominated misses, but ANOVA conceptual + two-sample CI computation are also weak.

## High-miss data (drill targets)

| Topic | IND | Group | Drill |
|---|---|---|---|
| ANOVA when-to-use | 56% | 44% | Slot 13 draft + FRQ slot 22 |
| Single-mean p-value interp | 68% | 57% | FRQ slot 20 |
| Paired means interp | — | 55% | FRQ slot 21 |
| Two-sample CI (unequal var) | — | mixed q12 50% (47.8% incomplete) | Slot 11 draft |
| Paired-t workflow w/ data | — | mixed q15/q18 43-54% incomplete | Slot 7 draft |

## Coverage gaps (vs existing Practice + IND)

- ANOVA — no auto-graded conceptual coverage anywhere
- Choose-which-test (one-mean vs paired vs two-sample vs ANOVA) — completely absent
- Two-sample CI computation — students struggle (50% on mixed review) but it wasn't isolated in IND
- Paired-t with abstract / table — single representative item across all assessments

## Slate (22 = 19 auto-graded + 3 FRQ)

| # | Sub | Src | Title |
|---|---|---|---|
| 1 | 7.1 | reuse | CI for one mean (t) — compute |
| 2 | 7.1 | reuse | HT for one mean (t) — full workflow |
| 3 | 7.1 | **draft** | One-mean test stat, sigma unknown, abstract |
| 4 | 7.1 | **draft** | Interpret CI for one mean |
| 5 | 7.1 | reuse | Sleep hours one-sample |
| 6 | 7.2 | reuse | Paired t — reaction times |
| 7 | 7.2 | **draft** | Paired t — full workflow from data table |
| 8 | 7.2/.3 | **draft** | Paired vs two-sample — choose |
| 9 | 7.3 | reuse | Two-mean HT — energy drinks |
| 10 | 7.3 | reuse | Interpret CI for two means |
| 11 | 7.3 | **draft** | CI for two means (unequal var) — compute (TOP-MISS) |
| 12 | 7.3 | **draft** | Two-sample full workflow |
| 13 | 7.5 | **draft** | When to use ANOVA — choose (TOP-MISS) |
| 14 | 7.5 | **draft** | ANOVA summary table — compute MS, F |
| 15 | 7.5 | **draft** | ANOVA conclusion — interpret F and decide |
| 16 | 7.x | **draft** | State H0/Ha for mean (single + two) |
| 17 | 7.x | reuse | p-value to decision |
| 18 | 7.x | reuse | Type I vs Type II — numerical context |
| 19 | 7.1 | reuse | Daily steps one-sample |
| 20 | **FRQ** | reuse | Single-mean p-value interpretation |
| 21 | **FRQ** | reuse | Paired means interpretation |
| 22 | **FRQ** | reuse | ANOVA interpretation |

## Drafts to write (9 files, all under `questions/stats-tests/inference-for-means/` or `two-sample-inference/`)

- `inference-for-means/q4-test-statistic-abstract.php` (slot 3)
- `inference-for-means/q5-ci-interpretation-mean.php` (slot 4)
- `inference-for-means/q6-paired-t-workflow.php` (slot 7)
- `inference-for-means/q7-paired-vs-two-sample-choice.php` (slot 8)
- `two-sample-inference/q17-ci-two-means-compute.php` (slot 11)
- `two-sample-inference/q18-two-mean-full-workflow.php` (slot 12)
- `inference-for-means/q8-anova-when-to-use.php` (slot 13)
- `inference-for-means/q9-anova-summary-table.php` (slot 14)
- `inference-for-means/q10-anova-conclusion.php` (slot 15)
- `hypothesis-testing/q13-null-alt-mean-context.php` (slot 16)

Note: 10 files listed because slot 16 lives in `hypothesis-testing/` not `inference-for-means/`.

## Next steps

1. Approval gate on slate.
2. Draft 10 new questions (groups of 5, approval per batch).
3. `mom-transfer` push to cid 301265, block 0-7-11.
4. Set due date and unhide before students see it.

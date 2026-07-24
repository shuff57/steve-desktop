---
name: "Homework: Ch8 Probability (8.1, 8.2, 8.4 mixed)"
kind: hw
book: applied-finite-math
chapter_section: "8"
slug: ch8-probability-mixed
created_at: 2026-05-04
source_url: https://shuff57.github.io/bookSHelf/index.html
cid: "306621"
block: null
subtype: Homework
defattempts: 3
versions_per_question: 20
penalty_type: none
penalty_reduction_pct: 0
gbcategory: Default
displaymethod: All at once
show_solutions: after last try
due: null
default_points: 1
aid: "21973137"
transferred_at: 2026-05-05
status: live
---

# Homework: Ch8 Probability (8.1, 8.2, 8.4 mixed)

**Status:** Ready to transfer. 15 `.php` files written under `questions/probability/`. Assessment shell `aid=21973137` already exists in MOM (course `cid=306621`); `mom-transfer` should add questions to that existing assessment.

**Source:** Applied Finite Math, Chapter 8 — Probability (sections 8.1, 8.2, 8.4) on the [bookshelf](https://shuff57.github.io/bookSHelf/applied-finite-math/chapter-8-probability/).

**MOM settings:**
- Subtype: **Homework** (retries enabled)
- Attempts: **3 × 20 versions per question**, no penalty
- Gradebook category: **Homework**
- Display: **All at once** *(teacher override of default "One question at a time")*
- Show solutions: **after each attempt**
- Due: **left blank** — teacher sets in MOM after upload
- Per-question default points: **1** (15 total)

## Coverage

| Section | Sub-topic | Slots | Difficulty mix |
|---------|-----------|-------|----------------|
| 8.1 Sample Spaces & Probability | 8.1.1, 8.1.2, 8.1.3 | 1–5 | 2 easy / 2 mod / 1 hard |
| 8.2 Mutually Exclusive & Addition Rule | 8.2.1, 8.2.2, 8.2.3, 8.2.4 | 6–10 | 2 easy / 3 mod |
| 8.4 Conditional Probability | 8.4.1, 8.4.2, 8.4.3, 8.4.4 | 11–15 | 1 easy / 3 mod / 1 hard |

**Total difficulty:** 5 easy / 7 moderate / 3 hard (≈ 33 / 47 / 20).

## Questions

All 15 transferred to MOM aid=21973137 (Correct on testquestion2.php). Order on the assessment matches slot order.

| # | Title | Sub-topic | Diff | Type | qid | File |
|---|-------|-----------|------|------|-----|------|
| 1 | Count outcomes (coins, dice, mixed) | 8.1.1 | easy | mp num×3 | 1820833 | `questions/probability/q1-count-outcomes.php` |
| 2 | P(named card category) | 8.1.2 | easy | number | 1820836 | `questions/probability/q2-probability-card-draw.php` |
| 3 | P(exactly one head) on n coins | 8.1.2 | mod | number | 1820848 | `questions/probability/q3-probability-exactly-one-head.php` |
| 4 | P(sum = k) on two dice | 8.1.3 | mod | number | 1820849 | `questions/probability/q4-probability-two-dice-sum.php` |
| 5 | Three children — exactly 2 boys & at least one boy | 8.1.3 | hard | mp num×2 | 1820850 | `questions/probability/q5-three-children-boys.php` |
| 6 | Set ops on dice events (A∪B, A∩B, Aᶜ) | 8.2.1 | easy | mp ntuple×3 | 1820852 | `questions/probability/q6-set-ops-dice-events.php` |
| 7 | Mutually exclusive: yes/no for 3 pairs | 8.2.2 | easy | mp choices×3 | 1820853 | `questions/probability/q7-mutually-exclusive-yesno.php` |
| 8 | Addition Rule from word-problem percentages | 8.2.3 | mod | number | 1820855 | `questions/probability/q8-addition-rule-percentages.php` |
| 9 | P(king or heart) on a card draw | 8.2.3 | mod | number | 1820856 | `questions/probability/q9-king-or-heart.php` |
| 10 | Two-way table — P(A∩B), P(A∪B) | 8.2.4 | mod | mp num×2 | 1820857 | `questions/probability/q10-twoway-table-addition.php` |
| 11 | Identify the conditioning event | 8.4.1 | easy | choices | 1820858 | `questions/probability/q11-identify-conditioning-event.php` |
| 12 | P(2 boys \| first-born is boy) — reduced sample space | 8.4.2 | mod | mp num×2 | 1820860 | `questions/probability/q12-three-children-given-first-boy.php` |
| 13 | P(A\|B) from P(A∩B) and P(B) | 8.4.3 | mod | number | 1820862 | `questions/probability/q13-conditional-probability-formula.php` |
| 14 | Two-way table — P(A\|B) and P(B\|A) asymmetry | 8.4.4 | mod | mp num×2 | 1820863 | `questions/probability/q14-twoway-conditional.php` |
| 15 | P(A\|B) using P(A∪B) — solve for intersection first | 8.4.3 | hard | number | 1820864 | `questions/probability/q15-conditional-from-union.php` |

## Notes for `mom-transfer`

1. `aid=21973137` already exists — **do NOT create a new assessment**. Run the per-question create→verify→add loop directly against the existing shell.
2. After the question-add phase, re-confirm settings on `addassessment2.php?id=21973137`:
   - subtype = `Homework`
   - defattempts = `3`
   - versions per question = `20`
   - penalty = none
   - gbcategory = `Homework`
   - displaymethod = `All at once` *(teacher override; do not auto-revert to "one at a time")*
   - show_solutions = `after each attempt`
   - due = leave blank (teacher will set)
3. Verify question order matches slot 1–15 (easy → moderate → hard ramp); MOM may auto-sort by qid after Add — re-order via the verify-order step if so.
4. After upload, run the repo-docs phase to fold `questions/probability/AGENTS.md` into the parent `../../AGENTS.md` roster (the family is brand-new — the parent doc may need a new row).

# Unit 4 Test: Ch7 Sets & Counting, Ch8 Probability

**Book:** Applied Finite Math (cid: 306621)
**Kind:** Individual Test (Quiz)
**Scope:** 7.1 Sets, 7.3 Permutations, 7.5 Combinations, 8.1 Sample Spaces, 8.2 Addition Rule (×3), 8.4 Conditional Probability (×3)
**Created:** 2026-05-09
**Last revised:** 2026-05-11 (renamed, FRQs removed)
**Transferred:** 2026-05-11
**AID:** 22533384
**Block:** 0-33
**Status:** Transferred — hidden from students. Teacher must set date window and unhide before publishing.

## Summary

- **10 questions, all multipart auto-graded** — no FRQs
- **30 auto-graded parts** total
- **Total points:** 100 (10 × 10 pts)
- **Estimated time:** ~50 min (10 × 5 min multiparts)
- **3 attempts** × **1 version** per question
- **Penalty:** 33% reduction triggered after the first full-score attempt — perfect first try locks score; any subsequent attempt loses 33%
- **Show solutions:** after the last attempt within the date window
- **Hidden from students:** yes — teacher must un-hide before publishing
- **Date window:** to be set manually by the teacher in MOM

## Number-Entry Format

Every number-type part uses `$abstolerance = 0.005` per the probability/sets family conventions. Students may enter probabilities as **exact fractions (e.g., `1/13`) OR rounded decimals (e.g., `0.0769`)** interchangeably. IMathAS evaluates the entered expression and matches against the precomputed answer within tolerance.

## Question Roster

### Sets / Counting (7.1, 7.3, 7.5) — Slots 1-3

| # | Question | File | QID | Parts | Points |
|---|----------|------|-----|-------|--------|
| 1 | Survey "find neither" chain (3 chained steps) | `questions/sets/q13-demorgan-verification.php` | 1823543 | 3 | 10 |
| 2 | Race finish orders (total → in 1st → not in 1st via complement) | `questions/combinatorics/q16-race-finish.php` | 1823545 | 3 | 10 |
| 3 | Committees with at-least-one via complement counting | `questions/combinatorics/q17-committee-constraints.php` | 1823547 | 3 | 10 |

### Probability — Sample Spaces & Addition Rule (8.1, 8.2 ×3) — Slots 4-7

| # | Question | File / Source | QID | Parts | Points |
|---|----------|------|-----|-------|--------|
| 4 | Urn draw — sample space → P(red) → P(not red) via complement | `questions/probability/q16-urn-draw.php` | 1823548 | 3 | 10 |
| 5 | P(neither) chain — naive sum → Addition Rule → complement | `questions/probability/q17-survey-overlap.php` | 1823549 | 3 | 10 |
| 6 | Movie genre 2×2 — joint → Addition Rule → mutex justification | `questions/probability/q18-genre-twoway-mutex.php` | 1823550 | 3 | 10 |
| 7 | **Addition Rule Formula** — given P(A), P(B), P(A∩B): P(A∪B), P(A and not B), P(neither) | MOM library (no repo file) | 1824651 | 3 | 10 |

### Probability — Conditional (8.4 ×3) — Slots 8-10

| # | Question | File / Source | QID | Parts | Points |
|---|----------|------|-----|-------|--------|
| 8 | Medical test Bayes chain — P(D∩+) → P(+) → P(D\|+) | `questions/probability/q19-medical-test-bayes.php` | 1823551 | 3 | 10 |
| 9 | Asymmetry chain — joint → P(Senior\|STEM) → P(STEM\|Senior) reusing same joint | `questions/probability/q20-class-twoway-asymmetry.php` | 1823552 | 3 | 10 |
| 10 | **Conditional via Reduced Sample Space** — two dice given sum=k: \|reduced SS\|, P(first=v\|sum=k), P(first odd\|sum=k) | MOM library (no repo file) | 1824664 | 3 | 10 |

## Coverage Check

| Section | Multipart slots | Coverage |
|---------|-----------------|----------|
| 7.1 Sets | Q1 (De Morgan via Survey) | one angle |
| 7.3 Permutations | Q2 (Race finish) | one angle |
| 7.5 Combinations | Q3 (Committees with constraint) | one angle |
| 8.1 Sample Spaces | Q4 (Urn draw) | one angle |
| 8.2 Addition Rule | Q5 (Survey overlap), Q6 (Genre 2×2 + mutex), Q7 (Addition Rule Formula) | three angles |
| 8.4 Conditional | Q8 (Medical Bayes), Q9 (2×2 asymmetry), Q10 (Reduced sample space) | three angles |

Every chapter section has at least one auto-graded multipart. 8.2 and 8.4 each get three distinct angles.

## Time Budget

| Bucket | Count | Min/each | Subtotal |
|--------|-------|----------|----------|
| Auto-graded multiparts | 10 | 5 | 50 min |
| **Total** | **10** | — | **50 min** |

Fits comfortably under the 60-minute class window.

## Revision History

### 2026-05-11 — Aligned with Unit 4 Quiz (group test) principles

- Renamed: "Individual Test: Ch7 Sets & Counting + Ch8 Probability" → "Unit 4 Test: Ch7 Sets & Counting, Ch8 Probability"
- **Removed 2 FRQs** (slot 9 q1823557 Perm vs Comb, slot 10 q1823560 2×2 Interpretation) — matched the group test which had its 3 FRQs removed and replaced with auto-graded items
- **Added 2 MOM library questions** (1824651 Addition Rule Formula, 1824664 Conditional via Reduced Sample Space) — same source as the group test's replacements (1824646, 1824651, 1824664)
- **Updated intro** to drop the "Two of the questions are free-response" line (matches group test which has no FRQ mention)
- Final order: 7.1 → 7.3 → 7.5 → 8.1 → 8.2 ×3 → 8.4 ×3
- Total stays at 10 questions / 100 points / 50 min

### 2026-05-09 — Original deployment

- 10 fresh drafts (8 multipart + 2 FRQs); transferred to MOM as aid 22533384

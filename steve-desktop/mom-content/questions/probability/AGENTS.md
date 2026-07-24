# Probability Questions — Sample Spaces, Addition Rule, Conditional

**Parent:** `../../AGENTS.md`
**Files:** 24 autograded probability questions covering sample spaces, events, the Addition Rule, mutually exclusive events, conditional probability, independence, and expected value.

## OVERVIEW

Probability questions test counting outcomes in sample spaces, computing P(E) for equally likely outcomes, set operations on events (union, intersection, complement), the Addition Rule, recognizing mutually exclusive events, two-way table reading, and conditional probability (reduced sample space and the formula). Every question is auto-graded. Every numeric scenario randomizes.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-count-outcomes.php` | 3 | number × 3 | Sample space size for n coins, n dice, and a die-plus-coins experiment |
| `q2-probability-card-draw.php` | 1 | number | P(named card category) from a 52-card deck across 11 categories |
| `q3-probability-exactly-one-head.php` | 1 | number | P(exactly one head) flipping n ∈ {2,3,4} coins |
| `q4-probability-two-dice-sum.php` | 1 | number | P(sum = k) on two dice for k ∈ {5,6,7,8,9,10} |
| `q5-three-children-boys.php` | 2 | number × 2 | 3-child family: P(exactly 2 boys/girls), P(at least one boy/girl) |
| `q6-set-ops-dice-events.php` | 3 | ntuple × 3 | A∪B, A∩B, Aᶜ for two events on a single die across 3 scenarios |
| `q7-mutually-exclusive-yesno.php` | 3 | choices × 3 | Mutually exclusive yes/no for 3 fixed event pairs |
| `q8-addition-rule-percentages.php` | 1 | number | P(A∪B) from word-problem percentages across 5 contexts |
| `q9-king-or-heart.php` | 1 | number | P(rank or suit) on a card draw — Addition Rule with overlap = 1 card |
| `q10-twoway-table-addition.php` | 2 | number × 2 | P(A∩B), P(A∪B) from a 2×2 Engineering/Sales × Senior/Junior table (total 100) |
| `q11-identify-conditioning-event.php` | 1 | choices | Pick the conditioning event from a worded conditional statement |
| `q12-three-children-given-first-boy.php` | 2 | number × 2 | Reduced sample space size and P(2 boys \| first-born is a boy) |
| `q13-conditional-probability-formula.php` | 1 | number | P(A\|B) = P(A∩B) / P(B) from given probabilities |
| `q14-twoway-conditional.php` | 2 | number × 2 | P(A\|B) and P(B\|A) from a 2×2 Coffee/Tea table (total 200) — highlights asymmetry |
| `q15-conditional-from-union.php` | 1 | number | Solve for P(A∩B) via Addition Rule, then compute P(A\|B) |
| `q16-urn-draw.php` | 3 | number × 3 | Urn with R/B/G balls: sample space size, P(red), P(not red) — 3 randomized scenarios |
| `q17-survey-overlap.php` | 3 | number × 3 | P(neither) chain: naive sum P(A)+P(B) → Addition Rule for P(A∪B) → complement 1−P(A∪B) |
| `q18-genre-twoway-mutex.php` | 3 | number × 2, choices | 2×2 movie genre × watched table: P(A∩W), P(A∪W), mutually-exclusive yes/no |
| `q19-medical-test-bayes.php` | 3 | number × 3 | Disease + test chain: P(D∩+), total P(+), and P(D\|+) — Bayes-flavored multistep |
| `q20-class-twoway-asymmetry.php` | 3 | number × 3 | Asymmetry chain: joint P(STEM∩Senior) → P(Senior\|STEM) → P(STEM\|Senior) reusing the same joint |
| `q21-conditional-2x2-with-independence.php` | 4 | number × 4 | 2×2 commute table: marginal, joint, conditional probabilities, then test independence |
| `q22-expected-value-of-discrete-rv.php` | 3 | number × 3 | Discrete RV: confirm probabilities sum to 1, compute E(X) and E(X²) |
| `q23-addition-rule-formula.php` | 3 | number × 3 | Given P(A), P(B), P(A∩B): find P(A∪B), P(A∩B^c), P(neither) — formula application |
| `q24-conditional-dice-reduced-sample-space.php` | 3 | number × 3 | Two dice, given sum=k: \|reduced SS\|, P(first=v \| sum=k), P(first odd \| sum=k) |

## CONVENTIONS

1. **Auto-graded only.** Use `numfunc` for probabilities (MathQuill input — fractions render as stacked fractions, exponents as superscripts; accepts decimals, fractions, and arithmetic expressions). Use `ntuple` for set answers. Numeric tolerance is `0.005` so students can enter 4-decimal rounding (e.g. `0.0769`) or exact fractions (`1/13`) or arithmetic like `1/13 + 1/13` interchangeably. The repo was migrated from `number` to `numfunc` on 2026-05-12 after live verification that fractions and decimals both grade correctly.
2. **Set answer scaffold.** `$answer = "{1,3,5}"` + `$displayformat[i] = "set"` + `$answerformat[i] = "anyorder"` — accepts any bracket style in any order.
3. **Randomization via `jointrandfrom(...)`.** Put the question's parameters AND the precomputed answer (or fraction string) in parallel arrays so grading stays exact:
   ```
   $ks      = array(5, 6, 7, 8, 9, 10)
   $nums    = array(4, 5, 6, 5, 4, 3)
   $reduced = array("1/9", "5/36", "1/6", "5/36", "1/9", "1/12")
   $picked = jointrandfrom($ks, $nums, $reduced)
   ```
4. **2×2 tables.** Pick 3 cell counts with `rand(min,max)`, set the 4th cell so the row/column marginals fit the chosen total. This guarantees the marginals are integers and the joint counts sum to total.
5. **Display the fraction inside the solution.** Probabilities should appear as both the exact fraction (`4/13`) and the decimal approximation (`0.3077`) so students can match either format.
6. **`pow()` is blocked.** Use `2^$n` (IMathAS treats `^` as exponentiation).
7. **Type picker is `Multipart`** in MOM, even for single-part questions, so indexed `$displayformat[i]` / `$answerformat[i]` work as expected.

## ADDING A NEW PROBABILITY QUESTION

1. Copy an existing file that matches the answer shape (`q3-...php` for a single-part probability with parallel arrays, `q5-...php` for a multipart fixed-answer setup, `q10-...php` for a two-way table problem).
2. Replace the parallel arrays / table cells with the new scenarios. Every scenario's answer must be precomputed.
3. Keep the white-card UI and blue-chip part labels for visual consistency with the rest of the family.
4. Set the type picker to **Multipart** in MOM.
5. Verify in the MOM editor preview by clicking through 3-5 New Versions; confirm the rendered question matches the precomputed answer.

## GOTCHAS

- If a probability renders as `0.16666...` and the student enters `0.17`, tolerance of `0.005` accepts it. Tighter tolerance can fail correct rounded answers; looser tolerance can accept wrong answers.
- For two-way tables, never let any cell go to 0 unless that's the pedagogical point — students may misread an empty cell as "missing data."
- The conditional `if (...) { ... }` must use IMathAS syntax (single-line, no braces around `else if`); see `q1-count-outcomes.php`.
- For `choices` answer type, the option text appears verbatim — keep options grammatically parallel so the wording does not telegraph the answer.

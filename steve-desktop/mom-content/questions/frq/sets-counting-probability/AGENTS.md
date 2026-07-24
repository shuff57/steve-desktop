# Sets/Counting/Probability FRQs — Venn Diagrams, Perm vs Comb, Conditional Probability

**Parent:** `../AGENTS.md`
**Files:** 6 free-response questions covering Venn diagram reasoning, permutation vs combination justification (and the contrast explainer), conditional probability interpretation, tree-diagram counting, and two-way table interpretation

## OVERVIEW

These FRQs ask students to explain their reasoning in prose, covering the three topic areas from weeks 11-13 of Applied Finite Math. Each question randomizes its scenario across 3 meaningfully different contexts. Every question follows the standard 5-section FRQ scaffold.

## QUESTION TYPES

| File | Description |
|------|-------------|
| `q1-venn-diagram-reasoning.php` | Draw and interpret a 2-circle Venn diagram from survey data; compute exactly-one and neither counts |
| `q2-permutation-vs-combination.php` | Diagnose whether a counting problem is permutation or combination, justify the choice, and compute |
| `q3-interpreting-conditional-probability.php` | Explain P(A\|B) in context from a two-way table, and explain why P(A\|B) != P(B\|A) |
| `q4-tree-diagram-counting.php` | Build a tree diagram for a 2-stage random experiment, list the sample space, compute P(named event) |
| `q5-twoway-interpretation.php` | From a 2×2 table, compute marginals + P(A∩B), then explain P(A\|B) in context and why P(A\|B) ≠ P(B\|A) |
| `q6-perm-vs-comb-explained.php` | Contrast a permutation scenario with a combination scenario sharing same n,r: identify each, compute both, explain why "order matters" gives r! factor difference |

## CONVENTIONS

1. **FRQ scaffold.** All files follow the 5-section pattern: contexts, CSS/JS block, student rubric, instructor rubric, question text.
2. **10 points each.** Points distributed by conceptual weight, not evenly.
3. **3 randomized contexts per question.** Contexts differ in scenario, not just in numbers.
4. **Narrative variables use $r_ prefix.** One per rubric category, composed into `$sample_narrative`.
5. **Rubric tables** use `border-collapse:separate`, `border-radius:8px`, alternating `#fff9ea` row tint, and `user-select:text` on td elements.

## ADDING A NEW FRQ

1. Copy `free-response-template.php` as the starting point.
2. Design 3 meaningfully different contexts with precomputed answers.
3. Build the rubric (2-4 categories, 10 pts total, neutral student items, specific ideal targets).
4. Follow the `mom-style-guide` for voice, colors, and layout.
5. Verify in MOM testquestion preview before adding to any assessment.

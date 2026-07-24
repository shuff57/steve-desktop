# Sets Questions — Set Notation, Operations, and Venn Counts

**Parent:** `../../AGENTS.md`
**Files:** 14 autograded set questions covering notation, operations, Venn diagrams, De Morgan verification, and inclusion-exclusion arithmetic

## OVERVIEW

Sets questions test set notation, cardinality, the core operations (union, intersection, complement), how disjoint relates to complement, mixed-operation expressions, and 2- and 3-circle Venn-diagram counting. Every question is auto-graded. Every question that can be randomized is randomized — no hardcoded scenarios.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-set-notation-months.php` | 1 | number | Cardinality `|M|` of months starting with a random letter (J/M/A/D) |
| `q2-subsets-two-element.php` | 3 | number, number, number | Subsets of a random 2-element set: count, n, 2^n |
| `q3-subsets-count-only.php` | 1 | number | Count subsets of a random n-element set (n ∈ {4,5,6,7}); answer is 2^n |
| `q4-union.php` | 1 | ntuple (set) | A ∪ B across 3 matched A/B scenarios |
| `q5-intersection.php` | 1 | ntuple (set) | A ∩ B across the same 3 scenarios as Q4 |
| `q6-complement.php` | 1 | ntuple (set) | Complement of A relative to U across 3 U/A scenarios |
| `q7-disjoint-not-complement.php` | 3 | number × 3 | `|A ∩ B|`, `|A ∪ B|`, `|U|` — shows disjoint ≠ complement |
| `q8-mixed-ops-empty.php` | 3 | number × 3 | Cardinalities of stages of `(A ∪ B)^c ∩ C`; final is empty |
| `q9-mixed-ops-union-complement.php` | 1 | ntuple (set) | `A ∪ (B ∩ C)^c` across 3 scenarios |
| `q10-venn-2circle-total.php` | 1 | number | 2-circle Venn total (everyone in at least one set) |
| `q11-venn-2circle-neither.php` | 1 | number | 2-circle Venn neither count |
| `q12-venn-3circle-streamers.php` | 3 | number × 3 | 3-circle Venn: exactly one, at least two, none |
| `q13-demorgan-verification.php` | 3 | number, number, choices | "Find neither" chain: |M∪E| via Addition → |neither| = |U|−|M∪E| → recognize Mᶜ∩Eᶜ |
| `q14-inclusion-exclusion-survey.php` | 3 | number × 3 | Given |U|, |A|, |B|, |neither|: find |A∪B|, |A∩B|, |A only| via inclusion-exclusion |

## CONVENTIONS

1. **Auto-graded only.** Use `number` for cardinalities/Venn counts, `ntuple` for set answers, and `multipart` for multi-part items.
2. **Set answer scaffold.** `$answer = "{1,3,5,7,9}"` + `$displayformat[0] = "set"` + `$answerformat[0] = "anyorder"` — accepts any bracket style in any order.
3. **Randomization via `jointrandfrom(...)`.** Put inputs and the precomputed answer in parallel arrays so grading stays exact:
   ```
   $As       = array("2, 4, 6, 8, 10", "1, 3, 5, 7, 9", "1, 2, 3, 4")
   $Bs       = array("1, 2, 3, 4, 5",  "2, 3, 5, 7",    "3, 4, 5, 6, 7")
   $unions   = array("{1,2,3,4,5,6,8,10}", "{1,2,3,5,7,9}", "{1,2,3,4,5,6,7}")
   $picked = jointrandfrom($As, $Bs, $unions)
   ```
4. **Venn-count randomization.** Randomize the 7 disjoint regions directly with `rand(min, max)` on each, then derive `|A|`, `|B|`, `|A ∩ B|`, etc. from the regions. This guarantees internal consistency and lets you precompute the student-facing answers exactly.
5. **Braces around an interpolated variable.** In Question Text, write `{ $A_display }` with spaces — `{$A_display}` is PHP variable-interpolation and strips the braces. This bit every single set-operation question.
6. **Empty-set results.** Do not try to grade `"{}"` as an ntuple — ask for the cardinality (`= 0`) with a `number` answer instead, or structure as a multipart walking through intermediate non-empty sets.
7. **`pow()` is blocked.** Use `2^$n` (IMathAS treats `^` as exponentiation).
8. **`implode` / C-style `for` loops are blocked.** Use `joinarray($arr, ", ")`, `subarray($arr, "0:$n")`, or simply precompute parallel display strings.

## ADDING A NEW SETS QUESTION

1. Copy an existing file that matches the answer shape (`q4-union.php` for a set-valued answer, `q12-...php` for a Venn question, `q2-...php` for a multipart of numbers).
2. Replace the `$As` / `$Bs` / `$answers` parallel arrays with 3+ scenarios that cover the pedagogical range. Every scenario's answer must be precomputed.
3. Keep braces around interpolated displays: `{ $A_display }`.
4. Set the type picker to **Multipart** in MOM, even for single-part questions, so indexed `$displayformat[0]` / `$answerformat[0]` work as expected.
5. Verify in the MOM editor preview by reading the rendered `.question` text and submitting the computed answer. Iterate through a few `New Version` clicks to confirm every scenario grades as Correct.

## GOTCHAS

- If the rendered set shows `{  }` empty, Common Control errored — check the preview for an "Error in question" banner. Most causes: blocked macro (`pow`, `implode`, etc.), C-style `for` loop, or variable-index lookup in Question Text.
- If `ntuple` grades Correct answers as Incorrect, check that BOTH `$displayformat[0] = "set"` AND `$answerformat[0] = "anyorder"` are set. Either alone is not enough.
- `missing $anstypes for multipart or conditional question` means Common Control errored out before reaching the `$anstypes = ...` line. Fix the earlier error; `$anstypes` itself is rarely the problem.

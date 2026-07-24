# Combinatorics Questions — Permutations and Combinations

**Parent:** `../../AGENTS.md`
**Files:** 17 autograded combinatorics questions covering permutations (7.3) and combinations (7.5)

## OVERVIEW

Combinatorics questions test the multiplication axiom, shrinking-pool permutations, the `nPr` and `nCr` formulas, restricted-slot reasoning, the glue trick for "must sit together", combinations as unordered selections (committees, hands, handshakes, lattice paths), and the perm-vs-comb diagnostic. Every question is auto-graded. Every question is randomized — no fixed scenarios.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-letter-sequences.php` | 1 | number | Shrinking-pool permutations from a small alphabet (4 scenarios pairing pool size + r) |
| `q2-word-restricted-slot.php` | 1 | number | Word permutations with a vowel/consonant in a fixed slot (4 word/restriction scenarios) |
| `q3-compute-npr.php` | 4 | number × 4 | Direct evaluation of four nPr expressions; each part draws from its own (n,r) pool |
| `q4-glue-trick.php` | 1 | number | k specific people seated together in a row of n; answer = (n−k+1)! · k! |
| `q5-shelf-by-subject.php` | 1 | number | Combined permutations across two book pools split by subject |
| `q6-not-together.php` | 3 | number × 3 | Total / together / not-together chain on n people in a row |
| `q7-three-digit-parity.php` | 1 | number | Three-digit number counts under any/odd/even constraint (slot-by-slot reasoning) |
| `q8-word-all-distinct.php` | 1 | number | n! permutations of a word with all distinct letters |
| `q9-compute-ncr.php` | 4 | number × 4 | Direct evaluation of four nCr expressions; each part draws from its own (n,r) pool |
| `q10-committee.php` | 1 | number | Committee of r members from a group of n; context (workers/players/club/students) randomized |
| `q11-card-hand.php` | 1 | number | r-card hand from a 52-card deck; r ∈ {2, 3, 4, 5} |
| `q12-handshakes.php` | 1 | number | Handshakes among n people = nC2; n ∈ {8..15} |
| `q13-coin-heads.php` | 1 | number | Coins tossed n times with exactly k heads; (n,k) drawn from a 5-row pool |
| `q14-lattice-paths.php` | 1 | number | Shortest routes across an a-by-b block grid; answer = C(a+b, a) |
| `q15-perm-or-comb.php` | 4 | choices × 4 | Identify permutation vs combination across 4 random scenarios drawn from 24 prompts |
| `q16-race-finish.php` | 3 | number × 3 | Race permutations: total finish orders, with named runner in 1st, with named runner not in 1st |
| `q17-committee-constraints.php` | 3 | number × 3 | "At least one of Alex or Bea" chain via complement counting: total C(n,r) → neither C(n−2,r) → subtract |

## CONVENTIONS

1. **Auto-graded only.** Use `number` for counts, `multipart` for chains, and `choices` with `$displayformat[n] = "select"` for the perm-vs-comb diagnostic.
2. **Precompute every answer as a literal integer.** This file family does NOT rely on `nCr()` / `nPr()` / `factorial()` IMathAS macros — instead, every (n, r) scenario carries its precomputed answer in a parallel array and is selected with `jointrandfrom`. This avoids macro-availability surprises and keeps grading deterministic.
3. **Randomization via `jointrandfrom(...)`.** Inputs (n, r, scenario text) and the precomputed answer all live in parallel arrays of length 3+. For the multipart compute-nPr/nCr questions, each part has its own pool so the four sub-answers vary independently.
4. **Math expressions in Question Text use backticks.** `\`$na P $ra\`` renders as MathJax via the asciimath pipeline. The `data-asciimath` attribute on each `<mjx-container>` exposes the rendered expression for verification scripts.
5. **Centralize step strings in arrays when the explanation depends on r.** The Q1 letter-sequence file precomputes a `$steps` array (`"4 \cdot 3"`, `"5 \cdot 4 \cdot 3"`, …) so the solution body can reference `'.$step_str.'` instead of dynamically expanding the product in pseudo-PHP.
6. **For compound contexts, randomize sentence templates separately from numeric inputs.** Q10 picks `(n, r, expansion)` via `jointrandfrom`, then independently picks one of four sentence templates that interpolate `$nval` and `$rval`.
7. **Choices answers are 0-indexed.** `$answer[n] = 0` selects the first option, `$answer[n] = 1` the second. Pair with `$noshuffle[n] = "all"` so the option order is fixed and the indices stay valid.
8. **Pre-render answer labels for the solution guide.** When the solution body wants to say "The answer is Permutation," precompute `$label = $labels[$answer[n]]` rather than reading the raw 0/1 (which renders as a number, not the label — see root AGENTS.md anti-patterns).

## ADDING A NEW COMBINATORICS QUESTION

1. Copy an existing file with the matching answer shape (`q1` for a single-number permutation, `q3` for a 4-part compute, `q15` for a multi-dropdown decision).
2. Build a `jointrandfrom` pool of 3+ scenarios, including the precomputed answer in a parallel array. Verify each row's answer by hand or with a calculator.
3. For backtick math (`\`$n P $r\``), confirm the expression renders by reading the `<mjx-container data-asciimath>` attribute on the testquestion preview — text scraping alone misses MathJax SVGs.
4. Verify by reading the rendered question, parsing the displayed n/r values (or `data-asciimath` for math expressions), looking up the expected answer, and submitting it. Repeat through several "New Version" rolls to cover every scenario row.

## GOTCHAS

- **MathJax-rendered expressions are invisible to plain `textContent`** — use `data-asciimath` attributes for verification of `q3-compute-npr.php` and `q9-compute-ncr.php`.
- **`testquestion2.php` previews include the auto-shown answer in the rendered text** ("This solution is for a similar problem, not your specific version" plus the answer value). This means scraping `.question` text catches both the prompt and an "answer hint" — extract the prompt portion only when the test is more complex than a single number.
- **The 10-second Playwriter timeout fires on every Add click** even when the assignment-add succeeded. Re-query the "Questions in Assessment" table after the timeout instead of retrying the action.
- **Default search scope on `addquestions2.php` is "In Libraries"** which doesn't include private unassigned questions when searching by qid. Search by the question description string instead.
- **Choices dropdown values render as integers in `$answer[n]`** — feeding `$answer[0]` directly into prose in the solution guide would print "0" or "1". Map to a string via a `$labels` array first.

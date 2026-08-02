# Second-Half Group Test Blueprint — Math 12 Sp26

**Scope:** Weeks 9–14, Chapters 6–9  
**Target:** 13 problems, ~100 points  
**Item types:** `number`, `choices`, `multipart` (all auto-graded; no FRQs)

| # | Section | Topic | Source | Item Type | Points |
|---|---------|-------|--------|-----------|--------|
| 1 | 6.1 | Simple interest — find I and maturity value A | PULL `questions/finance/simple-interest-and-discount.php` (Part A only) | number (2 parts) | 8 |
| 2 | 6.2 | Compound interest — future value and present value | PULL `questions/finance/compound-interest.php` (Parts A & B only) | number (2 parts) | 8 |
| 3 | 6.3 | Effective annual rate + compare two banks | PULL `questions/finance/compound-interest-compare.php` | number + choices (3 parts) | 8 |
| 4 | 6.4 | Ordinary annuity future value | NEW `questions/annuity/ordinary-annuity-fv.php` | number | 7 |
| 5 | 6.4 | Loan amortization — monthly payment and total interest | NEW `questions/annuity/loan-amortization-payment.php` | number (2 parts) | 9 |
| 6 | 7.1 | 2-set Venn diagram — given region counts, find union/intersection/complement | NEW `questions/sets/venn-two-set.php` | number (3 parts) | 8 |
| 7 | 7.1 | 3-set Venn diagram — inclusion-exclusion cardinality | NEW `questions/sets/venn-three-set.php` | number (3 parts) | 9 |
| 8 | 7.3 | Multiplication principle (counting with sequential choices) | NEW `questions/counting/multiplication-principle.php` | number | 7 |
| 9 | 7.5 | Permutations and combinations — two-part problem | NEW `questions/counting/permutations-combinations.php` | number (2 parts) | 8 |
| 10 | 8.1–8.2 | Sample space + basic probability (single event, complement) | NEW `questions/probability/basic-probability.php` | number (3 parts) | 8 |
| 11 | 8.2–8.4 | Addition rule P(A or B) with two events | NEW `questions/probability/addition-rule.php` | number (2 parts) | 8 |
| 12 | 8.4 | Conditional probability P(A|B) using a two-way table | NEW `questions/probability/conditional-probability.php` | number (2 parts) | 9 |
| 13 | 8.5/9.2 | Expected value — discrete random variable | NEW `questions/probability/expected-value.php` | number | 7 |

**Total: 100 points**

## Pull-from-repo files

- `questions/finance/simple-interest-and-discount.php` — Q1 (Part A only; Parts B & C dropped)
- `questions/finance/compound-interest.php` — Q2 (Parts A & B; Part C dropped)
- `questions/finance/compound-interest-compare.php` — Q3 (all parts)

## New files to author

| File | Description |
|------|-------------|
| `questions/annuity/AGENTS.md` | Folder conventions for annuity/amortization questions |
| `questions/annuity/ordinary-annuity-fv.php` | Ordinary annuity future value (single number answer) |
| `questions/annuity/loan-amortization-payment.php` | Monthly loan payment + total interest (2 numbers) |
| `questions/sets/AGENTS.md` | Folder conventions for set-theory questions |
| `questions/sets/venn-two-set.php` | 2-set Venn diagram cardinalities (3 number answers) |
| `questions/sets/venn-three-set.php` | 3-set Venn inclusion-exclusion (3 number answers) |
| `questions/counting/AGENTS.md` | Folder conventions for counting questions |
| `questions/counting/multiplication-principle.php` | Sequential choice counting (1 number answer) |
| `questions/counting/permutations-combinations.php` | nPr and nCr comparison (2 number answers) |
| `questions/probability/AGENTS.md` | Folder conventions for probability questions |
| `questions/probability/basic-probability.php` | Sample space, P(event), complement (3 number answers) |
| `questions/probability/addition-rule.php` | P(A or B) with/without overlap (2 number answers) |
| `questions/probability/conditional-probability.php` | P(A|B) from two-way table (2 number answers) |
| `questions/probability/expected-value.php` | E(X) for discrete distribution (1 number answer) |

## Notes for deployment

- Pull questions can be used verbatim or adapted (drop parts not needed for this test).
- All new questions use `loadlibrary("finance")` only where the finance library is needed (annuity files); set/counting/probability questions use no library.
- Scoring: `$abstolerance = 0.01` for dollar answers; `$abstolerance = 0.0001` for rate/probability answers; `$abstolerance = 0.5` (or integer format) for cardinality/count answers.

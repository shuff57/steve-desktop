# Counting Questions

**Parent:** `../../AGENTS.md`  
**Sections covered:** 7.3 (multiplication principle), 7.5 (permutations and combinations)

## OVERVIEW

Questions in this folder cover Sections 7.3 and 7.5 of Applied Finite Math. All are auto-graded numeric questions. Use the built-in `factorial(n)`, `nCr(n,r)`, and `nPr(n,r)` macros — no `loadlibrary()` needed.

## FORMULAS IN SCOPE

| Concept | Formula | IMathAS macro |
|---------|---------|--------------|
| Multiplication principle | n₁ × n₂ × … × nₖ | Direct arithmetic |
| Permutations | P(n,r) = n! / (n−r)! | `nPr(n, r)` |
| Combinations | C(n,r) = n! / (r! × (n−r)!) | `nCr(n, r)` |

## DESIGN PATTERN

- Build integer-answer problems: all counts are positive whole numbers
- Use `$answerformat[N] = "integer"` and `$abstolerance = 0.5` for each answer
- Construct problems from a known answer: pick n and r first, compute nPr/nCr, then write the word problem
- Use concrete contexts (passwords, race finishes, committee selection, pizza toppings, etc.)
- Use `$contexts` arrays for scenario variability

## SECTION SCAFFOLD

```
1. No library needed (nCr, nPr, factorial are core macros)
2. $contexts array + $ci for scenario randomization
3. Pick n, r from parallel arrays; compute answers with nCr/nPr
4. $anstypes = array("number") or array("number","number")
5. $answerformat[N] = "integer"; $abstolerance = 0.5
6. $css_block (copy verbatim from free-response-template.php)
7. $solutionguide with Step/Work table showing formula substitution
8. Question text is concrete word problem (no abstract P(n,r) notation in the stem)
```

## FILE NAMING

`{slug}.php` (e.g., `multiplication-principle.php`, `permutations-combinations.php`)

## ANTI-PATTERNS

- Never use `loadlibrary()` — `nCr`, `nPr`, `factorial` are always available
- Never confuse permutation and combination contexts — permutations require ordered selection
- Never pick n and r that make nCr or nPr exceed ~100,000 (answers get unwieldy)
- Never put probability language in counting questions — this folder is about counting only

# Linear Programming Questions — Simplex Method

**Parent:** `../../AGENTS.md`
**Files:** 3 autograded LP questions using the simplex method

## OVERVIEW

Linear programming questions present real-world optimization scenarios where students must set up and solve LP problems using the simplex method. Each question is a separate file with a fixed business context, hardcoded coefficients, and a complete step-by-step solution guide showing every pivot.

## QUESTION TYPES

| File | Context | Objective | Constraints | Solution |
|------|---------|-----------|-------------|----------|
| `q1-simplex-bakery.php` | Bakery (cookies/cakes) | Max 40x₁+30x₂ | x₁+x₂≤12, 2x₁+x₂≤16 | (4, 8), Z=400 |
| `q2-simplex-woodworker.php` | Woodworker (shelves/desks) | Max 50x₁+40x₂ | 2x₁+x₂≤10, x₁+x₂≤8 | (2, 6), Z=340 |
| `q3-simplex-candle-shop.php` | Candle shop (jar/pillar) | Max 20x₁+25x₂ | x₁+2x₂≤14, x₁+x₂≤8 | (2, 6), Z=190 |

All questions use `multipart(number, number, number, file)` — 3 integer answers (optimal x₁, x₂, Z) plus a work upload.

## SECTION SCAFFOLD

```
1. loadlibrary("stats")
2. $anstypes = array("number", "number", "number", "file")
3. $answerformat[N] = "integer" for numeric parts
4. $scoremethod[3] = "takeanything" for upload
5. Hardcoded $answer[0], $answer[1], $answer[2]
6. $css_block with .rubric-container and .simplex-table styles
7. $solutionguide showing full LP formulation, every pivot tableau, and final answer
8. $questiontext with business scenario and resource constraints
```

Output block:
```
$questiontext
$answerbox[0]
$answerbox[1]
$answerbox[2]
<p>Upload your simplex tableau work:</p>
$answerbox[3]

///

$solutionguide
```

## SOLUTION GUIDE

Each file includes a complete solution guide with:
1. Variable definitions and LP formulation
2. Standard form with slack variables
3. Initial simplex tableau
4. Each pivot step with entering/departing variable rationale and resulting tableau
5. Optimality check (all indicators non-negative)
6. Final answer with context interpretation

Tableaux use `.simplex-table` class (monospace, centered, collapsed borders).

## FILE NAMING

`q{N}-simplex-{context}.php` (e.g., `q1-simplex-bakery.php`)

## ANTI-PATTERNS

- Never use randomized arrays for simplex problems in separate files; hardcode all values directly
- Never put the solution guide in the student-visible section; it goes after `///`
- Never use `loadlibrary("matrix")` here; these are number-type answers, not matrix entries

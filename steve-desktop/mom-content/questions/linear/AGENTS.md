# Linear Function Questions — Linear Modeling Patterns

**Parent:** `../../AGENTS.md`
**Files:** 1 question (more to come) covering Chapter 1 linear-function applications: break-even, intersection of lines, supply/demand equilibrium.

## OVERVIEW

These autograded questions ask students to set up and solve linear-function word problems. Unlike the matrix family, they use simple `number` answer types — no matrix entry, no file upload.

## SECTION SCAFFOLD

```
1. loadlibrary("stats")
2. $contexts = parallel arrays of {product, company} pairs; $j = rand(...)
3. Construct-from-solution: pick a clean integer break-even quantity / equilibrium first,
   then derive coefficient values that produce it
4. $anstypes = array("number", "number", ...) — autograded numeric responses
5. $answerformat[N] = "integer" or "decimal" as appropriate
6. Copy $css_block verbatim from free-response-template.php
7. $solutionguide using .rubric-container + .rubric-table Step/Work pattern
```

Output block (no `///` needed — solutionguide goes in the Answer field):
```
$questiontext
$answerbox[0]
$answerbox[1]
...

// === ANSWER ===
$solutionguide
```

## CONSTRUCT-FROM-SOLUTION PATTERN (break-even example)

To guarantee an integer intersection point, fix the intersection FIRST then derive coefficients:

```php
$margin = rand(3, 9)         // chosen profit margin (mr - mc)
$x_be   = 10 * rand(4, 30)   // chosen break-even quantity
$b      = $margin * $x_be    // derived fixed cost
```

This ensures the cost line `mc*x + b` and revenue line `mr*x` cross at a clean `x_be` with integer revenue.

## FILE NAMING

`q{N}-{slug}.php` (e.g., `q1-break-even.php`)

| File | Description |
|------|-------------|
| `q1-break-even.php` | Break-even quantity and revenue from linear cost/revenue functions |

## ANTI-PATTERNS

- Never compute the break-even point by randomizing all coefficients and accepting whatever non-integer answer emerges — derive coefficients FROM a chosen integer solution
- Never put a `file` upload box on a Chapter 1 question — these are short numeric drills
- Never use `loadlibrary("matrix")` here — only `"stats"` is needed

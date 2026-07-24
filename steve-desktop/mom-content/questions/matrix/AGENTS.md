# Matrix Questions — IMathAS Linear Algebra Pattern

**Parent:** `../../AGENTS.md`  
**Files:** 6 matrix computation questions (2×2 and 3×3: RREF, system-of-equations, and inverse+equation)

## OVERVIEW

Matrix questions ask students to row-reduce an augmented matrix and upload hand-written work. Unlike FRQs, these use `loadlibrary("matrix")`, not `"stats"`, and require two answer boxes: a matrix entry field + a file upload.

## SECTION SCAFFOLD

```
1. loadlibrary("matrix")
2. Generate answer first → build question from it (construct-from-solution pattern)
3. Build augmented matrix ($Aug) and display ($disp)
4. Set answer types: $anstypes = array("matrix", "file")
5. Define matrix answer: $answer[0] = matrixformat($RREF)
6. Configure upload: $scoremethod[1] = "takeanything", $answerformat[1] = "images,.pdf"
7. Copy $css_block verbatim from free-response-template.php
8. Build $solutionguide using $css_block + .rubric-container + .rubric-table pattern
```

Output block (no `///` needed — solutionguide goes in Answer field):
```
<div>$disp</div>

RREF: $answerbox[0]

Work upload: $answerbox[1]

// === ANSWER ===
$solutionguide
```

## KEY IMathAS MATRIX MACROS

| Macro | Signature | Purpose |
|-------|-----------|---------|
| `matrix()` | `matrix(array, rows, cols)` | Create matrix object from flat array (row-major) |
| `matrixdisplaytable()` | `matrixdisplaytable(mat, "", 1, 1)` | Render matrix as augmented display table |
| `matrixformat()` | `matrixformat(mat)` | Format matrix for answer comparison |

All require `loadlibrary("matrix")`.

## CONSTRUCT-FROM-SOLUTION PATTERN

Always generate the **known solution first**, then derive the matrix coefficients from it. This guarantees integer arithmetic throughout:

```php
// 2x2 example:
$a = nonzerorand(-2,2)      // off-diagonal coefficient
$c = nonzerorand(-2,2)
$a22 = $a*$c + 1            // ensures determinant = 1 (invertible)

$x0,$y0 = nonzerodiffrands(-4,4,2)   // known solution

$b1 = $x0 + $a*$y0          // RHS of row 1
$b2 = $c*$x0 + $a22*$y0     // RHS of row 2

$Aug = matrix(array(1,$a,$b1, $c,$a22,$b2), 2, 3)
$RREF = matrix(array(1,0,$x0, 0,1,$y0), 2, 3)
```

## SOLUTION GUIDE STRUCTURE

Uses `$css_block` (copied verbatim from `free-response-template.php`) + `.rubric-container` + `.rubric-table` with Step/Work columns. Each intermediate step is a separate `matrix()` object rendered via `matrixdisplaytable()`, nested inside `<td>` cells.

Pattern: `$solutionguide = $css_block . '<div class="rubric-container"><details>...<table class="rubric-table">...</table></details></div>'`

- **Given** row: shows original augmented matrix
- **Step N** rows: row operation label + resulting matrix display
- **Last row**: uses `.col-cat-bot` / `.col-check-bot`, contains green answer highlight with final RREF
- See `reference/css-style-guide.md` for full class reference

## FILE NAMING

`matrix-{2x2|3x3}-{rref|equation|inverse-equation}.php`

| File | Description |
|------|-------------|
| `matrix-2x2-rref.php` | 2×2 system → RREF (2 rows, 3 cols augmented) |
| `matrix-2x2-equation.php` | 2×2 system of equations presentation |
| `matrix-3x3-rref.php` | 3×3 system → RREF (3 rows, 4 cols augmented) |
| `matrix-3x3-equation.php` | 3×3 system of equations presentation |
| `matrix-2x2-inverse-equation.php` | 2×2 matrix inverse + system of equations |
| `matrix-3x3-inverse-equation.php` | 3×3 matrix inverse + system of equations |

## ANTI-PATTERNS

- Never hardcode matrix entries — always derive from random solution values
- Never use `loadlibrary("stats")` here — use `"matrix"` only
- Never set a correct answer for the upload box — `$scoremethod[1] = "takeanything"` always
- Never use inline styles for solution guides — use `$css_block` classes (`.rubric-container`, `.rubric-table`, etc.)
- The `$answersize[0]` must match matrix dimensions: `"2,3"` for 2×2, `"3,4"` for 3×3

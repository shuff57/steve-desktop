# Matrix Question Types

Types: `numericalmatrix`, `calculatedmatrix`, `complexmatrix`, `calccomplexmatrix`, `algmatrix`

Matrix answer format: `'[(row1col1,row1col2,...),(row2col1,...)]'`  
Example 2×3 matrix: `'[(1,2,3),(4,5,6)]'`

---

## `numericalmatrix` — Numerical Matrix

Student enters a matrix of numbers. Entries compared to tolerance. Multipart name: `matrix`.

### Required Variables

- `$answer` — Matrix string. Example: `'[(1,2,3),(8/2,5,6)]'` is a 2×3 matrix.

### Options / Settings

- `$answersize` — `'rows,cols'`. Provides grid of entry boxes. Without it, student uses ASCIIMath notation.
- `$answerformat` — `scalarmult`, `ref` (row echelon form), `rowequiv`, `anyroworder`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$scoremethod` — `'byelement'` for partial credit.
- `$displayformat` — `'det'` for vertical bars (determinant), `'inline'` for inline display, `'augmented'` for augment bar.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20. Only used without `$answersize`.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `$answer`.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("numericalmatrix", "file");
$answer[0] = "[(1,0,0),(0,1,0),(0,0,1)]";  // 3x3 identity
$answersize[0] = "3,3";
$scoremethod[0] = "byelement";
$scoremethod[1] = "takeanything";
$answerformat[1] = "images,.pdf";
```

---

## `calculatedmatrix` — Calculated Matrix

Student enters a matrix of calculations (`2/3`, `5^2`). Entries compared to tolerance. Multipart name: `calcmatrix`.

### Required Variables

- `$answer` — Matrix string. Example: `'[(1,2,3),(8/2,5,6)]'`.

### Options / Settings

- `$answersize` — `'rows,cols'`.
- `$answerformat` — `scalarmult`, `ref`, `rowequiv`, `anyroworder`, `fraction`, `reducedfraction`, `mixednumber`, `scinot`, `fracordec`, `nodecimal`, `notrig`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$scoremethod` — `'byelement'`.
- `$displayformat` — `'det'`, `'inline'`, `'augmented'`.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$hidepreview` — `true` to hide Preview.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `$answer`.
- `$previewloc` — In Question Text.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("calculatedmatrix");
$answer[0] = "[(1/2,2/3),(3/4,1/5)]";
$answersize[0] = "2,2";
$answerformat[0] = "fraction";
```

---

## `complexmatrix` — Complex Matrix

Student enters a matrix of complex numbers in `a+bi` form. Multipart name: `complexmatrix`.

### Required Variables

- `$answer` — Matrix of complex numbers. Example: `'[(1+i,2,3i),(8,5+i,6)]'`.

### Options / Settings

- `$answersize` — `'rows,cols'`.
- `$answerformat` — `nosoln`, `nosolninf`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$scoremethod` — `'byelement'`.
- `$displayformat` — `'det'`, `'inline'`, `'augmented'`.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `$answer`.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("complexmatrix");
$answer[0] = "[(1+2i,3-i),(0,4i)]";
$answersize[0] = "2,2";
$scoremethod[0] = "byelement";
```

---

## `calccomplexmatrix` — Calculated Complex Matrix

Student enters a matrix of complex calculations like `2/3+1/3i` or `5^2`. Multipart name: `calccomplexmatrix`.

### Required Variables

- `$answer` — Matrix of complex expressions. Example: `'[(1+i,2,3i),(3/2+i,3sqrt(2)i,6)]'`.

### Options / Settings

- `$answersize` — `'rows,cols'`.
- `$answerformat` — `sloppycomplex`, `generalcomplex`, `allowjcomplex`, `fraction`, `reducedfraction`, `mixednumber`, `scinot`, `fracordec`, `nodecimal`, `notrig`, `nosoln`, `nosolninf`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$scoremethod` — `'byelement'`.
- `$displayformat` — `'det'`, `'inline'`, `'augmented'`.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$hidepreview` — `true` to hide Preview.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `$answer`.
- `$previewloc` — In Question Text.
- `$hidetips` — `true` to hide tips.

---

## `algmatrix` — Algebraic Matrix

Student enters a matrix of algebraic expressions like `x^2` or `3t+1`. Multipart name: `algmatrix`.

### Required Variables

- `$answer` — Matrix of expressions. Example: `'[(x,x^2,3),(4,5+x,2)]'`.

### Options / Settings

- `$answersize` — `'rows,cols'`.
- `$variables` — List of variables. Default `'x'`.
- `$domain` — `'inputmin,inputmax'`. Default `-10` to `10`.
- `$answerformat` — `generalcomplex`, `nosoln`, `nosolninf`, `sameform`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$scoremethod` — `'byelement'`.
- `$displayformat` — `'det'`, `'inline'`, `'augmented'`.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$hidepreview` — `true` to hide Preview.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `$answer`.
- `$previewloc` — In Question Text.
- `$hidetips` — `true` to hide tips.

### Notes

- All matrix types use `loadlibrary("matrix")` for full matrix operation support.
- Build the answer matrix first from a known solution, then construct the question around it (avoids impossible systems).
- See `questions/matrix/` for complete working examples of `numericalmatrix` + `calculatedmatrix` patterns.

# N-Tuple and Complex Number Question Types

Types: `ntuple`, `calcntuple`, `complexntuple`, `calccomplexntuple`, `algntuple`, `complex`, `calccomplex`

N-tuples use brackets: `()`, `[]`, `{}`, or `<>`.

---

> **Common options** (all types): `$readerlabel` — screenreader label; `$answerbox` — in Question Text; `$showanswer` — defaults to correct answer; `$hidetips = true` — hide tips.

## `ntuple` — N-Tuple

Student enters an n-tuple or list of n-tuples. For coordinate points, vectors, etc. Multipart name: `ntuple`.

### Answer Format

```
'(1,2)'               // point
'<2,3,4>,<1,5,7>'    // list of vectors
'(1,2) or (1,3)'     // multiple valid answers
```

### Required Variables

- `$answer` — String with n-tuple(s). Surrounded by `()`, `[]`, `{}`, or `<>`.

### Options / Settings

- `$displayformat` — `point`, `pointlist`, `vector`, `vectorlist`, `list`, `set`, `setlist`. Changes tips only.
- `$answerformat` — `scalarmult`, `anyorder`, `nosoln`, `nosolninf`.
- `$scoremethod` — `'byelement'` for partial credit on individual elements.
- `$partweights` — Per-element weights with `byelement`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.

### Example

```php
$anstypes = array("ntuple");
$answer = "(3, -2)";
$displayformat[0] = "point";
$scoremethod[0] = "byelement";
```

---

## `calcntuple` — Calculated N-Tuple

Like N-Tuple but allows mathematical expressions like `(5/3, 2/3)`. Multipart name: `calcntuple`.

### Required Variables

- `$answer` — String with n-tuple(s) of expressions.

### Options / Settings

- `$displayformat` — `point`, `pointlist`, `vector`, `vectorlist`, `list`, `set`, `setlist`.
- `$answerformat` — `scalarmult`, `anyorder`, `fraction`, `reducedfraction`, `mixednumber`, `scinot`, `fracordec`, `nodecimal`, `notrig`, `nosoln`, `nosolninf`, `sameform`.
- `$scoremethod` — `'byelement'`.
- `$partweights` — Per-element weights.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.

### Example

```php
$anstypes = array("calcntuple");
$answer = "(1/3, 2/5)";
$answerformat[0] = "fraction";
```

---

## `complexntuple` — Complex N-Tuple

N-tuple of complex numbers in `a+bi` form. Multipart name: `complexntuple`.

### Required Variables

- `$answer` — String with n-tuple(s) of complex numbers. Examples: `'(1,2+i)'`, `'<i,3+i,4>,<1+i,5-i,7>'`.

### Options / Settings

- `$displayformat` — `point`, `pointlist`, `vector`, `vectorlist`, `list`, `set`, `setlist`.
- `$answerformat` — `allowjcomplex`, `nosoln`, `nosolninf`.
- `$scoremethod` — `'byelement'`.
- `$partweights` — Per-element weights.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.

---

## `calccomplexntuple` — Calculated Complex N-Tuple

Like Complex N-Tuple but allows mathematical expressions like `(5/3+sqrt(2)i, 2/3+1/4i)`. Multipart name: `calccomplexntuple`.

### Required Variables

- `$answer` — String with n-tuple(s). Must be `a+bi` format unless `sloppycomplex`/`generalcomplex` used.

### Options / Settings

- `$displayformat` — `point`, `pointlist`, `vector`, `vectorlist`, `list`, `set`, `setlist`.
- `$answerformat` — `sloppycomplex`, `generalcomplex`, `allowjcomplex`, `fraction`, `reducedfraction`, `mixednumber`, `scinot`, `fracordec`, `nodecimal`, `notrig`, `nosoln`, `nosolninf`, `sameform`.
- `$scoremethod` — `'byelement'`.
- `$partweights` — Per-element weights.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer.
- `$hidetips` — `true` to hide tips.

---

## `algntuple` — Algebraic N-Tuple

N-tuple of algebraic expressions. For parameterized points/vectors like `(x, x^2, 4-x)`. Multipart name: `algntuple`.

### Required Variables

- `$answer` — String with n-tuple(s) of expressions. Examples: `'(x,x^2)'`, `'<t,4-t,2+3t>'`.

### Options / Settings

- `$displayformat` — `point`, `pointlist`, `vector`, `vectorlist`, `list`, `set`, `setlist`.
- `$variables` — List of variables. Default `'x'`.
- `$domain` — `'inputmin,inputmax'`. Default `-10` to `10`.
- `$answerformat` — `generalcomplex`, `nosoln`, `nosolninf`, `sameform`.
- `$requiretimes` — Format checking.
- `$requiretimeslistpart` — Per-ntuple format checking for lists.
- `$scoremethod` — `'byelement'`.
- `$partweights` — Per-element weights.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.

---

## `complex` — Complex Number

Student enters a complex number in `a+bi` form. Multipart name: `complex`.

### Required Variables

- `$answer` — Complex number string. Example: `'3+2i'`.

### Options / Settings

- `$answerformat` — `'list'` for list of complex numbers. `'allowjcomplex'` for `j` instead of `i`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.

### Example

```php
$anstypes = array("complex");
$answer = "3+2i";
```

---

## `calccomplex` — Calculated Complex Number

Like Complex but allows expressions like `1/3+sqrt(2)i`. Must be `a+bi` format unless `sloppycomplex` used. Multipart name: `calccomplex`.

### Required Variables

- `$answer` — Complex expression string. Example: `'3+2i'`, `'2/3+1/3i'`.

### Options / Settings

- `$answerformat` — `sloppycomplex`, `generalcomplex`, `allowjcomplex`, `fraction`, `reducedfraction`, `mixednumber`, `scinot`, `fracordec`, `nodecimal`, `notrig`, `sameform`, `list`, `allowplusminus`, `nosoln`, `nosolninf`.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.

### Example

```php
$anstypes = array("calccomplex");
$answer = "1/2 + sqrt(3)/2 * i";
$answerformat[0] = "fraction";
```

**Notes:** `getntupleparts(string)` extracts components from n-tuple `$stuanswers`/`$stuanswersval`. `scoremultiorder()` supports reordering for `ntuple`, `calcntuple`, `complex`, `calccomplex`.

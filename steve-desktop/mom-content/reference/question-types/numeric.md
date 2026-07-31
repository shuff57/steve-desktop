# Numeric Question Types

Types: `number`, `calculated`

---

## `number` — Numeric Answer

Student enters a number (integer, decimal, or scientific notation). Compared to tolerance. Also accepts `DNE`, `oo`, `-oo`.

### Answer Format

```php
$answer = 42;             // single number
$answer = "3 or 5 or 7"; // multiple correct values
$answer = "(2,5]";        // range answer
$answer = "1,2,3";        // list answer
```

### Options / Settings

- `$reltolerance` — Relative error tolerance. Default `.001` (.1%).
- `$abstolerance` — Absolute error tolerance. Overrides `$reltolerance`.
- `$reqdecimals` — Minimum decimal accuracy. `'=2'` requires exactly 2. `'r2'` requires rounding to 2. Supports `+-` suffix for tolerance.
- `$reqsigfigs` — Significant figure accuracy. `'=2'` requires exactly 2. `'[2,4]'` for range. Supports `+-` suffix.
- `$answerformat` — `list`, `exactlist`, `orderedlist`, `set`, `integer`, `units`, `nosoln`, `nosolninf`. Can combine: `'nosoln,list'`.
- `$requiretimes` — Format checking. Pairs: `symbol,count`. `'#'` prefix for whole number match. `'||'` for alternatives. `'regex:'` for regex.
- `$requiretimeslistpart` — Like `$requiretimes` but applied to each list element.
- `$partialcredit` — `array(number,score,...)` for partial credit on other answers.
- `$ansprompt` — String displayed before input box.
- `$displayformat` — `'alignright'` to right-align.
- `$answerboxsize` — Character width. Default 20.
- `$readerlabel` — Visually hidden screenreader label.
- `$answerbox` — In Question Text — places input box.
- `$showanswer` — Answer shown to students. Defaults to `$answer`.
- `$hidetips` — `true` to hide entry tips.

### Example

```php
$anstypes = array("number");
$answer = 3.14159;
$reltolerance = .001;
```

---

## `calculated` — Computed Numeric

Student enters a number or calculation (`2/3`, `5^2`, `sin(2)`). Also accepts `DNE`, `oo`, `-oo`. String form of answer renders: `$answer = "2^4"` shows `2^4`.

### Answer Format

```php
$answer = 16;       // number
$answer = "2^4";    // rendered string form
```

### Options / Settings

- `$answerformat` — Base formats: `fraction`, `reducedfraction`, `mixednumber`, `mixednumberorimproper`, `sloppymixednumber`, `scinot`, `scinotordec`, `fracordec`, `decimal`. Mixins: `allowunreduced`, `allowxtimes`, `allowplusminus`, `nodecimal`, `notrig`, `allowmixed`, `allowdegrees`, `noval (deprecated)`, `showval`, `sameform`. List options: `list`, `exactlist`, `orderedlist`. Special: `nosoln`, `nosolninf`.
- `$formatfeedbackon` — `true` to enable right-answer-wrong-format feedback.
- `$reltolerance` — Relative error tolerance. Default `.001`.
- `$abstolerance` — Absolute error tolerance. Overrides `$reltolerance`.
- `$reqdecimals` — Decimal accuracy required.
- `$reqsigfigs` — Significant figure accuracy. Only for `decimal`, `scinot`, `scinotordec` formats.
- `$requiretimes` — Format checking. Same as Number type, plus `'ignore_spaces,true'` and `'ignore_symbol,$'`.
- `$requiretimeslistpart` — Per-element format checking. Semicolon-separated for different per element.
- `$partialcredit` — `array(answer,score,...)`. Can make `$requiretimes`/`$answerformat` arrays for partial credit answers.
- `$ansprompt` — String before input box.
- `$answerboxsize` — Default 20.
- `$hidepreview` — `true` to hide Preview button.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `$answer`.
- `$previewloc` — In Question Text — preview button location.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("calculated");
$answer = "2/3";
$answerformat = "fraction";
```

### Notes

- Use `calculated` when you want students to enter expressions (fractions, exponents, trig values) rather than plain decimals.
- `$reqsigfigs` only works with `decimal`, `scinot`, `scinotordec` formats.

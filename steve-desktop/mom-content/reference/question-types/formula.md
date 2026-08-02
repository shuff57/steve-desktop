# Formula / Expression Question Types

Types: `function` (multipart name: `numfunc`), `string`, `interval`, `calcinterval`

---

## `function` — Function / Algebraic Expression

Student enters a function or algebraic expression or equation. System accepts any algebraically equivalent answer. Multipart name: `numfunc`.

### Answer Format

```php
$answer = "2sin(x)";        // single expression
$answer = "x^2 or x^4";     // multiple valid answers
```

### Required Variables

- `$answer` — Answer function as string.

### Options / Settings

- `$variables` — List of variables. Default `'x'`. Use `'f()'` for function symbols.
- `$domain` — `'inputmin,inputmax'`. Default `-10` to `10`. Third element `'integers'` for integer domain. Multi-variable: `'0,5,20,25'`.
- `$requiretimes` — Format checking. `'#'` matches any number. `'regex:'` for regex. `'ignore_case,false'` for case sensitivity.
- `$answerformat` — `equation`, `inequality`, `doubleinequality`, `generalcomplex`, `toconst`, `scalarmult`, `allowplusminus`, `nosoln`, `nosolninf`, `sameform`, `list`, `casesensitivevars`. Can combine.
- `$partialcredit` — `array(string,score,...)` for partial credit expressions.
- `$formatfeedbackon` — `true` for wrong-format feedback.
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$hidepreview` — `true` to hide Preview.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to `makeprettydisp($answer)`.
- `$previewloc` — In Question Text — preview button location.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("function");
$answer = "x^2 + 3x - 4";
$variables = "x";
$domain = "-10,10";
```

---

## `string` — String / Word Answer

Student enters a string (word or list of letters). Multipart name: `string`.

### Required Variables

- `$answer` — String answer. Multiple: `$answer = "dog or cat"`.

### Options / Settings

- `$strflags` — Comparison flags (set to `1` for on, `0` for off):
  - `ignore_case` — Ignores case differences. **Default ON.**
  - `trim_whitespace` — Trims leading/ending whitespace.
  - `compress_whitespace` — Compresses multiple spaces to one. **Default ON.**
  - `remove_whitespace` — Removes all whitespace.
  - `ignore_order` — Treats `ABC` and `CBA` as equivalent.
  - `ignore_commas` — Removes commas.
  - `partial_credit` — Awards partial credit based on Levenshtein distance.
  - `all_words` — Credit for containing key words. `$answer` is comma-separated word list.
  - `allow_diff=n` — Full credit for Levenshtein distance <= n.
  - `in_answer` — Credit if `$answer` contained anywhere in student answer.
  - `regex` — Interprets `$answer` as regular expression.
  - `special_or` — Use `*or*` instead of `or` for separating answers.
  - `ignore_symbol=sym` — Ignore specified symbol. Can use multiple times.
  - **Note:** Only one of `partial_credit`, `allow_diff`, `in_answer`, `regex` at a time.
- `$scoremethod` — `'takeanything'`, `'takeanythingorblank'`, `'submitblank'`.
- `$answerformat` — `'list'` for list of answers. `'logic'` for logic statements (define `$variables`). `'setexp'` for set expressions.
- `$partialcredit` — `array(string,score,...)`. Not for `list` or `all_words`.
- `$displayformat` — `'usepreview'` for mathquill, `'usepreviewnomq'` for asciimath preview, `'typeahead'` for auto-suggest (provide list in `$questions`).
- `$variables` — For `logic`/`setexp` answerformat.
- `$requiretimes` — Format checking, mainly for `logic`/`setexp`.
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer text.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("string");
$answer = "photosynthesis";
$strflags['ignore_case'] = 1;
```

---

## `interval` — Interval Notation

Student enters interval notation. Example: `(2,5]U(7,oo)`. `DNE` for empty set. Multipart name: `interval`.

### Required Variables

- `$answer` — Interval notation string. `U` for union. `oo`/`-oo` for infinity. Multiple: `'or'`.

### Options / Settings

- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$reqdecimals` — Decimal accuracy required.
- `$answerformat` — `'normalcurve'` for shade-under-curve widget. `'list'` for comma-separated intervals. `'allowsloppyintervals'` accepts unsimplified unions.
- `$scoremethod` — `'partialcredit'` for partial credit scoring (new system only).
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("interval");
$answer = "(-oo,3]U(5,oo)";
```

---

## `calcinterval` — Calculated Interval

Interval with calculated values like `[2/5,sqrt(8)]`. Can use inequality format. Multipart name: `calcinterval`.

### Required Variables

- `$answer` — Interval notation string. Example: `'(-oo,4]U(9/2,oo)'`.

### Options / Settings

- `$answerformat` — `fraction`, `reducedfraction`, `mixednumber`, `scinot`, `fracordec`, `nodecimal`, `notrig`, `list`, `allowsloppyintervals`, `inequality`. `'inequality'` requires student to enter inequality (answer still in interval form).
- `$reltolerance` — Default `.001`.
- `$abstolerance` — Overrides `$reltolerance`.
- `$reqdecimals` — Decimal accuracy required.
- `$scoremethod` — `'partialcredit'` (new system only).
- `$ansprompt` — String before input.
- `$answerboxsize` — Default 20.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer.
- `$previewloc` — In Question Text.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("calcinterval");
$answer = "[1/3, sqrt(5)]";
$answerformat = "fraction";
```

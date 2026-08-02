# Choice Question Types

Types: `multiple_choice`, `multiple_answer`, `matching`

---

## `multiple_choice` — Multiple Choice

Student selects the correct answer. Order automatically randomized. Multipart name: `choices`.

### Required Variables

- `$questions` — Array of choices (or `$choices` — don't define both).
- `$answer` — Index into `$questions` for correct answer (zero-indexed). Multiple correct: `$answer = "0 or 1"`.

### Options / Settings

- `$displayformat` — `horiz`, `select`, `2column`, `3column`, `inline`.
- `$noshuffle` — `'all'` (no shuffle), `'last'` (shuffle all but last), `'last2'`, `'last3'`.
- `$partialcredit` — `array(index,score,...)`.
- `$readerlabel` — Screenreader label.
- `$ansprompt` — Override `'Select an answer'` text when `displayformat=select`.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer text.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("multiple_choice");
$questions = array("Paris", "London", "Madrid", "Rome");
$answer = 0;  // Paris is correct
$noshuffle = "last";
```

---

## `multiple_answer` — Multiple Answer

Student selects all correct choices. Order automatically randomized. Multipart name: `multans`.

### Required Variables

- `$questions` — Array of choices (or `$choices`).
- `$answers` — List of correct indices. Multiple valid combos: `$answers = "0,1 or 0,3"`.

### Options / Settings

- `$scoremethod` — `'answers'` (divide by answer count), `'allornothing'`, `'takeanything'`.
- `$displayformat` — `horiz`, `2column`, `3column`, `inline`.
- `$noshuffle` — `'all'` or `'last'`.
- `$answerformat` — `'addnone'` adds `'None of these'` option.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answers text.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("multiple_answer");
$questions = array("Red", "Blue", "Green", "Yellow");
$answers = "0,2";  // Red and Green are correct
$scoremethod = "allornothing";
```

---

## `matching` — Matching

Student matches answers with questions. Multipart name: `matching`.

### Required Variables

- `$questions` — Array of questions (left side with entry boxes).
- `$answers` — Array of answers (right side, lettered).

### Options / Settings

- `$matchlist` — Indices into `$answers` for each question. Default: one-to-one. Example: `'0,0,1'` (first two questions both match answer 0).
- `$questiontitle` — Title above questions list.
- `$answertitle` — Title above answers list.
- `$noshuffle` — `'questions'` or `'answers'` to retain original order of one list.
- `$displayformat` — `'select'` or `'2columnselect'`. Only for pure text answers.
- `$scoremethod` — `'allornothing'` for no partial credit.
- `$ansprompt` — Override default `'-'` in dropdowns.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct matches.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("matching");
$questions = array("Dog", "Cat", "Fish");
$answers = array("Bark", "Meow", "Swim");
// Default matchlist: 0,1,2 (one-to-one)
$noshuffle = "answers";  // Keep answers in original order
```

### Notes

- `$matchlist` enables many-to-one matching (e.g., multiple questions can match the same answer).
- `$displayformat = 'select'` or `'2columnselect'` is only valid for plain text answers (no math markup).

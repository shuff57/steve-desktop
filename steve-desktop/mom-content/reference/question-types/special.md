# Special Question Types

Types: `multipart`, `conditional`, `chemeqn`, `molecule`

---

## `multipart` — Multipart Question

Contains multiple parts, each a different question type. Parts are scored independently (or as a whole). Multipart name: N/A — this IS the container type.

### Required Variables

- `$anstypes` — Array or list of answer type short names:
  `number`, `calculated`, `choices`, `multans`, `matching`, `numfunc`, `draw`, `ntuple`, `calcntuple`, `complexntuple`, `calccomplexntuple`, `algntuple`, `matrix`, `calcmatrix`, `complexmatrix`, `calccomplexmatrix`, `algmatrix`, `complex`, `calccomplex`, `interval`, `calcinterval`, `chemeqn`, `molecule`, `essay`, `file`, `string`.
- Per-part variables — Define `$answer[0]`, `$answer[1]`, etc. with part index suffix. Variables without index apply to all parts.
- `$answerbox[partnum]` — In Question Text. Or use `[AB0]`, `[AB1]` shorthand.

### Options / Settings

- `$answeights` — Array/list of weights. Best as percents: `array(.2,.3,.5)`.
- `$scoremethod` — `'singlescore'` (total parts), `'allornothing'`. Use `$scoremethod['whole']` for per-part values too.
- `$defaults` — `$defaults['variable'] = value` to set default for all parts while allowing per-part overrides.
- `$showanswer` — Per-part: `$showanswer[1]='...'`. Or single string for whole problem.
- `$showanswerloc` — In Question Text. `$showanswerloc` or `$showanswerloc[part]`. Or `[SAB]`/`[SABn]` shorthand. `$showanswerstyle = 'inline'` for inline display.
- `[SAB#]` — In Detailed Solution to include show-answer values directly.

### Scaffolding

Break question into chunks. Second chunk shows after first is correctly answered or tries exhausted.

- Add isolated line with `///` in Question Text where chunks break.
- **Note:** Only available in new assessment interface. Old interface shows as regular multipart.

### Example

```php
$anstypes = array("number", "calculated", "essay");
$answer[0] = 42;
$answer[1] = "2/3";
$answeights = array(.4, .4, .2);
$displayformat[2] = 'editornopaste';
```

---

## `conditional` — Conditional Scoring

Variation on multipart. Multiple answer boxes, single score based on a conditional test of student answers. Multipart name: N/A — container type.

### Required Variables

- `$anstypes` — Array or list of answer types.
- `$answer` — Boolean expression, numerical value `[0,1]`, `array('function',$a,$b)`, `array('number',$a,$b)`, or array of mixed comparisons.
- Per-part variables — Define with index suffix. **Do NOT define per-part `$answer`.**
- `$answerbox[partnum]` — In Question Text.

### Options / Settings

- Per-part options — Suffix with part index. `$variables`, `$domain`, `$reltolerance`, `$abstolerance` used for comparison — don't set per-part.
- `$showanswer` — Defaults to `'Answers may vary'`.

### Scaffolding

- Add `///` in Question Text.
- Set `$seqPartDone[pn] = true` for each part when completed.
- All parts in previous chunk must have `$seqPartDone = true` to advance.

### Example

```php
// Student enters x and y — correct if x + y = 10
$anstypes = array("number", "number");
$answer = ($stuanswers[$thisq][0] + $stuanswers[$thisq][1] == 10);
```

### Notes

- `conditional` is useful when there are multiple valid input combinations, e.g., entering any two numbers that sum to a target value.
- Unlike `multipart`, there is a single pass/fail score — you cannot give partial credit per part.

---

## `chemeqn` — Chemical Equation

Student enters a chemical formula or reaction equation. Multipart name: `chemeqn`.

### Required Variables

- `$answer` — Chemical formula string like `'H_2O'`. Spaces ignored. For reactions: `'->'` for right arrow, `'<->'` for equilibrium. Example: `'2H+O->H_2O'`.

### Options / Settings

- `$answerformat` — `'reaction'` for reaction equations.
- `$variables` — List of atoms to populate the equation editor.
- `$answerboxsize` — Default 20.
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer.

### Example

```php
$anstypes = array("chemeqn");
$answer = "H_2SO_4";
```

### Reaction Example

```php
$anstypes = array("chemeqn");
$answer = "CH_4 + 2O_2 -> CO_2 + 2H_2O";
$answerformat[0] = "reaction";
$variables[0] = "C,H,O";
```

---

## `molecule` — Molecule Drawing

Student sketches a molecule using the Kekule drawing tool. Keyboard alternative provided for accessibility. Multipart name: `molecule`.

### Required Variables

- `$answer` — `'SMILES~~~CML'` format. **Use the answer generator tool** in the question editor — do not write manually. Multiple answers: separated by `'or'`.

### Options / Settings

- `$displayformat` — `'condensed'` to display atom labels on the drawing.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to drawing of correct answer.

### Example

```php
$anstypes = array("molecule");
// $answer generated using the question editor's molecule tool
$answer = "c1ccccc1~~~<cml>...</cml>";  // benzene — use generator for actual CML
$displayformat[0] = "condensed";
```

### Notes

- The `SMILES~~~CML` format is complex — always use the built-in answer generator tool when authoring molecule questions.
- Keyboard-based entry is automatically available as an accessibility alternative.

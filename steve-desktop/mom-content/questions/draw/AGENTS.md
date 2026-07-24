# Draw Questions — Coordinate-Plane Graphing

**Parent:** `../../AGENTS.md`
**Files:** 2 draw questions

## OVERVIEW

Draw questions use the `draw` answer type: the student places points on a coordinate grid and MOM grades by comparing the underlying equation. Expand when more graphing tasks are needed.

## QUESTION TYPES

| File | Answer Type | Description |
|------|-------------|-------------|
| `graph-linear-function.php` | draw (twopoint) | Graph y = mx + b on a grid. Random integer slope (±1..±3) and nonzero integer intercept (±1..±4). |
| `graph-sine-transformation.php` | draw (twopoint,trig) | Graph y = a·sin(x−h) + k over one period using the sine curve tool. Random amp (2–3) with reflection, phase shift h ∈ {0, ±π/6, ±π/4, ±π/3}, vertical shift k ∈ {±1, ±2}. |

## CONVENTIONS

1. Set question type to **draw** in the MOM editor (not multipart).
2. Build `$answers` as a cleaned function string: `makepretty($m_part . "x+" . $b)` so sign quirks like `"x+-3"` become `"x-3"`.
3. Keep the displayed LaTeX equation separate from `$answers` — wrap render logic in its own variable (`$m_latex`, `$b_latex`).
4. Choose slopes from a short `$m_opts` list of integers only to guarantee clean grid snapping. Avoid fractional slopes unless the tool supports them.
5. Exclude zero from intercept options when the pedagogical point requires a non-origin line.

## ADDING A NEW DRAW QUESTION

1. Copy `graph-linear-function.php` as the starting template.
2. Randomize the parameters with `randfrom(array(...))`; keep options integer where possible.
3. Set the MOM question type to **Drawing** via the question-type picker.
4. Preview in MOM and verify the correct graph submits as **Correct**; test two or three seeds before declaring done.

## GOTCHAS

- If the grader rejects a correct-looking line, the `$answers` string may have a sign glitch (`x+-3`). Always pipe through `makepretty(...)`.
- The draw tool needs two distinct clicks before Submit — scripted verification must place both before calling Submit.

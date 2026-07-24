# FRQ Questions — IMathAS Free-Response Pattern

**Parent:** `../../AGENTS.md`

## OVERVIEW

FRQ files are organized into subfolders by source question set. Each subfolder name matches the question-set txt file (without `.txt`). All files follow an identical 5-section scaffold — copy `../../free-response-template.php` as the starting point, never write from scratch.

## FOLDER STRUCTURE

```
frq/
  descriptive-statistics/       # Sampling, variable types, displays, summary stats (9 questions)
  normal-distribution/          # Z-scores, empirical rule, percentiles, QQ plots, CLT (5 questions)
  inference-for-proportions/    # Proportions, chi-square, general inference concepts (13 questions)
  inference-for-means/          # Inference for means FRQs (10 questions)
```

Each subfolder contains its own `manifest.json` and source prompt file (`prompts.txt` or `INDEX.txt`).

## SECTION SCAFFOLD (in order inside Common Control)

```
1. Library + answer type setup
2. $contexts array + scenario index ($i)
3. Parallel scenario arrays indexed by $i
4. $css_block  (shared CSS + JS — copy verbatim from template)
5. $rubricbutton  (student checklist, NO answers)
6. $rubricanswerbutton  (instructor rubric WITH .ideal-ans targets + model narrative)
7. $questiontext  (prompt HTML, embeds $rubricbutton at bottom)
```

Then the answer output block:
```
$questiontext
$answerbox[0]
///
$rubricanswerbutton
```

## KEY VARIABLES

| Variable | Purpose |
|----------|---------|
| `$contexts` | Array of ≥3 randomized scenario strings |
| `$i` | `rand(0, count($contexts)-1)` — scenario selector |
| `$css_block` | Full `<style>` + `<script>` block — copy verbatim |
| `$rubricbutton` | Student-visible checklist — checkboxes only, no answers |
| `$rubricanswerbutton` | Instructor rubric — `.ideal-ans` spans reveal target answers |
| `$sample_narrative` | Concatenated model narrative for the `full-response-box` |
| `$questiontext` | Complete question HTML wrapping prompt + `$rubricbutton` |

## RUBRIC STRUCTURE

```html
<!-- Student rubric ($rubricbutton): -->
<tr class="row-colored">
  <td><b>Category Name</b></td>
  <td><ul><li><label><input type="checkbox"> Requirement text</label></li></ul></td>
</tr>

<!-- Instructor rubric ($rubricanswerbutton): -->
<tr class="row-colored">
  <td><b>Category Name<br>(N pts)</b></td>
  <td><ul><li>Checklist item.
      <span class="ideal-ans">Target: "model answer text"</span></li></ul></td>
</tr>
```

Both end with a `<div class="full-response-box">` containing `$sample_narrative`.

## CSS CLASSES (copy from template, do not modify)

| Class | Role |
|-------|------|
| `.rubric-container` | Outer wrapper for collapsible details |
| `.rubric-table` | Rounded-corner table |
| `.row-colored` | Alternating `#fff9ea` row tint |
| `.ideal-ans` | Green left-border block for answer targets |
| `.full-response-box` | Green bordered model response area |

## NAMING

Files follow `q{N}-{kebab-slug}.php` where slug matches `title` in the manifest.

## RANDOMIZATION RULE

**Never hardcode numerical datasets or parameters.** Use MOM randomizers:
- Scalar values (means, SDs, scores, prices): `rand()`, `rrand()`, `randfrom()`
- Raw datasets: use `$contexts` with 3 distinct pre-constructed datasets as separate context variants
- Computed answer values must be derived from the randomized inputs, not hardcoded

## ANTI-PATTERNS

- Never use `$displayformat[0]='editor'` — always `'editornopaste'`
- Never put model answers in `$rubricbutton` — only checkboxes
- Never hardcode scenario text directly — use `$contexts` array
- Never hardcode numerical values that appear in the question — randomize them
- The `$css_block` JS handles animated `<details>` toggle — do not simplify it
- Last row `<td>` elements need `.col-cat-bot` / `.col-check-bot` classes for border-radius

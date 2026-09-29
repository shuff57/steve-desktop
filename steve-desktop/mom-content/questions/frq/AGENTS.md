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

The student and instructor rubrics carry the SAME categories and the SAME point values. A student who
reads the checklist must be able to see exactly how the answer will be marked. If the two disagree,
that is a defect: fix the student rubric to match the instructor rubric, never the reverse.

```html
<!-- Student rubric ($rubricbutton): points shown, plain-language items -->
<!-- Student rubric ($rubricbutton): points shown, plain-language items -->
<tr class="row-colored">
  <td style="text-align:center;"><b>Category Name<br>(N pts)</b></td>
  <td><ul><li><label><input type="checkbox"> What to include, in plain language.</label></li></ul></td>
</tr>

<!-- Instructor rubric ($rubricanswerbutton): -->
<tr class="row-colored">
  <td><b>Category Name<br>(N pts)</b></td>
  <td><ul><li>Checklist item.
      <span class="ideal-ans">Target: "model answer text"</span></li></ul></td>
</tr>
```

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

## STUDENT-FACING LANGUAGE

Added 2026-09-29 after a student scored 0/14 with three retries on the ch2 group FRQ. The old checklist
said "ensure your explanation covers these points", hid the point values, and in places asked for a word
the student could not be expected to produce. A student who reasons correctly must be able to earn the
points. These rules apply to `$rubricbutton` and to the prompt in `$questiontext`.

- **Show the points.** Every category carries `(N pts)` in the student rubric, matching the instructor
  rubric exactly. No point values means no way to budget effort on a 14-point essay.
- **Never require a specific word.** If an item can only be earned by writing a particular term, rewrite it as the
  underlying observation. "Say which measure is more resistant" is a trap; "say which of the two moves less when the
  extreme value changes" is not.
- **Write the items so the reasoning, not a keyword, earns the point.** A disclaimer in the header does
  not help a student who never learned the word; an item phrased as the underlying observation does. Ask
  which measure moves less when an extreme value changes, rather than which measure is more resistant.
  vocabulary trap from costing a student every point.
- **Name the thing you refer to.** "Which display suits the given goal" is unanswerable from the checklist if the
  goal is never stated. Restate it.
- **Use the words the course teaches, and no others.** "More reliable" is not a statistics term; a student who
  writes "more consistent" cannot tell whether that earned credit. Prefer the taught term, or a plain gloss.
- **Never use the false-contrast constructions** `not just`, `not only`, `only that`. This repo's own lint flags them,
  and they imply a penalty without saying what earns the point. Say what earns it instead.
- **Write what earns the point, not what to avoid.** "Finish with one sentence naming the variable" beats "do not
  just use numbers".
- **Where a criterion allows a choice of method, say so.** "(standard deviation or IQR, as long as you say which)"
  stops students guessing which one the grader wants.

## ANTI-PATTERNS

- Never use `$displayformat[0]='editor'` — always `'editornopaste'`
- Never put model answers in `$rubricbutton` — only checkboxes
- Never hide the point values from the student rubric — see STUDENT-FACING LANGUAGE
- Never grade on vocabulary the student was not given — see STUDENT-FACING LANGUAGE
- Never hardcode scenario text directly — use `$contexts` array
- Never hardcode numerical values that appear in the question — randomize them
- The `$css_block` JS handles animated `<details>` toggle — do not simplify it
- Last row `<td>` elements need `.col-cat-bot` / `.col-check-bot` classes for border-radius
- **Render the question after writing it.** `moddataset.php` stores three fields (control / qtext /
  solution) and saving proves only what was stored, not that the PHP parses. Open
  `testquestion2.php?cid=<cid>&qsetid=<qid>` and read the page. A stray quote inside a single-quoted
  string turns the rest of an English sentence into code, and the only symptom is a warning list.
- Never put model answers in `$rubricbutton` — only checkboxes
- Never hardcode scenario text directly — use `$contexts` array
- Never hardcode numerical values that appear in the question — randomize them
- The `$css_block` JS handles animated `<details>` toggle — do not simplify it
- Last row `<td>` elements need `.col-cat-bot` / `.col-check-bot` classes for border-radius

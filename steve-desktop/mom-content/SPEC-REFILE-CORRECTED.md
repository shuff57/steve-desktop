# SPEC — re-file corrected question sources into their EXISTING library ids

49 question sources were repaired today (two rendering defect classes, both confirmed visible to
students). MOM still holds the **old, broken** versions of the ones already filed. This task pushes
the corrected code back.

**This is an edit of existing library questions. Nothing is created, nothing is attached, no points
change, no manifest changes.**

Drive Playwright over CDP `http://127.0.0.1:9223` using
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/_push/mom.mjs`.
Not browser-harness. **Do not kill the Chrome process** — the MOM session lives in memory.

**If any path or id below does not resolve, STOP and say so rather than guessing.**

## What to re-file — 15 questions across 3 assessments

Machine-readable list: `mom-content/_push/refile.json`.
Source root: `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/`

**4.3 Binomial Distribution — aid 23438245** (live since 2026-08-14)

```
1877080   questions/probability/binomial/q7-binomial-translate.php
```

**4.5 Continuous Probability Functions — aid 23444255**

```
1878127  q2-probability-is-area.php        1878133  q7-uniform-quartiles.php
1878128  q3-single-value-zero.php          1878134  q8-sanity-checks.php
1878129  q4-complement-tail.php            1878135  q9-height-vs-probability.php
1878131  q5-cdf-left-area.php              1878136  pre-frq-grade-a-uniform-reasoning.php
1878132  q6-uniform-mean-sd.php
```
(all under `questions/probability/uniform/`)

**5.2 Using the Normal Distribution — aid 23444258**

```
1878584  q14-at-least-percentile.php       1878587  q17-interpret-the-percentile.php
1878585  q15-quartiles-and-iqr.php         1878588  q18-percentile-vs-probability.php
1878586  q16-middle-percent.php
```
(all under `questions/normal-distribution/`)

### Already done — verify only, do NOT re-file

`1878498` (`q10-empirical-rule-bands.php`, 5.1 slot 7) was re-filed earlier today and verified
showing six answer boxes. Confirm it still reads six; do not re-save it.

## How

For each qsetid: open `moddataset.php?id=<qsetid>&cid=334437`, replace `control`, `qtext` and
`solution` with the corresponding sections of the corrected source, save, read back.

- **The five `// === ... ===` marker lines are DELIMITERS, not content.** Strip the marker line
  (and any blank line directly after it) from each section before filing. A previous push filed
  them and `// === QUESTION TEXT ===` rendered as visible text on all ten questions of 5.2.
- `control`, `qtext`, `solution` are **CodeMirror**. Use `cm.CodeMirror.setValue()`; setting
  `textarea.value` saves EMPTY.
- Set all three values, then click the **single visible `<button type="button">Save</button>`**.
  Clicking Save repeatedly races and the solution field intermittently fails to commit — that was
  measured today. The only `input[type=submit]` is the invisible `justupdatelibs`, which discards
  everything and redirects exactly like success.
- **Do not touch `qtype`.** All are already correct `multipart`.
- Do not touch points, attachment order, manifests, or index files.

## Verify

Per question:

1. Read back `control`/`qtext`/`solution` and compare to the corrected source sections, marker
   stripped. Normalise only CRLF→LF, em dash→`--`, ellipsis→`...`.
2. Stored `qtext` must not begin with `//`.

Per assessment, render Teacher Preview and assert **zero** of each:

3. `=== QUESTION TEXT ===`, `=== COMMON CONTROL ===`, `=== ANSWER ===`
4. **The concat leak.** Search the rendered page text for `' . ` and ` . '`. This is the defect being
   fixed and the whole point of the task — 4.3 currently renders
   `"a. ' . the probability that at least 4 of the 12 students attend . '"`.
   Note the leaked text can be long, so do not bound your search pattern to a few characters; that
   is how it was missed once already.
5. `Eeek!` count is 0.

Then confirm each assessment still totals 100 points and still grades.

## Report

Per question: qsetid, byte-match y/n, qtext-starts-with-`//` y/n. Per assessment: marker count,
concat-leak count, Eeek count, points total, grades y/n. State any check you could not perform —
if you have no image input, say so; the visual pass is routed elsewhere.

`mom-content/questions/` is CLAIMED by claude for this task — you are READING those sources, not
writing them. If a source still looks wrong, report it rather than editing it.

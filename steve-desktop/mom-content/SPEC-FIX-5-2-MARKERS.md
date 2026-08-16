# SPEC — repair 5.2: the section markers were filed into the question fields

**Gate result on 5.2 (aid 23444258): FAILED.** The push is otherwise good — 10 filed, attached in
slot order, points exactly 100, qtype `multipart` on all 10, no duplicates, manifest and library
written. One defect, and it is student-visible on every question.

## The defect

Each field was saved **including its own marker comment line**:

```
stored control  starts:  // === COMMON CONTROL ===      <- should start: loadlibrary("stats");
stored qtext    starts:  // === QUESTION TEXT ===       <- should start: <div style="...
stored solution starts:  // === ANSWER ===              <- should start: $solutionguide
```

`qtext` is **HTML, not code**. `//` is not a comment there, so that line renders as literal visible
text at the top of every question. Measured on the live Teacher Preview 2026-08-16:

```
aid 23444258 (5.2)   "// === QUESTION TEXT ===" visible on 10 of 10 questions
aid 23444257 (5.1)   0 occurrences        <- 5.1 was filed correctly; match it
```

**Why your own check passed it:** the read-back was compared against a baseline that also included
the marker, so the comparison agreed with itself. The correct baseline is the section CONTENT ONLY,
with the `// === ... ===` line and any blank line after it removed.

The five markers are delimiters that map a source section onto a form field. They are never part of
the value. See "The five markers map onto five fields" in
`~/.claude/skills/mom-transfer/SKILL.md`.

## The fix

For each of the ten qsetids below, re-open `moddataset.php?id=<qsetid>&cid=334437`, re-fill
`control`, `qtext` and `solution` with the marker line stripped, and save.

```
1878580  q2-normal-probability.php               1878585  q15-quartiles-and-iqr.php
1878581  q5-normal-probability-context.php       1878586  q16-middle-percent.php
1878582  q3-inverse-normal-percentile.php        1878587  q17-interpret-the-percentile.php
1878583  q13-complement-tail.php                 1878588  q18-percentile-vs-probability.php
1878584  q14-at-least-percentile.php             1878589  pre-frq-grade-a-percentile-interpretation.php
```

All under `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/questions/normal-distribution/`.

**Re-file into these SAME library ids.** Do not create new questions and do not re-attach — the
assessment already points at them, points are already correct at 100, and the manifest already
records them. This is an edit of ten existing library questions, nothing more.

Reminders that bite here specifically:

- `control`, `qtext`, `solution` are **CodeMirror**. Write with `cm.CodeMirror.setValue()`; setting
  `textarea.value` saves EMPTY.
- The real Save is the visible `<button type="button">Save</button>`. The only
  `input[type=submit]` is the invisible `justupdatelibs`, which discards your edit and redirects
  exactly like success.
- Do not touch `qtype` — all ten are already correct `multipart`.
- Do not touch points, attachment order, or the manifest.

## Verify — with the RIGHT baseline this time

1. For each of the ten, read back `control`/`qtext`/`solution` and compare against the source
   section **content only** (marker line stripped). Normalise only: CRLF to LF, em dash to `--`,
   ellipsis to `...`. Do NOT normalise away the marker — its absence is the whole point.
2. Assert the stored `qtext` does **not** begin with `//`.
3. Re-render Teacher Preview for aid 23444258 and assert the page text contains
   **zero** occurrences of `=== QUESTION TEXT ===`, `=== COMMON CONTROL ===` or `=== ANSWER ===`.
   That is the check that would have caught this; 5.1 returns 0 and is the reference.
4. Confirm every question still grades: header Score 102/100, Answered 10/10.

## Also — one question in 5.1 needs re-filing

Separate defect, found by visual review, already fixed in the repo source:

**`questions/normal-distribution/q10-empirical-rule-bands.php` -> qsetid `1878498`** (5.1 slot 7).
`$anstypes` declared 3 entries while the question sets `$answer[0..5]` and references six
answerboxes, so part (b) rendered without its upper box and part (c) rendered with no boxes at all.
It still scored full marks because ungraded parts cannot lose points. The source now declares six
`numfunc` entries.

Re-file the corrected source into qsetid **`1878498`** — same library id, no new question, no
re-attach. Then re-render 5.1 (aid 23444257) and confirm **six** answer boxes across parts a/b/c,
each with its own label and no orphaned "(Enter the upper value.)" text.

Note `mom-content/questions/` is claimed by claude; you are READING those sources, not writing
them. Report if a source looks wrong rather than editing it.

## Report

Per question: qsetid, marker-stripped y/n, qtext starts-with-`//` y/n, byte-match y/n, still-grades
y/n. Plus the rendered-page marker count for aid 23444258 (must be 0) and the answer-box count for
5.1 slot 7 (must be 6). State any check you could not perform.

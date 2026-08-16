# SPEC — repair QUESTION TEXT rendering defects, ONE BATCH

**Source repair only. Do not touch MyOpenMath. No browser. No pushing.**

Work **only** the files listed in the batch file named in the launch note (e.g.
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/_push/batch1.txt`).
Each line is a repo-relative path plus its hit counts. Ignore every other file — later batches
cover them.

Paths in the batch file are relative to:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/`

**If the batch file does not exist, STOP and say so rather than guessing.**

## Defect A — PHP concatenation leaking into QUESTION TEXT

QUESTION TEXT is **not** a PHP string; it interpolates `$var` directly. A `' . $var . '`
concatenation substitutes the variable but leaves the quote-dot punctuation **visible**.

Confirmed live 2026-08-16:

```
5.2   "Find the value k such that ' . 60 . '% of the values are at least k."
4.3   "a. ' . the probability that at least 4 of the 12 students attend . '"
```

**The identical syntax inside `$solutionguide` is CORRECT** — that is a real PHP string. Only
QUESTION TEXT is wrong. Do not touch concatenation anywhere else.

| Shape | Repair |
|---|---|
| `' . $pPct . '` | `$pPct` |
| `' . $phrases[0] . '` | `$phrases[0]` |
| `' . $scenarios[$i] . '` | precompute `$scenario = $scenarios[$i]`, use `$scenario` |
| `' . ($b - $a) . '` | precompute `$width = $b - $a`, use `$width` |
| `' . round($cl * 100) . '` | precompute `$clPct = round($cl * 100)`, use `$clPct` |
| `' . joinarray($data, ", ") . '` | precompute `$dataList = joinarray($data, ", ")`, use `$dataList` |

### Where a precomputed variable goes — this is where the last run broke

**Define it AFTER everything it reads from.** The previous attempt inserted
`$scenario = $scenarios[$i]` two lines ABOVE the `$scenarios = array(...)` definition. Nothing
errors; `$scenario` is simply empty and the question renders with a blank where the scenario
should be.

Put each new variable immediately after the last thing it depends on, in the file's existing
style. Never reuse a name already in the file — grep before choosing one.

`node mom-content/_push/usecheck.mjs` catches this class of mistake. Run it.

## Defect B — function names inside backticks

Backticks are ASCIIMath delimiters, so a function NAME inside them is typeset as symbols:

```
`invNorm`    renders as   ∈ vN or m
`normalcdf`  renders as   ‖a‖ lcdf
```

Repair: drop the backticks, leave the name as plain text. Applies in QUESTION TEXT **and**
`$solutionguide`, since both render.

Where the name sits inside a real expression, unwrap only the name and keep the math:

```
BEFORE   `invNorm(' . round($leftArea, 3) . ', 0, 1)`
AFTER    invNorm(`$leftAreaRounded`, 0, 1)
```

## Hard constraints

- Edit only the `.php` files named in your batch, all under `mom-content/questions/`.
- **Change no number, answer, tolerance, randomisation, or wording of what is asked.** This is a
  rendering repair. If a fix would alter what the student is asked or what counts as correct,
  SKIP that file and report it.
- Preserve formatting and the five `// === ... ===` marker lines exactly.
- Do not touch `$answer`, `$anstypes`, `$abstolerance`, manifests, or index files.

## Verify before reporting — all three must be run

```bash
cd C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop
node mom-content/_push/qtext-audit.mjs      # your batch's files must no longer appear
node mom-content/_push/anstypes-audit.mjs   # must stay 0 mismatches
node mom-content/_push/usecheck.mjs         # must report no use-before-definition
```

`qtext-audit` reports the whole book, so other batches will still show hits — that is expected.
What matters is that **none of your batch's files appear in its output**.

## Report

- Files changed, hits repaired per class, and every new variable you added with the line it was
  placed after.
- The three audit outputs, verbatim.
- Any file skipped, and why.
- Which checks you could not perform.

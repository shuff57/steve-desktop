# SPEC — repair two source defect classes across the intro-stats-sh question bank

**Source repair only. Do NOT push, file, attach or touch MyOpenMath in this task.** Re-filing the
corrected questions is a separate gated pass. No browser needed at all.

Both defects were found by visual review of rendered assignments, confirmed live, and are invisible
to every automated check we run: the byte-exact read-back passes (the filed code matches a source
that is itself wrong), the qtype audit passes, and the assignment still scores 102/100 because
nothing about them affects grading.

## Defect A — PHP concatenation leaking into QUESTION TEXT

**65 hits across 45 files.**

QUESTION TEXT is **not** a PHP string. It interpolates `$var` directly. A `' . $var . '`
concatenation renders with the variable substituted but the **quote-dot punctuation left literal**.

Confirmed live 2026-08-16:

```
5.2 aid 23444258   "Find the value k such that ' . 60 . '% of the values are at least k."
4.3 aid 23438245   "a. ' . the probability that at least 4 of the 12 students attend . '"
```

4.3 has been live since 2026-08-14 and passed a "102/100, Answered 12/12" sign-off carrying this.

**The same syntax inside `$solutionguide` is CORRECT** — that really is a PHP string being built by
concatenation. Only QUESTION TEXT is affected. Do not touch concatenation anywhere else.

### How to repair, by shape

Run `node mom-content/_push/qtext-audit.mjs` from `steve-desktop/` for the current list.

| Shape | Repair |
|---|---|
| `' . $pPct . '` | `$pPct` — direct interpolation |
| `' . $phrases[0] . '` | `$phrases[0]` — constant index interpolates |
| `' . $scenarios[$i] . '` | **verify before trusting.** If a variable index does not interpolate, precompute: `$scenario = $scenarios[$i]` in COMMON CONTROL, then use `$scenario` |
| `' . ($b - $a) . '` | **cannot be interpolated.** Add `$width = $b - $a` to COMMON CONTROL and use `$width` |
| `' . round($cl * 100) . '` | add `$clPct = round($cl * 100)` to COMMON CONTROL, use `$clPct` |
| `' . joinarray($data, ", ") . '` | add `$dataList = joinarray($data, ", ")` to COMMON CONTROL, use `$dataList` |

Roughly 36 of the 65 are the first two shapes and are a safe substitution. The remaining ~29 need a
new variable in COMMON CONTROL — `round($cl * 100)` accounts for about 11 of them and `($b - $a)`
for 5, so a handful of definitions covers most.

**Name new variables in the file's existing style** and define them next to the values they derive
from, not at the top. Never redefine a name the file already uses — grep first.

## Defect B — function names inside backticks

**19 hits across 10 files.**

Backticks are ASCIIMath delimiters. A function NAME inside them is typeset as symbols:

```
`invNorm`     renders as   ∈ vN or m
`normalcdf`   renders as   ‖a‖ lcdf
```

Repair: **drop the backticks**, leaving the name as plain text — `invNorm`, `normalcdf`.

Applies in QUESTION TEXT and in `$solutionguide` alike, since both render.

**Careful:** where the name is part of a real expression, keep the math and unwrap only the name.

```
BEFORE   `invNorm(' . round($leftArea, 3) . ', 0, 1)`
AFTER    invNorm(`$leftAreaRounded`, 0, 1)      <- name plain, numbers still math
```

That example also carries Defect A, so it needs both repairs and a precomputed variable.

## Constraints

- Edit **only** files under `mom-content/questions/`. Do not touch manifests, indexes or `_push/`.
- **Do not change any question's meaning, numbers, randomisation, answers or tolerances.** This is a
  rendering repair. If a fix would change what the student is asked or what counts as correct, STOP
  and report that file instead.
- Preserve each file's existing formatting and the five `// === ... ===` marker lines exactly.
- Do not "fix" `$answer`, `$anstypes`, or `$abstolerance`.

## Verify — all of this is scriptable, run it yourself before reporting

```bash
cd steve-desktop
node mom-content/_push/qtext-audit.mjs      # both classes must report 0 hits
node mom-content/_push/anstypes-audit.mjs   # must stay 0 mismatches
```

Then, for a spot check that you have not broken interpolation, pick **three** repaired files and
show me, for each: the before line, the after line, and any COMMON CONTROL line you added.

A file that still has hits is not done. A file where you changed a number is worse than one you
skipped — report skips, do not guess.

## Report

- Count of files changed, hits repaired per class, and new COMMON CONTROL variables added.
- The three spot-check diffs.
- Any file you deliberately skipped and why.
- Both audit outputs, verbatim.
- Which checks you could not perform. If you have no image input, say so — the visual pass is
  routed elsewhere.

Note: nothing is claimed right now, so you have write access to `mom-content/questions/`. Do not
push anything; re-filing the corrected questions into MOM is a separate task that comes after this
one is gated.

# SPEC — re-save seven already-filed 3.5 questions after a source fix

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

Read first, both in full:
- `C:/Users/shuff/.claude/skills/mom-transfer/SKILL.md`
- `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/reference/transfer-rules.md`

**Do not touch the message center.** Do not read or write `.msgbox`, do not run `msg.mjs`. That log
is large and reading it will eat your context. Report to stdout at the end.

## What this is

3.5 is already pushed and live: cid **334437**, aid **23312138**, ten questions, 102/100. Nothing
about the assessment needs to change — **do not create anything, do not re-file anything, do not
touch points, order, or the book link.**

A visual review found four defects that the grading could not see, and the source `.php` files have
already been fixed and committed. Seven filed questions still hold the OLD version. Your job is to
re-save each one's edited field from source, then prove the fix rendered.

**Every change is inside the `=== COMMON CONTROL ===` section.** No `qtext`, `solution`, `qtype`, or
description changed. So for each question: update the CONTROL field only, leave every other field
alone.

## The seven

| qid | source file (all under `mom-content/questions/probability/`) | what changed |
|---|---|---|
| 1874866 | `q35-read-a-given-tree.php` | SVG width 470 → 640; new `$negNames`; contexts capitalised |
| 1874867 | `q36-conditional-from-a-venn.php` | `$contexts` rewritten to "sorted by whether" |
| 1874868 | `q32-tree-two-draws-without-replacement.php` | contexts capitalised |
| 1874869 | `q33-with-versus-without-replacement.php` | new `$containers`/`$container`; contexts capitalised |
| 1874870 | `q37-three-draws-without-replacement.php` | contexts capitalised |
| 1874872 | `q39-at-least-one-two-ways.php` | contexts capitalised |
| 1874873 | `pre-frq-grade-a-diagram-setup.php` | contexts capitalised |

## The CodeMirror trap

The control field is a CodeMirror editor. Setting `textarea.value` **saves empty** — it has done so
before and the save still reports success. Write through the CodeMirror instance, then read the
field back before clicking Save, and read it back again after. A byte-exact read-back against the
source file is the only proof.

Normalise em dash to `--` and compare stripped (MOM trims a leading newline).

## Prove each fix rendered

Not "the field saved" — that is not the same thing. For each, open the question and check what the
page shows:

- **1874866 (q35)** — the one that matters. Cycle **New Version** until you have seen **all three**
  contexts. On each: the `<svg>` must be **640** wide, and **all four leaf labels must be fully
  visible inside it** — no text crossing or exceeding the right edge. The two bottom leaves must
  read as **different** strings. On the "screen positive" context the negative leaves must say
  **"screen negative"**, not "test negative". Report the four leaf strings verbatim for each context.
- **1874867 (q36)** — the stem must read like `100 customers, sorted by whether they ordered a
  drink and whether they ordered dessert.` Cycle all three contexts; none may read as though the
  whole count did both things.
- **1874869 (q33)** — cycle all three contexts. Part (d)'s options must name the SAME container as
  the stem: bag with marbles, drawer with socks, box with pens. The old text said "bag" always.
  Report the container word in the stem and in option 1 for each of the three.
- **all seven** — the stem must begin with a **capital letter**. Cycle all three contexts on each.

## Regrade

The keys did not change, so this should still be perfect — which is exactly why it is worth
confirming you did not break one. Open Teacher Preview for aid 23312138, answer and submit every
part of all ten questions, polling `.scoreresult` before the next click (the submits are AJAX and
race). Work every answer out from the RENDERED page.

**Done is `102/100`, Answered `10/10`.**

## Screenshots

One per repaired question, **FULL PAGE, ONE SHOT**: `Emulation.setDeviceMetricsOverride` to
`scrollHeight` + margin, a single `Page.captureScreenshot`, then clear the override. **Do not scroll
and stitch.** Confirm each image's landmarks appear once.

Save to `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/scratchpad/`:
`3-5-fix-1874866.png`, `3-5-fix-1874867.png`, `3-5-fix-1874868.png`, `3-5-fix-1874869.png`,
`3-5-fix-1874870.png`, `3-5-fix-1874872.png`, `3-5-fix-1874873.png`

For **1874866**, capture one image **per context** instead of one:
`3-5-fix-1874866-ctx1.png`, `-ctx2.png`, `-ctx3.png` — the clipping was context-dependent.

## Report to stdout

Per qid: byte-exact read-back pass/fail, the stem's first six words, and the specific render check
above. Plus the final header score, and **which checks you could not perform.**

The visual pass/fail is NOT yours — capture the images and leave the judgement to me.

Do not edit any `.php`. Do not edit the manifest. Do not re-run `sync-index`.

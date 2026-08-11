# SPEC — push 3.5 Tree and Venn Diagrams into master course 334437

Read first, both in full:
- `C:/Users/shuff/.claude/skills/mom-transfer/SKILL.md`
- `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/reference/transfer-rules.md`

**Do not touch the message center.** Do not read or write `.msgbox`, do not run `msg.mjs`. That log
is ~119KB and reading it will eat your context. Report to stdout at the end.

## The assignment

Manifest: `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/introduction-to-stats-sh/hw/3-5-tree-and-venn-diagrams.json`

Ten questions, points already summing to exactly 100 — **do not rebalance**. None has ever been
filed; verify that against `reference/question-library.json` before filing, because a double-filed
question is the one failure that does not undo cleanly.

**All ten are newly authored and none has ever rendered anywhere.** Six carry inline SVG (Venn
diagrams and probability trees), which is new for this bank — MyOpenMath has no Venn or tree
primitive, so these are hand-built `<svg>` strings. Expect the SVG to be where trouble is, if
anywhere.

## Course settings

cid **334437**, kind `hw`, so `copyfrom` = template_aid **23258795**, with
`copydates` / `copysummary` / `copyinstr` / `copyendmsg` **unchecked**. Undated: `sdatetype=0`,
`edatetype=2000000000`. `allowpractice` disappearing is expected, not a regression.

**Order:** place 3.5 AFTER 3.4 (aid 23301054) in the Chapter 3 block, via `moveitem.php`.

**Book link:** derive this section's bookSHelf URL from 3.4's pattern, confirm it loads, and say
which you used. `copyfrom` does not bring resource rows — you will click Add Resource.

**Description field:** the text BETWEEN `NAME - DESCRIPTION:` and the trailing `===`, with no leading
`//` and no `=` characters. A re-save earlier today stored the whole raw marker line, and the
byte-exact check did not catch it because it compared the bad value against the same bad split. Read
each description back and confirm it does not start with `//` and contains no `===`.

## Verify, per question

- byte-exact read-back of description / control / qtext / solution against source (normalise em dash
  to `--`; MOM also trims a leading newline, so compare stripped)
- `qtype` off `moddataset.php` against each file's `SET QUESTION TYPE TO` marker
- Teacher Preview: ANSWER AND SUBMIT every part, polling that question's `.scoreresult` before
  clicking the next — the submits are AJAX and race
- work every answer out from the RENDERED page; choices shuffle per seed
- **done is `102/100`, Answered `10/10`**

## Extra checks, factual so they are yours

- **Every SVG must actually render.** For each of the six with a diagram, confirm the page contains
  an `<svg>` element with non-zero width and height, and that it is not empty. A broken SVG string
  produces a blank gap, not an error.
- q31 / q34 / q36 / q38 (Venns): the four region values shown must sum to the stated total.
- q32 / q35 / q37 / q39 (trees): the leaf probabilities must sum to 1.
- q39 specifically: parts (a) and (b) must produce the SAME number by two routes.
- On the pre-FRQ (slot 10), cycle "New Version" through all three contexts and confirm each reads
  grammatically — no `a students`-style article/plural mismatch.

## Screenshots

One per question, **FULL PAGE, ONE SHOT**: `Emulation.setDeviceMetricsOverride` to
`scrollHeight` + margin, a single `Page.captureScreenshot`, then clear the override. **Do not scroll
and stitch** — a stitched capture came back as the same region repeated four times while still
matching `scrollHeight`. Confirm each image's landmarks appear once before reporting.

Save to:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/scratchpad/3-5-slot1.png` … `3-5-slot10.png`

## Write back

`target.aid` and all ten `qid`s into the manifest, entries into `question-library.json`, then
`bun mom-content/reference/sync-index.ts` and confirm `--check` exits clean.

## Report to stdout

A per-question table: slot, title, qsetid, points, graded correct y/n. Plus the final header score,
the Book URL used, which SVGs rendered, and **which checks you could not perform.** The visual
pass/fail is NOT yours — capture the screenshots and leave the judgement to me.

Do not edit any `.php`.

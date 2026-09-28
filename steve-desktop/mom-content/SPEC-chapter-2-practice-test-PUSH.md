# SPEC — push Chapter 2 Practice Test into master course 334437

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

Read first, both in full:
- `steve-desktop/skills/mom-transfer/SKILL.md`
- `steve-desktop/mom-content/reference/transfer-rules.md`

**Do not touch the message center.** Do not read or write `.msgbox`, do not run `msg.mjs`. Report to
stdout at the end.

## The assignment

Manifest: `steve-desktop/mom-content/books/introduction-to-stats-sh/practice/chapter-2-practice-test.json`

14 slots, points already summing to exactly 100 (12×6 + 14 + 14) — **do not rebalance.**

- **Slots 1–12 (auto-graded): already have real `qid`s** from the 2.1–2.7 homework pushes. Skip step
  2 (filing) for these — attach by `qsetid` directly (`modquestion2.php?qsetid=<qid>&cid=334437&aid=<aid>&from=addq&process=true&usedef=true`).
  Confirm each `qid` still resolves in `moddataset.php?id=<qsetid>&cid=334437` before attaching; do
  not re-file it.
- **Slots 13–14 (FRQ): `qid` is `null`, never filed anywhere.** These are the normal push (file, then
  attach). Verify both are still absent from `reference/question-library.json` before filing — a
  double-file is the one failure that does not undo cleanly.
  - Slot 13: `questions/frq/descriptive-statistics/q4-choosing-the-right-display.php`
  - Slot 14: `questions/frq/descriptive-statistics/q9-choosing-the-right-measure-of-center.php`

**Zero-overlap gate (already satisfied by the manifest, do not violate it):** none of this test's 14
`file_path`/`qid` values may also appear in `chapter-2-group-test.json` or
`chapter-2-individual-test.json`. If a script-driven check finds an overlap, STOP — the manifest is
wrong, not you.

## Course settings

cid **334437**, kind `practice`, so `copyfrom` = template_aid **23258795** (the 1.1/2.1-style
Homework template — confirmed by `reference/assessment-presets.json`: all kinds copy this one, then
adjust per kind). `copydates` / `copysummary` / `copyinstr` / `copyendmsg` stay **unchecked**.
Undated: `sdatetype=0`, `edatetype=2000000000`. `allowpractice` disappearing on save is expected, not
a regression.

Per `reference/intro-stats-assessment-settings.md`, Practice column, confirm after copy (adjust
anything the template didn't already carry):

| Setting | Value |
|---|---|
| Attempts / versions per question | 3 / 20 |
| Penalty | none |
| Early-finish bonus | 2%, 24h before due (inert while undated — do not chase it) |
| Gradebook category | HW — `798368` in this course |
| Late passes | 2 (Up to 1) |
| Time limit | none |
| Passcode | none |
| Display | All questions on one page |
| Scores / answers shown | during / after last attempt |

**Name:** `Chapter 2 Practice Test`.

**Placement:** no existing Chapter 2 assessment to sit near. Place directly after the Chapter 1
practice test (aid `23444240`) at the same level, via `moveitem.php`. If that placement looks wrong
once you're looking at the real course tree, say so and hold rather than guessing a block address.

**Book link:** the Chapter 1 practice test carries none (a chapter test spans too many sections for
one bookSHelf URL). If `copyfrom` brought over 2.1's Book resource row anyway, remove it rather than
leaving a wrong-section link — do not invent a "Chapter 2" URL if none exists in bookSHelf's routing.

## Verify, per question

- Slots 1–12: confirm `qtype` off `moddataset.php` still matches what the question was filed as —
  this is a re-attach, not a re-file, so this is a sanity check, not a fix.
- Slots 13–14: byte-exact read-back of description/control/qtext/solution against source (normalise
  em dash → `--`; MOM trims a leading newline, so compare stripped), then `qtype` vs each file's `SET
  QUESTION TYPE TO` marker.
- Teacher Preview: ANSWER AND SUBMIT every part of all 14 questions, polling `.scoreresult` before
  clicking the next one — submits are AJAX and race.
- Work every answer out from the RENDERED page, not the source — `choices` shuffle per seed.
- **Done is `100/100` (or `102/100` if the early-finish bonus fires), Answered `14/14`.**
- Two slots reuse questions already live on the 2.7 homework (`q1-z-score-compute.php` filed under
  5-1, `standard-deviation-step-by-step.php` health `unchecked`) — this is intentional reuse, not a
  defect; just confirm they still render and grade correctly here.

## Screenshots

Only for the two newly-filed FRQs (slots 13–14) — the other 12 are known-good reattaches. **FULL
PAGE, ONE SHOT**: `Emulation.setDeviceMetricsOverride` to `scrollHeight` + margin, a single
`Page.captureScreenshot`, then clear the override. Do not scroll-and-stitch.

Save to: `steve-desktop/scratchpad/chapter-2-practice-slot13.png`, `…-slot14.png`

## Write back

`target.aid` into the manifest; `qid` into slots 13–14; new entries into `question-library.json` for
the two FRQs; then `bun mom-content/reference/sync-index.ts` and confirm `--check` exits clean. Edit
the JSON as text, in place — do not round-trip it through a JSON parser/serializer.

## Report to stdout

A per-question table: slot, title, qid, points, graded correct y/n. Final header score, the
placement you used, whether the Book-link row needed removing, and **which checks you could not
perform.** The visual pass/fail on the two FRQ screenshots is not yours — capture and leave judgement
to me.

Do not edit any `.php`.

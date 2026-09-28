# SPEC — push Chapter 2 Individual Test into master course 334437

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

Read first, both in full:
- `steve-desktop/skills/mom-transfer/SKILL.md`
- `steve-desktop/mom-content/reference/transfer-rules.md`

**Do not touch the message center.** Do not read or write `.msgbox`, do not run `msg.mjs`. Report to
stdout at the end.

## The assignment

Manifest: `steve-desktop/mom-content/books/introduction-to-stats-sh/ind/chapter-2-individual-test.json`

10 slots, points already summing to exactly 100 (8×9 + 14 + 14) — **do not rebalance.**

- **Slots 1–8 (auto-graded): already have real `qid`s** from the 2.1–2.7 homework pushes. Skip step 2
  (filing) — attach by `qsetid` directly. Confirm each `qid` still resolves in
  `moddataset.php?id=<qsetid>&cid=334437` before attaching; do not re-file it.
- **Slots 9–10 (FRQ): `qid` is `null`, never filed anywhere.** File then attach. Verify both are still
  absent from `reference/question-library.json` first — a double-file does not undo cleanly.
  - Slot 9: `questions/frq/descriptive-statistics/q7-comparing-means-and-standard-deviations.php`
  - Slot 10: `questions/frq/descriptive-statistics/q11-compare-distributions-essay.php`

**Zero-overlap gate (already satisfied by the manifest, do not violate it):** none of this test's 10
`file_path`/`qid` values may also appear in `chapter-2-practice-test.json` or
`chapter-2-group-test.json`. If a check finds an overlap, STOP — the manifest is wrong, not you.

## Course settings

cid **334437**, kind `ind`, `copyfrom` = template_aid **23258795**, then adjust to the Individual Test
column below. `copydates` / `copysummary` / `copyinstr` / `copyendmsg` stay **unchecked**. Undated:
`sdatetype=0`, `edatetype=2000000000`.

Per `reference/intro-stats-assessment-settings.md`, Individual Test column:

| Setting | Value |
|---|---|
| Subtype | Test |
| Attempts / versions per question | 2 / 1 |
| Penalty | 50% per attempt, starting after attempt 1 |
| Early-finish bonus | none |
| Gradebook category | IND — `798370` in this course (matches `mom_settings.gbcategory_id_334437` already in the manifest) |
| Late passes | 0 (None) |
| Time limit | 89 min regular / 81 min Wednesday, `allowovertime` on |
| Passcode | **required** — see `~/Documents/mom-test-passcodes-2026-27.md`. Copy-time setting, set
  it LAST. Do not invent a code. |
| Display | All questions on one page |
| Scores / answers shown | during / after last attempt |

**Name:** `Chapter 2 Individual Test`.

**CORRECTED (learned from the practice-test push, 2026-09-27): a stub assessment ALREADY EXISTS.**
Aid **23444246** is already created, already named "Chapter 2 Individual Test", already sitting in
the correct outline position in block `0-2-8-2` (Chapter 2's block, alongside the other two Chapter 2
stubs — NOT block `0-2-8` as the manifest's `global_block` guessed; confirm the real block off the
live tree and record it). **Do NOT create a new assessment via `copyfrom`/`addassessment2.php` —
attach into the existing stub aid 23444246 instead.** Read its current settings off
`addassessment2.php?id=23444246&cid=334437` first; only change fields that don't already match the
Individual Test column below. If the stub is dated (the practice stub was found dated
09/21-09/23/2026, in the past), set it undated: `sdatetype=0`, `edatetype=2000000000`.

**Book link:** the practice-test push found a live convention on the Chapter 2 stubs: repoint any
stale section-specific Book link to `https://oerbookshelf.app/introduction-to-stats/` rather than
removing the row (transfer-rules.md: emptying a resource row DELETES it and shifts remaining rows
up — destructive, not neutral). Check what's already on the stub before touching it.

## Verify, per question

- Slots 1–8: sanity-check `qtype` off `moddataset.php` still matches (re-attach, not re-file).
- Slots 9–10: byte-exact read-back (em dash → `--`, strip leading newline), `qtype` vs source marker.
- Teacher Preview: ANSWER AND SUBMIT every part of all 10, polling `.scoreresult` before the next —
  AJAX races.
- Work every answer from the RENDERED page — `choices` shuffle per seed.
- **Done is `100/100`, Answered `10/10`.**
- Six of the eight auto slots (3, 4, 5, 6, 7, 8) carry health `unchecked` in
  `reference/question-index.json` — never replayed by the regress baseline. Render and answer them
  same as everything else; flag in the report if any behaves oddly.

## Screenshots

Only for the two newly-filed FRQs (slots 9–10). **FULL PAGE, ONE SHOT**:
`Emulation.setDeviceMetricsOverride` to `scrollHeight` + margin, single `Page.captureScreenshot`, then
clear the override. No scroll-and-stitch.

Save to: `steve-desktop/scratchpad/chapter-2-ind-slot9.png`, `…-slot10.png`

## Write back

`target.aid` into the manifest; `qid` into slots 9–10; new `question-library.json` entries for the two
FRQs; then `bun mom-content/reference/sync-index.ts` and confirm `--check` exits clean. Edit the JSON
as text, in place.

## Report to stdout

Per-question table: slot, title, qid, points, graded correct y/n. Final header score, the block you
confirmed/used, the passcode source you read from (never the code itself), and **which checks you
could not perform.** Visual pass/fail on the FRQ screenshots is not yours to call.

Do not edit any `.php`.

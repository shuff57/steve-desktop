# SPEC — push Chapter 2 Group Test into master course 334437

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

Read first, both in full:
- `steve-desktop/skills/mom-transfer/SKILL.md`
- `steve-desktop/mom-content/reference/transfer-rules.md`

**Do not touch the message center.** Do not read or write `.msgbox`, do not run `msg.mjs`. Report to
stdout at the end.

## The assignment

Manifest: `steve-desktop/mom-content/books/introduction-to-stats-sh/group/chapter-2-group-test.json`

14 slots, points already summing to exactly 100 (12×6 + 14 + 14) — **do not rebalance.**

- **Slots 1–12 (auto-graded): already have real `qid`s** from the 2.1–2.7 homework pushes. Skip step
  2 (filing) — attach by `qsetid` directly. Confirm each `qid` still resolves in
  `moddataset.php?id=<qsetid>&cid=334437` before attaching; do not re-file it.
- **Slots 13–14 (FRQ): `qid` is `null`, never filed anywhere.** File then attach. Verify both are
  still absent from `reference/question-library.json` first — a double-file does not undo cleanly.
  - Slot 13: `questions/frq/descriptive-statistics/q5-interpreting-bimodal-data.php`
  - Slot 14: `questions/frq/descriptive-statistics/q8-five-number-summary-and-outliers.php`

**Zero-overlap gate (already satisfied by the manifest, do not violate it):** none of this test's 14
`file_path`/`qid` values may also appear in `chapter-2-practice-test.json` or
`chapter-2-individual-test.json`. If a check finds an overlap, STOP — the manifest is wrong, not you.

## Course settings

cid **334437**, kind `group`, `copyfrom` = template_aid **23258795**, then adjust to the Group Test
column below. `copydates` / `copysummary` / `copyinstr` / `copyendmsg` stay **unchecked**. Undated:
`sdatetype=0`, `edatetype=2000000000`.

Per `reference/intro-stats-assessment-settings.md`, Group Test column:

| Setting | Value |
|---|---|
| Subtype | Test |
| Attempts / versions per question | 3 / 1 |
| Penalty | none |
| Early-finish bonus | none |
| Gradebook category | GROUP — `798369` in this course |
| Late passes | 0 (None) |
| Time limit | 89 min regular / 81 min Wednesday, `allowovertime` on |
| Passcode | **required** — see `~/Documents/mom-test-passcodes-2026-27.md`. This is a copy-time
  setting like dates; set it LAST, since a later settings save can clear it. Do not invent a code. |
| Display | All questions on one page |
| Scores / answers shown | during / after last attempt |

**Name:** `Chapter 2 Group Test`.

**CORRECTED (learned from the practice-test push, 2026-09-27): a stub assessment ALREADY EXISTS.**
Aid **23444245** is already created, already named "Chapter 2 Group Test", already sitting in the
correct outline position in block `0-2-8-2` (Chapter 2's block), after the Chapter 2 Individual Test
stub or alongside it — check the live course tree to confirm exact order among the three Chapter 2
stubs. **Do NOT create a new assessment via `copyfrom`/`addassessment2.php` — attach into the
existing stub aid 23444245 instead.** Read its current settings off `addassessment2.php?id=23444245&cid=334437`
first; only change fields that don't already match the Group Test column below. If the stub is
dated (the practice stub was found dated 09/21-09/23/2026, in the past), set it undated:
`sdatetype=0`, `edatetype=2000000000`, per the master-course rule.

**Book link:** the practice-test push found the Chapter 1 precedent's claim ("no Book link") to be
false, and found a live convention on the Chapter 2 Group Test's sibling stubs: repoint any stale
section-specific Book link to `https://oerbookshelf.app/introduction-to-stats/` rather than removing
the row (transfer-rules.md: emptying a resource row DELETES it and shifts remaining rows up —
destructive, not neutral). Check what's already on the stub before touching it.

## Verify, per question

- Slots 1–12: sanity-check `qtype` off `moddataset.php` still matches (re-attach, not re-file).
- Slots 13–14: byte-exact read-back (em dash → `--`, strip leading newline), `qtype` vs source
  marker.
- Teacher Preview: ANSWER AND SUBMIT every part of all 14, polling `.scoreresult` before the next —
  AJAX races.
- Work every answer from the RENDERED page — `choices` shuffle per seed.
- **Done is `100/100`, Answered `14/14`.**
- Four slots (7, 8, 10, 11) carry health `unchecked` in `reference/question-index.json` — the regress
  baseline has never replayed them. Render and answer them same as everything else; flag in the
  report if any of the four behaves oddly so the baseline can be updated afterward.
- Slot 12 reuses `normal-distribution/q4-empirical-rule.php`, filed under 5-1 but already live on the
  2.7 homework — intentional reuse, confirm it renders/grades correctly here.

## Screenshots

Only for the two newly-filed FRQs (slots 13–14). **FULL PAGE, ONE SHOT**:
`Emulation.setDeviceMetricsOverride` to `scrollHeight` + margin, single `Page.captureScreenshot`, then
clear the override. No scroll-and-stitch.

Save to: `steve-desktop/scratchpad/chapter-2-group-slot13.png`, `…-slot14.png`

## Write back

`target.aid` into the manifest; `qid` into slots 13–14; new `question-library.json` entries for the
two FRQs; then `bun mom-content/reference/sync-index.ts` and confirm `--check` exits clean. Edit the
JSON as text, in place.

## Report to stdout

Per-question table: slot, title, qid, points, graded correct y/n. Final header score, the placement
used, the passcode source you read from (never the code itself), and **which checks you could not
perform.** Visual pass/fail on the FRQ screenshots is not yours to call.

Do not edit any `.php`.

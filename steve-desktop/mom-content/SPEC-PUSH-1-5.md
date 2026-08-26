# SPEC — push 1.5 Data Collection Experiment (lab) into master course 334437

Read first, both in full:
- `C:/Users/shuff/.claude/skills/mom-transfer/SKILL.md`
- `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/reference/transfer-rules.md`

Then the two precedents, in the order you will follow them:
- `SPEC-3-5-PUSH.md` — full-push pattern (create/find assessment, file, attach, points, Teacher
  Preview verify, screenshots).
- `SPEC-PUSH-53.md` — the section for the file/attach/points mechanics that the 5.3 session
  survived on, and its per-question phase discipline.

The message center: check your inbox ONCE at session start, then do not read or write `.msgbox`
or `log.jsonl` again — that log is large and this push is long. Reply to stdout at the end
(incrementally, see REPORT INCREMENTALLY below).

## The assignment

Manifest:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/introduction-to-stats-sh/lab/1-5-data-collection-experiment.json`

Sixteen questions, points **15 x 6 + 10 = 100 — do not rebalance**. The manifest's `target.aid` and
all sixteen `qid`s are null — this is the push that fills them. **None has ever been filed.**
Verify that first against `reference/question-library.json` (grep `data-collection`): a
double-filed question is the one failure that does not undo cleanly.

All sixteen are multipart. All carry the shared randomized 60-count generator (frequencies are
multiples of 3, every relative frequency exact 2dp, bottom cumulative exactly 1). The at-most/
more-than choice scaffolds are structurally identical across the four questions: correct index is
**0** for the cut at 2 (ungrouped only) and **2** for the cut at 3 (either table), and the correct
option must be derived from the RENDERED draw, never replayed from source.

## Browser and course state — discover first, then branch

1. The push drives the already-authenticated MOM Chrome over CDP `http://127.0.0.1:9223` via
   `_push/mom.mjs` (`connect()`, `go(page, url)`, `CID`). If the port is down: start Chrome with
   `--remote-debugging-port=9223` on the MOM-signed-in profile (or bring the browser-harness
   daemon up first). **Do not kill the Chrome process.** Never close tabs.
2. Origin assert before anything page-level:
   `if (!/myopenmath\.com/.test(location.host)) return {abort:'wrong origin'}` — a stray tab on
   another origin returns 0-row results that parse cleanly and read as "not found".
3. Find the 1.5 assessment on course 334437:
   - The course holds a full ch1–12 scaffold. Look in the Chapter 1 block for an assessment whose
     name reads like `1.5 Data Collection Experiment` (5.3's scaffold aid pre-existed EMPTY as
     `23444259` — the same may hold for 1.5).
   - **If it exists (empty or with slots): use its aid as `target.aid`. Do not touch its
     settings** — it is a `lab` and the scaffold was built with them. Check only: no passcode
     (`assmpassword` reads empty = genuinely none, read it on load — navigation, never fetch),
     intro names no dates, and its `extreflinks[]` rows before touching resources.
   - **If no 1.5 assessment exists: create one.** Copy from the existing LAB `4-4` (aid
     `23444251`) via `copyfrom` — copyfrom brings attempt settings but NOT `extreflinks[]`, and
     NOT dates: set `sdatetype=0`, `edatetype=2000000000` (undated, master-course rule), gradebook
     category `792510` (GROUP), no passcode, name `1.5 Data Collection Experiment - Lab`. Then
     write the correct name and record the new aid.
   - Read the aid back off `addassessment2.php?id=<aid>&cid=334437` — never `?aid=` (that URL is
     the blank Add form and silently creates a duplicate on save).

## Order and resources

- **Order:** 1.5 sits AFTER 1.4 in the Chapter 1 block. Re-read the authoritative `blockselect`
  from `moveitem.php` BEFORE moving (block addresses are positional and re-index after every
  move). `moveitem.php` POST needs `csrfp-token` in the body from any course page's `CSRFP.setToken`:
  `{item, block, newblock, moveafter, csrfp-token}`; answer `OK` is the success tell. The `item`
  is the COURSE-ITEM id from `moveDialog('<block>','<item>')`, never the aid.
- **Book link:** the section's bookSHelf URL is
  `bookSHelf/docs/introduction-to-stats/chapter-1-sampling-and-data/1.5_data_collection_experiment.html`
  — confirm it loads, and say which URL you used. `copyfrom` does NOT bring resource rows; the
  existing scaffold row for Book may be stale (ch-renumber dead-link bug family) — **never empty a
  row to mean "no link"**; assert the whole ordered row list, click **Add Resource** to grow it.
  Resources per settings reference: Book (this section), rāSHio, Desmos Scientific Calc.
- **Description field:** text between `NAME - DESCRIPTION:` and the trailing `===` — no leading
  `//`, no `===` characters. Read each description back and confirm.

## Audits, before any filing

Run the three `_push` audits, each written from a real defect byte-exact verification missed:
```bash
cd C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop
node mom-content/_push/qtext-audit.mjs
node mom-content/_push/anstypes-audit.mjs
node mom-content/_push/usecheck.mjs
```
Expect clean modulo the whole family being new. Also rerun the sandbox render sweep
(`node C:/Users/shuff/AppData/Local/Temp/opencode/save15.mjs` re-shoot is fine) — the family was
visually reviewed 2026-08-26 with one defect found and fixed AT SOURCE (camila-vs-rosa
parentheticals, see below) — the files on disk are the reviewed files; the filed copies must be
byte-equal to them.

**Known-issue note (2026-08-26 review):** camila-vs-rosa-settlement.php — two parentheticals in
student-visible text were eaten by MathJax on the rendered page (`(intervals 0-1, 2-3, 4-5, 6-7+)`
and `(one row per count)` rendered as TeX italic letter soup). Fixed at source by rephrasing
without parentheses; the fix is ALREADY in the file the push files. The parenthetical pattern is a
family-wide convention violation to keep an eye on: grep the 16 sources for `( ` inside
student-visible double-quoted/HTML sections before filing each one.

## Flow, per question (16 x, phases separated)

```
0. audits (above), once
1. file    GET moddataset.php?aid=<aid>&cid=334437   (no id= ⇒ new-question form)
           - strip the // === ... === marker line AND the blank line after it from each section
           - fill control / qtext / solution via the page's own editor handles:
             window.controlEditor, window.qEditor.qtext, window.qEditor.solution
           - set qtype via #qtypedd picker (a[data-sn=multipart]), THEN assert hidden
             [name=qtype] changed before saving
           - Save: the visible <button> "Save" — FOUR identical ones exist, all bound to
             quickSaveQuestion(true); any one commits the whole form over AJAX. Click ONE.
           - read the qsetid off the response page's attach links; if the URL still has no id=,
             poll for it (save navigates async) — a blind retry files a duplicate
           - verify: moddataset.php?id=<qsetid> read-back byte-exact; then
             testquestion2.php?qsetid=<qsetid> renders with ZERO `Eeek!`, ZERO marker text
             (`=== QUESTION TEXT ===` etc), ZERO concat leaks (unbounded pattern: `' . ` / ` . '`)
2. attach  GET modquestion2.php?qsetid=<qid>&cid=334437&aid=<aid>&from=addq&process=true&usedef=true
           - FIRST assert input[name="curq[]"] does not already contain that qsetid — the attach
             GET is NOT idempotent (4.4 got an 11th row that way)
3. points  via modquestion2.php?id=<instanceid>&aid=<aid>&cid=334437 (hidden `points`) —
           lab is 6 pts x 15 + 10; read back from a FRESH load: total exactly 100
```

**REPORT INCREMENTALLY** (5.3's lesson — runs get killed on budget): after question 4, after
question 8, after filing all 16, and after each verify phase — send to the agent that handed you
the task:
```
node C:/Users/shuff/.claude/bin/msg.mjs send --from claude --to opencode --re last --text "..."
```

## Verify, per question (Teacher Preview — the standing rule applies to ALL 16)

- byte-exact read-back of description / control / qtext / solution against source. Normalise ONLY
  CRLF→LF, em dash→`--`, ellipsis→`...`, `&lt;`/`&gt;`. MOM also trims a leading newline — compare
  stripped.
- qtype off `moddataset.php?id=<qsetid>` against each file's `SET QUESTION TYPE TO:` marker.
- Teacher Preview: ANSWER and SUBMIT every part, polling that question's `div.scoreresult`
  (`correct`/`incorrect`) before clicking the next — submits are AJAX and race. The seed rolls on
  every load (`testquestion2.php` especially): keep observe and act on the SAME load, instruct by
  option text, never carry an element index across loads.
- choices options shuffle per seed: match the rendered option whose TEXT carries the answer, and
  work the numbers out from the rendered page — replaying source proves nothing.
- numfunc parts: MathQuill — set the visible field AND read the hidden input back; resubmit if it
  did not sync. (Multi-part questions: the hidden-input path is the one that 2.7 measured.)
- done is **100/100, Answered 16/16** (lab: NO 2% early-finish bonus, so not 102).
- `div.scoreresult` ONLY. An unsubmitted question has no such div at all; header can lag — read
  per-question results, not the header, for "did it submit".

## Extra checks, factual

- Every table in slots 4–10, 12, 13: frequencies sum exactly 60, relative frequencies = f/60 to
  exactly 2dp, bottom cumulative exactly 1 — except slot 12 (`bottom-row-cumulative-check.php`):
  BY DESIGN its table sums to 58 (freq of the 3-row shown as 1) with bottom cum 0.9667 and the
  answers are 2 / the 3 movies row / 1.0000.
- Slot 1: the 12 positions = start + 4k mod N with one wrap, no repeats when gcd(N,4)=1.
- Slots 2, 16: the 24-name case; gcd(24,4)=4 → 6 distinct names.
- Slots 7–10: verify the correct `which table` index against the rendered cut (2 → 0, 3 → 2).
- Slot 16 pre-FRQ: exactly one of the four responses earns all three rubric categories (the
  `$pos` permutation is NOT C/D-identical by design — responses differ by which sentence is
  missing; the answer index tracks the permuted labels shown), part (b) answers "0,1" under the
  shown labels, part (c) answers index 0.
- Description max 254 chars — the sixteen are under, confirm read-back is not truncated.

## Screenshots

One per question, **FULL PAGE, ONE SHOT**: `Emulation.setDeviceMetricsOverride` to `scrollHeight`
+ margin, a single `Page.captureScreenshot`, then clear the override. **Do not scroll and stitch.**
Confirm each image's landmarks appear once before reporting.

Save to:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/scratchpad/1-5-slot1.png` …
`1-5-slot16.png`

## Write back — as TEXT, never a JSON round-trip

`target.aid` and all sixteen `qid`s into the manifest; entries in `reference/question-library.json`
(cid 334437 = breadcrumb, library is shared — check `libs` off moddataset, not the index, before
any "two libraries" conclusion); update the qid column in
`questions/data-collection/AGENTS.md` (currently TBD). Then:
```bash
cd C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop
bun mom-content/reference/sync-index.ts
bun mom-content/reference/sync-index.ts --check   # must exit 0
```
The manifests are hand-formatted one-question-per-line — a JSON parse/stringify rewrites every
line. Assert each file still parses.

Also: update `target.aid` on the manifest FIRST (5.3's rule: write qid back in the SAME step as
filing, never as a later chore — the silent path is a duplicate library entry on the next push).

## Report to stdout (and via msg.mjs increments)

A per-question table: slot, file, qsetid, points, byte-match y/n, submitted-and-graded-correct y/n.
Plus: the aid used (existing scaffold or created with copyfrom), the Book URL used, the final
header score, the audits' output, and **which checks you could not perform**. The visual pass on
the MOM-filed copies is the USER'S to call — the screenshots + the sandbox-side review summary are
your input to it. State them separately.

Do not edit any `.php` during the push beyond what is already in the files — they were reviewed
visually 2026-08-26 and are the reviewed state (one fix landed: camila-vs-rosa-settlement.php
parentheticals).

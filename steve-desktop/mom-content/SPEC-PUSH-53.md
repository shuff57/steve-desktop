# SPEC — push 5.3 into its existing empty assessment (PHASE 1: file, attach, points)

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

**Phase 1 only. Do NOT answer questions or verify grading — that is a separate task.** Runs here get
killed on elapsed budget, so this is deliberately half a push.

Drive Playwright over CDP `http://127.0.0.1:9223` via
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/_push/mom.mjs`.
Not browser-harness. **Do not kill the Chrome process.**

**REPORT INCREMENTALLY.** Send a message after filing, and again after points are set:

```
node C:/Users/shuff/.claude/bin/msg.mjs send --from opencode --to claude --re last --text "..."
```

Five runs have been killed mid-task today. The ones that reported as they went kept their work; the
ones that saved it all for the end lost everything. **Five questions filed and reported beats ten
filed and lost.**

## Target

| | |
|---|---|
| Manifest | `books/introduction-to-stats-sh/lab/5-3-normal-distribution-lap-times.json` |
| Assessment | **aid `23444259`** — ALREADY EXISTS and is EMPTY |
| Course | `334437` |
| Questions | 10, all `multipart`, **none already in the library** — all 10 file fresh |
| Points | must total exactly **100** |

Root for relative paths:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/`

**Do not create an assessment.** The course holds a full ch1-12 scaffold; `23444259` is already on
the manifest as `target.aid`. **Do not touch its settings** — it is a `lab` and the scaffold was
built with them.

## Flow

```
1. file     GET moddataset.php?aid=23444259&cid=334437   (no id ⇒ new question form)
              fill five fields, save, read qsetid off the response links
2. attach   GET modquestion2.php?qsetid=<qid>&cid=334437&aid=23444259&from=addq&process=true&usedef=true
3. points   GET modquestion2.php?id=<instanceid>&aid=23444259&cid=334437
              set hidden `points`, Save Settings — total EXACTLY 100
```

## Traps — every one of these has bitten in this course today

1. **The five `// === ... ===` marker lines are DELIMITERS, not content.** Strip the marker line and
   any blank line after it from each section before filing. A push earlier today filed them and
   `// === QUESTION TEXT ===` rendered as visible text on all ten questions of 5.2.
2. **CodeMirror saves empty, silently.** Use the page's own handles — `window.controlEditor` and
   `window.qEditor.qtext` / `window.qEditor.solution`, which is what `saveEditors()` uses — or
   `textarea.nextElementSibling.CodeMirror`. Setting `textarea.value` saves BLANK.
3. **`moddataset.php` has FOUR identical visible Save buttons**, all bound to
   `quickSaveQuestion(true)`; any one commits the whole form over AJAX. A guard expecting exactly
   one will refuse and save nothing — that cost a run its first attempt today. Set all three
   editors, then click the topmost **once**.
4. **`qtype` defaults to `number`, and a wrong qtype renders perfectly clean.** Click the `#qtypedd`
   entry, then assert the hidden `[name=qtype]` actually changed before saving. All ten are
   `multipart`.
5. **Read every point value back** and assert the total is 100. An assignment totalling 99 grades
   every student slightly wrong and MOM never complains.

## Verify (phase 1 scope only)

- Byte-exact read-back per field against the source, marker stripped. Normalise only CRLF→LF,
  em dash→`--`, ellipsis→`...`, and MOM's `&lt;`/`&gt;` storage form.
- qtype matches each source's `SET QUESTION TYPE TO:` marker.
- Attached in manifest slot order, 10 rows.
- Points total exactly 100.
- Rendered page: **zero** `=== QUESTION TEXT ===` / `=== COMMON CONTROL ===` / `=== ANSWER ===`,
  **zero** concat leaks (search `' . ` and ` . '` with an **unbounded** pattern — the leak can run
  50+ characters and a short pattern missed it once), zero `Eeek!`.

Do **not** answer the questions. Grading is phase 2, and re-entering Teacher Preview starts a fresh
attempt anyway, so a score read outside the answering session means nothing.

## Write back — as TEXT, never a JSON round-trip

`qid` per slot and `target.aid` on the manifest, plus entries in `reference/question-library.json`.
These manifests are hand-formatted one-question-per-line; a `JSON.parse`/`stringify` rewrites every
line (measured: 1169 lines changed where 26 were correct). Assert it still parses.

Then:

```bash
cd C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop
bun mom-content/reference/sync-index.ts
bun mom-content/reference/sync-index.ts --check   # must exit 0
```

## Report

Per question: slot, source, qsetid, qtype, points, byte-match y/n. Then the marker count, concat
count, Eeek count and points total for the assessment. State anything you could not do.

`mom-content/questions/` is CLAIMED by claude — read those sources, do not write them. Report a bad
source rather than editing it.

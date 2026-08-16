# SPEC — finish Chapter 5 in the master course 334437

Three assignments. **5.1 is already pushed** and needs only its final verification; **5.2 and 5.3
are empty and need the full push.** Every question `.php` already exists and every manifest is
complete — this is not authoring.

Follow `~/.claude/skills/mom-transfer/SKILL.md`. This spec overrides it in two places, both marked
OVERRIDE. Everything else in the skill applies as written, including the qtype audit, the
byte-exact read-back, the points-total rule, Teacher Preview, and `sync-index`.

**If any path below does not exist, STOP and say so rather than guessing.**

## OVERRIDE 1 — the assessments already exist. Do NOT create any.

334437 already holds a complete 74-assessment scaffold for chapters 1-12 (verified live
2026-08-16). Step 1 of the skill's flow is **already done**. Creating one makes a duplicate.

| Manifest (absolute) | Kind | Qs | Pts | EXISTING aid | State |
|---|---|---|---|---|---|
| `.../books/introduction-to-stats-sh/hw/5-1-the-standard-normal-distribution.json` | hw | 10 | 100 | **23444257** | PUSHED — verify only |
| `.../books/introduction-to-stats-sh/hw/5-2-using-the-normal-distribution.json` | hw | 10 | 100 | **23444258** | empty — full push |
| `.../books/introduction-to-stats-sh/lab/5-3-normal-distribution-lap-times.json` | lab | 10 | 100 | **23444259** | empty — full push |

Prefix for those paths:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/`
which is also the root for the relative `file_path` values inside the manifests.

Each aid is already recorded as `target.aid`. **Do not touch assessment settings** — no `copyfrom`,
no dates. The scaffold was built with them. If something looks wrong, report it, do not fix it.

## OVERRIDE 2 — drive with Playwright over CDP, not browser-harness

browser-harness cannot work here: Chrome's remote-debugging gate 404s on the default profile, and a
harness daemon already running ignores a later `BU_CDP_URL`. An authenticated Chrome is up on a
throwaway profile with the gate open:

```
CDP endpoint   http://127.0.0.1:9223     (logged in as Steven Huff)
```

Use the staged connector:
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/_push/mom.mjs`
It exports `connect()`, `go(page, url)`, `CID`. `go()` throws if a login form is served at a content
URL — that is how a dead session disguises itself as a small successful page. Do not work around it.

`playwright` resolves from `bookSHelf/node_modules`; the connector re-roots module resolution there
via `createRequire`. Import through the connector or you get `ERR_MODULE_NOT_FOUND`.

**One tab, navigated.** **Do not kill the Chrome process** — the MOM session cookie is in memory and
closing it costs a manual re-login. `browser.close()` on a CDP connection only detaches; that is fine.

### assess2 is a Vue SPA

`/assess2/?cid=&aid=` mounts asynchronously. The **Teacher Preview** button does not exist in the DOM
on load — waiting for the URL or `domcontentloaded` is not enough, and a capture taken too early is
of the loading shell (measured: 142KB shell vs 855KB real page). Wait for a control matching
`/teacher\s*preview/i` to exist, click it, then wait for answer widgets before measuring anything.

Answer widgets here are **MathQuill spans and radios, not `input[type=text]`** — a count of text
inputs reads 0 on a fully working page. Do not use that as a health signal.

## Task A — verify 5.1 (aid 23444257), already pushed

Already done and verified by claude, do NOT redo:

- all 10 attached in slot order; `1874315` and `1874316` REUSED (not refiled), 8 newly filed
  `1878485`-`1878502`
- points `10x7 + 9 + 9 + 12 = 100`, read back per instance
- qtype audit: 10/10 `multipart`, zero mismatches
- `description` / `qtext` / `solution` exact-equal on all 10; `control` differs on 2 questions ONLY
  by MOM normalizing an em dash to `--` inside a `//` comment — benign, pre-existing, do not "fix"
- manifest + `question-library.json` + `question-index.json` all written

**What is left: Teacher Preview.** Fill and WORK every question, submit each, confirm it grades.
Full marks alone is not the finish line — a question can render clean and still be wrong. Report any
question that does not reach full marks, and any that does but looks wrong.

## Task B — push 5.2 (aid 23444258), then Task C — push 5.3 (aid 23444259)

Full flow per the skill, minus creation: file → attach → points → Teacher Preview → write back.

Do them **one at a time**, and STOP for the gate after each. Do not batch all three and report at the end.

## Traps

1. **`reference/question-library.json` outranks a manifest `qid`, always.** Check every source path
   against it before filing. A question already in the index gets attached, never refiled. This is
   real, not theoretical: two of 5.1's ten were already filed while the manifest said `null`.
2. **CodeMirror saves empty, silently** — `control`/`qtext`/`solution` are CM instances; setting
   `textarea.value` saves blank. Write via `cm.CodeMirror.setValue()`. Byte-compare the read-back.
3. **qtype defaults to `number`, silently**, and a wrong qtype **renders perfectly clean**. Click the
   `#qtypedd` picker entry, then assert the hidden `[name=qtype]` actually changed before saving.
4. **The real Save is a visible `<button type=button>Save</button>`.** The only `input[type=submit]`
   is the invisible `justupdatelibs`, which discards everything and redirects like a success.
5. **Do NOT round-trip manifests through JSON.** They are hand-formatted, one question per line; a
   `JSON.parse`/`stringify` rewrites every line. (Measured today: 1169 lines changed where 26 were
   correct.) Patch as text, then assert it still parses.
6. **Screenshots must be ONE-SHOT full page** — `Emulation.setDeviceMetricsOverride` to
   `scrollHeight`, a single `Page.captureScreenshot`, then clear. Never scroll-and-stitch. Pixel
   height is not proof: confirm the page's landmarks appear **once each** and the last question's
   Submit sits near the BOTTOM of the image.

## Write-back, then sync

Per question: `qid` on the manifest slot AND an entry in `reference/question-library.json`.
Per assignment: `target.aid`, plus a `_pushed` note shaped like `hw/4-3-binomial-distribution.json`.

Then, **not optional**:

```bash
bun mom-content/reference/sync-index.ts
bun mom-content/reference/sync-index.ts --check   # must exit 0
```

`mom-content/questions/` is CLAIMED by claude — do not write there. Those files are what your
read-back is compared against. You have write access to the manifests, the two index files, and
`_push/`. If a `.php` needs a fix, report it; do not edit it.

## Gate — claude verifies. You do not call pass/fail on your own work.

1. Byte-exact read-back per filed question, field by field.
2. qtype matches the source marker for every question.
3. Points total exactly 100.
4. Slot order matches the manifest.
5. No duplicate library entries.
6. Teacher Preview: every question answered, submitted, and seen to grade.
7. `sync-index --check` exits 0.

Report a **per-question table**: slot, source file, qsetid, filed-or-reused, qtype, points,
byte-match y/n, graded y/n. **Do not claim success for any question you did not see render and
grade.**

## Answer me

Which gate checks could you NOT perform, and why? And name the one unpinned decision you had to
make, and what you chose.

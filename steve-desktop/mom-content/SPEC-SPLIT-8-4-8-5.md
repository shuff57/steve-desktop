# SPEC — split the merged 8.4+8.5 assessment into two

Steve's decision, 2026-08-16: **split.** The master course scaffold merged §8.4 and §8.5 into one
assessment before the questions existed — a planning guess. The repo now holds two complete,
balanced 100-point assignments, and merging would mean discarding roughly half the authored work.

This is the ONE place the no-create rule is deliberately broken. Everywhere else, the assessment
already exists and must be reused.

Follow `~/.claude/skills/mom-transfer/SKILL.md`. Drive Playwright over CDP `http://127.0.0.1:9223`
using `mom-content/_push/mom.mjs` — not browser-harness. Do not kill the Chrome process.

**If any path or id below does not resolve, STOP and say so rather than guessing.**

## Current state

| | |
|---|---|
| Existing assessment | **`23444280`** — currently titled `8.4 Rare Events, the Sample, Decision and Conclusion + 8.5 Additional Information and Full Hypothesis Test Examples` |
| Its question count | **0** — empty, verified 2026-08-16 |
| Course | `334437` (Global Question Class) |

| Repo manifest | Qs | Pts |
|---|---|---|
| `.../books/introduction-to-stats-sh/hw/8-4-rare-events-the-sample-decision-and-conclusion.json` | 13 | 100 |
| `.../books/introduction-to-stats-sh/hw/8-5-additional-information-and-full-hypothesis-test-examples.json` | 12 | 100 |

Prefix: `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/`

Both manifests currently have `target.aid = null` — deliberately left unlinked pending this decision.

## Task 1 — rename the existing assessment to 8.4 only

`23444280` is empty, so renaming is safe and loses nothing.

New title, exactly:

```
8.4 Rare Events, the Sample, Decision and Conclusion
```

The course page renames in place via the row's editable title (`input.editname#inputA23444280`), or
through `addassessment2.php?id=23444280&cid=334437`. **That settings form is Vue** — set the value
then dispatch `input` with `{bubbles:true}`, or the save silently keeps the old title. Read the
title back off the course page afterwards and confirm it changed.

Do NOT touch any other setting on it. Do not set dates.

## Task 2 — create ONE new assessment for 8.5

`POST addassessment2.php?block=0&cid=334437`. Only `name` is required; the redirect to
`addquestions2.php?cid=&aid=<NEW AID>` is how you learn the new aid.

Name, exactly:

```
8.5 Additional Information and Full Hypothesis Test Examples
```

Settings: `copyfrom = 23258795` (the `hw` template, per `reference/assessment-presets.json`), with
`copysummary` / `copyinstr` / `copydates` / `copyendmsg` all left **unchecked**. Undated —
`sdatetype=0`, `edatetype=2000000000`. Read the sdatetype/edatetype **radios** to confirm, never the
date text fields, which stay populated with values MOM no longer honours.

Place it directly after 8.4 in the course ordering if the UI allows it without disturbing anything
else. If ordering turns out to be fiddly, leave it where it lands and say so — position is
cosmetic, and I would rather have it in the wrong place than have you dragging rows around a
94-assessment course.

**Create exactly one assessment. If you find yourself creating a second, stop — something is wrong.**

## Task 3 — push both, one at a time

Full flow per the skill: file → attach → points (exactly 100 each) → Teacher Preview, working every
question → write back.

Do **8.4 first, then STOP and report.** Do not start 8.5 until gated.

Traps, all of which have bitten in this course already:

1. **`reference/question-library.json` outranks a manifest `qid`.** Check all 25 source paths
   against it before filing anything. Several 8.x questions are shared with other assignments and
   may already be filed. A question in the index gets attached, never refiled.
2. **CodeMirror saves empty, silently.** Write `control`/`qtext`/`solution` via
   `cm.CodeMirror.setValue()`, never `textarea.value`. Byte-compare the read-back.
3. **qtype defaults to `number`, and a wrong qtype renders perfectly clean.** Click the `#qtypedd`
   entry, then assert the hidden `[name=qtype]` changed before saving.
4. **The real Save is a visible `<button type=button>Save</button>`** — the only
   `input[type=submit]` is the invisible `justupdatelibs`, which discards everything and redirects
   like a success.
5. **Do NOT round-trip manifests through JSON.** Hand-formatted, one question per line. Patch as
   text, then assert it parses.
6. **assess2 is a Vue SPA** — the Teacher Preview button does not exist on load. Wait for it, click
   it, then wait for answer widgets. Widgets are MathQuill spans and radios, so
   `input[type=text]` counts 0 on a healthy page.
7. **Screenshots one-shot full page** — `Emulation.setDeviceMetricsOverride` to `scrollHeight`, a
   single `Page.captureScreenshot`, then clear. Never stitch.

## Write-back, then sync

- `target.aid` on each manifest: `23444280` for 8.4, the NEW aid for 8.5.
- `qid` on every question slot, plus entries in `reference/question-library.json`.
- A `_pushed` note shaped like `hw/4-3-binomial-distribution.json`.
- Record in the 8.5 manifest that its assessment was **created by this push** (everything else in
  ch1-12 was pre-existing scaffold) so the next reader is not confused.

Then, not optional:

```bash
bun mom-content/reference/sync-index.ts
bun mom-content/reference/sync-index.ts --check   # must exit 0
```

`mom-content/questions/` is CLAIMED by claude — do not write there. Manifests, the two index files
and `_push/` are yours.

## Gate — claude verifies. You do not call pass/fail on your own work.

1. `23444280` reads the new 8.4-only title, and still has its original settings.
2. Exactly ONE new assessment created, correctly named, undated, copied from `23258795`.
3. Per question: byte-exact read-back, qtype matches source marker.
4. Points total exactly 100 on each of the two.
5. Slot order matches the manifests.
6. No duplicate library entries.
7. Teacher Preview: every question answered, submitted, seen to grade.
8. `sync-index --check` exits 0.

Report a per-question table: slot, source file, qsetid, filed-or-reused, qtype, points, byte-match
y/n, graded y/n. **Do not claim success for any question you did not see render and grade.**

## Answer me

Which gate checks could you NOT perform, and why? Name the one unpinned decision you made and what
you chose. If you have no image input, say so plainly — the visual check will be routed elsewhere.

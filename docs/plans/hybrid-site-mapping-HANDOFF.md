# Handoff — hybrid site mapping (as of 2026-07-29)

Read this first, then `hybrid-site-mapping.md` for the full plan and the trap list.

> **2026-07-29 — see also `selector-ladder-HANDOFF.md`.** Uncommitted work on top of `9a3a9ae`
> changed how elements are addressed: `role=name` now outranks `#id`, so newly healed selectors
> persist in the `role=name` form. Old profiles still resolve unchanged. It also fixed a live bug
> where ranking returned a bare tag and replay clicked the first `<a>` on the page.
>
> **`regress.js` FAILED — the app is wedged, not the change.** `listTabs()` reports a tab that has
> no target in CDP `/json/list`, so the crawl read nothing (`peak queue 0`). Restart the app before
> running the harness. The failed run also wiped the `books-toscrape-com` and `quotes-toscrape-com`
> baseline profiles (`regress.js:238` deletes before crawling); the Canvas and MyOpenMath profiles
> are intact.

## Where it stands

**The goal condition is MET on BOTH LMSs.** All six acceptance criteria pass on a live,
authenticated, *populated* course on each:

- **Canvas** `canvas.butte.edu/courses/31407` (75 enrolled) — 135 pages, 19 chunks, 16 sections,
  0 navigation failures, 0 student names on disk. Reached at `4ef212e`.
- **MyOpenMath** `cid=193698` (76 enrolled) — 35 pages, 11 chunks, 10 sections, 0 roster matches,
  **0 tokens inside URLs**. Reached at `9a3a9ae`, after four fixes for one root cause (below).

Branch `desktop`, working tree clean at **`9a3a9ae`**. 1062 tests, typecheck 0 errors,
`regress.js` 3/3 on the offline suite (books 18 · quotes 133 · the-internet 59), re-run after the redaction change.

| # | Criterion | Evidence |
|---|---|---|
| 1 | No two captured pages are the same | 135 distinct DOMs of 135 graded |
| 2 | Every doc URL resolves | 10/10 land, zero 404s |
| 3 | Sampled claims match reality | ≥8/10 within ±20% |
| 4 | No PII in the artifacts | 0 of 19 person-shaped labels match a live roster entry |
| 5 | No model read a roster | 7 tool calls, 1 navigation, 0 to a people surface |
| 6 | Sections are real sections | 13 sections, each owns its surface, 0 cross-listings |

## MyOpenMath re-run DONE — 2026-07-29. ALL SIX HOLD, on both LMSs now.

Course `cid=193698` (76 enrolled — `316341` has ZERO students, which is why it never tested
criterion 4). 35 pages captured, 10 sections, Question Set Management (601 question-bank pages)
deselected at the gate. **PASS: 1, 2, 3, 4, 5, 6 — plus 0 tokens inside URLs.** Table and analysis
in `hybrid-site-mapping.md`. Branch `desktop`, clean at **`9a3a9ae`**. `regress.js` 3/3, 1062 tests,
typecheck 0 errors.

Everything that failed was one root cause — **one surface reached two ways counted as several**:

- `b51dd04` the approval gate **could not render at all**. It keyed `{#each}` by section name and
  MyOpenMath returned two sections called "Messages". A duplicate key is FATAL in Svelte 5: the whole
  gate failed to paint, and the aborted render left the PREVIOUS message on screen, so a survey that
  had completed correctly read as one that died mid-flight.
- `c854846` `normalizeUrl` sorts query params (the course home was stored twice, once stale — that
  was criterion 3's only real miss) and `aliasKey` groups zero-valued-param variants, a zero being a
  DEFAULT (`folder=0`) rather than a selection.
- `66f0281` a section cannot list another section's index (criterion 6, stated directly — frequency
  cannot catch it when a site renders a different nav per script), one surface yields one section,
  and diagnostic lists are keyed by position.
- `9a3a9ae` never tokenize inside the site's own URL. `/forums/newthreads.php` was coming out as
  `new⟦D29⟧s.php` because "Thread" is a data cell on that page. Fixed by protecting the URL SPAN,
  NOT by broadening the exemption — dropping a substring-matched value untokenizes it everywhere,
  which on a site serving `/bookmarks.php` would expose a student named Mark. `gateSecrets` keeps
  the outbound gate in agreement so a protected URL cannot refuse the chunk.

Two corrections worth carrying forward: half of criterion 3's original failure was **the harness
measuring wrong** (`input[type=submit]` is a button by computed AX role — the app was right), and the
map is SMALLER after the fix (25 vs 41) because aliases that were double-counted now collapse, so
page counts across this change are not comparable.

Two operational rules this cost real time to learn:
- **Never reload the app UI.** It orphans the native webview: new tab id, old `window.name`,
  `closeTab` does not destroy the view, and a fresh `newTab` reuses the orphan. The app's `navigate`
  then no-ops while CDP still works. Only a restart recovers — and that kills every LMS session.
- **A failed render lies about state.** The panel's DOM showed values the code could not produce.
  Attach a CDP console + `Runtime.exceptionThrown` listener BEFORE theorising about the environment.

## What is NOT proven — start here

1. ~~MyOpenMath has not been re-run~~ — done, and all six now pass there. What is left: **Question
   Set Management (601 pages) was deselected at the gate**, so template saturation was exercised
   only on the 85-page remainder. A full-site MyOpenMath run has never completed.
2. **Criterion 4 compares against one course's roster.** A name not on that roster — a
   co-instructor, a dropped student, a name in prose — would not be detected.
3. **Criterion 6's checker judges the DOC, not the section list.** It cannot see a section that
   was wrongly *omitted*.
4. `## ⟦D12⟧` is still in the Canvas doc — that is "People", which appears in no Canvas URL, so
   it is not structural and stays tokenized. Cosmetic, not a defect.

## The seven fixes of 2026-07-28/29, and why each matters

Every one came from running against a real site, not from prediction.

- `9d2e9fc` **chrome by frequency** — furniture is what many sections carry, not what the start
  page has. Both earlier tests were wrong in opposite directions.
- `0689255` **a mutating verb belongs to a path segment, not a title** — `/pages/how-to-submit-
  your-homework` was being refused. MyOpenMath hid this because its URLs are `modcourse.php`.
- `f9705e6` **the survey must never open its own tab** — a CDP-created tab has no session, so it
  hit the SSO wall. Also: report the agent's own diagnosis instead of "use Map this site instead".
- `8ae71b5` **person ids in a path segment** (`/users/<id>`), not just a query. `slot` now carries
  `in: 'query' | 'path'`.
- `a92a15f` **a label on a link that addresses a person IS that person** — 26 real student names
  were on disk from discussion-board post authors. The *page* was never the right unit; the link
  is. This is the most important fix of the set.
- `6effb03` **a compound URL key exempts its parts** — `module_item_id` → `module`, which had
  made 40 doc URLs unopenable.
- `4ef212e` **match a label to its URL segment across case and plural** — `Assignments` ↔
  `/assignments`, which had left every section heading tokenized.

## Running it

App: `cd steve-desktop && bun run tauri:dev`. CDP port is printed in the log and **changes
between runs** — read it, don't assume 9223.

Harness lives in `~/.claude/plans/hybrid-harness/` (copied out of the session scratchpad, which
is disposable). Run with `bun <script>`; several hardcode `canvas-butte-edu` and need the domain
edited for another site: `crit6.js`, `rosterx.js`, `structcheck2.js`, `canvas-names.js`,
`canvas-distinct.js`, `wouldfix.js`.

A full run, in order:

```bash
bun marker.js            # app tab id must match the webview's window.name
bun canvasauth.js        # 0 password fields + course nav = signed in
bun clear.js             # clear THROUGH the app, never rm the store under it
bun canvasrun.js         # survey -> gate -> approve -> capture, logs progress
# then verification
bun fingerprint.js <store-dir>      # criterion 1
bun probe-map.js  <store-dir> 10    # criteria 2, 3
bun rosterx.js                      # criterion 4 (loads the live roster, prints counts only)
bun didnav.js <survey-transcript>   # criterion 5
bun crit6.js                        # criterion 6
```

MyOpenMath equivalents, added 2026-07-29 (course ids ROTATE — never reuse one from a doc):

```bash
bun momauth.js 9223                        # signed in? + the live cid list, from the home page
bun momenroll.js 9223 <cid> [cid...]       # COUNT-ONLY enrollment per course, never prints a name
bun momstart.js 9223 <cid>                 # land on the course; catches the login form AT course.php
bun momclear.js 9223                       # remounts the panel FIRST, aborts unless it holds mom
bun momgate.js 9223                        # survey only — stops at the gate and lists the sections
bun momcapture.js 9223 "Question Set Management"   # deselect, verify the count dropped, approve
# then verification (crit6/rosterx have MyOpenMath variants; the rest take a domain dir)
bun momcrit6.js www-myopenmath-com         # criterion 6, surfaces derived from the doc
bun momrosterx.js 9223 <cid>               # criterion 4 + tokens-inside-URLs, counts only
```

`momclear.js` exists because "Clear all" aims at whatever map the panel loaded on mount: after
crawling another site it still held `the-internet.herokuapp.com · 59 pages`. It remounts the panel
and refuses unless the header names MyOpenMath. `momcapture.js` refuses to approve unless the page
count actually dropped — approving 733 pages by accident is an hour of a live account.

> **2026-07-29 — `momclear.js` is STALE. Its abort tells you nothing about the store.** It dispatches
> `steve:action-panel` events to remount the panel; those no longer switch views, so it sits on the
> Dashboard, finds no site-map header, and prints
> `ABORT — the panel does not hold a <site> map` **even when the store is present and populated**. That
> abort is the guard firing on a bad premise. Do not conclude the store is empty from it.
>
> **Current route:** click the **Site Profiles** nav button. The view lists one row per domain
> (`<domain>` · `<N> pages · <doc state>`) with per-row **Update** / **Delete**. Delete is
> **arm-then-confirm**: the first click changes that row's button to `Confirm delete`, the second
> performs it.
>
> **The trap that nearly deleted the wrong profile:** walking up from a label to "the nearest ancestor
> containing a Delete button" reaches the whole panel, so the click arms the FIRST row
> (`auth.w3.org`), silently — nothing appears to happen, and only one button's text changed. The row is
> `button.parentElement.parentElement`. Require it to name **exactly one** domain, the right one, with a
> matching page count; arm; then **re-read which row is armed**; then confirm. That guard is the only
> reason `auth.w3.org` survived. Remount (Dashboard → Site Profiles) to clear a wrong arm.
>
> Done this way on 2026-07-29: the hijacked `canvas-butte-edu` store (87 pages, half-captured) was
> deleted — 89 files → 0 — with `auth-w3-org` and `www-myopenmath-com` verified untouched on disk. The
> verified GREEN 135-page map is backed up at `scratchpad/canvas-green-135` (137 json + 1 md).

Finding the survey transcript: the app spawns a fresh CLI process, so it is NOT this session's
`.jsonl`. Search `~/.claude/projects/*/` for one containing `Survey the STRUCTURE`, modified
during the run. Take the big one — a real survey is ~40-60 lines; the 12-line files are
fragment/merge calls and grading one gives a meaningless PASS.

## Hard-won operational rules

- **Never trust `listTabs()`.** Compare against `curl http://127.0.0.1:<port>/json/list`. They
  disagreed once: the app claimed two tabs on the course page, CDP showed one on the roster.
- **Never kill the app to abort a run.** Use the panel's Stop button (works during survey and
  chunked capture since `8a6689b`). Killing it ends the LMS session — `PHPSESSID` and the Canvas
  SSO cookie do not survive a restart, and you will have to ask the user to sign in again.
- **Never `rm` the profile store while the app is running.** It holds the site map in memory and
  will write it straight back. `bun clear.js` (the app's own "Clear all") is the correct route —
  though note it does not delete `_people.json` or `_sitemap-ai.md`; remove those by hand.
- **Zero source edits while a run is in flight.** HMR remounts the component and orphans it.
- **Only one monitor process.** `nohup bun x.js > log 2>&1 &` as its own command — backgrounding
  a whole `&&` chain launches two, and the second auto-approves a capture while the first is
  still clearing the store. That produced a "0 pages captured" run that looked like a bug.
- **Never print a suspected name to verify it.** Hash it, count it, or report its JSON path.
  Printing one to check writes it into the transcript, which is the thing being prevented.
- **Delete pre-fix stores.** A backup taken before `a92a15f` contains real student names; two
  such directories were deleted on 2026-07-29 for exactly that reason.

## Next, in the order I would do it

1. ~~Tokens inside URLs~~ — FIXED (`9a3a9ae`), 8 → **0**, both forum URLs navigable. Not by
   broadening the exemption (that untokenizes a real name site-wide: `/bookmarks.php` vs a student
   named Mark) but by protecting the URL SPAN — mask the site's own URLs, sweep, restore, with
   `gateSecrets` keeping the outbound gate in agreement so a protected URL cannot refuse the chunk.
   Next here would be **item 2 in the plan's list: the built-in Canvas site profile.**
2. **Built-in Canvas site profile** — the user's idea, and the run history is the argument for
   it. `/users`, `/discussion_topics`, `?module_item_id`, the `/modules/items/<id>` redirect are
   identical at every Canvas institution. Ship the profile; let the crawl verify and extend it
   rather than rediscover it. Shape: seeded sections + people surfaces + per-person templates.
3. **Teach-mode integration** — the recording feature already exists (`TeachMode.svelte`,
   `teach-recorder.ts`). The crawl gives coverage of shapes; recording gives the tasks that
   matter. The map then verifies recorded steps still resolve (`verifyMap` / heal already exist).
4. Plan items still open: **item 5** (`SURVEY_MAX_PAGES = 12` coverage) and **item 6** (residual
   PII decision — largely overtaken by `a92a15f`).

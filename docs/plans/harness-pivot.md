# Plan: adopt browser-harness, and stop mapping sites we do not use

**Decision (Steve, 2026-07-29).** Stop investing in exhaustive preemptive site mapping — it has been
flaky across sites and most of what it maps is never used. Adopt `browser-use/browser-harness` as a
dependency, keep only a LOOSE map for orientation, and let per-task knowledge accumulate as real
automations get built.

Supersedes the expansion half of `hybrid-site-mapping.md`. What that work proved about SAFETY still
governs everything below — see "What does not change".

## Why this is the better spend

Today's runs mapped 273 Canvas pages and surveyed 725 MyOpenMath ones. Steve will touch maybe ten
task paths: enter grades, post an announcement, email a parent, find missing work. Exhaustive
coverage was the expensive way to reach them, and its correctness criteria (does the doc's claim
match live control counts) are weaker than the obvious test — *did the task work?*

`browser-harness` is already built for the accumulate-as-you-go model, verified by reading the source:

```
agent-workspace/domain-skills/<host>/<task>.md     ~60 shipped examples
BH_DOMAIN_SKILLS=1  → read every file in domain-skills/<site>/ before inventing an approach
goto_url(...)       → returns up to 10 skill filenames for the navigated host
agent_helpers.py    → where the agent writes task-specific helpers it needs again
```

## What it is, measured not assumed

- `v0.1.8`, **`Development Status :: 3 - Alpha`**, MIT, Python >= 3.11, 7,489 lines.
- Deps pinned: `cdp-use==1.4.5`, `fetch-use==0.4.0`, `pillow`, `websockets`.
- Attaches to a RUNNING Chromium CDP endpoint (`admin.py:544` reads `/json/version` →
  `webSocketDebuggerUrl`). Same model as our WebView2 on `--remote-debugging-port`.
- **No selector healing.** It does not replace `fingerprint.ts` / `rankCandidates`. "Improves itself"
  means domain-skill files + `agent_helpers.py`, not element recovery.
- Telemetry: PostHog EU, **opt-out (ON by default)**, payload is install id + version/python/os/
  machine. A `FORBIDDEN_KEYS` denylist drops url/uri/href/path/title/text/selector/query/content/
  cookie/email/password/token, and any value containing `://` becomes `[redacted]`. Disable with
  `browser-harness telemetry disable` or `BH_TELEMETRY=0`.
- Bonus, directly useful: `interaction-skills/cross-origin-iframes.md` + `iframes.md` speak to the
  open gap in `selector-ladder-HANDOFF.md` (top-frame-only resolution vs Canvas LTI iframes).

## How it plugs in (cheaper than expected)

`run_agent_cli` (`lib.rs:2027`) spawns `claude -p …` as a child process, and browser-harness ships as
a Claude Code plugin (`.claude-plugin/`, `SKILL.md`). **The dependency lands next to the CLI, not in
the Rust binary** — no Python in the Tauri build, no new app-side transport.

Tab groups: our app takes ONE port for the whole WebView2 process (`run()` scans `9222..=9242` once);
every tab is a target on it. Multi-group works by target selection, not by port-per-group. Separate
ports would require separate webview processes — out of scope, and not needed.

## What does not change (non-negotiable)

These come from live findings on real student data, not caution:

1. **A model-written skill file is model output landing on disk.** The people-pointer rule applies to
   skill files exactly as to profiles: shapes and `{studentId}` templates, never a person. `a92a15f`
   put 26 real student names on disk from discussion-board authors; a recipe saying "click the row
   for <name>" is the same defect in a new file type.
2. **Self-written helper code must be reviewed before it runs against a live gradebook.** Fine for
   scraping arxiv; a wrong click here is a grade change. `agent_helpers.py` diffs get read.
3. **Telemetry off, explicitly**, and asserted in the setup check rather than assumed.
4. **The six criteria still bind whatever writes to disk** — above all criterion 4 (no PII in
   artifacts) and 5 (no model reads a roster). Loose mapping does not loosen those.
5. **Capture stays** for elements a task touches: stored fingerprints are the substrate replay heals
   against. We stop mapping *everything*, not mapping.
6. **Never drive app work through the default daemon.** Always `BU_NAME=steve` +
   `BU_CDP_URL=http://127.0.0.1:<port>`. Measured, not theoretical: the default daemon attached to the
   personal Chrome and listed a Gmail tab. Assert `Browser.getVersion` reports `Edg/` before any
   action that writes — a daemon on the wrong browser is a wrong click somewhere unaudited.

## Phases

### 1. Install + prove the connection — **PASSED 2026-07-29**

Acceptance was: attaches to the app's own WebView2 target (not a separate Chrome) and reads a page.

```
product      : Edg/150.0.4078.105     ← our WebView2, not Chrome
target count : 1  (ours only)
title        : S.T.E.V.E Desktop      ← read a real page's DOM
has steveCtl : object                 ← it can see window.__steveControl
```

Telemetry asserted off by re-reading status, not assumed:
`{"enabled": false, "disabled_by_config": true}`.

#### The invocation is not optional — it is a safety rule

```bash
BH_TELEMETRY=0 BU_NAME=steve BU_CDP_URL=http://127.0.0.1:<port> browser-harness <<'PY' … PY
```

**The first attempt attached to Steve's personal Chrome and enumerated Gmail, Cloudflare and
selfservice.butte.edu** — because `daemon.py:1` is "one daemon per `BU_NAME`", a `default` daemon was
already running and holding its own connection, so `BU_CDP_URL` was ignored entirely. The env var is
read by the daemon **at startup**; handing it to an existing daemon does nothing.

A default-daemon run aimed at a gradebook task would drive the wrong browser silently. Hence
non-negotiable #6 below.

#### This also answers the multi-tab-group question properly

One daemon per `BU_NAME`, each with its own `BU_CDP_URL`. That is the isolation Steve asked about, and
it is cleaner than port-per-group: our app still takes ONE port for the whole WebView2 process, and
harness-side isolation comes from the daemon name, not the port.

### 2. Loose mapping replaces exhaustive mapping
- ~~Keep the built-in profile path (`canvas-profile.ts`, uncommitted)~~ **LANDED `6a22d6c`** —
  12 sections for zero model calls. That IS loose mapping. 1082 tests, typecheck 0.
  Two comments were fixed on the way in: the code claimed the profile was intersected with the course
  nav, which is the design the live probe rejected.
- Cap or retire the deep chunked capture as the default. Question banks (601 pages) stop being
  crawled at all.
- Acceptance: a Canvas course yields a usable orientation map in under 3 minutes, no survey agent.

### 3. Two real tasks, in this order. Read-only first, write second.

**Decided 2026-07-29.** Both get done; the order is the safety design. "Find missing work" proves the
accumulate-as-you-go loop with nothing at stake. Grade entry is the task worth having, and it only
runs after the loop itself is trustworthy — a defect found in 3a is a wrong answer, the same defect
found first in 3b is a wrong grade.

#### 3a. Find missing work for a course (read-only) — **DONE 2026-07-29, one item outstanding**

**PII rule chosen by Steve: ids only, never names — including in the transcript.** Delivered stricter
than that: **zero identifiers at all**, by using `submission_summary` (per-assignment counts, no user
ids in the response) and projecting fields *inside the page* so only counts cross the CDP boundary.

Result on `40160` ("Stats Fall 25 - Huff", teacher, 34 students): 20 published assignments,
**77 not-submitted**, 1 ungraded, 602 graded. Heaviest: Chi-Square / Linear Regression / Unit 1 Mock
Test at 8 each; all four Tests at 0 missing.

**Not `31407`** — that is a `designer` enrolment, so every grader endpoint 403s there while
`/assignments` returns 200, which reads exactly like a dead session. See
[[canvas-31407-is-designer-not-teacher]]; the verified 135-page map is therefore role-scoped.

Skill file written: `~/.config/browser-harness/agent-workspace/domain-skills/canvas.butte.edu/`
`find-missing-work.md` (4.4 KB). It records the API route, the role-403 trap, the
`needs_grading_count`-is-not-a-permission-probe trap, the `Promise.all` fix for the IPC timeout, and
the `BU_NAME`/`createTarget` driving recipe.

- Acceptance — **zero roster matches: PASS.** Roster of 34 fetched, matched and discarded *in-page*;
  returned counts only: `fullNameMatches: 0`, `nameTokenMatches: 0` (tokens > 3 chars). No `/users/<id>`
  literals; templates are `{courseId}` / `{assignmentId}` / `{studentId}`.
- Acceptance — **"a second run is measurably faster": MEASURED, and it transfers.** The stronger test
  was run: not the same course again, but a course never touched before — `41919` ("Huff - StatsC1000 -
  Sp26", 22 students). One invocation, first try, no exploration: **8 seconds**, 24 published
  assignments, 38 not-submitted, role confirmed `teacher`.

  | | run 1 (`40160`, discovering) | run 2 (`41919`, following the file) |
  |---|---|---|
  | harness invocations | 6 | **1** |
  | dead ends | `submission_summary` 403, IPC timeout (~200s), two bad permission probes | **none** |
  | wall clock | minutes | **8s** |

  The knowledge transferred to an unseen course, which is the actual claim behind
  accumulate-as-you-go — not merely "the second run of the same thing is cached".
- Caveat, still open: I authored the skill file rather than having a model discover-and-write it. Safer
  (no model ever held a name) but the **self-writing half of the pivot remains unproven**. What is
  proven is that a written file makes the next run cheap.

#### 3b. Enter a grade (writes)
Gated on 3a passing. Three steps, each one a stop.

1. **Empty course first.** MyOpenMath `cid=316341` has **ZERO students enrolled** — the reason it
   never tested criterion 4 is exactly what makes it the right rehearsal ground. A wrong click there
   reaches no one. Prove the full write path here before touching a populated gradebook.
   **Canvas now has an equivalent, found 2026-07-29:** `34903` ("SANDBOX2 - S. Huff") — active
   `TeacherEnrollment`, **0 students**, 37 assignments, unpublished; `30504` is a second one. Rehearse a
   Canvas grade write there rather than on MyOpenMath, so the rehearsal matches the target platform.
2. **Read the helper diff.** Whatever `agent_helpers.py` gained in step 1 gets read by a human before
   it runs against a real course. Non-negotiable #2, and this is the case it was written for.
3. **One known grade, then verify externally.** On the populated course: a single cell, a value
   chosen in advance, then confirm through the MyOpenMath audit log (`myopenmath-audit-log-check`)
   that exactly one mutation landed and it is the intended one. The app's own report is not evidence
   of what the app did.
- Acceptance: audit log shows exactly one change, matching the intent; the written skill file names
  no student; and the harness can be stopped mid-task without leaving a partial write.
- If any step is ambiguous, stop. A grade is not a retryable operation.

#### 3b status 2026-07-29

**Step 1 — DONE, on Canvas rather than MyOpenMath.** Sandbox `34903`, assignment `985355`, the course's
**Test Student** (`StudentViewEnrollment`) — the only gradeable subject in an empty course.

```
preflight: total_students 0 · real students 0 · isTestStudent true · Edg/ asserted
write    : null → PUT 200 → score 7 → revert PUT 200 → null      (no residue)
```

The full write path is proven: `_csrf_token` cookie URL-decoded into an `X-CSRF-Token` header, `PUT
submission[posted_grade]`, re-`GET` to verify, restore to the captured prior value. Preflight and write
live in **one** expression, because state can change between calls.

**Step 2 — nothing to review.** `agent_helpers.py` does not exist; no helper code was generated. So
non-negotiable #2 is satisfied *vacuously*, not by inspection. It still binds the moment a helper appears.

Skill file written and PII-checked: `domain-skills/canvas.butte.edu/enter-a-grade.md`. Combined check
over **both** skill files against **two** live rosters (56 students, matched and discarded in-page):
`fullNameMatches: 0`, `nameTokenMatches: 0`, `userIdMatches: 0`.

**Step 3 — BLOCKED on Steve, and deliberately not guessed.** A real grade write needs four values that
are his to name: course, assignment, student, value. There is no defensible default for "which grade to
put on whose record", so no assumption was made. Two things he should weigh first:

- **Every teaching course is from a past term** (FA25 / SP26 as of 2026-07). Writing to a concluded
  course edits a final grade record.
- The `40160` gradebook is essentially complete — 602 graded, **1** ungraded (Roll Call Attendance).
  There is no backlog that needs a bulk write, which weakens the case for doing this at all right now.

### 4. Fold in the iframe technique — scoped 2026-07-29, needs the app to validate

**Correction to this plan's own earlier claim:** `interaction-skills/iframes.md` and
`cross-origin-iframes.md` are **3-line stubs** — author notes on what the docs should cover, with no
recipe in them. I called them a directly-useful bonus; they are not. The technique is in the code.

The mechanism worth porting (`helpers.py:355`, `helpers.py:448`):

```python
iframe_target(url_substr)  # first Target.getTargets entry with type == "iframe"
js(expr, target_id=tid)    # Target.attachToTarget(flatten=True) -> sessionId -> evaluate THERE
```

**The gap is narrower than `selector-ladder-HANDOFF.md` states, and that matters — it makes this
cheap.** That doc says resolution is top-frame only, which is true, but implies frame identity is
absent. It is not:

- `merged-tree.ts:65-82` walks `contentDocument` and tags every node with its owning `frameId`;
  `DOM.getDocument` is called with `pierce: true` and AX is fetched **per frame** (`:316`), merged by
  `(frameId, backendNodeId)`.
- Then it is **discarded**: `frameId` occurs nowhere outside `merged-tree.ts` and its test — not in
  `fingerprint.ts`, not in `selector-resolve.ts`, not in `replay.ts`.

So capture already knows which frame an element lives in, and replay throws that away before asking
`document.querySelector`. The port is *carrying a field we already compute*, plus two resolve paths:

| Frame kind | Where it lives | How to resolve |
|---|---|---|
| same-origin | same CDP target, pierced into the captured tree | frame-scoped context, or `contentDocument` walk |
| cross-origin (Canvas LTI) | **its own** target, `type == "iframe"` | `attachToTarget(flatten=True)` → evaluate in that session |

**LANDED `7798822` — `frame-resolve.ts` + 16 tests (1098 suite, typecheck 0/0).**

`planFrameResolution(frameId, mainFrameId, targets)` → `{kind:'main'}` | `{kind:'target',targetId}` |
`{kind:'pierce'}`, plus `pierceQueryExpr` / `pierceCountExpr` / `REACHABLE_DOCS_EXPR` for the
same-origin walk. Decoupled on purpose: **no import of `fingerprint.ts` or `selector-resolve.ts`**,
because both are uncommitted in the other session's tree — editing them would repeat exactly the
collision that voided the regress run.

Properties the tests pin, each one a way this could go wrong quietly:

- a missing/unknown `frameId` → `main`, so **every stored profile resolves exactly as it does today**;
- piercing prefers the **outermost** document, so widening the search cannot silently relocate a match
  that already worked;
- a cross-origin child that throws on `contentDocument` is **skipped, not fatal** — one hostile frame
  must not hide the same-origin element after it;
- a frame **cycle terminates** (documents are deduped);
- a `page` target whose id equals the frameId is **not** attached to — only `type === 'iframe'` counts.

- **Still to do:** call `planFrameResolution` from resolution. Until then this module is dead code —
  iframes do NOT resolve yet. That wiring waits on the other session's files landing.
- Acceptance: a stored selector for an element inside a Canvas LTI iframe resolves instead of
  reporting drift. That is the false-drift class this is here to kill. **Not yet verified live** —
  jsdom frames stand in for real OOPIF targets.
- Also worth stealing, separately: their **default is coordinate clicks** — CDP mouse events cross
  iframe/shadow/cross-origin boundaries at the compositor level. Good escape hatch for clicking, but
  it is not element *identity*, so it stays a fallback tier and does not replace this work.
- Deliberately not started: this needs live Canvas LTI pages to validate, so it waits on the app.

## Open questions for Steve

- ~~Which task is phase 3?~~ **Decided: both, read-only first.** See phase 3 above.
- ~~Alpha (`0.1.8`) pinned, or vendored?~~ **Decided by Steve 2026-07-29: neither — track latest and
  auto-update.** My pin recommendation is overruled; recorded because the tradeoff still applies.
  - Install is **editable from a clone at `C:\Users\shuff\Developer\browser-harness`**, not from
    PyPI. `uv tool list` showing `v0.1.8` is the clone's version, and `--update` reported "up to
    date" because it was *detached at the `v0.1.8` tag* — frozen, unable to ever advance.
  - Fixed: clone moved onto `main` tracking `origin/main` (`dbe6f8f`, 45 commits fast-forwarded to
    reach the same code as the tag). Updates now actually land.
  - **Accepted consequence:** upstream `main` on an Alpha package can change behaviour under a live
    gradebook task with no warning. The mitigation is not a pin — it is that phase 3b writes only
    after a human reads the diff, and that `git log` in that clone is the first thing to check when
    behaviour changes for no local reason.

## The wedge is NOT blocking — browser-harness routes around it. **No restart needed.**

The webview is wedged (`webview-wedge-silent-miscapture`), verified from both sides: app `listTabs()`
claims a tab on `the-internet.herokuapp.com`, while CDP and `Target.getTargets` see **only the app UI**,
and the app's own `newTab()` returns ok while producing no target. It also explains the regression
suite's `peak queue 0` — that run was never graded, it was wedged.

**But the wedge is in the app's tab plumbing, not in WebView2.** Measured 2026-07-29:

```
Target.createTarget(url=…)   → real page target appears
js(…, target_id=tid)         → reads its DOM (Target.attachToTarget, flattened)
Target.closeTarget(tid)      → clean, back to 1 target
```

And the created target **inherits the process's cookies**, so the live LMS sessions are reachable.
MyOpenMath, read-only, counts only: `landed host www.myopenmath.com`, `path is login: False`,
**`pw fields: 0`**, `course links: 16`, logout present — `momauth.js`'s own signed-in test, passing.

Consequences, and they are the pivot paying for itself on day one:

1. **Phases 2-3 are unblocked without spending Steve's sign-ins.** No restart.
2. The app's tab layer — the source of the wedge, the orphaned `window.name`, the `newTab`-reuses-the-
   orphan bug — becomes **optional** for harness-driven work. Do not fix the wedge in order to proceed;
   proceed around it.
3. `ensure_real_tab()` stays forbidden anyway: with the app UI as the only pre-existing page target, it
   can navigate the app away. Create your own target, use it, close it.

Still true: the app's own crawl/capture path (`SiteMapper`, `regress.js`) needs the tab layer and
therefore still needs a restart. That is now a *separate* decision, not a prerequisite.

## App restarted 2026-07-29 ~16:35 — wedge GONE, poisoned store CLEARED

Another session's background task killed the app (its "relaunch" exited 0 but left nothing running).
Restarted from this session: **CDP on 9223**, `Edg/150.0.4078.105`.

- **Wedge cleared, verified the way it failed before:** the app's own `newTab()` now produces a real
  page target (1 → 2 targets). It is not merely "no tab open".
- **`canvas-butte-edu` store deleted through the app** — 89 files → **0**, with `auth-w3-org` and
  `www-myopenmath-com` confirmed untouched on disk. Route and the arm-then-confirm trap are documented
  in `hybrid-site-mapping-HANDOFF.md`; `momclear.js` is stale and its ABORT says nothing about the store.
- **LMS sessions are GONE** (they died with the restart). Steps below needing a signed-in course wait on
  Steve: MyOpenMath and Canvas sign-in.
- **After a restart, use a fresh `BU_NAME`** — the old daemon still holds the dead browser's WebSocket
  even on the same port number.

## If and when the app IS restarted, in this order

Not needed for harness-driven work (see above). Needed only for the app's own crawl/capture path.
Ordered because two of these destroy evidence if run early, and the `canvas-butte-edu` store is
currently **half-captured** (87 of 273 pages, hijacked).

1. `curl :<port>/json/list` — confirm a **real page target** exists for the content tab, not just the
   app UI. If it does not, the restart did not take; do not run anything else.
2. Clear the poisoned Canvas store **through the app**, not with `rm` — `bun momclear.js <port> canvas`
   after navigating to the course, since the panel loads its map on mount and "Clear all" aims at
   whatever it loaded. It refuses unless the header names the site, which is the guard working.
   *This cannot be done while wedged:* the panel mounts on the phantom `the-internet.herokuapp.com`
   tab and the guard (correctly) aborts.
3. Only then `regress.js` — and see `selector-ladder-HANDOFF.md` first: it is **retired as the default
   gate**, and its `rmSync` runs before it knows the app can crawl, which is how the three toy-site
   baselines were lost. Prefer the verify-condemnation-delta check documented there.
4. Phase 2 acceptance: a Canvas course yields orientation in under 3 minutes with **zero** survey
   agent spawns, now that `6a22d6c` is in.
5. **Never kill PIDs `15524` / `49600`** to "clear leftovers" — they are `bun run tauri:dev` and
   `bun run dev`, i.e. the app itself. A subagent report recommended exactly this. Always print `Name`
   and `CreationDate` when identifying processes; omitting them caused this to be misread twice.

## Loose ends inherited from today

- Regression subagent is still running the offline suite; it grades the OTHER session's uncommitted
  selector work, so let it finish and report into `selector-ladder-HANDOFF.md`.
- ~~`canvas-butte-edu` store holds a HALF-CAPTURED 87-page run~~ **CLEARED** (89 files → 0) through the
  app's Site Profiles view. The verified 135-page GREEN map remains backed up at
  `scratchpad/canvas-green-135` (137 json + 1 md) — that scratchpad is disposable, so copy it somewhere
  durable if it still matters.
- ~~`canvas-profile.ts` + `canvas-profile.test.ts` + the SiteMapper wiring are uncommitted~~ —
  **committed `6a22d6c`** (1082 tests, typecheck 0/0). The other session's `fingerprint.ts` /
  `selector-resolve.ts` work is still uncommitted in the same tree and was deliberately left alone,
  so that green run covers both changes and is not isolated proof of either.

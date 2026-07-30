# Goal: a hybrid site-mapping schema that produces a map you can trust

## The goal, stated so it can fail

Point the app at a logged-in course, run survey → gate → capture → merge, and get a
`_sitemap-ai.md` whose every claim is true of the live site.

Done means all six hold on a **live authenticated course**, each proven by the check
named in "Verification" below — not by the app's status line:

1. **No two captured pages are the same page.** No single captured DOM (control counts +
   the resolved set of outbound links) appears under more than 4 distinct URLs.

   > Corrected 2026-07-28. The first version graded control-count *diversity* at ≥ 60%
   > distinct. That is wrong: a clean books.toscrape crawl scores 0.444 because a catalogue
   > legitimately reads 20b/0i/95a on every listing page. Low diversity is normal on template
   > sites. DOM identity is the real signal — two different listing pages list different
   > items, so their link sets differ even when their counts match. Under the corrected
   > check, clean books PASSes (largest group 3, all genuine aliases of `/`) and the
   > contaminated MyOpenMath store FAILs with groups of 44 and 37.
2. **Every URL in the doc resolves.** Zero 404s, zero redirects to a different path.
3. **Sampled claims match reality.** For ≥ 8 randomly sampled doc rows, live control
   counts are within ±20% of what the doc asserts.
4. **No PII in the artifacts.** Zero person names, emails, or untokenized ids in
   `_sitemap-ai.md` *and* in stored profiles.
5. **No model read a roster.** Zero *navigations* to a people surface in the survey
   agent's transcript.

   > Corrected 2026-07-28 (second correction of this kind — see criterion 1). The first
   > version counted full-URL *references*, which is unachievable by construction: the
   > course home's nav bar links to Roster and Gradebook, so reading the one page the
   > survey must read puts those hrefs in context. Measured on the 15:33 run —
   > `transcript-people.js` reports 24 references across listusers/gradebook/msglist and
   > FAILs, while `didnav.js` reports 16 tool calls, 2 navigation-shaped, and **0
   > navigations to a people surface**. Nothing loaded a roster; the old check graded
   > link-presence, not context contamination. What matters is whether a page was OPENED.
6. **Sections are real sections.** Each `##` section's rows are members of that
   section, not site-wide nav chrome repeated per section.

## Where it stands (2026-07-28)

Committed on `desktop`: `b59bd45` (fragment/merge actually reach a model),
`cd6fe7f` (fragments overlap capture), `a1068f2` (survey agent barred from people
surfaces; app seeds them index-only).

Works, proven live: survey → sections; approval gate; deterministic capture; 20+
fragments through the redaction gate across 4 sites; cross-section dedupe; parallel
fragments (barrier observed binding at 3); people-surface guard (6 gradebook + 6 msgs
URL refs → 0); no names in the MyOpenMath doc.

**Broken, and it invalidates output:** the webview wedge. On the MyOpenMath run, 82 of
84 profiles shared one fingerprint — the crawler stored one stuck page under 82 URLs
and reported `7 chunks, 190 pages, 7 sections, 0 failures`. The resulting doc asserts a
"159b/43i/43a global shell" that is one wedged page, and an entire Forums section built
on `/forums/managelibs.php`, which 404s.

### Fixed 2026-07-28 — `6b07f67` and follow-ups

The landed-URL assert (item 1) is in. Writing it surfaced three further bugs that no
test and no status line would ever have shown; each is listed here because each was
*invisible* rather than merely unfixed.

1. **Redirect vs wedge.** The first assert failed any non-exact landing, which threw away
   real pages behind 302s. A wedge leaves you on the PREVIOUS page; a redirect puts you on
   a new one. That distinction is the fix. the-internet: 63 pages/2 skips → **64/0**.
2. **Seeding captured the wrong page.** `mapHere(start)` ran without navigating back, but
   the survey agent drives the same tab and leaves it wherever it finished (a live run
   ended on `coursereports.php`). That page was being captured, filed under the course
   home's URL, and used to seed both the people-surface list and the chrome set. Silent.
3. **The survey parser threw away good surveys.** `/\[[\s\S]*\]/` is greedy, so it ran past
   the JSON array to the last `]` anywhere in the reply — the agent's closing note said
   `folder=<0-N[-M]>`. A correct 5-section survey was reported as "survey returned no
   sections". Replaced with `firstJsonArray`, a bracket-depth scan that skips brackets
   inside strings. Lazy matching would have failed the opposite way.
4. **Chrome subtraction over-subtracted the start page.** `idx === start` compared the
   survey's clean `course.php?cid=316341` against a `start` carrying the login session
   param `&r=…`, so the section that IS the start page had its own 232 links removed as
   chrome and came back as 1 page. Compare with `landedOn`, not `===`.

Confirmed working on the live course: 7 sections, with **Roster, Gradebook and Messages
seeded index-only** — automation keeps its access, no model walks them.

### 2026-07-28, later — four more commits, all from defects the testing found

`8bf25d3` refuse a logged-out capture + drop the plain-name roster rule ·
`8a6689b` template saturation in the chunked path + a working Stop ·
`8d4d63a` redaction matches vocabulary on boundaries, ids on substrings ·
(`13b70a8` earlier: people surfaces become pointers)

Each was invisible to the status line, and three were invisible to the criteria above:

1. **A dead session looks like a successful crawl of a smaller site.** MyOpenMath serves
   its LOGIN PAGE at `course.php?cid=316341` with no redirect, so `landedOn` passed and a
   14 KB profile of a login form was stored as the course home. `asksForAPassword` now
   refuses it. The session also does NOT survive an app restart (PHPSESSID), so never kill
   the app to abort a run — which is why Stop had to start working.
2. **The roster content-heuristic was wrong on a fifth of the site.** `looksLikeRoster`
   accepted five plain "Jane Doe" labels; MyOpenMath's footer is exactly five (For
   Instructors / About Us / Forgot Password / Forgot Username / Privacy Policy), so the
   login page AND 66 of 305 real course pages scored as rosters. No threshold separates
   them — the footer is 5 person-shaped labels of 18 (28%), a real gradebook is 31%. The
   plain form is gone; comma form only.
3. **The chunked path never collapsed templates.** `crawl()` has sampled repeating
   templates since it was written; the survey enumerates a URL list and the capture loop
   walked it verbatim. Live: 243 of the first 323 pages were `moddataset.php?id=` in three
   view modes. Replaying all 376 captured pages through the rule gives 49 kept / 327
   collapsed. The panel promised this behaviour for both modes and delivered it in one.
   Collapsing must not shrink the DOCUMENT, so each chunk now carries its collapsed count
   into the fragment prompt.
4. **One data cell poisoned the site's whole vocabulary.** An item-type column reading
   "Forum"/"Assessment" put those WORDS in the dictionary, and the substring sweep rewrote
   `/forums/forums.php` → `/⟦D41⟧s/⟦D41⟧s.php`. Vocabulary now matches on word boundaries;
   anything containing a digit keeps substring matching, which is what still catches
   `/submissions/127333` (see Traps).

## Work, in order

### 1. Landed-URL assert — BLOCKING, do first
Nothing downstream is trustworthy until this exists. After `navigateEmbedded` + `settle()`
and before `mapHere` stores anything, compare the webview's actual `location.href` to the
intended URL. On mismatch: record a failure for that page and **skip the capture** — never
store a page under a URL it isn't.

- Lives in the shared capture path (`crawl`, chunked capture, verify all use it), which
  is why it was deferred. It is no longer optional.
- Compare normalized (ignore trailing slash, `#fragment`, and known session params like
  MyOpenMath's `&r=`); a redirect to a *different path* is a mismatch, not a success.
- Diagnostic already known: the app's `navigate` no-ops while an in-page
  `location.href = …` works. Consider retry-via-in-page-assignment once before failing.
- Test: unit test that a mismatch records a failure and stores nothing. Live test: the
  MyOpenMath capture must come back with fingerprint diversity ≥ 60% or a pile of
  honest failures — either is acceptable, silent success is not.

### 2. Re-run MyOpenMath end to end
The current `_sitemap-ai.md` and the 84 profiles are contaminated — delete them and
recapture. Then run the full Verification block. This is the regression test for #1.

### 3. Section identity
Six sections each reported 42–43 pages that deduped to ~half. Sections are enumerating
site-wide nav chrome, so "Course Content" contains Gradebook, Calendar and Site home.
Fix: when enumerating a section index, drop links that appear on the *start page* too
(they are chrome, not members). Acceptance: criterion 6.

### 4. Canvas — pattern FIXED, live run still owed
The regex fix below is in: `PEOPLE_SURFACE` now carries `\/users?\b`, `\/grades\b`,
`\/submissions?\b`, `\/people\b`, `speed_?grader`, `\/analytics\/(users|student)`. What has
NOT happened is a Canvas crawl. This is the next live test and it matters more than another
MyOpenMath run, for two reasons: **(a)** the 2026-07-28 MyOpenMath course has ZERO enrolled
students, so criterion 4 was proven against a course with no names in it — the pointer
design has never met a populated roster; **(b)** Canvas addresses people by PATH
(`/courses/31407/users/12345`), and `{studentId}` templating in `people-pointer.ts` reads the
person id from a QUERY parameter. That case is listed as untested in the pointer commit and
is the most likely thing to break.

Original finding, kept for context:
`PEOPLE_SURFACE` was written against MyOpenMath. Measured 2026-07-28 against the 125 stored
`canvas-butte-edu` profiles: the shipped regex matches **3**, a Canvas-aware pattern matches
**8**. The five it misses are real people surfaces already captured:

```
/courses/31407/users                                  <- THE ROSTER
/courses/31407/grades
/courses/31407/grades/⟦STU⟧
/courses/31407/assignments/844633/submissions/⟦STU⟧
/courses/31407/assignments/844634/submissions/⟦STU⟧
```

Root cause: the alternative is `\/users?\/` — it requires a **trailing slash**, so the roster
at `/courses/<id>/users` slips past. Redaction did tokenize the ids (`⟦STU⟧`), but the
*surface* was never classified, so the survey agent is not barred from opening it and the
crawler will enumerate it per student.

Fix: `\/users?\/` → `\/users?\b`, and add `\/grades\b`, `\/submissions?\b`, `\/people\b`,
`speed_?grader`, `\/analytics\/(users|student)`. Broadening is the safe direction — a
false positive only makes a surface index-only, which still satisfies the standing constraint
that automation can reach it.

### 5. Survey coverage
`SURVEY_MAX_PAGES = 12` found 3 of ~50 categories on books.toscrape. A large course will
get a correct partial map. Decide: raise the budget, or have the app seed sections from
start-page links generally (as it now does for people surfaces) and let the agent only
name them.

### 6. Residual PII on disk
`course-gradebook-*.json` and `course-coursereports-*.json` hold person-shaped strings in
`interactive.buttons[].text`. `redactTree` keeps control labels by design; on a roster the
label is the person. These never reach a model, but they are on disk. Decide: treat
control labels as data on `PEOPLE_SURFACE` pages, accept, or delete those profiles.

## ALL SIX HOLD — 2026-07-29, live Canvas course (canvas.butte.edu/courses/31407)

135 pages, 19 chunks, 16 sections, 0 navigation failures. This is the goal condition stated at
the top of this file, met on a **populated** course — 75 enrolled people — which is what the
2026-07-28 MyOpenMath runs could not test, that course having no students in it.

| # | Criterion | Result | Evidence |
|---|---|---|---|
| 1 | No two captured pages are the same page | **PASS** | `fingerprint.js`: 135 graded, **135 distinct DOMs**, largest identical-DOM group 1 |
| 2 | Every URL in the doc resolves | **PASS** | `probe-map.js`: 10/10 land, correct titles, zero 404s |
| 3 | Sampled claims match reality | **PASS** | ≥8 of 10 sampled rows within ±20% (e.g. stored 20b/0i/38a vs live 22b/0i/36a) |
| 4 | No PII in the artifacts | **PASS** | 19 distinct person-shaped labels on disk, **0** matching a live roster entry — hashed against the real roster, never printed. Was 26 before `a92a15f`. |
| 5 | No model read a roster | **PASS** | `didnav.js`: 7 tool calls, 1 navigation-shaped, **0** to a people surface |
| 6 | Sections are real sections | **PASS** | `crit6.js`: 13 sections with page tables, each owning its own surface, **0** listing another section's index |

Document: 36 tokens, **0 inside a URL** (was 40), headings readable (`## Modules`, `## Pages`,
`## Discussions`…). People pointer: `slot { param: "users", in: "path", resolveFrom: …/users }`.

Seven commits got here, each from a defect the testing found rather than one predicted:
`0689255` a mutating verb belongs to a path segment, not a title — Canvas slugs like
`how-to-submit-your-homework` were being refused ·
`f9705e6` the survey must never open its own tab (a CDP-created tab has no session), and the
agent's own diagnosis is now reported instead of being replaced by generic advice ·
`8ae71b5` locate a person id held in a path segment (`/users/<id>`), not just a query ·
`a92a15f` **a label on a link that addresses a person IS that person** — 26 real student names
were on disk from discussion-board post authors ·
`6effb03` a compound URL key exempts its parts (`module_item_id` → `module`) ·
`4ef212e` match a label to its URL segment across case and plural (`Assignments` ↔ `/assignments`)

### What is NOT proven

- **MyOpenMath has not been re-run since `8d4d63a`.** Its last document predates four of the
  fixes above. The criteria are met on Canvas only.
- Criterion 4 is a **hash comparison against the roster of one course**. A name that is not on
  that roster — a co-instructor, a dropped student — would not be detected by it.
- `crit6.js` judges by rows, not headings, because a heading can be redacted (`## ⟦D12⟧` is
  People — "People" appears in no Canvas URL, so it is not structural and stays tokenized).

## ALL SIX HOLD ON MYOPENMATH TOO — 2026-07-29, cid=193698 (76 enrolled), after 3 fixes

The second LMS now meets the same bar as Canvas. 10 sections / 676 pages surveyed, Question Set
Management (601 pages of question-bank template) deselected at the gate, **25 pages captured**, 9
sections written.

| # | Criterion | First run | After fixes | Evidence |
|---|---|---|---|---|
| 1 | No two captured pages are the same page | PASS | **PASS** | 25 graded, 20 distinct DOMs, **no DOM spans two scripts** (was 3) |
| 2 | Every URL in the doc resolves | PASS | **PASS** | 10/10 land, zero 404s |
| 3 | Sampled claims match reality | FAIL 6/10 | **PASS 9/10** | nine rows exact |
| 4 | No PII in the artifacts | PASS | **PASS** | 35 person-shaped labels, **0** matching the live 76-person roster; 0 in the doc |
| 5 | No model read a roster | PASS | **PASS** | 17 tool calls, 1 navigation-shaped, **0** to a people surface |
| 6 | Sections are real sections | FAIL ×3 | **PASS** | 8 sections, each owning its surface, 0 cross-listings |

Three commits, all one root cause — *one surface reached two ways was counted as several*:
`c854846` normalizeUrl sorts query params + `aliasKey` groups zero-valued-param variants ·
`66f0281` a section cannot list another section's index, and one surface yields one section ·
`b51dd04` the gate keyed its `{#each}` by section name, which two "Messages" sections made fatal.

`regress.js` **3/3** (books 18 · quotes 132 · the-internet 59, against the 18/133/57 baseline),
1056 tests, typecheck 0 errors.

**Two corrections to the earlier verdict, both worth keeping:**
1. Half of criterion 3's original failure was **the harness measuring wrong, not the app**.
   `<input type=submit>` has computed AX role `button`, so the app counted it as a button — correctly,
   it is an action, not a field — while `probe-map.js` read the explicit role attribute and called it
   an input. That manufactured 2 of 4 misses (managelibs was an exact 3-element swap). Fixed in the
   harness. The remaining miss is the calendar, and it FLIPPED direction between runs (stored 2i/14a
   vs live 1i/15a, then the reverse), so that page genuinely varies between visits.
2. The map is SMALLER than the failing run — 25 pages vs 41 — because deduping aliases and removing
   foreign indexes collapses pages the earlier run double-counted. Smaller and truer, but any
   page-count comparison across this fix is meaningless.

**Tokens inside URLs: FIXED (`9a3a9ae`), 8 → 0** on a re-run (35 pages, 10 sections, all six still
pass, criterion 4 re-proven at 0 roster matches). `/forums/newthreads.php` was coming out as
`/forums/new⟦D29⟧s.php`: "Thread" is a data cell on the forums page, so the word entered the value
dictionary, and the outbound sweep is a plain substring replace. `keepStructural` could not reach it
— `newthreads` is a compound with no separator, and the corpus holds no bare `thread` segment.

**The fix that was rejected matters more than the one taken.** Broadening `keepStructural` to
"substring of any segment" is one line and is unsafe: it drops the value from the dictionary
ENTIRELY, so `/bookmarks.php` untokenizes a student named Mark everywhere he appears and
`/announcements` untokenizes an Ann. There is no syntactic rule separating a site's own vocabulary
from a short given name — so protect the URL SPAN instead (mask, sweep, restore) and the value stays
tokenized everywhere except where it is an address. `gateSecrets` keeps `callModelTree` in agreement,
because a protected URL containing a dictionary value would otherwise refuse every chunk — which
presents as "the crawl worked but wrote nothing".

### The pattern behind three separate crashes: keying a list by a merely-usually-unique value

Section name, page URL, failure URL — each is unique on every site until a real one repeats it. In
Svelte 5 a duplicate key is FATAL: it does not drop a row, it aborts the render, so the panel keeps
painting its PREVIOUS state. That is why a completed survey looked like one that died mid-flight, and
why the DOM showed state the code could not produce. **Reading the app's DOM is subject to the same
rule as reading its status line.** Attach a CDP console + `Runtime.exceptionThrown` listener before
theorising about the environment — it found in one run what two runs and an app restart did not.

## MyOpenMath re-run — 2026-07-29, live populated course (cid=193698, 76 enrolled). 4 of 6 hold.

The owed re-run. Course `193698` was picked because `316341` — the one every earlier MyOpenMath run
used — has **0 enrolled students**, which is why criterion 4 was never really tested there. 11
sections / 733 pages surveyed; Question Set Management (601 pages of question-bank template) was
deselected at the gate, so 132 planned → **41 captured** (4 template groups collapsed), 10 sections
written, 1 capture failed.

| # | Criterion | Result | Evidence |
|---|---|---|---|
| 1 | No two captured pages are the same page | **PASS** | `fingerprint.js`: 41 graded, 33 distinct DOMs, largest cross-script group 3 |
| 2 | Every URL in the doc resolves | **PASS** | `probe-map.js`: 10/10 land, correct titles, zero 404s |
| 3 | Sampled claims match reality | **FAIL** | 6/10 within ±20%. One real defect (below); 3 misses are ±1 elements on counts of 1–9, where the ±20% band is narrower than a single element |
| 4 | No PII in the artifacts | **PASS** | `momrosterx.js`: 768 roster cells hashed (171 distinct), 38 person-shaped labels on disk, **0** matching a roster entry; 0 in the doc |
| 5 | No model read a roster | **PASS** | `didnav.js`: 12 tool calls, 4 navigation-shaped, **0** to a people surface |
| 6 | Sections are real sections | **FAIL** | `momcrit6.js`: 3 cross-listings — Course Content lists the Messages, Forums and Library Management indexes as its own rows |

**Both failures, the duplicate `## Messages` sections, the gate crash and 4 tokenized URLs are ONE
root cause: MyOpenMath addresses a surface several ways, and every dedupe key treats the variants as
different pages.**

- `&folder=0` aliasing — `/msgs/msglist.php?cid=N` and `…&folder=0` are the same surface.
  `chromeByFrequency` counts them separately, so neither variant reaches the "carried by 6+ of 11
  indexes" threshold and neither is subtracted. That is criterion 6's failure, and it is also why the
  survey returned TWO sections named "Messages".
- **Query-parameter ORDER** — the store holds `course.php?cid=193698&folder=0` AND
  `course.php?folder=0&cid=193698` as separate profiles of one page. `normalizeUrl` does not sort
  query params. The second one's stale counts (47b/2i/179a vs 10b/21i/140a live) are criterion 3's
  only genuine miss.
- **Heterogeneous chrome** — MyOpenMath scripts render different navs (msglist.php does not carry the
  full course nav), so a real nav link never appears on half the section indexes. `chromeByFrequency`
  assumes uniform furniture, which Canvas has and MyOpenMath does not.
- **Compound URL segments defeat the label↔segment exemption** (`4ef212e`). `/forums/newthreads.php`
  → `/forums/new⟦D29⟧s.php`, same for `flaggedthreads.php` and `report-missing⟦D35⟧.php`: the label
  "Threads" is a SUBSTRING of the segment `newthreads`, not equal to it, so the exemption misses and
  the vocabulary sweep rewrites the URL. Canvas scored 0 tokens-in-URLs because its segments match
  their labels whole (`Assignments` ↔ `/assignments`).

Fixed during this run, and it was blocking everything: **the approval gate could not render.**
`{#each chunkSections as sec (sec.name)}` keys by section name, MyOpenMath returned two sections
named "Messages", and Svelte 5 treats a duplicate key as fatal — so the whole gate silently failed to
paint. `surveyAndPlan` had completed correctly (11 sections, 733 pages, message set); the aborted
render left the PREVIOUS message on screen, so the panel read as "the flow stopped after the survey"
and the DOM showed state the code could not produce. Two runs and an app restart were spent on
environment theories before a CDP console listener found it in one run. Keyed by index URL + position
now. **Reading the app's DOM is subject to the same rule as reading its status line: a failed render
lies about state.**

Also learned, the hard way: **reloading the app UI ORPHANS the native webview.** After `Page.reload`
the app minted a new tab id while the webview kept the old `window.name`; `closeTab` did not destroy
the native view and a fresh `newTab` silently reused the orphan. End state: the app's `navigate`
no-ops while in-page `location.href` and CDP `Page.navigate` both still work. The trap list said a
reload does not CLEAR a wedge; it also CAUSES one. Only an app restart recovers, at the cost of every
LMS session.

## Criterion status — superseded, kept for the harness notes: 2026-07-28, MyOpenMath (28 pages)

Not the app's status line. Every row below names the script that produced it.

| # | Criterion | Result | Evidence |
|---|---|---|---|
| 1 | No two captured pages are the same page | **PASS** | `fingerprint.js`: 28 graded, 22 distinct DOMs, largest identical-DOM group **1**, no DOM spans two scripts. (Was 44 and 37.) |
| 2 | Every URL in the doc resolves | **PASS** | `probe-map.js` 10/10 land, correct titles, zero 404s |
| 3 | Sampled claims match reality | **PASS** (harness fixed) | Was 0/10 — the probe counted RAW DOM while the doc counts the app's MERGED TREE, manufacturing a failure on every row. `probe-map.js`'s reader now mirrors `mergedToProfile` (a non-navigable `<a>` is a BUTTON, not a link) and reads 8/10 within ±20%, most rows exact: 631b/227i/1011a stored vs 630b/228i/1011a live. The 2 misses are `folder=` pages, whose folder state is session-sticky, so a direct navigate lands on the default folder. |
| 4 | No PII in the artifacts | **PASS, weakly** | 0 untokenized comma-form labels, 0 person ids in URLs; the 2 "id leaks" are `stu=0`/`filteruid=0` filter defaults. **But this course has no enrolled students** — proven against a roster with no names in it. |
| 5 | No model read a roster | **PASS** (corrected defn) | `didnav.js`: 16 tool calls, 2 navigation-shaped, **0** navigations to a people surface |
| 6 | Sections are real sections | **FIX COMMITTED, unverified live** | The failure: "## Course Content" listed Roster, Gradebook, Messages, Forums and Course map as its rows, each also under its own heading. Cause: the section IS the course home, and the start page was exempted from chrome subtraction. `9d2e9fc` replaces that with `chromeByFrequency` — drop links carried by max(3, half) of the section indexes. Unit-tested against the real shape; **the live section list is the proof and has not been produced yet.** |

Also green: `bun run test` 993 pass, `bun run typecheck` 0 errors, `regress.js` **3/3**
(books 18 · quotes 133 · the-internet 57) after the day's mapper changes. Notably `/login`
is now the one "uncovered" page on two of the three sites — the login-wall guard behaving
correctly on sites it was never written for.

**Updated after `9d2e9fc`: 5 of 6 hold on measurement; 1 is a committed fix awaiting a live
run; 1 (criterion 4) is proven only against an EMPTY roster.**

The single remaining question is therefore the same one item 4 asks: run this against a
course that actually has people in it. Everything else is either green or waiting on the
same run to confirm it. The doc measured above also predates `8d4d63a` and `9d2e9fc`, so a
re-run is owed regardless — spend it on Canvas, not on MyOpenMath again.

What that run must show, all four in one pass:
- criterion 6 — no section lists another section's index as one of its rows
- criterion 4 — against a POPULATED roster: zero person names in the doc and in profiles
- the pointer file — `{studentId}` resolved from a PATH segment, not a query parameter
- the doc — zero `⟦D…⟧` inside a URL

## Verification — run ALL of it, every time

The app's own summary has reported success through four separate broken states. Do not
accept it as evidence.

The harness is written and validated. It lives in the session scratchpad
(`…/cd4cc163-…/scratchpad/`); copy it forward rather than rewriting it, and note that each
script was checked against BOTH a known-good and a known-bad store before being trusted:

| Script | Criterion | Validated by |
|---|---|---|
| `fingerprint.js <domain-dir>` | 1 | PASSes clean books (largest group 3), FAILs contaminated MyOpenMath (44 and 37) |
| `probe-map.js <domain-dir> [n]` | 2, 3 | navigates by CDP `Page.navigate`, since the app's own navigate is the thing under test |
| `pii-sweep.js <root> [domain]` | 4 | id pass + name pass; name pass is COUNT-ONLY and never prints a match |
| `transcript-people.js <jsonl>` | 5 (superseded) | counts full URLs, not bare words — but see the criterion-5 correction: link-presence is not context contamination |
| `crit6.js` | 6 | judges by ROWS, not headings: a section's own surface is the one most of its rows point at, a cross-listing is a row citing a surface another section owns. Blocks with no page table (the doc's "Suggested workflows") are skipped — they cite many surfaces on purpose |
| `rosterx.js` | 4 | loads the LIVE roster, hashes it, intersects with person-shaped labels on disk. Prints counts only, never a name. Validated by catching the real 26-name leak before `a92a15f` and reading 0 after |
| `didnav.js <jsonl>` | 5 | counts NAVIGATION-shaped tool calls (`new_tab`/`goto_url`/`Page.navigate`/`location.href =`) whose target is a people surface. Validated against the 15:33 run: 24 references, 0 navigations |
| `regress.js [port]` | mapper regression | 3-site suite, run after any mapper change |

Finding the survey transcript: the app spawns a fresh CLI process per run, so its transcript
is NOT this session's `.jsonl`. Search `~/.claude/projects/*/` by mtime around the run and
take the BIG one — a real survey is ~60 lines and a few hundred URLs; the 12-line files are
fragment/merge calls. Grading a 12-line fragment transcript gives a meaningless PASS.

Caveat on `pii-sweep.js`'s name pass: it is a shape heuristic and false-positives heavily on
prose (a Terms-of-Service page scored 45, help pages 26–28). Read it per-file and care about
the roster-shaped files (`gb-itemresults2`, `gradebook`, `coursereports`), not the total.

```powershell
# 1. Fingerprint diversity — catches the wedge
$recent = Get-ChildItem $profileDir -Filter *.json |
  Where-Object { $_.LastWriteTime -gt (Get-Date).AddHours(-2) -and $_.Name -ne '_sitemap.json' }
# group by "{buttons}b/{inputs}i/{links}a"; distinct/total must be >= 0.6
```

```bash
# 2+3. Every doc URL resolves, and sampled claims match live counts
#   navigate via in-page location.href (the app's navigate can silently no-op),
#   then read {pathname+search, title, button/input/link counts} and diff vs the doc.
#   Any "404 Not Found" title is a fabricated or dead route.
```

```powershell
# 4. PII scan — COUNT, never print. Classify capitalized pairs against a UI vocabulary;
#    report only "UNRECOGNIZED: n". Check the doc AND the stored profiles.
```

```powershell
# 5. People surfaces in the agent transcript — count FULL URLs, not bare words.
#    The prompt now contains the deny-list terms itself, so word counts give false hits
#    (this produced a wrong "29 navigations" figure; the real number was 6).
```

Plus: `bun run test` and `bun run typecheck` green, and `regress.js` 3/3 against the
2026-07-26 baseline (books 18 / quotes 134 / the-internet 58) after any mapper change.

## Traps that cost real time — do not rediscover these

- **The app renders one ActionPanel per tab.** A stray blank tab means
  `document.querySelector` drives the *blank* tab's mapper with `pageUrl = ''`. Close blank
  tabs; confirm exactly one `.provider-select` exists.
- **Verify `pageUrl` before every model run.** Click "Map this page" first; if it files under
  the right domain, `pageUrl` is real. An empty one buys a 900s agent run that cannot succeed.
- **Zero source edits while a CLI run is in flight** — HMR remounts the component and orphans
  the run, and the stale instance keeps driving the tab underneath you.
- **The webview leaks CDP targets across runs.** Before driving, confirm the target list has
  no orphans; a survey agent will happily drive a stale tab from a previous site.
- **Restarting the app clears a wedge; reloading the UI does not.** Watch for an orphaned
  vite holding port 5174 on relaunch.
- **`regress.js` wipes a domain folder before crawling and aborts if the app holds it** —
  destroying contents but leaving the directory. Back up any `_sitemap-ai.md` first, and
  navigate the app off that domain to release the lock.
- **Never restore a blanket `catch` in `fetchFragment`.** It hid a dead sidecar and a
  redaction-gate refusal across three live runs, each presenting as "crawl worked, wrote
  nothing".
- **Tokenize the finished prompt, not one field** — the leak was a section name ("Home").
  Over-redaction is the correct direction for the OUTBOUND prompt.
  > Amended 2026-07-28 (`8d4d63a`). This trap used to end "word-boundary matching would let
  > `/submissions/127333` through", and that is still true of *blanket* boundary matching —
  > which is why the fix splits on a digit. A value containing a digit is an identifier and
  > keeps substring matching, so `/submissions/127333` → `/submissions/⟦D…⟧` exactly as
  > before (asserted in `redact-tree.test.ts`). Only digit-free VOCABULARY moved to word
  > boundaries, because unrestricted substring matching was rewriting the site's own nouns
  > inside URL paths and making the document unusable. Do not "restore" the old behaviour
  > without reading that test — and never apply boundaries to a value containing a digit.
- **The merge model reconstructs redacted values from context** and writes them into its
  reply. The saved doc is scrubbed for this reason; keep that scrub.

## Standing constraints

- Automation must retain access to gradebook, roster and users. Deny-listing them is not an
  acceptable fix; map their *shape*, never walk them per student.
- Live student data: read-only, deny-by-default, and prove claims with evidence rather than
  the app's status line.
- Model calls authenticate by CLI login. There is no sidecar on :3456; do not build one.

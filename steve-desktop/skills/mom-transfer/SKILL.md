---
name: mom-transfer
description: Move questions between the local bank and a real MyOpenMath course, in either direction. PUSH — create the assessment, file each question into the library, attach it, then render it and fix what did not come out right. PULL — read a live course's assessments and questions back into the repo, registering a book and importing the sources you authored. Use when transferring an assignment, a book section, or a hand-picked set of questions into MyOpenMath, and when adopting an existing MOM course into the repo.
tags:
  - agent-task
  - myopenmath
---

# mom-transfer — bank ⇄ MyOpenMath

App-managed file. It is rewritten from `skills/mom-transfer/SKILL.md` in the S.T.E.V.E repo every
launch, so edit it there, not here.

Moves questions out of `mom-content/` and into a live MyOpenMath course, then **looks at what
landed**. Every URL and field name below was verified live against MOM in sandbox course 334243 on
2026-07-31; none of it is inferred from documentation.

```json
{
  "task": "Transfer a MyOpenMath assignment from the local question bank into a MOM course. Ask which assignment (book + assignment name, or a manifest path under mom-content/books/) and which course id if they are not already stated, then follow the mom-transfer procedure: create or reuse the assessment, file each question into the question library, attach it, render the result and repair anything that did not come out right. Report a per-question table at the end and do not claim success for any question you did not see render."
}
```

## Who runs this

The push is mechanical-but-fiddly against an exact spec and a proven script, which is the shape a
cheap model is good at. Measured 2026-08-09 pushing 2.5/2.6/2.7 into 334437 — 30 questions filed, 34
attached, all three totalling exactly 100 — `deepseek-v4-flash:0731` did nearly all of it and got it
right, including recovering from the false-`FAILED` save without blind-retrying.

```
Opus  ──spec──▶  opencode / deepseek-v4-flash:0731  ──drives MOM──▶  push
  ▲                                                                   │
  └────────────────  gate: byte-exact, 100 pts, order, dupes  ◀───────┘
```

```bash
opencode run "Check your inbox." --auto -m ollama-cloud/deepseek-v4-flash:0731
```

Hand off through the message center, not an inline prompt — `msg.mjs send --from claude --to
opencode`, then read the threaded reply. Claim the files first so the run cannot edit its own gate.

**Flash never owns the pass/fail call on its own work.** The gate is what makes this safe, not the
model: byte-exact read-back, points total, question order, no duplicate library entries — all
verified independently by whoever wrote the spec. Two failed reviews → rebuild on Sonnet, never send
flash a third time.

Opus keeps: which section, the points split, anything Steve has to decide, and the gate. It does not
type the driving script.

**One model, not two.** `MOM_TRANSFER_MODELS` in `src/integrations/mom/page-agent-config.ts` names
`gemma4:cloud` as its default, and that is a *different road* — the in-app page-agent tool-calling
loop, measured on a fixture form and never used to push a real question. Do not split the work
across both. Once flash has written the driving script, executing it is `evalScript`, not a model
decision, so there is no page-interaction seam for a second model to fill. Steve's call, 2026-08-10.

## One tab, navigated — not a tab per page

A push is dozens of sequential page loads: a `moddataset.php` per question, a `modquestion2.php` per
attach, `addquestions2.php`, `assess2`. Drive them all through **one tab**.

```
new_tab(url)     ONCE, at the start of the session
goto_url(url)    every navigation after that
wait_for_load()  after each one
ensure_real_tab() only when the current tab has gone stale or internal
```

browser-harness says *"First navigation is `new_tab(url)`, not `goto_url(url)`"* — that is about the
**first** navigation only, and it reads as a blanket rule. Taken that way you get a new tab per
question: thirty-odd tabs by the end of an eleven-question push, each holding a live MOM session.
Seen 2026-08-10. It also makes the run harder to watch, which matters because watching it is how
Steve catches a push going wrong early.

## Two ids, not one

MOM stores a question **twice over**:

| Thing | Id | Where it lives |
|---|---|---|
| Library question | `qsetid`, e.g. `1867347` | the PHP code — `moddataset.php?id=<qsetid>` |
| Assessment instance | e.g. `340741820` | a *reference* + points/order — `modquestion2.php?id=<instid>` |

An assessment does not contain questions; it points at library ones. The `qid` values already
recorded in `mom-content/books/*/ind/*.md` are **library** ids, so that is what a manifest's `qid`
field means. Store the library id; the instance id is disposable.

## Everything goes to one master course

There is exactly one destination: the **master course**, `presets.question_home.cid` in
`assessment-presets.json` (currently **334437**). Questions are filed there, assignments are built
there, and that course is the living MyOpenMath copy of this repo — the thing Steve shares with
colleagues and copies into his own teaching sections.

```
mom-content (git history)  ──push──▶  master course 334437
                                          │  questions filed into the library
                                          │  assignments built and ordered
                                          │
                                          └──Steve copies the course in MOM──▶ each teaching section
```

**This transfer never touches a teaching section.** Getting content in front of students is Steve's
own copy step inside MyOpenMath, done when he chooses. So a push can never disturb a running class,
and "which section does this go to" is not a question this skill answers.

What that means in practice:

- **Push everything to the master course**, both the filing and the assignment. `target.cid` and
  `filing.cid` in a manifest are the same course now; a `target.cid` pointing anywhere else is left
  over from before this changed and should be queried, not followed.
- **Build it to be read by someone else.** It is shared, so assignment names, ordering and the
  chapter folders are part of the deliverable, not housekeeping.
- **Never edit a question in MOM's own editor.** Edit the `.php` in the repo and re-push. Editing in
  both places is the only way the two copies can disagree; with one-way flow a difference can only
  mean a failed write, which the qtype audit catches. This matters more now, not less: a copy taken
  into a teaching section carries whatever was in the master at that moment, so a hand-edit there
  silently propagates to every copy made afterwards.
- **Attaching by `qsetid` still applies within the master course** — a question used by two
  assignments is filed once and attached twice, never filed twice.

**Check `reference/question-library.json` before filing anything.** It maps source file → qsetid
across *every* assignment, which the per-assignment manifests structurally cannot: a manifest only
knows the qids of its own slots, so a question reused by a second assignment looks unfiled and gets
filed **again** — two library questions from one source, drifting apart from that moment. If the
index already has the file, skip step 2 and go straight to the attach. Record every new qsetid there
as well as in the manifest. `question-library.ts` has the reader, writer and the push planner.

## The flow

```
1. assessment   POST addassessment2.php?block=0&cid=<cid>      only `name` is required
                  └─ redirects to addquestions2.php?cid=&aid=<NEW AID>   ← this is how you learn aid

2. question     GET  moddataset.php?aid=<aid>&cid=<cid>        no `id` ⇒ new question form
                  fill five fields (below), POST the form
                  └─ response links carry qsetid=<NEW LIBRARY ID>

3. attach       GET  modquestion2.php?qsetid=<qid>&cid=<cid>&aid=<aid>&from=addq&process=true&usedef=true

4. points       GET  modquestion2.php?id=<instanceid>&aid=<aid>&cid=<cid>   per question
                  set the hidden `points` input, click "Save Settings"
                  └─ the whole assignment must total EXACTLY 100

5. verify       GET  /assess2/?cid=<cid>&aid=<aid>  →  click "Teacher Preview"
                  fill EVERY question, submit it, confirm it grades correct
                  └─ done when the header reads Score: 100/100, Answered: N/N
```

Step 3 is a plain GET. **A question that already has a `qid` in the manifest skips step 2
entirely** — re-pushing an assignment whose questions are already in the library is just N GETs.

Steps 4 and 5 are not optional and not "if there is time". They are the two things that decide
whether students get a working, correctly-weighted assignment, and neither is implied by a
successful save. Details for both are below.

### Filing into a course that has no assessments yet

The question home starts empty, and **filing needs an `aid`** — `moddataset.php` takes one. So step 1
runs there too, purely to create a filing vehicle. Its settings do not matter and it needs no
`copyfrom` template, because nobody sits that course. Record its aid as `filing.aid` on the manifest.

`copyfrom` being **absent** from the new-assessment form is the tell that a course has no assessments
to copy from — useful confirmation, not an error.

**Cross-course attach works.** A `qsetid` filed from one course attaches cleanly into an assessment
in another (verified live 2026-08-08: questions filed from 334243 attached into 334437). That is what
makes one library entry serve every section. Prove it on ONE question before looping.

**And it works for questions you never filed.** A MyOpenMath *shared-library* `qsetid` — one written
by someone else, that has never been through step 2 here — attaches by the same step-3 GET. Verified
live 2026-08-16: `468549` (multipart) and `1178549` (draw), both from IM3's course, attached into a
fresh assessment in 334243 and came back as Q1/Q2 with the right ids and titles.

That is what makes a **pull**ed book pushable. An IM1/IM3 manifest has a `qid` on every slot and a
`file_path` on almost none — 2 791 of 3 091 slots have no source in this repo, because the questions
are MOM's, not ours. Such a manifest is still a complete, replayable assignment:

```
file_path + qid   →  skip step 2, attach          (already in the library)
file_path, no qid →  step 2 then step 3           (the normal push)
qid, no file_path →  skip step 2, attach          (shared library — never file it)
neither           →  STOP. nothing identifies the question
```

**Never try to "fix" a `qid, no file_path` slot by authoring a replacement.** The question exists,
it is what students have been answering, and a lookalike you write is a different question with a
different id. Attach the id.

The one thing you lose is repair: with no source, a shared-library question cannot be edited here
and re-pushed. That is correct — it is not ours to edit. Report it and leave it.

## Settings are Steve's call, once per kind

MOM's defaults are not what Steve wants, and every homework should match every other homework.
**Do not replay a stored list of settings fields** — the new-assessment form already has a
`copyfrom` picker ("copy options from"), so MOM does the copying itself and there is no field list
to drift out of step when MOM adds a setting.

`mom-content/reference/assessment-presets.json` therefore stores a *pointer*: for each manifest
`kind` (`hw`, `group`, `ind`), which existing assessment to copy from.

**Before creating the assessment**, look up `presets[<kind>]`:

- **Present** — on the new-assessment form set `copyfrom` to `template_aid` and leave
  `copysummary` / `copyinstr` / `copydates` / `copyendmsg` **unchecked**. They default to unchecked;
  `copydates` especially must stay that way or the new assignment inherits the template's dates.
  Then set `name` and the dates. Do not ask about settings.
- **Missing** — do not guess. Tell Steve there is no `<kind>` template yet, open
  `addassessment2.php?cid=<cid>&block=0`, and let him set one up exactly how he wants that kind to
  work. Record its aid as the new `template_aid`. Every later assignment of that kind copies it.

`template_aid` is per course — the `copyfrom` list only contains assessments from the same `cid`, so
a template from another course will not be selectable. If `cid` changes, that kind needs a new
template.

To change a kind's settings, edit the template assessment in MOM. Everything pushed afterwards
copies the new version; nothing in this repo needs touching.

### The book link is swapped, not built

A homework assessment carries an external resource labelled **Book** pointing at that section on
bookSHelf. Copying from the template brings the resource row with it, so the job is to **swap the
URL to this assignment's section** — not to add a row from nothing.

Check first: if `showextrefs` is on and an `extreflinks[]` input already holds the template's URL,
replace its value. Only if the row genuinely is not there do you tick `showextrefs`, click
**Add Resource**, and fill `extreflinks[]` with the URL and `extreflabels[]` with `Book`. The row is
created by that button — the inputs do not exist in the DOM until it is clicked, so a fill that
"silently did nothing" means you skipped it.

Never leave the previous section's link on a new assignment. A homework pointing at the wrong
section is worse than one pointing at nothing, because nobody checks a link that looks present.

### `mom_settings` is intent, not fields

Most manifests carry a `mom_settings` block. It is written in prose (`"subtype": "Quiz"`,
`"displaymethod": "All questions on one page"`) — it records what the assignment is *meant* to be,
for a human reading the manifest. It is **not** a set of MOM form values and must never be replayed
into the form. The template assessment is what sets the actual fields; `mom_settings` is what you
check the result against if the two seem to disagree.

### Dates — leave them off when pushing to the master course

**Do not set open/due dates on an assessment pushed into the question home (`334437`).** Leave the
availability radios on their undated default. Steve's call, 2026-08-09: "they only matter once we
copy them over to the live sections" — nothing is ever taught out of the master, so a date there is
never the date anyone sits, and a *wrong* one is worse than none because it looks authoritative.
Uncheck them and move on.

A date set there is not a defect to repair either. Two assignments sharing a window, a window in the
past, a blank one — none of it matters until the copy, and reasoning about term cadence in the
master is wasted work. (Learned by doing it: a whole week-by-week cadence was worked out for 2.5–2.7
and a "collision" escalated as a decision, all of it moot.)

Dates become real at copy time, in the teaching section. `due_date_rule` in
`assessment-presets.json` is the convention for that, and it is still **provisional** — "next class
period" stands in as *the next day, rolling forward to Monday if that lands on a weekend*, until
Steve's term calendar exists. If you do set a date for any reason, say which one in the report so a
wrong one is obvious immediately, and never silently invent a different convention.

Everything else on the form is unaffected: points still have to total exactly 100, the byte-exact
read-back still runs, and every part still gets answered in Teacher Preview. Those are correctness.
Dates are scheduling, and scheduling happens downstream.

#### Undated takes "keep open for ungraded practice" with it — this is expected

`allowpractice` reads **"Keep open for un-graded practice after the due date."** Remove the due date
and there is no "after" for it to mean, so MOM **unticks AND hides the checkbox**. Measured live
2026-08-09: three assessments went undated (`sdatetype=0`, `edatetype=2000000000`) and all three read
back `allowpractice` off and `offsetParent === null`, while a still-dated control kept it on and
visible.

Do not treat that as a regression and do not try to preserve it. Steve's decision, same day: the
master course is undated, and **dates and ungraded-practice are BOTH copy-time settings** — they get
configured in the teaching section, where they actually mean something. Setting either one upstream
is work that gets overwritten.

Two consequences worth knowing:

- **An "undated" assessment still carries stale date values underneath.** MOM keeps the `sdate`/
  `edate` text inputs populated (they came back holding a default one-week window) and simply stops
  honouring them. So reading `sdate` off the form does NOT tell you whether an assessment is dated —
  read the `sdatetype`/`edatetype` radios instead.
- **The `hw` template (`23258795`) is undated too**, so anything pushed with `copyfrom` inherits
  undated availability. That is intended; it is also why a mistake on the template propagates.

### Write the student instructions from the settings, never by hand

`intro` is what the student reads before starting, so it must say what the settings actually do. It
is **generated**, never stored and never hand-written — that is how a question set ends up promising
three attempts while the form grants two.

Generate it *after* the copy, by reading the values back off the created assessment's own settings
form. The copy is the only thing that knows what the template actually contained, so reading the
result is also the check that the copy worked.

Keep it to a few plain sentences covering only what a student needs:

- when it opens and when it is due (`sdate`/`stime`, `edate`/`etime`)
- how many attempts per question (`defattempts`) and what an attempt costs — if
  `defattemptpenalty` is `0` say attempts are free, otherwise say how much is lost and after which
  attempt (`defattemptpenaltyaftern`)
- whether they can regenerate a similar question (`defregens`) and what that costs (`defregenpenalty`)
- when scores and answers appear (`showscores`, `showans`)

Say nothing about a setting you did not read off the form. If a field is absent, leave that sentence
out rather than assuming MOM's default — the whole point is that the text and the form agree.

## The five markers map onto five fields

| Marker in the `.php` | Form field |
|---|---|
| `NAME - DESCRIPTION:` | `description` |
| `SET QUESTION TYPE TO:` | `qtype` |
| `COMMON CONTROL` | `control` |
| `QUESTION TEXT` | `qtext` |
| `ANSWER` | `solution` |

The form also carries a `csrfp-token`. So you must **load the real form and submit that form** —
never hand-roll a POST, it will be rejected.

**The same table read right-to-left is the PULL direction.** `moddataset.php?id=<qsetid>` is the
source of truth in both directions: push writes those five fields, pull reads them back off
`element.value`. Nothing else is needed to reconstruct a bank `.php`.

## PULL — MyOpenMath → bank

Use when adopting a course that already exists in MOM. Two separate jobs; do them in this order and
do not merge them.

```
1. REGISTER   course.php ──▶ books/<slug>/<track>/*.json      metadata only, no sources
2. IMPORT     moddataset.php ──▶ questions/**.php             only what you authored
```

Registering is read-only and safe. Importing writes to the bank, so it needs the provenance check
below first.

### 1. Register a course

Blocks are the whole problem. **A collapsed block renders none of its children into the course-page
DOM**, so a populated course reads as empty. Walk `course.php?cid=<cid>&folder=<path>` recursively —
the "Isolate" links give the child folder paths (`0-6`, `0-6-2`, `0-6-2-10`, …). Verified 2026-08-16:
IM3 showed 6 assessments at `folder=0` and had 74; IM1 showed 0 and had 138.

For each assessment, `addquestions2.php?aid=<aid>&cid=<cid>` lists its questions.

**Use `mom-content/reference/extract-assessment-questions.js` verbatim. Do not write your own.**
That page has **three table layouts** and a naive extractor silently returns zero rows on two of
them — which reads as "this assessment is empty" and passes every structural check. It cost 163
lost questions on a run that reported 71/71 green.

| layout | what breaks a naive extractor |
|---|---|
| plain | — |
| checkbox | `Order` is a `<select>` whose innerText is *every* option (`Q1\nQ2\n…`); `Points` is an `<input>` so the cell text is empty; the header reads `Points\nDefault:` |
| group | header row has no qid and no `moddataset` link. Pool rows either carry `Q1-1`-style orders **or no order cell at all** (first cell is an `Ungroup` link) |

Also: the library search table on the same page has far more `moddataset` links than the real one,
so "pick the biggest table" picks the wrong one. Anchor on the `Questions in Assessment` heading.

**Zero questions is only believable when the page literally says `No Questions currently in
assessment`.** Any other zero is your extractor failing. Re-read once, then STOP and report.

### 2. Decide what is actually yours — before importing anything

Most questions in a real course are **MyOpenMath shared-library questions you assembled, not wrote**.
Measured across IM1+IM3: 1 376 distinct questions, of which **1 183 were shared library** and only
131 were locally authored.

Do not import the shared-library ones. You already have permanent access to them by `qsetid`, which
is exactly how MOM itself reuses them; copying 1 183 files you did not write buries your own bank.

**Judge provenance by the `author` field on `moddataset.php`, not by the `(local for …)` marker in
the title.** A title can carry several markers (`(local for X) (local for Steven Huff)`) and says
only who copied it, not who wrote it. `author` is authoritative.

### 3. Import the ones that are

Read the five fields off `moddataset.php?id=<qsetid>` and write the markers back out in bank order:
`NAME - DESCRIPTION`, `SET QUESTION TYPE TO`, `COMMON CONTROL`, `QUESTION TEXT`, `ANSWER`. Omit the
`ANSWER` section when `solution` is empty — plenty of live questions have none.

Then wire it up, or the import is invisible to the next authoring pass:

```
questions/<family>/<slug>.php        the source
reference/question-library.json      path -> {qsetid, cid, qtype}
books/**/*.json                      set file_path, drop "unbanked"
bun reference/sync-index.ts          rebuild the derived index (never hand-edit it)
```

### Traps specific to pulling

- **`question-lint.mjs` flags `$answers[` as a typo, and on pulled questions it is often wrong.**
  `$answers` is the documented variable for `draw` (`essay-file.md`), `choices` and `matching`
  (`choice.md`). A pulled file is a byte-for-byte copy of something already running in front of
  students — **do not "fix" it to satisfy the lint.** Flag it. The only thing that settles an answer
  key is answering the question and reading the score back.
- Slugified descriptions collide often (21 of 131 needed a suffix — courses reuse names like
  "Practice Test"). Disambiguate deterministically, in qsetid order.
- A dead session serves the **login form at the content URL**, so it looks like an empty course
  rather than an error. Check for `input[type=password]` on every navigation and stop on the first.

## Three traps that all look like success

None is caught by "the save succeeded". All three are only visible when you read the result back.

### 1. `control`, `qtext` and `solution` are CodeMirror, not textareas

Setting `textarea.value` appears to work and saves **empty**. CodeMirror overwrites the textarea
from its own (empty) document when the form submits. `description` *is* a plain textarea and saves
correctly either way — which is exactly what makes this read as a partial success instead of a bug.

**CodeMirror attaches AFTER page load, and that is the version of this trap that actually bites.**
Evaluate too early and `nextElementSibling.CodeMirror` is undefined, your code takes the
`ta.value = text` fallback, the save reports success and the question body is EMPTY. Measured
2026-08-16 filing 5.3: the first attempt fell through to the textarea path on `qtext` and
`solution`, saved, and returned **no qsetid at all**. Wait for the editors before touching them:

```js
await page.waitForFunction(() => ['control','qtext','solution'].every(n => {
  const ta = document.querySelector(`[name=${n}]`);
  return ta && ta.nextElementSibling && ta.nextElementSibling.CodeMirror;
}), { timeout: 30000 });
```

Once they exist, the page's own handles are the most reliable route — they are what `saveEditors()`
itself uses: `window.controlEditor`, `window.qEditor.qtext`, `window.qEditor.solution`. Fall back to
the sibling div only if those are absent.

Write through the editor instance, which CM5 exposes on the sibling div:

```js
const ta = document.querySelector('[name=control]');
const cm = ta.nextElementSibling;            // div.CodeMirror
if (cm && cm.CodeMirror) cm.CodeMirror.setValue(text);   // NOT ta.value = text
else ta.value = text;                        // description and anything un-wrapped
```

Symptom when you get this wrong: the question renders as
`Eeek! $questions is not defined or needs to be an array`.

### 2. `qtype` defaults to `number`

Forget it and every question is filed as numeric and renders wrong. Always set it from the
`SET QUESTION TYPE TO:` marker.

It **does** have a picker, despite the hidden `[name=qtype]` input: a bootstrap dropdown `#qtypedd`
whose entries carry `data-sn` (`a[data-sn=choices]`). Setting the hidden input directly changes the
DOM and is **discarded on save**. Click the picker entry, then assert the hidden input actually
changed before saving.

And when re-saving an existing question, the **real Save is a visible
`<button type="button">Save</button>`**. The only `input[type=submit]` on `moddataset.php` is
`justupdatelibs` — "Save Library Change Only" — and it is *invisible*. Clicking it saves library
membership, discards everything else, and redirects exactly like a successful save.

This is the single most common cause of a transferred question rendering wrong.

**Rendering it is not enough to check it.** A wrong `qtype` renders completely clean: a `choices`
question filed as `number` shows a text box where the radio buttons belong — no `Eeek!`, a widget
present, nothing untypeset. Every render check passes it. Verify it directly instead, for every
question: read `[name=qtype]` off `moddataset.php?id=<qsetid>&cid=<cid>` and compare against the
source's marker. `auditQTypes` in `src/integrations/mom/transfer-via-agent.ts` does the comparison.
Found on 1 of 15 questions in 1.2 that had already passed render verification.

### 3. The assessment SETTINGS form is Vue, not plain HTML

Every field on `addassessment2.php` carries `v-model` — dates, `allowpractice`, every other
checkbox and select. Setting `.value` or `.checked` directly updates the DOM but not Vue's
underlying data, so the save silently keeps the OLD value: same shape of failure as trap 1, a
different library. Set the value, then dispatch the event Vue listens for —
`input` for text, `change` for select/checkbox/radio, both with `{bubbles:true}`.

It breaks reading too, not just writing: `fetch(url).then(parse)` on that page returns empty for
every field, because Vue fills the inputs on mount and the raw HTML ships them blank. Navigate to
the page and read the live DOM — never a fetched copy.

Verified repeatedly: 2026-08-02 building course 334243, and again 2026-08-09 creating 2.5, 2.6,
2.7. Full mechanics in `transfer-rules.md`.

## Never retype question content

Question bodies run to several KB of PHP and HTML. Retyping them through the model corrupts them in
ways that still render — a truncated question looks fine. Read the file, split it on the markers,
and pass the sections through as data:

```js
evalScript(`(function(){ var d = ${JSON.stringify(sections)}; ... })()`)
```

`JSON.stringify` on the whole section object is what keeps it byte-exact. After filling, **compare
the read-back field values against the source sections with an exact string equality check**. A
length check is not enough.

## The markers are DELIMITERS. Never file them.

The five `// === ... ===` lines map a source section onto a form field. They are not part of any
value. Strip the marker line, and any blank line directly after it, before filing.

`qtext` is **HTML, not code** — `//` is not a comment there. A push on 2026-08-16 filed the markers
and `// === QUESTION TEXT ===` rendered as visible text at the top of **all ten** questions of 5.2.

It passed that run's own byte-exact read-back, because the run compared against a baseline that also
contained the markers. **A verifier that derives its expectation the same way the writer did agrees
with the writer's mistake.** The cheap independent check is a count on the rendered page:

```js
(document.body.innerText.match(/===\s*(QUESTION TEXT|COMMON CONTROL|ANSWER)/g) || []).length  // must be 0
```

## A freshly opened Teacher Preview shows NO prior score

Re-entering Teacher Preview starts a **new attempt**. The header shows no score and no question
shows "Score on last try" — **whether or not the assignment was ever graded**. Measured 2026-08-16:
5.2 was answered and graded 102/100 inside its own run, and read zero questions-with-a-score when
the preview was reopened minutes later.

Consequences:

- **Grading must be read in the SAME session that answers.** "Open it later and check the header" is
  not a verification; it is a fresh attempt with nothing entered.
- A capture taken for visual review shows an **unanswered** page unless the capturing run answered it
  first. Do not read `Score: 0/100, Answered: 0/10` on such a capture as a defect — that mistake was
  made today and cost a re-run.
- The durable evidence is the run's in-session report of what it entered and what MOM said back,
  question by question. Which is why the report format asks for exactly that.

## Verify, then fix, then verify again

After attaching, render the assessment and read it. Do not skip this because the save reported OK.

Open `/assess2/?cid=<cid>&aid=<aid>`, click **Teacher Preview**, then check each question for:

- `Eeek!` anywhere in the text → a control-code problem, almost always trap 1 (empty `control`)
- the answer widget missing, or a number box where choices belong → trap 2 (`qtype`)
- the prompt visibly truncated, or `$variable` appearing literally → content did not transfer whole
- math showing as backtick-delimited source instead of typeset → look at it, do not trust text
  extraction; `innerText` does not capture MathJax output

**Take a screenshot and look at it.** Text extraction cannot see typesetting, layout, or a collapsed
solution box. If a question fails: reopen `moddataset.php?id=<qsetid>&cid=<cid>`, re-fill the wrong
field through CodeMirror, submit, and render again. Loop until clean or until you have failed the
same question twice — then stop and report it rather than grinding.

**The screenshot must be FULL PAGE, and you have to check that it is.** A default capture is
viewport-height and stops partway down a long question — on 2026-08-10 four pre-FRQ captures came
back at 49-59KB against 235KB for the same kind of question captured whole, cutting off above the
sample responses and every answer widget. Nothing reported an error; the images simply ended. That
is the worst shape a check can take, because a truncated screenshot reads as a completed visual
check while covering none of the part that usually breaks.

**Take it in ONE shot: `Emulation.setDeviceMetricsOverride` to `scrollHeight` + margin, a single
`Page.captureScreenshot`, then clear the override.** Do not scroll and stitch. Later the same day a
stitched capture came back as the same top-of-page region repeated four times down the image — the
output canvas advanced and the scroll position did not.

**And do not accept pixel-height as the proof.** That stitched image was exactly `scrollHeight` tall
and passed the extent check while showing none of the page below the first screenful. Height tells
you the canvas is big enough, not that the content is there once. Confirm the page's own landmarks
appear **once each** — "Four students answered." once, the last part's prompt once, and the Submit
button near the BOTTOM of the image rather than partway up.

## Write back to the manifest

Two fields, both of which make the next run cheap and safe:

- `target.aid` (and `target.cid`) on the assignment — without it every push creates a *new*
  assessment.
- `qid` on each question entry — without it every push files a *duplicate* library question.

Edit the manifest as text, not through a JSON round-trip: these files are hand-formatted and
re-serialising them rewrites every line. `setQuestionQid` in
`src/integrations/mom/book-membership.ts` does exactly this, and refuses to write anything that does
not parse. Verify the result parses before saving.

### Then sync the bank index — this is not optional

```bash
bun mom-content/reference/sync-index.ts            # rewrite it
bun mom-content/reference/sync-index.ts --check    # exit 1 if stale, change nothing
```

`question-index.json` is the file **step 2 consults to decide whether a question needs filing at
all**, and nothing in `src/` writes it — `recordFiled` in `question-library.ts` has no production
caller, because the push is driven by an agent following this skill rather than by app code. So the
library index gets maintained by the push and the bank index silently does not.

It drifts exactly as you would expect. An audit on 2026-08-09 found **32 questions on disk and
absent from the index**, 56 entries carrying `qsetid: null` while `question-library.json` held a
real id, and 171 stale `used_by` lists. A question missing from the index is invisible to the next
authoring pass, which is how one source becomes two library questions that drift apart — the exact
failure the index exists to prevent.

Run it at the end of every push, and do not hand-roll the equivalent. The first attempt at this was
an ad-hoc `glob('books/*/*/*.json')`, which matched 21 of the 44 manifests and **blanked `used_by`
on every question referenced only by the deeper `college/` and `high-school/` trees**. The script
walks recursively, derives every field from the file itself, preserves `health` (it renders nothing,
so it must never upgrade a `BROKEN` question to `ok`), and is idempotent — running it twice changes
nothing, so it is safe to run whenever you are unsure.

## Every assignment is out of 100 points

Balance the per-question points so they sum to **exactly 100**. Points live on the assessment
*instance*, not the library question, so setting them cannot leak into another section sharing the
same `qsetid`.

They are **not** on `addquestions.php`. Each question's "Change Settings" link goes to
`modquestion2.php?id=<instanceid>&aid=&cid=`, where `points` is a **hidden** input — set it and click
**Save Settings**. Instance ids are consecutive from the first attached question.

Distribute evenly and put the remainder on the heaviest questions, normally the multipart ones.
Eleven questions is `10 + 9x10`; fourteen is `8x2 + 7x12`. Then **read every value back and assert
the total is 100** — an assignment totalling 99 grades every student slightly wrong and MOM never
complains.

## Fill and WORK every question — rendering is not testing

A question can render perfectly, throw nothing anywhere, pass `questionHealth`, and still be broken:
a dropdown that never populates, an answer box that rejects the right value, a matching set whose
key does not line up with its options. **"No error was thrown" is not evidence.**

In Teacher Preview, for **every** question: enter the answer, **submit it**, and confirm it comes
back **correct** — every part of a multipart, not just the first. Then look at the rendered page for
typesetting, a doubled prompt, or a literal `$variable`.

`Score: <total>/<total>, Answered: N/N` in the header is the completion test. It is unreachable
unless every question both renders and grades correctly, so it subsumes the per-question checks —
but only if you got there by actually answering, not by assuming.

**"Show All Answers" does not fill or reveal the key.** It toggles the solution display.

### Submitting is AJAX, and it races — promoted here on the third sighting

There is no "Submit All". Each question has its own **Submit Question** button, the click fires an
AJAX request, and **clicking the next one before the previous request lands silently drops it**.
Every click reports success; the header count quietly runs behind; the question you think you tested
was never submitted. Seen on 1.1 and twice more on 1.2.

Do not click in a loop. After each click, poll for that question's result element before moving on:

```js
// a submitted question gets <div class="scoreresult correct|incorrect"> prepended
// inside its .questionwrap parent; an unsubmitted one has no such div at all
document.getElementById('questionwrap' + n).parentElement.querySelector(':scope > .scoreresult')
```

That div is also the only trustworthy per-question verdict — reading the parent's `innerText`
instead bleeds neighbouring questions' text and invents results for questions never answered.

### Work the answer out from the page, not from the source

A `choices` question's options are **shuffled per seed**, so the source's `$answer` index does not
address the rendered option — and where the key itself is randomized (`$answer = $stype[$vi]`) the
source does not know the answer either. Match the option by its rendered **label text**, and derive
the answer from the rendered prompt.

This is the slow way and it is the right way: an answer replayed out of the same file that generated
the key cannot detect a wrong key. Solving the question independently can. `transfer-rules.md` has
the worked mechanics for `matching`, which is the fiddly case.

## A homework is ten to fifteen questions, and never fewer than ten

Steve's rule, 2026-08-10. **Ten is a floor, not a target.** Fifteen is the comfortable ceiling and
going past it is fine — 1.2 runs to 29 — but a set that lands at eight is not finished, it is short.

The pre-FRQ counts toward the total.

**A thin bank is not a reason to ship nine.** It is the signal that the section needs questions
written before it can be pushed. 3.2 and 3.4 were both built under this floor on the day the rule
was set — 8 and 6 — precisely because the probability bank ran dry, and the honest fix is authoring,
not a smaller assignment. Check the count against the floor *before* writing the manifest: if the
unfiled stock cannot reach ten, say so and write what is missing first.

The one thing not to do is pad. Filler that repeats a slot already covered makes the set longer and
teaches nothing, and it is worse than the short assignment it replaced.

## Every homework carries one to three pre-FRQs

Steve's rule, 2026-08-10: **at least one pre-FRQ per assignment, never more than three.** The exact
count is the pusher's call within that range.

A pre-FRQ is not a free response — it is the auto-graded mirror of one. It reuses an FRQ's scenario
and its grading checklist, then has the student **grade four sample responses against the rubric**
instead of writing one. That is why it coexists with the no-free-response rule below: it teaches the
FRQ's standard and still marks itself. The seven in the bank are named `pre-frq-*.php`, and every one
sits in the **last slot** of its assignment at **12 points**.

**The full pattern is written up in `mom-content/reference/pre-frq-template.md`** — the invariant
three-part structure, the two legitimate variants, the dropped-category table, and the `$answers[1]`
typo that silently leaves a part with no answer key. Read it before writing one.

How many:

| Count | When |
|---|---|
| 1 | the default — the section is one coherent skill |
| 2 | the section carries two genuinely different FRQ-able skills |
| 3 | a chapter-capstone section, and only then |

**"No FRQ to mirror" is not an exemption.** If nothing in `questions/frq/<family>/` covers the
section, the pre-FRQ gets **written** — that is authoring work under the `mom-question` skill, done
before the push, not a reason to ship an assignment without one. Check `questions/frq/` for a mirror
first; say in the manifest `_note` which FRQ each pre-FRQ mirrors, or that one had to be authored.

**Known backlog as of 2026-08-10** — 1.1, 1.2, 1.3, 1.4, 2.1, 2.2 and 2.4 have one each; **2.3, 2.5,
2.6, 2.7, 3.1 and 3.3 have none.** Pre-FRQs were retrofitted onto the first seven in a later pass
that never reached the rest, so this is an unfinished job rather than a set of deliberate omissions.
2.5, 2.6 and 2.7 have obvious mirrors already in the bank (`q9-choosing-the-right-measure-of-center`,
`q5-interpreting-bimodal-data`, `q7-comparing-means-and-standard-deviations`) — matched by filename,
not yet by reading them.

## Homework carries no free response

Steve's standing rule (2026-08-08): **a `hw` assignment must be fully auto-graded.** He is not going
to hand-mark essays, so a free-response part is not a feature there, it is a bill.

Before pushing any `hw`, grep every source for `essay` in `$anstypes` and convert those parts to
`choices`. The two ways MOM handles an essay part are both wrong for homework: with
`$answer[n] = ""` plus `$scoremethod[n] = "takeanything"` it awards full marks for any text at all,
and without them it scores 0 and waits for manual grading — silently costing the student that part
of an otherwise auto-graded question. Both were live in 1.4 until they were converted.

This applies to `hw` only. `questions/frq/` exists for hand-graded tests and is untouched by it.

Watch the softer version too: a `string` part is auto-graded and so satisfies the rule, but it
demands near-exact phrasing. Flag those in `REVIEW-NEEDED.md` rather than assuming they are fine.

## Report honestly

Finish with one row per question: slot, title, library qid, points, and **graded correct or not**.
Any question you did not actually fill, submit and see graded is reported as **unverified**, never as
success. State the points split and the final header score.

## Then reflect — this step is what makes the skill improve with use

Read `mom-content/reference/transfer-rules.md` **before** you start. It holds what earlier pushes
learned by breaking a real course, and the failure you are about to hit may already be solved there.

**Then close every push with this routine, clean run or not.** Skipping it is how the same hour gets
paid twice.

1. **Collect** every one of these that happened during the push, while it is still in front of you:
   - anything that cost more than one attempt;
   - anything that reported success and was not (a save that stored empty, a redirect that meant
     nothing, a green check on a wrong field);
   - any selector, field name, id pattern or URL you had to *discover* rather than read here;
   - anything about the questions themselves — a bad key, a wrong `qtype`, a leaking answer — which
     belongs in `mom-content/REVIEW-NEEDED.md`, not here, because it is content not process.
2. **Keep only what you proved.** A guessed rule makes every later push worse than no rule. If you
   worked around something without understanding it, write the symptom and say it is unexplained —
   an honest unknown is useful; a confident wrong answer is not.
3. **Write it in the shape already there:** what broke, the symptom you would recognise it by next
   time, and the fix. Include the concrete string — the selector, the field name, the error text —
   because that is what makes it findable when it recurs.
4. **Correct what is already there.** If a rule in the file turned out to be wrong or incomplete,
   edit it rather than appending a contradiction. Two rules disagreeing is worse than one wrong rule.
5. **Promote on the third sighting.** A failure seen three times is not a learned rule any more, it
   is part of the procedure — move it into this skill's body and say so in the report.

Nothing to add is a normal outcome of a genuinely clean run. **Do not invent a rule to have written
one** — noise here costs every future push a little reading and a little trust.

Edit `transfer-rules.md` directly; it is an ordinary file. This skill's own body lives at
`steve-desktop/skills/mom-transfer/SKILL.md` in the repo — a promotion in step 5 means editing
*that*, never the installed copy under `~/.claude/`, which is overwritten on every launch.

---

**This file is installed and maintained by S.T.E.V.E Desktop.** It is compiled into the app and
written to `~/.claude/skills/mom-transfer/` on launch, so it arrives with the app and updates with
it. Edits here are replaced on the next launch — change the copy in the repo at
`steve-desktop/skills/mom-transfer/SKILL.md` instead. The rules it points at
(`transfer-rules.md`) are ordinary files and are yours to edit freely.

## Byte-exactness proves the transfer, never the content

Three separate defect classes were found in this bank on 2026-08-16. **Every one scored 102/100 with
every question marked correct, and every one passed the byte-exact read-back and the qtype audit.**

| Defect | What rendered | Why the gates missed it |
|---|---|---|
| `$anstypes` declared 3 entries for a question setting `$answer[0..5]` | part (b) lost its upper box, part (c) rendered **no boxes** | the filed code matched the source exactly — the SOURCE was wrong. Ungraded parts cannot lose points, so it scored full marks |
| PHP concat in QUESTION TEXT | `the middle ' . 80 . '% of the values` | same: faithful copy of a wrong source |
| function name inside backticks | `` `invNorm` `` typeset as `∈ vN or m` | same |

A byte-exact read-back answers "did MOM store what the file says". It cannot answer "is the file
right". **Only two things caught these: a human-scale look at the rendered page, and static audits
written afterwards from the defects themselves.**

So a push is not finished when the read-back matches. Run the static audits first, and get eyes on
the render.

### The static audits — run all three before any push

They live in `mom-content/_push/` and take seconds:

```bash
cd steve-desktop
node mom-content/_push/qtext-audit.mjs      # concat leak in QUESTION TEXT; function names in backticks
node mom-content/_push/anstypes-audit.mjs   # $anstypes count vs max($answer[N])+1 vs answerbox count
node mom-content/_push/usecheck.mjs         # a derived variable defined ABOVE the array it reads
```

Each was written from a real defect and each found exactly the class it was written for and nothing
else. Treat "clean" as meaning *clean against the three things we know to check* — not as a
guarantee. See `mom-content/reference/learned-rules.md` for the measured detail.

### The visual pass needs image input, and the pushing model does not have it

`deepseek-v4-flash` has no image input. It says so when asked, which is the right behaviour — but it
means **the render check cannot be part of the push run.** Capture the page, then route the image to
a model that can see. Both defects found by eyes on 2026-08-16 were invisible to every text check.

Capture rules are in "Verify, then fix, then verify again": one-shot full page, never scroll-and-
stitch, and confirm landmarks appear once each rather than trusting pixel height.

## Long pushes get killed. Structure the work so a kill costs one step.

Six consecutive dispatches were killed mid-task on 2026-08-16; the last died having read ten source
files without touching MOM. The runs that survived were the small ones.

Three habits, each of which demonstrably saved work:

- **Pre-extract what the run would otherwise read.** `mom-content/_push/crib.mjs <manifest>` emits an
  answer crib — `$anstypes`, the seed arrays, the derived variables, every `$answer[...]` and the
  tolerances — at roughly a tenth the size of the sources. The run then spends its budget driving
  the browser instead of reading PHP.
- **Split the push.** file+attach+points is one task; answer-and-grade is another. A combined push
  is the shape that kept dying.
- **Report incrementally, via the message centre, as you go.** The 4.5 verification survived only
  because its report was sent *before* the kill. Five questions verified and reported beats ten
  verified and lost. The message log is the durable record; the run's ending is not.

If the dispatch channel itself starts failing — several kills in a row, each earlier than the last —
stop re-dispatching and do the step directly. That is escalation, not stubbornness; re-sending into
a failing channel is the grind the roster rules warn about.

## `moddataset.php` has FOUR identical Save buttons

All four are bound to `quickSaveQuestion(true)` and any one commits the whole form over AJAX. A
guard written to expect exactly one will refuse and save nothing — that cost a run its first attempt
on 2026-08-16. Set all three editors, then click the topmost **once**; do not click several, which
races and intermittently drops the `solution` field.

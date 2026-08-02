---
name: mom-transfer
description: Push questions from the local MyOpenMath bank into a real MOM course — create the assessment, file each question into the library, attach it, then render it and fix what did not come out right. Use when transferring an assignment, a book section, or a hand-picked set of questions into MyOpenMath.
tags:
  - agent-task
  - myopenmath
---

# mom-transfer — bank → MyOpenMath

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

## Two ids, not one

MOM stores a question **twice over**:

| Thing | Id | Where it lives |
|---|---|---|
| Library question | `qsetid`, e.g. `1867347` | the PHP code — `moddataset.php?id=<qsetid>` |
| Assessment instance | e.g. `340741820` | a *reference* + points/order — `modquestion2.php?id=<instid>` |

An assessment does not contain questions; it points at library ones. The `qid` values already
recorded in `mom-content/books/*/ind/*.md` are **library** ids, so that is what a manifest's `qid`
field means. Store the library id; the instance id is disposable.

## The flow

```
1. assessment   POST addassessment2.php?block=0&cid=<cid>      only `name` is required
                  └─ redirects to addquestions2.php?cid=&aid=<NEW AID>   ← this is how you learn aid

2. question     GET  moddataset.php?aid=<aid>&cid=<cid>        no `id` ⇒ new question form
                  fill five fields (below), POST the form
                  └─ response links carry qsetid=<NEW LIBRARY ID>

3. attach       GET  modquestion2.php?qsetid=<qid>&cid=<cid>&aid=<aid>&from=addq&process=true&usedef=true

4. render       GET  /assess2/?cid=<cid>&aid=<aid>  →  click "Teacher Preview"  →  read it
```

Step 3 is a plain GET. **A question that already has a `qid` in the manifest skips step 2
entirely** — re-pushing an assignment whose questions are already in the library is just N GETs.

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

### Dates

`due_date_rule` in the same file. It is currently **provisional** — "next class period" stands in as
*the next day, rolling forward to Monday if that lands on a weekend*, until Steve's term calendar
exists. Say which date you used in the report so a wrong one is obvious immediately, and do not
silently invent a different convention.

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

## Two traps that both look like success

Neither is caught by "the save succeeded". Both are only visible when the question renders.

### 1. `control`, `qtext` and `solution` are CodeMirror, not textareas

Setting `textarea.value` appears to work and saves **empty**. CodeMirror overwrites the textarea
from its own (empty) document when the form submits. `description` *is* a plain textarea and saves
correctly either way — which is exactly what makes this read as a partial success instead of a bug.

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

## Write back to the manifest

Two fields, both of which make the next run cheap and safe:

- `target.aid` (and `target.cid`) on the assignment — without it every push creates a *new*
  assessment.
- `qid` on each question entry — without it every push files a *duplicate* library question.

Edit the manifest as text, not through a JSON round-trip: these files are hand-formatted and
re-serialising them rewrites every line. `setQuestionQid` in
`src/integrations/mom/book-membership.ts` does exactly this, and refuses to write anything that does
not parse. Verify the result parses before saving.

## Report honestly

Finish with one row per question: slot, title, library qid, rendered clean or not. Any question you
did not actually see rendered is reported as unverified, not as success.

## Then reflect

Read `mom-content/reference/transfer-rules.md` **before** you start — it holds rules earlier pushes
learned by breaking a real course, and it is where the failure you are about to hit may already be
solved.

When a push needed a repair, append what you learned to that file before you finish. One line, in
the same shape as the rules already there: what broke, the symptom you would recognise it by next
time, and the fix. Only write a rule you actually proved — a guess here makes every later push
worse. Nothing to add is the normal outcome of a clean run; do not invent a rule to have written one.

If the same failure shows up a third time, it has stopped being a learned rule and belongs in this
skill's body instead — say so in the report rather than appending a third near-duplicate.

---

**This file is installed and maintained by S.T.E.V.E Desktop.** It is compiled into the app and
written to `~/.claude/skills/mom-transfer/` on launch, so it arrives with the app and updates with
it. Edits here are replaced on the next launch — change the copy in the repo at
`steve-desktop/skills/mom-transfer/SKILL.md` instead. The rules it points at
(`transfer-rules.md`) are ordinary files and are yours to edit freely.

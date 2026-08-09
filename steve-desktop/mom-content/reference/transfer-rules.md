# Learned transfer rules

Written by the transfer loop after pushes that needed repairs. Each line is a rule the loop
discovered by breaking something in a real course and then fixing it — the same shape as
`learned-rules.md`, but about getting a question INTO MyOpenMath rather than about writing one.

Safe to edit or delete by hand — a wrong rule here makes every later push worse, so prune freely.

- `modquestion.php?cid=` is not the question editor and has not been for some time; it answers
  `Missing qsetid` with no form on the page. The editor is `moddataset.php`. A push aimed at the old
  URL fills nothing and can still report success, so check that the fields you set actually exist
  before trusting a fill.
- Creating a question from inside an assessment (`moddataset.php?aid=…`) files it into the library
  but does NOT attach it. The response page carries the attach links; miss that step and the
  question exists, is findable in Manage Question Set, and is simply absent from the assessment.
- Read back and compare every field after a save. Exact string equality against the source section,
  not length — a question truncated mid-HTML still renders without complaint.
- MyOpenMath rewrites an em dash to `--` in the `control` field on save. The saved text is therefore
  legitimately 1 char longer per em dash and an exact compare reports a false failure. Normalise
  `—` → `--` before comparing, building the dash with `chr(0x2014)` — a literal one written into a
  helper script arrives mojibaked and the normaliser then silently never matches. It is only
  cosmetic while the em dashes sit in `//` comments; one inside a student-visible string genuinely
  changes what the student reads, so check where they are rather than waving the difference through,
  and fix those at the source by writing `&mdash;` instead.
- In a matching question the answer selects DISPLAY as `a`–`h` but their option values are `"0"`–
  `"7"`. Setting `select.value = "c"` silently no-ops — the assignment is rejected and the field
  stays empty, with no error anywhere. Set the zero-based index as a string instead. Read
  `option.value`, never `option.text`, before driving any MOM select.
- Rendering a matching question clean does not prove its answer key is right. The only check that
  does is answering it and reading the score back. Answer every question and submit — a full-marks
  run is the difference between "it displays" and "it works".
- Reaching `addblock.php` (or `addassessment2.php`) by typing the URL yourself gives a form whose
  action carries `folder=` but NOT `block=`, and POSTing it returns a bare **HTTP 500** with no
  message anywhere. Enter through the URL the page's own `additem(blk, tb)` builds —
  `add<type>.php?block=<parent>&tb=b&cid=<cid>` — and the action comes out with `block=` in it and
  the POST succeeds. Symptom is a 500 on submit while every field staged correctly, so it reads as
  a server fault rather than a bad entry point.
- A block nests by the `block=<parent>` on that entry URL; children address as `<parent>-<n>`,
  1-based, where `n` counts **every item** in the parent, not just the blocks. A collapsed parent
  does not render its children into the course page DOM at all, so "the block I just made is
  missing" usually means it is there and hidden — but before assuming that, check the address, which
  re-indexes (see the positional-addressing rule further down). `chgblocks.php` lists only the
  blocks; `moveitem.php`'s `blockselect` is the authoritative address list.
- Moving an item between blocks is not on any settings form. It is `moveitem.php`, driven by the
  page's `moveitem()`: POST `{item, block, newblock, moveafter, csrfp-token}` and it answers the
  literal string `OK`. `item` is the COURSE-ITEM id from `moveDialog('<block>','<item>')`, not the
  assessment's `aid` — 1.1 is item `45020296` and aid `23108651`. Omit the token and it answers
  HTTP 200 with a "submission has been blocked" page instead.
- `assmpassword` is `<input type="password">`, which a browser never repopulates on load. So EVERY
  later save of an assessment's settings form submits it empty and silently clears the stored code —
  set at creation, then wiped by a subsequent dates edit, with nothing reporting it. Set the passcode
  as the LAST write to an assessment, and re-set it after any later edit. MOM does echo the value
  back on reload, so a read-back check works.
- The assessment `intro` (student instructions) is a TinyMCE editor over a HIDDEN textarea. Setting
  `textarea.value` saves EMPTY, exactly like the CodeMirror trap for `control`/`qtext` — different
  library, same failure and same silence. Write it with `tinymce.get('intro').setContent(html)`, and
  verify with `getContent().length` rather than the textarea.
- Emptying an external-resource row DELETES it and shifts the remaining rows up, so code that patches
  "row 0" destroys a different link every time it runs: two passes over the same assessment removed
  the Book row and then the raSHio row, leaving Desmos sitting in slot 0. Never clear a row to mean
  "no link" — assert the whole ordered list of rows instead, clicking **Add Resource** to grow it,
  and point a link somewhere honest (the book index) rather than deleting its row.
- The assessment settings form is Vue-reactive: setting a checkbox's `.checked` or an input's `.value`
  directly updates the DOM node but not Vue's underlying model, so the save silently submits the OLD
  value even though the control visibly shows the new one. Dispatch a `change` event on the element
  right after setting it (`el.dispatchEvent(new Event('change', { bubbles: true }))`) so Vue's handler
  actually fires. Hit repeatedly on 2026-08-02 building course 334243 and not caught until read-back.
- Piping a script into a browser driver on stdin gets it decoded as cp1252 on Windows, so a literal
  `—` in the source arrives as `â€”` and every title written from it is silently mojibake. Build
  non-ASCII at runtime (`chr(0x2014)`, `String.fromCharCode(...)`) and keep the file pure ASCII.
  The trap: a repair script that compares its staged value against a target string that is ITSELF
  already corrupted reports success for all of them. Verify by re-reading the field back off the
  server and comparing against the intended text, and expect a straggler — 2 of 12 needed a second
  pass because the save had not committed before the read-back.
- The **real Save on `moddataset.php` is a `<button type="button">Save</button>`**, not a submit
  input. The only `input[type=submit]` on that form is `justupdatelibs` — "Save Library Change
  Only" — and it is **invisible**. Clicking it saves library membership, silently discards every
  other change, and redirects to `manageqset.php` exactly like a successful save. Two repair
  attempts reported `clicked Save Library Change Only` and changed nothing before the button list
  was dumped. Pick the save by visible text `Save` with `offsetParent !== null`, never by
  `input[type=submit]`.
- `qtype` **does** have a picker, contrary to the skill body: a bootstrap dropdown `#qtypedd` whose
  anchors carry `data-sn` (`a[data-sn=choices]`). Writing the hidden `[name=qtype]` input directly
  sets the value in the DOM and it is discarded on save. Click the picker anchor, then assert the
  hidden input changed before saving.
- **A wrong `qtype` renders completely clean.** Slot 14 of 1.2 was declared `choices` and stored as
  `number`: it rendered with no `Eeek!`, no missing widget, just a text box where three radio
  buttons belonged — so every render-based check passed it. Render verification structurally cannot
  catch this. Audit it directly instead: read `[name=qtype]` off `moddataset.php?id=<qsetid>` for
  every question and compare against the source's `SET QUESTION TYPE TO:` marker. 1 of 15 had
  drifted, and only that comparison found it.
- `testquestion2.php` **rolls a new seed on every load**, and the seed changes the options, their
  order and the correct answer. Reading a question in one page load and answering it in another
  answers a different question. Keep observe and act on the same load, and never carry an element
  index across loads — the index map is positional and regenerated per extraction, so a stale index
  silently addresses the wrong control. Instruct by option text; let the agent resolve the index.
- The first DOM extraction after a load can **race MathJax** and come back with the answer controls
  missing entirely — the question text renders but the checkbox list is simply absent. The agent
  loop re-extracts each step so it self-heals, but any single-shot read needs a second extraction.

- **IMathAS emits HTML with unquoted attributes, everywhere** -- `moddataset.php` writes
  `<input type=hidden name=qtype id=qtype value="choices" />`, and the render endpoint writes
  `<input type=radio ...>`. A DOM query is unaffected, but any audit that fetches raw HTML and
  greps for a quoted attribute (`name="qtype"`, `type="radio"`) matches nothing and condemns
  **every** question. Match `type=["']?radio` or split on `id=qtype value="`. Seen twice now:
  once auditing qtype ("all 7 questions failed"), once counting answer widgets in a render, where
  it briefly looked like 106 bank files had a broken `$choices[n]` -- `$choices[n]` is fine in
  multipart. Both were entirely the audit's own bug. When an audit fails 100% of a population,
  suspect the audit before the data.
- A **long loop inside one `js()` call blows the CDP read timeout while the work keeps running.**
  Fifteen sequential attach `fetch`es raised `Runtime.evaluate timed out` and all fifteen had
  landed. Never treat that timeout as failure and never blind-retry it -- re-read the server state
  first, or the retry doubles whatever the first pass already did. Keep a `js()` batch to a handful
  of round trips.
- **`browser-harness`'s `js()` cannot carry a backslash escape or a regex literal.** A backslash-n
  inside the expression comes back as `SyntaxError: Invalid or unexpected token`, and a `/(\d+)/` as
  `Invalid regular expression: missing /` -- and the reported line/column points at the escape, not
  at anything wrong with the page. Build literals with `String.fromCharCode(10)`, split on plain
  strings instead of matching, and do any real pattern work in Python.
- A question **filed after its neighbours attaches at the END** of the assessment, whatever order
  the manifest says. Reorder on `addquestions2.php`: each row carries a position `<select id="<n>">`
  (1-based, `onchange="moveitem2(n)"`). Set `.value` to the target position and dispatch `change`.
- **A `choices` question's options are shuffled per seed, so the source's `$answer` index cannot be
  replayed** -- index 0 in the source is not option 0 on the page. Worse, the randomized scenario is
  usually what decides *which* option is right (`$answer = $stype[$vi]`), so the key is not knowable
  from the file at all. Match the option by its rendered **label text**, and work the answer out from
  the rendered prompt. That is slower than replaying the source and it is the point: an answer
  derived from the same file that generated the key proves nothing, while solving the question
  independently is what actually tests the key.
- The **Submit buttons are siblings of `questionwrap<N>`, not children.** Scoping the query to the
  question wrapper finds none and reads as "this question has no submit control". Collect every
  button whose text starts `Submit Question` in document order and index by question number.
- Clicking **Add Resource** on the assessment settings form and reading `extreflinks[]` back **in the
  same `js()` call still returns 0 rows**; the row is there on the next call. Do not conclude the
  click failed and click again -- that leaves two rows.
- A question's result after submitting is `div.scoreresult` with class `correct` or `incorrect`,
  prepended inside the `.questionwrap` parent. An unsubmitted question has no such div at all, which
  makes it the reliable "did this actually submit" test. Reading `innerText` of the parent instead
  bleeds neighbouring questions' text and invents results for questions never answered.

- **MOM truncates the `description` field at 254 characters, silently.** No warning, no error; the
  saved value is simply cut mid-word, and an exact read-back compare then reports a false failure on
  a question that is otherwise fine. The description is how a question is found in a bank of
  hundreds, so one cut mid-sentence is a real loss. Keep every `NAME - DESCRIPTION:` line at or
  under 254 characters. **34 of 503 bank questions currently exceed it** (see REVIEW-NEEDED); the
  ones already filed are stored truncated.
- **A literal em dash in a STUDENT-VISIBLE string is rewritten to `--` on save**, per the rule
  above about the control field. The fix is not to tolerate the difference, it is to write
  `&mdash;` in the source: the entity survives the save byte for byte and renders identically. Four
  of 1.3's fifteen questions had literal dashes in solution-guide prose and shipped `--` to the
  student until this was corrected.
- **Re-saving a library question does NOT change an assessment attempt already in progress.** The
  attempt grades against the version stored when the question was generated, so a repaired key keeps
  scoring the old way and it looks like the fix never landed. Seen both ways on 1.3: after the
  re-save, the per-part "View Key" already showed the NEW answer while the submit still scored the
  OLD one. Click **Get a similar question** on that question to regenerate from the current library
  code, then answer the fresh version.
- **How to find WHICH part of a multipart failed, and what MOM thinks the answer is.** Each part's
  input carries `class="ansgrn"` or `class="ansred"`; a partial score gives no other clue, and
  reading the surrounding text finds the same marker for every part. Beside each input is a
  `button.keybtn` ("View Key") whose `aria-controls` names an element `ans<q>-<part>` holding MOM's
  stored key once clicked. That is the fastest way to separate "my answer was wrong" from "the
  question's key is wrong" -- on 1.3 it proved the key was.
- **Block addresses are positional and re-index after every move.** `<parent>-<n>` counts ALL items
  in the parent, not just the blocks, and `n` shifts whenever anything moves out. On 1.3 one block
  was `0-4`, then `0-3`, then `0-2` across three consecutive moves; a stale address answers
  `Location to insert at could not be found`. **Re-read the authoritative list before every move**
  from `moveitem.php?cid=&item=&block=` -- its `blockselect` options ARE the valid addresses. A
  wrong address can also fail *silently*: a guessed `addblock.php?block=0-1` created nothing and
  reported no error.
- `moveitem.php` needs **`csrfp-token` in the POST body** or it answers HTTP 200 with
  `Your submission has been blocked because we were unable to verify it came from a...`. Read the
  token off any course page (`CSRFP.setToken("...")` in an inline script). With it, the response is
  the literal `OK`.
- Mass Change / Blocks (`chgblocks.php`) is how to fix block visibility in bulk, but its `avail`
  radio is **0 = Hide, 1 = Show by Dates, 2 = Show Always**, and a newly created block defaults to
  *date-limited* using whatever dates the add form was pre-filled with. Left alone, a chapter block
  silently hides everything inside it after that date. Set `avail=2`; setting `1` looks like a
  change and keeps the dates.

- **After "Get a similar question", the PREVIOUS attempt's `div.scoreresult` is still in the DOM.**
  A poller that waits for that element to exist therefore returns instantly with the OLD verdict, so
  a question you just repaired reads as still failing. Cost three false "still 3 of 6" readings on
  1.4 while the fix had in fact landed. Capture the existing verdict text before submitting and wait
  for it to CHANGE, or reload and read the header total, which is never stale.
- A `string` answer holds several accepted phrasings **separated by the literal `" or "`**, so any
  alternative that itself contains the word "or" is silently shredded into fragments no student can
  type. `"order effects, such as practice or fatigue, from being tied to one condition or which
  session came first"` is four fragments, not two answers. Same trap for an HTML entity: `&ndash;`
  inside an answer must be typed literally to match. Grep the bank with a `" or "`-count check
  before trusting any string answer, and write alternatives that use "and" internally.
- An **`essay` part is auto-credited only when the source sets `$answer[n] = ""` AND
  `$scoremethod[n] = "takeanything"`.** Without them the part scores 0 and is held for manual
  grading, which quietly costs the student that part's marks in an otherwise auto-graded multipart.
  With them it awards full credit for literally any text. **Steve's rule (2026-08-08): homework
  carries no free response at all** — neither variant is acceptable, so convert essay parts to
  `choices`. Check `$anstypes` for `essay` before pushing any `hw` assignment.
---

## Standing requirements (Steve, 2026-08-08)

These are not learned-from-failure rules. They are requirements every push must satisfy, and both
happen AFTER the questions are attached but BEFORE the push is reported as done.

### Every assignment is out of 100 points

Balance the per-question points so they sum to exactly 100. Points live on the assessment
*instance*, not the library question, so this is set from the assessment's question list — the same
place order is set — and it does not touch the library or any other section using the same `qsetid`.

Distribute as evenly as the count allows and put the remainder on the heaviest questions, which are
normally the multipart ones. Eleven questions is `9 x 10 + 10 x 1 = 100`; fourteen is
`7 x 12 + 8 x 2 = 100`. State the split in the report so an unintended weighting is visible
immediately.

The total is a hard check, not an aim: read the points back off the saved form and assert they add
to 100. An assignment that totals 99 or 101 grades every student wrong by a fraction and nothing in
MOM complains.

### Preview and actually WORK every question before reporting done

Rendering a question is not testing it. A question can render perfectly, throw no error anywhere in
the HTML, pass `questionHealth`, and still be broken in the browser — a dropdown that never
populates, an answer box that rejects the correct value, a matching set whose options do not line
up with what the key expects.

So for **every** question in the assessment, in Teacher Preview:

1. **Fill the answer in** — actually type the value, choose the radio, set every dropdown.
2. **Submit it** and confirm it marks **correct**. A correct answer scored wrong is the single most
   damaging failure that reaches students, and it is invisible to every automated check we have.
3. **Look at the rendered page** — typesetting, layout, a prompt printed twice, a collapsed solution
   box, `$variable` showing literally.
4. For multipart, do this for **every part**, not just the first.

"No error was thrown" is not evidence. The evidence is: the answer was entered, it was submitted,
and it came back correct. Report per question what was entered and what came back. Any question not
actually filled and submitted is reported as **unverified**, never as passing.

### How to actually fill a `matching` question programmatically (proved on 1.1, 2026-08-08)

"Show All Answers" does **not** reveal or fill the key — it toggles the solution display. Derive the
key from source instead; it is deterministic:

- `$questions` holds the key terms. With `$noshuffle = "questions"` they render in source order.
- `$answers[i]` is the correct partner for term `i`. Entries past the terms are distractors.
- The controls are `select[name="qn<questionIndex>-<termIndex>"]`, and **option values are 0..7,
  which index the rendered description list directly** — no letter parsing needed.
- The rendered descriptions sit in the question container's `innerText` after the literal word
  `Description`, one per line and **without** `a.` / `b.` prefixes. Splitting on `^[a-h]\.` finds
  nothing and looks like the descriptions are missing.

Descriptions interpolate variables, so source text never equals rendered text. Convert each
`$answers[i]` to a pattern first — replace both `{$var}` and bare `$var` with `.+?`, escape the
rest — then match against the rendered lines to get the option value. Handle BOTH placeholder
spellings; handling only `{$var}` produces garbage like `(?:client\}` that matches nothing.

Set the value, then `dispatchEvent(new Event('change', {bubbles:true}))` — assigning `.value` alone
does not register with MOM.

One mechanical note: there is no "Submit All" control, so each question needs its own
**Submit Question** click. The race that used to be described here is now in the skill body: poll for
the question's `div.scoreresult` after each click, rather than re-reading the header total
afterwards, which tells you a question was dropped but not which one.

The header `Score: 100/100, Answered: 11/11` is the completion test. It is unreachable unless every
question renders AND grades correctly, so it subsumes the per-question checks.
- When several subagents write questions **in parallel**, give each one its OWN scratch directory for
  rendering and tiling. Handing them all the same path means one agent screenshots another's file and
  reports on a drawing that is not its own -- caught only because one agent noticed the tile showed a
  question it had not written. Its verification would otherwise have looked completely normal. The
  same applies to staging: `git add -- <dir>` while agents are still writing sweeps their half-finished
  files into the commit. Stage by explicit path.

---

## From the 2.1 push into master course 334437 (2026-08-09)

- **`copyfrom` copies the attempt settings but NOT the external-resource rows.** 2.1 copied from 1.1
  and came out with attempts, versions and penalties right and **zero** `extreflinks[]` rows, even
  though `showextrefs` was already ticked. So the Book link is *created*, not swapped, on a
  copied assessment — click **Add Resource** first. The skill's "check whether a row already holds
  the template's URL" step is still right, it just answers "no row" more often than expected.
- **Points are not a form field on `addquestions2.php`.** The per-question inputs are `input#pts-N`
  (0-based, no `name`, so they are never submitted), saved by the page's own `updatePts()` which
  diffs against `data-lastval` and calls `submitChanges()`. Set every `#pts-N`, then call
  `updatePts()`. Setting the values and clicking a submit button saves nothing, silently.
- **The header score is the points check.** With the 2% early-finish bonus on, a fully correct
  homework reads **102/100**, not 100/100 — `total x 1.02`. That is the cheapest proof the points
  sum to exactly 100, because it is arithmetic on the real total rather than a re-read of the fields.
- **The Save button on `moddataset.php` has `href="#"`, so the first `wait_for_load()` after clicking
  it can return before the navigation starts.** The URL then carries no `id=`, which reads as a
  failed save on a question that filed perfectly. One question in 14 hit this. Poll for the URL to
  carry `id=` instead of trusting a single read — and if it still looks failed, **check the qsetid
  sequence for a gap and read that id back** before retrying. A blind retry files a duplicate.
- **`js()` cannot carry a backslash, so question content cannot be passed as `JSON.stringify`** — PHP
  source is full of `\n` escapes and the call dies with `SyntaxError: Invalid or unexpected token`.
  Base64-encode each section in Python and decode in the page with `atob` + `TextDecoder`; the
  payload is then pure ASCII with no escapes. This replaces the skill's `JSON.stringify` recipe for
  any browser-harness-driven push.
- **A scrape of `addquestions2.php` for `qsetid=` returns the LIBRARY BROWSER too.** It reported
  "200 distinct qsetids on the assessment" for a 14-question assignment. The attached list is
  `input[name="curq[]"]` (in order), or count the `moveitem2` position selects. When an audit's
  answer is absurd, suspect the audit.
- **Student instructions in a master course must not name dates.** The skill says generate `intro`
  from the settings so the two agree, which is right for a teaching section. In a course that exists
  to be COPIED, a hardcoded date is wrong from the first copy onward, while MOM's own
  "Available ... until ..." line always tracks the assessment. Write the rules — attempts, penalties,
  bonus, late passes, when scores and answers appear — and let MOM render the dates.
- **Verifying against the drawing, not the prose, is what caught the randomizer detail.** For an SVG
  question, read the tick `<text>` elements' `y` coordinates and calibrate px-per-unit off the
  gridlines before reading any plotted point. Assuming the baseline instead produced a self-consistent
  but entirely wrong set of frequencies on the first attempt (it implied a category with frequency 0).

- **`updatePts()` saves by async AJAX, so navigating straight afterwards silently drops it.** The
  points read back as the OLD values with the new questions at 1 point each. Wait a few seconds
  after calling it, then reload and assert the total. The failure looks exactly like "the fields
  did not take".
- **Write `qid` back in the SAME step as filing the question, never as a later chore.** Three
  questions added to 2.1 were filed and attached but their qsetids never reached the manifest; the
  next push would have filed all three a second time and the copies would have drifted from that
  moment. It surfaced only because a re-push crashed on the missing key -- the silent path is a
  duplicate library entry. `question-library.json` exists to prevent exactly this and cannot help if
  nothing writes to it.
- **A canvas click needs the bounding rect re-read immediately before it.** Caching the rect across
  five clicks put every point after the first at the wrong height, because the page had scrolled;
  the recorded coordinates were self-consistent and completely wrong. Re-read the rect per click.

- **A stored `intro` describes the settings as they were WHEN IT WAS WRITTEN, so changing the
  settings silently makes it a lie.** After fixing chapter 1's four assessments the old intros still
  told students "questions are shown one at a time" and "late work is accepted with no penalty",
  both now false, and never mentioned the early-finish bonus that had just been switched on. Nothing
  flags it: the form and the text are separate fields. **Regenerate `intro` in the same pass as any
  settings change**, and do not skip it because text is already present -- "already present" was the
  exact check that would have shipped the wrong text to students.
- **Gradebook categories are per course and a fresh course has none.** `gbcategory` can only be set
  to an id that exists in THAT course, so a master course needs its categories created before any
  assessment can be filed under one. `gbsettings.php?cid=` -> `addcat()` per row, name them, Save
  Changes; the ids come back in the field names (`name[798368]`). A course copy carries them along.
- **Setting the per-question points fields one at a time saves only the FIRST one.**
  `addquestions2.php` saves points through `updatePts()` -> `submitChanges()`, and `submitChanges`
  opens with `if (inTransit) { return; }`. A loop that sets `#pts-N` and calls `updatePts()` on each
  pass therefore fires one real AJAX save -- carrying only the value set so far -- and every later
  call is dropped on the floor with no error. The read-back is unmistakable once you look:
  `[5,1,1,1,1,...]` where 16 values were sent. **Set every `#pts-N` first, then call `updatePts()`
  exactly once**, then wait for `inTransit` to clear before navigating -- `updatePts()` also takes
  no argument and iterates all the fields itself, so per-field calls were never right.
- **Always read the points back from a fresh page load.** The save is asynchronous and navigating too
  early drops it; a stale in-page read shows the values you typed rather than the values stored. The
  sum has to come back exactly 100.
- **The attach script's "N distinct qsetids on the assessment" line is not the attached list.** It
  scrapes every `qsetid=` in the page HTML, which on `addquestions2.php` includes the whole library
  browser -- it reported 200 for a 16-question assignment. The attached list is
  `input[name="curq[]"]`, in order.
- **`cid` in question-library.json is a breadcrumb, not a home.** It records which course's Manage
  Questions page a question was created through. MyOpenMath questions belong to a **user and a
  library**; a course only references them. Verified live 2026-08-09 across the whole index --
  questions filed from 334243 and from 334437, oldest to newest, all report the SAME library
  (`libs=1221`), Author `Huff,Steven`, and open editable from either course. Two entries with
  different `cid`s are not in two places.
  Reading it as a home produced a false report that the master's chapter 1 depended on another
  course's library; acting on that would have filed **15 duplicate library questions** -- the exact
  failure this index exists to prevent. **Check `libs` on `moddataset.php`, not the index, before
  ever concluding two questions live apart.**
- **What actually governs sharing a master course is Use Rights, and it is per question.** Every
  question in the bank is `userights = 0` (Private), uniformly. That, not any per-assignment
  property, is what decides whether a colleague can use the course after a copy or share.
- **`tb=b` appends to the BOTTOM of the block, so pushing sections out of order leaves them out of
  order.** Pushing 2.4 before 2.3 put 2.3 underneath it on the course page -- correct content,
  wrong outline, and the outline is what students navigate by. Fix without re-creating anything:
  `moveDialog('<block>','<itemid>')` opens `moveitem.php` in an iframe; set `#itemselect` to the
  item it should follow and call the iframe's `moveitem()`, which POSTs
  `{item, block, newblock, moveafter}` and reloads. The item ids are in the page's own
  `moveDialog('0-1-2','45310367')` calls, in rendered order.
- **A collapsed block renders none of its contents into the DOM.** A fresh chapter block comes up
  collapsed, so scraping `a[href*="assess2/?cid"]` off the course page reports its assessment as
  MISSING even though it exists, is attached and grades. Expand it (`toggleblock(event,'<n>','<blk>')`)
  before believing a course-page scrape. Nearly caused a "fix" for an assignment that was fine.
- **Navigating inside a loop leaves the browser somewhere else.** A sweep that visits each
  assessment ends on the last one, so a follow-up `js()` meant for the course page silently runs
  against `addquestions2.php` and answers about a page you are not on. Re-navigate before every
  page-level assertion.

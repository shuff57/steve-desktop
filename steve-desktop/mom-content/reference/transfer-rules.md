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
- **The assessment settings form is Vue, so a bare `.value` assignment is discarded on submit.**
  Every field on `addassessment2.php` carries `v-model` (`<input name="sdate" v-model="sdate">`).
  Setting `.value` updates the DOM and nothing else: Vue re-serialises from its own data on submit,
  so the field saves EMPTY or unchanged while the page looked right the whole time -- the same shape
  of failure as the CodeMirror trap. Set the value then dispatch the event Vue listens for:
  `input` for text, `change` for select/checkbox/radio, both with `{bubbles:true}`.
  Second consequence: **fetching that page and parsing it does not give you the values.** The HTML
  ships empty inputs and Vue fills them on mount, so `fetch(...).then(parse)` returns `''` for every
  field and reads as "this assessment has no dates". Navigate to it and read the live DOM instead.
  Verified 2026-08-09 creating 2.5, 2.6, 2.7.
- **Question ORDER is `itemarray`, and one `submitChanges()` persists it.** `generateOutput()` on
  `addquestions2.php` builds the saved order by walking the page's `itemarray` global directly, so
  reordering that array in place and calling `submitChanges()` once rewrites the outline -- no
  `moveitem.php` iframe needed for questions (that is for assessments within a block). Verified live
  on 2.5: two reused questions attached last landed at the bottom, and one reorder put all twelve
  into manifest slot order, confirmed on a fresh reload.
  `submitChanges()` opens with `if (inTransit) { return; }`, so a second call in the same page load
  is silently dropped. Save once, wait, reload, then check.
- **qsetids are GLOBAL across MyOpenMath, not per course.** The next id in the sequence usually
  belongs to a different teacher entirely -- 1874288 read `M10A [PreTeXt] (2.3) You Try` and 1874293
  `Finding limit of ln(sqrt(x^2)-x`, neither of them ours, both sitting inside a run of our own ids.
  This matters for the false-`FAILED` recovery: hunting the gap between two known ids is a sound way
  to find a question that filed but did not report, **but only because you then confirm it by
  reading `[name=description]` off `moddataset.php?id=<id>`**. Recovering by position alone would
  eventually attach a stranger's question to Steve's course.
- **A `numfunc` part needs its MathQuill field set, not its hidden input.** Reported by the builder
  on 2.7 slot 10 (`q1-z-score-compute`) and consistent with its final 12/12: setting the hidden
  input registered only the radio part, and the two z-score parts scored zero until the editable
  MathQuill field itself was set and the form resubmitted. This CONTRADICTS the older note that the
  hidden input can be set directly -- treat that as true only for single-part questions until
  someone re-measures it. Not personally verified end to end; the evidence is the score header.

---

## From the 4.1/4.2/4.3 pushes into master course 334437 (2026-08-14)

- **A marker splitter that strips the leading `//` from the first comment line of a comment-first
  `control` section CORRUPTS the question.** The first comment line becomes PHP code ("unquoted
  string Three.. treating as string"), `$anstypes` is never seen, and the question renders as
  "Error in question: missing $anstypes" with a single degraded control. The byte-exact read-back
  does NOT catch it: the stored value is compared against the same broken split, so it passes.
  Six of eleven 4.1 questions were filed this way and all six had to be re-saved. The splitter must
  take the control section VERBATIM between the `// === COMMON CONTROL ===` and
  `// === QUESTION TEXT ===` marker lines -- never strip comment prefixes. The same applies to
  `qtext` and `solution`. Symptom to recognise: a question that renders with one control where the
  source has three, or "Error in question: missing $anstypes" on `testquestion2.php`.
- **`testquestion2.php?qsetid=<id>` is the cheap render check after every save.** It renders the
  library question standalone with a fresh seed and reports PHP warnings/errors in the page head
  ("Caught warning in the question code: ..."). Run it for every filed question before attaching;
  it catches the splitter corruption, the `$scenario`-before-`$scenarios` ordering bug, and any
  other control-code defect that a render in the assessment would show only as a degraded widget.
- **A pre-existing bank defect: `$scenario = $scenarios[$i]` before `$scenarios = array(...)`**
  (pre-frq-grade-an-ev-decision.php). The scenario renders empty and the question still saves and
  attaches. Fixed at the source and re-saved. Grep for `$scenario = $scenarios` appearing before
  the array definition when a pre-FRQ's scenario is blank.
- **A pre-existing bank defect: `$validChoices` inverted in q2-legitimacy-check.php.** Legit tables
  were keyed "No" and broken ones "Yes" -- every seed graded the opposite of correct. The
  in-progress attempt kept grading the OLD key after the re-save ("Re-saving a library question
  does NOT change an assessment attempt already in progress"), so the question had to be
  regenerated with "Get a similar question" and re-answered. The 102/100 header was only reachable
  after that.
- **The Teacher Preview header lags the AJAX submits.** After a submit, the `.assess-header` can
  read the old count for several seconds while the per-question `div.scoreresult` already shows
  the new score. Read the per-question scoreresults (or reload) before concluding a submit was
  dropped -- a "dropped" submit that actually landed shows up as "Your answers have not changed
  since your last submission" on the next click, which is the real tell.
- **Question numbers in the preview carry Unicode format markers** (`⁨3⁩` = U+2068/U+2069), so a
  JS match on `Question 3` fails silently. Strip `[\u2068\u2069\u200b\u200c\u200d]` before
  matching button text.
- **MathQuill `latex()` writes do not always sync to the hidden input on re-submit.** On 4.2 Q7
  the visible field showed the new value while the hidden input kept the old one, and the resubmit
  graded the old answer. After any re-fill, read the hidden input's value back and set it directly
  if it did not change.
- **A question's part inputs are numbered by the ASSESSMENT, not the source.** 4.2 Q2's parts were
  `qn2000` (plain text) / `qn2001` / `qn2002` while the source's `$answerbox[0..2]` suggested
  MathQuill for all three. Read the live DOM's input list per question before filling; a
  `numfunc` part can render as a plain text input when the source declares it otherwise.
- **The `updatePts()` trap is the reverse of the documented one: setting `data-lastval` yourself
  makes it see NO change.** `updatePts()` diffs each field against its `data-lastval` attribute;
  if you set both, `haschg` stays false and nothing saves. Set only `.value`, then call
  `updatePts()` once. (The documented "set every #pts-N then call updatePts() once" is right; the
  extra attribute write is the new failure mode.)
- **The assessment settings form's Save is `input[type=submit]` with value "Save Changes"**, not a
  button. A click on a button named "Save" on that page does nothing. (On `moddataset.php` the
  real Save IS a button -- the two pages differ.)
- **`extreflinks[]` rows are plain HTML, not Vue** -- setting `.value` + dispatching `input` and
  `change` works, but the row must be saved via the form's real submit (see previous rule).

---

## From the ch4 filing push and the rename survey (2026-08-15/16)

- **`addassessment2.php?aid=<aid>&cid=<cid>` is NOT the edit form. It silently returns a blank
  "Add Assessment" page.** The `aid` parameter is ignored entirely, every field comes back empty,
  and the form action carries `block=` with no `id=`. Saving from there **creates a duplicate
  assessment** instead of editing the one you meant, and nothing on the page says so -- the only
  tell is the breadcrumb reading `Add Assessment` rather than `Modify Assessment`. The working
  URL uses **`id=`**: `addassessment2.php?id=<aid>&cid=<cid>`. Verified live on aid 23444263:
  `?aid=` gave an empty form, `?id=` gave `name = "7.1 The Central Limit Theorem for Sample
  Means (Averages)"`. **`block=` is optional** -- the course page's own links include it, but the
  form loads and reads back correctly without it, so no block-path lookup is needed to rename an
  assessment. Blocks are a different page: `addblock.php?cid=<cid>&id=<blockpath>`, field
  `[name=title]`, and that one IS plain HTML so `fetch` + parse reads it fine.
- **Any save of the assessment settings form silently clears the passcode** unless you re-send it.
  `assmpassword` is `<input type=password>`, so a plain form submit posts whatever is in the box --
  and nothing repopulates it for you. **But MOM DOES echo the stored value back on load**, which
  makes this recoverable: aid 23444268 (Chapter 7 Group Test) read back `ZDV4WC` on load. An empty
  read means the assessment genuinely has no passcode, NOT that the value is hidden -- 23444263 and
  23444270 both read empty because neither is passcoded. So the safe pattern is capture-then-restore:
  read `assmpassword` BEFORE editing, and either re-set it in the same save or re-set and read it
  back afterwards. Only the Group Tests are passcoded.
- **Reading the settings form requires NAVIGATION, not `fetch`.** `fetch(addassessment2.php...)`
  + `DOMParser` returns empty for `name`, `intro` and `extreflinks[]` alike -- the values are
  filled in on mount. A sweep built on fetch reports "no Book link" for every assessment in the
  course, which reads as a finding rather than as a bug in the sweep. Navigate, and poll for
  `[name=name]` to become non-empty before reading anything.
- **The attach GET is NOT idempotent.**
  `modquestion2.php?qsetid=&cid=&aid=&from=addq&process=true&usedef=true` adds a **second
  instance** of the same library question if it is already on the assessment -- one `qsetid`, two
  rows, points counted twice. Re-running an already-filed slot to re-verify it is enough to
  trigger this (hit 2026-08-15 on 4.4, which went to 11 questions). Always read
  `input[name="curq[]"]` first and skip the attach if the qsetid is present. To remove the extra,
  splice it out of the page's `itemarray` by its INSTANCE id and call `submitChanges()` once,
  then reload and confirm.
- **MOM HTML-escapes bare `<` and `>` in `qtext` on save**, so a byte-exact read-back raises a
  FALSE failure on any question with inequalities in a math span: `` `P(1 < x < 4) =` `` is stored
  as `` `P(1 &lt; x &lt; 4) =` ``. Growth is exactly 3 chars per angle character (+9 on a question
  with three), and a screenshot confirms the math still typesets correctly -- the entity is a
  storage form, not what the student sees. Normalise `&lt;`/`&gt;` on BOTH sides before comparing,
  the same way the em dash is normalised. Do not "fix" the source.
- **Two `.php` marker dialects exist in the bank and a strict splitter rejects the older one.**
  Besides `// === COMMON CONTROL ===` / `// === ANSWER ===`, older files carry
  `// === COMMON CONTROL (paste into Common Control) ===` (a parenthetical the marker regex must
  tolerate) and separate `qtext` from the worked solution with a bare `///` line instead of an
  `ANSWER` marker. Of 209 questions surveyed: 201 standard, 5 `///`-separated, 3 with no solution
  section at all. Match `COMMON CONTROL\b.*?===`, and when no `ANSWER` marker follows
  `QUESTION TEXT`, split the tail on a standalone `///`. A question with genuinely no solution
  section is a content gap, not a parse failure -- file it with an empty `solution` and note it.
- **The Book resource links were generated from MOM's chapter NUMBERS and are dead.** They carry
  the correct topic slug but the stale number: aid 23444263 points at
  `.../chapter-7-the-central-limit-theorem/7.1_...` while the book has
  `chapter-6-the-central-limit-theorem/6.1_...`, and aid 23444270 points at
  `.../chapter-8-confidence-intervals/8.1_...` while the book has `chapter-7-confidence-intervals`.
  Both 404. Every assessment from MOM ch7 up is affected, and the correction is the same -1 shift
  as the chapter renumber, so do the links in the same pass as the renames.
- **The stored `intro` on these assessments hardcodes dates** ("Opens 01/04/2027 at 10:11 am and
  is due 01/06/2027..."), which contradicts the standing rule that a master-course intro must not
  name dates -- the master is undated and exists to be copied. Pre-existing on at least 23444263
  and 23444270. Regenerate the intro whenever touching these assessments.
- **A relative `fetch('/course/...')` silently queries whatever origin the tab is on.** After a
  pass that verifies Book links by navigating to oerbookshelf.app, the browser is left there, and
  the next audit's `fetch('/course/chgassessments2.php?cid=...')` hits **oerbookshelf.app** instead
  of MyOpenMath. It does not throw: it returns a page that parses to zero rows, so the audit
  reports "0 assessments" or "not found in any course" -- a clean, confident, entirely false
  negative. Measured 2026-08-16: an orphan-assessment check reported `found:false` for all four
  courses, when the item was present in all four. The documented rule ("navigating inside a loop
  leaves the browser somewhere else") is the same trap, one step removed. Fix: open every sweep
  with an origin assert --
  `if (!/myopenmath\.com/.test(location.host)) return {abort:'wrong origin', host:location.host};`
  -- and treat a total of 0 as a bug in the sweep, not a finding about the course.
- **An expired session returns the login page with HTTP 200.** Not 401, not 302 to a login URL --
  a plain 200 carrying the login form. So `if (!r.ok) throw` never fires, `DOMParser` parses it
  happily, and every selector finds nothing: an assessment sweep reports 0 assessments, a roster
  check reports 0 students, a link audit reports no Book links. All clean, all well-formed, all
  wrong. The tell is **byte-identical response lengths across two different `cid`s** (6799 bytes
  for both 334437 and 339304, measured 2026-08-16). Assert on page CONTENT, never on status:
  `/action=newuser|Forgot your password|Register as a new student/i` means logged out; the inverse
  check is that a real course page contains `Course Messages` or `chgassessments2`. Re-authenticate
  before drawing any conclusion from a sweep that came back empty.
- **The roster is `listusers.php?cid=<id>`. There is no `roster.php` -- it 404s.** A sweep that
  guesses the filename parses the 404 body, finds no user links, and reports **0 students for every
  course** -- including ones known to hold ~27 and ~29. Nothing errors. Same failure family as the
  two above; the sanity floor that catches it is "a uniform zero across courses I know are
  non-empty is impossible", applied to the RESULT rather than to whether the call succeeded. Take
  the URL off the course page's own nav instead of guessing it.
  Counting: student rows link out with `uid=`, so `new Set` over `a[href*="uid="]` is the count.
  Measured 2026-08-16 -- Global 0, P3 27, P4 **0**, P7 29, with body sizes corroborating (~11.8KB
  for the empty rosters vs ~27-28KB for the full ones). **Keep a known non-empty course in every
  such sweep as a control**: it is what distinguishes "this course is empty" from "my parser is
  broken", and the two are indistinguishable without it.

> Three sweeps in one session returned confident, well-formed, entirely wrong answers: a relative
> fetch against the wrong origin, a 404 parsed as data, and a 200 login page parsed as data. **None
> was caught by the call reporting failure; every one was caught by asking whether the RESULT was
> plausible.** Build the floor into the sweep -- an origin assert, a logged-out assert, and an
> expected-range check on the count -- and return `{abort: ...}` rather than a number when one trips.

## `assess/index.php?cid=` 404s — and the 404 reads as an empty course (2026-08-24)

Filling the empty 1.3 in the three live Stats sections. The first recon reported
**"expected exactly one 1.3, found 0"** for a course that had one all along.

- `https://www.myopenmath.com/assess/index.php?cid=<cid>` returns Apache's **404 Not Found**
  page. It is a normal 200-shaped navigation as far as Playwright is concerned, the
  dead-session guard passes (no login form on it), and a scrape for
  `a[href*="addassessment2.php?id="]` finds **zero links** — which is indistinguishable from a
  course with no assessments. Nothing anywhere says "not found" unless you read `document.title`.
- The working listing is `course/chgassessments2.php?cid=<cid>` (what `_audit-all.mjs` already
  used): one checkbox per assessment, `value` = aid, label text = name. Filter to `/^\d{6,}$/` —
  the mass-change panel contributes stray boxes valued `on` or a small integer.
- **Every page in this area is under `/course/`, not `/assess/`**: `chgassessments2.php`,
  `addassessment2.php`, `addquestions2.php`, `modquestion2.php`, `moddataset.php`. The
  skill body writes them bare, which reads as `/assess/` if you have just come from `assess2/`.
- `mom-live.mjs` carried the dead URL inside `readAssessmentDates()`. Now fixed: a new
  exported `listAssessments(page, cid)` owns the listing and `readAssessmentDates` calls it.

Recognise it by: a course you know is populated reporting zero assessments, or a named
assessment "not found". Assert on `document.title` / body text before believing an empty list.

## Attaching a filed assignment into a live teaching section (2026-08-24)

`mom-transfer` says pushes go to the master and Steve copies into sections by hand. When a
section's assessment stub exists but is **empty**, the cheap fix is not a delete-and-recopy:

- An assessment does not contain questions, it **points at library ones**, so an empty stub is
  not damaged — it is pointing at nothing. Attaching the master's own qsetids fills it with
  literally the same library entries, not a copy that could drift.
- Attaching is a plain GET per question:
  `course/modquestion2.php?qsetid=<qid>&cid=<cid>&aid=<aid>&from=addq&process=true&usedef=true`
- **Do not delete and re-copy from the master.** The master is deliberately undated, so the copy
  would overwrite the section's real bell-schedule dates (the ones repaired 2026-08-21) with
  nothing. Attaching never opens the settings form, so dates are untouched by construction.
- Guard the write on `No Questions currently in assessment` — refuse to attach on top of
  existing questions rather than silently doubling an assignment.
- Content re-verification is NOT needed for questions already live and verified in the master:
  same qsetid means same question, not a copy. What IS new per section is the attach and the
  per-instance points, so verify those directly — re-read every `points` value from a fresh
  navigation and assert the total.

Done for 1.3 in period 3 (339304/23440092), period 4 (334243/23114376) and period 7
(339625/23464599): 16 questions each, 15x6 + 1x10 = 100, all three rendering 16 questions in
Teacher Preview with zero `Eeek!`, zero marker leak and zero literal `$vars`.

## The three live sections are shells past 1.2 (measured 2026-08-24)

Swept every assessment in all three for attached-question count:

| section | cid | assessments | filled | empty |
|---|---|---|---|---|
| period 3 | 339304 | 92 | 4 | 88 |
| period 4 | 334243 | 92 | 3 | 89 |
| period 7 | 339625 | 92 | 3 | 89 |

All 92 stubs exist in each, with dates and settings, and content was only ever filled for
`Entering Answers`, 1.1 and 1.2 (period 3 also had 1.3 as of this run, before 4 and 7 were
done). This is unfinished authoring, not a broken copy — the stub structure is fine everywhere.

## Drive MOM with `playwright-cli`, not a hand-rolled CDP connect (Steve, 2026-08-31)

Every push before this one connected Playwright-over-CDP to a browser somebody had already
started and logged in by hand — `_push/mom.mjs` (hardcoded port 9223) or `_push/mom-live.mjs`
(the app's own dynamic port). That is the step that kept being missing: on 2026-08-31 nothing
on the box was listening on 9222-9242 and no Chrome was running with
`--remote-debugging-port`, so a transfer could not start at all. `mom.mjs` had also been
carrying a stale `C:/Users/shuff/` in its `createRequire` path since a username change, which
throws `MODULE_NOT_FOUND` the moment you connect — fixed the same day.

**Use the `playwright-cli` skill instead. It owns the browser, so there is nothing to start
first.** Installed globally on this box: `npm install -g @playwright/cli@latest` (0.1.18).

```bash
node _push/mom-session.mjs                 # ensure a live session; opens the login if needed
playwright-cli -s=mom goto "https://www.myopenmath.com/course/course.php?cid=334437"
playwright-cli -s=mom close                # keeps the profile; delete-data destroys it
```

- **`--persistent` does NOT save the MyOpenMath login. Corrected 2026-08-31 - the earlier
  version of this section claimed it did.** MOM issues `PHPSESSID` with `expires: -1`, a
  *session* cookie: it lives in the browser's memory and is never written to the profile, so
  there is nothing on disk for a restart to reload. The login form has no "remember me" either
  (`username`, `password`, and passkey fields, nothing else). The profile at
  `%LOCALAPPDATA%\ms-playwright\daemon\<hash>\ud-mom-chrome` is real and does persist
  history, prefs and non-session cookies - just not the one that matters.

  How the wrong claim survived: the browser was never closed during the session that made it,
  so "it persists" was inferred from the profile directory existing rather than measured. One
  line settles it, and it is cheap enough to run whenever the question comes up:

  ```js
  (await page.context().cookies('https://www.myopenmath.com/'))
    .find(c => c.name === 'PHPSESSID').expires        // -1 => memory only
  ```

- **So expect to log in once per work session, and check BEFORE a long run.**
  `_push/mom-session.mjs` does exactly that: it reports `live`, or opens the session headed at
  the login page and exits non-zero so a push cannot start against a dead session. A `SESSION
  DEAD` thrown twenty questions into a push is correct behaviour but expensive; this moves the
  same discovery to second zero. `--wait` blocks until the login completes.
- `state-save`/`state-load` would survive a restart, but it writes a live session token to a
  file and MOM expires sessions server-side regardless, so it is not a real fix - it just moves
  where the staleness shows up. Not used.
- **`--headed` is required for the login itself.** Without it the session opens headless and
  the browser Steve is supposed to type into does not exist on screen — the first attempt on
  2026-08-31 did exactly this and read as a working session serving the login form.
- **On Windows a MOM URL must be quoted, and `&` still bites.** `cmd.exe`/PowerShell treat `&`
  as a command separator. Quote every URL, and in PowerShell use `playwright-cli --% goto
  "...?a=1&b=2"`. Every MOM content URL in this file has at least two query params.
- The session-dead check is unchanged and still matters: a dead session serves the LOGIN FORM
  at a content URL. Test for `input[name=username]` **and** a password field **and** no
  `Log Out` — never `input[type=password]` alone, which false-fires on the `assmpassword`
  passcode field of the assessment settings form.
- `.playwright-cli/` (snapshots, console logs, screenshots) is written into whatever directory
  the command runs from. It is gitignored at the repo root as of this date.

`mom.mjs` and `mom-live.mjs` still work and are not deleted — they remain the right tool for a
scripted batch write like the 92-assessment retime, where driving one page at a time through a
CLI would be absurd. The rule is about how a *transfer* starts: reach for `playwright-cli`
first, and only fall back to a CDP connect when a run is genuinely a loop over hundreds of
pages.

## 1.5 lab into the three sections — and the manifest that lied (2026-08-31)

Master `334437` aid **23444239**, sixteen questions, qsetids `1890450` and `1890458`-`1890472`,
15x6 + 1x10 = 100.

| section | cid | aid | state found | action |
|---|---|---|---|---|
| period 3 | 339304 | 23942582 | already 16/16, 100 pts | none |
| period 4 | 334243 | 23114378 | `No Questions currently in assessment` | attached 16, set points, 100 |
| period 7 | 339625 | 23948827 | already 16/16, 100 pts | none |

**The manifest said none of this had ever happened.** `target.aid` was `null`, all sixteen `qid`s
were `null`, and `question-library.json` had zero `data-collection` rows — while the master and two
of the three sections were fully built. The repo record was three pushes behind reality, and a run
that trusted it would have filed all sixteen questions a **second** time. That is the one failure
that does not undo cleanly.

So: **the write-back is not bookkeeping, it is the duplicate-filing guard.** Never treat a `null`
qid as proof a question is unfiled — it is a prompt to go look. Read the assessment back off
`addquestions2.php` first; that page is the only source of truth about what is filed. All sixteen
ids, `target.aid`, the sixteen `question-library.json` rows and a `sync-index.ts` run were written
on 2026-08-31, and `--check` now exits 0. Their `filed` date is recorded as the day they were read
back off MOM, not the (unknown) day they were actually filed.

**`usedef=true` on the attach gives the LIBRARY default, not the master's per-instance points.**
All sixteen came into period 4 at **1 point each, total 16**, and the attach reported success for
every one. Points are per-instance by design, so they cannot travel with a qsetid — step 4 is not
optional on a section attach either. Read the instance ids off the page rather than assuming they
are consecutive: period 4's ran `354238153`-`354238169` but **skipped `354238163`**.

`points` on `modquestion2.php` is a hidden input and the page is **not** Vue — there are two visible
`input[type=submit][value="Save Settings"]` buttons and either one commits. Set the value through
the native setter, dispatch `input`+`change`, click Save, then re-read from a **fresh** navigation:
period 4 read back 16/16 at 6,6,...,6,10 = 100.

Dates were untouched by construction (neither the attach nor the points form opens the settings
form) and read back intact afterwards: P3 08/28 10:11am -> 09/01 10:11am, P4 08/31 10:11am ->
09/02 10:11am, P7 08/28 2:03pm -> 09/01 2:03pm.

**Two defects found here that the attach did not cause, both fixed on Steve's say-so:**

- **Period 4's lab closed 09/02/2026 at 10:11 am, and 09/02/2026 is a Wednesday.** The 3-or-4
  block starts at **10:33 am** on Wednesdays, so it shut 22 minutes before the period it is due
  at the start of, the same class of defect as the 44 found 2026-08-21. Now `10:33 am`, read
  back from a fresh navigation.
- **Period 7's lab carried the 1.1 Book link**
  (`.../1.1_definitions_of_statistics_probability_and_key_terms`): the exact "previous section's
  link left on a new assignment" failure this file warns about, and the reason it warns, since
  nobody checks a link that looks present. Master, P3 and P4 all had the right one. All three
  now read `.../1.5_data_collection_experiment` (URL confirmed 200).

`addassessment2.php` carries exactly one visible `input[type=submit][value="Save Changes"]`.
Neither `etime` nor `extreflinks[]` exposed a `v-model` attribute, but the native-setter plus
`input`/`change` recipe was used anyway and both wrote correctly. **Guard the write on the value
you expect to replace**: both fixes were written to abort rather than write if the pre-read did
not match, which is what stops a rerun stamping over a hand-correction.

## Chapter 1 Group / Practice / Individual Tests do not exist yet (measured 2026-08-31)

All **twelve** stubs, three kinds across master and all three sections, report
`No Questions currently in assessment`:

| | master 334437 | period 3 339304 | period 4 334243 | period 7 339625 |
|---|---|---|---|---|
| Chapter 1 Practice Test | 23444240 | 23440095 | 23115818 | 23464602 |
| Chapter 1 Group Test | 23444241 | 23440096 | 23114379 | 23464603 |
| Chapter 1 Individual Test | 23444242 | 23440097 | 23114380 | 23464604 |

And the `introduction-to-stats-sh` book has **no `group/` or `practice/` directory at all**, only
`hw/` and `lab/`. So there is nothing to transfer: this is authoring work under `mom-question`,
not a push. The `group`/`practice`/`ind` presets in `assessment-presets.json` describe how each is
selected; the older `introduction-to-stats` book's `college/` and `high-school/` tracks hold
group/practice manifests but on a different chapter alignment, so they are precedent, not source.

## Chapter 1 Group + Practice Tests pushed to master (2026-08-31)

Both were assembled from questions already in the library, then pushed into the stubs that already
existed. Steve's calls this day: **real hand-graded FRQs, not pre-FRQs**, and the practice test
covers the **same fourteen sub-topics as the group test with different questions** - zero shared
qsetids, verified programmatically before the push.

| | aid | questions | points | FRQ slots |
|---|---|---|---|---|
| Chapter 1 Group Test | 23444241 | 17 | 14x6 + 6 + 5 + 5 = 100 | 15-17 |
| Chapter 1 Practice Test | 23444240 | 22 | 19x4 + 3x8 = 100 | 20-22 |

Six FRQs filed (all byte-exact on all five fields, verified from a fresh navigation):
`1893145` q1-identifying-sampling-bias, `1893146` q2-classification-and-justification,
`1893148` q3-stratified-vs-simple-random-sampling, `1893149` q6-frequency-distribution-analysis,
`1893150` q10-sampling-design-critique, `1893151` q12-reading-a-study-frq.

### The FRQ sources use a DIFFERENT marker layout than the rest of the bank

`questions/frq/**` predates the five-marker convention. It carries only the first three markers and
then splits the remaining two fields with bare comment lines:

```
// === NAME - DESCRIPTION: ... ===
// === SET QUESTION TYPE TO: ... ===
// === COMMON CONTROL (paste into Common Control) ===
   ...control...
//question text          <- delimiter, NOT a comment in the control
   $questiontext
   $answerbox[0]
///                      <- delimiter
   $rubricanswerbutton
```

A splitter that only knows `// === QUESTION TEXT ===` and `// === ANSWER ===` finds neither, and
the natural failure is filing the whole file as `control` with **empty qtext and solution** - which
saves clean and renders `Eeek!`. All six of these files matched the layout exactly (`qtext` 27
bytes, `solution` 19 bytes, every time), so test for both layouts rather than assuming.

### Search the library BEFORE filing an FRQ

Archive manifests reference these same FRQ files against a different course, so a copy could already
have existed. `addquestions2.php` has a `#search` input: fill it, press Enter, read the
`moddataset` rows. None of the six was there (only the two pre-FRQ **mirrors**, `1874125` and
`1874244`, which are different questions). Two minutes of searching is the whole defence against the
one failure that does not undo cleanly.

### The post-save qsetid scrape is flaky - fall back to a search, never to a retry

Filing q3 reported `no qsetid in response` and the run stopped. **The save had actually succeeded**
(`1893148`); only the `/qsetid=(\d+)/` scrape of `document.body.innerHTML` came back empty that one
time. Blind-retrying would have filed a second copy. Stopping was correct; the fix is a fallback
that looks the question up by description in the library search before giving up.

Also: a gap in the id sequence proves nothing. `1893147` sits between two of ours and is somebody
else's question ("Acceleration, concept of linear acceleration") - MOM library ids are allocated
globally across all authors, not per course.

### Stub settings were three-quarters right, which is the dangerous amount

The pre-existing stubs already carried a passcode and the right attempts/versions/category, so they
read as configured. Against `intro-stats-assessment-settings.md` the group test was still wrong on
**three** fields - no time limit (should be 89), a 2% early-finish bonus (tests get none) and late
passes set to "Up to 1" (tests allow none) - and the practice test's **Book link still pointed at
1.1**. All four fixed and read back fresh; both now point at
`.../chapter-1-sampling-and-data` (a chapter test has no single section).

Every one of those fields is `offsetParent === null` on load - they sit in collapsed panels. Being
invisible does not stop them submitting, and does not stop the native-setter + `input`/`change`
recipe writing them. Do not skip a field because a snapshot cannot see it.

**Left deliberately: `timelimit` is 89, the regular-day period length.** Period 4's group test falls
on Wed 09/02, where the 3-or-4 block runs 81 minutes. That is a copy-time correction, like the dates.

## `intro` and `summary` are TinyMCE - and existing-but-not-`initialized` loses the write

Third editor on this codebase, third variant of the same trap. `moddataset.php` wraps `control`/
`qtext`/`solution` in **CodeMirror**; `addassessment2.php` is **Vue** for its plain fields and wraps
`intro` and `summary` in **TinyMCE 8**. Each needs a different write, and all three fail the same
way: the save reports success and stores the old value or nothing.

```js
await page.waitForFunction(() => {
  const e = window.tinymce?.get?.('intro');
  return e && e.initialized;            // NOT merely `e` - see below
}, {timeout: 30000});
await page.evaluate(html => {
  const ed = window.tinymce.get('intro');
  ed.setContent(html);                  // NOT textarea.value =
  ed.save();                            // push the editor doc down into the textarea
  document.querySelector('input[type=submit][value="Save Changes"]').click();
}, html);
```

**`tinymce.get('intro')` returns an editor before that editor is usable.** Waiting only for the
handle to exist, then calling `setContent`, silently loses the content: the POST goes out with
`intro=` empty and the stored value is wiped. That is worse than a no-op - on 2026-08-31 it
**destroyed** the group test's existing 508-character intro before the right recipe was found.
`e.initialized` is the flag that distinguishes them, and `waitUntil:'load'` (not
`'domcontentloaded'`) gives it a chance to become true.

Two dead ends, so nobody re-walks them:

- **Setting `textarea.value` through the native setter with `input`/`change` does not work**, even
  though the same recipe is correct for every Vue-bound field on that form. TinyMCE re-syncs the
  textarea from its own document on submit and overwrites you - the field keeps its OLD value, which
  reads as "the save silently ignored my change".
- **Writing the value and clicking submit in the same tick makes the POST look right and changes
  nothing.** Captured via `page.on('request')`, the body carried `intro=<p>PROBE-BETA</p>` and the
  stored value was still empty, because the editor itself had never initialized. A correct-looking
  POST body is not evidence the write will land.

Diagnostics worth reusing: `page.on('request', r => r.postData())` filtered to the form action shows
exactly what left the browser, and submitting a **canary** to three fields at once (a TinyMCE field,
a second TinyMCE field, and a plain input) is what separated "this one field is being dropped" from
"the whole save is failing". `groupmax` makes a good inert canary on a non-group assessment - the
server normalises it back, which is itself a useful signal that the save ran.

`getContent()` returns `&mdash;` as the entity, and `introOk` string-compares false purely on
whitespace between `<p>` blocks. Compare on normalised whitespace, or on paragraph and `<strong>`
counts, rather than byte-exactly.

### Intros must not hard-code dates or clock times

The `hw` template's intro ends *"The opening and due dates are shown with the assignment on the
course page"* and names no date. The 1.5 lab's and the group test's original intros both hard-coded
`08/31/2026` and `10:11 am`/`11:40 am` - and the group test's was quoting a date that was not even
its own class day, plus a close time that was about to change. Dates and clock times are **copy-time**
values, and period 7 runs `2:03-3:32` while Wednesdays shift the whole block, so any hard-coded time
is wrong for two of the three sections the moment it is copied. Describe the settings; point at the
course page for the schedule.

### Two more spec gaps found by auditing rather than assuming (2026-08-31)

Both on assessments that had already passed a content check and looked configured:

- **Chapter 1 Practice Test had NO `intro` at all** (0 characters), against
  `intro-stats-assessment-settings.md`'s rule that student instructions are generated from the
  settings. Now 1 105 characters covering attempts, regens, scoring, the three written-response
  slots, the 2% bonus and the late pass.
- **Chapter 1 Group Test closed at `11:40 am`, exactly period end, with `allowovertime` OFF.** The
  spec is period end **+7** *because* overtime is granted: a 10:11 start plus an 89-minute timer
  lands on 11:40, so the window shut at the instant the timer expired and any late start or overtime
  was unusable. Both halves were wrong and both are fixed - `11:47 am`, `allowovertime` on. Same
  defect class as the 44 found 2026-08-21.

Read every row of the settings table off the live form. Three-quarters-right is the dangerous
amount: the right passcode and category made this pair look done.

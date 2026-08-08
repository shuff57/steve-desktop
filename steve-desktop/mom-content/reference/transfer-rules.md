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

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
  `—` → `--` before comparing. It is only cosmetic while the em dashes sit in `//` comments; one
  inside a student-visible string genuinely changes what the student reads, so check where they are
  rather than waving the difference through.
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
  1-based (`0-6-1` is the first block inside `0-6`). A collapsed parent does not render its children
  into the course page DOM at all, so "the block I just made is missing" usually means it is there
  and hidden — check `chgblocks.php`, which lists the whole tree.
- Moving an item between blocks is not on any settings form. It is `moveitem.php`, driven by the
  page's `moveitem()`: POST `{item, block, newblock, moveafter}` and it answers the literal string
  `OK`. `item` is the COURSE-ITEM id from `moveDialog('<block>','<item>')`, not the assessment's
  `aid` — 1.1 is item `45020296` and aid `23108651`.
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

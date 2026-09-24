# SPEC-CH1-REVIEW-PUSH

**Book:** integrated-math-1
**Skill:** mom-transfer

## Scope

Push `mom-content/books/integrated-math-1/practice/chapter-1-review-sub-day.json` (25 brand-new
questions, all `qid: null`, all `file_path` set) into the live master course, then verify by
answering every part in Teacher Preview. The manifest is the source of truth for slot order and
points — read it fresh before doing anything.

- **Course (question home / master):** cid `334437`.
- **Assignment:** does not exist yet. Creating it is step 1 of the flow (`addassessment2.php` with
  no existing aid — this IS the "filing into a course with no assessments yet" case only in the
  sense that *this assignment* is new; the course itself already has assessments).
- **copyfrom:** `presets.practice.template_aid` = `23258795` (from
  `mom-content/reference/assessment-presets.json`). Leave `copysummary` / `copyinstr` /
  `copydates` / `copyendmsg` **unchecked** — they default unchecked, keep them that way.
- **Dates:** leave undated. Every assessment in the master course (334437) stays undated per
  `assessment-presets.json`'s `question_home._dates` — do not set `sdate`/`edate` here even though
  this assignment is meant for Fri 2026-09-25. That date is Steve's own copy-time step into his
  teaching section, done manually in MyOpenMath after this push, and is out of scope for this spec.
  Say this plainly in your report so it is not silently missed.
- **Name:** "Chapter 1 Review (Sub Day)".
- **Gradebook category:** HW (`798368`) per the master course's existing categories, matching what
  `copyfrom` on the hw/practice template already sets — confirm after copy, do not hand-set unless
  the copy did not carry it.

## Do NOT touch these local files — reserve them for the write-back step after your report is verified

Do not edit `chapter-1-review-sub-day.json`, `reference/question-library.json`, or any file in
`mom-content/questions/im1/`. Read them for source content only. Report every qid/instance-id/
points value you produce back in your reply — the write-back into the manifest and the index
happens after your report is checked against the live course, not by you.

## Target state (25 slots, 100 points total — verify this sums to 100 before you finish)

All 25 questions are new — every one needs `moddataset.php` (no existing `id=`) to file into the
library, then `modquestion2.php` to attach, then points set to exactly 4 on each instance. Slot
order in the assessment must match the manifest's slot order 1-25.

| Slot | File | Points |
|---|---|---|
| 1 | questions/im1/order-of-operations-mixed-parentheses.php | 4 |
| 2 | questions/im1/order-of-operations-with-exponents.php | 4 |
| 3 | questions/im1/order-of-operations-negative-integers.php | 4 |
| 4 | questions/im1/order-of-operations-nested-groups.php | 4 |
| 5 | questions/im1/order-of-operations-absolute-value.php | 4 |
| 6 | questions/im1/function-machine-single-operation.php | 4 |
| 7 | questions/im1/growing-pattern-write-the-rule.php | 4 |
| 8 | questions/im1/growing-pattern-find-next-figure.php | 4 |
| 9 | questions/im1/function-machine-two-step-inverse.php | 4 |
| 10 | questions/im1/identify-linear-vs-nonlinear-pattern.php | 4 |
| 11 | questions/im1/evaluate-function-given-rule.php | 4 |
| 12 | questions/im1/is-it-a-function-from-table.php | 4 |
| 13 | questions/im1/is-it-a-function-from-ordered-pairs.php | 4 |
| 14 | questions/im1/vertical-line-test-concept.php | 4 |
| 15 | questions/im1/function-notation-evaluate-and-solve.php | 4 |
| 16 | questions/im1/domain-and-range-from-a-table.php | 4 |
| 17 | questions/im1/domain-and-range-interval-from-description.php | 4 |
| 18 | questions/im1/domain-and-range-discrete-points.php | 4 |
| 19 | questions/im1/identify-restricted-domain-real-world.php | 4 |
| 20 | questions/im1/range-from-a-function-and-a-domain-set.php | 4 |
| 21 | questions/im1/zero-and-negative-exponent-rules.php | 4 |
| 22 | questions/im1/simplify-product-of-powers.php | 4 |
| 23 | questions/im1/simplify-power-of-a-power.php | 4 |
| 24 | questions/im1/simplify-quotient-of-powers.php | 4 |
| 25 | questions/im1/evaluate-expression-with-negative-exponents.php | 4 |

Total: 100.

## The flow (per `steve-desktop/skills/mom-transfer/SKILL.md` — read it in full first)

1. `addassessment2.php?block=0&cid=334437`, `name` = "Chapter 1 Review (Sub Day)", `copyfrom` =
   `23258795`, leave the copy-summary/instr/dates/endmsg checkboxes unchecked. Submit — the redirect
   to `addquestions2.php?cid=&aid=<NEW AID>` is how you learn the new aid. Record it.
2. Per question, in slot order 1-25: `moddataset.php?aid=<aid>&cid=334437` (no `id`), fill the five
   fields from the file's five markers via CodeMirror (never the raw textarea — trap 1 in the
   skill), set `qtype` via the `#qtypedd` picker (not the hidden input directly — trap 2), submit,
   capture the new `qsetid`.
3. Attach: `modquestion2.php?qsetid=<qid>&cid=334437&aid=<aid>&from=addq&process=true&usedef=true`.
4. Points: `modquestion2.php?id=<instanceid>&aid=<aid>&cid=334437`, set the hidden `points` input to
   `4`, Save Settings.
5. After all 25 are filed, attached and pointed: verify every value sums to exactly 100.
6. **Book link:** this book (`integrated-math-1`) has no bookSHelf chapter pages yet past chapter 1
   foundations per `MAP-IM1-GLOBAL.md`, so there is nothing to swap the Book resource link to. Leave
   whatever the `copyfrom` template carried, or remove the resource row if `showextrefs` is on and
   empty — do not invent a URL.

## Verify contract

1. **Static audits first**: `node mom-content/_push/qtext-audit.mjs` and
   `node mom-content/_push/anstypes-audit.mjs` are manifest-driven (stats book only, per the
   authoring pass's own finding) and will not scan `im1/` — note that rather than treating a silent
   pass as evidence. Re-run the authoring pass's scoped manual equivalents against all 25 files
   instead, or trust the authoring pass's own clean results (already run per file) and say which you
   did.
2. **Byte-exact read-back** on all 25 filed questions (em dash → `--`, trim leading newline) and
   **qtype audit** (`moddataset.php?id=<qsetid>` form's `[name=qtype]` vs each file's
   `SET QUESTION TYPE TO:` marker) — for all 25, not a sample.
3. **Teacher Preview**, one session: `/assess2/?cid=334437&aid=<aid>` → Teacher Preview. Answer and
   submit **every part of all 25 questions**, polling each question's `.scoreresult` before moving
   to the next (submission is AJAX and races — see the skill's "Submitting is AJAX" section). Select
   `choices`/`multans` options by rendered label text, never by source index. Strip bidi isolates
   (`⁦-⁩`) before parsing the header score.
4. Confirm the header reads **Score: 100/100 (or 102/100 with the early-finish bonus if enabled),
   Answered: 25/25** in the same session that answered — a reopened preview shows no prior score.
5. **Full-page screenshot** of the assignment in Teacher Preview (one-shot
   `Emulation.setDeviceMetricsOverride`, not scroll-and-stitch) so a human can eyeball typesetting —
   this is a fresh, never-before-rendered-in-a-real-course set, so a visual pass matters more than
   usual, not less.
6. Confirm the assessment settings read back **undated** (`sdatetype=0`, `edatetype=2000000000`) —
   if the copy somehow carried a date from the template, remove it; this course stays undated.

## Report format

For every one of the 25 slots: slot number, title, library qid (newly filed), instance id, points,
attached (y/n), graded correct (y/n) — mark **unverified** for anything you did not personally
fill/submit/see graded, never claim success you did not observe. State the final header score and
Answered count verbatim. State the static-audit results and which method you used (re-run vs. trust
the authoring pass). State the new assignment's aid. State plainly that no date was set on this
push and that Steve still needs to copy this assignment into his teaching section and set Fri
2026-09-25 open/due there himself. If anything failed twice on the same question, stop and report
it rather than grinding further.

## Read this before starting

`mom-content/reference/transfer-rules.md` and `mom-content/reference/learned-rules.md` — both hold
real failures from earlier pushes into this same course (334437). `steve-desktop/skills/mom-transfer/SKILL.md`
in full, especially: CodeMirror trap (1), `qtype` picker trap (2), Vue-backed settings form trap (3),
the four-identical-Save-buttons trap, and the bidi-isolate header-parsing trap.

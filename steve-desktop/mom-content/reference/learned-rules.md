# Learned rules

Written by the question writer after runs that needed repairs. Each line is a rule the loop
discovered by breaking something and then fixing it, in the same shape as the hand-written rules
it ships with.

Safe to edit or delete by hand — a wrong rule here makes every later run worse, so prune freely.
- Set `SET QUESTION TYPE TO: multipart` whenever the question text contains more than one `$answerbox[i]` — any single-answer type makes `$answer` and `$answerbox` scalars, so indexing them throws "Uninitialized string offset" and a strpos array-given error.
- In a multipart question set `$strflags[$i]` to ONE comma-separated string (`"ignore_case,trim_whitespace"`). The nested form `$strflags[$i]['ignore_case'] = 1` hands IMathAS an array where it runs strpos on a string, which kills the whole control block — so `$answerbox` is never built either and you also get "Uninitialized string offset" on every answer line. The nested form is only valid for a single-part question, where `$strflags` itself is the flag set.
- Separate acceptable phrasings for a `string` answer with ` or `, never commas. Commas mean alternatives ONLY under the `all_words` flag, and `all_words` requires EVERY comma-separated item to appear — so a comma list without it, or with it, both mis-score a correct one-phrase answer. This one renders perfectly clean and still grades students wrong, so nothing in the loop catches it.
- The four types worth reaching for are multiple choice, select-all-that-apply, matching, and free response. Which one fits is a judgement about the exercise, not a lookup — but these are the usual starting points: a calculation normally wants free response, because offering computed values to choose between turns arithmetic into elimination; a definition normally reads better as multiple choice or matching than as typing; select-all-that-apply suits a concept with several conditions to satisfy. Depart from any of these when the exercise genuinely calls for something else, and say why in the brief.
- A question does not have to pick ONE type. `multipart` takes an `$anstypes` array, so parts can differ — part (a) a computed value typed in, part (b) multiple choice about what that value means, part (c) a matching set. Mixing is often the honest shape for an exercise that asks the student to compute something and then interpret it, and it beats splitting one exercise into two questions.
- Prefer a selectable type over free text when what is being tested is whether the student RECOGNISES something rather than whether they can phrase it — typing "the mean weekly exercise time for all clients" is graded on wording, and no list of accepted phrasings is ever complete. Free response is still the right call when there is one exact answer, especially a computed one.
- When a matching question is the right shape, a couple of extra descriptions beyond the matched ones stop the last term being free by elimination, and `$noshuffle = "questions"` keeps the terms in teaching order while the descriptions move. Both are worth doing, neither is required. The distractors that earn their place are the mistakes the topic actually produces — a count where a mean or proportion belongs, or a population drawn too wide.
- Emit the solution guide from the ANSWER section as `$solutionguide`, never inline it in QUESTION TEXT. Checked against the live renderer: the ANSWER field is not shipped with the prompt, so the guide is absent from the student's HTML entirely and cannot be reached even from view-source. The same `<details>` block placed in QUESTION TEXT is one click from every answer, mid-attempt.
- A question taken from the section's numbered problems must REPACKAGE that problem's own solution (`div.solution-content`: `Step 1 —` / `Step 2 —` / `Step 3 —`, then `Answer:`) into `$solutionguide`, keeping its steps and wording against the randomized values — that is the explanation the student was taught from. A question invented beyond the problem set needs one authored in the same shape.
- The render sandbox seeds deterministically, so it CANNOT verify randomization: a question calling `rand()` fifty times returns byte-identical HTML on every POST. Rendering twice and diffing proves nothing. Check randomization by reading the control block, and grep for `diffrands|randsfrom|randfrom|jointrandfrom|rand\(` — a bare `rand\(` misses `diffrands(`, which is how a properly randomized question gets reported as static.
- The same fixed seed also means you cannot judge the SHAPE of generated data from a render. A stemplot built by a random walk came back with six identical values in a row, and the digit-by-digit rewrite came back with two rows holding all ten digits — both look like generator bugs and neither is. They are one seed's draw, replayed on every POST. Three probes went into chasing the first one before `rand()` was confirmed uniform inside nested loops. Judge a generator by reasoning about its construction, or by varying an INPUT you control; never by how one render happens to look. What a render can still tell you: whether the plot builds at all, and whether values land where the prompt says.
- Prefer a generator whose worst case is bounded over one that is merely unlikely to look bad. A running sum with a small random step sorts itself for free but lets repeats pile up without limit; walking the digits 0..9 per stem caps every value at two occurrences by construction. Students draw thousands of seeds, so "improbable" happens to somebody.
- Do not call `implode()`, `array_slice()`, `array_rand()`, `exp()`, `pow()`, `shuffle()` or `number_format()`. Use `**` for powers, `round($v, 2)` for money, and `jointrandfrom` or an explicit index for picking from an array. A rejected call kills the whole control block, so the question renders as an empty box and nothing in the error names the function.
- Some variable names are **reserved by IMathAS and rejected outright**: `$score` renders the whole
  question as `Eeek.. unallowed var $score!`. The message names the variable, so the fix is quick once
  seen, but it is invisible until you render &mdash; nothing about the name looks special. Rename to
  something qualified (`$pctScore`) rather than hunting for the full reserved list. Assume the same trap
  exists for other short, engine-sounding names and reach for a qualified name by default.
- An **unescaped apostrophe inside a single-quoted PHP string ends the string early** and takes the
  rest of the control block with it. `$solutionguide = '... today's residents ...'` renders as
  `Eeek.. unallowed macro` with every substituted scalar coming back blank &mdash; the prose looks
  fine in the file and the failure appears far from its cause. Write `today's`. Prose in a solution
  guide is where this bites, because that is the only place long English sentences live.
- **Do not put a parenthetical inside an SVG `<text>` label.** MathJax runs on the rendered page and
  swallows it: the source says `Weight (pounds)` and the drawing shows `Weight`. The units simply
  vanish, the render reports clean, and the axis is left unlabelled in the way that matters. Spell it
  into the phrase &mdash; `Weight in pounds`. Caught on a box plot; it had already been silently
  dropping units on five graphs. Same cause as parenthesised prose typesetting as italic math.
- **Apostrophe escaping is opposite in the two places you write prose.** `$solutionguide` is a
  SINGLE-quoted string, so an apostrophe must be escaped as `'` or it ends the string early and
  silently kills the control block. Choice strings in `$questions[i]` are DOUBLE-quoted, where `'`
  is not an escape sequence at all &mdash; the backslash renders literally and the student reads
  `a class's midpoint`. Escape in the solution guide, never in the choices. Simplest safe habit:
  avoid apostrophes in option text entirely, since a possessive can always be reworded.
- The MathJax-eats-parentheses trap is **not confined to SVG**. A choice option reading "a quarter of
  the data (25%)" rendered as "a quarter of the data 25 ," &mdash; the parentheses gone and the percent
  sign with them. Anywhere student-visible, prefer no parentheses at all; where a parenthetical is
  genuinely wanted, reword it into the sentence. A unit or a symbol inside brackets is the dangerous
  case, because what disappears is the part that carried the meaning.

## Layout and display (2026-08-09, proved live in course 334437)

- **`$answerbox[n]` only becomes an input where it is written LITERALLY in the question text.**
  Built into a string in the control block it prints as the plain text `$answerbox[0]` and no input
  appears -- confirmed by probe, one literal box rendered and one built-in-control box did not.
  Consequence: **the number of answer boxes cannot vary with the seed.** A fill-in-the-plot question
  therefore needs fixed row counts; randomize the stems, the digits and the order instead.
- **A `draw` grid whose window starts at 0 clips the tick NUMBERS off both axes.** `$grid` of
  `"0,6,0,14,..."` puts the axes flush against the canvas edge and MyOpenMath draws the labels
  outside the visible area: the student gets a bare grid with nothing to read heights against.
  Start the window negative -- `"-1,6,-2,14,1,2,470,380"` -- to leave the labels room. Caught only by
  screenshotting the live question; the sandbox never renders the canvas at all.
- **The render sandbox cannot verify a `draw` question.** It falls back to the keyboard-entry list
  ("Add new drawing element") and draws no canvas, so neither the tool nor the axes nor the grading
  are exercised. A `draw` question is unverified until drawn in a real course.
- **Make every plotted value land ON a labeled gridline.** Force the data even when the gridlines are
  every 2, and never let a tick step exceed 2 on a graph a student reads values off. Steve, 2026-08-09.
- **In the box-plot template the tick COUNT and the tick VALUE step are separate numbers.** The loop
  runs `for ($g=0..$nTicks)` but computes `$val = 10 * $g`, so changing `$nTicks` alone moves nothing
  and the axis silently keeps its old labels -- it renders clean and looks unchanged. Change BOTH.
  Every box-plot question in 2.4 was copied from this template and carries the same pair.
- **A tick step of 2 is only readable if the DATA is small.** Forcing step 2 onto an axis that runs
  to 140 needs seventy labels. Shrink the generated values instead (widths of 2/4/6/8 rather than
  10/20/30/40) so the fine scale fits; the five-number values stay even and keep landing on ticks.

## Rounding a table the student then adds up (2026-08-09, proved live in course 334437)

- **If a question asks the student to ADD UP a displayed column, the displayed values must be
  exact.** `percent-more-than-cutoff-via-complement` drew six independent frequencies, so the total
  was whatever it happened to be (59 on the seed that caught it). The key was computed from exact
  fractions with `abstolerance 0.05`, but the table showed relative frequencies rounded to three
  decimals. Part (a) asked for `1 - cumulative` and part (b) asked the student to add the rows above
  the cutoff instead, promising "It should match part (a) exactly":

  | route | what the student computes | result |
  |---|---|---|
  | (a) complement | `1 - 0.780` | 22.0 -- accepted |
  | (b) add the rows | `0.119 + 0.102` | 22.1 -- **rejected**, off by 0.066 |

  The fix is not a wider tolerance, which hides the contradiction. **Choose N first, from totals
  whose reciprocals terminate inside the displayed precision (40, 50, 100 for three decimals), then
  deal the observations out to sum to it.** The displayed column then IS the true column and adding
  it up cannot drift. Same shape as the `$cnt` deal-out in `build-the-grouped-frequency-table`.
- **A clean render and a correct key together still miss this.** The question graded correct for a
  student who used the intended route; only entering the answer the OTHER stated route produces
  exposed it. When a question offers two routes to one number, submit BOTH.

## Rescaling a generator moves more than the data (2026-08-09, proved live in course 334437)

- **When you shrink a question's value scale, every constant derived from the old scale has to move
  with it -- and the leftovers do not announce themselves.** `spread-within-each-quarter-of-a-box-plot`
  had its section widths shrunk from 10/20/30/40 to 2/4/6/8 so the axis could carry a tick every 2.
  One constant stayed behind: part (d)'s second interval is built by trimming a fixed amount off one
  end of the widest quarter, and that trim was still `10`. The widest quarter is now 8 wide, so the
  trim overshot the far end and the interval printed **backwards** -- "from 24 to 22", above a
  maximum of 22.
- **It rendered clean, and it graded correct.** Part (d)'s answer does not depend on that interval's
  endpoints, so every automated check passed it: no `Eeek`, no missing widget, full marks on submit.
  It was visible only by reading the question the way a student would.
- Sister case already in this file: `$nTicks` and `$val` in the box-plot template. Same shape --
  two constants encode one scale, and moving one silently leaves the other wrong. **After any
  rescale, grep the file for the old magnitude** (`10`, `20`, ...) before calling it done.
- **Where a context and a readable axis conflict, change the context.** `median-is-not-the-center-of-the-box`
  offered "monthly rent in dollars" at `$scaleUp = 10`, which dragged the tick step to 20. Nothing
  about the question needs to be about rent, so the context was replaced with a small-unit one.
  The exceptions are constructions that genuinely force a wide range -- 1.5*IQR fences reach far
  past the box, and three groups share one axis -- and there a step of 10 with every plotted value
  landing on a labeled tick is the honest answer, not a step of 2 with seventy labels.

## MyOpenMath silently rewrites what you file (2026-08-09, proved live in course 334437)

- **A `NAME - DESCRIPTION` longer than 254 characters is TRUNCATED on save, with no warning.**
  Confirmed live: a 271-character description came back at exactly 254. The description is how a
  question is found in a bank of hundreds, so a chopped one is a real loss even though the question
  itself works perfectly. **17 sources in the bank are currently over the limit** -- two of them
  (`q20-do-the-percents-allow-a-pie-chart`, `q21-percent-versus-count-comparison`) are already live
  in 1.2 with truncated names, and 14 more are FRQ questions not yet pushed. Check the length before
  filing, not after.
- **A Unicode ellipsis `…` is stored as three periods**, the same way an em dash is stored as `--`.
  Harmless to the student, but a byte-exact verify flags it as a mismatch forever, so the source
  should just use `...`. Keep the verifier's normalisation and the source in step, or every future
  push reports a false failure on the same question.
- Both are only visible because the push verifies by reading each field BACK and comparing exactly.
  A length check would have caught the truncation but not the ellipsis; a render check would have
  caught neither.

## `$anstypes` counts ANSWER BOXES, not lettered parts (2026-08-16, found live in 5.1)

- **One `$anstypes` entry per answer box.** A part that asks for two numbers -- "between what two
  values?" -- needs TWO entries, not one. `q10-empirical-rule-bands.php` declared
  `array("numfunc","numfunc","numfunc")` for three lettered parts while setting `$answer[0..5]` and
  referencing six `answerbox`es. IMathAS rendered boxes 1-3 only: part (a) got both its boxes,
  part (b) lost its upper box, part (c) rendered **no boxes at all**.
- **It still scored full marks.** The parts that never rendered could not be graded, so the
  assignment read 102/100 with every question marked correct. Neither the byte-exact read-back, the
  qtype audit, the points total, nor the score caught it -- the filed code was byte-identical to a
  source that was itself wrong.
- **Only a human-scale look at the render found it**, which is why the visual pass is not optional
  and why it needs image input. The model driving the push had none and said so.
- Static check, cheap, run it before pushing: compare the `$anstypes` entry count against
  `max($answer[N]) + 1` and the `answerbox` reference count. An audit across all 395 questions in
  `introduction-to-stats-sh` found exactly this one, so it is a real trap but not a systemic one.

## QUESTION TEXT is not a PHP string (2026-08-16, 65 hits across 45 files)

- **QUESTION TEXT interpolates `$var` directly.** A `' . $var . '` concatenation substitutes the
  variable and leaves the quote-dot punctuation **visible to the student**:
  `"Find k such that ' . 60 . '% of the values are at least k."` Live in 5.2 and in 4.3, which had
  been signed off at 102/100 twelve days earlier.
- **The identical syntax inside `$solutionguide`, `$rubric` or `$responses` is CORRECT** -- those
  really are PHP strings built by concatenation. A blanket find-and-replace breaks every pre-FRQ in
  the bank. Only QUESTION TEXT is wrong.
- An expression cannot be interpolated. `' . ($b - $a) . '` and `' . round($cl * 100) . '` each need
  a precomputed variable in COMMON CONTROL -- **defined AFTER everything it reads from.** A derived
  variable placed above its own array is silently empty and renders as a blank; that mistake was
  made once during this repair and caught by `_push/usecheck.mjs`.

## Backticks are ASCIIMath delimiters, so a function NAME inside them is typeset (2026-08-16, 19 hits across 10 files)

- `` `invNorm` `` renders as `∈ vN or m`; `` `normalcdf` `` as `‖a‖ lcdf`. Function names are plain
  text, never backticked. Keep the math where it belongs:
  `` `invNorm(' . round($x,3) . ', 0, 1)` `` becomes ``invNorm(`$xRounded`, 0, 1)``.
- Applies in QUESTION TEXT and `$solutionguide` alike -- both render.

### What caught these, and what did not

Neither defect was visible to any automated gate. The byte-exact read-back **passed**, because the
filed code matched a source that was itself wrong; the qtype audit passed; points totalled 100; the
assignment scored 102/100 with every question marked correct. A rendered page reviewed by something
with **image input** found them. The static audits (`_push/qtext-audit.mjs`,
`_push/anstypes-audit.mjs`, `_push/usecheck.mjs`) were written afterwards, from the defects, and are
cheap -- run all three before any push.

## A freshly opened Teacher Preview shows NO prior score (2026-08-16)

- Entering Teacher Preview again starts a **new attempt**. The header reads no score and no
  question shows "Score on last try", **whether or not the assignment was ever graded**.
- Measured: 5.2 (aid 23444258) was answered and graded 102/100 inside its own push run, and read
  **0 questions with a score** when the preview was reopened minutes later. 4.5 reads the same, so
  its zero is NOT evidence it was never verified.
- **Consequence: grading must be read in the SAME session that answers.** "Open the preview and
  check the header" is not a verification — it is a fresh attempt with nothing entered yet, and a
  screenshot of one is a picture of an empty form, not of a failure.
- This also means a capture taken for visual review shows an UNANSWERED page unless the capturing
  run answered it first. Do not read "Score: 0/100, Answered: 0/10" on such a capture as a defect.
- What IS durable evidence: the run's in-session report of what it entered and what MOM said back,
  question by question. Which is why the report format asks for exactly that.

## Choice options are SHUFFLED, so source indices do not address them (2026-08-16)

- `$answer[n] = 0` means the **source's** first option. MOM renders the options in a shuffled order
  and renumbers the `value` attributes, so "source index 0" is not "the option with `value=0`" and
  is certainly not "the first radio in the DOM".
- Measured on 5.3's pre-FRQ: source `$answer[1] = "1,2"` graded correct only when the boxes labelled
  *State the Theoretical Value* and *Compare and Explain the Gap* were ticked — which rendered as
  `value=2` and `value=0`. Selecting DOM positions 1 and 2 ticked *State the Empirical Value* and
  scored 6.67/10 with the part marked Incorrect.
- **Select choice and multans options by matching the LABEL TEXT**, taken from MOM's own
  "Show All Answers" reveal, never by index. Reveal in a throwaway preview session first, then
  answer in a fresh one — preview resets on re-entry, so revealing cannot contaminate the grading
  pass.
- A part marked Incorrect is not automatically a defect. Rule out your own option-mapping first; on
  5.3 the "defect" was entirely the index assumption.

## Every number MOM prints is wrapped in bidi isolates (2026-08-16)

- `Score: ⁨100⁩/⁨100⁩` contains U+2068/U+2069 around each number. A regex like
  `/Score:\s*([\d.]+)/` **silently returns null**, so a fully graded page reads as ungraded — which
  is exactly how 5.3 appeared to score nothing while every question was in fact marked Correct.
- Strip them before matching anything numeric:
  `s.replace(/[\u2066-\u2069\u200e\u200f]/g, '')`.
- Related layout fact: the per-question "Score on last try" line and the "Submit Question" buttons
  live **outside** `[id^=questionwrap]`. Scoping a query to the question block finds neither.

## Practice tests are due on the individual-test day (Steve, 2026-08-16)

- In every stats class, a chapter's **Practice Test is due on the day the Individual Test is sat.**
  It is preparation for that sitting, so its window closes when the sitting begins.
- This is a **copy-time** setting, like every other date. The master course 334437 stays undated —
  nothing is taught out of it, so a date there is never the date anyone sits. Apply this when the
  course is copied into a teaching section, alongside `due_date_rule` in `assessment-presets.json`.
- Consequence for the scaffold: each chapter's `Chapter N Practice Test` and
  `Chapter N Individual Test` share a date. Whatever sets the Individual Test day sets both.

## The session-dead check must look for the LOGIN form, not a password field

- Assessment settings (`addassessment2.php`) carry an `assmpassword` passcode input. A guard written
  as `document.querySelector('input[type=password]')` fires on it and reports a **dead session on a
  perfectly live one** — measured 2026-08-16, which stopped a rename mid-task.
- Test for the login form itself: `input[name=username]` present, a password field present, and no
  `Log Out` in the page text. `_push/mom.mjs` now does this.

## Group/Individual Test due-times were wrong in 3 of 4 live sections (found and fixed 2026-08-21)

- Audited every Group Test and Individual Test in the three live Stats teaching sections (period 3
  `cid 339304`, period 4 `cid 334243`, period 7 `cid 339625`) against the rule in
  `intro-stats-assessment-settings.md` / `bell-schedule-2026-27.md`: both should OPEN and CLOSE on
  the SAME day, closing at period-end+7 (`11:47 am` regular, `12:01 pm` Wednesday, `10:44 am` on a
  minimum day). **44 of the 72 tests were wrong** — periods 3 and 7 had chapters 1-5 closing at
  period-START instead (the open/close times were identical) and spanning 2-6 calendar days instead
  of one; period 4 had every chapter spanning multiple days, plus chapters 4-5 also reverted to
  closing at period-start. Chapters 7+ in periods 3/7 were already correct, which is how the defect
  was found — the pattern broke cleanly at one chapter and never got backfilled.
- **The MyOpenMath course Calendar's Agenda view (`showcalendar.php?cid=`, `setcalview(1)` to switch
  from month to agenda) is the fast way to read every assessment's due date+time in one page** —
  far cheaper than opening `addassessment2.php` per assessment. But it is a DISPLAY, not proof: the
  actual fix and its verification both went through the live settings form. Set the "Show N weeks"
  select to its max option before switching views, or the agenda only covers ~4 weeks.
- **Confirmed the write recipe from mom-transfer's Vue trap actually works on `sdate`/`edate`/
  `stime`/`etime`**: use the native `HTMLInputElement.prototype.value` setter (not plain `.value =`,
  which some Vue builds still pick up via property descriptor tricks — do it the documented way
  anyway) then dispatch `input` and `change` with `{bubbles:true}`, then click the real
  `input[type=submit]` "Save Changes" button. **Never trust the DOM state right after the click** —
  it redirects to `course.php`. The only real proof is navigating back to
  `addassessment2.php?id=<aid>&cid=<cid>` FRESH and reading the values off that reload. All 44
  fixes were confirmed this way; 0 failed.
- `_push/mom-live.mjs` and `_push/_audit-dates.mjs` are the reusable tools for this — connect to the
  S.T.E.V.E Desktop app's own CDP port (not the standalone-Chrome `mom.mjs` on 9223) so the write
  goes through the teacher's already-logged-in session, and never guess or hardcode a `cid`; read it
  from `listClasses()`.

## Term retime (2026-08-21) — period 7 whole-course block fix done; IM1 blocked

- **Root cause of the period-7 defect above**: period 7's course (`cid 339625`) was carrying the
  period 3/4 block's clock times (`10:11am` etc.) on every single assessment, not just tests — the
  "retime pass" flagged as still-pending in `intro-stats-assessment-settings.md`'s 2026-08-02 note
  had never been done. **Fixed and verified 2026-08-21: all 92 assessments now use the 7-or-8 block**
  (`2:03/2:09pm` open regular/Wed, `3:39/3:37pm` test-close, `11:56am`/`1:03pm` on a minimum day).
  `sdate`/`edate` were never touched — only `stime`/`etime`. Periods 3 and 4 were already on their
  correct block (3-or-4) and needed no block correction, only the same-day/period-end+7 fix recorded
  above.
- **`bookSHelf` has authoritative per-course pacing calendars** — `projects/Introduction to
  Stats/stats_course_schedule.md` (odd-day / even-day columns; period 3 & 7 = odd, period 4 = even —
  confirmed by cross-checking against the live dates, which matched exactly) and `projects/Integrated
  Math 1/im1_course_schedule.md` (period 6 = even only). Both already bake in the real 2026-27
  holiday/break calendar — check these before ever computing dates by hand.
- **Homework/lab due-date rule, from Steve directly**: due at the START of the NEXT time that exact
  class period is in session — not a fixed +1 day. A Friday-opened homework in a period that doesn't
  meet again until Tuesday is due Tuesday, at that day's period-start time. Rotation is simple A/B
  alternation by school day (skip weekends/holidays, don't count them — matches the "closure doesn't
  advance the odd/even counter" rule already stated in `stats_course_schedule.md`).
- **Stats retime confirmed the existing chapter-to-date sequence is correct** — cross-checked several
  live dates against `stats_course_schedule.md` and they matched exactly, so a stats retime only ever
  needs a TIME correction (block/day-type), never a date recompute.
- **IM1 (`cid 340140`, period 6) is NOT ready for the same treatment — stopped 2026-08-21, needs
  Steve to reconcile first.** The live course carries 138 assessments and is a copy of an OLDER
  curriculum, not a clean base:
  - Past Chapter 3, the live course's chapter numbers do not match `im1_course_schedule.md`'s new
    numbering — e.g. the calendar's Chapter 4 (Sequences) corresponds to a live assessment set
    labeled "Chapter 5 & Chapter 8"; the calendar's Chapter 6 (Congruence) corresponds to live's
    "Chapter 7". A title-fuzzy-match script would silently mis-date everything past Ch3.
  - The live course also carries real cruft that must never get a 2026-27 date: `AAA Homework
    Setting Master`, `AAA Practice Quiz`, `AAA Quiz Setting Master`, `demonstration`, `Sample
    Questions`, `Chapter 3 Quiz (Spring 2020)`, `Exam 1/2/3 Practice`, and a parallel set of
    old-numbering homeworks (`5.1`, `6.1`, `7.1`, `8.1`, `9.1`...) left over from a prior course
    version.
  - Even within the clean-looking Ch0-3 region, granularity does not match 1:1 — e.g. calendar's
    single "1.5 Working with Exponents" day corresponds to TWO live homeworks ("Rewriting
    Expressions with Exponents" + "Zero and Negative Exponents"). And Chapter 0 is missing its
    Practice Test and Individual Test entirely — only a "Chapter 0 Group Quiz" exists.
  - **Steve's call, 2026-08-21: fix the calendar/course mismatch first, then the reconciled calendar
    becomes the one source of truth for assignments AND due dates** — not a job to resume by
    guessing matches. Whoever picks this up next should start by reconciling
    `im1_course_schedule.md` against the live `cid 340140` assessment list, not by writing a
    fuzzy-matcher.
- `_push/_audit-all.mjs` (whole-course, any assessment kind — used for the period 7 audit) and the
  scratch computation scripts referenced in this session (not committed; rebuild from this recipe if
  needed) are the pattern for any future retime: audit all → compute target sdate/stime/edate/etime
  against the bookSHelf calendar → present the full diff for confirmation → apply in the same
  generalized `_apply-fixes.mjs` (`stime`/`etime`-only for a block-time fix, `edate`/`etime`-only for
  a same-day-close fix) → verify every write from a fresh page reload, never from the post-save DOM.

## Period pairs 3&4, 5&6, 7&8 share one start/due TIME-OF-DAY (Steve, 2026-08-21)

- This is a **block schedule** — Period 3 and Period 4 meet in the same daily time slot (10:11-11:40
  regular, 10:33-11:54 Wednesday, 9:37-10:37 minimum day), likewise 5&6 and 7&8. So any course taught
  in period 3 shares its class-time-of-day with whatever course is taught in period 4 that slot, and
  the same for 5&6 and 7&8. See `bell-schedule-2026-27.md` for the full table.
- Consequence: a due-date TIME rule (e.g. "period start", "period end +7 min") set for one course in
  a pair is the same clock time for the other course in that pair — only the DATE can differ, driven
  by each course's own syllabus/day. Which course sits in which period comes from that course's
  syllabus (bookSHelf repo), not from anything in this repo.
- This is still provisional, like `due_date_rule` in `assessment-presets.json` — it is scheduling
  for Steve's not-yet-built term calendar, not something to apply to the undated master course
  334437. Apply it at copy time, alongside the bell-schedule table.

## `$anstypes` as a comma string cannot be overridden per index (2026-08-31)

This renders the last part with **no options at all** and no error anywhere:

```
$anstypes = "number,number,number,number,number"
$anstypes[4] = "choices"        <-- silently does nothing
```

The string form is parsed once. Indexing into it afterwards is not an override; the part stays
`number`, `$questions[4]` is never used, and the student sees a prompt with nothing to click.
Declare every type up front instead: `$anstypes = "number,number,number,number,choices"`.

Caught only by counting radio groups on the render. The question threw no error, scored, and read
as fine in the byte-exact read-back, because the source really did say that.

## CodeMirror auto-indents `///`, and an unclosed `<p>` is what triggers it

The multipart separator has to sit at column 0. A QUESTION TEXT line like

```
<p>Answer: `t =` $answerbox[0]        <-- no </p>
```

leaves CodeMirror's HTML mode inside an open tag, so it indents the next line and the separator is
filed as `  ///`. Measured 2026-08-31: the byte-exact read-back flagged a two-character `qtext`
difference, which is the only reason it was noticed. Close every tag; check the read-back diff
rather than assuming a two-byte difference is whitespace noise.

## Read a rendered question's own values from `data-asciimath`

`innerText` drops every backticked expression, because IMathAS renders AsciiMath through MathJax to
SVG. A prompt reading "find the blocks at `t = 8` minutes" extracts as "find the blocks at minutes".
MathJax keeps the source on the container:

```js
[...w.querySelectorAll('mjx-container')].map(e => e.getAttribute('data-asciimath'))
// -> ["t = 8", "N = 1 * 3^t", "N(8) = 1 * 3^8 = 6561", ...]
```

That is how to answer a question from the page rather than replaying the source, which is required
anyway because `choices` options are shuffled per seed. Match radio options by **label text**.

## Two things block Playwright clicks inside an assessment

- **`beforeunload`.** Once answers are entered, navigating away raises a native dialog, and an
  unhandled one wedges every later tool call with `does not handle the modal state`. Register
  `page.on('dialog', d => d.accept())` before the first fill.
- **`.dialog-overlay`.** An in-page overlay intercepts pointer events, so Playwright's
  actionability-checked `click()` times out after 30s while reporting the element as "visible,
  enabled and stable". Click in-page instead — `page.evaluate(() => el.click())` — which bypasses
  the interception. Both cost a run on 2026-08-31.

## The render sandbox is blocked on the school network (2026-08-31)

`mom.huffpalmer.fyi` returns the district filter's **"Web Page Blocked … Category: parked"** page
over http, and its TLS interception certificate (`issuer=CN=205.155.196.228`) over https, which
curl, Node and Chrome all reject independently. `myopenmath.com` and `oerbookshelf.app` are fine, so
it is specific to this host.

Until it is unblocked, verify in the real course instead: file into the global class, attach to a
scratch assessment, and answer it in Teacher Preview. That is a stronger check than the sandbox
anyway — it exercises grading, which the sandbox never does.

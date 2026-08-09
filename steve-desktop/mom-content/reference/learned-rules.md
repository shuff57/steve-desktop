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

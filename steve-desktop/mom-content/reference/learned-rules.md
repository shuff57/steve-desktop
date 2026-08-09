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

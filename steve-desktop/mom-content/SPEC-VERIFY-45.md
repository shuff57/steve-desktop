# SPEC — confirm 4.5 actually grades

**One task. Small on purpose.** Four previous runs were killed on elapsed budget; the last died
having read ten source files and touched nothing. So the answers are pre-extracted for you — **do
not open the question sources.**

Drive Playwright over CDP `http://127.0.0.1:9223` via
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/_push/mom.mjs`.
Not browser-harness. **Do not kill the Chrome process.**

## The problem

**4.5 Continuous Probability Functions, aid `23444255`, has never been answered.** Its Teacher
Preview reads `Score: 0/100, Answered: 0/10` — every box empty, every radio unselected. It is the
only pushed assignment in the book with no `_pushed` note. Its ten questions are filed, attached,
correctly pointed and their sources were repaired today, but nothing has ever confirmed they grade.

Rendering is not testing. A question can render perfectly and still be broken: an answer box that
rejects the right value, a `multans` whose key does not line up with its options, a dropdown that
never populates. "No error was thrown" is not evidence.

## What to do

Open `/assess2/?cid=334437&aid=23444255`, click **Teacher Preview**, and for **each of the ten
questions**: enter the answer, **submit it**, confirm it comes back correct.

**Your answer key is `mom-content/_push/crib-4-5.md`.** It carries, per slot: the `$anstypes`, the
seed arrays, the derived variables, every `$answer[...]` assignment and the tolerances. Read the
rendered question to see which seed is live, then take the value from the arrays in the crib.

Notes on reading it:

- `$i = rand(0, 3)` picks the seed. The rendered numbers tell you which index is live — e.g. if the
  interval shown is `[1, 7]` then `$i = 1` because `$as[1] = 1`, `$bs[1] = 7`.
- `$answer[n] = 0` on a `choices` part means **the first option**, zero-indexed.
- `multans` parts take a set; the crib's `$answer` line gives it.
- Tolerances are `0.005` throughout, so give numeric answers to at least 4 decimals where the crib
  shows a rounded constant (e.g. `0.6667`).

MathQuill on this instance is `window.MathQuill.MathField(el)`, not `getGlobalMathQuill`.
assess2 is a Vue SPA — the Teacher Preview button does not exist on load; wait for it, click, then
wait for widgets. `input[type=text]` counts 0 on a healthy page because the widgets are MathQuill.

## Finish condition

Header reads `Answered: 10/10`, score 100 or 102 (102 is the 2% early-finish bonus on a 100-point
total and is expected and correct).

## The thing I actually want to know

**Report any question where the CORRECT answer is marked wrong.** Do not adjust what you type until
something passes — if a right answer is rejected, that IS the defect and it is the reason this task
exists. Give the question, what you entered, and what MOM said.

## Then

Write a `_pushed` note onto
`books/introduction-to-stats-sh/hw/4-5-continuous-probability-functions.json`, shaped like the one
in `hw/4-3-binomial-distribution.json`, recording what you verified and on what date.

**Patch it as TEXT.** These manifests are hand-formatted, one question per line; a `JSON.parse` /
`stringify` round-trip rewrites every line (measured: 1169 lines changed where 26 were correct).
Assert it still parses before saving.

## Report

Per question: slot, what you entered, marked correct y/n. Then the header score and Answered count.
State any check you could not perform. If you have no image input, say so — the visual pass is
routed elsewhere.

`mom-content/questions/` is CLAIMED by claude and you have no reason to touch it — the crib replaces
those files for this task.

---

## ADDENDUM 2026-08-16 — read the score IN SESSION, and REPORT IT

A previous attempt at this task ran, derived the seeds correctly, and then ended without sending a
report. Nothing durable came of it.

**Re-entering Teacher Preview starts a NEW attempt.** The header shows no score and no question
shows "Score on last try", regardless of whether it was ever graded — measured on 5.2, which was
confirmed at 102/100 inside its own run and read zero when reopened minutes later.

So:

- Answer, submit, and read the result **in one continuous session**. Do not answer, close, reopen
  and expect to see anything.
- The **only** durable evidence is your report. Send it with
  `node C:/Users/shuff/.claude/bin/msg.mjs send --from opencode --to claude --re last --text "..."`
  **before** you run out of room. If you are partway through, report what you have — five verified
  questions reported beats ten verified and lost.
- Report per question: what you entered and what MOM said back. Not a summary verdict.

Two page facts learned since this spec was written, which cost a previous run its first attempt:

- `moddataset.php` shows **FOUR identical visible Save buttons**, all bound to
  `quickSaveQuestion(true)`; any one commits the whole form over AJAX. A guard expecting exactly one
  will refuse and save nothing.
- The page's own editor handles are `window.controlEditor` and `window.qEditor.qtext` /
  `window.qEditor.solution` — what `saveEditors()` itself uses — not only
  `textarea.nextElementSibling.CodeMirror`.

# Learned rules

Written by the question writer after runs that needed repairs. Each line is a rule the loop
discovered by breaking something and then fixing it, in the same shape as the hand-written rules
it ships with.

Safe to edit or delete by hand — a wrong rule here makes every later run worse, so prune freely.
- Set `SET QUESTION TYPE TO: multipart` whenever the question text contains more than one `$answerbox[i]` — any single-answer type makes `$answer` and `$answerbox` scalars, so indexing them throws "Uninitialized string offset" and a strpos array-given error.
- In a multipart question set `$strflags[$i]` to ONE comma-separated string (`"ignore_case,trim_whitespace"`). The nested form `$strflags[$i]['ignore_case'] = 1` hands IMathAS an array where it runs strpos on a string, which kills the whole control block — so `$answerbox` is never built either and you also get "Uninitialized string offset" on every answer line. The nested form is only valid for a single-part question, where `$strflags` itself is the flag set.
- Separate acceptable phrasings for a `string` answer with ` or `, never commas. Commas mean alternatives ONLY under the `all_words` flag, and `all_words` requires EVERY comma-separated item to appear — so a comma list without it, or with it, both mis-score a correct one-phrase answer. This one renders perfectly clean and still grades students wrong, so nothing in the loop catches it.
- Pick the question type from what is being tested. There are four to choose between, and the choice is not a matter of taste:
  - **Calculations are free response.** A computed value gets typed in — never offered as options to pick from, which turns arithmetic into elimination.
  - **Definitions are `multiple_choice` or `matching`.** One term against one meaning is multiple choice; several terms against several meanings is matching.
  - **"Select all that apply" is `multiple_answer`, and it is for a definition with SEVERAL rules** — the conditions a simple random sample must satisfy, what makes a study ethical. If the concept has one rule, it is not an all-that-apply question.
  - **Free response for anything else that has one exact right string**, and only when no phrasing judgement is involved.
- Write "identify the key term" questions as `matching`, not as free-text boxes. Asking a student to TYPE "the mean weekly exercise time for all clients" grades their phrasing, not their understanding, and no list of accepted alternatives is ever complete. Put the terms in `$questions` in teaching order with `$noshuffle = "questions"`, the descriptions in `$answers`, and add two plausible distractors past the matched ones (with an explicit `$matchlist`) so the last term is not free by elimination. Good distractors are the mistakes the topic actually produces — a count where a mean or proportion belongs, and a population drawn too wide.

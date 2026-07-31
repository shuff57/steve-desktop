# Learned rules

Written by the question writer after runs that needed repairs. Each line is a rule the loop
discovered by breaking something and then fixing it, in the same shape as the hand-written rules
it ships with.

Safe to edit or delete by hand — a wrong rule here makes every later run worse, so prune freely.
- Set `SET QUESTION TYPE TO: multipart` whenever the question text contains more than one `$answerbox[i]` — any single-answer type makes `$answer` and `$answerbox` scalars, so indexing them throws "Uninitialized string offset" and a strpos array-given error.

# MOM dialect rules

The rules a MyOpenMath question must follow. Breaking one makes MOM refuse the question, usually
without saying clearly why — several of these cost a real broken question in the bank to find.

This is the readable copy. The machine copy is `MOM_DIALECT_RULES` in
`src/integrations/mom/revise.ts`, and a test fails if the two ever disagree — so change both or
neither.

Rules the writer teaches itself land in [learned-rules.md](learned-rules.md) instead; those are
appended by the reflection step and are safe to prune by hand.
- Statements in the control block take NO trailing semicolon (`$ci = rand(0, 4)`).
- Keep all five markers, in order: NAME - DESCRIPTION, SET QUESTION TYPE TO, COMMON CONTROL, QUESTION TEXT, ANSWER.
- Math is AsciiMath in BACKTICKS (`I = Prt`). Never TeX dollar delimiters — `$` means currency here.
- Question text substitutes SCALARS only; it has no nested array indexing, so `$questions[0][$answer[0]]` renders as the literal string "Array[1]". Precompute a scalar in the control block instead.
- A `choices` answer is an INDEX into `$questions[i]`, not the option text.
- `$anstypes`, every `$answer[i]`, every `$ansprompt[i]` and every `$answerbox[i]` must stay in agreement.
- Precompute every derived value so the prompt and the answer key cannot disagree.
- Round money with `round($v, 2)`; never `number_format`.
- The ANSWER section is CODE, not markup. Put HTML in `$solutionguide` in the control block and reference `$solutionguide` there — raw `<div>` in ANSWER is a syntax error.
- Braces EVALUATE. `{$x}` in question text is parsed as an expression, so set-builder notation like `{students who own a dog}` breaks the question. Use `&#123;` / `&#125;`.
- Use `&&` and `||`. The word operators `and` / `or` are rejected by the parser.
- One statement per line. Two statements joined by `;` on one line fails.

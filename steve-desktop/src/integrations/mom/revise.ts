/**
 * Prompt for revising one MyOpenMath question from the preview's chat rail.
 *
 * The agent edits the .php file in place through the CLI's own tools, so the prompt's job is to pin
 * the target and restate the dialect rules that are easy to get wrong. MOM's control block is a
 * RESTRICTED PHP — the mistakes below are the ones that make a question render as "Error in question"
 * rather than fail loudly here.
 *
 * These files are course content, not student data: nothing identifying goes to the model.
 */

export interface ReviseRequest {
  /** Absolute path of the question file to edit. */
  path: string;
  /** `<family>/<slug>` — how the teacher refers to it. */
  label: string;
  /** The teacher's instruction, verbatim. */
  instruction: string;
  /** Current file contents, so the agent does not have to guess before reading. */
  contents: string;
}

/** House rules the agent must not break. Derived from the repo's own AGENTS.md conventions. */
export const MOM_DIALECT_RULES = [
  'Statements in the control block take NO trailing semicolon (`$ci = rand(0, 4)`).',
  'Keep all five markers, in order: NAME - DESCRIPTION, SET QUESTION TYPE TO, COMMON CONTROL, QUESTION TEXT, ANSWER.',
  'Math is AsciiMath in BACKTICKS (`I = Prt`). Never TeX dollar delimiters — `$` means currency here.',
  'Question text substitutes SCALARS only; it has no nested array indexing, so `$questions[0][$answer[0]]` renders as the literal string "Array[1]". Precompute a scalar in the control block instead.',
  'A `choices` answer is an INDEX into `$questions[i]`, not the option text.',
  '`$anstypes`, every `$answer[i]`, every `$ansprompt[i]` and every `$answerbox[i]` must stay in agreement.',
  'Precompute every derived value so the prompt and the answer key cannot disagree.',
  'Round money with `round($v, 2)`; never `number_format`.',
  // The four below were each found by repairing real broken questions in the bank, not guessed.
  'The ANSWER section is CODE, not markup. Put HTML in `$solutionguide` in the control block and reference `$solutionguide` there — raw `<div>` in ANSWER is a syntax error.',
  'Braces EVALUATE. `{$x}` in question text is parsed as an expression, so set-builder notation like `{students who own a dog}` breaks the question. Use `&#123;` / `&#125;`.',
  'Use `&&` and `||`. The word operators `and` / `or` are rejected by the parser.',
  'One statement per line. Two statements joined by `;` on one line fails.',
];

/**
 * Build the revision prompt.
 *
 * Scope is stated twice — once as the task and once as a prohibition — because the agent has full
 * filesystem tools here and the question bank is a live teaching asset.
 */
export function buildRevisePrompt(req: ReviseRequest): string {
  return [
    `Revise ONE MyOpenMath question file: ${req.path}`,
    `The teacher refers to it as \`${req.label}\`.`,
    '',
    'TEACHER REQUEST (verbatim):',
    req.instruction.trim(),
    '',
    'RULES for this dialect — breaking one makes MyOpenMath refuse the question:',
    ...MOM_DIALECT_RULES.map((r) => `- ${r}`),
    '',
    'SCOPE:',
    '- Edit ONLY that one file. Do not touch any other file, and do not create new ones.',
    '- Do not change randomization ranges, wording or answers beyond what the request asks for.',
    '- If the request is ambiguous, make the smallest reasonable change and say what you assumed.',
    '',
    'When done, reply with ONE short line describing what changed. No preamble, no code block.',
    '',
    'Current contents:',
    '```',
    req.contents,
    '```',
  ].join('\n');
}

/**
 * A follow-up turn in an existing revision session.
 *
 * The rules, the scope and the file are already in that session's context, so restating them costs
 * tokens and invites the agent to re-litigate a change it already made. Only the re-read matters:
 * the file on disk is now its own last edit, not what it was first shown.
 */
export function buildFollowUpPrompt(instruction: string): string {
  return [
    'Another change to the SAME file, under the same rules and the same scope.',
    'Re-read it from disk first — you have edited it since you last saw it.',
    '',
    'TEACHER REQUEST (verbatim):',
    instruction.trim(),
    '',
    'When done, reply with ONE short line describing what changed. No preamble, no code block.',
  ].join('\n');
}

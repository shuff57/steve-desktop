/**
 * Show a question's answer key in the sandbox preview.
 *
 * The sandbox at `mom.huffpalmer.fyi` renders the QUESTION TEXT section only — it evaluates the
 * control block (so `$answer` and `$solutionguide` are computed) but never emits the ANSWER section.
 * Measured, not assumed: a POST whose ANSWER section holds `$solutionguide` comes back without it.
 *
 * Asking the sandbox twice would not work either. Questions randomize (`rand(...)`), so a second
 * evaluation draws different values and the key would describe a question the teacher is not
 * looking at — worse than no key, because it looks authoritative.
 *
 * So the key is appended INTO the question text and rendered in the SAME pass: one evaluation, one
 * set of random values, question and key guaranteed to agree.
 */

const QUESTION_TEXT = '// === QUESTION TEXT ===';
const ANSWER = '// === ANSWER ===';

/** `$answerbox[0]`, `$answerbox[1]`, … — how many parts the body actually asks for. */
function answerBoxCount(source: string): number {
  const seen = new Set<number>();
  for (const m of source.matchAll(/\$answerbox\[(\d+)\]/g)) seen.add(Number(m[1]));
  return seen.size;
}

/** A question defines `$solutionguide` — referencing it when it does not would render an error. */
function definesSolutionGuide(source: string): boolean {
  return /^\s*\$solutionguide\s*=/m.test(source);
}

/**
 * The declared `$anstypes`, in order — `array("choices","number")` or a bare `"number"`.
 *
 * Needed because a `choices` answer is an INDEX into `$questions[i]`, not the thing the student
 * picks. The repo's own questions do this (`$label_a = $labels[$answer[0]]`), and a key that printed
 * the raw index would show `0` where the teacher needs "Permutation" — useless for the exact case
 * these previews exist to check.
 */
export function answerTypes(source: string): string[] {
  const arr = source.match(/^\s*\$anstypes\s*=\s*array\(([^)]*)\)/m);
  if (arr) return [...arr[1].matchAll(/"([^"]*)"|'([^']*)'/g)].map((m) => m[1] ?? m[2] ?? '');
  const scalar = source.match(/^\s*\$anstypes\s*=\s*(?:"([^"]*)"|'([^']*)')/m);
  return scalar ? [scalar[1] ?? scalar[2] ?? ''] : [];
}

/** `choices` and its relatives resolve through `$questions`; everything else prints its value. */
function isChoice(type: string | undefined): boolean {
  return type === 'choices' || type === 'multans';
}

/**
 * The key panel appended to the body. `$answer[i]` for a multipart question, plain `$answer`
 * otherwise — MOM uses the scalar form when there is a single answer box.
 */
/** Name for a scalar we precompute in the control block. Prefixed so it cannot collide. */
const KEY_VAR = '$__momkey';

/**
 * Control-block lines that resolve each choices answer to its option text.
 *
 * Required, not stylistic: question-text substitution does NOT do nested indexing. Emitting
 * `$questions[0][$answer[0]]` in the body renders the literal string `Array[1]` — `$questions[0]` is
 * substituted as an array, then `[$answer[0]]` is left as text. Verified live before this existed.
 * The control block is real evaluation, so the lookup has to happen there and the body prints a scalar.
 */
function keyControlLines(source: string): string {
  const n = answerBoxCount(source);
  const types = answerTypes(source);
  if (n === 0) return isChoice(types[0]) ? `${KEY_VAR} = $questions[$answer]` : '';
  const lines: string[] = [];
  for (let i = 0; i < n; i++) {
    if (isChoice(types[i])) lines.push(`${KEY_VAR}${i} = $questions[${i}][$answer[${i}]]`);
  }
  return lines.join('\n');
}

function keyBlock(source: string): string {
  const n = answerBoxCount(source);
  const types = answerTypes(source);
  // A choices answer shows the OPTION TEXT it selects, with the raw index beside it so the two can be
  // checked against each other. The text comes from a scalar precomputed in the control block.
  const row = (i: number) =>
    isChoice(types[i])
      ? `<div>[${i}] <b>${KEY_VAR}${i}</b> <span style="opacity:.6">(index $answer[${i}])</span></div>`
      : `<div>[${i}] <b>$answer[${i}]</b></div>`;
  const scalarRow = isChoice(types[0])
    ? `<div><b>${KEY_VAR}</b> <span style="opacity:.6">(index $answer)</span></div>`
    : '<div><b>$answer</b></div>';
  const rows =
    n > 0 ? Array.from({ length: n }, (_, i) => row(i)).join('\n    ') : scalarRow;
  const guide = definesSolutionGuide(source) ? '\n  <div style="margin-top:8px">$solutionguide</div>' : '';
  return `
<div style="margin-top:16px;border:2px dashed #4CAF50;border-radius:8px;padding:10px 12px;background:#f1f8f2">
  <div style="font:bold 12px Arial;letter-spacing:.05em;text-transform:uppercase;opacity:.7;margin-bottom:6px">Answer key — preview only</div>
    ${rows}${guide}
</div>`;
}

/**
 * Return `source` with an answer-key panel appended to its QUESTION TEXT section.
 *
 * Inserted before the ANSWER marker when there is one, so the key lands inside the body the sandbox
 * renders rather than in the section it drops. Returns the source unchanged when there is no
 * QUESTION TEXT marker — a malformed file should render (and fail) exactly as it would without this.
 */
export function withAnswerKey(source: string): string {
  const qtIndex = source.indexOf(QUESTION_TEXT);
  if (qtIndex < 0) return source;

  // Precomputed lookups go at the END of the control block — after the question's own
  // $questions/$answer assignments, which they read.
  const control = keyControlLines(source);
  const head = control
    ? `${source.slice(0, qtIndex).replace(/\s*$/, '')}\n${control}\n\n`
    : source.slice(0, qtIndex);
  const withControl = head + source.slice(qtIndex);

  const qt2 = withControl.indexOf(QUESTION_TEXT);
  const block = keyBlock(source);
  const ansIndex = withControl.indexOf(ANSWER, qt2 + QUESTION_TEXT.length);
  if (ansIndex < 0) return `${withControl.replace(/\s*$/, '')}\n${block}\n`;

  return `${withControl.slice(0, ansIndex).replace(/\s*$/, '')}\n${block}\n\n${withControl.slice(ansIndex)}`;
}

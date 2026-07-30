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
 * The key panel appended to the body. `$answer[i]` for a multipart question, plain `$answer`
 * otherwise — MOM uses the scalar form when there is a single answer box.
 */
function keyBlock(source: string): string {
  const n = answerBoxCount(source);
  const rows =
    n > 0
      ? Array.from({ length: n }, (_, i) => `<div>[${i}] <b>$answer[${i}]</b></div>`).join('\n    ')
      : '<div><b>$answer</b></div>';
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

  const block = keyBlock(source);
  const ansIndex = source.indexOf(ANSWER, qtIndex + QUESTION_TEXT.length);
  if (ansIndex < 0) return `${source.replace(/\s*$/, '')}\n${block}\n`;

  return `${source.slice(0, ansIndex).replace(/\s*$/, '')}\n${block}\n\n${source.slice(ansIndex)}`;
}

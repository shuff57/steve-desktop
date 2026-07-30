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

/** Inline chip shown immediately after an answer box, so the key sits WITH the part it answers. */
function chip(inner: string): string {
  return (
    '<span style="display:inline-block;margin-left:6px;padding:1px 7px;border-radius:10px;' +
    'background:#e8f5e9;border:1px solid #4CAF50;color:#1b5e20;font:600 13px Arial;vertical-align:middle">' +
    inner +
    '</span>'
  );
}

/** The key for part `i`: option text for a choices part, the value otherwise. */
function chipFor(i: number, types: string[]): string {
  return isChoice(types[i])
    ? chip(`${KEY_VAR}${i} <span style="opacity:.65;font-weight:400">(#$answer[${i}])</span>`)
    : chip(`$answer[${i}]`);
}

/** Solution guide only — the per-part answers now live inline, next to their boxes. */
function guideBlock(source: string): string {
  if (!definesSolutionGuide(source)) return '';
  return `
<div style="margin-top:14px;border-top:1px dashed #4CAF50;padding-top:8px">
  <div style="font:bold 11px Arial;letter-spacing:.05em;text-transform:uppercase;opacity:.6;margin-bottom:4px">Solution — preview only</div>
  $solutionguide
</div>`;
}

/**
 * Put a key chip directly after every answer box in the body.
 *
 * Indexed boxes are matched first: a bare `$answerbox` pattern would otherwise also match the
 * `$answerbox` prefix of `$answerbox[0]` and split it.
 */
function annotateBoxes(body: string, types: string[]): string {
  let out = body.replace(/\$answerbox\[(\d+)\]/g, (m, d) => m + chipFor(Number(d), types));
  out = out.replace(/\$answerbox(?!\[|<span)/g, (m) =>
    m + (isChoice(types[0]) ? chip(`${KEY_VAR} <span style="opacity:.65;font-weight:400">(#$answer)</span>`) : chip('$answer')),
  );
  return out;
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

  // Annotate only the BODY. The control block is code, and the ANSWER section is dropped by the
  // sandbox anyway — a chip in either place would be wrong or invisible.
  const bodyStart = withControl.indexOf(QUESTION_TEXT) + QUESTION_TEXT.length;
  const ansIndex = withControl.indexOf(ANSWER, bodyStart);
  const bodyEnd = ansIndex < 0 ? withControl.length : ansIndex;

  const types = answerTypes(source);
  const body = annotateBoxes(withControl.slice(bodyStart, bodyEnd), types);
  const guide = guideBlock(source);
  const annotated = `${body.replace(/\s*$/, '')}\n${guide}\n`;

  return withControl.slice(0, bodyStart) + annotated + withControl.slice(bodyEnd);
}

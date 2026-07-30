/**
 * Tell the teacher when a previewed question is BROKEN.
 *
 * The preview's job is to catch problems before a question reaches students, and until now it
 * could not: the render sandbox answers HTTP 200 for a broken question and prints its diagnostics
 * into the page body, so `renderErr` (transport-level) stayed null and the iframe showed what
 * looked like a real question. Swept all 418 bank questions against the live sandbox: 21 are
 * broken and every one of them previewed as if fine.
 *
 * Two independent sources, because neither alone is enough:
 *  - `renderProblems` reads the sandbox's own diagnostics out of the returned HTML.
 *  - `undefinedVars` is STATIC, for the failure the render cannot express: MOM substitutes an
 *    unassigned variable with the empty string and says nothing. `regression/intro/q18` asks
 *    students to interpret a y-intercept whose value is missing from the equation for exactly
 *    this reason.
 */

/** A question is broken (errors) or suspicious (warnings). Warnings still render. */
export interface QuestionHealth {
  errors: string[];
  warnings: string[];
}

const QUESTION_TEXT = '// === QUESTION TEXT ===';
const ANSWER = '// === ANSWER ===';

/** `&quot;` etc. — the sandbox escapes its own messages before printing them. */
function decode(s: string): string {
  return s
    .replace(/&quot;/g, '"')
    .replace(/&#0?39;|&apos;/g, "'")
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&amp;/g, '&')
    .replace(/&nbsp;/g, ' ')
    .replace(/\s+/g, ' ')
    .trim();
}

/**
 * IMathAS complaining about ITSELF, not about this question.
 *
 * The sandbox runs stateless with no session, so engine globals it expects are absent — a healthy
 * question can still surface "Undefined global variable $myrights on line 486 in parsers.php".
 * The teacher cannot act on it and it appears on questions that render perfectly, so reporting it
 * would train them to ignore the banner. The tell is the file: a question is evaluated TEXT and
 * its own errors are attributed to "Common Control", never to a `.php` file.
 */
function isEngineNoise(msg: string): boolean {
  return /\bin\s+[\w./-]+\.php\b/i.test(msg);
}

/**
 * Diagnostics the sandbox printed into the rendered HTML.
 *
 * Matching is deliberately narrow. A bare `Notice:` / `Warning:` match is NOT safe — questions
 * write those words as prose ("<i>Notice:</i> the two conditional probabilities differ"), and
 * because the solution guide only renders with the key appended, that false positive looked
 * exactly like the answer-key transform breaking the question. A real PHP diagnostic names a
 * variable or carries "on line N".
 */
export function renderProblems(html: string): string[] {
  const found: string[] = [];

  // A question's OWN failure is always attributed to the question — "…of Common Control",
  // "…the text in this question". The question is POSTed text, never a file.
  for (const m of html.matchAll(/Caught error while evaluating[^<\n]{0,240}/gi)) {
    found.push(decode(m[0]));
  }
  for (const m of html.matchAll(
    /(?:Undefined (?:global )?variable|Undefined array key|Undefined index)[^<\n]{0,160}/gi,
  )) {
    if (!isEngineNoise(m[0])) found.push(decode(m[0]));
  }
  for (const m of html.matchAll(/(?:Notice|Warning|Deprecated):[^<\n]{0,120}\bon line \d+/gi)) {
    if (!isEngineNoise(m[0])) found.push(decode(m[0]));
  }
  // Nested indexing does not exist in question-text substitution: `$questions[0][$answer[0]]`
  // prints the literal "Array[1]". Precompute a scalar in the control block instead.
  if (/\bArray\[/.test(html)) {
    found.push('Nested array indexing in question text — renders as the literal "Array[…]".');
  }
  // A variable marker that survived substitution is showing to the student as source.
  const raw = html.match(/\$answerbox(?:\[\d+\])?|\$answer\[\d+\]|\$solutionguide/);
  if (raw) found.push(`"${raw[0]}" was printed literally instead of being substituted.`);

  return [...new Set(found)];
}

/** Supplied by MOM/IMathAS itself, so absence from the control block is not a defect. */
const BUILTINS = new Set(['answerbox', 'answer', 'anstypes', 'questions', 'solutionguide', 'ansprompt']);

/**
 * Variables the QUESTION TEXT prints that the control block never assigns.
 *
 * These render as NOTHING with no diagnostic, so the render cannot reveal them — the sentence just
 * quietly loses a number.
 */
export function undefinedVars(source: string): string[] {
  const qt = source.indexOf(QUESTION_TEXT);
  if (qt < 0) return [];
  const bodyStart = qt + QUESTION_TEXT.length;
  const ans = source.indexOf(ANSWER, bodyStart);
  const body = source.slice(bodyStart, ans < 0 ? source.length : ans);
  const control = source.slice(0, qt);

  const assigned = new Set<string>();
  // The whole left-hand side, then every name in it — because MOM has a multi-assignment form,
  // `$a,$b = diffrands(1,5,2)`, and matching only the name adjacent to `=` sees $b but not $a.
  // That misread `regression/intro/q18` as missing its y-intercept when the value renders fine.
  // `=(?!=)` so a comparison is not mistaken for an assignment.
  const LHS = /((?:\$[A-Za-z_]\w*(?:\[[^\]]*\])?\s*,\s*)*\$[A-Za-z_]\w*(?:\[[^\]]*\])?)\s*=(?!=)/g;
  for (const m of control.matchAll(LHS)) {
    for (const v of m[1].matchAll(/\$([A-Za-z_]\w*)/g)) assigned.add(v[1]);
  }
  for (const m of control.matchAll(/as\s+\$([A-Za-z_]\w*)/g)) assigned.add(m[1]);

  const missing = new Set<string>();
  for (const m of body.matchAll(/\$([A-Za-z_]\w*)/g)) {
    if (!assigned.has(m[1]) && !BUILTINS.has(m[1])) missing.add(m[1]);
  }
  return [...missing];
}

/** Everything the preview knows about whether this question is sound. */
export function questionHealth(source: string, html: string): QuestionHealth {
  const undef = undefinedVars(source);
  return {
    errors: renderProblems(html),
    warnings: undef.length
      ? [
          `Never assigned, so ${undef.length === 1 ? 'it renders' : 'they render'} as blank: ` +
            undef.map((v) => `$${v}`).join(', '),
        ]
      : [],
  };
}

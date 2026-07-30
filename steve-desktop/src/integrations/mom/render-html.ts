/**
 * Repair the render sandbox's MathJax configuration before the HTML reaches the preview iframe.
 *
 * The sandbox ships this config:
 *
 *     tex:{inlineMath:[["\(","\)"],["$","$"]]}
 *
 * Both pairs are wrong for MyOpenMath content, and the first is wrong by accident:
 *
 * 1. `"\("` is NOT a backslash-paren. `\(` is not a valid JS escape, so the backslash is dropped and
 *    the delimiter is the single character `(`. Every parenthesised phrase on the page is therefore
 *    typeset as math — "(the amount the borrower actually receives)" comes out in math italics.
 * 2. `["$","$"]` makes anything between two dollar signs math. MOM writes currency with `$` all over
 *    ("$3400", `$ansprompt = "Interest: $"`), so ordinary prose between two amounts becomes math too.
 *
 * MyOpenMath does not use TeX dollar delimiters at all — IMathAS marks math with BACKTICKS, and the
 * sandbox already configures `asciimath.delimiters` for that. So TeX inline math can be restricted to
 * the real `\(…\)` form without losing anything the question bank actually uses.
 *
 * Fixed here rather than in the sandbox because the sandbox's source lives in neither repo. If it is
 * ever corrected upstream this becomes a no-op: the replacement is idempotent.
 */

/** `\(…\)` and `\[…\]`, written so the emitted JS string really is a backslash followed by a paren. */
const FIXED_TEX = String.raw`tex:{inlineMath:[["\\(","\\)"]],displayMath:[["\\[","\\]"]]}`;

/**
 * Rewrite the `tex:{…}` MathJax option block so only genuine TeX delimiters trigger math.
 *
 * Returns the HTML unchanged when there is no `tex:{…}` block — a sandbox that stops emitting one is
 * not a reason to mangle the page.
 */
export function fixMathDelimiters(html: string): string {
  return html.replace(/tex:\s*\{[^{}]*\}/, FIXED_TEX);
}

/** Everything the preview does to sandbox HTML before it becomes the iframe's `srcdoc`. */
export function prepareRenderHtml(html: string): string {
  return fixMathDelimiters(html);
}

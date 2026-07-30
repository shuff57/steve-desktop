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

import { isEngineNoise } from './health';

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

/**
 * Fit the render to a PANE instead of a page.
 *
 * The sandbox styles its output as a standalone document — `max-width:760px; margin:1.5rem auto;
 * padding:0 1rem` — which inside the preview iframe shows as a narrow centred column with gutters and
 * a dead band at the top. Paragraph margins then stack with the question's own `line-height:1.6`, so
 * multipart questions drift apart.
 *
 * Only layout is touched. Fonts, colours and the question's own inline styling are left alone: this
 * pane is meant to show what MyOpenMath will show.
 */
const PANE_CSS = `<style>
  body{max-width:none!important;margin:0!important;padding:10px 12px!important}
  .question p{margin:.5em 0}
  .question hr{margin:.85em 0;border:0;border-top:1px solid rgba(0,0,0,.18)}
  .question .toppad{margin:.25em 0}
</style>`;

/** Put the pane rules last in `<head>` so they win over the sandbox's own block. */
export function fitToPane(html: string): string {
  if (html.includes('data-pane-css')) return html;
  const css = PANE_CSS.replace('<style>', '<style data-pane-css>');
  return html.includes('</head>') ? html.replace('</head>', `${css}</head>`) : css + html;
}

/**
 * Drop the sandbox's complaints about ITSELF from the rendered page.
 *
 * Running stateless with no session, IMathAS prints things like
 * `<div class="qerr">Caught warning in the question code: Undefined global variable $myrights on
 * line 486 in parsers.php</div>` at the top of questions that are perfectly healthy. It reads as
 * though the teacher's question is broken when nothing is wrong with it, and there is no action
 * they could take.
 *
 * A question's OWN failures are kept — those are attributed to "Common Control" rather than to a
 * `.php` file, and they are exactly what the preview exists to show.
 */
export function stripEngineNoise(html: string): string {
  return html.replace(/<div class="qerr">([\s\S]*?)<\/div>/g, (m, inner) => (isEngineNoise(inner) ? '' : m));
}

/** Everything the preview does to sandbox HTML before it becomes the iframe's `srcdoc`. */
export function prepareRenderHtml(html: string): string {
  return fitToPane(fixMathDelimiters(stripEngineNoise(html)));
}

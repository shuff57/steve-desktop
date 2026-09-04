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

/**
 * Recolour the render for a dark app, WITHOUT touching layout or the question's own styling.
 *
 * Everything here is a colour, and each one is a `filter`-free override so MathJax's SVG output and
 * the question's own inline colours keep working. Inputs get an explicit background: leaving them at
 * the UA default paints a white box in the middle of a dark question.
 *
 * This is deliberately NOT the default. MyOpenMath serves students a light page, so a dark preview
 * is a comfort setting, not a faithful one — which is why the caller offers it as a toggle.
 */
const DARK_CSS = `<style data-dark-css>
  html,body{background:#141220!important;color:#e8e6f0!important}
  .question,.question *{border-color:rgba(255,255,255,.22)!important}
  .question hr{border-top-color:rgba(255,255,255,.22)!important}
  a{color:#8ab4ff!important}
  /* Radios and checkboxes are EXCLUDED: painting a background over a native control makes an
     unselected radio render as a solid filled dot, so every option on a multiple-choice question
     looks already chosen. color-scheme:dark is what actually recolours them — it asks the UA to
     draw its own control dark instead of us drawing over it. */
  input:not([type=radio]):not([type=checkbox]),select,textarea{background:#1e1b2b!important;
    color:#e8e6f0!important;border:1px solid rgba(255,255,255,.28)!important}
  input[type=radio],input[type=checkbox]{color-scheme:dark;accent-color:#8ab4ff}
  button,input[type=button],input[type=submit]{background:rgba(255,255,255,.10)!important;
    color:#e8e6f0!important;border:1px solid rgba(255,255,255,.28)!important}
  table,th,td{border-color:rgba(255,255,255,.22)!important}
  th{background:rgba(255,255,255,.06)!important}

  /* The part cards are painted by INLINE style attributes on the question's own markup, which no
     ordinary rule can reach — an !important author rule is what outranks a normal inline style.
     Matched by their literal colour so the coding survives: blue still reads as a part label and
     green still reads as an answer, instead of every callout flattening to one grey.
     Grouped by what the colour MEANS, not one line per hex, because the bank uses 35 distinct
     background colours and half of them are the same intent spelled differently. Matching is by
     SUBSTRING, so the #fff rule already catches #fff9ea, #fff3e0 and friends.
     ponytail: enumerated, not computed — CSS cannot ask "is this colour light?". darkCssCovers
     below is the guard: a new palette colour fails a test instead of quietly rendering a white
     band in a dark question, which is how #f6f8fc shipped as an unreadable Total row. */
  [style*="#fff"]{background:#1e1b2b!important;color:#e8e6f0!important}

  /* neutral greys and near-whites — cards, zebra rows, table totals */
  [style*="#fafafa"],[style*="#f2f2f2"],[style*="#f8f8f8"],[style*="#f7f9fa"],[style*="#f6f8fc"],
  [style*="#eef2f7"],[style*="#f3f4f6"],[style*="#f8f9fa"],[style*="#f5f5f5"],[style*="#e5e7eb"],
  [style*="#fbfbfd"],[style*="#f8fafc"]
    {background:rgba(255,255,255,.05)!important;color:#e8e6f0!important}

  /* blues — part labels and prompts */
  [style*="#e8f0fe"],[style*="#f0f4ff"],[style*="#e3f2fd"],[style*="#eef2ff"],[style*="#d1ecf1"],
  [style*="#eef4ff"],[style*="#eef4fb"]
    {background:rgba(59,130,246,.22)!important;color:#dbe6ff!important}

  /* greens — answers and confirmations */
  [style*="#e8f5e9"],[style*="#bbf7d0"],[style*="#f0fff4"],[style*="#c3e6cb"]
    {background:rgba(34,197,94,.20)!important;color:#d8f5de!important}

  /* ambers — cautions and worked-example callouts */
  [style*="#fed7aa"],[style*="#fef9e7"]{background:rgba(245,158,11,.20)!important;color:#f7e6c4!important}

  /* purples and pinks — the rarer accent cards */
  [style*="#e9d5ff"],[style*="#f5f0ff"]{background:rgba(168,85,247,.20)!important;color:#ecd9ff!important}
  [style*="#fce4ec"]{background:rgba(236,72,153,.20)!important;color:#fbd5e5!important}

  /* pale reds — the "a classmate wrote this, find the flaw" callout, paired with a #b91c1c rule */
  [style*="#fef7f7"],[style*="#fef2f2"]{background:rgba(239,68,68,.18)!important;color:#fbd9d9!important}

  /* The same colours again where the sandbox sets them from a stylesheet rather than inline. A
     [style*=] selector cannot reach these: the colour lives in a class rule, not a style attribute,
     so each one needs naming here as well as in the lists above. */
  .sol-wrap details{background:#1e1b2b!important}
  .sol-wrap summary{background:rgba(59,130,246,.22)!important;color:#dbe6ff!important}
  /* The colour matters as much as the background here: .sol-wrap carries an inline color:#21242c,
     which the body text inherits, so recolouring only the background leaves the marking notes dark
     grey on dark grey. Caught by looking at a render — the bank-wide guard scans background
     declarations and cannot see a dark colour sitting on a dark background. */
  .sol-body{background:rgba(255,255,255,.05)!important;color:#e8e6f0!important}
  /* the full-credit model answer on the FRQ solution guides — blue, paired with a #1865f2 border */
  .ideal{background:rgba(59,130,246,.22)!important;color:#dbe6ff!important}
</style>`;

/** Every hex the dark sheet targets, read out of the sheet itself so the two cannot drift apart. */
const DARK_CSS_COLOURS = [...DARK_CSS.matchAll(/\[style\*="(#[0-9a-fA-F]{3,6})"\]/g)].map((m) =>
  m[1].toLowerCase(),
);

/**
 * Would the dark sheet recolour an inline `background:<hex>`?
 *
 * Asks the same question the browser does — the selectors match by SUBSTRING, so `#fff` covers
 * `#fff9ea`. An uncovered colour is not a cosmetic miss: the card keeps its light background while
 * the surrounding text turns light, so the content becomes unreadable rather than merely ugly.
 * The bank-wide test uses this; it is exported for that reason.
 */
export function darkCssCovers(hex: string): boolean {
  const c = hex.toLowerCase();
  return DARK_CSS_COLOURS.some((m) => c.includes(m));
}

/** Append the dark overrides, last in `<head>` so they beat the sandbox's own rules. */
export function darkenRender(html: string): string {
  if (html.includes('data-dark-css')) return html;
  return html.includes('</head>') ? html.replace('</head>', `${DARK_CSS}</head>`) : DARK_CSS + html;
}

/**
 * Everything the preview does to sandbox HTML before it becomes the iframe's `srcdoc`.
 *
 * `dark` is opt-in: the honest default is the light page a student actually sees.
 */
export function prepareRenderHtml(html: string, dark = false): string {
  const prepared = fitToPane(fixMathDelimiters(stripEngineNoise(html)));
  return dark ? darkenRender(prepared) : prepared;
}

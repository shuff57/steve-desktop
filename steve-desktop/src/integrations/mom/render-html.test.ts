import { describe, it, expect } from 'vitest';
import { readdirSync, readFileSync, statSync } from 'node:fs';
import { join } from 'node:path';
import {
  fixMathDelimiters,
  prepareRenderHtml,
  fitToPane,
  stripEngineNoise,
  darkenRender,
  darkCssCovers,
} from './render-html';

// The exact config the sandbox returned on 2026-07-30.
const SANDBOX_HEAD =
  '<script>window.MathJax={loader:{load:["input/asciimath","input/tex","output/chtml"]},' +
  'asciimath:{delimiters:[["`","`"]]},tex:{inlineMath:[["\\(","\\)"],["$","$"]]}};</script>';

describe('fitToPane', () => {
  const PAGE = `<!doctype html><head>${SANDBOX_HEAD}</head><body><div class="question">hi</div></body>`;

  it('overrides the standalone-page body sizing so the render fills the pane', () => {
    // The sandbox sets max-width:760px; margin:1.5rem auto — a centred column with gutters and a
    // dead band at the top once it is inside the preview iframe.
    const out = fitToPane(PAGE);
    expect(out).toContain('max-width:none!important');
    expect(out).toContain('margin:0!important');
  });

  it('puts the override last in head so it beats the sandbox block', () => {
    const out = fitToPane(PAGE);
    expect(out.indexOf('data-pane-css')).toBeGreaterThan(out.indexOf('window.MathJax'));
    expect(out.indexOf('data-pane-css')).toBeLessThan(out.indexOf('</head>'));
  });

  it('is idempotent', () => {
    const once = fitToPane(PAGE);
    expect(fitToPane(once)).toBe(once);
  });

  it('still applies when there is no head element', () => {
    expect(fitToPane('<div class="question">hi</div>')).toContain('data-pane-css');
  });
});

describe('fixMathDelimiters', () => {
  it('stops a bare paren from being a math delimiter', () => {
    // `"\("` is not a backslash-paren — \( is not a valid JS escape, so it is just "(", and every
    // parenthesised phrase on the page was being typeset as math.
    const out = fixMathDelimiters(SANDBOX_HEAD);
    expect(out).not.toContain('[["\\(","\\)"],["$","$"]]');
    expect(out).toContain('inlineMath:[["\\\\(","\\\\)"]]');
  });

  it('stops dollar signs from being math delimiters', () => {
    // MOM writes currency with $ constantly, so prose between two amounts became math.
    expect(fixMathDelimiters(SANDBOX_HEAD)).not.toContain('["$","$"]');
  });

  it('pins displayMath too, so $$ cannot open display math by default', () => {
    expect(fixMathDelimiters(SANDBOX_HEAD)).toContain('displayMath:[["\\\\[","\\\\]"]]');
  });

  it('leaves the asciimath backtick config alone — that is what MOM actually uses', () => {
    expect(fixMathDelimiters(SANDBOX_HEAD)).toContain('asciimath:{delimiters:[["`","`"]]}');
  });

  it('keeps the rest of the document intact', () => {
    const page = `<!doctype html><head>${SANDBOX_HEAD}</head><body><div class="question">A (parenthetical) and $500.</div></body>`;
    const out = fixMathDelimiters(page);
    expect(out).toContain('<div class="question">A (parenthetical) and $500.</div>');
    expect(out).toContain('<!doctype html>');
  });

  it('is idempotent, so an upstream fix does not double-apply', () => {
    const once = fixMathDelimiters(SANDBOX_HEAD);
    expect(fixMathDelimiters(once)).toBe(once);
  });

  it('returns HTML with no tex block unchanged rather than mangling it', () => {
    const noTex = '<script>window.MathJax={asciimath:{delimiters:[["`","`"]]}};</script>';
    expect(fixMathDelimiters(noTex)).toBe(noTex);
  });

  it('prepareRenderHtml applies the delimiter fix AND the pane fit', () => {
    const out = prepareRenderHtml(SANDBOX_HEAD);
    expect(out).toContain('inlineMath:[["\\\\(","\\\\)"]]');
    expect(out).toContain('data-pane-css');
  });
});

describe('stripEngineNoise', () => {
  /**
   * The exact banner a teacher reported seeing on a healthy question. IMathAS emits it because the
   * sandbox runs with no session, not because anything is wrong with the question.
   */
  it('removes IMathAS complaining about its own internals', () => {
    const html =
      '<body><div class="qerr">Caught warning in the question code: Undefined global variable $myrights on line 486 in parsers.php</div><div class="question"><p>A researcher…</p></div></body>';
    const out = stripEngineNoise(html);
    expect(out).not.toContain('myrights');
    expect(out).not.toContain('qerr');
    expect(out).toContain('A researcher');
  });

  it('KEEPS a failure attributed to the question itself', () => {
    const html =
      '<div class="qerr">Caught error while evaluating the code in this question: syntax error on line 94 of Common Control</div>';
    expect(stripEngineNoise(html)).toBe(html);
  });

  it('leaves a page with no diagnostics alone', () => {
    expect(stripEngineNoise('<p>fine</p>')).toBe('<p>fine</p>');
  });

  it('is applied by prepareRenderHtml', () => {
    const html = '<head></head><body><div class="qerr">Undefined global variable $myrights in parsers.php</div></body>';
    expect(prepareRenderHtml(html)).not.toContain('myrights');
  });
});

describe('darkenRender', () => {
  const PAGE = `<!doctype html><head>${SANDBOX_HEAD}</head><body><div class="question">hi</div></body>`;

  it('is off by default — a student sees a light page, so that is the honest preview', () => {
    expect(prepareRenderHtml(PAGE)).not.toContain('data-dark-css');
  });

  it('recolours only when asked', () => {
    expect(prepareRenderHtml(PAGE, true)).toContain('data-dark-css');
  });

  it('gives answer boxes an explicit background', () => {
    // Left at the UA default they paint a white box in the middle of a dark question.
    expect(darkenRender(PAGE)).toMatch(/input[^{]*\{[^}]*background/);
  });

  it('lands after the sandbox stylesheet, or the sandbox wins', () => {
    const out = darkenRender(PAGE);
    expect(out.indexOf('data-dark-css')).toBeGreaterThan(out.indexOf('MathJax'));
    expect(out).toContain('</head>');
  });

  it('is idempotent — re-preparing an already-dark page does not stack stylesheets', () => {
    const once = darkenRender(PAGE);
    expect(darkenRender(once)).toBe(once);
  });

  it('does not disturb the pane layout fix', () => {
    expect(prepareRenderHtml(PAGE, true)).toContain('data-pane-css');
  });
});

describe('the dark sheet covers every colour the bank actually paints', () => {
  /**
   * An inline `background:#xxx` the dark sheet does not match keeps its LIGHT background while the
   * surrounding text turns light — unreadable, not merely ugly. That is how `#f6f8fc` shipped as an
   * invisible "Total" row on the 1.3 frequency tables, found by looking at a render rather than by
   * any check. Twenty colours were uncovered at that point, across 556 uses.
   *
   * This walks the real bank rather than a fixture: the failure mode is a question author reaching
   * for a new colour, which no fixture would ever contain.
   */
  const ROOT = new URL('../../../mom-content/questions', import.meta.url).pathname.replace(/^\//, '');

  function allPhp(dir: string): string[] {
    const out: string[] = [];
    for (const name of readdirSync(dir)) {
      const p = join(dir, name);
      if (statSync(p).isDirectory()) out.push(...allPhp(p));
      else if (name.endsWith('.php')) out.push(p);
    }
    return out;
  }

  it('leaves no inline background unrecoloured', () => {
    const uncovered = new Map<string, number>();
    for (const file of allPhp(ROOT)) {
      for (const m of readFileSync(file, 'utf8').matchAll(/background:\s*(#[0-9a-fA-F]{3,6})/g)) {
        const hex = m[1].toLowerCase();
        if (!darkCssCovers(hex)) uncovered.set(hex, (uncovered.get(hex) ?? 0) + 1);
      }
    }
    // Named in the message so a failure says WHICH colour to add, not just that one is missing.
    expect([...uncovered.keys()].sort()).toEqual([]);
  });

  it('matches by substring, the way the selectors do', () => {
    expect(darkCssCovers('#fff9ea')).toBe(true); // caught by the #fff rule
    expect(darkCssCovers('#123456')).toBe(false);
  });
});

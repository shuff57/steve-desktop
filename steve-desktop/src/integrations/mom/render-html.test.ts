import { describe, it, expect } from 'vitest';
import { fixMathDelimiters, prepareRenderHtml, fitToPane } from './render-html';

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

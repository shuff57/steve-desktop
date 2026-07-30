import { describe, it, expect } from 'vitest';
import { fixMathDelimiters, prepareRenderHtml } from './render-html';

// The exact config the sandbox returned on 2026-07-30.
const SANDBOX_HEAD =
  '<script>window.MathJax={loader:{load:["input/asciimath","input/tex","output/chtml"]},' +
  'asciimath:{delimiters:[["`","`"]]},tex:{inlineMath:[["\\(","\\)"],["$","$"]]}};</script>';

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

  it('prepareRenderHtml applies the delimiter fix', () => {
    expect(prepareRenderHtml(SANDBOX_HEAD)).toBe(fixMathDelimiters(SANDBOX_HEAD));
  });
});

import { describe, it, expect } from 'vitest';
import { withCheckData, checkableParts, injectChecker } from './answer-check';

const multipart = `// === NAME - DESCRIPTION: t ===
// === SET QUESTION TYPE TO: multipart ===
// === COMMON CONTROL ===
$anstypes = array("number","number")
$answer[0] = 12
$answer[1] = 34
$abstolerance = 0.01
// === QUESTION TEXT ===
<p>a $answerbox[0]</p>
<p>b $answerbox[1]</p>
// === ANSWER ===
$solutionguide
`;

const single = `// === COMMON CONTROL ===
$anstypes = "choices"
$answer = 2
// === QUESTION TEXT ===
<p>pick $answerbox</p>
// === ANSWER ===
`;

describe('withCheckData', () => {
  it('embeds one substituted answer per part, before the ANSWER marker', () => {
    const out = withCheckData(multipart);
    expect(out).toContain('data-n="2"');
    expect(out).toContain('data-a0="$answer[0]"');
    expect(out).toContain('data-a1="$answer[1]"');
    expect(out.indexOf('__momcheck')).toBeLessThan(out.indexOf('// === ANSWER ==='));
  });

  it('carries the declared tolerance so a rounded entry is not called wrong', () => {
    expect(withCheckData(multipart)).toContain('data-tol="0.01"');
  });

  it('uses the scalar $answer form when there is a single box', () => {
    const out = withCheckData(single);
    expect(out).toContain('data-a0="$answer"');
    expect(out).toContain('data-t0="choices"');
  });

  it('leaves a file with no QUESTION TEXT marker alone', () => {
    expect(withCheckData('$a = 1')).toBe('$a = 1');
  });

  it('hides the marker, since it holds the answers', () => {
    expect(withCheckData(multipart)).toContain('style="display:none"');
  });
});

describe('checkableParts', () => {
  it('counts numeric and choices parts', () => {
    expect(checkableParts(multipart)).toBe(2);
    expect(checkableParts(single)).toBe(1);
  });

  /**
   * numfunc is judged by expression equivalence, which a string compare gets wrong — `2x` vs
   * `x*2`. Declining is better than confidently marking a correct answer wrong.
   */
  /**
   * Regression: regression/intro/q18 is a real `choices` question with NO `$anstypes` — its type
   * lives only in the header. Without that fallback the type resolved to empty and the checker
   * disabled itself on a question it can judge exactly.
   */
  it('falls back to the SET QUESTION TYPE header when $anstypes is absent', () => {
    const noAnstypes = `// === SET QUESTION TYPE TO: choices ===
// === COMMON CONTROL ===
$answer = 3
// === QUESTION TEXT ===
<p>pick $answerbox</p>
`;
    expect(checkableParts(noAnstypes)).toBe(1);
    expect(withCheckData(noAnstypes)).toContain('data-t0="choices"');
  });

  it('does not let the header type stand in for missing per-part types', () => {
    const multi = `// === SET QUESTION TYPE TO: multipart ===
// === COMMON CONTROL ===
$answer[0] = 1
// === QUESTION TEXT ===
<p>a $answerbox[0]</p>
`;
    expect(checkableParts(multi)).toBe(0);
  });

  it('refuses numfunc rather than guessing at equivalence', () => {
    const q = multipart.replace('array("number","number")', 'array("numfunc","numfunc")');
    expect(checkableParts(q)).toBe(0);
  });
});

describe('injectChecker', () => {
  it('inserts the script inside body', () => {
    const out = injectChecker('<html><body><p>hi</p></body></html>');
    expect(out).toContain('data-momcheck_script');
    expect(out.indexOf('data-momcheck_script')).toBeLessThan(out.indexOf('</body>'));
  });

  it('is idempotent, so a re-render does not stack checkers', () => {
    const once = injectChecker('<body></body>');
    expect(injectChecker(once)).toBe(once);
  });

  it('still appends when there is no body tag', () => {
    expect(injectChecker('<p>x</p>')).toContain('data-momcheck_script');
  });
});

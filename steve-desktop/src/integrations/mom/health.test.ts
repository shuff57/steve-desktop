import { describe, it, expect } from 'vitest';
import { renderProblems, undefinedVars, questionHealth } from './health';

/**
 * Every string here came off the live sandbox during the 418-question sweep, not from
 * imagination — the detector exists to catch these exact shapes.
 */
describe('renderProblems', () => {
  it('finds the sandbox\'s caught-error message and unescapes it', () => {
    const html = `<div class="err">Caught error while evaluating the code in this question: syntax error, unexpected token &quot;&lt;&quot;, expecting end of file on line 89 of Common Control</div>`;
    const [msg] = renderProblems(html);
    expect(msg).toContain('syntax error');
    expect(msg).toContain('"<"'); // decoded
    expect(msg).toContain('line 89');
  });

  /**
   * Regression: this exact message appears on `regression/intro/q18`, which renders perfectly.
   * $myrights is an IMathAS session global, absent because the sandbox is stateless — engine
   * noise the teacher cannot act on. Reporting it would train them to ignore the banner.
   */
  it('ignores IMathAS complaining about its own .php internals', () => {
    const html = '<p>Undefined global variable $myrights on line 465 in QuestionHtmlGenerator.php</p>';
    expect(renderProblems(html)).toEqual([]);
  });

  it('still reports an undefined-variable diagnostic that names no engine file', () => {
    expect(renderProblems('<p>Undefined variable $total</p>')[0]).toContain('$total');
  });

  it('flags nested indexing that leaked through as "Array[…]"', () => {
    expect(renderProblems('<p>Pick: Array[1]</p>')[0]).toMatch(/Nested array indexing/);
  });

  it('flags a variable marker that was never substituted', () => {
    expect(renderProblems('<p>$answerbox[0]</p>')[0]).toMatch(/printed literally/);
  });

  it('stays silent on a healthy render', () => {
    expect(renderProblems('<p>What is 2 plus 2? <input></p>')).toEqual([]);
  });

  /**
   * Regression: `probability/q14-twoway-conditional` writes "Notice:" as PROSE in its solution
   * guide. A bare /Notice:/ match reported it as broken, and because the guide only renders with
   * the answer key appended, it looked like the answer-key transform was at fault.
   */
  it('does not treat the word "Notice:" in prose as a PHP diagnostic', () => {
    const html = '<p><i>Notice:</i> the two conditional probabilities differ because the denominators differ.</p>';
    expect(renderProblems(html)).toEqual([]);
  });

  it('catches a notice attributed to the question, not to an engine file', () => {
    // "of Common Control" means the question's own code; PHP emits its diagnostics as one
    // unbroken run of text, which is what lets the match require "on line N" safely.
    expect(renderProblems('Notice: bad offset on line 12 of Common Control').length).toBe(1);
  });

  it('suppresses the same notice when it points at the sandbox harness', () => {
    expect(renderProblems('Notice: Undefined offset: 3 in /app/serve.php on line 12')).toEqual([]);
  });
});

describe('undefinedVars', () => {
  const wrap = (control: string, body: string) =>
    `// === COMMON CONTROL ===\n${control}\n// === QUESTION TEXT ===\n${body}\n// === ANSWER ===\n`;

  it('reports a variable the body prints but the control block never assigns', () => {
    // The real regression/intro/q18 shape: an intercept that renders as blank.
    expect(undefinedVars(wrap('$b = 4.3', '<p>`haty = $a - $b x`</p>'))).toEqual(['a']);
  });

  /**
   * Regression: `regression/intro/q18` assigns with MOM's multi-assignment form. Matching only
   * the name adjacent to `=` saw $b but not $a, and reported a y-intercept as blank when it
   * renders as a real number.
   */
  it('accepts the multi-assignment form `$a,$b = …`', () => {
    expect(undefinedVars(wrap('$a,$b = diffrands(1,5,2)', '<p>`haty = $a - $b x`</p>'))).toEqual([]);
  });

  it('accepts indexed and foreach assignments', () => {
    expect(undefinedVars(wrap('$v[0] = 3\nforeach ($xs as $row)', '<p>$v $row</p>'))).toEqual([]);
  });

  it('does not flag MOM builtins', () => {
    expect(undefinedVars(wrap('$x = 1', '<p>$x $answerbox[0] $solutionguide</p>'))).toEqual([]);
  });

  it('ignores currency, which is a dollar sign followed by a digit', () => {
    expect(undefinedVars(wrap('$P = 100', '<p>Pay $500 on $P</p>'))).toEqual([]);
  });

  it('does not read the ANSWER section, which the sandbox drops', () => {
    const src = '// === COMMON CONTROL ===\n$x = 1\n// === QUESTION TEXT ===\n<p>$x</p>\n// === ANSWER ===\n$onlyhere\n';
    expect(undefinedVars(src)).toEqual([]);
  });

  it('returns nothing for a file with no QUESTION TEXT marker', () => {
    expect(undefinedVars('$a = 1')).toEqual([]);
  });
});

describe('questionHealth', () => {
  it('separates a render error from a static warning', () => {
    const src = '// === COMMON CONTROL ===\n$b = 1\n// === QUESTION TEXT ===\n<p>$a $b</p>\n';
    const h = questionHealth(src, '<p>Caught error while evaluating the code in this question: boom</p>');
    expect(h.errors).toHaveLength(1);
    expect(h.warnings[0]).toContain('$a');
  });

  it('is clean for a sound question', () => {
    const src = '// === COMMON CONTROL ===\n$a = 1\n// === QUESTION TEXT ===\n<p>$a</p>\n';
    expect(questionHealth(src, '<p>1</p>')).toEqual({ errors: [], warnings: [] });
  });
});

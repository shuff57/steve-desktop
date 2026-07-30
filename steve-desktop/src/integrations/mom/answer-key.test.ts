import { describe, it, expect } from 'vitest';
import { withAnswerKey } from './answer-key';

const MULTIPART = `// === NAME - DESCRIPTION: 6.1 Simple Interest ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$P = rand(5, 50) * 100
$I = round($P * 0.05 * 2, 2)
$anstypes = array("number","number")
$answer[0] = $I
$answer[1] = $P
$solutionguide = '<b>I = Prt</b>'

// === QUESTION TEXT ===

Find the interest. $answerbox[0]
Find the principal. $answerbox[1]

// === ANSWER ===

$solutionguide
`;

const SINGLE = `// === NAME - DESCRIPTION: probe ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===
$a = 2
$answer = $a
$anstypes = "number"

// === QUESTION TEXT ===
What is $a? $answerbox

// === ANSWER ===
`;

describe('withAnswerKey', () => {
  it('puts the key INSIDE the question text, before the ANSWER marker', () => {
    // The sandbox renders QUESTION TEXT and drops ANSWER, so a key after the marker is invisible.
    const out = withAnswerKey(MULTIPART);
    expect(out.indexOf('Answer key')).toBeGreaterThan(out.indexOf('// === QUESTION TEXT ==='));
    expect(out.indexOf('Answer key')).toBeLessThan(out.indexOf('// === ANSWER ==='));
  });

  it('emits one row per answerbox for a multipart question', () => {
    const out = withAnswerKey(MULTIPART);
    expect(out).toContain('$answer[0]');
    expect(out).toContain('$answer[1]');
    expect(out).not.toContain('$answer[2]');
  });

  it('uses the scalar $answer when the body has a single unindexed answerbox', () => {
    const out = withAnswerKey(SINGLE);
    expect(out).toContain('<b>$answer</b>');
    expect(out).not.toContain('$answer[0]');
  });

  it('includes $solutionguide only when the question defines it', () => {
    // Referencing an undefined $solutionguide renders an error where the key should be.
    expect(withAnswerKey(MULTIPART)).toContain('$solutionguide');
    expect(withAnswerKey(SINGLE)).not.toContain('$solutionguide');
  });

  it('counts distinct indices, not repeated references to the same box', () => {
    const repeated = MULTIPART.replace('Find the principal. $answerbox[1]', 'Again $answerbox[0] $answerbox[1]');
    const out = withAnswerKey(repeated);
    expect(out).toContain('$answer[1]');
    expect(out).not.toContain('$answer[2]');
  });

  it('preserves the original body and control block verbatim', () => {
    const out = withAnswerKey(MULTIPART);
    expect(out).toContain('$P = rand(5, 50) * 100');
    expect(out).toContain('Find the interest. $answerbox[0]');
    expect(out).toContain('// === ANSWER ===');
  });

  it('appends at the end when there is no ANSWER marker', () => {
    const noAnswer = MULTIPART.slice(0, MULTIPART.indexOf('// === ANSWER ==='));
    const out = withAnswerKey(noAnswer);
    expect(out).toContain('Answer key');
    expect(out).toContain('$answerbox[1]');
  });

  it('returns a malformed source unchanged rather than guessing', () => {
    // No QUESTION TEXT marker: let it render and fail as it normally would.
    const junk = '$a = 1\n$answer = $a\n';
    expect(withAnswerKey(junk)).toBe(junk);
  });
});

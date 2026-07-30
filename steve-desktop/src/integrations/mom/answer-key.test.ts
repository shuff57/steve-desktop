import { describe, it, expect } from 'vitest';
import { withAnswerKey, answerTypes } from './answer-key';

const CHOICES = `// === NAME - DESCRIPTION: perm or comb ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("choices", "number")
$answer[0] = 1
$answer[1] = 42
$questions[0] = array("Permutation", "Combination")

// === QUESTION TEXT ===
Which is it? $answerbox[0]
How many? $answerbox[1]

// === ANSWER ===
`;

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
  it('puts each key INLINE, right after the box it answers', () => {
    // Was a panel at the bottom; the teacher has to scroll and match up indices by eye.
    const out = withAnswerKey(MULTIPART);
    const box0 = out.indexOf('$answerbox[0]');
    const key0 = out.indexOf('$answer[0]', box0);
    const box1 = out.indexOf('$answerbox[1]');
    expect(key0).toBeGreaterThan(box0);
    expect(key0).toBeLessThan(box1); // part 0's key lands before part 1 begins
  });

  it('keeps the annotation inside the body, not the control block or ANSWER section', () => {
    const out = withAnswerKey(MULTIPART);
    const firstChip = out.indexOf('background:#e8f5e9');
    expect(firstChip).toBeGreaterThan(out.indexOf('// === QUESTION TEXT ==='));
    expect(firstChip).toBeLessThan(out.indexOf('// === ANSWER ==='));
  });

  it('emits one chip per answerbox for a multipart question', () => {
    const out = withAnswerKey(MULTIPART);
    expect(out).toContain('$answer[0]');
    expect(out).toContain('$answer[1]');
    expect(out).not.toContain('$answer[2]');
  });

  it('uses the scalar $answer when the body has a single unindexed answerbox', () => {
    const out = withAnswerKey(SINGLE);
    expect(out).toContain('$answerbox<span');
    expect(out).not.toContain('$answer[0]');
  });

  it('does not split an indexed box by matching the bare $answerbox prefix', () => {
    const out = withAnswerKey(MULTIPART);
    expect(out).toContain('$answerbox[0]<span');
    expect(out).not.toContain('$answerbox<span style="display:inline-block;margin-left:6px;padding:1px 7px;border-radius:10px;background:#e8f5e9;border:1px solid #4CAF50;color:#1b5e20;font:600 13px Arial;vertical-align:middle">$answer</span>[0]');
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
    expect(out).toContain('$answerbox[1]<span');
  });

  it('resolves a choices answer to its option TEXT, not the raw index', () => {
    // $answer[0] is an index into $questions[0]; printing it raw shows "1" where the teacher needs
    // "Combination". This is the case multiple-choice previews exist to check.
    const out = withAnswerKey(CHOICES);
    // The lookup MUST be in the control block: question-text substitution has no nested indexing,
    // so `$questions[0][$answer[0]]` in the body renders the literal string `Array[1]` (seen live).
    expect(out).toContain('$__momkey0 = $questions[0][$answer[0]]');
    expect(out.indexOf('$__momkey0 =')).toBeLessThan(out.indexOf('// === QUESTION TEXT ==='));
    expect(out).toContain('$__momkey0 <span');
    expect(out).not.toContain('<b>$questions[0][$answer[0]]</b>');
    expect(out).toContain('(#$answer[0])');
  });

  it('leaves a non-choices part in the same question printing its value', () => {
    const out = withAnswerKey(CHOICES);
    expect(out).toContain('$answerbox[1]<span');
    expect(out).not.toContain('$__momkey1');
  });

  it('resolves the scalar form too', () => {
    const single = CHOICES.replace('$anstypes = array("choices", "number")', '$anstypes = "choices"')
      .replace('Which is it? $answerbox[0]\nHow many? $answerbox[1]', 'Which is it? $answerbox');
    const out = withAnswerKey(single);
    expect(out).toContain('$__momkey = $questions[$answer]');
    expect(out.indexOf('$__momkey =')).toBeLessThan(out.indexOf('// === QUESTION TEXT ==='));
  });

  it('reads $anstypes in both array and scalar form', () => {
    expect(answerTypes(CHOICES)).toEqual(['choices', 'number']);
    expect(answerTypes(MULTIPART)).toEqual(['number', 'number']);
    expect(answerTypes(SINGLE)).toEqual(['number']);
    expect(answerTypes('$a = 1')).toEqual([]);
  });

  it('returns a malformed source unchanged rather than guessing', () => {
    // No QUESTION TEXT marker: let it render and fail as it normally would.
    const junk = '$a = 1\n$answer = $a\n';
    expect(withAnswerKey(junk)).toBe(junk);
  });
});

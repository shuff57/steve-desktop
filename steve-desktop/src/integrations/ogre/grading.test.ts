import { describe, expect, it } from 'vitest';
import {
  buildSingleGradePrompt,
  extractCustomInstructions,
  extractFirstName,
  getScaleInfo,
  normalizeFeedbackHtml,
  parseSingleGradeResponse,
  snapScore,
  type Rubric,
} from './grading';

const RUBRIC: Rubric = {
  maxScore: 4,
  essayPrompt: 'Interpret the residual.',
  checklistItems: [{ category: 'Method', items: ['States residual = observed − predicted', 'Substitutes correctly'] }],
};

describe('score scaling', () => {
  // The model always answers 0-10; the rubric here is out of 4.
  it('descales a virtual 0-10 score to the rubric max', () => {
    expect(parseSingleGradeResponse('{"score": 10, "feedback": "<p>ok</p>"}', 4).score).toBe(4);
    expect(parseSingleGradeResponse('{"score": 5, "feedback": "<p>ok</p>"}', 4).score).toBe(2);
  });

  it('never exceeds the rubric max even if the model overshoots', () => {
    expect(parseSingleGradeResponse('{"score": 47, "feedback": "x"}', 4).score).toBe(4);
  });

  it('floors a negative or unparseable score at 0', () => {
    expect(parseSingleGradeResponse('{"score": -3, "feedback": "x"}', 4).score).toBe(0);
    expect(parseSingleGradeResponse('{"score": "abc", "feedback": "x"}', 10).score).toBe(0);
  });

  it('snaps to an increment the max can express', () => {
    expect(snapScore(3.26, 10)).toBe(3.5); // 0.5 steps
    expect(snapScore(1.1, 4)).toBe(1); // 0.25 steps
    expect(snapScore(0.44, 1)).toBe(0.4); // 0.1 steps
  });

  it('treats a missing maxScore as 10', () => {
    expect(getScaleInfo(undefined)).toEqual({ virtualMax: 10, factor: 1 });
  });
});

describe('category-weighted scoring', () => {
  // Weighted mode produces a real-scale score directly and must NOT be descaled again.
  it('composes criterion scores by percentage weight', () => {
    const out = parseSingleGradeResponse(
      '{"criterion_scores": {"Method": 10, "Context": 5}, "score": 8, "feedback": "<p>x</p>"}',
      10,
      { Method: 50, Context: 50 },
      { Method: 10, Context: 10 },
    );
    // (10/10 * .5) + (5/10 * .5) = 0.75 -> 7.5 of 10
    expect(out.score).toBe(7.5);
  });

  it('ignores a category the rubric gave no weight', () => {
    const out = parseSingleGradeResponse(
      '{"criterion_scores": {"Method": 10, "Bogus": 0}, "score": 9, "feedback": "x"}',
      10,
      { Method: 100 },
      { Method: 10 },
    );
    expect(out.score).toBe(10);
  });

  it('falls back to the plain score when no criterion_scores come back', () => {
    const out = parseSingleGradeResponse('{"score": 5, "feedback": "x"}', 10, { Method: 100 }, { Method: 10 });
    expect(out.score).toBe(5);
  });
});

describe('parsing model replies', () => {
  it('reads plain JSON', () => {
    expect(parseSingleGradeResponse('{"score": 8, "feedback": "<p>good</p>"}', 10).feedback).toBe('<p>good</p>');
  });

  it('strips markdown code fences', () => {
    expect(parseSingleGradeResponse('```json\n{"score": 8, "feedback": "<p>a</p>"}\n```', 10).score).toBe(8);
  });

  it('strips <think> reasoning blocks', () => {
    const raw = '<think>let me consider</think>\n{"score": 6, "feedback": "<p>b</p>"}';
    expect(parseSingleGradeResponse(raw, 10).score).toBe(6);
  });

  it('recovers JSON embedded in prose', () => {
    const raw = 'Here is my grade:\n{"score": 7, "feedback": "<p>c</p>"}\nHope that helps!';
    expect(parseSingleGradeResponse(raw, 10).score).toBe(7);
  });

  it('survives unescaped LaTeX backslashes', () => {
    const raw = '{"score": 9, "feedback": "<p>Use \\hat{y} here</p>"}';
    expect(parseSingleGradeResponse(raw, 10).score).toBe(9);
  });

  it('returns a visible error rather than a silent 0 when nothing parses', () => {
    const out = parseSingleGradeResponse('the model rambled and never produced JSON', 10);
    expect(out.score).toBe(0);
    expect(out.feedback).toMatch(/error parsing/i);
  });

  it('rewrites a "Last, First" greeting to first name only', () => {
    const out = parseSingleGradeResponse(
      '{"score": 8, "feedback": "<p>Hi Nakamura, Yuki,</p><p>body</p>"}',
      10,
      null,
      null,
      'Nakamura, Yuki',
    );
    expect(out.feedback).toContain('<p>Hi Yuki,</p>');
    expect(out.feedback).toContain('<p>body</p>');
  });
});

describe('extractFirstName', () => {
  it('handles both name orders', () => {
    expect(extractFirstName('Nakamura, Yuki')).toBe('Yuki');
    expect(extractFirstName('Yuki Nakamura')).toBe('Yuki');
    expect(extractFirstName('')).toBe('');
  });
});

describe('normalizeFeedbackHtml', () => {
  it('leaves real HTML alone', () => {
    expect(normalizeFeedbackHtml('<p>already html</p>')).toBe('<p>already html</p>');
  });

  it('converts markdown a drifting model emitted instead', () => {
    const out = normalizeFeedbackHtml('**Requirement one**\n> You said: nothing\nNeeds work');
    expect(out).toContain('<strong>Requirement one</strong>');
    expect(out).toContain('<blockquote>You said: nothing</blockquote>');
  });

  it('leaves plain prose with no markers untouched', () => {
    expect(normalizeFeedbackHtml('just a sentence')).toBe('just a sentence');
  });
});

describe('custom instructions', () => {
  it('treats plain text as an instructor override', () => {
    expect(extractCustomInstructions('Be strict about units.')).toEqual({
      calibration: null,
      overrideInstructions: 'Be strict about units.',
    });
  });

  it('splits calibration from a trailing override', () => {
    const input = 'SCORING CALIBRATION:\nExcellent: full marks\n\nAdequate: most marks\n\nAlso deduct for missing units.';
    const { calibration, overrideInstructions } = extractCustomInstructions(input);
    expect(calibration).toContain('Excellent: full marks');
    expect(calibration).toContain('Adequate: most marks'); // anchor labels stay with calibration
    expect(overrideInstructions).toBe('Also deduct for missing units.');
  });

  it('handles calibration with no override', () => {
    const { calibration, overrideInstructions } = extractCustomInstructions('SCORING CALIBRATION:\nExcellent: yes');
    expect(calibration).toBe('Excellent: yes');
    expect(overrideInstructions).toBeNull();
  });
});

describe('buildSingleGradePrompt', () => {
  it('asks for 0-10 regardless of the real max, and includes the work', () => {
    const p = buildSingleGradePrompt(RUBRIC, 'The residual is 2.4');
    expect(p).toContain('MAX SCORE: 10');
    expect(p).toContain('The residual is 2.4');
    expect(p).toContain('Interpret the residual.');
  });

  it('numbers every checklist requirement so feedback can be per-requirement', () => {
    const p = buildSingleGradePrompt(RUBRIC, 'x');
    expect(p).toContain('1. [Method] States residual = observed − predicted');
    expect(p).toContain('2. [Method] Substitutes correctly');
  });

  it('says so explicitly when the student submitted nothing', () => {
    expect(buildSingleGradePrompt(RUBRIC, '')).toContain('(No response submitted)');
  });

  it('puts an instructor override above the base philosophy', () => {
    const p = buildSingleGradePrompt({ ...RUBRIC, customInstructions: 'Ignore spelling.' }, 'x');
    expect(p.indexOf('INSTRUCTOR OVERRIDE')).toBeLessThan(p.indexOf('GRADING PHILOSOPHY'));
  });

  it('omits the criterion_scores JSON field when the rubric has no checklist', () => {
    // The format-rule prose mentions criterion_scores unconditionally (as in O.G.R.E);
    // it's the quoted JSON field that must not appear.
    expect(buildSingleGradePrompt({ maxScore: 10, essayPrompt: 'q' }, 'x')).not.toContain('"criterion_scores":');
  });

  it('emits the criterion_scores JSON field when categories exist', () => {
    expect(buildSingleGradePrompt(RUBRIC, 'x')).toContain('"criterion_scores":');
  });
});

import { describe, expect, it } from 'vitest';
import {
  buildBatchPrompt,
  buildBridgeResponses,
  chunkStudents,
  generateScoringAnchors,
  mergeResults,
  parseBatchResponse,
  type BatchResult,
  type BatchStudent,
} from './batch';
import type { Rubric } from './grading';

const RUBRIC: Rubric = {
  maxScore: 10,
  essayPrompt: 'Interpret the residual.',
  checklistItems: [{ category: 'Method', items: ['States the formula', 'Substitutes correctly'] }],
};
const ANCHORS = generateScoringAnchors(RUBRIC);

const mkStudents = (n: number): BatchStudent[] =>
  Array.from({ length: n }, (_, i) => ({ index: i, name: `Student ${i}`, response: `answer ${i}` }));

describe('generateScoringAnchors', () => {
  it('places anchors at 95/80/65/45 percent', () => {
    expect(generateScoringAnchors({ maxScore: 10 })).toMatchObject({
      excellent: { score: 10 },
      adequate: { score: 8 },
      belowAverage: { score: 7 },
      minimal: { score: 5 },
    });
  });

  it('keeps a decimal for small maxima so anchors do not collapse together', () => {
    const a = generateScoringAnchors({ maxScore: 4 });
    const scores = [a.excellent.score, a.adequate.score, a.belowAverage.score, a.minimal.score];
    expect(new Set(scores).size).toBe(4); // all distinct
    expect(a.excellent.score).toBe(3.8);
  });
});

describe('chunkStudents', () => {
  it('keeps a class that fits in one context as a single chunk', () => {
    const chunks = chunkStudents(mkStudents(20), 20);
    expect(chunks).toHaveLength(1);
    expect(chunks[0]!.needsAnchors).toBe(false);
  });

  it('splits past the chunk size and marks later chunks as needing anchors', () => {
    const chunks = chunkStudents(mkStudents(45), 20);
    expect(chunks.map((c) => c.students.length)).toEqual([20, 20, 5]);
    expect(chunks.map((c) => c.needsAnchors)).toEqual([false, true, true]);
  });

  it('returns nothing for an empty roster', () => {
    expect(chunkStudents([], 20)).toEqual([]);
  });
});

describe('mergeResults', () => {
  it('restores roster order across chunks', () => {
    const merged = mergeResults([
      [{ studentIndex: 2, score: 5, feedback: 'c' }],
      [{ studentIndex: 0, score: 9, feedback: 'a' }],
      [{ studentIndex: 1, score: 7, feedback: 'b' }],
    ]);
    expect(merged.map((r) => r.studentIndex)).toEqual([0, 1, 2]);
  });
});

describe('positional result mapping', () => {
  // Models very often renumber from 0 in each chunk. Trusting their studentIndex
  // would assign chunk 2's grades to chunk 1's students.
  it('ignores the model studentIndex and maps by position', () => {
    const students: BatchStudent[] = [
      { index: 20, name: 'Twenty', response: 'x' },
      { index: 21, name: 'Twentyone', response: 'y' },
    ];
    const reply = JSON.stringify([
      { studentIndex: 0, score: 10, feedback: '<p>first</p>' },
      { studentIndex: 1, score: 4, feedback: '<p>second</p>' },
    ]);
    const out = parseBatchResponse(reply, students, 10);
    expect(out.map((r) => r.studentIndex)).toEqual([20, 21]);
    expect(out[0]!.score).toBe(10);
    expect(out[1]!.score).toBe(4);
  });

  it('drops extra results rather than inventing gradebook entries', () => {
    const students = mkStudents(2);
    const reply = JSON.stringify([
      { studentIndex: 0, score: 8, feedback: 'a' },
      { studentIndex: 1, score: 7, feedback: 'b' },
      { studentIndex: 2, score: 6, feedback: 'phantom' },
    ]);
    expect(parseBatchResponse(reply, students, 10)).toHaveLength(2);
  });
});

describe('parseBatchResponse recovery', () => {
  const students = mkStudents(2);

  it('unwraps a results/students/grades wrapper object', () => {
    for (const key of ['results', 'students', 'grades', 'data']) {
      const reply = JSON.stringify({ [key]: [{ score: 8, feedback: 'a' }, { score: 6, feedback: 'b' }] });
      expect(parseBatchResponse(reply, students, 10)[0]!.score).toBe(8);
    }
  });

  it('tolerates trailing commas', () => {
    const reply = '[{"studentIndex":0,"score":8,"feedback":"a"},{"studentIndex":1,"score":6,"feedback":"b"},]';
    expect(parseBatchResponse(reply, students, 10).map((r) => r.score)).toEqual([8, 6]);
  });

  it('recovers individual objects from a malformed array', () => {
    const reply = '[{"studentIndex":0,"score":8,"feedback":"a"} MANGLED {"studentIndex":1,"score":6,"feedback":"b"}';
    expect(parseBatchResponse(reply, students, 10).map((r) => r.score)).toEqual([8, 6]);
  });

  it('falls back to prose score lines', () => {
    const reply = 'Student 0: 8/10 — good\nStudent 1: 6/10 — ok';
    expect(parseBatchResponse(reply, students, 10).map((r) => r.score)).toEqual([8, 6]);
  });

  it('marks every student as an error rather than silently scoring 0', () => {
    const out = parseBatchResponse('the model refused', students, 10);
    expect(out).toHaveLength(2);
    expect(out.every((r) => r.feedback.match(/error parsing/i))).toBe(true);
  });

  it('descales batch scores to the rubric max', () => {
    const reply = JSON.stringify([{ score: 10, feedback: 'a' }, { score: 5, feedback: 'b' }]);
    expect(parseBatchResponse(reply, students, 4).map((r) => r.score)).toEqual([4, 2]);
  });

  it('joins an array-valued feedback field', () => {
    const reply = JSON.stringify([{ score: 8, feedback: ['<p>one</p>', '<p>two</p>'] }, { score: 6, feedback: 'b' }]);
    expect(parseBatchResponse(reply, students, 10)[0]!.feedback).toContain('<p>one</p>');
  });
});

describe('buildBridgeResponses', () => {
  const students = mkStudents(6);
  const results: BatchResult[] = [
    { studentIndex: 0, score: 10, feedback: 'top' },
    { studentIndex: 1, score: 9, feedback: 'high' },
    { studentIndex: 2, score: 8, feedback: 'mid' },
    { studentIndex: 3, score: 7, feedback: 'mid2' },
    { studentIndex: 4, score: 3, feedback: 'low' },
    { studentIndex: 5, score: 1, feedback: 'bottom' },
  ];

  it('spans quality tiers and names each example', () => {
    const bridge = buildBridgeResponses(results, students, ANCHORS);
    expect(bridge.length).toBeGreaterThanOrEqual(3);
    expect(new Set(bridge.map((b) => b.tier)).size).toBeGreaterThan(1);
    expect(bridge[0]!.name).toMatch(/Student/);
  });

  it('never repeats a student', () => {
    const bridge = buildBridgeResponses(results, students, ANCHORS);
    expect(new Set(bridge.map((b) => b.studentIndex)).size).toBe(bridge.length);
  });

  it('falls back to a low/mid/high spread when everyone clusters in one tier', () => {
    const flat = students.map((s) => ({ studentIndex: s.index, score: 8, feedback: 'same' }));
    const bridge = buildBridgeResponses(flat, students, ANCHORS);
    expect(bridge.every((b) => b.tier === 'spread' || b.tier === 'adequate')).toBe(true);
    expect(bridge.length).toBeGreaterThanOrEqual(1);
  });

  it('returns nothing for an empty chunk', () => {
    expect(buildBridgeResponses([], students, ANCHORS)).toEqual([]);
  });
});

describe('buildBatchPrompt', () => {
  it('lists every student with their index header', () => {
    const p = buildBatchPrompt(RUBRIC, mkStudents(3), ANCHORS);
    expect(p).toContain('--- Student 0: Student 0 ---');
    expect(p).toContain('--- Student 2: Student 2 ---');
    expect(p).toContain('Return results for ALL 3 students');
  });

  it('says so explicitly when a student submitted nothing', () => {
    const p = buildBatchPrompt(RUBRIC, [{ index: 0, name: 'A', response: '' }], ANCHORS);
    expect(p).toContain('(No response submitted)');
  });

  it('includes bridge examples with numbers stripped', () => {
    // One student's jittered values must not leak into another's evaluation.
    const bridge = [{ studentIndex: 0, score: 9, feedback: 'The slope was 3.75 exactly', name: 'A', tier: 'excellent' }];
    const p = buildBatchPrompt(RUBRIC, mkStudents(2), ANCHORS, bridge);
    expect(p).toContain('CALIBRATION EXAMPLES');
    expect(p).toContain('The slope was [N] exactly');
    expect(p).not.toContain('3.75');
  });

  it('omits the calibration block entirely on the first chunk', () => {
    expect(buildBatchPrompt(RUBRIC, mkStudents(2), ANCHORS, null)).not.toContain('CALIBRATION EXAMPLES (from previously graded batch');
  });

  it('flags per-student questions only when they actually differ', () => {
    const same = buildBatchPrompt(RUBRIC, mkStudents(2), ANCHORS);
    expect(same).not.toContain('DIFFERENT randomized numeric values');

    const jittered: BatchStudent[] = [
      { index: 0, name: 'A', response: 'x', prompt: 'Find the mean of 3, 5, 7' },
      { index: 1, name: 'B', response: 'y', prompt: 'Find the mean of 2, 4, 9' },
    ];
    const p = buildBatchPrompt(RUBRIC, jittered, ANCHORS);
    expect(p).toContain('DIFFERENT randomized numeric values');
    expect(p).toContain('THEIR QUESTION: Find the mean of 3, 5, 7');
  });

  it('splits instructions appended to the prompt body back out', () => {
    const r: Rubric = {
      ...RUBRIC,
      essayPrompt: 'Interpret the residual.\n\nADDITIONAL GRADING INSTRUCTIONS:\nBe generous on units.',
    };
    const p = buildBatchPrompt(r, mkStudents(1), ANCHORS);
    expect(p).toContain('INSTRUCTOR OVERRIDE');
    expect(p).toContain('Be generous on units.');
    // The instruction text must not remain in the question shown to the model.
    expect(p.split('QUESTION/PROMPT:')[1]!.split('\n')[1]).toBe('Interpret the residual.');
  });
});

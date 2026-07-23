/**
 * The second-pass review. The properties that matter are the ones that protect grades
 * already earned: a review can revise a score, but a failed review must never destroy one,
 * and every number crossing into the prompt must be on the 0-10 scale the parser descales
 * from.
 */
import { describe, expect, it } from 'vitest';
import { PARSE_FAILURE_FEEDBACK, generateScoringAnchors, type BatchResult, type BatchStudent } from './batch';
import { detectOutliers } from './outliers';
import { reviewOutliers, type Student } from './grade';
import {
  applyReview,
  buildOutlierReviewPrompt,
  outlierStudentsFrom,
  parseOutlierReview,
} from './outlier-review';
import type { Rubric } from './grading';

const rubric: Rubric = {
  maxScore: 20, // deliberately NOT 10, so a scale slip shows up
  essayPrompt: 'Explain whether the maximum is an outlier.',
  checklistItems: [{ category: 'Outlier', points: 20, items: ['Compare max to the upper fence.'] }],
};

/**
 * Ten solid scores and one clear low one. The class size matters: with only four peers a
 * single outlier inflates σ enough to push itself back inside the 2σ threshold and go
 * undetected — which is a real property of the detector, not a quirk of this fixture.
 */
const NAMES = [
  'Lovelace, Ada',
  'Ochoa, Ben',
  'Ruiz, Cleo',
  'Patel, Dev',
  'Fenwick, Gil',
  'Haruki, Ito',
  'Osei, Jem',
  'Vance, Kai',
  'Duarte, Lia',
  'Mbeki, Nia',
  'Nakamura, Eve', // the outlier
];
const SCORES = [18, 17, 18, 17.5, 16, 17, 18, 16.5, 17, 18, 2];
const OUTLIER = 10; // roster index of Eve

const results: BatchResult[] = SCORES.map((score, i) => ({
  studentIndex: i,
  score,
  feedback:
    i === OUTLIER
      ? '<p>Hi Eve,</p><p>Missed everything.</p>'
      : `<p>Hi ${NAMES[i]!.split(', ')[1]},</p><p>Good.</p>`,
}));

const students: BatchStudent[] = NAMES.map((name, i) => ({
  index: i,
  name,
  response:
    i === OUTLIER
      ? 'The maximum sits above the upper fence, so it is an outlier.'
      : 'Max exceeds the upper fence, so it is an outlier.',
}));

describe('outlier review prompt', () => {
  const report = detectOutliers(results);
  const flagged = outlierStudentsFrom(report, results, students);

  it('flags the low score and carries its original verdict forward', () => {
    expect(report.outliers.map((o) => o.studentIndex)).toEqual([OUTLIER]);
    expect(flagged).toHaveLength(1);
    expect(flagged[0]!.originalScore).toBe(2);
    expect(flagged[0]!.originalFeedback).toContain('Missed everything');
    expect(flagged[0]!.response).toContain('upper fence');
  });

  const prompt = buildOutlierReviewPrompt(
    rubric,
    flagged,
    generateScoringAnchors(rubric),
    { mean: report.mean, stdDev: report.stdDev, totalStudents: results.length },
    results,
    students,
  );

  it('states every score on the 0-10 scale the parser descales from', () => {
    // maxScore is 20, so a real 2 must appear as 1 and the real mean (15.91) as 8.
    expect(prompt).toContain('MAX SCORE: 10');
    expect(prompt).toContain('ORIGINAL SCORE: 1/10');
    expect(prompt).toContain('Batch mean score: 8/10');
    // A real-scale number leaking through would read as out of range.
    expect(prompt).not.toContain('/20');
    expect(prompt).not.toMatch(/ORIGINAL SCORE: 2\b/);
  });

  it('includes similarly-scored peers so the model checks consistency', () => {
    // Nobody scored within 1 of a 2, so this outlier gets no peer block — correct: a
    // fabricated "peer" 14 points away would calibrate toward the wrong standard.
    expect(prompt).not.toContain('PEER COMPARISON');

    // A student who IS near others gets them.
    const near = outlierStudentsFrom(
      { mean: 10, stdDev: 1, outliers: [{ studentIndex: 0, score: 18, deviation: 3 }] },
      results,
      students,
    );
    const withPeers = buildOutlierReviewPrompt(
      rubric,
      near,
      generateScoringAnchors(rubric),
      { mean: 15.91, stdDev: 4.45, totalStudents: results.length },
      results,
      students,
    );
    expect(withPeers).toContain('PEER COMPARISON');
    expect(withPeers).toContain('[Score 9/10]'); // another 18, scaled
    // Two peers at most — this is a comparable, not a re-read of the class.
    expect(withPeers.match(/\[Score /g)).toHaveLength(2);
  });

  it('numbers students from 0 for this prompt, not by roster index', () => {
    // The model never sees the roster. Asking for index 10 invites it to echo 10 back for a
    // one-student list, which positional mapping would then misplace.
    expect(prompt).toContain('--- Student 0: Nakamura, Eve ---');
    expect(prompt).not.toContain(`--- Student ${OUTLIER}:`);
  });

  it('asks for the same per-requirement HTML the main batch produces', () => {
    expect(prompt).toContain('Compare max to the upper fence.');
    expect(prompt).toContain('<blockquote>');
    expect(prompt).toContain('"adjusted"');
    expect(prompt).toContain('Return results for ALL 1 student(s)');
  });
});

describe('applying a review', () => {
  const flagged = outlierStudentsFrom(detectOutliers(results), results, students);

  it('maps a 0-based reply back onto the real roster index', () => {
    const reply = JSON.stringify([
      { studentIndex: 0, score: 8, feedback: '<p>Hi Eve,</p><p>Actually correct.</p>', adjusted: true },
    ]);
    const reviewed = parseOutlierReview(reply, flagged, rubric);
    expect(reviewed).toHaveLength(1);
    expect(reviewed[0]!.studentIndex).toBe(OUTLIER); // not 0
    expect(reviewed[0]!.score).toBe(16); // 8/10 descaled to /20

    const { merged, changed } = applyReview(results, reviewed, (r) => r.feedback === PARSE_FAILURE_FEEDBACK);
    expect(changed).toEqual([OUTLIER]);
    expect(merged.map((r) => r.score)).toEqual([...SCORES.slice(0, OUTLIER), 16]);
    // Untouched students keep their exact original objects.
    expect(merged[0]).toBe(results[0]);
  });

  it('reports no change when the review upholds the original score', () => {
    const reply = JSON.stringify([
      { studentIndex: 0, score: 1, feedback: '<p>Hi Eve,</p><p>Still wrong.</p>', adjusted: false },
    ]);
    const reviewed = parseOutlierReview(reply, flagged, rubric);
    const { merged, changed } = applyReview(results, reviewed, (r) => r.feedback === PARSE_FAILURE_FEEDBACK);
    expect(changed).toEqual([]);
    expect(merged[OUTLIER]!.score).toBe(2);
  });

  it('never lets an unparseable review overwrite a real grade', () => {
    const reviewed = parseOutlierReview('the model said nothing useful', flagged, rubric);
    // The parser marks this as a failure rather than a zero...
    expect(reviewed[0]!.feedback).toBe(PARSE_FAILURE_FEEDBACK);
    expect(reviewed[0]!.score).toBe(0);

    // ...and applying it must drop the row, not zero the student.
    const { merged, changed } = applyReview(results, reviewed, (r) => r.feedback === PARSE_FAILURE_FEEDBACK);
    expect(changed).toEqual([]);
    expect(merged[OUTLIER]!.score).toBe(2);
    expect(merged[OUTLIER]!.feedback).toContain('Missed everything');
  });
});

describe('reviewOutliers', () => {
  const roster: Student[] = students.map((s) => ({ name: s.name, responseText: s.response }));

  it('does not call the model when nothing is flagged', async () => {
    const flat: BatchResult[] = results.map((r) => ({ ...r, score: 10 }));
    let called = 0;
    const out = await reviewOutliers(roster, flat, rubric, {}, { run: async () => { called++; return '[]'; } });
    expect(called).toBe(0);
    expect(out.reviewed).toBe(0);
    expect(out.results).toBe(flat);
  });

  it('redacts every student in the batch, not only the flagged ones', async () => {
    let seen = '';
    await reviewOutliers(roster, results, rubric, {}, {
      run: async (prompt) => {
        seen = prompt;
        return JSON.stringify([{ studentIndex: 0, score: 8, feedback: '<p>Hi there,</p><p>ok</p>' }]);
      },
    });
    // Peer excerpts can carry a non-outlier's name into the prompt, so the whole roster is
    // registered. No real name may survive in either roster or signed order.
    for (const name of ['Nakamura', 'Eve', 'Lovelace', 'Ada', 'Ochoa', 'Ruiz', 'Patel', 'Mbeki', 'Nia']) {
      expect(seen, `"${name}" reached the model`).not.toContain(name);
    }
    expect(seen).toMatch(/⟦S\d+⟧/);
  });

  it('rehydrates names locally and revises only the flagged student', async () => {
    const out = await reviewOutliers(roster, results, rubric, {}, {
      run: async () => JSON.stringify([{ studentIndex: 0, score: 9, feedback: '<p>Hi Eve,</p><p>Reread it.</p>' }]),
    });
    expect(out.reviewed).toBe(1);
    expect(out.changed).toEqual([OUTLIER]);
    expect(out.results[OUTLIER]!.score).toBe(18); // 9/10 of 20
    expect(out.results.slice(0, OUTLIER).map((r) => r.score)).toEqual(SCORES.slice(0, OUTLIER));
  });
});

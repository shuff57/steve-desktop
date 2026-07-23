/**
 * Generated calibration anchors. The load-bearing claims are that the parser survives the
 * formatting a real model actually emits, that a failed generation is loud rather than
 * four blank examples, and that the edited text lands where `buildBatchPrompt` reads it.
 */
import { describe, expect, it, vi } from 'vitest';

vi.mock('@tauri-apps/api/core', () => ({ invoke: vi.fn() }));

import { generateScoringAnchors } from './batch';
import { buildBatchPrompt } from './batch';
import { extractCustomInstructions, type Rubric } from './grading';
import {
  anchorsToText,
  buildAnchorGenerationPrompt,
  parseAnchorResponses,
  withCalibration,
} from './anchors';
import { generateAnchorExamples } from './grade';

const rubric: Rubric = {
  maxScore: 20,
  essayPrompt: 'Decide whether the maximum is an outlier.',
  checklistItems: [{ category: 'Outlier', points: 20, items: ['Compare max to the upper fence.'] }],
};
const anchors = generateScoringAnchors(rubric); // 19 / 16 / 13 / 9

describe('anchor generation prompt', () => {
  it('states the real max score, since it asks for prose rather than a grade', () => {
    const p = buildAnchorGenerationPrompt(rubric, anchors, null);
    // Every grading prompt asks 0-10 and gets descaled. This one is not descaled, so it
    // must speak in the rubric's own units or the examples describe the wrong standard.
    expect(p).toContain('MAX SCORE: 20');
    expect(p).toContain('EXCELLENT (19/20)');
    expect(p).toContain('MINIMAL (9/20)');
    expect(p).toContain('Compare max to the upper fence.');
  });

  it('carries leniency into the examples, in the matching direction', () => {
    const lenient = buildAnchorGenerationPrompt(rubric, anchors, 20);
    const strict = buildAnchorGenerationPrompt(rubric, anchors, 80);
    expect(lenient).toContain('LENIENT GRADING MODE (30% more lenient');
    expect(strict).toContain('STRICT GRADING MODE (30% more strict');
    // Exemplars are what the grader compares against, so a lenient rubric graded against
    // strict exemplars is still graded strictly.
    expect(lenient).not.toContain('STRICT GRADING MODE');
    expect(buildAnchorGenerationPrompt(rubric, anchors, 50)).not.toContain('GRADING MODE');
  });
});

describe('parsing the reply', () => {
  it('reads the plain format', () => {
    const out = parseAnchorResponses(
      `EXCELLENT (19/20):
IQR is 21, fence is 93.5, max 118 exceeds it, so it is an outlier.

ADEQUATE (16/20):
The max is bigger than the fence so it's an outlier.

BELOW AVERAGE (13/20):
I think 118 is too high.

MINIMAL (9/20):
It's an outlier.`,
      anchors,
    );
    expect(out.map((a) => a.label)).toEqual(['Excellent', 'Adequate', 'Below Average', 'Minimal']);
    expect(out[0]!.response).toContain('93.5');
    expect(out[3]!.response).toBe("It's an outlier.");
    // A section must not swallow the next one's heading.
    expect(out[0]!.response).not.toContain('ADEQUATE');
  });

  it('survives markdown and a missing max score', () => {
    // O.G.R.E required an exact `Label (score/max):` and returned four blank examples
    // otherwise — a silent failure that reads as a successful generation.
    const out = parseAnchorResponses(
      `**Excellent (19)**
Full derivation shown.
## Adequate
Mostly right.
_Below Average:_
Vague.
Minimal — 9/20
Nothing much.`,
      anchors,
    );
    expect(out.map((a) => a.response)).toEqual([
      'Full derivation shown.',
      'Mostly right.',
      'Vague.',
      'Nothing much.',
    ]);
  });

  it('drops reasoning blocks and echoed placeholders', () => {
    const out = parseAnchorResponses(
      `<think>let me plan</think>
EXCELLENT (19/20):
[write example]
Real content here.
ADEQUATE (16/20):
Fine.
BELOW AVERAGE (13/20):
Weak.
MINIMAL (9/20):
None.`,
      anchors,
    );
    expect(out[0]!.response).toBe('Real content here.');
    expect(out[0]!.response).not.toContain('let me plan');
  });

  it('leaves a tier blank when the model skipped it', () => {
    const out = parseAnchorResponses('EXCELLENT (19/20):\nOnly this one.', anchors);
    expect(out[0]!.response).toBe('Only this one.');
    expect(out.slice(1).every((a) => a.response === '')).toBe(true);
  });
});

describe('feeding anchors back into grading', () => {
  it('lands where buildBatchPrompt actually reads calibration', () => {
    const text = anchorsToText([
      { label: 'Excellent', score: 19, response: 'Full derivation.' },
      { label: 'Minimal', score: 9, response: 'Bare assertion.' },
    ]);
    const withAnchors = withCalibration(rubric, text, 'Accept informal notation.');

    // The carrier is customInstructions with a SCORING CALIBRATION: header — the shape
    // extractCustomInstructions splits and buildBatchPrompt emits.
    const split = extractCustomInstructions(withAnchors.customInstructions);
    expect(split.calibration).toContain('Full derivation.');
    expect(split.overrideInstructions).toBe('Accept informal notation.');

    const prompt = buildBatchPrompt(
      withAnchors,
      [{ index: 0, name: 'A', response: 'x' }],
      generateScoringAnchors(withAnchors),
    );
    expect(prompt).toContain('SCORING CALIBRATION EXAMPLES');
    expect(prompt).toContain('Full derivation.');
    expect(prompt).toContain('INSTRUCTOR OVERRIDE INSTRUCTIONS');
  });

  it('keeps plain instructions working when there are no anchors', () => {
    const out = withCalibration(rubric, '   ', 'Be generous on notation.');
    expect(out.customInstructions).toBe('Be generous on notation.');
    expect(extractCustomInstructions(out.customInstructions).calibration).toBeNull();
  });

  it('leaves the rubric untouched when there is nothing to add', () => {
    expect(withCalibration(rubric, '', '')).toBe(rubric);
  });
});

describe('generateAnchorExamples', () => {
  it('returns one example per tier', async () => {
    const out = await generateAnchorExamples(rubric, {}, {
      run: async () =>
        'EXCELLENT (19/20):\nA\nADEQUATE (16/20):\nB\nBELOW AVERAGE (13/20):\nC\nMINIMAL (9/20):\nD',
    });
    expect(out.map((a) => a.response)).toEqual(['A', 'B', 'C', 'D']);
    expect(out.map((a) => a.score)).toEqual([19, 16, 13, 9]);
  });

  it('throws rather than returning four blanks', async () => {
    await expect(
      generateAnchorExamples(rubric, {}, { run: async () => 'I could not do that.' }),
    ).rejects.toThrow(/no usable calibration examples/i);
  });

  it('sends the rubric and nothing else — there is no student work to leak', async () => {
    let seen = '';
    await generateAnchorExamples(rubric, {}, {
      leniency: 20,
      run: async (p) => {
        seen = p;
        return 'EXCELLENT (19/20):\nA\nADEQUATE (16/20):\nB\nBELOW AVERAGE (13/20):\nC\nMINIMAL (9/20):\nD';
      },
    });
    expect(seen).toContain('Compare max to the upper fence.');
    expect(seen).toContain('LENIENT GRADING MODE');
    expect(seen).not.toMatch(/STUDENTS? TO/i);
    expect(seen).not.toContain('RESPONSE:');
  });
});

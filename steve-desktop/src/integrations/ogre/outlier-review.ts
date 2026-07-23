/**
 * Second-pass review of the students `detectOutliers` flagged.
 *
 * Ported from `grading-server/grading.js` (buildOutlierReviewPrompt). Detection on its own
 * only says "these scores look wrong"; this is the pass that actually re-reads them. Each
 * flagged student goes back to the model with its original score, its original feedback,
 * the batch statistics, and — the part that makes it more than a re-run — two peers who
 * scored similarly, so the model checks consistency rather than re-deriving a score from
 * scratch and landing somewhere new for the same reasons.
 *
 * SCALE: like every other prompt here, this asks for 0-10 regardless of the rubric's real
 * maxScore, and `parseBatchResponse` descales the reply. Every number that goes INTO the
 * prompt is therefore scaled up to the 0-10 view first — original scores, the batch mean,
 * the standard deviation, the anchors. O.G.R.E's version sent real-scale numbers, which is
 * safe there only because its parser did not descale. Mixing the two conventions would
 * double-scale the result.
 */
import { getScaleInfo, getScoringScaleString, extractCustomInstructions, type Rubric } from './grading';
import { GRADING_PHILOSOPHY } from './grading-constants';
import { parseBatchResponse, type BatchResult, type BatchStudent, type ScoringAnchors } from './batch';
import type { OutlierReport } from './outliers';

/** A flagged student, carrying the verdict being re-examined. */
export interface OutlierStudent extends BatchStudent {
  originalScore: number;
  originalFeedback: string;
}

/** Everything the review needs about the batch the outliers came from. */
export interface BatchStats {
  mean: number;
  stdDev: number;
  totalStudents: number;
}

/**
 * Pair each flagged student with the full result and response text needed to review it.
 * Returns the students in roster order; an outlier whose result is missing is skipped
 * rather than reviewed against a blank.
 */
export function outlierStudentsFrom(
  report: OutlierReport,
  results: BatchResult[],
  students: BatchStudent[],
): OutlierStudent[] {
  const byIndex = new Map(results.map((r) => [r.studentIndex, r]));
  const out: OutlierStudent[] = [];
  for (const o of report.outliers) {
    const result = byIndex.get(o.studentIndex);
    const student = students.find((s) => s.index === o.studentIndex);
    if (!result || !student) continue;
    out.push({ ...student, originalScore: result.score, originalFeedback: result.feedback });
  }
  return out.sort((a, b) => a.index - b.index);
}

/**
 * Up to two peers whose scores bracket this student's, for consistency checking.
 * Responses are truncated — the point is a comparable, not a full re-read of the class.
 */
function peersFor(
  student: OutlierStudent,
  results: BatchResult[],
  students: BatchStudent[],
  scaled: (n: number) => number,
  virtualMax: number,
): string {
  const responseFor = (i: number) => students.find((s) => s.index === i)?.response ?? '';
  const peers = results
    .filter((r) => r.studentIndex !== student.index && Math.abs(r.score - student.originalScore) <= 1)
    .sort(
      (a, b) => Math.abs(a.score - student.originalScore) - Math.abs(b.score - student.originalScore),
    )
    .slice(0, 2);
  if (peers.length === 0) return '';

  let out = '\nPEER COMPARISON (students who scored similarly — verify consistency):\n';
  for (const peer of peers) {
    const text = responseFor(peer.studentIndex) || '(no response)';
    const preview = text.slice(0, 300);
    out += `  [Score ${scaled(peer.score)}/${virtualMax}]: ${preview}${text.length > 300 ? '...' : ''}\n`;
  }
  return out;
}

export function buildOutlierReviewPrompt(
  rubric: Rubric,
  outlierStudents: OutlierStudent[],
  anchors: ScoringAnchors,
  stats: BatchStats,
  allResults: BatchResult[] = [],
  allStudents: BatchStudent[] = [],
): string {
  const maxScore = rubric.maxScore ?? '10';
  const { virtualMax, factor } = getScaleInfo(maxScore);
  const scaled = (s: number) => Math.round(s * factor * 10) / 10;

  let essayPrompt = rubric.essayPrompt || '(No prompt provided)';
  let customInstructions = rubric.customInstructions || '';
  // Older rubrics carry instructions appended to the prompt body; split them back out.
  const instrMatch = essayPrompt.match(/\n\nADDITIONAL GRADING INSTRUCTIONS:\n([\s\S]+)$/);
  if (instrMatch) {
    if (!customInstructions) customInstructions = instrMatch[1]!.trim();
    essayPrompt = essayPrompt.replace(/\n\nADDITIONAL GRADING INSTRUCTIONS:\n[\s\S]+$/, '').trim();
  }
  const { calibration, overrideInstructions } = extractCustomInstructions(customInstructions);

  let prompt = `You are an expert grading assistant performing a SECOND-PASS REVIEW of flagged student responses. Output: JSON array only.

These students received scores that deviated sharply from the batch mean. Your job is to re-evaluate each one carefully by comparing against the rubric AND against similarly-scored peers to ensure the score is accurate and consistent.

BATCH CONTEXT:
- Batch mean score: ${scaled(stats.mean)}/${virtualMax}
- Standard deviation: ${scaled(stats.stdDev)}
- Total students in batch: ${stats.totalStudents}

${
    overrideInstructions
      ? `INSTRUCTOR OVERRIDE INSTRUCTIONS (you MUST follow these — they take absolute precedence):\n${overrideInstructions}\n\n`
      : ''
  }GRADING PHILOSOPHY:
${GRADING_PHILOSOPHY}

MAX SCORE: ${virtualMax}

QUESTION/PROMPT:
${essayPrompt}
`;

  if (rubric.checklistItems && rubric.checklistItems.length > 0) {
    prompt += '\nGRADING CHECKLIST:\n';
    for (const item of rubric.checklistItems) {
      if (item.category) prompt += `- ${item.category}${item.points != null ? ` (${item.points} points)` : ''}\n`;
      for (const sub of item.items ?? []) prompt += `  - ${sub}\n`;
    }
  }

  if (rubric.modelText) prompt += `\nMODEL RESPONSE (for reference):\n${rubric.modelText}\n`;

  prompt += `
SCORING ANCHORS:
- Excellent (${scaled(anchors.excellent.score)}/${virtualMax}): ${anchors.excellent.description}
- Adequate (${scaled(anchors.adequate.score)}/${virtualMax}): ${anchors.adequate.description}
- Below Average (${scaled(anchors.belowAverage.score)}/${virtualMax}): ${anchors.belowAverage.description}
- Minimal (${scaled(anchors.minimal.score)}/${virtualMax}): ${anchors.minimal.description}

${getScoringScaleString()}
`;

  if (calibration) {
    prompt += `\nSCORING CALIBRATION EXAMPLES (use to calibrate score levels only — grade against rubric criteria and SCORING SCALE above, not these examples):\n${calibration}\n`;
  }

  // Randomized question values mean a peer's numbers are not this student's numbers.
  const hasPerStudentPrompts = outlierStudents.some((s) => s.prompt && s.prompt !== essayPrompt);
  if (hasPerStudentPrompts) {
    prompt += `\nIMPORTANT: Each student received the same question structure but with DIFFERENT randomized numeric values. Each student's specific question is shown below their header. Grade each student against THEIR specific values.\n`;
  }

  prompt += '\nSTUDENTS TO RE-EVALUATE:\n\n';
  outlierStudents.forEach((student, i) => {
    // Numbered from 0 for THIS prompt: the model does not see the roster, and results are
    // mapped back by position. Sending the roster index invites it to echo that instead.
    prompt += `--- Student ${i}: ${student.name} ---\n`;
    if (hasPerStudentPrompts && student.prompt) prompt += `THEIR QUESTION: ${student.prompt}\n`;
    prompt += `ORIGINAL SCORE: ${scaled(student.originalScore)}/${virtualMax}\n`;
    prompt += `ORIGINAL FEEDBACK: ${student.originalFeedback}\n`;
    prompt += `RESPONSE:\n${student.response || '(No response submitted)'}\n`;
    prompt += peersFor(student, allResults, allStudents, scaled, virtualMax);
    prompt += '\n';
  });

  // Mirror the main batch's per-requirement feedback shape so a reviewed student's
  // feedback does not visibly differ from an unreviewed one's.
  const allReqs = (rubric.checklistItems ?? []).flatMap((c) => c.items ?? []);
  let example = '';
  if (allReqs.length > 0) {
    example = `\\n<p><strong>${allReqs[0]}</strong></p>\\n<blockquote>You said: \\"[quote what student wrote]\\"</blockquote>\\n<p>[Correct/Incorrect/Incomplete — explain WHY; wrap math in backticks, e.g. \`x^2 + 3x\`]</p>\\n<p><em>To improve: [specific suggestion]</em></p>`;
    for (let i = 1; i < allReqs.length; i++) {
      example += `\\n<p><strong>${allReqs[i]}</strong></p>\\n<blockquote>You said: ...</blockquote>\\n<p>...</p>`;
    }
  }

  prompt += `
INSTRUCTIONS:
- Re-read each student's response carefully
- Compare against the rubric, scoring anchors, and the batch mean (${scaled(stats.mean)})
- If the original score seems correct, return the SAME score
- If the original score was too high or too low, return an ADJUSTED score
- Provide updated feedback in the same HTML format as the main batch

FEEDBACK FORMAT RULE: The feedback string must contain one section for EACH requirement from the GRADING CHECKLIST. Do NOT group by category. Use HTML tags: <strong> for the requirement header, <blockquote> for the student's words (or "You did not address this."), <p> for your evaluation, <em> for the "To improve" line. Wrap all math expressions in backticks, e.g. \`x^2 + 3x\` or \`p < 0.05\`. Start the feedback with "<p>Hi [name],</p>" using the student's first name. Do NOT use markdown (no **bold**, no "> quote", no *italic*).

RESPONSE FORMAT:
You MUST respond with a valid JSON array ONLY. No markdown, no code fences, no explanation.

[
  {
    "studentIndex": <0-based position in the list above>,
    "score": <integer 0-${virtualMax} (see SCORING SCALE above)>,
    "feedback": "<p>Hi [name],</p>${example}",
    "adjusted": <true if score changed, false if kept same>
  }
]

FORMAT REMINDER: each "feedback" string MUST be HTML with <strong>/<blockquote>/<p>/<em> tags exactly as shown above. No markdown.
CRITICAL: Return results for ALL ${outlierStudents.length} student(s) in the array.`;

  return prompt;
}

/**
 * Parse a review reply. Reuses the batch parser — including its repair ladder and its
 * positional mapping, which is what restores the real roster indices from the 0-based
 * ones the prompt asked for.
 */
export function parseOutlierReview(
  aiText: string,
  outlierStudents: OutlierStudent[],
  rubric: Rubric,
): BatchResult[] {
  const maxScore = parseFloat(String(rubric.maxScore ?? 10)) || 10;
  return parseBatchResponse(
    aiText,
    outlierStudents,
    maxScore,
    rubric.categoryWeights ?? null,
    rubric.categoryMaxPoints ?? null,
  );
}

/**
 * Overlay reviewed scores onto the batch. Returns a new array in roster order.
 *
 * A review that produced nothing parseable must not silently zero a student who already
 * had a real grade, so unparseable rows are dropped rather than applied — the caller sees
 * fewer revisions, never a destroyed one.
 */
export function applyReview(
  results: BatchResult[],
  reviewed: BatchResult[],
  isFailure: (r: BatchResult) => boolean,
): { merged: BatchResult[]; changed: number[] } {
  const usable = reviewed.filter((r) => !isFailure(r));
  const byIndex = new Map(usable.map((r) => [r.studentIndex, r]));
  const changed: number[] = [];

  const merged = results.map((r) => {
    const rev = byIndex.get(r.studentIndex);
    if (!rev) return r;
    if (rev.score !== r.score) changed.push(r.studentIndex);
    return rev;
  });

  return { merged, changed };
}

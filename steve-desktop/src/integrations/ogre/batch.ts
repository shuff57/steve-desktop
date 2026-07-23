/**
 * Batch grading — the "grade every student in one AI context" path.
 *
 * Ported from `grading-server/grading.js` (generateScoringAnchors, buildBatchPrompt,
 * parseBatchResponse, validateBatchResults, chunkStudents, mergeResults) and
 * `server.js` (buildBridgeResponses). Pure: no network, no DB.
 *
 * Grading a class in one context is what keeps scores consistent between students —
 * the model can compare them against each other, which a per-student loop cannot do.
 * Past the chunk size, bridge responses carry the standard from one chunk to the next.
 */
import { GRADING_PHILOSOPHY } from './grading-constants';
import {
  extractCustomInstructions,
  getScaleInfo,
  getScoringScaleString,
  normalizeFeedbackHtml,
  normalizeGreeting,
  snapScore,
  type Rubric,
  type RubricChecklistItem,
} from './grading';

export interface BatchStudent {
  index: number;
  name: string;
  response: string;
  /** Set when each student saw the same question with different randomized values. */
  prompt?: string;
}

export interface BatchResult {
  studentIndex: number;
  score: number;
  feedback: string;
}

export interface ScoringAnchors {
  excellent: { score: number; description: string };
  adequate: { score: number; description: string };
  belowAverage: { score: number; description: string };
  minimal: { score: number; description: string };
}

export interface BridgeResponse extends BatchResult {
  name: string;
  tier: string;
}

export interface StudentChunk {
  students: BatchStudent[];
  needsAnchors: boolean;
  chunkIndex: number;
}

/** Score levels for calibration. Descriptions stay generic — they calibrate level, not content. */
export function generateScoringAnchors(rubric: Rubric): ScoringAnchors {
  const maxScore = parseFloat(String(rubric.maxScore)) || 10;
  // Below 6 points, rounding to integers would collapse the anchors onto each other.
  const roundScore = (s: number) => (maxScore < 6 ? Math.round(s * 10) / 10 : Math.round(s));
  return {
    excellent: {
      score: roundScore(maxScore * 0.95),
      description: 'Demonstrates clear understanding with most key concepts addressed.',
    },
    adequate: {
      score: roundScore(maxScore * 0.8),
      description: 'Addresses the topic and shows awareness of key concepts, even with gaps or imprecision.',
    },
    belowAverage: {
      score: roundScore(maxScore * 0.65),
      description: 'Makes a genuine attempt that engages with the prompt, showing limited but real understanding.',
    },
    minimal: {
      score: roundScore(maxScore * 0.45),
      description: 'Shows some effort and awareness related to the topic, even if mostly incomplete.',
    },
  };
}

export function chunkStudents(students: BatchStudent[], chunkSize = 20): StudentChunk[] {
  if (!students || students.length === 0) return [];
  if (students.length <= chunkSize) return [{ students, needsAnchors: false, chunkIndex: 0 }];

  const chunks: StudentChunk[] = [];
  for (let i = 0; i < students.length; i += chunkSize) {
    chunks.push({
      students: students.slice(i, i + chunkSize),
      needsAnchors: i > 0, // the first chunk sets the standard; later ones inherit it
      chunkIndex: chunks.length,
    });
  }
  return chunks;
}

export function mergeResults(chunkResults: BatchResult[][]): BatchResult[] {
  if (!chunkResults || chunkResults.length === 0) return [];
  return chunkResults.flat().sort((a, b) => a.studentIndex - b.studentIndex);
}

/**
 * Pick graded examples from the finished chunk to calibrate the next one, spread across
 * quality tiers. Without this, each chunk grades in isolation and the class ends up with
 * a visible seam where chunk 1 was harsher than chunk 2.
 */
export function buildBridgeResponses(
  chunkResults: BatchResult[],
  chunkStudentList: BatchStudent[],
  anchors: ScoringAnchors,
): BridgeResponse[] {
  if (!chunkResults || chunkResults.length === 0) return [];
  const getName = (r: BatchResult) =>
    chunkStudentList.find((s) => s.index === r.studentIndex)?.name ?? `Student ${r.studentIndex}`;

  const excellentThreshold = anchors.excellent.score - 1;
  const adequateRange: [number, number] = [anchors.belowAverage.score + 1, anchors.excellent.score - 1];
  const belowAvgRange: [number, number] = [anchors.minimal.score + 1, anchors.belowAverage.score];
  const minimalThreshold = anchors.minimal.score + 1;

  const pickFromTier = (tier: BatchResult[], label: string, count = 2): BridgeResponse[] => {
    if (tier.length === 0) return [];
    const sorted = [...tier].sort((a, b) => a.score - b.score);
    const picks = sorted.length >= 2 && count >= 2 ? [sorted[0]!, sorted[sorted.length - 1]!] : [sorted[0]!];
    return picks.slice(0, count).map((r) => ({ ...r, name: getName(r), tier: label }));
  };

  const bridges = [
    ...pickFromTier(
      chunkResults.filter((r) => r.score >= excellentThreshold),
      'excellent',
    ),
    ...pickFromTier(
      chunkResults.filter((r) => r.score >= adequateRange[0] && r.score <= adequateRange[1]),
      'adequate',
    ),
    ...pickFromTier(
      chunkResults.filter((r) => r.score >= belowAvgRange[0] && r.score <= belowAvgRange[1]),
      'belowAverage',
    ),
    ...pickFromTier(
      chunkResults.filter((r) => r.score < minimalThreshold),
      'minimal',
    ),
  ];

  // Sparse distribution (everyone clustered in one tier) — fall back to a low/mid/high spread.
  if (bridges.length < 3) {
    const sorted = [...chunkResults].sort((a, b) => a.score - b.score);
    const fallback = [sorted[0]!, sorted[Math.floor(sorted.length / 2)]!, sorted[sorted.length - 1]!];
    return [...new Map(fallback.map((r) => [r.studentIndex, { ...r, name: getName(r), tier: 'spread' }])).values()];
  }
  return [...new Map(bridges.map((b) => [b.studentIndex, b])).values()];
}

// Numbers from one student's jittered question must not bleed into another's evaluation.
const stripBridgeNumbers = (text: string) => String(text ?? '').replace(/-?\d+(?:\.\d+)?/g, '[N]');

export function buildBatchPrompt(
  rubric: Rubric,
  students: BatchStudent[],
  anchors: ScoringAnchors,
  bridgeResponses: BridgeResponse[] | null = null,
): string {
  const maxScore = rubric.maxScore ?? '10';
  const { virtualMax, factor: scoreFactor } = getScaleInfo(maxScore);
  const scoreHint = 'integer 0-10 (see SCORING SCALE below)';

  let essayPrompt = rubric.essayPrompt || '(No prompt provided)';
  let customInstructions = rubric.customInstructions || '';
  // Older rubrics carry instructions appended to the prompt body; split them back out.
  const instrMatch = essayPrompt.match(/\n\nADDITIONAL GRADING INSTRUCTIONS:\n([\s\S]+)$/);
  if (instrMatch) {
    customInstructions = instrMatch[1]!.trim();
    essayPrompt = essayPrompt.replace(/\n\nADDITIONAL GRADING INSTRUCTIONS:\n[\s\S]+$/, '').trim();
  }
  const { calibration, overrideInstructions } = extractCustomInstructions(customInstructions);

  let prompt = `You are an expert grading assistant. Grade ALL students in this batch against the provided rubric. Output: JSON array only.

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
    prompt += '\nGRADING REQUIREMENTS (write feedback for EACH numbered item):\n';
    let reqNum = 1;
    for (const item of rubric.checklistItems) {
      for (const sub of item.items ?? []) {
        prompt += `${reqNum}. [${item.category || 'General'}] ${sub}\n`;
        reqNum++;
      }
    }
    prompt += `\nSCORING BY CATEGORY (score each 0-10, server applies weights automatically):\n`;
    for (const item of rubric.checklistItems) {
      if (item.category) prompt += `- ${item.category}: 10 points\n`;
    }
    prompt += `\nPARTIAL CREDIT RULE: When a requirement is addressed conceptually but lacks specific values, formulas, or concrete evidence, award 40-60% of that category's points. Award 20-40% if only loosely related; 60-80% if substantially complete but missing one key element. Evaluate each requirement INDEPENDENTLY - do not let strength on one compensate for weakness on another. For 5-point categories, map bands roughly as: 1-2 pts (20-40%), 2-3 pts (40-60%), 3-4 pts (60-80%); reserve 4-5 pts for essentially complete/correct coverage.

CONCEPTUAL THOROUGHNESS RULE: When a student demonstrates genuine understanding of the underlying concept — correct reasoning, valid approach, or sound logic — but has execution flaws (arithmetic errors, missing units, informal notation, incomplete steps), award 60-80% of that category's points. Concept mastery with flawed execution always scores higher than rote correctness without understanding. A student who explains WHY a method works but makes a calculation error deserves more credit than one who copies a formula without understanding it.\n`;
  }

  if (rubric.categoryWeights && Object.keys(rubric.categoryWeights).length > 0) {
    prompt += `\nCATEGORY WEIGHTS (percentage of total grade):\n`;
    for (const [cat, pct] of Object.entries(rubric.categoryWeights)) prompt += `- ${cat}: ${pct}% of total grade\n`;
    prompt += `Grade each category on its own merits. These weights will be applied automatically.\n`;
  }

  if (rubric.rubricItems && rubric.rubricItems.length > 0) {
    prompt += '\nKEY CONCEPTS TO ADDRESS:\n';
    for (const item of rubric.rubricItems as RubricChecklistItem[]) {
      if (item.category) prompt += `${item.category}:\n`;
      for (const sub of item.items ?? []) prompt += `  - ${sub}\n`;
    }
  }

  if (rubric.modelText) prompt += `\nMODEL RESPONSE (for reference):\n${rubric.modelText}\n`;

  const scaled = (s: number) => Math.round(s * scoreFactor * 10) / 10;
  prompt += `
SCORING ANCHORS (use these as calibration references):
- Excellent (${scaled(anchors.excellent.score)}/${virtualMax}): ${anchors.excellent.description}
- Adequate (${scaled(anchors.adequate.score)}/${virtualMax}): ${anchors.adequate.description}
- Below Average (${scaled(anchors.belowAverage.score)}/${virtualMax}): ${anchors.belowAverage.description}
- Minimal (${scaled(anchors.minimal.score)}/${virtualMax}): ${anchors.minimal.description}

Compare each student response to these anchors to ensure consistency.

${getScoringScaleString()}


`;

  const floor = 8;
  const floorHigh = Math.min(floor + 1, 10);
  prompt += `
CRITICAL: A response that correctly hits every rubric criterion earns ${floor}-${floorHigh}, REGARDLESS of length.
A short, accurate answer scores higher than a long, partially-wrong one.
Only drop below ${floor} if a rubric criterion is genuinely missing or incorrect — NOT merely brief.
This ${floor}+ rule applies only when ALL rubric criteria are substantively addressed — brevity cannot compensate for missing sub-criteria.
Score ${floor} requires all rubric criteria to be substantively correct; score ${floor - 1} when one criterion is only partially met or missing a key element — but if conceptual understanding is demonstrated, score ${floor - 1} not ${floor - 2}.
`;

  if (calibration) {
    prompt += `\nSCORING CALIBRATION EXAMPLES (use to calibrate score levels only — grade against rubric criteria and SCORING SCALE above, not these examples):\n${calibration}\n`;
  }

  if (bridgeResponses && bridgeResponses.length > 0) {
    prompt += `
CALIBRATION EXAMPLES (from previously graded batch — you MUST match this scoring standard):
`;
    const tiers: Record<string, BridgeResponse[]> = {};
    for (const br of bridgeResponses) (tiers[br.tier || 'other'] ??= []).push(br);

    const tierLabels: Record<string, string> = {
      excellent: 'HIGH QUALITY',
      adequate: 'AVERAGE QUALITY',
      belowAverage: 'BELOW AVERAGE',
      minimal: 'LOW QUALITY',
      spread: 'REFERENCE',
    };
    for (const [tier, examples] of Object.entries(tiers)) {
      prompt += `\n${tierLabels[tier] ?? tier.toUpperCase()}:\n`;
      for (const br of examples) {
        prompt += `  - "${br.name || 'Student ' + br.studentIndex}" = ${scaled(br.score)}/${virtualMax}: ${stripBridgeNumbers(
          (br.feedback ?? '').substring(0, 300),
        )}\n`;
      }
    }
    prompt += `
CONSISTENCY RULES:
- A response of SIMILAR quality to a calibration example MUST receive a SIMILAR score (within 1 point).
- A response BETTER than the "high quality" examples should score the same or higher.
- A response WORSE than the "low quality" examples should score the same or lower.
- Score distribution should be comparable to the previous batch.
`;
  }

  const hasPerStudentPrompts = students.some((s) => s.prompt && s.prompt !== essayPrompt);
  if (hasPerStudentPrompts) {
    prompt += `\nIMPORTANT: Each student received the same question structure but with DIFFERENT randomized numeric values. Each student's specific question is shown below their header. Grade each student against THEIR specific values, not the shared prompt above.\n`;
  }

  prompt += '\nSTUDENTS TO GRADE:\n\n';
  for (const student of students) {
    prompt += `--- Student ${student.index}: ${student.name} ---\n`;
    if (hasPerStudentPrompts && student.prompt) prompt += `THEIR QUESTION: ${student.prompt}\n`;
    prompt += `${student.response || '(No response submitted)'}\n\n`;
  }

  const corItems = (rubric.checklistItems ?? []).map((c) => ({
    name: (c.category ?? '').replace(/\s*\(\d+\s*pts?\)/i, '').trim(),
  }));
  const corField =
    corItems.length > 0
      ? `    "criterion_scores": {${corItems.map(({ name }) => `"${name}": <0-10>`).join(', ')}},\n`
      : '';

  const firstIdx = students[0]?.index ?? 0;
  const secondIdx = students.length > 1 ? (students[1]?.index ?? firstIdx + 1) : firstIdx + 1;

  const allReqs: string[] = [];
  for (const item of rubric.checklistItems ?? []) for (const sub of item.items ?? []) allReqs.push(sub);

  let feedbackExample = '';
  if (allReqs.length > 0) {
    feedbackExample = `\\n<p><strong>${allReqs[0]}</strong></p>\\n<blockquote>You said: \\"[quote what student wrote]\\"</blockquote>\\n<p>[Correct/Incorrect/Incomplete — explain WHY; wrap math in backticks, e.g. \`x^2 + 3x\`]</p>\\n<p><em>To improve: [specific suggestion]</em></p>`;
    if (allReqs.length > 1) {
      feedbackExample += `\\n<p><strong>${allReqs[1]}</strong></p>\\n<blockquote>You did not address this.</blockquote>\\n<p>[Explain what was expected]</p>\\n<p><em>To improve: [specific suggestion]</em></p>`;
    }
    for (let i = 2; i < allReqs.length; i++) {
      feedbackExample += `\\n<p><strong>${allReqs[i]}</strong></p>\\n<blockquote>You said: ...</blockquote>\\n<p>...</p>`;
    }
  }

  prompt += `
${corItems.length > 0 ? `GRADING PROCESS:\nFor each student: (1) score each category independently using the PARTIAL CREDIT RULE above, (2) record category scores in criterion_scores, (3) sum for the final score. Do NOT adjust scores to hit a desired total.\n` : ''}

FEEDBACK FORMAT RULE: The feedback string must contain one section for EACH numbered requirement from GRADING REQUIREMENTS. Do NOT group by category. Use HTML tags: <strong> for the requirement header, <blockquote> for the student's words (or "You did not address this."), <p> for your evaluation, <em> for the "To improve" line. Wrap all math expressions in backticks, e.g. \`x^2 + 3x\` or \`p < 0.05\`. For ANY requirement that is not at full credit, you MUST include both (a) a specific reason citing the student's words or omission, and (b) a "To improve" line with an actionable next step. Critiques and "To improve" suggestions MUST cite a specific rubric requirement that was not met. Do NOT invent additional standards (e.g., showing intermediate work, extra clarity, formatting polish) that the rubric does not explicitly require. If the response satisfies what the rubric asks, mark it correct — even if a more detailed answer is conceivable. criterion_scores uses categories for scoring, but feedback MUST be per-requirement.

[
  {
    "studentIndex": ${firstIdx},
    ${corField}    "score": <${scoreHint}>
    "feedback": "<p>Hi [name],</p>${feedbackExample}"
  },
  {
    "studentIndex": ${secondIdx},
    ${corField}    "score": <${scoreHint}>
    "feedback": "<same format — one section per requirement>"
  }
  // ... continue for all ${students.length} students
]

CRITICAL: Return results for ALL ${students.length} students. Use the studentIndex from each \"--- Student N:\" header.

FORMAT REMINDER: each "feedback" string MUST be HTML with <strong>/<blockquote>/<p>/<em> tags exactly as shown in the template above. Do NOT use markdown (no **bold**, no "> quote", no *italic*).`;

  return prompt;
}

/**
 * Map parsed results onto students POSITIONALLY — Nth result to Nth student — and
 * ignore the model's own studentIndex. Models routinely renumber from 0 in every
 * chunk, so trusting their index silently assigns chunk 2's grades to chunk 1's
 * students. The prompt guarantees order, so position is the reliable key.
 */
function validateBatchResults(
  parsed: { score?: unknown; feedback?: unknown; criterion_scores?: Record<string, unknown> }[],
  students: BatchStudent[],
  maxScore: number,
  categoryWeights?: Record<string, number> | null,
  categoryMaxPoints?: Record<string, number> | null,
): BatchResult[] {
  const { factor } = getScaleInfo(maxScore);

  const results = parsed.map((item, idx) => {
    let score = parseFloat(String(item.score));
    if (isNaN(score) || score < 0) score = 0;

    let usedWeighting = false;
    if (categoryWeights && item.criterion_scores && typeof item.criterion_scores === 'object') {
      let weightedFraction = 0;
      let validCount = 0;
      for (const [category, catScore] of Object.entries(item.criterion_scores)) {
        const raw = parseFloat(String(catScore));
        if (isNaN(raw) || raw < 0) continue;
        const weightPct = categoryWeights[category];
        if (weightPct == null) continue;
        const catMax = categoryMaxPoints?.[category] || 10;
        weightedFraction += (raw / catMax) * (weightPct / 100);
        validCount++;
      }
      if (validCount > 0) {
        score = weightedFraction * maxScore;
        usedWeighting = true;
      }
    }

    if (!usedWeighting) score = score / factor;
    if (score > maxScore) score = maxScore;
    score = snapScore(score, maxScore);

    const rawFeedback = Array.isArray(item.feedback) ? item.feedback.join('\n') : (item.feedback ?? '');
    const normalized = normalizeFeedbackHtml(String(rawFeedback).trim()) || 'Graded by AI.';
    const student = idx < students.length ? students[idx] : null;
    const feedback = normalizeGreeting(normalized, student?.name);
    const studentIndex = student ? student.index : idx;

    return { studentIndex, score, feedback };
  });

  // Extra results carry unreliable indices and would create phantom gradebook entries.
  return results.length > students.length ? results.slice(0, students.length) : results;
}

/**
 * Parse a batch reply. Models wrap the array in objects, prose, fences and trailing
 * commas; each fallback is one observed failure mode. A student the model skipped
 * entirely must surface as an error, never as a silent 0.
 */
export function parseBatchResponse(
  aiText: string,
  students: BatchStudent[],
  maxScore: number,
  categoryWeights?: Record<string, number> | null,
  categoryMaxPoints?: Record<string, number> | null,
): BatchResult[] {
  let text = (aiText ?? '').trim();
  text = text.replace(/<think>[\s\S]*?<\/think>/gi, '').trim();

  const fenceMatch = text.match(/```json\s*([\s\S]*?)\s*```/) || text.match(/```\s*([\s\S]*?)\s*```/);
  if (fenceMatch) text = fenceMatch[1]!.trim();

  const unwrap = (p: unknown): unknown[] | null => {
    if (Array.isArray(p)) return p;
    if (p && typeof p === 'object') {
      const o = p as Record<string, unknown>;
      for (const key of ['results', 'students', 'grades', 'data']) {
        if (Array.isArray(o[key])) return o[key] as unknown[];
      }
    }
    return null;
  };

  const tryParse = (str: string): unknown[] | null => {
    const fixLatex = (s: string) => s.replace(/\\(?!["\\/bfnrtu])/g, '\\\\');
    const fixTrailingCommas = (s: string) => s.replace(/,\s*([}\]])/g, '$1');
    for (const candidate of [str, fixLatex(str), fixLatex(fixTrailingCommas(str))]) {
      try {
        const got = unwrap(JSON.parse(candidate));
        if (got) return got;
      } catch {
        /* next repair */
      }
    }
    return null;
  };

  const validate = (rows: unknown[]) =>
    validateBatchResults(rows as never[], students, maxScore, categoryWeights, categoryMaxPoints);

  const whole = tryParse(text);
  if (whole) return validate(whole);

  const arrayMatch = text.match(/\[[\s\S]*\]/);
  if (arrayMatch) {
    const inner = tryParse(arrayMatch[0]);
    if (inner) return validate(inner);
  }

  // Individual objects, when the array as a whole is malformed.
  const objPattern = /\{\s*"studentIndex"\s*:\s*(\d+)\s*,\s*"score"\s*:\s*(\d+\.?\d*)\s*,\s*"feedback"\s*:\s*"([^"]*)"/g;
  const regexResults: unknown[] = [];
  let m: RegExpExecArray | null;
  while ((m = objPattern.exec(text)) !== null) {
    regexResults.push({ studentIndex: parseInt(m[1]!, 10), score: parseFloat(m[2]!), feedback: m[3] });
  }
  if (regexResults.length > 0) return validate(regexResults);

  // Last resort: prose score lines like "Student 5: 8/10".
  const linePattern = /student\s*(\d+)[^:]*:\s*(\d+\.?\d*)\s*\/\s*\d+/gi;
  const lineResults: unknown[] = [];
  while ((m = linePattern.exec(text)) !== null) {
    lineResults.push({
      studentIndex: parseInt(m[1]!, 10),
      score: parseFloat(m[2]!),
      feedback: 'Score extracted from non-JSON response.',
    });
  }
  if (lineResults.length > 0) return validate(lineResults);

  return students.map((student, idx) => ({
    studentIndex: student.index ?? idx,
    score: 0,
    feedback: 'Error parsing AI response. Please try again.',
  }));
}

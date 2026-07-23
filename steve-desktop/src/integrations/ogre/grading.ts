/**
 * Single-student grading: prompt construction and response parsing.
 *
 * Ported from `grading-server/grading.js` (buildSingleGradePrompt,
 * parseSingleGradeResponse, clampSingleResult and their helpers). Everything here is
 * pure — no network, no DB — so the prompt wording and the score arithmetic are
 * directly testable. The impure half (redaction, model call) lives in ./grade.ts.
 *
 * Scoring convention, carried over from O.G.R.E: every prompt asks the model for an
 * integer 0-10 regardless of the rubric's real maxScore, and the score is converted
 * back here. Models calibrate far better against a fixed 0-10 scale than against an
 * arbitrary one.
 */
import { GRADING_PHILOSOPHY, SCORING_SCALE_DESCRIPTORS } from './grading-constants';

export interface RubricChecklistItem {
  category: string;
  items?: string[];
  points?: number;
  categoryWeight?: number;
  criterionWeight?: number;
}

export interface Rubric {
  /** Real maximum score. Prompts always ask for 0-10; the reply is scaled back to this. */
  maxScore?: number | string;
  essayPrompt?: string;
  customInstructions?: string;
  checklistItems?: RubricChecklistItem[];
  rubricItems?: RubricChecklistItem[];
  modelText?: string;
  categoryWeights?: Record<string, number>;
  categoryMaxPoints?: Record<string, number>;
  weightMode?: 'category' | 'criterion' | string;
}

export interface GradeResult {
  score: number;
  feedback: string;
}

// ── helpers ────────────────────────────────────────────────────────────────────

/**
 * Custom instructions double as a carrier for scoring calibration examples. When the
 * text opens with "SCORING CALIBRATION:", everything up to the first blank line that
 * is NOT followed by an anchor label is calibration; the remainder is an instructor
 * override that outranks the base philosophy.
 */
export function extractCustomInstructions(customInstructions?: string): {
  calibration: string | null;
  overrideInstructions: string | null;
} {
  if (!customInstructions) return { calibration: null, overrideInstructions: null };
  if (!customInstructions.startsWith('SCORING CALIBRATION:')) {
    return { calibration: null, overrideInstructions: customInstructions };
  }

  const withoutHeader = customInstructions.slice('SCORING CALIBRATION:\n'.length);
  const ANCHOR_LABELS = ['Excellent', 'Adequate', 'Below Average', 'Minimal'];
  let splitPos = -1;
  let searchFrom = 0;
  while (searchFrom < withoutHeader.length) {
    const idx = withoutHeader.indexOf('\n\n', searchFrom);
    if (idx === -1) break;
    const afterBreak = withoutHeader.slice(idx + 2).trimStart();
    if (afterBreak.length > 0 && !ANCHOR_LABELS.some((l) => afterBreak.startsWith(l))) {
      splitPos = idx;
      break;
    }
    searchFrom = idx + 2;
  }

  const calibPart = (splitPos === -1 ? withoutHeader : withoutHeader.slice(0, splitPos)).trim();
  const overridePart = (splitPos === -1 ? '' : withoutHeader.slice(splitPos)).trim();
  return { calibration: calibPart || null, overrideInstructions: overridePart || null };
}

/** All prompts grade out of 10; `factor` converts the reply back to the real maxScore. */
export function getScaleInfo(maxScore: number | string | undefined): { virtualMax: number; factor: number } {
  const max = parseFloat(String(maxScore)) || 10;
  return { virtualMax: 10, factor: 10 / max };
}

/** Round to an increment the max score can actually express. */
export function snapScore(score: number, maxScore: number): number {
  if (maxScore >= 5) return Math.round(score * 2) / 2; // 0.5 increments
  if (maxScore >= 2) return Math.round(score * 4) / 4; // 0.25 increments
  return Math.round(score * 10) / 10; // 0.1 increments
}

export function getScoringScaleString(customDescriptors?: { score: number; descriptor: string }[]): string {
  const descriptors = customDescriptors ?? SCORING_SCALE_DESCRIPTORS;
  return (
    'SCORING SCALE (use integers 0-10 — server converts to actual points):\n' +
    descriptors.map((s) => `${s.score.toString().padStart(2)} – ${s.descriptor}`).join('\n')
  );
}

/** "Doe, Jane" -> "Jane"; "Jane Doe" -> "Jane". */
export function extractFirstName(name?: string): string {
  const raw = (name || '').trim();
  if (!raw) return '';
  return raw.includes(',')
    ? raw.split(',').slice(1).join(',').trim().split(/\s+/)[0]!
    : raw.split(/\s+/)[0]!;
}

/**
 * Models echo back whatever name they were shown, which in a gradebook is usually
 * "Last, First". Rewrite the greeting to first-name-only without touching the body.
 */
export function normalizeGreeting(html: string, name?: string): string {
  if (!html || typeof html !== 'string') return html;
  const firstName = extractFirstName(name);
  if (!firstName) return html;
  return html.replace(/<p>\s*(?:Hi|Hello)\s+[^<]*?<\/p>/i, `<p>Hi ${firstName},</p>`);
}

/**
 * Some models drop the requested HTML and answer in markdown under long-context
 * drift. Convert it back so downstream rendering stays consistent. Input is returned
 * untouched when it already contains HTML or has no markdown markers.
 */
export function normalizeFeedbackHtml(feedback: string): string {
  if (!feedback || typeof feedback !== 'string') return feedback;
  if (/<(p|strong|blockquote|em)\b/i.test(feedback)) return feedback;
  const hasMarkdown = /\*\*[^*\n]+\*\*/.test(feedback) || /^\s*>\s+/m.test(feedback);
  if (!hasMarkdown) return feedback;
  const out: string[] = [];
  for (let line of feedback.split(/\r?\n/)) {
    line = line.trim();
    if (!line) continue;
    line = line.replace(/\*\*([^*\n]+)\*\*/g, '<strong>$1</strong>');
    line = line.replace(/(^|[^*])\*([^*\n]+)\*(?!\*)/g, '$1<em>$2</em>');
    if (/^>\s+/.test(line)) out.push('<blockquote>' + line.replace(/^>\s+/, '') + '</blockquote>');
    else out.push('<p>' + line + '</p>');
  }
  return out.join('');
}

// ── prompt ─────────────────────────────────────────────────────────────────────

export function buildSingleGradePrompt(rubric: Rubric, studentWork: string, instructions?: string): string {
  const maxScore = rubric.maxScore ?? '10';
  const { virtualMax } = getScaleInfo(maxScore);
  const essayPrompt = rubric.essayPrompt || '(No prompt provided)';
  const { calibration, overrideInstructions } = extractCustomInstructions(rubric.customInstructions);

  // Weighted modes are resolved here — the model only ever sees adjusted point totals,
  // never the raw weights, so it can't try to apply them itself.
  const weightMode = rubric.weightMode;
  const effectivePoints = (item: RubricChecklistItem): number => {
    const max = parseFloat(String(maxScore)) || 10;
    if (weightMode === 'category' && item.categoryWeight != null) {
      const base = item.points && item.points > 0 ? item.points : max;
      return Math.round(base * (item.categoryWeight / 100) * 10) / 10;
    }
    if (weightMode === 'criterion' && item.criterionWeight != null) {
      const base = item.points && item.points > 0 ? item.points : max;
      return Math.round(base * (item.criterionWeight / 100) * 10) / 10;
    }
    return item.points && item.points > 0 ? item.points : max;
  };

  let prompt = `You are an expert grading assistant. Grade this student's work against the provided rubric. Output: JSON object only.

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
    prompt += `\nPARTIAL CREDIT RULE: When a requirement is addressed conceptually but lacks specific values, formulas, or concrete evidence, award 40-60% of that category's points. Award 20-40% if only loosely related; 60-80% if substantially complete but missing one key element. Evaluate each requirement INDEPENDENTLY - do not let strength on one compensate for weakness on another.

CONCEPTUAL THOROUGHNESS RULE: When a student demonstrates genuine understanding of the underlying concept — correct reasoning, valid approach, or sound logic — but has execution flaws (arithmetic errors, missing units, informal notation, incomplete steps), award 60-80% of that category's points. Concept mastery with flawed execution always scores higher than rote correctness without understanding. A student who explains WHY a method works but makes a calculation error deserves more credit than one who copies a formula without understanding it.\n`;
  }

  if (rubric.categoryWeights && typeof rubric.categoryWeights === 'object') {
    const weightEntries = Object.entries(rubric.categoryWeights);
    if (weightEntries.length > 0) {
      prompt += `\nCATEGORY WEIGHTS (percentage of total grade):\n`;
      for (const [cat, pct] of weightEntries) prompt += `- ${cat}: ${pct}% of total grade\n`;
      prompt += `Grade each category on its own merits. These weights will be applied automatically.\n`;
    }
  }

  if (rubric.rubricItems && rubric.rubricItems.length > 0) {
    prompt += '\nKEY CONCEPTS TO ADDRESS:\n';
    for (const item of rubric.rubricItems) {
      if (item.category) prompt += `${item.category}:\n`;
      for (const sub of item.items ?? []) prompt += `  - ${sub}\n`;
    }
  }

  if (rubric.modelText) prompt += `\nMODEL RESPONSE (for reference):\n${rubric.modelText}\n`;

  prompt += '\n' + getScoringScaleString() + '\n';
  if (calibration) {
    prompt += `\nSCORING CALIBRATION EXAMPLES (use to calibrate score levels only — grade against rubric criteria and SCORING SCALE above, not these examples):\n${calibration}\n`;
  }

  prompt += `\nSTUDENT WORK:\n${studentWork || '(No response submitted)'}\n`;
  if (instructions) prompt += `\nADDITIONAL INSTRUCTIONS:\n${instructions}\n`;

  const corItems = (rubric.checklistItems ?? []).map((c) => ({
    name: (c.category ?? '').replace(/\s*\(\d+\s*pts?\)/i, '').trim(),
    pts: effectivePoints(c),
  }));
  const corField =
    corItems.length > 0
      ? `  "criterion_scores": {${corItems.map(({ name, pts }) => `"${name}": <0-${pts} pts>`).join(', ')}},\n`
      : '';

  const allReqs: string[] = [];
  for (const item of rubric.checklistItems ?? []) for (const sub of item.items ?? []) allReqs.push(sub);

  let feedbackExample = '';
  if (allReqs.length > 0) {
    feedbackExample = `\\n<p><strong>${allReqs[0]}</strong></p>\\n<blockquote>You said: \\"[quote]\\"</blockquote>\\n<p>[Correct/Incorrect — explain WHY; wrap math in backticks, e.g. \`x^2 + 3x\`]</p>\\n<p><em>To improve: [suggestion]</em></p>`;
    if (allReqs.length > 1) {
      feedbackExample += `\\n<p><strong>${allReqs[1]}</strong></p>\\n<blockquote>You did not address this.</blockquote>\\n<p>[What was expected]</p>\\n<p><em>To improve: [suggestion]</em></p>`;
    }
    for (let i = 2; i < allReqs.length; i++) {
      feedbackExample += `\\n<p><strong>${allReqs[i]}</strong></p>\\n<blockquote>You said: ...</blockquote>\\n<p>...</p>`;
    }
  }

  prompt += `
${corItems.length > 0 ? 'GRADING PROCESS: Score each category independently using the PARTIAL CREDIT RULE, then sum for the final score.\n' : ''}
FEEDBACK FORMAT RULE: The feedback string must contain one section for EACH numbered requirement from GRADING REQUIREMENTS. Do NOT group by category. Use HTML tags: <strong> for the requirement header, <blockquote> for the student's words (or "You did not address this."), <p> for your evaluation, <em> for the "To improve" line. Wrap all math expressions in backticks, e.g. \`x^2 + 3x\` or \`p < 0.05\`. For ANY requirement that is not at full credit, you MUST include both (a) a specific reason citing the student's words or omission, and (b) a "To improve" line with an actionable next step. Critiques and "To improve" suggestions MUST cite a specific rubric requirement that was not met. Do NOT invent additional standards (e.g., showing intermediate work, extra clarity, formatting polish) that the rubric does not explicitly require. If the response satisfies what the rubric asks, mark it correct — even if a more detailed answer is conceivable. criterion_scores uses categories for scoring, but feedback MUST be per-requirement.

RESPONSE FORMAT:
Return ONLY valid JSON. No markdown code fences. No explanation text.

{
${corField}  "score": <integer 0-10 (see SCORING SCALE below)>
  "feedback": "<p>Hi [name],</p>${feedbackExample}"
}

FORMAT REMINDER: the "feedback" string MUST be HTML with <strong>/<blockquote>/<p>/<em> tags exactly as shown above. Do NOT use markdown (no **bold**, no "> quote", no *italic*).`;

  return prompt;
}

// ── parse ──────────────────────────────────────────────────────────────────────

function clampSingleResult(
  parsed: { score?: unknown; feedback?: unknown; criterion_scores?: Record<string, unknown> },
  maxScore: number,
  categoryWeights?: Record<string, number> | null,
  categoryMaxPoints?: Record<string, number> | null,
): GradeResult {
  let score = parseFloat(String(parsed.score));
  if (isNaN(score) || score < 0) score = 0;

  // Percentage-weighted composite: sum(catScore / catMax * weightPct / 100) * maxScore.
  // This already produces a real-scale score, so it skips the descale below.
  let usedWeighting = false;
  if (categoryWeights && parsed.criterion_scores && typeof parsed.criterion_scores === 'object') {
    let weightedFraction = 0;
    let validCount = 0;
    for (const [category, catScore] of Object.entries(parsed.criterion_scores)) {
      const raw = parseFloat(String(catScore));
      if (isNaN(raw) || raw < 0) continue;
      const weightPct = categoryWeights[category];
      if (weightPct == null) continue; // a category with no weight doesn't contribute
      const catMax = categoryMaxPoints?.[category] || 10;
      weightedFraction += (raw / catMax) * (weightPct / 100);
      validCount++;
    }
    if (validCount > 0) {
      score = weightedFraction * maxScore;
      usedWeighting = true;
    }
  }

  if (!usedWeighting) {
    const { factor } = getScaleInfo(maxScore);
    score = score / factor; // descale from the virtual 0-10 back to the real max
  }
  if (score > maxScore) score = maxScore;
  score = snapScore(score, maxScore);

  const feedback = normalizeFeedbackHtml(String(parsed.feedback ?? '').trim()) || 'Graded by AI.';
  return { score, feedback };
}

/**
 * Parse a model reply into { score, feedback }. Models wrap JSON in prose, code
 * fences, and reasoning blocks, and LaTeX backslashes routinely break JSON.parse —
 * hence the four escalating attempts before giving up.
 */
export function parseSingleGradeResponse(
  aiText: string,
  maxScore: number,
  categoryWeights?: Record<string, number> | null,
  categoryMaxPoints?: Record<string, number> | null,
  studentName?: string,
): GradeResult {
  let text = (aiText ?? '').trim();
  text = text.replace(/<think>[\s\S]*?<\/think>/gi, '').trim();

  const fenceMatch = text.match(/```json\s*([\s\S]*?)\s*```/) || text.match(/```\s*([\s\S]*?)\s*```/);
  if (fenceMatch) text = fenceMatch[1]!.trim();

  const finalize = (result: GradeResult): GradeResult => {
    if (studentName) result.feedback = normalizeGreeting(result.feedback, studentName);
    return result;
  };
  const clamp = (p: object) => clampSingleResult(p, maxScore, categoryWeights, categoryMaxPoints);
  // Unescaped LaTeX backslashes are the usual JSON.parse failure; double them and retry.
  const fixLatex = (s: string) => s.replace(/\\(?!["\\/bfnrtu])/g, '\\\\');

  for (const candidate of [text, fixLatex(text)]) {
    try {
      return finalize(clamp(JSON.parse(candidate)));
    } catch {
      /* next strategy */
    }
  }

  const objMatch = text.match(/\{[\s\S]*\}/);
  if (objMatch) {
    for (const candidate of [objMatch[0], fixLatex(objMatch[0])]) {
      try {
        return finalize(clamp(JSON.parse(candidate)));
      } catch {
        /* next strategy */
      }
    }
  }

  const regexMatch = text.match(/"score"\s*:\s*(\d+\.?\d*)\s*,\s*"feedback"\s*:\s*"([^"]*)"/);
  if (regexMatch) {
    return finalize(clamp({ score: parseFloat(regexMatch[1]!), feedback: regexMatch[2] }));
  }

  return { score: 0, feedback: 'Error parsing AI response. Please try again.' };
}

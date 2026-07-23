/**
 * Generated scoring anchors — worked example responses at each quality tier.
 *
 * Ported from `grading-server/server.js` (buildAnchorGenerationPrompt,
 * parseAnchorResponses). `generateScoringAnchors` in ./batch.ts produces the *scores* and
 * generic descriptions; this produces the far more useful thing: what an actual answer to
 * THIS question looks like at each level, written by the model and then edited by you.
 *
 * The result is stored as the rubric's `customInstructions`, prefixed `SCORING CALIBRATION:`
 * — the exact shape `extractCustomInstructions` already splits out and `buildBatchPrompt`
 * already emits under "SCORING CALIBRATION EXAMPLES". No new plumbing; the carrier existed
 * and was simply never filled.
 *
 * No student work reaches this prompt — only the rubric — so there is nothing to redact.
 * The caller still routes it through the same CLI transport as everything else.
 */
import type { Rubric } from './grading';
import type { ScoringAnchors } from './batch';

export interface AnchorExample {
  label: string;
  score: number;
  response: string;
}

/** The four tiers, in the order the prompt asks for them. */
export function anchorTiers(anchors: ScoringAnchors): { label: string; score: number }[] {
  return [
    { label: 'Excellent', score: anchors.excellent.score },
    { label: 'Adequate', score: anchors.adequate.score },
    { label: 'Below Average', score: anchors.belowAverage.score },
    { label: 'Minimal', score: anchors.minimal.score },
  ];
}

/**
 * Leniency has to reach the examples too. A lenient rubric graded against strict
 * exemplars is still graded strictly — the exemplars are what the grader actually
 * compares against, so they must be written in the same register as the criteria.
 */
function leniencyGuidance(leniency: number | null): string {
  if (leniency == null || leniency === 50) return '';
  if (leniency < 50) {
    return `CRITICAL — LENIENT GRADING MODE (${50 - leniency}% more lenient than standard):
The teacher has chosen lenient grading. Your example responses MUST reflect how a LENIENT teacher would grade. This means:

- EXCELLENT: Complete and correct, but does NOT need to show every step or use formal notation. A clear, concise answer that covers all concepts counts as excellent.
- ADEQUATE: Gets the main ideas right using everyday language. May skip steps, use informal phrasing like "I subtracted them" instead of writing formulas. This is STILL adequate under lenient grading.
- BELOW AVERAGE: Shows they understood the general concept but is vague or incomplete. Does NOT write formulas or show calculations — just states results or describes the process in words.
- MINIMAL: Barely engages. Might just state a conclusion without any reasoning.

DO NOT write formula-heavy responses for Below Average or Minimal tiers. A lenient Below Average response should look like what a struggling student actually writes — short, imprecise, but showing they tried.
`;
  }
  return `CRITICAL — STRICT GRADING MODE (${leniency - 50}% more strict than standard):
The teacher has chosen strict grading. Your example responses MUST reflect how a STRICT teacher would grade. This means:

- EXCELLENT: Flawless — exact formulas with proper notation, every step shown, precise terminology, complete logical chain, thorough contextual analysis.
- ADEQUATE: Complete and correct but may have minor presentation gaps. Still shows all formulas and key steps.
- BELOW AVERAGE: Shows partial knowledge — gets some formulas right but misses key steps or makes errors. Under strict grading this is NOT adequate.
- MINIMAL: Very incomplete — may state a result without showing work, or show only one part of a multi-step problem.
`;
}

export function buildAnchorGenerationPrompt(
  rubric: Rubric,
  anchors: ScoringAnchors,
  leniency: number | null = null,
): string {
  // Unlike the grading prompts, this one states the REAL max score: it is asking for
  // prose, not a score, so nothing here gets descaled on the way back.
  const maxScore = parseFloat(String(rubric.maxScore)) || 10;
  const parts: string[] = [
    'You are a grading calibration assistant.',
    'For the assignment below, write FOUR brief example student responses at different quality levels.',
    'These examples will be shown to an AI grader so it understands what each score level looks like for THIS specific question.\n',
  ];

  const tone = leniencyGuidance(leniency);
  if (tone) parts.push(tone);

  if (rubric.essayPrompt) parts.push(`ASSIGNMENT:\n${rubric.essayPrompt}\n`);

  const rubricLines: string[] = [];
  for (const item of rubric.checklistItems ?? []) {
    if (item.category) rubricLines.push(`[${item.category}]`);
    for (const sub of item.items ?? []) rubricLines.push(`  - ${sub}`);
  }
  for (const item of rubric.rubricItems ?? []) {
    if (item.category) rubricLines.push(`[${item.category}]`);
    for (const sub of item.items ?? []) rubricLines.push(`  - ${sub}`);
  }
  if (rubricLines.length > 0) parts.push(`RUBRIC:\n${rubricLines.join('\n')}\n`);

  if (rubric.modelText) parts.push(`MODEL ANSWER:\n${rubric.modelText}\n`);
  parts.push(`MAX SCORE: ${maxScore}\n`);
  parts.push(
    'Write one realistic example student response per level. Match the length and style a student would actually write for this assignment.\n',
  );
  for (const t of anchorTiers(anchors)) {
    parts.push(`${t.label.toUpperCase()} (${t.score}/${maxScore}):\n[write example]\n`);
  }

  return parts.join('\n').trimEnd();
}

/**
 * Split the reply into its four sections.
 *
 * Headers are matched loosely — label, then the score anywhere before the line ends.
 * O.G.R.E required an exact `Label (score/max):`, so a model that wrote `**EXCELLENT
 * (19/20)**` or dropped the max produced four empty anchors and no error, which reads
 * as "generation worked, examples were blank".
 */
export function parseAnchorResponses(text: string, anchors: ScoringAnchors): AnchorExample[] {
  const tiers = anchorTiers(anchors);
  const clean = (text ?? '').replace(/<think>[\s\S]*?<\/think>/gi, '');

  // Leading run is [^A-Za-z], not [^\w] — underscore is a word character, so `_Below
  // Average:_` would otherwise fail to match its own heading.
  const headerFor = (label: string) =>
    new RegExp(`^[^A-Za-z]*${label.replace(/ /g, '\\s+')}\\b[^\\n]*$`, 'im');

  // Locate every header first, so each section ends where the next one starts.
  const found = tiers.map((t) => {
    const m = clean.match(headerFor(t.label));
    return { ...t, at: m ? clean.indexOf(m[0]) : -1, len: m ? m[0].length : 0 };
  });

  return found.map((t, i) => {
    if (t.at < 0) return { label: t.label, score: t.score, response: '' };
    const start = t.at + t.len;
    const next = found.slice(i + 1).find((n) => n.at > t.at);
    const body = clean.slice(start, next ? next.at : undefined);
    return {
      label: t.label,
      score: t.score,
      // Strip the model's own placeholder if it echoed the template back.
      response: body.replace(/^\s*\[write example\]\s*/i, '').trim(),
    };
  });
}

/** The editable text shown in the panel: one labelled block per tier. */
export function anchorsToText(examples: AnchorExample[]): string {
  return examples
    .filter((a) => a.response)
    .map((a) => `${a.label}: ${a.response}`)
    .join('\n\n');
}

/**
 * Fold the edited anchor text into the rubric as calibration.
 *
 * `extractCustomInstructions` treats everything after the `SCORING CALIBRATION:` header
 * as calibration until a blank line NOT followed by a tier label, and the remainder as
 * instructor overrides — so the anchors must come first and the instructions after.
 */
export function withCalibration(rubric: Rubric, anchorText: string, instructions = ''): Rubric {
  const anchor = anchorText.trim();
  const extra = instructions.trim();
  if (!anchor) return extra ? { ...rubric, customInstructions: extra } : rubric;

  const combined = `SCORING CALIBRATION:\n${anchor}${extra ? `\n\n${extra}` : ''}`;
  return { ...rubric, customInstructions: combined };
}

// Agent-polish pass for a recorded workflow. The CLI only writes PROSE (a good name, a one-line
// description, a numbered human summary); the replayable ```json workflow is always composed
// deterministically by workflow-skill.ts, so replay never depends on the agent getting it right —
// and a failed/garbled polish degrades to the raw workflow, not a broken skill.

import type { Workflow } from './types/site-profile';
import { workflowToSkill } from './workflow-skill';
import { createPageMask } from './page-agent-mask';
import type { PageMask } from './page-agent-mask';

export interface TeachPolish {
  name: string;
  description: string;
  summary: string; // markdown, numbered prose steps
}

/** Every recorded fill/select value becomes a ⟦V n⟧ token — they may be student names, ids or
 *  grades. Deterministic and total, not a prompt instruction the model could ignore. Walks
 *  `workflow.values` (teach-tokens.ts's promoted fixed values) the same way — it is a sibling
 *  data path to `steps[].value`, not a special case, and skipping it here was the leak Finding 1
 *  caught: promotion's own value guard is a second line of defense, not the only one. */
export function tokenizeWorkflowValues(workflow: Workflow): Workflow {
  let n = 0;
  const steps = workflow.steps.map((s) => (s.value === undefined ? s : { ...s, value: `⟦V${++n}⟧` }));
  if (!workflow.values) return { ...workflow, steps };
  const values = Object.fromEntries(Object.keys(workflow.values).map((k) => [k, `⟦V${++n}⟧`]));
  return { ...workflow, steps, values };
}

/**
 * The FERPA trust boundary for this path: the whole serialized workflow, not selected fields.
 *
 * Tokenizing `value` alone was not enough, and the gap was measured on a live recording rather
 * than imagined. A `<select>`'s ACCESSIBLE NAME is its concatenated option list, so recording one
 * choice from a student dropdown captured the entire roster three times over — in the step's
 * `description`, in the `role=combobox[name="…"]` candidate selector, and again in the
 * fingerprint. The value was masked; the names went to the model anyway.
 *
 * So mask the serialized JSON as one body, the same way the page agent masks a whole prompt
 * instead of just the observation. Intent survives: "-- pick a student -- ⟦STU1⟧ ⟦STU2⟧" still
 * says what the control is for, which is all the prose needs.
 *
 * Prompt-only. The stored skill is composed from the ORIGINAL workflow, so replay still has the
 * real selectors and values — masking here would break the thing it is meant to protect.
 *
 * Takes an optional shared `mask` so a narration string masked afterward (buildTeachPolishPrompt)
 * reuses the same token map — a name mentioned in both gets the same ⟦STU n⟧ in both.
 */
export function maskWorkflowForPrompt(workflow: Workflow, startUrl?: string, mask: PageMask = createPageMask()): string {
  const json = JSON.stringify(tokenizeWorkflowValues(workflow), null, 2);
  return mask.text(json, startUrl ?? workflow.trigger ?? '');
}

/** Prompt: summarise the recorded UI trace. Text only, no CDP/shell — a plain one-shot ask.
 *  `narration` is the teacher's own free-text note on what they were doing (Teach's Stop-time
 *  box) — masked as its own whole-body call, same mask instance as the workflow so tokens agree,
 *  never spliced into the JSON (that would corrupt a body downstream code parses). Masked with
 *  `plainNames: true`: narration is prose a human typed, not page text, so the plain-name
 *  false-positive risk `commaOnly` guards against (see page-agent-mask.ts) does not apply. */
export function buildTeachPolishPrompt(workflow: Workflow, startUrl?: string, narration?: string): string {
  const mask = createPageMask();
  const maskedWorkflow = maskWorkflowForPrompt(workflow, startUrl, mask);
  const narrationText = narration?.trim();
  const maskedNarration = narrationText ? mask.text(narrationText, startUrl, { plainNames: true }) : '';
  const narrationLines = maskedNarration
    ? [`The teacher described what they were doing: "${maskedNarration}"`, '']
    : [];

  return [
    'You are turning a recorded browser interaction into a clear, reusable skill description.',
    'Below is a JSON workflow captured from a user clicking through a site' + (startUrl ? ` starting at ${startUrl}` : '') + '.',
    'Each step is a real UI action (click / fill / select) with the element it targeted.',
    'Entered values are redacted tokens like ⟦V1⟧, and any personal name or id is a token like',
    '⟦STU1⟧ — describe those fields generically from their element description (e.g. "enter the',
    'student name"); never invent, echo, or guess what a token stands for.',
    '',
    ...narrationLines,
    'Write a short, human description of what this workflow accomplishes — the INTENT, not a',
    'literal replay.',
    '',
    'WORKFLOW:',
    '```json',
    maskedWorkflow,
    '```',
    '',
    'The "description" you write is how an agent later DECIDES to reach for this skill again — lead',
    'it with WHEN to use this (the trigger situation or task), then what it does. Prefer "Use when…"',
    'phrasing over a flat recap of the steps.',
    '',
    'Output ONLY a JSON object, no code fence, no other text:',
    '{"name": "<short imperative skill name, <=6 words>",',
    ' "description": "<when to use this + what it does, lead with the trigger, <=140 chars>",',
    ' "summary": "<numbered markdown list, one line per meaningful step, in plain language>"}',
  ].join('\n');
}

/**
 * Compose the final SKILL.md: workflow-skill.ts owns the frontmatter + replayable ```json block;
 * the polished numbered summary (if any) is spliced in right after the H1 as prose. Works with a
 * null polish — you still get a valid, replayable skill from the raw recording alone.
 */
export function composeTeachSkill(workflow: Workflow, polish: TeachPolish | null, urlPattern?: string): string {
  const md = workflowToSkill(workflow, { description: polish?.description, urlPattern });
  if (!polish?.summary) return md;
  const lines = md.split('\n');
  const h1 = lines.findIndex((l) => l.startsWith('# '));
  lines.splice(h1 >= 0 ? h1 + 1 : 0, 0, '', polish.summary);
  return lines.join('\n');
}

/** Pull the polish object out of the CLI's final text; null if it isn't parseable. */
export function parseTeachPolish(raw: string): TeachPolish | null {
  const m = raw.match(/\{[\s\S]*\}/);
  if (!m) return null;
  try {
    const o = JSON.parse(m[0]) as Partial<TeachPolish>;
    if (typeof o.name !== 'string' || typeof o.description !== 'string') return null;
    return {
      name: o.name.trim().slice(0, 80),
      description: o.description.replace(/\s+/g, ' ').trim().slice(0, 140),
      summary: typeof o.summary === 'string' ? o.summary.trim() : '',
    };
  } catch {
    return null;
  }
}

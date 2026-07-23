// Agent-polish pass for a recorded workflow. The CLI only writes PROSE (a good name, a one-line
// description, a numbered human summary); the replayable ```json workflow is always composed
// deterministically by workflow-skill.ts, so replay never depends on the agent getting it right —
// and a failed/garbled polish degrades to the raw workflow, not a broken skill.

import type { Workflow } from './types/site-profile';
import { workflowToSkill } from './workflow-skill';

export interface TeachPolish {
  name: string;
  description: string;
  summary: string; // markdown, numbered prose steps
}

/** FERPA trust boundary for this path: the model NEVER sees recorded literal values (they may be
 *  student names/IDs/grades). Every fill/select value becomes a ⟦V n⟧ token; the element's own
 *  label/description carries the intent, which is all prose needs. Tokenization here is
 *  deterministic and total — not a prompt instruction the model could ignore. */
export function tokenizeWorkflowValues(workflow: Workflow): Workflow {
  let n = 0;
  return {
    ...workflow,
    steps: workflow.steps.map((s) => (s.value === undefined ? s : { ...s, value: `⟦V${++n}⟧` })),
  };
}

/** Prompt: summarise the recorded UI trace. Text only, no CDP/shell — a plain one-shot ask. */
export function buildTeachPolishPrompt(workflow: Workflow, startUrl?: string): string {
  return [
    'You are turning a recorded browser interaction into a clear, reusable skill description.',
    'Below is a JSON workflow captured from a user clicking through a site' + (startUrl ? ` starting at ${startUrl}` : '') + '.',
    'Each step is a real UI action (click / fill / select) with the element it targeted.',
    'Entered values are redacted tokens like ⟦V1⟧ — describe those fields generically from their',
    'element description (e.g. "enter the student name"); never invent or echo a value.',
    '',
    'Write a short, human description of what this workflow accomplishes — the INTENT, not a',
    'literal replay.',
    '',
    'WORKFLOW:',
    '```json',
    JSON.stringify(tokenizeWorkflowValues(workflow), null, 2),
    '```',
    '',
    'Output ONLY a JSON object, no code fence, no other text:',
    '{"name": "<short imperative skill name, <=6 words>",',
    ' "description": "<one sentence, what it does & when to run it, <=140 chars>",',
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

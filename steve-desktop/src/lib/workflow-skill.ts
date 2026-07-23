import type { Workflow } from './types/site-profile';

// A trained workflow persists as a SKILL.md: human-readable frontmatter + a JSON
// steps block. This is the format the existing skills system already reads
// (skill-parser.ts), so a captured workflow is a first-class, re-runnable skill.

export interface WorkflowSkillMeta {
  description?: string;
  urlPattern?: string;
}

export function workflowToSkill(workflow: Workflow, meta: WorkflowSkillMeta = {}): string {
  const fm = [
    '---',
    `name: ${workflow.name}`,
    `description: ${meta.description ?? workflow.trigger ?? `Replay the "${workflow.name}" workflow`}`,
    ...(meta.urlPattern ? [`urlPatterns:`, `  - ${meta.urlPattern}`] : []),
    '---',
  ].join('\n');

  const body = [
    `# ${workflow.name}`,
    '',
    workflow.trigger ? `Trigger: ${workflow.trigger}` : '',
    '',
    'Recorded steps (replayed deterministically):',
    '',
    '```json',
    JSON.stringify(workflow, null, 2),
    '```',
    '',
  ].join('\n');

  return `${fm}\n\n${body}`;
}

export function skillToWorkflow(markdown: string): Workflow {
  const block = markdown.match(/```json\s*([\s\S]*?)```/);
  if (!block) {
    throw new Error('Skill markdown has no ```json steps block; refusing to guess a workflow.');
  }
  const parsed = JSON.parse(block[1].trim()) as unknown;
  if (
    typeof parsed !== 'object' ||
    parsed === null ||
    !Array.isArray((parsed as Workflow).steps) ||
    typeof (parsed as Workflow).name !== 'string'
  ) {
    throw new Error('Skill JSON block is not a valid workflow.');
  }
  const workflow = parsed as Workflow;
  // prefer the frontmatter name if the body name was stripped
  // ponytail: regex over gray-matter — gray-matter needs Node Buffer, which doesn't exist in the
  // WebView ("Run failed: Buffer is not defined"). We only ever read our own generated frontmatter.
  const fmName = /^---\r?\n[\s\S]*?\r?\n---/.exec(markdown)?.[0].match(/^name:\s*(.+)$/m)?.[1]?.trim();
  if (fmName) workflow.name = fmName;
  return workflow;
}

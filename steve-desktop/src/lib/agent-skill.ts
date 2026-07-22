// A finished agent run saved as a re-runnable skill. Stored as a SKILL.md: human-readable frontmatter
// + a JSON block holding the task (and, for a planned run, the approved plan). Re-running loads the
// task back into the agent and re-executes it — robust to small site changes, unlike a deterministic
// selector replay. Mirrors workflow-skill.ts's ```json convention so it's a first-class skill.

export interface AgentTask {
  task: string;
  plan?: string;
}

export function taskToSkill(name: string, t: AgentTask, description?: string): string {
  const fm = [
    '---',
    `name: ${name}`,
    `description: ${(description ?? t.task).replace(/\s+/g, ' ').slice(0, 140)}`,
    'tags:',
    '  - agent-task',
    '---',
  ].join('\n');
  const body = [
    `# ${name}`,
    '',
    'Re-runnable agent task — open it in the browser Agent panel with ▶ Run.',
    '',
    '```json',
    JSON.stringify(t, null, 2),
    '```',
    '',
  ].join('\n');
  return `${fm}\n\n${body}`;
}

/** Pull the task (and optional plan) back out of a saved agent-task skill; null if it isn't one. */
export function skillToTask(markdown: string): AgentTask | null {
  const block = markdown.match(/```json\s*([\s\S]*?)```/);
  if (!block) return null;
  try {
    const parsed = JSON.parse(block[1].trim()) as unknown;
    if (parsed && typeof (parsed as AgentTask).task === 'string') {
      const p = parsed as AgentTask;
      return { task: p.task, plan: typeof p.plan === 'string' ? p.plan : undefined };
    }
  } catch {
    /* not a valid agent-task block (e.g. a workflow's {name,steps}) */
  }
  return null;
}

/** Cheap predicate for the Run button — is this skill a re-runnable agent task (not a workflow/guide)? */
export function isAgentTaskSkill(markdown: string): boolean {
  return skillToTask(markdown) !== null;
}

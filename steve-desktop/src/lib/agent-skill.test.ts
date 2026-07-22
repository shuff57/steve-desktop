import { describe, it, expect } from 'vitest';
import { taskToSkill, skillToTask, isAgentTaskSkill } from './agent-skill';

describe('agent-skill', () => {
  const md = taskToSkill('Email the roster', { task: 'email the roster to me', plan: '1. open gradebook\n2. [MUTATES] send' }, 'email the roster to me');

  it('round-trips task + plan through SKILL.md', () => {
    expect(md).toContain('name: Email the roster');
    expect(md).toContain('agent-task');
    const back = skillToTask(md);
    expect(back?.task).toBe('email the roster to me');
    expect(back?.plan).toContain('[MUTATES] send');
  });

  it('handles a plan-less (direct) run', () => {
    const back = skillToTask(taskToSkill('Quick task', { task: 'do the thing' }));
    expect(back).toEqual({ task: 'do the thing', plan: undefined });
  });

  it('detects agent-task skills and rejects workflows / prose', () => {
    expect(isAgentTaskSkill(md)).toBe(true);
    expect(isAgentTaskSkill('# just prose\nno json here')).toBe(false);
    expect(isAgentTaskSkill('```json\n{"name":"wf","steps":[]}\n```')).toBe(false); // a workflow, not a task
  });
});

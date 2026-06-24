import { describe, it, expect } from 'vitest';
import { workflowToSkill, skillToWorkflow } from './workflow-skill';
import type { Workflow } from './types/site-profile';

const workflow: Workflow = {
  name: 'Enter Grades',
  trigger: 'enter grades for a student',
  steps: [
    { action: 'fill', selector: '#studentName', value: 'Jane Doe', description: 'student name field' },
    { action: 'fill', selector: '#grade', value: 'A', description: 'grade field' },
    { action: 'click', selector: '#submit', description: 'submit button' },
  ],
};

describe('workflow <-> skill round-trip', () => {
  it('serialises a workflow to SKILL.md markdown with frontmatter', () => {
    const md = workflowToSkill(workflow, { urlPattern: 'sis.example.edu' });
    expect(md).toContain('---');
    expect(md).toContain('name: Enter Grades');
    expect(md).toContain('sis.example.edu');
    expect(md).toContain('"action": "fill"');
  });

  it('parses the markdown back into an equivalent workflow', () => {
    const md = workflowToSkill(workflow, { urlPattern: 'sis.example.edu' });
    const parsed = skillToWorkflow(md);
    expect(parsed).toEqual(workflow);
  });

  it('survives a write/read cycle through a string store (re-runnable later)', () => {
    const store: Record<string, string> = {};
    store['Enter Grades'] = workflowToSkill(workflow);
    // ...later, fresh load with no training context...
    const reloaded = skillToWorkflow(store['Enter Grades']);
    expect(reloaded.steps).toHaveLength(3);
    expect(reloaded.name).toBe('Enter Grades');
  });

  it('throws on markdown without a steps block rather than guessing', () => {
    expect(() => skillToWorkflow('---\nname: Broken\n---\nNo steps here.')).toThrow();
  });
});

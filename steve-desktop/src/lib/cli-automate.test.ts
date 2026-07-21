import { describe, it, expect } from 'vitest';
import { buildAutomatePlanPrompt, buildAutomateExecPrompt, planHasMutations, cleanAutomateOutput } from './cli-automate';

const base = {
  cdpPort: 9223,
  startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
  task: 'Post an announcement titled Welcome',
  map: '# Site map\nForums at /forums.php',
  scope: { key: 'cid', value: '316341' },
};

describe('buildAutomatePlanPrompt', () => {
  const p = buildAutomatePlanPrompt(base);
  it('is read-only and carries the task + map + scope', () => {
    expect(p).toContain('PLANNING');
    expect(p).toContain('STRICTLY READ-ONLY');
    expect(p).toContain('Post an announcement titled Welcome');
    expect(p).toContain('# Site map');
    expect(p).toContain('cid=316341');
  });
  it('asks for [MUTATES] tagging and a risk line', () => {
    expect(p).toContain('[MUTATES]');
    expect(p).toContain('## Risk');
  });
  it('handles a missing map', () => {
    expect(buildAutomatePlanPrompt({ ...base, map: '' })).toContain('No site map is available yet');
  });
});

describe('buildAutomateExecPrompt', () => {
  const p = buildAutomateExecPrompt({ ...base, approvedPlan: '1. [MUTATES] submit the form' });
  it('embeds the approved plan and bounds the agent to it', () => {
    expect(p).toContain('EXECUTING');
    expect(p).toContain('1. [MUTATES] submit the form');
    expect(p).toContain('ONLY these steps');
    expect(p).toContain('not in the approved plan');
  });
  it('keeps the session + same-origin guard', () => {
    expect(p).toContain('Never log out');
    expect(p).toContain('log[\\s_-]?out'); // DENY_LINK source is inlined
    expect(p).toContain('# Result');
  });
  it('pins the agent to the existing embedded target (no new window)', () => {
    expect(p).toContain('EXISTING');
    expect(p).toContain('Target.createTarget');
  });
  it('pins execution to the marked tab when a marker is given', () => {
    const pm = buildAutomateExecPrompt({ ...base, approvedPlan: '1. submit', marker: 'steve-tab-9' });
    expect(pm).toContain('window.name === "steve-tab-9"');
  });
  it('injects the click-cursor overlay so the user can track clicks', () => {
    expect(p).toContain('SHOW YOUR CLICKS');
    expect(p).toContain('__steveCursorMove');
    expect(p).toContain('addScriptToEvaluateOnNewDocument');
  });
});

describe('planHasMutations', () => {
  it('detects mutating steps for the review warning', () => {
    expect(planHasMutations('1. navigate\n2. [MUTATES] submit')).toBe(true);
    expect(planHasMutations('1. navigate\n2. read the table')).toBe(false);
  });
});

describe('cleanAutomateOutput', () => {
  it('strips a wrapping fence', () => {
    expect(cleanAutomateOutput('```markdown\n# Result\nok\n```')).toBe('# Result\nok');
  });
});

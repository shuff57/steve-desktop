import { describe, it, expect } from 'vitest';
import { buildAutomatePlanPrompt, buildAutomateExecPrompt, planHasMutations, cleanAutomateOutput, parsePlan, buildEnhancePrompt } from './cli-automate';

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
  it('tells the agent to scout visibly — navigate + move the cursor over each target', () => {
    expect(p).toContain('SCOUT IT VISIBLY');
    expect(p).toContain('__steveCursorMove');
    expect(p).toContain('Do NOT plan from memory');
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
  it('tells the agent to drive the cursor via __steveCursorMove (never follows the user)', () => {
    expect(p).toContain('__steveCursorMove');
    expect(p).toContain('never follows the user');
  });
  it('tells the agent to flash before screenshots', () => {
    expect(p).toContain('__steveScreenshotFlash');
  });
  it('attaches files (image or video) in-browser via DOM.setFileInputFiles, not the OS picker', () => {
    expect(p).toContain('DOM.setFileInputFiles');
    expect(p).toContain('.png and .mp4 identically');
  });

  describe('multi-tab exec', () => {
    const m = buildAutomateExecPrompt({ ...base, approvedPlan: '1. open tab B\n2. [MUTATES] submit', multiTab: true });
    it('swaps the single-tab pin for the __steveControl bridge', () => {
      expect(m).toContain('__steveControl.newTab');
      expect(m).toContain('__steveControl.login');
      expect(m).not.toContain('act IN PLACE on the marked target');
      expect(m).not.toContain('Do NOT open a new window or tab');
    });
    it('relaxes global same-origin to per-tab but keeps the no-logout guard', () => {
      expect(m).toContain('Each tab stays on its own site');
      expect(m).not.toContain('Same-origin only (www.myopenmath.com)');
      expect(m).toContain('log[\\s_-]?out'); // DENY_LINK still inlined
    });
    it('still bounds the agent to the approved plan and asks for the audit report', () => {
      expect(m).toContain('ONLY these steps');
      expect(m).toContain('# Result');
      expect(m).toContain('## Changed');
    });
  });

  describe('multi-tab plan (read-only with a login carve-out)', () => {
    const p2 = buildAutomatePlanPrompt({ ...base, multiTab: true });
    it('lets the plan open tabs and log in to reach a second site, nothing else stateful', () => {
      expect(p2).toContain('__steveControl.newTab');
      expect(p2).toContain('only authenticates');
      expect(p2).toContain('Do NOT click, submit, POST'); // read-only rule survives
      expect(p2).not.toContain('Same-origin only: stay on www.myopenmath.com');
    });
  });

  describe('no page open (empty start URL)', () => {
    it('plan tells the agent to open the site itself instead of a fixed START', () => {
      const p = buildAutomatePlanPrompt({ ...base, startUrl: '', multiTab: true });
      expect(p).toContain('No page is open yet');
      expect(p).toContain('__steveControl.newTab');
      expect(p).not.toContain('START at .');
    });
    it('exec drops the "navigate back" when there is nowhere to return to', () => {
      const e = buildAutomateExecPrompt({ ...base, startUrl: '', approvedPlan: '1. do it', multiTab: true });
      expect(e).toContain('When done, output ONLY a markdown result report');
      expect(e).not.toContain('navigate back to ');
    });
  });

  describe('direct run (no approved plan)', () => {
    const d = buildAutomateExecPrompt(base); // "Run now": nothing was reviewed
    it('drops the plan framing and lets the agent work out the steps', () => {
      expect(d).toContain('No plan was written');
      expect(d).not.toContain('APPROVED PLAN');
      expect(d).not.toContain('ONLY these steps');
    });
    it('still bounds mutations to the task and keeps every safety guard', () => {
      expect(d).toContain('beyond what the task plainly asks for');
      expect(d).toContain('Post an announcement titled Welcome');
      expect(d).toContain('Never log out');
      expect(d).toContain('log[\\s_-]?out');
      expect(d).toContain('cid=316341');
      expect(d).toContain('Same-origin only');
    });
    it('still asks for the Changed/Verdict report so the run can be audited', () => {
      expect(d).toContain('# Result');
      expect(d).toContain('## Changed');
      expect(d).toContain('## Verdict');
    });
  });
});

describe('planHasMutations', () => {
  it('detects mutating steps for the review warning', () => {
    expect(planHasMutations('1. navigate\n2. [MUTATES] submit')).toBe(true);
    expect(planHasMutations('1. navigate\n2. read the table')).toBe(false);
  });
});

describe('parsePlan', () => {
  const plan = '# Plan\n1. Navigate to `course.php?cid=316341` — establish the view.\n2. Read the page\n   to find the form.\n6. **[MUTATES]** Fill the name field with `DEMO`.\n## Risk\nCreates one block; nothing existing is edited.';
  const parsed = parsePlan(plan);
  it('extracts numbered steps and folds continuation lines', () => {
    expect(parsed.steps).toHaveLength(3);
    expect(parsed.steps[0]).toEqual({ n: 1, text: 'Navigate to course.php?cid=316341 — establish the view.', mutates: false });
    expect(parsed.steps[1].text).toBe('Read the page to find the form.'); // continuation folded in
  });
  it('flags mutating steps and strips the [MUTATES] marker + markdown', () => {
    expect(parsed.steps[2]).toEqual({ n: 6, text: 'Fill the name field with DEMO.', mutates: true });
  });
  it('captures the Risk block separately', () => {
    expect(parsed.risk).toBe('Creates one block; nothing existing is edited.');
  });
  it('returns no steps for an unnumbered plan', () => {
    expect(parsePlan('just some prose').steps).toHaveLength(0);
  });
});

describe('cleanAutomateOutput', () => {
  it('strips a wrapping fence', () => {
    expect(cleanAutomateOutput('```markdown\n# Result\nok\n```')).toBe('# Result\nok');
  });
});

describe('buildEnhancePrompt', () => {
  const p = buildEnhancePrompt('record a clip and email it to sam@x.com');
  it('embeds the task and the real app capabilities, and asks for steps only', () => {
    expect(p).toContain('record a clip and email it to sam@x.com');
    expect(p).toContain('__steveControl.startRecording');
    expect(p).toContain('__steveScreenshotFlash');
    expect(p).toContain('DOM.setFileInputFiles');
    expect(p).toContain("Keep the user's intent EXACTLY");
    expect(p).toContain('Output ONLY the rewritten task prompt');
  });
});

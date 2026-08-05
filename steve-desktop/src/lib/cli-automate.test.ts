import { describe, it, expect } from 'vitest';
import { buildAutomatePlanPrompt, buildAutomateExecPrompt, planHasMutations, cleanAutomateOutput, parsePlan, buildEnhancePrompt } from './cli-automate';

const base = {
  startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
  task: 'Post an announcement titled Welcome',
  map: true,
  scope: { key: 'cid', value: '316341' },
};

describe('buildAutomatePlanPrompt', () => {
  const p = buildAutomatePlanPrompt(base);
  it('is read-only and carries the task + map + scope', () => {
    expect(p).toContain('PLANNING');
    expect(p).toContain('STRICTLY READ-ONLY');
    expect(p).toContain('Post an announcement titled Welcome');
    expect(p).toContain('SITE MAP — this site has one. Call page_map');
    expect(p).toContain('cid=316341');
  });
  it('serves the map through page_map instead of embedding it', () => {
    // The full map is tens of thousands of tokens; the prompt only points at page_map and the
    // slice is fetched on demand.
    expect(p).toContain('page_map');
    expect(p).not.toContain('Forums at /forums.php');
  });
  it('asks for [MUTATES] tagging and a risk line', () => {
    expect(p).toContain('[MUTATES]');
    expect(p).toContain('## Risk');
  });
  it('handles a missing map', () => {
    expect(buildAutomatePlanPrompt({ ...base, map: false })).toContain('No site map is available yet');
  });
  it('tells the agent to scout visibly rather than plan from memory', () => {
    expect(p).toContain('SCOUT IT VISIBLY');
    expect(p).toContain('page_read');
    expect(p).toContain('Do NOT plan from memory');
  });
  it('hands over no browser port, and forbids finding one', () => {
    // The port is the whole enforcement: an agent that can evaluate JS on the tab reads a
    // gradebook verbatim, and that text never passes through app code, so nothing can mask it.
    expect(p).not.toMatch(/127\.0\.0\.1:\d+/);
    expect(p).not.toContain('webSocketDebuggerUrl');
    expect(p).not.toContain('Runtime.evaluate');
    expect(p).toContain('no playwright/puppeteer');
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
  it('mapDocPath → agent maintains the map: heal verified drift in place, then continue', () => {
    const pm = buildAutomateExecPrompt({ ...base, mapDocPath: 'C:\\repo\\.agents\\site-profiles\\x\\_sitemap-ai.md' });
    expect(pm).toContain('MAPPING MAINTENANCE');
    expect(pm).toContain('_sitemap-ai.md');
    expect(pm).toContain('## Heal log');
    expect(pm).toContain('resume the task');
    expect(pm).toContain('STEVE_MAP_HEAL:'); // transparency marker → activity-log message
    expect(p).not.toContain('MAPPING MAINTENANCE'); // absent without a stored doc
  });

  it('keeps the session + same-origin guard', () => {
    expect(p).toContain('Never log out');
    expect(p).toContain('log[\\s_-]?out'); // DENY_LINK source is inlined
    expect(p).toContain('# Result');
  });
  it('drives the browser only through the page tools — no port, no browser of its own', () => {
    expect(p).not.toMatch(/127\.0\.0\.1:\d+/);
    expect(p).not.toContain('webSocketDebuggerUrl');
    expect(p).not.toContain('Runtime.evaluate');
    expect(p).toContain('page_read');
    expect(p).toContain('no playwright/puppeteer');
  });
  it('says what the tokens are, so the agent passes them back instead of guessing', () => {
    // The failure this prevents is the agent "helpfully" substituting a real-looking name for
    // ⟦STU4⟧, which then matches nothing on the page.
    expect(p).toContain('⟦STU4⟧');
    expect(p).toContain('VERBATIM');
  });
  it('attaches files (image or video) onto the page input, not the OS picker', () => {
    expect(p).toContain('page_attach_file');
    expect(p).toContain('no OS file picker');
  });

  describe('multi-tab exec', () => {
    const m = buildAutomateExecPrompt({ ...base, approvedPlan: '1. open tab B\n2. [MUTATES] submit', multiTab: true });
    it('offers the tab tool, and only when the run may span sites', () => {
      expect(m).toContain('page_tabs');
      expect(m).toContain('never type a password');
      expect(buildAutomateExecPrompt({ ...base, approvedPlan: '1. go' })).not.toContain('page_tabs');
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
    it('never hands the agent a session id to pass around', () => {
      // It used to be an argument the agent supplied on every bridge call — so an agent that could
      // name its own session could name someone else's and drive their tab. The app asserts
      // ownership now, which means the id has no business being in the prompt at all.
      expect(m).not.toMatch(/SESSION ID|sessionId/i);
    });
  });

  describe('multi-tab plan (read-only with a login carve-out)', () => {
    const p2 = buildAutomatePlanPrompt({ ...base, multiTab: true });
    it('lets the plan open tabs and log in to reach a second site, nothing else stateful', () => {
      expect(p2).toContain('page_tabs open/activate/login');
      expect(p2).toContain('only authenticates');
      expect(p2).toContain('No page_click, no page_type'); // read-only rule survives
      expect(p2).not.toContain('Same-origin only: stay on www.myopenmath.com');
    });
  });

  describe('no page open (empty start URL)', () => {
    it('plan tells the agent to open the site itself instead of a fixed START', () => {
      const p = buildAutomatePlanPrompt({ ...base, startUrl: '', multiTab: true });
      expect(p).toContain('No page is open yet');
      expect(p).toContain('page_tabs open');
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

  it('strips technical noise (code calls, arrows, Why clauses, scouting asides)', () => {
    const noisy = '# Plan\n1. Navigate — __steveControl.newTab("https://mail.google.com") → returns tab id. Why: sanctioned way to open a site. *(Already done during scouting — tab 84fcc302 exists; reuse via __steveControl.activate.)*';
    const t = parsePlan(noisy).steps[0].text;
    expect(t).not.toMatch(/__steveControl|Why:|→|84fcc302|newTab/);
    expect(t).toBe('Navigate');
  });

  it('keeps only the first sentence and drops parenthetical padding', () => {
    const wordy = '# Plan\n1. Bring the Gmail tab to the front. (Scouting found it signed in as x@y.com with 1,431 unread.)\n2. Confirm the inbox is visible. No sign-in is needed — the account is already logged in. Nothing is changed.';
    const steps = parsePlan(wordy).steps;
    expect(steps[0].text).toBe('Bring the Gmail tab to the front.');
    expect(steps[1].text).toBe('Confirm the inbox is visible.');
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
    // Capabilities described in plain words, not API names: the rewritten task is read by a
    // human and then by an agent whose tools are named something else entirely.
    expect(p).toContain('record the tab to a video');
    expect(p).toContain('never the OS file picker');
    expect(p).not.toMatch(/__steve|DOM\.setFileInputFiles/);
    expect(p).toContain("Keep the user's intent EXACTLY");
    expect(p).toContain('Output ONLY the rewritten task prompt');
  });
});

describe('the return-home instruction', () => {
  it('never tells the agent to reload the page it is already on', () => {
    // Measured live: an unconditional "navigate back to <startUrl>" made the agent reload the page
    // it had just acted on, clearing the selection the task had made. The run then reported an end
    // state its own last action had undone.
    const p = buildAutomateExecPrompt({ ...base, approvedPlan: '1. pick a student' });
    expect(p).toContain('do NOT');
    expect(p).toContain('reloading can undo what you just did');
    expect(p).not.toMatch(/When done, navigate back to/);
  });
});

describe('the map gets smarter through use', () => {
  const docPath = 'C:/repo/.agents/site-profiles/x/_sitemap-ai.md';

  it('with no map yet, the agent starts one as a side effect of the task', () => {
    // "Run now" deliberately does not stall behind a crawl. Mapping is not a chore you invoke;
    // the first run writes down what it had to learn anyway.
    const p = buildAutomateExecPrompt({ ...base, map: false, mapDocPath: docPath });
    expect(p).toContain('has no map yet');
    expect(p).toContain('SIDE EFFECT');
    expect(p).toContain('do not let it delay the work');
    expect(p).not.toContain('MAPPING MAINTENANCE'); // nothing to heal yet
  });

  it('with a map, it heals rather than restarting one', () => {
    const p = buildAutomateExecPrompt({ ...base, mapDocPath: docPath });
    expect(p).toContain('MAPPING MAINTENANCE');
    expect(p).not.toContain('has no map yet');
  });

  it('with nowhere to write, it is told to do neither', () => {
    const p = buildAutomateExecPrompt({ ...base, map: false });
    expect(p).not.toContain('has no map yet');
    expect(p).not.toContain('MAPPING MAINTENANCE');
  });
});

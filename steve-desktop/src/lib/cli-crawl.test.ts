import { describe, it, expect } from 'vitest';
import { buildCliCrawlPrompt, cleanMappingDoc, parseCliCrawlOutput, buildCliVerifyPrompt, parseCliVerifyOutput, cdpTargetInstruction, cdpMultiTabInstruction } from './cli-crawl';
import { summarizeVerifyReport } from './verify-summary';

describe('cdpTargetInstruction', () => {
  it('pins by window.name marker when present (unambiguous with duplicate tabs)', () => {
    const s = cdpTargetInstruction('x.edu', 'steve-tab-abc');
    expect(s).toContain('window.name === "steve-tab-abc"');
    expect(s).toContain('Target.createTarget');
    expect(s).not.toContain('url is on x.edu');
  });
  it('falls back to host matching without a marker', () => {
    const s = cdpTargetInstruction('x.edu', undefined);
    expect(s).toContain('EXISTING page target whose url is on x.edu');
    expect(s).toContain('Target.createTarget');
  });
});

describe('cdpMultiTabInstruction', () => {
  const s = cdpMultiTabInstruction('www.safecolleges.com');
  it('hands over the __steveControl bridge (open/login/activate) as the only way to make tabs', () => {
    expect(s).toContain('__steveControl.newTab');
    expect(s).toContain('__steveControl.login');
    expect(s).toContain('__steveControl.activate');
    expect(s).toContain('steve-tab-<id>'); // page targets identified by their marker
  });
  it('still bans self-opened tabs/browsers and keeps creds off the model', () => {
    expect(s).toContain('Never call Target.createTarget');
    expect(s).toContain('never type a password yourself');
  });
  it('tells the agent to carry cross-tab data in its own notes and return to the start host', () => {
    expect(s).toContain('OWN working notes');
    expect(s).toContain('www.safecolleges.com');
  });
});

describe('buildCliCrawlPrompt', () => {
  const p = buildCliCrawlPrompt({
    cdpPort: 9223,
    startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
    scope: { key: 'cid', value: '316341' },
  });

  it('hands over the debug port and host', () => {
    expect(p).toContain('http://127.0.0.1:9223');
    expect(p).toContain('www.myopenmath.com');
  });

  it('carries the exact deny rules the deterministic crawler enforces', () => {
    expect(p).toContain('log[\\s_-]?out'); // DENY_LINK source
    expect(p).toContain('\\/admin\\/'); // ADMIN_PATH source
    expect(p).toContain('(action|act|do|op|cmd|task|mode|delete|remove)='); // ACTION_PARAM
    expect(p).toContain('add(?!ress)'); // MUTATING_VERB tail
  });

  it('pins the course scope and read-only rules', () => {
    expect(p).toContain('cid param is absent or equals 316341');
    expect(p).toContain('READ-ONLY');
    expect(p).toContain('at most 30 pages');
  });

  it('pins the crawl to the existing embedded target (no new window)', () => {
    expect(p).toContain('EXISTING page target');
    expect(p).toContain('Target.createTarget');
  });

  it('threads a marker into the crawl prompt when given', () => {
    const pm = buildCliCrawlPrompt({
      cdpPort: 9223,
      startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
      scope: { key: 'cid', value: '316341' },
      marker: 'steve-tab-xyz',
    });
    expect(pm).toContain('window.name === "steve-tab-xyz"');
  });
});

describe('cleanMappingDoc', () => {
  it('unwraps a fenced doc and passes bare docs through', () => {
    expect(cleanMappingDoc('```markdown\n# Map\nbody\n```')).toBe('# Map\nbody');
    expect(cleanMappingDoc('\n# Map\nbody\n')).toBe('# Map\nbody');
  });
});

describe('parseCliCrawlOutput', () => {
  it('splits doc from the ---PAGES--- json list and dedups by url', () => {
    const raw = '# Site map\nbody text\n---PAGES---\n[{"name":"Home","url":"https://x.edu/a"},{"name":"Dup","url":"https://x.edu/a"},{"name":"Grades","url":"https://x.edu/g"}]';
    const r = parseCliCrawlOutput(raw);
    expect(r.doc).toBe('# Site map\nbody text');
    expect(r.pages).toEqual([
      { name: 'Home', url: 'https://x.edu/a' },
      { name: 'Grades', url: 'https://x.edu/g' },
    ]);
  });
  it('no marker → doc only, empty pages', () => {
    const r = parseCliCrawlOutput('# Just a doc\nno list');
    expect(r.doc).toBe('# Just a doc\nno list');
    expect(r.pages).toEqual([]);
  });
  it('garbled json after marker → doc kept, pages empty', () => {
    const r = parseCliCrawlOutput('# Doc\n---PAGES---\n[not json');
    expect(r.doc).toBe('# Doc');
    expect(r.pages).toEqual([]);
  });
  it('drops entries without a url', () => {
    const r = parseCliCrawlOutput('# D\n---PAGES---\n[{"name":"NoUrl"},{"url":"https://x.edu/ok"}]');
    expect(r.pages).toEqual([{ name: 'https://x.edu/ok', url: 'https://x.edu/ok' }]);
  });
});

describe('parseCliVerifyOutput', () => {
  it('splits report / healed doc / recapture list', () => {
    const raw = [
      '# Verification report',
      '- Home: CONFIRMED',
      '---HEALED-DOC---',
      '# Site map: X',
      'corrected body',
      '---RECAPTURE---',
      '[{"name":"Home","url":"https://x.edu/home?folder=0"}]',
    ].join('\n');
    const r = parseCliVerifyOutput(raw);
    expect(r.report).toBe('# Verification report\n- Home: CONFIRMED');
    expect(r.healedDoc).toBe('# Site map: X\ncorrected body');
    expect(r.recapture).toEqual([{ name: 'Home', url: 'https://x.edu/home?folder=0' }]);
  });

  it('report-only output → no heal, no recapture', () => {
    const r = parseCliVerifyOutput('# Verification report\nall good');
    expect(r.report).toBe('# Verification report\nall good');
    expect(r.healedDoc).toBeNull();
    expect(r.recapture).toEqual([]);
  });

  it('healed doc but empty recapture array', () => {
    const r = parseCliVerifyOutput('# report\n---HEALED-DOC---\n# doc\n---RECAPTURE---\n[]');
    expect(r.report).toBe('# report');
    expect(r.healedDoc).toBe('# doc');
    expect(r.recapture).toEqual([]);
  });
});

describe('buildCliVerifyPrompt', () => {
  const p = buildCliVerifyPrompt({
    cdpPort: 9223,
    startUrl: 'https://x.edu/course?cid=1',
    docPath: 'C:\\repo\\.agents\\site-profiles\\x-edu\\_sitemap-ai.md',
    marker: 'steve-tab-abc',
  });
  it('points at the doc on disk (goal prompt — the doc is never embedded) and keeps read-only rails', () => {
    expect(p).toContain('_sitemap-ai.md');
    expect(p).toContain('Read it yourself');
    expect(p).toContain('READ-ONLY');
    expect(p).toContain('# Verification report');
    expect(p).toContain('---HEALED-DOC---');
  });
  it('carries the same deny rules as the crawl prompt', () => {
    expect(p).toContain('log[\\s_-]?out');
    expect(p).toContain('\\/admin\\/');
  });
  it('onlyUrls → targeted re-map: checks the dirty pages, leaves the rest of the doc alone', () => {
    const t = buildCliVerifyPrompt({
      cdpPort: 9223,
      startUrl: 'https://x.edu/course?cid=1',
      docPath: 'C:\\repo\\.agents\\site-profiles\\x-edu\\_sitemap-ai.md',
      onlyUrls: ['https://x.edu/gb?cid=1', 'https://x.edu/forums?cid=1'],
    });
    expect(t).toContain('TARGETED RE-MAP');
    expect(t).toContain('- https://x.edu/gb?cid=1');
    expect(t).toContain('untouched');
    expect(t).not.toContain('Re-check every page');
    expect(p).not.toContain('TARGETED RE-MAP'); // absent without a dirty list
  });
});

describe('summarizeVerifyReport', () => {
  const report = [
    '# Verification report',
    '## Pages',
    '- CONFIRMED Gradebook: https://x.edu/gb — matches',
    '- `CONFIRMED` Roster: https://x.edu/r',
    '- DISCREPANCY: Calendar url moved to /cal2.php',
    '- DISCREPANCY: Forums — page missing',
    '## Verdict',
    'Accurate enough for automation after the two fixes above.',
    'Could not check the LTI page (requires launch context).',
  ].join('\n');

  it('splits confirmed vs discrepancy bullets and pulls the verdict', () => {
    const s = summarizeVerifyReport(report);
    expect(s.confirmed).toHaveLength(2);
    expect(s.confirmed[0]).toContain('Gradebook');
    expect(s.discrepancies).toEqual([
      'Calendar url moved to /cal2.php',
      'Forums — page missing',
    ]);
    expect(s.verdict).toContain('Accurate enough');
  });

  it('unparseable report → empty summary (UI falls back to rendered markdown)', () => {
    const s = summarizeVerifyReport('The site looks fine to me.');
    expect(s.confirmed).toEqual([]);
    expect(s.discrepancies).toEqual([]);
  });

  it('classifies bullets with the verdict word mid-line (real Gmail report shape)', () => {
    const s = summarizeVerifyReport([
      '## Pages',
      '- **Inbox** `#inbox` — DISCREPANCY: Forums tab is gone; counts drifted.',
      '- **Starred** `#starred` — CONFIRMED: renders, empty state.',
      '- **Sent** `#sent` — CONFIRMED: 468 conversations.',
    ].join('\n'));
    expect(s.discrepancies).toHaveLength(1);
    expect(s.discrepancies[0]).toContain('Forums tab is gone');
    expect(s.confirmed).toHaveLength(2);
  });
});

describe('goal prompts stay under 4000 chars', () => {
  it('crawl prompt (worst case: marker + scope)', () => {
    const p = buildCliCrawlPrompt({
      cdpPort: 9223,
      startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
      scope: { key: 'cid', value: '316341' },
      marker: 'steve-tab-00000000-0000-0000-0000-000000000000',
    });
    expect(p.length).toBeLessThan(4000);
  });
  it('verify prompt (fixed size — doc lives in a file)', () => {
    const p = buildCliVerifyPrompt({
      cdpPort: 9223,
      startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
      docPath: 'C:\\Users\\someone\\repo\\.agents\\site-profiles\\www-myopenmath-com\\_sitemap-ai.md',
      marker: 'steve-tab-00000000-0000-0000-0000-000000000000',
    });
    expect(p.length).toBeLessThan(4000);
  });
  it('targeted verify prompt at the 6-URL cap', () => {
    const p = buildCliVerifyPrompt({
      cdpPort: 9223,
      startUrl: 'https://www.myopenmath.com/course/course.php?cid=316341',
      docPath: 'C:\\Users\\someone\\repo\\.agents\\site-profiles\\www-myopenmath-com\\_sitemap-ai.md',
      marker: 'steve-tab-00000000-0000-0000-0000-000000000000',
      onlyUrls: Array.from({ length: 6 }, (_, i) =>
        `https://www.myopenmath.com/course/gradebook.php?cid=316341&folder=long-folder-name-${i}`),
    });
    expect(p.length).toBeLessThan(4000);
  });
});

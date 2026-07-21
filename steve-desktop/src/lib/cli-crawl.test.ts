import { describe, it, expect } from 'vitest';
import { buildCliCrawlPrompt, cleanMappingDoc, parseCliCrawlOutput, buildCliVerifyPrompt, parseCliVerifyOutput } from './cli-crawl';

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
  it('feeds the doc + page list back and asks for a read-only report', () => {
    const p = buildCliVerifyPrompt({
      cdpPort: 9223,
      startUrl: 'https://x.edu/course?cid=1',
      doc: '# Site map\nGradebook at /g',
      pages: [{ name: 'Gradebook', url: 'https://x.edu/g' }],
    });
    expect(p).toContain('VERIFY it against the');
    expect(p).toContain('- Gradebook: https://x.edu/g');
    expect(p).toContain('# Site map\nGradebook at /g');
    expect(p).toContain('READ-ONLY');
    expect(p).toContain('# Verification report');
  });
});

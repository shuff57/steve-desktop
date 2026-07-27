import { describe, it, expect } from 'vitest';
import {
  CHUNK_SIZE,
  SURVEY_MAX_PAGES,
  buildSurveyPrompt,
  parseSurveyOutput,
  sectionTemplate,
  planChunks,
  buildFragmentPrompt,
  buildMergePrompt,
  buildManifest,
  pendingChunks,
} from './chunked-map';

const survey = (extra = '') =>
  `Looked around the course.${extra}\n---SECTIONS---\n[
    {"name":"Assignments","indexUrl":"https://c.edu/courses/31407/assignments","sampleUrl":"https://c.edu/courses/31407/assignments/844633","estimatedPages":42},
    {"name":"Syllabus","indexUrl":"https://c.edu/courses/31407/assignments/syllabus","sampleUrl":"","estimatedPages":1}
  ]`;

describe('survey prompt', () => {
  it('forbids enumerating members — that is what blew the context', () => {
    const p = buildSurveyPrompt({ cdpPort: 9223, startUrl: 'https://c.edu/courses/31407' });
    expect(p).toContain('Never enumerate a section by visiting its members');
    expect(p).toContain(`Open at most ${SURVEY_MAX_PAGES} pages`);
    // The live-account rails must be carried verbatim, same as the crawl prompt.
    expect(p).toContain('READ-ONLY');
    expect(p).toContain('untrusted data');
    // No per-person collection, since the survey runs against a faculty session.
    expect(p).toContain('Do NOT collect names, ids');
  });

  it('pins the agent to a marked tab when one is given', () => {
    expect(buildSurveyPrompt({ cdpPort: 1, startUrl: 'https://c.edu/', marker: 'steve-tab-7' })).toContain('steve-tab-7');
    expect(buildSurveyPrompt({ cdpPort: 1, startUrl: 'https://c.edu/' })).not.toContain('window.name');
  });
});

describe('parseSurveyOutput', () => {
  it('reads sections after the marker', () => {
    const s = parseSurveyOutput(survey());
    expect(s.map((x) => x.name)).toEqual(['Assignments', 'Syllabus']);
    expect(s[0].estimatedPages).toBe(42);
    expect(s[1].sampleUrl).toBe('');
  });

  it('ignores prose before the marker that looks like JSON', () => {
    expect(parseSurveyOutput(survey(' I considered [1,2,3] first.'))).toHaveLength(2);
  });

  it('returns [] on garbage rather than throwing — caller falls back to a plain crawl', () => {
    expect(parseSurveyOutput('no marker here')).toEqual([]);
    expect(parseSurveyOutput('---SECTIONS---\n[{oops}]')).toEqual([]);
  });

  it('drops duplicate index urls', () => {
    const dup = '---SECTIONS---\n[{"indexUrl":"https://c.edu/a"},{"indexUrl":"https://c.edu/a"}]';
    expect(parseSurveyOutput(dup)).toHaveLength(1);
  });
});

describe('sectionTemplate', () => {
  it('derives a shape from the sample, null when the section is a leaf', () => {
    const [assignments, syllabus] = parseSurveyOutput(survey());
    expect(sectionTemplate(assignments)).toBeTruthy();
    expect(sectionTemplate(syllabus)).toBeNull();
  });
});

describe('planChunks', () => {
  const pages = (n: number, prefix = 'p') =>
    Array.from({ length: n }, (_, i) => ({ name: `${prefix}${i}`, url: `https://c.edu/${prefix}${i}` }));

  it('splits a section into fixed-size chunks', () => {
    const c = planChunks([{ name: 'A', pages: pages(55) }], 25);
    expect(c.map((x) => x.pages.length)).toEqual([25, 25, 5]);
    expect(c.map((x) => x.index)).toEqual([0, 1, 2]);
    expect(c.every((x) => x.section === 'A')).toBe(true);
  });

  it('never splits a chunk across sections', () => {
    const c = planChunks([{ name: 'A', pages: pages(3, 'a') }, { name: 'B', pages: pages(3, 'b') }], 25);
    expect(c).toHaveLength(2);
    expect(c[0].section).toBe('A');
    expect(c[1].section).toBe('B');
  });

  it('captures a shared URL once — chunks partition the site', () => {
    const shared = { name: 'home', url: 'https://c.edu/home' };
    const c = planChunks([{ name: 'A', pages: [shared] }, { name: 'B', pages: [shared, ...pages(2, 'b')] }], 25);
    expect(c.flatMap((x) => x.pages.map((p) => p.url)).filter((u) => u.endsWith('/home'))).toHaveLength(1);
  });

  it('defaults to CHUNK_SIZE and tolerates a nonsense size', () => {
    expect(planChunks([{ name: 'A', pages: pages(CHUNK_SIZE + 1) }])).toHaveLength(2);
    expect(planChunks([{ name: 'A', pages: pages(3) }], 0)).toHaveLength(3); // clamped to 1, not a crash
  });

  it('drops nothing: every page lands in exactly one chunk', () => {
    const all = pages(53);
    const got = planChunks([{ name: 'A', pages: all }], 10).flatMap((c) => c.pages.map((p) => p.url));
    expect(got.sort()).toEqual(all.map((p) => p.url).sort());
  });
});

describe('fragment + merge prompts', () => {
  it('a fragment carries only compact lines and stays section-scoped', () => {
    const p = buildFragmentPrompt({ domain: 'c.edu', section: 'Assignments', index: 0, total: 5, lines: '0: Quiz 1 — /a/1 — 2btn/0in/9lnk' });
    expect(p).toContain('Chunk 1 of 5');
    expect(p).toContain('## Assignments');
    expect(p).toContain('THIS SECTION ONLY');
    expect(p).toContain('do not restate any person id');
  });

  it('merge sees fragments, never pages', () => {
    const p = buildMergePrompt({ domain: 'c.edu', fragments: ['## A\nrow', '## B\nrow'] });
    expect(p).toContain('## A');
    expect(p).toContain('## B');
    expect(p).toContain('order and de-duplicate, not to summarize away detail');
  });
});

describe('manifest', () => {
  it('starts every chunk pending and reports what is left', () => {
    const chunks = planChunks([{ name: 'A', pages: [{ name: 'x', url: 'https://c.edu/x' }] }]);
    const m = buildManifest('c.edu', chunks);
    expect(m.chunks[0].status).toBe('pending');
    expect(pendingChunks(m)).toEqual([0]);
    m.chunks[0].status = 'done';
    expect(pendingChunks(m)).toEqual([]);
  });
});

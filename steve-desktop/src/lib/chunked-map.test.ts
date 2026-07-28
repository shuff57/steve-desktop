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
  tokenizeSecrets,
  peopleSections,
  PEOPLE_SURFACE,
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

describe('people surfaces', () => {
  // A live MyOpenMath survey navigated to gradebook.php 29x and roster pages 26x — the agent read
  // a class list to learn a URL the app already had. It is now barred from opening them, and the
  // app seeds them from the start page's links instead, so automation keeps its access.
  it('bars the agent from opening anything that lists people', () => {
    const p = buildSurveyPrompt({ cdpPort: 1, startUrl: 'https://www.myopenmath.com/course/course.php?cid=1' });
    expect(p).toContain('NEVER open a page that LISTS PEOPLE');
    expect(p).toContain('Do not report them either');
  });

  it('matches the surfaces that cost us, and leaves content pages alone', () => {
    for (const u of ['/course/gradebook.php?cid=1', '/roster.php', '/msgs/msglist.php', '/courses/31407/users/9']) {
      expect(PEOPLE_SURFACE.test(u)).toBe(true);
    }
    for (const u of ['/course/course.php?cid=1', '/course/showcalendar.php', '/forums/forums.php']) {
      expect(PEOPLE_SURFACE.test(u)).toBe(false);
    }
  });

  it('seeds them index-only so the crawler never walks one per student', () => {
    const secs = peopleSections([
      { label: 'Gradebook', href: 'https://m.com/course/gradebook.php?cid=1' },
      { label: 'Roster', href: 'https://m.com/roster.php?cid=1' },
      { label: 'Course Map', href: 'https://m.com/course/coursemap.php?cid=1' },
    ]);
    expect(secs.map((s) => s.name)).toEqual(['Gradebook', 'Roster']);
    // Empty sampleUrl => no template is derived => the section captures as a single page.
    expect(secs.every((s) => s.sampleUrl === '')).toBe(true);
    expect(secs.every((s) => sectionTemplate(s) === null)).toBe(true);
  });

  it('drops duplicate hrefs', () => {
    const dup = [{ href: 'https://m.com/gradebook.php' }, { href: 'https://m.com/gradebook.php' }];
    expect(peopleSections(dup)).toHaveLength(1);
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

describe('tokenizeSecrets', () => {
  // The gate (callModelTree) throws if any redacted value >= 3 chars survives in the outbound
  // text. These are the exact conditions that killed all ten chunks of a live run.
  const gateWouldRefuse = (text: string, secrets: Record<string, string>) =>
    Object.values(secrets).some((v) => v.trim().length >= 3 && text.includes(v));

  it('replaces a secret value that reappears as a page title', () => {
    const secrets = { '⟦D1⟧': 'Prestigio SmartBook' };
    const lines = '0: Prestigio SmartBook — /product/545 — 2btn/0in/9lnk';
    const out = tokenizeSecrets(lines, secrets);
    expect(out).toContain('⟦D1⟧');
    expect(gateWouldRefuse(out, secrets)).toBe(false);
  });

  it('replaces every occurrence, not just the first', () => {
    const out = tokenizeSecrets('Acme — /a — Acme', { '⟦D1⟧': 'Acme' });
    expect(out).toBe('⟦D1⟧ — /a — ⟦D1⟧');
  });

  it('leaves short values alone — the gate ignores them and replacing shreds ordinary words', () => {
    expect(tokenizeSecrets('a laptop at /a', { '⟦D1⟧': 'a' })).toBe('a laptop at /a');
  });

  it('is a no-op when nothing collides', () => {
    const lines = '0: Home — / — 1btn/0in/5lnk';
    expect(tokenizeSecrets(lines, { '⟦D1⟧': 'Nowhere To Be Found' })).toBe(lines);
  });

  it('clears the gate across a whole accumulated map', () => {
    const secrets = { '⟦D1⟧': 'Lenovo V110-15', '⟦D2⟧': 'Dell Inspiron', '⟦STU⟧': '127333' };
    const lines = ['0: Lenovo V110-15 — /p/1', '1: Dell Inspiron — /p/2 — user 127333'].join('\n');
    expect(gateWouldRefuse(lines, secrets)).toBe(true);
    expect(gateWouldRefuse(tokenizeSecrets(lines, secrets), secrets)).toBe(false);
  });
});

describe('outbound prompts clear the redaction gate', () => {
  // callModelTree throws when any redacted value >= 3 chars survives in the outbound text. The
  // live failure was NOT in the page lines: the section name "Home" was itself a captured nav
  // label, so tokenizing only `lines` left the leak in the prompt's heading.
  const leaks = (text: string, secrets: Record<string, string>) =>
    Object.values(secrets).filter((v) => v.trim().length >= 3 && text.includes(v));

  it('tokenizes a secret that appears only in the section name', () => {
    const secrets = { '⟦D1⟧': 'Home' };
    const prompt = buildFragmentPrompt({ domain: 'c.edu', section: 'Home', index: 0, total: 1, lines: '0: x — /x — 0btn/0in/0lnk' });
    expect(leaks(prompt, secrets)).toEqual(['Home']); // the bug, before tokenizing
    expect(leaks(tokenizeSecrets(prompt, secrets), secrets)).toEqual([]);
  });

  it('tokenizes a secret that appears only in the domain', () => {
    const secrets = { '⟦D1⟧': 'c.edu' };
    const prompt = buildFragmentPrompt({ domain: 'c.edu', section: 'S', index: 0, total: 1, lines: '0: x — /x — 0btn/0in/0lnk' });
    expect(leaks(tokenizeSecrets(prompt, secrets), secrets)).toEqual([]);
  });

  it('clears the gate for the merge prompt too', () => {
    const secrets = { '⟦D1⟧': 'Assignments' };
    const prompt = buildMergePrompt({ domain: 'c.edu', fragments: ['## Assignments\nrow'] });
    expect(leaks(prompt, secrets)).toEqual(['Assignments']);
    expect(leaks(tokenizeSecrets(prompt, secrets), secrets)).toEqual([]);
  });
});

describe('saved document is scrubbed, not just the prompts', () => {
  // Observed live on scrapethissite.com: the merge model inferred redacted values from context
  // and wrote them into the document it returned ("⟦D5⟧ → Podocnemididae"). The gate only guards
  // what is SENT, so the doc gets the same dictionary applied before it is persisted.
  it('re-tokenizes a value the model reconstructed into its reply', () => {
    const secrets = { '⟦D5⟧': 'Podocnemididae' };
    const modelReply = '# Site map: x\n\nRecovered ⟦D5⟧ → "Podocnemididae" from context.';
    const scrubbed = tokenizeSecrets(modelReply, secrets);
    expect(scrubbed).not.toContain('Podocnemididae');
    expect(scrubbed).toContain('⟦D5⟧');
  });

  it('leaves a clean document untouched', () => {
    const doc = '# Site map: x\n\n## A\n| Page | URL |\n|---|---|\n| Home | `/` |';
    expect(tokenizeSecrets(doc, { '⟦D1⟧': 'Nothing here' })).toBe(doc);
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

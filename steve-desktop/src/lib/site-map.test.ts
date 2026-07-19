import { describe, it, expect } from 'vitest';
import { isCrawlableLink, normalizeUrl, profileToNode, upsertPage, emptySiteMap, suggestTrim } from './site-map';

describe('suggestTrim — post-crawl cleanup hints', () => {
  const node = (url: string, pageName: string, buttons: number, inputs: number, links: number) => ({
    url, pageName, links: Array.from({ length: links }, (_, i) => ({ label: 'l', href: `/l${i}` })),
    counts: { buttons, inputs, links },
  });
  it('flags structural duplicates (keeping the first) and dead-ends', () => {
    let map = emptySiteMap('x.com', '2026-06-24T00:00:00Z');
    map.pages.push(node('https://x.com/a', 'a', 3, 1, 5)); // template original
    map.pages.push(node('https://x.com/b', 'b', 3, 1, 5)); // dup layout → trim
    map.pages.push(node('https://x.com/help', 'help', 0, 0, 4)); // dead-end → trim
    map.pages.push(node('https://x.com/form', 'form', 2, 4, 9)); // keep
    const sug = suggestTrim(map);
    const urls = sug.map((s) => s.url);
    expect(urls).toContain('https://x.com/b');
    expect(urls).toContain('https://x.com/help');
    expect(urls).not.toContain('https://x.com/a');
    expect(urls).not.toContain('https://x.com/form');
  });
});

describe('normalizeUrl — collapse anchor/popover URLs to the same page', () => {
  it('drops the #fragment but keeps the query', () => {
    expect(normalizeUrl('https://x.com/index.php#modal')).toBe('https://x.com/index.php');
    expect(normalizeUrl('https://x.com/index.php?cid=2#tab')).toBe('https://x.com/index.php?cid=2');
  });
  it('leaves a fragmentless URL unchanged and is idempotent', () => {
    expect(normalizeUrl('https://x.com/a')).toBe('https://x.com/a');
    expect(normalizeUrl(normalizeUrl('https://x.com/a#b'))).toBe('https://x.com/a');
  });
});
import type { SiteProfile } from './types/site-profile';

describe('isCrawlableLink — crawl frontier trust boundary', () => {
  const base = 'https://app.example.com/course/home';
  it('allows same-origin http(s) navigation links', () => {
    expect(isCrawlableLink('/course/lesson?id=2', base)).toBe(true);
    expect(isCrawlableLink('https://app.example.com/grades', base)).toBe(true);
  });
  it('rejects logout / sign-out / destructive links', () => {
    expect(isCrawlableLink('/logout.php', base)).toBe(false);
    expect(isCrawlableLink('/account/sign-out', base)).toBe(false);
    expect(isCrawlableLink('/assess?action=submit', base)).toBe(false);
    expect(isCrawlableLink('/items/5/delete', base)).toBe(false);
  });
  it('rejects role/view switches that would drop a teacher into student preview', () => {
    expect(isCrawlableLink('/course/course.php?cid=1&stuview=on', base)).toBe(false);
    expect(isCrawlableLink('/admin?impersonate=42', base)).toBe(false);
    expect(isCrawlableLink('/u?view_as=student', base)).toBe(false);
    // the way BACK to teacher view stays allowed
    expect(isCrawlableLink('/course/course.php?cid=1&teachview=1', base)).toBe(true);
  });
  it('rejects cross-origin and non-http schemes', () => {
    expect(isCrawlableLink('https://evil.com/x', base)).toBe(false);
    expect(isCrawlableLink('mailto:a@b.com', base)).toBe(false);
    expect(isCrawlableLink('javascript:void(0)', base)).toBe(false);
  });
});

function profile(url: string, links: { text: string; href: string }[]): SiteProfile {
  return {
    url,
    domain: new URL(url).hostname,
    pageName: 'p',
    profiledAt: '2026-06-24T00:00:00Z',
    interactive: {
      buttons: [],
      links: links.map((l) => ({ text: l.text, selector: 'a', href: l.href })),
      inputs: [], selects: [], checkboxes: [], radios: [], forms: [],
    },
    summary: { buttons: 0, links: links.length, inputs: 0, selects: 0, checkboxes: 0, radios: 0, forms: 0, landmarks: 0, headings: 0 },
  };
}

describe('site map accumulation', () => {
  it('upserts by url (re-mapping a page replaces, never duplicates)', () => {
    let map = emptySiteMap('app.example.com', '2026-06-24T00:00:00Z');
    map = upsertPage(map, profileToNode(profile('https://app.example.com/a', [])));
    map = upsertPage(map, profileToNode(profile('https://app.example.com/a', [{ text: 'X', href: '/x' }])));
    expect(map.pages).toHaveLength(1);
    expect(map.pages[0].links).toHaveLength(1);
  });
});

import { describe, it, expect } from 'vitest';
import { isCrawlableLink, profileToNode, upsertPage, emptySiteMap } from './site-map';
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

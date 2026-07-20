import { describe, it, expect } from 'vitest';
import { isCrawlableLink, normalizeUrl, profileToNode, upsertPage, emptySiteMap, suggestTrim, structuralSignature, urlTemplate } from './site-map';

describe('urlTemplate — collapse a URL family so a roster is not crawled per-student', () => {
  it('treats differing ids as one family', () => {
    expect(urlTemplate('https://x.com/student.php?uid=41')).toBe(urlTemplate('https://x.com/student.php?uid=42'));
    expect(urlTemplate('https://x.com/course/12/roster')).toBe(urlTemplate('https://x.com/course/99/roster'));
  });
  it('keeps genuinely different pages apart', () => {
    expect(urlTemplate('https://x.com/student.php?uid=41')).not.toBe(urlTemplate('https://x.com/grades.php?uid=41'));
    // different query KEYS are a different family, even with the same value
    expect(urlTemplate('https://x.com/p?uid=1')).not.toBe(urlTemplate('https://x.com/p?cid=1'));
  });
  it('is order-insensitive on query keys and survives a bad URL', () => {
    expect(urlTemplate('https://x.com/p?b=1&a=2')).toBe(urlTemplate('https://x.com/p?a=9&b=8'));
    expect(urlTemplate('not a url')).toBe('not a url');
  });
});

describe('structuralSignature — same template, different data', () => {
  const page = (name: string, id: number) => ({
    domain: 'x.com', pageName: 'p', url: 'u', profiledAt: '', summary: {},
    interactive: {
      buttons: [{ text: 'Save', selector: `#save-${id}` }],
      inputs: [{ label: 'Grade', selector: `#grade-${id}` }],
      links: [{ text: name, href: `/student.php?uid=${id}` }],
      selects: [], checkboxes: [], radios: [], forms: [],
    },
  }) as never;

  it('matches two student pages that differ only by name and id', () => {
    // Raw names differ and are NOT redacted here — link text must still not split them.
    expect(structuralSignature(page('Doe, Jane', 41))).toBe(structuralSignature(page('Roe, Rick', 42)));
  });
  it('treats redaction tokens as data too', () => {
    expect(structuralSignature(page('⟦D1⟧', 41))).toBe(structuralSignature(page('⟦D7⟧', 42)));
  });
  it('still splits on a differing button label (real template change)', () => {
    const a = page('Doe, Jane', 41) as { interactive: { buttons: { text: string; selector: string }[] } };
    a.interactive.buttons = [{ text: 'Unenroll', selector: '#save-41' }];
    expect(structuralSignature(a as never)).not.toBe(structuralSignature(page('Roe, Rick', 42)));
  });
  it('differs when the page really has a different shape', () => {
    const extra = page('Doe, Jane', 41) as { interactive: { buttons: unknown[] } };
    extra.interactive.buttons = [...extra.interactive.buttons, { text: 'Delete', selector: '#del' }];
    expect(structuralSignature(extra as never)).not.toBe(structuralSignature(page('Roe, Rick', 42)));
  });
});

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
  it('rejects concatenated mutation endpoints a \\b guard missed (real crawl escapes)', () => {
    // Every one of these was actually visited during a live MyOpenMath crawl because
    // /\bremove\b/ does not match "addremoveteachers". Substring matching now catches them.
    expect(isCrawlableLink('/admin/addremoveteachers.php?cid=1', base)).toBe(false);
    expect(isCrawlableLink('/admin/transfercourse.php?cid=1', base)).toBe(false);
    expect(isCrawlableLink('/admin/unhidefromcourselist.php?cid=1', base)).toBe(false);
    expect(isCrawlableLink('/admin/modcourseorder.php?cid=1', base)).toBe(false);
    expect(isCrawlableLink('/course/chgassessments2.php?cid=1', base)).toBe(false);
    expect(isCrawlableLink('/course/copyoneitem.php?cid=1&copyid=0-7-3', base)).toBe(false);
    expect(isCrawlableLink('/course/addblock.php?cid=1&id=0-3', base)).toBe(false);
    expect(isCrawlableLink('/course/enrollfromothercourse.php?cid=1', base)).toBe(false);
    expect(isCrawlableLink('/course/listusers.php?cid=1&chgstuinfo=true&uid=2', base)).toBe(false);
  });

  it('rejects the whole admin surface and GET-triggered actions', () => {
    expect(isCrawlableLink('/admin/teacherauditlog.php', base)).toBe(false);
    expect(isCrawlableLink('/admin/forms.php?action=edit&id=3', base)).toBe(false);
    expect(isCrawlableLink('/course/anything.php?do=wipe', base)).toBe(false);
    expect(isCrawlableLink('/course/anything.php?op=merge', base)).toBe(false);
  });

  it('still allows the read-only pages worth mapping', () => {
    expect(isCrawlableLink('/course/gradebook.php?cid=1&stu=5', base)).toBe(true);
    expect(isCrawlableLink('/course/viewactionlog.php?cid=1&uid=2', base)).toBe(true);
    expect(isCrawlableLink('/course/viewloginlog.php?cid=1&uid=2', base)).toBe(true);
    expect(isCrawlableLink('/course/course.php?cid=1', base)).toBe(true);
    // 'address' must not trip the 'add' verb
    expect(isCrawlableLink('/user/editaddress.php?uid=2', base)).toBe(true);
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

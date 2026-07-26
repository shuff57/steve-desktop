import { describe, it, expect } from 'vitest';
import { isCrawlableLink, normalizeUrl, profileToNode, upsertPage, emptySiteMap, withinPathFence, structuralSignature, urlTemplate, withinScope, isTemplateSaturated, nextFrontierIndex, findSuspectPages, MAX_SAMPLES_PER_TEMPLATE, SAMPLES_PER_TEMPLATE } from './site-map';

describe('isTemplateSaturated — stop re-crawling one page per row of data', () => {
  it('collapses a family once its shape repeats', () => {
    expect(isTemplateSaturated(SAMPLES_PER_TEMPLATE, 1)).toBe(true);
  });
  it('will not collapse on a single sample — one page cannot prove a template', () => {
    expect(isTemplateSaturated(1, 1)).toBe(false);
  });
  it('keeps sampling while the shape is still changing, below the ceiling', () => {
    expect(isTemplateSaturated(SAMPLES_PER_TEMPLATE, 2)).toBe(false);
  });
  it('stops at the hard ceiling even when every sample looks unique', () => {
    // The real case: moddataset.php names each question's variables in its input labels, so
    // no two samples ever match and the signature rule alone never fires. One crawl spent 277
    // of 505 visits on this family. The ceiling is what bounds it.
    expect(isTemplateSaturated(MAX_SAMPLES_PER_TEMPLATE, 99)).toBe(true);
    expect(isTemplateSaturated(MAX_SAMPLES_PER_TEMPLATE - 1, 99)).toBe(false);
  });
});

describe('template saturation under fan-out — where the check has to happen', () => {
  // A listing page enqueues its whole family at once: one gradebook lists 12 students, one
  // course page lists 26 assessments. This models that burst to prove WHY checking only at
  // enqueue time cannot bound it — every link is queued while tplMapped is still 0.
  function runCrawl(links: string[], checkOnDequeue: boolean): number {
    const queue: string[] = [];
    const queued = new Set<string>();
    const mapped = new Map<string, number>();
    const shapes = new Map<string, Set<string>>();
    const saturated = (t: string) => isTemplateSaturated(mapped.get(t) ?? 0, shapes.get(t)?.size ?? 0);

    for (const l of links) {
      // the fan-out: every link discovered from ONE page, before any has been visited
      if (queued.has(l) || saturated(urlTemplate(l))) continue;
      queued.add(l);
      queue.push(l);
    }

    let visits = 0;
    while (queue.length) {
      const target = queue.shift()!;
      if (checkOnDequeue && saturated(urlTemplate(target))) continue;
      visits += 1;
      const t = urlTemplate(target);
      mapped.set(t, (mapped.get(t) ?? 0) + 1);
      // each page's shape differs (per-student data in the labels), so the signature rule
      // never fires and only the hard ceiling can stop this family
      const s = shapes.get(t) ?? new Set<string>();
      s.add(`shape-${visits}`);
      shapes.set(t, s);
    }
    return visits;
  }

  const students = Array.from({ length: 12 }, (_, i) =>
    `https://www.myopenmath.com/course/viewactionlog.php?cid=306621&uid=${1000 + i}`);

  it('enqueue-time checking alone visits every student — the bug', () => {
    expect(runCrawl(students, false)).toBe(12);
  });

  it('re-checking on dequeue caps the family at the ceiling', () => {
    expect(runCrawl(students, true)).toBe(MAX_SAMPLES_PER_TEMPLATE);
  });
});

describe('nextFrontierIndex — template-diversity frontier ordering', () => {
  it('picks a link from an unseen template ahead of one already sampled', () => {
    const rosterA = 'https://x.com/roster.php?uid=1';
    const rosterB = 'https://x.com/roster.php?uid=2';
    const settings = 'https://x.com/settings.php';
    const queue = [rosterA, rosterB, settings];
    const tplMapped = new Map([[urlTemplate(rosterA), 2]]); // roster template already sampled
    expect(queue[nextFrontierIndex(queue, tplMapped)]).toBe(settings);
  });
  it('falls back to FIFO once every queued template already has a sample', () => {
    const a = 'https://x.com/a.php';
    const b = 'https://x.com/b.php';
    const tplMapped = new Map([[urlTemplate(a), 1], [urlTemplate(b), 1]]);
    expect(nextFrontierIndex([a, b], tplMapped)).toBe(0);
  });
  it('returns -1 for an empty queue', () => {
    expect(nextFrontierIndex([], new Map())).toBe(-1);
  });
});

describe('findSuspectPages — AI self-audit candidates', () => {
  const node = (url: string, pageName: string, links: { label: string; href: string }[], counts: { buttons: number; inputs: number; links: number }) =>
    ({ url, pageName, links, counts });

  it('flags a page referenced elsewhere but captured with nothing on it', () => {
    let map = emptySiteMap('x.com', '2026-06-24T00:00:00Z');
    map.pages.push(node('https://x.com/modules', 'modules', [{ label: 'Assignments', href: 'https://x.com/assignments' }], { buttons: 2, inputs: 0, links: 1 }));
    map.pages.push(node('https://x.com/assignments', 'assignments', [], { buttons: 0, inputs: 0, links: 0 })); // suspect: referenced, empty
    const suspects = findSuspectPages(map);
    expect(suspects.map((s) => s.url)).toEqual(['https://x.com/assignments']);
  });

  it('does not flag an unreferenced empty page or a referenced non-empty page', () => {
    let map = emptySiteMap('x.com', '2026-06-24T00:00:00Z');
    map.pages.push(node('https://x.com/modules', 'modules', [{ label: 'Grades', href: 'https://x.com/grades' }], { buttons: 1, inputs: 0, links: 1 }));
    map.pages.push(node('https://x.com/grades', 'grades', [], { buttons: 3, inputs: 0, links: 0 })); // referenced, not empty
    map.pages.push(node('https://x.com/orphan', 'orphan', [], { buttons: 0, inputs: 0, links: 0 })); // empty, unreferenced
    expect(findSuspectPages(map)).toHaveLength(0);
  });
});

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

describe('withinScope — keep the crawl inside one course', () => {
  const start = 'https://www.myopenmath.com/course/course.php?cid=301265';
  it('rejects a link belonging to another course', () => {
    // This is how one course became four: home lists every course, each with its own cid.
    expect(withinScope('https://www.myopenmath.com/course/course.php?cid=301417', start)).toBe(false);
    expect(withinScope('https://www.myopenmath.com/admin/teacherauditlog.php?cid=304240', start)).toBe(false);
  });
  it('allows the same course and shared, scope-less navigation', () => {
    expect(withinScope('https://www.myopenmath.com/course/gradebook.php?cid=301265&stu=5', start)).toBe(true);
    expect(withinScope('https://www.myopenmath.com/index.php', start)).toBe(true); // no cid = shared nav
  });
  it('falls back to a directory fence when the start page has no course id', () => {
    // Used to enforce nothing at all, which let a crawl of one w3.org section escape into the
    // whole domain (4200+ queued and climbing). No scope param now means "stay in this area".
    expect(withinScope('https://x.com/a?cid=9', 'https://x.com/home')).toBe(false);
    expect(withinScope('https://x.com/home/sub?cid=9', 'https://x.com/home')).toBe(true);
  });
});

describe('urlTemplate — different courses must not collapse together', () => {
  it('keeps the scope value so two courses stay distinct', () => {
    // Courses are organised differently; collapsing them would skip a third course's layout.
    expect(urlTemplate('https://x.com/course.php?cid=301265'))
      .not.toBe(urlTemplate('https://x.com/course.php?cid=301417'));
  });
  it('still collapses students WITHIN one course', () => {
    expect(urlTemplate('https://x.com/gradebook.php?cid=301265&stu=41'))
      .toBe(urlTemplate('https://x.com/gradebook.php?cid=301265&stu=42'));
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

describe('withinPathFence — containment when the site has no scope param', () => {
  const start = 'https://www.w3.org/WAI/ARIA/apg/patterns/';
  it('keeps links inside the directory the crawl started in', () => {
    expect(withinPathFence('https://www.w3.org/WAI/ARIA/apg/patterns/toolbar/', start)).toBe(true);
    expect(withinPathFence('/WAI/ARIA/apg/patterns/toolbar/examples/toolbar/', start)).toBe(true);
  });
  it('blocks the rest of the domain — the 4200-link runaway', () => {
    expect(withinPathFence('https://www.w3.org/blog/2026/some-post/', start)).toBe(false);
    expect(withinPathFence('https://www.w3.org/WAI/ARIA/', start)).toBe(false);
    expect(withinPathFence('https://example.com/WAI/ARIA/apg/patterns/', start)).toBe(false);
  });
  it('treats a filename start URL as its directory', () => {
    expect(withinPathFence('https://x.com/app/list.php?p=2', 'https://x.com/app/index.php')).toBe(true);
    expect(withinPathFence('https://x.com/other/', 'https://x.com/app/index.php')).toBe(false);
  });
  it('fences nothing when the crawl starts at the site root', () => {
    expect(withinPathFence('https://x.com/anywhere/deep', 'https://x.com/')).toBe(true);
  });
  it('withinScope falls back to the fence only when there is no scope param', () => {
    const mom = 'https://www.myopenmath.com/course/course.php?cid=316341';
    // scope param present → cross-directory shared nav still allowed, as before
    expect(withinScope('https://www.myopenmath.com/msgs/msglist.php?cid=316341', mom)).toBe(true);
    expect(withinScope('https://www.myopenmath.com/course/course.php?cid=999', mom)).toBe(false);
  });
});

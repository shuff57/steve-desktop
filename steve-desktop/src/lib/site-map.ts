import type { SiteProfile } from './types/site-profile';

// A site map is the per-domain aggregate of page captures: each page plus its outbound nav
// links. Two ways to fill it — accumulate (map each page you visit) or auto-crawl (follow the
// safe links yourself) — both upsert SitePageNodes into the same structure.

export interface SitePageNode {
  url: string;
  pageName: string;
  links: { label: string; href: string }[]; // absolute, deduped outbound links
  counts: { buttons: number; links: number; inputs: number };
  /** AI page digest (structurally = PageCard; inlined to avoid an import cycle with page-card.ts). */
  card?: { pageType: string; purpose: string; keyActions: string[]; automationValue: 'high' | 'medium' | 'low' };
}

export interface SiteMap {
  domain: string;
  startedAt: string;
  pages: SitePageNode[];
}

// Links we must NEVER auto-follow: they end the session, mutate state, or SWITCH ROLE/VIEW.
// Role/view switches (e.g. MyOpenMath's ?stuview=on "Student View") drop a teacher into student
// preview, after which every teacher-only page 403s with "you must be a teacher". Deny-by-default
// on the crawl frontier is the trust boundary — accumulate mode never navigates, so this only
// gates crawl.
// `studentview` did not match Canvas's actual spelling, /courses/N/student_view/1, so a live
// crawl walked into Student View. Canvas then denies everything outside that one course, which
// looked like the whole account had lost authorisation. Separator-tolerant now, same lesson as
// the addremoveteachers escape: match how endpoints are really spelled, not how we'd spell them.
// Entering Student View deliberately (before starting a crawl) still works — only the link is
// gated, and the pages inside carry ordinary course URLs.
export const DENY_LINK =
  /(log[\s_-]?out|sign[\s_-]?out|log[\s_-]?off|logon|sign[\s_-]?in|stuview|student[\s_-]?view|test[\s_-]?student|view[_-]?as|viewas|impersonate|masquerade|loginas|become[_-]?user)/i;

/**
 * Verbs that mark a URL as potentially state-changing. Matched as SUBSTRINGS, deliberately
 * without \b word boundaries: real endpoints concatenate their verbs
 * (addremoveteachers.php, unhidefromcourselist.php, chgstuinfo, modcourseorder.php) and a
 * \bremove\b style guard never matched any of them — that gap let a live-gradebook crawl walk
 * straight into /admin/addremoveteachers.php.
 *
 * This is a read-only mapper against someone's live gradebook, so the asymmetry is stark:
 * over-blocking costs a few unmapped pages, under-blocking can mutate real student data.
 * Deny aggressively. The carve-outs are benign NOUNS that merely contain a verb:
 *
 *  - 'address' (contains "add")
 *  - 'assignment' (contains "assign") — Canvas addresses its core listing as
 *    /courses/N/assignments, and blocking it cost the whole assignment surface: one real
 *    Canvas map had 14 such links, every one refused. The verb form that actually mutates
 *    (/assign, assign_grade.php, ?do=assign, assignrole) still matches, and anything that
 *    pairs the noun with a real verb is still caught by that verb — assignmentdelete.php by
 *    'delete', ?action=… by ACTION_PARAM.
 */
export const MUTATING_VERB =
  /(delete|destroy|remove|discard|submit|drop|unenroll|enroll|transfer|archive|restore|merge|import|reset|revoke|grant|promote|demote|assign(?!ment)|copy|duplicate|unhide|hide|reorder|modcourse|chg|change|add(?!ress))/i;

/** The admin surface is never needed to map a course for automation — and is all mutation. */
export const ADMIN_PATH = /\/admin\//i;

/**
 * Verb-carrying query params. Plenty of PHP apps perform the action on GET when one of
 * these is present (…/forms.php?action=…), so a bare navigation is already a write.
 */
export const ACTION_PARAM = /[?&](action|act|do|op|cmd|task|mode|delete|remove)=/i;

/**
 * Page-serving extensions. Anything else with an extension is a FILE, not a page.
 *
 * Navigating the embedded browser to a .zip/.pdf/.png does not render a page — it downloads
 * the file to the user's real Downloads folder. One the-internet.herokuapp.com run dumped 33
 * files there (and the "(3)" suffixes showed earlier runs had done it too) while spending a
 * page visit on each. Extensionless paths are pages (Canvas, most modern routing).
 *
 * Allow-list, not a deny-list of binary types: a new media format must not silently become
 * crawlable, and the cost of over-blocking is one unmapped page against writing junk to disk.
 */
const PAGE_EXT = /^(html?|php\d?|aspx?|jspx?|s?html|xhtml|cfm|do|action|cgi|pl|py|rb|erb)$/i;

/**
 * A client-side template placeholder that was never filled in.
 *
 * Canvas ships unrendered Handlebars templates in the live DOM, so capture picks up hrefs like
 * /courses/34903/modules/items/{{ id }} and ?files[]={{ content_id }}. They are not URLs — every
 * one costs a page visit and can only 404 or redirect. Matched before and after URL parsing
 * because the braces arrive percent-encoded (%7B%7B) about as often as raw.
 */
const UNRENDERED_TEMPLATE = /\{\{|%7B%7B|\$\{|<%=/i;

/** The file extension of a URL's last path segment, or null when it has none. */
function pathExtension(pathname: string): string | null {
  const last = pathname.split('/').pop() ?? '';
  // Require a leading letter so version-ish segments (/v2.0, /course/1.5) aren't read as files.
  const m = last.match(/\.([a-z][a-z0-9]{0,4})$/i);
  return m ? m[1] : null;
}

/**
 * Strip the data out of a string so two pages of the same template compare equal:
 * redaction tokens (⟦D1⟧) and digit runs both become placeholders.
 */
function dataFree(s: string): string {
  return (s ?? '')
    .replace(/⟦[^⟧]*⟧/g, '*')
    .replace(/\d+/g, '#')
    .trim()
    .toLowerCase();
}

/**
 * A page's shape, ignoring its data. Two roster/student pages built from one template
 * produce the same signature even though every name, id and href differs.
 * Sorted so DOM ordering jitter doesn't split a template into two.
 *
 * Link TEXT is deliberately excluded: on a roster it is the row's data (the student's
 * name) and would make every otherwise-identical page look unique — the exact case this
 * exists to collapse. The href shape carries the structure instead. Button text and input
 * labels ARE kept: those are static UI chrome ("Save", "Grade") that distinguishes a real
 * template change.
 */
export function structuralSignature(p: SiteProfile): string {
  const parts: string[] = [];
  for (const b of p.interactive.buttons) parts.push(`b:${dataFree(b.text)}|${dataFree(b.selector)}`);
  for (const i of p.interactive.inputs) parts.push(`i:${dataFree(i.label)}|${dataFree(i.selector)}|${i.type ?? ''}`);
  for (const l of p.interactive.links) parts.push(`l:${dataFree(l.href ?? '')}`);
  return parts.sort().join('\n');
}

/**
 * Query params that identify WHICH course/section a page belongs to. Used both to keep the
 * crawl inside the course you started in, and to stop two different courses collapsing into
 * one template.
 */
const SCOPE_PARAMS = ['cid', 'courseid', 'course_id', 'classid', 'class_id', 'sectionid', 'section_id'];

/** The course/section this URL belongs to, if the site identifies one. */
export function scopeOf(url: string): { key: string; value: string } | null {
  try {
    const p = new URL(url).searchParams;
    for (const k of SCOPE_PARAMS) {
      const v = p.get(k);
      if (v) return { key: k, value: v };
    }
  } catch {
    /* not a URL */
  }
  return null;
}

/**
 * Is this link inside the same course as the page we started from? A link carrying no scope
 * param is shared navigation (home, help) and stays allowed — it's a DIFFERENT value for the
 * same key that means we've wandered into another course.
 */
export function withinScope(href: string, startUrl: string, fence?: string | null): boolean {
  const s = scopeOf(startUrl);
  if (s) {
    try {
      const v = new URL(href).searchParams.get(s.key);
      return v === null || v === s.value;
    } catch {
      return true;
    }
  }
  if (!fence) return withinPathFence(href, startUrl);
  // `fence` is a path prefix from deriveFence, not a URL — resolve href against the start page
  // and compare paths directly (feeding a bare path to new URL() throws and would allow all).
  try {
    const s = new URL(startUrl);
    const u = new URL(href, startUrl);
    if (u.origin !== s.origin) return false;
    return fence === '/' || (u.pathname + '/').startsWith(fence);
  } catch {
    return true;
  }
}

/** Directory prefix of a URL — `/a/b/page.html` and `/a/b/` both give `/a/b/`. */
function dirOf(pathname: string): string {
  const segs = pathname.split('/').filter(Boolean);
  if (segs.length && segs[segs.length - 1].includes('.')) segs.pop();
  return segs.length ? '/' + segs.join('/') + '/' : '/';
}

/**
 * Choose the crawl fence from EVIDENCE rather than assuming the start URL's own directory.
 *
 * The start page's links say what area it belongs to. Using the start directory alone is wrong
 * whenever you begin on a leaf: from `/courses/34903/assignments` a directory fence excludes
 * `/courses/34903/grades` and `/modules` — the siblings that ARE the course. Widening blindly to
 * the parent is equally wrong: from `/WAI/ARIA/apg/patterns/` the parent re-opens most of w3.org.
 *
 * The signal is SIBLINGS, not a majority. A majority vote over all links widens far too far —
 * tried on an APG pattern page, the W3C/WAI header and footer outvoted the section and the fence
 * became /WAI/, queueing 645 pages instead of 62. Instead:
 *   1. if the start directory already holds several links, the start page is the index OF its
 *      area — fence there (this is /WAI/ARIA/apg/patterns/, which lists every pattern);
 *   2. otherwise the start page is a leaf, so look one level up: if its PARENT holds several
 *      links that aren't under the start directory, those siblings are the area
 *      (/courses/34903/assignments → /courses/34903/, alongside grades and modules);
 *   3. otherwise keep the start directory.
 * Only ever widens by one level, and errs narrow: too narrow is fixed by starting a level up,
 * too wide costs hours of crawling.
 */
const FENCE_EVIDENCE = 3;

export function deriveFence(startUrl: string, links: string[]): string {
  let start: URL;
  try {
    start = new URL(startUrl);
  } catch {
    return startUrl;
  }
  const startDir = dirOf(start.pathname);
  const paths: string[] = [];
  for (const l of links) {
    try {
      const u = new URL(l, startUrl);
      if (u.origin === start.origin && u.pathname !== start.pathname) paths.push(u.pathname + '/');
    } catch {
      /* skip unparseable */
    }
  }
  if (!paths.length) return startDir;

  const under = (dir: string) => paths.filter((p) => p.startsWith(dir)).length;
  if (under(startDir) >= FENCE_EVIDENCE) return startDir; // an index of its own area

  const segs = startDir.split('/').filter(Boolean);
  if (segs.length < 2) return startDir; // parent would be the origin root — never widen to that
  const parent = '/' + segs.slice(0, -1).join('/') + '/';
  const siblings = under(parent) - under(startDir);
  return siblings >= FENCE_EVIDENCE ? parent : startDir;
}

/**
 * Containment for sites with no scope param: stay inside the DIRECTORY the crawl started in.
 *
 * Without this the crawl is bounded only by the 1000-page safety backstop. Starting on
 * w3.org/WAI/ARIA/apg/patterns/ escaped into the whole of w3.org — the frontier grew past 4200
 * queued links and was still climbing, i.e. "Map this site" would have run for hours and mapped
 * a domain instead of the area asked for. Every earlier target hid this: MyOpenMath carries
 * cid=, and the rest were small sites.
 *
 * The fence is the start URL's directory, so /WAI/ARIA/apg/patterns/ also admits
 * /WAI/ARIA/apg/patterns/toolbar/examples/. A start URL at the site root fences nothing, which
 * is the honest reading of "map this whole site".
 */
export function withinPathFence(href: string, startUrl: string): boolean {
  try {
    const start = new URL(startUrl);
    const u = new URL(href, startUrl);
    if (u.origin !== start.origin) return false;
    const dir = dirOf(start.pathname);
    if (dir === '/') return true; // started at the root — the whole origin IS the area
    return (u.pathname + '/').startsWith(dir);
  } catch {
    return true;
  }
}

/**
 * Collapse a URL to the family it belongs to: numeric path segments and query VALUES become
 * placeholders, query keys are kept (sorted). So student.php?uid=41 and student.php?uid=42
 * share a template, while student.php?uid=41 and grades.php?uid=41 do not.
 *
 * The scope param keeps its VALUE: two courses are organised differently, so
 * course.php?cid=301265 and course.php?cid=301417 must NOT collapse together — otherwise
 * sampling two similar courses would skip a third that is laid out differently.
 */
export function urlTemplate(url: string): string {
  try {
    const u = new URL(url);
    // A slug carrying an id is ONE family, not a thousand. Replacing only the digits left
    // /catalogue/a-paris-apartment_612/ and /catalogue/sharp-objects_997/ as distinct templates,
    // so a 1000-book catalogue never saturated: a live crawl was at 208 pages with 438 still
    // queued and barely draining. A segment ending in -123 or _123 collapses whole; other
    // segments keep the digit-level replacement (page-2.html -> page-#.html).
    const path = u.pathname
      .split('/')
      .map((seg) => (/^.+[-_]\d+$/.test(seg) ? '#' : seg.replace(/\d+/g, '#')))
      .join('/');
    const scope = scopeOf(url);
    const keys = [...u.searchParams.keys()]
      .sort()
      .map((k) => (scope && k === scope.key ? `${k}=${scope.value}` : k))
      .join(',');
    return `${u.origin}${path}${keys ? `?${keys}` : ''}`;
  } catch {
    return url;
  }
}

/** Strip the #fragment so anchor/popover links (index.php#x) dedupe to the same page. */
export function normalizeUrl(url: string): string {
  try {
    const u = new URL(url);
    u.hash = '';
    return u.toString();
  } catch {
    return url;
  }
}

/**
 * Did navigation actually land on the page we asked for?
 *
 * The embedded webview's navigate resolves even when the page never moves, so a caller that
 * trusts it captures whatever was already on screen and files it under the URL it requested.
 * One live run stored 82 distinct URLs against a single wedged page and reported zero failures.
 *
 * A landing is: same origin, same path (trailing slash and #fragment ignored), and every query
 * param we asked for still present with its value. That admits a site adding its own params
 * (MyOpenMath appends &r=<session>) without a deny-list, and rejects a redirect to a different
 * path — which is a different page, however legitimate the redirect.
 */
export function landedOn(intended: string, actual: string): boolean {
  let want: URL;
  let got: URL;
  try {
    want = new URL(intended);
    got = new URL(actual);
  } catch {
    return false;
  }
  const path = (u: URL) => u.pathname.replace(/\/+$/, '');
  if (want.origin !== got.origin || path(want) !== path(got)) return false;
  for (const [k, v] of want.searchParams) {
    if (!got.searchParams.getAll(k).includes(v)) return false;
  }
  return true;
}

/** Same-origin http(s) link that isn't a logout/destructive action — safe to auto-navigate to. */
export function isCrawlableLink(href: string, baseUrl: string): boolean {
  let u: URL;
  let base: URL;
  try {
    base = new URL(baseUrl);
    u = new URL(href, baseUrl);
  } catch {
    return false;
  }
  if (u.protocol !== 'http:' && u.protocol !== 'https:') return false;
  if (u.host !== base.host) return false; // same-origin only
  const ext = pathExtension(u.pathname);
  if (ext && !PAGE_EXT.test(ext)) return false; // a file download, not a page
  if (UNRENDERED_TEMPLATE.test(href) || UNRENDERED_TEMPLATE.test(u.href)) return false;
  const target = u.pathname + u.search;
  if (DENY_LINK.test(target)) return false; // session / role-switch
  if (ADMIN_PATH.test(u.pathname)) return false; // admin surface
  if (ACTION_PARAM.test(u.search)) return false; // GET-triggered action
  if (MUTATING_VERB.test(target)) return false; // anything that reads as a write
  return true;
}

/**
 * Does this captured page ask for a password?
 *
 * The one thing every checked-URL guard misses. `landedOn` proves the browser is on the URL we
 * asked for — but MyOpenMath serves its LOGIN PAGE at `course.php?cid=316341` when the session has
 * expired, with no redirect. So the URL matched, the assert passed, and a 14 KB profile of a login
 * form was stored as the course home: `password` input, "Login with Passkey", 17 links of footer.
 * The whole crawl would then have mapped a logged-out site and reported success.
 *
 * A password field is the signal because it cannot appear on a page we have any business storing:
 * either we are logged out, or it is a credential form, and a structural map of one is worth
 * nothing in the first place. Change-password URLs are already unreachable — MUTATING_VERB blocks
 * `chg|change` — so this does not cost the crawl a legitimate page.
 */
export function asksForAPassword(profile: SiteProfile): boolean {
  return (profile.interactive?.inputs ?? []).some((i) => (i.type ?? '').toLowerCase() === 'password');
}

/** Absolute, deduped outbound links from a captured profile (all of them — for the nav graph). */
export function profileLinks(profile: SiteProfile): { label: string; href: string }[] {
  const out: { label: string; href: string }[] = [];
  const seen = new Set<string>();
  for (const l of profile.interactive.links) {
    if (!l.href) continue;
    let abs: string;
    try {
      abs = new URL(l.href, profile.url).toString();
    } catch {
      continue;
    }
    if (seen.has(abs)) continue;
    seen.add(abs);
    out.push({ label: l.text || '(no label)', href: abs });
  }
  return out;
}

export function profileToNode(profile: SiteProfile): SitePageNode {
  return {
    url: profile.url,
    pageName: profile.pageName,
    links: profileLinks(profile),
    counts: { buttons: profile.summary.buttons, links: profile.summary.links, inputs: profile.summary.inputs },
  };
}

export function emptySiteMap(domain: string, startedAt: string): SiteMap {
  return { domain, startedAt, pages: [] };
}

// suggestTrim (similarity-based "likely duplicate" hints) was removed: verify verdicts are the
// only deletion authority now — its count-signature heuristic flagged 55 of 64 genuinely
// distinct pages in a live trial, and a one-click "Trim all" over that ruins mappings.

/**
 * How many pages of one URL family to map before trusting it's a template. Two, not one:
 * a single sample can't tell a template from a coincidence.
 */
export const SAMPLES_PER_TEMPLATE = 2;

/**
 * Hard ceiling per URL family, however unstable its shape looks.
 *
 * The signature rule below only collapses a family once its samples come back byte-identical,
 * and some families never do: MyOpenMath's moddataset.php names each question's variables in
 * its input labels, so every question is "structurally unique" and the family never saturates.
 * One real crawl spent 277 of 505 page visits there — 55% of the crawl re-learning one page.
 *
 * Above this count the shape has been sampled enough to be useful whether or not the samples
 * agree, so stop paying a page visit per row of data.
 */
export const MAX_SAMPLES_PER_TEMPLATE = 3;

/**
 * Has this URL family been sampled enough to stop enqueueing more of it? Either the shape
 * repeated and is therefore known, or we've hit the ceiling and further samples aren't earning
 * their page visit.
 *
 * Skipped pages are never silently dropped — the caller records them per template and the panel
 * shows the count, so a collapsed family is always visible as a decision, not a gap.
 */
export function isTemplateSaturated(mapped: number, distinctShapes: number): boolean {
  if (mapped >= MAX_SAMPLES_PER_TEMPLATE) return true;
  return mapped >= SAMPLES_PER_TEMPLATE && distinctShapes === 1;
}

/**
 * The sibling family a URL belongs to — everything sharing one parent directory.
 *
 * Slug-named siblings (/author/albert-einstein/, /author/bob-marley/) carry no id for
 * urlTemplate to collapse, so each reads as its own template: a live crawl spent 46 page
 * visits on one author index learning a single shape.
 *
 * Top-level pages return null. /about/ and /contact/ share a parent but are not a family,
 * and the-internet.herokuapp.com is 43 genuinely different demo pages hanging off the root.
 * Only a real subdirectory counts as evidence of a collection.
 */
export function siblingFamily(url: string): string | null {
  try {
    const u = new URL(url);
    const segs = new URL(urlTemplate(url)).pathname.split('/').filter(Boolean);
    if (segs.length < 2) return null;
    segs.pop();
    return `${u.origin}/${segs.join('/')}/*`;
  } catch {
    return null;
  }
}

/** Distinct URL templates under one parent before it counts as a collection, not a coincidence. */
export const FAMILY_EVIDENCE = 3;

/**
 * Has a sibling family been sampled enough to stop enqueueing more of it?
 *
 * Stricter than the per-template rule on purpose: it needs FAMILY_EVIDENCE *different*
 * templates that all came back the same shape. Two sibling pages are not a collection, and a
 * family whose URLs already collapse to one template can never trip this — which is what keeps
 * it inert on the sites where per-template saturation is already doing the work.
 */
export function isFamilySaturated(mapped: number, distinctShapes: number, distinctTemplates: number): boolean {
  if (distinctTemplates < FAMILY_EVIDENCE) return false;
  return mapped >= FAMILY_EVIDENCE && distinctShapes === 1;
}

/**
 * Is the loaded site map for a DIFFERENT site than the page currently on screen?
 *
 * The panel loads a map once, on mount, so navigating afterwards leaves the previous site's map
 * in memory. Any destructive action then aims at the wrong site: "Clear all" deleted three
 * whole profile directories during testing — quotes twice, scrapethissite once — because it
 * fired just after a navigation and `siteMap.domain` still named the site left behind.
 *
 * Unknown/unparseable current URL returns false: refusing to clear when we cannot tell where we
 * are would strand the user with a map they cannot delete.
 */
export function mapIsStale(mapDomain: string | null | undefined, currentUrl: string): boolean {
  if (!mapDomain) return false;
  try {
    // about:blank parses but has no hostname — that is "don't know", not "different site".
    const host = new URL(currentUrl).hostname;
    return host ? host !== mapDomain : false;
  } catch {
    return false;
  }
}

/** Fold a page into the map, replacing any existing entry for the same URL. */
export function upsertPage(map: SiteMap, node: SitePageNode): SiteMap {
  const pages = map.pages.filter((p) => p.url !== node.url);
  pages.push(node);
  return { ...map, pages };
}

/**
 * Index of the next URL to dequeue from the crawl frontier, biased toward template diversity:
 * a queued link whose template has never been sampled jumps ahead of plain FIFO order. Without
 * this, one listing page that fans out a single template (a 325-URL question bank, a roster)
 * can drain the entire page budget before the crawl ever reaches a different part of the site —
 * a validated head-to-head crawl spent all 18 pages on ONE template family this way.
 * Falls back to FIFO (index 0) once every template already queued has at least one sample.
 */
export function nextFrontierIndex(queue: string[], tplMapped: Map<string, number>): number {
  if (!queue.length) return -1;
  const idx = queue.findIndex((u) => !(tplMapped.get(urlTemplate(u)) ?? 0));
  return idx === -1 ? 0 : idx;
}

/**
 * Pages other mapped pages link to but which themselves captured with nothing on them — the
 * AI self-audit candidate list. A page referenced by, say, a course's module index yet mapped
 * with zero buttons/inputs/links is more likely a load-timing miss than a genuinely blank page,
 * and is worth one re-capture. "Referenced" is an exact URL match against other pages' outbound
 * links, not a template match — it's the specific reference that is suspicious, not the shape.
 */
export function findSuspectPages(map: SiteMap): SitePageNode[] {
  const referenced = new Set<string>();
  for (const p of map.pages) {
    for (const l of p.links) {
      try {
        referenced.add(normalizeUrl(new URL(l.href, p.url).toString()));
      } catch {
        /* not a URL — skip */
      }
    }
  }
  return map.pages.filter(
    (p) => referenced.has(p.url) && p.counts.buttons === 0 && p.counts.inputs === 0 && p.links.length === 0,
  );
}

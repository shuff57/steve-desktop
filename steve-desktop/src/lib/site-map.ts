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
export const DENY_LINK =
  /(log[\s_-]?out|sign[\s_-]?out|log[\s_-]?off|logon|sign[\s_-]?in|stuview|studentview|view[_-]?as|viewas|impersonate|masquerade|loginas|become[_-]?user)/i;

/**
 * Verbs that mark a URL as potentially state-changing. Matched as SUBSTRINGS, deliberately
 * without \b word boundaries: real endpoints concatenate their verbs
 * (addremoveteachers.php, unhidefromcourselist.php, chgstuinfo, modcourseorder.php) and a
 * \bremove\b style guard never matched any of them — that gap let a live-gradebook crawl walk
 * straight into /admin/addremoveteachers.php.
 *
 * This is a read-only mapper against someone's live gradebook, so the asymmetry is stark:
 * over-blocking costs a few unmapped pages, under-blocking can mutate real student data.
 * Deny aggressively. 'address' is the one carve-out, since it is a common benign noun.
 */
export const MUTATING_VERB =
  /(delete|destroy|remove|discard|submit|drop|unenroll|enroll|transfer|archive|restore|merge|import|reset|revoke|grant|promote|demote|assign|copy|duplicate|unhide|hide|reorder|modcourse|chg|change|add(?!ress))/i;

/** The admin surface is never needed to map a course for automation — and is all mutation. */
export const ADMIN_PATH = /\/admin\//i;

/**
 * Verb-carrying query params. Plenty of PHP apps perform the action on GET when one of
 * these is present (…/forms.php?action=…), so a bare navigation is already a write.
 */
export const ACTION_PARAM = /[?&](action|act|do|op|cmd|task|mode|delete|remove)=/i;

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
export function withinScope(href: string, startUrl: string): boolean {
  const s = scopeOf(startUrl);
  if (s) {
    try {
      const v = new URL(href).searchParams.get(s.key);
      return v === null || v === s.value;
    } catch {
      return true;
    }
  }
  return withinPathFence(href, startUrl);
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
    // Directory of the start URL: drop a trailing filename (anything with a dot in the last segment).
    const segs = start.pathname.split('/').filter(Boolean);
    if (segs.length && segs[segs.length - 1].includes('.')) segs.pop();
    if (!segs.length) return true; // started at the root — the whole origin IS the area
    const dir = '/' + segs.join('/') + '/';
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
    const path = u.pathname.replace(/\d+/g, '#');
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
  const target = u.pathname + u.search;
  if (DENY_LINK.test(target)) return false; // session / role-switch
  if (ADMIN_PATH.test(u.pathname)) return false; // admin surface
  if (ACTION_PARAM.test(u.search)) return false; // GET-triggered action
  if (MUTATING_VERB.test(target)) return false; // anything that reads as a write
  return true;
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

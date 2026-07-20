import type { SiteProfile } from './types/site-profile';

// A site map is the per-domain aggregate of page captures: each page plus its outbound nav
// links. Two ways to fill it — accumulate (map each page you visit) or auto-crawl (follow the
// safe links yourself) — both upsert SitePageNodes into the same structure.

export interface SitePageNode {
  url: string;
  pageName: string;
  links: { label: string; href: string }[]; // absolute, deduped outbound links
  counts: { buttons: number; links: number; inputs: number };
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
const DENY_LINK =
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
const MUTATING_VERB =
  /(delete|destroy|remove|discard|submit|drop|unenroll|enroll|transfer|archive|restore|merge|import|reset|revoke|grant|promote|demote|assign|copy|duplicate|unhide|hide|reorder|modcourse|chg|change|add(?!ress))/i;

/** The admin surface is never needed to map a course for automation — and is all mutation. */
const ADMIN_PATH = /\/admin\//i;

/**
 * Verb-carrying query params. Plenty of PHP apps perform the action on GET when one of
 * these is present (…/forms.php?action=…), so a bare navigation is already a write.
 */
const ACTION_PARAM = /[?&](action|act|do|op|cmd|task|mode|delete|remove)=/i;

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
 * Collapse a URL to the family it belongs to: numeric path segments and query VALUES are
 * placeholders, query keys are kept (sorted). So student.php?uid=41 and student.php?uid=42
 * share a template, while student.php?uid=41 and grades.php?uid=41 do not.
 */
export function urlTemplate(url: string): string {
  try {
    const u = new URL(url);
    const path = u.pathname.replace(/\d+/g, '#');
    const keys = [...u.searchParams.keys()].sort().join(',');
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

export interface TrimSuggestion {
  url: string;
  pageName: string;
  reason: string;
}

/**
 * Post-crawl cleanup hints (instead of a hard page cap): flag pages that are likely noise —
 * structural duplicates (same button/input/link signature → probably one template) and
 * dead-ends (no buttons or inputs → nothing to automate). The first of each duplicate group
 * is kept; the rest are suggested for trimming.
 */
export function suggestTrim(map: SiteMap): TrimSuggestion[] {
  const out: TrimSuggestion[] = [];
  const flagged = new Set<string>();
  const sig = (p: SitePageNode) => `${p.counts.buttons}|${p.counts.inputs}|${p.links.length}`;

  const groups = new Map<string, SitePageNode[]>();
  for (const p of map.pages) {
    const g = groups.get(sig(p));
    if (g) g.push(p); else groups.set(sig(p), [p]);
  }
  for (const group of groups.values()) {
    if (group.length < 2) continue;
    for (const p of group.slice(1)) {
      out.push({ url: p.url, pageName: p.pageName, reason: `same layout as "${group[0].pageName}" — likely duplicate` });
      flagged.add(p.url);
    }
  }
  for (const p of map.pages) {
    if (flagged.has(p.url)) continue;
    if (p.counts.buttons === 0 && p.counts.inputs === 0) {
      out.push({ url: p.url, pageName: p.pageName, reason: 'no buttons or inputs — nothing to automate' });
    }
  }
  return out;
}

/** Fold a page into the map, replacing any existing entry for the same URL. */
export function upsertPage(map: SiteMap, node: SitePageNode): SiteMap {
  const pages = map.pages.filter((p) => p.url !== node.url);
  pages.push(node);
  return { ...map, pages };
}

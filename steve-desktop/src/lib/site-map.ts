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
  /(log[\s_-]?out|sign[\s_-]?out|log[\s_-]?off|logon|sign[\s_-]?in|\bdelete\b|\bremove\b|\bsubmit\b|\bdestroy\b|stuview|studentview|view[_-]?as|viewas|impersonate|masquerade|loginas|become[_-]?user)/i;

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
  if (DENY_LINK.test(u.pathname + u.search)) return false;
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

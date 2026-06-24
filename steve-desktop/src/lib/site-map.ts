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

// Links we must NEVER auto-follow: they end the session or mutate state. Deny-by-default on the
// crawl frontier is the trust boundary — accumulate mode never navigates, so this only gates crawl.
const DENY_LINK = /(log[\s_-]?out|sign[\s_-]?out|log[\s_-]?off|logon|sign[\s_-]?in|\bdelete\b|\bremove\b|\bsubmit\b|\bdestroy\b)/i;

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

/** Fold a page into the map, replacing any existing entry for the same URL. */
export function upsertPage(map: SiteMap, node: SitePageNode): SiteMap {
  const pages = map.pages.filter((p) => p.url !== node.url);
  pages.push(node);
  return { ...map, pages };
}

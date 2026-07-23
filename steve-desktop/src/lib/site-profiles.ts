import { invoke } from '@tauri-apps/api/core';
import { SITE_PROFILES_DIR } from './constants';
import type { SiteProfile } from './types/site-profile';
import { isSiteProfile } from './types/site-profile';
import type { SiteMap } from './site-map';
import { domainToPath, slugify } from './utils/index';

export interface StoredProfileInfo {
  domain: string;
  pageName: string;
  path: string;
}

function pathToDomain(domainPath: string): string {
  return domainPath.replace(/-/g, '.');
}

function pageNameFromPath(path: string): string {
  const fileName = path.split('/').pop() ?? '';
  return fileName.replace(/\.json$/i, '');
}

function isNotFoundError(error: unknown): boolean {
  return error instanceof Error && /not found/i.test(error.message);
}

export function getProfilePath(domain: string, pageName: string): string {
  // slugify can legitimately return '' (missing or punctuation-only page name); fall back
  // so we never write a dotfile like "/.json" that later reads back as a different page.
  return `${SITE_PROFILES_DIR}/${domainToPath(domain)}/${slugify(pageName) || 'page'}.json`;
}

export function findProfileByUrl(profiles: StoredProfileInfo[], url: string): string | null {
  try {
    const hostname = new URL(url).hostname;
    const match = profiles.find((profile) => profile.domain === hostname);
    return match?.path ?? null;
  } catch {
    return null;
  }
}

export async function saveProfile(profile: SiteProfile): Promise<string> {
  const path = getProfilePath(profile.domain, profile.pageName);
  const directory = path.split('/').slice(0, -1).join('/');
  await invoke('create_dir', { path: directory, recursive: true });
  await invoke('write_file', { path, contents: JSON.stringify(profile, null, 2) });
  return path;
}

export async function loadProfile(domain: string, pageName: string): Promise<SiteProfile | null> {
  try {
    const contents = await invoke<string>('read_file', { path: getProfilePath(domain, pageName) });
    const parsed: unknown = JSON.parse(contents);
    return isSiteProfile(parsed) ? parsed : null;
  } catch (error) {
    if (isNotFoundError(error)) {
      return null;
    }
    return null;
  }
}

export async function listProfiles(): Promise<StoredProfileInfo[]> {
  const paths = await invoke<string[]>('list_files', { path: SITE_PROFILES_DIR, recursive: true });
  const profiles = await Promise.all(
    paths
      // _-prefixed files (_sitemap.json) are bookkeeping, not page profiles — counting them
      // made the card say "24 pages" while re-map correctly walked 23.
      .filter((path) => path.endsWith('.json') && !(path.split('/').pop() ?? '').startsWith('_'))
      .map(async (path) => {
        const contents = await invoke<string>('read_file', { path });
        const parsed: unknown = JSON.parse(contents);
        if (isSiteProfile(parsed)) {
          return {
            domain: parsed.domain,
            pageName: parsed.pageName,
            path,
          } satisfies StoredProfileInfo;
        }

        const relative = path.replace(`${SITE_PROFILES_DIR}/`, '');
        const [domainPath] = relative.split('/');
        return {
          domain: pathToDomain(domainPath ?? ''),
          pageName: pageNameFromPath(path),
          path,
        } satisfies StoredProfileInfo;
      }),
  );

  return profiles;
}

export async function deleteProfile(domain: string, pageName: string): Promise<void> {
  await invoke('delete_file', { path: getProfilePath(domain, pageName) });
}

// Per-domain site map (accumulate / auto-crawl) lives alongside the page profiles.
export function getSiteMapPath(domain: string): string {
  return `${SITE_PROFILES_DIR}/${domainToPath(domain)}/_sitemap.json`;
}

export async function saveSiteMap(map: SiteMap): Promise<string> {
  const path = getSiteMapPath(map.domain);
  await invoke('create_dir', { path: path.split('/').slice(0, -1).join('/'), recursive: true });
  await invoke('write_file', { path, contents: JSON.stringify(map, null, 2) });
  return path;
}

// Model-authored mapping document from the Claude-driven crawl, beside the JSON map.
export function getMappingDocPath(domain: string): string {
  return `${SITE_PROFILES_DIR}/${domainToPath(domain)}/_sitemap-ai.md`;
}

export async function saveMappingDoc(domain: string, contents: string): Promise<string> {
  const path = getMappingDocPath(domain);
  await invoke('create_dir', { path: path.split('/').slice(0, -1).join('/'), recursive: true });
  await invoke('write_file', { path, contents });
  return path;
}

// Agent verification report, beside the mapping doc.
export function getVerifyReportPath(domain: string): string {
  return `${SITE_PROFILES_DIR}/${domainToPath(domain)}/_sitemap-verify.md`;
}

export async function loadMappingDoc(domain: string): Promise<string | null> {
  try {
    const contents = await invoke<string>('read_file', { path: getMappingDocPath(domain) });
    return contents?.trim() ? contents : null;
  } catch {
    return null;
  }
}

export async function saveVerifyReport(domain: string, contents: string): Promise<string> {
  const path = getVerifyReportPath(domain);
  await invoke('create_dir', { path: path.split('/').slice(0, -1).join('/'), recursive: true });
  await invoke('write_file', { path, contents });
  return path;
}

/**
 * Overwrite the mapping doc with a corrected version, keeping the prior one as `_sitemap-ai.prev.md`
 * so a heal is reversible. Returns the healed doc's path.
 */
export async function healMappingDoc(domain: string, corrected: string): Promise<string> {
  const path = getMappingDocPath(domain);
  try {
    const prior = await invoke<string>('read_file', { path });
    if (prior?.trim()) {
      await invoke('write_file', { path: path.replace(/\.md$/, '.prev.md'), contents: prior });
    }
  } catch {
    /* no prior doc — nothing to back up */
  }
  await invoke('write_file', { path, contents: corrected });
  return path;
}

// ── Dirty pages ────────────────────────────────────────────────────────────
// A page is marked dirty when a heal fires on it during replay. Dirty pages get a targeted
// single-page re-map on the next Update — never a full-site crawl.

export function getDirtyPath(domain: string): string {
  return `${SITE_PROFILES_DIR}/${domainToPath(domain)}/_dirty.json`;
}

export async function markPageDirty(domain: string, url: string): Promise<void> {
  const path = getDirtyPath(domain);
  let pages: Record<string, string> = {};
  try {
    pages = JSON.parse(await invoke<string>('read_file', { path })) as Record<string, string>;
  } catch {
    /* first mark for this domain */
  }
  pages[url] = new Date().toISOString();
  await invoke('create_dir', { path: path.split('/').slice(0, -1).join('/'), recursive: true });
  await invoke('write_file', { path, contents: JSON.stringify(pages, null, 2) });
}

export async function getDirtyPages(domain: string): Promise<string[]> {
  try {
    return Object.keys(JSON.parse(await invoke<string>('read_file', { path: getDirtyPath(domain) })) as Record<string, string>);
  } catch {
    return [];
  }
}

export async function clearDirtyPages(domain: string): Promise<void> {
  try {
    await invoke('delete_file', { path: getDirtyPath(domain) });
  } catch {
    /* already clean */
  }
}

// ── Drift telemetry ────────────────────────────────────────────────────────
// Heal pressure per page, accumulated from ordinary replays. A page that starts leaning on the
// later heal tiers is drifting and flags itself for re-map — no verification pass needed.

export function getDriftPath(domain: string): string {
  return `${SITE_PROFILES_DIR}/${domainToPath(domain)}/_drift.json`;
}

type DriftFile = Record<string, { steps: number; tiers: Record<string, number> }>;

async function readDrift(domain: string): Promise<DriftFile> {
  try {
    return JSON.parse(await invoke<string>('read_file', { path: getDriftPath(domain) })) as DriftFile;
  } catch {
    return {};
  }
}

/** Fold one replay's tier usage into the page's running stats and return them. */
export async function recordPageDrift(
  domain: string,
  url: string,
  results: { tier?: string }[],
): Promise<{ steps: number; tiers: Record<string, number> }> {
  const all = await readDrift(domain);
  const prior = all[url] ?? { steps: 0, tiers: {} };
  const tiers = { ...prior.tiers };
  for (const r of results) if (r.tier) tiers[r.tier] = (tiers[r.tier] ?? 0) + 1;
  const next = { steps: prior.steps + results.length, tiers };
  all[url] = next;
  const path = getDriftPath(domain);
  await invoke('create_dir', { path: path.split('/').slice(0, -1).join('/'), recursive: true });
  await invoke('write_file', { path, contents: JSON.stringify(all, null, 2) });
  return next;
}

/** Narrow an Update run's page list to the dirty ones. Same origin+path+query counts as the
 *  same page (hash and trailing slash don't). Dirty URLs matching nothing → full list, so a
 *  stale dirty file can never silently skip a re-map. */
export function filterToDirty<T extends { url: string }>(pages: T[], dirty: string[]): T[] {
  if (!dirty.length) return pages;
  const key = (u: string) => {
    try {
      const x = new URL(u);
      return x.origin + x.pathname.replace(/\/$/, '') + x.search;
    } catch {
      return u;
    }
  };
  const dirtyKeys = new Set(dirty.map(key));
  const hit = pages.filter((p) => dirtyKeys.has(key(p.url)));
  return hit.length ? hit : pages;
}

export async function loadSiteMap(domain: string): Promise<SiteMap | null> {
  try {
    const contents = await invoke<string>('read_file', { path: getSiteMapPath(domain) });
    return JSON.parse(contents) as SiteMap;
  } catch {
    return null;
  }
}

export async function deleteSiteMap(domain: string): Promise<void> {
  try {
    await invoke('delete_file', { path: getSiteMapPath(domain) });
  } catch {
    /* already gone */
  }
}

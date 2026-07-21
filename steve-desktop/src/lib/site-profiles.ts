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
      .filter((path) => path.endsWith('.json'))
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

import { fetch as tauriFetch } from '@tauri-apps/plugin-http';

import {
  deleteSkill as deleteDbSkill,
  getSkill as getDbSkill,
  getSkills as getDbSkills,
  saveSkill as saveDbSkill,
  updateSkillActive as updateDbSkillActive,
  type Skill,
} from './db';
import { parseSkillMarkdown } from './skill-parser';

export const SKILLS_SH_SEARCH_URL = 'https://skills.sh/api/search';

export interface SkillSearchResult {
  id: string;
  skillId: string;
  name: string;
  source: string;
  installs: number;
  description?: string;
}

export interface InstallSkillParams {
  skillId: string;
  name: string;
  source: string;
  description: string;
  content: string;
}

export interface InstallResult {
  installed: boolean;
  id: string;
}

export interface SyncSiteProfilesResult {
  imported: number;
  updated: number;
}

export interface SyncLocalSkillsResult {
  imported: number;
  skipped: number;
}

export interface UpsertInstalledSkillParams {
  id?: string;
  name: string;
  description?: string;
  content: string;
  source?: string;
  sourceId?: string;
  isActive?: number;
  urlPattern?: string | null;
}

export const BUNDLED_PROFILES: string[] = [];

function normalizeSkillId(skillId: string): string {
  let normalized = skillId.replace(/^(vercel-|google-|microsoft-|aws-)/i, '');
  normalized = normalized.replace(/:/g, '-');
  return normalized;
}

function buildInstalledSkillId(source: string, sourceId: string): string {
  return `${source}:${sourceId}`;
}

async function parseResponseData<T>(response: Response): Promise<T> {
  const text = await response.text();
  return JSON.parse(text) as T;
}

export function buildSkillContentUrl(source: string, skillId: string): string {
  const normalizedSkillId = normalizeSkillId(skillId);
  return `https://raw.githubusercontent.com/${source}/main/skills/${normalizedSkillId}/SKILL.md`;
}

export async function searchSkills(query: string, limit = 20): Promise<SkillSearchResult[]> {
  try {
    const response = await tauriFetch(`${SKILLS_SH_SEARCH_URL}?q=${encodeURIComponent(query)}&limit=${limit}`);
    const data = await parseResponseData<{ skills?: SkillSearchResult[] }>(response);
    return data.skills ?? [];
  } catch {
    return [];
  }
}

export async function fetchTrendingSkills(): Promise<SkillSearchResult[]> {
  try {
    const response = await tauriFetch('https://skills.sh/api/skills/trending/0');
    const data = await parseResponseData<{ skills?: SkillSearchResult[] }>(response);
    return data.skills ?? [];
  } catch {
    return [];
  }
}

export async function fetchSkillContent(source: string, skillId: string): Promise<string> {
  const url = buildSkillContentUrl(source, skillId);
  const response = await tauriFetch(url);
  return response.text();
}

export async function listInstalledSkills(): Promise<Skill[]> {
  return getDbSkills();
}

export async function getInstalledSkill(id: string): Promise<Skill | null> {
  return getDbSkill(id);
}

export async function getSkillBySource(source: string, sourceId: string): Promise<Skill | null> {
  return getDbSkill(buildInstalledSkillId(source, sourceId));
}

export async function getActiveSkills(): Promise<Skill[]> {
  const skills = await getDbSkills();
  return skills.filter((skill) => skill.is_active === 1);
}

export async function getSkillsWithUrlPattern(): Promise<Skill[]> {
  const skills = await getDbSkills();
  return skills.filter((skill) => Boolean(skill.url_pattern?.trim()));
}

export async function upsertInstalledSkill(params: UpsertInstalledSkillParams): Promise<string> {
  const id = params.id ?? buildInstalledSkillId(params.source ?? 'local', params.sourceId ?? globalThis.crypto.randomUUID());

  await saveDbSkill({
    id,
    name: params.name,
    description: params.description ?? null,
    content: params.content,
    source: params.source ?? 'local',
    is_active: params.isActive ?? 0,
    url_pattern: params.urlPattern ?? null,
  });

  return id;
}

export async function deleteInstalledSkill(id: string): Promise<void> {
  await deleteDbSkill(id);
}

export async function setInstalledSkillActive(id: string, isActive: number): Promise<void> {
  await updateDbSkillActive(id, isActive);
}

export async function installSkill(params: InstallSkillParams): Promise<InstallResult> {
  const id = buildInstalledSkillId(params.source, params.skillId);
  const existing = await getDbSkill(id);

  if (existing) {
    return { installed: false, id: existing.id };
  }

  await saveDbSkill({
    id,
    name: params.name,
    description: params.description,
    content: params.content,
    source: params.source,
    is_active: 0,
  });

  return { installed: true, id };
}

export async function buildSkillInjection(): Promise<string> {
  const skills = await getActiveSkills();
  if (skills.length === 0) {
    return '';
  }

  return skills.map((skill) => `\n\n--- SKILL: ${skill.name} ---\n${skill.content}\n--- END SKILL ---\n\n`).join('');
}

export async function getSkillInjectionSize(): Promise<{ charCount: number; skillCount: number }> {
  const injection = await buildSkillInjection();
  const skills = await getActiveSkills();
  return { charCount: injection.length, skillCount: skills.length };
}

export function findMatchingProfiles(url: string, skills: Skill[]): Skill[] {
  const lowerUrl = url.toLowerCase();
  return skills.filter((skill) => {
    if (!skill.url_pattern) {
      return false;
    }

    const patterns = skill.url_pattern
      .split(',')
      .map((pattern) => pattern.trim())
      .filter(Boolean);

    return patterns.some((pattern) => lowerUrl.includes(pattern.toLowerCase()));
  });
}

export async function buildSiteContextInjection(url: string): Promise<string> {
  const skillsWithPatterns = await getSkillsWithUrlPattern();
  const matching = findMatchingProfiles(url, skillsWithPatterns);
  if (matching.length === 0) {
    return '';
  }

  return matching
    .map((skill) => `\n\n--- SITE GUIDE: ${skill.name} ---\n${skill.content}\n--- END SITE GUIDE ---\n\n`)
    .join('');
}

export async function syncSiteProfiles(profiles: string[] = BUNDLED_PROFILES): Promise<SyncSiteProfilesResult> {
  let imported = 0;
  let updated = 0;

  for (const content of profiles) {
    try {
      const parsed = parseSkillMarkdown(content);
      const sourceId = parsed.urlPatterns?.[0] ?? '';
      if (!sourceId) {
        continue;
      }

      const id = buildInstalledSkillId('site-profile', sourceId);
      const existing = await getDbSkill(id);

      await saveDbSkill({
        id,
        name: parsed.name || sourceId,
        description: parsed.description || '',
        content,
        source: 'site-profile',
        is_active: existing?.is_active ?? 0,
        url_pattern: parsed.urlPatterns?.join(',') ?? null,
      });

      if (existing) {
        updated++;
      } else {
        imported++;
      }
    } catch (error) {
      console.warn('syncSiteProfiles: failed to sync profile:', error);
    }
  }

  return { imported, updated };
}

export async function syncLocalSkills(): Promise<SyncLocalSkillsResult> {
  return { imported: 0, skipped: 0 };
}

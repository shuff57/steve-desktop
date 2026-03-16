import Database from '@tauri-apps/plugin-sql';
import { DB_NAME } from './constants';

export interface OAuthToken {
  access_token: string;
  refresh_token: string | null;
  token_type: string | null;
  expires_at: number | null;
}

export interface ProviderConfig {
  id: string;
  api_url: string | null;
  api_key: string | null;
  model: string | null;
  is_active: number;
}

export interface Skill {
  id: string;
  name: string;
  description: string | null;
  content: string;
  source: string;
  is_active: number;
  url_pattern: string | null;
}

export interface SiteProfile {
  id: string;
  domain: string;
  page_name: string;
  profile_json: string;
}

type SqlDb = Awaited<ReturnType<typeof Database.load>>;

let db: SqlDb | null = null;

async function initDB(): Promise<SqlDb> {
  if (!db) {
    db = await Database.load(DB_NAME);
  }
  return db;
}

export async function saveOAuthToken(
  provider: string,
  access_token: string,
  refresh_token?: string | null,
  token_type?: string | null,
  expires_at?: number | null,
): Promise<void> {
  const database = await initDB();
  await database.execute(
    `INSERT INTO oauth_tokens (provider, access_token, refresh_token, token_type, expires_at, created_at, updated_at)
     VALUES ($1, $2, $3, $4, $5, datetime('now'), datetime('now'))
     ON CONFLICT(provider) DO UPDATE SET
       access_token = $2,
       refresh_token = $3,
       token_type = $4,
       expires_at = $5,
       updated_at = datetime('now')`,
    [provider, access_token, refresh_token ?? null, token_type ?? null, expires_at ?? null],
  );
}

export async function getOAuthToken(provider: string): Promise<OAuthToken | null> {
  const database = await initDB();
  const rows = await database.select<OAuthToken[]>(
    'SELECT access_token, refresh_token, token_type, expires_at FROM oauth_tokens WHERE provider = $1',
    [provider],
  );
  return rows.length > 0 ? rows[0] : null;
}

export async function deleteOAuthToken(provider: string): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM oauth_tokens WHERE provider = $1', [provider]);
}

export async function saveProviderConfig(
  id: string,
  api_url?: string | null,
  api_key?: string | null,
  model?: string | null,
  is_active?: number,
): Promise<void> {
  const database = await initDB();
  await database.execute(
    `INSERT INTO provider_configs (id, api_url, api_key, model, is_active, created_at, updated_at)
     VALUES ($1, $2, $3, $4, $5, datetime('now'), datetime('now'))
     ON CONFLICT(id) DO UPDATE SET
       api_url = $2,
       api_key = $3,
       model = $4,
       is_active = $5,
       updated_at = datetime('now')`,
    [id, api_url ?? null, api_key ?? null, model ?? null, is_active ?? 0],
  );
}

export async function getProviderConfig(id: string): Promise<ProviderConfig | null> {
  const database = await initDB();
  const rows = await database.select<ProviderConfig[]>(
    'SELECT id, api_url, api_key, model, is_active FROM provider_configs WHERE id = $1',
    [id],
  );
  return rows.length > 0 ? rows[0] : null;
}

export async function getActiveProvider(): Promise<ProviderConfig | null> {
  const database = await initDB();
  const rows = await database.select<ProviderConfig[]>(
    'SELECT id, api_url, api_key, model, is_active FROM provider_configs WHERE is_active = 1 ORDER BY id LIMIT 1',
  );
  return rows.length > 0 ? rows[0] : null;
}

export async function listProviderConfigs(): Promise<ProviderConfig[]> {
  const database = await initDB();
  return database.select<ProviderConfig[]>(
    'SELECT id, api_url, api_key, model, is_active FROM provider_configs ORDER BY id',
  );
}

export async function setSetting(key: string, value: string): Promise<void> {
  const database = await initDB();
  await database.execute(
    `INSERT INTO app_settings (key, value) VALUES ($1, $2)
     ON CONFLICT(key) DO UPDATE SET value = $2`,
    [key, value],
  );
}

export async function getSetting(key: string): Promise<string | null> {
  const database = await initDB();
  const rows = await database.select<{ value: string | null }[]>(
    'SELECT value FROM app_settings WHERE key = $1',
    [key],
  );
  return rows.length > 0 ? rows[0].value : null;
}

export async function saveSkill(skill: {
  id: string;
  name: string;
  description?: string | null;
  content: string;
  source?: string;
  is_active?: number;
  url_pattern?: string | null;
}): Promise<void> {
  const database = await initDB();
  await database.execute(
    `INSERT INTO skills (id, name, description, content, source, is_active, url_pattern)
     VALUES ($1, $2, $3, $4, $5, $6, $7)
     ON CONFLICT(id) DO UPDATE SET
       name = $2,
       description = $3,
       content = $4,
       source = $5,
       is_active = $6,
       url_pattern = $7`,
    [
      skill.id,
      skill.name,
      skill.description ?? null,
      skill.content,
      skill.source ?? 'local',
      skill.is_active ?? 1,
      skill.url_pattern ?? null,
    ],
  );
}

export async function getSkills(): Promise<Skill[]> {
  const database = await initDB();
  return database.select<Skill[]>(
    'SELECT id, name, description, content, source, is_active, url_pattern FROM skills ORDER BY name',
  );
}

export async function getSkill(id: string): Promise<Skill | null> {
  const database = await initDB();
  const rows = await database.select<Skill[]>(
    'SELECT id, name, description, content, source, is_active, url_pattern FROM skills WHERE id = $1',
    [id],
  );
  return rows.length > 0 ? rows[0] : null;
}

export async function deleteSkill(id: string): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM skills WHERE id = $1', [id]);
}

export async function updateSkillActive(id: string, isActive: number): Promise<void> {
  const database = await initDB();
  await database.execute('UPDATE skills SET is_active = $1 WHERE id = $2', [isActive, id]);
}

export async function saveSiteProfile(domain: string, pageName: string, profileJson: string): Promise<void> {
  const database = await initDB();
  await database.execute(
    `INSERT INTO site_profiles (id, domain, page_name, profile_json, created_at, updated_at)
     VALUES (lower(hex(randomblob(16))), $1, $2, $3, datetime('now'), datetime('now'))
     ON CONFLICT(domain, page_name) DO UPDATE SET
       profile_json = $3,
       updated_at = datetime('now')`,
    [domain, pageName, profileJson],
  );
}

export async function getSiteProfile(domain: string, pageName: string): Promise<SiteProfile | null> {
  const database = await initDB();
  const rows = await database.select<SiteProfile[]>(
    'SELECT id, domain, page_name, profile_json FROM site_profiles WHERE domain = $1 AND page_name = $2',
    [domain, pageName],
  );
  return rows.length > 0 ? rows[0] : null;
}

export async function listSiteProfiles(): Promise<SiteProfile[]> {
  const database = await initDB();
  return database.select<SiteProfile[]>(
    'SELECT id, domain, page_name, profile_json FROM site_profiles ORDER BY domain, page_name',
  );
}

export async function deleteSiteProfile(id: string): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM site_profiles WHERE id = $1', [id]);
}
export async function getProviderConfigs() { return []; }
export async function deleteProviderConfig(id: string): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM provider_configs WHERE id = $1', [id]);
}

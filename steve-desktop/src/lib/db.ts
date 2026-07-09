import Database from '@tauri-apps/plugin-sql';
import { invoke } from '@tauri-apps/api/core';
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

// Credential passwords live in the OS keychain (Windows Credential Manager / macOS Keychain),
// encrypted at rest — only non-secret metadata is in steve.db. The DB `password` column is kept
// as a legacy read-fallback for rows saved before this change. Never synced to any cloud (design).
export interface SiteCredential {
  id: number;
  site_name: string;
  url_pattern: string;
  username: string;
  password: string;
  notes: string | null;
  totp_secret?: string; // base32 2FA seed; kept in the keychain, never in the DB
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

export async function saveProviderConfig(config: {
  id: string;
  api_url?: string | null;
  api_key?: string | null;
  model?: string | null;
  is_active?: number;
}): Promise<void> {
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
    [config.id, config.api_url ?? null, config.api_key ?? null, config.model ?? null, config.is_active ?? 0],
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

// One keychain entry per credential id. Helpers swallow nothing — callers decide.
const credKey = (id: number) => `credential:${id}`;
const totpKey = (id: number) => `credential:${id}:totp`;
const keyringSet = (key: string, secret: string) => invoke<void>('keyring_set', { key, secret });
const keyringGet = (key: string) => invoke<string | null>('keyring_get', { key });
const keyringDelete = (key: string) => invoke<void>('keyring_delete', { key });

export async function getSiteCredentials(): Promise<SiteCredential[]> {
  const database = await initDB();
  const rows = await database.select<SiteCredential[]>(
    'SELECT id, site_name, url_pattern, username, password, notes FROM site_credentials ORDER BY site_name',
  );
  // Source each password from the keychain. For rows saved before this change (secret absent,
  // legacy plaintext still in the DB), migrate it into the keychain and blank the column.
  return Promise.all(
    rows.map(async (r) => {
      const secret = await keyringGet(credKey(r.id)).catch(() => null);
      const totp_secret = (await keyringGet(totpKey(r.id)).catch(() => null)) ?? undefined;
      if (secret == null && r.password) {
        await keyringSet(credKey(r.id), r.password).catch(() => {});
        await database.execute("UPDATE site_credentials SET password = '' WHERE id = $1", [r.id]).catch(() => {});
        return { ...r, totp_secret }; // keep the real password for this call; next read comes from the keychain
      }
      return { ...r, password: secret ?? r.password, totp_secret };
    }),
  );
}

export async function saveSiteCredential(cred: {
  id?: number;
  site_name: string;
  url_pattern: string;
  username: string;
  password: string;
  notes?: string | null;
  totp_secret?: string | null;
}): Promise<void> {
  const database = await initDB();
  // Both password and 2FA seed go only to the keychain, never the DB.
  const setTotp = async (id: number) => {
    const seed = cred.totp_secret?.replace(/\s/g, '');
    if (seed) await keyringSet(totpKey(id), seed);
    else await keyringDelete(totpKey(id)).catch(() => {});
  };
  if (cred.id) {
    await database.execute(
      `UPDATE site_credentials SET
         site_name = $1, url_pattern = $2, username = $3, password = '', notes = $4,
         updated_at = datetime('now')
       WHERE id = $5`,
      [cred.site_name, cred.url_pattern, cred.username, cred.notes ?? null, cred.id],
    );
    await keyringSet(credKey(cred.id), cred.password);
    await setTotp(cred.id);
  } else {
    const res = await database.execute(
      `INSERT INTO site_credentials (site_name, url_pattern, username, password, notes)
       VALUES ($1, $2, $3, '', $4)`,
      [cred.site_name, cred.url_pattern, cred.username, cred.notes ?? null],
    );
    if (res.lastInsertId != null) {
      await keyringSet(credKey(Number(res.lastInsertId)), cred.password);
      await setTotp(Number(res.lastInsertId));
    }
  }
}

export async function deleteSiteCredential(id: number): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM site_credentials WHERE id = $1', [id]);
  await keyringDelete(credKey(id)).catch(() => {});
  await keyringDelete(totpKey(id)).catch(() => {});
}

export interface Bookmark {
  id: number;
  title: string;
  url: string;
}

export async function getBookmarks(): Promise<Bookmark[]> {
  const database = await initDB();
  return database.select<Bookmark[]>('SELECT id, title, url FROM bookmarks ORDER BY title');
}

// url is UNIQUE — INSERT OR IGNORE makes "bookmark this page" idempotent.
export async function addBookmark(title: string, url: string): Promise<void> {
  const database = await initDB();
  await database.execute('INSERT OR IGNORE INTO bookmarks (title, url) VALUES ($1, $2)', [title, url]);
}

export async function deleteBookmark(id: number): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM bookmarks WHERE id = $1', [id]);
}

export async function deleteBookmarkByUrl(url: string): Promise<void> {
  const database = await initDB();
  await database.execute('DELETE FROM bookmarks WHERE url = $1', [url]);
}

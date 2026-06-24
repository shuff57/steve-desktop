import { beforeEach, describe, expect, it, vi } from 'vitest';

const { mockExecute, mockSelect, mockLoad, mockInvoke } = vi.hoisted(() => {
  const mockExecute = vi.fn().mockResolvedValue({ rowsAffected: 1, lastInsertId: 1 });
  const mockSelect = vi.fn().mockResolvedValue([]);
  const mockLoad = vi.fn().mockResolvedValue({ execute: mockExecute, select: mockSelect });
  const mockInvoke = vi.fn().mockResolvedValue(null);
  return { mockExecute, mockSelect, mockLoad, mockInvoke };
});

vi.mock('@tauri-apps/plugin-sql', () => ({
  default: {
    load: mockLoad,
  },
}));

vi.mock('@tauri-apps/api/core', () => ({
  invoke: mockInvoke,
}));

import {
  deleteOAuthToken,
  deleteSiteProfile,
  deleteSkill,
  getOAuthToken,
  getProviderConfig,
  getSetting,
  getSiteProfile,
  getSkill,
  getSkills,
  listProviderConfigs,
  listSiteProfiles,
  saveOAuthToken,
  saveProviderConfig,
  saveSiteProfile,
  saveSkill,
  setSetting,
  updateSkillActive,
  getSiteCredentials,
  saveSiteCredential,
  deleteSiteCredential,
  type SiteCredential,
} from './db';

describe('db.ts', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    mockSelect.mockResolvedValue([]);
  });

  it('saveOAuthToken writes expected upsert SQL and params', async () => {
    await saveOAuthToken('github', 'access-1', 'refresh-1', 'Bearer', 12345);

    expect(mockLoad).toHaveBeenCalledWith('sqlite:steve.db');
    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('INSERT INTO oauth_tokens'),
      ['github', 'access-1', 'refresh-1', 'Bearer', 12345],
    );
  });

  it('getOAuthToken returns null when no rows exist', async () => {
    mockSelect.mockResolvedValueOnce([]);

    const token = await getOAuthToken('github');

    expect(token).toBeNull();
    expect(mockSelect).toHaveBeenCalledWith(
      expect.stringContaining('SELECT access_token, refresh_token, token_type, expires_at FROM oauth_tokens'),
      ['github'],
    );
  });

  it('getOAuthToken returns token fields when row exists', async () => {
    mockSelect.mockResolvedValueOnce([
      {
        access_token: 'access-2',
        refresh_token: 'refresh-2',
        token_type: 'Bearer',
        expires_at: 999,
      },
    ]);

    await saveOAuthToken('github', 'access-1');
    const token = await getOAuthToken('github');

    expect(token).toEqual({
      access_token: 'access-2',
      refresh_token: 'refresh-2',
      token_type: 'Bearer',
      expires_at: 999,
    });
  });

  it('setSetting and getSetting round trip expected values', async () => {
    await setSetting('theme', 'dark');

    mockSelect.mockResolvedValueOnce([{ value: 'dark' }]);
    const value = await getSetting('theme');

    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('INSERT INTO app_settings'),
      ['theme', 'dark'],
    );
    expect(value).toBe('dark');
  });

  it('saveSkill/getSkills/getSkill/updateSkillActive/deleteSkill perform CRUD SQL operations', async () => {
    await saveSkill({
      id: 'skill-1',
      name: 'My Skill',
      description: 'desc',
      content: 'content',
      source: 'local',
      is_active: 1,
      url_pattern: 'https://example.com/*',
    });

    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('INSERT INTO skills'),
      ['skill-1', 'My Skill', 'desc', 'content', 'local', 1, 'https://example.com/*'],
    );

    mockSelect.mockResolvedValueOnce([
      {
        id: 'skill-1',
        name: 'My Skill',
        description: 'desc',
        content: 'content',
        source: 'local',
        is_active: 1,
        url_pattern: 'https://example.com/*',
      },
    ]);
    const all = await getSkills();
    expect(all).toHaveLength(1);

    mockSelect.mockResolvedValueOnce([
      {
        id: 'skill-1',
        name: 'My Skill',
        description: 'desc',
        content: 'content',
        source: 'local',
        is_active: 1,
        url_pattern: 'https://example.com/*',
      },
    ]);
    const one = await getSkill('skill-1');
    expect(one?.id).toBe('skill-1');

    await updateSkillActive('skill-1', 0);
    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('UPDATE skills SET is_active = $1'),
      [0, 'skill-1'],
    );

    await deleteSkill('skill-1');
    expect(mockExecute).toHaveBeenCalledWith('DELETE FROM skills WHERE id = $1', ['skill-1']);
  });

  it('exposes provider and site profile APIs for steve schema', async () => {
    await saveProviderConfig({ id: 'ollama', api_url: 'http://localhost:11434', api_key: 'k', model: 'llama3', is_active: 1 });
    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('INSERT INTO provider_configs'),
      ['ollama', 'http://localhost:11434', 'k', 'llama3', 1],
    );

    mockSelect.mockResolvedValueOnce([
      { id: 'ollama', api_url: 'http://localhost:11434', api_key: 'k', model: 'llama3', is_active: 1 },
    ]);
    const config = await getProviderConfig('ollama');
    expect(config?.id).toBe('ollama');

    mockSelect.mockResolvedValueOnce([
      { id: 'ollama', api_url: 'http://localhost:11434', api_key: 'k', model: 'llama3', is_active: 1 },
    ]);
    const allConfigs = await listProviderConfigs();
    expect(allConfigs).toHaveLength(1);

    await saveSiteProfile('example.com', 'watch-page', '{"autoplay":true}');
    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('INSERT INTO site_profiles'),
      ['example.com', 'watch-page', '{"autoplay":true}'],
    );

    mockSelect.mockResolvedValueOnce([
      { id: 'profile-1', domain: 'example.com', page_name: 'watch-page', profile_json: '{"autoplay":true}' },
    ]);
    const profile = await getSiteProfile('example.com', 'watch-page');
    expect(profile?.domain).toBe('example.com');

    mockSelect.mockResolvedValueOnce([
      { id: 'profile-1', domain: 'example.com', page_name: 'watch-page', profile_json: '{"autoplay":true}' },
    ]);
    const profiles = await listSiteProfiles();
    expect(profiles).toHaveLength(1);

    await deleteSiteProfile('profile-1');
    expect(mockExecute).toHaveBeenCalledWith('DELETE FROM site_profiles WHERE id = $1', ['profile-1']);
  });

  it('deletes oauth token by provider', async () => {
    await deleteOAuthToken('github');
    expect(mockExecute).toHaveBeenCalledWith('DELETE FROM oauth_tokens WHERE provider = $1', ['github']);
  });

  it('saveSiteCredential inserts metadata (empty DB password) and stores the secret in the keychain', async () => {
    mockExecute.mockResolvedValueOnce({ rowsAffected: 1, lastInsertId: 42 });
    await saveSiteCredential({
      site_name: 'MyOpenMath',
      url_pattern: 'myopenmath.com',
      username: 'teacher@example.edu',
      password: 'hunter2',
      notes: 'period 1',
    });

    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('INSERT INTO site_credentials'),
      ['MyOpenMath', 'myopenmath.com', 'teacher@example.edu', 'period 1'], // no password in the DB
    );
    expect(mockInvoke).toHaveBeenCalledWith('keyring_set', { key: 'credential:42', secret: 'hunter2' });
  });

  it('saveSiteCredential updates metadata by id and re-stores the secret in the keychain', async () => {
    await saveSiteCredential({
      id: 7,
      site_name: 'Canvas',
      url_pattern: 'instructure.com',
      username: 'u',
      password: 'p',
    });

    expect(mockExecute).toHaveBeenCalledWith(
      expect.stringContaining('UPDATE site_credentials SET'),
      ['Canvas', 'instructure.com', 'u', null, 7], // no password in the DB
    );
    expect(mockInvoke).toHaveBeenCalledWith('keyring_set', { key: 'credential:7', secret: 'p' });
  });

  it('getSiteCredentials sources the password from the keychain', async () => {
    const row: SiteCredential = {
      id: 1,
      site_name: 'Canvas',
      url_pattern: 'instructure.com',
      username: 'u',
      password: '', // DB column is empty now
      notes: null,
    };
    mockSelect.mockResolvedValueOnce([row]);
    mockInvoke.mockResolvedValueOnce('secret-from-keychain');

    const creds = await getSiteCredentials();

    expect(mockInvoke).toHaveBeenCalledWith('keyring_get', { key: 'credential:1' });
    expect(creds).toEqual([{ ...row, password: 'secret-from-keychain' }]);
  });

  it('getSiteCredentials migrates a legacy DB password into the keychain', async () => {
    mockSelect.mockResolvedValueOnce([
      { id: 5, site_name: 'X', url_pattern: 'x.com', username: 'u', password: 'legacy-pw', notes: null },
    ]);
    mockInvoke.mockResolvedValueOnce(null); // keyring_get → no entry yet

    const creds = await getSiteCredentials();

    expect(mockInvoke).toHaveBeenCalledWith('keyring_set', { key: 'credential:5', secret: 'legacy-pw' });
    expect(mockExecute).toHaveBeenCalledWith("UPDATE site_credentials SET password = '' WHERE id = $1", [5]);
    expect(creds[0].password).toBe('legacy-pw'); // still usable on this call
  });

  it('deleteSiteCredential removes the row and the keychain entry', async () => {
    await deleteSiteCredential(3);
    expect(mockExecute).toHaveBeenCalledWith('DELETE FROM site_credentials WHERE id = $1', [3]);
    expect(mockInvoke).toHaveBeenCalledWith('keyring_delete', { key: 'credential:3' });
  });

  it('does not export grading-related functions', async () => {
    const module = await import('./db');
    expect(module).not.toHaveProperty('saveGradingSession');
    expect(module).not.toHaveProperty('getGradingHistory');
    expect(module).not.toHaveProperty('getGradingStats');
    expect(module).not.toHaveProperty('saveBatchResult');
    expect(module).not.toHaveProperty('getBatchSessions');
    expect(module).not.toHaveProperty('saveEmbedding');
    expect(module).not.toHaveProperty('searchEmbeddings');
    expect(module).not.toHaveProperty('saveCalibrationSet');
    expect(module).not.toHaveProperty('getCalibrationSets');
    expect(module).not.toHaveProperty('updateVisibleColumns');
    expect(module).not.toHaveProperty('getVisibleColumns');
  });
});

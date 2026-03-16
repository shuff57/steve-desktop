import { beforeEach, describe, expect, it, vi } from 'vitest';

const mockExecute = vi.fn().mockResolvedValue({ rowsAffected: 1, lastInsertId: 1 });
const mockSelect = vi.fn().mockResolvedValue([]);
const mockLoad = vi.fn().mockResolvedValue({ execute: mockExecute, select: mockSelect });

vi.mock('@tauri-apps/plugin-sql', () => ({
  default: {
    load: mockLoad,
  },
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
    await saveProviderConfig('ollama', 'http://localhost:11434', 'k', 'llama3', 1);
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

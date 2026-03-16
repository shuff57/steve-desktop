import { beforeEach, describe, expect, it, vi } from 'vitest';

const invokeMock = vi.fn();

vi.mock('@tauri-apps/api/core', () => ({
  invoke: invokeMock,
}));

import type { SiteProfile } from './types/site-profile';
import {
  deleteProfile,
  findProfileByUrl,
  getProfilePath,
  listProfiles,
  loadProfile,
  saveProfile,
} from './site-profiles';

const sampleProfile: SiteProfile = {
  url: 'https://example.com/home',
  domain: 'example.com',
  pageName: 'home',
  profiledAt: '2026-03-15T00:00:00.000Z',
  interactive: { buttons: [], links: [], inputs: [], selects: [], checkboxes: [], radios: [], forms: [] },
  summary: { buttons: 0, links: 0, inputs: 0, selects: 0, checkboxes: 0, radios: 0, forms: 0, landmarks: 0, headings: 0 },
};

describe('getProfilePath', () => {
  it('generates correct path for domain and page', () => {
    const path = getProfilePath('example.com', 'home');
    expect(path).toBe('.agents/site-profiles/example-com/home.json');
  });

  it('handles dots in domain', () => {
    const path = getProfilePath('butte-keenan.safecolleges.com', 'video-player');
    expect(path).toBe('.agents/site-profiles/butte-keenan-safecolleges-com/video-player.json');
  });

  it('slugifies page names', () => {
    const path = getProfilePath('example.com', 'Video Player');
    expect(path).toBe('.agents/site-profiles/example-com/video-player.json');
  });
});

describe('findProfileByUrl', () => {
  it('finds matching profile by domain', () => {
    const profiles = [
      { domain: 'example.com', pageName: 'home', path: '.agents/site-profiles/example-com/home.json' },
    ];
    const result = findProfileByUrl(profiles, 'https://example.com/home');
    expect(result).toBe('.agents/site-profiles/example-com/home.json');
  });

  it('returns null when no match', () => {
    const result = findProfileByUrl([], 'https://unknown.com');
    expect(result).toBeNull();
  });

  it('returns null for invalid url input', () => {
    const profiles = [
      { domain: 'example.com', pageName: 'home', path: '.agents/site-profiles/example-com/home.json' },
    ];
    expect(findProfileByUrl(profiles, 'not a valid url')).toBeNull();
  });
});

describe('site profile storage', () => {
  beforeEach(() => {
    invokeMock.mockReset();
  });

  it('saveProfile creates the domain directory, writes pretty json, and returns the saved path', async () => {
    invokeMock.mockResolvedValue(undefined);

    const savedPath = await saveProfile(sampleProfile);

    expect(savedPath).toBe('.agents/site-profiles/example-com/home.json');
    expect(invokeMock).toHaveBeenNthCalledWith(1, 'create_dir', {
      path: '.agents/site-profiles/example-com',
      recursive: true,
    });
    expect(invokeMock).toHaveBeenNthCalledWith(2, 'write_file', {
      path: '.agents/site-profiles/example-com/home.json',
      contents: JSON.stringify(sampleProfile, null, 2),
    });
  });

  it('loadProfile returns the parsed profile when the file exists', async () => {
    invokeMock.mockResolvedValue(JSON.stringify(sampleProfile));

    const profile = await loadProfile('example.com', 'home');

    expect(profile).toEqual(sampleProfile);
    expect(invokeMock).toHaveBeenCalledWith('read_file', {
      path: '.agents/site-profiles/example-com/home.json',
    });
  });

  it('loadProfile returns null when the file is missing', async () => {
    invokeMock.mockRejectedValue(new Error('not found'));

    const profile = await loadProfile('example.com', 'home');

    expect(profile).toBeNull();
  });

  it('listProfiles returns stored profile metadata from json files', async () => {
    invokeMock
      .mockResolvedValueOnce([
        '.agents/site-profiles/example-com/home.json',
        '.agents/site-profiles/second-example-com/video-player.json',
      ])
      .mockResolvedValueOnce(JSON.stringify(sampleProfile))
      .mockResolvedValueOnce(
        JSON.stringify({
          ...sampleProfile,
          domain: 'second-example.com',
          pageName: 'video-player',
          url: 'https://second-example.com/video',
        }),
      );

    const profiles = await listProfiles();

    expect(invokeMock).toHaveBeenNthCalledWith(1, 'list_files', {
      path: '.agents/site-profiles',
      recursive: true,
    });
    expect(profiles).toEqual([
      { domain: 'example.com', pageName: 'home', path: '.agents/site-profiles/example-com/home.json' },
      {
        domain: 'second-example.com',
        pageName: 'video-player',
        path: '.agents/site-profiles/second-example-com/video-player.json',
      },
    ]);
  });

  it('deleteProfile deletes the profile file', async () => {
    invokeMock.mockResolvedValue(undefined);

    await deleteProfile('example.com', 'home');

    expect(invokeMock).toHaveBeenCalledWith('delete_file', {
      path: '.agents/site-profiles/example-com/home.json',
    });
  });
});

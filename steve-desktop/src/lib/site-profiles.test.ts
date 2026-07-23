import { beforeEach, describe, expect, it, vi } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));

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

  it('falls back to "page" when the name is missing or unslugifiable', () => {
    // Real sites hand back undefined/punctuation-only page names; never write "/.json".
    expect(getProfilePath('example.com', undefined as unknown as string))
      .toBe('.agents/site-profiles/example-com/page.json');
    expect(getProfilePath('example.com', '???')).toBe('.agents/site-profiles/example-com/page.json');
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

describe('dirty pages — heal-driven targeted re-map', () => {
  beforeEach(() => {
    invokeMock.mockReset();
  });

  it('markPageDirty accumulates URLs into _dirty.json', async () => {
    invokeMock.mockImplementation(async (cmd: string) => {
      if (cmd === 'read_file') return JSON.stringify({ 'https://x.edu/a': '2026-07-23T00:00:00Z' });
      return undefined;
    });
    const { markPageDirty } = await import('./site-profiles');
    await markPageDirty('x.edu', 'https://x.edu/b');

    const write = invokeMock.mock.calls.find(([cmd]) => cmd === 'write_file');
    expect(write?.[1]).toMatchObject({ path: '.agents/site-profiles/x-edu/_dirty.json' });
    const stored = JSON.parse((write?.[1] as { contents: string }).contents) as Record<string, string>;
    expect(Object.keys(stored)).toEqual(['https://x.edu/a', 'https://x.edu/b']);
  });

  it('getDirtyPages returns [] on a clean domain, clearDirtyPages deletes the file', async () => {
    invokeMock.mockRejectedValueOnce(new Error('not found'));
    const { getDirtyPages, clearDirtyPages } = await import('./site-profiles');
    expect(await getDirtyPages('x.edu')).toEqual([]);

    invokeMock.mockResolvedValue(undefined);
    await clearDirtyPages('x.edu');
    expect(invokeMock).toHaveBeenCalledWith('delete_file', { path: '.agents/site-profiles/x-edu/_dirty.json' });
  });

  it('filterToDirty narrows to dirty pages, ignoring hash/trailing-slash noise but keeping query identity', async () => {
    const { filterToDirty } = await import('./site-profiles');
    const pages = [
      { name: 'gb', url: 'https://x.edu/gb?cid=1' },
      { name: 'forums', url: 'https://x.edu/forums?cid=1' },
      { name: 'cal', url: 'https://x.edu/cal?cid=1' },
    ];
    expect(filterToDirty(pages, ['https://x.edu/gb/?cid=1#top'])).toEqual([pages[0]]);
    // query differs = different page — no match → fall back to the FULL list, never silently skip
    expect(filterToDirty(pages, ['https://x.edu/gb?cid=2'])).toEqual(pages);
    // no dirty entries → untouched
    expect(filterToDirty(pages, [])).toEqual(pages);
  });
});

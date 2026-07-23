import { describe, it, expect, vi, beforeEach } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: invokeMock }));

import { loadMOMIndex, isJunkFamily } from './loader';

// The real filesystem walk now lives in Rust (mom_load_index) and is covered by cargo test
// `mom_tests::walks_questions_into_families` against the same fixture. Here we test the pure
// junk rule and that the TS wrapper shapes the command's reply correctly.

describe('isJunkFamily', () => {
  it('flags the Windows artifacts the loader must skip', () => {
    expect(isJunkFamily('nul')).toBe(true);
    expect(isJunkFamily('NUL')).toBe(true);
    expect(isJunkFamily('$APPDATA')).toBe(true);
    expect(isJunkFamily('C:Usersshuff')).toBe(true);
    expect(isJunkFamily('C:UsersshuffAppData')).toBe(true);
  });
  it('does not flag real family names', () => {
    expect(isJunkFamily('frq')).toBe(false);
    expect(isJunkFamily('mcq')).toBe(false);
    expect(isJunkFamily('descriptive-statistics')).toBe(false);
  });
});

describe('loadMOMIndex', () => {
  beforeEach(() => invokeMock.mockReset());

  it('passes the root through and wraps the families the command returns', async () => {
    invokeMock.mockResolvedValue([
      {
        name: 'frq',
        count: 1,
        questions: [{ slug: 'descriptive-statistics', path: '/mom/questions/frq/descriptive-statistics', hasManifest: true }],
      },
    ]);

    const index = await loadMOMIndex('/mom');

    expect(invokeMock).toHaveBeenCalledWith('mom_load_index', { root: '/mom' });
    expect(index.families).toHaveLength(1);
    expect(index.families[0]!.name).toBe('frq');
    expect(index.families[0]!.questions[0]).toMatchObject({
      slug: 'descriptive-statistics',
      hasManifest: true,
    });
  });

  it('returns an empty families array when the command reports nothing (missing questions dir)', async () => {
    invokeMock.mockResolvedValue([]);
    expect((await loadMOMIndex('/no-such-root')).families).toEqual([]);
  });

  it('tolerates a null reply rather than crashing the page', async () => {
    invokeMock.mockResolvedValue(null);
    expect((await loadMOMIndex('/mom')).families).toEqual([]);
  });
});

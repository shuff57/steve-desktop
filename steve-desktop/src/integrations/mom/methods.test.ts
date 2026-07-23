import { describe, it, expect, vi, beforeEach } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: invokeMock }));

import { momIsland } from './index';

// The filesystem lives behind Rust commands now (cargo test covers the real walk against the
// fixture). These assert the island surface: which command each method calls, and how it maps
// the reply — including the manifest stats folded into getQuestion.

const FIXTURE_FAMILIES = [
  {
    name: 'frq',
    count: 1,
    questions: [
      { slug: 'descriptive-statistics', path: '/mom/questions/frq/descriptive-statistics', hasManifest: true },
    ],
  },
];
const FIXTURE_MANIFEST = JSON.stringify({
  version: 1,
  questions: [
    { slug: 'q1-test', status: 'completed' },
    { slug: 'q2-pending', status: 'pending' },
  ],
});
const FIXTURE_PHP = '<?php\n$questiontext = "Find the mean of the data set";\n';

beforeEach(() => invokeMock.mockReset());

describe('momIsland.browse', () => {
  it('returns the family index for the root', async () => {
    invokeMock.mockResolvedValue(FIXTURE_FAMILIES);
    const idx = await momIsland.methods.browse('/mom');
    expect(invokeMock).toHaveBeenCalledWith('mom_load_index', { root: '/mom' });
    expect(idx.families).toHaveLength(1);
    expect(idx.families[0]!.name).toBe('frq');
  });
});

describe('momIsland.getQuestion', () => {
  it('returns the PHP file contents + manifest stats for a known question', async () => {
    invokeMock.mockResolvedValue({
      path: '/mom/questions/frq/descriptive-statistics/q1-test.php',
      contents: FIXTURE_PHP,
      manifestText: FIXTURE_MANIFEST,
    });

    const q = await momIsland.methods.getQuestion('frq', 'descriptive-statistics', '/mom');

    expect(invokeMock).toHaveBeenCalledWith('mom_read_question', {
      root: '/mom',
      family: 'frq',
      slug: 'descriptive-statistics',
    });
    expect(q.family).toBe('frq');
    expect(q.slug).toBe('descriptive-statistics');
    expect(q.contents).toContain('$questiontext');
    expect(q.contents).toContain('Find the mean');
    expect(q.manifest).toEqual({ completed: 1, pending: 1, total: 2 });
  });

  it('reports zeroed stats when the question has no manifest', async () => {
    invokeMock.mockResolvedValue({ path: '/x/q.php', contents: FIXTURE_PHP, manifestText: null });
    const q = await momIsland.methods.getQuestion('frq', 'descriptive-statistics', '/mom');
    expect(q.manifest).toEqual({ completed: 0, pending: 0, total: 0 });
  });

  // Unknown family/slug is detected by mom_read_question in Rust (it resolves against the
  // walked index) — the island just lets that rejection through.
});

describe('momIsland.getFamily', () => {
  it('returns family + questions + aggregate manifest stats', async () => {
    invokeMock.mockImplementation(async (cmd: string) =>
      cmd === 'mom_read_manifest' ? FIXTURE_MANIFEST : FIXTURE_FAMILIES,
    );

    const fam = await momIsland.methods.getFamily('frq', '/mom');

    expect(fam.name).toBe('frq');
    expect(fam.count).toBe(1);
    expect(fam.questions).toHaveLength(1);
    expect(fam.manifest.total).toBe(2);
  });

  it('rejects an unknown family', async () => {
    invokeMock.mockResolvedValue(FIXTURE_FAMILIES);
    await expect(momIsland.methods.getFamily('nope', '/mom')).rejects.toThrow(/Unknown family/);
  });
});

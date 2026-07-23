import { describe, it, expect, vi, beforeEach } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: invokeMock }));

import { getFrqSetStats, readManifest, parseManifest, aggregateStats } from './manifest';

// Reading the file is Rust's job (mom_read_manifest); parsing + aggregation stay pure here.
const FIXTURE_MANIFEST = JSON.stringify({
  version: 1,
  questions: [
    { slug: 'q1-test', status: 'completed' },
    { slug: 'q2-pending', status: 'pending' },
  ],
});

describe('parseManifest (pure)', () => {
  it('returns questions and version', () => {
    const m = parseManifest(FIXTURE_MANIFEST);
    expect(m.version).toBe(1);
    expect(m.questions).toHaveLength(2);
    expect(m.questions[0]).toEqual({ slug: 'q1-test', status: 'completed' });
    expect(m.questions[1]).toEqual({ slug: 'q2-pending', status: 'pending' });
  });

  it('rejects a manifest missing version or questions', () => {
    expect(() => parseManifest('{"version":1}', 'f')).toThrow(/missing version or questions/);
    expect(() => parseManifest('{"questions":[]}', 'f')).toThrow(/missing version or questions/);
  });
});

describe('aggregateStats (pure)', () => {
  it('counts completed, pending, and total', () => {
    expect(aggregateStats(parseManifest(FIXTURE_MANIFEST))).toEqual({ completed: 1, pending: 1, total: 2 });
  });

  it('ignores statuses that are neither completed nor pending, but still counts them in total', () => {
    const m = parseManifest('{"version":1,"questions":[{"slug":"a","status":"draft"}]}');
    expect(aggregateStats(m)).toEqual({ completed: 0, pending: 0, total: 1 });
  });
});

describe('readManifest', () => {
  beforeEach(() => invokeMock.mockReset());

  it('reads the folder through the command and parses it', async () => {
    invokeMock.mockResolvedValue(FIXTURE_MANIFEST);
    const m = await readManifest('/mom/questions/frq/descriptive-statistics');
    expect(invokeMock).toHaveBeenCalledWith('mom_read_manifest', {
      folder: '/mom/questions/frq/descriptive-statistics',
    });
    expect(m.questions).toHaveLength(2);
  });

  it('throws a clear error when manifest.json is missing (command returns null)', async () => {
    invokeMock.mockResolvedValue(null);
    await expect(readManifest('/mom/no-such-folder')).rejects.toThrow(/manifest/);
  });
});

describe('getFrqSetStats', () => {
  beforeEach(() => invokeMock.mockReset());

  it('reports completed, pending, and total counts', async () => {
    invokeMock.mockResolvedValue(FIXTURE_MANIFEST);
    expect(await getFrqSetStats('/mom/questions/frq/descriptive-statistics')).toEqual({
      completed: 1,
      pending: 1,
      total: 2,
    });
  });

  it('returns zeroed stats when the folder has no manifest', async () => {
    invokeMock.mockResolvedValue(null);
    expect(await getFrqSetStats('/mom/no-such-folder')).toEqual({ completed: 0, pending: 0, total: 0 });
  });
});

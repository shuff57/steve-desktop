import { describe, it, expect } from 'vitest';
import { join } from 'node:path';
import { getFrqSetStats, readManifest } from './manifest';

const FIXTURE_ROOT = join(__dirname, '__tests__', 'fixtures', 'mom');

describe('readManifest', () => {
  it('returns questions and version from a fixture manifest', async () => {
    const m = await readManifest(join(FIXTURE_ROOT, 'questions', 'frq', 'descriptive-statistics'));
    expect(m.version).toBe(1);
    expect(m.questions).toHaveLength(2);
    expect(m.questions[0]).toEqual({ slug: 'q1-test', status: 'completed' });
    expect(m.questions[1]).toEqual({ slug: 'q2-pending', status: 'pending' });
  });

  it('throws a clear error when manifest.json is missing', async () => {
    await expect(readManifest(join(FIXTURE_ROOT, 'no-such-folder'))).rejects.toThrow(/manifest/);
  });
});

describe('getFrqSetStats', () => {
  it('reports completed, pending, and total counts', async () => {
    const stats = await getFrqSetStats(join(FIXTURE_ROOT, 'questions', 'frq', 'descriptive-statistics'));
    expect(stats).toEqual({ completed: 1, pending: 1, total: 2 });
  });

  it('returns zeroed stats when the folder has no manifest', async () => {
    const stats = await getFrqSetStats(join(FIXTURE_ROOT, 'no-such-folder'));
    expect(stats).toEqual({ completed: 0, pending: 0, total: 0 });
  });
});

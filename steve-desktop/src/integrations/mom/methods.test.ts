import { describe, it, expect } from 'vitest';
import { join } from 'node:path';
import { momIsland } from './index';

const FIXTURE_ROOT = join(__dirname, '__tests__', 'fixtures', 'mom');

describe('momIsland.browse', () => {
  it('returns the family index for the fixture root', async () => {
    const idx = await momIsland.methods.browse(FIXTURE_ROOT);
    expect(idx.families).toHaveLength(1);
    expect(idx.families[0]!.name).toBe('frq');
  });
});

describe('momIsland.getQuestion', () => {
  it('returns the PHP file contents + manifest stats for a known question', async () => {
    const q = await momIsland.methods.getQuestion('frq', 'descriptive-statistics', FIXTURE_ROOT);
    expect(q.family).toBe('frq');
    expect(q.slug).toBe('descriptive-statistics');
    expect(q.contents).toContain('$questiontext');
    expect(q.contents).toContain('Find the mean');
    expect(q.manifest).toEqual({ completed: 1, pending: 1, total: 2 });
  });

  it('rejects unknown family', async () => {
    await expect(momIsland.methods.getQuestion('nope', 'whatever', FIXTURE_ROOT))
      .rejects.toThrow(/Unknown family/);
  });

  it('rejects unknown slug', async () => {
    await expect(momIsland.methods.getQuestion('frq', 'nope', FIXTURE_ROOT))
      .rejects.toThrow(/Unknown question/);
  });
});

describe('momIsland.getFamily', () => {
  it('returns family + questions + aggregate manifest stats', async () => {
    const fam = await momIsland.methods.getFamily('frq', FIXTURE_ROOT);
    expect(fam.name).toBe('frq');
    expect(fam.count).toBe(1);
    expect(fam.questions).toHaveLength(1);
    expect(fam.manifest.total).toBe(2);
  });
});

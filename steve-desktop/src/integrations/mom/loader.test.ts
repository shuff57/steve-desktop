import { describe, it, expect } from 'vitest';
import { join } from 'node:path';
import { loadMOMIndex, isJunkFamily } from './loader';

// Fixture: src/integrations/mom/__tests__/fixtures/mom/questions/frq/descriptive-statistics/q1-test.php
// + a manifest.json with two entries (one completed, one pending).
const FIXTURE_ROOT = join(__dirname, '__tests__', 'fixtures', 'mom');

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
  it('walks questions/<family>/ and returns families with questions', async () => {
    const index = await loadMOMIndex(FIXTURE_ROOT);
    expect(index.families).toHaveLength(1);
    const family = index.families[0]!;
    expect(family.name).toBe('frq');
    expect(family.count).toBe(1);
    expect(family.questions[0]).toMatchObject({
      slug: 'descriptive-statistics',
      hasManifest: true,
    });
    expect(family.questions[0]!.path).toContain('descriptive-statistics');
  });

  it('reports hasManifest as boolean for each question', async () => {
    const index = await loadMOMIndex(FIXTURE_ROOT);
    for (const f of index.families) {
      for (const q of f.questions) {
        expect(typeof q.hasManifest).toBe('boolean');
      }
    }
  });

  it('returns empty families array when questions dir is missing', async () => {
    // Use a non-existent root; loader must not throw.
    const index = await loadMOMIndex(join(FIXTURE_ROOT, '..', 'no-such-root'));
    expect(index.families).toEqual([]);
  });
});

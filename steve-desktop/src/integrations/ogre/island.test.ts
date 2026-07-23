import { describe, it, expect, vi } from 'vitest';

vi.mock('@tauri-apps/plugin-sql', () => ({
  default: { load: vi.fn().mockResolvedValue({ execute: vi.fn(), select: vi.fn() }) },
}));
vi.mock('@tauri-apps/api/core', () => ({ invoke: vi.fn() }));

import { ogreIsland } from './index';

describe('ogreIsland', () => {
  it('exposes an ogre island with the right id and label', () => {
    expect(ogreIsland.id).toBe('ogre');
    expect(ogreIsland.label).toBe('OGRE');
    expect(ogreIsland.enabled).toBe(true);
  });

  it('exposes the data-access surface phases 5 and 6 build on', () => {
    for (const m of [
      'listSiteProfiles',
      'getSiteProfile',
      'listRubrics',
      'getRubric',
      'addGradingSession',
      'listGradingSessions',
      'getBatchResume',
      'setBatchResume',
      'clearBatchResume',
      'gradeOne',
    ]) {
      expect(typeof ogreIsland.methods[m as keyof typeof ogreIsland.methods]).toBe('function');
    }
  });
});

import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { ogreIsland } from './index';
import { closeAllOgreDbs } from './db';

describe('ogreIsland', () => {
  // Each test calls openOgreDb on a unique in-memory path. Reset the
  // singleton between tests so the path-keyed cache doesn't leak.
  beforeEach(() => {
    closeAllOgreDbs();
  });
  afterEach(() => {
    closeAllOgreDbs();
  });

  it('exposes an ogre island with the right id and label', () => {
    expect(ogreIsland.id).toBe('ogre');
    expect(ogreIsland.label).toBe('OGRE');
    expect(ogreIsland.enabled).toBe(true);
  });

  it('exposes openOgreDb() that returns a Database with the canonical tables', () => {
    expect(typeof ogreIsland.methods.openOgreDb).toBe('function');
    const db = ogreIsland.methods.openOgreDb(':memory:');
    const names = (db
      .prepare("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
      .all() as { name: string }[]).map((row) => row.name);
    expect(names).toContain('site_profiles');
    expect(names).toContain('grading_sessions');
    expect(names).toContain('skills');
    expect(names).toContain('response_embeddings');
  });
});

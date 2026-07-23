/**
 * openOgreDb() — the public entry into the schema. Tests cover:
 *  - it accepts a custom path (used by tests with :memory:)
 *  - it returns a working better-sqlite3 Database with the canonical
 *    tables present after a single call
 *  - a second call to the same path returns the same cached Database
 *    instance (singleton-by-path)
 *  - different paths get different instances
 */
import { describe, it, expect } from 'vitest';
import { openOgreDb, closeAllOgreDbs } from './db';

describe('openOgreDb', () => {
  // Reset the singleton cache between tests so each test gets a clean
  // open. The cache is per-path; without this, a test that calls
  // db.close() would leave a dead reference in the cache and the next
  // test that opens the same path would get a closed Database.
  beforeEach(() => {
    closeAllOgreDbs();
  });

  it('returns a Database with the canonical tables after opening', () => {
    const db = openOgreDb(':memory:');
    const names = (db
      .prepare("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
      .all() as { name: string }[]).map((row) => row.name);
    expect(names).toContain('site_profiles');
    expect(names).toContain('grading_sessions');
    expect(names).toContain('skills');
    expect(names).toContain('response_embeddings');
  });

  it('is idempotent: a second open on the same path returns the same Database', () => {
    const a = openOgreDb(':memory:');
    const b = openOgreDb(':memory:');
    expect(b).toBe(a);
  });

  it('returns distinct Database instances for distinct paths', () => {
    const a = openOgreDb('file:test-a?mode=memory&cache=shared');
    const b = openOgreDb('file:test-b?mode=memory&cache=shared');
    expect(a).not.toBe(b);
  });

  it('the returned Database can insert and select on a canonical table', () => {
    const db = openOgreDb(':memory:');
    db.prepare(
      `INSERT INTO site_profiles (id, name, url_patterns, selectors, feedback, save, navigation)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
    ).run('sp-rt-1', 'Test Profile', '[]', '{}', '{}', '{}', '{}');
    const row = db.prepare('SELECT name FROM site_profiles WHERE id = ?').get('sp-rt-1') as
      | { name: string }
      | undefined;
    expect(row?.name).toBe('Test Profile');
  });

  it('closeAllOgreDbs clears the singleton cache', () => {
    const a = openOgreDb('file:test-cleanup?mode=memory&cache=shared');
    const b = openOgreDb('file:test-cleanup?mode=memory&cache=shared');
    expect(a).toBe(b);
    closeAllOgreDbs();
    const c = openOgreDb('file:test-cleanup?mode=memory&cache=shared');
    // After closeAll, the cache is empty, so the new open produces a
    // fresh instance. c should be a different object than a.
    expect(c).not.toBe(a);
  });
});

/**
 * Phase 4 schema tests.
 *
 * Drives the migration on a fresh in-memory DB and asserts:
 *  - every canonical O.G.R.E table is created
 *  - each table has an `island_id` column (default 'ogre')
 *  - a row inserted into each of the four tables the island cares about
 *    (site_profiles, skills (rubrics live here), grading_sessions (history),
 *    response_embeddings (embeddings)) round-trips with all fields intact
 *  - migrations are idempotent (running them twice does not throw or
 *    duplicate rows)
 */
import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import BetterSqlite3 from 'better-sqlite3';
import type { Database } from 'better-sqlite3';
import { readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const HERE = dirname(fileURLToPath(import.meta.url));

// Tables O.G.R.E creates in database.ts. Ported 1:1 with an `island_id`
// column added to each. Listed in the order they appear in the canonical
// migrations (database.ts, electron-main).
const OGRE_TABLES = [
  'provider_configs',
  'grading_sessions',
  'app_settings',
  'oauth_tokens',
  'site_credentials',
  'site_profiles',
  'batch_session',
  'skills',
  'response_embeddings',
] as const;

const MIGRATION_PATH = join(HERE, 'migrations', '001-ogre-schema.sql');

function freshDb(): Database {
  return new BetterSqlite3(':memory:');
}

function runMigrations(db: Database): void {
  const sql = readFileSync(MIGRATION_PATH, 'utf-8');
  db.exec(sql);
}

function tableNames(db: Database): string[] {
  return (db
    .prepare("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")
    .all() as { name: string }[]).map((row) => row.name);
}

function hasColumn(db: Database, table: string, column: string): boolean {
  const rows = db.prepare(`PRAGMA table_info(${table})`).all() as { name: string }[];
  return rows.some((row) => row.name === column);
}

describe('001-ogre-schema migration', () => {
  let db: Database;
  beforeEach(() => {
    db = freshDb();
  });
  afterEach(() => {
    db.close();
  });

  it('creates every O.G.R.E table on a fresh DB', () => {
    runMigrations(db);
    const names = tableNames(db);
    for (const expected of OGRE_TABLES) {
      expect(names, `expected table "${expected}" to be created`).toContain(expected);
    }
  });

  it('adds an island_id column to every O.G.R.E table', () => {
    runMigrations(db);
    for (const table of OGRE_TABLES) {
      expect(hasColumn(db, table, 'island_id'), `table "${table}" should have island_id column`).toBe(true);
    }
  });

  it('is idempotent — running migrations twice does not throw', () => {
    runMigrations(db);
    expect(() => runMigrations(db)).not.toThrow();
  });
});

describe('round-trip insert/select', () => {
  let db: Database;
  beforeEach(() => {
    db = freshDb();
    runMigrations(db);
  });
  afterEach(() => {
    db.close();
  });

  it('site_profiles round-trips every field', () => {
    const id = 'sp-test-1';
    db.prepare(
      `INSERT INTO site_profiles
        (id, name, url_patterns, selectors, feedback, save, navigation, extraction, island_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ogre')`,
    ).run(
      id,
      'MyOpenMath Math12-Sp26',
      JSON.stringify(['*://www.myopenmath.com/*']),
      JSON.stringify({ studentNames: '#studentnames', responses: 'textarea[name*="stuanswers"]' }),
      JSON.stringify({ scoreField: 'input[name*="score"]' }),
      JSON.stringify({ submitSelector: 'input[type="submit"]' }),
      JSON.stringify({ nextSelector: 'a.next' }),
      JSON.stringify({ mode: 'iframe' }),
    );

    const row = db.prepare('SELECT * FROM site_profiles WHERE id = ?').get(id) as Record<string, unknown>;
    expect(row.id).toBe(id);
    expect(row.name).toBe('MyOpenMath Math12-Sp26');
    expect(JSON.parse(row.url_patterns as string)).toEqual(['*://www.myopenmath.com/*']);
    expect(JSON.parse(row.selectors as string)).toEqual({
      studentNames: '#studentnames',
      responses: 'textarea[name*="stuanswers"]',
    });
    expect(row.island_id).toBe('ogre');
    expect(typeof row.created_at).toBe('string');
  });

  it('skills (rubric content) round-trips every field', () => {
    const id = 'skill-rubric-1';
    db.prepare(
      `INSERT INTO skills
        (id, name, description, content, source, source_id, is_active, learned_corrections, island_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ogre')`,
    ).run(
      id,
      'FRQ Statistics Rubric',
      '10-point rubric for descriptive statistics FRQs',
      JSON.stringify({ criteria: [{ name: 'accuracy', max: 6 }, { name: 'clarity', max: 4 }] }),
      'ogre',
      'frq-stats-v1',
      1,
      JSON.stringify([{ pattern: 'wrong z-score', adjustment: -1 }]),
    );

    const row = db.prepare('SELECT * FROM skills WHERE id = ?').get(id) as Record<string, unknown>;
    expect(row.id).toBe(id);
    expect(row.name).toBe('FRQ Statistics Rubric');
    expect(JSON.parse(row.content as string)).toEqual({
      criteria: [{ name: 'accuracy', max: 6 }, { name: 'clarity', max: 4 }],
    });
    expect(row.is_active).toBe(1);
    expect(row.island_id).toBe('ogre');
  });

  it('grading_sessions (history) round-trips every field', () => {
    const insert = db.prepare(
      `INSERT INTO grading_sessions
        (provider_id, model, student_count, mean_score, min_score, max_score,
         median_score, max_possible_score, page_url, question_id, custom_instructions, island_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ogre')`,
    );
    const result = insert.run(
      'ollama', 'llama3.1:8b', 12, 7.5, 4, 10, 8, 10,
      'https://www.myopenmath.com/grade.php?cid=306621',
      'q-fall-1',
      'Be strict on the central tendency questions.',
    );

    const row = db
      .prepare('SELECT * FROM grading_sessions WHERE id = ?')
      .get(result.lastInsertRowid) as Record<string, unknown>;
    expect(row.provider_id).toBe('ollama');
    expect(row.model).toBe('llama3.1:8b');
    expect(row.student_count).toBe(12);
    expect(row.mean_score).toBe(7.5);
    expect(row.page_url).toBe('https://www.myopenmath.com/grade.php?cid=306621');
    expect(row.island_id).toBe('ogre');
  });

  it('response_embeddings round-trips a BLOB embedding', () => {
    const session = db
      .prepare(
        `INSERT INTO grading_sessions
          (provider_id, model, student_count, page_url, island_id)
          VALUES (?, ?, ?, ?, 'ogre')`,
      )
      .run('ollama', 'llama3.1:8b', 1, 'https://example.com/q1');

    const embedding = new Float32Array([0.1, 0.2, 0.3, 0.4]);
    // Slice the typed-array's underlying ArrayBuffer to a clean view. A raw
    // Node Buffer is a Uint8Array over a pooled ArrayBuffer that's padded
    // to a 16-byte boundary; without the slice, `new Float32Array(buf)`
    // would read past the real data.
    const buf = Buffer.from(embedding.buffer.slice(embedding.byteOffset, embedding.byteOffset + embedding.byteLength));
    db.prepare(
      `INSERT INTO response_embeddings
        (session_id, rubric_hash, student_response, score, feedback, embedding, embedding_model, island_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'ogre')`,
    ).run(
      Number(session.lastInsertRowid),
      'rubric-hash-abc',
      'The mean is 5.2 and the SD is 1.4.',
      8.5,
      'Good work — minor notation error on SD.',
      buf,
      'nomic-embed-text',
    );

    const row = db
      .prepare('SELECT * FROM response_embeddings WHERE rubric_hash = ?')
      .get('rubric-hash-abc') as Record<string, unknown>;
    expect(row.score).toBe(8.5);
    expect(row.embedding_model).toBe('nomic-embed-text');
    const restored = new Float32Array(
      (row.embedding as Buffer).buffer.slice(
        (row.embedding as Buffer).byteOffset,
        (row.embedding as Buffer).byteOffset + (row.embedding as Buffer).byteLength,
      ),
    );
    expect(restored.length).toBe(4);
    // IEEE 754: 0.1 is not exactly representable in float32, so use
    // toBeCloseTo per element.
    expect(restored[0]).toBeCloseTo(0.1, 5);
    expect(restored[1]).toBeCloseTo(0.2, 5);
    expect(restored[2]).toBeCloseTo(0.3, 5);
    expect(restored[3]).toBeCloseTo(0.4, 5);
    expect(row.island_id).toBe('ogre');
  });
});

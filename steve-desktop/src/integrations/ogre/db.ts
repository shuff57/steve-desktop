/**
 * openOgreDb() — the typed accessor into the O.G.R.E SQLite schema.
 *
 * Path-resolution rules:
 *  - explicit `path` argument wins (used by tests with `:memory:` or
 *    shared-memory URIs)
 *  - otherwise defaults to `~/.steve/ogre.db` per the integration plan
 *
 * The Database is cached by path. Calling openOgreDb with the same path
 * twice returns the same instance. Tests that need a fresh state should
 * use a unique path (`:memory:` is unique-per-call in better-sqlite3, so
 * re-using it returns the cached instance — use `closeAllOgreDbs()` to
 * reset between tests).
 *
 * Migrations live at ./migrations/*.sql and are applied in lexical order
 * the first time a path is opened. Every migration is idempotent
 * (CREATE TABLE IF NOT EXISTS), so re-applying is a no-op.
 */
import BetterSqlite3 from 'better-sqlite3';
import type { Database } from 'better-sqlite3';
import { readFileSync, readdirSync, mkdirSync } from 'node:fs';
import { homedir } from 'node:os';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const HERE = dirname(fileURLToPath(import.meta.url));
const MIGRATIONS_DIR = join(HERE, 'migrations');

const cache = new Map<string, Database>();

function defaultDbPath(): string {
  return join(homedir(), '.steve', 'ogre.db');
}

function applyMigrations(db: Database): void {
  const files = readdirSync(MIGRATIONS_DIR)
    .filter((name) => name.endsWith('.sql'))
    .sort();
  for (const file of files) {
    const sql = readFileSync(join(MIGRATIONS_DIR, file), 'utf-8');
    db.exec(sql);
  }
}

export interface OpenOgreDbOptions {
  /** Override the database path. Defaults to ~/.steve/ogre.db. */
  path?: string;
  /**
   * If true and the path's parent directory does not exist, create it.
   * Defaults to true for non-`:memory:` paths so the file-based singleton
   * works out of the box on first launch.
   */
  ensureDir?: boolean;
}

export function openOgreDb(pathOrOpts?: string | OpenOgreDbOptions): Database {
  const opts: OpenOgreDbOptions =
    typeof pathOrOpts === 'string' ? { path: pathOrOpts } : pathOrOpts ?? {};
  const path = opts.path ?? defaultDbPath();

  const cached = cache.get(path);
  if (cached) return cached;

  if (opts.ensureDir !== false && path !== ':memory:' && !path.startsWith('file:')) {
    mkdirSync(dirname(path), { recursive: true });
  }

  const db = new BetterSqlite3(path);
  applyMigrations(db);
  cache.set(path, db);
  return db;
}

/**
 * Close every cached Database and clear the cache. Tests use this to
 * reset state between cases. Production code should rarely call this.
 */
export function closeAllOgreDbs(): void {
  for (const db of cache.values()) {
    db.close();
  }
  cache.clear();
}

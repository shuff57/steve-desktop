-- 001-ogre-schema.sql
--
-- O.G.R.E SQLite schema, ported 1:1 from
--   O.G.R.E-OllamaGradingRubricEvaluator/ogre-desktop/electron-main/database.ts
-- with one change: every table gains an `island_id TEXT NOT NULL DEFAULT 'ogre'`
-- column so future islands can share the same database without colliding.
--
-- Idempotent: every CREATE uses IF NOT EXISTS. The migration is safe to
-- apply against a fresh DB and an existing one. There is no _migrations
-- version table — the SQL itself is the source of truth, and re-applying
-- is a no-op. Add a version table if a future phase needs to gate
-- destructive changes.
--
-- The 12 O.G.R.E migrations (database.ts) are flattened here. The ALTER
-- TABLE ADD COLUMN statements from migrations 9, 11, and 12 are inlined
-- into the initial CREATE TABLE for each affected table.

PRAGMA journal_mode = WAL;

-- migration 1: provider_configs
CREATE TABLE IF NOT EXISTS provider_configs (
  id TEXT PRIMARY KEY NOT NULL,
  api_url TEXT,
  api_key TEXT,
  model TEXT,
  is_active INTEGER NOT NULL DEFAULT 0,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  updated_at TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);

-- migration 2: grading_sessions (the canonical grading history)
CREATE TABLE IF NOT EXISTS grading_sessions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  provider_id TEXT,
  model TEXT,
  student_count INTEGER,
  mean_score REAL,
  min_score REAL,
  max_score REAL,
  median_score REAL,
  max_possible_score REAL,
  page_url TEXT,
  question_id TEXT,
  custom_instructions TEXT,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);

-- migration 3: app_settings (key/value with seeded defaults)
CREATE TABLE IF NOT EXISTS app_settings (
  key TEXT PRIMARY KEY NOT NULL,
  value TEXT,
  island_id TEXT NOT NULL DEFAULT 'ogre'
);
INSERT OR IGNORE INTO app_settings (key, value) VALUES ('setup_complete', 'false');
INSERT OR IGNORE INTO app_settings (key, value) VALUES ('history_visible_columns', '["timestamp","provider","model","studentCount","meanScore","pageUrl"]');

-- migration 4: oauth_tokens
CREATE TABLE IF NOT EXISTS oauth_tokens (
  provider TEXT PRIMARY KEY NOT NULL,
  access_token TEXT NOT NULL,
  refresh_token TEXT,
  token_type TEXT,
  expires_at INTEGER,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  updated_at TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);

-- migration 5: site_credentials
CREATE TABLE IF NOT EXISTS site_credentials (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  site_name TEXT NOT NULL,
  url_pattern TEXT NOT NULL,
  username TEXT NOT NULL,
  password TEXT NOT NULL,
  notes TEXT,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  updated_at TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);

-- migration 6 + 11: site_profiles (with extraction column inlined)
CREATE TABLE IF NOT EXISTS site_profiles (
  id TEXT PRIMARY KEY NOT NULL,
  name TEXT NOT NULL,
  url_patterns TEXT NOT NULL,
  selectors TEXT NOT NULL,
  feedback TEXT NOT NULL,
  save TEXT NOT NULL,
  navigation TEXT NOT NULL,
  extraction TEXT DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  updated_at TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);

-- migration 7: batch_session (last student processed per URL)
CREATE TABLE IF NOT EXISTS batch_session (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  url TEXT NOT NULL,
  last_student_name TEXT NOT NULL,
  timestamp TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);
CREATE UNIQUE INDEX IF NOT EXISTS idx_batch_session_url ON batch_session(url);

-- migration 8 + 9 + 12: skills (with url_pattern and learned_corrections
-- inlined). Rubrics live in this table — content is the rubric JSON, and
-- the existing O.G.R.E agent-skills surface uses the same row shape.
CREATE TABLE IF NOT EXISTS skills (
  id TEXT PRIMARY KEY NOT NULL,
  name TEXT NOT NULL,
  description TEXT NOT NULL DEFAULT '',
  content TEXT NOT NULL DEFAULT '',
  source TEXT,
  source_id TEXT,
  is_active INTEGER NOT NULL DEFAULT 0,
  url_pattern TEXT,
  learned_corrections TEXT DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  updated_at TEXT NOT NULL DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);
CREATE UNIQUE INDEX IF NOT EXISTS idx_skills_source ON skills(source, source_id) WHERE source IS NOT NULL;

-- migration 10: response_embeddings (BLOB embedding per graded response)
CREATE TABLE IF NOT EXISTS response_embeddings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  session_id INTEGER REFERENCES grading_sessions(id),
  rubric_hash TEXT NOT NULL,
  student_response TEXT,
  score REAL NOT NULL,
  feedback TEXT,
  embedding BLOB NOT NULL,
  embedding_model TEXT NOT NULL,
  created_at TEXT DEFAULT (datetime('now')),
  island_id TEXT NOT NULL DEFAULT 'ogre'
);
CREATE INDEX IF NOT EXISTS idx_embeddings_rubric_hash ON response_embeddings(rubric_hash);
CREATE INDEX IF NOT EXISTS idx_embeddings_model ON response_embeddings(embedding_model);

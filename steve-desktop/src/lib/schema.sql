-- Migration 1: OAuth tokens for AI provider authentication
CREATE TABLE IF NOT EXISTS oauth_tokens (
  provider TEXT PRIMARY KEY,
  access_token TEXT NOT NULL,
  refresh_token TEXT,
  token_type TEXT,
  expires_at INTEGER,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

-- Migration 2: AI provider configurations (API URLs, keys, models)
CREATE TABLE IF NOT EXISTS provider_configs (
  id TEXT PRIMARY KEY,
  api_url TEXT,
  api_key TEXT,
  model TEXT,
  is_active INTEGER DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

-- Migration 3: Application settings (key-value store)
CREATE TABLE IF NOT EXISTS app_settings (
  key TEXT PRIMARY KEY,
  value TEXT
);
INSERT OR IGNORE INTO app_settings (key, value) VALUES ('setup_complete', 'false');

-- This file documents the current shape of steve.db. It is NOT executed at runtime —
-- the migrations that actually build the DB live in src-tauri/src/lib.rs. Keep the two
-- in sync: a change here needs a matching numbered Migration there.

-- Migration 4 (+9): Agent skills (local, marketplace, created, ogre-authored)
-- Widened in 2026-07 to fold in the ogre-island's rows. The last three columns arrive
-- via ALTER TABLE in migration 9 for existing DBs; shown inline here as the final shape.
CREATE TABLE IF NOT EXISTS skills (
  id TEXT PRIMARY KEY,
  name TEXT NOT NULL,
  description TEXT,
  content TEXT NOT NULL,
  source TEXT NOT NULL DEFAULT 'local',
  is_active INTEGER DEFAULT 1,
  url_pattern TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  source_id TEXT,
  learned_corrections TEXT,
  updated_at TEXT DEFAULT (datetime('now'))
);
CREATE UNIQUE INDEX IF NOT EXISTS idx_skills_source ON skills(source, source_id) WHERE source_id IS NOT NULL;

-- Migration 5 (+9): Site profiles (ogre-shaped structured grading config)
-- Originally a flat (domain, page_name, profile_json) cache; replaced 2026-07 with
-- ogre's structured shape so the ogre-island can write grading configs into the same
-- table the rest of the app reads. The old columns had zero production consumers
-- (only test fixtures), so migration 9 drops and recreates. On-disk JSON profiles in
-- `~/.agents/site-profiles/` are unaffected (they live in site-profiles.ts, not here).
CREATE TABLE IF NOT EXISTS site_profiles (
  id TEXT PRIMARY KEY,
  name TEXT NOT NULL,
  url_patterns TEXT NOT NULL,
  selectors TEXT NOT NULL,
  feedback TEXT NOT NULL,
  save TEXT NOT NULL,
  navigation TEXT NOT NULL,
  extraction TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

-- Migration 6: Saved site credentials for local autofill (never synced to cloud)
CREATE TABLE IF NOT EXISTS site_credentials (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  site_name TEXT NOT NULL,
  url_pattern TEXT NOT NULL,
  username TEXT NOT NULL,
  password TEXT NOT NULL,
  notes TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

-- Migration 7: Saved bookmarks (quick-nav to sites)
CREATE TABLE IF NOT EXISTS bookmarks (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  url TEXT NOT NULL UNIQUE,
  created_at TEXT DEFAULT (datetime('now'))
);

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

-- Migration 4: Agent skills (local, marketplace, created)
CREATE TABLE IF NOT EXISTS skills (
  id TEXT PRIMARY KEY,
  name TEXT NOT NULL,
  description TEXT,
  content TEXT NOT NULL,
  source TEXT NOT NULL DEFAULT 'local',
  is_active INTEGER DEFAULT 1,
  url_pattern TEXT,
  created_at TEXT DEFAULT (datetime('now'))
);

-- Migration 5: Site profiles (JSON metadata for profiled pages)
CREATE TABLE IF NOT EXISTS site_profiles (
  id TEXT PRIMARY KEY,
  domain TEXT NOT NULL,
  page_name TEXT NOT NULL,
  profile_json TEXT NOT NULL,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now')),
  UNIQUE(domain, page_name)
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

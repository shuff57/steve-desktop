/**
 * Type definitions matching the O.G.R.E schema in
 * `./migrations/001-ogre-schema.sql`. These shapes are what the rest of
 * the app (and the grading server in phase 5) sees when reading from or
 * writing to the O.G.R.E tables.
 *
 * Conventions:
 *  - Every row type includes `island_id` because the schema namespace
 *    requires it.
 *  - The `*Insert` type omits columns that the schema fills in itself
 *    (`id AUTOINCREMENT`, `created_at`, `updated_at`, `island_id` with
 *    a default of 'ogre').
 *  - JSON-encoded columns are typed as `string` here. The accessor
 *    layer (added in phase 5) will parse/serialize at the boundary so
 *    the rest of the app sees objects, not strings.
 *
 * `ISLAND_IDS` is the runtime list of valid island_id values. Exported
 * for validation in phases 5/6 (e.g., when an island_id arrives in a
 * payload from another island).
 */

/** The full set of island_ids this island can produce. */
export const ISLAND_IDS = ['ogre'] as const;
export type IslandId = (typeof ISLAND_IDS)[number];

/** The O.G.R.E default island_id. */
export const OGRE_ISLAND_ID: IslandId = 'ogre';

// ---------------------------------------------------------------------------
// provider_configs
// ---------------------------------------------------------------------------

export interface ProviderConfig {
  id: string;
  api_url: string | null;
  api_key: string | null;
  model: string | null;
  is_active: 0 | 1;
  created_at: string;
  updated_at: string;
  island_id: IslandId;
}

export interface ProviderConfigInsert {
  id: string;
  api_url?: string | null;
  api_key?: string | null;
  model?: string | null;
  is_active?: 0 | 1;
}

// ---------------------------------------------------------------------------
// grading_sessions  (the "grading history")
// ---------------------------------------------------------------------------

export interface GradingSession {
  id: number;
  provider_id: string | null;
  model: string | null;
  student_count: number | null;
  mean_score: number | null;
  min_score: number | null;
  max_score: number | null;
  median_score: number | null;
  max_possible_score: number | null;
  page_url: string | null;
  question_id: string | null;
  custom_instructions: string | null;
  created_at: string;
  island_id: IslandId;
}

export interface GradingSessionInsert {
  provider_id?: string | null;
  model?: string | null;
  student_count?: number | null;
  mean_score?: number | null;
  min_score?: number | null;
  max_score?: number | null;
  median_score?: number | null;
  max_possible_score?: number | null;
  page_url?: string | null;
  question_id?: string | null;
  custom_instructions?: string | null;
}

// ---------------------------------------------------------------------------
// app_settings
// ---------------------------------------------------------------------------

export interface AppSetting {
  key: string;
  value: string | null;
  island_id: IslandId;
}

export interface AppSettingInsert {
  key: string;
  value?: string | null;
}

// ---------------------------------------------------------------------------
// oauth_tokens
// ---------------------------------------------------------------------------

export interface OAuthToken {
  provider: string;
  access_token: string;
  refresh_token: string | null;
  token_type: string | null;
  expires_at: number | null;
  created_at: string;
  updated_at: string;
  island_id: IslandId;
}

export interface OAuthTokenInsert {
  provider: string;
  access_token: string;
  refresh_token?: string | null;
  token_type?: string | null;
  expires_at?: number | null;
}

// ---------------------------------------------------------------------------
// site_credentials
// ---------------------------------------------------------------------------

export interface SiteCredential {
  id: number;
  site_name: string;
  url_pattern: string;
  username: string;
  password: string;
  notes: string | null;
  created_at: string;
  updated_at: string;
  island_id: IslandId;
}

export interface SiteCredentialInsert {
  site_name: string;
  url_pattern: string;
  username: string;
  password: string;
  notes?: string | null;
}

// ---------------------------------------------------------------------------
// site_profiles
// ---------------------------------------------------------------------------

/**
 * One profile = a "shape" for a grading site: the URL match, the DOM
 * selectors for student names + responses, the selectors that drive
 * writing scores back, and a navigation pattern. JSON columns are
 * stored as TEXT in the schema; the typed layer exposes them as raw
 * `string` for now (parse in the app layer when you need the shape).
 */
export interface SiteProfile {
  id: string;
  name: string;
  /** JSON array of URL glob patterns. */
  url_patterns: string;
  /** JSON object: { studentNames, responses, ... }. */
  selectors: string;
  /** JSON object: where the score field is and how to fill it. */
  feedback: string;
  /** JSON object: how to save/submit the score. */
  save: string;
  /** JSON object: how to advance to the next student. */
  navigation: string;
  /** Optional JSON object: extraction strategy. */
  extraction: string | null;
  created_at: string;
  updated_at: string;
  island_id: IslandId;
}

export interface SiteProfileInsert {
  id: string;
  name: string;
  url_patterns: string;
  selectors: string;
  feedback: string;
  save: string;
  navigation: string;
  extraction?: string | null;
}

// ---------------------------------------------------------------------------
// batch_session
// ---------------------------------------------------------------------------

export interface BatchSession {
  id: number;
  url: string;
  last_student_name: string;
  timestamp: string;
  island_id: IslandId;
}

export interface BatchSessionInsert {
  url: string;
  last_student_name: string;
}

// ---------------------------------------------------------------------------
// skills  (rubrics live here; the O.G.R.E agent-skills surface reuses
// this row shape, with `content` holding the rubric JSON)
// ---------------------------------------------------------------------------

export interface Skill {
  id: string;
  name: string;
  description: string;
  /** JSON body. For rubrics, this is the criteria array. */
  content: string;
  source: string | null;
  source_id: string | null;
  is_active: 0 | 1;
  url_pattern: string | null;
  /** JSON array of corrections learned from past grading. */
  learned_corrections: string | null;
  created_at: string;
  updated_at: string;
  island_id: IslandId;
}

export interface SkillInsert {
  id: string;
  name: string;
  description?: string;
  content?: string;
  source?: string | null;
  source_id?: string | null;
  is_active?: 0 | 1;
  url_pattern?: string | null;
  learned_corrections?: string | null;
}

// ---------------------------------------------------------------------------
// response_embeddings
// ---------------------------------------------------------------------------

export interface ResponseEmbedding {
  id: number;
  session_id: number | null;
  rubric_hash: string;
  student_response: string | null;
  score: number;
  feedback: string | null;
  embedding: Buffer;
  embedding_model: string;
  created_at: string | null;
  island_id: IslandId;
}

export interface ResponseEmbeddingInsert {
  session_id?: number | null;
  rubric_hash: string;
  student_response?: string | null;
  score: number;
  feedback?: string | null;
  embedding: Buffer;
  embedding_model: string;
}

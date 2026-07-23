/**
 * Type definitions for the ogre island's tables, which live in steve.db —
 * see migrations 9 and 10 in `src-tauri/src/lib.rs`.
 *
 * Conventions:
 *  - The `*Insert` type omits columns the schema fills in itself
 *    (`id AUTOINCREMENT`, `created_at`, `updated_at`).
 *  - JSON-encoded columns are typed as `string` here. `./db.ts` parses and
 *    serializes at the boundary so the rest of the app sees objects.
 *
 * O.G.R.E's `island_id` column is not carried over. It was constant 'ogre' on
 * every row of the ogre-only tables, and in the tables now shared with steve
 * (skills, site_profiles) it would have mislabelled steve's own rows.
 * `skills.source = 'rubric'` is what marks a rubric.
 */


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

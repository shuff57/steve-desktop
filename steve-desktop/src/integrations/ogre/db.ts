/**
 * ogre-island data access.
 *
 * The island's tables live in steve.db, reached through tauri-plugin-sql. The
 * original port opened its own `~/.steve/ogre.db` with better-sqlite3, which cannot
 * work: better-sqlite3 and `node:fs` are Node APIs and this code runs in a WebView.
 * (The mom island hit the same wall and moved its filesystem into Rust.) The schema
 * now ships as migrations 9 and 10 in src-tauri/src/lib.rs.
 *
 * Rubrics live in `skills` with `source = 'rubric'` — that column, not a separate
 * island_id, is what separates them from steve's own skills.
 */
import { openSteveDb, saveSkill } from '../../lib/db';
import type { ImportedRubric } from './import-rubric';
import type { GradingSession, GradingSessionInsert, SiteProfile, Skill } from './types';

/** Rubric rows are skills tagged with this source. */
export const RUBRIC_SOURCE = 'rubric';

const SITE_PROFILE_COLUMNS =
  'id, name, url_patterns, selectors, feedback, save, navigation, extraction, created_at, updated_at';
const SKILL_COLUMNS =
  'id, name, description, content, source, source_id, is_active, url_pattern, learned_corrections, created_at, updated_at';

export async function listSiteProfiles(): Promise<SiteProfile[]> {
  const db = await openSteveDb();
  return db.select<SiteProfile[]>(`SELECT ${SITE_PROFILE_COLUMNS} FROM site_profiles ORDER BY name`);
}

export async function getSiteProfile(id: string): Promise<SiteProfile | null> {
  const db = await openSteveDb();
  const rows = await db.select<SiteProfile[]>(
    `SELECT ${SITE_PROFILE_COLUMNS} FROM site_profiles WHERE id = $1`,
    [id],
  );
  return rows[0] ?? null;
}

export async function listRubrics(): Promise<Skill[]> {
  const db = await openSteveDb();
  return db.select<Skill[]>(`SELECT ${SKILL_COLUMNS} FROM skills WHERE source = $1 ORDER BY name`, [
    RUBRIC_SOURCE,
  ]);
}

export async function getRubric(id: string): Promise<Skill | null> {
  const db = await openSteveDb();
  const rows = await db.select<Skill[]>(
    `SELECT ${SKILL_COLUMNS} FROM skills WHERE id = $1 AND source = $2`,
    [id, RUBRIC_SOURCE],
  );
  return rows[0] ?? null;
}

/**
 * Store an imported rubric. The id is derived from the question's identity, so
 * re-importing the same question overwrites its rubric instead of stacking near-duplicates
 * in the list. Returns the skills row id.
 */
export async function saveImportedRubric(r: ImportedRubric): Promise<string> {
  const id = `ogre-rubric:${r.sourceId}`;
  await saveSkill({
    id,
    name: r.name,
    description: `Imported from ${r.sourceId}`,
    content: JSON.stringify(r.rubric, null, 2),
    source: RUBRIC_SOURCE,
    source_id: r.sourceId,
  });
  return id;
}

/** Append a completed batch run to the grading history. Returns its row id. */
export async function addGradingSession(s: GradingSessionInsert): Promise<number> {
  const db = await openSteveDb();
  const res = await db.execute(
    `INSERT INTO grading_sessions
       (provider_id, model, student_count, mean_score, min_score, max_score, median_score,
        max_possible_score, page_url, question_id, custom_instructions)
     VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)`,
    [
      s.provider_id ?? null,
      s.model ?? null,
      s.student_count ?? null,
      s.mean_score ?? null,
      s.min_score ?? null,
      s.max_score ?? null,
      s.median_score ?? null,
      s.max_possible_score ?? null,
      s.page_url ?? null,
      s.question_id ?? null,
      s.custom_instructions ?? null,
    ],
  );
  return Number(res.lastInsertId);
}

export async function listGradingSessions(limit = 50): Promise<GradingSession[]> {
  const db = await openSteveDb();
  return db.select<GradingSession[]>(
    `SELECT id, provider_id, model, student_count, mean_score, min_score, max_score,
            median_score, max_possible_score, page_url, question_id, custom_instructions, created_at
       FROM grading_sessions ORDER BY id DESC LIMIT $1`,
    [limit],
  );
}

/**
 * Resume marker: the last student processed for a URL, so a re-run picks up where it
 * stopped instead of re-grading (and re-submitting) work already done.
 */
export async function getBatchResume(url: string): Promise<string | null> {
  const db = await openSteveDb();
  const rows = await db.select<{ last_student_name: string }[]>(
    'SELECT last_student_name FROM batch_session WHERE url = $1',
    [url],
  );
  return rows[0]?.last_student_name ?? null;
}

export async function setBatchResume(url: string, lastStudentName: string): Promise<void> {
  const db = await openSteveDb();
  await db.execute(
    `INSERT INTO batch_session (url, last_student_name, timestamp)
     VALUES ($1, $2, datetime('now'))
     ON CONFLICT(url) DO UPDATE SET last_student_name = $2, timestamp = datetime('now')`,
    [url, lastStudentName],
  );
}

export async function clearBatchResume(url: string): Promise<void> {
  const db = await openSteveDb();
  await db.execute('DELETE FROM batch_session WHERE url = $1', [url]);
}

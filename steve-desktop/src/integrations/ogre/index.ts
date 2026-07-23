/**
 * ogre-island — adopts the O.G.R.E grading-server (providers, batch loop,
 * SSE streaming) and SQLite schema (site-profiles, rubrics, history).
 *
 * Phase 4 (this commit) adds the SQLite schema and typed accessor:
 *  - openOgreDb() returns a better-sqlite3 Database with the canonical
 *    O.G.R.E tables, namespaced by `island_id = 'ogre'`.
 *
 * Phases 5 and 6 will add:
 *  - loadStudents(profile)         -> { name, responseText }[]
 *  - gradeBatch(input)             -> AsyncIterable<GradingEvent>
 *  - listRubrics() / getRubric(id) -> Skill  (rubrics live in `skills`)
 *  - listSiteProfiles() / getSiteProfile(id)
 */
import type { Database } from 'better-sqlite3';
import { defineIsland } from '../_shared/island';
import { openOgreDb as openOgreDbImpl } from './db';

export interface OgreMethods {
  /**
   * Open the O.G.R.E SQLite database. The path defaults to
   * `~/.steve/ogre.db`; pass `:memory:` (or any other path) to override.
   * The Database is cached by path — a second call with the same path
   * returns the same instance.
   */
  openOgreDb(path?: string): Database;
}

export const ogreIsland = defineIsland<OgreMethods>({
  id: 'ogre',
  label: 'OGRE',
  methods: {
    openOgreDb: (path) => openOgreDbImpl(path),
  },
});

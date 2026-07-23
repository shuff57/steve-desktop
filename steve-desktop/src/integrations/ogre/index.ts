/**
 * ogre-island — adopts the O.G.R.E grading-server (providers, batch loop,
 * streaming) and its SQLite schema (site-profiles, rubrics, history).
 *
 * The tables live in steve.db via tauri-plugin-sql; see ./db.ts for why the
 * original better-sqlite3 accessor could not survive the port.
 *
 * Phase 5 will add:
 *  - loadStudents(profile) -> { name, responseText }[]
 *  - gradeBatch(input)     -> AsyncIterable<GradingEvent>
 */
import { defineIsland } from '../_shared/island';
import {
  addGradingSession,
  clearBatchResume,
  getBatchResume,
  getRubric,
  getSiteProfile,
  listGradingSessions,
  listRubrics,
  listSiteProfiles,
  setBatchResume,
} from './db';
import type { GradingSession, GradingSessionInsert, SiteProfile, Skill } from './types';

export interface OgreMethods {
  listSiteProfiles(): Promise<SiteProfile[]>;
  getSiteProfile(id: string): Promise<SiteProfile | null>;
  /** Rubrics are `skills` rows with `source = 'rubric'`. */
  listRubrics(): Promise<Skill[]>;
  getRubric(id: string): Promise<Skill | null>;
  addGradingSession(s: GradingSessionInsert): Promise<number>;
  listGradingSessions(limit?: number): Promise<GradingSession[]>;
  /** Resume marker so a re-run skips students already graded and submitted. */
  getBatchResume(url: string): Promise<string | null>;
  setBatchResume(url: string, lastStudentName: string): Promise<void>;
  clearBatchResume(url: string): Promise<void>;
}

export const ogreIsland = defineIsland<OgreMethods>({
  id: 'ogre',
  label: 'OGRE',
  methods: {
    listSiteProfiles,
    getSiteProfile,
    listRubrics,
    getRubric,
    addGradingSession,
    listGradingSessions,
    getBatchResume,
    setBatchResume,
    clearBatchResume,
  },
});

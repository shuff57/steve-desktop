/**
 * ogre-island — adopts the O.G.R.E grading-server (providers, batch loop,
 * streaming) and its SQLite schema (site-profiles, rubrics, history).
 *
 * The tables live in steve.db via tauri-plugin-sql; see ./db.ts for why the
 * original better-sqlite3 accessor could not survive the port.
 *
 * Phase 5: loadStudents() scrapes a MyOpenMath grading page, gradeOne()/gradeBatch()
 * grade through the CLI behind the redaction gate.
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
  saveImportedRubric,
  setBatchResume,
} from './db';
import { gradeBatch, gradeOne, generateAnchorExamples, reviewOutliers } from './grade';
import type { ReviewOutcome } from './grade';
import type { AnchorExample } from './anchors';
import { matchProfile, profileFromRow } from './load-students';
import type { ExtractionProfile } from './load-students';
import { importRubricFromPage } from './import-rubric';
import type { ImportedRubric } from './import-rubric';
import { gradeableFrom, loadStudents, toGradingStudents } from './load-students';
import type { ExtractedStudent, LoadOptions } from './load-students';
import type { GradeProvider, GradingEvent, Student } from './grade';
import type { BatchResult } from './batch';
import type { GradeResult, Rubric } from './grading';
import type { GradingSession, GradingSessionInsert, SiteProfile, Skill } from './types';

export interface OgreMethods {
  listSiteProfiles(): Promise<SiteProfile[]>;
  getSiteProfile(id: string): Promise<SiteProfile | null>;
  /** Rubrics are `skills` rows with `source = 'rubric'`. */
  listRubrics(): Promise<Skill[]>;
  getRubric(id: string): Promise<Skill | null>;
  /**
   * Build a rubric from the MyOpenMath question the browser is on. Read-only: it
   * evaluates one expression and writes nothing back to the page.
   */
  importRubricFromPage(evaluate: (expression: string) => Promise<unknown>): Promise<ImportedRubric>;
  /** Persist an imported rubric, keyed on the question so a re-import updates in place. */
  saveImportedRubric(r: ImportedRubric): Promise<string>;
  addGradingSession(s: GradingSessionInsert): Promise<number>;
  listGradingSessions(limit?: number): Promise<GradingSession[]>;
  /** Resume marker so a re-run skips students already graded and submitted. */
  getBatchResume(url: string): Promise<string | null>;
  setBatchResume(url: string, lastStudentName: string): Promise<void>;
  clearBatchResume(url: string): Promise<void>;
  /** Grade one student. Work reaches the model only through the redaction gate. */
  gradeOne(
    student: Student,
    rubric: Rubric,
    provider: GradeProvider,
    opts?: { instructions?: string },
  ): Promise<GradeResult>;
  /**
   * Grade a class together, so scores stay comparable between students. Yields
   * progress per chunk; the final event carries every result in roster order.
   */
  gradeBatch(
    students: Student[],
    rubric: Rubric,
    provider: GradeProvider,
    opts?: { chunkSize?: number },
  ): AsyncGenerator<GradingEvent, BatchResult[], void>;
  /**
   * Second pass over the students detectOutliers flagged, re-read against their peers.
   * Returns the batch unchanged when nothing is flagged or the review fails to parse —
   * a bad review must never be able to lower a grade that was already earned.
   */
  reviewOutliers(
    students: Student[],
    results: BatchResult[],
    rubric: Rubric,
    provider: GradeProvider,
  ): Promise<ReviewOutcome>;
  /**
   * Worked example answers at each quality tier, for this question. Sends the rubric
   * only — no student work exists at this point, so there is nothing to redact.
   */
  generateAnchorExamples(
    rubric: Rubric,
    provider: GradeProvider,
    opts?: { leniency?: number },
  ): Promise<AnchorExample[]>;
  /** Extraction profiles from site_profiles, minus any whose selectors are unusable. */
  listExtractionProfiles(): Promise<ExtractionProfile[]>;
  /** First profile matching the URL, or null — never a guess. */
  matchProfile(url: string, profiles: ExtractionProfile[]): ExtractionProfile | null;
  /**
   * Read student responses off a MyOpenMath gradeallq2 page. Read-only — it evaluates
   * one expression and touches nothing. Defaults to students who answered and are not
   * yet graded, so a re-run never overwrites a human's scores.
   */
  loadStudents(
    evaluate: (expression: string) => Promise<unknown>,
    opts?: LoadOptions,
  ): Promise<ExtractedStudent[]>;
  gradeableFrom(students: ExtractedStudent[], opts?: LoadOptions): ExtractedStudent[];
  toGradingStudents(students: ExtractedStudent[]): Student[];
}

/** Site profiles, narrowed to extraction and with the unusable ones dropped. */
async function listExtractionProfiles(): Promise<ExtractionProfile[]> {
  const rows = await listSiteProfiles();
  return rows.map(profileFromRow).filter((p): p is ExtractionProfile => p !== null);
}

export const ogreIsland = defineIsland<OgreMethods>({
  id: 'ogre',
  label: 'OGRE',
  methods: {
    listSiteProfiles,
    getSiteProfile,
    listRubrics,
    getRubric,
    importRubricFromPage,
    saveImportedRubric,
    addGradingSession,
    listGradingSessions,
    getBatchResume,
    setBatchResume,
    clearBatchResume,
    gradeOne,
    gradeBatch,
    reviewOutliers,
    generateAnchorExamples,
    listExtractionProfiles,
    matchProfile,
    loadStudents,
    gradeableFrom,
    toGradingStudents,
  },
});

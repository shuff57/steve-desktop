/**
 * mom-island — read-only question browser + authoring/upload flow for the
 * MyOpenMath question bank. The island surface (browse / getQuestion /
 * getFamily / createDraft / upload) is the only way the main app talks to
 * the mom question bank. No Tauri or CDP wiring in the type signatures; the
 * page supplies `root` (a filesystem path) explicitly.
 */
import { invoke } from '@tauri-apps/api/core';
import { defineIsland } from '../_shared/island';
import {
  loadMOMIndex,
  loadMOMBooks,
  loadMOMDefaultRoot,
  type MOMFamily,
  type MOMIndex,
  type MOMQuestion,
  type MomBook,
  type MomBookQuestion,
} from './loader';
import {
  getFrqSetStats,
  parseManifest,
  aggregateStats,
  type FrqSetStats,
  type MOMManifest,
} from './manifest';
import { createDraft as draftCreate, type CreateDraftOpts, type DraftResult } from './draft';
import { uploadToMOM as uploadRun, type UploadOpts } from './upload';

export type { MOMFamily, MOMIndex, MOMQuestion, FrqSetStats, MOMManifest, MomBook, MomBookQuestion };
export { loadMOMIndex, loadMOMBooks, loadMOMDefaultRoot } from './loader';
export { getFrqSetStats, readManifest } from './manifest';
export { createDraft, isValidSlug, type CreateDraftOpts, type DraftResult } from './draft';
export { uploadToMOM, type UploadOpts } from './upload';
export { getTemplates, findTemplate, type MomTemplate } from './templates';

export interface MomQuestionDetail {
  family: string;
  slug: string;
  path: string;
  contents: string;
  manifest: FrqSetStats;
}

export interface MomFamilyDetail {
  name: string;
  count: number;
  questions: MOMQuestion[];
  manifest: FrqSetStats;
}

export interface MomMethods {
  /** Walk `<root>/questions/<family>/` and return the index. */
  browse(root: string): Promise<MOMIndex>;
  /** Read one question's PHP file + sibling manifest stats. */
  getQuestion(family: string, slug: string, root: string): Promise<MomQuestionDetail>;
  /** Read one family's questions + aggregate manifest stats. */
  getFamily(family: string, root: string): Promise<MomFamilyDetail>;
  /** Read + parse every assignment manifest under `<root>/books/` (the organizing spine). */
  listBooks(root: string): Promise<MomBook[]>;
  /** The in-repo mom-content path, so the browser can default there. '' if unresolved. */
  getDefaultRoot(): Promise<string>;
  /** Copy a template into the drafts dir, returning the new file's path. */
  createDraft(family: string, opts: CreateDraftOpts): Promise<DraftResult>;
  /** Drive the in-app browser to MyOpenMath's modquestion.php and paste. */
  upload(opts: UploadOpts): Promise<boolean>;
}

/** What `mom_read_question` hands back: the resolved .php plus its sibling manifest text. */
interface MomQuestionRead {
  path: string;
  contents: string;
  manifestText: string | null;
}

export const momIsland = defineIsland<MomMethods>({
  id: 'mom',
  label: 'MOM',
  methods: {
    async browse(root) {
      return loadMOMIndex(root);
    },

    async getQuestion(family, slug, root) {
      // Rust resolves family+slug against the walked index (caller paths are never trusted),
      // reads the .php, and returns the sibling manifest in the same round trip.
      const read = await invoke<MomQuestionRead>('mom_read_question', { root, family, slug });
      // Manifest stats are decorative. A foreign/legacy manifest shape (e.g. frq's
      // {source, questions:{...}}) must never block viewing or rendering the question.
      let manifest = { completed: 0, pending: 0, total: 0 };
      if (read.manifestText) {
        try {
          manifest = aggregateStats(parseManifest(read.manifestText, family));
        } catch {
          /* leave zeroed stats */
        }
      }
      return { family, slug, path: read.path, contents: read.contents, manifest };
    },

    async getFamily(family, root) {
      const idx = await loadMOMIndex(root);
      const fam = idx.families.find((f) => f.name === family);
      if (!fam) throw new Error(`Unknown family: ${family}`);
      // Manifest lives on the family root, not per-question. The current fixture has it
      // inside each question folder, so aggregate across all questions for now.
      let completed = 0;
      let pending = 0;
      for (const q of fam.questions) {
        const s = await getFrqSetStats(q.path);
        completed += s.completed;
        pending += s.pending;
      }
      return { name: fam.name, count: fam.count, questions: fam.questions, manifest: { completed, pending, total: completed + pending } };
    },

    listBooks(root) {
      return loadMOMBooks(root);
    },

    getDefaultRoot() {
      return loadMOMDefaultRoot();
    },

    createDraft(family, opts) {
      return draftCreate(family, opts);
    },

    upload(opts) {
      return uploadRun(opts);
    },
  },
});

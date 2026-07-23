/**
 * mom-island — read-only question browser + authoring/upload flow for the
 * MyOpenMath question bank. The island surface (browse / getQuestion /
 * getFamily / createDraft / upload) is the only way the main app talks to
 * the mom question bank. No Tauri or CDP wiring in the type signatures; the
 * page supplies `root` (a filesystem path) explicitly.
 */
import { readFile, readdir } from 'node:fs/promises';
import { defineIsland } from '../_shared/island';
import { loadMOMIndex, type MOMFamily, type MOMIndex, type MOMQuestion } from './loader';
import { getFrqSetStats, type FrqSetStats, type MOMManifest } from './manifest';
import { createDraft as draftCreate, type CreateDraftOpts, type DraftResult } from './draft';
import { uploadToMOM as uploadRun, type UploadOpts } from './upload';

export type { MOMFamily, MOMIndex, MOMQuestion, FrqSetStats, MOMManifest };
export { loadMOMIndex } from './loader';
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
  /** Copy a template into the drafts dir, returning the new file's path. */
  createDraft(family: string, opts: CreateDraftOpts): Promise<DraftResult>;
  /** Drive the in-app browser to MyOpenMath's modquestion.php and paste. */
  upload(opts: UploadOpts): Promise<boolean>;
}

async function resolveQuestionPath(
  root: string,
  family: string,
  slug: string,
): Promise<string> {
  // Re-walk the index for the family+slug pair. We don't trust caller-supplied
  // paths; the index is the source of truth.
  const idx = await loadMOMIndex(root);
  const fam = idx.families.find((f) => f.name === family);
  if (!fam) throw new Error(`Unknown family: ${family}`);
  const q = fam.questions.find((qq) => qq.slug === slug);
  if (!q) throw new Error(`Unknown question: ${family}/${slug}`);
  return q.path;
}

export const momIsland = defineIsland<MomMethods>({
  id: 'mom',
  label: 'MOM',
  methods: {
    async browse(root) {
      return loadMOMIndex(root);
    },

    async getQuestion(family, slug, root) {
      const dir = await resolveQuestionPath(root, family, slug);
      // The loader returns the question folder; the PHP file lives inside it.
      const files = await readdir(dir);
      const php = files.find((f) => f.endsWith('.php'));
      if (!php) throw new Error(`No .php file in ${dir}`);
      const path = `${dir}/${php}`;
      const contents = await readFile(path, 'utf-8');
      const manifest = await getFrqSetStats(dir);
      return { family, slug, path, contents, manifest };
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

    createDraft(family, opts) {
      return draftCreate(family, opts);
    },

    upload(opts) {
      return uploadRun(opts);
    },
  },
});

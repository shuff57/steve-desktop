/**
 * mom-island draft — copy a question template into a working directory so
 * the user can edit it without touching the source repo. The drafts dir
 * lives outside the mom repo (Tauri app-data) so a stray draft never
 * corrupts the catalog.
 */
import { copyFile, mkdir } from 'node:fs/promises';
import { isAbsolute, join, normalize, sep } from 'node:path';

export interface CreateDraftOpts {
  /** Path to the user's mom repo (MOM_ROOT). */
  momRoot: string;
  /** Path to the app-data drafts directory. */
  draftsDir: string;
  /** Template path, relative to `<momRoot>/questions/`. */
  templatePath: string;
  /** Slug for the new draft (becomes the file basename, no .php needed). */
  slug: string;
}

export interface DraftResult {
  draftPath: string;
  family: string;
  slug: string;
}

const SLUG_RE = /^[A-Za-z0-9][A-Za-z0-9._-]*$/;

export function isValidSlug(slug: string): boolean {
  return SLUG_RE.test(slug);
}

export async function createDraft(family: string, opts: CreateDraftOpts): Promise<DraftResult> {
  if (!isValidSlug(opts.slug)) {
    throw new Error(`invalid slug: ${opts.slug}`);
  }

  const questionsDir = join(opts.momRoot, 'questions');
  const sourcePath = isAbsolute(opts.templatePath)
    ? opts.templatePath
    : join(questionsDir, opts.templatePath);
  // Normalize both, ensure the source is within the questions dir.
  const normalizedSource = normalize(sourcePath);
  const normalizedRoot = normalize(questionsDir);
  if (!normalizedSource.startsWith(normalizedRoot + sep) && normalizedSource !== normalizedRoot) {
    throw new Error(`template path escapes questions dir: ${opts.templatePath}`);
  }

  // Drafts live at <draftsDir>/<family>/<slug>.php — family is a subdir so
  // switching families doesn't mix.
  const draftDir = join(opts.draftsDir, family);
  await mkdir(draftDir, { recursive: true });
  const draftPath = join(draftDir, `${opts.slug}.php`);

  try {
    await copyFile(normalizedSource, draftPath);
  } catch (e) {
    if (e instanceof Error && /ENOENT/.test(e.message)) {
      throw new Error(`template not found: ${opts.templatePath}`);
    }
    throw e;
  }

  return { draftPath, family, slug: opts.slug };
}

/**
 * mom-island draft — copy a question template into a working directory so
 * the user can edit it without touching the source repo. The drafts dir
 * lives outside the mom repo (Tauri app-data) so a stray draft never
 * corrupts the catalog.
 *
 * The copy + containment check run in Rust (`mom_create_draft`) — a WebView has no filesystem,
 * and the guard belongs where the paths are actually resolved. Slug validation stays here too
 * so the UI can fail fast without a round trip.
 */
import { invoke } from '@tauri-apps/api/core';

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

/** Mirrored by mom_is_valid_slug in lib.rs — keep the two in step. */
export function isValidSlug(slug: string): boolean {
  return SLUG_RE.test(slug);
}

export async function createDraft(family: string, opts: CreateDraftOpts): Promise<DraftResult> {
  if (!isValidSlug(opts.slug)) {
    throw new Error(`invalid slug: ${opts.slug}`);
  }
  return await invoke<DraftResult>('mom_create_draft', {
    momRoot: opts.momRoot,
    draftsDir: opts.draftsDir,
    templatePath: opts.templatePath,
    family,
    slug: opts.slug,
  });
}

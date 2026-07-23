/**
 * mom-island manifest reader — reads `manifest.json` from an FRQ question
 * folder and exposes aggregate stats (completed / pending / total).
 *
 * Manifest shape (v1, the only version we know):
 *   {
 *     "version": 1,
 *     "questions": [ { "slug": "q1-test", "status": "completed" }, ... ]
 *   }
 */
import { readFile } from 'node:fs/promises';
import { join } from 'node:path';

export type ManifestStatus = 'completed' | 'pending' | string;

export interface ManifestEntry {
  slug: string;
  status: ManifestStatus;
}

export interface MOMManifest {
  version: number;
  questions: ManifestEntry[];
}

export interface FrqSetStats {
  completed: number;
  pending: number;
  total: number;
}

export class ManifestNotFoundError extends Error {
  constructor(folder: string) {
    super(`manifest.json not found in ${folder}`);
    this.name = 'ManifestNotFoundError';
  }
}

/** Read & parse a folder's manifest.json. Throws ManifestNotFoundError if missing. */
export async function readManifest(folder: string): Promise<MOMManifest> {
  const path = join(folder, 'manifest.json');
  let text: string;
  try {
    text = await readFile(path, 'utf-8');
  } catch (e) {
    if (e instanceof Error && /ENOENT/.test(e.message)) {
      throw new ManifestNotFoundError(folder);
    }
    throw e;
  }
  const parsed = JSON.parse(text) as MOMManifest;
  if (typeof parsed.version !== 'number' || !Array.isArray(parsed.questions)) {
    throw new Error(`Invalid manifest in ${folder}: missing version or questions`);
  }
  return parsed;
}

/** Aggregate completed/pending/total counts. Returns zeroed stats if the
 *  folder has no manifest — the UI uses this to render "no manifest yet"
 *  without an extra try/catch. */
export async function getFrqSetStats(folder: string): Promise<FrqSetStats> {
  try {
    const m = await readManifest(folder);
    let completed = 0;
    let pending = 0;
    for (const q of m.questions) {
      if (q.status === 'completed') completed++;
      else if (q.status === 'pending') pending++;
    }
    return { completed, pending, total: m.questions.length };
  } catch (e) {
    if (e instanceof ManifestNotFoundError) {
      return { completed: 0, pending: 0, total: 0 };
    }
    throw e;
  }
}

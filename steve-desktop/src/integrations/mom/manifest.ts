/**
 * mom-island manifest reader — reads `manifest.json` from an FRQ question
 * folder and exposes aggregate stats (completed / pending / total).
 *
 * The file read happens in Rust (`mom_read_manifest`) — a WebView has no filesystem. Parsing
 * and aggregation stay here as pure functions so they remain directly unit-testable.
 *
 * Manifest shape (v1, the only version we know):
 *   {
 *     "version": 1,
 *     "questions": [ { "slug": "q1-test", "status": "completed" }, ... ]
 *   }
 */
import { invoke } from '@tauri-apps/api/core';

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

/** Parse + validate manifest text. Pure — the caller supplies the bytes. */
export function parseManifest(text: string, folder = ''): MOMManifest {
  const parsed = JSON.parse(text) as MOMManifest;
  if (typeof parsed.version !== 'number' || !Array.isArray(parsed.questions)) {
    throw new Error(`Invalid manifest in ${folder}: missing version or questions`);
  }
  return parsed;
}

/** Count completed/pending/total over a parsed manifest. Pure. */
export function aggregateStats(manifest: MOMManifest): FrqSetStats {
  let completed = 0;
  let pending = 0;
  for (const q of manifest.questions) {
    if (q.status === 'completed') completed++;
    else if (q.status === 'pending') pending++;
  }
  return { completed, pending, total: manifest.questions.length };
}

/** Read & parse a folder's manifest.json. Throws ManifestNotFoundError if missing. */
export async function readManifest(folder: string): Promise<MOMManifest> {
  const text = await invoke<string | null>('mom_read_manifest', { folder });
  if (text == null) throw new ManifestNotFoundError(folder);
  return parseManifest(text, folder);
}

/** Aggregate completed/pending/total counts. Returns zeroed stats if the
 *  folder has no manifest — the UI uses this to render "no manifest yet"
 *  without an extra try/catch. */
export async function getFrqSetStats(folder: string): Promise<FrqSetStats> {
  try {
    return aggregateStats(await readManifest(folder));
  } catch (e) {
    if (e instanceof ManifestNotFoundError) {
      return { completed: 0, pending: 0, total: 0 };
    }
    throw e;
  }
}

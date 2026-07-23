/**
 * mom-island loader — read-only index of the MyOpenMath question bank.
 *
 * The walk itself lives in Rust (`mom_load_index`): a WebView has no filesystem, and importing
 * node:fs here threw at import time and white-screened the whole app. This module is the thin
 * typed wrapper over that command, plus the pure junk-folder rule (mirrored in lib.rs).
 */
import { invoke } from '@tauri-apps/api/core';

export interface MOMQuestion {
  slug: string;
  path: string;
  hasManifest: boolean;
}

export interface MOMFamily {
  name: string;
  count: number;
  questions: MOMQuestion[];
}

export interface MOMIndex {
  families: MOMFamily[];
}

/** Family-level folders we must never treat as content. Matches Windows artifacts
 *  from the older gradebook/scripts droppings and the user-reported `nul` quirk.
 *  The Rust walk enforces the same rule — keep the two in step (mom_is_junk_family). */
const JUNK_FAMILY_RE = /^(nul|\$APPDATA$|C:Users.*)$/i;

export function isJunkFamily(name: string): boolean {
  return JUNK_FAMILY_RE.test(name);
}

/** Index `<root>/questions/<family>/<slug>/`. A missing questions dir yields no families
 *  (the UI renders an empty state) rather than throwing. */
export async function loadMOMIndex(root: string): Promise<MOMIndex> {
  const families = await invoke<MOMFamily[]>('mom_load_index', { root });
  return { families: families ?? [] };
}

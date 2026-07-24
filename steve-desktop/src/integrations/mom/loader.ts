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

/** Index the question bank. A question is a `.php` FILE; `slug` is its path relative to the
 *  family (nested for `frq/`, flat elsewhere) — the same identity the book manifests use.
 *  A missing questions dir yields no families (empty state) rather than throwing. */
export async function loadMOMIndex(root: string): Promise<MOMIndex> {
  const families = await invoke<MOMFamily[]>('mom_load_index', { root });
  return { families: families ?? [] };
}

// ── Books / assignment manifests ────────────────────────────────────────────────

/** One entry in an assignment's ordered question list. `filePath` is repo-relative
 *  (`questions/<family>/<slug>`); `qid` is the live MyOpenMath question id once uploaded. */
export interface MomBookQuestion {
  slot?: number;
  filePath: string;
  title?: string;
  subTopic?: string;
  role?: string;
  difficulty?: string;
  points?: number;
  qid?: string;
  verifyStatus?: string;
  isFrq?: boolean;
}

/** A MyOpenMath assignment: its settings, target course, and ordered questions. */
export interface MomBook {
  /** Path relative to `books/`, the stable id (e.g. `intro-stats/high-school/hw/ch8.1-day1.json`). */
  path: string;
  name: string;
  book?: string;
  kind?: string;
  chapterSection?: string;
  slug?: string;
  cid?: string;
  questions: MomBookQuestion[];
}

/** Read + parse every assignment manifest under `<root>/books/`. Unparseable files are
 *  skipped rather than failing the whole load. */
export async function loadMOMBooks(root: string): Promise<MomBook[]> {
  const files = (await invoke<{ path: string; text: string }[]>('mom_load_books', { root })) ?? [];
  const books: MomBook[] = [];
  for (const f of files) {
    try {
      const j = JSON.parse(f.text) as Record<string, unknown>;
      const target = (j.target ?? {}) as { cid?: string };
      const rawQs = Array.isArray(j.questions) ? (j.questions as Record<string, unknown>[]) : [];
      books.push({
        path: f.path,
        name: typeof j.name === 'string' ? j.name : f.path,
        book: j.book as string | undefined,
        kind: j.kind as string | undefined,
        chapterSection: j.chapter_section as string | undefined,
        slug: j.slug as string | undefined,
        cid: target.cid,
        questions: rawQs.map((q) => ({
          slot: q.slot as number | undefined,
          filePath: (q.file_path as string) ?? '',
          title: q.title as string | undefined,
          subTopic: q.sub_topic as string | undefined,
          role: q.role as string | undefined,
          difficulty: q.difficulty as string | undefined,
          points: q.points as number | undefined,
          qid: q.qid as string | undefined,
          verifyStatus: q.verify_status as string | undefined,
          isFrq: q.is_frq as boolean | undefined,
        })),
      });
    } catch {
      /* skip an unparseable manifest */
    }
  }
  return books;
}

/** The in-repo `mom-content/` dir, so the browser can default there instead of asking the
 *  user to paste a path. Empty string when it can't be resolved. */
export async function loadMOMDefaultRoot(): Promise<string> {
  return (await invoke<string>('mom_default_root')) ?? '';
}

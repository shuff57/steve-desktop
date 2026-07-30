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
  /** Every book this assignment belongs to; an assignment may be shared across courses. */
  books: string[];
  kind?: string;
  chapterSection?: string;
  slug?: string;
  cid?: string;
  questions: MomBookQuestion[];
}

/**
 * Which book an assignment belongs to.
 *
 * The `book` field is NOT reliable — six real assignments under `applied-finite-math/` never had
 * one, so grouping on the field alone silently drops them into an "unknown" pile. The directory is
 * the actual organising fact, so it wins when the field is missing.
 */
export function bookOf(path: string, declared?: unknown): string {
  if (typeof declared === 'string' && declared.trim()) return declared.trim();
  const first = path.replace(/\\/g, '/').split('/').filter(Boolean)[0];
  return first && first.endsWith('.json') ? '' : (first ?? '');
}

/**
 * Every book an assignment belongs to.
 *
 * An assignment can be shared — the same probability homework is reasonable in more than one
 * course — so membership is a LIST. `books` wins when present; otherwise it degrades to the single
 * `book` field, and finally to the directory, so nothing has to be migrated to keep working.
 */
export function booksOf(path: string, j: Record<string, unknown>): string[] {
  const raw = j.books;
  if (Array.isArray(raw)) {
    const list = raw.filter((b): b is string => typeof b === 'string' && b.trim().length > 0).map((b) => b.trim());
    if (list.length) return [...new Set(list)];
  }
  const single = bookOf(path, j.book);
  return single ? [single] : [];
}

/**
 * Is this manifest an assignment, or a working file that happens to live under `books/`?
 *
 * The `_scrape-*.json` files are item-analysis and inventory dumps — no name, no kind, no
 * questions, keys that are raw MyOpenMath ids. They were being listed as if they were assignments.
 */
export function isAssignmentManifest(path: string, j: Record<string, unknown>): boolean {
  const base = path.replace(/\\/g, '/').split('/').pop() ?? '';
  if (base.startsWith('_')) return false;
  return typeof j.name === 'string' && j.name.trim().length > 0;
}

/** A course, as declared in `books/_books.json`. */
export interface MomBookEntry {
  slug: string;
  title?: string;
  /** Kept for reference but no longer taught — sorted last and marked in the UI. */
  archived?: boolean;
}

/** The registry file, which is deliberately not an assignment. */
const REGISTRY = '_books.json';

/**
 * The books that exist, independent of whether anything is in one yet.
 *
 * Without this a book is only implied by its assignments, so a NEW course is invisible until its
 * first assignment exists — which makes "create the book, then fill it" impossible. Also carries
 * real titles: slug-casing turns `introduction-to-stats` into "Introduction To Stats", which is
 * wrong, and cannot know that `-sh` is a suffix rather than a word.
 */
export async function loadMOMBookRegistry(root: string): Promise<MomBookEntry[]> {
  const files = (await invoke<{ path: string; text: string }[]>('mom_load_books', { root })) ?? [];
  const hit = files.find((f) => (f.path.replace(/\\/g, '/').split('/').pop() ?? '') === REGISTRY);
  if (!hit) return [];
  try {
    const j = JSON.parse(hit.text) as { books?: unknown };
    if (!Array.isArray(j.books)) return [];
    return j.books
      .filter((b): b is Record<string, unknown> => !!b && typeof b === 'object')
      .map((b) => ({
        slug: typeof b.slug === 'string' ? b.slug.trim() : '',
        title: typeof b.title === 'string' ? b.title : undefined,
        archived: b.archived === true,
      }))
      .filter((b) => b.slug.length > 0);
  } catch {
    return []; // a malformed registry must not take the whole Books view down
  }
}

/** Read + parse every assignment manifest under `<root>/books/`. Unparseable files are
 *  skipped rather than failing the whole load. */
export async function loadMOMBooks(root: string): Promise<MomBook[]> {
  const files = (await invoke<{ path: string; text: string }[]>('mom_load_books', { root })) ?? [];
  const books: MomBook[] = [];
  for (const f of files) {
    try {
      const j = JSON.parse(f.text) as Record<string, unknown>;
      if (!isAssignmentManifest(f.path, j)) continue;
      const target = (j.target ?? {}) as { cid?: string };
      const rawQs = Array.isArray(j.questions) ? (j.questions as Record<string, unknown>[]) : [];
      books.push({
        path: f.path,
        name: typeof j.name === 'string' ? j.name : f.path,
        book: bookOf(f.path, j.book) || undefined,
        books: booksOf(f.path, j),
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

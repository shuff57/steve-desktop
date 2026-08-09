/**
 * One index from source file to MOM library id, across every assignment.
 *
 * `qid` is recorded inside each assignment manifest, found by `file_path` within
 * that manifest. That is fine while every question belongs to exactly one
 * assignment — which was true of all 40 sources on 2026-08-02 — and wrong the
 * moment a question is reused, which is the whole point of keeping a bank:
 *
 *   1.2's manifest carries qid 1868232 for q13-select-all-quantitative-discrete
 *   a later review assignment references the same file, with no qid of its own
 *   the pusher sees "no qid" and files it AGAIN → two library questions
 *   they then drift, and fixing one fixes only half the course
 *
 * The index is the single answer to "has this source already been filed, and as
 * what?", so a second assignment attaches the existing library question (a plain
 * GET) instead of creating a duplicate. Fix a question once, every assignment
 * that references it improves — which is the point of a bank that grows.
 *
 * Generated, not hand-formatted, so a plain JSON round-trip is safe here. The
 * assignment manifests are the opposite and must keep using the format-preserving
 * editor in book-membership.ts.
 */

/** Where the index lives, relative to mom-content. */
export const LIBRARY_INDEX_PATH = 'reference/question-library.json';

export interface LibraryEntry {
  /** MOM library id — `moddataset.php?id=<qsetid>`. */
  qsetid: string;
  /**
   * The course whose Manage Questions page the question was created through — a
   * breadcrumb for tracing a push, and nothing more.
   *
   * It is NOT a home, an owner, or a boundary, and two entries with different
   * `cid`s are not in different places. Verified live 2026-08-09: every question
   * in the index, filed from 334243 and 334437 alike, reports the same MyOpenMath
   * library (`libs=1221`) and is editable from either course. MyOpenMath questions
   * belong to a user and a library; courses only reference them. Reading this field
   * as a home produced a false report that the master course's chapter 1 depended
   * on another course's library — the "fix" would have filed 15 duplicates.
   */
  cid: string;
  /** ISO date of the push that created it. */
  filed: string;
  /** Question type declared by the source when it was filed, for the audit. */
  qtype?: string;
}

export type QuestionLibrary = Record<string, LibraryEntry>;

/** Parse an index, treating anything malformed as empty rather than throwing mid-push. */
export function parseLibrary(text: string): QuestionLibrary {
  if (!text.trim()) return {};
  try {
    const parsed = JSON.parse(text) as unknown;
    if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) return {};
    const out: QuestionLibrary = {};
    for (const [file, v] of Object.entries(parsed as Record<string, unknown>)) {
      const e = v as Partial<LibraryEntry>;
      if (e && typeof e.qsetid === 'string' && e.qsetid) {
        out[file] = {
          qsetid: e.qsetid,
          cid: String(e.cid ?? ''),
          filed: String(e.filed ?? ''),
          ...(e.qtype ? { qtype: String(e.qtype) } : {}),
        };
      }
    }
    return out;
  } catch {
    return {};
  }
}

/** Serialise sorted by path, so a push produces a reviewable one-line diff. */
export function serialiseLibrary(lib: QuestionLibrary): string {
  const sorted: QuestionLibrary = {};
  for (const key of Object.keys(lib).sort()) sorted[key] = lib[key];
  return JSON.stringify(sorted, null, 2) + '\n';
}

/**
 * Record a freshly filed question.
 *
 * Refuses to silently overwrite an existing, different qsetid: that means the
 * source was filed twice, which is the exact duplicate this index exists to
 * prevent, and it should surface as an error rather than as a quiet reassignment.
 */
export function recordFiled(
  lib: QuestionLibrary,
  filePath: string,
  entry: LibraryEntry,
): { library: QuestionLibrary; duplicate?: { existing: string; incoming: string } } {
  const prev = lib[filePath];
  if (prev && prev.qsetid !== entry.qsetid) {
    return { library: lib, duplicate: { existing: prev.qsetid, incoming: entry.qsetid } };
  }
  return { library: { ...lib, [filePath]: entry } };
}

/**
 * Decide what a push should do with each question in an assignment.
 *
 * `attach` already exists in the library and only needs the attach GET.
 * `file` has never been pushed and needs the whole write path.
 *
 * The manifest's own qid still wins when present — it is the record of what that
 * assignment actually attached — but the index catches the case the manifest
 * cannot see: a DIFFERENT assignment already filed this source.
 */
export function planPush(
  questions: { slot: number; file_path: string; qid?: string | null }[],
  lib: QuestionLibrary,
): { slot: number; file_path: string; action: 'attach' | 'file'; qsetid?: string; reusedFrom?: 'manifest' | 'library' }[] {
  return questions.map((q) => {
    if (q.qid) {
      return { slot: q.slot, file_path: q.file_path, action: 'attach' as const, qsetid: q.qid, reusedFrom: 'manifest' as const };
    }
    const known = lib[q.file_path];
    if (known) {
      return { slot: q.slot, file_path: q.file_path, action: 'attach' as const, qsetid: known.qsetid, reusedFrom: 'library' as const };
    }
    return { slot: q.slot, file_path: q.file_path, action: 'file' as const };
  });
}

/**
 * Create the containers a question can be filed into: a BOOK (a course) and an ASSIGNMENT within it.
 *
 * A family needs nothing created — it is just the directory a question is written to, so it comes
 * into existence with its first question. Books and assignments are different: they are declared in
 * files, and until they are declared the writer's "Add to book" picker has nothing to offer.
 *
 * Both are written as plain new files rather than through the in-place manifest editor, because
 * there is no hand-formatting to preserve yet. The registry IS hand-formatted, so that one is
 * edited as text.
 */

import { invoke } from '@tauri-apps/api/core';
import { readBookManifest } from './book-membership';

/** Registry of declared books, so a course with no assignments yet is still listed. */
export const BOOK_REGISTRY = '_books.json';

/** `Applied Finite Math` -> `applied-finite-math`. Slugs are path segments, so they must be tame. */
export function slugify(title: string): string {
  return title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

/**
 * Add a book to the registry, preserving its formatting.
 *
 * The registry is a hand-aligned array, so the new entry is appended as one line in the same shape
 * rather than reserialised. Throws on a duplicate slug: two books sharing a slug would make every
 * assignment's `book` field ambiguous.
 */
export function addBookToRegistry(text: string, slug: string, title: string): string {
  const parsed = JSON.parse(text) as { books?: { slug: string }[] };
  if ((parsed.books ?? []).some((b) => b.slug === slug)) {
    throw new Error(`a book with slug "${slug}" already exists`);
  }

  const open = /"books"\s*:\s*\[/.exec(text);
  if (!open) throw new Error('registry has no books array');
  const close = text.indexOf(']', open.index);
  if (close < 0) throw new Error('unterminated books array');

  const body = text.slice(open.index + open[0].length, close);
  const indent = /\n([ \t]+)\{/.exec(body)?.[1] ?? '    ';
  const entry = `{ "slug": ${JSON.stringify(slug)}, "title": ${JSON.stringify(title)} }`;
  const empty = body.trim().length === 0;
  const head = empty
    ? text.slice(0, open.index + open[0].length)
    : text.slice(0, close).replace(/\s*$/, '') + ',';
  return `${head}\n${indent}${entry}\n${indent.slice(0, -2)}${text.slice(close)}`;
}

export interface NewAssignment {
  /** Book slug the assignment belongs to. */
  book: string;
  /** `hw` | `practice` | `group` | `ind` — also the directory it lands in. */
  kind: string;
  /** What the teacher calls it. */
  name: string;
  /** Optional chapter/section label, e.g. `7-8`. */
  chapterSection?: string;
  /** Today, as `YYYY-MM-DD`. Passed in rather than read from the clock so this stays testable. */
  today: string;
}

/** `books/<book>/<kind>/<slug>.json` — the layout every existing manifest already follows. */
export function assignmentPath(book: string, kind: string, slug: string): string {
  return `${book}/${kind}/${slug}.json`;
}

/**
 * A minimal but complete assignment manifest.
 *
 * Deliberately omits `target` and `mom_settings`: those describe a LIVE MyOpenMath assignment, and
 * inventing a cid or an attempt policy here would put a plausible-looking lie in the file. They get
 * filled in when the assignment is actually pushed.
 */
export function newAssignmentManifest(a: NewAssignment): { slug: string; text: string } {
  const slug = slugify(a.name);
  if (!slug) throw new Error('assignment needs a name');
  const body: Record<string, unknown> = {
    name: a.name,
    book: a.book,
    kind: a.kind,
    slug,
    created_at: a.today,
    questions: [],
  };
  if (a.chapterSection?.trim()) body.chapter_section = a.chapterSection.trim();
  return { slug, text: JSON.stringify(body, null, 2) + '\n' };
}

/** Declare a new book. Returns its slug. */
export async function createBook(root: string, title: string): Promise<string> {
  const slug = slugify(title);
  if (!slug) throw new Error('book needs a title');
  const before = await readBookManifest(root, BOOK_REGISTRY);
  const after = addBookToRegistry(before, slug, title.trim());
  JSON.parse(after); // refuse to write something unparseable
  await invoke('mom_write_book', { root, path: BOOK_REGISTRY, text: after });
  return slug;
}

/** Create an empty assignment inside a book. Returns the manifest path, relative to `books/`. */
export async function createAssignment(root: string, a: NewAssignment): Promise<string> {
  const { slug, text } = newAssignmentManifest(a);
  const path = assignmentPath(a.book, a.kind, slug);
  await invoke('mom_create_book_file', { root, path, text });
  return path;
}

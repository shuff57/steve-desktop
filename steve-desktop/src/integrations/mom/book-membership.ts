/**
 * Change which books an assignment belongs to, without reformatting its manifest.
 *
 * These files are hand-formatted and inconsistent — some put a whole question on one line, others
 * pretty-print every key — so a JSON round-trip would rewrite every line and bury the one-field
 * change in noise. This edits the `books` key in place and leaves everything else byte-identical.
 *
 * Membership is a LIST because the same assignment can legitimately sit in more than one course.
 */

import { invoke } from '@tauri-apps/api/core';

/** The `books` line, however it is currently written (array on one line, or absent). */
const BOOKS_KEY = /^([ \t]*)"books"[ \t]*:[ \t]*\[[^\]]*\][ \t]*,?[ \t]*$/m;
const BOOK_KEY = /^([ \t]*)"book"[ \t]*:[ \t]*(?:"[^"]*"|null)[ \t]*,?[ \t]*$/m;

function render(indent: string, books: string[], trailingComma: boolean): string {
  const list = books.map((b) => JSON.stringify(b)).join(', ');
  return `${indent}"books": [${list}]${trailingComma ? ',' : ''}`;
}

/**
 * Return `text` with `books` set to `books`.
 *
 * Replaces an existing `books` line; otherwise inserts one after `book` if that exists, and
 * failing that after the opening brace — so a manifest that has never declared either still gets
 * a well-placed key rather than one appended somewhere arbitrary.
 */
export function setBooksInManifest(text: string, books: string[]): string {
  const unique = [...new Set(books.map((b) => b.trim()).filter(Boolean))];

  const existing = BOOKS_KEY.exec(text);
  if (existing) {
    const hadComma = existing[0].trimEnd().endsWith(',');
    return text.replace(BOOKS_KEY, render(existing[1], unique, hadComma));
  }

  const single = BOOK_KEY.exec(text);
  if (single) {
    const hadComma = single[0].trimEnd().endsWith(',');
    // Keep `book` as-is for anything still reading it; add `books` alongside as the richer form.
    return text.replace(BOOK_KEY, `${single[0].replace(/,?[ \t]*$/, ',')}\n${render(single[1], unique, hadComma)}`);
  }

  const brace = text.indexOf('{');
  if (brace < 0) return text;
  const nl = text.indexOf('\n', brace);
  const at = nl < 0 ? text.length : nl;
  return text.slice(0, at) + '\n' + render('  ', unique, true) + text.slice(at);
}

/** Read a manifest's raw text (the loader hands back parsed books, not the source). */
export async function readBookManifest(root: string, path: string): Promise<string> {
  const files = (await invoke<{ path: string; text: string }[]>('mom_load_books', { root })) ?? [];
  const norm = (p: string) => p.replace(/\\/g, '/');
  const hit = files.find((f) => norm(f.path) === norm(path));
  if (!hit) throw new Error(`manifest not found: ${path}`);
  return hit.text;
}

/**
 * Persist a new book membership for one assignment.
 *
 * Re-reads the manifest from disk first rather than trusting anything cached, then writes back the
 * minimally-edited text. Throws if the result is not valid JSON, so a bad edit cannot land.
 */
export async function saveBookMembership(root: string, path: string, books: string[]): Promise<void> {
  const before = await readBookManifest(root, path);
  const after = setBooksInManifest(before, books);
  JSON.parse(after); // refuse to write something unparseable
  await invoke('mom_write_book', { root, path, text: after });
}

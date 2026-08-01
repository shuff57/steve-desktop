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

/**
 * Append a question to an assignment's ordered list, formatting preserved.
 *
 * Inserted before the array's closing bracket with the indentation of the entry above it, so a
 * hand-aligned one-entry-per-line manifest stays that way. The slot number continues the sequence
 * rather than being guessed, because slots are what the teacher reads back.
 */
export function appendQuestionSlot(text: string, filePath: string, title: string): string {
  const open = /"questions"\s*:\s*\[/.exec(text);
  if (!open) throw new Error('manifest has no questions array');

  // Walk to the matching close, respecting strings and nesting.
  let depth = 0, inStr = false, esc = false, close = -1;
  for (let i = open.index + open[0].length - 1; i < text.length; i++) {
    const c = text[i];
    if (inStr) {
      if (esc) esc = false;
      else if (c === '\\') esc = true;
      else if (c === '"') inStr = false;
      continue;
    }
    if (c === '"') inStr = true;
    else if (c === '[' || c === '{') depth++;
    else if (c === ']' || c === '}') {
      depth--;
      if (depth === 0) { close = i; break; }
    }
  }
  if (close < 0) throw new Error('unterminated questions array');

  const body = text.slice(open.index + open[0].length, close);
  const slots = [...body.matchAll(/"slot"\s*:\s*(\d+)/g)].map((m) => Number(m[1]));
  const slot = slots.length ? Math.max(...slots) + 1 : 1;

  const entry =
    `{ "slot": ${slot}, "file_path": ${JSON.stringify(filePath)}, "title": ${JSON.stringify(title)} }`;
  const indent = /\n([ \t]+)\{/.exec(body)?.[1] ?? '    ';
  const empty = body.trim().length === 0;
  const insert = empty ? `\n${indent}${entry}\n${indent.slice(0, -2)}` : `,\n${indent}${entry}\n${indent.slice(0, -2)}`;

  // Drop the whitespace that preceded the bracket, so the new entry lands where it belongs.
  const head = empty ? text.slice(0, open.index + open[0].length) : text.slice(0, close).replace(/\s*$/, '');
  return head + insert + text.slice(close);
}

/**
 * Record the MyOpenMath id a question was pushed as.
 *
 * This is what stops a second push from filing forty duplicates into a live course: a question that
 * already carries a `qid` has already landed, so the pusher skips it. Format-preserving like the
 * rest of this file — the entry is found by its `file_path` and only the `qid` key is touched.
 *
 * Returns the text unchanged when no entry matches, so a caller pushing a question that is not in
 * this manifest fails loudly at the verify step rather than silently rewriting the wrong entry.
 */
export function setQuestionQid(text: string, filePath: string, qid: string): string {
  const norm = (p: string) => p.replace(/\\/g, '/');
  // Locate the entry by its file_path value, whatever whitespace the manifest uses around the colon.
  const at = [...text.matchAll(/"file_path"\s*:\s*"([^"]*)"/g)]
    .find((m) => norm(m[1]) === norm(filePath))?.index;
  if (at === undefined) return text;

  // Walk to the entry's closing brace with strings skipped — titles in this bank really do contain
  // `}`, so a plain indexOf would cut the entry in half and produce unparseable JSON.
  const open = text.lastIndexOf('{', at);
  let close = -1;
  for (let i = at, inStr = false, esc = false; i < text.length; i++) {
    const c = text[i];
    if (inStr) {
      if (esc) esc = false;
      else if (c === '\\') esc = true;
      else if (c === '"') inStr = false;
    } else if (c === '"') inStr = true;
    else if (c === '}') { close = i; break; }
  }
  if (open < 0 || close < 0) return text;

  const entry = text.slice(open, close);
  const existing = /"qid"\s*:\s*(?:"[^"]*"|null|\d+)/;
  const next = existing.test(entry)
    ? entry.replace(existing, `"qid": ${JSON.stringify(qid)}`)
    // Ride alongside file_path rather than appending at the end, so a one-line entry stays readable
    // and a pretty-printed one keeps the id next to the thing it identifies.
    : entry.replace(/("file_path"\s*:\s*"[^"]*")/, `$1, "qid": ${JSON.stringify(qid)}`);

  return text.slice(0, open) + next + text.slice(close);
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

/** Add a freshly-written question to one assignment. Same refuse-if-unparseable rule. */
export async function addQuestionToAssignment(
  root: string,
  path: string,
  filePath: string,
  title: string,
): Promise<void> {
  const before = await readBookManifest(root, path);
  const after = appendQuestionSlot(before, filePath, title);
  JSON.parse(after);
  await invoke('mom_write_book', { root, path, text: after });
}

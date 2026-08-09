// Rebuild question-index.json from what is actually on disk, so the bank index cannot fall behind.
//
//   bun mom-content/reference/sync-index.ts            # rewrite the index, print what changed
//   bun mom-content/reference/sync-index.ts --check    # exit 1 if it is out of date, change nothing
//
// Why this exists: question-index.json is the REUSE index -- the thing consulted before writing a
// new question, so a source that is already in the bank gets attached rather than filed twice. It
// is also the only one of the three reference files nothing in src/ reads or writes: the push is
// driven by an agent following the mom-transfer skill, and `recordFiled` in
// src/integrations/mom/question-library.ts has no production caller at all. So the library index
// was being maintained by the push and this one was not.
//
// It drifted exactly as you would expect. On 2026-08-09 an audit found 32 questions on disk and
// missing from the index, 56 entries carrying `qsetid: null` while question-library.json held a
// real id, and 171 stale `used_by` lists. A question missing from the index is invisible to the
// next authoring pass, which is how one source becomes two library questions that then drift
// apart -- the precise failure the index exists to prevent.
//
// Everything here is DERIVED, so running it twice changes nothing:
//   path     the file itself
//   desc     its `NAME - DESCRIPTION` marker
//   qtype    its `SET QUESTION TYPE TO` marker
//   used_by  every manifest whose questions[] references it
//   qsetid   question-library.json, which is what the push actually writes
//   health   PRESERVED from the existing entry; this script renders nothing and must never
//            silently upgrade a BROKEN question to ok. New entries start "unchecked".
//
// Imports are relative and that is safe here because this file lives inside the repo; the same
// import from the session scratchpad would not resolve.
import { readFileSync, writeFileSync, readdirSync, statSync } from 'fs';
import { join, dirname, relative } from 'path';

const ROOT = join(dirname(new URL(import.meta.url).pathname.replace(/^\/([A-Za-z]:)/, '$1')), '..');
const INDEX = join(ROOT, 'reference', 'question-index.json');
const LIBRARY = join(ROOT, 'reference', 'question-library.json');

const NAME_RE = /^\/\/\s*===\s*NAME - DESCRIPTION:\s*(.*?)\s*===\s*$/m;
const QTYPE_RE = /^\/\/\s*===\s*SET QUESTION TYPE TO:\s*(.*?)\s*===\s*$/m;

type Entry = {
  path: string;
  desc: string;
  qtype: string;
  used_by: string[];
  qsetid: string | null;
  health: string;
};

function walk(dir: string, out: string[] = []): string[] {
  for (const name of readdirSync(dir)) {
    const full = join(dir, name);
    if (statSync(full).isDirectory()) walk(full, out);
    else if (name.endsWith('.php')) out.push(full);
  }
  return out;
}

// Every manifest that references a question, keyed by the question's repo-relative path.
function collectUsedBy(): Map<string, Set<string>> {
  const used = new Map<string, Set<string>>();
  const booksDir = join(ROOT, 'books');
  for (const manifest of walk(booksDir, []).concat([])) void manifest; // .php walk finds none here
  const stack = [booksDir];
  while (stack.length) {
    const dir = stack.pop()!;
    for (const name of readdirSync(dir)) {
      const full = join(dir, name);
      if (statSync(full).isDirectory()) { stack.push(full); continue; }
      if (!name.endsWith('.json')) continue;
      let doc: any;
      try { doc = JSON.parse(readFileSync(full, 'utf8')); } catch { continue; }
      if (!Array.isArray(doc?.questions)) continue;
      const slug: string = doc.slug || name.replace(/\.json$/, '');
      for (const q of doc.questions) {
        if (!q?.file_path) continue;
        if (!used.has(q.file_path)) used.set(q.file_path, new Set());
        used.get(q.file_path)!.add(slug);
      }
    }
  }
  return used;
}

const check = process.argv.includes('--check');

const library: Record<string, { qsetid: string }> = JSON.parse(readFileSync(LIBRARY, 'utf8'));
const existing: Entry[] = JSON.parse(readFileSync(INDEX, 'utf8'));
const prev = new Map(existing.map((e) => [e.path, e]));
const usedBy = collectUsedBy();

const rows: Entry[] = [];
for (const abs of walk(join(ROOT, 'questions'))) {
  const rel = relative(ROOT, abs).split('\\').join('/');
  const src = readFileSync(abs, 'utf8');
  const was = prev.get(rel);
  rows.push({
    path: rel,
    desc: NAME_RE.exec(src)?.[1] ?? '',
    qtype: QTYPE_RE.exec(src)?.[1] ?? '?',
    used_by: [...(usedBy.get(rel) ?? [])].sort(),
    qsetid: library[rel]?.qsetid ?? null,
    // Never re-grade health here: this script does not render anything.
    health: was?.health ?? 'unchecked',
  });
}
rows.sort((a, b) => (a.path < b.path ? -1 : a.path > b.path ? 1 : 0));

const next = JSON.stringify(rows, null, 1) + '\n';
const current = readFileSync(INDEX, 'utf8');

const added = rows.filter((r) => !prev.has(r.path)).map((r) => r.path);
const dropped = existing.filter((e) => !rows.some((r) => r.path === e.path)).map((e) => e.path);
const requalified = rows.filter((r) => {
  const was = prev.get(r.path);
  if (!was) return false;
  return (
    was.desc !== r.desc ||
    was.qtype !== r.qtype ||
    String(was.qsetid) !== String(r.qsetid) ||
    (was.used_by ?? []).slice().sort().join(',') !== r.used_by.join(',')
  );
}).map((r) => r.path);

if (next === current) {
  console.log(`question-index.json is up to date (${rows.length} entries).`);
  process.exit(0);
}

console.log(`added ${added.length}, removed ${dropped.length}, updated ${requalified.length}`);
for (const p of added.slice(0, 12)) console.log(`  + ${p}`);
for (const p of dropped.slice(0, 12)) console.log(`  - ${p}`);
for (const p of requalified.slice(0, 12)) console.log(`  ~ ${p}`);

if (check) {
  console.error('\nOut of date. Run without --check to rewrite it.');
  process.exit(1);
}

writeFileSync(INDEX, next);
console.log(`\nwrote ${rows.length} entries.`);

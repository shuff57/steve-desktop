// Static audit: does $anstypes declare as many parts as the question actually uses?
// IMathAS renders ONE answer box per $anstypes entry. Declare 3 and set $answer[0..5] and the
// last three parts silently never render — and cannot be graded, so the question scores 100%.
// Found on q10-empirical-rule-bands.php (5.1 slot 7) via visual review, 2026-08-16.
import fs from 'node:fs';
import path from 'node:path';

import { fileURLToPath } from 'url';
import { dirname, resolve } from 'path';
const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const BOOK = `${ROOT}/books/introduction-to-stats-sh`;

const files = new Set();
for (const dir of ['hw', 'lab']) {
  for (const f of fs.readdirSync(`${BOOK}/${dir}`)) {
    if (!f.endsWith('.json')) continue;
    const d = JSON.parse(fs.readFileSync(`${BOOK}/${dir}/${f}`, 'utf8'));
    for (const q of d.questions) files.add(q.file_path);
  }
}

const rows = [];
for (const rel of [...files].sort()) {
  const p = `${ROOT}/${rel}`;
  if (!fs.existsSync(p)) { rows.push({ rel, err: 'MISSING FILE' }); continue; }
  const src = fs.readFileSync(p, 'utf8');

  // Count declared answer types. Handles array("a","b") across one or more lines.
  const m = src.match(/\$anstypes\s*=\s*array\s*\(([\s\S]*?)\)/);
  const declared = m ? (m[1].match(/"[^"]*"|'[^']*'/g) || []).length : null;

  // Highest $answer[N] index actually assigned.
  const idx = [...src.matchAll(/\$answer\s*\[\s*(\d+)\s*\]\s*=/g)].map((x) => Number(x[1]));
  const used = idx.length ? Math.max(...idx) + 1 : null;

  // How many answer boxes the question text asks for.
  const boxes = (src.match(/answerbox/g) || []).length;

  if (declared == null && used == null) continue; // not a multipart-style question
  const mismatch = declared != null && used != null && declared !== used;
  rows.push({ rel, declared, used, boxes, mismatch });
}

const bad = rows.filter((r) => r.mismatch);
console.log(`questions scanned: ${rows.length}`);
console.log(`ANSTYPES/ANSWER MISMATCHES: ${bad.length}\n`);
for (const r of bad) {
  console.log(`  ${r.rel}`);
  console.log(`      anstypes=${r.declared}  answers=${r.used}  answerbox refs=${r.boxes}   -> ${r.used - r.declared} part(s) never render`);
}
const missing = rows.filter((r) => r.err);
if (missing.length) console.log(`\nmissing files: ${missing.length}`);
fs.writeFileSync('anstypes-audit.json', JSON.stringify(rows, null, 2));

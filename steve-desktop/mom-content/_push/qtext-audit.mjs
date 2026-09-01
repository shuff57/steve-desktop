// Two source-defect classes found by visual review of 5.2, audited across the whole book.
//
// A) CONCAT LEAK — QUESTION TEXT interpolates $var directly; it is NOT a PHP string. A
//    `' . $var . '` concatenation renders LITERALLY: "the middle '. 80 .'% of the values".
//    The same syntax inside $solutionguide is CORRECT (that really is a PHP string), so this
//    must only be flagged inside the QUESTION TEXT section.
//
// B) BACKTICKED FUNCTION NAMES — backticks are ASCIIMath delimiters. `invNorm` is typeset as
//    symbols ("∈ vN or m"), `normalcdf` likewise. Function names must be plain text, not math.
import fs from 'node:fs';

import { fileURLToPath } from 'url';
import { dirname, resolve } from 'path';
const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const BOOK = `${ROOT}/books/introduction-to-stats-sh`;

const files = new Set();
for (const dir of ['hw', 'lab', 'group', 'practice']) {
  if (!fs.existsSync(`${BOOK}/${dir}`)) continue;
  for (const f of fs.readdirSync(`${BOOK}/${dir}`)) {
    if (!f.endsWith('.json')) continue;
    for (const q of JSON.parse(fs.readFileSync(`${BOOK}/${dir}/${f}`, 'utf8')).questions) files.add(q.file_path);
  }
}

const FUNC = /(invNorm|invnorm|normalcdf|invnormalcdf|\bcdf\b|binomialcdf|tcdf)/;
const concatHits = [], mathHits = [];

for (const rel of [...files].sort()) {
  const p = `${ROOT}/${rel}`;
  if (!fs.existsSync(p)) continue;
  const src = fs.readFileSync(p, 'utf8').replace(/\r\n/g, '\n');

  const qtextM = src.match(/===\s*QUESTION TEXT\s*===\n([\s\S]*?)(?=\n\/\/\s*===|$)/);
  const qtext = qtextM ? qtextM[1] : '';
  const qtextStart = qtextM ? src.slice(0, src.indexOf(qtextM[1])).split('\n').length : 0;

  // (A) concat inside QUESTION TEXT only
  qtext.split('\n').forEach((line, i) => {
    const m = line.match(/'\s*\.\s*[^']{1,200}?\s*\.\s*'/);
    if (m) concatHits.push({ rel, line: qtextStart + i, snippet: m[0].slice(0, 70) });
  });

  // (B) backticked segments containing a function name, anywhere (qtext and solution both render)
  for (const m of src.matchAll(/`([^`\n]{1,80})`/g)) {
    if (FUNC.test(m[1])) {
      const line = src.slice(0, m.index).split('\n').length;
      mathHits.push({ rel, line, snippet: '`' + m[1].slice(0, 60) + '`' });
    }
  }
}

const byFile = (arr) => [...new Set(arr.map((h) => h.rel))];

console.log(`questions scanned: ${files.size}\n`);
console.log(`A) CONCAT LEAK IN QUESTION TEXT — ${concatHits.length} hits in ${byFile(concatHits).length} files`);
for (const h of concatHits.slice(0, 25)) console.log(`   ${h.rel.split('/').slice(-1)[0]}:${h.line}  ${h.snippet}`);
if (concatHits.length > 25) console.log(`   ... and ${concatHits.length - 25} more`);

console.log(`\nB) FUNCTION NAME INSIDE BACKTICKS — ${mathHits.length} hits in ${byFile(mathHits).length} files`);
for (const h of mathHits.slice(0, 25)) console.log(`   ${h.rel.split('/').slice(-1)[0]}:${h.line}  ${h.snippet}`);
if (mathHits.length > 25) console.log(`   ... and ${mathHits.length - 25} more`);

fs.writeFileSync('qtext-audit.json', JSON.stringify({ concatHits, mathHits }, null, 2));
console.log(`\nfiles affected (A): ${byFile(concatHits).length}   (B): ${byFile(mathHits).length}`);

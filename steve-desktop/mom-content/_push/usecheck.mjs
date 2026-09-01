// Did the repair insert a derived variable BEFORE the array it derives from?
// Seen in the killed run's diff: `$scenario = $scenarios[$i]` added above the `$scenarios = array(...)`
// definition, which yields an empty value and renders as nothing.
import fs from 'node:fs';
import { execSync } from 'node:child_process';

import { fileURLToPath } from 'url';
import { dirname, resolve } from 'path';
const REPO = resolve(dirname(fileURLToPath(import.meta.url)), '..', '..', '..');
const files = execSync('git status --porcelain steve-desktop/mom-content/questions/', { cwd: REPO, encoding: 'utf8' })
  .trim().split('\n').filter(Boolean).map((l) => l.slice(3).trim());

for (const rel of files) {
  // Strip // comments — a comment naming a variable is not a use of it, and counting it
  // produces false positives that bury the real ones.
  const src = fs.readFileSync(`${REPO}/${rel}`, 'utf8')
    .replace(/\r\n/g, '\n')
    .split('\n')
    .map((l) => l.replace(/^\s*\/\/.*$/, ''));
  const firstDef = {};
  src.forEach((l, i) => {
    const m = l.match(/^\s*(\$[A-Za-z_]\w*)\s*=/);
    if (m && firstDef[m[1]] === undefined) firstDef[m[1]] = i;
  });

  const problems = [];
  src.forEach((l, i) => {
    // a right-hand-side reference to $arr[...] or a bare $var
    const rhs = l.replace(/^\s*\$[A-Za-z_]\w*\s*=/, '');
    for (const m of rhs.matchAll(/(\$[A-Za-z_]\w*)/g)) {
      const v = m[1];
      if (firstDef[v] !== undefined && firstDef[v] > i) {
        problems.push(`${v} used line ${i + 1}, first defined line ${firstDef[v] + 1}`);
      }
    }
  });

  const uniq = [...new Set(problems)];
  if (uniq.length) {
    console.log(`\n*** ${rel.split('/').slice(-1)[0]}`);
    for (const p of uniq.slice(0, 4)) console.log(`      ${p}`);
  }
}
console.log('\nchecked', files.length, 'modified files');

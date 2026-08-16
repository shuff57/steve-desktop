// Build a compact answer crib for one assignment, so the run spends its budget driving the
// browser instead of reading 50KB of PHP. Four runs have now been killed; the last died having
// read ten sources and touched nothing.
//
// usage: node crib.mjs <manifest-abs-path> > crib.md
import fs from 'node:fs';

const ROOT = 'C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content';
const man = JSON.parse(fs.readFileSync(process.argv[2], 'utf8'));

const out = [];
out.push(`# Answer crib — ${man.name}`);
out.push('');
out.push('Derived from the sources so you do not have to read them. Each block gives the answer');
out.push('types, the seed arrays, and every `$answer[...]` assignment verbatim. The rendered page');
out.push('tells you which seed is live (`$i`); combine that with the arrays below to get the values.');
out.push('');

man.questions.forEach((q) => {
  const src = fs.readFileSync(`${ROOT}/${q.file_path}`, 'utf8').replace(/\r\n/g, '\n');
  const grab = (re) => (src.match(re) || []).map((s) => s.trim());

  const anstypes = (src.match(/\$anstypes\s*=\s*array\s*\([^)]*\)/) || [''])[0].trim();
  // seed arrays: name = array( ... ) on one line, numeric or short string
  const arrays = grab(/^\s*\$\w+\s*=\s*array\([^\n]{0,160}\)\s*$/gm).filter((l) => !/^\$anstypes/.test(l)).slice(0, 8);
  const idx = grab(/^\s*\$i\s*=\s*rand\([^\n]*\)\s*$/gm);
  const derived = grab(/^\s*\$\w+\s*=\s*(?!array\()[^\n]{0,110}$/gm)
    .filter((l) => /\$\w+\[|[-+*\/]|round\(|sqrt\(/.test(l))
    .filter((l) => !/^\$(css|rubric|responses|solutionguide|questions)\b/.test(l))
    .slice(0, 14);
  const answers = grab(/^\s*\$answer\[\d+\]\s*=[^\n]*$/gm);
  const tol = grab(/^\s*\$(abs|rel)tolerance\[\d+\]\s*=[^\n]*$/gm).slice(0, 3);
  const scoremethod = grab(/^\s*\$scoremethod\[\d+\]\s*=[^\n]*$/gm);

  out.push(`## slot ${q.slot} — ${q.file_path.split('/').pop()}  (qsetid ${q.qid}, ${q.points} pts)`);
  out.push('');
  out.push('```php');
  if (anstypes) out.push(anstypes);
  idx.forEach((l) => out.push(l));
  arrays.forEach((l) => out.push(l));
  derived.forEach((l) => out.push(l));
  answers.forEach((l) => out.push(l));
  scoremethod.forEach((l) => out.push(l));
  if (tol.length) out.push(tol[0] + (tol.length > 1 ? `   // (${tol.length} tolerances, all alike)` : ''));
  out.push('```');
  out.push('');
});

console.log(out.join('\n'));

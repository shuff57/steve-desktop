// Flags `a ' . $var` / `an ' . $var` in MOM question sources.
// An article in front of an interpolated noun is right for one randomised context and wrong for the
// next: it shipped "a order", "a customers" and "a students" on three separate questions.
import fs from 'node:fs';
import path from 'node:path';

const root = process.argv[2] || '.';
const re = /(?:^|[^A-Za-z])(an?) ' \. \$([A-Za-z_]+)/g;
const hits = [];

function walk(dir) {
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) walk(p);
    else if (e.name.endsWith('.php')) {
      const txt = fs.readFileSync(p, 'utf8');
      const lines = txt.split('\n');
      lines.forEach((line, i) => {
        re.lastIndex = 0;
        let m;
        while ((m = re.exec(line))) hits.push({ file: p.replace(/\\/g, '/'), line: i + 1, art: m[1], v: m[2] });
      });
    }
  }
}

walk(root);
for (const h of hits) console.log(`  ${h.art} ' . $${h.v}`.padEnd(28) + `${h.file}:${h.line}`);
console.log(`\n${hits.length} site(s). Each needs the variable to be SINGULAR and to start with a consonant sound.`);

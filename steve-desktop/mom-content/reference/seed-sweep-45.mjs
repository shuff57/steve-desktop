// Seed-sweep the 4-5 uniform questions through the MOM render sandbox.
//   node <this> [seedsPerQuestion]
import { readFileSync } from 'fs';
import { join } from 'path';
import { questionHealth } from '../../src/integrations/mom/health.ts';

const MOM = join(process.cwd(), 'steve-desktop/mom-content');
const DIR = join(MOM, 'questions/probability/uniform');
const SANDBOX = 'https://mom.huffpalmer.fyi/';
const SEEDS = Number(process.argv[2] || 25);
const CONCURRENCY = 6;

const files = ['q7-uniform-quartiles.php', 'q8-sanity-checks.php', 'q9-height-vs-probability.php', 'pre-frq-grade-a-uniform-reasoning.php'];
console.log(`seed-sweeping ${files.length} new 4-5 questions x ${SEEDS} seeds`);

function stripNoise(msg) {
  const kept = msg
    .split(' | ')
    .map((p) => p.trim())
    .filter((p) => p.length > 0)
    .filter((p) => !/Undefined global variable \$myright/i.test(p));
  return kept.length ? kept.join(' | ') : null;
}

async function renderOnce(src) {
  const res = await fetch(SANDBOX, { method: 'POST', headers: { 'Content-Type': 'text/plain' }, body: src });
  const html = await res.text();
  if (res.status !== 200) return [`sandbox HTTP ${res.status}`];
  const h = questionHealth(src, html);
  return h.errors.map(stripNoise).filter((e) => e !== null);
}

const queue = [];
for (const f of files) {
  const src = readFileSync(join(DIR, f), 'utf8');
  for (let s = 0; s < SEEDS; s++) queue.push({ f, src });
}

let done = 0;
const bad = [];
await Promise.all(
  Array.from({ length: CONCURRENCY }, async () => {
    while (queue.length) {
      const { f, src } = queue.shift();
      const errs = await renderOnce(src);
      if (errs.length) bad.push({ f, errs });
      done++;
    }
  }),
);

console.log(`\n${bad.length} failing render(s) across ${files.length * SEEDS} seed-renders`);
for (const b of bad) console.log(`  ${b.f}: ${JSON.stringify(b.errs)}`);
process.exit(bad.length ? 1 : 0);



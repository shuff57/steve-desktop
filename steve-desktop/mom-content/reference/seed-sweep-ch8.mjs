// Seed-sweep the chapter-8 hypothesis-testing questions through the MOM render sandbox.
// Each POST is a fresh server-side seed, so N renders = N seeds. Requires every render clean.
//   node mom-content/reference/seed-sweep-ch8.mjs [seedsPerQuestion]
import { readFileSync, readdirSync } from 'fs';
import { join } from 'path';
import { questionHealth, isEngineNoise } from '../../src/integrations/mom/health.ts';

const MOM = join(process.cwd(), 'mom-content');
const DIR = join(MOM, 'questions/stats-tests/hypothesis-testing');
const SANDBOX = 'https://mom.huffpalmer.fyi/';
const SEEDS = Number(process.argv[2] || 30);
const CONCURRENCY = 6;

const files = readdirSync(DIR).filter((f) => f.endsWith('.php')).sort();
console.log(`seed-sweeping ${files.length} questions x ${SEEDS} seeds (concurrency ${CONCURRENCY})`);

function stripNoise(msg) {
  const kept = msg
    .split(' | ')
    .map((p) => p.trim())
    .filter((p) => p.length > 0)
    .filter((p) => !/Undefined global variable \$myright/i.test(p));
  return kept.length ? kept.join(' | ') : null;
}

async function renderOnce(src, rel) {
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
      const errs = await renderOnce(src, f);
      if (errs.length) bad.push({ f, errs });
      done++;
      if (done % 100 === 0) console.log(`  ${done}/${files.length * SEEDS}`);
    }
  }),
);

console.log(`\n${bad.length} failing render(s) across ${files.length * SEEDS} seed-renders`);
for (const b of bad) console.log(`  ${b.f}: ${JSON.stringify(b.errs)}`);
process.exit(bad.length ? 1 : 0);

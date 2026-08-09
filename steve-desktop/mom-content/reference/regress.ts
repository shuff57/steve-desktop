// Replay the whole question bank through the render sandbox and diff against a stored baseline.
//
//   bun mom-content/reference/regress.ts                 # check every question against the baseline
//   bun mom-content/reference/regress.ts --write         # accept current results as the new baseline
//   bun mom-content/reference/regress.ts --only descriptive-stats   # substring filter on the path
//
// Why this exists: rules in learned-rules.md and transfer-rules.md are written from one live
// failure and then applied to every question written afterwards. Nothing has ever checked that a
// new rule does not contradict an older one -- the only questions rendered are the ones being
// touched. This replays all of them, so a rule change that quietly breaks a question written six
// sections ago shows up as a diff instead of as a student's wrong grade.
//
// It is the cheap half of the idea SkillOpt is built around: accept a change only when it does not
// make the held-out cases worse. No model calls, no labelled eval set -- the sandbox's own
// diagnostics are the score.
//
// Imports are relative and that is safe here because this file lives inside the repo; the same
// import from the session scratchpad would not resolve.
import { readFileSync, writeFileSync, existsSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';
import { questionHealth } from '../../src/integrations/mom/health.ts';

// IMathAS pipe-joins several diagnostics into one string, so a real error routinely arrives glued
// to engine noise: "Caught warning ... $myrights ... | Eeek.. unallowed macro nPr". Filtering the
// whole message because it contains the noise throws the error away with it; that is exactly how
// eight broken questions read as clean. Split the message, drop only the noise parts, keep the
// rest. The only true noise is the stateless-session $myrights warning, whichever file names it.
function stripNoise(msg: string): string | null {
  const kept = msg
    .split(' | ')
    .map((p) => p.trim())
    .filter((p) => p.length > 0)
    .filter((p) => !/Undefined global variable \$myright/i.test(p));
  return kept.length ? kept.join(' | ') : null;
}

const HERE = dirname(fileURLToPath(import.meta.url));
const MOM = join(HERE, '..');
const INDEX = join(HERE, 'question-index.json');
const BASELINE = join(HERE, 'regress-baseline.json');
const SANDBOX = 'https://mom.huffpalmer.fyi/';
const CONCURRENCY = 6;

type Result = { errors: string[]; warnings: string[] };

const write = process.argv.includes('--write');
const onlyIdx = process.argv.indexOf('--only');
const only = onlyIdx >= 0 ? process.argv[onlyIdx + 1] : '';

if (!existsSync(INDEX)) {
  console.error('no question-index.json -- run: python reference/build-question-index.py');
  process.exit(2);
}

const index: { path: string }[] = JSON.parse(readFileSync(INDEX, 'utf8'));
const targets = index.map((r) => r.path).filter((p) => !only || p.includes(only));
console.log(`replaying ${targets.length} questions through the sandbox (concurrency ${CONCURRENCY})`);

async function render(rel: string): Promise<Result> {
  const src = readFileSync(join(MOM, rel), 'utf8');
  // One retry: a transient sandbox hiccup must not read as a question regression.
  for (let attempt = 0; attempt < 2; attempt++) {
    try {
      const res = await fetch(SANDBOX, {
        method: 'POST',
        headers: { 'Content-Type': 'text/plain' },
        body: src,
      });
      const html = await res.text();
      if (res.status !== 200) {
        if (attempt === 0) continue;
        return { errors: [`sandbox HTTP ${res.status}`], warnings: [] };
      }
      const h = questionHealth(src, html);
      return {
        errors: h.errors.map(stripNoise).filter((e): e is string => e !== null).sort(),
        warnings: [...h.warnings].sort(),
      };
    } catch (err) {
      if (attempt === 1) return { errors: [`fetch failed: ${String(err)}`], warnings: [] };
    }
  }
  return { errors: ['unreachable'], warnings: [] };
}

const current: Record<string, Result> = {};
let done = 0;
const queue = [...targets];
await Promise.all(
  Array.from({ length: CONCURRENCY }, async () => {
    while (queue.length) {
      const rel = queue.shift()!;
      current[rel] = await render(rel);
      done++;
      if (done % 25 === 0) console.log(`  ${done}/${targets.length}`);
    }
  }),
);

const dirty = Object.entries(current).filter(([, r]) => r.errors.length || r.warnings.length);
console.log(`\n${dirty.length} of ${targets.length} questions report something`);

if (write) {
  writeFileSync(BASELINE, JSON.stringify(current, null, 1) + '\n');
  console.log(`baseline written: ${dirty.length} questions carry a known error or warning`);
  for (const [p, r] of dirty) console.log(`  ${p}\n    ${JSON.stringify(r)}`);
  process.exit(0);
}

if (!existsSync(BASELINE)) {
  console.error('no baseline yet -- run once with --write, and read what it records before trusting it');
  process.exit(2);
}

const base: Record<string, Result> = JSON.parse(readFileSync(BASELINE, 'utf8'));
const key = (r: Result) => JSON.stringify(r);
const regressed: string[] = [];
const fixed: string[] = [];
const added: string[] = [];

for (const [p, r] of Object.entries(current)) {
  if (!(p in base)) {
    added.push(p);
    if (r.errors.length || r.warnings.length) regressed.push(`${p}  NEW, and not clean: ${key(r)}`);
    continue;
  }
  if (key(r) === key(base[p])) continue;
  const worse = r.errors.length > base[p].errors.length || r.warnings.length > base[p].warnings.length;
  (worse ? regressed : fixed).push(`${p}\n    was ${key(base[p])}\n    now ${key(r)}`);
}
const missing = Object.keys(base).filter((p) => !(p in current));

if (added.length) console.log(`\n${added.length} question(s) new since the baseline`);
if (missing.length && !only) console.log(`${missing.length} question(s) in the baseline are gone from the bank`);
if (fixed.length) {
  console.log(`\n${fixed.length} improved:`);
  for (const line of fixed) console.log('  ' + line);
}
if (regressed.length) {
  console.log(`\nREGRESSED (${regressed.length}):`);
  for (const line of regressed) console.log('  ' + line);
  process.exit(1);
}
console.log('\nno regressions');

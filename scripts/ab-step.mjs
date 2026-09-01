#!/usr/bin/env node
// ab-step: run one agent-browser action and prove it changed the page.
//
// Why: an agent that cannot tell a no-op click from a real one will retry
// forever. This wraps each action in a before/after snapshot hash. Identical
// hash means the action accomplished nothing -> stop, do not retry.
//
//   node scripts/ab-step.mjs mom click @e25
//   node scripts/ab-step.mjs mom --reset      # clear the step counter
//
// exit 0 = page changed   exit 3 = stalled   exit 4 = over budget

import { execFileSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { readFileSync, writeFileSync, rmSync, existsSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const BUDGET = Number(process.env.AB_STEP_BUDGET ?? 40);
const [session, ...action] = process.argv.slice(2);
if (!session || (!action.length && action[0] !== '--reset')) {
  console.error('usage: ab-step.mjs <session> <agent-browser args...> | <session> --reset');
  process.exit(64);
}
const counter = join(tmpdir(), `ab-step-${session}.count`);

if (action[0] === '--reset') { rmSync(counter, { force: true }); console.log('reset'); process.exit(0); }

// On Windows the npm global bin is a .cmd shim, and since CVE-2024-27980 Node
// refuses to execFile a .cmd without shell:true -- which would re-open the
// quoting holes we are trying to avoid. Call the native exe the shim wraps.
function resolveBin() {
  if (process.env.AB_BIN) return process.env.AB_BIN;
  if (process.platform !== 'win32') return 'agent-browser';
  const roots = [process.env.npm_config_prefix, join(process.env.APPDATA ?? '', 'npm')].filter(Boolean);
  for (const r of roots) {
    const exe = join(r, 'node_modules', 'agent-browser', 'bin', 'agent-browser-win32-x64.exe');
    if (existsSync(exe)) return exe;
  }
  throw new Error('agent-browser native binary not found. Set AB_BIN to its full path.');
}
const BIN = resolveBin();
const ab = (...args) =>
  execFileSync(BIN, ['--session', session, ...args], { encoding: 'utf8', timeout: 120_000 });
const fingerprint = () => createHash('sha1').update(ab('snapshot', '-i')).digest('hex').slice(0, 12);

let n = 0;
try { n = Number(readFileSync(counter, 'utf8')) || 0; } catch {}
if (++n > BUDGET) {
  console.error(`OVER BUDGET: ${n} steps on session '${session}' (limit ${BUDGET}). Hand to a human.`);
  process.exit(4);
}
writeFileSync(counter, String(n));

const before = fingerprint();
let out = '';
try {
  out = ab(...action);
} catch (e) {
  console.error(`ACTION FAILED (step ${n}): ${action.join(' ')}`);
  console.error((e.stderr || e.message || '').toString().trim());
  process.exit(1);
}
// A navigation does not land instantly, so fingerprinting straight after the
// action reports every real click as a stall. Poll instead: a genuine change
// trips within a few hundred ms, a genuine no-op never trips at all.
const SETTLE_MS = Number(process.env.AB_SETTLE_MS ?? 4000);
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
let after = before;
for (const deadline = Date.now() + SETTLE_MS; Date.now() < deadline; ) {
  after = fingerprint();
  if (after !== before) break;
  await sleep(300);
}

process.stdout.write(out);
if (before === after) {
  console.error(`\nSTALLED (step ${n}/${BUDGET}): '${action.join(' ')}' left the page identical (${before}).`);
  console.error('Do NOT retry this action. Re-snapshot, pick a different target, or escalate.');
  process.exit(3);
}
console.error(`\nok (step ${n}/${BUDGET}): ${before} -> ${after}`);

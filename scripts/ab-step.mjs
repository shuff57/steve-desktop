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
// It also remembers. `--as <intent>` files the selector that just worked under a name,
// keyed by page type; `--use <intent>` plays it back on the next visit so the agent skips
// the snapshot entirely. The fingerprint above is what makes that safe to trust: a cached
// selector that clicks and moves nothing is stale, and gets struck or evicted on the spot.
//
//   node scripts/ab-step.mjs mom click "input[name=submitchanges]" --as save
//   node scripts/ab-step.mjs mom click --use save
//   node scripts/ab-step.mjs mom --profile    # what is known about the current page
//
// exit 0 = page changed   exit 3 = stalled   exit 4 = over budget
// exit 5 = known trap, refused   exit 6 = cache miss, take a snapshot

import { execFileSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { readFileSync, writeFileSync, rmSync, existsSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import * as profile from './site-profile.mjs';

const BUDGET = Number(process.env.AB_STEP_BUDGET ?? 40);
const [session, ...rawArgs] = process.argv.slice(2);
// --use/--as are ours, not agent-browser's, so strip them before the action is forwarded.
let useIntent = null, asIntent = null;
const action = [];
for (let i = 0; i < rawArgs.length; i++) {
  if (rawArgs[i] === '--use') { useIntent = rawArgs[++i]; continue; }
  if (rawArgs[i] === '--as') { asIntent = rawArgs[++i]; continue; }
  action.push(rawArgs[i]);
}
if (!session || (!action.length && !useIntent)) {
  console.error('usage: ab-step.mjs <session> <agent-browser args...> [--as <intent>|--use <intent>]');
  console.error('       ab-step.mjs <session> --reset | --profile');
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
const quiet = (fn, fallback) => { try { return fn(); } catch { return fallback; } };
const currentUrl = () => quiet(() => ab('get', 'url').trim(), '');
// A selector that resolves to nothing is structurally gone; one that resolves to many is
// too vague to file. Both are answered by the same cheap probe, no snapshot needed.
const countOf = (sel) => Number(quiet(() => ab('get', 'count', sel).trim().match(/[0-9]+/)?.[0], NaN));

// Reading a JSON file must not require a live browser. Probing for the current URL
// cold-starts one, which hung for two minutes against a closed session (2026-09-02), so
// this branch runs before the probe and takes an explicit URL when there is no session.
if (action[0] === '--profile') {
  const target = action[1] ?? currentUrl();
  if (!target) { console.error('--profile needs a URL when no session is live.'); process.exit(64); }
  console.log(profile.describe(target));
  process.exit(0);
}

const url = currentUrl();

// Refuse a control already recorded as a look-alike that saves nothing. This is the one
// check that must run before the step counter, because being talked out of a bad click is
// not a step -- charging for it would push a careful agent over budget for being careful.
const candidate = useIntent ? profile.resolve(url, useIntent)?.selector : action[1];
if (candidate) {
  const trap = profile.isTrap(url, candidate);
  if (trap) {
    console.error(`REFUSED: '${candidate}' is a known trap on ${url}`);
    console.error(`  ${trap.why}`);
    process.exit(5);
  }
}

if (useIntent) {
  const hit = profile.resolve(url, useIntent);
  if (!hit) {
    console.error(`CACHE MISS: nothing filed as '${useIntent}' for this page type.`);
    console.error(`Take a snapshot, find the target, then re-run with --as ${useIntent} to file it.`);
    process.exit(6);
  }
  action.push(hit.selector);
  console.error(`using '${useIntent}' = ${hit.selector} (${hit.hits} ok)`);
}

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
  if (useIntent) {
    // An error is not proof the selector is gone. Measured 2026-09-02 against the app's
    // embedded browser: a link with 4 verified successes failed because a CSS animation
    // (`div.container.rise-up-long`) was still covering it, and treating that as `gone`
    // evicted good knowledge on the first transient overlay. Probe before condemning --
    // absent is a fact, "the click errored" is only a symptom.
    const sel = profile.resolve(url, useIntent)?.selector ?? '';
    const gone = countOf(sel) === 0;
    const verdict = profile.record(url, useIntent, { moved: false, gone });
    const why = gone ? 'selector no longer in the DOM'
      : verdict === 'evicted' ? 'still present, but it has now failed twice here'
      : 'still present, so the error was situational';
    console.error(`profile: '${useIntent}' ${verdict} -- ${why}.`);
  }
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
  if (useIntent) {
    // Present-but-inert earns a strike; absent is unambiguous and evicts now.
    const gone = countOf(profile.resolve(url, useIntent)?.selector ?? '') === 0;
    const verdict = profile.record(url, useIntent, { moved: false, gone });
    const why = gone ? 'selector no longer in the DOM'
      : verdict === 'evicted' ? 'present but inert twice running'
      : 'present but inert';
    console.error(`profile: '${useIntent}' ${verdict} -- ${why}.`);
  } else {
    // Only selectors given explicitly are suspected. A cached one that stalls is handled
    // above as a stale entry: the site moved, which is a reason to forget the selector, not
    // to condemn the control forever.
    const verdict = profile.suspect(url, action[1], `clicked, page fingerprint unchanged (${before})`);
    if (verdict === 'trapped') console.error(`profile: '${action[1]}' filed as a TRAP -- stalled twice here.`);
    else if (verdict === 'noted') console.error(`profile: noted '${action[1]}' as suspect -- a second stall files it as a trap.`);
  }
  process.exit(3);
}
console.error(`\nok (step ${n}/${BUDGET}): ${before} -> ${after}`);

if (useIntent) profile.record(url, useIntent, { moved: true, gone: false });
if (asIntent) {
  // File it against the URL we were on when we acted, not wherever the click landed.
  const sel = action[1];
  const hits = countOf(sel);
  if (!sel || sel.startsWith('@')) {
    console.error(`not filed: '${sel}' is a snapshot ref, which is valid only for this one page load.`);
  } else if (hits !== 1) {
    console.error(`not filed: '${sel}' matches ${Number.isNaN(hits) ? 'an unknown number of' : hits} elements; a profile target must be unique.`);
  } else {
    const t = profile.learn(url, asIntent, sel);
    console.error(`profile: filed '${asIntent}' = ${sel} (${t.hits} ok)`);
  }
}

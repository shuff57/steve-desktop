#!/usr/bin/env node
// site-profile: remember what worked on a page so the next visit can skip the snapshot.
//
// The expensive part of driving a site is not the click, it is the snapshot that finds
// what to click -- ~1KB of accessibility tree per step, every step, forever. This stores
// the selector that actually worked, keyed by host+path (the page TYPE, query strings
// stripped, because a cid varies per course but the page shape does not), so a repeat
// visit reads a ~200 byte recipe instead.
//
// Cached selectors rot SILENTLY when a site is redesigned -- a stale button still exists
// and still clicks, it just does nothing -- so nothing here is trusted on the strength of
// merely resolving. Trust is earned from ab-step's fingerprint, on a two-tier rule:
//
//   selector no longer in the DOM     -> structural miss, evict immediately
//   selector present but page unmoved -> behavioral miss, one strike, evict on the second
//   page moved                        -> hits++
//
// The second tier is deliberately lenient: a Save button that is merely disabled right now
// stalls exactly like a dead one, and evicting real knowledge on that is worse than
// carrying a suspect entry for one more step.
//
//   node scripts/site-profile.mjs show <url>            what is known about this page type
//   node scripts/site-profile.mjs list                  every profile on disk
//   node scripts/site-profile.mjs trap <url> <sel> <why>  mark a selector as never-click
//
// Used as a library by ab-step.mjs; the CLI above is for inspection.

import { readFileSync, writeFileSync, mkdirSync, existsSync, readdirSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '_profile');
const today = () => new Date().toISOString().slice(0, 10);

/** host + pathname identify the page TYPE. Query strings carry the instance (which course,
 *  which assessment) and would shatter one profile into hundreds of identical ones. */
export function keyFor(url) {
  let u;
  try { u = new URL(url); } catch { return null; }
  const path = u.pathname.replace(/^\/+|\/+$/g, '') || '_root';
  const slug = path.replace(/\//g, '__').replace(/[^A-Za-z0-9._-]/g, '_');
  return { host: u.host, slug, match: `${u.host}/${path}`, file: join(ROOT, u.host, `${slug}.json`) };
}

export function load(url) {
  const k = keyFor(url);
  if (!k || !existsSync(k.file)) return null;
  try { return { ...k, data: JSON.parse(readFileSync(k.file, 'utf8')) }; } catch { return null; }
}

function blank(k) {
  return { match: k.match, created: today(), updated: today(), targets: {}, traps: [], suspects: {}, notes: [] };
}

function save(k, data) {
  data.updated = today();
  mkdirSync(dirname(k.file), { recursive: true });
  writeFileSync(k.file, JSON.stringify(data, null, 2) + '\n');
}

/** The selector to try for an intent, or null on a cache miss. */
export function resolve(url, intent) {
  const p = load(url);
  return p?.data.targets?.[intent] ?? null;
}

export function traps(url) {
  return load(url)?.data.traps ?? [];
}

/** True when `selector` is a known do-nothing control. The match is one-directional: the
 *  candidate must CONTAIN the recorded trap, so a trap filed as `input[name=justupdatelibs]`
 *  still catches `form input[name=justupdatelibs]`.
 *
 *  Matching the other way round looks symmetric and is not. A trap of `a[href="#"]` contains
 *  the string `a`, so a bare `a` candidate was refused as a trap (measured 2026-09-02) -- any
 *  short selector gets swallowed by any longer trap that happens to contain it. Missing a trap
 *  is the cheaper error anyway: the fingerprint catches an inert click one step later, whereas
 *  a false refusal blocks real work with no way around it. */
export function isTrap(url, selector) {
  return traps(url).find((t) => selector === t.selector || selector.includes(t.selector)) ?? null;
}

export function learn(url, intent, selector) {
  const k = keyFor(url);
  if (!k) return null;
  const data = load(url)?.data ?? blank(k);
  const prior = data.targets[intent];
  data.targets[intent] = {
    selector,
    hits: prior?.selector === selector ? (prior.hits ?? 0) + 1 : 1,
    strikes: 0,
    learned: prior?.selector === selector ? prior.learned : today(),
    lastOk: today(),
  };
  save(k, data);
  return data.targets[intent];
}

/** Record what the fingerprint said. `gone` means the selector no longer resolves at all.
 *  Returns 'kept' | 'struck' | 'evicted' so the caller can say what happened. */
export function record(url, intent, { moved, gone }) {
  const k = keyFor(url);
  const p = load(url);
  if (!k || !p?.data.targets?.[intent]) return 'kept';
  const t = p.data.targets[intent];
  if (moved) {
    t.hits = (t.hits ?? 0) + 1; t.strikes = 0; t.lastOk = today();
    save(k, p.data); return 'kept';
  }
  if (gone || (t.strikes ?? 0) >= 1) {
    delete p.data.targets[intent];
    save(k, p.data); return 'evicted';
  }
  t.strikes = (t.strikes ?? 0) + 1;
  save(k, p.data); return 'struck';
}

/** Record that `selector` stalled. A stall is evidence, not proof: a Save button that is
 *  disabled right now stalls exactly like one that never worked, and a trap refuses forever
 *  (ab-step exit 5). So the first stall only makes a note; the second promotes it to a trap.
 *  Same asymmetry as eviction -- a false trap blocks real work, while a missed trap costs one
 *  wasted step that the fingerprint catches anyway.
 *  Returns 'noted' | 'trapped'. */
export function suspect(url, selector, why) {
  const k = keyFor(url);
  if (!k || !selector || selector.startsWith('@')) return 'skipped';
  const data = load(url)?.data ?? blank(k);
  data.suspects = data.suspects ?? {};
  const n = (data.suspects[selector] ?? 0) + 1;
  if (n >= 2) {
    delete data.suspects[selector];
    if (!data.traps.some((t) => t.selector === selector)) {
      data.traps.push({ selector, why, added: today(), evidence: `stalled ${n}x` });
    }
    save(k, data);
    return 'trapped';
  }
  data.suspects[selector] = n;
  save(k, data);
  return 'noted';
}

export function addTrap(url, selector, why) {
  const k = keyFor(url);
  if (!k) return null;
  const data = load(url)?.data ?? blank(k);
  if (!data.traps.some((t) => t.selector === selector)) data.traps.push({ selector, why, added: today() });
  save(k, data);
  return data.traps;
}

/** Compact, agent-readable dump -- this is what replaces a snapshot on a warm page. */
export function describe(url) {
  const p = load(url);
  if (!p) return `no profile for ${keyFor(url)?.match ?? url}`;
  const lines = [`# ${p.data.match}  (updated ${p.data.updated})`];
  const t = Object.entries(p.data.targets);
  lines.push(t.length ? 'targets:' : 'targets: (none learned yet)');
  for (const [name, v] of t) lines.push(`  ${name} = ${v.selector}   [${v.hits} ok${v.strikes ? `, ${v.strikes} strike` : ''}]`);
  if (p.data.traps.length) {
    lines.push('traps (never click):');
    for (const x of p.data.traps) lines.push(`  ${x.selector} -- ${x.why}`);
  }
  const sus = Object.entries(p.data.suspects ?? {});
  if (sus.length) lines.push(`suspect (stalled once, trap on the next): ${sus.map(([x]) => x).join(', ')}`);
  for (const n of p.data.notes) lines.push(`note: ${n}`);
  return lines.join('\n');
}

function listAll() {
  if (!existsSync(ROOT)) return [];
  return readdirSync(ROOT, { withFileTypes: true })
    .filter((d) => d.isDirectory())
    .flatMap((d) => readdirSync(join(ROOT, d.name)).filter((f) => f.endsWith('.json'))
      .map((f) => join(ROOT, d.name, f)));
}

if (process.argv[1] && fileURLToPath(import.meta.url) === process.argv[1]) {
  const [cmd, ...rest] = process.argv.slice(2);
  if (cmd === 'show') console.log(describe(rest[0]));
  else if (cmd === 'list') {
    const files = listAll();
    if (!files.length) console.log('no profiles yet');
    for (const f of files) {
      const d = JSON.parse(readFileSync(f, 'utf8'));
      console.log(`${d.match}  ${Object.keys(d.targets).length} targets, ${d.traps.length} traps  (${d.updated})`);
    }
  } else if (cmd === 'trap') { addTrap(rest[0], rest[1], rest.slice(2).join(' ')); console.log(describe(rest[0])); }
  else { console.error('usage: site-profile.mjs show <url> | list | trap <url> <selector> <why>'); process.exit(64); }
}

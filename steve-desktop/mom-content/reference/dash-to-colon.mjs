// Bank-wide em-dash removal. Written for the 2026-09-02 sweep after the prose rule landed in
// skills/mom-question; kept because the same job recurs whenever questions arrive from elsewhere.
//
//   node reference/dash-to-colon.mjs <dir>            dry run, prints a categorised report
//   node reference/dash-to-colon.mjs <dir> --write    apply
//
// It is NOT a blind s/dash/colon/. Three things break under that, all of them found by reading the
// bank rather than by reasoning about it:
//
//   1. EN DASHES ARE RANGES, not punctuation. `$b4 &ndash; $e4`, `a–b`, `' . $lo[$i] . '&ndash;'`
//      are class intervals in frequency tables. A colon there changes what the student reads and
//      what the table means. En dashes are never touched.
//   2. A PAIRED DASH BRACKETS AN ASIDE -- `X, an aside, Y` in dash form. Replacing both with colons
//      gives `X: aside: Y`, which is not English. Pairs get commas instead, which is the only
//      correct recast for that shape. Counted and reported separately so the deviation is visible.
//   3. A DASH ALREADY FOLLOWED BY A COLON stacks into `a: b:`. Those are left for a human.
//
// Everything else -- a single dash inside one sentence -- becomes a colon, which is what it was
// standing in for.
import { readFileSync, writeFileSync, readdirSync, statSync } from 'node:fs';
import path from 'node:path';

const ROOT = process.argv[2] || 'questions';
const WRITE = process.argv.includes('--write');

const EM = '—';
// The em-dash family only. `&ndash;` and – are deliberately absent: see note 1 above.
const DASH_G = new RegExp(`&mdash;|${EM}|(?<=[A-Za-z0-9)\\]"'])\\s--\\s`, 'g');
// A run of three or more hyphens is a comment rule (`// --- Incident C`), not punctuation.
const RULE = /-{3,}/;

const files = [];
(function walk(d) {
  for (const e of readdirSync(d, { withFileTypes: true })) {
    const p = path.join(d, e.name);
    if (e.isDirectory()) walk(p);
    else if (e.name.endsWith('.php')) files.push(p);
  }
})(ROOT);

const stats = { colon: 0, pairComma: 0, skippedColonStack: 0, skippedRule: 0, filesChanged: 0 };
const samples = { colon: [], pairComma: [], skippedColonStack: [] };

// A pair is two dashes in the same sentence: no sentence-ending punctuation between them, and
// close enough together to be an aside rather than two unrelated dashes in a long solution guide.
// An aside is SHORT and unbroken. The first cut of this used `[.!?]\s` as the sentence test and
// mis-paired 466 dashes, because `translate the question.</span>` ends a sentence with a TAG rather
// than a space, and because two dashes on consecutive comment lines have only a newline between
// them. Any of these between two dashes means they are two separate dashes, not a pair.
const PAIR_GAP = 90;
// Quotes count as a break too: `array("Yes — a treatment", "No — only observed")` is two separate
// strings each wanting its own colon, not one aside spanning both.
const BREAK = /[\n.!?;:"']|<\/?p\b|<\/?li\b|<\/?span\b|<br|<\/?td\b|<\/?div\b/i;
function classify(text) {
  const hits = [...text.matchAll(DASH_G)];
  const kind = new Array(hits.length).fill('colon');
  for (let i = 0; i < hits.length - 1; i++) {
    if (kind[i] !== 'colon') continue;
    const a = hits[i], b = hits[i + 1];
    const between = text.slice(a.index + a[0].length, b.index);
    if (between.length > PAIR_GAP) continue;
    if (BREAK.test(between)) continue;
    kind[i] = 'pairOpen'; kind[i + 1] = 'pairClose';
  }
  return { hits, kind };
}

for (const f of files) {
  const src = readFileSync(f, 'utf8');
  const { hits, kind } = classify(src);
  if (!hits.length) continue;
  let out = '', last = 0, changed = false;
  for (let i = 0; i < hits.length; i++) {
    const h = hits[i];
    const before = src.slice(Math.max(0, h.index - 60), h.index);
    const after = src.slice(h.index + h[0].length, h.index + h[0].length + 60);
    const ctx = (before.slice(-45) + '[' + h[0].trim() + ']' + after.slice(0, 45)).replace(/\s+/g, ' ');

    let repl = null;
    if (RULE.test(h[0]) || /-{2,}\s*$/.test(before)) {
      stats.skippedRule++;
    } else if (/^\s*:/.test(after)) {
      stats.skippedColonStack++;
      if (samples.skippedColonStack.length < 6) samples.skippedColonStack.push(`${f}: ${ctx}`);
    } else if (kind[i] === 'colon') {
      repl = ':';
      stats.colon++;
      if (samples.colon.length < 8) samples.colon.push(`${f}: ${ctx}`);
    } else {
      repl = ',';
      stats.pairComma++;
      if (samples.pairComma.length < 8) samples.pairComma.push(`${f}: ${ctx}`);
    }

    if (repl !== null) {
      // Absorb the whitespace on both sides: `X &mdash; Y` -> `X: Y`, never `X : Y`.
      let s = h.index, e = h.index + h[0].length;
      while (s > 0 && /[ \t]/.test(src[s - 1])) s--;
      while (e < src.length && /[ \t]/.test(src[e])) e++;
      out += src.slice(last, s) + repl + (e < src.length && src[e] !== '\n' ? ' ' : '');
      last = e;
      changed = true;
    }
  }
  out += src.slice(last);
  if (changed) {
    stats.filesChanged++;
    if (WRITE) writeFileSync(f, out, 'utf8');
  }
}

console.log(`${files.length} files scanned under ${ROOT}${WRITE ? '' : '   (DRY RUN)'}\n`);
console.log(`  ${String(stats.colon).padStart(5)}  dash -> colon        (single dash inside one sentence)`);
console.log(`  ${String(stats.pairComma).padStart(5)}  dash -> comma        (paired dash bracketing an aside)`);
console.log(`  ${String(stats.skippedColonStack).padStart(5)}  skipped              (a colon already follows; would stack)`);
console.log(`  ${String(stats.skippedRule).padStart(5)}  skipped              (comment rule, not punctuation)`);
console.log(`  ${String(stats.filesChanged).padStart(5)}  files ${WRITE ? 'written' : 'would change'}\n`);
for (const [k, v] of Object.entries(samples)) {
  if (!v.length) continue;
  console.log(`--- ${k} ---`);
  v.forEach((x) => console.log('  ' + x));
  console.log();
}

#!/usr/bin/env node
// Open the MOM + Aeries tabs for one class period inside the already-running S.T.E.V.E app,
// log in via the app's own saved credentials (never read by this script), and land each tab
// on that period's course / gradebook.
//
// Assumes the app is ALREADY running with its window NOT minimized -- see
// .claude/skills/verify/SKILL.md ("minimized window" gotcha) and
// .claude/skills/open-period/SKILL.md for that preflight. This script only drives the
// already-running app over CDP; it does not launch anything itself.
//
// Usage: node scripts/open-period.mjs <period> [--port 9222] [--refresh]
//   --refresh   ignore the cached period->course/gradebook map and re-scrape both sites

import { writeFile, readFile, mkdir } from 'node:fs/promises';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const rawArgs = process.argv.slice(2);
const refresh = rawArgs.includes('--refresh');
const portIdx = rawArgs.indexOf('--port');
const port = portIdx >= 0 ? rawArgs[portIdx + 1] : '9222';
const period = rawArgs.find((a, i) => !a.startsWith('--') && rawArgs[i - 1] !== '--port');
if (!period) {
  console.error('usage: node scripts/open-period.mjs <period> [--port 9222] [--refresh]');
  process.exit(1);
}

const HERE = dirname(fileURLToPath(import.meta.url));
// Gitignored under the existing "scripts/_profile/" rule (scraped-site-content cache) --
// see .gitignore, same category as site-profile.mjs's selector cache.
const CACHE_FILE = join(HERE, '_profile', 'period-map.json');

const MOM_URL = 'https://www.myopenmath.com/index.php';
// The bare root '/' shows a portal chooser (Admin/Teacher/Parent) instead of auto-continuing
// to the teacher home -- confirmed live 2026-09-04: only the fresh-login redirect lands on
// Default.aspx automatically, a plain re-navigate to '/' does not, even with a live session.
const AERIES_URL = 'https://chicousd.aeries.net/teacher/Default.aspx';

// ── raw CDP ─────────────────────────────────────────────────────────────────────────────────
// Playwright's connectOverCDP crashes on this app's shared workers (see
// gradebook/scripts/gb-compare-cdp.mjs for the same finding), so talk to one page target's own
// webSocketDebuggerUrl directly instead of Playwright's browser-level connect.
async function pageTargets() {
  const list = await fetch(`http://127.0.0.1:${port}/json/list`).then((r) => r.json());
  return list.filter((x) => x.type === 'page');
}

async function findTarget(urlSubstring) {
  const targets = await pageTargets();
  const t = targets.find((x) => x.url.includes(urlSubstring));
  if (!t) throw new Error(`No page target matching "${urlSubstring}". Open tabs: ${targets.map((x) => x.url).join(', ')}`);
  return t;
}

async function withPage(urlSubstring, fn) {
  const target = await findTarget(urlSubstring);
  const ws = new WebSocket(target.webSocketDebuggerUrl);
  await new Promise((resolve, reject) => {
    ws.addEventListener('open', resolve, { once: true });
    ws.addEventListener('error', reject, { once: true });
  });
  let id = 0;
  const pending = new Map();
  ws.addEventListener('message', (ev) => {
    const msg = JSON.parse(ev.data);
    if (msg.id && pending.has(msg.id)) {
      pending.get(msg.id)(msg);
      pending.delete(msg.id);
    }
  });
  const send = (method, params) => {
    const myId = ++id;
    return new Promise((resolve) => {
      pending.set(myId, resolve);
      ws.send(JSON.stringify({ id: myId, method, params }));
    });
  };
  try {
    return await fn(send);
  } finally {
    ws.close();
  }
}

async function evalOnPage(urlSubstring, expression, { awaitPromise = true } = {}) {
  return withPage(urlSubstring, async (send) => {
    await send('Runtime.enable', {});
    const result = await send('Runtime.evaluate', { expression, awaitPromise, returnByValue: true });
    if (result.result?.exceptionDetails) {
      const ex = result.result.exceptionDetails;
      throw new Error(`Page eval failed: ${ex.exception?.description || ex.text}`);
    }
    return result.result?.result?.value;
  });
}

// ── app control ─────────────────────────────────────────────────────────────────────────────
// window.__steveControl is exposed by Browser.svelte specifically for scripted driving
// (listTabs/newTab/navigate/login) -- see src/pages/Browser.svelte around "steveWindow.__steveControl".
const shellEval = (expr, opts) => evalOnPage('localhost:5174', expr, opts);

async function getOrCreateTab(urlSubstring, canonicalUrl) {
  const tabs = JSON.parse(await shellEval('JSON.stringify(window.__steveControl.listTabs())'));
  const existing = tabs.find((t) => (t.url || '').includes(urlSubstring));
  if (existing) {
    await shellEval(`window.__steveControl.navigate(${JSON.stringify(existing.id)}, ${JSON.stringify(canonicalUrl)})`);
    return existing.id;
  }
  const id = await shellEval(`window.__steveControl.newTab(${JSON.stringify(canonicalUrl)})`);
  for (let i = 0; i < 20; i++) {
    const targets = await pageTargets();
    if (targets.some((t) => t.url.includes(urlSubstring))) return id;
    await new Promise((r) => setTimeout(r, 500));
  }
  throw new Error(`Tab for ${urlSubstring} never registered as a CDP target -- is the app window minimized?`);
}

async function loginAndSubmit(tabId, urlSubstring) {
  await shellEval(`window.__steveControl.login(${JSON.stringify(tabId)})`).catch(() => {});
  await new Promise((r) => setTimeout(r, 800));
  // loginNow() fills AND is supposed to submit, but skips the submit whenever the on-load
  // autofill already pre-filled the fields first (autofill.ts: "don't overwrite what you
  // typed" treats the app's own pre-fill the same as the user's typing). Confirmed live
  // 2026-09-04 on MOM: fields held the right values but the form just sat there. Belt and
  // suspenders -- submit ourselves if a filled, still-present password field is found.
  await evalOnPage(
    urlSubstring,
    `(() => {
      const pw = document.querySelector('input[type=password]');
      if (pw && pw.value && pw.form) { pw.form.requestSubmit(); return 'submitted'; }
      return 'nothing to submit';
    })()`
  ).catch(() => {});
  await new Promise((r) => setTimeout(r, 1500));
}

// ── period discovery ────────────────────────────────────────────────────────────────────────
// Both sites put the period number as a leading token on the course/class name
// ("3 Intro to Stats - 2627", Aeries row "3 YearInt Statistics..."). Confirmed live 2026-09-04.
async function discoverMom() {
  const links = JSON.parse(
    await evalOnPage(
      'myopenmath.com',
      `JSON.stringify([...document.querySelectorAll('a[href*="course/course.php?folder=0&cid="]')]
        .map(a => ({ text: a.textContent.trim(), href: a.getAttribute('href') })))`
    )
  );
  const byPeriod = {};
  for (const { text, href } of links) {
    const m = /^(\d+)\s/.exec(text);
    if (!m) continue; // "Global Question Class", "IM3-Huff-25-26", etc: no period prefix, skip
    const cidMatch = /cid=(\d+)/.exec(href);
    if (!cidMatch) continue;
    byPeriod[m[1]] = { cid: cidMatch[1], name: text };
  }
  return byPeriod;
}

async function discoverAeries() {
  const links = JSON.parse(
    await evalOnPage(
      'aeries.net',
      `JSON.stringify([...document.querySelectorAll('a[href*="gradebook/"]')].map(a => {
        const row = a.closest('tr') || a.closest('li') || a.parentElement;
        return { text: a.textContent.trim(), href: a.getAttribute('href'), rowText: (row ? row.textContent : '').replace(/\\s+/g, ' ').trim() };
      }))`
    )
  );
  const byPeriod = {};
  for (const { text, href, rowText } of links) {
    const m = /^(\d+)\s/.exec(rowText);
    const gbMatch = /gradebook\/(\d+)/.exec(href);
    if (!m || !gbMatch) continue;
    byPeriod[m[1]] = { gradebook: gbMatch[1], name: text };
  }
  return byPeriod;
}

async function loadCache() {
  try {
    return JSON.parse(await readFile(CACHE_FILE, 'utf8'));
  } catch {
    return {};
  }
}
async function saveCache(data) {
  await mkdir(dirname(CACHE_FILE), { recursive: true });
  await writeFile(CACHE_FILE, JSON.stringify(data, null, 2));
}

// ── main ────────────────────────────────────────────────────────────────────────────────────
console.log(`Opening period ${period} (CDP port ${port})...`);

const momTabId = await getOrCreateTab('myopenmath.com', MOM_URL);
const aeriesTabId = await getOrCreateTab('aeries.net', AERIES_URL);

await loginAndSubmit(momTabId, 'myopenmath.com');
await loginAndSubmit(aeriesTabId, 'aeries.net');

let cache = refresh ? {} : await loadCache();
if (refresh || !cache.mom?.[period]) cache.mom = await discoverMom();
if (refresh || !cache.aeries?.[period]) cache.aeries = await discoverAeries();
await saveCache(cache);

const mom = cache.mom[period];
const ae = cache.aeries[period];

if (mom) {
  // The Gradebook view, not the course home page -- "open the correct gb" means this one, and
  // it's also the page gb-compare-cdp.mjs/gb-new-assignment need (#availshow, span.cattothdr
  // only exist here). Confirmed live 2026-09-04: course.php landed the compare script on
  // "0 categories found" even though the course itself was right.
  await shellEval(
    `window.__steveControl.navigate(${JSON.stringify(momTabId)}, ${JSON.stringify(`https://www.myopenmath.com/course/gradebook.php?cid=${mom.cid}`)})`
  );
  console.log(`MOM    : ${mom.name}  (cid ${mom.cid})`);
} else {
  console.log(`MOM    : no course with a "${period}" prefix -- left on the dashboard.`);
}

if (ae) {
  // Needs the /teacher/ prefix -- the bare /gradebook/... path 404s outright. Confirmed live
  // 2026-09-04 (a first, coincidental redirect made this look fine once; it isn't).
  await shellEval(
    `window.__steveControl.navigate(${JSON.stringify(aeriesTabId)}, ${JSON.stringify(`https://chicousd.aeries.net/teacher/gradebook/${ae.gradebook}/F/ScoresByClass`)})`
  );
  console.log(`Aeries : ${ae.name}  (gradebook ${ae.gradebook})`);
} else {
  console.log(`Aeries : no gradebook with a "${period}" prefix -- left on the teacher home page.`);
}

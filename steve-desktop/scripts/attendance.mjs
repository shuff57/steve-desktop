// Cross-check a MyOpenMath section's login log against an Aeries attendance period, and
// (only with --apply) tick Absent for students with no login inside the class window.
//
// Read-only by default. Everything it needs about WHICH day and WHICH period comes off the
// Aeries page as it is currently loaded — set those in the app, not here, so the thing you
// see on screen is the thing that gets written.
//
// SAFETY, learned live 2026-08-25:
//  * Aeries has NO save button. Every `chkA` carries an inline onclick calling TeacherSetATT(),
//    which posts immediately. A click IS the write. There is no review step and no undo.
//  * The page may already read "Attendance submitted at <time>" — the teacher took attendance
//    by hand. Ticking a box then AMENDS a filed record. --apply refuses unless you also pass
//    --amend, so that can never happen by accident.
//  * A MOM login is a proxy for presence, not presence. Present-but-on-paper, a dead
//    Chromebook, or finishing early all look identical to absent. Never let this script have
//    the last word on a real student.
import { createRequire } from 'module';
const require = createRequire('C:/Users/shuff57/Documents/GitHub/bookSHelf/package.json');
const { chromium } = require('playwright');

const arg = (k, d) => { const i = process.argv.indexOf(k); return i > 0 ? process.argv[i + 1] : d; };
const has = (k) => process.argv.includes(k);
const cid = arg('--cid');
const port = arg('--port', '9222');
if (!cid) {
  console.error('usage: node attendance.mjs --cid <momCourseId> [--port 9222] [--apply --amend]');
  process.exit(1);
}

const browser = await chromium.connectOverCDP(`http://127.0.0.1:${port}`);
const pages = browser.contexts()[0].pages();
const ae = pages.find((p) => p.url().includes('aeries.net'));
const mom = pages.find((p) => p.url().includes('myopenmath'));
if (!ae) throw new Error('No Aeries tab open in the app.');
if (!mom) throw new Error('No MyOpenMath tab open in the app.');

// ── 1. Aeries: the day, the period window, and the roster ──────────────────────────────────
// The period dropdown's own label ("4 (10:11 AM - 11:40 AM)") is the authoritative window.
// Reading it beats a bell-schedule file, which cannot know about an assembly or a minimum day.
const aeries = await ae.evaluate(() => {
  const pick = (suffix) => {
    const s = [...document.querySelectorAll('select')].find((x) => x.id.endsWith(suffix));
    return s ? s.options[s.selectedIndex]?.text?.trim() : null;
  };
  const rows = [];
  document.querySelectorAll('input[id$=chkA]').forEach((a) => {
    const tr = a.closest('tr');
    if (!tr) return;
    // Drop the empty spacer cells before indexing. The row is [seat, studentId, name, grade]
    // only AFTER that filter — indexing the raw cells puts the student ID where the name
    // should be, and every name then matches nothing, which reads as "nobody is enrolled".
    const cells = [...tr.querySelectorAll('td')]
      .map((td) => (td.innerText || '').replace(/\s+/g, ' ').trim())
      .filter(Boolean);
    rows.push({
      seat: cells[0], sid: cells[1], name: cells[2],
      absent: a.checked, tardy: tr.querySelector('input[id$=chkT]')?.checked ?? null,
      chkId: a.id,
    });
  });
  return {
    date: pick('ddlDate'),
    period: pick('PeriodList'),
    // Aeries writes this banner TWO different ways depending on the render path, and both were
    // seen on the same page minutes apart (2026-08-25):
    //   initial page load     -> "Attendance submitted at 08/25/2026 11:51 AM"
    //   period-change postback-> "Attendance for today was submitted at 8/25/2026 11:51 AM."
    // Matching only one of them reports "not yet submitted" for a period that WAS submitted,
    // which silently disarms the --amend guard below. Match both.
    submitted: (document.body.innerText.match(/Attendance (?:for today )?(?:was )?submitted at[^\n]*/i) || [''])[0].trim(),
    rows,
  };
});

const win = /\((\d+):(\d+)\s*(AM|PM)\s*-\s*(\d+):(\d+)\s*(AM|PM)\)/i.exec(aeries.period || '');
if (!win) throw new Error(`Could not parse a class window out of period "${aeries.period}".`);
const to24 = (h, m, ap) => ((+h % 12) + (/pm/i.test(ap) ? 12 : 0)) * 60 + +m;
const winFrom = to24(win[1], win[2], win[3]);
const winTo = to24(win[4], win[5], win[6]);

// MOM's login log prints "Tuesday, August 25, 2026" — build that exact string from the Aeries
// date so the two are compared as the same day, with no timezone arithmetic anywhere.
const [mo, da, yr] = (aeries.date || '').split('/').map(Number);
const dayString = new Date(yr, mo - 1, da).toLocaleDateString('en-US', {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
});

// ── 2. MyOpenMath: every student's full login history for that day ─────────────────────────
// The roster's "Last Access" column is NOT enough: it shows only the MOST RECENT login, so a
// student who worked in class and logged in again at lunch reads as absent. The per-student
// login log is the only signal that answers "did they log in DURING the period".
await mom.goto(`https://www.myopenmath.com/course/listusers.php?cid=${cid}`, { waitUntil: 'domcontentloaded' });
if (await mom.evaluate(() => !!document.querySelector('input[name=username]') && !/Log ?Out/i.test(document.body.innerText))) {
  throw new Error('MyOpenMath session is dead. Log in via the app Browse tab (Passwords -> Log in now).');
}
// Each roster row carries the Login Log link twice; dedupe by uid.
const uids = [...new Set(await mom.evaluate(() =>
  [...document.querySelectorAll('a[href*="viewloginlog.php"]')].map((a) => /uid=(\d+)/.exec(a.getAttribute('href'))[1])))];

const momStudents = [];
for (const uid of uids) {
  await mom.goto(`https://www.myopenmath.com/course/viewloginlog.php?cid=${cid}&uid=${uid}`, { waitUntil: 'domcontentloaded' });
  // Trust the page's own "Login Log for <name>" heading rather than the roster row order —
  // pairing by position breaks the moment a row is added or the sort changes.
  const d = await mom.evaluate(() => {
    const t = document.body.innerText;
    return {
      who: (t.match(/Login Log for ([^\n]+)/) || [, ''])[1].trim(),
      lines: t.split('\n').map((x) => x.trim()).filter((x) => /^\w+day, \w+ \d+, \d{4}, \d+:\d+ (am|pm)$/i.test(x)),
    };
  });
  const today = d.lines
    .map((l) => {
      const m = /^(.+), (\d+):(\d+) (am|pm)$/i.exec(l);
      return { day: m[1], t: to24(m[2], m[3], m[4]), clock: `${m[2]}:${m[3]}${m[4]}` };
    })
    .filter((x) => x.day === dayString);
  momStudents.push({ uid, who: d.who, today, inWindow: today.filter((x) => x.t >= winFrom && x.t <= winTo) });
}

// ── 3. Match Aeries names to MOM names ─────────────────────────────────────────────────────
// Aeries: "Gonzalez, Elizabeth G. (Ellie)" / "Ando, Benjamen M." / "Ecklund, Logan D. NEW"
// MOM:    "Gonzalez, Ellie"               / "Ando, Ben"          / (absent from the course)
// So: last name must agree, and the MOM first name must equal the legal first name, the
// parenthesised nickname, or be a prefix of the legal one ("Ben" -> "Benjamen").
const norm = (s) => (s || '').toLowerCase().replace(/[^a-z, ]/g, ' ').replace(/\s+/g, ' ').trim();
function parseAeries(n) {
  const nick = norm((/\(([^)]+)\)/.exec(n) || [, ''])[1]);
  const [last, rest = ''] = norm(n.replace(/\(.*?\)/g, ' ').replace(/\bNEW\b/g, ' ')).split(',');
  return { last: (last || '').trim(), first: (rest.trim().split(' ')[0] || ''), nick };
}
function parseMom(n) {
  const [last, rest = ''] = norm(n).split(',');
  return { last: (last || '').trim(), first: (rest.trim().split(' ')[0] || '') };
}
const matches = (a, m) =>
  !!a.last && a.last === m.last &&
  (m.first === a.first || m.first === a.nick ||
    (a.first.startsWith(m.first) && m.first.length >= 3) ||
    (m.first.startsWith(a.first) && a.first.length >= 3));

const report = aeries.rows.map((r) => {
  const a = parseAeries(r.name);
  const hits = momStudents.filter((m) => matches(a, parseMom(m.who)));
  if (hits.length === 0) return { ...r, verdict: 'NO MOM ACCOUNT', detail: 'not enrolled in this MOM course' };
  // Two MOM students matching one Aeries row means the name rule is not sharp enough here.
  // Refusing is the only safe answer: guessing marks the wrong child absent.
  if (hits.length > 1) return { ...r, verdict: 'AMBIGUOUS', detail: hits.map((h) => h.who).join(' | ') };
  const m = hits[0];
  return m.inWindow.length > 0
    ? { ...r, verdict: 'present', detail: m.inWindow.map((x) => x.clock).join(', ') }
    : {
        ...r, verdict: 'NO LOGIN IN WINDOW',
        detail: m.today.length ? `logged in ${m.today.map((x) => x.clock).join(', ')} — outside` : 'no login at all that day',
      };
});

const unmatchedMom = momStudents.filter((m) => !aeries.rows.some((r) => matches(parseAeries(r.name), parseMom(m.who))));

console.log(`\nAeries : ${aeries.date}  period ${aeries.period}`);
console.log(`MOM    : cid ${cid}  (${momStudents.length} enrolled, ${aeries.rows.length} on the Aeries roster)`);
console.log(aeries.submitted ? `BANNER : ${aeries.submitted}   <-- already filed; ticking a box AMENDS it` : 'BANNER : not yet submitted');
console.log('');
for (const r of report) {
  const flag = r.verdict === 'present' ? '  ok ' : r.verdict === 'NO LOGIN IN WINDOW' ? ' ABS!' : ' ??  ';
  console.log(`${flag} ${String(r.seat).padStart(3)} ${r.name.padEnd(34)} ${r.verdict.padEnd(20)} ${r.detail}`);
}
if (unmatchedMom.length) console.log(`\nIn MOM but not on this Aeries roster: ${unmatchedMom.map((m) => m.who).join(', ')}`);

const toMark = report.filter((r) => r.verdict === 'NO LOGIN IN WINDOW' && !r.absent);
console.log(`\n${toMark.length} would be marked absent: ${toMark.map((r) => r.name).join(', ') || '(none)'}`);
report
  .filter((r) => r.verdict !== 'present' && r.verdict !== 'NO LOGIN IN WINDOW')
  .forEach((r) => console.log(`SKIPPED (needs a human): ${r.name} — ${r.verdict}, ${r.detail}`));

// ── 4. Write, only when explicitly told twice ──────────────────────────────────────────────
if (!has('--apply')) {
  console.log('\nDry run. Re-run with --apply to tick these boxes.');
  process.exit(0);
}
if (aeries.submitted && !has('--amend')) {
  console.log(`\nREFUSING: this period was already submitted (${aeries.submitted}).`);
  console.log('Someone took attendance by hand. Add --amend if you mean to change a filed record.');
  process.exit(2);
}
for (const r of toMark) {
  // A real click, not `.checked = true`: the write lives in the element's inline onclick
  // (TeacherSetATT). Setting the property fires no handler and saves nothing, while leaving
  // the box looking ticked — a silent no-op that reads as success.
  await ae.click(`#${r.chkId}`);
  await ae.waitForTimeout(700);
  console.log(`  marked absent: ${r.name}`);
}
await ae.waitForTimeout(1500);
const after = await ae.evaluate(
  (ids) => ids.map((id) => ({ id: id.split('_').slice(-2).join('_'), checked: document.getElementById(id)?.checked })),
  toMark.map((r) => r.chkId),
);
console.log('\nread-back:', JSON.stringify(after));
process.exit(0);

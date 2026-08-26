// Reusable Playwright-over-CDP connector for the S.T.E.V.E Desktop app's OWN embedded
// browser (the "dashboard"), not a standalone Chrome. The app scans ports 9222-9242 at
// launch and logs which one it took ("CDP enabled on port <N>") — read it from there,
// it changes between runs. See steve-desktop/.claude/skills/verify/SKILL.md.
//
// Unlike mom.mjs (hardcoded to port 9223 and the master course 334437), this is for
// reading/writing LIVE TEACHING SECTIONS — real students, real due dates. A wrong date
// here is worse than none (mom-transfer/SKILL.md). Never guess a cid or a date: read it
// back and report before writing anything.
import { createRequire } from 'module';
const require = createRequire('C:/Users/shuff57/Documents/GitHub/bookSHelf/package.json');
const { chromium } = require('playwright');

export async function connect(port) {
  if (!port) throw new Error('connect(port) needs the CDP port from the tauri:dev log.');
  const browser = await chromium.connectOverCDP(`http://127.0.0.1:${port}`);
  const ctx = browser.contexts()[0];
  const page = ctx.pages().find((p) => p.url().includes('myopenmath')) || ctx.pages()[0];
  page.setDefaultTimeout(30000);
  return { browser, page };
}

/** Same dead-session check as mom.mjs: a dead session serves the LOGIN FORM at the
 *  content URL, which otherwise reads as a small successful page. Test the login form's
 *  own username field, not `input[type=password]` alone — assessment settings carry an
 *  `assmpassword` passcode field that false-positives as "session dead" on a live one. */
export async function go(page, url) {
  await page.goto(url, { waitUntil: 'domcontentloaded' });
  const dead = await page.evaluate(() =>
    !!document.querySelector('input[name=username]') &&
    !!document.querySelector('input[type=password]') &&
    !/Log Out/i.test(document.body.innerText));
  if (dead) {
    throw new Error(`SESSION DEAD — login form served at ${url}. Log in inside the app's Browse tab, then retry.`);
  }
  return page;
}

/** List the teacher's own classes (My Classes), which is where a course's real cid lives —
 *  never trust a cid typed from memory. Read-only. */
export async function listClasses(page) {
  await go(page, 'https://www.myopenmath.com/index.php');
  return page.evaluate(() => {
    return [...document.querySelectorAll('a[href*="course/course.php"][href*="cid="]')].map((a) => {
      const m = /cid=(\d+)/.exec(a.getAttribute('href') || '');
      return { name: a.textContent.trim(), cid: m ? m[1] : null };
    }).filter((c) => c.cid);
  });
}

/** Every assessment in a course: {name, aid}. The listing lives at
 *  `course/chgassessments2.php` — one checkbox per assessment, carrying the aid as its
 *  value and the name as its label. NOT `assess/index.php?cid=`, which 404s: that URL
 *  served an Apache "Not Found" page whose zero `addassessment2.php` links read as an
 *  EMPTY COURSE rather than an error, and sent a 2026-08-24 run looking for a section
 *  that was there all along. Every page under this area is `/course/`, not `/assess/`.
 *  The checkbox scrape also picks up stray non-assessment boxes from the mass-change
 *  panel (values like `on` or a small integer), so keep only 6+ digit numeric ids. */
export async function listAssessments(page, cid) {
  await go(page, `https://www.myopenmath.com/course/chgassessments2.php?cid=${cid}`);
  await page.waitForTimeout(1200);
  return page.evaluate(() =>
    [...document.querySelectorAll('input[type=checkbox]')].map((el) => ({
      aid: el.value,
      name: (el.closest('label')?.textContent?.trim()
        || el.parentElement?.textContent?.trim()?.slice(0, 120) || '').replace(/\s+/g, ' '),
    })).filter((x) => /^\d{6,}$/.test(x.aid)));
}

/** Read back every assessment's dated/undated state and (if dated) its actual sdate/edate/
 *  stime/etime, for one course. Read-only — this is the "check" half of the task; setting
 *  dates is a separate, explicit write against values a human confirmed. */
export async function readAssessmentDates(page, cid) {
  const list = await listAssessments(page, cid);
  const out = [];
  for (const { name, aid } of list) {
    await go(page, `https://www.myopenmath.com/course/addassessment2.php?id=${aid}&cid=${cid}`);
    const dates = await page.evaluate(() => {
      const val = (sel) => document.querySelector(sel)?.value ?? null;
      const checked = (sel) => document.querySelector(sel)?.checked ?? null;
      return {
        sdatetype: val('select[name=sdatetype], input[name=sdatetype]:checked'),
        edatetype: val('select[name=edatetype], input[name=edatetype]:checked'),
        sdate: val('input[name=sdate]'),
        stime: val('input[name=stime]'),
        edate: val('input[name=edate]'),
        etime: val('input[name=etime]'),
        allowpractice: checked('input[name=allowpractice]'),
      };
    });
    out.push({ name, aid, ...dates });
  }
  return out;
}

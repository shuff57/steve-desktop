// Read-only audit: every Group Test / Individual Test in a course, real sdate/stime/edate/etime
// read off the live addassessment2.php settings form (Vue-rendered DOM, not a fetched copy —
// see mom-transfer/SKILL.md trap 3). Writes nothing.
import { connect, go } from './mom-live.mjs';

const port = process.argv[2] || '9222';
const cid = process.argv[3];
const pattern = process.argv[4] || 'Group Test|Individual Test';
if (!cid) { console.error('usage: node _audit-dates.mjs <port> <cid> [namePattern]'); process.exit(1); }

const { page } = await connect(port);
await go(page, `https://www.myopenmath.com/course/chgassessments2.php?cid=${cid}`);
await page.waitForTimeout(1200);

const re = new RegExp(pattern, 'i');
const items = await page.evaluate((patternSrc) => {
  const re = new RegExp(patternSrc, 'i');
  return [...document.querySelectorAll('input[type=checkbox]')]
    .map((el) => {
      const label = el.closest('label')?.textContent?.trim()
        || el.parentElement?.textContent?.trim()?.slice(0, 100) || '';
      return { aid: el.value, label };
    })
    .filter((x) => x.aid && x.aid !== '0' && re.test(x.label));
}, pattern);

const results = [];
for (const { aid, label } of items) {
  await go(page, `https://www.myopenmath.com/course/addassessment2.php?id=${aid}&cid=${cid}`);
  await page.waitForTimeout(900);
  const f = await page.evaluate(() => {
    const val = (sel) => document.querySelector(sel)?.value ?? null;
    const checkedRadio = (name) => document.querySelector(`input[name="${name}"]:checked`)?.value ?? null;
    return {
      name: document.querySelector('[name=name]')?.value ?? null,
      sdatetype: checkedRadio('sdatetype'),
      edatetype: checkedRadio('edatetype'),
      sdate: val('input[name=sdate]'),
      stime: val('input[name=stime]'),
      edate: val('input[name=edate]'),
      etime: val('input[name=etime]'),
    };
  });
  results.push({ cid, aid, label, ...f });
  console.error(`  read ${label} (${aid})`);
}

console.log(JSON.stringify(results, null, 2));
process.exit(0);

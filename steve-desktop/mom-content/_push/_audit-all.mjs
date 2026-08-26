// Read-only: every assessment in a course (any kind), real sdate/stime/edate/etime off the
// live settings form. Broader than _audit-dates.mjs's name-filtered version — this is for a
// whole-course retime, not just Group/Individual Tests.
import { connect, go } from './mom-live.mjs';

const port = process.argv[2] || '9222';
const cid = process.argv[3];
if (!cid) { console.error('usage: node _audit-all.mjs <port> <cid>'); process.exit(1); }

const { page } = await connect(port);
await go(page, `https://www.myopenmath.com/course/chgassessments2.php?cid=${cid}`);
await page.waitForTimeout(1200);

const items = await page.evaluate(() => {
  return [...document.querySelectorAll('input[type=checkbox]')]
    .map((el) => {
      const label = el.closest('label')?.textContent?.trim()
        || el.parentElement?.textContent?.trim()?.slice(0, 100) || '';
      return { aid: el.value, label };
    })
    .filter((x) => x.aid && x.aid !== '0');
});

const results = [];
for (const { aid, label } of items) {
  await go(page, `https://www.myopenmath.com/course/addassessment2.php?id=${aid}&cid=${cid}`);
  await page.waitForTimeout(700);
  const f = await page.evaluate(() => {
    const val = (sel) => document.querySelector(sel)?.value ?? null;
    const checkedRadio = (name) => document.querySelector(`input[name="${name}"]:checked`)?.value ?? null;
    return {
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

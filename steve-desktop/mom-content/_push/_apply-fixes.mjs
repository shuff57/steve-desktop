// Applies a filtered slice of a fixes.json to one course, writing any subset of
// sdate/stime/edate/etime through the real Vue-bound inputs (native setter + input/change
// events, per mom-transfer trap 3), clicking the real "Save Changes" submit, then reading
// back from a FRESH page load — the only trustworthy proof a save stuck. Never trusts the
// DOM state right after clicking save. Generalized from the edate/etime-only original so it
// can also do a stime/etime-only block-time correction (period 7 retime).
import { connect, go } from './mom-live.mjs';
import fs from 'node:fs';

const port = process.argv[2];
const fixesPath = process.argv[3];
const cidFilter = process.argv[4]; // optional
if (!port || !fixesPath) { console.error('usage: node _apply-fixes.mjs <port> <fixes.json> [cid]'); process.exit(1); }

const all = JSON.parse(fs.readFileSync(fixesPath, 'utf8'));
const fixes = cidFilter ? all.filter((f) => f.cid === cidFilter) : all;

const { page } = await connect(port);
const report = [];

for (const fix of fixes) {
  const { cid, aid, label, target } = fix;
  const fields = Object.keys(target); // e.g. ['edate','etime'] or ['stime','etime']

  await go(page, `https://www.myopenmath.com/course/addassessment2.php?id=${aid}&cid=${cid}`);
  await page.waitForTimeout(1000);

  await page.evaluate(({ target, fields }) => {
    const setVal = (name, value) => {
      const el = document.querySelector(`[name="${name}"]`);
      const setter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
      setter.call(el, value);
      el.dispatchEvent(new Event('input', { bubbles: true }));
      el.dispatchEvent(new Event('change', { bubbles: true }));
    };
    for (const f of fields) setVal(f, target[f]);
  }, { target, fields });

  await page.waitForTimeout(400);
  await page.click('input[type=submit]');
  await page.waitForTimeout(1800);

  // Read back from a FRESH load.
  await go(page, `https://www.myopenmath.com/course/addassessment2.php?id=${aid}&cid=${cid}`);
  await page.waitForTimeout(1000);
  const after = await page.evaluate(() => ({
    sdate: document.querySelector('[name=sdate]')?.value,
    stime: document.querySelector('[name=stime]')?.value,
    edate: document.querySelector('[name=edate]')?.value,
    etime: document.querySelector('[name=etime]')?.value,
  }));

  const ok = fields.every((f) => after[f] === target[f]);
  report.push({ cid, aid, label, target, after, ok });
  console.error(`${ok ? 'OK  ' : 'FAIL'} ${label} (${aid}) -> ${fields.map((f) => `${f}=${after[f]}`).join(' ')}`);
}

console.log(JSON.stringify(report, null, 2));
const failed = report.filter((r) => !r.ok);
console.error(`\n${report.length - failed.length}/${report.length} confirmed. ${failed.length} FAILED.`);
process.exit(failed.length ? 1 : 0);

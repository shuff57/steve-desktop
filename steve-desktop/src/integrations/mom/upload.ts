/**
 * mom-island upload — open MyOpenMath's modquestion.php editor and fill the
 * Common Control + Question Text fields via CDP. Stops BEFORE submit; the
 * user must review and click submit themselves. This is the safety boundary
 * for phase 3.
 */
import { cdp as defaultCDP, type CDPClient } from '../../lib/cdp-client';

export interface UploadOpts {
  cdpClient?: CDPClient;
  /** Course ID in MyOpenMath. */
  cid: number;
  /** Common Control value (the "anstype" set: e.g. "num", "choices", "tf"). */
  controls: string;
  /** Question body — what the student sees. */
  questionText: string;
}

const MOM_BASE = 'https://www.myopenmath.com';

/** Build a Runtime.evaluate expression that sets an element's value and
 *  fires the change event MOM listens to. Plain `.value =` doesn't dispatch
 *  input/change; the dispatched events are what wire the field into the
 *  React-style handlers MyOpenMath's editor uses. */
function setFieldExpr(selector: string, value: string): string {
  return [
    '(() => {',
    '  const el = document.querySelector(' + JSON.stringify(selector) + ');',
    '  if (!el) return false;',
    '  el.value = ' + JSON.stringify(value) + ';',
    "  el.dispatchEvent(new Event('input', { bubbles: true }));",
    "  el.dispatchEvent(new Event('change', { bubbles: true }));",
    '  return true;',
    '})()',
  ].join('\n');
}

export async function uploadToMOM(opts: UploadOpts): Promise<boolean> {
  const client = opts.cdpClient ?? defaultCDP;
  const connected = await client.connect().catch(() => false);
  if (!connected) return false;

  const url = `${MOM_BASE}/modquestion.php?cid=${encodeURIComponent(String(opts.cid))}`;
  await client.send('Page.navigate', { url });

  // Fill question text. The selectors match MyOpenMath's modquestion.php form
  // (used for years; verify against the live page if a future rev changes them).
  await client.send('Runtime.evaluate', {
    expression: setFieldExpr('#qtext', opts.questionText),
    returnByValue: true,
  });
  // Fill common control.
  await client.send('Runtime.evaluate', {
    expression: setFieldExpr('#controls', opts.controls),
    returnByValue: true,
  });

  // Intentionally no submit. The user reviews in the live editor and clicks
  // submit themselves. This is the phase-3 safety boundary.
  return true;
}

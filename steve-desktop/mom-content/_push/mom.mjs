// Shared Playwright handle on the already-authenticated MOM Chrome (port 9223).
// One tab, navigated — never a tab per page (mom-transfer skill).
import { createRequire } from 'module';
const require = createRequire('C:/Users/shuff57/Documents/GitHub/bookSHelf/package.json');
const { chromium } = require('playwright');

export const CID = '334437';

export async function connect() {
  const browser = await chromium.connectOverCDP('http://127.0.0.1:9223');
  const ctx = browser.contexts()[0];
  const page = ctx.pages().find((p) => p.url().includes('myopenmath')) || ctx.pages()[0];
  page.setDefaultTimeout(30000);
  return { browser, page };
}

/** Navigate and fail loudly if the session died — a dead session serves the LOGIN FORM
 *  at the content URL, which otherwise reads as a successful small page. */
export async function go(page, url) {
  await page.goto(url, { waitUntil: 'domcontentloaded' });
  // A dead session serves the LOGIN FORM at the content URL, which otherwise reads as a small
  // successful page. But do NOT test on `input[type=password]` alone — assessment settings carry
  // an `assmpassword` passcode field, and that false-positived as "session dead" on a live
  // session (2026-08-16). Test for the login form's own username field.
  const dead = await page.evaluate(() =>
    !!document.querySelector('input[name=username]') &&
    !!document.querySelector('input[type=password]') &&
    !/Log Out/i.test(document.body.innerText));
  if (dead) {
    throw new Error(`SESSION DEAD — login form served at ${url}. Stopping rather than guessing.`);
  }
  return page;
}

#!/usr/bin/env node
// Guarantee a LIVE MyOpenMath session before a long run starts.
//
// Why this exists (2026-08-31): MOM issues PHPSESSID with `expires: -1` -- a SESSION cookie,
// held in memory and never written to disk. `playwright-cli --persistent` therefore does NOT
// save the login, whatever the profile directory suggests, and the login form has no
// "remember me" (username / password / passkey, nothing else). So the session dies with the
// browser process and has to be re-established by hand each work session.
//
// The old failure shape: a push runs `SESSION DEAD -- login form served at <url>` twenty
// questions in. That throw is correct, but the discovery is expensive -- half a push of
// browser work is already spent. This moves the same discovery to second zero.
//
//   node _push/mom-session.mjs             check; open the login headed if dead; exit 1
//   node _push/mom-session.mjs --wait      same, but block until the login completes
//   node _push/mom-session.mjs --quiet     machine-readable single line, no banner
//
// Exit 0 = live, safe to push. Exit 1 = not live; a headed browser is waiting for a login.
// Exit 2 = something is wrong that logging in will not fix.
import { execFileSync } from 'node:child_process';
import { existsSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

/**
 * Resolve playwright-cli's JS entry and run it under `node` directly.
 *
 * Do NOT execFile the shim. On Windows `playwright-cli` is a POSIX shell script (ENOENT to
 * execFile) and `playwright-cli.cmd` is refused outright (EINVAL) -- Node 20+ blocks .cmd
 * through execFile without a shell. Both failures are silent if the caller swallows them,
 * which is exactly how the first version of this guard reported "no browser open" against a
 * perfectly live session. `shell: true` would work but re-opens the quoting hole that MOM's
 * `&`-laden URLs fall straight into, so resolve the .js and skip the shell entirely.
 */
const CLI_JS = (() => {
  if (process.env.PLAYWRIGHT_CLI_JS && existsSync(process.env.PLAYWRIGHT_CLI_JS)) return process.env.PLAYWRIGHT_CLI_JS;
  const rel = join('node_modules', '@playwright', 'cli', 'playwright-cli.js');
  const roots = [];
  if (process.env.APPDATA) roots.push(join(process.env.APPDATA, 'npm'));
  try { roots.push(execFileSync('npm', ['prefix', '-g'], { encoding: 'utf8', shell: true }).trim()); } catch {}
  if (process.env.HOME) roots.push(join(process.env.HOME, '.npm-global'), '/usr/local', '/usr');
  for (const r of roots) {
    for (const p of [join(r, rel), join(r, 'lib', rel)]) if (existsSync(p)) return p;
  }
  return null;
})();

const SESSION = process.env.MOM_SESSION || 'mom';
const HOME_URL = 'https://www.myopenmath.com/index.php';
const WAIT = process.argv.includes('--wait');
const QUIET = process.argv.includes('--quiet');
const WAIT_TIMEOUT_MS = 10 * 60 * 1000;
const POLL_MS = 5000;

const say = (...a) => { if (!QUIET) console.log(...a); };

function cli(args, { allowFail = false } = {}) {
  try {
    return execFileSync(process.execPath, [CLI_JS, ...args], { encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] });
  } catch (e) {
    if (allowFail) return (e.stdout || '') + (e.stderr || '');
    throw e;
  }
}

/** Is there a browser under this session name at all? */
function isOpen() {
  const out = cli(['list'], { allowFail: true });
  // `list` prints one block per browser: "- <name>:" then "  status: open"
  const block = out.split(/^- /m).find((b) => b.startsWith(`${SESSION}:`));
  return !!block && /status:\s*open/.test(block);
}

/**
 * Live == a content page that is NOT the login form.
 *
 * Do NOT test on `input[type=password]` alone: assessment settings (addassessment2.php) carry
 * an `assmpassword` passcode field of the same type, and that guard has already reported a dead
 * session on a live one (2026-08-16), halting a rename. Test the login form's own username
 * field, AND a password field, AND the absence of "Log Out".
 */
function probe() {
  const js = `JSON.stringify((()=>{`
    + `const u=!!document.querySelector('input[name=username]');`
    + `const p=!!document.querySelector('input[type=password]');`
    + `const o=/Log ?Out/i.test(document.body.innerText);`
    + `return {loginForm:u&&p&&!o, loggedIn:o, url:location.href};`
    + `})())`;
  const out = cli(['-s=' + SESSION, '--raw', 'eval', js], { allowFail: true });
  const m = /\{.*\}/s.exec(out.replace(/\\"/g, '"'));
  if (!m) return null;
  try { return JSON.parse(m[0]); } catch { return null; }
}

function openHeaded() {
  // --headed is REQUIRED for a login: headless opens a browser nobody can type into, which
  // then reads as a working session quietly serving the login form (measured 2026-08-31).
  cli(['-s=' + SESSION, 'open', '--persistent', '--headed', HOME_URL], { allowFail: true });
}

/**
 * Credentials, if they have been stored. NEVER inline them here -- this file is in git.
 * Env wins, then ~/.mom-creds.json, which lives outside every working tree on purpose.
 */
function creds() {
  if (process.env.MOM_USER && process.env.MOM_PASS) {
    return { username: process.env.MOM_USER, password: process.env.MOM_PASS };
  }
  const home = process.env.USERPROFILE || process.env.HOME;
  const p = process.env.MOM_CREDS || (home && join(home, '.mom-creds.json'));
  if (!p || !existsSync(p)) return null;
  try {
    const c = JSON.parse(readFileSync(p, 'utf8'));
    return c.username && c.password ? c : null;
  } catch { return null; }
}

/**
 * Fill and submit the login form. Returns true only when the page comes back signed in --
 * a failed login re-serves the SAME form with no error banner in the DOM we can rely on, so
 * the post-condition is "Log Out is present", never "the POST returned 200".
 */
function autoLogin(c) {
  cli(['-s=' + SESSION, 'goto', HOME_URL], { allowFail: true });
  const staged = probeJson(`JSON.stringify((()=>{`
    + `const u=document.querySelector('input[name=username]');`
    + `const p=document.querySelector('input[type=password]');`
    + `if(!u||!p) return {ok:false,why:'no login form'};`
    + `const fire=(el)=>{el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));};`
    + `u.value=${JSON.stringify(c.username)}; fire(u);`
    + `p.value=${JSON.stringify(c.password)}; fire(p);`
    + `const f=u.form||p.form; if(!f) return {ok:false,why:'inputs have no form'};`
    + `const btn=[...f.querySelectorAll('input[type=submit],button')].find(b=>/log ?in|sign ?in|submit/i.test(b.value||b.innerText||''));`
    + `if(btn) btn.click(); else f.submit();`
    + `return {ok:true};`
    + `})())`);
  if (!staged || !staged.ok) return false;
  // The submit navigates; give it a few polls rather than one optimistic read.
  for (let i = 0; i < 6; i++) {
    Atomics.wait(new Int32Array(new SharedArrayBuffer(4)), 0, 0, 1500);
    const p = probe();
    if (p && p.loggedIn && !p.loginForm) return true;
  }
  return false;
}

/** probe() without the goto -- used mid-login, where navigating would discard the fill. */
function probeJson(js) {
  const out = cli(['-s=' + SESSION, '--raw', 'eval', js], { allowFail: true });
  const m = /\{[\s\S]*\}/.exec(out.replace(/\\"/g, '"'));
  if (!m) return null;
  try { return JSON.parse(m[0]); } catch { return null; }
}

function check() {
  if (!isOpen()) return { live: false, why: 'no browser open under session "' + SESSION + '"' };
  cli(['-s=' + SESSION, 'goto', HOME_URL], { allowFail: true });
  const p = probe();
  if (!p) return { live: false, why: 'could not read the page (browser may have just closed)' };
  if (p.loginForm) return { live: false, why: 'login form served at a content URL' };
  if (!p.loggedIn) return { live: false, why: 'no "Log Out" on the page; not a signed-in view' };
  return { live: true, url: p.url };
}

if (!CLI_JS) {
  console.error('Cannot find playwright-cli.js. Install it with `npm install -g @playwright/cli@latest`,');
  console.error('or point PLAYWRIGHT_CLI_JS at the file. Refusing to guess -- a guard that cannot run');
  console.error('must fail loudly, not report "no session" against a live one.');
  process.exit(2);
}

let r = check();

if (!r.live) {
  say(`MOM session "${SESSION}" is NOT live -- ${r.why}`);
  if (!isOpen()) { say('opening a headed browser at the login page...'); openHeaded(); }

  // Stored credentials turn the once-per-work-session login into a no-op. Still headed, still
  // the same browser -- only the typing is automated, so a login that fails falls straight
  // through to the manual path below rather than leaving the caller stuck.
  const c = creds();
  if (c) {
    say(`trying the stored login for "${c.username}"...`);
    if (autoLogin(c)) {
      r = check();
      if (r.live) {
        say(`MOM session "${SESSION}" is live -- ${r.url}  (auto-login)`);
        if (QUIET) console.log(`live ${SESSION} ${r.url}`);
        process.exit(0);
      }
    }
    say('stored login did not take -- falling back to a manual login.');
  }

  say('');
  say('  >> Log in to MyOpenMath in the browser window that just opened. <<');
  say('     MOM will not remember you: PHPSESSID is a session cookie (expires: -1),');
  say('     so this is once per work session, not once per machine.');
  say('');

  if (!WAIT) {
    say('Re-run this with --wait to block until you are in, then start the push.');
    process.exit(1);
  }

  const deadline = Date.now() + WAIT_TIMEOUT_MS;
  while (Date.now() < deadline) {
    Atomics.wait(new Int32Array(new SharedArrayBuffer(4)), 0, 0, POLL_MS);
    r = check();
    if (r.live) break;
    if (!isOpen()) { say('browser was closed; giving up.'); process.exit(2); }
  }
  if (!r.live) { say('timed out waiting for a login.'); process.exit(1); }
}

say(`MOM session "${SESSION}" is live -- ${r.url}`);
if (QUIET) console.log(`live ${SESSION} ${r.url}`);
process.exit(0);

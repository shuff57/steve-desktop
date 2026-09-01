# `_push/` — tools for driving a MyOpenMath push

> **Start a transfer with `node _push/mom-session.mjs`, not these.** As of 2026-08-31 the
> browser is owned by the `playwright-cli` skill (session `mom`), so there is no debug port
> to bring up first — but MOM's login does NOT persist across a browser restart, so run the
> guard before any long push. The connectors below remain the right tool for scripted batch
> writes over hundreds of pages (the 92-assessment retime), and nothing else. See
> `reference/transfer-rules.md`.

Five committed files. Everything else in this directory is scratch from a
particular push and is gitignored.

| File | What it is |
|---|---|
| `mom-session.mjs` | `node mom-session.mjs [--wait] [--quiet]` — the session guard. Exit 0 = live and safe to push; exit 1 = not live and a headed browser is waiting for a login; exit 2 = broken in a way logging in will not fix. Exists because MOM issues `PHPSESSID` with `expires: -1`, a **session** cookie that `--persistent` cannot save, so the login has to be redone once per work session. It always probes `index.php`, never a settings page, so the `assmpassword` false-positive that has bitten this check twice is structurally impossible here. Runs playwright-cli's `.js` under `node` rather than execFile-ing the shim — on Windows the shim is ENOENT and the `.cmd` is EINVAL, and swallowing either makes the guard report "no session" against a live one. |
| `mom.mjs` | Playwright-over-CDP connector, hardcoded to the standalone Chrome debug port 9223 and the master course 334437 (question-bank pushes). `connect()`, `go(page, url)`, `CID`. |
| `mom-live.mjs` | Same connector, generalized: `connect(port)` takes the S.T.E.V.E Desktop app's own dynamic CDP port (9222-9242, read from its dev log — see `.claude/skills/verify/SKILL.md`), and `go()`/`listClasses()`/`readAssessmentDates()` take any `cid`. For driving LIVE TEACHING SECTIONS through the app's own logged-in browser tab — real students, real dates. Both connectors throw when a login form is served at a content URL, so a dead session never silently reads as a small successful page. Resolve `playwright` out of bookSHelf's `node_modules` via `createRequire` — both do this. |
| `_audit-dates.mjs` | `node _audit-dates.mjs <port> <cid> [namePattern]` — read-only, name-filtered (default `Group Test\|Individual Test`). Reads sdate/stime/edate/etime off the live `addassessment2.php` settings form (Vue DOM, not a fetched copy). Used to find the Ch1-5 tests across periods 3/4/7 that were closing at period-START instead of period-end+7 — see `bell-schedule-2026-27.md` and `learned-rules.md`, 2026-08-21. |
| `_audit-all.mjs` | Same as `_audit-dates.mjs` but unfiltered — every assessment in a course, any kind. Used for the period-7 whole-course block-time retime (92/92 fixed 2026-08-21). Its checkbox scrape also picks up a few stray non-assessment checkboxes from `chgassessments2.php`'s mass-change panel (junk `aid`s like `"on"`/small numbers) — filter on `sdate !== null` to drop them. |
| `_apply-fixes.mjs` | `node _apply-fixes.mjs <port> <fixes.json> [cid]` — writes any subset of sdate/stime/edate/etime from a fixes-array (`{cid,aid,label,target:{...}}` per entry) through the real Vue-bound inputs, clicks the real Save, then re-navigates FRESH and reads back to confirm. Never trust the DOM right after the save click — it redirects. Generalized 2026-08-21 so the same script does an edate/etime-only same-day-close fix (period 3/4/7 tests) or a stime/etime-only block-time fix (period 7 whole course) — pass whichever fields you computed in `target`. |
| `crib.mjs` | `node crib.mjs <manifest>` → compact answer crib (anstypes, seed arrays, derived vars, every `$answer[...]`, tolerances) at roughly a tenth the size of the sources. Hand this to a driving run instead of making it read the `.php` files. |
| `qtext-audit.mjs` | PHP concat left literal in QUESTION TEXT, and function names inside backticks. Both render as visible junk and both pass every other gate. |
| `anstypes-audit.mjs` | `$anstypes` entry count vs `max($answer[N])+1` vs `answerbox` references. A short count silently drops answer boxes and still scores full marks. |
| `usecheck.mjs` | A derived variable defined ABOVE the array it reads from — renders empty, never errors. |

Run all three audits before any push:

```bash
cd steve-desktop
node mom-content/_push/qtext-audit.mjs
node mom-content/_push/anstypes-audit.mjs
node mom-content/_push/usecheck.mjs
```

Each was written *from* a real defect that byte-exact verification and a 102/100
score had both passed. They catch the three things we know to check — not
everything. The visual pass still needs a model with image input; see
`skills/mom-transfer/SKILL.md`.

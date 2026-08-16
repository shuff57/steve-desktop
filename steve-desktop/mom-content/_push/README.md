# `_push/` — tools for driving a MyOpenMath push

Five committed files. Everything else in this directory is scratch from a
particular push and is gitignored.

| File | What it is |
|---|---|
| `mom.mjs` | Playwright-over-CDP connector. `connect()`, `go(page, url)`, `CID`. `go()` throws when a login form is served at a content URL — a dead session otherwise reads as a small successful page. Resolves `playwright` out of bookSHelf's `node_modules` via `createRequire`. |
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

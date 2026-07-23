# gradebook-island

Rehosts the `gradebook/playwright-grading/` scripts (floor-grader and qid-scraper) so they can be called from inside the Tauri app.

## What this island does

| Method | Purpose | Dry-run by default? |
|---|---|---|
| `runFloorScores(opts)` | Compute floored scores from handwork analysis; write CSVs | Yes — pass `writeBack: true` to push to MyOpenMath |
| `runScrapeQids(opts)` | Print question-number → qid map for an assignment | N/A (read-only) |

Both methods return `{ ok, exitCode, stdout, stderr, csvPaths? }` so the UI can show results and any CSVs the script wrote.

## Why the scripts live outside this repo

The `floor-scores.mjs` and `scrape-qids.mjs` scripts use `playwright` to drive a separate Chrome process (the one with the user's logged-in MyOpenMath session). Adding `playwright` to the Tauri app's `package.json` would bloat the bundle and is not needed — the scripts already work as a standalone Node project.

The island locates the scripts via `config.ts`:

1. Caller-provided `scriptsDir` (e.g. `C:/Users/shuff/Documents/GitHub/gradebook/playwright-grading`)
2. `GRADEBOOK_SCRIPTS_DIR` env var
3. `./scripts/` relative to this file (default; useful for tests, not for production)

Same resolution for the output dir (`outDir` → `GRADEBOOK_OUT_DIR` → `~/Documents/GitHub/gradebook`).

## Calling it from a UI

```ts
import { gradebookIsland } from '$lib/integrations/gradebook';

const result = await gradebookIsland.methods.runFloorScores({
  cid: 306621,
  aid: 22202268,
  label: 'unit1',
  analysis: 'C:/Users/shuff/Documents/GitHub/gradebook/temp_unit1_handwork_analysis.json',
  scriptsDir: 'C:/Users/shuff/Documents/GitHub/gradebook/playwright-grading',
});

if (result.ok) {
  for (const csv of result.csvPaths) {
    // open the CSV or show a download link
  }
} else {
  // show result.stderr
}
```

## What's NOT here

- No Skills.svelte wiring yet. The existing Skills page is for markdown-imported skills; the floor-grader needs CID/AID inputs the current schema doesn't carry. A future "Gradebook" page (or extending the skill schema) is the place for UI.
- No Tauri-side config UI. For now, pass `scriptsDir` explicitly or set `GRADEBOOK_SCRIPTS_DIR`.
- No write-back confirmation modal. The dry-run default is the safety; the caller must opt in to `--write-back` and is responsible for confirming.

## Smoke-test evidence

- `runner.test.ts` — uses a fake spawner; checks argv, exit code, stdout/stderr capture, CSV path discovery.
- `runner.e2e.test.ts` — spawns a **real** Node subprocess (a fake floor-scores.mjs in a temp dir), exercises the full path including filesystem writes. This is the closest we can get to a real MyOpenMath test without Chrome.
- `args.test.ts` — pure CLI-arg serializer tests.
- `config.test.ts` — verifies scriptsDir / outDir resolution order.

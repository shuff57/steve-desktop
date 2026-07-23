# ogre-island

O.G.R.E (Ollama Grading Rubric Evaluator) folded into steve-desktop as a
self-contained island. Lives entirely under `src/integrations/ogre/`;
imports nothing from `src/integrations/gradebook/` or
`src/integrations/mom/`. The boundary guard in
`src/integrations/_shared/boundary.test.ts` enforces this at test time.

## What's in this folder

```
ogre/
  index.ts               island surface — the data-access methods
  db.ts                  typed accessors over the shared steve.db connection
  db.test.ts             asserts the SQL each accessor issues
  island.test.ts         asserts the island surface
  types.ts               TypeScript row/insert types per O.G.R.E table
  README.md              this file
```

## What's built (phases 4-5)

- **Schema** — ported from
  `O.G.R.E-OllamaGradingRubricEvaluator/ogre-desktop/electron-main/database.ts`,
  and it lives in **steve.db**, as migrations 9 and 10 in
  `src-tauri/src/lib.rs`. Six of the nine O.G.R.E tables were already steve.db
  tables; migration 9 widened `skills` and `site_profiles` to O.G.R.E's shape and
  migration 10 adds the three that were missing.
- **No separate ogre.db.** The original port opened `~/.steve/ogre.db` with
  `better-sqlite3`. That could never run: `better-sqlite3` and `node:fs` are Node
  APIs, and this code runs in a WebView. (The mom island hit the same wall and
  moved its filesystem into Rust.) `better-sqlite3` has been dropped as a
  dependency.
- **No `island_id` column.** O.G.R.E namespaced every row with
  `island_id = 'ogre'`. In the ogre-only tables it was a constant, and in the
  tables now shared with steve it would have mislabelled steve's own rows.
  `skills.source = 'rubric'` is what marks a rubric.
- **Typed accessors** — `db.ts` exposes site-profile, rubric, grading-history and
  batch-resume reads/writes over the one shared `steve.db` connection.
- **Type definitions** — `types.ts` exports the row and insert shape for
  every O.G.R.E table.

## Tables (port 1:1 from O.G.R.E)

| Table              | Purpose                                                        |
|--------------------|----------------------------------------------------------------|
| `provider_configs` | API URL/key/model for each AI provider, with active flag      |
| `grading_sessions` | The "grading history" — one row per batch run, with stats      |
| `app_settings`     | Key/value global settings (seeded with `setup_complete`, etc.) |
| `oauth_tokens`     | Per-provider OAuth tokens                                      |
| `site_credentials` | Username/password per site                                     |
| `site_profiles`    | The O.G.R.E site shape: selectors, feedback, save, navigation, extraction |
| `batch_session`    | Last student processed per URL (resume marker)                 |
| `skills`           | Rubrics live here — `content` is the rubric JSON               |
| `response_embeddings` | Per-response embedding BLOB with score/feedback/model        |

All tables have an `island_id TEXT NOT NULL DEFAULT 'ogre'` column added by
this port so future islands can share the same database without colliding.

## What's deferred

These belong to later phases and are **not** built here:

- **Phase 5 — grading server port.** Provider modules (Ollama, Anthropic,
  OpenAI, Gemini, GitHub Models, RunPod), the prompt-construction
  pipeline, the "grade all students in one AI context" batch loop from
  `O.G.R.E-OllamaGradingRubricEvaluator/grading-server/grading.js`, and
  SSE streaming. The island surface will grow a
  `gradeBatch(input): AsyncIterable<GradingEvent>` method.
- **Phase 6 — UI.** `SiteProfiles.svelte` rewrite against the O.G.R.E
  schema, a `Rubrics.svelte` editor, a `GradingPanel.svelte` for review-
  before-submit. The plan's `load-students.ts` will scrape student
  responses from MyOpenMath via the existing `src/lib/cdp-client.ts`.
- **OGRE Cloud** (Google login + JWT + worker). Out of scope per the
  plan; the island boundary keeps the door open.

## Usage

```ts
import { ogreIsland } from '~/integrations/ogre';

const profiles = await ogreIsland.methods.listSiteProfiles();
const rubrics = await ogreIsland.methods.listRubrics();

// Resume marker — skip students a previous run already graded and submitted.
const last = await ogreIsland.methods.getBatchResume(pageUrl);
```

Every method is async: tauri-plugin-sql crosses the WebView/Rust boundary.
JSON-encoded columns (`selectors`, `url_patterns`, ...) are still strings at this
layer. Grading server logic lands in phase 5, the UI in phase 6.

## Test status

- OGRE-specific: 2 test files (`db.test.ts` asserts the SQL each accessor
  issues; `island.test.ts` covers the island surface).
- The schema itself is verified by applying every `lib.rs` migration to a
  throwaway DB — both the fresh path and a v8-with-rows upgrade.

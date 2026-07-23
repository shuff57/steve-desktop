# ogre-island

O.G.R.E (Ollama Grading Rubric Evaluator) folded into steve-desktop as a
self-contained island. Lives entirely under `src/integrations/ogre/`;
imports nothing from `src/integrations/gradebook/` or
`src/integrations/mom/`. The boundary guard in
`src/integrations/_shared/boundary.test.ts` enforces this at test time.

## What's in this folder

```
ogre/
  index.ts               island surface — currently exposes openOgreDb()
  db.ts                  openOgreDb(): path-keyed singleton accessor
  db.test.ts             migration smoke tests + round-trips
  open-db.test.ts        openOgreDb() API + singleton-by-path tests
  island.test.ts         asserts the island surface exposes openOgreDb()
  types.ts               TypeScript row/insert types per O.G.R.E table
  migrations/
    001-ogre-schema.sql  the canonical O.G.R.E schema, ported 1:1
  README.md              this file
```

## What's built (phase 4)

- **`better-sqlite3` (^13.0.1)** added as a runtime dep. `@types/better-sqlite3`
  (^7.6.13) added as a dev dep for type info.
- **Schema** — ported 1:1 from
  `O.G.R.E-OllamaGradingRubricEvaluator/ogre-desktop/electron-main/database.ts`
  with one namespace change: every table gains an `island_id TEXT NOT NULL
  DEFAULT 'ogre'` column. Migrations are idempotent (`CREATE TABLE IF NOT
  EXISTS`, `INSERT OR IGNORE`); the migration file can be re-run safely.
- **DB file location** — `~/.steve/ogre.db`. Override with
  `openOgreDb(path)`. Tests use `:memory:` or `file:...` URIs.
- **Typed accessor** — `openOgreDb(path?)` returns a `better-sqlite3
  Database` with the canonical tables. Singleton by path.
- **Type definitions** — `types.ts` exports the row and insert shape for
  every O.G.R.E table (`SiteProfile`, `Skill`, `GradingSession`,
  `ResponseEmbedding`, `ProviderConfig`, `OAuthToken`, `SiteCredential`,
  `BatchSession`, `AppSetting`). The `IslandId` union and `ISLAND_IDS`
  runtime constant are the namespace contract.

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

// Default path: ~/.steve/ogre.db
const db = ogreIsland.methods.openOgreDb();

// Or an explicit path (tests do this)
const mem = ogreIsland.methods.openOgreDb(':memory:');
```

The returned `Database` is a vanilla `better-sqlite3` instance. The rest
of the island (typed wrappers, parsed JSON columns, server logic) lands
in phases 5 and 6.

## Test status

- `bunx vitest run` — 502 tests pass, 0 failures (project-wide)
- `bunx svelte-check` — 0 errors
- OGRE-specific: 3 test files, 14 tests (`db.test.ts` covers migration +
  round-trips; `open-db.test.ts` covers the singleton accessor;
  `island.test.ts` covers the island surface)

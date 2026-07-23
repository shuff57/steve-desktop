# ogre-island

O.G.R.E (Ollama Grading Rubric Evaluator) folded into steve-desktop as a
self-contained island. Lives entirely under `src/integrations/ogre/`;
imports nothing from `src/integrations/gradebook/` or
`src/integrations/mom/`. The boundary guard in
`src/integrations/_shared/boundary.test.ts` enforces this at test time.

## What's in this folder

```
ogre/
  index.ts               island surface — data access + grading
  db.ts                  typed accessors over the shared steve.db connection
  grade.ts               gradeOne / gradeBatch — redaction gate + CLI transport
  grading.ts             single-student prompt + response parsing (pure)
  batch.ts               batch prompt, chunking, bridge calibration, parsing (pure)
  grading-constants.ts   grading philosophy + scoring scale, ported verbatim
  grade.cli.e2e.test.ts  opt-in: spawns the real claude CLI (OGRE_CLI_E2E=1)
  fixtures/              O.G.R.E's synthetic 30-student demo class
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

## Grading (phase 5)

`gradeOne(student, rubric, provider)` and `gradeBatch(students, rubric, provider)`.
Batch grades a class in one model context per chunk — that shared context is what
keeps scores comparable between students — and carries bridge examples from one
chunk into the next so chunk 2 doesn't drift from chunk 1.

**Transport is the claude/opencode CLI**, spawned through Rust's `run_agent_cli`.
No API keys: the CLI's own login authenticates. `providers.js` from O.G.R.E is not
ported at all. Pass `opts.run` to inject a different transport in tests.

**Redaction is not optional.** Student work reaches a model only through
`model-gate.callModel`, which refuses to send anything whose known identifiers
survive into the payload. `identifiersFor()` registers the full name, the student
id, AND each name part — a roster "Nakamura, Yuki" does not match work signed
"Yuki Nakamura", and assertOutbound cannot catch what it was never given.

**Never report zeros as grades.** `assertGraded` throws when the model returns
nothing parseable. Verbatim O.G.R.E returned score 0 with an error message per
student; here those results feed a gradebook, where a silent row of zeros is
indistinguishable from a class that genuinely all failed. A local reasoning model
that empties its budget into chain-of-thought produces exactly that.

Ported near-verbatim and worth leaving alone: the grading philosophy text, the
0-10 scoring scale, and the score arithmetic. A differential harness confirmed the
prompts are byte-identical to O.G.R.E's across rubric shapes. Not ported: outlier
review, pairwise sweeps, historical calibration from `response_embeddings`.

## Engines: claude and opencode

Grading runs through whichever CLI `engineForProvider` picks — `claude` for
anthropic/claude, `opencode` for everything else. Both are verified end to end against
the synthetic fixture.

**opencode requires `--agent summary`, and grading passes it.** opencode's default agent
boots its entire coding stack — skills, tool schemas, git snapshot tracking — before it
sees your prompt. `--agent` selects the tool set, the way claude uses
`--disallowed-tools "*"`. Measured on this machine with an identical one-line prompt:

| agent | input tokens |
|---|---|
| (default) | 29,329 |
| plan | 29,609 |
| title | 4,945 |
| **summary** | **4,560** |

Without it, ~29.3K of overhead plus a 14K-char grading prompt overruns a local Ollama's
32768-token ceiling; the model truncates at ~89 output tokens and returns nothing
parseable, and `assertGraded` refuses the result. `--pure` does not help — it only skips
external plugins, not the built-in tools.

**Model ids must be `provider/model`.** `cliModelArg` prefixes a bare id with `ollama/`,
which assumes a local ollama provider in `~/.config/opencode/opencode.jsonc`. An install
authenticated against Ollama Cloud (`opencode auth list` → "Ollama Cloud") exposes
`ollama-cloud/*` instead, and a bare id fails with `ProviderModelNotFoundError`. Pass the
qualified id — anything containing `/` is passed through untouched.

Observed: opencode + `ollama-cloud/gemma4:31b` graded 3 students in ~13s; claude graded
the same 3 in ~45s.

## Test status

- Unit: `db.test.ts` (SQL per accessor), `grading.test.ts` and `batch.test.ts`
  (prompt + parsing + scoring, pure), `grade.test.ts` (redaction, transport,
  batch orchestration), `island.test.ts` (surface).
- Schema: every `lib.rs` migration applied to a throwaway DB — the fresh path and
  a v8-with-rows upgrade.
- End-to-end: `OGRE_CLI_E2E=1 npx vitest run src/integrations/ogre/grade.cli.e2e.test.ts`
  spawns the real `claude` CLI with the same argv `run_agent_cli` builds and the
  prompt on stdin. Opt-in because it costs tokens and leaves the machine — the
  fixture is invented students, and must stay that way.
  `OGRE_CLI_E2E_STUDENTS=30` exercises chunking and bridge calibration.

Mocked tests could not have found any of the three defects the live runs did:
ungraded zeros passing as grades, the 180s CLI timeout killing every real batch,
and e2e assertions passing vacuously over an empty result set.

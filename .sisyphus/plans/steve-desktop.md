# S.T.E.V.E Desktop — Clone OGRE for Video Automation

## TL;DR

> **Quick Summary**: Clone the OGRE desktop app (Tauri 2 + Svelte 5) into a stripped-down `steve-desktop/` app that keeps login/auth, embedded browser, discover tab (3 modes), agent tab (AI chat + automation), and skills management — removing all grading features. Uses the site-profiler skill's JSON schema for page profiles instead of OGRE's grading-specific SiteProfile.
>
> **Deliverables**:
> - `steve-desktop/` — Complete Tauri 2 + Svelte 5 desktop app
> - OAuth login flow (GitHub, OpenAI, Anthropic, Google, Ollama)
> - Embedded browser with multi-tab, CDP integration
> - Discover tab with 3 interaction modes (form/chat/teach-by-example)
> - Agent tab with AI chat + browser automation
> - Skills page (My Skills / Find Skills / Create Skill)
> - Site profiles using `.agents/site-profiles/` JSON format
>
> **Estimated Effort**: XL
> **Parallel Execution**: YES — 6 waves
> **Critical Path**: Scaffold → Core Lib → Rust Backend → UI Shell → Feature Tabs → Verification

---

## Context

### Original Request
Clone OGRE's login flow and embedded browser for S.T.E.V.E. Remove all grading features. Keep only: embedded browser, discover tab, agent tab, skills page, and sidebar navigation.

### Interview Summary
**Key Discussions**:
- Backend: Reuse OGRE backend if possible, or create lightweight API
- Agent tab: Both AI chat interface AND automation controls
- Discover tab: All 3 modes (form, chat, teach-by-example)
- Profile format: Use the site-profiler skill's JSON schema (NOT OGRE's SiteProfile)
- Location: `steve-desktop/` inside the existing S.T.E.V.E repo
- Test strategy: TDD (RED → GREEN → REFACTOR)
- Discovery: Generic (not video-specific)

### Metis Review
**Identified Gaps** (addressed):
- Type coupling: batch-grader.ts exports types used by 8+ files → RESOLVED: use site-profiler schema instead, no need to extract OGRE types
- discover.ts imports RubricCriterion → must replace with generic types during port
- server.ts is grading-server specific → remove entirely, replace App.svelte event listeners
- SiteProfile format mismatch → RESOLVED: use site-profiler skill's discovery-schema.json
- Unused deps (katex, mathlive, turndown) → explicitly remove
- Sidecar code in lib.rs → remove ~200 lines
- Close-to-tray → just quit (no sidecar)
- OAuth client IDs → need separate registration for S.T.E.V.E

---

## Work Objectives

### Core Objective
Create a standalone Tauri 2 + Svelte 5 desktop application that provides an embedded browser with AI-powered page discovery and automation capabilities, without any grading-specific features.

### Concrete Deliverables
- `steve-desktop/` project with working Tauri app
- OAuth setup wizard for AI provider authentication
- Embedded browser with multi-tab navigation and CDP integration
- Discovery panel with form/chat/teach-by-example modes
- Agent chat with review/auto modes for browser automation
- Skills management page (find, create, discover local)
- Site profiles stored as `.agents/site-profiles/` JSON files

### Definition of Done
- [ ] `cd steve-desktop && bun install && bun run build` exits 0
- [ ] `cd steve-desktop/src-tauri && cargo check` exits 0
- [ ] `cd steve-desktop && bun test` — all tests pass
- [ ] `grep -r "grading\|rubric\|batch_grader\|student_count\|mean_score" steve-desktop/src/ | wc -l` returns 0
- [ ] `grep -r "from.*batch-grader\|from.*grading-api\|from.*rubric-api\|from.*calibration" steve-desktop/src/ | wc -l` returns 0
- [ ] App launches, shows setup wizard, completes OAuth, shows browser with discover + agent tabs

### Must Have
- OAuth device flows for GitHub, OpenAI, Anthropic (PKCE), Google, and Ollama API key
- Embedded browser with CDP-based DOM access and script injection
- Discovery system with all 3 interaction modes
- Agent chat with browser action capabilities
- Skills page with search, create, and local discovery
- Site profile persistence using site-profiler JSON schema
- Multi-tab browser support
- SQLite database (steve.db) for auth tokens, provider configs, skills

### Must NOT Have (Guardrails)
- NO grading features: rubrics, scoring, feedback, batch grading, calibration
- NO grading server sidecar or provider-sync
- NO RubricCard, StudentWorkCard, BatchPanel, SolverChat components
- NO grading_sessions, batch_session, response_embeddings DB tables
- NO katex, mathlive, turndown dependencies
- NO OGRE's SiteProfile types (use site-profiler schema instead)
- NO reuse of OGRE's OAuth client IDs (register new ones)
- NO updater config pointing to OGRE's GitHub releases
- NO MathField.svelte, ResponseRenderer.svelte, ExtractionConfigPanel.svelte
- NO History, Logs, or Rubrics pages
- NO close-to-tray behavior (just quit)

---

## Verification Strategy

> **ZERO HUMAN INTERVENTION** — ALL verification is agent-executed.

### Test Decision
- **Infrastructure exists**: NO (new project — must set up)
- **Automated tests**: TDD (RED → GREEN → REFACTOR)
- **Framework**: vitest (node mode for pure TS, browser mode for Svelte components)
- **Rust tests**: cargo test with tauri test features
- **Each task follows**: Write failing test → minimal implementation → refactor

### QA Policy
Every task MUST include agent-executed QA scenarios.
Evidence saved to `.sisyphus/evidence/task-{N}-{scenario-slug}.{ext}`.

- **Build verification**: `bun run build`, `cargo check` exit codes
- **Test verification**: `bun test` with pass/fail counts
- **Grading removal**: grep for forbidden terms across codebase
- **UI verification**: Playwright via Playwriter for component rendering

---

## Execution Strategy

### Parallel Execution Waves

```
Wave 1 (Foundation — scaffold + config + types):
├── Task 1: Scaffold Tauri 2 + Svelte 5 project [quick]
├── Task 2: Configure vitest with TDD infrastructure [quick]
├── Task 3: Define S.T.E.V.E site-profile TypeScript types from discovery-schema.json [quick]
├── Task 4: Create steve.db SQLite schema (migrations) [quick]
└── Task 5: Port constants.ts and shared utilities [quick]

Wave 2 (Core Lib — TDD: test first, then port):
├── Task 6: Port CDP client + actions (cdp-client.ts, cdp-actions.ts) [deep]
├── Task 7: Port browser management (browser.ts, webview-layout.ts, webview-lifecycle.ts) [deep]
├── Task 8: Port database layer (db.ts — adapted for steve.db) [deep]
├── Task 9: Port OAuth system (oauth.ts) [deep]
├── Task 10: Port DOM snapshot engine (dom-snapshot.ts, dom-snapshot-types.ts) [unspecified-high]
└── Task 11: Port autofill system (autofill.ts) [quick]

Wave 3 (Discovery + Agent Lib — depends on Wave 2):
├── Task 12: Port discovery engine (discover.ts — remove RubricCriterion, use site-profiler types) [deep]
├── Task 13: Port discovery intent system (discovery-intent.ts, discovery-ui.ts) [unspecified-high]
├── Task 14: Port confirmation flow (confirmation-flow.ts, type-mappers.ts adapted) [unspecified-high]
├── Task 15: Port agent system (agent-loop.ts, agent-types.ts, agent-api.ts, agent-dom.ts, agent-prompt.ts) [deep]
├── Task 16: Port skills API (skills-api.ts, skill-parser.ts, skill-creation-prompt.ts) [unspecified-high]
└── Task 17: Port site profile management (site-profiles.ts — adapted for JSON file storage) [unspecified-high]

Wave 4 (Rust Backend — depends on Wave 1):
├── Task 18: Port lib.rs — webview creation + IPC commands (no sidecar) [deep]
├── Task 19: Port lib.rs — CDP discovery + OAuth callback handling [deep]
├── Task 20: Port lib.rs — DB migrations for steve.db [quick]
└── Task 21: Configure tauri.conf.json + Cargo.toml for S.T.E.V.E [quick]

Wave 5 (UI Components — depends on Waves 2-4):
├── Task 22: Port App.svelte with simplified sidebar [visual-engineering]
├── Task 23: Port SetupWizard.svelte (OAuth flow UI) [visual-engineering]
├── Task 24: Port Browser.svelte (embedded webview + tabs) [visual-engineering]
├── Task 25: Create ActionPanel.svelte (Agent + Discovery tabs — from GradingPanel) [visual-engineering]
├── Task 26: Port Discovery components (Panel, ModeSelector, Chat, Progress, Results, Confirmation, SaveDialog) [visual-engineering]
├── Task 27: Port AgentChat.svelte [visual-engineering]
├── Task 28: Port Skills page + components (Skills.svelte, SkillCard, SkillSearch, SkillCreator) [visual-engineering]
├── Task 29: Port Dashboard.svelte (simplified) [visual-engineering]
└── Task 30: Port Settings pages (ProviderSettings, CredentialSettings) [visual-engineering]

Wave 6 (Integration + Cleanup — depends on all):
├── Task 31: Integration wiring — connect all components, verify routing [deep]
├── Task 32: Grading removal verification (grep + ast-grep audit) [unspecified-high]
├── Task 33: Build verification (bun build + cargo build) [quick]
└── Task 34: Icon + branding update (app name, icons, window title) [quick]

Wave FINAL (After ALL tasks — independent review, 4 parallel):
├── Task F1: Plan compliance audit (oracle)
├── Task F2: Code quality review (unspecified-high)
├── Task F3: Real manual QA via Playwriter (unspecified-high)
└── Task F4: Scope fidelity check (deep)

Critical Path: Task 1 → Task 6/7/8 → Task 12/15 → Task 18/19 → Task 22-25 → Task 31 → F1-F4
Parallel Speedup: ~65% faster than sequential
Max Concurrent: 6 (Wave 2)
```

### Dependency Matrix

| Task | Depends On | Blocks |
|------|-----------|--------|
| 1-5 | — | 6-11, 18-21 |
| 6 | 1, 5 | 12, 15, 18 |
| 7 | 1, 5 | 24, 25 |
| 8 | 1, 4 | 12, 16, 17 |
| 9 | 1, 5 | 23 |
| 10 | 1, 5 | 12, 13 |
| 11 | 1, 5 | 24 |
| 12 | 6, 8, 10, 3 | 26 |
| 13 | 10, 3 | 26 |
| 14 | 3, 12 | 26 |
| 15 | 6, 8 | 27 |
| 16 | 8 | 28 |
| 17 | 3, 8 | 26 |
| 18-19 | 1 | 24, 31 |
| 20-21 | 1, 4 | 31 |
| 22-30 | Waves 2-4 | 31 |
| 31-34 | All above | F1-F4 |

### Agent Dispatch Summary

- **Wave 1**: 5 tasks → `quick` ×5
- **Wave 2**: 6 tasks → `deep` ×4, `unspecified-high` ×1, `quick` ×1
- **Wave 3**: 6 tasks → `deep` ×2, `unspecified-high` ×4
- **Wave 4**: 4 tasks → `deep` ×2, `quick` ×2
- **Wave 5**: 9 tasks → `visual-engineering` ×9
- **Wave 6**: 4 tasks → `deep` ×1, `unspecified-high` ×1, `quick` ×2
- **FINAL**: 4 tasks → `oracle` ×1, `unspecified-high` ×2, `deep` ×1

---

## TODOs

- [x] 1. Scaffold Tauri 2 + Svelte 5 project

  **What to do**:
  - Run `npm create tauri-app@latest steve-desktop -- --template svelte` (or manual scaffold)
  - Configure package.json with deps from OGRE (minus katex, mathlive, turndown): @tauri-apps/api, @tauri-apps/plugin-http, @tauri-apps/plugin-shell, @tauri-apps/plugin-sql, @tauri-apps/plugin-process, marked, gray-matter
  - Add devDeps: @sveltejs/vite-plugin-svelte, svelte ^5, vite ^7, vitest, @vitest/ui
  - Create vite.config.js, svelte.config.js, jsconfig.json matching OGRE's patterns
  - Create index.html entry point with `<div id="app">`
  - Create src/main.js that mounts App.svelte
  - Create minimal src/App.svelte placeholder

  **Must NOT do**:
  - Do NOT include katex, mathlive, turndown, turndown-plugin-gfm
  - Do NOT copy OGRE's updater config or pubkey

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 2, 3, 4, 5)
  - **Blocks**: Tasks 6-11, 18-21
  - **Blocked By**: None

  **References**:
  - `ogre-desktop/package.json` — dependency list (copy runtime deps minus grading-specific ones)
  - `ogre-desktop/vite.config.js` — Vite config pattern (Svelte plugin + polling watch)
  - `ogre-desktop/svelte.config.js` — vitePreprocess setup
  - `ogre-desktop/jsconfig.json` — compiler options (ESNext, strict, source maps)
  - `ogre-desktop/index.html` — HTML shell pattern
  - `ogre-desktop/src/main.js` — 3-line Svelte mount pattern

  **Acceptance Criteria**:
  - [ ] `cd steve-desktop && bun install` exits 0
  - [ ] `cd steve-desktop && bun run build` exits 0 (minimal vite build)
  - [ ] src/main.js mounts App.svelte to #app div

  **QA Scenarios**:
  ```
  Scenario: Fresh scaffold builds successfully
    Tool: Bash
    Steps:
      1. cd steve-desktop && bun install
      2. bun run build
      3. ls dist/index.html
    Expected Result: All 3 commands exit 0, dist/index.html exists
    Evidence: .sisyphus/evidence/task-1-scaffold-build.txt

  Scenario: No grading dependencies present
    Tool: Bash
    Steps:
      1. grep -E "katex|mathlive|turndown" steve-desktop/package.json | wc -l
    Expected Result: 0
    Evidence: .sisyphus/evidence/task-1-no-grading-deps.txt
  ```

  **Commit**: YES
  - Message: `feat(steve-desktop): scaffold Tauri 2 + Svelte 5 project`
  - Files: `steve-desktop/package.json`, `steve-desktop/vite.config.js`, `steve-desktop/svelte.config.js`, `steve-desktop/index.html`, `steve-desktop/src/main.js`, `steve-desktop/src/App.svelte`

- [x] 2. Configure vitest with TDD infrastructure

  **What to do**:
  - Create vitest.config.ts with node environment for pure TS tests
  - Add test script to package.json: `"test": "vitest run"`
  - Create a sample test file `src/lib/__tests__/setup.test.ts` that verifies vitest works
  - Add @tauri-apps/api/mocks to devDependencies for IPC testing
  - Configure test globals (vi.stubGlobal patterns for WebSocket, fetch)

  **Must NOT do**:
  - Do NOT copy OGRE's integration test exclusions (they reference grading tests)

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 3, 4, 5)
  - **Blocks**: Tasks 6-17
  - **Blocked By**: None (can scaffold vitest config independently)

  **References**:
  - `ogre-desktop/vitest.config.ts` — test config pattern (node env, globals, include patterns)
  - `ogre-desktop/src/lib/discover.test.ts` — example test pattern (vi.stubGlobal, vi.fn)

  **Acceptance Criteria**:
  - [ ] `cd steve-desktop && bun test` runs and passes 1+ test
  - [ ] vitest.config.ts exists with node environment

  **QA Scenarios**:
  ```
  Scenario: Vitest runs successfully
    Tool: Bash
    Steps:
      1. cd steve-desktop && bun test
    Expected Result: 1 test passes, 0 failures
    Evidence: .sisyphus/evidence/task-2-vitest-runs.txt
  ```

  **Commit**: YES
  - Message: `test(steve-desktop): configure vitest with TDD infrastructure`
  - Files: `steve-desktop/vitest.config.ts`, `steve-desktop/src/lib/__tests__/setup.test.ts`

- [x] 3. Define S.T.E.V.E site-profile TypeScript types from discovery-schema.json

  **What to do**:
  - Create `src/lib/types/site-profile.ts` with TypeScript interfaces matching `discovery-schema.json`
  - Types: SiteProfile, InteractiveElements, WorkflowStep, Workflow, FrameInfo, SelectorCandidate, ElementContext
  - These replace OGRE's SiteProfile/SiteSelectors/FeedbackConfig/SaveConfig types entirely
  - Write tests for type guards / validation helpers

  **Must NOT do**:
  - Do NOT import from batch-grader.ts or any OGRE grading types
  - Do NOT include grading-specific fields (studentSection, scoreInput, feedbackBox)

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 2, 4, 5)
  - **Blocks**: Tasks 12, 13, 14, 17
  - **Blocked By**: None

  **References**:
  - `/home/shuff57/.config/opencode/skills/site-profiler/references/discovery-schema.json` — the authoritative schema
  - `.agents/site-profiles/butte-keenan-safecolleges-com/video-player.json` — real example of the format

  **Acceptance Criteria**:
  - [ ] `src/lib/types/site-profile.ts` compiles without errors
  - [ ] Types match discovery-schema.json exactly (interactive, workflows, frames, landmarks)
  - [ ] No references to grading/rubric/batch in the type file

  **QA Scenarios**:
  ```
  Scenario: Types compile and match schema
    Tool: Bash
    Steps:
      1. cd steve-desktop && npx tsc --noEmit src/lib/types/site-profile.ts
    Expected Result: exits 0, no type errors
    Evidence: .sisyphus/evidence/task-3-types-compile.txt

  Scenario: No grading references in types
    Tool: Bash
    Steps:
      1. grep -i "grading\|rubric\|student\|score\|feedback\|batch" steve-desktop/src/lib/types/site-profile.ts | wc -l
    Expected Result: 0
    Evidence: .sisyphus/evidence/task-3-no-grading.txt
  ```

  **Commit**: YES
  - Message: `feat(steve-desktop): define site-profile types from discovery-schema.json`
  - Files: `steve-desktop/src/lib/types/site-profile.ts`

- [x] 4. Create steve.db SQLite schema (migrations)

  **What to do**:
  - Define SQL migrations for steve.db (adapted from OGRE's lib.rs migrations):
    - Migration 1: `oauth_tokens` (provider, access_token, refresh_token, token_type, expires_at, created_at, updated_at)
    - Migration 2: `provider_configs` (id, api_url, api_key, model, is_active, created_at, updated_at)
    - Migration 3: `app_settings` (key, value) — seed with setup_complete='false'
    - Migration 4: `skills` (id, name, description, content, source, is_active, url_pattern, created_at)
    - Migration 5: `site_profiles` (id, domain, page_name, profile_json, created_at, updated_at)
  - Create `src/lib/schema.sql` or document migrations for lib.rs
  - Write tests validating schema correctness

  **Must NOT do**:
  - Do NOT include grading_sessions, batch_session, response_embeddings, visible_columns tables
  - Do NOT include history_visible_columns default setting

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 2, 3, 5)
  - **Blocks**: Tasks 8, 20
  - **Blocked By**: None

  **References**:
  - `ogre-desktop/src-tauri/src/lib.rs:700-900` — OGRE's migration SQL (adapt, remove grading tables)
  - `ogre-desktop/src/lib/db.ts` — frontend database API pattern

  **Acceptance Criteria**:
  - [ ] Schema file exists with 5 migrations (no grading tables)
  - [ ] No references to grading_sessions, batch_session, response_embeddings

  **QA Scenarios**:
  ```
  Scenario: No grading tables in schema
    Tool: Bash
    Steps:
      1. grep -i "grading_session\|batch_session\|response_embed\|visible_columns" steve-desktop/src/lib/schema.sql | wc -l
    Expected Result: 0
    Evidence: .sisyphus/evidence/task-4-no-grading-tables.txt
  ```

  **Commit**: YES
  - Message: `feat(steve-desktop): define steve.db schema (no grading tables)`
  - Files: `steve-desktop/src/lib/schema.sql`

- [x] 5. Port constants.ts and shared utilities

  **What to do**:
  - Copy `constants.ts` from OGRE, remove grading-specific constants
  - Update app name constants to S.T.E.V.E
  - Create `src/lib/utils/` with any shared utility functions needed across modules
  - Port helper functions used by multiple modules (URL parsing, text truncation, etc.)

  **Must NOT do**:
  - Do NOT include grading server URLs, rubric constants, or grading-specific config

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 2, 3, 4)
  - **Blocks**: Tasks 6-11
  - **Blocked By**: None

  **References**:
  - `ogre-desktop/src/lib/constants.ts` — constant definitions (adapt naming)

  **Acceptance Criteria**:
  - [ ] constants.ts exists with S.T.E.V.E branding (no OGRE references)
  - [ ] No grading-specific constants

  **Commit**: YES
  - Message: `feat(steve-desktop): port constants and shared utilities`
  - Files: `steve-desktop/src/lib/constants.ts`

- [x] 6. Port CDP client + actions (TDD)

  **What to do**:
  - RED: Write tests for CDPClient (connect, send, disconnect, timeout handling)
  - GREEN: Copy `cdp-client.ts` and `cdp-actions.ts` from OGRE, adapt
  - REFACTOR: Remove grading-specific actions (if any), clean imports
  - Remove `checkDangerousPatterns` grading references if present

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 2 (with Tasks 7-11)
  - **Blocked By**: Tasks 1, 5

  **References**:
  - `ogre-desktop/src/lib/cdp-client.ts` — WebSocket CDP client (202 lines)
  - `ogre-desktop/src/lib/cdp-actions.ts` — CDP action implementations (470 lines)
  - `ogre-desktop/src/lib/cdp-actions.test.ts` — existing tests to adapt

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/cdp-client.test.ts` passes
  - [ ] `bun test src/lib/cdp-actions.test.ts` passes

  **Commit**: YES
  - Message: `feat(steve-desktop): port CDP client and actions (TDD)`

- [x] 7. Port browser management (TDD)

  **What to do**:
  - RED: Write tests for browser API (createEmbeddedBrowser, navigate, inject, bounds)
  - GREEN: Copy `browser.ts`, `webview-layout.ts`, `webview-lifecycle.ts` from OGRE
  - REFACTOR: Remove grading-specific browser actions, clean imports
  - Use mockIPC for Tauri invoke() calls in tests

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 2 (with Tasks 6, 8-11)
  - **Blocked By**: Tasks 1, 5

  **References**:
  - `ogre-desktop/src/lib/browser.ts` — webview management (410 lines)
  - `ogre-desktop/src/lib/webview-layout.ts` — bounds calculation (86 lines)
  - `ogre-desktop/src/lib/webview-lifecycle.ts` — lifecycle utilities

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/browser.test.ts` passes
  - [ ] calculateWebviewBounds() correctly computes bounds for sidebar + panel layouts

  **Commit**: YES
  - Message: `feat(steve-desktop): port browser management (TDD)`

- [x] 8. Port database layer (TDD)

  **What to do**:
  - RED: Write tests for db.ts API (saveOAuthToken, getOAuthToken, saveProviderConfig, setSetting, skill CRUD)
  - GREEN: Copy `db.ts` from OGRE, adapt for steve.db schema (remove grading functions)
  - REFACTOR: Remove all grading-session, batch, rubric, embedding, calibration DB functions
  - Database name: `sqlite:steve.db`

  **Must NOT do**:
  - Do NOT include saveGradingSession, getGradingHistory, saveBatchResult, saveEmbedding functions
  - Do NOT import from batch-grader.ts

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 2 (with Tasks 6, 7, 9-11)
  - **Blocked By**: Tasks 1, 4

  **References**:
  - `ogre-desktop/src/lib/db.ts` — full DB API (keep auth + skills + profiles, remove grading)

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/db.test.ts` passes
  - [ ] `grep -i "grading\|rubric\|batch\|calibrat\|embedding" src/lib/db.ts | wc -l` returns 0

  **Commit**: YES
  - Message: `feat(steve-desktop): port database layer for steve.db (TDD)`

- [x] 9. Port OAuth system (TDD)

  **What to do**:
  - RED: Write tests for device flows (GitHub, OpenAI, Anthropic PKCE, Google), token refresh
  - GREEN: Copy `oauth.ts` from OGRE
  - REFACTOR: Update client IDs placeholder comments noting S.T.E.V.E needs its own registrations
  - Keep all 4 device flows + Ollama API key path

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 2 (with Tasks 6-8, 10-11)
  - **Blocked By**: Tasks 1, 5

  **References**:
  - `ogre-desktop/src/lib/oauth.ts` — OAuth device flows (GitHub, OpenAI, Anthropic PKCE, Google)

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/oauth.test.ts` passes
  - [ ] All 4 device flows + Ollama tested

  **Commit**: YES
  - Message: `feat(steve-desktop): port OAuth system (TDD)`

- [x] 10. Port DOM snapshot engine (TDD)

  **What to do**:
  - RED: Write tests for smartWalk, priority classification, sibling dedup, budget trimming
  - GREEN: Copy `dom-snapshot.ts` and `dom-snapshot-types.ts` from OGRE
  - REFACTOR: Remove grading-specific priority rules if any (data-student, data-score → keep as generic data-* detection)

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 2 (with Tasks 6-9, 11)
  - **Blocked By**: Tasks 1, 5

  **References**:
  - `ogre-desktop/src/lib/dom-snapshot.ts` — smart DOM walker
  - `ogre-desktop/src/lib/dom-snapshot-types.ts` — SnapshotNode, SnapshotResult types

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/dom-snapshot.test.ts` passes
  - [ ] Priority classification works for generic elements

  **Commit**: YES
  - Message: `feat(steve-desktop): port DOM snapshot engine (TDD)`

- [x] 11. Port autofill system (TDD)

  **What to do**:
  - RED: Write tests for generateAutoFillScript with various site patterns
  - GREEN: Copy `autofill.ts` from OGRE
  - REFACTOR: Keep LMS patterns (useful for video training sites that require login)

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 2 (with Tasks 6-10)
  - **Blocked By**: Tasks 1, 5

  **References**:
  - `ogre-desktop/src/lib/autofill.ts` — auto-fill script generator (237 lines)

  **Acceptance Criteria**:
  - [ ] `bun test src/lib/autofill.test.ts` passes

  **Commit**: YES
  - Message: `feat(steve-desktop): port autofill system (TDD)`

- [x] 12. Port discovery engine — remove RubricCriterion, use site-profiler types (TDD)

  **What to do**:
  - RED: Write tests for discovery workflow (capture → analyze → parse → validate → complete)
  - GREEN: Copy `discover.ts` from OGRE, adapt:
    - Replace ALL `RubricCriterion` imports/usages with generic types
    - Replace OGRE's SelectorMap with site-profiler's InteractiveElements
    - Remove rubric-specific extraction logic (lines ~1100-1400)
    - Update DISCOVERY_SYSTEM_PROMPT to be generic (not grading-specific)
    - Update DISCOVERY_USER_PROMPT_TEMPLATE to describe generic page elements
    - Keep the heuristic fast-path but make patterns generic
  - REFACTOR: Verify zero grading references remain

  **Must NOT do**:
  - Do NOT import from rubric-api.ts or batch-grader.ts
  - Do NOT keep grading-specific selectors in system prompt (studentSection, scoreInput, feedbackBox)

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 3 (with Tasks 13-17)
  - **Blocked By**: Tasks 3, 6, 8, 10

  **References**:
  - `ogre-desktop/src/lib/discover.ts` — discovery engine (~1500 lines, heavy adaptation needed)
  - `ogre-desktop/src/lib/discover.test.ts` — existing tests (adapt)
  - `/home/shuff57/.config/opencode/skills/site-profiler/SKILL.md` — target profile format
  - `/home/shuff57/.config/opencode/skills/site-profiler/references/discovery-schema.json` — output schema

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/discover.test.ts` passes
  - [ ] `grep -i "rubric\|grading\|student.*section\|score.*input\|feedback.*box" src/lib/discover.ts | wc -l` returns 0
  - [ ] Discovery produces site-profiler JSON format output

  **Commit**: YES
  - Message: `feat(steve-desktop): port discovery engine with site-profiler types (TDD)`

- [x] 13. Port discovery intent system (TDD)

  **What to do**:
  - RED: Write tests for 3 intent modes (form, chat, example) and hint parsing
  - GREEN: Copy `discovery-intent.ts` and `discovery-ui.ts` from OGRE
  - REFACTOR: Generalize form mode fields (remove "student count", "score inputs" → generic "interactive elements", "forms", "video players")

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 3 (with Tasks 12, 14-17)
  - **Blocked By**: Tasks 3, 10

  **References**:
  - `ogre-desktop/src/lib/discovery-intent.ts` — 3-mode intent system (502 lines)
  - `ogre-desktop/src/lib/discovery-intent.test.ts` — existing tests

  **Acceptance Criteria**:
  - [ ] `bun test src/lib/discovery-intent.test.ts` passes
  - [ ] All 3 modes produce valid DiscoveryHints

  **Commit**: YES
  - Message: `feat(steve-desktop): port discovery intent system (TDD)`

- [x] 14. Port confirmation flow (TDD)

  **What to do**:
  - RED: Write tests for confirmation state machine (accept, refine, cancel, back)
  - GREEN: Copy `confirmation-flow.ts` from OGRE, adapt selector keys for site-profiler format
  - GREEN: Adapt `type-mappers.ts` to convert discovery results → site-profiler profile format
  - REFACTOR: Remove grading-specific selector keys

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 3 (with Tasks 12, 13, 15-17)
  - **Blocked By**: Tasks 3, 12

  **References**:
  - `ogre-desktop/src/lib/confirmation-flow.ts` — confirmation state machine (162 lines)
  - `ogre-desktop/src/lib/type-mappers.ts` — DiscoveryResult → SiteProfile conversion
  - `ogre-desktop/src/lib/confirmation-flow.test.ts` — existing tests

  **Acceptance Criteria**:
  - [ ] `bun test src/lib/confirmation-flow.test.ts` passes
  - [ ] Confirmation flow uses site-profiler selector types, not grading ones

  **Commit**: YES
  - Message: `feat(steve-desktop): port confirmation flow (TDD)`

- [x] 15. Port agent system (TDD)

  **What to do**:
  - RED: Write tests for agent loop, action proposals, browser action execution
  - GREEN: Copy `agent-loop.ts`, `agent-types.ts`, `agent-api.ts`, `agent-dom.ts`, `agent-prompt.ts`
  - REFACTOR: Replace grading action vocabulary (fill score, write feedback) with generic actions (click, navigate, wait, fill, interact with iframe)

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 3 (with Tasks 12-14, 16-17)
  - **Blocked By**: Tasks 6, 8

  **References**:
  - `ogre-desktop/src/lib/agent-loop.ts` — agent automation loop
  - `ogre-desktop/src/lib/agent-types.ts` — agent type definitions
  - `ogre-desktop/src/lib/agent-api.ts` — agent API client
  - `ogre-desktop/src/lib/agent-dom.ts` — DOM fuzzy matching
  - `ogre-desktop/src/lib/agent-prompt.ts` — system prompts

  **Acceptance Criteria**:
  - [ ] Tests written BEFORE implementation
  - [ ] `bun test src/lib/agent-*.test.ts` passes
  - [ ] Agent prompt references generic page interaction, not grading

  **Commit**: YES
  - Message: `feat(steve-desktop): port agent system (TDD)`

- [x] 16. Port skills API (TDD)

  **What to do**:
  - RED: Write tests for skills CRUD, marketplace search, local sync
  - GREEN: Copy `skills-api.ts`, `skill-parser.ts`, `skill-creation-prompt.ts`
  - REFACTOR: These are already domain-agnostic — minimal changes needed

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 3 (with Tasks 12-15, 17)
  - **Blocked By**: Task 8

  **References**:
  - `ogre-desktop/src/lib/skills-api.ts` — skills CRUD + marketplace
  - `ogre-desktop/src/lib/skill-parser.ts` — markdown skill parsing
  - `ogre-desktop/src/lib/skill-creation-prompt.ts` — AI skill creation prompt

  **Acceptance Criteria**:
  - [ ] `bun test src/lib/skills-api.test.ts` passes

  **Commit**: YES
  - Message: `feat(steve-desktop): port skills API (TDD)`

- [x] 17. Port site profile management — JSON file storage (TDD)

  **What to do**:
  - RED: Write tests for profile CRUD (save, load, list, delete, findByUrl)
  - GREEN: Adapt `site-profiles.ts` to store profiles as JSON files in `.agents/site-profiles/`
  - GREEN: Remove OGRE's SQLite-based ProfileStorageImpl, replace with file-based storage
  - GREEN: Remove built-in grading profiles (MyOpenMath, Canvas SpeedGrader)
  - REFACTOR: Use site-profiler types from Task 3

  **Must NOT do**:
  - Do NOT include built-in grading profiles
  - Do NOT use SQLite for profile storage (use JSON files)

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high`
  - **Skills**: [`superpowers/test-driven-development`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 3 (with Tasks 12-16)
  - **Blocked By**: Tasks 3, 8

  **References**:
  - `ogre-desktop/src/lib/site-profiles.ts` — profile CRUD (adapt storage to JSON files)
  - `.agents/site-profiles/` — existing profiles from site-profiler skill testing

  **Acceptance Criteria**:
  - [ ] `bun test src/lib/site-profiles.test.ts` passes
  - [ ] Profiles saved as JSON files to .agents/site-profiles/<domain>/
  - [ ] No built-in grading profiles

  **Commit**: YES
  - Message: `feat(steve-desktop): port site profiles with JSON file storage (TDD)`

- [x] 18. Port lib.rs — webview creation + IPC commands (no sidecar)

  **What to do**:
  - Copy OGRE's lib.rs, then surgically remove:
    - SidecarState struct and spawn_sidecar() (~200 lines)
    - Tray menu server-status items
    - Close-to-tray behavior (just quit on close)
    - Grading-specific DB migrations
  - Keep: webview creation (both Tauri native + Linux wry/GTK paths), IPC commands (create_embedded_browser, navigate_embedded, set_webview_bounds, inject_webview_script, hide/show/destroy_webview, get_embedded_url)
  - Update DB name: `sqlite:steve.db`
  - Update app identifier: `com.steve.desktop`

  **Must NOT do**:
  - Do NOT include sidecar spawning, tray server status, externalBin config
  - Do NOT include grading_sessions or batch_session migrations

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 4 (with Tasks 19-21)
  - **Blocked By**: Task 1

  **References**:
  - `ogre-desktop/src-tauri/src/lib.rs` — 1568-line Rust backend (keep ~1000 lines, remove ~500)

  **Acceptance Criteria**:
  - [ ] `cd steve-desktop/src-tauri && cargo check` exits 0
  - [ ] `grep -i "sidecar\|spawn_sidecar\|grading.server" src-tauri/src/lib.rs | wc -l` returns 0
  - [ ] DB name is sqlite:steve.db

  **Commit**: YES
  - Message: `feat(steve-desktop): port lib.rs — webview, IPC, DB (no sidecar)`

- [x] 19. Port lib.rs — CDP discovery + OAuth callback handling

  **What to do**:
  - Keep get_cdp_port and discover_cdp_target commands
  - Keep OAuth callback handling (browser redirect)
  - Verify these work with the adapted webview creation from Task 18

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 4 (with Tasks 18, 20, 21)
  - **Blocked By**: Task 1

  **References**:
  - `ogre-desktop/src-tauri/src/lib.rs` — CDP and OAuth sections

  **Acceptance Criteria**:
  - [ ] `cargo check` passes with CDP + OAuth commands

  **Commit**: YES (combined with Task 18 if sequential)
  - Message: `feat(steve-desktop): port CDP discovery + OAuth callback`

- [x] 20. Port lib.rs — DB migrations for steve.db

  **What to do**:
  - Implement the 5 migrations from Task 4's schema.sql in Rust
  - Remove grading-specific migrations (grading_sessions, batch_session, response_embeddings, visible_columns)
  - Keep: oauth_tokens, provider_configs, app_settings, skills, site_profiles

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 4 (with Tasks 18, 19, 21)
  - **Blocked By**: Tasks 1, 4

  **References**:
  - Task 4's schema.sql
  - `ogre-desktop/src-tauri/src/lib.rs:700-900` — OGRE's migration pattern

  **Acceptance Criteria**:
  - [ ] 5 migrations in lib.rs matching schema.sql
  - [ ] No grading table migrations

  **Commit**: YES (combined with Task 18)
  - Message: `feat(steve-desktop): add steve.db migrations`

- [x] 21. Configure tauri.conf.json + Cargo.toml for S.T.E.V.E

  **What to do**:
  - Create tauri.conf.json:
    - productName: "S.T.E.V.E Desktop"
    - identifier: "com.steve.desktop"
    - window title: "S.T.E.V.E - Sitting Through Every Video Entirely"
    - Remove externalBin, resources (no sidecar)
    - Remove updater config (add later if needed)
    - Keep security: { csp: null }
  - Create Cargo.toml:
    - Copy OGRE's deps minus sidecar-specific ones
    - Keep: tauri, tauri-plugin-shell, tauri-plugin-http, tauri-plugin-sql, tokio, serde, reqwest, uuid
    - Linux: gtk, wry

  **Must NOT do**:
  - Do NOT include externalBin or resources for grading-server
  - Do NOT include updater pubkey pointing to OGRE's releases

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 4 (with Tasks 18-20)
  - **Blocked By**: Task 1

  **References**:
  - `ogre-desktop/src-tauri/tauri.conf.json` — config template (adapt)
  - `ogre-desktop/src-tauri/Cargo.toml` — Rust deps (adapt)

  **Acceptance Criteria**:
  - [ ] tauri.conf.json has S.T.E.V.E branding, no externalBin/resources
  - [ ] Cargo.toml compiles (cargo check)
  - [ ] `grep -i "ogre\|grading" steve-desktop/src-tauri/tauri.conf.json | wc -l` returns 0

  **Commit**: YES
  - Message: `feat(steve-desktop): configure tauri.conf.json + Cargo.toml`

- [x] 22. Port App.svelte with simplified sidebar

  **What to do**:
  - Copy OGRE's App.svelte, strip sidebar to: Dashboard, Browse, Skills, Settings
  - Remove nav items: Grade Now, Grading History, Rubrics, Activity Log, Site Templates
  - Update branding: O.G.R.E → S.T.E.V.E
  - Keep: setup_complete gate, sidebar collapse, page routing, window resize handling
  - Remove: listenSessionComplete import (from server.ts), grading-specific event listeners

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 23-30)
  - **Blocked By**: Waves 2-4

  **References**:
  - `ogre-desktop/src/App.svelte` — root component (adapt sidebar nav)

  **Acceptance Criteria**:
  - [ ] Sidebar shows only: Dashboard, Browse, Skills, Settings
  - [ ] `grep -i "grade\|rubric\|history\|logs" src/App.svelte | wc -l` returns 0
  - [ ] Branding says S.T.E.V.E, not O.G.R.E

  **Commit**: YES
  - Message: `feat(steve-desktop): port App.svelte with simplified sidebar`

- [x] 23. Port SetupWizard.svelte (OAuth flow UI)

  **What to do**:
  - Copy OGRE's SetupWizard.svelte as-is (it's already domain-agnostic)
  - Update any OGRE branding text to S.T.E.V.E
  - Keep all 4 OAuth flows + Ollama API key path

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22, 24-30)
  - **Blocked By**: Task 9

  **References**:
  - `ogre-desktop/src/pages/SetupWizard.svelte` — 4-step OAuth wizard

  **Acceptance Criteria**:
  - [ ] Setup wizard renders 4 steps
  - [ ] No OGRE branding in UI text

  **Commit**: YES
  - Message: `feat(steve-desktop): port SetupWizard with S.T.E.V.E branding`

- [x] 24. Port Browser.svelte (embedded webview + tabs)

  **What to do**:
  - Copy OGRE's Browser.svelte, remove GradingPanel integration
  - Replace GradingPanel with ActionPanel (Task 25)
  - Keep: multi-tab management, URL bar, navigation controls, webview bounds calculation
  - Keep: drawer-panel resize handle for ActionPanel

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22, 23, 25-30)
  - **Blocked By**: Tasks 7, 11, 18

  **References**:
  - `ogre-desktop/src/pages/Browser.svelte` — browser with tabs (adapt)

  **Acceptance Criteria**:
  - [ ] Browser page renders with URL bar and tab management
  - [ ] No grading panel references

  **Commit**: YES
  - Message: `feat(steve-desktop): port Browser with multi-tab (no grading panel)`

- [x] 25. Create ActionPanel.svelte (Agent + Discovery tabs only)

  **What to do**:
  - Create from OGRE's GradingPanel.svelte but with only 2 tabs: Agent + Discovery
  - Remove: Grader tab, batch sub-mode, rubric selection, solver chat
  - Keep: provider/model selector, resizable panel, collapse toggle, keyboard shortcuts
  - Rename GradingPanel → ActionPanel throughout

  **Must NOT do**:
  - Do NOT include Grader mode, batch mode, RubricCard, StudentWorkCard, SolverChat

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22-24, 26-30)
  - **Blocked By**: Waves 2-4

  **References**:
  - `ogre-desktop/src/pages/GradingPanel.svelte` — 599-line tabbed panel (strip to 2 tabs)

  **Acceptance Criteria**:
  - [ ] ActionPanel shows exactly 2 tabs: Agent, Discovery
  - [ ] `grep -i "grading\|grader\|rubric\|batch\|solver" src/pages/ActionPanel.svelte | wc -l` returns 0

  **Commit**: YES
  - Message: `feat(steve-desktop): create ActionPanel (Agent + Discovery tabs)`

- [x] 26. Port Discovery components (all 7)

  **What to do**:
  - Copy all discovery UI components from OGRE:
    - DiscoveryPanel.svelte, DiscoveryModeSelector.svelte, DiscoveryChat.svelte
    - DiscoveryProgress.svelte, DiscoveryResults.svelte
    - DiscoveryConfirmation.svelte, DiscoverySaveDialog.svelte
  - Adapt imports to use site-profiler types from Task 3
  - Update form mode fields to be generic (not grading-specific)
  - Also port: ProfileManager.svelte, ProviderSelector.svelte

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22-25, 27-30)
  - **Blocked By**: Tasks 12, 13, 14, 17

  **References**:
  - `ogre-desktop/src/components/grading/Discovery*.svelte` — all 7 discovery components
  - `ogre-desktop/src/components/grading/ProfileManager.svelte`
  - `ogre-desktop/src/components/grading/ProviderSelector.svelte`

  **Acceptance Criteria**:
  - [ ] All 7 discovery components render without errors
  - [ ] Mode selector shows Form/Chat/Example options

  **Commit**: YES
  - Message: `feat(steve-desktop): port discovery UI components`

- [x] 27. Port AgentChat.svelte

  **What to do**:
  - Copy OGRE's AgentChat.svelte
  - Adapt action vocabulary from grading to generic browser actions
  - Keep: review/auto modes, message display, action proposals, context tracking

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22-26, 28-30)
  - **Blocked By**: Task 15

  **References**:
  - `ogre-desktop/src/components/grading/AgentChat.svelte` — agent chat UI

  **Acceptance Criteria**:
  - [ ] Agent chat renders with review/auto mode toggle
  - [ ] No grading-specific action types visible

  **Commit**: YES
  - Message: `feat(steve-desktop): port AgentChat component`

- [x] 28. Port Skills page + components

  **What to do**:
  - Copy Skills.svelte (3-tab page: My Skills / Find Skills / Create Skill)
  - Copy SkillCard.svelte, SkillSearch.svelte, SkillCreator.svelte, SkillPicker.svelte
  - These are already domain-agnostic — minimal changes needed
  - Update any OGRE branding

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22-27, 29-30)
  - **Blocked By**: Task 16

  **References**:
  - `ogre-desktop/src/pages/Skills.svelte` — skills page
  - `ogre-desktop/src/components/skills/Skill*.svelte` — 4 skill components

  **Acceptance Criteria**:
  - [ ] Skills page shows 3 tabs
  - [ ] SkillSearch renders marketplace UI

  **Commit**: YES
  - Message: `feat(steve-desktop): port Skills page and components`

- [x] 29. Port Dashboard.svelte (simplified)

  **What to do**:
  - Copy OGRE's Dashboard.svelte, remove grading-specific health indicators
  - Keep: provider health, Ollama connection status
  - Remove: grading server status, grading stats, recent sessions

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22-28, 30)
  - **Blocked By**: Waves 2-4

  **References**:
  - `ogre-desktop/src/pages/Dashboard.svelte` — dashboard (simplify)

  **Acceptance Criteria**:
  - [ ] Dashboard shows provider health
  - [ ] No grading stats or server status

  **Commit**: YES
  - Message: `feat(steve-desktop): port simplified Dashboard`

- [x] 30. Port Settings pages (ProviderSettings + CredentialSettings)

  **What to do**:
  - Copy ProviderSettings.svelte and CredentialSettings.svelte
  - Remove: ColumnSettings, EmbeddingSettings, ThemeSettings (if grading-specific)
  - Create simplified Settings.svelte with just Providers + Credentials tabs

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 5 (with Tasks 22-29)
  - **Blocked By**: Tasks 8, 9

  **References**:
  - `ogre-desktop/src/pages/settings/ProviderSettings.svelte`
  - `ogre-desktop/src/pages/settings/CredentialSettings.svelte`

  **Acceptance Criteria**:
  - [ ] Settings shows Providers + Credentials tabs
  - [ ] No grading-specific settings (column config, embedding config)

  **Commit**: YES
  - Message: `feat(steve-desktop): port Settings (Providers + Credentials)`

- [x] 31. Integration wiring — connect all components, verify routing

  **What to do**:
  - Wire all components together in App.svelte routing
  - Verify navigation: Dashboard → Browse → Skills → Settings
  - Verify ActionPanel opens from Browser page
  - Verify Discovery → Agent tab switching works
  - Test full setup wizard → authenticated → browser flow

  **Recommended Agent Profile**:
  - **Category**: `deep`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: NO
  - **Sequential**: After all Wave 5 tasks
  - **Blocked By**: Tasks 22-30

  **Acceptance Criteria**:
  - [ ] All 4 sidebar pages render
  - [ ] Browser → ActionPanel → Discovery/Agent flow works
  - [ ] Setup wizard → main app transition works

  **Commit**: YES
  - Message: `feat(steve-desktop): wire all components and verify routing`

- [x] 32. Grading removal verification (grep + ast-grep audit)

  **What to do**:
  - Run comprehensive grep for ALL grading terms across steve-desktop/src/
  - Use ast-grep to find structural patterns (import from grading files, RubricCriterion usage)
  - Fix any remaining references
  - Verify: no grading DB tables, no grading API calls, no grading components

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 6 (with Tasks 33, 34)
  - **Blocked By**: Task 31

  **Acceptance Criteria**:
  - [ ] `grep -ri "grading\|rubric\|batch_grader\|student_count\|mean_score\|calibrat" steve-desktop/src/ | wc -l` returns 0
  - [ ] `grep -ri "from.*batch-grader\|from.*grading-api\|from.*rubric-api\|from.*server" steve-desktop/src/ | wc -l` returns 0

  **Commit**: YES
  - Message: `chore(steve-desktop): verify all grading references removed`

- [x] 33. Build verification (bun build + cargo build)

  **What to do**:
  - Run full frontend build: `bun run build`
  - Run full Rust check: `cargo check`
  - Run all tests: `bun test`
  - Fix any build errors

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: [`superpowers/verification-before-completion`]

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 6 (with Tasks 32, 34)
  - **Blocked By**: Task 31

  **Acceptance Criteria**:
  - [ ] `bun run build` exits 0
  - [ ] `cargo check` exits 0
  - [ ] `bun test` — all pass, 0 fail

  **Commit**: YES (if fixes needed)
  - Message: `fix(steve-desktop): resolve build errors`

- [x] 34. Icon + branding update

  **What to do**:
  - Update app icon (placeholder or S.T.E.V.E branded)
  - Update window title: "S.T.E.V.E - Sitting Through Every Video Entirely"
  - Update sidebar brand text
  - Verify no OGRE branding remains anywhere

  **Recommended Agent Profile**:
  - **Category**: `quick`
  - **Skills**: []

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 6 (with Tasks 32, 33)
  - **Blocked By**: Task 31

  **Acceptance Criteria**:
  - [ ] `grep -ri "ogre\|O\.G\.R\.E" steve-desktop/src/ steve-desktop/src-tauri/ | wc -l` returns 0
  - [ ] Window title shows S.T.E.V.E

  **Commit**: YES
  - Message: `chore(steve-desktop): update branding to S.T.E.V.E`

---

## Final Verification Wave (MANDATORY — after ALL implementation tasks)

> 4 review agents run in PARALLEL. ALL must APPROVE. Rejection → fix → re-run.

- [x] F1. **Plan Compliance Audit** — `oracle`
  Read the plan end-to-end. For each "Must Have": verify implementation exists (read file, run command). For each "Must NOT Have": search codebase for forbidden patterns — reject with file:line if found. Check evidence files exist in .sisyphus/evidence/. Compare deliverables against plan.
  Output: `Must Have [N/N] | Must NOT Have [N/N] | Tasks [N/N] | VERDICT: APPROVE/REJECT`

- [x] F2. **Code Quality Review** — `unspecified-high`
  Run `cargo check` + `bun test`. Review all files for: `as any`/`@ts-ignore`, empty catches, console.log in prod, commented-out code, unused imports. Check AI slop: excessive comments, over-abstraction, generic names. Verify no OGRE branding/naming remains.
  Output: `Build [PASS/FAIL] | Tests [N pass/N fail] | Files [N clean/N issues] | VERDICT`

- [ ] F3. **Real Manual QA** — `unspecified-high` (+ `playwright` skill)
  Start from clean state. Verify: app launches, setup wizard appears, OAuth flow completes (mock), browser loads page, discover tab works (all 3 modes), agent tab shows chat, skills page loads. Test cross-tab integration. Save to `.sisyphus/evidence/final-qa/`.
  Output: `Scenarios [N/N pass] | Integration [N/N] | VERDICT`

- [x] F4. **Scope Fidelity Check** — `deep`
  For each task: read "What to do", read actual diff. Verify 1:1 compliance. Run grading removal grep. Detect unaccounted changes. Flag scope creep.
  Output: `Tasks [N/N compliant] | Grading refs [0 found] | VERDICT`

---

## Commit Strategy

Each wave produces atomic commits following this pattern:
- `test(steve-desktop): add [module] tests` — RED phase
- `feat(steve-desktop): port [module] from OGRE` — GREEN phase
- `refactor(steve-desktop): clean [module]` — REFACTOR phase
- `chore(steve-desktop): remove grading references from [module]` — cleanup

---

## Success Criteria

### Verification Commands
```bash
cd steve-desktop && bun install                    # Expected: exits 0
cd steve-desktop && bun run build                  # Expected: exits 0
cd steve-desktop/src-tauri && cargo check           # Expected: exits 0
cd steve-desktop && bun test                       # Expected: all pass, 0 fail
grep -r "grading\|rubric\|batch_grader" steve-desktop/src/ | wc -l  # Expected: 0
grep -r "from.*batch-grader\|from.*grading-api" steve-desktop/src/ | wc -l  # Expected: 0
```

### Final Checklist
- [ ] All "Must Have" present
- [ ] All "Must NOT Have" absent
- [ ] All tests pass (vitest + cargo test)
- [ ] App launches and shows setup wizard
- [ ] OAuth flow completes for at least one provider
- [ ] Browser embeds and navigates to external URL
- [ ] Discover tab runs all 3 modes
- [ ] Agent tab shows chat interface
- [ ] Skills page lists/searches/creates skills
- [ ] Site profiles saved to .agents/site-profiles/

# Learnings — steve-desktop

## Project Structure
- OGRE source: /home/shuff57/Documents/GitHub/O.G.R.E-OllamaGradingRubricEvaluator/ogre-desktop/
- Output: /home/shuff57/Documents/GitHub/S.T.E.V.E-SittingThroughEveryVideoEntirely/steve-desktop/
- Stack: Tauri 2 + Svelte 5 runes ($state, $effect, $bindable)
- Tests: vitest (node mode for TS, browser mode for Svelte)
- DB: sqlite:steve.db (NOT ogre.db)
- App ID: com.steve.desktop
- No sidecar — embedded browser only

## Site Profile Format
- Use site-profiler skill's JSON schema (NOT OGRE's SiteProfile)
- Schema at: /home/shuff57/.config/opencode/skills/site-profiler/references/discovery-schema.json
- Storage: .agents/site-profiles/<domain>/<page>.json files (NOT SQLite)

## OGRE Files to COPY (adapted)
- src/lib/cdp-client.ts, cdp-actions.ts
- src/lib/browser.ts, webview-layout.ts, webview-lifecycle.ts
- src/lib/db.ts (remove grading functions)
- src/lib/oauth.ts
- src/lib/dom-snapshot.ts, dom-snapshot-types.ts
- src/lib/autofill.ts
- src/lib/discover.ts (remove RubricCriterion)
- src/lib/discovery-intent.ts, discovery-ui.ts
- src/lib/confirmation-flow.ts, type-mappers.ts
- src/lib/agent-loop.ts, agent-types.ts, agent-api.ts, agent-dom.ts, agent-prompt.ts
- src/lib/skills-api.ts, skill-parser.ts, skill-creation-prompt.ts
- Components: Discovery*, AgentChat, Skills*, Dashboard, Browser, SetupWizard

## OGRE Files to SKIP (never copy)
- src/lib/server.ts, provider-sync.ts
- src/lib/grading-api.ts, rubric-api.ts, batch-grader.ts
- src/lib/vector-store.ts, embedding-stats.ts, cosine-similarity.ts
- src/lib/calibration-*.ts
- Components: RubricCard, StudentWorkCard, BatchPanel, SolverChat, ExtractionConfigPanel
- Pages: History.svelte, Logs.svelte, Rubrics.svelte

## Task 1: Scaffold Completion

### Successful Patterns
- Manual file creation (Write tool) avoids interactive prompts from `npm create tauri-app`
- Exact OGRE patterns for vite.config.js, svelte.config.js, jsconfig.json work without modification
- Svelte 5 runes ($state) work in App.svelte placeholder
- bun install resolves all deps correctly (134 packages in 2.81s)
- vite build succeeds with minimal config (no Tauri src-tauri/ yet)

### Verified Constraints
- NO grading deps: katex, mathlive, turndown, updater all absent (grep count = 0)
- NO @tauri-apps/plugin-updater (correctly excluded)
- Runtime deps: marked, gray-matter, all Tauri plugins present
- Dev deps: vitest, @vitest/ui, jsdom all present

### Build Evidence
- vite build: 109 modules, 329ms, 24.45 kB JS, 0.68 kB CSS
- dist/ created with index.html, assets/
- All scripts work: dev, build, preview, test, tauri, tauri:dev, tauri:build

### Git Initialization
- Repo initialized, first commit: b502370
- 3707 files (node_modules + dist + bun.lock)
- Ready for Tauri src-tauri/ scaffold (Task 18-21)

## Task 11: autofill.ts Port (TDD)

**Pattern:** Porting library code from OGRE to S.T.E.V.E using TDD.

**Key Success Factors:**
1. **Test-first discipline** — Write comprehensive tests before implementation. This caught edge cases (special char escaping, wildcard patterns, null handling).
2. **Type definitions inline** — SiteCredential interface defined in autofill.ts since db.ts doesn't exist yet. Keeps module self-contained.
3. **LMS patterns preserved** — Canvas, Moodle, Blackboard, MyOpenMath selectors copied as-is. These are directly relevant for S.T.E.V.E's video training automation (many LMS platforms host training videos).
4. **No form auto-submit** — Script only fills and dispatches events, respecting user control (aligns with S.T.E.V.E philosophy).

**Test Coverage:**
- 29 tests, 64 assertions
- generateAutoFillScript: 11 tests (script generation, escaping, framework compat, retry logic)
- matchCredentialsToUrl: 10 tests (substring matching, wildcard patterns, case-insensitivity, null handling)
- LMS_LOGIN_SELECTORS: 8 tests (all platforms present, required fields)

**Verification:**
- All tests pass: `bun test src/lib/autofill.test.ts` → 29 pass, 0 fail
- No LSP errors on autofill.ts or autofill.test.ts
- Evidence: `.sisyphus/evidence/task-11-autofill-tests-pass.txt`

**Reusable Pattern:**
For future library ports, follow: (1) Read source, (2) Write tests first, (3) Port implementation, (4) Verify tests pass, (5) Generate evidence.

## Task 8: db.ts Port (TDD)

- Ported DB layer should use `DB_NAME` from `src/lib/constants.ts` and resolve to `sqlite:steve.db`.
- Keep DB API focused on STEVE schema tables only: `oauth_tokens`, `provider_configs`, `app_settings`, `skills`, `site_profiles`.
- Grading/batch/embedding/calibration functions must be fully excluded from exports and implementation.
- Under this toolchain, `vi.hoisted` failed in `bun test`; top-level `vi.fn()` mocks for `@tauri-apps/plugin-sql` are reliable.
- Evidence bundle for this task should include: db test pass output, banned-term grep count output, and build output.

## Task 7: browser/webview port (TDD)
- RED phase validated with missing-module failures before implementation:
  - bun test src/lib/webview-layout.test.ts → Cannot find module './webview-layout'
  - bun test src/lib/browser.test.ts → Cannot find module './browser'
- Ported files:
  - src/lib/webview-layout.ts (pure bounds math with clamp and extraTopOffset)
  - src/lib/webview-lifecycle.ts (lifecycle timing + destroy guards)
  - src/lib/browser.ts (embedded webview API wrappers)
- Browser API intentionally excludes grading-specific methods and server/grading imports.
- Added tests:
  - src/lib/webview-layout.test.ts
  - src/lib/browser.test.ts
  - src/lib/webview-lifecycle.test.ts (additional guardrail coverage)
- Required verification:
  - bun test src/lib/browser.test.ts → pass
  - bun test src/lib/webview-layout.test.ts → pass
- Evidence written: .sisyphus/evidence/task-7-browser-tests-pass.txt

## Task 9: oauth.ts Port (TDD)

- Ported OAuth library into `src/lib/oauth.ts` with required flows only: GitHub device flow, OpenAI device flow, Anthropic PKCE copy/paste flow, Google device flow, plus Ollama connectivity validation.
- Added required TODO placeholders for STEVE-specific OAuth registrations and preserved OGRE client IDs only as inline reference comments:
  - `GITHUB_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP' // OGRE: ...`
  - `OPENAI_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP' // OGRE: ...`
  - `ANTHROPIC_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP' // OGRE: ...`
  - `GOOGLE_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP' // OGRE: ...`
- Added test-first coverage in `src/lib/oauth.test.ts` for:
  - GitHub device-code endpoint + token polling endpoint usage
  - OpenAI token polling endpoint usage
  - Anthropic PKCE correctness (`code_challenge` equals SHA-256(verifier) base64url)
  - Google device flow verification URL open
  - `validateOllamaConnection()` false on network error
  - `refreshAnthropicToken()` endpoint call
  - `revokeGoogleToken()` best-effort behavior
- Runtime compatibility note: in this `bun test` environment, `vi.stubGlobal` and `vi.advanceTimersByTimeAsync` were unavailable; direct `globalThis.fetch` replacement and `vi.advanceTimersByTime` worked reliably.
- Verification complete:
  - `bun test src/lib/oauth.test.ts` → 7 pass, 0 fail
  - LSP diagnostics clean on `src/lib/oauth.ts` and `src/lib/oauth.test.ts`
  - Evidence file: `.sisyphus/evidence/task-9-oauth-tests-pass.txt`

## Task 6: cdp-client + cdp-actions Port (TDD)
- RED verified by missing-module failures before implementation:
  - `bun test src/lib/cdp-client.test.ts` -> Cannot find module './cdp-client'
  - `bun test src/lib/cdp-actions.test.ts` -> Cannot find module './cdp-client'
- Ported `src/lib/cdp-client.ts` as a thin CDP WebSocket client with target discovery, JSON-RPC request/response handling, timeout behavior, and event subscriptions.
- Ported `src/lib/cdp-actions.ts` with generic-only CDP actions and wrappers:
  - kept: `connectCDP`, `connect`, `disconnectCDP`, `disconnect`, `send`, `evalScript`, `injectScript`, `captureWebviewScreenshot`, `pwClick`, `pwType`, `pwGetText`, `cdpScreenshot`
  - excluded grading-specific actions and grading imports.
- Added tests first:
  - `src/lib/cdp-client.test.ts` (6 tests)
  - `src/lib/cdp-actions.test.ts` (4 tests)
- Runtime gotcha confirmed in this environment: `vi.unstubAllGlobals` and `vi.advanceTimersByTimeAsync` are unavailable under `bun test`; direct global restoration + `vi.advanceTimersByTime` works.
- Verification:
  - `bun test src/lib/cdp-client.test.ts` -> 6 pass
  - `bun test src/lib/cdp-actions.test.ts` -> 4 pass
  - LSP diagnostics clean on all changed files
- Evidence file: `.sisyphus/evidence/task-6-cdp-tests-pass.txt`

## Task 10: dom-snapshot Port (TDD)

- RED verified with `bun test src/lib/dom-snapshot.test.ts` failing on missing `./dom-snapshot` module before implementation.
- Ported `src/lib/dom-snapshot-types.ts` and `src/lib/dom-snapshot.ts` for STEVE with generic DOM-signal logic only.
- Priority rules were adapted away from grading-specific signals: generic `data-*`, `aria-*`, and `role` detection drive interactive priority instead of hard-coded grading attributes.
- In node-mode vitest/bun tests, validate the pure helpers only: script-string generation, JSON parsing, prompt formatting, and type guards. Do not try to execute the injected browser IIFE in these unit tests.
- Verification complete:
  - `bun test src/lib/dom-snapshot.test.ts` -> 8 pass
  - `bun run build` -> pass
  - LSP diagnostics clean on `src/lib/dom-snapshot.ts`, `src/lib/dom-snapshot-types.ts`, and `src/lib/dom-snapshot.test.ts`
- Evidence file: `.sisyphus/evidence/task-10-dom-snapshot-tests-pass.txt`

## Task 17: site-profiles.ts File Storage (TDD)
- RED verified with `bun test src/lib/site-profiles.test.ts` failing on missing `./site-profiles` module before implementation.
- `site-profiles.ts` should stay file-based and generic: JSON files under `.agents/site-profiles/<domain-path>/<page>.json`, with no SQLite storage reuse and no grading-profile defaults.
- For Tauri invoke tests in this repo, top-level `vi.fn()` mocks for `@tauri-apps/api/core` work reliably under `bun test`.
- Verification complete:
  - `bun test src/lib/site-profiles.test.ts` -> 11 pass
  - `bun run build` -> pass
  - LSP diagnostics clean on `src/lib/site-profiles.ts` and `src/lib/site-profiles.test.ts`
- Evidence file: `.sisyphus/evidence/task-17-profiles-tests-pass.txt`

## Task 13: discovery-intent + discovery-ui Port (TDD)

- RED verified first with `bun test src/lib/discovery-intent.test.ts` failing on missing `./discovery-intent` before implementation.
- OGRE's three-mode intent structure ports cleanly if only the form-mode fields and prompt language are generalized.
- Generic required selector names used for STEVE discovery: `videoPlayer`, `interactiveForm`, `navigationControl`, `quizElement`.
- `bun test` works against vitest-style imports in this repo; using `bun:test` caused an LSP module-resolution error even though Bun could execute tests.
- `discovery-ui.ts` can stay as a thin browser highlight helper; only labels and internal highlight data-attributes needed STEVE-specific renaming.
- Verification complete:
  - `bun test src/lib/discovery-intent.test.ts` -> 10 pass
  - `bun run build` -> pass
  - LSP diagnostics clean on `src/lib/discovery-intent.ts`, `src/lib/discovery-ui.ts`, and `src/lib/discovery-intent.test.ts`
- Evidence file: `.sisyphus/evidence/task-13-intent-tests-pass.txt`

## Task 15: agent loop stack port (TDD)

- RED was validated by running `bun test src/lib/agent-loop.test.ts` before implementation; initial failure was missing `./agent-loop` module.
- For nested iframe action typing, `Exclude<BrowserAction, { type: 'iframe_interact' }>` avoids union-property access errors that occur with `Omit` on discriminated unions.
- In this repo, keep agent vocabulary generic (`click`, `fill`, `navigate`, `wait`, `keyboard`, `scroll`, `iframe_interact`) and normalize legacy `type` action inputs inside loop mapping.
- Minimal evented controller shape (`on/start/stop/approve/skip/getState`) works well for bun/vitest tests and preserves OGRE-like state-machine flow.
- Verification completed with clean LSP diagnostics on all changed agent files and passing test evidence at `.sisyphus/evidence/task-15-agent-tests-pass.txt`.

## Task 12: discover.ts Port (TDD)

- RED verified first by running `bun test src/lib/discover.test.ts` before implementation; initial failure was missing `runDiscovery` export.
- `discover.ts` was rewritten as a generic discovery engine around `SiteProfile`-shaped output, with no rubric/grading/student scoring terminology.
- `buildDiscoveryPrompt` accepts either structured hints or a simple hint string to keep prompt construction flexible for tests and callers.
- `runDiscovery` keeps retry behavior and returns `{ profile, validation, screenshot }`; validation is selector-shape sanity derived from discovered interactive elements.
- Verification complete:
  - `bun test src/lib/discover.test.ts` -> 6 pass
  - `bun run build` -> pass
  - forbidden-term grep count on `src/lib/discover.ts` -> 0
  - LSP diagnostics clean on `src/lib/discover.ts` and `src/lib/discover.test.ts`
- Evidence file: `.sisyphus/evidence/task-12-discover-tests-pass.txt`

## Task 12 retry (concise discover rewrite)
- Kept `discover.ts` intentionally minimal: prompt builder + JSON parser + site-profile typed result shell only.
- Removed all grading/rubric concepts; forbidden term grep should remain part of verification for future edits.
- In this Bun version, test file invocation is reliable with `bun test ./src/lib/discover.test.ts` (explicit relative path).

## Task 16: skills libs port (TDD)
- RED verified with `bun test src/lib/skills-api.test.ts` failing on missing `./skill-parser` before implementation.
- `@tauri-apps/plugin-http` returns a standard `Response` in this workspace, so parse skills API payloads via `await response.text()` / JSON parsing rather than OGRE's older `response.data` access pattern.
- For test-file LSP cleanliness in this repo, import `describe`, `it`, and `expect` from `vitest` explicitly instead of relying only on configured globals.
- Verification complete:
  - `bun test src/lib/skills-api.test.ts` -> 2 pass
  - `bun run build` -> pass
  - LSP diagnostics clean on `skill-parser.ts`, `skills-api.ts`, `skill-creation-prompt.ts`, and `skills-api.test.ts`
- Evidence file: `.sisyphus/evidence/task-16-skills-tests-pass.txt`

## Task 14: confirmation-flow + type-mappers Port (TDD)

- RED verified first with `bun test src/lib/confirmation-flow.test.ts` failing on missing `./confirmation-flow` before implementation.
- `confirmation-flow.ts` ports cleanly as a pure state machine when required keys are passed explicitly instead of deriving grading-mode keys.
- For STEVE, `type-mappers.ts` should map `DiscoveryResult.profile` into `SiteProfile` by normalizing missing interactive collections and recomputing `summary` counts from site-profiler structures.
- `profileToDiscoveryResult()` can safely return `Partial<DiscoveryResult>` with `confidence: 'high'` and the persisted profile sections needed for refinement.
- Verification achieved on task files:
  - `bun test src/lib/confirmation-flow.test.ts src/lib/type-mappers.test.ts` -> 10 pass
  - LSP diagnostics clean on both implementation files and both test files
- Workspace build is currently blocked by an unrelated `Settings.svelte` -> `ProviderSettings.svelte` resolution failure, so task evidence should record partial verification honestly.
- Evidence file: `.sisyphus/evidence/task-14-confirmation-tests-pass.txt`

## Task 18: src-tauri backend scaffold (OGRE-adapted, no sidecar)
- Tauri capability permission for process plugin in v2 schema must be `process:allow-exit` (not `process:exit`).
- `tauri::generate_context!()` requires all icon files referenced in tauri.conf.json to exist at build time; placeholder `.gitkeep` alone is insufficient.
- For STEVE backend parity, keep OGRE embedded-browser IPC stack and Linux GTK/wry split, while removing sidecar runtime, updater plugin/config, and tray server-status/close-to-tray behavior.
- Database migrations kept only STEVE scope tables: `provider_configs`, `app_settings` (`setup_complete=false`), `oauth_tokens`, `skills`, `site_profiles`; DB URL set to `sqlite:steve.db`.


## Task 18: src-tauri backend scaffold (OGRE-adapted, no sidecar)
- Tauri capability permission for process plugin in v2 schema must be  (not ).
-  requires all icon files referenced in tauri.conf.json to exist at build time; placeholder  alone is insufficient.
- For STEVE backend parity, keep OGRE embedded-browser IPC stack and Linux GTK/wry split, while removing sidecar runtime, updater plugin/config, and tray server-status/close-to-tray behavior.
- Database migrations kept only STEVE scope tables: ,  (), , , ; DB URL set to .

## Grading Removal Audit (Task 32)

**Status**: COMPLETE - No issues found

**Audit Results**:
- ✓ Zero grading-api imports in src/
- ✓ Zero rubric-api imports in src/
- ✓ Zero batch-grader imports in src/
- ✓ Zero O.G.R.E branding in user-visible text
- ✓ Rust files (src-tauri/) clean of grading/ogre references
- ✓ tauri.conf.json properly branded as "S.T.E.V.E Desktop"

**Key Finding**: The codebase is fully migrated from OGRE to S.T.E.V.E branding. All grading-api dependencies have been successfully removed. The "grading" folder in src/components/ contains legitimate UI components (DiscoveryProgress, DiscoveryPanel, etc.) for video discovery, not grading functionality.

**Verification**: Audit report saved to `.sisyphus/evidence/task-32-grading-audit.txt`

## Task 31: integration build wiring
- Wiring previously placeholder pages in `App.svelte` can expose pre-existing import/export drift in dormant screens; full build validation is mandatory after route activation.
- `ActionPanel` can safely render `AgentChat` without pulling in unfinished discovery orchestrator paths; this keeps Browser tabs functional while avoiding known broken discovery exports.
- For this workspace state, missing exported helpers in legacy files were resolved with compatibility exports (`sendAgentMessage`, `syncLocalSkills`) so existing UI modules compile without changing user-facing task scope.

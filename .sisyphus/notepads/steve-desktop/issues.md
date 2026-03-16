# Issues — steve-desktop

## Known Issues
- No git repo initialized yet — commits happen after git init in Task 1
- discover.ts has RubricCriterion imports that MUST be removed (Task 12)
- OGRE OAuth client IDs cannot be reused — Task 9 uses placeholders with TODO comments
- Linux requires wry + GTK (separate code path from Windows/macOS) — see lib.rs

## Gotchas
- Svelte 5 runes require .test.svelte.ts extension for vitest browser mode tests
- vitest node mode works for pure TS lib files; browser mode needed for Svelte components
- mockIPC from @tauri-apps/api/mocks needed for invoke() calls in tests
- Linux webviews stored in LINUX_WEBVIEWS thread-local (GTK !Send constraint)
- CDP connection requires discover_cdp_target Rust command (bypasses CORS)

## Task 8 Gotcha
- `vi.hoisted` is not available in this test runtime path (`bun test`), so use direct top-level mock fns for `@tauri-apps/plugin-sql` instead.

## Task 7 notes
- OGRE browser.ts references CDP-heavy logic and grading helpers; steve-desktop scope only keeps embedded webview management surface.
- Existing autofill.ts implementation file is absent in steve-desktop despite autofill tests; browser.ts now uses internal script generation for injectAutofill to avoid coupling to missing module.

## Task 9 gotcha
- In current `bun test` runtime, `vi.stubGlobal`, `vi.unstubAllGlobals`, and `vi.advanceTimersByTimeAsync` were unavailable; use direct `globalThis.fetch` replacement and `vi.advanceTimersByTime`.

## Task 6 gotcha
- `bun test` in this repo timed out when `connectToUrl()` triggered `Page.enable` without a mocked response path; tests must auto-ack that CDP command in WebSocket mocks to avoid hangs.

## Task 17 gotcha
- The current `steve-desktop` workspace has no `src-tauri/` Rust command implementation yet for `create_dir`, `write_file`, `read_file`, `list_files`, or `delete_file`; the TS module and tests assume those Tauri invoke commands will be provided by the desktop shell layer.

## Task 12 gotcha
- There were two competing `discover.test.ts` variants in workspace history; ensure the task-specific test suite (with `runDiscovery` + prompt assertions) is the one present before doing RED/GREEN verification.

## Task 12 retry issue
- Initial retry hit stale test content mismatch; replacing the file with the task-scoped concise suite resolved import/export drift.

## Task 14 verification blocker
- `bun run build` currently fails outside the ported files with: `Could not resolve "./ProviderSettings.svelte" from "src/pages/settings/Settings.svelte"`.
- The referenced file exists at `src/pages/settings/ProviderSettings.svelte`, so this is an unrelated workspace-level build issue that should be debugged separately from confirmation-flow/type-mappers work.

## Task 18 issues encountered
- Initial cargo check failed because capability used invalid permission string `process:exit`; fixed to `process:allow-exit`.
- Initial cargo check also failed because configured icon files were missing; resolved by adding required icon files under `src-tauri/icons/`.


## Task 18 issues encountered
- Initial cargo check failed because capability used invalid permission string ; fixed to .
- Initial cargo check also failed because configured icon files were missing; resolved by adding required icon files under .

## Task 31 issues encountered
- Enabling `Skills` route surfaced a latent build error: `sendAgentMessage` was imported by `SkillCreator.svelte` but not exported by `src/lib/agent-api.ts`.
- Same route activation surfaced another latent build error: `syncLocalSkills` was imported by `src/pages/Skills.svelte` but not exported by `src/lib/skills-api.ts`.
- Enabling full Discovery panel in ActionPanel surfaced `runDiscovery` export mismatch from `src/lib/discover.ts`; kept Discovery tab placeholder to preserve Browser tab UX without introducing larger discovery refactor outside this task.

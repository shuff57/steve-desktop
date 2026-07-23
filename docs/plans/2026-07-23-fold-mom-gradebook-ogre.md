# Fold mom, gradebook, O.G.R.E into steve-desktop

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Port (a) mom question bank, (b) gradebook playwright skills, (c) O.G.R.E grading-server + SQLite schema into steve-desktop as three self-contained islands. Each is independent, addressable through one stable interface, removable without surgery.

**Architecture:** Island pattern. Three folders under `steve-desktop/steve-desktop/src/integrations/<name>/`. Each island owns its own data + scripts + tests, exposes a single typed entry file (e.g. `integrations/mom/index.ts` exports `MOMIsland` with `browse()`, `loadQuestion()`, `upload()`). The app talks to islands only through that entry; islands never `import` from `src/lib/` outside their own folder. Cross-island communication goes through the main app, not direct calls.

Easiest → hardest sequencing chosen to maximize verified behavior at every step (no big-bang integration).

**Tech Stack:** Tauri 2 + Svelte 5 + Vite (existing). Bun runtime for server. SQLite (better-sqlite3) for O.G.R.E schema. Playwright (existing dependency) for gradebook scripts. Existing `src/lib/cdp-client.ts` for in-app browser.

**Sequence (easiest first):**

1. **Island scaffolding** — three empty islands with a typed `Island` interface, so we don't bikeshed mid-build.
2. **gradebook-island** — rehost the 370-line playwright scripts as a Bun subprocess skill, surfaced through a Skills panel entry. *(Why first: it's the smallest scope, and the shell-out pattern is reusable for O.G.R.E.)*
3. **mom-island (read-only)** — filesystem-based indexer of the question catalog + browser page. *(Why second: pure data, no server logic, validates the island pattern under real UI.)*
4. **mom-island (authoring/upload)** — extend the island to draft new questions and open MyOpenMath via CDP. *(Why before O.G.R.E: tightens the in-app CDP bridge, which O.G.R.E will lean on heavily.)*
5. **ogre-island (schema)** — adopt SQLite schema for site-profiles + rubrics + grading history. No UI yet. *(Why before the server: schema is the contract; server is the implementation.)*
6. **ogre-island (grading-server port)** — adopt providers.js, grading.js, batch loop, SSE streaming into the existing `server/agent-server.ts`. *(Why before profiles UI: profiles are persisted state that the server must read.)*
7. **ogre-island (site-profiles + rubric UI)** — SiteProfiles page rewrite, rubric editor, MyOpenMath sync via CDP. *(Why last: this is where everything meets, and it should land on top of stable primitives.)*

Each numbered phase above is its own plan execution cycle. The skill recommends per-task TDD; this plan keeps that discipline inside each phase but treats each phase as the unit of verification.

---

## Phase 0: Island scaffolding

**Files:**
- Create: `steve-desktop/steve-desktop/src/integrations/README.md`
- Create: `steve-desktop/steve-desktop/src/integrations/_shared/island.ts`
- Create: `steve-desktop/steve-desktop/src/integrations/_shared/island.test.ts`
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/index.ts` (empty stub)
- Create: `steve-desktop/steve-desktop/src/integrations/mom/index.ts` (empty stub)
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/index.ts` (empty stub)

**Step 1: Write the failing test**

```ts
// steve-desktop/src/integrations/_shared/island.test.ts
import { describe, it, expect } from 'vitest';
import { defineIsland } from './island';

describe('defineIsland', () => {
  it('produces an island with id, label, and an enabled() defaulting to true', () => {
    const i = defineIsland({
      id: 'mom', label: 'MOM',
      methods: { browse: async () => [] },
    });
    expect(i.id).toBe('mom');
    expect(i.label).toBe('MOM');
    expect(i.enabled).toBe(true);
  });
});
```

**Step 2: Run the test, confirm failure**

```bash
cd steve-desktop && bunx vitest run src/integrations/_shared/island.test.ts
```
Expected: FAIL (module not found).

**Step 3: Implement minimal type**

```ts
// steve-desktop/src/integrations/_shared/island.ts
export interface Island<M> {
  id: string;
  label: string;
  enabled: boolean;
  methods: M;
}
export function defineIsland<M>(spec: {
  id: string; label: string; enabled?: boolean; methods: M;
}): Island<M> {
  return { id: spec.id, label: spec.label, enabled: spec.enabled ?? true, methods: spec.methods };
}
```

**Step 4: Run the test, confirm pass.**

**Step 5: Create three empty island stubs.** Each stub is a `defineIsland` call with `methods: {} as {}` and a placeholder `browse()` no-op.

**Step 6: Write `integrations/README.md` documenting the rule: islands never import from outside `src/integrations/<their-folder>/`. Cross-island calls go through the main app.**

**Step 7: Commit.**

```bash
git add src/integrations/
git commit -m "feat(integrations): island scaffold with shared defineIsland type"
```

---

## Phase 1: gradebook-island (shell-out skill)

**Goal:** Rehost the four playwright scripts (config.mjs, lib.mjs, floor-scores.mjs, scrape-qids.mjs) as a Bun-spawned skill, wired to the in-app Skills panel. The scripts run in their own Playwright context against a separate Chrome instance (unchanged behavior); the app captures stdout + output CSVs and surfaces results.

**Files (in worktree `integration/gradebook-island`):**
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/args.ts` — typed wrapper for CLI flag serialization. *(Built: ✅ committed 3fd7ef6)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/args.test.ts` *(Built: ✅)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/runner.ts` — Bun/Node subprocess wrapper with injectable spawner. *(Built: ✅ committed 3fd7ef6)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/runner.test.ts` — unit tests with fake spawner. *(Built: ✅)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/runner.e2e.test.ts` — real-subprocess e2e (no Chrome needed). *(Built: ✅ committed 2bf28eb)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/config.ts` — resolves `scriptsDir` and `outDir` from caller / env var / default. *(Built: ✅ committed 2bf28eb)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/config.test.ts` *(Built: ✅)*
- Create: `steve-desktop/steve-desktop/src/integrations/gradebook/README.md` — usage docs. *(Built: ✅ committed 2bf28eb)*
- Modify: `steve-desktop/steve-desktop/src/integrations/gradebook/index.ts` — wire the methods. *(Built: ✅ committed 2bf28eb)*
- Modify: `steve-desktop/steve-desktop/src/integrations/gradebook/island.test.ts` — assert new methods. *(Built: ✅)*

**Architectural change from the original plan:** the four playwright scripts **do NOT move into the steve-desktop repo**. They use `playwright` to drive a separate Chrome process (the user's logged-in MyOpenMath session). Adding `playwright` to the Tauri app's `package.json` would bloat the bundle for no benefit. The island locates the scripts at runtime via `config.ts` (caller override → `GRADEBOOK_SCRIPTS_DIR` env var → default `./scripts/`). User must point the app at their existing `gradebook/playwright-grading/` install (e.g. `C:/Users/shuff/Documents/GitHub/gradebook/playwright-grading`).

**Skills.svelte wiring is DEFERRED.** The existing Skills page is for markdown-imported skills stored in SQLite; the floor-grader needs CID/AID inputs the current schema doesn't carry. UI work belongs in a future "Gradebook" page (or extending the skill schema). The island's methods are callable from anywhere in the app — when the UI is needed, it can call them.

**Step 1: Failing test for runner argv parsing**

```ts
// src/integrations/gradebook/runner.test.ts
import { describe, it, expect } from 'vitest';
import { buildFloorArgs } from './runner';
describe('buildFloorArgs', () => {
  it('serializes opts into --flag=value CLI args', () => {
    expect(buildFloorArgs({ cid: 306621, aid: 22202268, label: 'unit1' }))
      .toEqual(['--cid=306621', '--aid=22202268', '--label=unit1']);
  });
  it('emits --write-back only when truthy', () => {
    expect(buildFloorArgs({ cid: 1, aid: 2, writeBack: true })).toContain('--write-back');
    expect(buildFloorArgs({ cid: 1, aid: 2, writeBack: false })).not.toContain('--write-back');
  });
});
```

**Step 2: Implement `buildFloorArgs` in `runner.ts`.** Pure function. No I/O.

**Step 3: Failing test for `runner.spawn` (mock Bun.spawn).** Inject a fake spawner that returns a canned `BunSubprocess`. Assert the runner resolves with `{ stdout, csvPaths: [path1, path2] }` when the script writes two CSVs.

**Step 4: Implement `runner.ts`:** `async function runFloorScores(opts, { spawn = Bun.spawn } = {})`. Uses `spawn(['bun', scriptPath, ...args])`, awaits exit, reads stdout, scans `--out-dir` for `.csv` files modified in the last 10s.

**Step 5: Wire island index.** `index.ts` exports the island with `runFloorScores` and `scrapeQids` as methods. Both return `{ ok, stdout, csvPaths | qids, error? }`.

**Step 6: Add Skills.svelte entries.** Two `<Skill>` cards. Each calls the island method on click, displays stdout in a `<pre>`, lists CSVs as download links. **Dry-run by default** — no `--write-back`. Make `writeBack` a separate "Push scores" button that requires a confirmation modal.

**Step 7: Manual smoke test.** With a real MyOpenMath session in Chrome, run from the app: pick a unit, click "Floor scores (dry-run)". Verify the CSV appears in the configured `--out-dir` and the stdout matches the standalone `node floor-scores.mjs` output for the same args.

**Step 8: Commit.** `feat(gradebook-island): shell-out floor grader + qid scraper as Skills entries`

**Why this is the right cut:** the existing scripts are working, tested against real Chrome, and course-aware. Re-implementing them in TypeScript or against the in-app CDP would be a week of work to reproduce behavior that already exists. Shell-out honors the user's "I just want the skills" answer. If the in-app browser ever needs to drive MyOpenMath directly, that's a later phase (likely O.G.R.E site-profiles).

---

## Phase 2: mom-island (read-only browser)

**Goal:** An in-app page that lists mom questions, previews the PHP template, and shows the family manifest. No upload yet.

**Files:**
- Create: `steve-desktop/steve-desktop/src/integrations/mom/loader.ts` — filesystem-based indexer. Given a `MOM_ROOT` path, walks `questions/<family>/` and emits a `MOMIndex { families: MOMFamily[] }`.
- Create: `steve-desktop/steve-desktop/src/integrations/mom/loader.test.ts` — fixture-driven: a tiny `questions/frq/...` tree under `tests/fixtures/mom/`, assert the loader emits the right counts + slug.
- Create: `steve-desktop/steve-desktop/src/integrations/mom/manifest.ts` — reads `manifest.json` from each FRQ subfolder, exposes `getFrqSetStats(family)`.
- Create: `steve-desktop/steve-desktop/src/integrations/mom/index.ts` — the island surface: `browse()`, `getQuestion(family, slug)`, `getFamily(family)`.
- Create: `steve-desktop/steve-desktop/src/pages/MomBrowser.svelte` — left nav (families), center list (questions), right pane (PHP preview + manifest metadata). Read-only.
- Modify: `steve-desktop/steve-desktop/src/App.svelte` — add route `/mom`.

**Step 1: Decide `MOM_ROOT`.** This is the only config that matters. Read from a Tauri settings file (`tauri.conf.json` or AppData settings.json), defaulting to `~/Documents/GitHub/mom`. Do **not** hardcode the absolute path; that was a bug in gradebook's `config.mjs` DEFAULTS — don't repeat it.

**Step 2: Test fixture.** Create `src/integrations/mom/__tests__/fixtures/mom/questions/frq/descriptive-statistics/q1-test.php` and a `manifest.json` with one entry. The loader test runs against this fixture, not the real `mom/` repo.

**Step 3: Implement `loader.ts`.** Single `async function loadMOMIndex(root: string): Promise<MOMIndex>`. `fs.readdir` recursive, skip `nul`/`$APPDATA`/`C:Users...` junk at the root (the stray Windows artifacts from the earlier review). Emit `{ families: [{ name, count, questions: [{ slug, path, hasManifest }] }] }`.

**Step 4: Test manifest reader.** `getFrqSetStats('descriptive-statistics')` returns the right completed/pending counts from a fixture manifest.

**Step 5: Page.** `MomBrowser.svelte` calls `browse()` on mount, renders the index. Clicking a question calls `getQuestion(family, slug)`, which reads the file and returns raw PHP text. Render in a `<pre>` with monospace font and line numbers. No syntax highlighting (YAGNI — read-only preview only).

**Step 6: Manual smoke test.** Point `MOM_ROOT` at the real `~/Documents/GitHub/mom`, verify the index matches `AGENTS.md`'s family counts (11 families, ~150 questions).

**Step 7: Commit.** `feat(mom-island): read-only question browser with family index + manifest`

---

## Phase 3: mom-island (authoring + upload)

**Goal:** Inside the app, draft a new question from a template, preview it, and (when ready) open MyOpenMath's question editor and paste. Optionally auto-fill answer type.

**Files:**
- Create: `steve-desktop/steve-desktop/src/integrations/mom/templates.ts` — exports a known list of starting templates (one per family), each pointing at a real `mom/questions/{family}/q*-*.php` file to copy.
- Create: `steve-desktop/steve-desktop/src/integrations/mom/draft.ts` — `createDraft(family)`: copy a template to a working file under Tauri app-data, return the path + the original template's `$anstypes` and `$questiontext` so the UI can prefill.
- Create: `steve-desktop/steve-desktop/src/integrations/mom/upload.ts` — uses `src/lib/cdp-client.ts` to navigate to MyOpenMath's `modquestion.php`, sets Common Control + Question Text fields by CDP DOM manipulation, leaves submit for human review.
- Create: `src/integrations/mom/draft.test.ts` — pure: given a fixture template, `createDraft('frq', 'descriptive-statistics')` returns a path containing the slug.
- Create: `src/integrations/mom/upload.test.ts` — mock `CDPClient`, verify the right `Runtime.evaluate` calls happen in the right order.
- Modify: `src/pages/MomBrowser.svelte` — add a "New question" button on each family. New page: `MomDraft.svelte` with a textarea (Common Control + Question Text combined, split on `///`), "Open in MOM" button.

**Step 1: Failing test for `createDraft`.** Assert the draft file exists after the call, and its contents are a copy of the template (no `mom-section-to-questions` skill needs to run yet — this is just file plumbing).

**Step 2: Implement `createDraft`.** Use Tauri `app_data_dir` for the draft path. Don't write to `mom/` directly — drafts live outside the source repo until the user promotes them.

**Step 3: Failing test for `upload`.** Mock CDP, expect: connect, navigate to `https://www.myopenmath.com/modquestion.php?cid=...`, eval `document.querySelector('#qtext').value = ...`, eval `#controls.value = ...`, then stop (no submit).

**Step 4: Implement `upload`.** Reuses `src/lib/cdp-client.ts`. The CDP client is already wired to the in-app embedded browser. The MyOpenMath tab must already be logged in (the user opens it in the app as they would today).

**Step 5: Confirm with user before any submit.** The button reads "Open in MOM (paste, do not submit)". A separate "Push to MOM" button — gated behind a confirmation modal — would be the next step but is **not** in this phase.

**Step 6: Manual smoke test.** Pick a family → "New question" → modify the Common Control → "Open in MOM" → verify the MyOpenMath editor has the right Common Control and Question Text in their fields, untouched, awaiting human review.

**Step 7: Commit.** `feat(mom-island): draft + open-in-MOM flow via CDP`

---

## Phase 4: ogre-island (SQLite schema)

**Goal:** Adopt the O.G.R.E SQLite schema (rubrics, grading_history, site_profiles, embeddings) inside the app's existing SQLite database. No UI yet — just the migrations and a typed accessor layer.

**Files:**
- Read first: `O.G.R.E-OllamaGradingRubricEvaluator/ogre-desktop/electron-main/database.ts` (canonical schema). Also check `grading-server/` for any tables defined there.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/migrations/001-ogre-schema.sql` — ported from the O.G.R.E repo verbatim, with one added `island_id` column per table to keep the schema namespaced.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/db.ts` — `openOgreDb()`: opens `~/.steve/ogre.db`, runs migrations on first load.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/types.ts` — TypeScript types matching the schema.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/db.test.ts` — uses an in-memory SQLite (`better-sqlite3` `:memory:`), runs migrations, asserts table existence + a round-trip insert/select.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/index.ts` — empty island surface for now; just declares the island.

**Step 1: Test the migration file with a fresh DB.** No real DB is touched in tests. Migration runs in `:memory:` and `sqlite_master` is queried for the expected tables.

**Step 2: Implement `db.ts`.** Single `openOgreDb()` function. Singleton. Cached by path. Migrations are idempotent (use `IF NOT EXISTS`).

**Step 3: Add `island_id` to every table.** Even though only one island uses these tables today, namespacing now means adding another grading-aware island later doesn't require a migration. Default value `'ogre'`.

**Step 4: Round-trip tests.** Insert a `site_profiles` row, select it back, assert every field survived. Same for `rubrics` and `grading_history`.

**Step 5: Commit.** `feat(ogre-island): adopt SQLite schema for site_profiles, rubrics, history`

**Note on schema fidelity:** if O.G.R.E's schema has moved since I last read it, this is where you reconcile. Read `O.G.R.E-OllamaGradingRubricEvaluator/ogre-desktop/electron-main/database.ts` at the start of this phase, port 1:1, then layer the `island_id` column.

---

## Phase 5: ogre-island (grading-server port)

**Goal:** Move the O.G.R.E grading-server's prompt construction, batch grading loop, and provider routing into steve-desktop's existing Bun server. SSE streaming preserved. No new HTTP routes added to the existing app — the island exposes a typed interface that the app calls in-process; the server-side work happens via function calls, not HTTP.

**Files:**
- Read first:
  - `O.G.R.E-OllamaGradingRubricEvaluator/grading-server/grading.js` (batch loop, prompt construction)
  - `O.G.R.E-OllamaGradingRubricEvaluator/grading-server/providers.js` (Ollama, Anthropic, OpenAI, Gemini, GitHub Models, RunPod)
  - `O.G.R.E-OllamaGradingRubricEvaluator/grading-server/server.js` (SSE pattern)
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/providers/index.ts` — ported `providers.js` as TypeScript modules. One file per provider.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/grading.ts` — ported `grading.js`, types-first.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/grading.test.ts` — uses a fake provider (no Ollama needed) to test the batch loop end-to-end on a 3-student fixture.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/streaming.ts` — typed `AsyncIterable<GradingEvent>` replacing the SSE HTTP layer.
- Modify: `steve-desktop/steve-desktop/src/integrations/ogre/index.ts` — expose `gradeBatch(input: GradeRequest): AsyncIterable<GradingEvent>`.
- Modify: `steve-desktop/steve-desktop/server/agent-server.ts` — wire the island's `gradeBatch` into a new HTTP route, keeping the SSE pattern.

**Step 1: Decide the seam.** The cleanest cut is "providers and grading are pure functions; streaming is the I/O." `grading.ts` is the pure part. `streaming.ts` wraps it for HTTP. Tests run against the pure part only — no Bun server, no Ollama, no network.

**Step 2: Failing test for a single provider.** Mock provider returns canned responses; the grading function takes a 3-student input, fans out (sequentially or concurrently — match O.G.R.E's existing behavior), emits one `GradingEvent` per student.

**Step 3: Port providers one at a time.** Start with Ollama (it's the most-used and the simplest). Add others as needed. Each provider has its own file with a uniform `Provider` interface: `{ name, grade(prompt, opts): Promise<GradeResponse> }`.

**Step 4: Port `grading.js`.** This is the load-bearing file. The prompt construction, the "grade all students in one AI context" insight (the README's main selling point), and the outlier detection logic must all survive the port. Read the original, write equivalent TypeScript, no clever refactors.

**Step 5: Test the batch loop with a fake provider.** 3 students, fake provider returns `8/10`, `7/10`, `9/10`. Assert: 3 events emitted, total = 24, ordering matches input order.

**Step 6: Wire into `agent-server.ts`.** New route `POST /ogre/grade` accepts the same payload as the old `grading-server`, returns SSE. **No behavior change for existing routes.**

**Step 7: Manual smoke test.** Reuse O.G.R.E's existing test fixture (3 students, 1 rubric) and confirm scores match what the old server would have produced.

**Step 8: Commit.** `feat(ogre-island): adopt grading-server providers + batch loop + streaming`

**Critical:** this phase is a port, not a rewrite. The README is explicit that "grade all students in a single AI context" is what produces consistent scores — do not break that. Read the original, copy the prompt template byte-for-byte, then translate to TypeScript.

---

## Phase 6: ogre-island (site-profiles + rubric UI)

**Goal:** Rewrite `SiteProfiles.svelte` against the O.G.R.E schema, add a Rubric editor page, wire MyOpenMath sync via CDP. This is where everything meets.

**Files:**
- Modify: `steve-desktop/steve-desktop/src/pages/SiteProfiles.svelte` — replace the current draft implementation with the O.G.R.E schema. Keep the existing "discover a profile from a URL" flow (it's working per your memory).
- Create: `steve-desktop/steve-desktop/src/pages/Rubrics.svelte` — list, create, edit, delete. Rubric body is JSON; schema lives in `ogre/types.ts`.
- Create: `steve-desktop/steve-desktop/src/pages/GradingPanel.svelte` — picks a site profile + rubric, loads student responses via the island, runs `gradeBatch()`, streams results back. **Review-before-submit** is enforced: results are shown in a table, no auto-write to the grading page.
- Modify: `steve-desktop/steve-desktop/src/integrations/ogre/load-students.ts` — given a site profile, scrape student names + response text from the current page via CDP.
- Create: `steve-desktop/steve-desktop/src/integrations/ogre/load-students.test.ts` — mock CDP, verify the right queries fire for a MyOpenMath profile.

**Step 1: Audit the existing `SiteProfiles.svelte`.** Per your memory, "Site Profiles Update plan" is pending a one-click verify→diff→heal. The current `site-profiles.ts` is real. The new version must keep all existing behavior (the verify→diff→heal flow) while moving storage to the O.G.R.E schema.

**Step 2: Failing test for `load-students`.** Mock CDP, pass a MyOpenMath profile, assert the eval calls include selectors for `#studentnames` and `textarea[name*='stuanswers']` (real O.G.R.E selectors — verify against a live page before locking in).

**Step 3: Implement `load-students.ts`.** Reads the profile's selectors, runs them via CDP `Runtime.evaluate`, returns `{ name, responseText }[]`.

**Step 4: `GradingPanel.svelte` UX.** Source of truth: existing O.G.R.E UX, ported to Svelte 5. Selectors:
- Site profile (required)
- Rubric (required)
- Cap fraction (from `gradebook/config.mjs` DEFAULTS — consider exposing)
- Write-back target: "Review only" / "Push to page" (the latter is gated behind a confirm modal)

**Step 5: Wire `gradeBatch` → `load-students` → score preview → optional write-back.** SSE event handler renders rows as they arrive. Confirmation modal must show the exact `student.name, score, feedback` triples that will be written. **Never write without confirmation.**

**Step 6: Manual smoke test against the live MyOpenMath Math12-Sp26 course** (the same one gradebook/scripts already touches). Pull 3 students, run with a real rubric + Ollama, confirm scores look sane, do NOT click "Push to page" in the smoke test.

**Step 7: Commit.** `feat(ogre-island): site-profiles + rubric editor + grading panel`

**This is the highest-risk phase.** It moves real data on a live gradebook. All of O.G.R.E's safety rules apply — "never overwrite non-zero scores without explicit approval" is in your `AGENTS.md`. Honor that. The "Review only" path must be the default and the only path that works without a confirm modal.

---

## Cross-cutting requirements (apply to every phase)

- **TDD discipline.** Failing test → pass → commit. Per the writing-plans skill, no exceptions.
- **Frequent commits.** One commit per task minimum. Subject line under 50 chars. `feat(<island>):` or `fix(<island>):` prefix.
- **No cross-island imports.** A grep for `from '.*integrations/(?!<this-island>)` must be empty after every phase.
- **No new dependencies unless the existing toolchain fails.** Bun + Vite + Svelte 5 + better-sqlite3 + playwright + the existing cdp-client should cover everything.
- **Real data guardrails.** gradebook is FERPA-scoped (per your memory). Never log student names, never write to `mom/`, never push to a real gradebook page without confirm.
- **AGENTS.md updates per phase.** Each phase that adds a non-trivial skill surface should append one line to `steve-desktop/AGENTS.md` under "Integrations."

## Worktree layout (set up 2026-07-23)

Each island lands in its own worktree, branched from `ai-site-mapping` at f800405. The plan's phase 0 (scaffolding) will land on `ai-site-mapping` first; phase 1+ in the per-island worktrees. Worktrees:

| Worktree path | Branch | Plan phases |
|---|---|---|
| `steve-desktop/.worktrees/gradebook-island/` | `integration/gradebook-island` | Phase 1 |
| `steve-desktop/.worktrees/mom-island/` | `integration/mom-island` | Phases 2 + 3 |
| `steve-desktop/.worktrees/ogre-island/` | `integration/ogre-island` | Phases 4 + 5 + 6 |

`.worktrees/` is gitignored at the outer repo root. Each worktree has its own `node_modules` (gitignored). Baseline test: 51 files, 482 tests, 0 failures in all three.

Merge order: gradebook → mom → ogre. Each PR reviewed independently.

## What is NOT in this plan

- **OGRE Cloud** (Google login + JWT + worker). Out of scope for the initial fold. The island boundary keeps the door open.
- **O.G.R.E Electron desktop shell.** Tauri stays. The desktop rebuild is steve-desktop itself.
- **Re-architecting the gradebook scripts to use the in-app CDP.** Shell-out is the lazy-correct cut. Revisit if a second consumer of the in-app browser wants the same operations.
- **Tightening the O.G.R.E schema** (renaming columns, splitting tables). Port 1:1, add `island_id` for namespacing, move on.
- **OGRE Cloud → steve-desktop auth migration.** `Auth model = CLI login` per your memory. O.G.R.E Cloud's Google login doesn't fit that model. Skip the cloud worker entirely.

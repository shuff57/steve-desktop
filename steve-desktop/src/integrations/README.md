# Integrations

Three integrations live under this folder. Each is an **island** — a self-contained module with its own data, scripts, and tests, exposed to the rest of the app through a single typed entry point.

## The islands

| Folder | Source project | Purpose |
|---|---|---|
| `gradebook/` | `gradebook/playwright-grading` | Floor-grader and qid-scraper skills, rehosted as Bun subprocesses |
| `mom/` | `mom/` (MyOpenMath question bank) | Read-only question browser + authoring/upload flow |
| `ogre/` | `O.G.R.E-OllamaGradingRubricEvaluator` | Grading server (providers, batch loop, streaming) + SQLite schema (site-profiles, rubrics, history) |

## The contract

Every island exports a named `xxxIsland` constant via `defineIsland` from `_shared/island.ts`. That constant is the **only** way the rest of the app talks to the island.

### Rules

1. **No cross-island imports.** A file under `integrations/<this-island>/` must never `import` from `integrations/<other-island>/` or from any path outside its own folder. Cross-island communication goes through the main app, not direct calls.
2. **No imports from `src/lib/`.** Islands own their own helpers. If two islands need the same helper, copy it (DRY is not worth the coupling for island code) or pull it up to `_shared/` — but only when a second island actually needs it.
3. **One entry file.** `index.ts` exports the island surface. The app imports only from there, never from internal modules.
4. **Island owns its data and config.** No `~/.steve/config.yaml` for island paths. Each island reads its config from Tauri settings, with safe defaults that don't hardcode absolute paths.

### Verifying the boundary

```bash
# Should return zero results after every commit:
grep -rE "from '.*integrations/(gradebook|mom|ogre)/" src/ \
  --exclude-dir=node_modules \
  --exclude-dir=.worktrees \
  | grep -v "from '\.\./" | grep -v "from './"
```

That grep matches imports from a *different* island's folder. Self-imports (`./` and `../`) are fine; cross-island imports are not.

## Adding a new island

1. Create `integrations/<name>/index.ts` that calls `defineIsland` with an empty `methods: {} as <Name>Methods`.
2. Define the `<Name>Methods` interface inline, with a comment block describing what each method will do.
3. Add a sibling `island.test.ts` asserting `id`, `label`, `enabled`.
4. Add a row to the table above.
5. Open a new worktree branched from `ai-site-mapping` (or `desktop` if that's the current trunk) before any non-trivial code lands.

## Worktrees (set up 2026-07-23)

| Island | Worktree | Branch |
|---|---|---|
| gradebook | `steve-desktop/.worktrees/gradebook-island/` | `integration/gradebook-island` |
| mom | `steve-desktop/.worktrees/mom-island/` | `integration/mom-island` |
| ogre | `steve-desktop/.worktrees/ogre-island/` | `integration/ogre-island` |

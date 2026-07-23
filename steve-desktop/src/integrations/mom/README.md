# mom-island

MyOpenMath question bank browser + authoring/upload. Lives in the app under
the **MOM** sidebar entry.

## What it does

- **Read-only browser** (`MomBrowser.svelte`): three-pane view of the user's
  `mom/` repo. Left: families. Center: questions. Right: PHP preview + manifest
  stats (`manifest.json` per question folder).
- **Authoring + upload** (`MomDraft.svelte`, modal): pick a template + slug,
  write the question body in one textarea (Common Control on the first line,
  `///` separator, then question text), push the contents into MyOpenMath's
  `modquestion.php` editor via CDP. **Paste-only** — the user must click submit
  in the live form. There is no auto-submit path.

## Files

| File | Purpose |
|---|---|
| `loader.ts` | Walk `<MOM_ROOT>/questions/<family>/<slug>/` and emit `MOMIndex`. Skips Windows junk (`nul`, `$APPDATA`, `C:Users*`). |
| `manifest.ts` | Read `manifest.json` and aggregate completed/pending counts. |
| `templates.ts` | Hardcoded list of starter templates (5 entries, one per family). |
| `draft.ts` | `createDraft(family, opts)` — copy a template into the drafts dir. |
| `upload.ts` | `uploadToMOM({ cdpClient, cid, controls, questionText })` — connect, navigate, fill `#qtext` and `#controls`, stop. |
| `index.ts` | Island surface (`momIsland`). Re-exports the public types. |
| `island.test.ts` | Asserts the island exposes the right id/label and methods. |
| `methods.test.ts` | End-to-end `browse / getQuestion / getFamily` against the fixture. |
| `loader.test.ts`, `manifest.test.ts`, `templates.test.ts`, `draft.test.ts`, `upload.test.ts` | Per-module tests, fixture-driven. |
| `__tests__/fixtures/mom/` | A tiny FRQ family with one `.php` and a `manifest.json` so the tests don't touch the real repo. |

## Configuration

Two Tauri settings, both user-supplied (no hardcoded absolute paths):

| Setting | Purpose |
|---|---|
| `mom_root` | Path to the user's `mom/` repo. Used by the read-only browser. |
| `mom_drafts_dir` | Path to a working dir for new-question drafts. Lives outside the source repo. |

Both are set via small forms in the MOM page itself — no native file dialog
dependency was added (the rest of the app doesn't use `@tauri-apps/plugin-dialog`).

## Island surface (`MomMethods`)

```ts
interface MomMethods {
  browse(root: string): Promise<MOMIndex>;
  getQuestion(family: string, slug: string, root: string): Promise<MomQuestionDetail>;
  getFamily(family: string, root: string): Promise<MomFamilyDetail>;
  createDraft(family: string, opts: CreateDraftOpts): Promise<DraftResult>;
  upload(opts: UploadOpts): Promise<boolean>;
}
```

`browse` / `getQuestion` / `getFamily` are pure filesystem reads. `createDraft`
copies a template out of the source repo into the drafts dir. `upload` drives
the in-app browser via the existing `src/lib/cdp-client.ts` singleton (a
`cdpClient` override is accepted for tests; the production code path uses the
shared one).

## Safety boundary

`upload` never clicks submit. The MyOpenMath editor is left in a state where
the human can review the Common Control and Question Text, then click submit
themselves. This matches the plan's "paste-only" rule and avoids the
"automation mutated a course" failure mode the MyOpenMath audit log was built
to surface.

## What's deferred (not in this PR)

- **No writeback from the textarea into the draft `.php` file.** The textarea
  is a staging area for the MOM paste; the file on disk is the canonical
  artifact, edited by the user in their editor of choice. When the user
  reports a need for a built-in PHP editor in the app, that becomes phase 3.5+.
- **No "Push to MOM" / auto-submit button.** Gated behind a confirmation
  modal in the plan; not built here.
- **Per-family template expansion.** Templates are hardcoded at 5 entries.
  When the user reports missing families, append to `templates.ts`.
- **No `ag-` text editor integration.** Editing the Common Control beyond
  the textarea (rich text, variables) is a future phase.

## Verified

- `bunx vitest run src/integrations/mom/` — 30 tests pass.
- `bunx vitest run` — 518 tests pass (489 baseline + 29 new).
- `bunx svelte-check` — 0 errors.
- `src/integrations/_shared/boundary.test.ts` — no cross-island imports.

## Known follow-ups

- Verify the `#qtext` / `#controls` selectors against the live MyOpenMath
  form before the next revision. The current selectors are the ones MOM has
  used for years; the build is gated on a visual check, not a fixture.
- The "Open in MOM" path assumes the embedded browser tab is already
  logged in to MyOpenMath. The plan says the user opens it as they would
  today — no automatic login.
- The `body` textarea's `///` separator is a placeholder. If the real
  MyOpenMath Common Control grows multi-line, the parser in `parseBody`
  may need to handle the change.

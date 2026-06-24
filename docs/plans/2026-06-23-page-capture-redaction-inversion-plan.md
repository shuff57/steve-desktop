# Page Capture + Redaction Inversion — Plan

**Date:** 2026-06-23
**Status:** Approved, implementing
**Source:** multi-agent research (`page-capture-research`) + two adversarial reviews

## TL;DR

Adopt the field-standard page representation — a **DOM-primary tree enriched with the
accessibility tree** (browser-use's `EnhancedDOMTreeNode` recipe) over our raw CDP, with a
**cache-then-self-heal** replay loop (Stagehand's idea) — *but* fix two things the
adversarial reviews proved before any of that:

1. **Redaction is value-based, not field-based.** `redact.ts` swaps *pre-known* strings.
   The richer representation (computed accessible names, nearby text) carries PII that was
   never a flagged field value → it would leak. **Invert it:** redact by **slot, deny-by-default**.
2. **The capture change is a rewrite, not "additive."** `dom-snapshot.ts` captures via
   injected JS with no `backendNodeId`; there are two unreconciled representations
   (`SiteProfile` candidates vs `replay.ts`'s `SnapshotResult`); `backendNodeId` is not
   unique across iframes; the action layer can only run CSS.

## What we reject (and why)

- **Pure AX snapshot** (Playwright `ariaSnapshot`, chrome-devtools-mcp): hands back
  ephemeral refs (`e5`) that die on reload → not persistable; blank on weak-ARIA SIS pages.
- **Pixels / Set-of-Mark / computer-use** (OmniParser, Skyvern, CUA): PII lives in the
  screenshot (no deterministic redaction) and coordinates don't persist. Vision = local
  self-heal hint only, never the model-bound representation.
- **Depending on browser-use / Stagehand / Playwright as runtimes**: wrong runtime
  (Python / Browserbase-cloud / second browser). Steal the ideas, keep our CDP + redactor.

## Build order

### Phase 0 — Redaction inversion (this PR, safety-critical, TDD first)
Redact by **slot, deny-by-default**, on the structured tree, *before* serialization:
- A node's content is **kept** only if it is chrome (interactive control or label/heading):
  `button, a, h1–h6, th, label, legend, summary, caption`, or role `button|link|heading|tab|menuitem`.
- Everything else carrying text or a `value` is a **data slot** → tokenized **regardless of
  contents** (`⟦D1⟧…`), so unknown PII (computed accessible names, free-text notes, name
  variants, reformatted IDs) cannot leak by being "not in the dictionary."
- The values discovered by slot-tokenization seed a **second-pass value-dictionary swap**
  (existing `Redactor`) over the free-text fields (`dom`, messages) — defense in depth.
- Rehydration restores tokens locally; the model never sees raw data-slot content.
- **Adversarial tests are the deliverable**: PII in a `<td>` not in any list; PII in a
  computed `aria-label`; PII in a free-text `<p>`; formatting variants; same value in two
  slots. These fail today's value-dictionary; they must pass slot redaction.

Honest residual: PII inside a *chrome* node (e.g. heading "Jane Doe's Profile") is only
caught by the known-value second pass. Documented limit, not silently ignored.

### Phase 1 — CDP-native capture (replaces injected-JS capture)
- `DOM.getDocument(pierce=true)` ⨝ `Accessibility.getFullAXTree`, merged **keyed by
  `(frameId, backendNodeId)`** (not `backendNodeId` alone — not unique across iframes;
  SafeColleges runs content in iframes). Walk frames explicitly; pass `frameId` to
  `getFullAXTree` (Chrome 148+). AX tree builds lazily — prime it.
- **Unify the two representations**: one merged tree feeding both discovery (`SiteProfile`
  `SelectorCandidate`/`ElementContext`, which already exist) and replay.
- Runnable check: 2-iframe fixture, assert no node from frame A merges onto frame B.
- **UI-wired (2026-06-24):** ActionPanel → Discovery tab → `SiteMapper.svelte` triggers a live capture on the current page.

### Phase 2 — Persisted intent + ranked selectors
Per element: `intent{role, accessibleName, nearbyText}` (redacted before any model call),
ranked `selectors[role→testid→id→css→xpath]`, `fingerprint{ancestorPath, subtreeSig}`.
**Extend the action layer** (`cdp-actions.ts`) to resolve `role:` (AX query) and `xpath:`
(`document.evaluate`) — do not persist selector kinds the runtime can't replay.

### Phase 3 — Self-heal chain (reconcile `replay.ts`)  ✅ implemented + unit-tested
1. stored selectors top-down `[local, 0 cost]` →
2. deterministic structural re-match (LCS on `fingerprint`, anchored on id/role/landmark not
   raw position) `[local, 0 cost]` →
3. escalate to model with **redacted** structure + position `[cloud, last resort]` →
   rewrite cached selector on success. Reuse the existing token-match heal as Tier 2 unless
   measured to lose.

**Status (2026-06-24):** Tier 0 (recorded) + Tier 1 (stored candidates) + Tier 2 (local fuzzy
re-derivation) + **Tier 3** all live in `replay.ts`. Tier 3 = `modelRelocator(transport)`: slot-
redacts the fresh snapshot (`redactTree`), escalates through `callModelTree` (refuses on leak,
rehydrates locally), parses the selector (`parseRelocateReply`, rejects token echoes), validates
it exists, acts, and **rewrites `step.selector` in place** so the next replay hits the happy path.
Healer is injected (`replayWorkflow(wf, page, heal?)`) — transport/model failures fall through to
a skip, never throw. Tests: tier-3 recover + cache-rewrite, skip-on-ghost-selector, skip-on-
transport-failure, and the trust-boundary test (PII crosses as `⟦D1⟧`, never raw).

**Live wiring done (`replay-live.ts`):** `replayLive(workflow)` drives the real embedded browser
over CDP (`BrowserPageDriver`: `captureMergedTree` for snapshot, `selector-resolve` + `pwClick`/
`pwType` for exists/act) and passes `modelRelocator(sidecarTransport())` as the Tier-3 healer —
escalation posts the redacted relocate prompt to the model sidecar (`:3456/api/agent`). Requires a
CDP connection (throws otherwise). `PageDriver` is now async-tolerant (`Awaitable<T>`); sync mocks
and the demo `DemoPage` still satisfy it. Tested: sidecar transport, action mapping, no-CDP guard,
happy-path drive-through, and a full Tier-3 escalation→relocate→act.
**UI wiring done:** `SkillCard` shows a **▶ Run** button for any skill with a recorded steps
block; `Skills.svelte#handleRun` parses it (`skillToWorkflow`), connects CDP on demand
(`connectCDP` — the WebView2 already launches with `--remote-debugging-port`, nothing called it
before), invokes `replayLive`, and reports `N done / N self-healed / N skipped`. This is the first
live caller of the CDP path in the app. **Verify in GUI:** open the target page in the embedded
browser, then hit ▶ on a captured skill.

### Phase 4 — Measure, don't assert
New merged capture vs today's hybrid on 3–5 real SafeColleges/Vector pages: report AX-name
coverage and heal success rate. Only then is "better than the ad-hoc hybrid" defensible.
- **UI-wired (2026-06-24):** `SiteMapper.svelte` shows live coverage stats (frames, role %,
  interactive role-name %) and the redacted snapshot for in-app eyeballing; `server/measure-capture.ts` remains the headless variant.

## Honest conflicts (carried, not hidden)
- The heal anchor *is* the PII field. Tiers 1–2 heal locally on raw data; Tier 3 sends only
  redacted structure, so it relocates by role + position, weaker than "the Jane Doe row."
  Accept occasional Tier-3 failure on PII-distinguished elements; surface to operator, never leak.
- role+name degrades to CSS exactly on weak-ARIA SIS pages — budget for Tier 2/3 firing often.

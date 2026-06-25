# Site-Mapping Goal Prompt — Design

**Date:** 2026-06-25
**Status:** approved (brainstorm), validation pending

## Purpose

One reusable **goal prompt** that, per site, logs in → maps the right way →
verifies the map resolves live → asserts no PII survived → reports. Run by Claude
Code as operator, using **browser-harness to explore** and the **steve-desktop
app to validate** (the actual `redact-tree` + `site-map` code path).

## Engine

- **browser-harness (Python/CDP)** — log in, scout, drive the live sites.
- **steve-desktop app** — run the real `SiteMapper` workflow, produce the
  persisted `SiteProfile` JSON the rest of the product consumes.

## The template

```
Map and validate {{SITE_NAME}} for the steve-desktop automation layer.

LOGIN
- Site is already logged in (creds saved in the app). Confirm authenticated
  home (screenshot + page_info). If bounced to SSO/2FA, STOP and ask.

MAP  (mode = {{MODE}})
- page       → capture this one page.
- site       → BFS crawl, same-origin, skip logout/submit/role-switch links.
- generalize → map ONE representative instance ({{INSTANCE_NOTE}}); then open a
               SECOND instance and assert the same selectors resolve. Record only
               class-agnostic selectors, not per-instance URLs/IDs.

VERIFY
- Re-resolve every captured selector live; assert each still matches an element.
- Run site checks: {{VERIFY_CHECKS}}.
- On a broken selector: re-capture that page, diff, fix the map, repeat to green.

PII GATE
- {{PII_RULE}}  (default: run scripts/verify_no_pii.py over the saved profile
  JSON; FAIL the run if any name/email/phone/ID/DOB survived redaction.)

REPORT
- Per page: pass/fail, selector count, PII verdict. List trim suggestions.
- Save nothing containing student data outside the redacted profile JSON.
```

`generalize` is **operator behavior, not new app code**: map one instance, then
re-point the captured selectors at a second instance and confirm they resolve.

## Per-site fillings

### A. Keenan SafeSchool (`*.safecolleges.com`) — `site`
- VERIFY: dashboard → "My Assignments" → course player opens; confirm the
  `steve` skill's anchors (play, knowledge-check, final-assessment) still resolve.
- PII: standard gate — your name appears in chrome; assert it tokenizes to `⟦D…⟧`.
- Reuses the **`steve`** skill for course-taking. The map only proves the nav
  skeleton + steve's anchor selectors are current.

### B. MyOpenMath — `generalize` + authoring loop
- INSTANCE_NOTE: map one course's gradebook/question-editor; re-prove on a second
  course (cid differs, selectors must not).
- VERIFY: open question editor (`addquestions2.php` / `moddataset.php`), open
  `testquestion2.php` preview, locate Common Control / Question Text / Answer
  fields by stable selector.
- PII: standard gate — rosters are PII; assert student names tokenized.
- **Authoring docs are a pointer, not a copy.** The MyOpenMath site map carries
  one line: `authoring_docs: ../mom/reference/index.json`. The deep dive into
  IMathAS syntax/macros stays in the `mom/` repo, loaded lazily by the authoring
  skill — NOT mapped as pages.

Authoring loop (reuses the existing `mom/` stack — `/mom-section-to-questions`):
```
1. DOCS   read mom/reference/index.json → relevant macro/question-type files
2. DRAFT  write IMathAS file in questions/{family}/ (mom AGENTS.md conventions)
3. UPLOAD paste CC/QT/ANS into the editor (.sisyphus upload pattern)
4. TEST   testquestion2.php preview → submit computed answer
5. FIX    on error: read message, patch file, re-upload  ─┐
6. LOOP   repeat 3–5 until preview grades correct ────────┘
7. ADD    addquestions2.php → target assignment
```

### C. Ares LMS — `site` + **hard PII gate**
- VERIFY: navigate student/parent-contact pages; confirm a parent-email element
  is locatable by a STRUCTURAL selector (label/role), never by the value.
- PII: **HARD.** `verify_no_pii.py` must pass or the run is invalid. The map
  records WHERE a parent email lives (selector path), never the email. Retrieval
  happens later, on-device, at run time (same rehydrate pattern as `redact-tree`).
- **Recommendation:** for Ares, redact the profile BEFORE saving (run the
  `redactTree` value map over `mergedToProfile` output) so raw PII never hits
  disk. The script becomes a backstop, not the only defense. Small scoped change
  to `mapHere` in `SiteMapper.svelte`.

### D. Gmail / Google — `page`
- VERIFY: compose flow — Compose → To / Subject / Body → Send, each by stable
  selector.
- PII: standard — map compose chrome only; do not capture inbox contents.
- This is the target half of "email this parent": Ares supplies the address
  (selector → on-device value), Gmail supplies the compose surface.

## "Email this parent" chain (enabled by the maps)

```
You: "email {student}'s parent"
  → open Ares → locate email by structural selector (on-device only)
  → open Gmail compose → fill To + Subject/Body from you
  → you approve → Send
```

## Key finding

`mergedToProfile` (merged-tree.ts) does **NOT** redact — the persisted
`SiteProfile` keeps raw `text`/`label`/`href`. Only the model path
(`redactTree` in agent-loop) is redacted. So a captured label like "Message Jane
Doe's parent" lands raw on disk. `verify_no_pii.py` exists to catch this; for
Ares, prefer redact-before-save (above).

## Deliverables

- `docs/plans/2026-06-25-mapping-goal-prompt.md` — the copy-paste goal prompt.
- `scripts/verify_no_pii.py` — PII gate over saved profile JSON (`--self-test`).
- This design doc.

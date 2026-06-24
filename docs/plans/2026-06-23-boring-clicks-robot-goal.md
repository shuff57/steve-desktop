# Boring-Clicks Robot — Goal Prompt (Milestone 1)

**Date:** 2026-06-23
**Companion to:** `2026-06-23-boring-clicks-robot-design.md`

Smallest slice that proves the concept: the local loop with the redactor as a hard gate.
No cloud plane or billing yet.

```text
GOAL: Prove the "boring-clicks robot" core loop end-to-end on the local runner.

Read docs/plans/2026-06-23-boring-clicks-robot-design.md first. Build on the existing
steve-desktop app — reuse the embedded browser/CDP, site discovery, agent loop, and
skills code already present. The only new safety-critical code is the redactor.

DEMO THAT DEFINES SUCCESS (must be runnable on demand):
1. Open an arbitrary website in the embedded browser.
2. "Train" it on that page: run discovery to produce a site-profile field map, and
   capture a repeatable workflow by example (e.g. "fill these fields and submit").
3. Replay: the agent autonomously executes the learned workflow on that page (review
   mode is fine) and reports what it did.

PASS CRITERIA (all must hold):
- [ ] Discovery produces a site-profile with interactive elements AND flags which fields
      are identifiers (name/ID-like). Saved as JSON under .agents/site-profiles/<domain>/.
- [ ] A trained workflow persists as a skill and can be re-run later without re-training.
- [ ] Replay completes the workflow on the same page; a summary lists each action taken
      and anything skipped/why (per AGENTS.md audit rule).
- [ ] REDACTOR GATE: every payload sent to the model passes through deterministic
      redaction first. A test feeds a snapshot containing known identifiers (e.g.
      "Jane Doe", ID 4471) and asserts NONE appear in the outbound payload; rehydration
      restores them locally. The model-call path refuses any un-redacted payload.
- [ ] SELF-HEAL: when a selector no longer matches (simulate by altering the page), the
      agent re-derives page state and recovers instead of failing silently or guessing.
- [ ] No school credentials and no raw student data leave the machine; nothing is synced
      to any cloud service in this milestone.

BUILD ORDER: redact.ts + its test suite first (TDD, RED→GREEN) — nothing touches a model
until that passes. Then wire redaction into the agent's model-call path. Then the
open→train→replay UI loop.

OUT OF SCOPE (do NOT build yet): cloud control plane, accounts, billing, the named cloud
model vendor wiring (a mock/local model is fine to prove the loop), free-text PII handling.
```

## Why these criteria

- **Redactor gate** — the product's whole safety claim. If a known identifier reaches the
  outbound payload, the milestone fails.
- **Persistence** — a trained workflow must survive as a reusable skill, else it's a demo, not a tool.
- **Self-heal** — what makes it worth more than a brittle macro.
- **Audit + no-data-leaves** — the trust-boundary guarantees from AGENTS.md and the design.

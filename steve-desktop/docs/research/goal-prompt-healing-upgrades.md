# Goal prompt — self-healing & map-freshness upgrades

Feed this to an implementation agent working in this repo. It states the goal and the hard
constraints; the path is the agent's to choose. Source: docs/research/ai-mapping-sota-2026-07-23.md.

---

GOAL: Make S.T.E.V.E's replay healing and site-map freshness self-maintaining, so a teacher's
saved skills keep working as MyOpenMath/Gmail/etc. drift — without scheduled full re-crawls and
without growing model spend. Work in priority order; each stage must land green before the next:

1. OUTCOME-GATED HEALS + PASSIVE REFRESH. A heal (any tier) persists to the stored profile/skill
   ONLY after its step's postcondition passes (URL changed, expected element appeared, value
   verified set). On every successful action, re-capture the target element's attributes and
   refresh its stored fingerprint. Mark a page dirty when a heal fires; dirty pages get a
   targeted single-page re-map, never a full-site crawl.
2. PATTERN-BASED PII DETECTION. Extend the existing Redactor (src/lib/redact.ts — the product's
   trust boundary) with reversible pattern tokens (emails, phones, numeric student IDs) so
   identifiers we never enumerated still cannot reach a model.
3. WEIGHTED FINGERPRINT RANKER. Replace the candidate/fuzzy tiers: record ~12 signals per
   element (role, accessible name, text, neighbor text, tag, id/class tokens, href, bbox
   center+size, sibling index, ancestor-path hash); on failure score ALL interactive elements
   and take top-k. Then the model tier becomes an ARBITER over those top-k snippets
   (slot-redacted), never the whole tree.
4. AX-TREE-FIRST OBSERVATION. Use CDP's Accessibility tree (pruned: interactive + visible only)
   as the observation/fingerprint source; DOM/screenshot remain fallbacks for broken-a11y pages.
5. KEY-NODE VERIFY + DRIFT TELEMETRY. Each page profile carries 3–5 must-exist key nodes + one
   postcondition; verify = replay key nodes only, escalating to re-map on failure. Count which
   heal tier fires per page; rising tier usage auto-flags the page for re-map.

HARD CONSTRAINTS:
- Never weaken the deny-regex safety rails or the read-only guarantees of verify/crawl paths.
- FERPA: every model-bound payload passes the Redactor; parameterized skills stay value-free;
  roster/student data stays local. No new model-bound path bypasses assertOutbound/assertNoLeak.
- Token discipline: inject only the CURRENT page's profile into any prompt; learn only from
  verified-successful runs. If a change grows model calls or prompt size, justify or drop it.
- Surgical diffs; match existing style; every stage leaves tests (vitest) proving its gate:
  e.g. a heal that fails its postcondition must NOT persist — test that.
- Compatibility: existing stored profiles and SKILL.md workflows must keep replaying unchanged;
  new fingerprint fields are additive.

DONE WHEN: all five stages merged green (vitest + svelte-check + cargo check), a live fixture
run shows a wrong-selector step healing, verifying its postcondition, persisting the fix, and
the same skill replaying cleanly a second time with zero heal tiers fired.

# AI site-mapping / self-healing — research survey (2026-07-23)

Two subagent surveys: open-source repos (repo-scout) and published techniques (technique-scout).
Condensed here; kept for implementation planning. Citations are in the session transcripts;
verify arXiv ids before leaning on any single claim.

## Where we're already ahead
- Deny-regex safety scoping on live data (logout/admin/mutating links) — barely exists in
  published agent work. Keep, never weaken.
- Persistent per-page profiles are cheap vs. online skill-induction (AWM/ASI pay a "double cost";
  ~50% of naively induced workflows in arXiv 2606.15017's audit came from FAILED runs).
- URL-template collapsing (roster of 200 = 1 template).

## Consensus gaps (both reports agree)
1. **Outcome-gated heals + passive refresh** (mabl pattern). Persist a heal ONLY after the step's
   postcondition passes; re-capture element attributes on every successful run so profiles
   refresh for free. Mostly obsoletes the scheduled verify pass. Highest leverage.
2. **Weighted element fingerprint, scored against all candidates.** Store ~12 signals per element
   (role, accessible name, text, neighbor text, tag, id/class tokens, href, bbox center+size,
   sibling index, ancestor-path hash). On failure, rank ALL interactive elements — replaces the
   stop-at-first-match tier cascade. We currently ignore geometry entirely.
3. **LLM as arbiter over top-k candidates**, not whole-tree relocation (VON Similo LLM: −44%
   failed localizations, cheaper). Keep slot redaction on the snippets.
4. **AX-tree-first observation.** CDP Accessibility domain gives it free; pruned tree ≈ 4k tokens
   vs ≈ 50k screenshot-equivalent. Screenshot/vision stays as canvas fallback only.
   (Counterpoint: many sites have broken a11y — hybrid, not a11y-only.)
5. **Key-node verify** (WebCanvas pattern): 3–5 must-exist elements + one postcondition per page;
   full re-map only when key nodes fail. Add drift telemetry: per-page heal-tier counters —
   rising tier usage = staleness signal.

## Repo-specific borrowables
- **Agent-E (MIT)**: mmid injection (sequential attrs on interactive elements) + 3-level DOM
  distillation to compact JSON. Cheap drift tolerance.
- **Stagehand (MIT)**: action caching + lazy replanning on change — closest to our replay model.
- **Tarsier (MIT)**: bracketed-ID visual tagging + OCR text layout for text-only models —
  fallback tier for canvas/visual-only widgets.
- **PII pattern detection**: our `redact.ts` tokenizes KNOWN identifiers (dictionary). Gap:
  pattern-based detection (emails, phones, IDs) for identifiers we haven't enumerated.
  Reversible tokens, same trust boundary.
- Not worth it: vision-only automation (Skyvern-style) for structured LMS sites; custom site-map
  DSL; XPath persistence; FireCrawl /map as primary discovery.

## Discipline rule (arXiv 2606.15017)
Memory modules can net-LOSE under honest token accounting. Inject only the current page's
profile into prompts; learn only from verified-successful trajectories.

## Suggested build order
1. Outcome-gated heals + passive refresh (reliability, no new ML)
2. Pattern-based PII detection added to redact.ts (FERPA hardening, small)
3. Weighted fingerprint ranker (replaces tiers 2–3) + LLM arbiter over top-k
4. AX-tree pruned observation format (token cost)
5. Key-node verify + drift telemetry (replaces scheduled full verify)

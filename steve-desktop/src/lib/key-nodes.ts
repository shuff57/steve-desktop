import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';
import { isInteractive, selectorForNode } from './fingerprint';
import type { HealTier } from './replay';

// Key-node verify + drift telemetry (stage 5).
//
// Checking a page was all-or-nothing: re-map every page to learn whether anything moved. That is
// slow and costs a model call per page. Instead each page carries a handful of must-exist
// anchors — if those still resolve, the page is intact and a full re-map is pointless; if one is
// missing, the page really has changed and re-mapping is justified.
//
// The second half is cheaper still: replay already reports WHICH heal tier recovered each step.
// A page that used to run clean and now leans on later tiers is drifting, and says so without
// any verification pass at all.

export interface KeyNode {
  selector: string;
  /** What it was, so a failure report reads like a sentence rather than a selector dump. */
  label: string;
}

/** Rank by how well an anchor survives change — a testid outlives an id, which outlives a tag. */
function anchorStrength(node: SnapshotNode): number {
  const a = node.attrs ?? {};
  if (a['data-testid']) return 4;
  if (a['id']) return 3;
  if (a['name']) return 2;
  if (a['aria-label'] || a['role']) return 1;
  return 0;
}

/**
 * Pick the 3–5 most durable interactive anchors on a page. These become the page's "is it still
 * itself?" check. Elements with no stable anchor are skipped — a key node that can't be addressed
 * would report drift on every run.
 */
export function deriveKeyNodes(snapshot: SnapshotResult, max = 5): KeyNode[] {
  const seen = new Set<string>();
  return snapshot.nodes
    .filter(isInteractive)
    .filter((n) => anchorStrength(n) > 0)
    .sort((a, b) => anchorStrength(b) - anchorStrength(a))
    .map((n) => ({
      selector: selectorForNode(n),
      label: (n.attrs['aria-label'] || n.attrs['name'] || n.text || n.tag || '').trim().slice(0, 60),
    }))
    .filter((k) => (seen.has(k.selector) ? false : (seen.add(k.selector), true)))
    .slice(0, max);
}

export interface KeyNodeVerdict {
  ok: boolean;
  missing: KeyNode[];
  checked: number;
}

/**
 * Verify a page by resolving only its key nodes — the cheap alternative to a re-map. A page with
 * no key nodes recorded reports ok:false so the caller falls back to a real re-map instead of
 * treating "nothing to check" as "nothing wrong".
 */
export async function verifyKeyNodes(
  keyNodes: KeyNode[],
  exists: (selector: string) => boolean | Promise<boolean>,
): Promise<KeyNodeVerdict> {
  if (!keyNodes.length) return { ok: false, missing: [], checked: 0 };
  const missing: KeyNode[] = [];
  for (const k of keyNodes) {
    if (!(await exists(k.selector))) missing.push(k);
  }
  return { ok: missing.length === 0, missing, checked: keyNodes.length };
}

// ── Drift telemetry ────────────────────────────────────────────────────────

/** How much each tier signals staleness. Reaching for the model is far worse than a stored
 *  candidate still matching, so they are not counted equally. */
const TIER_COST: Record<HealTier, number> = { candidate: 1, ranked: 2, fuzzy: 3, model: 4 };

export interface DriftStats {
  /** Total replayed steps observed for this page. */
  steps: number;
  /** Heal-tier counts across those steps. */
  tiers: Partial<Record<HealTier, number>>;
}

export function emptyDrift(): DriftStats {
  return { steps: 0, tiers: {} };
}

/** Fold one replay's results into a page's running stats. */
export function recordDrift(stats: DriftStats, results: { tier?: HealTier }[]): DriftStats {
  const tiers = { ...stats.tiers };
  for (const r of results) {
    if (r.tier) tiers[r.tier] = (tiers[r.tier] ?? 0) + 1;
  }
  return { steps: stats.steps + results.length, tiers };
}

/** Weighted heal pressure per step, 0 when a page replays clean. */
export function driftScore(stats: DriftStats): number {
  if (!stats.steps) return 0;
  let cost = 0;
  for (const [tier, n] of Object.entries(stats.tiers)) {
    cost += TIER_COST[tier as HealTier] * (n ?? 0);
  }
  return cost / stats.steps;
}

/**
 * True when a page is leaning on healing hard enough that its map is probably stale. Deliberately
 * needs a few observed steps first, so one unlucky run doesn't trigger a re-map.
 */
export function shouldRemap(stats: DriftStats, threshold = 1, minSteps = 3): boolean {
  return stats.steps >= minSteps && driftScore(stats) >= threshold;
}

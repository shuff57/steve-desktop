import type { Workflow, WorkflowStep } from './types/site-profile';
import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';
import { redactTree } from './redact-tree';
import { callModelTree, type ModelTransport } from './model-gate';
import { rankCandidates, type RankedCandidate } from './fingerprint';

// Replays a trained workflow deterministically. When a recorded selector no
// longer matches, it re-derives page state from a fresh snapshot and fuzzy-matches
// the step's intent to a current element instead of failing silently or guessing.
// Every step is audited (done / recovered / skipped + why) per AGENTS.md.
//
// Self-heal chain: 0) recorded selector → 1) stored candidate anchors →
// 2) local fuzzy re-derivation → 3) escalate to the model with REDACTED structure
// (last resort, cloud). Tiers 0–2 run locally on raw data; Tier 3 sends only the
// slot-redacted tree, so the model relocates by role/label/position and never sees PII.

type Awaitable<T> = T | Promise<T>;

export interface PageDriver {
  exists(selector: string): Awaitable<boolean>;
  snapshot(): Awaitable<SnapshotResult>;
  /** Perform the step against `selector`; return success. */
  act(step: WorkflowStep, selector: string): Awaitable<boolean>;
  /** Optional postcondition check after a successful act — value verified set, URL changed,
   *  expected element appeared. A heal persists ONLY when this passes (outcome-gated). */
  verify?(step: WorkflowStep, selector: string): Awaitable<boolean>;
  /** Optional passive refresh: re-capture the acted element's live attributes as fresh candidate
   *  selectors, so stored anchors stay current without a scheduled re-map. */
  fingerprint?(selector: string): Awaitable<string[] | null>;
}

export type StepStatus = 'done' | 'recovered' | 'skipped';

export interface ReplayStepResult {
  step: WorkflowStep;
  status: StepStatus;
  selectorUsed?: string;
  /** Set when the driver has a verify(): did the step's postcondition pass? */
  verified?: boolean;
  detail: string;
}

export interface ReplaySummary {
  workflow: string;
  results: ReplayStepResult[];
  completed: boolean;
  /** True when any heal tier fired — callers mark the page dirty for a targeted re-map. */
  healed: boolean;
}

const STOPWORDS = new Set(['the', 'a', 'an', 'field', 'button', 'input', 'box', 'link', 'for', 'to', 'and', 'of']);

function tokenize(text: string): string[] {
  return text
    .toLowerCase()
    .replace(/([a-z])([A-Z])/g, '$1 $2')
    .replace(/[^a-z0-9]+/gi, ' ')
    .split(' ')
    .filter((t) => t && !STOPWORDS.has(t));
}

function nodeLabel(node: SnapshotNode): string {
  return node.attrs['aria-label'] ?? node.attrs['name'] ?? node.attrs['id'] ?? node.text ?? '';
}

function nodeSelector(node: SnapshotNode): string {
  if (node.attrs['id']) return `#${node.attrs['id']}`;
  if (node.attrs['name']) return `${node.tag}[name="${node.attrs['name']}"]`;
  if (node.attrs['aria-label']) return `${node.tag}[aria-label="${node.attrs['aria-label']}"]`;
  return node.tag;
}

/** Re-derive the best matching selector for a step from a fresh snapshot. */
export function findSelectorForStep(step: WorkflowStep, snapshot: SnapshotResult): string | null {
  const wanted = new Set([
    ...tokenize(step.description ?? ''),
    ...tokenize(step.selector ?? ''),
  ]);
  if (wanted.size === 0) return null;

  let best: { selector: string; score: number } | null = null;
  for (const node of snapshot.nodes) {
    const labelTokens = tokenize(nodeLabel(node));
    let score = 0;
    for (const t of labelTokens) if (wanted.has(t)) score += 1;
    if (score > 0 && (!best || score > best.score)) {
      best = { selector: nodeSelector(node), score };
    }
  }
  return best ? best.selector : null;
}

// ── Tier 3: model relocation over the redacted tree ────────────────────────

/** Given a fresh page snapshot, return a selector for the step (or null). Async/cloud.
 *  `shortlist` (when the ranker produced one) is the handful of elements worth choosing between —
 *  a healer should arbitrate those rather than reading the whole tree. */
export type ModelHealer = (
  step: WorkflowStep,
  snapshot: SnapshotResult,
  shortlist?: RankedCandidate[],
) => Promise<string | null>;

/** The relocate instruction the model sees alongside the redacted page structure. When the ranker
 *  produced a shortlist, the model is asked to ARBITRATE those few rather than scan everything —
 *  cheaper, and it can't wander off to an element the ranker already judged irrelevant. */
export function buildRelocatePrompt(
  step: WorkflowStep,
  redactedStructure: string,
  shortlist?: RankedCandidate[],
): string {
  const intent = step.description || step.selector || step.action;
  const head = [
    'A recorded selector no longer matches. Relocate the element for this step.',
    `Step: ${step.action} — "${intent}"`,
    step.selector ? `Old selector (stale): ${step.selector}` : '',
  ].filter(Boolean);

  if (shortlist?.length) {
    return [
      ...head,
      '',
      'These are the closest elements on the page now, best-scored first. Choose the ONE that is',
      'the step\'s element and reply with ONLY its selector — or reply NONE if none of them is it.',
      ...shortlist.map((c, i) => `${i + 1}. ${c.selector}  (score ${c.score.toFixed(2)})`),
    ].join('\n');
  }

  return [
    ...head,
    'Reply with ONLY one CSS or role=name selector for the current element — nothing else.',
    '',
    'Redacted page structure (data values are tokens like ⟦D1⟧ — never use a token as a selector):',
    redactedStructure,
  ].join('\n');
}

/** Pull a single selector out of a model reply that should be just a selector. */
export function parseRelocateReply(reply: string): string | null {
  const line =
    reply
      .split('\n')
      .map((l) => l.trim())
      .filter((l) => l && !/^```/.test(l)) // drop blank lines and code-fence lines
      .shift() ?? '';
  const cleaned = line
    .replace(/^selector:\s*/i, '')
    .replace(/^["'`]|["'`]$/g, '')
    .trim();
  // a leftover token means the model echoed data, not a selector — reject it
  if (!cleaned || cleaned.includes('⟦')) return null;
  return cleaned;
}

/**
 * Tier-3 healer factory: escalate to the model with the slot-redacted tree (deny-by-default).
 * callModelTree refuses the call if any redacted value leaked into the payload and rehydrates
 * the reply locally. The model relocates by role/label/position — weaker than a PII-distinguished
 * anchor, by design — so it can miss on elements only data distinguishes; that surfaces as a skip.
 */
export function modelRelocator(transport: ModelTransport): ModelHealer {
  return async (step, snapshot, shortlist) => {
    const red = redactTree(snapshot);
    // Even when arbitrating a shortlist the call still goes through callModelTree, so the leak
    // gate runs on whatever is sent — selectors are chrome, but the gate is not optional.
    const reply = await callModelTree(red, (text) => transport(buildRelocatePrompt(step, text, shortlist)));
    const picked = parseRelocateReply(reply);
    if (!picked || /^none$/i.test(picked)) return null;
    // Arbitration is a CHOICE among the shortlist — anything else is the model inventing an
    // element, which we refuse rather than act on.
    if (shortlist?.length && !shortlist.some((c) => c.selector === picked)) return null;
    return picked;
  };
}

// ── Outcome gate + passive refresh ─────────────────────────────────────────

/** Rewrite the step's cached selector after a VERIFIED heal, keeping the stale one as a
 *  candidate so a site that flips back still heals cheaply. */
function persistHeal(step: WorkflowStep, selector: string): void {
  const old = step.selector;
  if (old && old !== selector) {
    step.candidates = [old, ...(step.candidates ?? []).filter((c) => c !== old && c !== selector)];
  }
  step.selector = selector;
}

/** Passive refresh: merge the acted element's fresh live anchors into the stored candidates.
 *  Best-effort — a refresh failure never affects the run. */
async function refreshFingerprint(page: PageDriver, step: WorkflowStep, selector: string): Promise<void> {
  if (!page.fingerprint) return;
  try {
    const fresh = (await page.fingerprint(selector)) ?? [];
    const merged = [...new Set([...fresh.filter((f) => f !== step.selector), ...(step.candidates ?? [])])];
    if (merged.length) step.candidates = merged.slice(0, 6);
  } catch {
    /* refresh is free-ride bookkeeping, never a failure */
  }
}

/**
 * Act on `selector` and gate the outcome: a heal persists to the workflow ONLY after the
 * driver's postcondition passes. Every tier funnels through here so persistence and passive
 * refresh have exactly one code path.
 */
async function actGated(
  page: PageDriver,
  step: WorkflowStep,
  selector: string,
  opts: { healed: boolean; okDetail: string; failDetail: string },
): Promise<ReplayStepResult> {
  const ok = await page.act(step, selector);
  if (!ok) return { step, status: 'skipped', selectorUsed: selector, detail: opts.failDetail };

  let verified: boolean | undefined;
  if (page.verify) {
    try {
      verified = await page.verify(step, selector);
    } catch {
      verified = false; // an unverifiable outcome is an unverified outcome
    }
    if (!verified) {
      return {
        step,
        status: 'skipped',
        selectorUsed: selector,
        verified: false,
        detail: `Acted on "${selector}" but the postcondition failed — outcome unverified, nothing persisted`,
      };
    }
  }

  if (opts.healed) persistHeal(step, selector);
  await refreshFingerprint(page, step, selector);
  return { step, status: opts.healed ? 'recovered' : 'done', selectorUsed: selector, verified, detail: opts.okDetail };
}

export async function replayWorkflow(
  workflow: Workflow,
  page: PageDriver,
  heal?: ModelHealer,
): Promise<ReplaySummary> {
  const results: ReplayStepResult[] = [];

  for (const step of workflow.steps) {
    const recorded = step.selector;

    // happy path: recorded selector still matches
    if (recorded && (await page.exists(recorded))) {
      results.push(
        await actGated(page, step, recorded, {
          healed: false,
          okDetail: `Acted on ${recorded}`,
          failDetail: `Action failed on ${recorded}`,
        }),
      );
      continue;
    }

    // self-heal tier 1: try the step's stored alternate anchors (role=name, id, testid…)
    let healedByCandidate: string | null = null;
    for (const cand of step.candidates ?? []) {
      if (cand !== recorded && (await page.exists(cand))) {
        healedByCandidate = cand;
        break;
      }
    }
    if (healedByCandidate) {
      results.push(
        await actGated(page, step, healedByCandidate, {
          healed: true,
          okDetail: `Selector "${recorded}" no longer matched; recovered via stored candidate "${healedByCandidate}"`,
          failDetail: `Candidate "${healedByCandidate}" also failed`,
        }),
      );
      continue;
    }

    // self-heal tier 2: rank EVERY interactive element against the step's stored fingerprint and
    // try the best few in order. Replaces stop-at-first-match — a renamed id costs a little
    // rather than disqualifying the element. Each attempt is still outcome-gated, so a
    // high-scoring wrong guess fails its postcondition instead of being persisted.
    const snapshot = await page.snapshot();
    let ranked: RankedCandidate[] = [];
    if (step.fingerprint) {
      ranked = rankCandidates(step.fingerprint, snapshot);
      let recoveredByRank: ReplayStepResult | null = null;
      for (const cand of ranked) {
        if (cand.selector === recorded || !(await page.exists(cand.selector))) continue;
        const r = await actGated(page, step, cand.selector, {
          healed: true,
          okDetail: `Selector "${recorded}" no longer matched; ranked page elements and recovered via "${cand.selector}" (score ${cand.score.toFixed(2)})`,
          failDetail: `Ranked candidate "${cand.selector}" failed its postcondition`,
        });
        if (r.status === 'recovered') {
          recoveredByRank = r;
          break;
        }
      }
      if (recoveredByRank) {
        results.push(recoveredByRank);
        continue;
      }
    }

    // tier 2b: no fingerprint (older recording) — fall back to the label-token match.
    const healed = findSelectorForStep(step, snapshot);
    if (healed && (await page.exists(healed))) {
      results.push(
        await actGated(page, step, healed, {
          healed: true,
          okDetail: `Selector "${recorded}" no longer matched; re-derived page state and recovered via "${healed}"`,
          failDetail: `Recovered selector "${healed}" also failed`,
        }),
      );
      continue;
    }

    // self-heal tier 3 (last resort, cloud): escalate to the model with the REDACTED tree.
    // Reuses the tier-2 snapshot — no second capture. The cached selector is rewritten only
    // after the postcondition passes (actGated), so an unverified relocation never persists.
    if (heal) {
      let relocated: string | null = null;
      try {
        relocated = await heal(step, snapshot, ranked.length ? ranked : undefined);
      } catch {
        relocated = null; // model/transport failure → fall through to skip, never throw
      }
      if (relocated && (await page.exists(relocated))) {
        const r = await actGated(page, step, relocated, {
          healed: true,
          okDetail: `Selector "${recorded}" not found locally; escalated to the model (redacted), relocated to "${relocated}" and rewrote the cache`,
          failDetail: `Relocated selector "${relocated}" also failed`,
        });
        results.push(r); // recovered, or an audited act/postcondition failure — both beat a generic skip
        continue;
      }
    }

    results.push({
      step,
      status: 'skipped',
      detail: `Selector "${recorded}" not found and no current element matched "${step.description ?? recorded}" — skipped instead of guessing`,
    });
  }

  return {
    workflow: workflow.name,
    results,
    completed: results.every((r) => r.status !== 'skipped'),
    healed: results.some((r) => r.status === 'recovered'),
  };
}

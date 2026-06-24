import type { Workflow, WorkflowStep } from './types/site-profile';
import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';
import { redactTree } from './redact-tree';
import { callModelTree, type ModelTransport } from './model-gate';

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
}

export type StepStatus = 'done' | 'recovered' | 'skipped';

export interface ReplayStepResult {
  step: WorkflowStep;
  status: StepStatus;
  selectorUsed?: string;
  detail: string;
}

export interface ReplaySummary {
  workflow: string;
  results: ReplayStepResult[];
  completed: boolean;
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

/** Given a fresh page snapshot, return a selector for the step (or null). Async/cloud. */
export type ModelHealer = (step: WorkflowStep, snapshot: SnapshotResult) => Promise<string | null>;

/** The relocate instruction the model sees alongside the redacted page structure. */
export function buildRelocatePrompt(step: WorkflowStep, redactedStructure: string): string {
  const intent = step.description || step.selector || step.action;
  return [
    'A recorded selector no longer matches. Relocate the element for this step.',
    `Step: ${step.action} — "${intent}"`,
    step.selector ? `Old selector (stale): ${step.selector}` : '',
    'Reply with ONLY one CSS or role=name selector for the current element — nothing else.',
    '',
    'Redacted page structure (data values are tokens like ⟦D1⟧ — never use a token as a selector):',
    redactedStructure,
  ]
    .filter(Boolean)
    .join('\n');
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
  return async (step, snapshot) => {
    const red = redactTree(snapshot);
    const reply = await callModelTree(red, (text) => transport(buildRelocatePrompt(step, text)));
    return parseRelocateReply(reply);
  };
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
      const ok = await page.act(step, recorded);
      results.push(
        ok
          ? { step, status: 'done', selectorUsed: recorded, detail: `Acted on ${recorded}` }
          : { step, status: 'skipped', selectorUsed: recorded, detail: `Action failed on ${recorded}` },
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
      const ok = await page.act(step, healedByCandidate);
      results.push(
        ok
          ? {
              step,
              status: 'recovered',
              selectorUsed: healedByCandidate,
              detail: `Selector "${recorded}" no longer matched; recovered via stored candidate "${healedByCandidate}"`,
            }
          : { step, status: 'skipped', selectorUsed: healedByCandidate, detail: `Candidate "${healedByCandidate}" also failed` },
      );
      continue;
    }

    // self-heal tier 2: re-derive page state and fuzzy-match the step's intent
    const snapshot = await page.snapshot();
    const healed = findSelectorForStep(step, snapshot);
    if (healed && (await page.exists(healed))) {
      const ok = await page.act(step, healed);
      results.push(
        ok
          ? {
              step,
              status: 'recovered',
              selectorUsed: healed,
              detail: `Selector "${recorded}" no longer matched; re-derived page state and recovered via "${healed}"`,
            }
          : { step, status: 'skipped', selectorUsed: healed, detail: `Recovered selector "${healed}" also failed` },
      );
      continue;
    }

    // self-heal tier 3 (last resort, cloud): escalate to the model with the REDACTED tree.
    // Reuses the tier-2 snapshot — no second capture. On success the cached selector is
    // rewritten in place so the next replay hits the happy path (caller persists the workflow).
    if (heal) {
      let relocated: string | null = null;
      try {
        relocated = await heal(step, snapshot);
      } catch {
        relocated = null; // model/transport failure → fall through to skip, never throw
      }
      if (relocated && (await page.exists(relocated))) {
        const ok = await page.act(step, relocated);
        if (ok) {
          step.selector = relocated;
          results.push({
            step,
            status: 'recovered',
            selectorUsed: relocated,
            detail: `Selector "${recorded}" not found locally; escalated to the model (redacted), relocated to "${relocated}" and rewrote the cache`,
          });
          continue;
        }
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
  };
}

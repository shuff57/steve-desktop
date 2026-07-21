import { callModelTree, type ModelTransport } from './model-gate';

// AI frontier planner: given the links that ALREADY passed isCrawlableLink (the hard,
// deterministic safety gate), ask the model which are worth visiting and in what order.
// The model replies with INDICES into the candidate list — it structurally cannot add a
// URL, only reorder or skip. Every failure mode falls back to "follow all, in order",
// which is exactly the deterministic BFS behavior.

export interface FrontierCandidate {
  url: string;
  label: string;
}

export interface FrontierPlan {
  /** Candidate URLs to visit, in priority order. Always a subset of the input. */
  follow: string[];
  /** Candidates the model chose not to visit, with its reason (for the UI, never silent). */
  skip: { url: string; reason: string }[];
  /** Reply entries that referenced no valid candidate — dropped, counted for diagnostics. */
  dropped: number;
}

/** The deterministic no-op plan: follow everything in the order given. */
export function fallbackPlan(candidates: FrontierCandidate[]): FrontierPlan {
  return { follow: candidates.map((c) => c.url), skip: [], dropped: 0 };
}

export function buildFrontierPrompt(
  candidates: FrontierCandidate[],
  goal: string,
  mapSummary: string,
): string {
  const list = candidates
    .map((c, i) => {
      let path = c.url;
      try {
        const u = new URL(c.url);
        path = u.pathname + u.search;
      } catch {
        /* keep as-is */
      }
      return `${i}: "${c.label}" → ${path}`;
    })
    .join('\n');
  return (
    `You prioritize a site crawl. Goal: ${goal}\n` +
    (mapSummary ? `Mapped so far:\n${mapSummary}\n` : '') +
    'Candidate links (already safety-filtered):\n' +
    list +
    '\n\nDecide which to visit for the goal and which are redundant (e.g. one row of a ' +
    'repeating list that is already represented). Reply with ONLY JSON, using the numeric ' +
    'indices above:\n' +
    '{"follow": [<indices in priority order>], "skip": [{"i": <index>, "reason": "<short>"}]}'
  );
}

/**
 * Parse the model's plan. Invariants enforced here, not trusted from the model:
 * - follow ⊆ candidates (indices out of range / duplicates are dropped and counted);
 * - a candidate mentioned nowhere is still followed (appended in original order) —
 *   coverage is only lost by an EXPLICIT skip with a reason;
 * - unparseable reply → null (caller uses fallbackPlan).
 */
export function parseFrontierPlan(reply: string, candidates: FrontierCandidate[]): FrontierPlan | null {
  const m = reply.match(/\{[\s\S]*\}/);
  if (!m) return null;
  let obj: unknown;
  try {
    obj = JSON.parse(m[0]);
  } catch {
    return null;
  }
  if (typeof obj !== 'object' || obj === null) return null;
  const o = obj as Record<string, unknown>;
  if (!Array.isArray(o['follow'])) return null;

  const valid = (i: unknown): i is number => typeof i === 'number' && Number.isInteger(i) && i >= 0 && i < candidates.length;
  const seen = new Set<number>();
  let dropped = 0;

  const follow: string[] = [];
  for (const i of o['follow'] as unknown[]) {
    if (!valid(i) || seen.has(i)) {
      dropped += 1;
      continue;
    }
    seen.add(i);
    follow.push(candidates[i].url);
  }

  const skip: { url: string; reason: string }[] = [];
  const rawSkip = Array.isArray(o['skip']) ? (o['skip'] as unknown[]) : [];
  for (const s of rawSkip) {
    const e = s as Record<string, unknown>;
    const i = e?.['i'];
    if (!valid(i) || seen.has(i)) {
      dropped += 1;
      continue;
    }
    seen.add(i);
    skip.push({ url: candidates[i].url, reason: typeof e['reason'] === 'string' ? e['reason'].slice(0, 120) : 'model skip' });
  }

  // Unmentioned candidates keep their visit — silence is not a skip.
  for (let i = 0; i < candidates.length; i++) {
    if (!seen.has(i)) follow.push(candidates[i].url);
  }
  return { follow, skip, dropped };
}

/**
 * Plan the frontier via the gated model call. Any failure (leak-check refusal, transport
 * error, bad reply) returns the fallback plan — the crawl never stalls on the model.
 */
export async function planFrontier(
  candidates: FrontierCandidate[],
  opts: { goal: string; mapSummary: string; secrets: Record<string, string>; transport: ModelTransport },
): Promise<FrontierPlan> {
  if (candidates.length < 2) return fallbackPlan(candidates); // nothing to prioritize
  try {
    const prompt = buildFrontierPrompt(candidates, opts.goal, opts.mapSummary);
    // Identity rehydrate: the reply holds indices and short reasons shown in the UI —
    // tokens must stay tokens. Leak check on `map` still applies.
    const reply = await callModelTree(
      { redactedText: prompt, map: opts.secrets, rehydrate: (t) => t, redact: (t) => t },
      opts.transport,
    );
    return parseFrontierPlan(reply, candidates) ?? fallbackPlan(candidates);
  } catch {
    return fallbackPlan(candidates);
  }
}

// ── Template tie-break ─────────────────────────────────────────────────────────────────
// The deterministic rule collapses a URL family only when samples come back byte-identical.
// Families that never do (per-row input labels) used to eat the crawl. When signatures
// disagree, ask the model whether the shapes are the same template anyway.

export function buildTiebreakPrompt(sigA: string, sigB: string): string {
  return (
    'Two pages from the same URL pattern produced these structural signatures ' +
    '(one element per line; * and # are data placeholders). Are they the SAME page template ' +
    'with different data, or genuinely different layouts?\n\n' +
    `--- A ---\n${sigA.slice(0, 4000)}\n--- B ---\n${sigB.slice(0, 4000)}\n\n` +
    'Reply with ONLY JSON: {"same": true} or {"same": false}'
  );
}

/** true/false = model verdict; null = unavailable (caller keeps deterministic behavior). */
export async function fetchTemplateTiebreak(
  sigA: string,
  sigB: string,
  secrets: Record<string, string>,
  transport: ModelTransport,
): Promise<boolean | null> {
  try {
    const reply = await callModelTree(
      { redactedText: buildTiebreakPrompt(sigA, sigB), map: secrets, rehydrate: (t) => t, redact: (t) => t },
      transport,
    );
    const m = reply.match(/\{[\s\S]*?\}/);
    if (!m) return null;
    const obj = JSON.parse(m[0]) as Record<string, unknown>;
    return typeof obj['same'] === 'boolean' ? obj['same'] : null;
  } catch {
    return null;
  }
}

import { fetch as tauriFetch } from '@tauri-apps/plugin-http';

// Model lists for the provider/model pickers. Ollama is fetched live from /api/tags (the only
// provider that exposes installed models locally); cloud providers use a known short list since
// there's no wired listing endpoint. Callers keep a free-text fallback, so an out-of-list model
// is still allowed — this only *populates* the dropdown.

const OLLAMA_DEFAULT_URL = 'http://localhost:11434';

// ponytail: hardcoded cloud lists — cheap and good enough. Upgrade to a real models endpoint
// per provider if/when one is wired (fetchAvailableModels in oauth.ts is still a stub).
const STATIC_MODELS: Record<string, string[]> = {
  openai: ['gpt-4o', 'gpt-4o-mini', 'o3', 'o1'],
  anthropic: ['claude-opus-5', 'claude-sonnet-5', 'claude-opus-4-8', 'claude-haiku-4-5-20251001'],
};

// Ollama Cloud models that support BOTH tool-calling AND vision, as the ids opencode runs
// (`ollama-cloud/<name>`, verified against `opencode models`). ollama.com's API does NOT reliably
// report cloud capabilities — its /api/show returns empty caps for almost every cloud model — so
// this is curated from the capability badges on each model's library page
// (https://ollama.com/search?c=cloud). Verified 2026-07-22.
// ponytail: hand-curated — refresh when the cloud catalog moves (re-scrape the `bg-indigo-50`
// capability badges, keep only models whose badges include both `tools` and `vision`).
export const OLLAMA_CLOUD_TOOLS_VISION = [
  'ollama-cloud/kimi-k2.6',
  'ollama-cloud/qwen3.5:397b',
  'ollama-cloud/minimax-m3',
  'ollama-cloud/mistral-large-3:675b',
  'ollama-cloud/gemma4:31b',
  'ollama-cloud/kimi-k2.5',
  'ollama-cloud/kimi-k2.7-code',
];

const PROVIDER_LABELS: Record<string, string> = {
  ollama: 'Ollama', openai: 'OpenAI', anthropic: 'Anthropic (Claude)',
  'github-models': 'GitHub Models', 'google-gemini': 'Google Gemini',
};

/** Friendly label for a provider_configs id (falls back to the id itself). */
export function providerLabel(id: string): string {
  return PROVIDER_LABELS[id] ?? id;
}

type Tier = 'best' | 'better' | 'good';

// Quality tier for known cloud models.
// ponytail: hardcoded ranking — refresh when model names change.
const MODEL_TIERS: Record<string, Tier> = {
  // Anthropic
  'claude-opus-5': 'best',
  'claude-opus-4-8': 'best',
  'claude-sonnet-5': 'better',
  'claude-sonnet-4-6': 'better',
  'claude-haiku-4-5-20251001': 'good',
  // OpenAI
  o3: 'best',
  o1: 'best',
  'gpt-4o': 'better',
  'gpt-4o-mini': 'good',
};

/** Parameter size in billions parsed from a model name (e.g. `:8b`, `8x7b` MoE → product), or undefined. */
export function parseModelSize(name: string): number | undefined {
  const lower = name.toLowerCase();
  const moe = lower.match(/(\d+(?:\.\d+)?)\s*x\s*(\d+(?:\.\d+)?)\s*b\b/);
  if (moe) return parseFloat(moe[1]) * parseFloat(moe[2]);
  const m = lower.match(/(\d+(?:\.\d+)?)\s*b\b/);
  return m ? parseFloat(m[1]) : undefined;
}

// Research-informed base quality for known model families whose names don't encode a size
// (e.g. `kimi-k2.6`, `minimax-m3`, `glm-5.2`). Without this, those frontier models fell into the
// ungrouped "Other" bucket. Sources: ollama.com cloud catalog + 2026 open-model rankings —
// GLM-5.x and Kimi-K2.x lead; DeepSeek-V4 / MiniMax-M3 / Qwen3.5 / large Mistral frontier;
// Gemma4 / Gemini-3-Flash / MiniMax-M2.x / Qwen3-Coder / Devstral / gpt-oss strong mid;
// Ministral / LFM / VibeThinker small. ponytail: hand-curated — refresh as the catalog moves.
// A family's tier is a CEILING — the best it can be regardless of size. Size can only demote
// (a small variant of a top family), never promote (a big-but-not-frontier model like gpt-oss).
const FAMILY_TIER: Array<[RegExp, Tier]> = [
  [/^glm-5/, 'best'],
  [/^kimi-k2/, 'best'],
  [/^deepseek-v[34]/, 'best'],
  [/^minimax-m3/, 'best'],
  [/^mistral-large/, 'best'],
  [/^qwen3\.5/, 'best'],
  [/^nemotron-3-(ultra|super)/, 'best'],
  [/^gemma4/, 'better'],
  [/^gemini-3-flash/, 'better'],
  [/^minimax-m2/, 'better'],
  [/^qwen3-coder/, 'better'],
  [/^devstral/, 'better'],
  [/^gpt-oss/, 'better'], // capable, but not frontier — capped below Best even at 120B
  [/^nemotron-3-nano/, 'better'],
  [/^ministral/, 'better'],
  [/^lfm2/, 'good'],
  [/^vibethinker/, 'good'],
];

const TIER_RANK: Record<Tier, number> = { good: 0, better: 1, best: 2 };
const lowerTier = (a: Tier, b: Tier): Tier => (TIER_RANK[a] <= TIER_RANK[b] ? a : b);

function familyTier(lower: string): Tier | undefined {
  for (const [re, tier] of FAMILY_TIER) if (re.test(lower)) return tier;
  return undefined;
}

function sizeTier(name: string): Tier | undefined {
  const size = parseModelSize(name);
  if (size === undefined) return undefined;
  return size < 9 ? 'good' : size <= 34 ? 'better' : 'best';
}

/**
 * Tier an Ollama model. A known family sets a quality CEILING; an explicit size can demote
 * within it (small variant) but never promote past it. Unknown family → size alone.
 * `:cloud` with neither → best (hosted frontier). Otherwise undefined (ungrouped).
 * ponytail: curated family ceilings + size; both tunable.
 */
export function ollamaTier(name: string): Tier | undefined {
  const lower = name.toLowerCase();
  const fam = familyTier(lower);
  const size = sizeTier(name);
  if (fam && size) return lowerTier(fam, size); // family caps; size may demote
  if (fam) return fam;
  if (size) return size;
  return lower.includes(':cloud') ? 'best' : undefined;
}

/** Tier any model: known cloud table first, then the Ollama heuristic. */
function tierOf(model: string): Tier | undefined {
  return MODEL_TIERS[model] ?? ollamaTier(model);
}

const TIER_ORDER = ['best', 'better', 'good'] as const;
const TIER_LABEL: Record<Tier, string> = { best: 'Best', better: 'Better', good: 'Good' };

export interface ModelGroup {
  /** optgroup label; '' means render these options flat (no group). */
  label: string;
  models: string[];
}

/** Bucket models into Best/Better/Good optgroups (each sorted A→Z); untierable models fall to an "Other"/flat group. */
export function groupModels(models: string[]): ModelGroup[] {
  const sortAZ = (a: string, b: string) => a.localeCompare(b, undefined, { sensitivity: 'base', numeric: true });
  const groups: ModelGroup[] = [];
  for (const tier of TIER_ORDER) {
    const inTier = models.filter((m) => tierOf(m) === tier).sort(sortAZ);
    if (inTier.length) groups.push({ label: TIER_LABEL[tier], models: inTier });
  }
  const other = models.filter((m) => tierOf(m) === undefined).sort(sortAZ);
  // If nothing was tiered, render the leftovers flat (label '') rather than "Other".
  if (other.length) groups.push({ label: groups.length ? 'Other' : '', models: other });
  return groups;
}

interface OllamaTagModel {
  name?: string;
  model?: string;
  capabilities?: string[];
}

/** Parse the {name, capabilities} of each model in an Ollama /api/tags response. */
export function parseOllamaTags(json: unknown): Array<{ name: string; capabilities?: string[] }> {
  const models = (json as { models?: OllamaTagModel[] })?.models;
  if (!Array.isArray(models)) return [];
  const out: Array<{ name: string; capabilities?: string[] }> = [];
  for (const m of models) {
    const name = m.name ?? m.model;
    if (typeof name === 'string' && name.length > 0) out.push({ name, capabilities: m.capabilities });
  }
  return out;
}

// The agent drives the browser purely from the redacted DOM tree — the sidecar forwards only
// messages + DOM to the model, never the screenshot — so tool calling is the only hard
// requirement. (Vision/thinking are unused as a filter; requiring them hid strong text models
// like DeepSeek-V4 and GLM-5.2.)
export const REQUIRED_OLLAMA_CAPS = ['tools'] as const;

/** True only if every required capability is present. */
export function hasRequiredCaps(caps: string[] | undefined | null): boolean {
  return !!caps && REQUIRED_OLLAMA_CAPS.every((c) => caps!.includes(c));
}

/** Compare dotted version arrays, e.g. [2,7] vs [3] → negative. */
function cmpVersion(a: number[], b: number[]): number {
  for (let i = 0; i < Math.max(a.length, b.length); i++) {
    const d = (a[i] ?? 0) - (b[i] ?? 0);
    if (d) return d;
  }
  return 0;
}

// Size/tier suffixes denote a variant of the SAME generation, so they're stripped when keying a
// version line — `deepseek-v4-pro` and `deepseek-v3.2` then share line `deepseek-v` and v4 wins.
// TYPE suffixes (coder, code, instruct, vision…) are NOT here, so `qwen3-coder` stays distinct
// from `qwen3.5` and `kimi-k2.7-code` from `kimi-k2.6`.
const TIER_SUFFIX = new Set([
  'pro', 'flash', 'mini', 'nano', 'micro', 'small', 'medium', 'large', 'xl',
  'super', 'ultra', 'lite', 'air', 'max', 'plus', 'turbo', 'fast',
]);

function normalizeLine(line: string): string {
  const parts = line.split('-').filter(Boolean);
  while (parts.length > 1 && TIER_SUFFIX.has(parts[parts.length - 1])) parts.pop();
  return parts.join('-');
}

/**
 * A model's version line: the base name (before any `:size`) with its first numeric run pulled
 * out and trailing tier suffixes stripped. e.g. `deepseek-v4-pro` → line `deepseek-v`, version [4];
 * `kimi-k2.7-code` → line `kimi-k-code`, [2,7] (type suffix kept). Null when the base has no number.
 */
function versionLine(name: string): { line: string; version: number[] } | null {
  const base = name.split(':')[0];
  const m = base.match(/\d+(?:\.\d+)*/);
  if (!m || m.index === undefined) return null;
  const rawLine = base.slice(0, m.index) + base.slice(m.index + m[0].length);
  return { line: normalizeLine(rawLine), version: m[0].split('.').map(Number) };
}

/**
 * Drop superseded versions within a line — keep only the newest (e.g. hide `kimi-k2.5` when
 * `kimi-k2.6` exists). Size variants (same line+version, e.g. `ministral-3:3b/8b`) and versionless
 * names (e.g. `gpt-oss`) are always kept. For Ollama lists only — cloud static lists have
 * non-version siblings (o1/o3, opus/sonnet) that must not collapse.
 */
export function latestModelVersions(models: string[]): string[] {
  const maxByLine = new Map<string, number[]>();
  for (const name of models) {
    const v = versionLine(name);
    if (!v) continue;
    const cur = maxByLine.get(v.line);
    if (!cur || cmpVersion(v.version, cur) > 0) maxByLine.set(v.line, v.version);
  }
  return models.filter((name) => {
    const v = versionLine(name);
    return !v || cmpVersion(v.version, maxByLine.get(v.line)!) === 0;
  });
}

/** Run an async fn over items with bounded concurrency, preserving order. */
async function mapLimit<T, R>(items: T[], limit: number, fn: (item: T) => Promise<R>): Promise<R[]> {
  const results = new Array<R>(items.length);
  let next = 0;
  const worker = async () => {
    while (next < items.length) {
      const i = next++;
      results[i] = await fn(items[i]);
    }
  };
  await Promise.all(Array.from({ length: Math.min(limit, items.length) }, worker));
  return results;
}

/** Capabilities for one model via /api/show (the authoritative source). Best-effort. */
async function ollamaCapabilities(
  base: string,
  headers: Record<string, string>,
  model: string,
): Promise<string[] | undefined> {
  try {
    const res = await tauriFetch(`${base}/api/show`, {
      method: 'POST',
      headers: { ...headers, 'Content-Type': 'application/json' },
      body: JSON.stringify({ model }),
    });
    if (!res.ok) return undefined;
    const data = (await res.json()) as { capabilities?: string[] };
    return Array.isArray(data?.capabilities) ? data.capabilities : undefined;
  } catch {
    return undefined;
  }
}

/**
 * Models to offer for a configured provider. `provider` is the provider_configs id
 * ('ollama', 'openai', 'anthropic', …). Ollama is fetched live (using the config's own URL/key
 * so Ollama Cloud works) and filtered to models that support tool calling + vision + thinking —
 * capabilities the browser agent needs. Cloud providers return a known static list. Throws only
 * on a reachable-but-erroring Ollama.
 */
export async function listProviderModels(
  provider: string,
  opts: { url?: string | null; apiKey?: string | null } = {},
): Promise<string[]> {
  if (provider === 'ollama-local' || provider === 'ollama') {
    const base = (opts.url || OLLAMA_DEFAULT_URL).replace(/\/$/, '');
    const headers: Record<string, string> = { Origin: new URL(base).origin };
    if (opts.apiKey) headers['Authorization'] = `Bearer ${opts.apiKey}`;
    const res = await tauriFetch(`${base}/api/tags`, { headers });
    if (!res.ok) throw new Error(`Failed to fetch Ollama models: ${res.status}`);

    const tagged = parseOllamaTags(await res.json());
    // /api/tags includes capabilities for local installs but NOT for the Ollama Cloud catalog,
    // so resolve the missing ones via /api/show — in parallel (the cloud catalog is large; doing
    // this sequentially never finishes). ponytail: per-switch fetch, add a cache if it's slow.
    const resolved = await mapLimit(tagged, 8, async (m) => ({
      name: m.name,
      caps: m.capabilities ?? (await ollamaCapabilities(base, headers, m.name)),
    }));
    const capable = resolved.filter((m) => hasRequiredCaps(m.caps)).map((m) => m.name);
    return latestModelVersions(capable); // hide superseded versions (e.g. kimi-k2.5 when 2.6 exists)
  }
  return STATIC_MODELS[provider] ?? [];
}

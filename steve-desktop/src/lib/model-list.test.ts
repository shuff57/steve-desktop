import { describe, it, expect, vi, beforeEach } from 'vitest';

const { mockFetch } = vi.hoisted(() => ({ mockFetch: vi.fn() }));
vi.mock('@tauri-apps/plugin-http', () => ({ fetch: mockFetch }));

import { parseOllamaTags, listProviderModels, groupModels, ollamaTier, hasRequiredCaps, latestModelVersions } from './model-list';

describe('parseOllamaTags', () => {
  it('parses name (or model) + capabilities, ignoring malformed entries', () => {
    const parsed = parseOllamaTags({
      models: [
        { name: 'qwen3:8b', capabilities: ['tools', 'vision', 'thinking'] },
        { model: 'llama3:8b' },
        {},
        { name: '' },
      ],
    });
    expect(parsed.map((m) => m.name)).toEqual(['qwen3:8b', 'llama3:8b']);
    expect(parsed[0].capabilities).toEqual(['tools', 'vision', 'thinking']);
  });
  it('returns [] for a shapeless response', () => {
    expect(parseOllamaTags(null)).toEqual([]);
    expect(parseOllamaTags({})).toEqual([]);
  });
});

describe('hasRequiredCaps', () => {
  it('requires tool calling (vision/thinking not required — the model never sees images)', () => {
    expect(hasRequiredCaps(['tools'])).toBe(true);
    expect(hasRequiredCaps(['completion', 'tools', 'thinking'])).toBe(true);
    expect(hasRequiredCaps(['vision'])).toBe(false);
    expect(hasRequiredCaps(['completion'])).toBe(false);
    expect(hasRequiredCaps(undefined)).toBe(false);
  });
});

describe('latestModelVersions', () => {
  it('drops superseded versions within a line', () => {
    expect(latestModelVersions(['kimi-k2.5', 'kimi-k2.6', 'kimi-k2.7-code'])).toEqual(['kimi-k2.6', 'kimi-k2.7-code']);
    expect(latestModelVersions(['glm-5', 'glm-5.1', 'glm-5.2'])).toEqual(['glm-5.2']);
    expect(latestModelVersions(['minimax-m2.7', 'minimax-m3'])).toEqual(['minimax-m3']);
  });
  it('supersedes across tier suffixes: deepseek-v4-pro/flash drop v3.2; type suffixes stay distinct', () => {
    expect(latestModelVersions(['deepseek-v3.2', 'deepseek-v4-pro', 'deepseek-v4-flash'])).toEqual([
      'deepseek-v4-pro',
      'deepseek-v4-flash',
    ]);
    // nano/super/ultra are the same generation (v3) → all kept
    expect(latestModelVersions(['nemotron-3-nano', 'nemotron-3-super', 'nemotron-3-ultra'])).toEqual([
      'nemotron-3-nano',
      'nemotron-3-super',
      'nemotron-3-ultra',
    ]);
    // 'coder' is a type, not a tier → stays separate from qwen3.5
    expect(latestModelVersions(['qwen3-coder', 'qwen3.5'])).toEqual(['qwen3-coder', 'qwen3.5']);
  });
  it('keeps size variants and versionless names', () => {
    expect(latestModelVersions(['ministral-3:3b', 'ministral-3:8b', 'ministral-3:14b'])).toEqual([
      'ministral-3:3b',
      'ministral-3:8b',
      'ministral-3:14b',
    ]);
    expect(latestModelVersions(['gpt-oss:120b', 'gpt-oss:20b'])).toEqual(['gpt-oss:120b', 'gpt-oss:20b']);
  });
});

describe('listProviderModels', () => {
  beforeEach(() => vi.clearAllMocks());

  it('keeps only tool-capable Ollama models (caps from /api/tags)', async () => {
    mockFetch.mockResolvedValue({
      ok: true,
      json: async () => ({
        models: [
          { name: 'qwen3:8b', capabilities: ['tools', 'thinking'] },
          { name: 'llama3:8b', capabilities: ['tools'] },
          { name: 'gemma:2b', capabilities: ['completion', 'vision'] }, // no tools → excluded
        ],
      }),
    });
    expect(await listProviderModels('ollama-local')).toEqual(['qwen3:8b', 'llama3:8b']);
    expect(mockFetch.mock.calls[0][0]).toBe('http://localhost:11434/api/tags');
    expect(mockFetch).toHaveBeenCalledTimes(1); // caps present → no /api/show needed
  });

  it('falls back to /api/show for capabilities when /api/tags omits them', async () => {
    mockFetch.mockImplementation(async (url: string, init?: { body: string }) => {
      if (url.endsWith('/api/tags')) {
        return { ok: true, json: async () => ({ models: [{ name: 'qwen3:8b' }, { name: 'phi3' }] }) };
      }
      const model = JSON.parse(init!.body).model;
      const capabilities = model === 'qwen3:8b' ? ['tools', 'thinking'] : ['completion'];
      return { ok: true, json: async () => ({ capabilities }) };
    });
    expect(await listProviderModels('ollama-local')).toEqual(['qwen3:8b']);
  });

  it('returns the known static list for cloud providers without any fetch', async () => {
    expect(await listProviderModels('anthropic')).toContain('claude-opus-4-8');
    expect(await listProviderModels('openai')).toContain('gpt-4o');
    expect(await listProviderModels('mystery')).toEqual([]);
    expect(mockFetch).not.toHaveBeenCalled();
  });

  it('throws when Ollama is reachable but errors', async () => {
    mockFetch.mockResolvedValue({ ok: false, status: 500 });
    await expect(listProviderModels('ollama-local')).rejects.toThrow(/500/);
  });
});

describe('groupModels', () => {
  it('buckets known cloud models into Best/Better/Good in that order', () => {
    const groups = groupModels(['claude-haiku-4-5-20251001', 'claude-opus-4-8', 'claude-sonnet-4-6']);
    expect(groups.map((g) => g.label)).toEqual(['Best', 'Better', 'Good']);
    expect(groups[0].models).toEqual(['claude-opus-4-8']);
    expect(groups[2].models).toEqual(['claude-haiku-4-5-20251001']);
  });

  it('renders unknown-only lists flat (no group label) — e.g. Ollama installs', () => {
    const groups = groupModels(['llama3', 'qwen2.5']);
    expect(groups).toEqual([{ label: '', models: ['llama3', 'qwen2.5'] }]);
  });

  it('puts unknown models alongside tiered ones under "Other"', () => {
    const groups = groupModels(['gpt-4o', 'my-custom-model']);
    expect(groups.map((g) => g.label)).toEqual(['Better', 'Other']);
    expect(groups[1].models).toEqual(['my-custom-model']);
  });

  it('sorts models alphabetically within each tier', () => {
    const groups = groupModels(['minimax-m3', 'kimi-k2.6', 'glm-5.2']); // all Best, unsorted
    expect(groups).toHaveLength(1);
    expect(groups[0].label).toBe('Best');
    expect(groups[0].models).toEqual(['glm-5.2', 'kimi-k2.6', 'minimax-m3']);
  });

  it('tiers Ollama models by parameter size', () => {
    const groups = groupModels(['llama3:8b', 'qwen2.5:32b', 'llama3.1:70b', 'mixtral:8x7b']);
    const by = Object.fromEntries(groups.map((g) => [g.label, g.models]));
    expect(by.Good).toEqual(['llama3:8b']);
    expect(by.Better).toEqual(['qwen2.5:32b']);
    expect(by.Best).toEqual(['llama3.1:70b', 'mixtral:8x7b']); // 70b and 8x7b=56
  });
});

describe('ollamaTier', () => {
  it('uses size alone for unknown families', () => {
    expect(ollamaTier('gemma2:2b')).toBe('good');
    expect(ollamaTier('llama3:8b')).toBe('good');
    expect(ollamaTier('mistral-small:22b')).toBe('better');
    expect(ollamaTier('qwen2.5:32b')).toBe('better');
    expect(ollamaTier('llama3.1:70b')).toBe('best');
    expect(ollamaTier('mixtral:8x7b')).toBe('best'); // MoE → 8*7=56
  });
  it('ranks known no-size families by reputation (so frontier models are not "Other")', () => {
    expect(ollamaTier('kimi-k2.6')).toBe('best');
    expect(ollamaTier('minimax-m3')).toBe('best');
    expect(ollamaTier('glm-5.2')).toBe('best');
    expect(ollamaTier('gemini-3-flash-preview')).toBe('better');
    expect(ollamaTier('minimax-m2.7')).toBe('better');
  });
  it('treats family as a ceiling: size demotes but never promotes', () => {
    // gpt-oss is capped below Best even at 120B
    expect(ollamaTier('gpt-oss:120b')).toBe('better');
    // a small variant of a top family is demoted by its size
    expect(ollamaTier('qwen3.5:8b')).toBe('good');
    expect(ollamaTier('ministral-3:3b')).toBe('good');
    expect(ollamaTier('ministral-3:14b')).toBe('better');
  });
  it('falls back to :cloud → best, and leaves truly unknown names untierable', () => {
    expect(ollamaTier('some-model:cloud')).toBe('best');
    expect(ollamaTier('phi3')).toBeUndefined();
  });
});

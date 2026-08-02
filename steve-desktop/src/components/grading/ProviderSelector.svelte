<script lang="ts">
  /**
   * ProviderSelector - Compact inline provider + model dropdowns.
   * Static list for STEVE.
   */

  import { onMount } from 'svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { getSetting, setSetting } from '../../lib/db';
  import { OLLAMA_CLOUD_TOOLS } from '../../lib/model-list';

  interface Props {
    provider?: string;
    model?: string;
    disabled?: boolean;
    onProviderChange?: (provider: string) => void;
    onModelChange?: (model: string) => void;
  }

  let {
    provider = $bindable(''),
    model = $bindable(''),
    disabled = false,
    onProviderChange,
    onModelChange,
  }: Props = $props();

  // ── Static Providers & Models ──────────────────────────────────────

  // Each entry is a CLI engine, not an HTTP provider: anthropic runs through the claude CLI,
  // opencode fronts Ollama Cloud. Each model is [value, label]: value feeds the CLI's --model/-m
  // flag (a stale value makes the CLI exit "model may not exist"), label is what's shown. Claude
  // uses BARE CLI aliases — the CLI resolves each to the current model in that tier, so no version
  // lives here to go stale. Default = `opus`, first in the list. opencode uses the tool-calling
  // Ollama Cloud ids from model-list.ts.
  type ModelOpt = readonly [value: string, label: string];
  const PROVIDERS: ReadonlyArray<{ id: string; label: string; models: ReadonlyArray<ModelOpt> }> = [
    {
      id: 'opencode',
      label: 'OpenCode',
      models: OLLAMA_CLOUD_TOOLS.map((id) => [id, id.replace(/^ollama-cloud\//, '')] as const),
    },
    {
      id: 'anthropic',
      label: 'Claude CLI',
      models: [
        ['opus', 'Opus'],
        ['fable', 'Fable'],
        ['sonnet', 'Sonnet'],
        ['haiku', 'Haiku'],
      ],
    },
  ];

  // ── Reactive state ──────────────────────────────────────────────────

  // OpenCode's models only apply once a key is configured for that provider — re-checked whenever
  // OpenCode is the engine, so a key added in Settings shows up on returning here.
  let hasOllamaCloud = $state(false);
  $effect(() => {
    if (provider !== 'opencode') return;
    invoke<boolean>('opencode_has_credential', { provider: 'ollama-cloud' })
      .then((v) => (hasOllamaCloud = v))
      .catch(() => (hasOllamaCloud = false));
  });

  const current = $derived(PROVIDERS.find((x) => x.id === provider));
  const models = $derived(
    current ? (current.id === 'opencode' && !hasOllamaCloud ? [] : current.models) : [],
  );
  const modelValues = $derived(models.map(([v]) => v));

  // ── Lifecycle ───────────────────────────────────────────────────────

  // Remember the last engine + model across sessions. Restore before persisting so the saved
  // choice isn't clobbered by the defaults that run on first paint.
  let restored = $state(false);
  onMount(async () => {
    const [p, m] = await Promise.all([getSetting('agent_provider'), getSetting('agent_model')]);
    if (p && PROVIDERS.some((x) => x.id === p)) provider = p;
    if (m) model = m;
    restored = true;
  });

  $effect(() => {
    if (restored && provider) setSetting('agent_provider', provider);
  });
  $effect(() => {
    if (restored && model) setSetting('agent_model', model);
  });

  $effect(() => {
    // Coerce unknown ids too: older sessions stored retired ids (ollama, openai, ...).
    if (!provider || !PROVIDERS.some((x) => x.id === provider)) {
      provider = PROVIDERS[0].id;
    }
  });

  // Keep the selected model valid — but only against a populated list, so a restored model isn't
  // wiped while the OpenCode key check is still resolving (models briefly empty).
  $effect(() => {
    if (models.length && !modelValues.includes(model)) model = models[0][0];
  });

  // ── Event handlers ────────────────────────────────────────────────

  function handleProviderChange(e: Event) {
    const value = (e.target as HTMLSelectElement).value;
    provider = value;
    onProviderChange?.(value);
  }

  function handleModelChange(e: Event) {
    const value = (e.target as HTMLSelectElement | HTMLInputElement).value;
    model = value;
    onModelChange?.(value);
  }
</script>

<section class="provider-selector">
  <div class="selector-row">
    <select
      class="provider-select"
      value={provider}
      onchange={handleProviderChange}
      disabled={disabled}
    >
      {#each PROVIDERS as p}
        <option value={p.id}>{p.label}</option>
      {/each}
    </select>

    <select
      class="model-select"
      value={model}
      onchange={handleModelChange}
      disabled={disabled || models.length === 0}
    >
      {#if models.length === 0}
        <option value="" disabled selected>No models available</option>
      {:else}
        {#each models as [v, l]}
          <option value={v}>{l}</option>
        {/each}
      {/if}
    </select>
  </div>
</section>

<style>
  .provider-selector {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
  }

  .selector-row {
    display: flex;
    gap: var(--spacing-2);
  }

  .provider-select,
  .model-select {
    flex: 1;
    background-color: var(--color-bg-main);
    border: 1px solid var(--color-border);
    color: var(--color-text-primary);
    border-radius: var(--radius-md);
    padding: var(--spacing-2);
    font-family: var(--font-body);
    font-size: 0.85rem;
  }

  .provider-select:focus,
  .model-select:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px var(--color-primary-bg);
  }

  .provider-select:disabled,
  .model-select:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
</style>

<script lang="ts">
  /**
   * ProviderSelector - Compact inline provider + model dropdowns, driven by the providers the
   * user actually configured/logged into (Settings → Providers). Defaults to the active one;
   * model options come live from Ollama or a known cloud list (model-list.ts).
   */
  import { onMount } from 'svelte';
  import { getActiveProvider, listProviderConfigs, type ProviderConfig } from '../../lib/db';
  import { listProviderModels, providerLabel, groupModels } from '../../lib/model-list';

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

  let providers: ProviderConfig[] = $state([]);
  let models: string[] = $state([]);

  // Model choices always include the currently-selected model, so a configured model that
  // isn't in the fetched/static list still shows as selected.
  const modelChoices = $derived(
    model && !models.includes(model) ? [model, ...models] : models,
  );
  const modelGroups = $derived(groupModels(modelChoices));

  onMount(async () => {
    try {
      providers = await listProviderConfigs();
      if (!provider) {
        const active = (await getActiveProvider()) ?? providers[0] ?? null;
        if (active) {
          provider = active.id;
          if (!model && active.model) model = active.model;
        }
      }
    } catch {
      providers = [];
    }
  });

  let prevProvider = '';
  $effect(() => {
    const p = provider;
    if (!p || p === prevProvider) return;
    prevProvider = p;
    const cfg = providers.find((x) => x.id === p);
    if (cfg?.model && !model) model = cfg.model;
    listProviderModels(p, { url: cfg?.api_url, apiKey: cfg?.api_key })
      .then((m) => {
        models = m;
        if (!model && m.length > 0) model = m[0];
      })
      .catch(() => { models = []; });
  });

  function handleProviderChange(e: Event) {
    const value = (e.target as HTMLSelectElement).value;
    provider = value;
    model = ''; // let the effect repopulate from the new provider's config/list
    onProviderChange?.(value);
  }

  function handleModelChange(e: Event) {
    const value = (e.target as HTMLSelectElement).value;
    model = value;
    onModelChange?.(value);
  }
</script>

<section class="provider-selector">
  <div class="selector-row">
    {#if providers.length === 0}
      <span class="provider-empty">No AI provider configured — add one in Settings → Providers.</span>
    {:else}
      <select
        class="provider-select"
        value={provider}
        onchange={handleProviderChange}
        disabled={disabled}
      >
        {#each providers as p}
          <option value={p.id}>{providerLabel(p.id)}{p.is_active ? ' ✓' : ''}</option>
        {/each}
      </select>

      <select
        class="model-select"
        value={model}
        onchange={handleModelChange}
        disabled={disabled || modelChoices.length === 0}
      >
        {#if modelChoices.length === 0}
          <option value="" disabled selected>No models available</option>
        {:else}
          {#each modelGroups as g}
            {#if g.label}
              <optgroup label={g.label}>
                {#each g.models as m}<option value={m}>{m}</option>{/each}
              </optgroup>
            {:else}
              {#each g.models as m}<option value={m}>{m}</option>{/each}
            {/if}
          {/each}
        {/if}
      </select>
    {/if}
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

  .provider-empty {
    font-size: 0.8rem;
    color: var(--color-text-secondary, var(--text-secondary));
    line-height: 1.4;
  }
</style>

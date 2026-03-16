<script lang="ts">
  /**
   * ProviderSelector - Compact inline provider + model dropdowns.
   * Static list for STEVE.
   */

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

  const PROVIDERS = [
    { id: 'ollama', label: 'Ollama (Local)', models: ['llama3.2', 'qwen2.5', 'llama3.1', 'phi4', 'mistral'] },
    { id: 'openai', label: 'OpenAI', models: ['gpt-4o', 'gpt-4o-mini', 'o1', 'o3-mini'] },
    { id: 'anthropic', label: 'Anthropic', models: ['claude-3-5-sonnet-latest', 'claude-3-5-haiku-latest', 'claude-3-opus-latest'] },
    { id: 'google-gemini', label: 'Google Gemini', models: ['gemini-2.5-pro', 'gemini-2.5-flash'] },
  ];

  // ── Reactive state ──────────────────────────────────────────────────

  let models: string[] = $state([]);

  // ── Lifecycle ───────────────────────────────────────────────────────

  $effect(() => {
    if (!provider) {
      provider = PROVIDERS[0].id;
    }
  });

  let prevProvider = '';
  $effect(() => {
    const p = provider;
    if (p && p !== prevProvider) {
      prevProvider = p;
      const found = PROVIDERS.find((x) => x.id === p);
      models = found ? found.models : [];
      if (!model || !models.includes(model)) {
        model = models[0] || '';
      }
    }
  });

  // ── Event handlers ────────────────────────────────────────────────

  function handleProviderChange(e: Event) {
    const value = (e.target as HTMLSelectElement).value;
    provider = value;
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
        {#each models as m}
          <option value={m}>{m}</option>
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

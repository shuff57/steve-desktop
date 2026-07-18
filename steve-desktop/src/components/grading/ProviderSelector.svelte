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

  // Each entry is a CLI engine, not an HTTP provider: anthropic runs through the claude
  // CLI, opencode fronts ollama.com cloud models. Model ids feed the CLI's --model/-m
  // flag, so a stale id is not a cosmetic problem: the CLI exits with "model may not
  // exist or you may not have access". Claude ids were verified against the installed
  // CLI; opencode models are free-form (typed bare, `ollama/` is prefixed on send) with
  // the datalist offering known ollama.com cloud ids.
  const PROVIDERS = [
    {
      id: 'opencode',
      label: 'OpenCode (Ollama Cloud)',
      freeform: true,
      models: ['kimi-k2.6:cloud', 'glm-5.1:cloud', 'qwen3-coder-next:cloud', 'deepseek-v4-pro:cloud', 'minimax-m2.7:cloud'],
    },
    {
      id: 'anthropic',
      label: 'Claude CLI',
      freeform: false,
      models: ['claude-opus-4-8', 'claude-sonnet-5', 'claude-haiku-4-5'],
    },
  ];

  // ── Reactive state ──────────────────────────────────────────────────

  let models: string[] = $state([]);
  let freeform = $state(false);

  // ── Lifecycle ───────────────────────────────────────────────────────

  $effect(() => {
    // Coerce unknown ids too: older sessions stored retired ids (ollama, openai, ...).
    if (!provider || !PROVIDERS.some((x) => x.id === provider)) {
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
      freeform = found?.freeform ?? false;
      if (!model || (!freeform && !models.includes(model))) {
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

    {#if freeform}
      <input
        class="model-select"
        type="text"
        list="ollama-cloud-models"
        value={model}
        oninput={handleModelChange}
        placeholder="e.g. kimi-k2.6:cloud"
        disabled={disabled}
      />
      <datalist id="ollama-cloud-models">
        {#each models as m}
          <option value={m}></option>
        {/each}
      </datalist>
    {:else}
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
</style>

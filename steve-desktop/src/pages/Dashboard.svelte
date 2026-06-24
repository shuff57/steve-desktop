<script lang="ts">
  import { onMount } from 'svelte';
  import { MousePointerClick, Brain, Settings } from 'lucide-svelte';
  import { listProviderConfigs } from '../lib/db';

  let { onnavigate = (_page: string) => {} }: {
    onnavigate?: (page: string) => void;
  } = $props();

  let providerStatus = $state('checking...');

  onMount(async () => {
    // Check provider status
    const providers = await listProviderConfigs();
    const activeProvider = providers.find((p: any) => p.is_active === 1);
    if (activeProvider) {
      providerStatus = `connected (${activeProvider.id})`;
    } else {
      providerStatus = 'not configured';
    }
  });
</script>

<div class="dashboard">
  <header>
    <h1>S.T.E.V.E Desktop</h1>
    <span class="version">v0.1.0</span>
  </header>

  <section class="health-indicators">
    <div class="indicator provider" class:ok={providerStatus.startsWith('connected')} class:warn={providerStatus === 'checking...'}>
      <span class="status-dot"></span>
      <span>Provider: {providerStatus}</span>
    </div>
  </section>

  <section class="quick-actions">
    <h2>Get Started</h2>
    <div class="actions-grid">
      <button class="action-card primary" onclick={() => onnavigate('browser')}>
        <span class="action-icon"><MousePointerClick size={28} /></span>
        <span class="action-title">Start a Task</span>
        <span class="action-desc">Open the browser and let STEVE work</span>
      </button>
      <button class="action-card" onclick={() => onnavigate('skills')}>
        <span class="action-icon"><Brain size={28} /></span>
        <span class="action-title">Manage Skills</span>
        <span class="action-desc">Review and configure skills</span>
      </button>
      <button class="action-card" onclick={() => onnavigate('settings')}>
        <span class="action-icon"><Settings size={28} /></span>
        <span class="action-title">Configure Settings</span>
        <span class="action-desc">Set up your AI provider and preferences</span>
      </button>
    </div>
  </section>
</div>

<style>
  .dashboard {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    font-family: var(--font-body);
  }

  header {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--color-border);
    padding-bottom: 1rem;
  }

  h1 {
    margin: 0;
    color: var(--color-text-primary);
    font-size: 2rem;
  }

  .version {
    color: var(--color-text-muted);
    font-size: 0.9rem;
    font-family: var(--font-mono);
  }

  /* Health Indicators */
  .health-indicators {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    background: var(--color-bg-card);
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
  }

  .indicator {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--color-error);
    box-shadow: 0 0 0 2px var(--color-error-bg);
    transition: background-color 0.3s ease;
  }

  .indicator.ok .status-dot {
    background: var(--color-success);
    box-shadow: 0 0 0 2px var(--color-success-bg);
  }

  .indicator.warn .status-dot {
    background: var(--color-warning);
    box-shadow: 0 0 0 2px var(--color-warning-bg);
  }

  /* Quick Actions */
  .quick-actions {
    margin-bottom: 2rem;
  }

  .quick-actions h2 {
    color: var(--color-text-primary);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
  }

  .actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
  }

  .action-card {
    background: var(--color-bg-card);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    /* <button> doesn't inherit color — set it so .action-title (color: inherit) isn't UA-default black */
    color: var(--color-text-primary);
    cursor: pointer;
    text-align: left;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .action-card:hover {
    background: var(--color-bg-card-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    border-color: var(--color-primary);
  }

  .action-card.primary {
    /* primary-hover bumps the blue/text contrast to WCAG AA in both themes. */
    background: var(--color-primary-hover);
    border-color: var(--color-primary-hover);
    color: var(--color-primary-text);
  }

  .action-card.primary .action-desc {
    color: var(--color-primary-text);
    opacity: 0.92;
  }

  .action-card.primary:hover {
    filter: brightness(1.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
  }

  .action-icon {
    font-size: 1.75rem;
    line-height: 1;
  }

  .action-title {
    font-size: 1rem;
    font-weight: 600;
    color: inherit;
  }

  .action-desc {
    font-size: 0.82rem;
    color: var(--text-secondary);
    line-height: 1.4;
  }
</style>

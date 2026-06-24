<script lang="ts">
  import ThemeSettings from './ThemeSettings.svelte';
  import ProviderSettings from './ProviderSettings.svelte';
  import CredentialSettings from './CredentialSettings.svelte';
</script>

<div class="settings-page">
  <header class="mb-6">
    <h1>Settings</h1>
    <p class="text-muted">Configure AI providers and application preferences</p>
  </header>

  <ThemeSettings />
  <ProviderSettings />
  <CredentialSettings />
</div>

<style>
  .settings-page {
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
  }

  h1 {
    font-size: 2rem;
    margin: 0;
    color: var(--text-primary);
  }
</style>

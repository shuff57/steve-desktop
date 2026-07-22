<script lang="ts">
  import { onMount } from 'svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { saveProviderConfig, setSetting } from '../lib/db';
  import {
    fetchAvailableModels,
    startGitHubDeviceFlow,
    startChatGPTDeviceFlow,
    startAnthropicDeviceFlow,
    startGoogleDeviceFlow
  } from '../lib/oauth';
  import type { DeviceFlowResult } from '../lib/oauth';
  import { fetch as tauriFetch } from '@tauri-apps/plugin-http';

  let { oncomplete }: { oncomplete?: () => void } = $props();

  let currentStep = $state(1);
  let loading = $state(false);
  let error = $state('');

  // Claude sign-in on the welcome step — the friendly no-terminal flow (same Rust claude_login_*
  // commands as Settings). Most users only need this; the provider/model steps below are optional.
  let claudeStatus: { loggedIn?: boolean; email?: string; subscriptionType?: string } = $state({});
  let claudeLoginUrl: string | null = $state(null);
  let claudeCode = $state('');
  let claudeBusy = $state(false);
  let claudeError = $state('');

  onMount(refreshClaudeStatus);

  async function refreshClaudeStatus() {
    try {
      claudeStatus = await invoke('claude_auth_status');
    } catch {
      claudeStatus = { loggedIn: false };
    }
  }

  async function startClaudeLogin() {
    claudeError = '';
    claudeBusy = true;
    try {
      claudeLoginUrl = await invoke<string>('claude_login_start');
    } catch (e) {
      claudeError = e instanceof Error ? e.message : String(e);
      claudeLoginUrl = null;
    } finally {
      claudeBusy = false;
    }
  }

  async function submitClaudeCode() {
    const code = claudeCode.trim();
    if (!code) return;
    claudeBusy = true;
    claudeError = '';
    try {
      claudeStatus = await invoke('claude_login_submit', { code });
      if (claudeStatus?.loggedIn) {
        claudeCode = '';
        claudeLoginUrl = null;
      } else {
        claudeError = 'Sign-in did not complete — double-check the code and try again.';
      }
    } catch (e) {
      claudeError = e instanceof Error ? e.message : String(e);
    } finally {
      claudeBusy = false;
    }
  }

  async function cancelClaudeLogin() {
    await invoke('claude_login_cancel').catch(() => {});
    claudeLoginUrl = null;
    claudeCode = '';
    claudeError = '';
  }

  // OAuth State
  let fetchedModels = $state<Record<string, Array<{id: string, name: string}>>>({});
  let fetchingModels = $state<Record<string, boolean>>({});
  let modelFetchErrors = $state<Record<string, string>>({});
  let oauthSignedIn = $state<Record<string, boolean>>({});

  // Device flow state
  let deviceFlows = $state<Record<string, DeviceFlowResult>>({});
  let authLoading = $state<Record<string, boolean>>({});
  let authErrors = $state<Record<string, string>>({});

  // All three use API-key auth (oauth disabled — the legal, no-OAuth-client path).
  let providers = $state([
    {
      id: 'anthropic',
      name: 'Anthropic (Claude)',
      enabled: false,
      apiKey: '',
      apiUrl: '',
      model: '',
      keyUrl: 'https://console.anthropic.com/settings/keys',
      placeholderKey: 'sk-ant-...',
      placeholderUrl: '',
      oauth: false,
      useApiKey: true
    },
    {
      id: 'openai',
      name: 'ChatGPT (OpenAI)',
      enabled: false,
      apiKey: '',
      apiUrl: '',
      model: '',
      keyUrl: 'https://platform.openai.com/api-keys',
      placeholderKey: 'sk-...',
      placeholderUrl: '',
      oauth: false,
      useApiKey: true
    },
    {
      id: 'ollama',
      name: 'Ollama Cloud',
      enabled: false,
      apiKey: '',
      apiUrl: 'https://ollama.com',
      model: '',
      keyUrl: 'https://ollama.com/settings/keys',
      placeholderKey: 'Required for Ollama Cloud',
      placeholderUrl: 'https://ollama.com',
      oauth: false,
      useApiKey: true
    }
  ]);

  let ollamaLocalDetected = $state(false);

  async function detectOllamaLocal() {
    try {
      const response = await tauriFetch('http://localhost:11434/api/tags', {
        headers: { 'Origin': 'http://localhost:11434' }
      });
      if (response.ok) {
        ollamaLocalDetected = true;
        const ollamaProvider = providers.find(p => p.id === 'ollama');
        if (ollamaProvider) {
          ollamaProvider.enabled = true;
          ollamaProvider.apiUrl = 'http://localhost:11434';
        }
      }
    } catch {
      ollamaLocalDetected = false;
    }
  }

  async function handleOAuthSignIn(providerId: string) {
    authErrors[providerId] = '';
    authLoading[providerId] = true;

    try {
      if (providerId === 'github-models') {
        const flow = await startGitHubDeviceFlow();
        handleDeviceFlow(providerId, flow);
      } else if (providerId === 'openai') {
        const flow = await startChatGPTDeviceFlow();
        handleDeviceFlow(providerId, flow);
      } else if (providerId === 'google-gemini') {
        const flow = await startGoogleDeviceFlow();
        handleDeviceFlow(providerId, flow);
      } else if (providerId === 'anthropic') {
        const flow = await startAnthropicDeviceFlow();
        handleDeviceFlow(providerId, flow);
      }
    } catch (err: any) {
      authErrors[providerId] = err instanceof Error ? err.message : String(err);
    } finally {
      authLoading[providerId] = false;
    }
  }

  async function handleDeviceFlow(providerId: string, flow: DeviceFlowResult) {
    deviceFlows[providerId] = flow;

    try {
      const result = await flow.poll();
      if (result.success) {
        oauthSignedIn[providerId] = true;
        fetchModels(providerId);
        delete deviceFlows[providerId];
      } else if (result.error !== 'Cancelled') {
        authErrors[providerId] = result.error || 'Authentication failed';
      }
    } catch (err: any) {
      if (deviceFlows[providerId]) {
        authErrors[providerId] = err instanceof Error ? err.message : String(err);
      }
    } finally {
      if (deviceFlows[providerId]) {
        delete deviceFlows[providerId];
      }
    }
  }

  function cancelAuth(providerId: string) {
    if (deviceFlows[providerId]) {
      deviceFlows[providerId].cancel();
      delete deviceFlows[providerId];
    }
    authLoading[providerId] = false;
    authErrors[providerId] = '';
  }

  async function fetchModels(providerId: string) {
    fetchingModels[providerId] = true;
    modelFetchErrors[providerId] = '';

    try {
      if (providerId === 'ollama') {
        const provider = providers.find(p => p.id === providerId);
        if (!provider || !provider.apiUrl) {
          throw new Error('Ollama API URL not configured');
        }

        const baseUrl = provider.apiUrl.replace(/\/$/, '');
        const url = `${baseUrl}/api/tags`;
        const headers: Record<string, string> = {
          'Origin': new URL(baseUrl).origin,
        };

        if (provider.apiKey) {
          headers['Authorization'] = `Bearer ${provider.apiKey}`;
        }

        const response = await tauriFetch(url, { headers });
        if (!response.ok) {
          throw new Error(`Failed to fetch models: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        const models = data.models?.map((m: any) => m.name) || [];

        fetchedModels[providerId] = models.map((m: string) => ({ id: m, name: m }));

        if (!provider.model && models.length > 0) {
          provider.model = models[0];
        }
      } else {
        const providerKeyMap: Record<string, "github" | "openai" | "anthropic" | "google"> = {
          'github-models': 'github',
          'openai': 'openai',
          'anthropic': 'anthropic',
          'google-gemini': 'google',
        };
        const oauthProvider = providerKeyMap[providerId];
        if (!oauthProvider) throw new Error('Unknown provider');
        const models = await fetchAvailableModels(oauthProvider);
        fetchedModels[providerId] = models.map((m: string) => ({ id: m, name: m }));

        const provider = providers.find(p => p.id === providerId);
        if (provider && !provider.model && models.length > 0) {
          provider.model = models[0];
        }
      }
    } catch (err: any) {
      const msg = err.message || 'Failed to fetch models.';
      if (providerId === 'ollama' && (msg.includes('connect') || msg.includes('refused') || msg.includes('network') || msg.includes('fetch'))) {
        modelFetchErrors[providerId] = 'Could not connect to Ollama. Make sure Ollama is running.';
      } else {
        modelFetchErrors[providerId] = msg;
      }
    } finally {
      fetchingModels[providerId] = false;
    }
  }

  function toggleAuthMethod(provider: any) {
    provider.useApiKey = !provider.useApiKey;
  }

  function nextStep() {
    error = '';

    if (currentStep === 2) {
      const enabledProviders = providers.filter(p => p.enabled);
      if (enabledProviders.length === 0) {
        error = 'Please select at least one provider.';
        return;
      }

      for (const p of enabledProviders) {
        if (p.id === 'ollama') {
          if (!p.apiUrl) { error = 'Ollama requires an API URL.'; return; }
        }
        // @ts-ignore
        if (p.oauth) {
           // @ts-ignore
           if (p.useApiKey) {
             if (!p.apiKey) { error = `${p.name} requires an API Key.`; return; }
           } else {
             if (!oauthSignedIn[p.id]) { error = `Please sign in to ${p.name} or use an API Key.`; return; }
           }
        } else if (['openai'].includes(p.id) && !p.apiKey) {
           error = `${p.name} requires an API Key.`; return;
        }
      }

      const ollamaProvider = enabledProviders.find(p => p.id === 'ollama');
      if (ollamaProvider && !fetchedModels['ollama']?.length && !fetchingModels['ollama']) {
        fetchModels('ollama');
      }
    }

    if (currentStep < 4) {
      currentStep++;
    }
  }

  function prevStep() {
    if (currentStep > 1) {
      currentStep--;
      error = '';
    }
  }

  async function saveAndComplete() {
    loading = true;
    try {
      // Signed in with Claude → ensure a runnable claude provider exists, even on the quick path
      // where the user skipped the provider/model steps.
      if (claudeStatus?.loggedIn) {
        await saveProviderConfig({ id: 'anthropic', api_url: '', api_key: '', model: 'claude-opus-4-8', is_active: 1 });
      }

      const enabledProviders = providers.filter(p => p.enabled);
      for (const provider of enabledProviders) {
        await saveProviderConfig({ id: provider.id, api_url: provider.apiUrl, api_key: provider.apiKey, model: provider.model, is_active: 1 });
      }

      await setSetting('setup_complete', 'true');
    } catch (e) {
      // Surface the failure instead of sending the user into a "no provider" state
      error = 'Failed to save provider: ' + (e instanceof Error ? e.message : String(e));
      loading = false;
      return;
    }
    oncomplete?.();
    loading = false;
  }
</script>

<div class="wizard-container">
  <div class="wizard-card">
    <div class="progress-bar">
      <div class="progress-step {currentStep >= 1 ? 'active' : ''}">1</div>
      <div class="line {currentStep >= 2 ? 'active' : ''}"></div>
      <div class="progress-step {currentStep >= 2 ? 'active' : ''}">2</div>
      <div class="line {currentStep >= 3 ? 'active' : ''}"></div>
      <div class="progress-step {currentStep >= 3 ? 'active' : ''}">3</div>
      <div class="line {currentStep >= 4 ? 'active' : ''}"></div>
      <div class="progress-step {currentStep >= 4 ? 'active' : ''}">4</div>
    </div>

    {#if currentStep === 1}
      <div class="step-content">
        <h1>Welcome to S.T.E.V.E!</h1>
        <p class="subtitle">Sign in with your Claude account to get started.</p>
        <p class="description">
          S.T.E.V.E helps you automate watching videos completely. The simplest way in is to sign in with your Claude account — no API key needed. You can add other providers afterward.
        </p>

        <div class="oauth-section">
          {#if claudeLoginUrl}
            <div class="device-flow-box">
              <p class="instructions">1. A browser window opened to sign in to Claude. If it didn't, <a href={claudeLoginUrl} target="_blank" rel="noopener noreferrer">open the sign-in page</a>.</p>
              <p class="instructions">2. After you approve, copy the code shown and paste it here:</p>
              <div class="flex-row">
                <input type="text" placeholder="Paste your sign-in code" bind:value={claudeCode} disabled={claudeBusy} style="flex:1; padding:0.5rem; border:1px solid var(--border-color); border-radius: var(--radius-md);" />
                <button class="btn-primary" style="margin:0;" disabled={claudeBusy || !claudeCode.trim()} onclick={submitClaudeCode}>{claudeBusy ? 'Signing in…' : 'Submit'}</button>
              </div>
              {#if claudeError}<div class="error-message" style="text-align:left; font-size:0.85rem;">{claudeError}</div>{/if}
              <button class="link-btn" onclick={cancelClaudeLogin}>Cancel</button>
            </div>
          {:else if claudeStatus?.loggedIn}
            <div class="signed-in-badge">✅ Signed in{claudeStatus.email ? ` as ${claudeStatus.email}` : ''}{claudeStatus.subscriptionType ? ` · ${claudeStatus.subscriptionType}` : ''}</div>
          {:else}
            {#if claudeError}<div class="error-message" style="font-size:0.85rem;">{claudeError}</div>{/if}
            <button class="btn-oauth" disabled={claudeBusy} onclick={startClaudeLogin}>{claudeBusy ? 'Opening browser…' : 'Sign in with Claude'}</button>
          {/if}
        </div>

        <div class="actions">
          <button class="btn-secondary" onclick={nextStep}>Add other providers</button>
          <button class="btn-primary" onclick={saveAndComplete} disabled={loading || !claudeStatus?.loggedIn}>
            {#if loading}Saving…{:else}Start using STEVE{/if}
          </button>
        </div>
      </div>
    {/if}

    {#if currentStep === 2}
      <div class="step-content">
        <h2>Select Providers</h2>
        <p class="subtitle">Enable and configure the AI providers you want to use.</p>
        
        <div class="providers-list">
          {#each providers as provider}
            <div class="provider-card {provider.enabled ? 'enabled' : ''}">
              <div class="card-header">
                <label class="checkbox-label">
                  <input type="checkbox" bind:checked={provider.enabled}>
                  <span class="provider-name">{provider.name}</span>
                </label>
                {#if provider.id === 'ollama_local'}
                  <button class="btn-sm" onclick={detectOllamaLocal}>
                    {#if ollamaLocalDetected}✅ Detected{:else}Auto-detect{/if}
                  </button>
                {/if}
              </div>

              {#if provider.enabled}
                <div class="card-body">
                  {#if provider.id === 'ollama'}
                    <div class="form-group">
                      <label for="ollama-url">API URL</label>
                      <input id="ollama-url" type="text" bind:value={provider.apiUrl} placeholder={provider.placeholderUrl}>
                    </div>
                    <div class="form-group">
                      <label for="ollama-key">API Key (Optional)</label>
                      <input id="ollama-key" type="password" bind:value={provider.apiKey} placeholder={provider.placeholderKey}>
                      <span class="hint">Only needed for cloud Ollama instances</span>
                    </div>
                  {:else if provider.oauth}
                    {#if provider.useApiKey}
                       <div class="form-group">
                        <label for="{provider.id}-key">API Key</label>
                        <input id="{provider.id}-key" type="password" bind:value={provider.apiKey} placeholder={provider.placeholderKey}>
                        <div class="flex-row">
                             {#if provider.keyUrl}
                               <a href={provider.keyUrl} target="_blank" rel="noopener noreferrer" class="help-link">Get API Key</a>
                             {/if}
                             <button class="link-btn" onclick={() => toggleAuthMethod(provider)}>Or sign in with {provider.name}</button>
                        </div>
                      </div>
                    {:else}
                       <div class="oauth-section">
                          {#if oauthSignedIn[provider.id]}
                             <div class="signed-in-badge">
                                <span>Signed in</span>
                             </div>
                             {#if fetchedModels[provider.id]?.length}
                                <span class="hint">{fetchedModels[provider.id].length} models available</span>
                             {/if}
                          {:else if deviceFlows[provider.id]}
                             <div class="device-flow-box">
                               <p class="instructions">1. A browser tab opened. Enter this code:</p>
                               <div class="code-display">
                                  {deviceFlows[provider.id].userCode}
                                  <button class="copy-btn" onclick={() => navigator.clipboard.writeText(deviceFlows[provider.id].userCode)}>Copy</button>
                               </div>
                               <p class="instructions">2. Authorize access in the browser, then wait...</p>
                               <div class="polling-indicator">
                                  <span class="spinner-icon">&#8987;</span> Waiting for authorization...
                               </div>
                               <button class="link-btn" onclick={() => cancelAuth(provider.id)}>Cancel</button>
                             </div>
                          {:else}
                             {#if authErrors[provider.id]}
                               <div class="error-message" style="margin-bottom: 0.5rem; text-align: left; font-size: 0.85rem;">{authErrors[provider.id]}</div>
                             {/if}
                             <button class="btn-oauth" onclick={() => handleOAuthSignIn(provider.id)} disabled={authLoading[provider.id]}>
                                {#if authLoading[provider.id]}Loading...{:else}Sign in with {provider.name}{/if}
                             </button>
                          {/if}
                           <button class="link-btn" onclick={() => toggleAuthMethod(provider)}>Or use API Key</button>
                        </div>
                     {/if}
                  {:else}
                    <div class="form-group">
                      <label for="{provider.id}-key-standard">API Key</label>
                      <input id="{provider.id}-key-standard" type="password" bind:value={provider.apiKey} placeholder={provider.placeholderKey}>
                      {#if provider.keyUrl}
                        <a href={provider.keyUrl} target="_blank" rel="noopener noreferrer" class="help-link">Get API Key</a>
                      {/if}
                    </div>
                  {/if}
                </div>
              {/if}
            </div>
          {/each}
        </div>

        {#if error}
          <div class="error-message">{error}</div>
        {/if}

        <div class="actions">
          <button class="btn-secondary" onclick={prevStep}>Back</button>
          <button class="btn-primary" onclick={nextStep}>Next</button>
        </div>
      </div>
    {/if}

    {#if currentStep === 3}
      <div class="step-content">
        <h2>Configure Models</h2>
        <p class="subtitle">Specify the model name for each enabled provider.</p>

        <div class="models-list">
          {#each providers.filter(p => p.enabled) as provider}
            <div class="model-card">
              <h3>{provider.name}</h3>
              <div class="form-group">
                <label for="{provider.id}-model">Model Name</label>
                
                {#if (provider.oauth && !provider.useApiKey && oauthSignedIn[provider.id]) || provider.id === 'ollama'}
                    {#if fetchingModels[provider.id]}
                        <div class="loading-models">Fetching models...</div>
                    {:else if fetchedModels[provider.id]?.length > 0}
                        <select id="{provider.id}-model" bind:value={provider.model} class="model-select">
                           <option value="" disabled>Select a model</option>
                           {#each fetchedModels[provider.id] as m}
                              <option value={m.id}>{m.name}</option>
                           {/each}
                        </select>
                        <div class="flex-row">
                          <button class="link-btn small" onclick={() => fetchModels(provider.id)}>Refresh Models</button>
                        </div>
                    {:else}
                         <div class="error-container">
                             {#if modelFetchErrors[provider.id]}
                               <span class="error-text">{modelFetchErrors[provider.id]}</span>
                             {/if}
                             <button class="btn-secondary small" onclick={() => fetchModels(provider.id)}>
                               {modelFetchErrors[provider.id] ? 'Retry' : 'Fetch Models'}
                             </button>
                             <input id="{provider.id}-model-manual" type="text" bind:value={provider.model} placeholder="Or enter model name manually">
                         </div>
                    {/if}
                {:else}
                   <input id="{provider.id}-model-text" type="text" bind:value={provider.model} placeholder="e.g. gpt-4o, claude-3-sonnet">
                   <span class="hint">Check your provider's documentation for exact model names.</span>
                {/if}
              </div>
            </div>
          {/each}
        </div>

        <div class="actions">
          <button class="btn-secondary" onclick={prevStep}>Back</button>
          <button class="btn-primary" onclick={nextStep}>Next</button>
        </div>
      </div>
    {/if}

    {#if currentStep === 4}
      <div class="step-content">
        <h2>Ready to Start?</h2>
        <p class="subtitle">Review your configuration.</p>

        <div class="summary-list">
          {#each providers.filter(p => p.enabled) as provider}
            <div class="summary-item">
              <span class="label">{provider.name}:</span>
              <span class="value">{provider.model || 'Default Model'}</span>
            </div>
          {/each}
        </div>

        <div class="actions">
          <button class="btn-secondary" onclick={prevStep}>Back</button>
          <button class="btn-primary" onclick={saveAndComplete} disabled={loading}>
            {#if loading}Saving...{:else}Save & Start{/if}
          </button>
        </div>
      </div>
    {/if}
  </div>
</div>

<style>
  .wizard-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100vw;
    background-color: var(--bg-primary);
  }

  .wizard-card {
    background: var(--bg-card);
    width: 100%;
    max-width: 600px;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    padding: 2rem;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow-y: auto;
  }

  .progress-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 0 1rem;
  }

  .progress-step {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--border-color);
    color: var(--text-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    z-index: 1;
  }

  .progress-step.active {
    background: var(--color-primary);
    color: var(--color-primary-text);
  }

  .line {
    flex: 1;
    height: 2px;
    background: var(--border-color);
    margin: 0 8px;
  }

  .line.active {
    background: var(--color-primary);
  }

  .step-content {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  h1, h2 {
    margin: 0;
    color: var(--text-primary);
    text-align: center;
    font-size: 1.75rem;
  }

  .subtitle {
    text-align: center;
    color: var(--text-secondary);
    margin: -0.5rem 0 0 0;
    font-size: 1.05rem;
  }

  .description {
    text-align: center;
    line-height: 1.6;
    color: var(--text-primary);
    font-size: 1rem;
  }

  .actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
  }

  .actions button {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: background 0.2s;
  }

  .btn-primary {
    background-color: var(--color-primary);
    color: var(--color-primary-text);
    margin-left: auto;
  }

  .btn-primary:hover {
    background-color: var(--color-primary-hover);
  }

  .btn-primary:disabled {
    background-color: var(--text-tertiary);
    cursor: not-allowed;
  }

  .btn-secondary {
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    margin-right: auto;
  }

  .btn-secondary:hover {
    background-color: var(--text-tertiary);
  }

  .providers-list, .models-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .provider-card, .model-card {
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1rem;
    transition: border-color 0.2s;
  }
  
  .model-card h3 {
    margin: 0 0 0.75rem 0;
    color: var(--text-primary);
    font-size: 1.1rem;
  }

  .provider-card.enabled {
    border-color: var(--color-primary);
    background-color: var(--bg-hover);
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: 500;
    font-size: 1.1rem;
    color: var(--text-primary);
  }
  
  .provider-name {
    color: var(--text-primary);
  }

  .checkbox-label input {
    margin-right: 0.75rem;
    width: 18px;
    height: 18px;
  }

  .card-body {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }

  .form-group label {
    font-size: 0.95rem;
    color: var(--text-primary);
    font-weight: 500;
  }

  .form-group input {
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
  }

  .help-link {
    font-size: 0.85rem;
    color: var(--color-primary);
    text-decoration: none;
    align-self: flex-start;
  }

  .help-link:hover {
    text-decoration: underline;
  }

  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
    background: var(--bg-secondary);
    border: 1px solid var(--text-tertiary);
    border-radius: var(--radius-md);
    cursor: pointer;
  }

  .hint {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-style: italic;
  }

  .summary-list {
    background: var(--bg-secondary);
    padding: 1rem;
    border-radius: var(--radius-lg);
  }

  .summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
  }

  .summary-item:last-child {
    border-bottom: none;
  }
  
  .summary-item .label {
    color: var(--text-primary);
    font-weight: 500;
  }
  
  .summary-item .value {
    color: var(--text-primary);
    font-family: var(--font-mono);
  }

  .error-message {
    color: var(--color-danger);
    text-align: center;
    font-weight: 500;
  }

  .flex-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
  }

  .link-btn {
    background: none;
    border: none;
    color: var(--color-primary);
    text-decoration: underline;
    cursor: pointer;
    font-size: 0.85rem;
    padding: 0;
  }
  
  .link-btn.small {
    font-size: 0.8rem;
    margin-top: 0.25rem;
  }

  .oauth-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    border: 1px dashed var(--border-color);
  }

  .btn-oauth {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    padding: 0.6rem 1.2rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    box-shadow: var(--shadow-sm);
  }

  .btn-oauth:hover {
    background-color: var(--bg-hover);
    border-color: var(--border-color);
    box-shadow: var(--shadow-sm);
  }

  .signed-in-badge {
    background-color: var(--bg-active);
    color: var(--color-success);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .loading-models {
    color: var(--text-secondary);
    font-style: italic;
    padding: 0.5rem;
  }

  .model-select {
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
    width: 100%;
    background-color: var(--bg-card);
  }

  .error-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .error-text {
    color: var(--color-danger);
    font-size: 0.85rem;
  }

  .device-flow-box {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 0.75rem;
    background: var(--bg-secondary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
  }

  .device-flow-box .instructions {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-primary);
  }

  .code-display {
    background: var(--bg-primary);
    color: var(--text-primary);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-family: var(--font-mono);
    font-size: 1.25rem;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.75rem;
    letter-spacing: 0.1em;
  }

  .copy-btn {
    font-size: 0.75rem;
    padding: 0.2rem 0.5rem;
    background: var(--color-primary);
    color: var(--color-primary-text);
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
  }

  .copy-btn:hover {
    background: var(--color-primary-hover);
  }

  .polling-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.85rem;
  }

  .spinner-icon {
    animation: spin 2s linear infinite;
    display: inline-block;
  }

</style>

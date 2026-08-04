<script lang="ts">
  import { onMount } from 'svelte';
  import {
    listProviderConfigs,
    saveProviderConfig,
    deleteProviderConfig,
    deleteOAuthToken,
  } from '../../lib/db';
  import { invoke } from '@tauri-apps/api/core';
  import { Activity, Pencil, Trash2, Plus, RefreshCw } from 'lucide-svelte';
  import type { ProviderConfig } from '../../lib/db';
  import {
    startGitHubDeviceFlow,
    startChatGPTDeviceFlow,
    startGoogleDeviceFlow,
  } from '../../lib/oauth';
  import type { DeviceFlowResult } from '../../lib/oauth';
  import { listProviderModels, groupModels } from '../../lib/model-list';

  let providers: ProviderConfig[] = $state([]);
  let editingProvider: string | null = $state(null);
  let showAddForm = $state(false);

  // New Provider State
  let newProviderId = $state('');
  let newProviderUrl = $state('');
  let newProviderKey = $state('');
  let newProviderModel = $state('');

  // OAuth / Auth Flow State
  let oauthStatus: Record<string, boolean> = $state({});
  let fetchedModels: Record<string, string[]> = $state({});
  let fetchingModels: Record<string, boolean> = $state({});
  let modelFetchErrors: Record<string, string> = $state({});
  let useApiKey: Record<string, boolean> = $state({});

  // Active authentication flows
  let deviceFlows: Record<string, DeviceFlowResult> = $state({});
  let authLoading: Record<string, boolean> = $state({});
  let authErrors: Record<string, string> = $state({});

  // Claude Code sign-in (the friendly, no-terminal flow). The CLI opens the browser, the user
  // pastes the code it shows, and we feed it to `claude auth login` over stdin — see the Rust
  // claude_login_* commands. claudeStatus mirrors `claude auth status`.
  let claudeStatus: { loggedIn?: boolean; email?: string; subscriptionType?: string } = $state({});
  let claudeLoginUrl: string | null = $state(null);
  let claudeCode = $state('');
  let claudeBusy = $state(false);
  let claudeError = $state('');

  // opencode cloud providers — key-based auth written straight to opencode's auth.json (the same
  // file its own `auth login` writes). No terminal picker. OAuth-only providers aren't here; they
  // still need opencode's TUI.
  const OPENCODE_KEY_PROVIDERS = [
    { id: 'ollama-cloud', name: 'Ollama Cloud', keysUrl: 'https://ollama.com/settings/keys' },
    { id: 'openai', name: 'OpenAI', keysUrl: 'https://platform.openai.com/api-keys' },
    { id: 'openrouter', name: 'OpenRouter', keysUrl: 'https://openrouter.ai/keys' },
    { id: 'opencode', name: 'OpenCode Zen', keysUrl: '' },
  ];
  let ocProvider = $state('ollama-cloud');
  const ocOpt = $derived(OPENCODE_KEY_PROVIDERS.find((p) => p.id === ocProvider));
  let ocKey = $state('');
  let ocBusy = $state(false);
  let ocError = $state('');
  let ocSaved = $state(false);

  // Three supported providers, all API-key auth (the legal, no-OAuth-client path).
  // keysUrl points at each vendor's own API-keys page.
  const PROVIDER_OPTIONS = [
    { id: 'anthropic', name: 'Anthropic (Claude)', requiresUrl: false, requiresKey: true, defaultUrl: '', canSignIn: true, keysUrl: 'https://console.anthropic.com/settings/keys' },
    { id: 'openai', name: 'ChatGPT (OpenAI)', requiresUrl: false, requiresKey: true, defaultUrl: '', canSignIn: false, keysUrl: 'https://platform.openai.com/api-keys' },
    { id: 'ollama', name: 'Ollama Cloud', requiresUrl: true, requiresKey: true, defaultUrl: 'https://ollama.com', canSignIn: false, keysUrl: 'https://ollama.com/settings/keys' },
  ];

  onMount(async () => {
    await loadProviders();
    await checkOAuthStatus();
  });

  async function saveOpencodeKey() {
    if (!ocKey.trim()) return;
    ocBusy = true;
    ocError = '';
    ocSaved = false;
    try {
      await invoke('opencode_save_key', { provider: ocProvider, key: ocKey.trim() });
      ocKey = '';
      ocSaved = true;
    } catch (e) {
      ocError = e instanceof Error ? e.message : String(e);
    } finally {
      ocBusy = false;
    }
  }

  async function loadProviders() {
    providers = await listProviderConfigs();
  }

  function getProviderKey(id: string): "github" | "openai" | "anthropic" | "google" | "ollama" | null {
    if (id === 'github-models') return 'github';
    if (id === 'openai') return 'openai';
    if (id === 'anthropic') return 'anthropic';
    if (id === 'google-gemini') return 'google';
    if (id === 'ollama') return 'ollama';
    return null;
  }

  async function checkOAuthStatus() {
    // Claude signs in through its own CLI login, not a stored token.
    await refreshClaudeStatus();
  }

  async function refreshClaudeStatus() {
    try {
      claudeStatus = await invoke('claude_auth_status');
    } catch {
      claudeStatus = { loggedIn: false };
    }
    oauthStatus['anthropic'] = !!claudeStatus?.loggedIn;
    if (claudeStatus?.loggedIn) fetchModels('anthropic');
  }

  // Kick off the browser sign-in: the CLI opens the browser and returns the URL, then waits for the
  // code the user pastes back (submitClaudeCode). No terminal, no API key.
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
      oauthStatus['anthropic'] = !!claudeStatus?.loggedIn;
      if (claudeStatus?.loggedIn) {
        claudeCode = '';
        claudeLoginUrl = null;
        fetchModels('anthropic');
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

  async function signOutClaude() {
    claudeBusy = true;
    claudeError = '';
    try {
      claudeStatus = await invoke('claude_auth_logout');
      oauthStatus['anthropic'] = !!claudeStatus?.loggedIn;
    } catch (e) {
      claudeError = e instanceof Error ? e.message : String(e);
    } finally {
      claudeBusy = false;
    }
  }

  async function startAuth(providerId: string) {
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
      }
    } catch (error) {
      authErrors[providerId] = error instanceof Error ? error.message : String(error);
    } finally {
      authLoading[providerId] = false;
    }
  }

  async function handleDeviceFlow(providerId: string, flow: DeviceFlowResult) {
    deviceFlows[providerId] = flow;
    
    try {
      const result = await flow.poll();
      if (result.success) {
        oauthStatus[providerId] = true;
        fetchModels(providerId);
        if (deviceFlows[providerId]) {
          delete deviceFlows[providerId];
        }
      } else if (result.error !== 'Cancelled') {
        authErrors[providerId] = result.error || 'Authentication failed';
      }
    } catch (error) {
      if (deviceFlows[providerId]) {
        authErrors[providerId] = error instanceof Error ? error.message : String(error);
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

  async function handleSignOut(providerId: string) {
    try {
      const providerKey = getProviderKey(providerId);
      if (providerKey) {
        await deleteOAuthToken(providerKey);
        oauthStatus[providerId] = false;
        fetchedModels[providerId] = [];
      }
    } catch (error) {
       oauthStatus[providerId] = false;
    }
  }

  async function fetchModels(providerId: string) {
    fetchingModels[providerId] = true;
    modelFetchErrors[providerId] = '';
    try {
      const provider = providers.find(p => p.id === providerId);
      // Same source as the embedded browser's ProviderSelector, so the dropdown matches.
      const models = await listProviderModels(providerId, {
        url: provider?.api_url,
        apiKey: provider?.api_key,
      });
      fetchedModels[providerId] = models;

      if (provider && !provider.model && models.length > 0) {
        provider.model = models[0];
      }
    } catch (error) {
      modelFetchErrors[providerId] = error instanceof Error ? error.message : String(error);
    } finally {
      fetchingModels[providerId] = false;
    }
  }

  async function saveProvider(config: ProviderConfig) {
    await saveProviderConfig({ id: config.id, api_url: config.api_url, api_key: config.api_key, model: config.model, is_active: config.is_active });
    await loadProviders();
    editingProvider = null;
  }

  async function deleteProvider(id: string) {
    await deleteProviderConfig(id);
    await loadProviders();
  }

  /** Two-step inline confirm — window.confirm() draws behind the WebView2 window and is unreachable. */
  let confirmingDeleteId = $state<string | null>(null);
  function armDelete(id: string) { confirmingDeleteId = id; }
  function cancelDelete() { confirmingDeleteId = null; }

  /** Inline arm for the cloud-ollama-without-key warning — same native-confirm dead-end as delete. */
  let pendingTestProvider = $state<ProviderConfig | null>(null);
  function armTestConnection(provider: ProviderConfig) {
    pendingTestProvider = provider;
  }
  function cancelTestConnection() {
    pendingTestProvider = null;
  }

  /** Inline notice — native alert() draws behind the WebView2 window, so results surface here. */
  let notice = $state<string | null>(null);
  let noticeKind = $state<'error' | 'info'>('error');
  let noticeTimer: ReturnType<typeof setTimeout> | undefined;
  function showNotice(message: string, kind: 'error' | 'info' = 'error', durationMs = 4000) {
    notice = message;
    noticeKind = kind;
    clearTimeout(noticeTimer);
    noticeTimer = setTimeout(() => { notice = null; }, durationMs);
  }

  async function continueTestConnection() {
    const provider = pendingTestProvider;
    pendingTestProvider = null;
    if (!provider) return;
    const option = PROVIDER_OPTIONS.find(p => p.id === provider.id);
    if (option?.requiresUrl && !provider.api_url) {
      showNotice('API URL is required for this provider');
      return;
    }
    if (!provider.model) {
      showNotice('Model name is required');
      return;
    }
    showNotice(`Provider "${provider.id}" configuration looks valid!`, 'info');
  }

  async function testConnection(provider: ProviderConfig) {
    const option = PROVIDER_OPTIONS.find(p => p.id === provider.id);
    
    if (option?.requiresKey && !provider.api_key && !oauthStatus[provider.id]) {
      showNotice('API Key is required for this provider (or sign in via OAuth)');
      return;
    }
    if (provider.id === 'ollama' && !provider.api_key && provider.api_url && !provider.api_url.includes('localhost')) {
      armTestConnection(provider); // warn in-app instead of a native confirm
      return;
    }
    if (option?.requiresUrl && !provider.api_url) {
      showNotice('API URL is required for this provider');
      return;
    }
    if (!provider.model) {
      showNotice('Model name is required');
      return;
    }
    
    showNotice(`Provider "${provider.id}" configuration looks valid!`, 'info');
  }

  function getAvailableProviders() {
    return PROVIDER_OPTIONS.filter(opt => !providers.find(p => p.id === opt.id));
  }

  function handleAddSelect() {
    const option = PROVIDER_OPTIONS.find(p => p.id === newProviderId);
    if (option) {
      newProviderUrl = option.defaultUrl;
      newProviderKey = '';
      newProviderModel = '';
    }
  }

  async function addNewProvider() {
    if (!newProviderId) return;
    
    await saveProviderConfig({ id: newProviderId, api_url: newProviderUrl, api_key: newProviderKey, model: newProviderModel, is_active: 1 });

    await loadProviders();
    showAddForm = false;
    resetAddForm();
  }

  function resetAddForm() {
    newProviderId = '';
    newProviderUrl = '';
    newProviderKey = '';
    newProviderModel = '';
  }
</script>

<section class="card mb-6">
  <h3>AI Accounts</h3>
  <p class="mb-6">Sign in with your Claude account (no API key needed), or configure OpenCode with a provider and API key.</p>

  <!-- Claude Code -->
  <div class="account-item">
    <div class="account-head">
      <h4>Claude Code</h4>
      {#if claudeStatus?.loggedIn}<span class="badge active">Signed in</span>{/if}
    </div>

    {#if claudeLoginUrl}
      <div class="device-flow-container">
        <p class="instructions">1. A browser window opened for you to sign in to Claude. If it didn't, <a href={claudeLoginUrl} target="_blank" rel="noopener noreferrer">open the sign-in page</a>.</p>
        <p class="instructions">2. After you approve, copy the code shown and paste it here:</p>
        <div class="paste-input-row">
          <input type="text" placeholder="Paste your sign-in code" bind:value={claudeCode} disabled={claudeBusy} />
          <button class="btn primary small" disabled={claudeBusy || !claudeCode.trim()} onclick={submitClaudeCode}>
            {claudeBusy ? 'Signing in…' : 'Submit'}
          </button>
        </div>
        {#if claudeError}<div class="error-banner">{claudeError}</div>{/if}
        <button class="ghost small" onclick={cancelClaudeLogin}>Cancel</button>
      </div>
    {:else if claudeStatus?.loggedIn}
      <div class="oauth-status success">
        <span class="icon">✅</span>
        Signed in{claudeStatus.email ? ` as ${claudeStatus.email}` : ''}{claudeStatus.subscriptionType ? ` · ${claudeStatus.subscriptionType}` : ''}
        <button class="ghost small" disabled={claudeBusy} onclick={signOutClaude}>Sign out</button>
      </div>
    {:else}
      {#if claudeError}<div class="error-banner">{claudeError}</div>{/if}
      <button class="btn oauth-btn" disabled={claudeBusy} onclick={startClaudeLogin}>
        {claudeBusy ? 'Opening browser…' : 'Sign in with Claude'}
      </button>
      <p class="hint">Uses your Claude account through the installed Claude Code app — no API key needed.</p>
    {/if}
  </div>

  <!-- Configure OpenCode: pick a provider, save its API key -->
  <div class="account-item">
    <div class="account-head"><h4>Configure OpenCode</h4></div>
    <div class="edit-form">
      <label>
        Provider
        <select bind:value={ocProvider}>
          {#each OPENCODE_KEY_PROVIDERS as p}
            <option value={p.id}>{p.name}</option>
          {/each}
        </select>
      </label>
      <label>
        API Key
        <input type="password" bind:value={ocKey} placeholder="Paste your cloud API key" />
      </label>
      {#if ocOpt?.keysUrl}
        {@const opt = ocOpt}
        <a class="keys-link" href={opt.keysUrl} target="_blank" rel="noopener noreferrer">Get your {opt.name} API key ↗</a>
      {/if}
      {#if ocError}<div class="error-banner">{ocError}</div>{/if}
      <div class="form-actions">
        <button class="primary" disabled={ocBusy || !ocKey.trim()} onclick={saveOpencodeKey}>{ocBusy ? 'Saving…' : 'Save key'}</button>
        {#if ocSaved}<span class="oauth-status success"><span class="icon">✅</span> Saved</span>{/if}
      </div>
    </div>
  </div>
</section>

<section class="card mb-6">
  <h3>AI Provider Configuration</h3>
  <p class="mb-6">Manage connections to local or cloud-based AI providers.</p>
  
  {#if notice}
    <div class="notice {noticeKind}" role="alert">{notice}</div>
  {/if}
  
  {#if providers.length === 0 && !showAddForm}
    <div class="empty-state">
      <p>No providers configured yet.</p>
      <button class="primary" onclick={() => showAddForm = true}><Plus size={16} /> Add Your First Provider</button>
    </div>
  {/if}

  <div class="providers-list">
    {#each providers as provider}
      {@const option = PROVIDER_OPTIONS.find(p => p.id === provider.id)}
      <div class="provider-item" class:editing={editingProvider === provider.id}>
        <div class="provider-header">
          <h4>{option?.name || provider.id}</h4>
          {#if editingProvider !== provider.id}
            <div class="actions">
              <button class="icon-btn" title="Test connection" aria-label="Test connection" onclick={() => testConnection(provider)}><Activity size={16} /></button>
              <button class="icon-btn" title="Edit" aria-label="Edit provider" onclick={() => editingProvider = provider.id}><Pencil size={16} /></button>
              {#if confirmingDeleteId === provider.id}
                <button class="confirm-btn" title="Confirm delete — cannot be undone" aria-label="Confirm delete provider" onclick={() => { deleteProvider(provider.id); cancelDelete(); }}>Confirm</button>
                <button class="icon-btn" title="Keep provider" aria-label="Cancel delete" onclick={cancelDelete}><span>✕</span></button>
              {:else}
                <button class="icon-btn danger" title="Delete" aria-label="Delete provider" onclick={() => armDelete(provider.id)}><Trash2 size={16} /></button>
              {/if}
            </div>
          {/if}
        </div>

        {#if pendingTestProvider?.id === provider.id}
          <div class="warning-banner">
            <span>Using a cloud Ollama endpoint without an API key may fail. Continue anyway?</span>
            <div class="warning-actions">
              <button class="confirm-btn" onclick={continueTestConnection}>Continue</button>
              <button class="icon-btn" title="Cancel" aria-label="Cancel test connection" onclick={cancelTestConnection}><span>✕</span></button>
            </div>
          </div>
        {/if}

        {#if editingProvider === provider.id}
          <div class="edit-form">
            {#if option?.requiresUrl}
              <label>
                API URL
                <input type="text" bind:value={provider.api_url} placeholder="https://api.example.com" />
              </label>
              {#if provider.id === 'ollama'}
                <div class="url-presets">
                  <span class="preset-label">Quick presets:</span>
                  <button type="button" class="preset-btn" onclick={() => provider.api_url = 'https://ollama.com'}>
                    Cloud (ollama.com)
                  </button>
                  <button type="button" class="preset-btn" onclick={() => provider.api_url = 'http://localhost:11434'}>
                    Local (localhost:11434)
                  </button>
                </div>
              {/if}
            {/if}
            
            {#if option?.requiresKey}
              {#if option.canSignIn}
                 <p class="hint">Manage Claude sign-in under <strong>AI Accounts</strong> above. This card just picks the model.</p>
              {:else}
                <label>
                  API Key
                  <input type="password" bind:value={provider.api_key} placeholder="sk-..." />
                </label>
                {#if option.keysUrl}
                  <a class="keys-link" href={option.keysUrl} target="_blank" rel="noopener noreferrer">
                    Get your {option.name} API key ↗
                  </a>
                {/if}
              {/if}
            {:else if provider.id === 'ollama'}
              <label>
                API Key <span class="optional-badge">(Optional - only for cloud endpoints)</span>
                <input type="password" bind:value={provider.api_key} placeholder="Leave empty for local" />
              </label>
            {/if}

            <label>
              Model
              {#if (option?.canSignIn && (oauthStatus[provider.id] || (useApiKey[provider.id] && provider.api_key))) || (provider.id === 'ollama' && provider.api_url)}
                  <div class="model-select-container">
                    {#if fetchingModels[provider.id]}
                        <div class="loading">Loading models...</div>
                    {:else if modelFetchErrors[provider.id]}
                        <div class="error">
                          Error: {modelFetchErrors[provider.id]}
                          <button class="secondary small" onclick={() => fetchModels(provider.id)}>Retry</button>
                        </div>
                        <input type="text" bind:value={provider.model} placeholder={provider.id === 'ollama' ? 'llama2:latest' : 'gpt-4o'} />
                    {:else if fetchedModels[provider.id]?.length > 0}
                        {@const choices = provider.model && !fetchedModels[provider.id].includes(provider.model) ? [provider.model, ...fetchedModels[provider.id]] : fetchedModels[provider.id]}
                        <div class="select-wrapper">
                            <select bind:value={provider.model}>
                                <option value="" disabled>Select a model</option>
                                {#each groupModels(choices) as g}
                                    {#if g.label}
                                        <optgroup label={g.label}>
                                            {#each g.models as m}<option value={m}>{m}</option>{/each}
                                        </optgroup>
                                    {:else}
                                        {#each g.models as m}<option value={m}>{m}</option>{/each}
                                    {/if}
                                {/each}
                            </select>
                            <button class="icon-btn" title="Refresh Models" aria-label="Refresh Models" onclick={() => fetchModels(provider.id)}>
                                <RefreshCw size={16} />
                            </button>
                        </div>
                    {:else}
                        <input type="text" bind:value={provider.model} placeholder={provider.id === 'ollama' ? 'llama2:latest' : 'gpt-4o'} />
                        <button class="secondary small" onclick={() => fetchModels(provider.id)}>Fetch Models</button>
                    {/if}
                  </div>
              {:else}
                  <input type="text" bind:value={provider.model} placeholder={provider.id === 'ollama' ? 'llama2:latest' : 'gpt-4o'} />
              {/if}
            </label>
            
            <label class="checkbox-label">
              <input type="checkbox" checked={!!provider.is_active} 
                     onchange={(e) => provider.is_active = (e.currentTarget as HTMLInputElement).checked ? 1 : 0} />
              <span>Active</span>
            </label>

            <div class="form-actions">
              <button class="primary" onclick={() => saveProvider(provider)}>Save Changes</button>
              <button class="ghost" onclick={() => editingProvider = null}>Cancel</button>
            </div>
          </div>
        {:else}
          <div class="provider-info">
            {#if provider.api_url}
              <div class="info-row">
                <span class="label">API URL:</span>
                <span class="value">{provider.api_url}</span>
              </div>
            {/if}
            {#if provider.api_key}
              <div class="info-row">
                <span class="label">API Key:</span>
                <span class="value">{'*'.repeat(20)}</span>
              </div>
            {/if}
            {#if option?.canSignIn && oauthStatus[provider.id]}
               <div class="info-row">
                 <span class="label">Auth:</span>
                 <span class="value success">✅ Signed In</span>
               </div>
            {/if}
            <div class="info-row">
              <span class="label">Model:</span>
              <span class="value">{provider.model || 'Not set'}</span>
            </div>
            <div class="info-row">
              <span class="label">Status:</span>
              <span class="badge" class:active={provider.is_active}>
                {provider.is_active ? 'Active' : 'Inactive'}
              </span>
            </div>
          </div>
        {/if}
      </div>
    {/each}
  </div>

  {#if !showAddForm && providers.length > 0}
    <button class="add-btn" onclick={() => showAddForm = true}>
      <Plus size={16} /> Add Another Provider
    </button>
  {/if}

  {#if showAddForm}
    <div class="add-form provider-item editing">
      <h4>Add New Provider</h4>
      
      <div class="edit-form">
        <label>
          Provider Type
          <select bind:value={newProviderId} onchange={handleAddSelect}>
            <option value="" disabled selected>Select a provider...</option>
            {#each getAvailableProviders() as opt}
              <option value={opt.id}>{opt.name}</option>
            {/each}
          </select>
        </label>

        {#if newProviderId}
          {@const newOption = PROVIDER_OPTIONS.find(p => p.id === newProviderId)}
          {#if newOption?.requiresUrl}
            <label>
              API URL
              <input type="text" bind:value={newProviderUrl} placeholder="https://api.example.com" />
            </label>
            {#if newProviderId === 'ollama'}
              <div class="url-presets">
                <span class="preset-label">Quick presets:</span>
                <button type="button" class="preset-btn" onclick={() => newProviderUrl = 'https://ollama.com'}>
                  Cloud (ollama.com)
                </button>
                <button type="button" class="preset-btn" onclick={() => newProviderUrl = 'http://localhost:11434'}>
                  Local (localhost:11434)
                </button>
              </div>
            {/if}
          {/if}

          {#if newOption?.requiresKey}
             {#if newOption.canSignIn}
                <p class="hint">You can sign in after saving.</p>
                <label>
                  API Key (Optional if using Auth)
                  <input type="password" bind:value={newProviderKey} placeholder="sk-..." />
                </label>
             {:else}
                <label>
                  API Key
                  <input type="password" bind:value={newProviderKey} placeholder="sk-..." />
                </label>
                {#if newOption.keysUrl}
                  <a class="keys-link" href={newOption.keysUrl} target="_blank" rel="noopener noreferrer">
                    Get your {newOption.name} API key ↗
                  </a>
                {/if}
             {/if}
          {:else if newProviderId === 'ollama'}
            <label>
              API Key <span class="optional-badge">(Optional - only for cloud endpoints)</span>
              <input type="password" bind:value={newProviderKey} placeholder="Leave empty for local" />
            </label>
          {/if}

          <label>
            Model
            <input type="text" bind:value={newProviderModel} placeholder={newProviderId === 'ollama' ? 'llama2:latest' : 'gpt-4o'} />
            <p class="hint">Save the provider first, then edit it to fetch available models.</p>
          </label>

          <div class="form-actions">
            <button class="primary" onclick={addNewProvider}>Add Provider</button>
            <button class="ghost" onclick={() => { showAddForm = false; resetAddForm(); }}>Cancel</button>
          </div>
        {/if}
      </div>
    </div>
  {/if}
</section>

<style>
  .account-item {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: var(--spacing-4);
    margin-bottom: var(--spacing-4);
  }

  .account-head {
    display: flex;
    align-items: center;
    gap: var(--spacing-4);
    margin-bottom: var(--spacing-4);
  }

  .account-head h4 {
    margin: 0;
    font-size: var(--font-size-lg);
    color: var(--text-primary);
  }

  .empty-state {
    text-align: center;
    padding: var(--spacing-4);
    background: var(--bg-card);
    border-radius: var(--radius-md);
    border: 1px dashed var(--border-color);
    color: var(--text-secondary);
  }

  .providers-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
  }

  .provider-item {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: var(--spacing-4);
    transition: border-color var(--transition-fast);
  }
  
  .provider-item:hover, .provider-item.editing {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-sm);
  }

  .provider-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-4);
  }
  
  .provider-header h4 {
    margin: 0;
    font-size: var(--font-size-lg);
    color: var(--text-primary);
  }

  .actions {
    display: flex;
    gap: var(--spacing-1);
    align-items: center;
  }

  .icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    padding: 0;
    border: 1px solid transparent;
    border-radius: var(--radius-sm);
    background: transparent;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all var(--transition-fast);
  }

  .icon-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
  }

  .icon-btn.danger:hover {
    background: var(--color-danger-bg);
    color: var(--color-danger);
  }

  .confirm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.25rem 0.6rem;
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: 500;
    border: 1px solid var(--color-danger);
    background: var(--color-danger-bg);
    color: var(--color-danger);
    cursor: pointer;
    white-space: nowrap;
    transition: all var(--transition-fast);
  }
  .confirm-btn:hover { background: var(--color-danger); color: #fff; }

  .warning-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing-2);
    margin-top: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-3);
    border: 1px solid var(--color-border);
    border-left: 3px solid #d97706;
    background: rgba(217,119,6,.10);
    border-radius: var(--radius-md);
    font-size: var(--font-size-sm);
    color: var(--color-text-primary);
  }
  .warning-actions { display: flex; align-items: center; gap: var(--spacing-2); }

  .notice {
    font-size: var(--font-size-sm);
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-4);
  }
  .notice.error { color: var(--color-danger); background: color-mix(in srgb, var(--color-danger) 10%, transparent); border: 1px solid color-mix(in srgb, var(--color-danger) 35%, transparent); }
  .notice.info { color: var(--text-primary); background: var(--color-bg-card); border: 1px solid var(--color-border); }

  /* Button variants — these classes were used in markup but never defined,
     so buttons fell back to the browser default. Themed here. */
  .primary, .secondary, .ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-4);
    border-radius: var(--radius-md);
    font-size: var(--font-size-sm);
    font-weight: 500;
    border: 1px solid transparent;
    transition: all var(--transition-fast);
  }

  .primary {
    background: var(--color-primary);
    color: var(--color-primary-text);
  }
  .primary:hover:not(:disabled) { background: var(--color-primary-hover); }

  .secondary {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-color: var(--border-color);
  }
  .secondary:hover:not(:disabled) { background: var(--bg-hover); }

  .ghost {
    background: transparent;
    color: var(--text-secondary);
    border-color: var(--border-color);
  }
  .ghost:hover:not(:disabled) { background: var(--bg-hover); color: var(--text-primary); }

  .primary:disabled, .secondary:disabled, .ghost:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .small {
    padding: 0.25rem 0.6rem;
    font-size: var(--font-size-xs);
  }

  .provider-info {
    display: grid;
    gap: var(--spacing-2);
  }

  .info-row {
    display: flex;
    gap: var(--spacing-4);
    align-items: center;
    font-size: var(--font-size-sm);
  }

  .info-row .label {
    font-weight: 500;
    color: var(--text-secondary);
    min-width: 80px;
  }

  .info-row .value {
    color: var(--text-primary);
    font-family: var(--font-family-mono);
  }

  .badge {
    padding: 0.125rem 0.625rem;
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    background: var(--bg-hover);
    color: var(--text-secondary);
    font-weight: 500;
    border: 1px solid var(--border-color);
  }

  .badge.active {
    background: var(--color-success-bg);
    color: var(--color-success);
    border-color: var(--color-success-border);
  }

  .edit-form {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
    margin-top: var(--spacing-2);
  }

  .checkbox-label {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: var(--spacing-2);
    cursor: pointer;
    margin-bottom: 0;
  }
  
  .checkbox-label input {
    width: auto;
    margin: 0;
  }

  .form-actions {
    display: flex;
    gap: var(--spacing-2);
    margin-top: var(--spacing-4);
  }

  .add-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    width: 100%;
    padding: var(--spacing-4);
    background: transparent;
    border: 2px dashed var(--border-color);
    color: var(--text-secondary);
    margin-top: var(--spacing-4);
    border-radius: var(--radius-md);
    transition: all 0.2s;
  }

  .add-btn:hover {
    border-color: var(--color-primary);
    color: var(--color-primary);
    background: var(--bg-hover);
  }

  /* OAuth Specifics */
  .oauth-status {
    display: flex;
    align-items: center;
    gap: var(--spacing-4);
    color: var(--text-primary);
    font-weight: 500;
  }

  .oauth-status.success {
    color: var(--color-success);
  }

  .oauth-btn {
    width: 100%;
    background-color: var(--color-primary);
    color: var(--color-primary-text);
    border: none;
  }
  
  .oauth-btn:hover {
    background-color: var(--color-primary-hover);
  }

  .hint {
    font-size: var(--font-size-xs);
    color: var(--text-tertiary);
    margin: 0 0 var(--spacing-2) 0;
  }

  .keys-link {
    font-size: var(--font-size-xs);
    color: var(--color-primary);
    text-decoration: underline;
    width: fit-content;
  }

  .keys-link:hover {
    color: var(--color-primary-hover);
  }

  .model-select-container {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
  }

  .select-wrapper {
    display: flex;
    gap: var(--spacing-2);
  }

  .loading {
    font-size: var(--font-size-xs);
    color: var(--text-tertiary);
    font-style: italic;
  }

  .error {
    color: var(--color-error);
    font-size: var(--font-size-sm);
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
  }

  .error-banner {
    background: var(--color-danger-bg);
    color: var(--color-danger);
    padding: var(--spacing-2);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-sm);
    border: 1px solid var(--color-danger-border);
  }

  /* Device Flow */
  .device-flow-container {
    background: var(--bg-primary);
    padding: var(--spacing-4);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
  }

  .url-presets {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-2);
    margin-top: var(--spacing-2);
    margin-bottom: var(--spacing-4);
    align-items: center;
  }

  .preset-label {
    font-size: var(--font-size-xs);
    color: var(--text-secondary);
  }

  .preset-btn {
    font-size: var(--font-size-xs);
    padding: 0.125rem 0.5rem;
    background: var(--bg-hover);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-full);
    cursor: pointer;
    transition: all var(--transition-fast);
  }

  .preset-btn:hover {
    color: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-primary);
  }

  .paste-input-row {
    display: flex;
    gap: var(--spacing-2);
    align-items: center;
  }

  .paste-input-row input {
    flex: 1;
  }
</style>

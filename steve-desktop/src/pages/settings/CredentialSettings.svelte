<script lang="ts">
  import { onMount } from 'svelte';
  import { 
    getSiteCredentials,
    saveSiteCredential,
    deleteSiteCredential
  } from '../../lib/db';
  import { Plus, Pencil, Trash2 } from 'lucide-svelte';
  import type { SiteCredential } from '../../lib/db';

  let credentials: SiteCredential[] = $state([]);
  let editingCredentialId: number | null = $state(null);
  let showAddCredentialForm = $state(false);
  let showPassword = $state(false);
  let credentialForm = $state({
    site_name: '',
    url_pattern: '',
    username: '',
    password: '',
    totp_secret: '',
    notes: ''
  });

  /** Inline notice — native alert() draws behind the WebView2 window, so errors surface here. */
  let notice = $state<string | null>(null);
  let noticeKind = $state<'error' | 'info'>('error');
  let noticeTimer: ReturnType<typeof setTimeout> | undefined;
  function showNotice(message: string, kind: 'error' | 'info' = 'error', durationMs = 4000) {
    notice = message;
    noticeKind = kind;
    clearTimeout(noticeTimer);
    noticeTimer = setTimeout(() => { notice = null; }, durationMs);
  }

  onMount(async () => {
    await loadCredentials();
  });

  async function loadCredentials() {
    credentials = await getSiteCredentials();
  }

  async function saveCredential() {
    if (!credentialForm.site_name || !credentialForm.url_pattern || !credentialForm.username || !credentialForm.password) {
      showNotice('Please fill in all required fields (Site Name, URL Pattern, Username, Password)');
      return;
    }

    try {
      await saveSiteCredential({
        id: editingCredentialId || undefined,
        site_name: credentialForm.site_name,
        url_pattern: credentialForm.url_pattern,
        username: credentialForm.username,
        password: credentialForm.password,
        totp_secret: credentialForm.totp_secret,
        notes: credentialForm.notes
      });
      
      await loadCredentials();
      resetCredentialForm();
    } catch (error) {
      showNotice('Failed to save credential: ' + error);
    }
  }

  async function deleteCredential(id: number, name: string) {
    try {
      await deleteSiteCredential(id);
      await loadCredentials();
    } catch (error) {
      showNotice('Failed to delete credential: ' + error);
    }
  }

  /** Two-step inline confirm — window.confirm() draws behind the WebView2 window and is unreachable. */
  let confirmingDeleteId = $state<number | null>(null);
  function armDelete(id: number) { confirmingDeleteId = id; }
  function cancelDelete() { confirmingDeleteId = null; }

  function editCredential(cred: SiteCredential) {
    editingCredentialId = cred.id || null;
    credentialForm = {
      site_name: cred.site_name,
      url_pattern: cred.url_pattern,
      username: cred.username,
      password: cred.password,
      totp_secret: cred.totp_secret || '',
      notes: cred.notes || ''
    };
    showAddCredentialForm = true;
  }

  function resetCredentialForm() {
    editingCredentialId = null;
    credentialForm = {
      site_name: '',
      url_pattern: '',
      username: '',
      password: '',
      totp_secret: '',
      notes: ''
    };
    showAddCredentialForm = false;
    showPassword = false;
  }
</script>

<!-- Site Credentials Section -->
<section class="card mb-6">
  <div class="header-with-action">
    <h3>Site Credentials</h3>
    {#if !showAddCredentialForm}
      <button class="primary small" onclick={() => { resetCredentialForm(); showAddCredentialForm = true; }}>
        <Plus size={16} /> Add Credential
      </button>
    {/if}
  </div>
  <p class="mb-6">Manage login credentials for video sites. Credentials are stored locally and sent only to the matching site.</p>
  
  {#if notice}
    <div class="notice {noticeKind}" role="alert">{notice}</div>
  {/if}
  
  {#if showAddCredentialForm}
    <div class="add-form provider-item editing mb-4">
      <h4>{editingCredentialId ? 'Edit Credential' : 'Add New Credential'}</h4>
      
      <div class="edit-form">
        <label>
          Site Name
          <input type="text" bind:value={credentialForm.site_name} placeholder="e.g. YouTube" />
        </label>

        <label>
          URL Pattern
          <input type="text" bind:value={credentialForm.url_pattern} placeholder="e.g. https://www.youtube.com/%" />
          <p class="hint">Use % as a wildcard. Example: https://www.youtube.com/% matches any YouTube page.</p>
        </label>

        <label>
          Username
          <input type="text" bind:value={credentialForm.username} placeholder="Username or Email" />
        </label>

        <label>
          Password
          <div class="password-input-wrapper">
            <input 
              type={showPassword ? "text" : "password"} 
              bind:value={credentialForm.password} 
              placeholder="Password" 
            />
            <button class="toggle-password" type="button" onclick={() => showPassword = !showPassword}>
              {showPassword ? '🙈' : '👁️'}
            </button>
          </div>
        </label>

        <label>
          2FA Secret (Optional)
          <input type="text" bind:value={credentialForm.totp_secret} placeholder="e.g. JBSWY3DPEHPK3PXP" autocomplete="off" spellcheck="false" />
          <p class="hint">The authenticator "can't scan? enter this key" code. Agent generates the 6-digit login code from it. Leave blank if the site has no 2FA.</p>
        </label>

        <label>
          Notes (Optional)
          <textarea bind:value={credentialForm.notes} rows="2" placeholder="Additional notes..."></textarea>
        </label>

        <div class="form-actions">
          <button class="primary" onclick={saveCredential}>Save Credential</button>
          <button class="ghost" onclick={resetCredentialForm}>Cancel</button>
        </div>
      </div>
    </div>
  {/if}

  {#if credentials.length === 0 && !showAddCredentialForm}
    <div class="empty-state">
      <p>No credentials saved yet.</p>
    </div>
  {:else if credentials.length > 0 && !showAddCredentialForm}
    <div class="providers-list">
      {#each credentials as cred}
        <div class="provider-item">
          <div class="provider-header">
            <h4>{cred.site_name}</h4>
            <div class="actions">
              <button class="icon-btn" title="Edit" aria-label="Edit credential" onclick={() => editCredential(cred)}><Pencil size={16} /></button>
              {#if confirmingDeleteId === cred.id}
                <button class="confirm-btn" title="Confirm delete — cannot be undone" aria-label="Confirm delete credential" onclick={() => { const id = cred.id; if (id !== undefined) { deleteCredential(id, cred.site_name); cancelDelete(); } }}>Confirm</button>
                <button class="icon-btn" title="Keep credential" aria-label="Cancel delete" onclick={cancelDelete}><span>✕</span></button>
              {:else}
                <button class="icon-btn danger" title="Delete" aria-label="Delete credential" onclick={() => cred.id !== undefined && armDelete(cred.id)}><Trash2 size={16} /></button>
              {/if}
            </div>
          </div>
          
          <div class="provider-info">
            <div class="info-row">
              <span class="label">URL Pattern:</span>
              <span class="value">{cred.url_pattern}</span>
            </div>
            <div class="info-row">
              <span class="label">Username:</span>
              <span class="value">{cred.username}</span>
            </div>
            <div class="info-row">
              <span class="label">Password:</span>
              <span class="value">********</span>
            </div>
            <div class="info-row">
              <span class="label">2FA:</span>
              <span class="value">{cred.totp_secret ? 'enabled' : 'off'}</span>
            </div>
            {#if cred.notes}
              <div class="info-row">
                <span class="label">Notes:</span>
                <span class="value text-muted">{cred.notes}</span>
              </div>
            {/if}
          </div>
        </div>
      {/each}
    </div>
  {/if}
</section>

<style>
  .header-with-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-4);
  }

  .empty-state {
    text-align: center;
    padding: var(--spacing-12);
    background: var(--color-bg-main);
    border-radius: var(--radius-md);
    border: 1px dashed var(--color-border);
    color: var(--color-text-secondary);
  }

  .providers-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
  }

  .provider-item {
    background: var(--color-bg-main);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: var(--spacing-4);
    transition: border-color var(--transition-fast);
  }
  
  .provider-item:hover, .provider-item.editing {
    border-color: var(--color-border-hover);
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
    color: var(--color-text-primary);
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
  .icon-btn:hover { background: var(--bg-hover); color: var(--text-primary); }
  .icon-btn.danger:hover { background: var(--color-danger-bg); color: var(--color-danger); }

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

  .notice {
    font-size: var(--font-size-sm);
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-4);
  }
  .notice.error { color: var(--color-danger); background: color-mix(in srgb, var(--color-danger) 10%, transparent); border: 1px solid color-mix(in srgb, var(--color-danger) 35%, transparent); }
  .notice.info { color: var(--text-primary); background: var(--color-bg-card); border: 1px solid var(--color-border); }

  /* Button variants — used in markup but otherwise undefined (browser default). */
  .primary, .ghost {
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
  .primary { background: var(--color-primary); color: var(--color-primary-text); }
  .primary:hover:not(:disabled) { background: var(--color-primary-hover); }
  .ghost { background: transparent; color: var(--text-secondary); border-color: var(--border-color); }
  .ghost:hover:not(:disabled) { background: var(--bg-hover); color: var(--text-primary); }
  .primary:disabled, .ghost:disabled { opacity: 0.5; cursor: not-allowed; }

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
    color: var(--color-text-secondary);
    min-width: 80px;
  }

  .info-row .value {
    color: var(--color-text-primary);
    font-family: var(--font-family-mono);
  }

  .edit-form {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
    margin-top: var(--spacing-2);
  }

  .form-actions {
    display: flex;
    gap: var(--spacing-2);
    margin-top: var(--spacing-4);
  }

  .hint {
    font-size: var(--font-size-xs);
    color: var(--color-text-muted);
    margin: 0 0 var(--spacing-2) 0;
  }

  .password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  
  .password-input-wrapper input {
    padding-right: 40px;
  }

  .toggle-password {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    font-size: 1.2rem;
    opacity: 0.7;
  }

  .toggle-password:hover {
    opacity: 1;
  }
</style>

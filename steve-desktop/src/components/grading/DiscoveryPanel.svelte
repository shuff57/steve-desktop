<script lang="ts">
  /**
   * DiscoveryPanel — Orchestrator for AI-powered page structure discovery.
   */
  import {
    runDiscovery,
    type DiscoveryResult,
    type ValidationResults,
    type SelectorMap
  } from '../../lib/discover';
  import {
    refineSelector,
    mergeSelectorSources,
    clearRefinementHighlights,
  } from '../../lib/discovery-ui';
  import {
    createConfirmationFlow,
    type ConfirmationFlow,
  } from '../../lib/confirmation-flow';
  import { ProfileStorageImpl, type SiteProfile } from '../../lib/site-profiles';
  import { discoveryResultToSiteProfile } from '../../lib/type-mappers';
  import { getEmbeddedUrl } from '../../lib/browser';

  import {
    type IntentMode,
    type FormModeInput,
    type ExampleSelection,
    createChatDiscoveryState,
    runChatDiscovery,
    intentToDiscoveryHints,
  } from '../../lib/discovery-intent';
  
  import DiscoveryProgress from './DiscoveryProgress.svelte';
  import DiscoveryResults from './DiscoveryResults.svelte';
  import DiscoveryConfirmation from './DiscoveryConfirmation.svelte';
  import DiscoverySaveDialog from './DiscoverySaveDialog.svelte';
  import DiscoveryModeSelector from './DiscoveryModeSelector.svelte';
  import DiscoveryChat from './DiscoveryChat.svelte';
  import { highlightSelector, SELECTOR_LABELS } from '../../lib/discovery-ui';

  // Props
  let {
    provider = '',
    model = '',
    pageLoadedUrl = '',
    refreshKey = 0,
    onProfileSaved = () => {},
  } = $props<{
    provider?: string;
    model?: string;
    pageLoadedUrl?: string;
    refreshKey?: number;
    onProfileSaved?: (profile: SiteProfile) => void;
  }>();

  // ── State ─────────────────────────────────────────────────────────────
  type DiscoveryPhase = 'idle' | 'running' | 'review' | 'confirming' | 'saving' | 'error';

  let phase = $state<DiscoveryPhase>('idle');
  let progressMessage = $state('');
  let progressPercent = $state(0);
  let error = $state('');

  // Mode & Intent State
  let mode = $state<IntentMode>('form');
  let formInput = $state<FormModeInput>({
    hasVideoPlayer: true,
    hasInteractiveForms: false,
    hasNavigation: false,
    hasQuizElements: false
  });
  let chatState = $state(createChatDiscoveryState());
  let exampleSelections = $state<ExampleSelection[]>([]);

  let discoveryResult = $state<DiscoveryResult | null>(null);
  let validationResults = $state<ValidationResults | null>(null);

  // Save dialog state
  let showSaveDialog = $state(false);
  let profileName = $state('');
  let saveStatus = $state('');

  // Confirmation flow state
  let confirmationFlow = $state<ConfirmationFlow | null>(null);
  let isRefining = $state(false);

  // Stale-data warning state
  let staleWarning = $state(false);
  let lastDiscoveryUrl = $state('');

  // ── Effects ───────────────────────────────────────────────────────────

  $effect(() => {
    if (!pageLoadedUrl) return;
    if (lastDiscoveryUrl && pageLoadedUrl !== lastDiscoveryUrl) {
      staleWarning = true;
    }
  });

  $effect(() => {
    if (refreshKey > 0) staleWarning = false;
  });

  // ── Actions ───────────────────────────────────────────────────────────

  async function handleStartDiscovery() {
    phase = 'running';
    error = '';
    progressMessage = 'Starting discovery...';
    progressPercent = 0;
    discoveryResult = null;
    validationResults = null;

    try {
      const hints = intentToDiscoveryHints(mode, mode === 'form' ? formInput : mode === 'chat' ? chatState.messages : exampleSelections);
      const workflow = await runDiscovery({
        provider: provider || undefined,
        model: model || undefined,
        hints,
        onProgress: (p) => {
          progressMessage = p.message;
          progressPercent = p.progress ?? 0;
          if (p.stage === 'error') {
            phase = 'error';
            error = p.error || 'Unknown error';
          }
        }
      });

      discoveryResult = workflow.draft;
      validationResults = workflow.validation;

      phase = 'review';
      lastDiscoveryUrl = pageLoadedUrl;
      staleWarning = false;
      profileName = 'New Discovery Profile';
    } catch (err) {
      phase = 'error';
      error = err instanceof Error ? err.message : String(err);
    }
  }

  async function handleChatSubmit() {
    try {
      const mockResponse = "I understand. I'll look for that structure.";
      chatState = await runChatDiscovery(chatState, chatState.messages[chatState.messages.length - 1].content, async () => mockResponse);
    } catch (e) {
      error = "Chat failed: " + e;
    }
  }

  async function handleStartConfirmation() {
    if (!discoveryResult || !validationResults) return;
    confirmationFlow = createConfirmationFlow(discoveryResult.selectors, validationResults, discoveryResult.navigation.mode);
    phase = 'confirming';
    const state = confirmationFlow.getState();
    if (state?.selector) await highlightSelector(state.selector);
  }

  async function handleRefine(key: keyof SelectorMap) {
    if (!discoveryResult) return;
    const currentSelector = discoveryResult.selectors[key] || '';
    try {
      const pickerResult = await refineSelector(currentSelector);
      const newSelector = mergeSelectorSources(currentSelector, pickerResult);
      const updatedSelectors: SelectorMap = { ...discoveryResult.selectors, [key]: newSelector };
      discoveryResult = { ...discoveryResult, selectors: updatedSelectors, notes: (discoveryResult.notes || '') + `\n[Refined] ${key}: ${newSelector}` };
      if (validationResults) {
        validationResults = { ...validationResults, [key]: { matchCount: 1, sampleText: '(Refined by user)', valid: true } };
      }
    } catch (_err) { /* refine picker failed — keep existing selector */ }
  }

  async function handleConfirmAccept() {
    if (!confirmationFlow) return;
    confirmationFlow.accept();
    if (confirmationFlow.phase === 'complete') {
      await handleConfirmationComplete();
    } else {
      const state = confirmationFlow.getState();
      state?.selector ? await highlightSelector(state.selector) : await clearRefinementHighlights();
    }
  }

  async function handleConfirmRefine() {
    if (!confirmationFlow || isRefining) return;
    const state = confirmationFlow.getState();
    if (!state) return;
    isRefining = true;
    try {
      const pickerResult = await refineSelector(state.selector || '');
      const newSelector = mergeSelectorSources(state.selector || '', pickerResult);
      confirmationFlow.refine(newSelector);
      if (confirmationFlow.phase === 'complete') {
        await handleConfirmationComplete();
      } else {
        const nextState = confirmationFlow.getState();
        nextState?.selector ? await highlightSelector(nextState.selector) : await clearRefinementHighlights();
      }
    } catch {} finally {
      isRefining = false;
    }
  }

  async function handleConfirmBack() {
    if (!confirmationFlow) return;
    confirmationFlow.back();
    const state = confirmationFlow.getState();
    if (state?.selector) await highlightSelector(state.selector);
  }

  async function handleConfirmCancel() {
    if (!confirmationFlow) return;
    confirmationFlow.cancel();
    await clearRefinementHighlights();
    confirmationFlow = null;
    phase = 'review';
  }

  async function handleConfirmationComplete() {
    if (!confirmationFlow || !discoveryResult) return;
    const confirmedSelectors = confirmationFlow.getConfirmedSelectors();
    discoveryResult = { ...discoveryResult, selectors: { ...discoveryResult.selectors, ...confirmedSelectors } };
    await clearRefinementHighlights();
    confirmationFlow = null;
    phase = 'review';
    showSaveDialog = true;
  }

  async function handleSaveProfile() {
    if (!discoveryResult || !profileName.trim()) return;
    phase = 'saving';
    saveStatus = 'Saving...';
    try {
      const storage = new ProfileStorageImpl();
      let currentUrl = 'example.com';
      try { currentUrl = await getEmbeddedUrl(); } catch {}
      let urlPattern = currentUrl;
      try { const u = new URL(currentUrl); urlPattern = u.hostname + u.pathname; } catch {}

      const newProfile = discoveryResultToSiteProfile(discoveryResult, undefined, {
        name: profileName.trim(),
        urlPatterns: [urlPattern],
      });
      await storage.saveProfile(newProfile);
      saveStatus = 'Saved!';
      phase = 'idle';
      showSaveDialog = false;
      onProfileSaved(newProfile);
      setTimeout(() => {
        saveStatus = '';
        discoveryResult = null;
        validationResults = null;
      }, 2000);
    } catch (err) {
      phase = 'review';
      saveStatus = '';
      error = err instanceof Error ? err.message : String(err);
    }
  }
</script>

<section class="discovery-panel">
  <div class="header">
    <h3>Discovery</h3>
    <p class="description">Automatically detect page elements using AI.</p>
  </div>

  <DiscoveryModeSelector bind:mode />

  {#if phase === 'idle' || phase === 'error'}
    <div class="mode-content">
      {#if mode === 'form'}
        <div class="form-mode-inputs">
          <label class="checkbox"><input type="checkbox" bind:checked={formInput.hasVideoPlayer}> Video Player Present</label>
          <label class="checkbox"><input type="checkbox" bind:checked={formInput.hasInteractiveForms}> Interactive Forms</label>
          <label class="checkbox"><input type="checkbox" bind:checked={formInput.hasNavigation}> Navigation Controls</label>
          <label class="checkbox"><input type="checkbox" bind:checked={formInput.hasQuizElements}> Quiz Elements</label>
          <div class="input-group">
            <label for="page-description">Page Description (Optional)</label>
            <input id="page-description" type="text" bind:value={formInput.pageDescription} placeholder="e.g. YouTube video page">
          </div>
        </div>
      {:else if mode === 'chat'}
        <DiscoveryChat bind:chatState onChatSubmit={handleChatSubmit} />
      {:else if mode === 'example'}
        <div class="example-mode-ui"><p>Click elements on the page to teach the AI (Coming Soon)</p></div>
      {/if}
    </div>
  {/if}

  {#if staleWarning}
    <div class="stale-warning">
      <small>⚠ Page has changed — discovery results may be outdated.</small>
      <button class="btn-link" onclick={() => { staleWarning = false; }}>Dismiss</button>
    </div>
  {/if}

  {#if phase === 'idle' || phase === 'error'}
    <button class="btn-primary full-width" onclick={handleStartDiscovery}>Discover Selectors</button>
  {/if}

  {#if phase === 'running'}
    <DiscoveryProgress {progressMessage} {progressPercent} />
  {/if}

  {#if (phase === 'review' || phase === 'saving') && discoveryResult}
    {#if discoveryResult}
      <div class="post-discovery-tools">
        {#if discoveryResult.heuristicMatch}
          <div class="engine-badge heuristic">⚡ Detected automatically: {discoveryResult.heuristicMatch.patternName}</div>
        {:else}
          <div class="engine-badge ai">
            🤖 Analyzed by AI
            {#if discoveryResult.snapshotMetadata}
              <span class="meta">({discoveryResult.snapshotMetadata.nodesIncluded}/{discoveryResult.snapshotMetadata.totalVisited} nodes)</span>
            {/if}
          </div>
        {/if}
      </div>
    {/if}

    <DiscoveryResults
      {discoveryResult}
      {validationResults}
      returnToBatch={false}
      onRefine={handleRefine}
      onConfirm={handleStartConfirmation}
      onSave={() => { showSaveDialog = true; }}
      onDiscard={() => { phase = 'idle'; }}
    />
  {/if}

  {#if phase === 'confirming' && confirmationFlow}
    <DiscoveryConfirmation
      {confirmationFlow}
      {isRefining}
      selectorLabels={SELECTOR_LABELS}
      onAccept={handleConfirmAccept}
      onRefine={handleConfirmRefine}
      onBack={handleConfirmBack}
      onCancel={handleConfirmCancel}
    />
  {/if}

  <DiscoverySaveDialog
    isOpen={showSaveDialog}
    bind:profileName
    isSaving={phase === 'saving'}
    onSave={handleSaveProfile}
    onCancel={() => { showSaveDialog = false; }}
  />

  {#if error}
    <div class="error-banner"><span class="error-icon">⚠️</span> <span class="error-text">{error}</span></div>
  {/if}
</section>

<style>
  .discovery-panel { display: flex; flex-direction: column; gap: var(--spacing-3); padding: var(--spacing-2); }
  .header h3 { margin: 0 0 var(--spacing-1) 0; font-size: 1rem; color: var(--text-primary); }
  .description { margin: 0; font-size: 0.85rem; color: var(--text-secondary); }
  .mode-content { background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: var(--spacing-3); margin-bottom: var(--spacing-3); }
  .form-mode-inputs { display: flex; flex-direction: column; gap: var(--spacing-2); }
  .checkbox { display: flex; align-items: center; gap: var(--spacing-2); font-size: 0.9rem; color: var(--text-primary); }
  .input-group { display: flex; flex-direction: column; gap: 4px; }
  .input-group label { font-size: 0.85rem; color: var(--text-secondary); }
  .input-group input { padding: 6px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); }
  .engine-badge { font-size: 0.85rem; padding: 4px 8px; border-radius: var(--radius-sm); margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: 6px; }
  .engine-badge.heuristic { background: var(--color-primary-bg); color: var(--color-primary); border: 1px solid var(--color-primary); }
  .engine-badge.ai { background: var(--color-accent-bg); color: var(--color-accent); border: 1px solid var(--color-accent-border); }
  .meta { opacity: 0.7; font-size: 0.8em; }
  .stale-warning { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 6px 10px; background: var(--color-warning-bg); border: 1px solid var(--color-warning-border); border-radius: 4px; margin-bottom: 8px; color: var(--color-warning); }
  .btn-link { background: transparent; border: none; color: var(--color-warning); cursor: pointer; font-size: 0.85rem; text-decoration: underline; padding: 0; }
  .btn-primary { padding: var(--spacing-2) var(--spacing-3); border-radius: var(--radius-md); font-weight: 500; cursor: pointer; font-size: 0.9rem; transition: all 0.2s; background: var(--color-primary); color: var(--color-primary-text); border: none; }
  .btn-primary:hover { background: var(--color-primary-hover); }
  .full-width { width: 100%; }
  .error-banner { display: flex; align-items: center; gap: var(--spacing-2); padding: var(--spacing-2); background: var(--color-danger-hover); border: 1px solid var(--color-danger); border-radius: var(--radius-md); color: var(--color-danger); font-size: 0.85rem; }
</style>

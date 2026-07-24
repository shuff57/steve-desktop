<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedImports: Svelte template uses imported components */
  /* biome-ignore-all lint/correctness/noUnusedVariables: Svelte template uses script bindings */
  import AutomateRunner from '../components/grading/AutomateRunner.svelte';
  import SkillRunner from '../components/grading/SkillRunner.svelte';
  import SiteMapper from '../components/grading/SiteMapper.svelte';
  import TeachMode from '../components/grading/TeachMode.svelte';
  import OgreGrader from '../components/grading/OgreGrader.svelte';
  import ProviderSelector from '../components/grading/ProviderSelector.svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { engineForProvider } from '../lib/agent-cli';

  let {
    isCollapsed = $bindable(false),
    width = $bindable(400),
    pageUrl = '',
    tabId = '',
    // With one panel mounted per browser tab, only the active one may handle
    // global keyboard shortcuts — otherwise Ctrl+B toggles once per instance.
    active = true,
  } = $props();

  // One engine selection for the whole panel — shared across the Agent, Discovery, and
  // Skills tabs so switching tabs never changes the AI engine. Passed down as props.
  let activeMode = $state('agent');  // 'agent' | 'discovery' | 'teach' | 'skills' | 'ogre'

  // The OGRE sidebar entry opens grading here rather than on its own route: "load students
  // from page" means the page on screen, so the controls have to sit beside it.
  //
  // Two paths because the drawer may have been closed when the request was made, in which
  // case no panel existed to hear the event: the live event covers an open drawer, the
  // sessionStorage handoff covers one opening for the first time. Read once and clear, so
  // it steers only the navigation that set it.
  const pendingMode = typeof sessionStorage !== 'undefined' ? sessionStorage.getItem('steve:panel-mode') : null;
  if (pendingMode) {
    activeMode = pendingMode;
    sessionStorage.removeItem('steve:panel-mode');
  }

  $effect(() => {
    const open = (e: Event) => {
      if (!active) return;
      const mode = (e as CustomEvent<{ mode?: string }>).detail?.mode;
      if (mode) {
        activeMode = mode;
        sessionStorage.removeItem('steve:panel-mode');
      }
    };
    window.addEventListener('steve:action-panel', open);
    return () => window.removeEventListener('steve:action-panel', open);
  });
  let activeProvider = $state('');
  let activeModel = $state('');

  // Claude sign-in can lapse; when the chosen engine is Claude and its login is gone, prompt here
  // (where you actually run things) rather than on the Dashboard. Re-checked on tab/engine change.
  let claudeLoggedIn = $state(true);
  $effect(() => {
    void activeMode; // re-check when switching tabs
    if (engineForProvider(activeProvider) !== 'claude') return;
    invoke<{ loggedIn?: boolean }>('claude_auth_status')
      .then((s) => (claudeLoggedIn = !!s?.loggedIn))
      .catch(() => (claudeLoggedIn = false));
  });
  const needsClaudeSignin = $derived(engineForProvider(activeProvider) === 'claude' && !claudeLoggedIn);

  // Bottom status: live CLI session + a context-usage ticker. Both ride the same
  // 'agent-cli-progress' stream the runners already emit — session id from every line, context
  // tokens from the engines' usage events. Resets each new session so the ticker tracks the run.
  let sessionId = $state<string | null>(null);
  let ctxTokens = $state<number | null>(null);
  $effect(() => {
    let unlisten: (() => void) | undefined;
    listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
      if (ev.payload.sessionId !== sessionId) {
        sessionId = ev.payload.sessionId; // a new run → reset the ticker
        ctxTokens = null;
      }
      const t = ctxTokensFromLine(ev.payload.line);
      if (t != null) ctxTokens = t;
    }).then((u) => (unlisten = u));
    return () => unlisten?.();
  });

  // "Context used" = the tokens the model actually read this turn (input + any cache), which is what
  // consumes the window. opencode reports it on step_finish; claude on each assistant turn.
  function ctxTokensFromLine(line: string): number | null {
    try {
      const ev = JSON.parse(line);
      if (ev?.type === 'step_finish' && ev.part?.tokens) {
        const tk = ev.part.tokens;
        return tk.input ?? tk.total ?? null; // opencode
      }
      const u = ev?.usage ?? ev?.message?.usage; // claude result / assistant
      if (u) return (u.input_tokens ?? 0) + (u.cache_read_input_tokens ?? 0) + (u.cache_creation_input_tokens ?? 0);
    } catch {
      /* not JSON */
    }
    return null;
  }

  function ctxWindow(model: string): number | null {
    const m = (model || '').toLowerCase();
    if (m === 'opus[1m]' || m === 'fable' || m === 'sonnet') return 1_000_000;
    if (m === 'opus' || m === 'haiku') return 200_000;
    return null; // opencode / unknown window
  }

  function fmtTokens(n: number): string {
    if (n >= 1_000_000) return `${(n / 1_000_000).toFixed(2)}M`;
    if (n >= 1000) return `${(n / 1000).toFixed(1)}K`;
    return String(n);
  }

  const ctxMax = $derived(ctxWindow(activeModel));
  const ctxPct = $derived(ctxMax && ctxTokens != null ? Math.min(100, (ctxTokens / ctxMax) * 100) : null);
  // Within the Agent tab: 'automate' (map-aware, review-gated CLI over CDP) or 'chat' (legacy loop).

  // Resize logic
  let isResizing = $state(false);
  let resizeStartX = 0;
  let resizeStartWidth = 0;
  
  // Non-reactive — intentionally plain let, NOT $state()
  let rafId: number | undefined;
  let pendingWidth: number = 0;

  function handleResizeStart(e: MouseEvent) {
    e.preventDefault();
    isResizing = true;
    resizeStartX = e.clientX;
    resizeStartWidth = width;
    
    window.addEventListener('mousemove', handleResizeMove);
    window.addEventListener('mouseup', handleResizeEnd);
    document.body.style.cursor = 'ew-resize';
    document.body.style.userSelect = 'none';
  }

  function handleResizeKeydown(e: KeyboardEvent) {
    const RESIZE_INCREMENT = 20; // pixels to adjust per keypress
    
    if (e.key === 'ArrowLeft') {
      e.preventDefault();
      width = Math.min(width + RESIZE_INCREMENT, window.innerWidth * 0.8);
    } else if (e.key === 'ArrowRight') {
      e.preventDefault();
      width = Math.max(width - RESIZE_INCREMENT, 280);
    } else if (e.key === 'Home') {
      e.preventDefault();
      width = 280; // minimum width
    } else if (e.key === 'End') {
      e.preventDefault();
      width = window.innerWidth * 0.8; // maximum width
    }
  }

  function handleResizeMove(e: MouseEvent) {
    if (!isResizing) return;
    
    let newWidth = window.innerWidth - e.clientX;
    
    const minWidth = 280;
    const maxWidth = window.innerWidth * 0.8;
    
    pendingWidth = Math.max(minWidth, Math.min(newWidth, maxWidth));
    
    if (rafId === undefined) {
      rafId = requestAnimationFrame(() => {
        width = pendingWidth;
        rafId = undefined;
      });
    }
  }

  function handleResizeEnd() {
    isResizing = false;
    if (rafId !== undefined) {
      cancelAnimationFrame(rafId);
      rafId = undefined;
      width = pendingWidth;
    }
    window.removeEventListener('mousemove', handleResizeMove);
    window.removeEventListener('mouseup', handleResizeEnd);
    document.body.style.cursor = '';
    document.body.style.userSelect = '';
  }
</script>

<div class="action-panel" class:collapsed={isCollapsed} class:resizing={isResizing} style="width: {isCollapsed ? '48px' : width + 'px'}">
  {#if !isCollapsed}
    <!-- svelte-ignore a11y_no_noninteractive_tabindex -->
    <!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
    <div 
      class="resize-handle"
      role="separator" 
      aria-orientation="vertical"
      aria-label="Resize panel (use arrow keys, Home, or End; drag with mouse)"
      aria-valuenow={width}
      aria-valuemin={280}
      aria-valuemax={Math.floor(typeof window !== 'undefined' ? window.innerWidth * 0.8 : 800)}
      tabindex="0"
      onmousedown={handleResizeStart}
      onkeydown={handleResizeKeydown}
    ></div>
  {/if}

  <div class="panel-header">
    {#if !isCollapsed}
      <h2 class="panel-title">S.T.E.V.E.</h2>
    {/if}
    <button class="icon-btn toggle-btn collapse-btn" onclick={() => isCollapsed = !isCollapsed} title={isCollapsed ? "Expand Panel (Ctrl+B)" : "Collapse Panel (Ctrl+B / Esc)"}>
      {#if isCollapsed}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
      {:else}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      {/if}
    </button>
  </div>

  <div class="panel-tabs">
    <button class="mode-tab" class:active={activeMode === 'agent'} onclick={() => activeMode = 'agent'}>
      <span class="mode-icon">🤖</span>
      {#if !isCollapsed}<span class="mode-label">Agent</span>{/if}
    </button>
    <button class="mode-tab" class:active={activeMode === 'discovery'} onclick={() => activeMode = 'discovery'}>
      <span class="mode-icon">🔍</span>
      {#if !isCollapsed}<span class="mode-label">Discovery</span>{/if}
    </button>
    <button class="mode-tab" class:active={activeMode === 'teach'} onclick={() => activeMode = 'teach'}>
      <span class="mode-icon">🎓</span>
      {#if !isCollapsed}<span class="mode-label">Teach</span>{/if}
    </button>
    <button class="mode-tab" class:active={activeMode === 'skills'} onclick={() => activeMode = 'skills'}>
      <span class="mode-icon">▶</span>
      {#if !isCollapsed}<span class="mode-label">Skills</span>{/if}
    </button>
    <button class="mode-tab" class:active={activeMode === 'ogre'} onclick={() => activeMode = 'ogre'}>
      <span class="mode-icon">📝</span>
      {#if !isCollapsed}<span class="mode-label">OGRE</span>{/if}
    </button>
  </div>
  
  {#if !isCollapsed}
    <div class="panel-engine">
      <ProviderSelector bind:provider={activeProvider} bind:model={activeModel} />
    </div>
    {#if needsClaudeSignin}
      <div class="signin-notice">⚠ You're signed out of Claude. Open <strong>Settings → AI Accounts</strong> to sign back in.</div>
    {/if}
    <div class="panel-content">
      {#if activeMode === 'agent'}
        <AutomateRunner provider={activeProvider} model={activeModel} />
      {:else if activeMode === 'skills'}
        <SkillRunner provider={activeProvider} model={activeModel} />
      {:else if activeMode === 'teach'}
        <TeachMode {pageUrl} provider={activeProvider} model={activeModel} />
      {:else if activeMode === 'ogre'}
        <OgreGrader {pageUrl} provider={activeProvider} model={activeModel} />
      {:else}
        <SiteMapper {pageUrl} provider={activeProvider} model={activeModel} />
      {/if}
    </div>
    <div class="panel-footer">
      <span title="Current CLI session">Session: {sessionId ? sessionId.slice(0, 8) : '—'}</span>
      <span class="ctx" title="Context used this session vs the model's window">
        Context:
        {#if ctxTokens != null}
          {fmtTokens(ctxTokens)}{#if ctxMax}<span class="ctx-dim"> / {fmtTokens(ctxMax)}</span>{/if}
          {#if ctxPct != null}
            <span class="ctx-bar"><span class="ctx-fill" style="width:{ctxPct}%"></span></span>
            {ctxPct < 10 ? ctxPct.toFixed(1) : Math.round(ctxPct)}%
          {/if}
        {:else}
          <span class="ctx-dim">{ctxMax ? `0 / ${fmtTokens(ctxMax)}` : (activeModel || '—')}</span>
        {/if}
      </span>
    </div>
  {/if}
</div>

<svelte:window onkeydown={(e) => {
  if (!active) return;
  const target = e.target as HTMLElement;
  if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.isContentEditable) {
    if (e.key !== 'Escape') return;
  }

  if (e.ctrlKey || e.metaKey) {
    if (e.key.toLowerCase() === 'b') {
      e.preventDefault();
      isCollapsed = !isCollapsed;
    }
  } else if (e.key === 'Escape') {
    if (!isCollapsed) {
      e.preventDefault();
      isCollapsed = true;
    }
  }
}} />

<style>
  .action-panel {
    position: relative;
    height: 100%;
    background-color: var(--bg-sidebar);
    border-left: 1px solid var(--border-color);
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
    display: flex; flex-direction: column;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden; flex-shrink: 0;
  }
  .action-panel.resizing {
    transition: none !important;
  }

  .panel-header {
    height: var(--header-height, 64px);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 var(--spacing-4);
    border-bottom: 1px solid var(--border-color); flex-shrink: 0;
  }
  .action-panel.collapsed .panel-header {
    justify-content: center;
    padding: 0 var(--spacing-1);
  }

  .panel-title {
    font-size: 1.1rem; margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }

  .toggle-btn {
    background: transparent; border: none;
    color: var(--text-secondary); cursor: pointer;
    padding: var(--spacing-2); border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
  }
  .toggle-btn:hover {
    background-color: var(--bg-hover); color: var(--text-primary);
  }

  .panel-tabs {
    display: grid; grid-template-columns: repeat(5, 1fr); padding: var(--spacing-2); gap: var(--spacing-1);
    background-color: var(--bg-sidebar);
    border-bottom: 1px solid var(--border-color); flex-shrink: 0;
  }
  .action-panel.collapsed .panel-tabs {
    grid-template-columns: 1fr; padding: var(--spacing-2) var(--spacing-1);
  }

  /* Five tabs in one row: stack icon over label so a wide label ("Discovery") stops
     clipping at the panel's usual width, instead of wrapping the strip to two rows. */
  .mode-tab {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 2px; padding: var(--spacing-2) 2px;
    background: transparent; border: none; border-radius: var(--radius-md);
    cursor: pointer; color: var(--text-secondary);
    font-size: 0.72rem; font-weight: 500; transition: all 0.2s;
  }
  .action-panel.collapsed .mode-tab { flex-direction: row; padding: var(--spacing-2); justify-content: center; }
  .action-panel.collapsed .mode-icon { font-size: 1.2rem; }
  .mode-icon { font-size: 1.05rem; line-height: 1; }
  .mode-label { white-space: nowrap; }
  .mode-tab:hover { background-color: var(--bg-hover); color: var(--text-primary); }
  .mode-tab.active { background-color: var(--bg-active); color: var(--color-primary); font-weight: 600; }

  .panel-engine {
    padding: var(--spacing-2) var(--spacing-4) 0;
  }

  .signin-notice {
    margin: var(--spacing-2) var(--spacing-4) 0;
    padding: var(--spacing-2) var(--spacing-4);
    border-radius: var(--radius-md);
    background: var(--color-warning-bg, var(--bg-hover));
    color: var(--color-warning, var(--text-primary));
    border: 1px solid var(--color-warning-border, var(--border-color));
    font-size: var(--font-size-sm, 0.85rem);
  }

  .panel-content {
    flex: 1; overflow-y: auto;
    display: flex; flex-direction: column;
    padding: var(--spacing-4); gap: var(--spacing-4);
  }

  .panel-footer {
    flex-shrink: 0;
    display: flex;
    justify-content: space-between;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-4);
    border-top: 1px solid var(--color-border, var(--border-color));
    font-size: 0.72rem;
    color: var(--color-text-muted, var(--text-secondary));
    font-family: var(--font-mono, monospace);
    white-space: nowrap;
    overflow: hidden;
  }

  .panel-footer span {
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .ctx {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    overflow: visible;
  }

  .ctx-dim {
    color: var(--color-text-muted, var(--text-tertiary));
    opacity: 0.7;
  }

  .ctx-bar {
    display: inline-block;
    width: 42px;
    height: 5px;
    border-radius: 3px;
    background: var(--bg-hover, rgba(128, 128, 128, 0.25));
    overflow: hidden;
    flex-shrink: 0;
  }

  .ctx-fill {
    display: block;
    height: 100%;
    background: var(--color-primary, #3b82f6);
    transition: width 0.3s ease;
  }

  .resize-handle {
    position: absolute;
    left: 0;
    top: 0;
    width: 6px;
    height: 100%;
    cursor: ew-resize;
    z-index: 100;
    transition: background-color 0.2s;
  }
  
  .resize-handle:hover,
  .resize-handle:active {
    background-color: var(--color-primary);
  }
</style>

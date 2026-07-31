<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedImports: Svelte template uses imported components */
  import type { Snippet } from 'svelte';
  import ProviderSelector from '../grading/ProviderSelector.svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { engineForProvider } from '../../lib/agent-cli';

  export type ShellMode = { id: string; icon: string; label: string };

  let {
    title = 'S.T.E.V.E.',
    modes = [] as ShellMode[],
    activeMode = $bindable(''),
    isCollapsed = $bindable(false),
    width = $bindable(400),
    // Engine choice lives here so it is one selection for the whole panel — switching
    // tabs never changes the AI engine. The owner binds it and passes it to each mode.
    provider = $bindable(''),
    model = $bindable(''),
    providerDisabled = false,
    // 'drawer' hangs off the window edge (the browser); 'column' is a card sitting in a row
    // of panes (MOM). Only the chrome differs — every behaviour below is shared.
    variant = 'drawer' as 'drawer' | 'column',
    // With one shell mounted per context (per browser tab, per tool), only the active
    // one may handle global keyboard shortcuts — otherwise Ctrl+B toggles once per instance.
    active = true,
    children,
  }: {
    title?: string;
    modes?: ShellMode[];
    activeMode?: string;
    isCollapsed?: boolean;
    width?: number;
    provider?: string;
    model?: string;
    providerDisabled?: boolean;
    variant?: 'drawer' | 'column';
    active?: boolean;
    children?: Snippet;
  } = $props();

  // Claude sign-in can lapse; when the chosen engine is Claude and its login is gone, prompt here
  // (where you actually run things) rather than on the Dashboard. Re-checked on tab/engine change.
  let claudeLoggedIn = $state(true);
  $effect(() => {
    void activeMode; // re-check when switching tabs
    if (engineForProvider(provider) !== 'claude') return;
    invoke<{ loggedIn?: boolean }>('claude_auth_status')
      .then((s) => (claudeLoggedIn = !!s?.loggedIn))
      .catch(() => (claudeLoggedIn = false));
  });
  const needsClaudeSignin = $derived(engineForProvider(provider) === 'claude' && !claudeLoggedIn);

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

  function ctxWindow(m: string): number | null {
    const s = (m || '').toLowerCase();
    if (s === 'opus' || s === 'opus[1m]' || s === 'fable' || s === 'sonnet') return 1_000_000;
    if (s === 'haiku') return 200_000;
    return null; // opencode / unknown window
  }

  function fmtTokens(n: number): string {
    if (n >= 1_000_000) return `${(n / 1_000_000).toFixed(2)}M`;
    if (n >= 1000) return `${(n / 1000).toFixed(1)}K`;
    return String(n);
  }

  const ctxMax = $derived(ctxWindow(model));
  const ctxPct = $derived(ctxMax && ctxTokens != null ? Math.min(100, (ctxTokens / ctxMax) * 100) : null);

  // Resize logic
  let isResizing = $state(false);
  let panelEl: HTMLElement | undefined = $state();

  // Non-reactive — intentionally plain let, NOT $state()
  let rafId: number | undefined;
  let pendingWidth: number = 0;
  // Drag width is measured from the panel's own right edge, which stays put while the left edge
  // moves. The window edge only works for a drawer flush against it — the MOM rail sits inside
  // page padding, and using innerWidth there makes the panel jump on mousedown.
  //
  // The grab point is folded in too: the handle is 6px wide, so grabbing its middle and holding
  // still would otherwise snap the edge to the cursor. The edge follows the cursor's DELTA.
  let dragAnchorX = 0;

  function handleResizeStart(e: MouseEvent) {
    e.preventDefault();
    isResizing = true;
    const r = panelEl?.getBoundingClientRect();
    dragAnchorX = r ? r.right + (e.clientX - r.left) : window.innerWidth;
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

    let newWidth = dragAnchorX - e.clientX;

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

<div bind:this={panelEl} class="action-panel {variant}" class:collapsed={isCollapsed} class:resizing={isResizing} style="width: {isCollapsed ? '48px' : width + 'px'}">
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
      <h2 class="panel-title">{title}</h2>
    {/if}
    <button class="icon-btn toggle-btn collapse-btn" onclick={() => isCollapsed = !isCollapsed} title={isCollapsed ? "Expand Panel (Ctrl+B)" : "Collapse Panel (Ctrl+B / Esc)"}>
      {#if isCollapsed}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
      {:else}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      {/if}
    </button>
  </div>

  {#if modes.length}
    <div class="panel-tabs" style="grid-template-columns: {isCollapsed ? '1fr' : `repeat(${modes.length}, 1fr)`}">
      {#each modes as mode (mode.id)}
        <button class="mode-tab" class:active={activeMode === mode.id} onclick={() => activeMode = mode.id}>
          <span class="mode-icon">{mode.icon}</span>
          {#if !isCollapsed}<span class="mode-label">{mode.label}</span>{/if}
        </button>
      {/each}
    </div>
  {/if}

  {#if !isCollapsed}
    <div class="panel-engine">
      <ProviderSelector bind:provider bind:model disabled={providerDisabled} />
    </div>
    {#if needsClaudeSignin}
      <div class="signin-notice">⚠ You're signed out of Claude. Open <strong>Settings → AI Accounts</strong> to sign back in.</div>
    {/if}
    <div class="panel-content">
      {@render children?.()}
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
          <span class="ctx-dim">{ctxMax ? `0 / ${fmtTokens(ctxMax)}` : (model || '—')}</span>
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
    display: flex; flex-direction: column;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden; flex-shrink: 0;
  }
  /* Hangs off the window edge: a lit seam on the left, shadow falling into the page. */
  .action-panel.drawer {
    border-left: 1px solid var(--border-color);
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
  }
  /* One card among sibling panes: rounded, no shadow, and the header shrinks — a 64px title
     bar is header-height only because the drawer aligns with the app's own header. */
  .action-panel.column {
    border: 1px solid var(--border-color);
    border-radius: 8px;
  }
  .action-panel.column .panel-header { height: 40px; }
  .action-panel.column .panel-title { font-size: 0.85rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.05em; }
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
    display: grid; padding: var(--spacing-2); gap: var(--spacing-1);
    background-color: var(--bg-sidebar);
    border-bottom: 1px solid var(--border-color); flex-shrink: 0;
  }
  .action-panel.collapsed .panel-tabs {
    padding: var(--spacing-2) var(--spacing-1);
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

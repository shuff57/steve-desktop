<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedImports: Svelte template uses imported components */
  /* biome-ignore-all lint/correctness/noUnusedVariables: Svelte template uses script bindings */
  import AgentChat from '../components/grading/AgentChat.svelte';
  import SkillRunner from '../components/grading/SkillRunner.svelte';
  import SiteMapper from '../components/grading/SiteMapper.svelte';
  import ProviderSelector from '../components/grading/ProviderSelector.svelte';

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
  let activeMode = $state('agent');  // 'agent' | 'discovery' | 'skills'
  let activeProvider = $state('');
  let activeModel = $state('');

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
    <button class="mode-tab" class:active={activeMode === 'skills'} onclick={() => activeMode = 'skills'}>
      <span class="mode-icon">▶</span>
      {#if !isCollapsed}<span class="mode-label">Skills</span>{/if}
    </button>
  </div>
  
  {#if !isCollapsed}
    <div class="panel-engine">
      <ProviderSelector bind:provider={activeProvider} bind:model={activeModel} />
    </div>
    <div class="panel-content">
      {#if activeMode === 'agent'}
        <AgentChat provider={activeProvider} model={activeModel} />
      {:else if activeMode === 'skills'}
        <SkillRunner provider={activeProvider} model={activeModel} />
      {:else}
        <SiteMapper {pageUrl} provider={activeProvider} model={activeModel} />
      {/if}
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
    display: grid; grid-template-columns: repeat(3, 1fr); padding: var(--spacing-2); gap: var(--spacing-1);
    background-color: var(--bg-sidebar);
    border-bottom: 1px solid var(--border-color); flex-shrink: 0;
  }
  .action-panel.collapsed .panel-tabs {
    grid-template-columns: 1fr; padding: var(--spacing-2) var(--spacing-1);
  }

  .mode-tab {
    display: flex; align-items: center; justify-content: center;
    gap: var(--spacing-2); padding: var(--spacing-2);
    background: transparent; border: none; border-radius: var(--radius-md);
    cursor: pointer; color: var(--text-secondary);
    font-size: 0.9rem; font-weight: 500; transition: all 0.2s;
  }
  .action-panel.collapsed .mode-tab { padding: var(--spacing-2); justify-content: center; }
  .action-panel.collapsed .mode-icon { font-size: 1.2rem; }
  .mode-tab:hover { background-color: var(--bg-hover); color: var(--text-primary); }
  .mode-tab.active { background-color: var(--bg-active); color: var(--color-primary); font-weight: 600; }

  .panel-engine {
    padding: var(--spacing-2) var(--spacing-4) 0;
  }

  .panel-content {
    flex: 1; overflow-y: auto;
    display: flex; flex-direction: column;
    padding: var(--spacing-4); gap: var(--spacing-4);
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

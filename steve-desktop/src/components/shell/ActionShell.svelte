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
    /**
     * Widest this panel may get. A drawer over a webview can fairly take 80% of the window, but a
     * column among sibling panes must leave its neighbours something to be — left unbounded, the
     * MOM rail could squeeze the preview it exists to work alongside down to a couple of hundred
     * pixels. null keeps the 80vw default.
     */
    maxWidth = null as number | null,
    // 'drawer' hangs off the window edge (the browser); 'column' is a card sitting in a row
    // of panes (MOM). Only the chrome differs — every behaviour below is shared.
    variant = 'drawer' as 'drawer' | 'column',
    // With one shell mounted per context (per browser tab, per tool), only the active
    // one may handle global keyboard shortcuts — otherwise Ctrl+B toggles once per instance.
    active = true,
    /**
     * The run this shell's own content is driving, when the owner tracks one. The progress channel is
     * global, so without this the ticker latches onto whichever run emitted last and reports a
     * stranger's context under this panel. null keeps the old adopt-anything behaviour for owners
     * that track nothing.
     */
    runSession = null as string | null,
    /**
     * Agent status for the live indicator. Child components (AutomateRunner etc.) bind this
     * to their own phase/status so the shell shows the PageAgent-style animated dot + status
     * text + gradient sweep. Values: 'idle' | 'running' | 'thinking' | 'executing' |
     * 'awaiting-approval' | 'completed' | 'error' | 'stopped'
     */
    agentStatus = $bindable('idle' as string),
    /**
     * Short status text shown next to the indicator dot (e.g. "Planning…", "Executing…").
     * When null the shell derives it from agentStatus.
     */
    agentStatusText = $bindable('' as string),
    /**
     * History cards to render in the panel (PageAgent-style color-coded step log).
     * Each entry: { icon, text, type } where type is one of 'default'|'input'|'output'|
     * 'question'|'observation'|'error'|'success'.
     */
    // $bindable like its two siblings above: ActionPanel does `bind:history`, and
    // without this the binding is a hard compile error rather than a silent no-op.
    history = $bindable([] as { icon: string; text: string; type?: string; meta?: string }[]),
    /**
     * Step count for the pill's progress chip — the overlay's `.pa-prog`. A plain number
     * because the rail's jobs have no known length; pass a string like "6/15" where one is.
     */
    agentProgress = '' as string,
    /**
     * Stop for the run in flight. The overlay puts Stop inside the pill, next to the thing
     * it stops; given one, so does this. Owners that cannot be stopped omit it and no
     * button is drawn.
     */
    onStop = null as (() => void) | null,
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
    maxWidth?: number | null;
    variant?: 'drawer' | 'column';
    active?: boolean;
    runSession?: string | null;
    agentStatus?: string;
    agentStatusText?: string;
    history?: { icon: string; text: string; type?: string; meta?: string }[];
    agentProgress?: string;
    onStop?: (() => void) | null;
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
      if (!active) return;
      if (runSession && ev.payload.sessionId !== runSession) return;
      if (ev.payload.sessionId !== sessionId) {
        sessionId = ev.payload.sessionId; // a new run → reset the ticker
        ctxTokens = null;
      }
      const t = ctxTokensFromLine(ev.payload.line);
      if (t != null) ctxTokens = t;
    }).then((u) => (unlisten = u));
    return () => unlisten?.();
  });

  // ── PageAgent-style live status indicator ──────────────────────────────────────
  // Map agent status to indicator CSS class and default status text.
  const statusClass = $derived(
    agentStatus === 'running' || agentStatus === 'thinking' ? 'thinking' :
    agentStatus === 'executing' ? 'executing' :
    agentStatus === 'awaiting-approval' ? 'awaiting' :
    agentStatus === 'completed' ? 'completed' :
    agentStatus === 'error' ? 'error' :
    agentStatus === 'stopped' ? 'stopped' :
    'idle'
  );
  const defaultStatusText = $derived(
    agentStatus === 'idle' ? 'Ready' :
    agentStatus === 'running' ? 'Running…' :
    agentStatus === 'thinking' ? 'Thinking…' :
    agentStatus === 'executing' ? 'Executing…' :
    agentStatus === 'awaiting-approval' ? 'Awaiting your approval' :
    agentStatus === 'completed' ? 'Done' :
    agentStatus === 'error' ? 'Error' :
    agentStatus === 'stopped' ? 'Stopped' :
    ''
  );
  const displayStatusText = $derived(agentStatusText || defaultStatusText);

  /**
   * Busy is narrower than active, and the difference is the glow.
   *
   * The in-page overlay hides its glow the moment the run stops thinking or executing
   * (`page-agent-overlay.ts` — `glow.style.display = thinking || executing`). `agent-active`
   * also covers 'completed' and 'awaiting-approval', so driving the glow off it would leave
   * the panel pulsing after the answer had landed.
   */
  const agentBusy = $derived(
    agentStatus === 'running' || agentStatus === 'thinking' || agentStatus === 'executing',
  );

  /**
   * Who has the wheel, as the overlay's pill shows it — the chip is what makes a handover
   * read as one system changing hands rather than two systems taking turns.
   */
  // Via engineForProvider, not a comparison against 'claude': the selector's id for the Claude
  // CLI is 'anthropic', so matching the engine name left the chip blank on the default provider.
  const roleLabel = $derived(engineForProvider(provider) === 'claude' ? 'Claude' : 'OpenCode');

  /**
   * Fade the status text on change WITHOUT owning its content.
   *
   * The previous version set `statusTextEl.textContent` itself while the template also
   * renders `{displayStatusText}` into that same node — two writers for one text node.
   * Worse, a change arriving mid-animation was pushed to `pendingStatusText` and then
   * dropped: the branch meant to replay it assigned an unused local and "touched"
   * displayStatusText, which does nothing. Observed live on the question rail — a green
   * `completed` dot sitting next to the word "Ready", because the dot is rendered
   * reactively and the text was stale.
   *
   * Svelte owns the text now; this only restarts the fade animation.
   */
  let statusTextEl: HTMLElement | undefined = $state();

  $effect(() => {
    // Re-run whenever the text changes.
    displayStatusText;
    const el = statusTextEl;
    if (!el) return;
    el.classList.remove('fade-in');
    // Force a reflow so re-adding the class restarts the animation rather than being
    // coalesced into a no-op.
    void el.offsetWidth;
    el.classList.add('fade-in');
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
  const MIN_WIDTH = 280;
  const widthCap = $derived(
    Math.max(MIN_WIDTH, maxWidth ?? (typeof window !== 'undefined' ? window.innerWidth * 0.8 : 800)),
  );
  // A stored width can outlive the layout that allowed it — restore 700px into a narrow window and
  // the panel would keep it until dragged. Clamp instead of trusting what was saved.
  $effect(() => {
    if (width > widthCap) width = widthCap;
  });

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
      width = Math.min(width + RESIZE_INCREMENT, widthCap);
    } else if (e.key === 'ArrowRight') {
      e.preventDefault();
      width = Math.max(width - RESIZE_INCREMENT, MIN_WIDTH);
    } else if (e.key === 'Home') {
      e.preventDefault();
      width = MIN_WIDTH;
    } else if (e.key === 'End') {
      e.preventDefault();
      width = widthCap;
    }
  }

  function handleResizeMove(e: MouseEvent) {
    if (!isResizing) return;

    const newWidth = dragAnchorX - e.clientX;
    pendingWidth = Math.max(MIN_WIDTH, Math.min(newWidth, widthCap));

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

<div bind:this={panelEl} class="action-panel {variant}" class:collapsed={isCollapsed} class:resizing={isResizing} class:agent-active={agentStatus !== 'idle' && agentStatus !== 'stopped'} style="width: {isCollapsed ? '48px' : width + 'px'}">
  {#if !isCollapsed}
    <!-- svelte-ignore a11y_no_noninteractive_tabindex -->
    <!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
    <div
      class="resize-handle"
      role="separator"
      aria-orientation="vertical"
      aria-label="Resize panel (use arrow keys, Home, or End; drag with mouse)"
      aria-valuenow={width}
      aria-valuemin={MIN_WIDTH}
      aria-valuemax={Math.floor(widthCap)}
      tabindex="0"
      onmousedown={handleResizeStart}
      onkeydown={handleResizeKeydown}
    ></div>
  {/if}

  <!-- The ambient edge glow, ported from the in-page overlay's `.pa-glow`. It rings the
       panel's own edges the way the overlay rings the viewport — the run is announced by
       the surface lighting up, not by a band of colour competing with the content. -->
  {#if agentBusy}<div class="agent-glow"></div>{/if}

  <!-- Header is chrome only. The status used to live here under a full-bleed gradient
       sweep, which is the same animation the overlay runs BLURRED and BEHIND a 40px pill —
       painted flat across a 400px header it became the loudest object on screen. The pill
       at the foot of the panel carries the presence now. -->
  <div class="panel-header">
    <div class="header-status">
      {#if isCollapsed}
        <span class="status-dot {statusClass}"></span>
      {/if}
    </div>
    <div class="header-controls">
      {#if !isCollapsed}
        <button class="icon-btn toggle-btn collapse-btn" onclick={() => isCollapsed = !isCollapsed} title="Collapse Panel (Ctrl+B / Esc)">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      {:else}
        <button class="icon-btn toggle-btn collapse-btn" onclick={() => isCollapsed = !isCollapsed} title="Expand Panel (Ctrl+B)">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
      {/if}
    </div>
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

    <!-- PageAgent-style history cards -->
    {#if history.length}
      <div class="history-cards">
        <!-- Keyed by position, not content: this is a sliding window over a run log, and a
             retry loop repeats step text verbatim ("Rendering…" on every attempt). Keying on
             text made those a duplicate key — a hard render error — and forced callers to
             stuff a unique value into `meta`, which is a VISIBLE field, so the workaround
             printed a stray index on every card. -->
        {#each history as card, i (i)}
          <div class="hcard" class:input={card.type === 'input'} class:output={card.type === 'output'} class:question={card.type === 'question'} class:observation={card.type === 'observation'} class:error={card.type === 'error'} class:success={card.type === 'success'}>
            <div class="hcard-content">
              <span class="hcard-icon">{card.icon}</span>
              <span class="hcard-text">{card.text}</span>
            </div>
            {#if card.meta}
              <div class="hcard-meta">{card.meta}</div>
            {/if}
          </div>
        {/each}
      </div>
    {/if}

    <div class="panel-content">
      {@render children?.()}
    </div>

    <!-- The overlay's status pill, in the same place relative to the log: the driven page
         stacks recent lines above a bottom-centred pill, and the panel stacks its scrolling
         log above this one. Same 40px height, same radius 12, same four-stop gradient
         blurred behind the head rather than painted across it. -->
    {#if agentStatus !== 'idle'}
      <div class="agent-pill">
        <div class="pill-bg" class:active={agentBusy}></div>
        <div class="pill-head">
          <span class="status-dot {statusClass}"></span>
          {#if roleLabel}<span class="pill-role">{roleLabel}</span>{/if}
          {#if agentProgress}<span class="pill-prog">{agentProgress}</span>{/if}
          <span bind:this={statusTextEl} class="pill-text">{displayStatusText}</span>
          {#if onStop && agentBusy}
            <button class="pill-stop" type="button" onclick={onStop}>Stop</button>
          {/if}
        </div>
      </div>
    {/if}

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
    background: var(--bg-sidebar);
    display: flex; flex-direction: column;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden; flex-shrink: 0;
  }
  .action-panel.drawer {
    border-left: 1px solid var(--border-color);
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
  }
  .action-panel.column {
    border: 1px solid var(--border-color);
    border-radius: 8px;
  }
  .action-panel.column .panel-header { height: 40px; }
  .action-panel.resizing { transition: none !important; }

  /* ── PageAgent-style header with glassmorphism ────────────────────────────── */
  .panel-header {
    position: relative;
    height: var(--header-height, 64px);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 var(--spacing-3) 0 var(--spacing-4);
    border-bottom: 1px solid var(--border-color); flex-shrink: 0;
    overflow: hidden;
    background: rgba(26, 22, 38, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
  }
  .action-panel.collapsed .panel-header {
    justify-content: center;
    padding: 0 var(--spacing-1);
  }

  /* ── Ambient edge glow ─────────────────────────────────────────────────────
     A port of the in-page overlay's `.pa-glow` (page-agent-overlay.ts): one inset
     box-shadow cycling the four AGENT_SWEEP stops over 6s, so the surface being
     driven lights up from its own edges. Spreads are scaled for a ~400px column —
     the overlay's 70px/170px are sized for a viewport and would flood a rail. */
  .agent-glow {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 5;
    border-radius: inherit;
    animation: agent-rainbow 6s linear infinite;
  }
  @keyframes agent-rainbow {
    0%   { box-shadow: inset 0 0 34px 4px rgba(57, 182, 255, 0.42),  inset 0 0 84px 16px rgba(189, 69, 251, 0.2); }
    25%  { box-shadow: inset 0 0 34px 4px rgba(189, 69, 251, 0.42),  inset 0 0 84px 16px rgba(255, 87, 51, 0.2); }
    50%  { box-shadow: inset 0 0 34px 4px rgba(255, 87, 51, 0.42),   inset 0 0 84px 16px rgba(255, 214, 0, 0.2); }
    75%  { box-shadow: inset 0 0 34px 4px rgba(255, 214, 0, 0.42),   inset 0 0 84px 16px rgba(57, 182, 255, 0.2); }
    100% { box-shadow: inset 0 0 34px 4px rgba(57, 182, 255, 0.42),  inset 0 0 84px 16px rgba(189, 69, 251, 0.2); }
  }

  /* ── Status pill ───────────────────────────────────────────────────────────
     The overlay's `.pa-pill`: 40px tall, radius 12, with the four-stop sweep
     running BEHIND it under a 16px blur, then a dark translucent head on top.
     The blur is the whole difference — the same gradient painted flat is a
     rainbow band, and blurred behind a pill it is a glow.
     Stops match AGENT_SWEEP; agent-visual.test.ts fails if they drift. */
  .agent-pill {
    position: relative;
    height: 40px;
    margin: 0 var(--spacing-4) var(--spacing-2);
    border-radius: 12px;
    flex-shrink: 0;
  }
  .pill-bg {
    position: absolute;
    inset: -2px -8px;
    border-radius: 16px;
    filter: blur(16px);
    overflow: hidden;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  .pill-bg.active { opacity: 1; }
  .pill-bg::before,
  .pill-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: linear-gradient(to bottom left, rgb(57, 182, 255), rgb(189, 69, 251), rgb(255, 87, 51), rgb(255, 214, 0), rgb(57, 182, 255));
    animation: sweep 2s linear infinite;
  }
  .pill-bg::after {
    background-image: linear-gradient(to bottom left, rgb(255, 214, 0), rgb(255, 87, 51), rgb(189, 69, 251), rgb(57, 182, 255), rgb(255, 214, 0));
    animation-delay: 1s;
  }
  @keyframes sweep {
    from { transform: translateX(-100%); }
    to { transform: translateX(100%); }
  }
  .pill-head {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.14), 0 0 5px 1px rgba(255, 255, 255, 0.12);
  }
  .pill-role {
    flex: 0 0 auto;
    font-size: 0.66rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    color: #fff;
    background: rgba(255, 255, 255, 0.14);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.22);
    white-space: nowrap;
  }
  /* Tabular figures so a rising count does not jitter the text beside it. */
  .pill-prog {
    flex: 0 0 auto;
    font-size: 0.68rem;
    color: rgba(255, 255, 255, 0.75);
    font-variant-numeric: tabular-nums;
  }
  .pill-stop {
    flex: 0 0 auto;
    height: 24px;
    padding: 0 8px;
    border: 0;
    border-radius: 4px;
    font-size: 0.68rem;
    font-weight: 600;
    cursor: pointer;
    background: rgba(239, 68, 68, 0.2);
    color: rgb(239, 68, 68);
  }
  .pill-stop:hover { background: rgba(239, 68, 68, 0.35); }
  .pill-text {
    flex: 1;
    min-width: 0;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 500;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .header-status {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    min-width: 0;
    position: relative;
    z-index: 1;
  }

  /* Animated status dot — PageAgent-style */
  .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.4);
    transition: background 0.3s, box-shadow 0.3s;
  }
  .status-dot.thinking {
    background: rgb(57, 182, 255);
    box-shadow: 0 0 6px rgb(57, 182, 255);
    animation: dot-pulse 0.8s ease-in-out infinite;
  }
  .status-dot.executing {
    background: rgb(189, 69, 251);
    box-shadow: 0 0 6px rgb(189, 69, 251);
    animation: dot-pulse 0.6s ease-in-out infinite;
  }
  .status-dot.awaiting {
    background: rgb(255, 214, 0);
    box-shadow: 0 0 6px rgb(255, 214, 0);
    animation: dot-pulse 1s ease-in-out infinite;
  }
  .status-dot.completed {
    background: rgb(34, 197, 94);
    box-shadow: 0 0 6px rgb(34, 197, 94);
  }
  .status-dot.error {
    background: rgb(239, 68, 68);
    box-shadow: 0 0 6px rgb(239, 68, 68);
  }
  .status-dot.stopped {
    background: rgba(255, 255, 255, 0.4);
  }
  .status-dot.idle {
    background: rgba(255, 255, 255, 0.3);
  }
  @keyframes dot-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.4); }
  }

  /* The fade rides the pill's text now — same effect, moved with the element. */
  .pill-text.fade-in {
    animation: text-fade-in 0.3s ease forwards;
  }
  @keyframes text-fade-in {
    0% { opacity: 0; transform: translateY(4px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .header-controls {
    display: flex;
    align-items: center;
    gap: 2px;
    position: relative;
    z-index: 1;
  }

  .toggle-btn {
    background: transparent; border: none;
    color: var(--sidebar-text, #b8b1c4); cursor: pointer;
    padding: var(--spacing-1); border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s, color 0.15s;
  }
  .toggle-btn:hover {
    background: var(--sidebar-hover-bg, rgba(255,255,255,0.06));
    color: var(--sidebar-text-active, #f5efe0);
  }

  .panel-tabs {
    display: grid; padding: var(--spacing-2); gap: var(--spacing-1);
    background: var(--bg-sidebar);
    border-bottom: 1px solid var(--border-color); flex-shrink: 0;
  }
  .action-panel.collapsed .panel-tabs {
    padding: var(--spacing-2) var(--spacing-1);
  }

  .mode-tab {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 2px; padding: var(--spacing-2) 2px;
    background: transparent; border: none; border-radius: var(--radius-md);
    cursor: pointer; color: var(--sidebar-text, #b8b1c4);
    font-size: 0.72rem; font-weight: 500; transition: all 0.2s;
  }
  .action-panel.collapsed .mode-tab { flex-direction: row; padding: var(--spacing-2); justify-content: center; }
  .action-panel.collapsed .mode-icon { font-size: 1.2rem; }
  .mode-icon { font-size: 1.05rem; line-height: 1; }
  .mode-label { white-space: nowrap; }
  .mode-tab:hover { background: var(--sidebar-hover-bg, rgba(255,255,255,0.06)); color: var(--sidebar-text-active, #f5efe0); }
  .mode-tab.active { background: var(--sidebar-active-bg, rgba(230,184,77,0.18)); color: var(--color-primary); font-weight: 600; }

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

  /* ── PageAgent-style history cards ────────────────────────────────────────── */
  .history-cards {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: var(--spacing-2) var(--spacing-4) 0;
    max-height: 200px;
    overflow-y: auto;
    flex-shrink: 0;
  }
  .hcard {
    /* A flex column with a max-height squashes its children before the scrollbar ever
       engages: ten cards in a 200px box compressed every row to a clipped sliver instead
       of scrolling. Cards keep their height; the container scrolls. */
    flex-shrink: 0;
    padding: 6px 10px;
    background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
    border-radius: 8px;
    border-left: 2px solid rgba(57, 182, 255, 0.5);
    font-size: 0.75rem;
    color: var(--sidebar-text-active, #e8e3d5);
    line-height: 1.3;
    position: relative;
    overflow: hidden;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.06), 0 1px 3px rgba(0,0,0,0.1);
  }
  .hcard::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
  }
  .hcard.input { border-left-color: rgb(34, 197, 94); background: linear-gradient(135deg, rgba(34,197,94,0.08), rgba(34,197,94,0.03)); }
  .hcard.output { border-left-color: rgb(34, 197, 94); background: linear-gradient(135deg, rgba(34,197,94,0.08), rgba(34,197,94,0.03)); }
  .hcard.question { border-left-color: rgb(255, 159, 67); background: linear-gradient(135deg, rgba(255,159,67,0.12), rgba(255,159,67,0.05)); }
  .hcard.observation { border-left-color: rgb(147, 51, 234); background: linear-gradient(135deg, rgba(147,51,234,0.08), rgba(147,51,234,0.03)); }
  .hcard.error { border-left-color: rgb(239, 68, 68); background: linear-gradient(135deg, rgba(239,68,68,0.08), rgba(239,68,68,0.03)); }
  .hcard.success {
    border-left: 4px solid rgb(34, 197, 94);
    background: linear-gradient(135deg, rgba(34,197,94,0.2), rgba(34,197,94,0.1), rgba(34,197,94,0.04));
    box-shadow: 0 4px 12px rgba(34,197,94,0.2), inset 0 1px 0 rgba(255,255,255,0.15);
    font-weight: 600;
    color: rgb(220, 252, 231);
    padding: 8px 12px;
  }
  .hcard-content {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    word-break: break-word;
    white-space: pre-wrap;
  }
  .hcard-icon { font-size: 0.8rem; flex-shrink: 0; line-height: 1.3; }
  .hcard-text { flex: 1; min-width: 0; }
  .hcard-meta {
    font-size: 0.65rem;
    color: rgba(255,255,255,0.5);
    margin-top: 4px;
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
  .panel-footer span { overflow: hidden; text-overflow: ellipsis; }

  .ctx {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    overflow: visible;
  }
  .ctx-dim { color: var(--color-text-muted, var(--text-tertiary)); opacity: 0.7; }
  .ctx-bar {
    display: inline-block;
    width: 42px;
    height: 5px;
    border-radius: 3px;
    background: var(--bg-hover, rgba(128,128,128,0.25));
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

<script lang="ts">
  /**
   * Front door for the in-app page-agent: type a task, pick a model, watch it drive.
   *
   * The loop itself was only reachable from code before this — there was no way
   * to run one without evaluating JS inside the app window. Detailed step output
   * still shows in the driven page (glow + pill + Stop); this bar carries the
   * controls and a one-line status so the page keeps the room.
   *
   * It claims the tab for the run through __steveControl, so a CLI agent cannot
   * drive the same tab underneath it.
   */
  import { cdp } from '../lib/cdp-client';
  import { connectCDP } from '../lib/cdp-actions';
  import { runAgentLoop, type AgentActivity } from '../lib/page-agent-loop';
  import { createPageMask } from '../lib/page-agent-mask';
  import { describeActivity } from '../lib/page-agent-overlay';
  import { beginAgentSession } from '../lib/agent-session';
  import { buildToolContext, claimTabForRun } from '../integrations/mom/transfer-via-agent';
  import { MOM_TRANSFER_MODELS } from '../integrations/mom/page-agent-config';
  import { SESSION_COLORS } from '../lib/agent-visual';

  let { tabId = '', baseURL = 'http://127.0.0.1:11434/v1' }: { tabId?: string; baseURL?: string } =
    $props();

  let task = $state('');
  let model = $state(MOM_TRANSFER_MODELS[0] as string);
  let running = $state(false);
  let status = $state('');
  let steps = $state(0);
  let result = $state<{ success: boolean; data: string } | null>(null);
  let controller: AbortController | null = null;

  async function run() {
    if (!task.trim() || running) return;
    if (!tabId) { status = 'Open a page first.'; return; }
    if (!(await connectCDP(undefined, tabId))) { status = 'Could not attach to the page.'; return; }

    running = true;
    result = null;
    steps = 0;
    status = 'Starting…';
    controller = new AbortController();
    const ctx = buildToolContext(cdp, controller.signal);
    // Hold the tab for the run; released in the finally below.
    const release = await claimTabForRun(tabId, `page-agent-${crypto.randomUUID()}`).catch(
      (e: unknown) => {
        status = e instanceof Error ? e.message : String(e);
        return null;
      },
    );
    if (!release) { running = false; return; }

    // One presence for the whole run, opened BEFORE the model is called so the page
    // shows an agent from the first moment rather than at the first tool call.
    // Same accent the claim gives the cursor, so pill and cursor read as one run.
    const session = beginAgentSession(ctx, controller, { task, accent: SESSION_COLORS[0] });
    const joined = session.join();
    let ok = false;
    try {
      const res = await runAgentLoop(
        {
          task,
          baseURL,
          model,
          apiKey: 'NA',
          maxSteps: 25,
          // This bar can be pointed at any page, including a live gradebook. One mask per run,
          // so tokens stay stable across steps and rehydrate back into the actions.
          mask: createPageMask(),
          onActivity: (a: AgentActivity) => {
            if (a.type === 'executing') steps += 1;
            status = describeActivity(a);
            joined.onActivity?.(a);
          },
          onStatusChange: (s) => joined.onStatusChange?.(s),
        },
        ctx,
      );
      result = { success: res.success, data: res.data };
      ok = res.success;
      status = res.success ? 'Done' : 'Finished with problems';
    } catch (e) {
      result = { success: false, data: e instanceof Error ? e.message : String(e) };
      status = 'Error';
    } finally {
      await session.end(
        controller.signal.aborted ? 'stopped' : ok ? 'done' : 'error',
        result?.success ? 'Done' : undefined,
      );
      await release();
      running = false;
      controller = null;
    }
  }

  function stop() {
    controller?.abort();
    status = 'Stopping…';
  }
</script>

<div class="pa-bar aux-bar">
  <div class="pa-controls">
    <input
      class="pa-task"
      type="text"
      bind:value={task}
      placeholder="Tell the page agent what to do on this page…"
      disabled={running}
      onkeydown={(e) => { if (e.key === 'Enter') run(); }} />
    <select class="pa-model" bind:value={model} disabled={running} title="Model (ranked by a live bench)">
      {#each MOM_TRANSFER_MODELS as m (m)}
        <option value={m}>{m.replace(':cloud', '')}</option>
      {/each}
    </select>
    {#if running}
      <button class="pa-btn pa-stop" onclick={stop}>Stop</button>
    {:else}
      <button class="pa-btn" onclick={run} disabled={!task.trim()}>Run</button>
    {/if}
  </div>

  {#if status}
    <div class="pa-status" class:err={result && !result.success} class:ok={result?.success}>
      {#if running}<span class="pa-dot"></span>{/if}
      <span class="pa-text">{status}</span>
      {#if steps}<span class="pa-steps">{steps} step{steps === 1 ? '' : 's'}</span>{/if}
    </div>
  {/if}
  {#if result}
    <div class="pa-result" class:err={!result.success}>{result.data}</div>
  {/if}
</div>

<style>
  /* Same in-flow bar pattern as bookmarks/passwords: the embedded browser is a
     native webview that paints over HTML, so this reserves space rather than
     floating. .aux-bar is what updateWebviewBounds measures. */
  .pa-bar {
    display: flex; flex-direction: column; gap: 5px;
    padding: 6px 8px;
    background: var(--bg-sidebar, var(--bg-input));
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }
  .pa-controls { display: flex; align-items: center; gap: 6px; }
  .pa-task {
    flex: 1; min-width: 0; height: 26px; padding: 0 10px;
    background: var(--bg-input); color: var(--text-primary);
    border: 1px solid var(--border-color); border-radius: 999px; font-size: 0.78rem;
  }
  .pa-task:focus { outline: none; border-color: var(--color-primary); }
  .pa-model {
    height: 26px; border-radius: 999px; padding: 0 8px; font-size: 0.72rem;
    background: var(--bg-input); color: var(--text-secondary);
    border: 1px solid var(--border-color); max-width: 170px;
  }
  .pa-btn {
    height: 26px; padding: 0 14px; border-radius: 999px; cursor: pointer;
    font-size: 0.75rem; font-weight: 600; white-space: nowrap;
    background: transparent; border: 1px solid var(--color-primary); color: var(--color-primary);
  }
  .pa-btn:hover:not(:disabled) { background: var(--color-primary-bg); }
  .pa-btn:disabled { opacity: 0.45; cursor: default; }
  .pa-stop { border-color: rgb(239, 68, 68); color: rgb(239, 68, 68); }
  .pa-status { display: flex; align-items: center; gap: 6px; font-size: 0.72rem; color: var(--text-secondary); }
  .pa-status.ok { color: rgb(34, 197, 94); }
  .pa-status.err { color: rgb(239, 68, 68); }
  .pa-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .pa-steps { flex-shrink: 0; opacity: 0.75; }
  .pa-dot {
    width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
    background: var(--color-primary); animation: pa-pulse 0.8s ease-in-out infinite;
  }
  @keyframes pa-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.35; } }
  .pa-result {
    font-size: 0.72rem; color: var(--text-primary);
    background: var(--bg-card, var(--bg-input));
    border: 1px solid var(--border-color); border-left: 2px solid rgb(34, 197, 94);
    border-radius: 6px; padding: 5px 8px; max-height: 84px; overflow-y: auto;
  }
  .pa-result.err { border-left-color: rgb(239, 68, 68); }
</style>

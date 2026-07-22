<script lang="ts">
  /**
   * AutomateRunner — map-aware task automation with a human review gate.
   *
   * Flow: describe a task → PLAN (a spawned CLI drives the logged-in browser READ-ONLY over the
   * CDP debug port and writes the steps it intends to take, using the existing site map for
   * context; it maps the site first if none exists) → you review the plan → APPROVE → EXECUTE
   * (the CLI carries out ONLY the approved steps, and may now click/submit).
   *
   * The approval is the safety gate between read-only planning and live mutation, so nothing that
   * changes the site happens until you approve a plan you can read.
   */
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { getActiveTabId, getEmbeddedUrl } from '../../lib/browser';
  import { domainFromUrl } from '../../lib/utils/index';
  import { scopeOf, normalizeUrl } from '../../lib/site-map';
  import { engineForProvider, cliModelArg, extractCliText, summarizeCliLine } from '../../lib/agent-cli';
  import { buildCliCrawlPrompt, parseCliCrawlOutput } from '../../lib/cli-crawl';
  import { buildAutomatePlanPrompt, buildAutomateExecPrompt, cleanAutomateOutput, planHasMutations, parsePlan } from '../../lib/cli-automate';
  import { loadMappingDoc, saveMappingDoc } from '../../lib/site-profiles';
  import { tabMarker } from '../../lib/tab-control';
  import { createCdpWatchdog } from '../../lib/cdp-watchdog';
  import { showAgentConnected, hideAgentConnected } from '../../lib/agent-overlay';
  import { createThrottledBuffer } from '../../lib/throttle-buffer';

  let { provider = '', model = '' }: { provider?: string; model?: string } = $props();

  let task = $state('');
  let stayInScope = $state(true);
  type Phase = 'idle' | 'mapping' | 'planning' | 'awaiting-approval' | 'executing' | 'done';
  let phase = $state<Phase>('idle');
  let msg = $state<string | null>(null);
  let plan = $state<string | null>(null);
  let result = $state<string | null>(null);
  let progress = $state<string[]>([]);
  let mapUsed = $state<'existing' | 'created' | 'none'>('none');
  /** The last finished run — lets the next task be a follow-up instead of starting cold. */
  let lastRun = $state<{ task: string; result: string } | null>(null);

  // ponytail: follow-up context is folded into the task string rather than a separate prompt field —
  // give it its own section in buildAutomatePlanPrompt if it ever needs to survive more than one hop.
  const taskWithContext = (t: string): string =>
    lastRun
      ? `Earlier in this session you were asked: ${lastRun.task}\n\nYou reported:\n${lastRun.result}\n\nFOLLOW-UP TASK: ${t}`
      : t;

  const busy = $derived(phase === 'mapping' || phase === 'planning' || phase === 'executing');

  async function currentUrl(): Promise<string> {
    const tabId = getActiveTabId();
    if (!tabId) return '';
    try { return await getEmbeddedUrl(tabId); } catch { return ''; }
  }

  /** window.name marker of the tab being driven — pins the spawned agent to the exact tab. */
  const markerNow = (): string | undefined => { const id = getActiveTabId(); return id ? tabMarker(id) : undefined; };

  /** Spawn the engine CLI with streamed progress; returns its final text. */
  async function spawn(prompt: string, label: string): Promise<string> {
    const port = await invoke<number | null>('get_cdp_port');
    if (!port) throw new Error('CDP debug port unavailable — restart the app.');
    const engine = engineForProvider(provider);
    const sessionId = crypto.randomUUID();
    // Batch progress updates (~150ms) so a burst of stream events doesn't re-render per line and
    // starve the main thread — the confirmed cause of the WebView2 CDP wedge.
    const progressBuf = createThrottledBuffer<string>((lines) => {
      // Collapse consecutive duplicate snapshots (e.g. several "reading the page" in a row).
      const merged = [...progress];
      for (const l of lines) if (merged[merged.length - 1] !== l) merged.push(l);
      progress = merged.slice(-40);
    });
    const unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
      if (ev.payload.sessionId !== sessionId) return;
      const s = summarizeCliLine(ev.payload.line);
      if (s) progressBuf.push(s);
    });
    // Watch the WebView2 debug endpoint: it has wedged under load, and a wedged endpoint means
    // the agent's CDP calls hang. Surface it so a stuck run is diagnosable, not silent.
    const watchdog = createCdpWatchdog({
      port,
      onWedge: () => { msg = '⚠ Browser debug endpoint has been unresponsive for ~15s — the run may be stuck. It can still recover on its own; restart the app only if it stays frozen.'; },
    });
    watchdog.start();
    // Show the "agent connected" overlay (border ring + arrow cursor) on the driven tab.
    const drivenTab = getActiveTabId();
    await showAgentConnected(drivenTab);
    try {
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // full-shell: the agent needs Bash to speak CDP
        timeoutSecs: 900,
        stream: true,
      });
      return extractCliText(engine, stdout);
    } finally {
      progressBuf.flush(); // emit any buffered progress lines
      watchdog.stop();
      await hideAgentConnected(drivenTab);
      unlisten();
    }
  }

  async function startPlan() {
    if (busy || !task.trim()) return;
    if (!provider) { msg = 'Pick an engine above first.'; return; }
    plan = null;
    result = null;
    progress = [];
    const startUrl = normalizeUrl(await currentUrl());
    if (!startUrl) { msg = 'Open a page in the browser first.'; return; }
    const domain = domainFromUrl(startUrl);
    const scope = stayInScope ? scopeOf(startUrl) : null;
    try {
      const port = await invoke<number | null>('get_cdp_port');
      if (!port) throw new Error('CDP debug port unavailable — restart the app.');

      // Map-first: reuse an existing site map, or build one now if the domain has none.
      let map = domain ? (await loadMappingDoc(domain)) : null;
      if (map) {
        mapUsed = 'existing';
      } else if (domain) {
        phase = 'mapping';
        msg = 'No site map yet — mapping the site first…';
        const crawl = await spawn(buildCliCrawlPrompt({ cdpPort: port, startUrl, scope, marker: markerNow() }), 'map');
        const { doc } = parseCliCrawlOutput(crawl);
        if (doc) { await saveMappingDoc(domain, doc).catch(() => {}); map = doc; mapUsed = 'created'; }
      }

      phase = 'planning';
      msg = 'Planning (read-only)…';
      progress = [];
      const raw = await spawn(buildAutomatePlanPrompt({ cdpPort: port, startUrl, task: taskWithContext(task), map: map ?? '', scope, marker: markerNow() }), 'plan');
      plan = cleanAutomateOutput(raw);
      if (!plan) throw new Error('The agent returned an empty plan.');
      phase = 'awaiting-approval';
      msg = planHasMutations(plan)
        ? 'Review the plan — it includes steps that will CHANGE the site. Approve to run.'
        : 'Review the plan. Approve to run.';
    } catch (e) {
      phase = 'idle';
      msg = `Planning failed: ${e instanceof Error ? e.message : String(e)}`;
    }
  }

  /**
   * Direct run — no plan, no review gate. For ad-hoc one-offs where reading a plan costs more than
   * the task is worth. Uses an existing site map if the domain has one, but never crawls first:
   * "run now" should start now.
   */
  async function runDirect() {
    if (busy || !task.trim()) return;
    if (!provider) { msg = 'Pick an engine above first.'; return; }
    plan = null;
    result = null;
    progress = [];
    const startUrl = normalizeUrl(await currentUrl());
    if (!startUrl) { msg = 'Open a page in the browser first.'; return; }
    const domain = domainFromUrl(startUrl);
    const scope = stayInScope ? scopeOf(startUrl) : null;
    try {
      const port = await invoke<number | null>('get_cdp_port');
      if (!port) throw new Error('CDP debug port unavailable — restart the app.');
      const map = domain ? await loadMappingDoc(domain) : null;
      mapUsed = map ? 'existing' : 'none';
      phase = 'executing';
      msg = 'Running the task directly — no plan was reviewed.';
      const raw = await spawn(
        buildAutomateExecPrompt({ cdpPort: port, startUrl, task: taskWithContext(task), map: map ?? '', scope, marker: markerNow() }),
        'exec',
      );
      result = cleanAutomateOutput(raw);
      lastRun = { task, result };
      task = '';
      phase = 'done';
      msg = 'Finished — result below. Check the site (and any audit log) to confirm.';
    } catch (e) {
      phase = 'done';
      msg = `Run failed: ${e instanceof Error ? e.message : String(e)}`;
    }
  }

  async function approveAndRun() {
    if (phase !== 'awaiting-approval' || !plan) return;
    progress = [];
    const startUrl = normalizeUrl(await currentUrl());
    const domain = domainFromUrl(startUrl);
    const scope = stayInScope ? scopeOf(startUrl) : null;
    try {
      const port = await invoke<number | null>('get_cdp_port');
      if (!port) throw new Error('CDP debug port unavailable — restart the app.');
      phase = 'executing';
      msg = 'Executing the approved plan…';
      const map = domain ? await loadMappingDoc(domain) : null;
      const raw = await spawn(
        buildAutomateExecPrompt({ cdpPort: port, startUrl, task: taskWithContext(task), map: map ?? '', scope, approvedPlan: plan, marker: markerNow() }),
        'exec',
      );
      result = cleanAutomateOutput(raw);
      lastRun = { task, result };
      task = ''; // the box becomes the follow-up box
      phase = 'done';
      msg = 'Execution finished — result below. Check the site (and any audit log) to confirm.';
    } catch (e) {
      phase = 'done';
      msg = `Execution failed: ${e instanceof Error ? e.message : String(e)}`;
    }
  }

  function cancel() {
    if (busy) return;
    phase = 'idle';
    plan = null;
    result = null;
    progress = [];
    msg = 'Cancelled — nothing was changed.';
  }
</script>

<div class="automate">
  <p class="muted">
    Describe a task, then either <strong>Plan</strong> it — read-only first (mapping the site if
    needed), and you approve before anything changes — or <strong>Run now</strong> for a one-off,
    which starts immediately with no plan to review.
  </p>

  {#if lastRun}
    <div class="followup">
      <span>Following up on: <em>{lastRun.task}</em></span>
      <button class="link" onclick={() => { lastRun = null; task = ''; }} disabled={busy}>start fresh</button>
    </div>
  {/if}

  <textarea
    class="task"
    bind:value={task}
    placeholder={lastRun
      ? "Follow-up — e.g. now do the same for period 3"
      : "e.g. Post an announcement in the forum titled 'Welcome to class'"}
    rows="3"
    disabled={busy}
  ></textarea>

  <label class="scope-row" title="Keep the agent within the course/section of the page you start on.">
    <input type="checkbox" bind:checked={stayInScope} disabled={busy} />
    <span>Stay in this course{#if scopeOf('')}{/if}</span>
  </label>

  <div class="btns">
    {#if phase === 'idle' || phase === 'done'}
      <button class="go" disabled={!task.trim() || !provider} onclick={startPlan} title="Map + plan read-only, then you approve before anything changes. Use for work you'll repeat.">
        🧭 Plan task
      </button>
      <button class="go direct" disabled={!task.trim() || !provider} onclick={runDirect} title="Skip planning and approval — the agent starts changing the site straight away. Use for quick one-offs.">
        ⚡ Run now
      </button>
    {/if}
    {#if phase === 'awaiting-approval'}
      <button class="go approve" onclick={approveAndRun}>✅ Approve &amp; run</button>
      <button class="cancel" onclick={cancel}>Cancel</button>
    {/if}
    {#if busy}
      <span class="running">{phase === 'mapping' ? '🕸 Mapping…' : phase === 'planning' ? '🧭 Planning…' : '⚙ Executing…'}</span>
    {/if}
  </div>

  {#if msg}<div class="msg" class:warn={phase === 'awaiting-approval' && plan && planHasMutations(plan)}>{msg}</div>{/if}
  {#if mapUsed !== 'none' && phase !== 'idle'}<div class="muted small">Map: {mapUsed === 'existing' ? 'used existing site map' : 'built a new site map first'}</div>{/if}

  {#if progress.length}
    <div class="hdr">Agent activity {#if busy}(live){/if}</div>
    <ul class="log">
      {#each progress.slice(-12) as line, i (i + line)}<li><code>{line}</code></li>{/each}
    </ul>
  {/if}

  {#if plan}
    {@const parsed = parsePlan(plan)}
    <div class="hdr">Plan{#if phase === 'awaiting-approval'} — awaiting your approval{/if}</div>
    {#if parsed.steps.length}
      <ol class="plan-steps">
        {#each parsed.steps as s (s.n)}
          <li class:mutates={s.mutates}>
            <span class="step-n">{s.n}</span>
            <span class="step-text">
              {#if s.mutates}<span class="mut-badge">changes site</span>{/if}{s.text}
            </span>
          </li>
        {/each}
      </ol>
      {#if parsed.risk}
        <div class="risk"><span class="risk-hdr">⚠ Risk</span> {parsed.risk}</div>
      {/if}
    {:else}
      <pre class="doc">{plan}</pre>
    {/if}
  {/if}

  {#if result}
    <div class="hdr">Result</div>
    <pre class="doc">{result}</pre>
  {/if}
</div>

<style>
  .automate { display: flex; flex-direction: column; gap: var(--spacing-3); }
  .muted { color: var(--text-secondary); font-size: 0.85rem; margin: 0; }
  .small { font-size: 0.78rem; }
  .task {
    width: 100%; box-sizing: border-box; resize: vertical;
    background: var(--bg-input); color: var(--text-primary);
    border: 1px solid var(--border-color); border-radius: var(--radius-md);
    padding: var(--spacing-2); font-size: 0.9rem;
  }
  .followup {
    display: flex; align-items: baseline; gap: var(--spacing-2); flex-wrap: wrap;
    font-size: 0.8rem; color: var(--text-secondary);
    border-left: 2px solid var(--color-primary); padding-left: var(--spacing-2);
  }
  .followup em { color: var(--text-primary); font-style: normal; }
  .link { background: none; border: none; padding: 0; color: var(--color-primary); cursor: pointer; font-size: 0.8rem; text-decoration: underline; }
  .link:disabled { opacity: 0.5; cursor: not-allowed; }
  .scope-row { display: flex; align-items: center; gap: 6px; font-size: 0.82rem; color: var(--text-secondary); }
  .btns { display: flex; align-items: center; gap: var(--spacing-2); flex-wrap: wrap; }
  .go {
    background: transparent; border: 1px solid var(--color-primary); color: var(--color-primary);
    border-radius: var(--radius-md); padding: 6px 14px; cursor: pointer; font-size: 0.88rem;
  }
  .go:disabled { opacity: 0.5; cursor: not-allowed; }
  .go.approve, .go.direct { border-color: var(--color-danger, #e5484d); color: var(--color-danger, #e5484d); }
  .cancel { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.85rem; }
  .running { font-size: 0.85rem; color: var(--text-secondary); }
  .msg {
    font-size: 0.85rem; color: var(--text-primary); background: var(--bg-card, var(--bg-input));
    border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 6px 10px;
  }
  .msg.warn { border-color: var(--color-danger, #e5484d); color: var(--color-danger, #e5484d); }
  .hdr { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); font-weight: 600; }
  .log { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; }
  .log code { font-size: 0.75rem; color: var(--text-secondary); word-break: break-all; }
  .doc {
    white-space: pre-wrap; word-break: break-word; font-size: 0.8rem;
    background: var(--bg-input); border: 1px solid var(--border-color);
    border-radius: var(--radius-md); padding: var(--spacing-2); margin: 0; max-height: 340px; overflow: auto;
  }
  .plan-steps {
    list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 4px;
    max-height: 340px; overflow: auto;
  }
  .plan-steps li {
    display: flex; gap: 8px; align-items: baseline;
    font-size: 0.82rem; line-height: 1.35; color: var(--text-primary);
    background: var(--bg-input); border: 1px solid var(--border-color);
    border-left: 3px solid var(--border-color);
    border-radius: var(--radius-sm, 4px); padding: 5px 9px;
  }
  .plan-steps li.mutates { border-left-color: var(--color-danger, #e5484d); }
  .step-n {
    flex: 0 0 auto; min-width: 1.4em; text-align: right;
    font-variant-numeric: tabular-nums; font-weight: 600; color: var(--text-secondary);
  }
  .step-text { flex: 1; word-break: break-word; }
  .mut-badge {
    display: inline-block; margin-right: 6px; padding: 1px 6px;
    font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;
    color: #fff; background: var(--color-danger, #e5484d); border-radius: 3px; vertical-align: baseline;
  }
  .risk {
    font-size: 0.8rem; line-height: 1.4; color: var(--text-primary);
    background: color-mix(in srgb, var(--color-danger, #e5484d) 10%, var(--bg-input));
    border: 1px solid var(--color-danger, #e5484d); border-radius: var(--radius-md);
    padding: 7px 10px; margin-top: 2px;
  }
  .risk-hdr { font-weight: 700; color: var(--color-danger, #e5484d); margin-right: 4px; }
</style>

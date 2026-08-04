<script lang="ts">
  /**
   * AutomateRunner — map-aware task automation with a human review gate.
   *
   * Flow: describe a task → PLAN (a spawned CLI reads the logged-in browser READ-ONLY and writes
   * the steps it intends to take, using the existing site map for context; it maps the site first
   * if none exists) → you review the plan → APPROVE → EXECUTE (the CLI carries out ONLY the
   * approved steps, and may now click/submit).
   *
   * The approval is the safety gate between read-only planning and live mutation, so nothing that
   * changes the site happens until you approve a plan you can read.
   *
   * The CLI orchestrates but does NOT touch the browser: it gets the `page` MCP tools instead of
   * the CDP port, and every page read comes back through the app with student names and ids
   * tokenized. The port is deliberately never in the prompt — with no port there is no raw-read
   * path, which is what makes the masking a boundary rather than a convention. The app still knows
   * the port (the wedge watchdog polls it); the spawned CLI never learns it.
   */
  import { onMount, onDestroy } from 'svelte';
  import {
    Sparkles, Globe, MousePointerClick, Keyboard, Camera, Eye, LogIn,
    AppWindow, Video, Paperclip, RotateCw, Terminal, MessageSquare,
    CheckCircle2, AlertTriangle,
  } from 'lucide-svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { upsertInstalledSkill } from '../../lib/skills-api';
  import { taskToSkill } from '../../lib/agent-skill';
  import { getActiveTabId, getEmbeddedUrl } from '../../lib/browser';
  import { domainFromUrl } from '../../lib/utils/index';
  import { scopeOf, normalizeUrl } from '../../lib/site-map';
  import { engineForProvider, cliModelArg, extractCliText, summarizeCliLine } from '../../lib/agent-cli';
  import { buildCliCrawlPrompt, parseCliCrawlOutput } from '../../lib/cli-crawl';
  import { buildAutomatePlanPrompt, buildAutomateExecPrompt, cleanAutomateOutput, planHasMutations, parsePlan, buildEnhancePrompt } from '../../lib/cli-automate';
  import { loadMappingDoc, saveMappingDoc, getMappingDocPath } from '../../lib/site-profiles';
  import { cdp } from '../../lib/cdp-client';
  import { connectCDP } from '../../lib/cdp-actions';
  import { buildToolContext } from '../../integrations/mom/transfer-via-agent';
  import { startPageToolsBridge } from '../../lib/page-tools-bridge';
  import { endRunMask, maskForRun } from '../../lib/page-agent-mask';
  import { MOM_TRANSFER_MODELS } from '../../integrations/mom/page-agent-config';
  import type { Confinement } from '../../lib/page-agent-run';
  import { createCdpWatchdog } from '../../lib/cdp-watchdog';
  import { showAgentConnected, hideAgentConnected } from '../../lib/agent-overlay';
  import { createThrottledBuffer } from '../../lib/throttle-buffer';
  import { summarizeRunResult } from '../../lib/result-summary';
  import { renderSkillPreview } from '../../lib/skill-parser';

  let {
    provider = '',
    model = '',
    agentStatus = $bindable('idle' as string),
    agentStatusText = $bindable('' as string),
  }: {
    provider?: string;
    model?: string;
    agentStatus?: string;
    agentStatusText?: string;
  } = $props();

  let task = $state('');
  // "✨ Enhance" rewrites the typed task into a detailed, capability-aware prompt via the engine.
  let enhancing = $state(false);
  let taskBeforeEnhance = $state<string | null>(null); // kept so an enhance can be reverted
  // Both always on, no toggle: confine each tab to the course/section it starts in, and let the
  // agent span tabs/sites in one run. Multi-tab is per-tab scoped, so the two compose.
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

  /**
   * Tokenize a piece of prompt content before it leaves for the CLI.
   *
   * Three things carry student data into a prompt and none of them are instructions: the task
   * (the teacher can type a name into it), the stored site map, and an earlier run's plan or
   * report — those come back from `spawn` rehydrated for the screen, so they arrive here holding
   * real names and have to be re-tokenized. Deliberately NOT applied to the surrounding
   * instructions: masking those eats the control labels the agent is meant to click.
   */
  const outbound = (sid: string, text: string, url: string): string =>
    text ? maskForRun(sid).text(text, url) : text;

  const busy = $derived(phase === 'mapping' || phase === 'planning' || phase === 'executing');

  // Sync phase/msg to the PageAgent-style status bindings
  $effect(() => {
    if (phase === 'idle') {
      agentStatus = 'idle';
      agentStatusText = '';
    } else if (phase === 'mapping') {
      agentStatus = 'executing';
      agentStatusText = 'Mapping the site…';
    } else if (phase === 'planning') {
      agentStatus = 'thinking';
      agentStatusText = 'Planning…';
    } else if (phase === 'awaiting-approval') {
      agentStatus = 'awaiting-approval';
      agentStatusText = msg || 'Awaiting your approval';
    } else if (phase === 'executing') {
      agentStatus = 'executing';
      agentStatusText = msg || 'Executing…';
    } else if (phase === 'done') {
      const failed = msg?.toLowerCase().includes('failed') || msg?.toLowerCase().includes('error');
      agentStatus = failed ? 'error' : 'completed';
      agentStatusText = failed ? 'Failed' : 'Done';
    }
  });

  // Session id of the run currently in flight, so the Stop button can terminate its spawned CLI.
  let currentSessionId = $state<string | null>(null);
  let stopping = $state(false);
  async function stopRun() {
    if (!currentSessionId || stopping) return;
    stopping = true;
    try {
      await invoke('stop_agent_cli', { sessionId: currentSessionId });
      msg = 'Stopped by you.';
    } catch (e) {
      msg = `Stop failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      stopping = false;
    }
  }

  // Elapsed-time ticker so a long run visibly progresses — reassures the user it's working, not stuck
  // (the CDP watchdog can false-alarm under heavy load while the run is actually fine).
  let elapsed = $state(0);
  $effect(() => {
    if (!busy) { elapsed = 0; return; }
    const start = Date.now();
    elapsed = 0;
    const id = setInterval(() => { elapsed = Math.floor((Date.now() - start) / 1000); }, 1000);
    return () => clearInterval(id);
  });
  function fmtElapsed(s: number): string {
    return `${Math.floor(s / 60)}:${String(s % 60).padStart(2, '0')}`;
  }

  /** Pick a plain-language icon for a live activity line (verb phrases from summarizeCliLine). */
  function activityIcon(line: string) {
    const l = line.toLowerCase();
    if (l.startsWith('navigating')) return Globe;
    if (l.startsWith('clicking')) return MousePointerClick;
    if (l.startsWith('filling')) return Keyboard;
    if (l.includes('screenshot')) return Camera;
    if (l.startsWith('reading')) return Eye;
    if (l.startsWith('logging in')) return LogIn;
    if (l.includes('tab')) return AppWindow;
    if (l.includes('recording')) return Video;
    if (l.startsWith('attaching')) return Paperclip;
    if (l.startsWith('reloading')) return RotateCw;
    if (l.startsWith('driving') || l.startsWith('running a command') || l.startsWith('using ')) return Terminal;
    return MessageSquare; // agent narration
  }
  const prettyActivity = (l: string) => l.charAt(0).toUpperCase() + l.slice(1);

  /** Rewrite the typed task into a detailed, capability-aware prompt. A one-shot text call — no
   *  browser, watchdog, or overlay (unlike a real run), so it stays fast and side-effect-free. */
  async function enhanceTask() {
    if (busy || enhancing || !task.trim()) return;
    if (!provider) { msg = 'Pick an engine above first.'; return; }
    const engine = engineForProvider(provider);
    const original = task;
    enhancing = true;
    msg = 'Enhancing your task with AI…';
    try {
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt: buildEnhancePrompt(original),
        sessionId: crypto.randomUUID(),
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: false, // a pure text rewrite — it needs no tools
        timeoutSecs: 120,
        stream: false,
      });
      const enhanced = cleanAutomateOutput(extractCliText(engine, stdout)).trim();
      if (enhanced) {
        taskBeforeEnhance = original;
        task = enhanced;
        msg = 'Task enhanced — review it, then Plan or Run.';
      } else {
        msg = 'Enhance returned nothing — task left unchanged.';
      }
    } catch (e) {
      msg = `Enhance failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      enhancing = false;
    }
  }

  function revertEnhance() {
    if (taskBeforeEnhance !== null) { task = taskBeforeEnhance; taskBeforeEnhance = null; }
  }

  // Save a finished run as a re-runnable skill, and load one back in (fired by the Skills page).
  let savedSkillMsg = $state('');

  function deriveSkillName(t: string): string {
    const s = t.replace(/\s+/g, ' ').trim();
    return (s.length > 48 ? s.slice(0, 45) + '…' : s) || 'Agent task';
  }

  async function saveAsSkill() {
    if (!lastRun) return;
    const name = deriveSkillName(lastRun.task);
    try {
      await upsertInstalledSkill({
        name,
        description: lastRun.task.replace(/\s+/g, ' ').slice(0, 140),
        content: taskToSkill(name, { task: lastRun.task, plan: plan ?? undefined }),
        source: 'local',
        isActive: 0,
      });
      savedSkillMsg = `Saved "${name}" to Skills — run it again anytime from the Skills tab.`;
    } catch (e) {
      savedSkillMsg = `Couldn't save skill: ${e instanceof Error ? e.message : String(e)}`;
    }
  }

  function handleLoadTask(e: Event) {
    const detail = (e as CustomEvent<{ task?: string }>).detail;
    if (!detail?.task) return;
    lastRun = null;
    plan = null;
    result = null;
    savedSkillMsg = '';
    task = detail.task;
    msg = 'Loaded a saved skill — review it, then Plan or Run.';
  }
  onMount(() => {
    window.addEventListener('steve:load-task', handleLoadTask);
    // The Skills page navigates here THEN hands over the task; a live event would fire before this
    // component mounts, so it stashes the task and we pick it up on mount.
    const pending = sessionStorage.getItem('steve:pending-task');
    if (pending) {
      sessionStorage.removeItem('steve:pending-task');
      handleLoadTask(new CustomEvent('steve:load-task', { detail: { task: pending } }));
    }
  });
  onDestroy(() => window.removeEventListener('steve:load-task', handleLoadTask));

  async function currentUrl(): Promise<string> {
    const tabId = getActiveTabId();
    if (!tabId) return '';
    try { return await getEmbeddedUrl(tabId); } catch { return ''; }
  }

  /**
   * The model behind `page_task`.
   *
   * It CANNOT be the CLI's own engine: the sub-task loop needs an OpenAI-compatible endpoint, which
   * the Claude engine is not. So page work runs on whatever Ollama serves locally — and when Ollama
   * is down or rate-limited, page_task says so and the orchestrator drives with the primitives
   * instead. That degrade path is why a missing Ollama is a slower run, not a failed one.
   */
  const SUBTASK_BASE_URL = 'http://127.0.0.1:11434/v1';
  const SUBTASK_MODEL = MOM_TRANSFER_MODELS[0] as string;

  /**
   * The fence the page tools enforce on every navigation.
   *
   * Null when no page is open (there is nothing to anchor to) or when the run is allowed to span
   * sites — the prompt still carries the rules, but a fence derived from a page the run does not
   * start on would refuse the first legitimate hop.
   */
  function confinementFor(startUrl: string, multiTab: boolean): Confinement | undefined {
    if (!startUrl) return undefined;
    return { startUrl, sameDomainOnly: !multiTab };
  }

  /** Spawn the engine CLI with streamed progress; returns its final text. Pass the sessionId that
   *  was baked into the prompt (tab ownership) so run/stop/progress, tab ownership and the page
   *  tools' mask all share one run identity. */
  async function spawn(
    prompt: string,
    label: string,
    sessionId: string = crypto.randomUUID(),
    confine?: Confinement,
  ): Promise<string> {
    // For the watchdog only. This never reaches the CLI — see the header comment.
    const port = await invoke<number | null>('get_cdp_port');
    if (!port) throw new Error('CDP debug port unavailable — restart the app.');
    const engine = engineForProvider(provider);
    currentSessionId = sessionId; // expose it so Stop can terminate this run
    // Batch progress updates (~150ms) so a burst of stream events doesn't re-render per line and
    // starve the main thread — the confirmed cause of the WebView2 CDP wedge.
    const progressBuf = createThrottledBuffer<string>((lines) => {
      // Collapse consecutive duplicate snapshots (e.g. several "reading the page" in a row).
      const merged = [...progress];
      for (const l of lines) if (merged[merged.length - 1] !== l) merged.push(l);
      progress = merged.slice(-40);
    });
    // The CLI only ever saw tokens, so everything it says carries them. The mask keeps student data
    // off the wire — the teacher reading their own gradebook is exactly who it does NOT hide from,
    // so put the names back on the way to the screen.
    const show = (s: string) => maskForRun(sessionId).rehydrate(s);
    const unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
      if (ev.payload.sessionId !== sessionId) return;
      const s = summarizeCliLine(ev.payload.line);
      if (s) progressBuf.push(show(s));
    });
    // Watch the WebView2 debug endpoint: it has wedged under load, and a wedged endpoint means
    // the agent's CDP calls hang. Surface it so a stuck run is diagnosable, not silent.
    // Timing is stated once and the warning text is derived from it — the message used to
    // hardcode "~15s" while the watchdog actually waited 8 × 5s, so it under-reported how long
    // the endpoint had really been quiet. Agent bursts routinely stall it for tens of seconds
    // and recover, so this deliberately waits well past a burst before saying anything.
    const WEDGE_INTERVAL_MS = 5000;
    const WEDGE_FAILURES_TO_TRIP = 8; // → warn only after ~40s of sustained silence
    const wedgeSecs = Math.round((WEDGE_INTERVAL_MS * WEDGE_FAILURES_TO_TRIP) / 1000);
    const watchdog = createCdpWatchdog({
      port,
      intervalMs: WEDGE_INTERVAL_MS,
      failuresToTrip: WEDGE_FAILURES_TO_TRIP,
      onWedge: () => { msg = `⚠ Browser debug endpoint has been unresponsive for ~${wedgeSecs}s — the run may be stuck. It can still recover on its own; restart the app only if it stays frozen.`; },
    });
    watchdog.start();
    // Show the "agent connected" overlay (border ring + arrow cursor) on the driven tab, and
    // register the run's session so tabs it opens are OWNED by this sessionId (no tab fights).
    const drivenTab = getActiveTabId();
    await showAgentConnected(drivenTab, sessionId);
    // Answer the CLI's page tools for the life of this run. Started BEFORE the spawn: the endpoint
    // has to exist by the time the CLI's first tool call lands.
    const controller = new AbortController();
    const bridge = await startPageToolsBridge({
      runId: sessionId,
      // Resolved per call, not captured: page_tabs can change which tab is active, and a context
      // taken now would keep reading the tab the agent has since switched away from.
      ctx: async () => {
        const tab = getActiveTabId();
        if (!tab) throw new Error('No page is open in the app.');
        if (!(await connectCDP(undefined, tab))) throw new Error('Could not attach to the page.');
        return buildToolContext(cdp, controller.signal);
      },
      subTask: { baseURL: SUBTASK_BASE_URL, model: SUBTASK_MODEL, confine },
      onActivity: (line) => progressBuf.push(show(line)),
    });
    try {
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // full-shell: the agent still reasons, writes files, and runs commands
        timeoutSecs: 900,
        stream: true,
        mcpConfig: bridge.mcpConfig,
      });
      return show(extractCliText(engine, stdout));
    } finally {
      controller.abort();
      await bridge.stop();
      progressBuf.flush(); // emit any buffered progress lines
      watchdog.stop();
      await hideAgentConnected(drivenTab, sessionId);
      unlisten();
      currentSessionId = null;
      // Drops the only copy of this run's identifiers. Last, because everything above still needs
      // to translate tokens back — the returned report is rehydrated before this runs.
      endRunMask(sessionId);
    }
  }

  async function startPlan() {
    if (busy || !task.trim()) return;
    if (!provider) { msg = 'Pick an engine above first.'; return; }
    plan = null;
    result = null;
    progress = [];
    const startUrl = normalizeUrl(await currentUrl());
    // No page open is fine — the agent opens the site the task needs via the tab bridge, so force
    // multi-tab (bridge access) and drop the host/scope confinement there is nothing to anchor to.
    const noPage = !startUrl;
    const effMultiTab = true; // always allow cross-site tab spanning
    const domain = noPage ? null : domainFromUrl(startUrl);
    const scope = noPage ? null : scopeOf(startUrl); // always confine to the starting course/section
    const confine = confinementFor(startUrl, effMultiTab);
    try {
      // Map-first: reuse an existing site map, or build one now if the domain has none.
      let map = domain ? (await loadMappingDoc(domain)) : null;
      if (map) {
        mapUsed = 'existing';
      } else if (domain) {
        phase = 'mapping';
        msg = 'No site map yet — mapping the site first…';
        const crawl = await spawn(buildCliCrawlPrompt({ startUrl, scope }), 'map', crypto.randomUUID(), confine);
        const { doc } = parseCliCrawlOutput(crawl);
        if (doc) { await saveMappingDoc(domain, doc).catch(() => {}); map = doc; mapUsed = 'created'; }
      }

      phase = 'planning';
      msg = 'Planning (read-only)…';
      progress = [];
      const sid = crypto.randomUUID(); // minted BEFORE the prompt so the run's tab ownership id is baked into it
      const raw = await spawn(buildAutomatePlanPrompt({ startUrl, task: outbound(sid, taskWithContext(task), startUrl), map: outbound(sid, map ?? '', startUrl), scope, multiTab: effMultiTab }), 'plan', sid, confine);
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
    // No page open is fine — the agent opens the site the task needs via the tab bridge, so force
    // multi-tab (bridge access) and drop the host/scope confinement there is nothing to anchor to.
    const noPage = !startUrl;
    const effMultiTab = true; // always allow cross-site tab spanning
    const domain = noPage ? null : domainFromUrl(startUrl);
    const scope = noPage ? null : scopeOf(startUrl); // always confine to the starting course/section
    try {
      const map = domain ? await loadMappingDoc(domain) : null;
      mapUsed = map ? 'existing' : 'none';
      phase = 'executing';
      msg = 'Running the task directly — no plan was reviewed.';
      // Resolved even with no map: "Run now" deliberately does not stall behind a crawl, so
      // instead the agent writes down what it had to learn anyway. First run leaves a rough map,
      // later runs heal and extend it — the map gets smarter through use, with nothing to invoke.
      const mapDocPath = domain ? await invoke<string>('resolve_path', { path: getMappingDocPath(domain) }).catch(() => undefined) : undefined;
      const sid = crypto.randomUUID();
      const raw = await spawn(
        buildAutomateExecPrompt({ startUrl, task: outbound(sid, taskWithContext(task), startUrl), map: outbound(sid, map ?? '', startUrl), mapDocPath, scope, multiTab: effMultiTab }),
        'exec',
        sid,
        confinementFor(startUrl, effMultiTab),
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
    const noPage = !startUrl;
    const effMultiTab = true; // always allow cross-site tab spanning
    const domain = noPage ? null : domainFromUrl(startUrl);
    const scope = noPage ? null : scopeOf(startUrl); // always confine to the starting course/section
    try {
      phase = 'executing';
      msg = 'Executing the approved plan…';
      const map = domain ? await loadMappingDoc(domain) : null;
      const mapDocPath = domain ? await invoke<string>('resolve_path', { path: getMappingDocPath(domain) }).catch(() => undefined) : undefined;
      const sid = crypto.randomUUID();
      const raw = await spawn(
        buildAutomateExecPrompt({ startUrl, task: outbound(sid, taskWithContext(task), startUrl), map: outbound(sid, map ?? '', startUrl), mapDocPath, scope, approvedPlan: outbound(sid, plan, startUrl), multiTab: effMultiTab }),
        'exec',
        sid,
        confinementFor(startUrl, effMultiTab),
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

  <div class="task-wrap">
    <textarea
      class="task"
      bind:value={task}
      placeholder={lastRun
        ? "Follow-up — e.g. now do the same for period 3"
        : "e.g. Post an announcement in the forum titled 'Welcome to class'"}
      rows="3"
      disabled={busy || enhancing}
    ></textarea>
    <button
      class="refine-chip"
      class:spinning={enhancing}
      onclick={enhanceTask}
      disabled={busy || enhancing || !task.trim() || !provider}
      title="Refine — rewrite your task into a detailed, step-by-step prompt that uses the app's capabilities (cursor, screenshots, recording, multi-tab)."
      aria-label={enhancing ? 'Refining…' : 'Refine prompt'}
    >
      <Sparkles size={15} strokeWidth={2} />
    </button>
    {#if taskBeforeEnhance !== null}
      <button class="revert-chip" onclick={revertEnhance} disabled={busy || enhancing} title="Undo the refine">revert</button>
    {/if}
  </div>

  <div class="btns">
    {#if phase === 'idle' || phase === 'done'}
      <div class="rocker" role="group" aria-label="Plan or run">
        <button class="seg plan" disabled={!task.trim() || !provider} onclick={startPlan} title="Map + plan read-only, then you approve before anything changes. Use for work you'll repeat.">
          {lastRun ? 'Plan follow-up' : 'Plan task'}
        </button>
        <button class="seg run" disabled={!task.trim() || !provider} onclick={runDirect} title="Skip planning and approval — the agent starts changing the site straight away. Use for quick one-offs.">
          {lastRun ? 'Send follow-up' : 'Run now'}
        </button>
      </div>
      {#if lastRun}
        <button class="go save-skill" onclick={saveAsSkill} title="Save this task (and its approved plan) as a reusable skill you can run again from the Skills tab.">
          ➕ Save as Skill
        </button>
      {/if}
    {/if}
    {#if phase === 'awaiting-approval'}
      <button class="go approve" onclick={approveAndRun}>✅ Approve &amp; run</button>
      <button class="cancel" onclick={cancel}>Cancel</button>
    {/if}
    {#if busy}
      <div class="run-row">
        <span class="running">
          {phase === 'mapping' ? '🕸 Mapping…' : phase === 'planning' ? '🧭 Planning…' : '⚙ Executing…'}
          <span class="elapsed" title="Elapsed time — it's still working">⏱ {fmtElapsed(elapsed)}</span>
        </span>
        <button class="cancel stop" onclick={stopRun} disabled={!currentSessionId || stopping}
          title="Terminate the running agent now — kills only this run's process, nothing else.">
          {stopping ? 'Stopping…' : '⏹ Stop'}
        </button>
      </div>
    {/if}
  </div>

  {#if savedSkillMsg}<div class="msg saved">{savedSkillMsg}</div>{/if}
  {#if msg}<div class="msg" class:warn={phase === 'awaiting-approval' && plan && planHasMutations(plan)}>{msg}</div>{/if}
  {#if mapUsed !== 'none' && phase !== 'idle'}<div class="muted small">Map: {mapUsed === 'existing' ? 'used existing site map' : 'built a new site map first'}</div>{/if}

  {#if progress.length}
    {@const recent = progress.slice(-8)}
    <div class="feed" class:live={busy}>
      <div class="feed-hdr">
        <span class="feed-title">{busy ? 'Working on it' : 'What the agent did'}</span>
        {#if busy}<span class="live-dot" aria-hidden="true"></span>{/if}
      </div>
      <ul class="steps">
        {#each recent as line, i (i + line)}
          {@const Icon = activityIcon(line)}
          <li class="step" class:current={busy && i === recent.length - 1}>
            <span class="step-ic"><Icon size={14} strokeWidth={2} /></span>
            <span class="step-lbl">{prettyActivity(line)}</span>
          </li>
        {/each}
      </ul>
    </div>
  {/if}

  {#if plan}
    {@const parsed = parsePlan(plan)}
    {@const mutCount = parsed.steps.filter((s) => s.mutates).length}
    <div class="panel plan-panel" class:pending={phase === 'awaiting-approval'}>
      <div class="panel-top">
        <span class="panel-title">Here's the plan</span>
        {#if phase === 'awaiting-approval'}<span class="pill pill-wait">Needs your OK</span>{/if}
      </div>
      {#if parsed.steps.length}
        <p class="plan-sub">
          {parsed.steps.length} step{parsed.steps.length === 1 ? '' : 's'} ·
          {#if mutCount > 0}
            <span class="danger-txt">{mutCount} {mutCount === 1 ? 'changes' : 'change'} the site</span>
          {:else}
            read-only — nothing on the site is changed
          {/if}
        </p>
        <ol class="steps-list">
          {#each parsed.steps as s (s.n)}
            <li class="pstep" class:mutates={s.mutates}>
              <span class="pnum">{s.n}</span>
              <span class="ptext">{s.text}</span>
              {#if s.mutates}<span class="pill pill-danger" title="This step changes the site">changes site</span>{/if}
            </li>
          {/each}
        </ol>
        {#if parsed.risk}
          <div class="risk">
            <span class="risk-ic"><AlertTriangle size={15} strokeWidth={2.2} /></span>
            <div><span class="risk-hdr">Heads up</span> {parsed.risk}</div>
          </div>
        {/if}
      {:else}
        <pre class="doc">{plan}</pre>
      {/if}
    </div>
  {/if}

  {#if result}
    {@const rs = summarizeRunResult(result)}
    <div class="panel result-panel">
      <div class="panel-top">
        <span class="result-ic"><CheckCircle2 size={16} strokeWidth={2.2} /></span>
        <span class="panel-title">Done</span>
      </div>

      {#if rs.steps.length || rs.verdict}
        <!-- Lead with the verdict and what CHANGED; the step-by-step markdown is one click away.
             An unparsed report (no steps, no verdict) falls through to the raw text below. -->
        {#if rs.verdict}<p class="verdict">{rs.verdict}</p>{/if}

        {#if rs.steps.length}
          <div class="rchips">
            <span class="chip ok">✓ {rs.done} done</span>
            {#if rs.skipped}<span class="chip warn">⚠ {rs.skipped} skipped</span>{/if}
            {#if rs.failed}<span class="chip bad">✕ {rs.failed} failed</span>{/if}
          </div>
        {/if}

        {#if rs.changed.length}
          <p class="clabel">{rs.noChanges ? 'Nothing was changed' : 'Changed'}</p>
          <ul class="chlist">
            {#each rs.changed as c}<li class:none={rs.noChanges}>{c}</li>{/each}
          </ul>
        {/if}

        {#if rs.steps.length}
          <details class="rsteps">
            <summary>{rs.steps.length} step{rs.steps.length === 1 ? '' : 's'}</summary>
            <ul class="slist">
              {#each rs.steps as s}
                <li class={s.status}><span class="sdot"></span>{s.text}</li>
              {/each}
            </ul>
          </details>
        {/if}

        <details class="rfull">
          <summary>Full report</summary>
          <!-- Same sanitized renderer the verify report uses — agent markdown is never trusted
               into {@html} unsanitized. -->
          <div class="md">
            {#await renderSkillPreview(result) then html}{@html html}{:catch}<pre class="doc">{result}</pre>{/await}
          </div>
        </details>
      {:else}
        <div class="result-body">{result}</div>
      {/if}
    </div>
  {/if}
</div>

<style>
  .automate { display: flex; flex-direction: column; gap: var(--spacing-3); }
  .muted { color: var(--text-secondary); font-size: 0.85rem; margin: 0; }
  .small { font-size: 0.78rem; }
  .task-wrap { position: relative; }
  .task {
    width: 100%; box-sizing: border-box; resize: none;
    background: var(--bg-input); color: var(--text-primary);
    border: 1px solid var(--border-color); border-radius: var(--radius-md);
    padding: var(--spacing-2); font-size: 0.9rem;
    /* leave a chin clear of the text for the floating refine chip */
    padding-bottom: 34px;
  }
  /* Floating sparkles chip sitting in the textarea's chin. */
  .refine-chip {
    position: absolute; right: 8px; bottom: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--bg-card, var(--bg-input)); color: var(--color-primary);
    border: 1px solid var(--border-color); cursor: pointer; padding: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.18);
    transition: color .12s ease, border-color .12s ease, transform .1s ease;
  }
  .refine-chip:hover:not(:disabled) { border-color: var(--color-primary); transform: translateY(-1px); }
  .refine-chip:disabled { opacity: 0.45; cursor: not-allowed; }
  .refine-chip.spinning :global(svg) { animation: refine-spin 0.9s linear infinite; }
  @keyframes refine-spin { to { transform: rotate(360deg); } }
  .revert-chip {
    position: absolute; right: 42px; bottom: 10px;
    background: none; border: none; padding: 0; cursor: pointer;
    font-size: 0.76rem; color: var(--text-secondary); text-decoration: underline;
  }
  .revert-chip:disabled { opacity: 0.5; cursor: not-allowed; }
  .followup {
    display: flex; align-items: baseline; gap: var(--spacing-2); flex-wrap: wrap;
    font-size: 0.8rem; color: var(--text-secondary);
    border-left: 2px solid var(--color-primary); padding-left: var(--spacing-2);
  }
  .followup em { color: var(--text-primary); font-style: normal; }
  .link { background: none; border: none; padding: 0; color: var(--color-primary); cursor: pointer; font-size: 0.8rem; text-decoration: underline; }
  .link:disabled { opacity: 0.5; cursor: not-allowed; }
  /* Full-width stacked actions — each button/group spans the sidebar. */
  .btns { display: flex; flex-direction: column; align-items: stretch; gap: var(--spacing-2); }
  /* Rocker switch: Plan (left) and Run (right) as two joined segments filling the sidebar. */
  .rocker {
    display: flex; width: 100%;
    border: 1px solid var(--border-color); border-radius: var(--radius-md);
    overflow: hidden; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12);
  }
  .seg {
    flex: 1 1 0; min-width: 0; box-sizing: border-box; text-align: center;
    background: var(--bg-input); color: var(--text-secondary);
    border: none; padding: 9px 12px; cursor: pointer;
    font-size: 0.9rem; font-weight: 500; line-height: 1.2;
    transition: background .12s ease, color .12s ease, filter .12s ease;
  }
  .seg:disabled { opacity: 0.5; cursor: not-allowed; }
  .seg.plan { border-right: 1px solid var(--border-color); }
  /* Plan is the raised, active side of the rocker. */
  .seg.plan:not(:disabled) { background: var(--color-primary); color: #fff; }
  .seg.plan:hover:not(:disabled) { filter: brightness(1.07); }
  .seg.run:hover:not(:disabled) { background: var(--bg-card, var(--bg-hover)); color: var(--color-danger, #e5484d); }
  /* One primary action (Plan). Everything else is a quieter outline so the eye lands on Plan. */
  .go {
    width: 100%; box-sizing: border-box; text-align: center;
    background: var(--bg-input); border: 1px solid var(--border-color); color: var(--text-primary);
    border-radius: var(--radius-md); padding: 9px 15px; cursor: pointer;
    font-size: 0.9rem; font-weight: 500; line-height: 1.2;
    transition: border-color .12s ease, background .12s ease, filter .12s ease;
  }
  .go:hover:not(:disabled) { border-color: var(--text-secondary); }
  .go:disabled { opacity: 0.5; cursor: not-allowed; }
  .go.approve { background: var(--color-success, #30a46c); border-color: var(--color-success, #30a46c); color: #fff; }
  .go.approve:hover:not(:disabled) { filter: brightness(1.07); }
  .go.save-skill { color: var(--color-success, #30a46c); }
  .go.save-skill:hover:not(:disabled) { border-color: var(--color-success, #30a46c); }
  .msg.saved { color: var(--color-success, #30a46c); }
  .cancel { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.85rem; }
  .stop { color: #ef4444; font-weight: 600; }
  .stop:disabled { opacity: 0.5; cursor: default; }
  .run-row { display: flex; align-items: center; gap: var(--spacing-2); }
  .running { font-size: 0.85rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 8px; }
  .run-row .stop { margin-left: auto; } /* push Stop to the right end of the timer */
  .elapsed { font-variant-numeric: tabular-nums; font-size: 0.8rem; color: var(--text-tertiary); }
  .msg {
    font-size: 0.85rem; color: var(--text-primary); background: var(--bg-card, var(--bg-input));
    border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 6px 10px;
  }
  .msg.warn { border-color: var(--color-danger, #e5484d); color: var(--color-danger, #e5484d); }
  .doc {
    white-space: pre-wrap; word-break: break-word; font-size: 0.8rem;
    background: var(--bg-input); border: 1px solid var(--border-color);
    border-radius: var(--radius-md); padding: var(--spacing-2); margin: 0; max-height: 340px; overflow: auto;
  }

  /* ---- Live activity feed ---- */
  .feed {
    background: var(--bg-card, var(--bg-input)); border: 1px solid var(--border-color);
    border-radius: var(--radius-md); padding: 10px 12px;
    display: flex; flex-direction: column; gap: 8px;
  }
  .feed.live { border-color: color-mix(in srgb, var(--color-primary) 40%, var(--border-color)); }
  .feed-hdr { display: flex; align-items: center; gap: 8px; }
  .feed-title {
    font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;
    font-weight: 700; color: var(--text-secondary);
  }
  .live-dot {
    width: 7px; height: 7px; border-radius: 50%; background: var(--color-primary);
    box-shadow: 0 0 0 0 color-mix(in srgb, var(--color-primary) 70%, transparent);
    animation: live-pulse 1.4s ease-out infinite;
  }
  @keyframes live-pulse {
    0% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--color-primary) 55%, transparent); }
    70% { box-shadow: 0 0 0 7px transparent; }
    100% { box-shadow: 0 0 0 0 transparent; }
  }
  .steps { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 3px; }
  .step {
    display: flex; align-items: center; gap: 9px;
    font-size: 0.83rem; line-height: 1.3; color: var(--text-secondary);
    padding: 3px 4px; border-radius: var(--radius-sm, 5px);
    opacity: 0.72; transition: opacity .15s ease, background .15s ease, color .15s ease;
  }
  .step:last-child { opacity: 1; color: var(--text-primary); }
  .step-ic {
    flex: 0 0 auto; display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 6px;
    background: var(--bg-input); color: var(--text-secondary);
  }
  .step-lbl { flex: 1; word-break: break-word; }
  .step.current { opacity: 1; background: color-mix(in srgb, var(--color-primary) 8%, transparent); color: var(--text-primary); }
  .step.current .step-ic { background: color-mix(in srgb, var(--color-primary) 16%, transparent); color: var(--color-primary); }

  /* ---- Panels (plan + result) ---- */
  .panel {
    background: var(--bg-card, var(--bg-input)); border: 1px solid var(--border-color);
    border-radius: var(--radius-md); padding: 12px; display: flex; flex-direction: column; gap: 9px;
  }
  .plan-panel.pending { border-color: color-mix(in srgb, var(--color-primary) 45%, var(--border-color)); }
  .panel-top { display: flex; align-items: center; gap: 8px; }
  .panel-title { font-size: 0.95rem; font-weight: 600; color: var(--text-primary); }
  .pill {
    display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 999px;
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap;
  }
  .pill-wait { margin-left: auto; color: var(--color-primary); background: color-mix(in srgb, var(--color-primary) 14%, transparent); }
  .pill-danger { color: var(--color-danger, #e5484d); background: color-mix(in srgb, var(--color-danger, #e5484d) 13%, transparent); }
  .plan-sub { margin: 0; font-size: 0.82rem; color: var(--text-secondary); }
  .danger-txt { color: var(--color-danger, #e5484d); font-weight: 600; }

  .steps-list {
    list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px;
    max-height: 360px; overflow: auto;
  }
  .pstep {
    display: flex; gap: 10px; align-items: flex-start;
    font-size: 0.85rem; line-height: 1.4; color: var(--text-primary);
    background: var(--bg-input); border: 1px solid var(--border-color);
    border-radius: 8px; padding: 8px 10px;
  }
  .pstep.mutates {
    border-color: color-mix(in srgb, var(--color-danger, #e5484d) 45%, var(--border-color));
    background: color-mix(in srgb, var(--color-danger, #e5484d) 6%, var(--bg-input));
  }
  .pnum {
    flex: 0 0 auto; display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 50%; margin-top: 1px;
    background: var(--bg-card, var(--border-color)); border: 1px solid var(--border-color);
    font-size: 0.75rem; font-weight: 700; font-variant-numeric: tabular-nums; color: var(--text-secondary);
  }
  .pstep.mutates .pnum { background: var(--color-danger, #e5484d); border-color: var(--color-danger, #e5484d); color: #fff; }
  .ptext { flex: 1; word-break: break-word; }
  .pstep .pill-danger { flex: 0 0 auto; margin-top: 2px; }

  .risk {
    display: flex; gap: 9px; align-items: flex-start;
    font-size: 0.83rem; line-height: 1.45; color: var(--text-primary);
    background: color-mix(in srgb, var(--color-danger, #e5484d) 9%, var(--bg-input));
    border: 1px solid color-mix(in srgb, var(--color-danger, #e5484d) 45%, var(--border-color));
    border-radius: 8px; padding: 9px 11px;
  }
  .risk-ic { flex: 0 0 auto; color: var(--color-danger, #e5484d); margin-top: 1px; }
  .risk-hdr { font-weight: 700; color: var(--color-danger, #e5484d); margin-right: 3px; }

  .result-panel { border-color: color-mix(in srgb, var(--color-success, #30a46c) 35%, var(--border-color)); }
  .result-ic { display: inline-flex; color: var(--color-success, #30a46c); }
  .result-body {
    white-space: pre-wrap; word-break: break-word;
    font-size: 0.87rem; line-height: 1.5; color: var(--text-primary);
    max-height: 360px; overflow: auto;
  }

  /* Summarized result: verdict + counts + what changed, with the detail collapsed. */
  .verdict { margin: 0 0 8px; font-size: 0.9rem; line-height: 1.5; color: var(--text-primary); }
  .rchips { display: flex; flex-wrap: wrap; gap: 6px; margin: 0 0 8px; }
  .chip { font-size: 0.75rem; font-weight: 600; padding: 3px 10px; border-radius: 999px; }
  .chip.ok { background: rgba(34,197,94,.15); color: #22c55e; }
  .chip.warn { background: rgba(217,119,6,.18); color: #d97706; }
  .chip.bad { background: rgba(185,28,28,.18); color: #ef4444; }
  .clabel { margin: 8px 0 4px; font-size: 0.72rem; text-transform: uppercase; letter-spacing: .04em; opacity: .6; }
  .chlist { list-style: none; margin: 0 0 6px; padding: 0; display: flex; flex-direction: column; gap: 4px; }
  .chlist li {
    font-size: 0.8rem; line-height: 1.45; word-break: break-word;
    background: rgba(217,119,6,.10); border-left: 3px solid #d97706;
    border-radius: 4px; padding: 5px 9px;
  }
  /* A read-only run's "nothing changed" line is reassurance, not a warning. */
  .chlist li.none { background: rgba(34,197,94,.10); border-left-color: #22c55e; }
  .rsteps, .rfull { margin: 6px 0 0; }
  .rsteps summary, .rfull summary { cursor: pointer; font-size: 0.78rem; opacity: .75; user-select: none; }
  .rsteps summary:hover, .rfull summary:hover { opacity: 1; }
  .slist { list-style: none; margin: 6px 0 0; padding: 0; display: flex; flex-direction: column; gap: 3px; }
  .slist li {
    display: flex; align-items: flex-start; gap: 7px;
    font-size: 0.8rem; line-height: 1.45; opacity: .85; word-break: break-word;
  }
  .sdot { width: 6px; height: 6px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; background: #22c55e; }
  .slist li.skipped .sdot { background: #d97706; }
  .slist li.failed .sdot { background: #ef4444; }
  .rfull .md { max-height: 300px; overflow: auto; font-size: 0.82rem; line-height: 1.5; margin-top: 6px; }
  .rfull :global(h1) { font-size: 0.95rem; margin: 0 0 6px; }
  .rfull :global(h2) { font-size: 0.87rem; margin: 10px 0 4px; }
  .rfull :global(ul) { margin: 4px 0; padding-left: 18px; }
  .rfull :global(li) { margin: 2px 0; }
  .rfull :global(p) { margin: 5px 0; }
</style>

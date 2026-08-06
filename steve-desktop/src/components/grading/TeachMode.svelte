<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedVariables: Svelte template uses script bindings */
  import { invoke } from '@tauri-apps/api/core';
  import { TeachRecorder, buildWorkflow } from '../../lib/teach-recorder';
  import { buildTeachPolishPrompt, parseTeachPolish, composeTeachSkill, type TeachPolish } from '../../lib/teach-polish';
  import { engineForProvider, cliModelArg, extractCliText } from '../../lib/agent-cli';
  import { upsertInstalledSkill } from '../../lib/skills-api';
  import { getActiveTabId, getEmbeddedUrl } from '../../lib/browser';
  import { domainFromUrl } from '../../lib/utils/index';
  import { showAgentConnected, hideAgentConnected } from '../../lib/agent-overlay';
  import { setParam } from '../../lib/teach-params';
  import { promoteToToken, setTokenValue, isSafeToPromote } from '../../lib/teach-tokens';
  import { stepMutates } from '../../lib/teach-mutates';
  import type { Workflow, WorkflowStep } from '../../lib/types/site-profile';

  let { pageUrl = '', provider = '', model = '' } = $props();

  const recorder = new TeachRecorder();
  // Mirrors AutomateRunner's Phase shape: recording produces steps, editing lets you shape them,
  // proposing polishes, awaiting-approval is the gate before anything is written to disk.
  type Phase = 'idle' | 'recording' | 'editing' | 'proposing' | 'awaiting-approval' | 'saved';
  let phase = $state<Phase>('idle');
  let steps = $state<WorkflowStep[]>([]);
  let values = $state<Record<string, string>>({}); // fixed tokens promoted out of step values
  let startUrl = $state('');
  let name = $state('');
  let narration = $state(''); // "what were you doing?" — the teacher's own statement of intent
  let msg = $state('');
  interface Proposal { name: string; description: string; summary: string; workflow: Workflow; content: string; urlPattern?: string }
  let proposal = $state<Proposal | null>(null);

  const ACTION_ICON: Record<string, string> = { click: '👆', fill: '⌨', select: '▾', navigate: '→' };

  async function start() {
    msg = '';
    steps = [];
    values = {};
    narration = '';
    proposal = null;
    startUrl = pageUrl || (await getEmbeddedUrl(getActiveTabId()).catch(() => '')) || '';
    try {
      await recorder.start((s) => (steps = [...s]));
      phase = 'recording';
      await showAgentConnected(getActiveTabId()); // same border overlay the agent uses — shows it's live
      msg = 'Recording. Click through the pages you want to teach, then Stop.';
    } catch (e) {
      msg = e instanceof Error ? e.message : String(e);
    }
  }

  async function stop() {
    await recorder.stop().catch(() => {});
    hideAgentConnected(getActiveTabId());
    steps = recorder.getSteps();
    if (!name) name = startUrl ? `Task on ${domainFromUrl(startUrl) || 'page'}` : 'Recorded task';
    phase = 'editing';
    msg = steps.length ? `Captured ${steps.length} step${steps.length === 1 ? '' : 's'}. Review, name it, and Propose.` : 'No steps captured.';
  }

  function removeStep(i: number) {
    steps = steps.filter((_, idx) => idx !== i);
  }

  // Mark a fill/select as a variable: replay asks for a roster and binds each row. The recorded
  // literal value is dropped from the saved skill (FERPA — no student data in stored skills).
  function toggleParam(i: number) {
    const s = steps[i];
    steps = steps.map((st, idx) => (idx === i ? setParam(st, s.param ? null : s.description || 'value') : st));
  }

  // Whether step i's literal value is eligible to become a named token: a fill/select, not already
  // parameterized, and not something the mask itself would flag as identifying — tokens are for
  // URLs, ids and course names, never student data. This is a UI hint only; promoteToToken
  // (teach-tokens.ts) enforces the same check as the real boundary, since a hidden button is not one.
  function canPromote(s: WorkflowStep): boolean {
    return (s.action === 'fill' || s.action === 'select') && !s.param && typeof s.value === 'string' && isSafeToPromote(s.value, startUrl);
  }

  function promoteStep(i: number) {
    const s = steps[i];
    if (!canPromote(s)) return;
    try {
      const bound = promoteToToken({ name, steps, values }, i, s.description || 'value', startUrl);
      steps = bound.steps;
      values = bound.values ?? {};
    } catch (e) {
      msg = e instanceof Error ? e.message : String(e);
    }
  }

  function setToken(key: string, value: string) {
    try {
      values = setTokenValue({ name, steps, values }, key, value, startUrl).values ?? {};
    } catch (e) {
      msg = e instanceof Error ? e.message : String(e);
      values = { ...values }; // restore the controlled input to its last accepted value
    }
  }

  // Polish is best-effort: the CLI only writes prose. If it's unavailable or fails, we still offer the
  // raw (fully replayable) workflow for approval — composeTeachSkill handles a null polish.
  async function polish(workflow: Workflow): Promise<TeachPolish | null> {
    if (!provider) return null;
    try {
      const engine = engineForProvider(provider);
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt: buildTeachPolishPrompt(workflow, startUrl, narration),
        sessionId: crypto.randomUUID(),
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: false, // text-only summarisation — no CDP/shell needed
        timeoutSecs: 120,
        stream: false,
      });
      return parseTeachPolish(extractCliText(engine, stdout));
    } catch {
      return null;
    }
  }

  // Stop -> Propose builds the skill and shows it for approval; nothing is written to disk yet.
  async function propose() {
    if (!steps.length) { msg = 'Nothing to save.'; return; }
    phase = 'proposing';
    msg = provider ? 'Polishing with the agent…' : 'Building the skill…';
    try {
      const draft: Workflow = { ...buildWorkflow(name || 'task', steps, startUrl), ...(Object.keys(values).length ? { values } : {}) };
      const p = await polish(draft);
      const skillName = (name || p?.name || 'Recorded task').trim();
      const workflow = { ...draft, name: skillName };
      const urlPattern = domainFromUrl(startUrl) || undefined;
      proposal = {
        name: skillName,
        description: p?.description ?? 'Recorded workflow — replay from the Skills tab.',
        summary: p?.summary ?? '',
        workflow,
        content: composeTeachSkill(workflow, p, urlPattern),
        urlPattern,
      };
      phase = 'awaiting-approval';
      msg = p ? 'Review the proposal — approve to save it.' : 'Polish unavailable — review the raw recording, then Save.';
    } catch (e) {
      phase = 'editing';
      msg = `Propose failed: ${e instanceof Error ? e.message : String(e)}`;
    }
  }

  function backToEditing() {
    proposal = null;
    phase = 'editing';
    msg = steps.length ? `Review, name it, and Propose.` : 'No steps captured.';
  }

  // Throw the recording away and go back to idle — the only way out short of pushing it through
  // Save. Confirmed: this destroys everything captured since Record, with nothing to undo it.
  // Two-step inline confirm, never window.confirm(): WebView2 draws native confirms behind the
  // window, so the OK button is unreachable and a discard becomes a dead-end. Arm first, then
  // commit on the second click.
  let confirmingDiscard = $state(false);
  function armDiscard() {
    confirmingDiscard = true;
  }
  function cancelDiscard() {
    confirmingDiscard = false;
  }
  function doDiscard() {
    steps = [];
    values = {};
    narration = '';
    proposal = null;
    name = '';
    phase = 'idle';
    msg = '';
    confirmingDiscard = false;
  }

  async function save() {
    if (!proposal) return;
    try {
      await upsertInstalledSkill({
        name: proposal.name,
        description: proposal.description,
        content: proposal.content,
        source: 'teach',
        isActive: 0, // opt-in like every captured skill; the user activates it on the Skills tab
        urlPattern: proposal.urlPattern ?? null,
      });
      msg = `Saved "${proposal.name}" — open the Skills tab to run it.`;
      phase = 'saved';
    } catch (e) {
      msg = `Save failed: ${e instanceof Error ? e.message : String(e)}`;
    }
  }
</script>

<section class="teach">
  <p class="teach-intro">
    Record yourself clicking through a task in the browser. STEVE turns your steps into a re-runnable
    skill that self-heals when the page changes.
  </p>

  <div class="teach-controls">
    {#if phase === 'idle' || phase === 'saved'}
      <button class="teach-btn primary" onclick={start}>● Record</button>
    {:else if phase === 'recording'}
      <button class="teach-btn stop" onclick={stop}>■ Stop</button>
    {/if}
  </div>

  {#if phase === 'editing' || phase === 'proposing'}
    {#if steps.length}
      <ol class="teach-steps">
        {#each steps as s, i (i)}
          <li>
            <span class="step-icon">{ACTION_ICON[s.action] ?? '•'}</span>
            <span class="step-desc">{s.action} <strong>{s.description}</strong>{#if s.param}<span class="step-param"> = {'{'}{s.param}{'}'}</span>{:else if s.value}<span class="step-val"> = {s.value}</span>{/if}</span>
            {#if phase === 'editing' && (s.action === 'fill' || s.action === 'select')}
              <button class="step-var" class:on={!!s.param} title={s.param ? 'Variable — replay asks for a roster of values. Click to restore the recorded value.' : 'Make this a variable: replay once per roster row instead of the recorded value.'} onclick={() => toggleParam(i)}>𝑥</button>
            {/if}
            {#if phase === 'editing' && canPromote(s)}
              <button class="step-token" title="Make this a fixed, editable token (a course URL, id, or name) shared across steps." onclick={() => promoteStep(i)}>🏷</button>
            {/if}
            {#if phase === 'editing'}<button class="step-del" title="Remove step" onclick={() => removeStep(i)}>✕</button>{/if}
          </li>
        {/each}
      </ol>
      {#if phase === 'editing' && steps.some((s) => s.action === 'fill' || s.action === 'select')}
        <p class="token-hint">
          🏷 turns a fixed value into an editable token shared across steps — it's saved in the skill
          file, so never promote anything that identifies a student. A value flagged as identifying
          won't show the 🏷 button at all.
        </p>
      {/if}
    {/if}

    {#if Object.keys(values).length}
      <div class="token-pills">
        {#each Object.entries(values) as [k, v] (k)}
          <label class="token-pill">
            <span class="token-key">{k} =</span>
            <input class="token-val" value={v} disabled={phase === 'proposing'} oninput={(e) => setToken(k, (e.target as HTMLInputElement).value)} />
          </label>
        {/each}
      </div>
    {/if}

    {#if steps.length}
      <textarea class="teach-narration" placeholder="What were you doing? (optional — helps write a better description)" bind:value={narration} disabled={phase === 'proposing'} rows="2"></textarea>
      <input class="teach-name" placeholder="Name this skill…" bind:value={name} disabled={phase === 'proposing'} />
      <div class="teach-controls">
        <button class="teach-btn primary" onclick={propose} disabled={phase === 'proposing'}>{phase === 'proposing' ? 'Proposing…' : '📝 Propose'}</button>
        {#if confirmingDiscard}
          <button class="teach-btn stop" onclick={doDiscard}>🗑 Confirm discard</button>
          <button class="teach-btn" onclick={cancelDiscard}>Keep</button>
        {:else}
          <button class="teach-btn" onclick={armDiscard} disabled={phase === 'proposing'}>🗑 Discard</button>
        {/if}
      </div>
    {/if}
  {/if}

  {#if phase === 'awaiting-approval' && proposal}
    {@const mutCount = proposal.workflow.steps.filter(stepMutates).length}
    <div class="panel plan-panel pending">
      <div class="panel-top">
        <span class="panel-title">{proposal.name}</span>
        <span class="pill pill-wait">Needs your OK</span>
      </div>
      <p class="proposal-desc">{proposal.description}</p>
      <p class="plan-sub">
        {proposal.workflow.steps.length} step{proposal.workflow.steps.length === 1 ? '' : 's'} ·
        {#if mutCount > 0}
          <span class="danger-txt">{mutCount} {mutCount === 1 ? 'changes' : 'change'} the site</span>
        {:else}
          read-only — nothing on the site is changed
        {/if}
      </p>
      <ol class="teach-steps">
        {#each proposal.workflow.steps as s, i (i)}
          <li>
            <span class="step-icon">{ACTION_ICON[s.action] ?? '•'}</span>
            <span class="step-desc">{s.action} <strong>{s.description}</strong>{#if s.param}<span class="step-param"> = {'{'}{s.param}{'}'}</span>{:else if s.value}<span class="step-val"> = {s.value}</span>{/if}</span>
            {#if stepMutates(s)}<span class="pill pill-danger" title="This step changes the site">changes site</span>{/if}
          </li>
        {/each}
      </ol>
      <div class="proposal-actions">
        <button class="teach-btn primary" onclick={save}>💾 Save as skill</button>
        <button class="teach-btn" onclick={backToEditing}>Back to editing</button>
        {#if confirmingDiscard}
          <button class="teach-btn stop" onclick={doDiscard}>🗑 Confirm discard</button>
        {:else}
          <button class="teach-btn" onclick={armDiscard}>🗑 Discard</button>
        {/if}
      </div>
    </div>
  {/if}

  {#if msg}<p class="teach-msg">{msg}</p>{/if}
</section>

<style>
  .teach { display: flex; flex-direction: column; gap: var(--spacing-3); padding: var(--spacing-2); }
  .teach-intro { font-size: 0.8rem; color: var(--color-text-muted); line-height: 1.4; margin: 0; }
  .teach-controls { display: flex; gap: var(--spacing-2); }
  .teach-btn {
    flex: 1; padding: var(--spacing-2) var(--spacing-3); border-radius: var(--radius-md);
    border: 1px solid var(--color-border); background: var(--color-bg-card);
    color: var(--color-text-primary); cursor: pointer; font-size: 0.85rem;
  }
  .teach-btn.primary { background: var(--color-primary-hover); border-color: var(--color-primary-hover); color: var(--color-primary-text); }
  .teach-btn.stop { background: #b91c1c; border-color: #b91c1c; color: #fff; }
  .teach-btn:disabled { opacity: 0.6; cursor: not-allowed; }
  .teach-steps { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; }
  .teach-steps li {
    display: flex; align-items: center; gap: var(--spacing-2); font-size: 0.8rem;
    padding: var(--spacing-1) var(--spacing-2); background: var(--color-bg-main);
    border: 1px solid var(--color-border); border-radius: var(--radius-sm);
  }
  .step-icon { flex: none; }
  .step-desc { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--color-text-secondary); }
  .step-desc strong { color: var(--color-text-primary); }
  .step-val { color: var(--color-text-muted); }
  .step-param { color: var(--color-primary-hover); font-weight: 600; }
  .step-var { flex: none; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: none; color: var(--color-text-muted); cursor: pointer; font-style: italic; line-height: 1; padding: 1px 5px; }
  .step-var.on { color: var(--color-primary-text); background: var(--color-primary-hover); border-color: var(--color-primary-hover); }
  .step-del { flex: none; border: none; background: none; color: var(--color-text-muted); cursor: pointer; }
  .step-del:hover { color: #b91c1c; }
  .step-token { flex: none; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: none; color: var(--color-text-muted); cursor: pointer; line-height: 1; padding: 1px 5px; }
  .step-token:hover { color: var(--color-primary-hover); border-color: var(--color-primary-hover); }
  .teach-name, .teach-narration {
    padding: var(--spacing-2); border-radius: var(--radius-md); border: 1px solid var(--color-border);
    background: var(--color-bg-main); color: var(--color-text-primary); font-size: 0.85rem;
  }
  .teach-narration { resize: none; font-family: inherit; }
  .teach-msg { font-size: 0.78rem; color: var(--color-text-muted); margin: 0; }

  .token-hint { font-size: 0.75rem; color: var(--color-text-muted); line-height: 1.4; margin: 2px 0 0; }

  /* Fixed-value token pills (item 2) — one editable "key = value" row per promoted step. */
  .token-pills { display: flex; flex-direction: column; gap: 4px; }
  .token-pill { display: flex; align-items: center; gap: var(--spacing-2); font-size: 0.8rem; }
  .token-key { flex: none; color: var(--color-text-secondary); font-weight: 600; }
  .token-val {
    flex: 1; min-width: 0; padding: 2px 6px; border-radius: var(--radius-sm);
    border: 1px solid var(--color-border); background: var(--color-bg-main); color: var(--color-text-primary);
    font-size: 0.8rem;
  }

  /* Approval card (item 1) — mirrors AutomateRunner's plan panel: same class names so the two
     surfaces read as one visual language. */
  .panel {
    background: var(--color-bg-card); border: 1px solid var(--color-border);
    border-radius: var(--radius-md); padding: 12px; display: flex; flex-direction: column; gap: 9px;
  }
  .plan-panel.pending { border-color: color-mix(in srgb, var(--color-primary) 45%, var(--color-border)); }
  .panel-top { display: flex; align-items: center; gap: 8px; }
  .panel-title { font-size: 0.95rem; font-weight: 600; color: var(--color-text-primary); }
  .pill {
    display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 999px;
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap;
  }
  .pill-wait { margin-left: auto; color: var(--color-primary); background: color-mix(in srgb, var(--color-primary) 14%, transparent); }
  .pill-danger { color: var(--color-danger, #e5484d); background: color-mix(in srgb, var(--color-danger, #e5484d) 13%, transparent); }
  .plan-sub { margin: 0; font-size: 0.82rem; color: var(--color-text-secondary); }
  .danger-txt { color: var(--color-danger, #e5484d); font-weight: 600; }
  .proposal-desc { margin: 0; font-size: 0.85rem; color: var(--color-text-primary); }
  .proposal-actions { display: flex; gap: var(--spacing-2); }
</style>

<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedVariables: Svelte template uses script bindings */
  import { invoke } from '@tauri-apps/api/core';
  import { TeachRecorder, buildWorkflow } from '../../lib/teach-recorder';
  import { buildTeachPolishPrompt, parseTeachPolish, composeTeachSkill } from '../../lib/teach-polish';
  import { engineForProvider, cliModelArg, extractCliText } from '../../lib/agent-cli';
  import { upsertInstalledSkill } from '../../lib/skills-api';
  import { getActiveTabId, getEmbeddedUrl } from '../../lib/browser';
  import { domainFromUrl } from '../../lib/utils/index';
  import { showAgentConnected, hideAgentConnected } from '../../lib/agent-overlay';
  import { setParam } from '../../lib/teach-params';
  import type { WorkflowStep } from '../../lib/types/site-profile';

  let { pageUrl = '', provider = '', model = '' } = $props();

  const recorder = new TeachRecorder();
  let recording = $state(false);
  let steps = $state<WorkflowStep[]>([]);
  let startUrl = $state('');
  let name = $state('');
  let busy = $state(false);
  let msg = $state('');

  const ACTION_ICON: Record<string, string> = { click: '👆', fill: '⌨', select: '▾', navigate: '→' };

  async function start() {
    msg = '';
    steps = [];
    startUrl = pageUrl || (await getEmbeddedUrl(getActiveTabId()).catch(() => '')) || '';
    try {
      await recorder.start((s) => (steps = [...s]));
      recording = true;
      await showAgentConnected(getActiveTabId()); // same border overlay the agent uses — shows it's live
      msg = 'Recording. Click through the pages you want to teach, then Stop.';
    } catch (e) {
      msg = e instanceof Error ? e.message : String(e);
    }
  }

  async function stop() {
    await recorder.stop().catch(() => {});
    hideAgentConnected(getActiveTabId());
    recording = false;
    steps = recorder.getSteps();
    if (!name) name = startUrl ? `Task on ${domainFromUrl(startUrl) || 'page'}` : 'Recorded task';
    msg = steps.length ? `Captured ${steps.length} step${steps.length === 1 ? '' : 's'}. Review, name it, and Save.` : 'No steps captured.';
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

  // Polish is best-effort: the CLI only writes prose. If it's unavailable or fails, we still save the
  // raw (fully replayable) workflow — composeTeachSkill handles a null polish.
  async function polish() {
    if (!provider) return null;
    try {
      const engine = engineForProvider(provider);
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt: buildTeachPolishPrompt(buildWorkflow(name || 'task', steps, startUrl), startUrl),
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

  async function save() {
    if (!steps.length) { msg = 'Nothing to save.'; return; }
    busy = true;
    msg = provider ? 'Polishing with the agent…' : 'Saving…';
    try {
      const p = await polish();
      const skillName = (name || p?.name || 'Recorded task').trim();
      const workflow = buildWorkflow(skillName, steps, startUrl);
      const urlPattern = domainFromUrl(startUrl) || undefined;
      const content = composeTeachSkill(workflow, p, urlPattern);
      await upsertInstalledSkill({
        name: skillName,
        description: p?.description ?? `Recorded workflow — replay from the Skills tab.`,
        content,
        source: 'teach',
        isActive: 0, // opt-in like every captured skill; the user activates it on the Skills tab
        urlPattern: urlPattern ?? null,
      });
      msg = `Saved "${skillName}" — open the Skills tab to run it.`;
      steps = [];
      name = '';
    } catch (e) {
      msg = `Save failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      busy = false;
    }
  }
</script>

<section class="teach">
  <p class="teach-intro">
    Record yourself clicking through a task in the browser. STEVE turns your steps into a re-runnable
    skill that self-heals when the page changes.
  </p>

  <div class="teach-controls">
    {#if !recording}
      <button class="teach-btn primary" onclick={start} disabled={busy}>● Record</button>
    {:else}
      <button class="teach-btn stop" onclick={stop}>■ Stop</button>
    {/if}
  </div>

  {#if steps.length}
    <ol class="teach-steps">
      {#each steps as s, i (i)}
        <li>
          <span class="step-icon">{ACTION_ICON[s.action] ?? '•'}</span>
          <span class="step-desc">{s.action} <strong>{s.description}</strong>{#if s.param}<span class="step-param"> = {'{'}{s.param}{'}'}</span>{:else if s.value}<span class="step-val"> = {s.value}</span>{/if}</span>
          {#if !recording && (s.action === 'fill' || s.action === 'select')}
            <button class="step-var" class:on={!!s.param} title={s.param ? 'Variable — replay asks for a roster of values. Click to restore the recorded value.' : 'Make this a variable: replay once per roster row instead of the recorded value.'} onclick={() => toggleParam(i)}>𝑥</button>
          {/if}
          {#if !recording}<button class="step-del" title="Remove step" onclick={() => removeStep(i)}>✕</button>{/if}
        </li>
      {/each}
    </ol>
  {/if}

  {#if !recording && steps.length}
    <input class="teach-name" placeholder="Name this skill…" bind:value={name} disabled={busy} />
    <button class="teach-btn primary" onclick={save} disabled={busy}>{busy ? 'Saving…' : '💾 Save as skill'}</button>
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
  .teach-name {
    padding: var(--spacing-2); border-radius: var(--radius-md); border: 1px solid var(--color-border);
    background: var(--color-bg-main); color: var(--color-text-primary); font-size: 0.85rem;
  }
  .teach-msg { font-size: 0.78rem; color: var(--color-text-muted); margin: 0; }
</style>

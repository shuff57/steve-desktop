<script lang="ts">
  /**
   * SkillRunner — lists saved workflow skills and replays them against the live embedded
   * browser (CDP) with Tier-3 model self-heal. Lives in the Browser page's ActionPanel because
   * replay acts on the page you're currently viewing.
   */
  import { onMount } from 'svelte';
  import { getSkills, type Skill } from '../../lib/db';
  import { skillToWorkflow } from '../../lib/workflow-skill';
  import { replayLive } from '../../lib/replay-live';
  import { connectCDP, isConnected } from '../../lib/cdp-actions';

  // Engine selection is owned by the panel (shared across tabs) and passed in.
  let { provider = '', model = '' }: { provider?: string; model?: string } = $props();

  let skills = $state<Skill[]>([]);
  let loading = $state(true);
  let runningId = $state<string | null>(null);
  let message = $state<string | null>(null);

  // Only skills carrying a recorded steps block can be replayed.
  const replayable = $derived(skills.filter((s) => /```json/.test(s.content)));

  async function load() {
    loading = true;
    try {
      skills = await getSkills();
    } catch {
      skills = [];
    } finally {
      loading = false;
    }
  }

  async function run(skill: Skill) {
    if (runningId) return;
    runningId = skill.id;
    message = `Running "${skill.name}"…`;
    try {
      const workflow = skillToWorkflow(skill.content);
      if (!workflow.steps.length) {
        message = `"${skill.name}" has no replayable steps.`;
        return;
      }
      if (!isConnected() && !(await connectCDP())) {
        message = 'Could not connect to the browser — load a page first.';
        return;
      }
      const summary = await replayLive(workflow, { provider, model });
      const c = (s: string) => summary.results.filter((r) => r.status === s).length;
      message = `${c('done')} done · ${c('recovered')} self-healed · ${c('skipped')} skipped${summary.completed ? ' · complete' : ''}`;
    } catch (e) {
      message = `Run failed: ${e instanceof Error ? e.message : 'Unknown error'}`;
    } finally {
      runningId = null;
    }
  }

  onMount(load);
</script>

<div class="skill-runner">
  <div class="runner-head">
    <span class="hdr">Replayable skills</span>
    <button class="refresh" onclick={load} title="Reload skills">↻</button>
  </div>

  {#if loading}
    <p class="muted">Loading…</p>
  {:else if replayable.length === 0}
    <p class="muted">No workflow skills yet. Capture one, or import a SKILL.md that has a recorded steps block.</p>
  {:else}
    <ul class="list">
      {#each replayable as skill (skill.id)}
        <li class="row">
          <div class="info">
            <span class="name" title={skill.name}>{skill.name}</span>
            {#if skill.url_pattern}<span class="pat">{skill.url_pattern}</span>{/if}
          </div>
          <button class="run" disabled={runningId !== null} onclick={() => run(skill)} title="Replay on the current page">
            {runningId === skill.id ? '⏳' : '▶'}
          </button>
        </li>
      {/each}
    </ul>
  {/if}

  {#if message}<div class="msg">{message}</div>{/if}
</div>

<style>
  .skill-runner { display: flex; flex-direction: column; gap: var(--spacing-2); }
  .runner-head { display: flex; align-items: center; justify-content: space-between; }
  .hdr { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); font-weight: 600; }
  .refresh { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.9rem; padding: 2px 6px; border-radius: var(--radius-sm); }
  .refresh:hover { background: var(--bg-hover); color: var(--text-primary); }
  .muted { color: var(--text-secondary); font-size: 0.85rem; margin: 0; }
  .list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: var(--spacing-1); }
  .row { display: flex; align-items: center; justify-content: space-between; gap: var(--spacing-2); padding: var(--spacing-2); background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); }
  .info { display: flex; flex-direction: column; min-width: 0; }
  .name { font-size: 0.9rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .pat { font-size: 0.7rem; color: var(--text-tertiary); }
  .run { background: transparent; border: 1px solid var(--color-success); color: var(--color-success); cursor: pointer; font-size: 0.9rem; line-height: 1; padding: 4px 10px; border-radius: var(--radius-md); flex-shrink: 0; }
  .run:hover:not(:disabled) { background: var(--color-success-bg); }
  .run:disabled { opacity: 0.5; cursor: not-allowed; }
  .msg { font-size: 0.8rem; color: var(--text-secondary); padding: var(--spacing-2); background: var(--bg-card); border-radius: var(--radius-md); }
</style>

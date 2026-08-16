<script lang="ts">
  /**
   * SkillRunner — lists saved workflow skills and replays them against the live embedded
   * browser (CDP) with Tier-3 model self-heal. Lives in the Browser page's ActionPanel because
   * replay acts on the page you're currently viewing.
   *
   * Parameterized skills (steps marked as variables in Teach mode) expand a roster box: paste
   * CSV or a spreadsheet block, one replay per row. Roster values stay local — they go to the
   * page over CDP and into the local run journal, never to a model (the heal path is already
   * slot-redacted). Every run writes a journal row so a batch against a live gradebook is
   * auditable after the fact.
   */
  import { onMount } from 'svelte';
  import { getSkills, addRunEntry, getRunEntries, saveSkill, type Skill, type RunEntry } from '../../lib/db';
  import { skillToWorkflow } from '../../lib/workflow-skill';
  import { taskForSkill } from '../../lib/agent-skill';
  import { replayLive } from '../../lib/replay-live';
  import { connectCDP, evalScript } from '../../lib/cdp-actions';
  import { workflowParams, bindWorkflow, parseRoster, rowLabel } from '../../lib/teach-params';
  import { resolveTokens } from '../../lib/teach-tokens';
  import { syncHealedAnchors } from '../../lib/workflow-heals';
  import { markPageDirty, recordPageDrift } from '../../lib/site-profiles';
  import type { Workflow } from '../../lib/types/site-profile';
  import { shouldRemap } from '../../lib/key-nodes';
  import type { HealTier } from '../../lib/replay';

  // Engine selection is owned by the panel (shared across tabs) and passed in.
  let { provider = '', model = '' }: { provider?: string; model?: string } = $props();

  let skills = $state<Skill[]>([]);
  let loading = $state(true);
  let runningId = $state<string | null>(null);
  let message = $state<string | null>(null);
  /** Skill whose roster box is open (parameterized skills only). */
  let rosterFor = $state<string | null>(null);
  let rosterText = $state('');
  let stopBatch = $state(false);
  let journal = $state<RunEntry[]>([]);
  let showJournal = $state(false);
  /** Non-empty when the skills read itself failed (bad schema), as opposed to having no skills. */
  let loadError = $state('');

  // Every active skill is listed — a recorded-steps workflow replays literally; anything else
  // (a prose/markdown skill, or an agent-task skill with no steps) hands off to the browser
  // Agent tab instead (see runViaAgent). Toggling a skill off in AI Skills > My Skills drops it
  // from this list too, since is_active also gates whether it can be matched into an agent run.
  const listed = $derived(skills.filter((s) => s.is_active !== 0));

  /** Does this skill have literal recorded steps to replay, vs. needing the Agent to interpret it? */
  function isWorkflowSkill(skill: Skill): boolean {
    try {
      return skillToWorkflow(skill.content).steps.length > 0;
    } catch {
      return false;
    }
  }

  /** Arm the recorder beside this browser and switch to it. Same door the Skills page uses —
   *  recording has to watch the embedded browser, so it happens here, not on a page. */
  function recordSkill() {
    window.dispatchEvent(new CustomEvent('steve:action-panel', { detail: { mode: 'teach' } }));
  }

  function paramsOf(skill: Skill): string[] {
    try {
      return workflowParams(skillToWorkflow(skill.content));
    } catch {
      return [];
    }
  }

  async function load() {
    loading = true;
    try {
      skills = await getSkills();
      if (loadError) loadError = '';
    } catch (e) {
      // Surfacing this matters: a swallowed read error rendered as "No workflow skills yet",
      // so a broken schema looked exactly like an empty library (it hid a failed migration
      // that had killed every skill).
      loadError = e instanceof Error ? e.message : String(e);
      skills = [];
    } finally {
      loading = false;
    }
    refreshJournal();
  }

  async function refreshJournal() {
    journal = await getRunEntries(30).catch(() => []);
  }

  async function ensureConnected(): Promise<boolean> {
    if (await connectCDP()) return true; // re-targets to the ACTIVE tab if the client is on another
    message = 'Could not connect to the browser — load a page first.';
    return false;
  }

  function summarize(summary: Awaited<ReturnType<typeof replayLive>>): { line: string; status: string } {
    const c = (s: string) => summary.results.filter((r) => r.status === s).length;
    const line = `${c('done')} done · ${c('recovered')} self-healed · ${c('skipped')} skipped${summary.completed ? ' · complete' : ''}`;
    const status = summary.completed ? 'complete' : c('done') + c('recovered') > 0 ? 'partial' : 'failed';
    return { line, status };
  }

  /** Outcome-gated heal persistence: replay rewrote selectors in the workflow only after their
   *  postconditions passed, so write the healed workflow back into the skill and mark the page
   *  dirty for a targeted re-map. Selectors/candidates only — never roster values (FERPA). */
  async function persistHeals(skill: Skill, workflow: Workflow): Promise<void> {
    try {
      const content = skill.content.replace(
        /```json\s*[\s\S]*?```/,
        '```json\n' + JSON.stringify(workflow, null, 2) + '\n```',
      );
      await saveSkill({ ...skill, content });
      skill.content = content; // keep the in-memory list consistent for the next run
      const href = await evalScript('location.href');
      if (href.success && typeof href.data === 'string') {
        await markPageDirty(new URL(href.data).hostname, href.data);
      }
    } catch {
      /* best-effort: the run already succeeded; an unsaved heal just re-heals next time */
    }
  }

  /** Drift telemetry: fold this replay's heal-tier usage into the page's running stats and, when
   *  the page is leaning on healing hard enough to look stale, flag it for a targeted re-map.
   *  Measured from ordinary runs, so no verification pass is needed to notice drift. */
  async function noteDrift(results: { tier?: string }[]): Promise<void> {
    try {
      const href = await evalScript('location.href');
      if (!href.success || typeof href.data !== 'string') return;
      const url = href.data;
      const domain = new URL(url).hostname;
      const stats = await recordPageDrift(domain, url, results);
      if (shouldRemap({ steps: stats.steps, tiers: stats.tiers as Partial<Record<HealTier, number>> })) {
        await markPageDirty(domain, url);
      }
    } catch {
      /* telemetry must never affect the run */
    }
  }

  /** Hand off to the browser Agent tab in this same drawer — same idiom the Skills page's ▶ Run
   *  uses, minus the cross-page steve:navigate (we're already on Browse). Covers both cases: the
   *  Agent panel already mounted (live event) and not yet mounted (sessionStorage, read on its
   *  own mount). */
  function runViaAgent(skill: Skill) {
    const taskText = taskForSkill(skill);
    sessionStorage.setItem('steve:pending-task', taskText);
    sessionStorage.setItem('steve:pending-task-autorun', '1');
    window.dispatchEvent(new CustomEvent('steve:load-task', { detail: { task: taskText, autoRun: true } }));
    window.dispatchEvent(new CustomEvent('steve:action-panel', { detail: { mode: 'agent' } }));
  }

  async function run(skill: Skill) {
    if (runningId) return;
    if (!isWorkflowSkill(skill)) {
      runViaAgent(skill);
      return;
    }
    // Parameterized skill → open the roster box instead of replaying the recorded literals
    // (there are none: parameterized steps store no value).
    if (paramsOf(skill).length) {
      rosterFor = rosterFor === skill.id ? null : skill.id;
      return;
    }
    runningId = skill.id;
    message = `Running "${skill.name}"…`;
    try {
      // Keep this master copy unresolved: a later selector heal must update its anchors without
      // serializing fixed-token literals back into the saved skill.
      const workflow = skillToWorkflow(skill.content);
      const tokenResolved = resolveTokens(workflow);
      if (!tokenResolved.steps.length) {
        message = `"${skill.name}" has no replayable steps.`;
        return;
      }
      if (!(await ensureConnected())) return;
      const summary = await replayLive(tokenResolved, { provider, model });
      const { line, status } = summarize(summary);
      message = line;
      await addRunEntry({ skill_id: skill.id, skill_name: skill.name, status, detail: line });
      if (summary.healed) {
        syncHealedAnchors(workflow, tokenResolved);
        await persistHeals(skill, workflow);
      }
      await noteDrift(summary.results);
    } catch (e) {
      message = `Run failed: ${e instanceof Error ? e.message : 'Unknown error'}`;
      await addRunEntry({ skill_id: skill.id, skill_name: skill.name, status: 'failed', detail: message }).catch(() => {});
    } finally {
      runningId = null;
      refreshJournal();
    }
  }

  async function runBatch(skill: Skill) {
    if (runningId) return;
    const params = paramsOf(skill);
    const roster = parseRoster(rosterText);
    const missing = params.filter((p) => !roster.headers.includes(p));
    if (!roster.rows.length) {
      message = `Paste a roster: first line "${params.join(',')}", one row per run.`;
      return;
    }
    if (missing.length) {
      message = `Roster is missing column(s): ${missing.join(', ')}.`;
      return;
    }
    const workflow = skillToWorkflow(skill.content);
    // Resolve fixed tokens ONCE, before anything else — they don't vary per row, so an unresolved
    // key is a workflow-wide problem, not a per-row one. Checking here instead of inside the loop
    // means a bad token surfaces as one clear message instead of N-1 redundant per-row failures.
    // `workflow` itself stays unresolved: it is what persistHeals writes back to storage below,
    // and a resolved literal must never be baked into the saved skill.
    let tokenResolved: Workflow;
    try {
      tokenResolved = resolveTokens(workflow);
    } catch (e) {
      message = `Can't run "${skill.name}": ${e instanceof Error ? e.message : 'a token is unresolved'}.`;
      return;
    }
    if (!(await ensureConnected())) return;
    runningId = skill.id;
    stopBatch = false;
    let ok = 0;
    let healedAny = false;
    try {
      for (let i = 0; i < roster.rows.length; i++) {
        if (stopBatch) break;
        const row = roster.rows[i];
        const label = rowLabel(row, roster.headers);
        message = `${i + 1}/${roster.rows.length} — ${label}…`;
        try {
          const bound = bindWorkflow(tokenResolved, row);
          const summary = await replayLive(bound, { provider, model });
          const { line, status } = summarize(summary);
          if (summary.healed) {
            healedAny = true;
            syncHealedAnchors(workflow, bound); // later rows replay with the healed anchors
          }
          if (status === 'complete') ok++;
          await addRunEntry({ skill_id: skill.id, skill_name: skill.name, row_label: label, status, detail: line });
        } catch (e) {
          const err = e instanceof Error ? e.message : 'Unknown error';
          await addRunEntry({ skill_id: skill.id, skill_name: skill.name, row_label: label, status: 'failed', detail: err }).catch(() => {});
        }
      }
      if (healedAny) await persistHeals(skill, workflow);
      message = stopBatch
        ? `Stopped — ${ok} of ${roster.rows.length} rows completed. Journal has the per-row record.`
        : `Batch done: ${ok}/${roster.rows.length} rows complete. Check the journal (and the site) to confirm.`;
    } finally {
      runningId = null;
      stopBatch = false;
      refreshJournal();
    }
  }

  onMount(load);
</script>

<div class="skill-runner">
  <div class="runner-head">
    <span class="hdr">Skills</span>
    <div class="runner-actions">
      <button class="record" onclick={recordSkill} title="Do the task once in this browser; STEVE writes the skill">
        🎓 Record Skill
      </button>
      <button class="refresh" onclick={load} title="Reload skills">↻</button>
    </div>
  </div>

  {#if loading}
    <p class="muted">Loading…</p>
  {:else if loadError}
    <p class="loaderr">Couldn't read your skills: {loadError}</p>
  {:else if listed.length === 0}
    <p class="muted">No active skills yet. Capture one, import a SKILL.md, or turn one on in AI Skills > My Skills.</p>
  {:else}
    <ul class="list">
      {#each listed as skill (skill.id)}
        {@const params = paramsOf(skill)}
        {@const workflowSkill = isWorkflowSkill(skill)}
        <li class="row">
          <div class="info">
            <span class="name" title={skill.name}>{skill.name}</span>
            {#if skill.url_pattern}<span class="pat">{skill.url_pattern}</span>{/if}
            {#if params.length}<span class="pat">vars: {params.join(', ')}</span>{/if}
          </div>
          <button
            class="run"
            disabled={runningId !== null}
            onclick={() => run(skill)}
            title={!workflowSkill ? 'Run in the browser Agent' : params.length ? 'Run with a roster of values' : 'Replay on the current page'}
          >
            {runningId === skill.id ? '⏳' : params.length ? '▶⋯' : '▶'}
          </button>
        </li>
        {#if rosterFor === skill.id}
          <li class="roster">
            <textarea
              rows="5"
              bind:value={rosterText}
              disabled={runningId !== null}
              placeholder={`Paste rows — first line names the columns:\n${params.join(',')}\n… one row per run (CSV or pasted from a spreadsheet)`}
            ></textarea>
            <div class="roster-actions">
              {#if runningId === skill.id}
                <button class="stop" onclick={() => (stopBatch = true)}>⏹ Stop after this row</button>
              {:else}
                <button class="go" disabled={runningId !== null} onclick={() => runBatch(skill)}>
                  Run {parseRoster(rosterText).rows.length || ''} row{parseRoster(rosterText).rows.length === 1 ? '' : 's'}
                </button>
              {/if}
            </div>
          </li>
        {/if}
      {/each}
    </ul>
  {/if}

  {#if message}<div class="msg">{message}</div>{/if}

  <div class="runner-head journal-head">
    <button class="jtoggle" onclick={() => (showJournal = !showJournal)}>
      {showJournal ? '▾' : '▸'} Recent runs {journal.length ? `(${journal.length})` : ''}
    </button>
  </div>
  {#if showJournal}
    {#if journal.length === 0}
      <p class="muted">No runs yet.</p>
    {:else}
      <ul class="jlist">
        {#each journal as e (e.id)}
          <li class="jrow" title={e.detail ?? ''}>
            <span class="jstatus {e.status}">{e.status === 'complete' ? '✓' : e.status === 'partial' ? '◐' : '✗'}</span>
            <span class="jname">{e.skill_name}{e.row_label ? ` — ${e.row_label}` : ''}</span>
            <span class="jtime">{e.created_at?.slice(5, 16) ?? ''}</span>
          </li>
        {/each}
      </ul>
    {/if}
  {/if}
</div>

<style>
  .skill-runner { display: flex; flex-direction: column; gap: var(--spacing-2); }
  .runner-head { display: flex; align-items: center; justify-content: space-between; }
  .runner-actions { display: flex; align-items: center; gap: var(--spacing-2); }
  .record { background: transparent; border: 1px solid var(--color-primary); color: var(--color-primary); cursor: pointer; font-size: 0.78rem; padding: 3px 10px; border-radius: var(--radius-md); flex-shrink: 0; }
  .record:hover { background: color-mix(in srgb, var(--color-primary) 12%, transparent); }
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
  .roster { display: flex; flex-direction: column; gap: var(--spacing-1); padding: var(--spacing-2); background: var(--bg-card); border: 1px dashed var(--border-color); border-radius: var(--radius-md); }
  .roster textarea { width: 100%; resize: vertical; font-size: 0.78rem; font-family: var(--font-mono, monospace); background: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: var(--spacing-1); box-sizing: border-box; }
  .roster-actions { display: flex; justify-content: flex-end; gap: var(--spacing-1); }
  .roster-actions .go { background: transparent; border: 1px solid var(--color-success); color: var(--color-success); cursor: pointer; font-size: 0.8rem; padding: 3px 10px; border-radius: var(--radius-md); }
  .roster-actions .go:hover:not(:disabled) { background: var(--color-success-bg); }
  .roster-actions .stop { background: transparent; border: 1px solid #b91c1c; color: #b91c1c; cursor: pointer; font-size: 0.8rem; padding: 3px 10px; border-radius: var(--radius-md); }
  .journal-head { margin-top: var(--spacing-1); }
  .jtoggle { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; font-weight: 600; padding: 0; }
  .jtoggle:hover { color: var(--text-primary); }
  .jlist { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; max-height: 180px; overflow-y: auto; }
  .jrow { display: grid; grid-template-columns: auto 1fr auto; align-items: baseline; gap: var(--spacing-2); font-size: 0.75rem; padding: 3px var(--spacing-2); background: var(--bg-card); border-radius: var(--radius-sm); }
  .jstatus.complete { color: var(--color-success); }
  .jstatus.partial { color: #d97706; }
  .jstatus.failed { color: #b91c1c; }
  .jname { color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .jtime { color: var(--text-tertiary); }
  .loaderr { font-size: 12px; color: #ef4444; background: rgba(239,68,68,.10); border-left: 3px solid #ef4444; border-radius: 4px; padding: 6px 9px; margin: 0; line-height: 1.45; }
</style>

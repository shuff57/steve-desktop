<script lang="ts">
  /**
   * OGRE rubric library. Rubrics are `skills` rows with `source = 'rubric'`; `content`
   * holds the rubric JSON that grading.ts consumes.
   *
   * The leniency slider rewrites the rubric text itself rather than appending an
   * instruction, which is why the preview shows the rewritten criteria — what you read
   * here is literally what reaches the model. It is deterministic and costs nothing, so
   * it re-renders as you drag.
   */
  import { onMount } from 'svelte';
  import { ogreIsland } from '../integrations/ogre';
  import { describeLeniency, restoreCategoryWeights, rewriteRubric } from '../integrations/ogre/leniency';
  import { evalScript, isConnected } from '../lib/cdp-actions';
  import type { Rubric } from '../integrations/ogre/grading';
  import type { Skill } from '../integrations/ogre/types';

  let rubrics = $state<Skill[]>([]);
  let selectedId = $state<string | null>(null);
  let loading = $state(true);
  let error = $state<string | null>(null);
  let leniency = $state(50);

  let importing = $state(false);
  let importError = $state<string | null>(null);
  let imported = $state<string | null>(null);

  const selected = $derived(rubrics.find((r) => r.id === selectedId) ?? null);

  /** Rubric JSON, or null when the row holds something unparseable. */
  const parsed = $derived.by((): Rubric | null => {
    if (!selected?.content) return null;
    try {
      return JSON.parse(selected.content) as Rubric;
    } catch {
      return null;
    }
  });

  /** Flat criteria text — the shape the leniency rewriter operates on. */
  const criteriaText = $derived.by(() => {
    if (!parsed) return '';
    const lines: string[] = [];
    for (const c of parsed.checklistItems ?? []) {
      lines.push(`## ${c.category}${c.points ? ` [${c.points} pts]` : ''}`);
      for (const item of c.items ?? []) lines.push(item);
      lines.push('');
    }
    return lines.join('\n').trimEnd();
  });

  const rewritten = $derived(
    leniency === 50 ? criteriaText : restoreCategoryWeights(rewriteRubric(criteriaText, leniency), criteriaText),
  );

  async function load() {
    loading = true;
    error = null;
    try {
      rubrics = await ogreIsland.methods.listRubrics();
      if (!selectedId && rubrics.length) selectedId = rubrics[0]!.id;
    } catch (e) {
      // Surfaced, not swallowed — an empty list and a failed read look identical otherwise.
      error = e instanceof Error ? e.message : String(e);
    } finally {
      loading = false;
    }
  }

  /**
   * Read the rubric off the MyOpenMath question currently open in Browse. Re-importing
   * the same question overwrites its rubric — the id is derived from the question — so
   * this is safe to run twice.
   */
  async function importFromPage() {
    importing = true;
    importError = null;
    imported = null;
    try {
      if (!isConnected()) throw new Error('Not connected to the browser. Open the question in Browse first.');
      const r = await ogreIsland.methods.importRubricFromPage(async (expression) => {
        const res = await evalScript(expression);
        if (!res.success) throw new Error(res.error ?? 'Page evaluation failed');
        return res.data;
      });
      const id = await ogreIsland.methods.saveImportedRubric(r);
      await load();
      selectedId = id;
      imported = r.name;
    } catch (e) {
      importError = e instanceof Error ? e.message : String(e);
    } finally {
      importing = false;
    }
  }

  onMount(load);
</script>

<div class="page">
  <header>
    <h1>Rubrics</h1>
    <p class="sub">Grading criteria used by OGRE. Stored as skills with source “rubric”.</p>
  </header>

  <section class="actions">
    <button onclick={importFromPage} disabled={importing}>
      {importing ? 'Reading question…' : 'Import from page'}
    </button>
    <span class="hint">Reads the grading checklist off the MyOpenMath question open in Browse. Read-only.</span>
  </section>

  {#if importError}
    <div class="error"><strong>Could not import a rubric.</strong><p>{importError}</p></div>
  {:else if imported}
    <p class="ok">Imported “{imported}”.</p>
  {/if}

  {#if loading}
    <p class="muted">Loading rubrics…</p>
  {:else if error}
    <div class="error">
      <strong>Could not load rubrics.</strong>
      <p>{error}</p>
      <button onclick={load}>Retry</button>
    </div>
  {:else if rubrics.length === 0}
    <div class="empty">
      <p>No rubrics yet.</p>
      <p class="muted">
        Your MyOpenMath questions already embed grading checklists and model responses.
        Open one in Browse and use “Import from page”.
      </p>
    </div>
  {:else}
    <div class="split">
      <ul class="list">
        {#each rubrics as r (r.id)}
          <li>
            <button class="row" class:active={r.id === selectedId} onclick={() => (selectedId = r.id)}>
              <span class="name">{r.name}</span>
              {#if r.description}<span class="desc">{r.description}</span>{/if}
            </button>
          </li>
        {/each}
      </ul>

      <section class="detail">
        {#if !selected}
          <p class="muted">Select a rubric.</p>
        {:else if !parsed}
          <div class="error">
            <strong>This rubric’s content is not valid JSON.</strong>
            <p class="muted">Grading would fail on it, so it is shown as-is rather than silently skipped.</p>
            <pre class="raw">{selected.content}</pre>
          </div>
        {:else}
          <h2>{selected.name}</h2>
          <dl class="meta">
            <div><dt>Max score</dt><dd>{parsed.maxScore ?? 10}</dd></div>
            <div><dt>Categories</dt><dd>{parsed.checklistItems?.length ?? 0}</dd></div>
            {#if parsed.weightMode}<div><dt>Weighting</dt><dd>{parsed.weightMode}</dd></div>{/if}
          </dl>

          {#if parsed.essayPrompt}
            <h3>Question</h3>
            <p class="prompt">{parsed.essayPrompt}</p>
          {/if}

          <div class="leniency">
            <div class="leniency-head">
              <label for="leniency">Rubric leniency</label>
              <span class="status" class:changed={leniency !== 50}>{describeLeniency(leniency)}</span>
            </div>
            <input id="leniency" type="range" min="0" max="100" step="5" bind:value={leniency} />
            <div class="scale"><span>Lenient</span><span>Original</span><span>Strict</span></div>
            <p class="hint">
              Rewrites the criteria themselves. Category headings and point values are never
              rewritten — they drive the score.
            </p>
          </div>

          <h3>Criteria {#if leniency !== 50}<span class="tag">rewritten</span>{/if}</h3>
          <pre class="criteria">{rewritten || '(no checklist items)'}</pre>

          {#if parsed.modelText}
            <details>
              <summary>Model response</summary>
              <pre class="criteria">{parsed.modelText}</pre>
            </details>
          {/if}
        {/if}
      </section>
    </div>
  {/if}
</div>

<style>
  .page { padding: 1.5rem; overflow-y: auto; height: 100%; }
  header { margin-bottom: 1.25rem; }
  h1 { margin: 0 0 0.25rem; font-size: 1.5rem; }
  .sub, .muted, .hint { color: var(--text-muted, #888); font-size: 0.9rem; margin: 0; }
  .actions { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem; }
  .actions button { padding: 0.45rem 0.9rem; border-radius: 6px; cursor: pointer; }
  .actions button:disabled { opacity: 0.5; cursor: default; }
  .ok { color: var(--accent, #4a9eff); font-size: 0.9rem; margin: 0 0 1rem; }
  .split { display: grid; grid-template-columns: minmax(180px, 260px) 1fr; gap: 1.5rem; align-items: start; }
  .list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.25rem; }
  .row {
    width: 100%; text-align: left; padding: 0.55rem 0.7rem; border-radius: 6px;
    border: 1px solid transparent; background: none; color: inherit; cursor: pointer;
    display: flex; flex-direction: column; gap: 0.15rem;
  }
  .row:hover { background: var(--surface-hover, rgba(127,127,127,0.1)); }
  .row.active { background: var(--surface-active, rgba(127,127,127,0.16)); border-color: var(--border, #4444); }
  .name { font-weight: 600; }
  .desc { font-size: 0.8rem; color: var(--text-muted, #888); }
  .detail { min-width: 0; }
  h2 { margin: 0 0 0.5rem; font-size: 1.2rem; }
  h3 { margin: 1.25rem 0 0.4rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted, #888); }
  .meta { display: flex; gap: 1.5rem; margin: 0; }
  .meta div { display: flex; flex-direction: column; }
  dt { font-size: 0.75rem; color: var(--text-muted, #888); }
  dd { margin: 0; font-weight: 600; }
  .prompt { margin: 0; line-height: 1.5; }
  .criteria, .raw {
    background: var(--surface, rgba(127,127,127,0.08)); padding: 0.75rem; border-radius: 6px;
    white-space: pre-wrap; word-break: break-word; font-size: 0.85rem; margin: 0; overflow-x: auto;
  }
  .leniency { margin-top: 1.5rem; padding: 0.9rem; border: 1px solid var(--border, #3333); border-radius: 8px; }
  .leniency-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem; }
  .leniency-head label { font-weight: 600; font-size: 0.9rem; }
  .status { font-size: 0.8rem; color: var(--text-muted, #888); }
  .status.changed { color: var(--accent, #4a9eff); font-weight: 600; }
  input[type='range'] { width: 100%; }
  .scale { display: flex; justify-content: space-between; font-size: 0.72rem; color: var(--text-muted, #888); }
  .hint { font-size: 0.78rem; color: var(--text-muted, #888); margin: 0.5rem 0 0; }
  .tag { font-size: 0.7rem; background: var(--accent, #4a9eff); color: #fff; padding: 0.1rem 0.4rem; border-radius: 4px; margin-left: 0.4rem; }
  .error { border: 1px solid #c0392b55; background: #c0392b11; padding: 0.9rem; border-radius: 8px; }
  .empty { padding: 2rem; text-align: center; border: 1px dashed var(--border, #3333); border-radius: 8px; }
  button { font: inherit; }
</style>

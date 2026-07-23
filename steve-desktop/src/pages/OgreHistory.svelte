<script lang="ts">
  /**
   * Past grading runs, newest first — one row per batch written by addGradingSession.
   * Read-only: this is the audit trail for what was graded, by which model, and how the
   * scores landed.
   */
  import { onMount } from 'svelte';
  import { ogreIsland } from '../integrations/ogre';
  import type { GradingSession } from '../integrations/ogre/types';

  let sessions = $state<GradingSession[]>([]);
  let loading = $state(true);
  let error = $state<string | null>(null);

  async function load() {
    loading = true;
    error = null;
    try {
      sessions = await ogreIsland.methods.listGradingSessions(100);
    } catch (e) {
      error = e instanceof Error ? e.message : String(e);
    } finally {
      loading = false;
    }
  }

  const num = (v: number | null | undefined, digits = 1) =>
    v === null || v === undefined ? '—' : Number(v).toFixed(digits);

  /** Trim a page URL down to something readable in a table cell. */
  function shortUrl(u: string | null | undefined): string {
    if (!u) return '—';
    try {
      const url = new URL(u);
      const qid = url.searchParams.get('qid');
      return qid ? `${url.pathname.split('/').pop()} · qid ${qid}` : url.pathname.split('/').pop() || u;
    } catch {
      return u.slice(0, 40);
    }
  }

  onMount(load);
</script>

<div class="page">
  <header>
    <h1>Grading history</h1>
    <p class="sub">Every batch run, newest first.</p>
  </header>

  {#if loading}
    <p class="muted">Loading…</p>
  {:else if error}
    <div class="error">
      <strong>Could not load history.</strong>
      <p>{error}</p>
      <button onclick={load}>Retry</button>
    </div>
  {:else if sessions.length === 0}
    <div class="empty">
      <p>No grading runs recorded yet.</p>
      <p class="muted">A row is written here when a batch completes.</p>
    </div>
  {:else}
    <table>
      <thead>
        <tr>
          <th>When</th><th>Model</th><th class="n">Students</th>
          <th class="n">Mean</th><th class="n">Min</th><th class="n">Max</th><th>Page</th>
        </tr>
      </thead>
      <tbody>
        {#each sessions as s (s.id)}
          <tr>
            <td class="when">{s.created_at ?? '—'}</td>
            <td>
              {s.model ?? '—'}
              {#if s.provider_id}<span class="provider">{s.provider_id}</span>{/if}
            </td>
            <td class="n">{s.student_count ?? '—'}</td>
            <td class="n strong">{num(s.mean_score)}</td>
            <td class="n">{num(s.min_score)}</td>
            <td class="n">{num(s.max_score)}</td>
            <td class="url" title={s.page_url ?? ''}>{shortUrl(s.page_url)}</td>
          </tr>
        {/each}
      </tbody>
    </table>
  {/if}
</div>

<style>
  .page { padding: 1.5rem; overflow-y: auto; height: 100%; }
  header { margin-bottom: 1.25rem; }
  h1 { margin: 0 0 0.25rem; font-size: 1.5rem; }
  .sub, .muted { color: var(--text-muted, #888); font-size: 0.9rem; margin: 0; }
  table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
  th, td { text-align: left; padding: 0.5rem 0.6rem; border-bottom: 1px solid var(--border, #3333); }
  th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted, #888); }
  .n { text-align: right; font-variant-numeric: tabular-nums; }
  .strong { font-weight: 600; }
  .when { white-space: nowrap; color: var(--text-muted, #888); }
  .provider { font-size: 0.72rem; color: var(--text-muted, #888); margin-left: 0.35rem; }
  .url { max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .error { border: 1px solid #c0392b55; background: #c0392b11; padding: 0.9rem; border-radius: 8px; }
  .empty { padding: 2rem; text-align: center; border: 1px dashed var(--border, #3333); border-radius: 8px; }
  button { font: inherit; }
</style>

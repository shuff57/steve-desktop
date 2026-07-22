<script lang="ts">
  import { onMount } from 'svelte';
  import { listArtifacts, deleteArtifact, openArtifact, readArtifact, getArtifactsDir, type Artifact } from '../lib/artifacts-api';

  let artifacts = $state<Artifact[]>([]);
  let loading = $state(true);
  let dir = $state('');
  let err = $state('');

  // In-app viewer (lightbox). Images reuse the full base64 already in the tile; videos are fetched
  // on demand (their bytes aren't in the list payload).
  let viewing = $state<Artifact | null>(null);
  let viewSrc = $state('');
  let viewLoading = $state(false);

  async function openViewer(a: Artifact) {
    viewing = a;
    viewSrc = '';
    if (a.kind === 'image' && a.thumb) {
      viewSrc = a.thumb; // already the full image
      return;
    }
    viewLoading = true;
    try {
      viewSrc = await readArtifact(a.name);
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
      viewing = null;
    } finally {
      viewLoading = false;
    }
  }

  function closeViewer() {
    viewing = null;
    viewSrc = '';
  }

  async function refresh() {
    loading = true;
    err = '';
    try {
      artifacts = await listArtifacts();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      loading = false;
    }
  }

  async function remove(name: string) {
    try {
      await deleteArtifact(name);
      if (viewing?.name === name) closeViewer();
      await refresh();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    }
  }

  function fmtSize(n: number): string {
    return n > 1_000_000 ? (n / 1_000_000).toFixed(1) + ' MB' : Math.max(1, Math.round(n / 1024)) + ' KB';
  }
  function fmtTime(ms: number): string {
    try { return new Date(ms).toLocaleString(); } catch { return ''; }
  }

  onMount(async () => {
    try { dir = await getArtifactsDir(); } catch { /* non-fatal */ }
    await refresh();
  });
</script>

<svelte:window onkeydown={(e) => { if (e.key === 'Escape') closeViewer(); }} />

<div class="artifacts">
  <header>
    <div>
      <h1>Artifacts</h1>
      <p class="sub">Screenshots and recordings the agent captured. Stored on this device, outside the project.</p>
    </div>
    <button class="refresh" onclick={refresh} disabled={loading}>↻ Refresh</button>
  </header>

  {#if dir}<p class="dir" title={dir}>{dir}</p>{/if}
  {#if err}<p class="err">{err}</p>{/if}

  {#if loading}
    <p class="empty">Loading…</p>
  {:else if artifacts.length === 0}
    <p class="empty">No artifacts yet. When the agent takes a screenshot or records a run, it shows up here.</p>
  {:else}
    <div class="grid">
      {#each artifacts as a (a.name)}
        <div class="tile">
          <button class="thumb" onclick={() => openViewer(a)} title="Open in app">
            {#if a.kind === 'image' && a.thumb}
              <img src={a.thumb} alt={a.name} />
            {:else if a.kind === 'video'}
              <span class="ph video">▶</span>
            {:else}
              <span class="ph">📄</span>
            {/if}
          </button>
          <div class="meta">
            <span class="name" title={a.name}>{a.name}</span>
            <span class="sm">{a.kind} · {fmtSize(a.size)} · {fmtTime(a.mtime)}</span>
          </div>
          <div class="actions">
            <button onclick={() => openViewer(a)}>Open</button>
            <button class="del" onclick={() => remove(a.name)}>Delete</button>
          </div>
        </div>
      {/each}
    </div>
  {/if}
</div>

{#if viewing}
  <!-- svelte-ignore a11y_click_events_have_key_events -->
  <div class="backdrop" role="presentation" onclick={closeViewer}>
    <!-- svelte-ignore a11y_click_events_have_key_events -->
    <div class="viewer" role="dialog" aria-modal="true" tabindex="-1" onclick={(e) => e.stopPropagation()}>
      <div class="viewer-bar">
        <span class="v-name" title={viewing.name}>{viewing.name}</span>
        <span class="v-actions">
          <button onclick={() => viewing && openArtifact(viewing.name)} title="Open in the OS default app">Open externally</button>
          <button class="del" onclick={() => viewing && remove(viewing.name)}>Delete</button>
          <button onclick={closeViewer} title="Close (Esc)">✕</button>
        </span>
      </div>
      <div class="viewer-body">
        {#if viewLoading}
          <p class="v-loading">Loading…</p>
        {:else if viewing.kind === 'video'}
          <!-- svelte-ignore a11y_media_has_caption -->
          <video src={viewSrc} controls autoplay></video>
        {:else if viewSrc}
          <img src={viewSrc} alt={viewing.name} />
        {:else}
          <p class="v-loading">Can't preview this file — try Open externally.</p>
        {/if}
      </div>
    </div>
  </div>
{/if}

<style>
  .artifacts { padding: 24px; overflow-y: auto; height: 100%; box-sizing: border-box; }
  header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
  h1 { margin: 0 0 4px; font-size: 22px; }
  .sub { margin: 0; opacity: .7; font-size: 13px; }
  .dir { font-size: 11px; opacity: .5; word-break: break-all; margin: 8px 0 0; }
  .err { color: #b91c1c; font-size: 13px; }
  .empty { opacity: .6; margin-top: 40px; text-align: center; }
  .refresh { padding: 6px 12px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  .refresh:disabled { opacity: .5; cursor: default; }
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-top: 20px; }
  .tile { border: 1px solid rgba(128,128,128,.25); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; }
  .thumb { border: 0; padding: 0; background: rgba(128,128,128,.08); cursor: pointer; aspect-ratio: 4 / 3; display: flex; align-items: center; justify-content: center; overflow: hidden; }
  .thumb img { width: 100%; height: 100%; object-fit: cover; }
  .ph { font-size: 40px; opacity: .5; }
  .ph.video { color: #059669; opacity: .85; }
  .meta { padding: 8px 10px; display: flex; flex-direction: column; gap: 2px; }
  .name { font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .sm { font-size: 10px; opacity: .6; }
  .actions { display: flex; gap: 6px; padding: 0 10px 10px; }
  .actions button { flex: 1; padding: 5px; font-size: 11px; border-radius: 5px; cursor: pointer; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  .del { color: #b91c1c; }

  /* In-app viewer (lightbox) */
  .backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.72); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 32px; box-sizing: border-box; }
  .viewer { background: #1a1a1a; border-radius: 10px; max-width: 92vw; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,.5); }
  .viewer-bar { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 10px 14px; border-bottom: 1px solid rgba(255,255,255,.1); color: #eee; }
  .v-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .v-actions { display: flex; gap: 8px; flex-shrink: 0; }
  .v-actions button { padding: 5px 10px; font-size: 12px; border-radius: 5px; cursor: pointer; border: 1px solid rgba(255,255,255,.2); background: transparent; color: #eee; }
  .viewer-body { display: flex; align-items: center; justify-content: center; overflow: auto; padding: 12px; }
  .viewer-body img, .viewer-body video { max-width: 88vw; max-height: 78vh; display: block; border-radius: 4px; }
  .v-loading { color: #ccc; padding: 60px; }
</style>

<script lang="ts">
  /**
   * mom-island browser — three-pane read-only question browser.
   * Left: families. Center: questions. Right: PHP preview + manifest stats.
   * MOM_ROOT is read from a Tauri setting; on first run the user picks a folder.
   *
   * Phase 3: "New question" per family opens a modal where the user picks a
   * template, sees the draft, and pastes it into MyOpenMath via CDP.
   */
  import { onMount } from 'svelte';
  import { getSetting, setSetting } from '../lib/db';
  import { momIsland, type MOMFamily, type MOMQuestion, type MomQuestionDetail, getTemplates, type MomTemplate, findTemplate } from '../integrations/mom';
  import MomDraft from './MomDraft.svelte';

  const ROOT_SETTING = 'mom_root';
  const DRAFTS_DIR_SETTING = 'mom_drafts_dir';

  let momRoot = $state<string | null>(null);
  let rootInput = $state('');
  let savingRoot = $state(false);
  let loading = $state(false);
  let err = $state<string | null>(null);

  let families = $state<MOMFamily[]>([]);
  let selectedFamily = $state<string | null>(null);
  let selectedQuestion = $state<MomQuestionDetail | null>(null);
  let loadingQuestion = $state(false);
  let questionErr = $state<string | null>(null);

  // Phase 3: draft modal state. When `draftingFamily` is set, the modal opens
  // for that family. draftsDir is the working dir the user has set.
  let draftingFamily = $state<string | null>(null);
  let draftsDir = $state<string | null>(null);
  let draftsDirInput = $state('');
  let savingDraftsDir = $state(false);
  const templates = getTemplates();

  const currentFamily = $derived<MOMFamily | null>(
    families.find((f) => f.name === selectedFamily) ?? null,
  );

  async function saveRoot() {
    if (savingRoot) return;
    const trimmed = rootInput.trim();
    if (!trimmed) return;
    savingRoot = true;
    err = null;
    try {
      await setSetting(ROOT_SETTING, trimmed);
      momRoot = trimmed;
      await loadIndex();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      savingRoot = false;
    }
  }

  async function clearRoot() {
    await setSetting(ROOT_SETTING, '');
    momRoot = null;
    rootInput = '';
    families = [];
    selectedFamily = null;
    selectedQuestion = null;
  }

  async function loadIndex() {
    if (!momRoot) return;
    loading = true;
    err = null;
    selectedFamily = null;
    selectedQuestion = null;
    try {
      const idx = await momIsland.methods.browse(momRoot);
      families = idx.families;
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
      families = [];
    } finally {
      loading = false;
    }
  }

  async function selectQuestion(q: MOMQuestion) {
    if (!momRoot || !selectedFamily) return;
    loadingQuestion = true;
    questionErr = null;
    try {
      selectedQuestion = await momIsland.methods.getQuestion(selectedFamily, q.slug, momRoot);
    } catch (e) {
      questionErr = e instanceof Error ? e.message : String(e);
      selectedQuestion = null;
    } finally {
      loadingQuestion = false;
    }
  }

  function selectFamily(name: string) {
    selectedFamily = name;
    selectedQuestion = null;
    questionErr = null;
  }

  onMount(async () => {
    const root = await getSetting(ROOT_SETTING).catch(() => null);
    if (root) {
      momRoot = root;
      await loadIndex();
    }
    const drafts = await getSetting(DRAFTS_DIR_SETTING).catch(() => null);
    if (drafts) {
      draftsDir = drafts;
      draftsDirInput = drafts;
    }
  });

  // Re-load if the user has manually edited the disk between visits. Not auto-polled.
  function refresh() {
    if (momRoot) loadIndex();
  }

  async function saveDraftsDir() {
    if (savingDraftsDir) return;
    const trimmed = draftsDirInput.trim();
    if (!trimmed) return;
    savingDraftsDir = true;
    err = null;
    try {
      await setSetting(DRAFTS_DIR_SETTING, trimmed);
      draftsDir = trimmed;
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      savingDraftsDir = false;
    }
  }

  function openDraftModal(family: string) {
    draftingFamily = family;
  }

  function closeDraftModal() {
    draftingFamily = null;
  }

  async function handleDraftCreated() {
    draftingFamily = null;
    // No re-browse: drafts live outside the source repo.
  }

  function templateFor(family: string): MomTemplate | null {
    return findTemplate(family);
  }
</script>

<div class="browser">
  <header>
    <div>
      <h1>MOM Question Browser</h1>
      <p class="sub">
        {#if momRoot}Browsing <code>{momRoot}</code>{:else}No root set — paste a path to start.{/if}
      </p>
    </div>
    <div class="header-actions">
      {#if momRoot}
        <button class="refresh" onclick={refresh} disabled={loading}>↻ Refresh</button>
        <button class="change" onclick={clearRoot}>Change root</button>
      {/if}
    </div>
  </header>

  {#if err}<p class="err">{err}</p>{/if}

  {#if !momRoot}
    <form class="root-form" onsubmit={(e) => { e.preventDefault(); saveRoot(); }}>
      <label>
        MOM root folder
        <input
          type="text"
          bind:value={rootInput}
          placeholder="C:\Users\shuff\Documents\GitHub\mom"
          required
        />
      </label>
      <button type="submit" disabled={savingRoot || rootInput.trim().length === 0}>
        {savingRoot ? 'Saving…' : 'Save'}
      </button>
    </form>
    <p class="empty">MOM_ROOT is unset. Paste the path to the folder that contains <code>questions/&lt;family&gt;/</code>.</p>
  {:else if loading}
    <p class="empty">Loading…</p>
  {:else if families.length === 0}
    <p class="empty">No families found under <code>{momRoot}/questions</code>. Is this the mom repo root?</p>
  {:else}
    <div class="panes">
      <aside class="families">
        <h2>Families</h2>
        <ul>
          {#each families as f (f.name)}
            <li>
              <button
                class="fam"
                class:active={selectedFamily === f.name}
                onclick={() => selectFamily(f.name)}
              >
                <span class="fam-name">{f.name}</span>
                <span class="fam-count">{f.count}</span>
              </button>
              {#if templateFor(f.name)}
                <button
                  class="new-q"
                  title="New question in {f.name}"
                  onclick={() => openDraftModal(f.name)}
                >+ New</button>
              {/if}
            </li>
          {/each}
        </ul>
      </aside>

      <section class="questions">
        <h2>Questions {currentFamily ? `· ${currentFamily.name}` : ''}</h2>
        {#if !currentFamily}
          <p class="empty">Select a family.</p>
        {:else}
          <ul>
            {#each currentFamily.questions as q (q.slug)}
              <li>
                <button
                  class="q"
                  class:active={selectedQuestion?.slug === q.slug}
                  onclick={() => selectQuestion(q)}
                >
                  <span class="q-slug">{q.slug}</span>
                  {#if q.hasManifest}<span class="badge">manifest</span>{/if}
                </button>
              </li>
            {/each}
          </ul>
        {/if}
      </section>

      <section class="preview">
        <h2>Preview</h2>
        {#if loadingQuestion}
          <p class="empty">Loading…</p>
        {:else if questionErr}
          <p class="err">{questionErr}</p>
        {:else if !selectedQuestion}
          <p class="empty">Select a question to preview the PHP.</p>
        {:else}
          {#if selectedQuestion.manifest.total > 0}
            <p class="stats">
              Manifest: {selectedQuestion.manifest.completed}/{selectedQuestion.manifest.total} completed
              {#if selectedQuestion.manifest.pending}· {selectedQuestion.manifest.pending} pending{/if}
            </p>
          {:else}
            <p class="stats muted">No manifest in this folder.</p>
          {/if}
          <pre>{selectedQuestion.contents}</pre>
        {/if}
      </section>
    </div>

    <footer class="drafts-config">
      {#if draftsDir}
        <span class="muted">Drafts dir: <code>{draftsDir}</code></span>
        <button class="change" onclick={() => { draftsDir = null; draftsDirInput = ''; }}>Change</button>
      {:else}
        <form class="drafts-form" onsubmit={(e) => { e.preventDefault(); saveDraftsDir(); }}>
          <label>
            Drafts working dir
            <input
              type="text"
              bind:value={draftsDirInput}
              placeholder="C:\Users\shuff\AppData\Roaming\steve-desktop\mom-drafts"
              required
            />
          </label>
          <button type="submit" disabled={savingDraftsDir || draftsDirInput.trim().length === 0}>
            {savingDraftsDir ? 'Saving…' : 'Save'}
          </button>
        </form>
        <p class="muted small">Required to enable "New question" — drafts live outside the source repo.</p>
      {/if}
    </footer>
  {/if}
</div>

{#if draftingFamily && momRoot && draftsDir}
  <MomDraft
    family={draftingFamily}
    momRoot={momRoot}
    draftsDir={draftsDir}
    onclose={closeDraftModal}
    oncreated={handleDraftCreated}
  />
{/if}

<style>
  .browser { padding: 24px; height: 100%; box-sizing: border-box; display: flex; flex-direction: column; overflow: hidden; }
  header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-shrink: 0; }
  h1 { margin: 0 0 4px; font-size: 22px; }
  .sub { margin: 0; opacity: .7; font-size: 13px; }
  .sub code { font-size: 12px; }
  .header-actions { display: flex; gap: 8px; }
  .refresh, .change { padding: 6px 12px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 13px; }
  .refresh:disabled, .change:disabled { opacity: .5; cursor: default; }
  .change { opacity: .7; }
  .root-form { display: flex; gap: 8px; align-items: end; margin-top: 16px; }
  .root-form label { display: flex; flex-direction: column; gap: 4px; flex: 1; font-size: 12px; opacity: .8; }
  .root-form input { padding: 7px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 13px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
  .root-form button { padding: 7px 14px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .root-form button:disabled { opacity: .5; cursor: default; }
  .err { color: #b91c1c; font-size: 13px; }
  .empty { opacity: .6; text-align: center; padding: 40px 16px; }
  .empty code { font-size: 12px; }

  .panes { display: grid; grid-template-columns: 200px 260px 1fr; gap: 12px; flex: 1; min-height: 0; margin-top: 16px; }
  aside, section { background: rgba(128,128,128,.06); border-radius: 8px; padding: 12px; overflow: hidden; display: flex; flex-direction: column; }
  h2 { margin: 0 0 8px; font-size: 13px; opacity: .7; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
  ul { list-style: none; margin: 0; padding: 0; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
  .fam, .q { display: flex; width: 100%; justify-content: space-between; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 6px; border: 1px solid transparent; background: transparent; color: inherit; cursor: pointer; text-align: left; font-size: 13px; }
  .fam:hover, .q:hover { background: rgba(128,128,128,.12); }
  .fam.active, .q.active { background: rgba(59,130,246,.18); border-color: rgba(59,130,246,.5); }
  .fam-count { font-size: 11px; opacity: .6; }
  .q-slug { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .badge { font-size: 10px; padding: 1px 6px; border-radius: 999px; background: rgba(34,197,94,.18); color: #22c55e; flex-shrink: 0; }

  .preview pre { flex: 1; overflow: auto; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 12.5px; line-height: 1.5; padding: 12px; border-radius: 6px; background: rgba(0,0,0,.25); margin: 0; white-space: pre; }
  .stats { margin: 0 0 8px; font-size: 12px; opacity: .85; }
  .stats.muted { opacity: .5; }

  .families li { display: flex; gap: 4px; align-items: stretch; }
  .families .fam { flex: 1; min-width: 0; }
  .new-q { padding: 0 8px; font-size: 11px; border-radius: 6px; border: 1px dashed rgba(128,128,128,.4); background: transparent; color: inherit; cursor: pointer; opacity: .7; }
  .new-q:hover { opacity: 1; border-color: rgba(59,130,246,.5); color: #3b82f6; }

  .drafts-config { margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(128,128,128,.15); display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; }
  .drafts-config .muted { font-size: 12px; opacity: .7; }
  .drafts-config .muted.small { font-size: 11px; }
  .drafts-config code { font-size: 11px; }
  .drafts-form { display: flex; gap: 8px; align-items: end; }
  .drafts-form label { display: flex; flex-direction: column; gap: 4px; flex: 1; font-size: 12px; opacity: .8; }
  .drafts-form input { padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 12px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
  .drafts-form button { padding: 6px 12px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 12px; }
  .drafts-form button:disabled { opacity: .5; cursor: default; }
  .drafts-config .change { padding: 4px 10px; font-size: 11px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; }
</style>

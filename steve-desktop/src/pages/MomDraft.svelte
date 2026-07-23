<script lang="ts">
  /**
   * mom-island draft modal — pick a template, see the draft file, push the
   * contents into MyOpenMath via CDP. Stops BEFORE submit (safety boundary).
   * Common Control and Question Text live in one textarea, split on `///`:
   * the line above the `///` is the Common Control, the rest is the question.
   */
  import { momIsland, findTemplate, getTemplates } from '../integrations/mom';

  interface Props {
    family: string;
    momRoot: string;
    draftsDir: string;
    onclose: () => void;
    oncreated?: (info: { draftPath: string; slug: string }) => void;
  }

  let { family, momRoot, draftsDir, onclose, oncreated }: Props = $props();

  const allTemplates = getTemplates();

  // State. `template` and `controlsDefault` are derived so they react to
  // family changes (the modal can be re-opened for a different family).
  const template = $derived(findTemplate(family));
  const controlsDefault = $derived(template?.anstype ?? 'num');

  let pickedTemplate = $state('');
  let slug = $state('q-new');
  let cid = $state<number | null>(null);
  let cidInput = $state('');
  let draftText = $state('');
  let draftPath = $state<string | null>(null);
  let creating = $state(false);
  let uploading = $state(false);
  let err = $state<string | null>(null);
  let lastResult = $state<string | null>(null);

  // The single textarea: first line is Common Control, then `///`, then question text.
  let body = $state('');

  // Initialize on mount and whenever the family changes. We use an effect so
  // re-opening the modal for a different family resets the form.
  $effect(() => {
    pickedTemplate = template?.sourcePath ?? allTemplates[0]?.sourcePath ?? '';
    body = `${controlsDefault}\n///\nNew question body…\n`;
  });

  async function create() {
    if (creating) return;
    if (!isValidSlug(slug)) {
      err = 'Invalid slug — use letters, digits, dot, dash, underscore (must start with alnum).';
      return;
    }
    if (!pickedTemplate) {
      err = 'Pick a template.';
      return;
    }
    creating = true;
    err = null;
    try {
      const result = await momIsland.methods.createDraft(family, {
        momRoot,
        draftsDir,
        templatePath: pickedTemplate,
        slug,
      });
      draftPath = result.draftPath;
      // Apply the current body to the file — the user may have already
      // edited it in the textarea before clicking Create.
      await writeDraftFile();
      oncreated?.({ draftPath: result.draftPath, slug });
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      creating = false;
    }
  }

  // Apply textarea to the draft file. We don't depend on a Tauri write
  // command yet — for now the textarea is a preview; the file copy is the
  // canonical artifact, edited on disk by the user with their editor of
  // choice. When they click Open in MOM, we send the textarea contents.
  function writeDraftFile() {
    // Intentionally a no-op. The user edits the .php file directly with
    // their editor; the textarea here is a staging area for the MOM paste.
    return Promise.resolve();
  }

  function parseBody(text: string): { controls: string; questionText: string } {
    const idx = text.indexOf('///');
    if (idx < 0) return { controls: controlsDefault, questionText: text };
    return {
      controls: text.slice(0, idx).trim() || controlsDefault,
      questionText: text.slice(idx + 3).replace(/^\n+/, ''),
    };
  }

  async function openInMOM() {
    if (uploading) return;
    if (!cid) {
      err = 'Enter a course ID (cid) to navigate to.';
      return;
    }
    uploading = true;
    err = null;
    lastResult = null;
    const { controls, questionText } = parseBody(body);
    try {
      const ok = await momIsland.methods.upload({ cid, controls, questionText });
      lastResult = ok
        ? 'Pasted into MyOpenMath. Review in the editor and click submit yourself.'
        : 'Could not connect to the embedded browser. Open the Browse tab and try again.';
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      uploading = false;
    }
  }

  function isValidSlug(s: string): boolean {
    return /^[A-Za-z0-9][A-Za-z0-9._-]*$/.test(s);
  }

  function updateCid() {
    const n = Number(cidInput);
    if (Number.isFinite(n) && n > 0) cid = n;
    else cid = null;
  }
</script>

<!-- svelte-ignore a11y_click_events_have_key_events a11y_no_static_element_interactions -->
<div class="backdrop" role="presentation" onclick={onclose}>
  <!-- svelte-ignore a11y_click_events_have_key_events a11y_no_static_element_interactions -->
  <div class="modal" role="dialog" aria-modal="true" tabindex="-1" onclick={(e) => e.stopPropagation()}>
    <header>
      <h2>New question in {family}</h2>
      <button class="x" onclick={onclose} aria-label="Close">×</button>
    </header>

    {#if err}<p class="err">{err}</p>{/if}
    {#if lastResult}<p class="ok">{lastResult}</p>{/if}

    <div class="row">
      <label>
        Template
        <select bind:value={pickedTemplate}>
          {#each allTemplates as t (t.sourcePath)}
            <option value={t.sourcePath}>{t.label}</option>
          {/each}
        </select>
      </label>
      <label>
        Slug
        <input type="text" bind:value={slug} />
      </label>
    </div>

    <label class="full">
      Body (first line = Common Control, then <code>///</code>, then question text)
      <textarea bind:value={body} rows="12"></textarea>
    </label>

    {#if draftPath}
      <p class="muted">Drafted to <code>{draftPath}</code></p>
    {/if}

    <div class="row">
      <label>
        Course ID (cid)
        <input
          type="number"
          bind:value={cidInput}
          oninput={updateCid}
          placeholder="306621"
        />
      </label>
    </div>

    <footer>
      <button onclick={create} disabled={creating}>
        {creating ? 'Creating…' : draftPath ? 'Recreate draft' : 'Create draft'}
      </button>
      <button class="primary" onclick={openInMOM} disabled={uploading || !cid}>
        {uploading ? 'Opening…' : 'Open in MOM (paste, do not submit)'}
      </button>
    </footer>
  </div>
</div>

<style>
  .backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 32px; box-sizing: border-box; }
  .modal { background: #1a1a1a; color: #eee; border-radius: 10px; padding: 20px 22px; max-width: 720px; width: 100%; max-height: 82vh; display: flex; flex-direction: column; box-shadow: 0 10px 40px rgba(0,0,0,.5); }
  header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
  h2 { margin: 0; font-size: 16px; }
  .x { background: none; border: none; color: inherit; cursor: pointer; font-size: 22px; padding: 0 4px; line-height: 1; }
  .row { display: flex; gap: 12px; margin-bottom: 12px; }
  .row label { display: flex; flex-direction: column; gap: 4px; flex: 1; font-size: 12px; opacity: .85; }
  .full { display: flex; flex-direction: column; gap: 4px; flex: 1; font-size: 12px; opacity: .85; min-height: 0; }
  .full textarea { flex: 1; min-height: 180px; padding: 10px 12px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: rgba(0,0,0,.25); color: inherit; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 12.5px; line-height: 1.5; resize: vertical; }
  input, select { padding: 7px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 13px; }
  code { background: rgba(128,128,128,.18); border-radius: 4px; padding: 0 4px; font-size: 11px; }
  footer { display: flex; gap: 8px; justify-content: flex-end; margin-top: 12px; }
  footer button { padding: 8px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,.2); background: transparent; color: #eee; cursor: pointer; font-size: 13px; }
  footer button:disabled { opacity: .5; cursor: default; }
  footer .primary { background: #3b82f6; border-color: #3b82f6; color: #fff; }
  footer .primary:disabled { background: rgba(59,130,246,.4); border-color: rgba(59,130,246,.4); }
  .err { color: #fca5a5; font-size: 13px; margin: 0 0 8px; }
  .ok { color: #86efac; font-size: 13px; margin: 0 0 8px; }
  .muted { font-size: 12px; opacity: .7; margin: 0 0 8px; }
  .muted code { font-size: 11px; }
</style>

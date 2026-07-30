<script lang="ts">
  /**
   * Write one question from a problem-set link, a description, or a pasted example, then loop
   * until the sandbox renders it clean.
   *
   * The agent writes the file through the CLI's own tools; this component does the half the agent
   * cannot do honestly — render the result and judge it — and hands any failure back. The run is
   * streamed line by line because the point is watching it happen, not getting a summary at the end.
   */
  import { invoke } from '@tauri-apps/api/core';
  import { listen, type UnlistenFn } from '@tauri-apps/api/event';
  import {
    buildAuthorPrompt,
    buildRepairPrompt,
    questionPath,
    shouldRetry,
    hasSource,
    MAX_ATTEMPTS,
    type AttemptResult,
  } from '../../integrations/mom/author';
  import { addQuestionToAssignment } from '../../integrations/mom/book-membership';
  import { momIsland } from '../../integrations/mom';
  import { questionHealth } from '../../integrations/mom/health';
  import { prepareRenderHtml } from '../../integrations/mom/render-html';
  import { cliModelArg, extractCliText, engineForProvider, summarizeCliLine, type AgentEngine } from '../../lib/agent-cli';

  let {
    root,
    sandboxUrl,
    families = [],
    placements = [],
    provider = null,
    model = null,
    onDone = (_: string) => {},
  } = $props<{
    root: string;
    sandboxUrl: string;
    /** Existing question families, for the picker. */
    families?: string[];
    /** Every book, with its assignments — including empty ones, which are still selectable. */
    placements?: { slug: string; title: string; items: { path: string; name: string }[] }[];
    provider?: string | null;
    model?: string | null;
    onDone?: (path: string) => void | Promise<void>;
  }>();

  const DEFAULT_SECTION = '1.1_definitions_of_statistics_probability_and_key_terms.html';

  let link = $state(DEFAULT_SECTION);
  let brief = $state('');
  /** Path on disk of the example image; set by pasting, or typed by hand. */
  let imagePath = $state('');
  /** Object URL for the pasted image, so the thumbnail shows what was actually captured. */
  let imagePreview = $state<string | null>(null);
  let pasteErr = $state<string | null>(null);
  let family = $state('descriptive-stats');
  /** Typing a new family instead of picking one. */
  let newFamily = $state(false);
  /** Where to file the finished question: pick a book, then one of its assignments. */
  let placeBook = $state('');
  let placeAssignment = $state('');
  let slug = $state('q1-key-terms');
  let running = $state(false);
  let lines = $state<string[]>([]);
  let attempts = $state<AttemptResult[]>([]);
  let finalPath = $state<string | null>(null);
  let fatal = $state<string | null>(null);

  const engine = $derived<AgentEngine>(engineForProvider(provider ?? undefined));
  const target = $derived(root ? questionPath(root, family.trim(), slug.trim()) : '');
  const sourced = $derived(hasSource({ link, brief, imagePath }));
  const chosenBook = $derived(placements.find((p: { slug: string }) => p.slug === placeBook) ?? null);
  // Changing book must clear a stale assignment, or the question files into the previous book's.
  $effect(() => {
    if (placeBook && !chosenBook?.items.some((i: { path: string }) => i.path === placeAssignment)) {
      placeAssignment = '';
    }
  });
  const canRun = $derived(!running && !!root && !!family.trim() && !!slug.trim() && sourced);

  function log(text: string) {
    lines = [...lines, text];
  }

  /**
   * Take an image off the clipboard and spill it to disk, because the agent opens a FILE.
   *
   * Bound to the whole panel rather than one input: Ctrl+V after a screenshot should just work,
   * without first having to find and focus the right box.
   */
  async function onPaste(e: ClipboardEvent) {
    if (running) return;
    const items = Array.from(e.clipboardData?.items ?? []);
    const item = items.find((i) => i.type.startsWith('image/'));
    const file = item?.getAsFile();
    if (!file) return; // a normal text paste — leave it to the focused field
    e.preventDefault();
    pasteErr = null;
    try {
      const bytes = [...new Uint8Array(await file.arrayBuffer())];
      imagePath = await invoke<string>('mom_save_pasted_image', { bytes });
      if (imagePreview) URL.revokeObjectURL(imagePreview);
      imagePreview = URL.createObjectURL(file);
    } catch (err) {
      pasteErr = err instanceof Error ? err.message : String(err);
    }
  }

  function clearImage() {
    if (imagePreview) URL.revokeObjectURL(imagePreview);
    imagePreview = null;
    imagePath = '';
    pasteErr = null;
  }

  /** Run one agent turn, streaming its output into the log. */
  async function turn(prompt: string): Promise<string> {
    const sessionId = crypto.randomUUID();
    let unlisten: UnlistenFn | null = null;
    try {
      unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
        if (ev.payload.sessionId !== sessionId) return; // other runs share this channel
        const summary = summarizeCliLine(ev.payload.line);
        if (summary) log(summary);
      });
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // it has to read the private repo and write the file
        timeoutSecs: 900,
        stream: true, // the whole point: watch it being written
      });
      return extractCliText(engine, stdout).trim();
    } finally {
      unlisten?.();
    }
  }

  /**
   * Render the file the agent wrote and judge it. The agent's own account is not evidence.
   *
   * Reads through the island's existing question reader rather than a raw file read, so the path
   * is resolved and guarded the same way the browser does it.
   */
  async function verify(): Promise<string[]> {
    const detail = await momIsland.methods
      .getQuestion(family.trim(), `${slug.trim().replace(/\.php$/i, '')}.php`, root)
      .catch(() => null);
    const contents = detail?.contents ?? '';
    if (!contents.trim()) return ['The agent did not write the file, or wrote it empty.'];
    const res = await fetch(sandboxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'text/plain' },
      body: contents,
    });
    if (!res.ok) return [`sandbox HTTP ${res.status}`];
    const health = questionHealth(contents, prepareRenderHtml(await res.text()));
    return [...health.errors, ...health.warnings];
  }

  async function run() {
    if (!canRun) return;
    running = true;
    lines = [];
    attempts = [];
    finalPath = null;
    fatal = null;
    const path = target;
    try {
      log(`Writing ${family}/${slug}.php`);
      let reply = await turn(
        buildAuthorPrompt({
          link: link.trim() || undefined,
          brief: brief.trim() || undefined,
          imagePath: imagePath.trim() || undefined,
          family: family.trim(),
          slug: slug.trim(),
          targetPath: path,
        }),
      );
      if (reply) log(reply);

      for (;;) {
        const errors = await verify();
        const n = attempts.length + 1;
        attempts = [...attempts, { attempt: n, errors, ok: errors.length === 0 }];
        if (errors.length === 0) {
          log(`Attempt ${n}: renders clean.`);
          finalPath = path;
          // File it only once it actually renders — a broken question must never enter a book.
          // A filing failure does not fail the run: the question is already written and good.
          if (placeAssignment) {
            try {
              await addQuestionToAssignment(
                root,
                placeAssignment,
                `questions/${family.trim()}/${slug.trim().replace(/\.php$/i, '')}.php`,
                reply || slug.trim(),
              );
              log(`Added to ${chosenBook?.items.find((i: { path: string }) => i.path === placeAssignment)?.name ?? placeAssignment}`);
            } catch (e) {
              log(`Could not file it: ${e instanceof Error ? e.message : String(e)}`);
            }
          }
          await onDone(path);
          return;
        }
        log(`Attempt ${n}: ${errors[0]}`);
        if (!shouldRetry(attempts)) {
          log(`Stopping after ${MAX_ATTEMPTS} attempts — over to you.`);
          return;
        }
        reply = await turn(buildRepairPrompt(path, errors, attempts.length + 1));
        if (reply) log(reply);
      }
    } catch (e) {
      fatal = e instanceof Error ? e.message : String(e);
    } finally {
      running = false;
    }
  }
</script>

<!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
<div class="author" onpaste={onPaste}>
  <div class="fields">
    <label>Problem set link <span class="opt">optional</span>
      <input bind:value={link} disabled={running} spellcheck="false" placeholder="https://… or 1.1_….html" />
    </label>

    <label>Describe the question <span class="opt">{link.trim() ? 'optional' : 'required'}</span>
      <textarea
        rows="2"
        bind:value={brief}
        disabled={running}
        placeholder="e.g. two-part: build a 95% CI, then interpret it"
      ></textarea>
    </label>

    <div class="shot">
      <span class="lbl">Example question <span class="opt">optional — paste a screenshot</span></span>
      {#if imagePreview}
        <div class="thumb">
          <img src={imagePreview} alt="Pasted example question" />
          <button class="x" title="Remove" disabled={running} onclick={clearImage}>×</button>
        </div>
      {:else}
        <div class="dropzone" class:err={!!pasteErr}>
          {pasteErr ?? 'Copy an image, then press Ctrl+V anywhere in this panel.'}
        </div>
      {/if}
    </div>

    <div class="pair">
      <label>Family
        {#if newFamily}
          <input bind:value={family} disabled={running} spellcheck="false" placeholder="new-family" />
        {:else}
          <select bind:value={family} disabled={running}>
            {#each families as f (f)}<option value={f}>{f}</option>{/each}
          </select>
        {/if}
      </label>
      <button class="plus" title={newFamily ? 'Pick an existing family' : 'New family'} disabled={running} onclick={() => (newFamily = !newFamily)}>
        {newFamily ? '↩' : '+'}
      </button>
      <label>Slug<input bind:value={slug} disabled={running} spellcheck="false" /></label>
    </div>

    {#if placements.length}
      <label>Add to book <span class="opt">optional</span>
        <select bind:value={placeBook} disabled={running}>
          <option value="">— don't file it —</option>
          {#each placements as p (p.slug)}
            <option value={p.slug}>{p.title} ({p.items.length})</option>
          {/each}
        </select>
      </label>

      {#if placeBook}
        <label>Assignment
          {#if chosenBook && chosenBook.items.length}
            <select bind:value={placeAssignment} disabled={running}>
              <option value="">— choose —</option>
              {#each chosenBook.items as it (it.path)}
                <option value={it.path}>{it.name}</option>
              {/each}
            </select>
          {:else}
            <span class="hint">This book has no assignments yet, so there is nothing to file into.</span>
          {/if}
        </label>
      {/if}
    {/if}
    <p class="target" title={target}>{target || 'Set the MOM root first.'}</p>
    <button class="go" onclick={run} disabled={!canRun}>
      {running ? 'Writing…' : 'Write question'}
    </button>
    {#if !sourced && !running}
      <p class="hint">Give it a link, a description, or a pasted example.</p>
    {/if}
  </div>

  {#if lines.length}
    <div class="log">
      {#each lines as l, i (i)}<div class="line">{l}</div>{/each}
      {#if running}<div class="line pending">…</div>{/if}
    </div>
  {/if}

  {#if finalPath}
    <p class="ok">Question renders clean. Open it in the browser to preview and check answers.</p>
  {/if}
  {#if fatal}<p class="bad">{fatal}</p>{/if}
</div>

<style>
  .author { display: flex; flex-direction: column; gap: 8px; min-height: 0; }
  .fields { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; }
  .pair { display: flex; gap: 6px; }
  .pair label { flex: 1; min-width: 0; }
  label { display: flex; flex-direction: column; gap: 2px; font-size: 11px; text-transform: uppercase;
          letter-spacing: .05em; opacity: .6; }
  input { font: inherit; font-size: 12px; padding: 4px 7px; border-radius: 6px; text-transform: none;
          border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  input:disabled, select:disabled, textarea:disabled { opacity: .55; }
  select, textarea { font: inherit; font-size: 12px; padding: 4px 7px; border-radius: 6px; text-transform: none;
                     border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  textarea { resize: vertical; }
  .opt { text-transform: none; letter-spacing: 0; opacity: .6; font-weight: 400; }
  .pair { align-items: flex-end; }
  .plus { flex-shrink: 0; width: 26px; height: 26px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3);
          background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .plus:disabled { opacity: .45; cursor: default; }
  .hint { margin: 0; font-size: 11px; opacity: .6; text-transform: none; letter-spacing: 0; }
  .shot { display: flex; flex-direction: column; gap: 2px; }
  .lbl { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; opacity: .6; }
  .dropzone { font-size: 11px; opacity: .55; padding: 8px; border-radius: 6px; text-align: center;
              border: 1px dashed rgba(128,128,128,.4); }
  .dropzone.err { color: #b91c1c; opacity: 1; border-color: rgba(185,28,28,.5); }
  .thumb { position: relative; }
  .thumb img { display: block; width: 100%; max-height: 120px; object-fit: contain;
               border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: rgba(128,128,128,.08); }
  .x { position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; border-radius: 50%;
       border: none; background: rgba(0,0,0,.6); color: #fff; cursor: pointer; font-size: 13px; line-height: 1; }
  .target { margin: 0; font-size: 11px; opacity: .5; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .go { padding: 6px 12px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3);
        background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .go:disabled { opacity: .45; cursor: default; }
  .log { flex: 1; overflow-y: auto; min-height: 0; display: flex; flex-direction: column; gap: 3px;
         border-top: 1px solid rgba(128,128,128,.2); padding-top: 8px; }
  .line { font-size: 12px; line-height: 1.4; opacity: .85; overflow-wrap: anywhere; }
  .pending { opacity: .5; font-style: italic; }
  .ok { margin: 0; font-size: 12px; color: #1b5e20; }
  .bad { margin: 0; font-size: 12px; color: #b91c1c; }
</style>

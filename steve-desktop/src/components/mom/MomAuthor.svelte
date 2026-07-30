<script lang="ts">
  /**
   * Write one question from a bookSHelf section, then loop until the sandbox renders it clean.
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
    MAX_ATTEMPTS,
    type AttemptResult,
  } from '../../integrations/mom/author';
  import { momIsland } from '../../integrations/mom';
  import { questionHealth } from '../../integrations/mom/health';
  import { prepareRenderHtml } from '../../integrations/mom/render-html';
  import { cliModelArg, extractCliText, engineForProvider, summarizeCliLine, type AgentEngine } from '../../lib/agent-cli';

  let {
    root,
    sandboxUrl,
    provider = null,
    model = null,
    onDone = (_: string) => {},
  } = $props<{
    root: string;
    sandboxUrl: string;
    provider?: string | null;
    model?: string | null;
    onDone?: (path: string) => void | Promise<void>;
  }>();

  const DEFAULT_SECTION = '1.1_definitions_of_statistics_probability_and_key_terms.html';

  let section = $state(DEFAULT_SECTION);
  let family = $state('descriptive-stats');
  let slug = $state('q1-key-terms');
  let running = $state(false);
  let lines = $state<string[]>([]);
  let attempts = $state<AttemptResult[]>([]);
  let finalPath = $state<string | null>(null);
  let fatal = $state<string | null>(null);

  const engine = $derived<AgentEngine>(engineForProvider(provider ?? undefined));
  const target = $derived(root ? questionPath(root, family.trim(), slug.trim()) : '');
  const canRun = $derived(!running && !!root && !!family.trim() && !!slug.trim() && !!section.trim());

  function log(text: string) {
    lines = [...lines, text];
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
  async function verify(_path: string): Promise<string[]> {
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
      log(`Writing ${family}/${slug}.php from ${section}`);
      let reply = await turn(buildAuthorPrompt({ section: section.trim(), family: family.trim(), slug: slug.trim(), targetPath: path }));
      if (reply) log(reply);

      for (;;) {
        const errors = await verify(path);
        const n = attempts.length + 1;
        attempts = [...attempts, { attempt: n, errors, ok: errors.length === 0 }];
        if (errors.length === 0) {
          log(`Attempt ${n}: renders clean.`);
          finalPath = path;
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

<div class="author">
  <div class="fields">
    <label>Section<input bind:value={section} disabled={running} spellcheck="false" /></label>
    <div class="pair">
      <label>Family<input bind:value={family} disabled={running} spellcheck="false" /></label>
      <label>Slug<input bind:value={slug} disabled={running} spellcheck="false" /></label>
    </div>
    <p class="target" title={target}>{target || 'Set the MOM root first.'}</p>
    <button class="go" onclick={run} disabled={!canRun}>
      {running ? 'Writing…' : 'Write question'}
    </button>
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
  input:disabled { opacity: .55; }
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

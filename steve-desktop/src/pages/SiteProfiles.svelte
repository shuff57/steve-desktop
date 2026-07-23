<script lang="ts">
  import { onMount } from 'svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { SITE_PROFILES_DIR } from '../lib/constants';
  import { domainToPath } from '../lib/utils/index';
  import { listProfiles, loadProfile, loadMappingDoc, loadSiteMap, healMappingDoc, getMappingDocPath } from '../lib/site-profiles';
  import { buildCliVerifyPrompt, parseCliVerifyOutput } from '../lib/cli-crawl';
  import { cliModelArg, extractCliText, summarizeCliLine, engineForProvider } from '../lib/agent-cli';
  import { renderSkillPreview } from '../lib/skill-parser';
  import { summarizeVerifyReport } from '../lib/verify-summary';
  import { listProviderConfigs } from '../lib/db';
  import { createEmbeddedBrowser, hideWebview, destroyWebview, injectScript, listenBrowserPageLoaded } from '../lib/browser';
  import { tabMarker, markerScript } from '../lib/tab-control';

  interface DomainGroup {
    domain: string;
    pages: number;
    hasDoc: boolean;
  }

  let groups = $state<DomainGroup[]>([]);
  let loading = $state(true);
  let err = $state('');
  let busy = $state('');
  let pendingDelete = $state('');

  // Update (re-map → diff → heal) state.
  let updating = $state(''); // domain currently being re-mapped
  let updateStep = $state('');
  let updateProgress = $state<string[]>([]);
  // Run meta shown in the progress modal: elapsed wall clock + the agent's context usage,
  // read from the CLI's own stream-json usage blocks. Both answer "is it still alive?".
  let updateStartedAt = $state(0);
  let nowMs = $state(0);
  let ctxTokens = $state(0);
  let tick: ReturnType<typeof setInterval> | undefined;
  const elapsed = $derived(() => {
    const s = Math.max(0, Math.floor((nowMs - updateStartedAt) / 1000));
    return `${Math.floor(s / 60)}:${String(s % 60).padStart(2, '0')}`;
  });
  const ctxLabel = $derived(ctxTokens >= 1000 ? `${(ctxTokens / 1000).toFixed(0)}k` : String(ctxTokens));
  let result = $state<{ domain: string; report: string; healedDoc: string; changed: boolean } | null>(null);
  let applying = $state(false);
  // Modal dismissal: a full-screen backdrop must always be closable, or it traps every click behind
  // it. Closing the RESULT modal clears it; closing the PROGRESS modal just hides it — the run keeps
  // going in the background and the result modal re-appears when it finishes.
  let modalDismissed = $state(false);

  function closeModal() {
    if (result) result = null;
    else modalDismissed = true;
  }

  async function refresh() {
    loading = true;
    err = '';
    try {
      const profiles = await listProfiles();
      const byDomain = new Map<string, number>();
      for (const p of profiles) byDomain.set(p.domain, (byDomain.get(p.domain) ?? 0) + 1);
      const out: DomainGroup[] = [];
      for (const [domain, pages] of byDomain) {
        const doc = await loadMappingDoc(domain).catch(() => null);
        out.push({ domain, pages, hasDoc: !!doc });
      }
      out.sort((a, b) => a.domain.localeCompare(b.domain));
      groups = out;
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      loading = false;
    }
  }

  async function remove(domain: string) {
    busy = domain;
    pendingDelete = '';
    try {
      const dir = `${SITE_PROFILES_DIR}/${domainToPath(domain)}`;
      const files = await invoke<string[]>('list_files', { path: dir, recursive: true });
      for (const f of files) await invoke('delete_file', { path: f }).catch(() => {});
      await refresh();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      busy = '';
    }
  }

  /** Re-map a stored profile: re-read its pages on a hidden background tab, have the agent verify its
   *  own mapping doc, then offer to apply the corrected doc. Read-only until you press Apply. */
  async function updateProfile(domain: string) {
    if (updating) return;
    err = '';
    result = null;
    updateProgress = [];
    updating = domain;
    modalDismissed = false;
    updateStep = 'Preparing…';
    updateStartedAt = Date.now();
    nowMs = Date.now();
    ctxTokens = 0;
    tick = setInterval(() => (nowMs = Date.now()), 1000);
    let tabId = '';
    let unlisten: (() => void) | undefined;
    let unlistenLoad: (() => void) | undefined;
    try {
      // On Windows the only working spawn engine is claude (opencode fails, os error 193); it
      // self-auths via the machine Claude Code login — same as the panel's real runs. Pick a claude
      // model from a configured claude provider if present, else the default.
      const engine = 'claude' as const;
      const configs = await listProviderConfigs().catch(() => []);
      const model = configs.find((c) => engineForProvider(c.id) === 'claude')?.model ?? 'claude-opus-4-8';

      const doc = await loadMappingDoc(domain);
      if (!doc) throw new Error('No mapping doc for this site to verify.');

      // Pages to re-check: prefer the stored site map, fall back to the page profiles.
      const map = await loadSiteMap(domain).catch(() => null);
      let pages = (map?.pages ?? []).map((p) => ({ name: p.pageName || p.url, url: p.url })).filter((p) => p.url);
      if (!pages.length) {
        const infos = (await listProfiles()).filter((p) => p.domain === domain);
        const loaded = await Promise.all(
          infos.map(async (i) => {
            const pr = await loadProfile(i.domain, i.pageName);
            return pr?.url ? { name: pr.pageName || pr.url, url: pr.url } : null;
          }),
        );
        pages = loaded.filter((p): p is { name: string; url: string } => !!p);
      }
      if (!pages.length) throw new Error('No mapped pages to re-check.');
      const startUrl = pages[0].url;

      const port = await invoke<number | null>('get_cdp_port');
      if (!port) throw new Error('CDP debug port unavailable — restart the app.');

      // Hidden transient tab — verify is read-only (navigate + read), so it needn't be watched.
      updateStep = 'Opening a background tab…';
      tabId = globalThis.crypto.randomUUID();
      // Re-stamp window.name on EVERY page load — a single injection is wiped by any navigation
      // (e.g. a sign-in redirect), which is what made the agent unable to find the marked tab.
      unlistenLoad = await listenBrowserPageLoaded(({ tabId: tid }) => {
        if (tid === tabId) injectScript(markerScript(tabId), tabId).catch(() => {});
      });
      await createEmbeddedBrowser(tabId, startUrl, true); // offscreen — never flashes over the UI
      await new Promise((r) => setTimeout(r, 2500)); // let it register + load as a CDP target
      await injectScript(markerScript(tabId), tabId).catch(() => {});
      await hideWebview(tabId).catch(() => {}); // belt + braces: fully hidden once registered

      updateStep = `Re-mapping ${pages.length} page(s) with ${engine}…`;
      const sessionId = globalThis.crypto.randomUUID();
      unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
        if (ev.payload.sessionId !== sessionId) return;
        const s = summarizeCliLine(ev.payload.line);
        if (s && updateProgress[updateProgress.length - 1] !== s) updateProgress = [...updateProgress, s].slice(-30);
        // Context meter: the CLI's stream-json assistant/result events carry per-turn usage.
        try {
          const u = (JSON.parse(ev.payload.line) as { message?: { usage?: Record<string, number> }; usage?: Record<string, number> });
          const usage = u.message?.usage ?? u.usage;
          if (usage) {
            const n = (usage.input_tokens ?? 0) + (usage.cache_read_input_tokens ?? 0) + (usage.cache_creation_input_tokens ?? 0) + (usage.output_tokens ?? 0);
            if (n > 0) ctxTokens = n;
          }
        } catch { /* not JSON — ignore */ }
      });

      // Goal prompt: the doc stays on disk and the agent reads it there — the prompt is a
      // fixed-size template (<4000 chars) instead of embedding an unbounded document.
      const docPath = await invoke<string>('resolve_path', { path: getMappingDocPath(domain) });
      const prompt = buildCliVerifyPrompt({ cdpPort: port, startUrl, docPath, marker: tabMarker(tabId) });
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true,
        timeoutSecs: 900,
        stream: true,
      });

      const { report, healedDoc } = parseCliVerifyOutput(extractCliText(engine, stdout));
      if (!report) throw new Error('The agent returned an empty verification report.');
      const changed = !!healedDoc && healedDoc.trim() !== doc.trim();
      result = { domain, report, healedDoc: healedDoc ?? '', changed };
      modalDismissed = false; // surface the result even if the progress modal was dismissed
      updateStep = changed ? 'Re-map done — review the changes, then Apply.' : 'Re-map done — no changes needed.';
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      unlisten?.();
      unlistenLoad?.();
      if (tabId) await destroyWebview(tabId).catch(() => {});
      if (tick) { clearInterval(tick); tick = undefined; }
      updating = '';
    }
  }

  async function applyUpdate() {
    if (!result?.changed) return;
    applying = true;
    try {
      await healMappingDoc(result.domain, result.healedDoc); // keeps _sitemap-ai.prev.md → reversible
      result = null;
      await refresh();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      applying = false;
    }
  }

  onMount(refresh);
</script>

<svelte:window onclick={() => (pendingDelete = '')} onkeydown={(e) => { if (e.key === 'Escape') closeModal(); }} />

<div class="profiles">
  <header>
    <div>
      <h1>Site Profiles</h1>
      <p class="sub">Maps the agent has learned for each site. Update re-checks the site and heals the map; Delete removes it from disk.</p>
    </div>
    <button class="refresh" onclick={refresh} disabled={loading || !!updating}>↻ Refresh</button>
  </header>

  {#if err}<p class="err">{err}</p>{/if}

  {#if loading}
    <p class="empty">Loading…</p>
  {:else if groups.length === 0}
    <p class="empty">No site profiles yet. Map a site from the Browse → Discovery panel and it shows up here.</p>
  {:else}
    <ul class="list">
      {#each groups as g (g.domain)}
        <li class="card">
          <div class="info">
            <span class="domain">{g.domain}</span>
            <span class="meta">{g.pages} {g.pages === 1 ? 'page' : 'pages'}{g.hasDoc ? ' · mapping doc' : ' · no doc'}</span>
          </div>
          <div class="actions">
            <button class="upd" disabled={!!updating || !g.hasDoc} title={g.hasDoc ? 'Re-check this site and heal the map' : 'No mapping doc to verify'} onclick={(e) => { e.stopPropagation(); updateProfile(g.domain); }}>
              {updating === g.domain ? 'Updating…' : 'Update'}
            </button>
            {#if pendingDelete === g.domain}
              <button class="confirm" disabled={busy === g.domain} onclick={(e) => { e.stopPropagation(); remove(g.domain); }}>
                {busy === g.domain ? 'Deleting…' : 'Confirm delete'}
              </button>
            {:else}
              <button class="del" disabled={!!updating} onclick={(e) => { e.stopPropagation(); pendingDelete = g.domain; }}>Delete</button>
            {/if}
          </div>
        </li>
      {/each}
    </ul>
  {/if}
</div>

{#if updating && !modalDismissed}
  <!-- svelte-ignore a11y_click_events_have_key_events -->
  <div class="backdrop" role="presentation" onclick={closeModal}>
    <!-- svelte-ignore a11y_click_events_have_key_events -->
    <div class="panel" role="dialog" aria-modal="true" tabindex="-1" onclick={(e) => e.stopPropagation()}>
      <h2>Updating {updating}</h2>
      <p class="step">{updateStep}</p>
      <p class="runmeta"><span>⏱ {elapsed()}</span>{#if ctxTokens}<span>context used ~{ctxLabel} tokens</span>{/if}</p>
      {#if updateProgress.length}
        <ul class="prog">
          {#each updateProgress.slice(-12) as line}<li>{line}</li>{/each}
        </ul>
      {/if}
      <div class="panel-actions">
        <p class="note">Read-only — runs in the background. Click away or press Esc to hide.</p>
        <button onclick={closeModal}>Hide</button>
      </div>
    </div>
  </div>
{:else if result && !modalDismissed}
  {@const s = summarizeVerifyReport(result.report)}
  <!-- svelte-ignore a11y_click_events_have_key_events -->
  <div class="backdrop" role="presentation" onclick={closeModal}>
    <!-- svelte-ignore a11y_click_events_have_key_events -->
    <div class="panel wide" role="dialog" aria-modal="true" tabindex="-1" onclick={(e) => e.stopPropagation()}>
      <h2>{result.domain} — verification</h2>
      {#if s.confirmed.length || s.discrepancies.length}
        <!-- Structured review: problems first, the long confirmed list collapsed. -->
        <div class="vsum">
          <span class="chip ok">✓ {s.confirmed.length} confirmed</span>
          <span class="chip {s.discrepancies.length ? 'warn' : 'ok'}">{s.discrepancies.length ? `⚠ ${s.discrepancies.length} discrepanc${s.discrepancies.length === 1 ? 'y' : 'ies'}` : 'no discrepancies'}</span>
        </div>
        {#if s.verdict}<p class="verdict">{s.verdict}</p>{/if}
        <div class="report">
          {#if s.discrepancies.length}
            <ul class="dlist">
              {#each s.discrepancies as d}<li>⚠ {d}</li>{/each}
            </ul>
          {/if}
          <details>
            <summary>{s.confirmed.length} confirmed page{s.confirmed.length === 1 ? '' : 's'}</summary>
            <ul class="clist">
              {#each s.confirmed as c}<li>✓ {c}</li>{/each}
            </ul>
          </details>
          <details>
            <summary>Full report</summary>
            <!-- Sanitized (renderSkillPreview = marked + sanitizeHtml) — model output over
                 untrusted pages never lands in {@html} unsanitized. -->
            <div class="md">
              {#await renderSkillPreview(result.report) then html}{@html html}{:catch}<pre class="raw">{result.report}</pre>{/await}
            </div>
          </details>
        </div>
      {:else}
        <!-- Report didn't match the expected shape — fall back to the rendered markdown. -->
        <div class="report md">
          {#await renderSkillPreview(result.report)}
            <pre class="raw">{result.report}</pre>
          {:then html}
            {@html html}
          {:catch}
            <pre class="raw">{result.report}</pre>
          {/await}
        </div>
      {/if}
      <div class="panel-actions">
        {#if result.changed}
          <span class="changed">The mapping doc will be updated (prior kept as a .prev backup).</span>
          <button class="confirm" disabled={applying} onclick={applyUpdate}>{applying ? 'Applying…' : 'Apply update'}</button>
        {:else}
          <span class="unchanged">No changes needed — the map still holds up.</span>
        {/if}
        <button onclick={() => (result = null)}>Close</button>
      </div>
    </div>
  </div>
{/if}

<style>
  .profiles { padding: 24px; overflow-y: auto; height: 100%; box-sizing: border-box; }
  header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
  h1 { margin: 0 0 4px; font-size: 22px; }
  .sub { margin: 0; opacity: .7; font-size: 13px; }
  .err { color: #b91c1c; font-size: 13px; }
  .empty { opacity: .6; margin-top: 40px; text-align: center; }
  .refresh { padding: 6px 12px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  .refresh:disabled { opacity: .5; cursor: default; }
  .list { list-style: none; margin: 20px 0 0; padding: 0; display: flex; flex-direction: column; gap: 8px; }
  .card { display: flex; justify-content: space-between; align-items: center; gap: 16px; border: 1px solid rgba(128,128,128,.25); border-radius: 8px; padding: 12px 16px; }
  .info { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
  .domain { font-weight: 600; font-size: 14px; }
  .meta { font-size: 11px; opacity: .6; }
  .actions { display: flex; gap: 8px; flex-shrink: 0; }
  .actions button { padding: 6px 12px; font-size: 12px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  .actions button:disabled { opacity: .45; cursor: default; }
  .upd { color: #059669; border-color: rgba(5,150,105,.4); }
  .del { color: #b91c1c; }
  .confirm { color: #fff; background: #b91c1c; border-color: #b91c1c; }

  .backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 32px; box-sizing: border-box; }
  .panel { background: #1a1a1a; color: #eee; border-radius: 10px; padding: 20px 22px; max-width: 480px; width: 100%; box-shadow: 0 10px 40px rgba(0,0,0,.5); }
  .panel.wide { max-width: 760px; max-height: 82vh; display: flex; flex-direction: column; }
  .panel h2 { margin: 0 0 10px; font-size: 16px; }
  .step { margin: 0 0 10px; font-size: 13px; opacity: .85; }
  .prog { list-style: none; margin: 0 0 10px; padding: 10px 12px; background: rgba(255,255,255,.05); border-radius: 6px; font-size: 12px; max-height: 220px; overflow-y: auto; }
  .prog li { opacity: .8; padding: 1px 0; }
  .note { margin: 0; font-size: 11px; opacity: .55; }
  .report { flex: 1; overflow: auto; word-break: break-word; font-size: 13px; line-height: 1.55; background: rgba(255,255,255,.05); border-radius: 6px; padding: 12px 14px; margin: 0 0 12px; }
  .report .raw { white-space: pre-wrap; margin: 0; font-size: 12px; }
  .vsum { display: flex; gap: 8px; margin: 0 0 6px; }
  .chip { font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 999px; }
  .chip.ok { background: rgba(34,197,94,.15); color: #22c55e; }
  .chip.warn { background: rgba(217,119,6,.18); color: #d97706; }
  .verdict { font-size: 13px; margin: 0 0 8px; opacity: .85; }
  .runmeta { display: flex; justify-content: space-between; gap: 12px; font-size: 12px; opacity: .7; margin: 0 0 4px; font-variant-numeric: tabular-nums; }
  .dlist { list-style: none; margin: 0 0 10px; padding: 0; display: flex; flex-direction: column; gap: 6px; }
  .dlist li { background: rgba(217,119,6,.12); border-left: 3px solid #d97706; border-radius: 4px; padding: 6px 10px; font-size: 12.5px; line-height: 1.45; }
  .clist { list-style: none; margin: 6px 0 0; padding: 0; display: flex; flex-direction: column; gap: 3px; }
  .clist li { font-size: 12px; opacity: .8; padding: 2px 4px; }
  .report details { margin: 6px 0; }
  .report summary { cursor: pointer; font-size: 12.5px; opacity: .8; user-select: none; }
  .report summary:hover { opacity: 1; }
  .md :global(h1) { font-size: 16px; margin: 0 0 8px; }
  .md :global(h2) { font-size: 14px; margin: 14px 0 6px; }
  .md :global(ul) { margin: 4px 0; padding-left: 20px; }
  .md :global(li) { margin: 2px 0; }
  .md :global(p) { margin: 6px 0; }
  .md :global(code) { background: rgba(128,128,128,.2); border-radius: 4px; padding: 0 4px; font-size: 12px; }
  .panel-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .panel-actions button { padding: 7px 14px; font-size: 13px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(255,255,255,.2); background: transparent; color: #eee; }
  .panel-actions button:disabled { opacity: .5; cursor: default; }
  .changed { font-size: 12px; color: #fbbf24; }
  .unchanged { font-size: 12px; color: #34d399; }
</style>

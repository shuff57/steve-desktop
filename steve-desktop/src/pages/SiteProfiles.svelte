<script lang="ts">
  import { onMount } from 'svelte';
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { SITE_PROFILES_DIR } from '../lib/constants';
  import { domainToPath } from '../lib/utils/index';
  import { listProfiles, loadProfile, loadMappingDoc, loadSiteMap, healMappingDoc } from '../lib/site-profiles';
  import { buildCliVerifyPrompt, parseCliVerifyOutput } from '../lib/cli-crawl';
  import { cliModelArg, extractCliText, summarizeCliLine, engineForProvider } from '../lib/agent-cli';
  import { listProviderConfigs, getClaudeApiKey } from '../lib/db';
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
      await createEmbeddedBrowser(tabId, startUrl);
      await new Promise((r) => setTimeout(r, 2500)); // let it register + load as a CDP target
      await injectScript(markerScript(tabId), tabId).catch(() => {});
      await hideWebview(tabId).catch(() => {}); // hide only AFTER it has registered/loaded

      updateStep = `Re-mapping ${pages.length} page(s) with ${engine}…`;
      const sessionId = globalThis.crypto.randomUUID();
      unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
        if (ev.payload.sessionId !== sessionId) return;
        const s = summarizeCliLine(ev.payload.line);
        if (s && updateProgress[updateProgress.length - 1] !== s) updateProgress = [...updateProgress, s].slice(-30);
      });

      const prompt = buildCliVerifyPrompt({ cdpPort: port, startUrl, doc, pages, marker: tabMarker(tabId) });
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        apiKey: await getClaudeApiKey(),
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
  <!-- svelte-ignore a11y_click_events_have_key_events -->
  <div class="backdrop" role="presentation" onclick={closeModal}>
    <!-- svelte-ignore a11y_click_events_have_key_events -->
    <div class="panel wide" role="dialog" aria-modal="true" tabindex="-1" onclick={(e) => e.stopPropagation()}>
      <h2>{result.domain} — verification</h2>
      <pre class="report">{result.report}</pre>
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
  .report { flex: 1; overflow: auto; white-space: pre-wrap; word-break: break-word; font-size: 12px; line-height: 1.5; background: rgba(255,255,255,.05); border-radius: 6px; padding: 12px 14px; margin: 0 0 12px; }
  .panel-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .panel-actions button { padding: 7px 14px; font-size: 13px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(255,255,255,.2); background: transparent; color: #eee; }
  .panel-actions button:disabled { opacity: .5; cursor: default; }
  .changed { font-size: 12px; color: #fbbf24; }
  .unchanged { font-size: 12px; color: #34d399; }
</style>

// Module-singleton state + logic for the Site Profiles "Update" run. It lives OUTSIDE the page
// component so navigating away doesn't kill the view of an in-flight run: the CLI keeps going,
// state keeps updating, and the report is waiting when you come back. Cleanup (unlisten +
// destroy the transient tab) runs in this module's finally regardless of what page is mounted.

import { invoke } from '@tauri-apps/api/core';
import { listen } from '@tauri-apps/api/event';
import { listProfiles, loadProfile, loadMappingDoc, loadSiteMap, getMappingDocPath, getDirtyPages, filterToDirty, clearDirtyPages, isNavigableUrl } from './site-profiles';
import { buildCliVerifyPrompt, parseCliVerifyOutput } from './cli-crawl';
import { cliModelArg, extractCliText, summarizeCliLine, engineForProvider } from './agent-cli';
import { listProviderConfigs } from './db';
import { createEmbeddedBrowser, hideWebview, destroyWebview, injectScript, listenBrowserPageLoaded } from './browser';
import { tabMarker, markerScript } from './tab-control';

export interface UpdateResult {
  domain: string;
  report: string;
  healedDoc: string;
  changed: boolean;
}

export const updateRun = $state({
  domain: '' as string, // non-empty while a run is in flight
  step: '',
  progress: [] as string[],
  err: '',
  result: null as UpdateResult | null,
  dismissed: false,
  startedAt: 0,
  now: 0,
  ctxTokens: 0,
  ctxMax: 0, // model context window, so the meter reads "used / max" like the Agent panel
  skippedRedacted: 0, // pages verify cannot re-check because their URL carries a redaction token
});

let tick: ReturnType<typeof setInterval> | undefined;

/** Re-map a stored profile: re-read its pages on a hidden background tab, have the agent verify
 *  its own mapping doc, then offer to apply the corrected doc. Read-only until Apply. */
export async function startUpdate(domain: string): Promise<void> {
  if (updateRun.domain) return;
  updateRun.err = '';
  updateRun.result = null;
  updateRun.progress = [];
  updateRun.domain = domain;
  updateRun.dismissed = false;
  updateRun.step = 'Preparing…';
  updateRun.startedAt = Date.now();
  updateRun.now = Date.now();
  updateRun.ctxTokens = 0;
  updateRun.skippedRedacted = 0;
  tick = setInterval(() => (updateRun.now = Date.now()), 1000);
  let tabId = '';
  let unlisten: (() => void) | undefined;
  let unlistenLoad: (() => void) | undefined;
  try {
    // On Windows the only working spawn engine is claude (opencode fails, os error 193); it
    // self-auths via the machine Claude Code login — same as the panel's real runs. Pick a claude
    // model from a configured claude provider if present, else the default.
    const engine = 'claude' as const;
    const configs = await listProviderConfigs().catch(() => []);
    const model = configs.find((c) => engineForProvider(c.id) === 'claude')?.model ?? 'claude-opus-5';
    // Opus 5 / Opus 4.6+ / Sonnet / Fable carry a 1M window; haiku and pre-4.6 opus are 200k.
    // Powers the "used / max" meter — same windows the Agent panel uses.
    // ponytail: coarse id match, refine if a non-1M claude model is ever configured here.
    updateRun.ctxMax = /opus-(4-[678]|5)|sonnet|fable/.test(model) ? 1_000_000 : 200_000;

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
    // Never hand verify a tokenized URL — it cannot load, so it would be condemned as dead every
    // run. Report the count instead of dropping them silently.
    const navigable = pages.filter((p) => isNavigableUrl(p.url));
    updateRun.skippedRedacted = pages.length - navigable.length;
    pages = navigable;
    if (!pages.length) throw new Error('No mapped pages to re-check.');

    // Heals mark pages dirty; when any exist, re-map ONLY those — never the full site.
    // >6 dirty pages = broad drift → full verify (also keeps the goal prompt under budget).
    const dirty = await getDirtyPages(domain).catch(() => []);
    const targeted = filterToDirty(pages, dirty);
    const isTargeted = dirty.length > 0 && targeted.length < pages.length && targeted.length <= 6;
    if (isTargeted) pages = targeted;
    const startUrl = pages[0].url;

    const port = await invoke<number | null>('get_cdp_port');
    if (!port) throw new Error('CDP debug port unavailable — restart the app.');

    // Hidden transient tab — verify is read-only (navigate + read), so it needn't be watched.
    updateRun.step = 'Opening a background tab…';
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

    const skipNote = updateRun.skippedRedacted ? ` (${updateRun.skippedRedacted} redacted page(s) skipped)` : '';
    updateRun.step = `Re-mapping ${pages.length} ${isTargeted ? 'dirty ' : ''}page(s) with ${engine}…${skipNote}`;
    const sessionId = globalThis.crypto.randomUUID();
    unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
      if (ev.payload.sessionId !== sessionId) return;
      const s = summarizeCliLine(ev.payload.line);
      if (s && updateRun.progress[updateRun.progress.length - 1] !== s) {
        updateRun.progress = [...updateRun.progress, s].slice(-30);
      }
      // Context meter: the CLI's stream-json assistant/result events carry per-turn usage.
      try {
        const u = JSON.parse(ev.payload.line) as { message?: { usage?: Record<string, number> }; usage?: Record<string, number> };
        const usage = u.message?.usage ?? u.usage;
        if (usage) {
          const n = (usage.input_tokens ?? 0) + (usage.cache_read_input_tokens ?? 0) + (usage.cache_creation_input_tokens ?? 0) + (usage.output_tokens ?? 0);
          if (n > 0) updateRun.ctxTokens = n;
        }
      } catch {
        /* not JSON — ignore */
      }
    });

    // Goal prompt: the doc stays on disk and the agent reads it there — the prompt is a
    // fixed-size template (<4000 chars) instead of embedding an unbounded document.
    const docPath = await invoke<string>('resolve_path', { path: getMappingDocPath(domain) });
    const prompt = buildCliVerifyPrompt({
      cdpPort: port,
      startUrl,
      docPath,
      marker: tabMarker(tabId),
      onlyUrls: isTargeted ? pages.map((p) => p.url) : undefined,
    });
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
    updateRun.result = { domain, report, healedDoc: healedDoc ?? '', changed };
    // Clear the dirty backlog ONLY when there's nothing to apply — verify confirmed the doc is
    // already accurate. When there IS a healed doc, the drift is still live until the user clicks
    // Apply, so the page stays dirty until then (cleared in applyUpdate).
    if (!changed) await clearDirtyPages(domain).catch(() => {});
    updateRun.dismissed = false; // surface the result even if the progress modal was dismissed
    updateRun.step = changed ? 'Re-map done — review the changes, then Apply.' : 'Re-map done — no changes needed.';
  } catch (e) {
    updateRun.err = e instanceof Error ? e.message : String(e);
  } finally {
    // Return everything to normal no matter which page is mounted: stop listeners, close the
    // transient tab (the agent has already navigated it back to its start URL per the prompt).
    unlisten?.();
    unlistenLoad?.();
    if (tabId) await destroyWebview(tabId).catch(() => {});
    if (tick) {
      clearInterval(tick);
      tick = undefined;
    }
    updateRun.domain = '';
  }
}

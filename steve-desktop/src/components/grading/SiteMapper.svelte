<script lang="ts">
  /**
   * SiteMapper — captures the live embedded page (merged DOM+AX tree over CDP) into a redacted
   * SiteProfile and shows what was captured plus the redacted snapshot, so you can eyeball that
   * no raw page text / PII would reach the model. DOM-only by design: nothing here is sent
   * anywhere — the model path stays the redacted tree from replay-live.
   */
  import { onMount, onDestroy } from 'svelte';
  import { cdp } from '../../lib/cdp-client';
  import { connectCDP, isConnected, evalScript } from '../../lib/cdp-actions';
  import { captureMergedTree, mergedToProfile, summarizeMerged, type CaptureStats } from '../../lib/merged-tree';
  import { redactTree } from '../../lib/redact-tree';
  import {
    saveProfile, saveSiteMap, loadSiteMap, deleteSiteMap, loadProfile, deleteProfile, getProfilePath,
  } from '../../lib/site-profiles';
  import { profileToNode, upsertPage, emptySiteMap, isCrawlableLink, normalizeUrl, suggestTrim, structuralSignature, urlTemplate, type SiteMap, type SitePageNode, type TrimSuggestion } from '../../lib/site-map';
  import { getActiveTabId, getEmbeddedUrl, navigateEmbedded, listenBrowserPageLoaded } from '../../lib/browser';
  import { domainFromUrl } from '../../lib/utils/index';
  import type { SiteProfile } from '../../lib/types/site-profile';

  let { pageUrl = '' } = $props<{ pageUrl?: string }>();

  // Reopening the panel restores the saved site map for whatever domain you're on.
  onMount(async () => {
    const domain = domainFromUrl(pageUrl);
    if (domain) siteMap = await loadSiteMap(domain);
  });

  // ponytail: no page cap — crawl the whole reachable site, then suggest pages to trim. SAFETY is
  // only a backstop against a pathological infinite frontier; Stop is the real control.
  const SAFETY_MAX = 1000;

  let mapping = $state(false);
  let message = $state<string | null>(null);
  let stats = $state<CaptureStats | null>(null);
  let interactive = $state<{ kind: string; label: string; selector: string }[]>([]);
  let redactedSize = $state(0);
  let redactedTokens = $state(0);
  let redactedSample = $state('');
  let savedPath = $state<string | null>(null);

  // Site-map state — built by auto-crawling: click through links, lazy-load each page, map it.
  let siteMap = $state<SiteMap | null>(null);
  let crawling = $state(false);
  let siteMsg = $state<string | null>(null);
  let stopRequested = false;
  let trimSuggestions = $state<TrimSuggestion[]>([]);
  /** Pages that threw during capture — surfaced so a skip is diagnosable, not silent. */
  let failures = $state<{ url: string; error: string }[]>([]);
  /** URL families collapsed after their shape repeated — never a silent truncation. */
  let collapsed = $state<{ template: string; skipped: number }[]>([]);

  // How many pages of one URL family to map before trusting it's a template. Two, not one:
  // a single sample can't tell a template from a coincidence.
  const SAMPLES_PER_TEMPLATE = 2;

  function flattenInteractive(p: SiteProfile) {
    const rows: { kind: string; label: string; selector: string }[] = [];
    for (const b of p.interactive.buttons) rows.push({ kind: 'button', label: b.text || '(no label)', selector: b.selector });
    for (const l of p.interactive.links) rows.push({ kind: 'link', label: l.text || '(no label)', selector: l.selector });
    for (const i of p.interactive.inputs) rows.push({ kind: 'input', label: i.label || '(no label)', selector: i.selector });
    return rows.slice(0, 20);
  }

  /** Capture the current page over CDP → profile (+ refresh the single-page display).
   * Returns the redactor too, so the profile can be redacted before it's persisted. */
  async function captureCurrent(url: string): Promise<{ profile: SiteProfile; redact: (t: string) => string }> {
    if (!isConnected() && !(await connectCDP())) {
      throw new Error('Could not connect to the browser — open a page in the browser first.');
    }
    const { snapshot, merged } = await captureMergedTree(cdp);
    const profile = mergedToProfile(merged, url);
    const red = redactTree(snapshot);
    stats = summarizeMerged(merged);
    interactive = flattenInteractive(profile);
    redactedSize = red.redactedText.length;
    redactedTokens = Object.keys(red.map).length;
    redactedSample = red.redactedText.slice(0, 1200);
    return { profile, redact: red.redact };
  }

  /** Tokenize any PII in the profile BEFORE it's written to disk. mergedToProfile keeps
   * raw labels/selectors (e.g. a roster name in role=…[name="Doe, Jane"]); this swaps the
   * value dictionary over the whole JSON so the saved artifact holds ⟦D…⟧, never names. */
  function redactProfile(p: SiteProfile, redact: (t: string) => string): SiteProfile {
    return JSON.parse(redact(JSON.stringify(p)));
  }

  async function map() {
    if (mapping) return;
    mapping = true;
    message = 'Capturing page…';
    stats = null;
    savedPath = null;
    try {
      const { profile, redact } = await captureCurrent(pageUrl);
      savedPath = await saveProfile(redactProfile(profile, redact));
      message = `Mapped ${profile.domain} · saved profile`;
    } catch (e) {
      message = `Map failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      mapping = false;
    }
  }

  const delay = (ms: number) => new Promise<void>((r) => setTimeout(r, ms));

  /**
   * Arm a page-load waiter BEFORE navigating, then await the returned function after.
   * Registering the listener up front closes a real race: listenBrowserPageLoaded is
   * itself async, so a fast/cached page could fire page-loaded before we were listening,
   * and every such page then ate the full timeout instead of resolving on load.
   */
  async function armPageLoad(timeoutMs = 12000): Promise<() => Promise<void>> {
    let fired = false;
    let onFire: (() => void) | null = null;
    const off = await listenBrowserPageLoaded(() => { fired = true; onFire?.(); });
    return () =>
      new Promise<void>((resolve) => {
        let done = false;
        const finish = () => {
          if (done) return;
          done = true;
          off();
          clearTimeout(timer);
          clearInterval(poll);
          resolve();
        };
        const timer = setTimeout(finish, timeoutMs);
        const poll = setInterval(() => { if (stopRequested) finish(); }, 150); // Stop interrupts
        if (fired) return finish(); // already loaded while we were setting up
        onFire = finish;
      });
  }

  /**
   * Wait until the DOM stops growing, instead of guessing with a fixed delay. Adaptive:
   * a static page settles in ~300ms, a JS-heavy one gets up to maxMs. Never throws —
   * eval failures mid-navigation just end the wait.
   */
  async function waitForDomStable(maxMs = 2500, quietMs = 350): Promise<void> {
    const started = Date.now();
    let last = -1;
    let stableAt = 0;
    while (Date.now() - started < maxMs) {
      if (stopRequested) return;
      // cdp-actions' evalScript resolves to an ActionResult ({success, data}) — NOT a raw
      // value. Reading it as a string yields "[object Object]" → NaN → 0, which never looks
      // stable and silently burns the whole budget on every page.
      const res = await evalScript('document.getElementsByTagName("*").length').catch(() => null);
      const count = Number(res?.data) || 0;
      if (count === last && count > 0) {
        if (!stableAt) stableAt = Date.now();
        else if (Date.now() - stableAt >= quietMs) return;
      } else {
        last = count;
        stableAt = 0;
      }
      await delay(120);
    }
  }

  /**
   * Lazy-loading settle: scroll to trigger lazy content, wait for the DOM to stop changing,
   * return to top. Assumes the page-load wait already happened (see armPageLoad).
   * Deliberately never throws — a settle failure must not abort the crawl, and `document.body`
   * is null on PDF/frameset pages.
   */
  async function settle(): Promise<void> {
    if (stopRequested) return;
    const scroll = (y: string) =>
      evalScript(`window.scrollTo(0, ${y})`).catch(() => undefined);
    await scroll('(document.body && document.body.scrollHeight) || 0');
    await waitForDomStable();
    if (stopRequested) return;
    await scroll('0');
    await delay(150);
  }

  /** Capture the current page, save its profile, and fold it into the per-domain site map. */
  async function mapHere(url: string, visited: Set<string>): Promise<SiteProfile> {
    const { profile, redact } = await captureCurrent(url);
    visited.add(url);
    const domain = profile.domain;
    // Redact before persisting: both the per-page profile and the site-map node are
    // built from the safe profile, so neither holds raw PII.
    const safe = redactProfile(profile, redact);
    await saveProfile(safe); // per-page JSON — feeds replay/skills and the review panel
    let map = siteMap && siteMap.domain === domain ? siteMap : (await loadSiteMap(domain)) ?? emptySiteMap(domain, new Date().toISOString());
    map = upsertPage(map, profileToNode(safe));
    siteMap = map;
    await saveSiteMap(map);
    // Return the RAW profile: the crawler reads its links to navigate the frontier,
    // and a tokenized href would break that. Only persisted artifacts are redacted.
    return profile;
  }

  // Auto-crawl, breadth-first: map the start page, queue its links, then navigate to each in turn,
  // mapping and queueing as we go. Breadth-first reaches the start page's links (e.g. each class)
  // before tunnelling deep into any one branch. Guardrailed: same-origin only, skips
  // logout/submit/destructive links and same-page anchors, dedupes by normalized URL. No page cap.
  async function crawl() {
    if (crawling) return;
    crawling = true;
    stopRequested = false;
    trimSuggestions = [];
    failures = [];
    collapsed = [];
    siteMsg = 'Crawling…';
    const visited = new Set<string>();
    const tabId = getActiveTabId();

    try {
      const start = normalizeUrl(pageUrl);
      const queue: string[] = [start];
      const queued = new Set<string>([start]);
      let first = true;

      // Template collapsing: a roster of 200 students is 200 pages of ONE template. Once
      // we've mapped SAMPLES_PER_TEMPLATE pages of a URL family AND they came back
      // structurally identical, stop enqueueing more of that family — the shape is known
      // and every further visit only differs by data. Their links are the same shape too,
      // so the first samples already queued whatever they reach: coverage is preserved.
      const tplSigs = new Map<string, Set<string>>(); // template -> distinct shapes seen
      const tplMapped = new Map<string, number>(); // template -> pages actually mapped
      const isSaturated = (t: string) =>
        (tplMapped.get(t) ?? 0) >= SAMPLES_PER_TEMPLATE && (tplSigs.get(t)?.size ?? 0) === 1;

      while (queue.length && !stopRequested && visited.size < SAFETY_MAX) {
        const target = queue.shift()!;

        // One bad page must not kill the whole crawl — navigation, settling AND capture
        // are all inside the guard. The page is marked visited so it isn't retried forever,
        // and the error is recorded against its URL so a skip is diagnosable.
        let profile: SiteProfile;
        let landed = target;
        try {
          if (!first) {
            // Arm the load listener BEFORE navigating so a fast load can't be missed.
            const loaded = await armPageLoad();
            await navigateEmbedded(tabId, target);
            await loaded();
            await settle();
            if (stopRequested) break;
          }
          first = false;

          landed = normalizeUrl(await getEmbeddedUrl(tabId).catch(() => target));
          if (visited.has(landed)) continue;
          profile = await mapHere(landed, visited);
        } catch (e) {
          visited.add(landed);
          failures.push({ url: landed, error: e instanceof Error ? e.message : String(e) });
          siteMsg = `Crawling — ${siteMap?.pages.length ?? 0} mapped · ${failures.length} skipped · ${queue.length} queued…`;
          continue;
        }
        // Record this page's shape against its URL family, so a repeating template is
        // recognised after SAMPLES_PER_TEMPLATE consistent samples.
        const landedTpl = urlTemplate(landed);
        tplMapped.set(landedTpl, (tplMapped.get(landedTpl) ?? 0) + 1);
        const sigs = tplSigs.get(landedTpl) ?? new Set<string>();
        sigs.add(structuralSignature(profile));
        tplSigs.set(landedTpl, sigs);

        siteMsg = `Crawling — ${siteMap?.pages.length ?? 0} mapped · ${queue.length} queued…`
          + (collapsed.length ? ` · ${collapsed.reduce((n, c) => n + c.skipped, 0)} repeats skipped` : '');

        for (const link of profile.interactive.links) {
          if (!link.href) continue;
          let abs: string;
          try { abs = normalizeUrl(new URL(link.href, landed).toString()); } catch { continue; }
          // Skip same-page anchors (popovers), already-seen pages, and unsafe links.
          if (abs === landed || queued.has(abs) || !isCrawlableLink(abs, landed)) continue;

          // Already know this family's shape? Don't spend a page visit re-learning it.
          const tpl = urlTemplate(abs);
          if (isSaturated(tpl)) {
            queued.add(abs); // mark handled so we don't re-evaluate it from another page
            const hit = collapsed.find((c) => c.template === tpl);
            if (hit) hit.skipped += 1;
            else collapsed.push({ template: tpl, skipped: 1 });
            continue;
          }

          queued.add(abs);
          queue.push(abs);
        }
      }

      await navigateEmbedded(tabId, start).catch(() => {}); // return you to where you started
      if (siteMap) trimSuggestions = suggestTrim(siteMap);
      const n = siteMap?.pages.length ?? 0;
      const repeats = collapsed.reduce((sum, c) => sum + c.skipped, 0);
      siteMsg = (stopRequested ? `Stopped — ${n} pages mapped.` : `Crawl done — ${n} pages mapped.`)
        + (repeats ? ` ${repeats} repeat page(s) skipped across ${collapsed.length} template(s).` : '')
        + (failures.length ? ` ${failures.length} page(s) skipped — see below.` : '')
        + (trimSuggestions.length ? ` ${trimSuggestions.length} suggested to trim below.` : '');
    } catch (e) {
      siteMsg = `Crawl failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      crawling = false;
    }
  }

  function stopCrawl() {
    stopRequested = true;
  }

  // If the panel is collapsed or you switch tabs, this component unmounts — halt any running
  // crawl so it can't keep navigating your browser headlessly with no UI/Stop button.
  onDestroy(() => { stopRequested = true; });

  async function trimOne(s: TrimSuggestion) {
    const page = siteMap?.pages.find((p) => p.url === s.url);
    if (page) await clearPage(page);
    trimSuggestions = trimSuggestions.filter((t) => t.url !== s.url);
  }

  async function trimAll() {
    for (const s of [...trimSuggestions]) {
      const page = siteMap?.pages.find((p) => p.url === s.url);
      if (page) await clearPage(page);
    }
    trimSuggestions = [];
  }

  // Review a saved page: load its profile JSON and show its interactive elements. (The redacted
  // snapshot isn't persisted — the saved SiteProfile is the durable, model-facing artifact.)
  async function reviewPage(p: SitePageNode) {
    if (!siteMap) return;
    try {
      const profile = await loadProfile(siteMap.domain, p.pageName);
      if (!profile) { siteMsg = `Could not load saved page: ${p.pageName}`; return; }
      stats = null;
      redactedSample = '';
      interactive = flattenInteractive(profile);
      savedPath = getProfilePath(siteMap.domain, p.pageName);
      message = `Reviewing saved page “${p.pageName}” — ${p.url}`;
    } catch (e) {
      siteMsg = `Review failed: ${e instanceof Error ? e.message : String(e)}`;
    }
  }

  // Clear one mapped page: delete its profile JSON and drop it from the site map.
  async function clearPage(p: SitePageNode) {
    if (!siteMap) return;
    try {
      await deleteProfile(siteMap.domain, p.pageName);
    } catch {
      /* file may already be gone */
    }
    const pages = siteMap.pages.filter((x) => x.url !== p.url);
    if (pages.length === 0) {
      await deleteSiteMap(siteMap.domain);
      siteMap = null;
      siteMsg = 'Cleared — no pages left.';
    } else {
      siteMap = { ...siteMap, pages };
      await saveSiteMap(siteMap);
      siteMsg = `Cleared ${p.pageName} — ${pages.length} pages left.`;
    }
  }

  // Clear the whole site map for this domain (every page profile + the map file).
  async function clearAll() {
    if (!siteMap) return;
    const domain = siteMap.domain;
    for (const p of siteMap.pages) {
      try { await deleteProfile(domain, p.pageName); } catch { /* ignore */ }
    }
    await deleteSiteMap(domain);
    siteMap = null;
    siteMsg = `Cleared all mapped pages for ${domain}.`;
  }
</script>

<div class="mapper">
  <div class="head">
    <span class="hdr">Map this page</span>
    <button class="map" disabled={mapping} onclick={map} title="Capture the current page over CDP">
      {mapping ? '⏳ Mapping…' : '🔍 Map this page'}
    </button>
  </div>
  <p class="muted">Open a page in the browser first, then map it. Capture is DOM-only — no screenshots or raw text leave this app.</p>

  {#if message}<div class="msg">{message}</div>{/if}

  <div class="section site">
    <div class="head">
      <span class="hdr">Map this site</span>
      <div class="site-btns">
        <button class="map" disabled={crawling} onclick={crawl}
          title="Visit the site's links breadth-first, lazy-load each page, and map them">
          {crawling ? '⏳ Crawling…' : '🕸 Map this site'}
        </button>
        {#if crawling}
          <button class="map stop" onclick={stopCrawl} title="Stop after the current page">⏹ Stop</button>
        {/if}
      </div>
    </div>
    <p class="muted">Crawls breadth-first from the current page (so the start page's links — e.g. each class — map first), lazy-loads each, and maps it. Same-origin only; skips logout/submit/destructive links and same-page popovers. No page limit — it suggests pages to trim when done. Drives your browser tab; use Stop to halt.</p>
    {#if siteMsg}<div class="msg">{siteMsg}</div>{/if}

    {#if collapsed.length}
      <div class="head">
        <span class="hdr">Repeating templates collapsed ({collapsed.length})</span>
      </div>
      <ul class="list">
        {#each collapsed as c (c.template)}
          <li class="row site-row">
            <span class="label" title={c.template}>{c.template} — <span class="kind">{c.skipped} repeat page(s) skipped, shape already mapped</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if failures.length}
      <div class="head">
        <span class="hdr">Skipped — capture failed ({failures.length})</span>
      </div>
      <ul class="list">
        {#each failures as f (f.url)}
          <li class="row site-row">
            <span class="label" title={f.url}>{f.url} — <span class="kind">{f.error}</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if trimSuggestions.length}
      <div class="head">
        <span class="hdr">Suggested to trim ({trimSuggestions.length})</span>
        <button class="link-btn" onclick={trimAll} title="Remove all suggested pages">Trim all</button>
      </div>
      <ul class="list">
        {#each trimSuggestions as s (s.url)}
          <li class="row site-row">
            <span class="label" title={s.url}>{s.pageName} — <span class="kind">{s.reason}</span></span>
            <button class="x" onclick={() => trimOne(s)} title="Trim this page">✕</button>
          </li>
        {/each}
      </ul>
    {/if}
    {#if siteMap && siteMap.pages.length}
      <div class="head">
        <span class="hdr">{siteMap.domain} — {siteMap.pages.length} pages</span>
        <button class="link-btn" onclick={clearAll} title="Delete every mapped page for this site">Clear all</button>
      </div>
      <ul class="list">
        {#each siteMap.pages as p}
          <li class="row site-row">
            <button class="rowmain" onclick={() => reviewPage(p)} title="Review this saved page">
              <span class="label" title={p.url}>{p.pageName}</span>
              <span class="kind">{p.links.length} links · {p.counts.buttons} btn · {p.counts.inputs} in</span>
            </button>
            <button class="x" onclick={() => clearPage(p)} title="Clear this page">✕</button>
          </li>
        {/each}
      </ul>
      <p class="muted">Click a page to review its captured elements; ✕ clears it.</p>
    {/if}
  </div>

  {#if stats}
    <div class="stats">
      <div class="stat"><span class="num">{stats.frames}</span><span class="lbl">frames</span></div>
      <div class="stat"><span class="num">{stats.domNodes}</span><span class="lbl">DOM nodes</span></div>
      <div class="stat"><span class="num">{stats.rolePct}%</span><span class="lbl">with AX role</span></div>
      <div class="stat"><span class="num">{stats.roleNamePct}%</span><span class="lbl">interactive w/ role-name</span></div>
    </div>
  {/if}

  {#if interactive.length}
    <div class="section">
      <span class="hdr">Interactive elements{#if stats} ({stats.interactive} total, first {interactive.length}){:else} ({interactive.length} shown){/if}</span>
      <ul class="list">
        {#each interactive as el}
          <li class="row">
            <span class="kind">{el.kind}</span>
            <span class="label" title={el.label}>{el.label}</span>
            <code class="sel" title={el.selector}>{el.selector}</code>
          </li>
        {/each}
      </ul>
    </div>
  {/if}

  {#if redactedSample}
    <div class="section">
      <span class="hdr">Redacted snapshot — {redactedSize.toLocaleString()} chars · {redactedTokens} data tokens</span>
      <p class="muted">This is exactly what the model would see. It should hold only chrome (controls, labels, headings) and ⟦D…⟧ tokens — no student names or IDs.</p>
      <pre class="sample">{redactedSample}{redactedSize > redactedSample.length ? '\n…' : ''}</pre>
    </div>
  {/if}

  {#if savedPath}<div class="msg">Profile JSON → <code>{savedPath}</code></div>{/if}
</div>

<style>
  .mapper { display: flex; flex-direction: column; gap: var(--spacing-3); }
  .head { display: flex; align-items: center; justify-content: space-between; gap: var(--spacing-2); }
  .hdr { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); font-weight: 600; }
  .map { background: transparent; border: 1px solid var(--color-primary); color: var(--color-primary); cursor: pointer; font-size: 0.85rem; padding: 6px 12px; border-radius: var(--radius-md); flex-shrink: 0; }
  .map:hover:not(:disabled) { background: var(--color-primary-bg); }
  .map:disabled { opacity: 0.5; cursor: not-allowed; }
  .map.stop { border-color: var(--color-danger); color: var(--color-danger); }
  .map.stop:hover:not(:disabled) { background: var(--color-danger-bg); }
  .site { padding-top: var(--spacing-2); border-top: 1px solid var(--border-color); }
  .site-btns { display: flex; gap: var(--spacing-2); flex-shrink: 0; }
  .site-row { display: flex; align-items: stretch; justify-content: space-between; gap: var(--spacing-2); padding: 0; }
  .rowmain { flex: 1; min-width: 0; display: flex; align-items: baseline; justify-content: space-between; gap: var(--spacing-2); background: transparent; border: none; color: inherit; cursor: pointer; text-align: left; padding: 4px var(--spacing-2); border-radius: var(--radius-sm); }
  .rowmain:hover { background: var(--bg-hover); }
  .x { background: transparent; border: none; color: var(--text-tertiary); cursor: pointer; padding: 0 8px; border-radius: var(--radius-sm); flex-shrink: 0; }
  .x:hover { background: var(--color-danger-bg); color: var(--color-danger); }
  .link-btn { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.75rem; text-decoration: underline; padding: 0; }
  .link-btn:hover { color: var(--color-danger); }
  .muted { color: var(--text-secondary); font-size: 0.8rem; margin: 0; line-height: 1.4; }
  .msg { font-size: 0.8rem; color: var(--text-secondary); padding: var(--spacing-2); background: var(--bg-card); border-radius: var(--radius-md); word-break: break-all; }
  .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-2); }
  .stat { display: flex; flex-direction: column; gap: 2px; padding: var(--spacing-2); background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); }
  .num { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); }
  .lbl { font-size: 0.7rem; color: var(--text-tertiary); }
  .section { display: flex; flex-direction: column; gap: var(--spacing-2); }
  .list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; max-height: 240px; overflow-y: auto; }
  .row { display: grid; grid-template-columns: auto 1fr; align-items: baseline; gap: var(--spacing-2); padding: 4px var(--spacing-2); background: var(--bg-card); border-radius: var(--radius-sm); }
  .kind { font-size: 0.65rem; text-transform: uppercase; color: var(--text-tertiary); }
  .label { font-size: 0.8rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .sel { grid-column: 1 / -1; font-size: 0.7rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .sample { font-size: 0.7rem; color: var(--text-secondary); background: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: var(--spacing-2); max-height: 220px; overflow: auto; white-space: pre-wrap; word-break: break-all; margin: 0; }
</style>

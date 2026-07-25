<script lang="ts">
  /**
   * SiteMapper — captures the live embedded page (merged DOM+AX tree over CDP) into a redacted
   * SiteProfile and shows what was captured plus the redacted snapshot, so you can eyeball that
   * no raw page text / PII would reach the model. DOM-only by design: nothing here is sent
   * anywhere — the model path stays the redacted tree from replay-live.
   */
  import { onMount, onDestroy } from 'svelte';
  import { cdp } from '../../lib/cdp-client';
  import { connectCDP, evalScript, deepCapture } from '../../lib/cdp-actions';
  import { captureMergedTree, mergedToProfile, summarizeMerged, type CaptureStats } from '../../lib/merged-tree';
  import { redactTree, redactProfileForStorage } from '../../lib/redact-tree';
  import {
    saveProfile, saveSiteMap, loadSiteMap, deleteSiteMap, loadProfile, deleteProfile, getProfilePath,
    saveMappingDoc, saveVerifyReport, healMappingDoc,
  } from '../../lib/site-profiles';
  import { profileToNode, upsertPage, emptySiteMap, isCrawlableLink, normalizeUrl, structuralSignature, urlTemplate, scopeOf, withinScope, isTemplateSaturated, nextFrontierIndex, findSuspectPages, SAMPLES_PER_TEMPLATE, MAX_SAMPLES_PER_TEMPLATE, type SiteMap, type SitePageNode } from '../../lib/site-map';
  import { fetchPageCard, PageCardCache, type PageCard } from '../../lib/page-card';
  import { buildCliCrawlPrompt, parseCliCrawlOutput, buildCliVerifyPrompt, parseCliVerifyOutput, CLI_CRAWL_MAX_PAGES } from '../../lib/cli-crawl';
  import { deriveKeyNodes, verifyKeyNodes } from '../../lib/key-nodes';
  import { tabMarker } from '../../lib/tab-control';
  import { showAgentConnected, hideAgentConnected } from '../../lib/agent-overlay';
  import { createThrottledBuffer } from '../../lib/throttle-buffer';
  import { engineForProvider, cliModelArg, extractCliText, summarizeCliLine } from '../../lib/agent-cli';
  import { invoke } from '@tauri-apps/api/core';
  import { listen } from '@tauri-apps/api/event';
  import { planFrontier, fetchTemplateTiebreak, type FrontierCandidate } from '../../lib/crawl-planner';
  import { fetchMapSynthesis, type MapSynthesis } from '../../lib/map-synthesis';
  import { sidecarTransport } from '../../lib/replay-live';
  import type { ModelTransport } from '../../lib/model-gate';
  import { gradePage, selectorsToProbe, summarize, pagesToPrune, type PageVerdict, type VerifySummary, type PruneDecision } from '../../lib/crawl-verify';
  import { selectorToCountExpr } from '../../lib/selector-resolve';
  import { getActiveTabId, getEmbeddedUrl, navigateEmbedded, listenBrowserPageLoaded } from '../../lib/browser';
  import { domainFromUrl } from '../../lib/utils/index';
  import type { SiteProfile } from '../../lib/types/site-profile';

  let { pageUrl = '', provider = '', model = '' } = $props<{ pageUrl?: string; provider?: string; model?: string }>();

  // Reopening the panel restores the saved site map for whatever domain you're on.
  // Set when the grading panel handed off a crawl to us: on completion we derive the
  // grading profile for the start page and switch back. See the crawl() finally below.
  const CRAWL_REQUEST = 'steve:crawl-for-grading';
  const DERIVE_ON_RETURN = 'steve:derive-after-crawl';
  let startedForGrading = false;

  onMount(async () => {
    const domain = domainFromUrl(pageUrl);
    if (domain) siteMap = await loadSiteMap(domain);

    // The grading panel asked us to map this site, then switched here. Pick it up and start.
    if (sessionStorage.getItem(CRAWL_REQUEST) === pageUrl) {
      sessionStorage.removeItem(CRAWL_REQUEST);
      startedForGrading = true;
      void crawl();
    }
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
  /** Pages that threw during capture — surfaced so a skip is diagnosable, not silent. */
  let failures = $state<{ url: string; error: string }[]>([]);
  /** URL families collapsed after their shape repeated — never a silent truncation. */
  let collapsed = $state<{ template: string; skipped: number }[]>([]);
  /** The scope detected on the start page, shown so you know what you're confined to. */
  let activeScope = $state<{ key: string; value: string } | null>(null);
  /** Links dropped for belonging to another course. */
  let outOfScope = $state(0);

  // AI-guided crawl: the model prioritizes the frontier, digests each new template into a
  // PageCard, breaks template ties, and names the finished map. Every call is gated
  // (callModelTree leak check) and every failure falls back to the deterministic path.
  let aiGuided = $state(false);
  /** Frontier links the model chose not to visit — a decision, never a silent gap. */
  let aiSkips = $state<{ url: string; reason: string }[]>([]);
  let synthesis = $state<MapSynthesis | null>(null);
  /** Pages the post-crawl self-audit flagged as suspect (referenced elsewhere, captured empty) and re-checked. */
  let selfAudited = $state<{ url: string; pageName: string }[]>([]);

  // Claude-driven crawl: the model picks every hop and writes the mapping doc itself.
  let aiDriving = $state(false);
  let aiDoc = $state<string | null>(null);
  let aiDocPath = $state<string | null>(null);
  /** Live progress lines streamed from the spawned agent while it crawls. */
  let aiProgress = $state<string[]>([]);
  /** Second-pass: the agent's own verification report of its mapping doc. */
  let aiVerifying = $state(false);
  let aiVerifyReport = $state<string | null>(null);
  /** Page list from the last agent crawl, reused by the agent-verify pass. */
  let aiPages = $state<{ name: string; url: string }[]>([]);

  // Post-crawl verification: re-visit each mapped page and check the agent could actually act
  // on it. Read-only — resolves selectors and compares shape, never clicks. Clicking to prove
  // reachability would be a write against a live course.
  let verifying = $state(false);
  let verdicts = $state<PageVerdict[]>([]);
  let verifySummary = $state<VerifySummary | null>(null);
  let verifyMsg = $state<string | null>(null);

  function flattenInteractive(p: SiteProfile) {
    const rows: { kind: string; label: string; selector: string }[] = [];
    for (const b of p.interactive.buttons) rows.push({ kind: 'button', label: b.text || '(no label)', selector: b.selector });
    for (const l of p.interactive.links) rows.push({ kind: 'link', label: l.text || '(no label)', selector: l.selector });
    for (const i of p.interactive.inputs) rows.push({ kind: 'input', label: i.label || '(no label)', selector: i.selector });
    return rows.slice(0, 20);
  }

  /** Capture the current page over CDP → profile (+ refresh the single-page display).
   * Returns the redactor too, so the profile can be redacted before it's persisted. */
  async function captureCurrent(url: string): Promise<{ profile: SiteProfile; redact: (t: string) => string; secrets: Record<string, string> }> {
    if (!(await connectCDP())) { // re-targets to the ACTIVE tab if the client is on another
      throw new Error('Could not connect to the browser. Open a page in the browser first.');
    }
    // Scroll to trigger lazy-loaded content BEFORE reading the tree — an async index page
    // (Canvas assignments) measures 0 links on a plain snapshot and 30+ after this settles.
    // Covers every capture path: map(), crawl() via mapHere(), and verifyMap().
    await deepCapture();
    const { snapshot, merged } = await captureMergedTree(cdp);
    const profile = mergedToProfile(merged, url);
    // Record the page's durable anchors while we're already looking at it — verify later resolves
    // just these instead of re-capturing the whole page. Prove each one against the page it came
    // from before storing it: an anchor that cannot re-find its own element would report drift on
    // every future run, which is worse than having no key nodes at all (verify then falls back to
    // a real re-map). One extra probe, only at capture time.
    const derived = deriveKeyNodes(snapshot);
    const selfCheck = await probeSelectors(derived.map((k) => k.selector));
    profile.keyNodes = derived.filter((k) => selfCheck[k.selector] === 1);
    const red = redactTree(snapshot);
    stats = summarizeMerged(merged);
    interactive = flattenInteractive(profile);
    redactedSize = red.redactedText.length;
    redactedTokens = Object.keys(red.map).length;
    redactedSample = red.redactedText.slice(0, 1200);
    // secrets = token → original value; the AI calls use it as the gate's leak dictionary.
    return { profile, redact: red.redact, secrets: red.map };
  }

  /** Tokenize any PII in the profile BEFORE it's written to disk. mergedToProfile keeps
   * raw labels/selectors (e.g. a roster name in role=…[name="Doe, Jane"]); this swaps the
   * value dictionary over the whole JSON so the saved artifact holds ⟦D…⟧, never names. */
  // Keeps the hostname intact through redaction — see redactProfileForStorage.
  const redactProfile = (p: SiteProfile, redact: (t: string) => string): SiteProfile =>
    redactProfileForStorage(p, redact);

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
  async function mapHere(url: string, visited: Set<string>): Promise<{ profile: SiteProfile; safe: SiteProfile; secrets: Record<string, string> }> {
    const { profile, redact, secrets } = await captureCurrent(url);
    visited.add(url);
    const domain = profile.domain;
    // Redact before persisting: both the per-page profile and the site-map node are
    // built from the safe profile, so neither holds raw PII.
    const safe = redactProfile(profile, redact);
    // Map entry FIRST, then the per-page JSON. The reverse order orphans a profile file if the
    // map write fails: nothing references it, so "Clear all" (which iterates the map) can never
    // delete it. This way a failure leaves a mapped page whose JSON is missing instead — visible
    // in the panel, and every reader already handles a null profile.
    let map = siteMap && siteMap.domain === domain ? siteMap : (await loadSiteMap(domain)) ?? emptySiteMap(domain, new Date().toISOString());
    map = upsertPage(map, profileToNode(safe));
    siteMap = map;
    await saveSiteMap(map);
    await saveProfile(safe); // per-page JSON — feeds replay/skills and the review panel
    // Return the RAW profile: the crawler reads its links to navigate the frontier,
    // and a tokenized href would break that. Only persisted artifacts are redacted.
    // `safe` + `secrets` feed the AI layer: safe is what the model may see, secrets is
    // the gate's leak dictionary.
    return { profile, safe, secrets };
  }

  /** Short context for the frontier planner: what's mapped, so "already represented" is decidable. */
  function mapSummaryFor(cards: PageCardCache): string {
    const pages = siteMap?.pages ?? [];
    const recent = pages.slice(-15).map((p) => {
      const card = cards.get(p.url);
      return `- ${p.pageName}${card ? ` [${card.pageType}]` : ''}`;
    });
    return pages.length ? `${pages.length} pages mapped, most recent:\n${recent.join('\n')}` : '';
  }

  // Auto-crawl, breadth-first: map the start page, queue its links, then navigate to each in turn,
  // mapping and queueing as we go. Breadth-first reaches the start page's links (e.g. each class)
  // before tunnelling deep into any one branch. Guardrailed: same-origin only, skips
  // logout/submit/destructive links and same-page anchors, dedupes by normalized URL. No page cap.
  async function crawl() {
    if (crawling) return;
    crawling = true;
    stopRequested = false;
    failures = [];
    collapsed = [];
    outOfScope = 0;
    aiSkips = [];
    synthesis = null;
    selfAudited = [];
    activeScope = scopeOf(pageUrl);
    siteMsg = 'Crawling…';
    const visited = new Set<string>();
    const tabId = getActiveTabId();

    // AI layer — null when off, and every helper degrades to the deterministic path on
    // its own (fallback plan / null card / null verdict), so the crawl never depends on it.
    const ai: ModelTransport | null = aiGuided && provider ? sidecarTransport({ provider, model }) : null;
    const cardCache = new PageCardCache();
    /** template → model tie-break verdict ("same template despite differing signatures"). */
    const tiebreak = new Map<string, boolean>();
    /** token → value across ALL captured pages: the leak dictionary only ever grows. */
    const allSecrets: Record<string, string> = {};
    const crawlGoal =
      'Map this course site for a teacher-automation agent: gradebook, assignments, rosters, ' +
      'settings. Skip redundant instances of repeating rows/lists already represented.';

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
      // AI tie-break: a family whose signatures never converge (per-row input labels) still
      // saturates once the model says the shapes are one template — after the same minimum
      // sample count the deterministic rule uses.
      const isSaturated = (t: string) =>
        isTemplateSaturated(tplMapped.get(t) ?? 0, tplSigs.get(t)?.size ?? 0) ||
        (tiebreak.get(t) === true && (tplMapped.get(t) ?? 0) >= SAMPLES_PER_TEMPLATE);

      /** Note a page we chose not to visit, so a collapsed family is always visible in the UI. */
      const noteCollapsed = (tpl: string) => {
        const hit = collapsed.find((c) => c.template === tpl);
        if (hit) hit.skipped += 1;
        else collapsed.push({ template: tpl, skipped: 1 });
      };

      while (queue.length && !stopRequested && visited.size < SAFETY_MAX) {
        // Template-diversity pick: a link from a template not yet sampled jumps the queue
        // ahead of FIFO order, so one template's fan-out (a roster, a question bank) can't
        // drain the whole page budget before a different part of the site is ever reached.
        const target = queue.splice(nextFrontierIndex(queue, tplMapped), 1)[0];

        // Re-check saturation HERE, not just at enqueue. A listing page fans out its whole
        // family in one burst — one gradebook lists 12 students, one course page lists 26
        // assessments — so every link is queued before any of them has been mapped and
        // tplMapped is still 0. The enqueue-time check cannot bound that; by the time the
        // count is meaningful the queue is already full. Checking again on dequeue, when the
        // counts are current, is what actually caps the family.
        const queuedTpl = urlTemplate(target);
        if (isSaturated(queuedTpl)) {
          noteCollapsed(queuedTpl);
          continue;
        }

        // One bad page must not kill the whole crawl — navigation, settling AND capture
        // are all inside the guard. The page is marked visited so it isn't retried forever,
        // and the error is recorded against its URL so a skip is diagnosable.
        let profile: SiteProfile;
        let safe: SiteProfile;
        let secrets: Record<string, string>;
        let landed = target;
        try {
          if (!first) {
            // Belt and suspenders: the queue only ever holds gate-passed links, but the
            // navigator re-checks anyway — no code path may navigate an unsafe URL.
            if (!isCrawlableLink(target, start)) continue;
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
          ({ profile, safe, secrets } = await mapHere(landed, visited));
        } catch (e) {
          visited.add(landed);
          // Count the attempt against its template even though it failed. Otherwise a family
          // whose pages all error never accrues samples, never saturates, and every one of its
          // links is tried — one run spent 35 navigations on chrome-error://chromewebdata that
          // way. A page that cannot be captured teaches us as much about the family as one that
          // can: stop after the ceiling either way.
          const failedTpl = urlTemplate(landed);
          tplMapped.set(failedTpl, (tplMapped.get(failedTpl) ?? 0) + 1);
          failures.push({ url: landed, error: e instanceof Error ? e.message : String(e) });
          siteMsg = `Crawling: ${siteMap?.pages.length ?? 0} mapped · ${failures.length} skipped · ${queue.length} queued…`;
          continue;
        }
        // Record this page's shape against its URL family, so a repeating template is
        // recognised after SAMPLES_PER_TEMPLATE consistent samples.
        const landedTpl = urlTemplate(landed);
        tplMapped.set(landedTpl, (tplMapped.get(landedTpl) ?? 0) + 1);
        const sigs = tplSigs.get(landedTpl) ?? new Set<string>();
        sigs.add(structuralSignature(profile));
        tplSigs.set(landedTpl, sigs);

        if (ai) {
          Object.assign(allSecrets, secrets);
          // One card per template family — a null (gate refusal, bad reply) just means no card.
          if (!cardCache.has(landed)) {
            const card = await fetchPageCard(safe, allSecrets, ai);
            if (card) {
              cardCache.set(landed, card);
              if (siteMap) {
                siteMap = { ...siteMap, pages: siteMap.pages.map((p) => (p.url === landed ? { ...p, card } : p)) };
                await saveSiteMap(siteMap);
              }
            }
          }
          // Signatures disagree within the family → ask once whether it's one template anyway.
          if (sigs.size > 1 && !tiebreak.has(landedTpl)) {
            const two = [...sigs];
            const verdict = await fetchTemplateTiebreak(two[0], two[1], allSecrets, ai);
            if (verdict !== null) tiebreak.set(landedTpl, verdict);
          }
        }

        siteMsg = `Crawling: ${siteMap?.pages.length ?? 0} mapped · ${queue.length} queued…`
          + (collapsed.length ? ` · ${collapsed.reduce((n, c) => n + c.skipped, 0)} repeats skipped` : '');

        // Deterministic filters first (gate, scope, saturation) — the AI planner only ever
        // sees links that already passed every one of them.
        const fresh: FrontierCandidate[] = [];
        for (const link of profile.interactive.links) {
          if (!link.href) continue;
          let abs: string;
          try { abs = normalizeUrl(new URL(link.href, landed).toString()); } catch { continue; }
          // Skip same-page anchors (popovers), already-seen pages, and unsafe links.
          if (abs === landed || queued.has(abs) || fresh.some((c) => c.url === abs) || !isCrawlableLink(abs, landed)) continue;

          // Stay in the course you started in — always. Without this the crawl reaches your
          // home page and follows it into every other course on the account.
          if (!withinScope(abs, start)) {
            queued.add(abs);
            outOfScope += 1;
            continue;
          }

          // Already know this family's shape? Don't spend a page visit re-learning it.
          const tpl = urlTemplate(abs);
          if (isSaturated(tpl)) {
            queued.add(abs); // mark handled so we don't re-evaluate it from another page
            noteCollapsed(tpl);
            continue;
          }

          fresh.push({ url: abs, label: link.text || '(no label)' });
        }

        if (ai && fresh.length > 1 && !stopRequested) {
          // The model reorders/skips within the safe set; it cannot add (indices only),
          // and any failure returns the fallback plan = exactly the deterministic order.
          const plan = await planFrontier(fresh, { goal: crawlGoal, mapSummary: mapSummaryFor(cardCache), secrets: allSecrets, transport: ai });
          for (const s of plan.skip) {
            queued.add(s.url); // a deliberate skip is handled — not re-evaluated from another page
            aiSkips = [...aiSkips, s];
          }
          for (const u of plan.follow) {
            queued.add(u);
            queue.push(u);
          }
        } else {
          for (const c of fresh) {
            queued.add(c.url);
            queue.push(c.url);
          }
        }
      }

      await navigateEmbedded(tabId, start).catch(() => {}); // return you to where you started

      // Synthesis first (it can only add), then the deterministic trim as the floor.
      if (ai && siteMap && !stopRequested) {
        siteMsg = 'Crawl done, synthesizing site map…';
        const cards: Record<string, PageCard | undefined> = {};
        for (const p of siteMap.pages) cards[p.url] = p.card ?? cardCache.get(p.url);
        synthesis = await fetchMapSynthesis(siteMap, cards, allSecrets, ai);
      }

      // AI self-audit: cross-reference the finished link graph. A page other pages point to
      // (e.g. listed in a module) but which itself captured with nothing on it is more likely
      // a load-timing miss than a genuinely blank page — worth one re-capture, not a full
      // second crawl. Deterministic graph check; gated on the same AI toggle as the rest of
      // this layer so it costs nothing when off.
      if (ai && siteMap && !stopRequested) {
        const suspects = findSuspectPages(siteMap);
        if (suspects.length) {
          siteMsg = `Self-audit: re-checking ${suspects.length} page(s) referenced elsewhere but captured empty…`;
          for (const s of suspects) {
            try {
              const loaded = await armPageLoad();
              await navigateEmbedded(tabId, s.url);
              await loaded();
              await settle();
              await mapHere(s.url, visited);
            } catch (e) {
              failures.push({ url: s.url, error: e instanceof Error ? e.message : String(e) });
            }
          }
          await navigateEmbedded(tabId, start).catch(() => {});
          selfAudited = suspects.map((s) => ({ url: s.url, pageName: s.pageName }));
        }
      }
      const n = siteMap?.pages.length ?? 0;
      const repeats = collapsed.reduce((sum, c) => sum + c.skipped, 0);
      siteMsg = (stopRequested ? `Stopped: ${n} pages mapped.` : `Crawl done: ${n} pages mapped.`)
        + (outOfScope ? ` ${outOfScope} link(s) outside ${activeScope?.key}=${activeScope?.value} not followed.` : '')
        + (repeats ? ` ${repeats} repeat page(s) skipped across ${collapsed.length} template(s).` : '')
        + (aiSkips.length ? ` ${aiSkips.length} link(s) skipped by AI — see below.` : '')
        + (selfAudited.length ? ` ${selfAudited.length} suspect page(s) re-checked by AI self-audit.` : '')
        + (failures.length ? ` ${failures.length} page(s) skipped — see below.` : '');
    } catch (e) {
      siteMsg = `Crawl failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      crawling = false;
      // Grading kicked this off: the crawl has returned to the start page, so hand back —
      // flag the page for derivation and switch the drawer to the grading tab, where onMount
      // learns the selectors. Even a stopped/failed crawl returns here so grading isn't stranded.
      if (startedForGrading) {
        startedForGrading = false;
        sessionStorage.setItem(DERIVE_ON_RETURN, normalizeUrl(pageUrl));
        window.dispatchEvent(new CustomEvent('steve:action-panel', { detail: { mode: 'ogre' } }));
      }
    }
  }

  function stopCrawl() {
    stopRequested = true;
  }

  // ── Spawned-agent crawl — TARGETED tool, opt-in, NOT the default mapper ────────────────
  // A single agent holding the whole site in one context blew up to 1.39M tokens on a real
  // crawl, and a validated head-to-head test found it adds ~0 extra discovery over
  // deterministic deep-capture (captureCurrent's deepCapture pass) for ~176k tokens spent on
  // 4 pages — deep-capture found the same links in ~4s. `crawl()` + deep-capture is the
  // default full-site path now (see the "Map this site" button); reach for this only for a
  // specific page deep-capture can't get into (content behind a click-to-expand/accordion
  // gate that scrolling alone won't trigger), not for mapping a whole site.
  //
  // The chosen engine CLI is spawned full-shell and handed the app's CDP debug port: the
  // crawl runs INSIDE the agent against the already-logged-in webview, and its final
  // stdout IS the mapping document. Safety here is prompt-level (the deny regexes are
  // spelled out verbatim in the prompt) — the CLI has its own tools, so unlike the BFS
  // crawl there is no hard gate between the model and the browser. The stop button
  // cannot kill a spawned CLI; the Rust-side timeout is the backstop.
  async function aiDriveCrawl() {
    if (crawling || aiDriving) return;
    if (!provider) { siteMsg = 'Pick an engine above first.'; return; }
    aiDriving = true;
    aiDoc = null;
    aiDocPath = null;
    aiProgress = [];
    failures = [];
    activeScope = scopeOf(pageUrl);
    const tabId = getActiveTabId();
    const sessionId = crypto.randomUUID();
    let unlisten: (() => void) | undefined;
    let progressBuf: ReturnType<typeof createThrottledBuffer<string>> | undefined;
    try {
      const port = await invoke<number | null>('get_cdp_port');
      if (!port) throw new Error('CDP debug port unavailable. Restart the app.');
      const engine = engineForProvider(provider);

      // Subscribe BEFORE spawning so no early event is missed. Only this run's lines.
      // Batch updates (~150ms) so a burst of stream events doesn't re-render per line and starve
      // the main thread (the confirmed cause of the CDP wedge).
      progressBuf = createThrottledBuffer<string>((lines) => { aiProgress = [...aiProgress, ...lines].slice(-40); });
      unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
        if (ev.payload.sessionId !== sessionId) return;
        const summary = summarizeCliLine(ev.payload.line);
        if (summary) progressBuf!.push(summary);
      });

      const prompt = buildCliCrawlPrompt({
        cdpPort: port,
        startUrl: normalizeUrl(pageUrl),
        scope: activeScope, // stay-on-course lives in the prompt, not a UI opt-out

        marker: getActiveTabId() ? tabMarker(getActiveTabId()) : undefined,
      });
      siteMsg = `Spawned ${engine}${model ? ` (${model})` : ''}. It is driving the crawl over CDP. Up to ~15 min; watch progress below.`;
      await showAgentConnected(getActiveTabId()); // connection overlay (border + cursor) on the driven tab
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // full-shell: the agent needs Bash to speak CDP
        timeoutSecs: 900,
        stream: true, // NDJSON progress events → aiProgress
      }).finally(() => hideAgentConnected(getActiveTabId()));
      const { doc, pages } = parseCliCrawlOutput(extractCliText(engine, stdout));
      if (!doc) throw new Error(`${engine} returned an empty mapping`);
      aiDoc = doc;
      aiPages = pages;
      aiVerifyReport = null;
      const domain = domainFromUrl(pageUrl);
      if (domain) aiDocPath = await saveMappingDoc(domain, doc).catch(() => null);

      // Capture pass: re-visit each page the agent reported through the app's OWN pipeline,
      // so we get app-native profiles (buttons/inputs/selectors) + a real SiteMap. This is
      // what makes the deterministic "Verify map" button work against an agent-driven crawl.
      // Every URL is re-gated here — the agent's list is not trusted to be safe.
      if (pages.length) {
        const start = normalizeUrl(pageUrl);
        const visited = new Set<string>();
        for (const [i, p] of pages.entries()) {
          const u = normalizeUrl(p.url);
          if (visited.has(u) || !isCrawlableLink(u, start)) continue;
          siteMsg = `Capturing page ${i + 1}/${pages.length} for verification: ${p.name}…`;
          try {
            const loaded = await armPageLoad();
            await navigateEmbedded(activeTabIdOr(tabId), u);
            await loaded();
            await settle();
            await mapHere(u, visited); // writes profile JSON + folds into siteMap
          } catch (e) {
            failures.push({ url: u, error: e instanceof Error ? e.message : String(e) });
          }
        }
        await navigateEmbedded(activeTabIdOr(tabId), start).catch(() => {});
      }
      siteMsg = `Agent crawl done: ${aiProgress.length} step(s), ${siteMap?.pages.length ?? 0} page(s) captured for verification.`
        + (pages.length ? '' : ' (agent returned no page list — doc only, nothing to verify.)')
        + (failures.length ? ` ${failures.length} capture(s) failed.` : '');
    } catch (e) {
      siteMsg = `Agent crawl failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      progressBuf?.flush();
      unlisten?.();
      aiDriving = false;
    }
  }

  /** getActiveTabId can momentarily return '' during capture; fall back to the id we started with. */
  function activeTabIdOr(fallback: string): string {
    return getActiveTabId() || fallback;
  }

  // Agent-driven verification: spawn the CLI again with its own doc + the page list, have it
  // re-read the live pages and report whether the document holds up. Read-only, same rails as
  // the crawl; the streamed progress reuses the same live log.
  async function aiVerifyDoc() {
    if (aiDriving || aiVerifying || !aiDoc) return;
    if (!provider) { siteMsg = 'Pick an engine above first.'; return; }
    aiVerifying = true;
    aiVerifyReport = null;
    aiProgress = [];
    failures = [];
    const tabId = getActiveTabId();
    const sessionId = crypto.randomUUID();
    let unlisten: (() => void) | undefined;
    let progressBuf: ReturnType<typeof createThrottledBuffer<string>> | undefined;
    try {
      const port = await invoke<number | null>('get_cdp_port');
      if (!port) throw new Error('CDP debug port unavailable. Restart the app.');
      const engine = engineForProvider(provider);
      progressBuf = createThrottledBuffer<string>((lines) => { aiProgress = [...aiProgress, ...lines].slice(-40); });
      unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
        if (ev.payload.sessionId !== sessionId) return;
        const summary = summarizeCliLine(ev.payload.line);
        if (summary) progressBuf!.push(summary);
      });
      // Goal prompt: the agent reads the doc from disk — ensure it's saved, then hand it the
      // absolute path (the spawned CLI's cwd is the temp dir, not the project root).
      const vDomain = domainFromUrl(pageUrl);
      let relDocPath = aiDocPath;
      if (!relDocPath) {
        if (!vDomain) throw new Error('No domain to save the mapping doc under.');
        relDocPath = await saveMappingDoc(vDomain, aiDoc);
        aiDocPath = relDocPath;
      }
      const docPath = await invoke<string>('resolve_path', { path: relDocPath });
      const prompt = buildCliVerifyPrompt({ cdpPort: port, startUrl: normalizeUrl(pageUrl), docPath, marker: getActiveTabId() ? tabMarker(getActiveTabId()) : undefined });
      siteMsg = `Spawned ${engine} to verify its own mapping: re-reading ${aiPages.length} page(s) over CDP…`;
      await showAgentConnected(getActiveTabId()); // connection overlay on the driven tab
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
      }).finally(() => hideAgentConnected(getActiveTabId()));
      const { report, healedDoc, recapture } = parseCliVerifyOutput(extractCliText(engine, stdout));
      if (!report) throw new Error(`${engine} returned an empty verification report`);
      aiVerifyReport = report;
      const domain = domainFromUrl(pageUrl);
      if (domain) await saveVerifyReport(domain, report).catch(() => {});

      // Heal the doc: overwrite _sitemap-ai.md with the corrected version (prior kept as .prev.md).
      let healed = false;
      if (domain && healedDoc && healedDoc.trim() !== aiDoc.trim()) {
        aiDoc = healedDoc;
        aiDocPath = await healMappingDoc(domain, healedDoc).catch(() => aiDocPath);
        healed = true;
      }

      // Heal the profiles: re-capture the pages verify flagged, at their corrected URLs, through
      // the app's own pipeline. Every URL is re-gated — the agent's list is not trusted to be safe.
      let recaptured = 0;
      if (recapture.length) {
        const start = normalizeUrl(pageUrl);
        const visited = new Set<string>();
        for (const [i, p] of recapture.entries()) {
          const u = normalizeUrl(p.url);
          if (visited.has(u) || !isCrawlableLink(u, start)) continue;
          siteMsg = `Healing profile ${i + 1}/${recapture.length}: ${p.name}…`;
          try {
            const loaded = await armPageLoad();
            await navigateEmbedded(activeTabIdOr(tabId), u);
            await loaded();
            await settle();
            await mapHere(u, visited);
            recaptured += 1;
          } catch (e) {
            failures.push({ url: u, error: e instanceof Error ? e.message : String(e) });
          }
        }
        await navigateEmbedded(activeTabIdOr(tabId), start).catch(() => {});
      }

      siteMsg = `Agent verification done. Report below.`
        + (healed ? ' Doc healed (prior saved as _sitemap-ai.prev.md).' : ' Doc unchanged.')
        + (recaptured ? ` ${recaptured} profile(s) re-captured.` : '')
        + (failures.length ? ` ${failures.length} re-capture(s) failed.` : '');
    } catch (e) {
      siteMsg = `Agent verification failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      progressBuf?.flush();
      unlisten?.();
      aiVerifying = false;
    }
  }

  /**
   * How many elements each selector matches, resolved in ONE page-side pass. Doing this per
   * selector would be a CDP round trip per control — hundreds per page. An invalid selector
   * throws inside querySelectorAll and is recorded as -1, which grades as broken (correct: the
   * agent cannot use it either).
   */
  async function probeSelectors(selectors: string[]): Promise<Record<string, number>> {
    if (!selectors.length) return {};
    // Must go through selectorToCountExpr, NOT querySelectorAll: 69% of stored selectors are
    // role=button[name="…"] accessibility anchors, which are not valid CSS. Probing them as CSS
    // throws on every one — that is what made the first verify run report 10,991 "broken"
    // selectors, measuring the probe's own wrong assumption rather than the site.
    const body = selectors
      .map((s) => {
        const key = JSON.stringify(s);
        return `try{o[${key}]=${selectorToCountExpr(s)}}catch(e){o[${key}]=-1}`;
      })
      .join(';');
    const js = `(function(){var o={};${body};return JSON.stringify(o)})()`;
    try {
      // evalScript resolves to an ActionResult ({success, data}); the script returns a JSON string.
      const res = await evalScript(js);
      return JSON.parse(String(res.data ?? '{}')) as Record<string, number>;
    } catch {
      return {};
    }
  }

  /**
   * Walk every mapped page and verify the agent could act on it: navigate there, re-capture, and
   * check each control's selector resolves to exactly one element. Ambiguous selectors (one
   * selector matching 24 roster rows) are the point — they pass a naive check and then click the
   * wrong student's row.
   */
  async function verifyMap() {
    if (!siteMap || verifying) return;
    verifying = true;
    stopRequested = false;
    verdicts = [];
    verifySummary = null;
    const tabId = getActiveTabId();
    const start = normalizeUrl(pageUrl);
    const pages = siteMap.pages;

    try {
      for (const [i, node] of pages.entries()) {
        if (stopRequested) break;
        verifyMsg = `Verifying ${i + 1}/${pages.length}: ${node.pageName}…`;

        // Same guard as the crawl: one unreachable page must not end the pass.
        try {
          const loaded = await armPageLoad();
          await navigateEmbedded(tabId, node.url);
          await loaded();
          await settle();
          if (stopRequested) break;

          const landed = normalizeUrl(await getEmbeddedUrl(tabId).catch(() => node.url));
          const priorProfile = await loadProfile(domainFromUrl(landed) || '', node.pageName).catch(() => null);

          // Key-node spot check first: if every durable anchor still resolves, the page is
          // intact and re-capturing it would only confirm that at full price. Only a missing
          // anchor justifies the expensive path.
          const keys = priorProfile?.keyNodes ?? [];
          if (keys.length) {
            const probed = await probeSelectors(keys.map((k) => k.selector));
            const intact = await verifyKeyNodes(keys, (s) => (probed[s] ?? 0) > 0);
            if (intact.ok) {
              verdicts = [...verdicts, {
                url: node.url,
                pageName: node.pageName,
                status: 'ok' as const,
                landedUrl: landed,
                signatureMatch: true,
                checks: keys.map((k) => ({ kind: 'button' as const, label: k.label, selector: k.selector, status: 'ok' as const, matches: 1 })),
              }];
              continue;
            }
            verifyMsg = `${node.pageName}: ${intact.missing.length} key node(s) missing — re-capturing…`;
          }

          const { profile: fresh } = await captureCurrent(landed);
          const baseline = priorProfile ?? (await loadProfile(fresh.domain, node.pageName).catch(() => null));
          const counts = await probeSelectors(selectorsToProbe(fresh));
          verdicts = [...verdicts, gradePage(fresh, baseline, counts, landed)];
        } catch (e) {
          verdicts = [
            ...verdicts,
            {
              url: node.url,
              pageName: node.pageName,
              status: 'unreachable',
              signatureMatch: false,
              checks: [],
              error: e instanceof Error ? e.message : String(e),
            },
          ];
        }
      }

      await navigateEmbedded(tabId, start).catch(() => {}); // put you back where you started
      const s = summarize(verdicts);
      verifySummary = s;
      verifyMsg =
        (stopRequested ? `Stopped — ${s.pages} of ${pages.length} verified.` : `Verified ${s.pages} page(s).`) +
        ` ${s.ok} ok` +
        (s.drifted ? ` · ${s.drifted} drifted` : '') +
        (s.unreachable ? ` · ${s.unreachable} unreachable` : '') +
        (s.ambiguous ? ` · ${s.ambiguous} ambiguous selector(s)` : '') +
        (s.broken ? ` · ${s.broken} broken` : '') +
        (s.healed ? ` · ${s.healed} need self-heal` : '');
    } catch (e) {
      verifyMsg = `Verify failed: ${e instanceof Error ? e.message : String(e)}`;
    } finally {
      verifying = false;
    }
  }

  // If the panel is collapsed or you switch tabs, this component unmounts — halt any running
  // crawl so it can't keep navigating your browser headlessly with no UI/Stop button.
  onDestroy(() => { stopRequested = true; });

  /** Pages the one-click pipeline pruned after verify — shown so a removal is a decision, not a gap. */
  let pruned = $state<PruneDecision[]>([]);

  /**
   * One-click "Map this site" pipeline: crawl → verify every page → prune what verify PROVED bad
   * (unreachable / broken / all-ambiguous / redirect-duplicates) → save. Long on a big site
   * (20-30 min) but one-time: it lands on a map an agent can trust without a human pass.
   * Verify is the ONLY deletion authority: a page that verifies stays, however similar it looks
   * to another. Similarity-based trimming (the old suggestTrim list + "Trim all") was removed
   * outright — a live trial showed it deleting 55 of 64 genuinely distinct pages that merely
   * shared a count signature, and handing users that button ruins mappings by accident.
   * Stop at any phase keeps what's done and skips the rest.
   */
  async function mapSite() {
    pruned = [];
    await crawl();
    if (!siteMap || stopRequested) return;
    await verifyMap();
    if (!siteMap || stopRequested || !verdicts.length) return;
    const drop = pagesToPrune(verdicts);
    for (const d of drop) {
      const page = siteMap?.pages.find((p) => p.url === d.url);
      if (page) await clearPage(page);
    }
    pruned = drop;
    siteMsg = `Good map: ${siteMap?.pages.length ?? 0} page(s) kept · ${drop.length} pruned by verify (bad/weak/duplicate).`;
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
      siteMsg = 'Cleared. No pages left.';
    } else {
      siteMap = { ...siteMap, pages };
      await saveSiteMap(siteMap);
      siteMsg = `Cleared ${p.pageName}. ${pages.length} pages left.`;
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
  <p class="muted">Open a page in the browser, then map it. Only the page structure is read; no screenshots or text ever leave this app.</p>

  {#if message}<div class="msg">{message}</div>{/if}

  <div class="section site">
    <div class="head">
      <span class="hdr">Map this site</span>
      <div class="site-btns">
        <!-- Default full-site path: deterministic BFS crawl() — deep-capture (scroll to trigger
             lazy-loaded content) each page, plus batched AI template labeling when an engine is
             selected. aiDriveCrawl (single spawned agent) is demoted to a targeted, opt-in tool
             below — see its doc comment for why. -->
        <button class="map" disabled={crawling || verifying || aiDriving || aiVerifying} onclick={mapSite}
          title="One run that lands on a trustworthy map: crawls the site breadth-first (deep-capturing each page), then re-visits every page to verify its recorded controls and prunes ONLY what verify proves bad (unreachable, broken, all-ambiguous, redirect-duplicates). A page that verifies stays, however similar it looks. Can take 20-30 minutes on a big site — it's a one-time run. Stop keeps what's done.">
          {crawling ? '⏳ Crawling…' : verifying ? '⏳ Verifying…' : '🕸 Map this site'}
        </button>
        {#if !crawling && !aiVerifying}
          <button class="map" disabled={aiDriving || !provider} onclick={aiDriveCrawl}
            title="Targeted, opt-in — not for mapping a whole site (see comment on aiDriveCrawl in code: a full-site run cost 1.39M tokens for ~0 extra discovery over deep-capture). Spawns the chosen engine CLI full-shell to drive one ambiguous or click-gated page/area itself (read-only rules + deny-list in its prompt, max {CLI_CRAWL_MAX_PAGES} pages). Cannot be stopped mid-run; bounded by a 15 min timeout. Needs an engine selected above.">
            {aiDriving ? '🤖 Agent driving…' : '🤖 AI-drive (targeted)'}
          </button>
        {/if}
        {#if aiDoc && !aiDriving}
          <button class="map" disabled={aiVerifying || !provider} onclick={aiVerifyDoc}
            title="Spawns the agent again to re-read the pages it mapped and check its own document against the live site (read-only browsing). Produces a verification report, then heals: rewrites the mapping doc with its corrections (prior kept as .prev.md) and re-captures any pages whose URL it corrected.">
            {aiVerifying ? '🔎 Verifying & healing…' : '🔎 Agent verify + heal'}
          </button>
        {/if}
        {#if siteMap?.pages.length && !crawling && !aiDriving && !aiVerifying}
          <button class="map" disabled={verifying} onclick={verifyMap}
            title="Re-visit every mapped page and check the agent could act on it (selectors resolve to one element). Read-only; resolves selectors, never clicks. Works for the agent-driven map too, since its pages are captured into profiles.">
            {verifying ? '⏳ Verifying…' : '✅ Verify map'}
          </button>
        {/if}
        {#if crawling || verifying}
          <button class="map stop" onclick={stopCrawl} title="Stop after the current page">⏹ Stop</button>
        {/if}
      </div>
    </div>
    <!-- AI-guided BFS toggle hidden with the BFS button on this branch; state + crawl() kept. -->
    <p class="muted">Maps this site page by page, starting from where you are now, so each class near the top is mapped first.</p>
    <ul class="how">
      <li>
        {#if scopeOf(pageUrl)}Stays in this course ({scopeOf(pageUrl)?.key}={scopeOf(pageUrl)?.value}) on this site only.
        {:else}Stays on this site only.{/if}
      </li>
      <li>Never changes anything. Skips logout, admin, and any add / edit / delete link.</li>
      <li>Repeating pages (student rosters, question banks) are sampled a couple of times, then skipped, up to {MAX_SAMPLES_PER_TEMPLATE} if they keep shifting.</li>
      <li>Runs in your browser tab. Press <strong>Stop</strong> anytime.</li>
    </ul>
    {#if siteMsg}<div class="msg">{siteMsg}</div>{/if}

    {#if collapsed.length}
      <div class="head">
        <span class="hdr">Repeating templates collapsed ({collapsed.length})</span>
      </div>
      <ul class="list">
        {#each collapsed as c (c.template)}
          <li class="row site-row">
            <span class="label" title={c.template}>{c.template}: <span class="kind">{c.skipped} repeat page(s) skipped, shape already mapped</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if selfAudited.length}
      <div class="head">
        <span class="hdr">AI self-audit: re-checked ({selfAudited.length})</span>
      </div>
      <ul class="list">
        {#each selfAudited as s (s.url)}
          <li class="row site-row">
            <span class="label" title={s.url}>{s.pageName}: <span class="kind">referenced elsewhere, was captured empty</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if pruned.length}
      <div class="head">
        <span class="hdr">Pruned by verify ({pruned.length})</span>
      </div>
      <ul class="list">
        {#each pruned as p (p.url)}
          <li class="row site-row">
            <span class="label" title={p.url}>{p.pageName}: <span class="kind">{p.reason}</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if aiProgress.length}
      <div class="head">
        <span class="hdr">Agent activity {#if aiDriving || aiVerifying}(live){/if}</span>
      </div>
      <ul class="list progress-log">
        {#each aiProgress.slice(-12) as line, i (i + line)}
          <li class="row site-row"><code class="sel">{line}</code></li>
        {/each}
      </ul>
    {/if}

    {#if aiDoc}
      <div class="head">
        <span class="hdr">Mapping document (written by the model)</span>
      </div>
      {#if aiDocPath}<div class="msg">Saved → <code>{aiDocPath}</code></div>{/if}
      <pre class="sample">{aiDoc}</pre>
    {/if}

    {#if aiVerifyReport}
      <div class="head">
        <span class="hdr">Agent verification report</span>
      </div>
      <pre class="sample">{aiVerifyReport}</pre>
    {/if}

    {#if aiSkips.length}
      <div class="head">
        <span class="hdr">Skipped by AI ({aiSkips.length})</span>
      </div>
      <ul class="list">
        {#each aiSkips as s (s.url)}
          <li class="row site-row">
            <span class="label" title={s.url}>{s.url}: <span class="kind">{s.reason}</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if synthesis}
      <div class="head">
        <span class="hdr">AI site map</span>
      </div>
      <ul class="list">
        {#each synthesis.sections as sec (sec.name)}
          <li class="row site-row">
            <span class="label">{sec.name}: <span class="kind">{sec.urls.map((u) => siteMap?.pages.find((p) => p.url === u)?.pageName ?? u).join(' · ')}</span></span>
          </li>
        {/each}
      </ul>
      {#if synthesis.workflows.length}
        <p class="muted">Suggested workflows: {synthesis.workflows.join(' · ')}</p>
      {/if}
    {/if}

    {#if verifyMsg}<div class="msg">{verifyMsg}</div>{/if}

    {#if verifySummary && !verifySummary.clean}
      <div class="head">
        <span class="hdr">Pages the agent may not act on correctly</span>
      </div>
      <p class="muted">Only problem pages are listed. <strong>Ambiguous</strong> is the one to fix first: the selector matches several elements, so a click silently hits the first. On a roster that is the wrong student.</p>
      <ul class="list">
        {#each verdicts.filter((v) => v.status !== 'ok') as v (v.url)}
          <li class="row site-row">
            <span class="label" title={v.url}>
              {v.pageName}
              {#if v.status === 'unreachable'}
                <span class="kind">unreachable: {v.error}</span>
              {:else}
                <span class="kind">
                  {v.checks.filter((c) => c.status === 'ambiguous').length} ambiguous ·
                  {v.checks.filter((c) => c.status === 'broken').length} broken ·
                  {v.checks.filter((c) => c.status === 'healed').length} self-heal
                  {#if !v.signatureMatch}· shape changed since crawl{/if}
                </span>
              {/if}
            </span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if failures.length}
      <div class="head">
        <span class="hdr">Skipped: capture failed ({failures.length})</span>
      </div>
      <ul class="list">
        {#each failures as f (f.url)}
          <li class="row site-row">
            <span class="label" title={f.url}>{f.url}: <span class="kind">{f.error}</span></span>
          </li>
        {/each}
      </ul>
    {/if}

    {#if siteMap && siteMap.pages.length}
      <div class="head">
        <span class="hdr">{siteMap.domain} · {siteMap.pages.length} pages</span>
        <button class="link-btn" onclick={clearAll} title="Delete every mapped page for this site">Clear all</button>
      </div>
      <ul class="list">
        {#each siteMap.pages as p}
          <li class="row site-row">
            <button class="rowmain" onclick={() => reviewPage(p)} title="Review this saved page">
              <span class="label" title={p.card ? `${p.url}\n${p.card.purpose}` : p.url}>{p.pageName}</span>
              <span class="kind">{#if p.card}{p.card.pageType} · {/if}{p.links.length} links · {p.counts.buttons} btn · {p.counts.inputs} in</span>
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
      <span class="hdr">Redacted snapshot · {redactedSize.toLocaleString()} chars · {redactedTokens} data tokens</span>
      <p class="muted">This is exactly what the model would see. It should hold only chrome (controls, labels, headings) and ⟦D…⟧ tokens, with no student names or IDs.</p>
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
  .how {
    margin: 6px 0 0; padding-left: 1.1em; list-style: none;
    color: var(--text-secondary); font-size: 0.8rem; line-height: 1.5;
    display: flex; flex-direction: column; gap: 3px;
  }
  .how li { position: relative; }
  .how li::before {
    content: ''; position: absolute; left: -0.95em; top: 0.5em;
    width: 4px; height: 4px; border-radius: 50%; background: var(--text-tertiary, var(--text-secondary));
  }
  .how strong { color: var(--text-primary); font-weight: 600; }
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

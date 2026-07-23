<script lang="ts">
  import { onMount, onDestroy, tick } from 'svelte';
  import { 
    createEmbeddedBrowser, 
    navigateEmbedded, 
    goBack, 
    goForward, 
    reloadBrowser,
    setWebviewBounds,
    getEmbeddedUrl,
    hideWebview,
    showWebview,
    destroyWebview,
    evalScriptInTab,
    listenBrowserUrlChanged, 
    listenBrowserPageLoaded,
    listenBrowserStatus,
    setActiveTabId,
    injectScript,
    evalScript as webviewEval,
    type BrowserEventPayload,
  } from '../lib/browser';
  import { matchCredentialsToUrl, generateAutoFillScript, loginFieldSelectors } from '../lib/autofill';
  import { totpNow } from '../lib/totp';
  import { connectCDP, isConnected, evalScript as cdpEval } from '../lib/cdp-actions';
  import { calculateWebviewBounds } from '../lib/webview-layout';
  import {
    shouldTriggerSidebarAnimation,
    scheduleBoundsUpdateAfterAnimation,
    createDestroyGuard,
  } from '../lib/webview-lifecycle';
  import { getSetting, setSetting, getSiteCredentials, saveSiteCredential, getBookmarks, addBookmark, deleteBookmark, type Bookmark, type SiteCredential } from '../lib/db';
  import { ICON_STRIP_WIDTH } from '../lib/constants';
  import { tabMarker, markerScript, type TabInfo } from '../lib/tab-control';
  import { agentOverlayScript, AGENT_OVERLAY_REMOVE, DIALOG_SUPPRESS_SCRIPT, SESSION_COLORS, type AgentActiveDetail } from '../lib/agent-overlay';
  import { startRecording, stopRecording } from '../lib/artifacts-api';
  import ActionPanel from './ActionPanel.svelte';

  // Exposed to spawned CLI agents so they can enumerate/drive the app's own tabs instead of
  // guessing from the CDP target list. Wraps the existing tab functions below — no separate
  // tab-management logic lives here.
  // (svelte-check rejects a top-level `declare global` inside a component script, so the
  // shape is a local type and `window` is cast at the use sites instead.)
  interface SteveControl {
    listTabs: () => TabInfo[];
    newTab: (url?: string) => Promise<string>;
    closeTab: (id: string) => Promise<void>;
    navigate: (id: string, url: string) => Promise<void>;
    activate: (id: string) => Promise<void>;
    /** Fill + submit the login form on a tab using saved creds. Returns true if a credential
     *  matched the tab's URL (creds stay on-device — never sent to the model). */
    login: (id?: string) => Promise<boolean>;
    /** Run JS inside a tab's embedded webview and return its result (JSON-parsed). This is the
     *  agent's read/act bridge: on this platform embedded tabs are NOT CDP targets, so the agent
     *  reaches them through the app-UI target (which IS) via this method. `id` omitted = active tab. */
    eval: (id: string | undefined, script: string) => Promise<unknown>;
    /** Start/stop an app-window screen recording (ffmpeg) — saved to the Artifacts gallery. */
    startRecording: () => Promise<string>;
    stopRecording: () => Promise<string | null>;
  }
  const steveWindow = window as Window & { __steveControl?: SteveControl };

  // The page stays MOUNTED when the user navigates away (App keeps <Browser> alive so embedded
  // webviews + any running agent task survive) — this flips false while another page is showing.
  // Native webviews float above the DOM, so hiding the holder in CSS isn't enough; we hide/show
  // the active tab's webview explicitly on the toggle.
  let { active = true }: { active?: boolean } = $props();

  let urlInput = $state('');
  // The tab an agent is currently driving — highlights its tab and keeps the connection overlay
  // (border ring + arrow cursor) re-injected across the agent's navigations.
  // Concurrent agent sessions, each colour-coded so two running at once are distinguishable. A session
  // is owned by the tab that started the run; it grows as the agent opens/switches tabs (each new tab
  // inherits the session of the tab the agent came from).
  interface AgentSession { owner: string; tabs: string[]; color: string; activeTab: string; }
  let sessions = $state<AgentSession[]>([]);
  function sessionOf(tabId: string): AgentSession | undefined {
    return sessions.find((s) => s.tabs.includes(tabId));
  }
  function nextSessionColor(): string {
    const used = new Set(sessions.map((s) => s.color));
    return SESSION_COLORS.find((c) => !used.has(c)) ?? SESSION_COLORS[sessions.length % SESSION_COLORS.length];
  }
  let showActionPanel = $state(false);
  let actionPanelCollapsed = $state(false);
  let actionPanelWidth = $state(400);
  let toastMessage = $state('');
  let toastTimer: ReturnType<typeof setTimeout> | undefined;
  
  let unlistenUrl: (() => void) | undefined;
  let unlistenLoaded: (() => void) | undefined;
  let unlistenStatus: (() => void) | undefined;
  let resizeTimeout: ReturnType<typeof setTimeout> | undefined;
  let sidebarAnimationId: number | undefined;
  const guard = createDestroyGuard();
  let cancelScheduledBoundsUpdate: (() => void) | undefined;
  let drawerResizeTimeout: ReturnType<typeof setTimeout> | undefined;

  interface Tab {
    id: string;
    url: string;
    title: string;
    isLoading: boolean;
    browserCreated: boolean;
  }
  let tabs = $state<Tab[]>([]);
  let activeTabId = $state('');
  let creatingTabId = $state('');

  let currentTab = $derived(tabs.find(t => t.id === activeTabId));
  // Which tab's ActionPanel to show. Normally the active tab's own panel — but if the active tab
  // belongs to an agent SESSION, show the SESSION OWNER's panel (where the run + its live feedback
  // live), so the agent's progress is shared across the controlled session instead of the agent's
  // newly-opened tab showing its own blank panel.
  let panelTabId = $derived(sessionOf(activeTabId)?.owner ?? activeTabId);
  let isLoading = $derived(currentTab?.isLoading ?? false);
  let browserCreated = $derived(currentTab?.browserCreated ?? false);
  let pageLoadedUrl = $derived(currentTab?.url ?? '');

  let bookmarks = $state<Bookmark[]>([]);
  let showBookmarks = $state(false);
  // Star reflects the whole SITE: lit on any page whose host matches a bookmarked host.
  let isBookmarked = $derived(!!pageLoadedUrl && bookmarks.some((b) => hostOf(b.url) === hostOf(pageLoadedUrl)));

  function hostOf(url: string): string {
    try { return new URL(url).hostname; } catch { return ''; }
  }

  async function loadBookmarks() {
    try {
      bookmarks = await getBookmarks();
    } catch (e) {
      // Most likely the bookmarks table isn't there yet — needs an app restart so migration 7 runs.
      bookmarks = [];
      console.error('loadBookmarks failed', e);
    }
  }

  // The embedded browser is a native webview that paints over the HTML UI, so a popover can't
  // overlay it. Instead, show a bookmarks BAR in HTML space below the toolbar and push the
  // webview down by the bar's height (extraTopOffset) — the page stays visible, just shorter.
  async function setBookmarksOpen(open: boolean) {
    showBookmarks = open;
    await tick();
    updateWebviewBounds();
  }

  async function toggleBookmark() {
    // Resolve the URL live — pageLoadedUrl can lag if the page-loaded event hasn't fired.
    let url = pageLoadedUrl;
    if (!url && activeTabId) {
      try { url = await getEmbeddedUrl(activeTabId); } catch { /* ignore */ }
    }
    if (!url) {
      showToast('Open a page first, then bookmark it.');
      return;
    }
    const host = hostOf(url);
    try {
      const onThisSite = bookmarks.filter((b) => hostOf(b.url) === host);
      if (onThisSite.length) {
        for (const b of onThisSite) await deleteBookmark(b.id); // unstar the whole site
        showToast('Bookmark removed');
      } else {
        await addBookmark(currentTab?.title || host, url);
        showToast('Bookmarked ★');
      }
      await loadBookmarks();
    } catch (e) {
      showToast('Bookmark failed: ' + (e instanceof Error ? e.message : String(e)));
      console.error('toggleBookmark failed', e);
    }
  }

  async function openBookmark(url: string) {
    await setBookmarksOpen(false);
    urlInput = url;
    await handleNavigate();
  }

  async function removeBookmark(id: number) {
    await deleteBookmark(id);
    await loadBookmarks();
  }

  // ── Passwords bar: capture a login off the live page, and review saved logins ──────────────
  let showPasswords = $state(false);
  let credentials = $state<SiteCredential[]>([]);
  let revealed = $state<Set<number>>(new Set());
  let autoSubmit = $state(false); // after autofilling a saved login, submit the form too

  async function loadCredentials() {
    try { credentials = await getSiteCredentials(); } catch { credentials = []; }
  }

  async function toggleAutoSubmit() {
    autoSubmit = !autoSubmit;
    await setSetting('autoSubmitLogin', autoSubmit ? 'true' : 'false');
  }

  async function togglePasswords(open: boolean) {
    showPasswords = open;
    if (open) await loadCredentials();
    await tick();
    updateWebviewBounds();
  }

  function toggleReveal(id: number) {
    const next = new Set(revealed);
    next.has(id) ? next.delete(id) : next.add(id);
    revealed = next;
  }

  // Fill the saved credential for this page and submit it. Same on-device path as the autofill on
  // page load — creds come from the local DB straight into the embedded page, never to a model.
  // If the site then asks for a Duo push, the user is right here to tap it.
  async function loginNow(tabId?: string): Promise<boolean> {
    const id = tabId ?? activeTabId;
    if (!id) return false;
    let url = '';
    try { url = await getEmbeddedUrl(id); } catch { /* ignore */ }
    if (!url) return false;
    const match = matchCredentialsToUrl(url, await getSiteCredentials());
    if (!match) return false;
    // submit=true regardless of the global toggle: an explicit "log in" means fill AND submit.
    const otp = match.totp_secret ? await totpNow(match.totp_secret).catch(() => '') : '';
    await injectScript(generateAutoFillScript(match.username, match.password, true, otp), id);
    return true;
  }

  async function handleLoginClick() {
    showToast((await loginNow()) ? 'Logging in…' : 'No saved login matches this page.');
  }

  // Read the username/password the user typed into the embedded page (over CDP) and save them.
  async function saveCurrentLogin() {
    let url = pageLoadedUrl;
    if (!url && activeTabId) { try { url = await getEmbeddedUrl(activeTabId); } catch { /* ignore */ } }
    if (!url) { showToast('Open the login page first.'); return; }
    try {
      if (!isConnected() && !(await connectCDP())) {
        showToast('Could not read the page — is it loaded?');
        return;
      }
      const sel = loginFieldSelectors(url);
      const script =
        `(function(){var u=document.querySelector(${JSON.stringify(sel.usernameSelector)});` +
        `var p=document.querySelector(${JSON.stringify(sel.passwordSelector)});` +
        `return JSON.stringify({username:u?u.value:'',password:p?p.value:''});})()`;
      const res = await cdpEval(script);
      const got = res.success && typeof res.data === 'string' ? JSON.parse(res.data) : { username: '', password: '' };
      if (!got.password) { showToast('No filled password field found on this page.'); return; }
      const host = hostOf(url);
      await saveSiteCredential({
        site_name: currentTab?.title || host,
        url_pattern: `https://${host}/%`,
        username: got.username || '',
        password: got.password,
      });
      showToast(`Login saved for ${host}`);
      await loadCredentials();
    } catch (e) {
      showToast('Save login failed: ' + (e instanceof Error ? e.message : String(e)));
      console.error('saveCurrentLogin failed', e);
    }
  }

  onMount(async () => {
    loadBookmarks();
    loadCredentials();
    try { autoSubmit = (await getSetting('autoSubmitLogin')) === 'true'; } catch { /* default off */ }
  });

  function showToast(message: string, durationMs = 3000) {
    if (toastTimer) clearTimeout(toastTimer);
    toastMessage = message;
    toastTimer = setTimeout(() => { toastMessage = ''; }, durationMs);
  }

  function getTabDisplayTitle(tab: Tab): string {
    if (!tab.url) return 'New Tab';
    try {
      const normalized = tab.url.startsWith('http') ? tab.url : 'https://' + tab.url;
      return new URL(normalized).hostname || 'New Tab';
    } catch {
      return tab.url.slice(0, 20) || 'New Tab';
    }
  }

  async function openNewTab(url?: string) {
    const id = crypto.randomUUID();
    const newTab: Tab = { id, url: url ?? '', title: '', isLoading: false, browserCreated: false };
    tabs = [...tabs, newTab];
    await switchTab(id);
    if (url) {
      urlInput = url;
      await handleNavigate();
    }
  }

  async function switchTab(id: string) {
    if (activeTabId && activeTabId !== id) {
      const prev = tabs.find(t => t.id === activeTabId);
      if (prev?.browserCreated) {
        await hideWebview(activeTabId).catch(() => {});
      }
    }
    activeTabId = id;
    setActiveTabId(id);
    const tab = tabs.find(t => t.id === id);
    if (tab) {
      urlInput = tab.url;
    }
    if (tab?.browserCreated) {
      await showWebview(id).catch(() => {});
      await tick();
      updateWebviewBounds();
    }
  }

  async function closeTab(id: string) {
    const tab = tabs.find(t => t.id === id);
    if (tab?.browserCreated) {
      await destroyWebview(id).catch(() => {});
    }
    tabs = tabs.filter(t => t.id !== id);
    if (activeTabId === id) {
      if (tabs.length > 0) {
        await switchTab(tabs[tabs.length - 1].id);
      } else {
        activeTabId = '';
        setActiveTabId('');
        urlInput = '';
      }
    }
  }

  function updateWebviewBounds() {
    if (!browserCreated || !activeTabId) return;
    
    const sidebar = document.querySelector('.sidebar');
    const sidebarWidth = sidebar ? sidebar.getBoundingClientRect().width : 60;
    
    const navBar = document.querySelector('.nav-bar');
    const navBarHeight = navBar ? navBar.getBoundingClientRect().height : 50;

    const tabBarEl = document.querySelector('.tab-bar');
    const tabBarHeight = tabBarEl ? tabBarEl.getBoundingClientRect().height : 0;

    // Any open aux bar (bookmarks / passwords) occupies HTML space above the webview.
    let barHeight = 0;
    document.querySelectorAll('.aux-bar').forEach((el) => { barHeight += el.getBoundingClientRect().height; });

    const drawerWidth = showActionPanel
      ? (actionPanelCollapsed ? ICON_STRIP_WIDTH : actionPanelWidth)
      : 0;

    const bounds = calculateWebviewBounds({
      sidebarWidth,
      navBarHeight,
      panelWidth: drawerWidth,
      windowWidth: window.innerWidth,
      windowHeight: window.innerHeight,
      extraTopOffset: tabBarHeight + barHeight,
    });
    
    if (bounds.width > 0 && bounds.height > 0) {
      setWebviewBounds(activeTabId, bounds.x, bounds.y, bounds.width, bounds.height).catch(() => {});
    }
  }

  $effect(() => {
    showActionPanel;
    actionPanelCollapsed;
    actionPanelWidth;
    if (browserCreated) {
      tick().then(() => updateWebviewBounds());
    }
  });

  // Page kept mounted across navigation: hide the native webview when another page is showing
  // (it would otherwise float over that page), re-show + re-bound when we return. The webview and
  // any in-flight agent run are never destroyed — that's the whole point of staying mounted.
  $effect(() => {
    const id = activeTabId;
    if (!active) {
      if (id) hideWebview(id).catch(() => {});
      return;
    }
    if (id && browserCreated) {
      showWebview(id).catch(() => {});
      tick().then(() => updateWebviewBounds());
    }
  });

  $effect(() => {
    actionPanelWidth;
    actionPanelCollapsed;
    showActionPanel;
    if (drawerResizeTimeout) clearTimeout(drawerResizeTimeout);
    drawerResizeTimeout = setTimeout(async () => {
      await setSetting('steveDrawerState', JSON.stringify({
        open: showActionPanel,
        width: actionPanelWidth,
        collapsed: actionPanelCollapsed
      }));
    }, 300);
  });

  function handleResize() {
    if (resizeTimeout) clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => updateWebviewBounds(), 100);
  }

  /** A spawn signals it is (dis)connecting from a tab — track it so the tab is highlighted and the
   *  overlay is re-injected across the agent's navigations (see the page-loaded handler). */
  function handleAgentActive(e: CustomEvent<AgentActiveDetail>) {
    const detail = e.detail;
    if (detail?.active) {
      const owner = detail.tabId;
      if (owner && !sessions.some((s) => s.owner === owner)) {
        const color = nextSessionColor();
        sessions = [...sessions, { owner, tabs: [owner], color, activeTab: owner }];
        injectScript(DIALOG_SUPPRESS_SCRIPT, owner).catch(() => {});
        injectScript(agentOverlayScript(color), owner).catch(() => {});
      }
    } else {
      // Run ended — end the session owned by this tab and strip its overlay from every tab it touched.
      const s = sessions.find((x) => x.owner === detail?.tabId) ?? sessionOf(detail?.tabId ?? '');
      if (s) {
        for (const t of s.tabs) injectScript(AGENT_OVERLAY_REMOVE, t).catch(() => {});
        sessions = sessions.filter((x) => x !== s);
        // Return focus to the tab that started the run so its panel (the plan / result) is visible
        // again — the agent may have left a different session tab active mid-run, which hid it.
        if (activeTabId !== s.owner && tabs.some((t) => t.id === s.owner)) void switchTab(s.owner);
      }
    }
  }

  /** The agent moved from `fromTabId` to `toTabId`. The new tab inherits the session of the tab the
   *  agent came from — so concurrent sessions never bleed colours — and gets that session's coloured
   *  overlay. A racing inject on a freshly-opened tab is harmless; the page-loaded handler re-injects. */
  async function followAgentTo(fromTabId: string, toTabId: string) {
    if (!toTabId) return;
    const s = sessionOf(fromTabId) ?? sessionOf(toTabId);
    if (!s) return;
    if (!s.tabs.includes(toTabId)) s.tabs = [...s.tabs, toTabId]; // grow this session
    s.activeTab = toTabId;
    await injectScript(DIALOG_SUPPRESS_SCRIPT, toTabId).catch(() => {});
    await injectScript(agentOverlayScript(s.color), toTabId).catch(() => {});
  }

  function handleSidebarChanged() {
    if (sidebarAnimationId) cancelAnimationFrame(sidebarAnimationId);

    const startTime = performance.now();
    const duration = 300; 

    function animateFrame() {
      const elapsed = performance.now() - startTime;
      updateWebviewBounds();

      if (elapsed < duration) {
        sidebarAnimationId = requestAnimationFrame(animateFrame);
      } else {
        sidebarAnimationId = undefined;
      }
    }

    sidebarAnimationId = requestAnimationFrame(animateFrame);
  }

  onMount(async () => {
    const savedDrawerState = await getSetting('steveDrawerState');
    if (guard.destroyed) return;
    if (savedDrawerState) {
      try {
        const state = JSON.parse(savedDrawerState);
        if (state.open !== undefined) showActionPanel = state.open;
        if (state.width !== undefined) actionPanelWidth = state.width;
        if (state.collapsed !== undefined) actionPanelCollapsed = state.collapsed;
      } catch {
        showActionPanel = false;
        actionPanelWidth = 400;
      }
    } else {
      showActionPanel = false;
      actionPanelWidth = 400;
    }

    unlistenUrl = await listenBrowserUrlChanged(({ tabId, url }: BrowserEventPayload) => {
      tabs = tabs.map(t => t.id === tabId ? { ...t, url } : t);
      if (tabId === activeTabId) urlInput = url;
    });
    if (guard.destroyed) { unlistenUrl(); return; }

    unlistenLoaded = await listenBrowserPageLoaded(async ({ tabId, url }: BrowserEventPayload) => {
      tabs = tabs.map(t => t.id === tabId ? { ...t, isLoading: false, url } : t);
      if (tabId === activeTabId) urlInput = url;
      await maybeAutofill(tabId, url);
      await checkPendingLogin(url);                       // offer to save a just-submitted login
      await injectScript(LOGIN_CAPTURE_SCRIPT, tabId).catch(() => {}); // arm capture on this page
      await injectScript(markerScript(tabId), tabId).catch(() => {}); // re-stamp window.name (resets cross-origin)
      // If this tab belongs to an agent session, re-show its (session-coloured) overlay AND neutralize
      // JS dialogs (a page load cleared both). An unhandled alert/confirm/beforeunload blocks the
      // page's message loop → the agent's CDP hangs; suppressing them keeps the agent from wedging.
      const loadedSession = sessionOf(tabId);
      if (loadedSession) {
        await injectScript(DIALOG_SUPPRESS_SCRIPT, tabId).catch(() => {});
        await injectScript(agentOverlayScript(loadedSession.color), tabId).catch(() => {});
      }
    });
    if (guard.destroyed) { unlistenUrl(); unlistenLoaded(); return; }

    unlistenStatus = await listenBrowserStatus(async (status: string) => {
      if (status === 'embedded-open' && creatingTabId) {
        const tid = creatingTabId;
        creatingTabId = '';
        tabs = tabs.map(t => t.id === tid ? { ...t, browserCreated: true, isLoading: false } : t);
        if (tid === activeTabId) {
          await tick();
          updateWebviewBounds();
          await showWebview(tid).catch(() => {});
        }
      } else if (status === 'error') {
        if (creatingTabId) {
          tabs = tabs.map(t => t.id === creatingTabId ? { ...t, isLoading: false } : t);
          creatingTabId = '';
        }
        showToast('Failed to create browser. Please try again.');
      }
    });
    if (guard.destroyed) { unlistenUrl(); unlistenLoaded(); unlistenStatus(); return; }

    window.addEventListener('resize', handleResize);
    window.addEventListener('steve:sidebar-changed', handleSidebarChanged);
    window.addEventListener('steve:agent-active', handleAgentActive as EventListener);

    steveWindow.__steveControl = {
      listTabs: (): TabInfo[] => tabs.map(t => ({
        id: t.id,
        url: t.url,
        title: t.title,
        active: t.id === activeTabId,
        ready: t.browserCreated,
        marker: tabMarker(t.id),
      })),
      // When a session is active, the coloured overlay follows the agent onto the tab it opens/
      // switches to. Capture the CURRENT tab first (the agent came from it) so the new tab inherits
      // that tab's session — this is how concurrent sessions keep their tabs (and colours) separate.
      newTab: async (url?: string) => { const from = activeTabId; await openNewTab(url); if (sessions.length) await followAgentTo(from, activeTabId); return activeTabId; },
      closeTab: async (id: string) => { await closeTab(id); },
      navigate: async (id: string, url: string) => { const from = activeTabId; await switchTab(id); urlInput = url; await handleNavigate(); if (sessions.length) await followAgentTo(from, id); },
      activate: async (id: string) => { const from = activeTabId; await switchTab(id); if (sessions.length) await followAgentTo(from, id); },
      login: (id?: string) => loginNow(id),
      // The agent's bridge into the real tab (embedded webviews aren't CDP targets on this
      // platform). Runs JS by tab LABEL via the Rust eval_webview_script, returns the parsed
      // result. Rust JSON.stringify's the value, so parse it back to a real JS value here.
      eval: async (id: string | undefined, script: string): Promise<unknown> => {
        const json = await evalScriptInTab(id ?? activeTabId, script);
        try { return JSON.parse(json); } catch { return json; }
      },
      // Record the tab the agent is on — pass its URL so the right target is captured with several open.
      startRecording: () => startRecording(tabs.find(t => t.id === activeTabId)?.url),
      stopRecording: () => stopRecording(),
    };

    await openNewTab();
  });

  onDestroy(() => {
    delete steveWindow.__steveControl;

    tabs.forEach(tab => {
      if (tab.browserCreated) {
        destroyWebview(tab.id).catch(() => {});
      }
    });

    guard.onDestroy([
      () => { if (unlistenUrl) unlistenUrl(); },
      () => { if (unlistenLoaded) unlistenLoaded(); },
      () => { if (unlistenStatus) unlistenStatus(); },
      () => { if (resizeTimeout) clearTimeout(resizeTimeout); },
      () => { if (drawerResizeTimeout) clearTimeout(drawerResizeTimeout); },
      () => { if (sidebarAnimationId) cancelAnimationFrame(sidebarAnimationId); },
      () => { if (cancelScheduledBoundsUpdate) cancelScheduledBoundsUpdate(); },
      () => { window.removeEventListener('resize', handleResize); },
      () => { window.removeEventListener('steve:sidebar-changed', handleSidebarChanged); },
      () => { window.removeEventListener('steve:agent-active', handleAgentActive as EventListener); },
    ]);
  });

  // On page load, fill saved credentials for a matching site. Local only:
  // creds come from the local DB, the fill script never auto-submits, and
  // nothing is sent anywhere but the matched page in the embedded browser.
  async function maybeAutofill(tabId: string, url: string) {
    try {
      const match = matchCredentialsToUrl(url, await getSiteCredentials());
      if (match) {
        const otp = match.totp_secret ? await totpNow(match.totp_secret).catch(() => '') : '';
        await injectScript(generateAutoFillScript(match.username, match.password, autoSubmit, otp), tabId);
      }
    } catch {
      // never surface credential errors to the page or logs
    }
  }

  // Captures a login on SUBMIT into localStorage; read+cleared on the next page load (below).
  // Stays in the embedded page; nothing is sent anywhere. Same-origin only (localStorage scope).
  const LOGIN_CAPTURE_SCRIPT = `(function(){
    if (window.__steveLoginHook) return; window.__steveLoginHook = true;
    document.addEventListener('submit', function(e){
      try {
        var form = e.target; if(!form || !form.querySelectorAll) return;
        var p = form.querySelector('input[type=password]'); if(!p || !p.value) return;
        var u = '', ins = form.querySelectorAll('input');
        for (var i=0;i<ins.length;i++){ var t=(ins[i].type||'').toLowerCase();
          if ((t==='text'||t==='email'||t==='tel'||t==='') && ins[i].value){ u=ins[i].value; break; } }
        localStorage.setItem('__steve_pending_login', JSON.stringify({username:u, password:p.value, host:location.hostname}));
      } catch(_){}
    }, true);
  })();`;

  let pendingLogin = $state<{ username: string; password: string; host: string } | null>(null);

  // After a login redirect, read the captured creds and offer to save them — unless this site
  // already has a saved credential. The prompt is the "auto-login" save bar.
  async function checkPendingLogin(url: string) {
    try {
      const raw = await webviewEval(
        "(function(){var k='__steve_pending_login';var v=localStorage.getItem(k);if(v)localStorage.removeItem(k);return v;})()",
      );
      if (!raw || raw === 'null') return;
      const got = JSON.parse(raw) as { username: string; password: string; host: string };
      if (!got?.password) return;
      if (matchCredentialsToUrl(url, credentials)) return; // already saved → nothing to offer
      pendingLogin = got;
      await tick();
      updateWebviewBounds();
    } catch { /* ignore — capture is best-effort */ }
  }

  async function clearPrompt() {
    pendingLogin = null;
    await tick();
    updateWebviewBounds();
  }

  async function savePendingLogin() {
    if (!pendingLogin) return;
    const { username, password, host } = pendingLogin;
    try {
      await saveSiteCredential({
        site_name: currentTab?.title || host,
        url_pattern: `https://${host}/%`,
        username,
        password,
      });
      showToast(`Login saved for ${host}`);
      await loadCredentials();
    } catch (e) {
      showToast('Save failed: ' + (e instanceof Error ? e.message : String(e)));
    }
    await clearPrompt();
  }

  async function handleNavigate() {
    if (!urlInput.trim() || !activeTabId) return;
    const url = urlInput.trim();
    tabs = tabs.map(t => t.id === activeTabId ? { ...t, isLoading: true, url } : t);
    try {
      if (!currentTab?.browserCreated) {
        creatingTabId = activeTabId;
        await createEmbeddedBrowser(activeTabId, url);
        await tick();
        updateWebviewBounds();
      } else {
        await navigateEmbedded(activeTabId, url);
      }
    } catch (e) {
      tabs = tabs.map(t => t.id === activeTabId ? { ...t, isLoading: false } : t);
    }
  }

  async function handleBack() {
    if (activeTabId) await goBack(activeTabId);
  }

  async function handleForward() {
    if (activeTabId) await goForward(activeTabId);
  }

  async function handleReload() {
    if (!activeTabId) return;
    tabs = tabs.map(t => t.id === activeTabId ? { ...t, isLoading: true } : t);
    await reloadBrowser(activeTabId);
  }

  function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'Enter') handleNavigate();
  }

  function toggleDrawer() {
    showActionPanel = !showActionPanel;
    if (showActionPanel) {
      actionPanelCollapsed = false;
    }
  }
</script>

<div class="browser-container">
  <div class="tab-bar">
    {#each tabs as tab (tab.id)}
      {@const s = sessionOf(tab.id)}
      <div
        class="tab"
        class:active={tab.id === activeTabId}
        class:agent-driving={s?.activeTab === tab.id}
        class:agent-group={!!s}
        style:--agent-color={s?.color ?? ''}
        onclick={() => switchTab(tab.id)}
        onkeydown={(e) => e.key === 'Enter' && switchTab(tab.id)}
        role="tab"
        tabindex="0"
        title={s?.activeTab === tab.id ? 'An agent is driving this tab' : s ? 'Part of an agent session' : ''}
      >
        {#if s?.activeTab === tab.id}<span class="agent-dot" aria-label="agent connected">●</span>{/if}
        <span class="tab-title">{tab.title || getTabDisplayTitle(tab)}</span>
        {#if tabs.length > 1}
          <button class="tab-close" onclick={(e) => { e.stopPropagation(); closeTab(tab.id); }}>×</button>
        {/if}
      </div>
    {/each}
    <button class="tab-new" onclick={() => openNewTab()}>+</button>
  </div>

  <div class="nav-bar">
    <div class="nav-controls">
      <button class="icon-btn" onclick={handleBack} title="Back">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </button>
      <button class="icon-btn" onclick={handleForward} title="Forward">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
      <button class="icon-btn" onclick={handleReload} title="Reload">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
      </button>
    </div>

    <div class="url-input-container">
      <input
        type="text"
        bind:value={urlInput}
        onkeydown={handleKeydown}
        placeholder="Enter URL..."
      />
      {#if isLoading}
        <div class="spinner"></div>
      {/if}
    </div>

    <button class="icon-btn star" class:active={isBookmarked} onclick={toggleBookmark} disabled={!activeTabId}
      title={isBookmarked ? 'Remove bookmark' : 'Bookmark this page'}>
      {isBookmarked ? '★' : '☆'}
    </button>

    <button class="icon-btn" class:active={showBookmarks} onclick={() => setBookmarksOpen(!showBookmarks)} title="Show bookmarks bar">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
    </button>

    <button class="icon-btn" class:active={showPasswords} onclick={() => togglePasswords(!showPasswords)} title="Passwords — save & view logins">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>
    </button>

    <button class="toggle-btn" onclick={toggleDrawer} title="Toggle Action Panel" class:active={showActionPanel}>
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
    </button>
  </div>

  {#if showBookmarks}
    <div class="bookmarks-bar aux-bar">
      {#if bookmarks.length === 0}
        <span class="bm-empty">No bookmarks yet — ☆ a page to save it.</span>
      {:else}
        {#each bookmarks as b (b.id)}
          <div class="bm-chip">
            <button class="bm-open" onclick={() => openBookmark(b.url)} title={b.url}>{b.title}</button>
            <button class="bm-x" onclick={() => removeBookmark(b.id)} title="Remove">×</button>
          </div>
        {/each}
      {/if}
    </div>
  {/if}

  {#if showPasswords}
    <div class="passwords-bar aux-bar">
      <button class="pw-save" onclick={handleLoginClick} title="Fill the saved login for this page and submit it">
        🔑 Log in now
      </button>
      <button class="pw-save" onclick={saveCurrentLogin} title="Read the username/password from this page and save it">
        Save this login
      </button>
      <label class="pw-toggle" title="After autofilling a saved login, submit the form automatically">
        <input type="checkbox" checked={autoSubmit} onchange={toggleAutoSubmit} /> Auto-submit
      </label>
      {#if credentials.length === 0}
        <span class="bm-empty">No saved logins yet — fill a login form, then “Save this login”.</span>
      {:else}
        {#each credentials as c (c.id)}
          <div class="pw-chip">
            <span class="pw-site" title={c.url_pattern}>{c.site_name}</span>
            <span class="pw-user">{c.username}</span>
            <span class="pw-pass">{revealed.has(c.id) ? c.password : '••••••••'}</span>
            <button class="pw-eye" onclick={() => toggleReveal(c.id)} title={revealed.has(c.id) ? 'Hide' : 'Show'}>
              {revealed.has(c.id) ? '🙈' : '👁'}
            </button>
          </div>
        {/each}
      {/if}
    </div>
  {/if}

  {#if pendingLogin}
    <div class="login-prompt aux-bar">
      <span class="lp-text">🔑 Save login for <b>{pendingLogin.host}</b>{pendingLogin.username ? ` — ${pendingLogin.username}` : ''}?</span>
      <button class="pw-save" onclick={savePendingLogin}>Save login</button>
      <button class="lp-no" onclick={clearPrompt}>Not now</button>
    </div>
  {/if}

  <div class="browser-content">
    <div class="webview-area">
      {#if !browserCreated}
      <div class="placeholder-text">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"/><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"/><line x1="14.83" y1="9.17" x2="19.07" y2="4.93"/><line x1="14.83" y1="9.17" x2="18.36" y2="5.64"/><line x1="4.93" y1="19.07" x2="9.17" y2="14.83"/></svg>
        <p>Embedded Browser Area</p>
        <p class="sub">Enter a URL above to get started</p>
      </div>
      {/if}
    </div>

    {#if showActionPanel}
      <!-- One panel instance per tab so each tab keeps its own chat/discovery/skills state.
           Inactive panels stay mounted but hidden (display:none) — state survives tab switches. -->
      {#each tabs as tab (tab.id)}
        <div class="panel-slot" style:display={tab.id === panelTabId ? 'contents' : 'none'}>
          <ActionPanel
            bind:isCollapsed={actionPanelCollapsed}
            bind:width={actionPanelWidth}
            pageUrl={tab.url}
            tabId={tab.id}
            active={tab.id === activeTabId}
          />
        </div>
      {/each}
    {/if}
  </div>

  {#if toastMessage}
    <div class="toast" role="status">{toastMessage}</div>
  {/if}
</div>

<style>
  /* Top-center so it sits over the HTML toolbar, not behind the native embedded webview. */
  .toast {
    position: fixed;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    background: var(--bg-card);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 0.85rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    pointer-events: none;
  }
  .browser-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: var(--bg-primary);
    overflow: hidden;
  }

  .browser-content {
    display: flex;
    flex: 1;
    overflow: hidden;
    position: relative;
  }

  .nav-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--bg-card);
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }

  .nav-controls {
    display: flex;
    gap: 0.25rem;
  }

  .icon-btn {
    background: transparent;
    border: none;
    color: var(--text-primary);
    padding: 0.4rem;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }

  .icon-btn:hover {
    background: var(--bg-hover);
  }

  .icon-btn:disabled { opacity: 0.4; cursor: not-allowed; }
  .icon-btn.active { color: var(--color-primary); }

  /* Bookmarks bar — a horizontal strip below the toolbar; the webview is pushed down to fit it. */
  .bookmarks-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    overflow-x: auto;
    background: var(--bg-sidebar, var(--bg-input));
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    white-space: nowrap;
  }
  .bm-empty { font-size: 0.8rem; color: var(--text-secondary); padding: 2px 4px; }
  .bm-chip {
    display: inline-flex; align-items: center; flex-shrink: 0;
    background: var(--bg-card, var(--bg-input));
    border: 1px solid var(--border-color);
    border-radius: 6px;
    max-width: 220px;
  }
  .bm-open {
    background: transparent; border: none; color: var(--text-primary);
    padding: 4px 8px; cursor: pointer; font-size: 0.8rem;
    max-width: 190px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .bm-open:hover { color: var(--color-primary); }
  .bm-x {
    background: transparent; border: none; color: var(--text-secondary);
    cursor: pointer; padding: 4px 6px; border-radius: 0 6px 6px 0; flex-shrink: 0; font-size: 0.9rem;
  }
  .bm-x:hover { background: var(--color-danger-bg); color: var(--color-danger); }

  /* Passwords bar — same strip pattern as bookmarks. */
  .passwords-bar {
    display: flex; align-items: center; gap: 6px;
    padding: 4px 8px; overflow-x: auto;
    background: var(--bg-sidebar, var(--bg-input));
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0; white-space: nowrap;
  }
  .pw-save {
    flex-shrink: 0; background: transparent; border: 1px solid var(--color-primary);
    color: var(--color-primary); border-radius: 6px; padding: 4px 10px;
    cursor: pointer; font-size: 0.8rem;
  }
  .pw-save:hover { background: var(--color-primary-bg); }
  .pw-toggle { display: inline-flex; align-items: center; gap: 4px; flex-shrink: 0; font-size: 0.78rem; color: var(--text-secondary); cursor: pointer; }
  .pw-toggle input { cursor: pointer; }
  .pw-chip {
    display: inline-flex; align-items: center; gap: 8px; flex-shrink: 0;
    background: var(--bg-card, var(--bg-input)); border: 1px solid var(--border-color);
    border-radius: 6px; padding: 4px 8px; font-size: 0.8rem;
  }
  .pw-site { color: var(--text-primary); font-weight: 600; }
  .pw-user { color: var(--text-secondary); }
  .pw-pass { color: var(--text-primary); font-family: var(--font-family-mono, monospace); }
  .pw-eye { background: transparent; border: none; cursor: pointer; padding: 0 2px; font-size: 0.9rem; }

  /* Auto-login save prompt — appears after a login submit is detected. */
  .login-prompt {
    display: flex; align-items: center; gap: 10px;
    padding: 6px 10px; flex-shrink: 0;
    background: var(--bg-active, rgba(59,130,246,0.12));
    border-bottom: 1px solid var(--border-color);
  }
  .lp-text { font-size: 0.85rem; color: var(--text-primary); }
  .lp-no {
    background: transparent; border: none; color: var(--text-secondary);
    cursor: pointer; font-size: 0.8rem; padding: 4px 8px; border-radius: 6px;
  }
  .lp-no:hover { background: var(--bg-hover); color: var(--text-primary); }

  .url-input-container {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
  }

  input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    padding-right: 2rem;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background-color: var(--bg-input);
    color: var(--text-primary);
    font-size: 0.9rem;
    transition: all 0.2s;
  }

  input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px var(--color-primary-bg);
  }

  .spinner {
    position: absolute;
    right: 0.75rem;
    width: 14px;
    height: 14px;
    border: 2px solid var(--border-color);
    border-top-color: var(--color-primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }

  .toggle-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.4rem;
    border-radius: 4px;
  }

  .toggle-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
  }

  .toggle-btn.active {
    background: var(--bg-active);
    color: var(--color-primary);
  }

  .webview-area {
    flex: 1;
    background: var(--bg-tertiary);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .placeholder-text {
    text-align: center;
    color: var(--text-tertiary);
    opacity: 0.5;
  }

  .placeholder-text svg {
    margin-bottom: 1rem;
    color: var(--text-secondary);
  }

  .placeholder-text p {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 500;
  }

  .placeholder-text .sub {
    font-size: 0.9rem;
    margin-top: 0.25rem;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .tab-bar {
    display: flex;
    align-items: flex-end;
    gap: 2px;
    padding: 0.25rem 0.5rem 0;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    overflow-x: auto;
    flex-shrink: 0;
  }

  .tab {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.3rem 0.75rem;
    border: 1px solid transparent;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
    background: transparent;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 0.8rem;
    max-width: 160px;
    min-width: 80px;
    white-space: nowrap;
    overflow: hidden;
    transition: background 0.15s;
  }

  .tab:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
  }

  .tab.active {
    background: var(--bg-card);
    border-color: var(--border-color);
    color: var(--text-primary);
  }

  /* A tab an agent is currently driving — glow + pulsing dot in the SESSION's colour (--agent-color),
     matching that session's in-page overlay so you can tell concurrent sessions apart. */
  .tab.agent-driving {
    border-color: var(--agent-color, #34d399);
    box-shadow: 0 0 0 1px var(--agent-color, #34d399), 0 0 8px var(--agent-color, #34d399);
  }
  /* Every tab in a session: a top accent + faint tint in the session colour, so the whole session
     reads as one coloured group. The driving tab additionally gets the stronger glow + ● dot above. */
  .tab.agent-group {
    border-top: 2px solid var(--agent-color, #34d399);
    background: color-mix(in srgb, var(--agent-color, #34d399) 8%, transparent);
  }
  .agent-dot {
    color: var(--agent-color, #10b981);
    font-size: 0.7rem;
    animation: agentPulse 1.1s ease-in-out infinite;
  }
  @keyframes agentPulse { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

  .tab-title {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .tab-close {
    background: none;
    border: none;
    color: var(--text-tertiary);
    cursor: pointer;
    padding: 0 2px;
    font-size: 1rem;
    line-height: 1;
    border-radius: 3px;
    flex-shrink: 0;
  }

  .tab-close:hover {
    background: var(--color-danger);
    color: var(--color-primary-text);
  }

  .tab-new {
    background: transparent;
    border: 1px solid transparent;
    color: var(--text-tertiary);
    cursor: pointer;
    padding: 0.3rem 0.5rem;
    border-radius: 4px 4px 0 0;
    font-size: 1rem;
    line-height: 1;
    flex-shrink: 0;
  }

  .tab-new:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
  }
</style>
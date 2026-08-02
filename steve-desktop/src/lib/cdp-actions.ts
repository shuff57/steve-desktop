import { invoke } from '@tauri-apps/api/core';
import { cdp, CDPClient, type CDPTarget } from './cdp-client';
import { selectorToElementExpr } from './selector-resolve';
import { getActiveTabId } from './browser';
import { tabMarker } from './tab-control';

export interface ActionResult {
  success: boolean;
  data?: unknown;
  error?: string;
}

const DANGEROUS_JS_PATTERNS = [
  'localStorage',
  'sessionStorage',
  'document.cookie',
  'window.location=',
  'window.location.href=',
  'fetch(',
  'XMLHttpRequest',
  'WebSocket(',
];

function checkDangerousPatterns(code: string): string | null {
  for (const pattern of DANGEROUS_JS_PATTERNS) {
    if (code.includes(pattern)) return pattern;
  }
  return null;
}

// Which tab the singleton `cdp` is connected to. The old behaviour ("first non-app-UI target,
// connect once, reuse forever") silently kept acting on whichever tab connected first after a tab
// switch — with several tabs (or several agents) open, in-app tools read/wrote the wrong tab.
let connectedTabId: string | null = null;

/**
 * The CDP `/json` target list.
 *
 * Goes through Rust because the DevTools HTTP endpoint sends no CORS headers, so
 * a `fetch` from the app UI throws "Failed to fetch" — which silently disabled
 * marker-pinning entirely: the probe below always came back empty-handed and
 * every connect quietly fell back to first-found discovery. Falls back to fetch
 * outside the app (tests, or a driver running in its own process), where the
 * request is same-origin-exempt and works.
 */
async function listCdpTargets(port: number): Promise<CDPTarget[]> {
  try {
    const raw = await invoke<string>('cdp_list_targets', { port });
    return JSON.parse(raw) as CDPTarget[];
  } catch {
    try {
      const resp = await fetch(`http://127.0.0.1:${port}/json`);
      return resp.ok ? ((await resp.json()) as CDPTarget[]) : [];
    } catch {
      return [];
    }
  }
}

/** ws url of the page target stamped with `marker` (window.name) — the only reliable disambiguator
 *  when several tabs share a URL. Probes each candidate over a throwaway ws connection. Retries
 *  briefly: the marker is re-stamped by the page-loaded handler, so a capture racing a navigation
 *  can probe before the stamp lands — one miss must not silently divert to the wrong tab. */
async function findTargetWsByMarker(port: number, marker: string, attempts = 3): Promise<string | null> {
  for (let i = 0; i < attempts; i++) {
    if (i > 0) await new Promise((r) => setTimeout(r, 500));
    try {
      const targets: CDPTarget[] = await listCdpTargets(port);
      if (!targets.length) continue;
      for (const t of targets) {
        if (t.type !== 'page' || !t.webSocketDebuggerUrl) continue;
        // No URL filter here on purpose. This probe matches an exact
        // `steve-tab-<uuid>` marker, which the app's own UI never carries, so a
        // false positive is impossible — whereas skipping every loopback origin
        // made a locally-served page undrivable, since MAIN_APP_PATTERNS treats
        // all of 127.0.0.1 / localhost as "this is the app". The URL filter still
        // guards the no-marker fallback, which resolves in Rust
        // (discover_cdp_target) and still refuses loopback there.
        const probe = new CDPClient();
        try {
          if (!(await probe.connectToUrl(t.webSocketDebuggerUrl))) continue;
          const res = (await probe.send('Runtime.evaluate', {
            expression: 'window.name',
            returnByValue: true,
          })) as { result?: { value?: unknown } };
          if (res.result?.value === marker) return t.webSocketDebuggerUrl;
        } catch {
          /* unreadable target — skip */
        } finally {
          await probe.disconnect();
        }
      }
    } catch {
      /* endpoint unreachable — retry, then caller falls back */
    }
  }
  return null;
}

/**
 * Connect the singleton client to the tab's page target — `tabId` explicit, else the active tab.
 * Already connected to that tab → no-op true. Connected to a DIFFERENT tab → re-targets, so a call
 * after a tab switch acts on the tab the user/agent means. Falls back to first-found discovery when
 * the tab has no marker yet (fresh tab before its first page load) or no tab context exists.
 */
export async function connectCDP(port?: number, tabId?: string): Promise<boolean> {
  try {
    const desired = tabId ?? getActiveTabId();
    if (cdp.isConnected() && (!desired || connectedTabId === desired)) return true;

    let resolvedPort = port;
    if (resolvedPort === undefined || resolvedPort === null) {
      const tauriPort = await invoke<number | null>('get_cdp_port');
      if (tauriPort === null || tauriPort === undefined) return false;
      resolvedPort = tauriPort;
    }

    const marked: string | null = desired ? await findTargetWsByMarker(resolvedPort, tabMarker(desired)) : null;
    const wsUrl = marked ?? (await invoke<string | null>('discover_cdp_target', { port: resolvedPort }));
    if (!wsUrl) return false;

    const ok = await cdp.connectToUrl(wsUrl);
    // Only a marker-verified connection may claim the tab. Caching a fallback (first-found)
    // connection as `desired` once glued a whole crawl to the wrong tab: every later call saw
    // "already connected to that tab" and read one stale page 32 times. A fallback connection
    // stays unclaimed so the next call re-probes for the real target.
    connectedTabId = ok && marked ? desired : null;
    return ok;
  } catch {
    return false;
  }
}

export async function connect(port?: number): Promise<boolean> {
  return await connectCDP(port);
}

export async function disconnectCDP(): Promise<void> {
  connectedTabId = null;
  await cdp.disconnect();
}

export async function disconnect(): Promise<void> {
  await disconnectCDP();
}

export async function send(method: string, params?: Record<string, unknown>): Promise<unknown> {
  return await cdp.send(method, params);
}

export function isConnected(): boolean {
  return cdp.isConnected();
}

export async function evalScript(script: string): Promise<ActionResult> {
  try {
    if (!cdp.isConnected()) return { success: false, error: 'Not connected to CDP' };
    const blocked = checkDangerousPatterns(script);
    if (blocked) return { success: false, error: `Blocked dangerous pattern: ${blocked}` };

    const result = (await cdp.send('Runtime.evaluate', {
      expression: script,
      returnByValue: true,
    })) as { result?: { value?: unknown } };

    return { success: true, data: result.result?.value };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function injectScript(script: string): Promise<ActionResult> {
  try {
    const blocked = checkDangerousPatterns(script);
    if (blocked) return { success: false, error: `Blocked dangerous pattern: ${blocked}` };

    await invoke('inject_webview_script', { script });
    return { success: true };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function captureWebviewScreenshot(): Promise<ActionResult> {
  try {
    const base64 = await invoke<string>('capture_webview_screenshot');
    return { success: true, data: base64 };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function cdpScreenshot(): Promise<string> {
  const result = (await cdp.send('Page.captureScreenshot', {
    format: 'jpeg',
    quality: 80,
  })) as { data: string };

  return 'data:image/jpeg;base64,' + result.data;
}

export async function pwClick(selector: string): Promise<ActionResult> {
  // Resolves CSS, role=name, and xpath selectors (selector-resolve.ts).
  const el = selectorToElementExpr(selector);
  const expression = `(function(){var el=${el}; if(!el) return false; el.click(); return true;})()`;
  const result = await evalScript(expression);
  if (result.success && result.data !== true) {
    return { success: false, error: `Element not found: ${selector}` };
  }
  return result;
}

export async function pwType(selector: string, text: string, clear?: boolean): Promise<ActionResult> {
  try {
    if (!cdp.isConnected()) return { success: false, error: 'Not connected to CDP' };

    const el = selectorToElementExpr(selector);
    const expression = clear
      ? `(function(){var el=${el};if(!el) return false; el.focus(); el.value=''; el.dispatchEvent(new Event('input',{bubbles:true})); return true;})()`
      : `(function(){var el=${el}; if(!el) return false; el.focus(); return true;})()`;

    const blocked = checkDangerousPatterns(expression);
    if (blocked) return { success: false, error: `Blocked dangerous pattern: ${blocked}` };

    const focused = (await cdp.send('Runtime.evaluate', {
      expression,
      returnByValue: true,
    })) as { result?: { value?: boolean } };

    if (!focused.result?.value) return { success: false, error: `Element not found: ${selector}` };

    await cdp.send('Input.insertText', { text });

    return { success: true };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

/**
 * Deep-capture settle, run BEFORE any DOM/AX snapshot. Modern JS index pages (React/Vue lists)
 * render their real content only once scrolled into view — a Canvas assignments page collapsed
 * via CSS measured 0 links on a plain snapshot and 33-39 after this. The items are already in
 * the DOM; scrolling is what triggers the lazy-load, not clicking. Repeatedly scroll to the
 * bottom, letting each scroll's lazy content settle, until the link count stops growing or the
 * scroll ceiling is hit, then return to the top. Never throws — a stuck settle must not abort
 * the capture it exists to help.
 */
export async function deepCapture(maxScrolls = 8, settleMs = 600): Promise<void> {
  const linkCount = async (): Promise<number> => {
    const res = await evalScript('document.querySelectorAll("a[href]").length').catch(() => null);
    return Number(res?.data) || 0;
  };

  // Wait for the initial load — async frameworks keep painting well past DOMContentLoaded.
  const started = Date.now();
  while (Date.now() - started < 5000) {
    const res = await evalScript('document.readyState').catch(() => null);
    if (res?.data === 'complete') break;
    await new Promise((r) => setTimeout(r, 150));
  }

  let last = await linkCount();
  for (let i = 0; i < maxScrolls; i++) {
    await evalScript('window.scrollTo(0, (document.body && document.body.scrollHeight) || 0)').catch(() => undefined);
    await new Promise((r) => setTimeout(r, settleMs));
    const count = await linkCount();
    if (count === last) break; // stabilized — that scroll surfaced nothing new
    last = count;
  }
  await evalScript('window.scrollTo(0, 0)').catch(() => undefined);
}

export async function pwGetText(selector?: string): Promise<ActionResult> {
  const expression = selector
    ? `(${selectorToElementExpr(selector)})?.textContent ?? ''`
    : `(document.body.innerText || '')`;
  return await evalScript(expression);
}

import { invoke } from '@tauri-apps/api/core';
import { listen } from '@tauri-apps/api/event';

let activeTabId = '';

export function setActiveTabId(id: string): void {
  activeTabId = id;
}

export function getActiveTabId(): string {
  return activeTabId;
}

function normalizeUrl(url: string): string {
  const normalized = url.trim();
  if (!normalized.startsWith('http://') && !normalized.startsWith('https://')) {
    return `https://${normalized}`;
  }
  return normalized;
}

export async function createEmbeddedBrowser(tabId: string, url: string, offscreen = false): Promise<void> {
  // offscreen: transient/background tabs render (and register as CDP targets) at x=-4000 so
  // they never flash over the UI.
  await invoke('create_embedded_browser', { tabId, url: normalizeUrl(url), offscreen });
}

export async function navigateEmbedded(tabId: string, url: string): Promise<void> {
  await invoke('navigate_embedded', { tabId, url: normalizeUrl(url) });
}

export async function goBack(tabId: string): Promise<void> {
  await invoke('go_back', { tabId });
}

export async function goForward(tabId: string): Promise<void> {
  await invoke('go_forward', { tabId });
}

export async function reloadBrowser(tabId: string): Promise<void> {
  await invoke('reload_browser', { tabId });
}

export async function setWebviewBounds(
  tabId: string,
  x: number,
  y: number,
  width: number,
  height: number,
): Promise<void> {
  await invoke('set_webview_bounds', { tabId, x, y, width, height });
}

export async function injectScript(script: string, tabId?: string): Promise<void> {
  await invoke('inject_webview_script', { tabId: tabId ?? activeTabId, script });
}

function escapeJsSingleQuoted(value: string): string {
  return value
    .replaceAll('\\', '\\\\')
    .replaceAll("'", "\\'")
    .replaceAll('\n', '\\n')
    .replaceAll('\r', '\\r');
}

function generateAutofillScript(username: string, password: string): string {
  const u = escapeJsSingleQuoted(username);
  const p = escapeJsSingleQuoted(password);
  return `(function(){const u='${u}';const p='${p}';window.__STEVE_AUTOFILL__={username:u,password:p};})();`;
}

export async function injectAutofill(tabId: string, username: string, password: string): Promise<void> {
  const script = generateAutofillScript(username, password);
  await invoke('inject_autofill', { tabId, script });
}

export async function getEmbeddedUrl(tabId?: string): Promise<string> {
  const id = tabId ?? activeTabId;
  try {
    return await invoke('get_embedded_url', { tabId: id });
  } catch (e) {
    // Under a transient main-thread CPU-starvation spike, Tauri's wv.url() fails with
    // "failed to receive" (the marshaling channel times out). The main thread usually recovers in
    // 1–2s, so retry once after a short delay before surfacing the error.
    if (String(e).includes('failed to receive')) {
      await new Promise((r) => setTimeout(r, 400));
      return await invoke('get_embedded_url', { tabId: id });
    }
    throw e;
  }
}

export async function hideWebview(tabId: string): Promise<void> {
  await invoke('hide_webview', { tabId });
}

export async function showWebview(tabId: string): Promise<void> {
  await invoke('show_webview', { tabId });
}

export async function destroyWebview(tabId: string): Promise<void> {
  await invoke('destroy_webview', { tabId });
}

/**
 * Runs `script` in the active tab's embedded webview and returns the JSON text of its
 * result (the Rust side JSON.stringify's it — see eval_webview_script in lib.rs). A
 * script returning true yields "true"; one returning a string yields a quoted "…".
 */
export async function evalScript(script: string): Promise<string> {
  const tabId = getActiveTabId();
  if (!tabId) throw new Error('No active browser tab to evaluate script in');
  return await invoke('eval_webview_script', { tabId, script });
}

/** Run `script` in a specific tab's embedded webview and return the JSON text of its result.
 *  The reliable bridge to an embedded tab (which is NOT a CDP target on this platform). */
export async function evalScriptInTab(tabId: string, script: string): Promise<string> {
  if (!tabId) throw new Error('No tab id to evaluate script in');
  return await invoke('eval_webview_script', { tabId, script });
}

export async function captureWebviewScreenshot(): Promise<string> {
  return await invoke('capture_webview_screenshot');
}

export type BrowserEventPayload = { tabId: string; url: string };

export async function listenBrowserUrlChanged(
  callback: (payload: BrowserEventPayload) => void,
): Promise<() => void> {
  return await listen<BrowserEventPayload>('browser-url-changed', (event) => {
    callback(event.payload);
  });
}

export async function listenBrowserPageLoaded(
  callback: (payload: BrowserEventPayload) => void,
): Promise<() => void> {
  return await listen<BrowserEventPayload>('browser-page-loaded', (event) => {
    callback(event.payload);
  });
}

export async function listenBrowserStatus(callback: (status: string) => void): Promise<() => void> {
  return await listen<string>('browser-status', (event) => {
    callback(event.payload);
  });
}

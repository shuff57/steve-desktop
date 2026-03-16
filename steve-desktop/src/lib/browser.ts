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

export async function createEmbeddedBrowser(tabId: string, url: string): Promise<void> {
  await invoke('create_embedded_browser', { tabId, url: normalizeUrl(url) });
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
  return await invoke('get_embedded_url', { tabId: tabId ?? activeTabId });
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

export async function evalScript(script: string): Promise<string> {
  return await invoke('eval_webview_script', { script });
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

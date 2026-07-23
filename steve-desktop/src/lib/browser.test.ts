import { beforeEach, describe, expect, it, vi } from 'vitest';

const { invokeMock, listenMock } = vi.hoisted(() => ({ invokeMock: vi.fn(), listenMock: vi.fn() }));

vi.mock('@tauri-apps/api/core', () => ({
  invoke: invokeMock,
}));

vi.mock('@tauri-apps/api/event', () => ({
  listen: listenMock,
}));

import {
  createEmbeddedBrowser,
  navigateEmbedded,
  setWebviewBounds,
  injectScript,
  injectAutofill,
  getEmbeddedUrl,
  hideWebview,
  showWebview,
  destroyWebview,
  evalScript,
  captureWebviewScreenshot,
  listenBrowserUrlChanged,
  listenBrowserPageLoaded,
  listenBrowserStatus,
  setActiveTabId,
} from './browser';

describe('browser API', () => {
  beforeEach(() => {
    invokeMock.mockReset();
    listenMock.mockReset();
    invokeMock.mockResolvedValue(undefined);
    listenMock.mockResolvedValue(() => {});
    setActiveTabId('');
  });

  it('createEmbeddedBrowser normalizes URL and invokes tauri command', async () => {
    await createEmbeddedBrowser('tab-1', ' example.com ');
    expect(invokeMock).toHaveBeenCalledWith('create_embedded_browser', {
      tabId: 'tab-1',
      url: 'https://example.com',
      offscreen: false,
    });
  });

  it('navigateEmbedded invokes navigate command', async () => {
    await navigateEmbedded('tab-2', 'https://example.com/path');
    expect(invokeMock).toHaveBeenCalledWith('navigate_embedded', {
      tabId: 'tab-2',
      url: 'https://example.com/path',
    });
  });

  it('setWebviewBounds passes bounds object fields', async () => {
    await setWebviewBounds('tab-3', 10, 20, 300, 400);
    expect(invokeMock).toHaveBeenCalledWith('set_webview_bounds', {
      tabId: 'tab-3',
      x: 10,
      y: 20,
      width: 300,
      height: 400,
    });
  });

  it('injectScript calls tauri with active tab fallback', async () => {
    setActiveTabId('tab-active');
    await injectScript('window.test = 1;');
    expect(invokeMock).toHaveBeenCalledWith('inject_webview_script', {
      tabId: 'tab-active',
      script: 'window.test = 1;',
    });
  });

  it('injectAutofill passes a script to tauri', async () => {
    await injectAutofill('tab-4', 'user', 'pass');

    expect(invokeMock).toHaveBeenCalledWith('inject_autofill', {
      tabId: 'tab-4',
      script: expect.any(String),
    });

    const payload = invokeMock.mock.calls[0][1] as { script: string };
    expect(payload.script).toContain('user');
    expect(payload.script).toContain('pass');
  });

  it('getEmbeddedUrl uses provided tab id', async () => {
    invokeMock.mockResolvedValue('https://example.com');
    const url = await getEmbeddedUrl('tab-5');
    expect(url).toBe('https://example.com');
    expect(invokeMock).toHaveBeenCalledWith('get_embedded_url', { tabId: 'tab-5' });
  });

  it('getEmbeddedUrl falls back to active tab id', async () => {
    setActiveTabId('tab-active');
    invokeMock.mockResolvedValue('https://active.example.com');

    const url = await getEmbeddedUrl();
    expect(url).toBe('https://active.example.com');
    expect(invokeMock).toHaveBeenCalledWith('get_embedded_url', { tabId: 'tab-active' });
  });

  it('hide/show/destroy map to tauri commands', async () => {
    await hideWebview('tab-6');
    await showWebview('tab-6');
    await destroyWebview('tab-6');

    expect(invokeMock).toHaveBeenNthCalledWith(1, 'hide_webview', { tabId: 'tab-6' });
    expect(invokeMock).toHaveBeenNthCalledWith(2, 'show_webview', { tabId: 'tab-6' });
    expect(invokeMock).toHaveBeenNthCalledWith(3, 'destroy_webview', { tabId: 'tab-6' });
  });

  it('evalScript invokes eval_webview_script against the active tab', async () => {
    setActiveTabId('tab-active');
    invokeMock.mockResolvedValue('{"ok":true}');
    const result = await evalScript('JSON.stringify({ ok: true })');
    expect(result).toBe('{"ok":true}');
    expect(invokeMock).toHaveBeenCalledWith('eval_webview_script', {
      tabId: 'tab-active',
      script: 'JSON.stringify({ ok: true })',
    });
  });

  it('evalScript refuses to run with no active tab', async () => {
    setActiveTabId('');
    await expect(evalScript('1+1')).rejects.toThrow('No active browser tab');
  });

  it('captureWebviewScreenshot invokes tauri command', async () => {
    invokeMock.mockResolvedValue('data:image/png;base64,abc');
    const shot = await captureWebviewScreenshot();
    expect(shot).toBe('data:image/png;base64,abc');
    expect(invokeMock).toHaveBeenCalledWith('capture_webview_screenshot');
  });

  it('listenBrowserUrlChanged returns unlisten and forwards payload', async () => {
    const unlisten = vi.fn();
    listenMock.mockResolvedValue(unlisten);
    const callback = vi.fn();

    const returned = await listenBrowserUrlChanged(callback);

    expect(listenMock).toHaveBeenCalledWith('browser-url-changed', expect.any(Function));
    expect(returned).toBe(unlisten);

    const handler = listenMock.mock.calls[0][1];
    handler({ payload: { tabId: 'tab-1', url: 'https://example.com' } });
    expect(callback).toHaveBeenCalledWith({ tabId: 'tab-1', url: 'https://example.com' });
  });

  it('listenBrowserPageLoaded returns unlisten and forwards payload', async () => {
    const unlisten = vi.fn();
    listenMock.mockResolvedValue(unlisten);
    const callback = vi.fn();

    const returned = await listenBrowserPageLoaded(callback);

    expect(listenMock).toHaveBeenCalledWith('browser-page-loaded', expect.any(Function));
    expect(returned).toBe(unlisten);

    const handler = listenMock.mock.calls[0][1];
    handler({ payload: { tabId: 'tab-2', url: 'https://loaded.example.com' } });
    expect(callback).toHaveBeenCalledWith({ tabId: 'tab-2', url: 'https://loaded.example.com' });
  });

  it('listenBrowserStatus returns unlisten and forwards status', async () => {
    const unlisten = vi.fn();
    listenMock.mockResolvedValue(unlisten);
    const callback = vi.fn();

    const returned = await listenBrowserStatus(callback);

    expect(listenMock).toHaveBeenCalledWith('browser-status', expect.any(Function));
    expect(returned).toBe(unlisten);

    const handler = listenMock.mock.calls[0][1];
    handler({ payload: 'embedded-open' });
    expect(callback).toHaveBeenCalledWith('embedded-open');
  });
});

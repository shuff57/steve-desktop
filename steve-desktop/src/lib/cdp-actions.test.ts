import { beforeEach, describe, expect, test, vi } from 'vitest';

vi.mock('@tauri-apps/api/core', () => ({
  invoke: vi.fn(),
}));

vi.mock('./cdp-client', () => ({
  cdp: {
    connectToUrl: vi.fn(),
    disconnect: vi.fn(),
    isConnected: vi.fn(),
    send: vi.fn(),
  },
}));

import { invoke } from '@tauri-apps/api/core';
import { cdp } from './cdp-client';
import { setActiveTabId } from './browser';
import {
  captureWebviewScreenshot,
  connectCDP,
  evalScript,
  injectScript,
} from './cdp-actions';

const mockInvoke = invoke as ReturnType<typeof vi.fn>;
const mockCdp = cdp as unknown as {
  connectToUrl: ReturnType<typeof vi.fn>;
  disconnect: ReturnType<typeof vi.fn>;
  isConnected: ReturnType<typeof vi.fn>;
  send: ReturnType<typeof vi.fn>;
};

describe('cdp-actions', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  test('connectCDP() calls invoke with discovery args and connects by ws url', async () => {
    mockInvoke.mockResolvedValueOnce(9222).mockResolvedValueOnce('ws://127.0.0.1:9222/devtools/page/abc');
    mockCdp.connectToUrl.mockResolvedValueOnce(true);

    const result = await connectCDP();

    expect(result).toBe(true);
    expect(mockInvoke).toHaveBeenCalledWith('get_cdp_port');
    expect(mockInvoke).toHaveBeenCalledWith('discover_cdp_target', { port: 9222 });
    expect(mockCdp.connectToUrl).toHaveBeenCalledWith('ws://127.0.0.1:9222/devtools/page/abc');
  });

  test('evalScript() sends Runtime.evaluate CDP command', async () => {
    mockCdp.isConnected.mockReturnValueOnce(true);
    mockCdp.send.mockResolvedValueOnce({ result: { value: 42 } });

    const result = await evalScript('6 * 7');

    expect(result.success).toBe(true);
    expect(mockCdp.send).toHaveBeenCalledWith('Runtime.evaluate', {
      expression: '6 * 7',
      returnByValue: true,
    });
    expect(result.data).toBe(42);
  });

  test('injectScript() uses inject_webview_script IPC', async () => {
    mockInvoke.mockResolvedValueOnce(true);

    const result = await injectScript('window.__steve = true;');

    expect(result.success).toBe(true);
    expect(mockInvoke).toHaveBeenCalledWith('inject_webview_script', {
      script: 'window.__steve = true;',
    });
  });

  test('captureWebviewScreenshot() returns base64 image data', async () => {
    mockInvoke.mockResolvedValueOnce('ZmFrZS1iYXNlNjQ=');

    const result = await captureWebviewScreenshot();

    expect(result.success).toBe(true);
    expect(result.data).toBe('ZmFrZS1iYXNlNjQ=');
    expect(mockInvoke).toHaveBeenCalledWith('capture_webview_screenshot');
  });

  describe('per-tab targeting', () => {
    test('connectCDP() is a no-op when already connected with no tab context', async () => {
      setActiveTabId('');
      mockCdp.isConnected.mockReturnValueOnce(true);

      expect(await connectCDP()).toBe(true);
      expect(mockInvoke).not.toHaveBeenCalled();
      expect(mockCdp.connectToUrl).not.toHaveBeenCalled();
    });

    test('connectCDP() re-targets when the active tab is not the connected one', async () => {
      setActiveTabId('tab-9');
      mockCdp.isConnected.mockReturnValueOnce(true); // connected — but to some other tab
      // Marker probe finds nothing → falls back to first-found discovery, but still reconnects.
      // Routed by command name rather than call order: the probe's target listing is its own
      // invoke, and order-coupled mocks broke the moment one was added.
      vi.stubGlobal('fetch', vi.fn().mockRejectedValue(new Error('endpoint down')));
      mockInvoke.mockImplementation(async (cmd: string) =>
        cmd === 'get_cdp_port' ? 9222
        : cmd === 'cdp_list_targets' ? '[]'
        : cmd === 'discover_cdp_target' ? 'ws://127.0.0.1:9222/devtools/page/t9'
        : undefined);
      mockCdp.connectToUrl.mockResolvedValueOnce(true);

      expect(await connectCDP()).toBe(true);
      expect(mockInvoke).toHaveBeenCalledWith('discover_cdp_target', { port: 9222 });
      expect(mockCdp.connectToUrl).toHaveBeenCalledWith('ws://127.0.0.1:9222/devtools/page/t9');

      vi.unstubAllGlobals();
      setActiveTabId('');
    });

    test('a fallback (unverified) connection is never cached as the tab — next call re-probes', async () => {
      // The previous test connected to tab-9 via FALLBACK discovery (marker probe unreachable).
      // Had that been cached as "connected to tab-9", this call would early-return and a whole
      // crawl could stay glued to the wrong tab. It must attempt a fresh connection instead.
      setActiveTabId('tab-9');
      mockCdp.isConnected.mockReturnValueOnce(true);
      vi.stubGlobal('fetch', vi.fn().mockRejectedValue(new Error('still down')));
      mockInvoke.mockImplementation(async (cmd: string) =>
        cmd === 'get_cdp_port' ? 9222
        : cmd === 'cdp_list_targets' ? '[]'
        : cmd === 'discover_cdp_target' ? 'ws://127.0.0.1:9222/devtools/page/t9'
        : undefined);
      mockCdp.connectToUrl.mockResolvedValueOnce(true);

      expect(await connectCDP()).toBe(true);
      expect(mockInvoke).toHaveBeenCalledWith('discover_cdp_target', { port: 9222 }); // re-probed, no early return

      vi.unstubAllGlobals();
      setActiveTabId('');
    });
  });
});

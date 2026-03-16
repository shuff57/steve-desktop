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
});

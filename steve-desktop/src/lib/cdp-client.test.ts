import { beforeEach, describe, expect, test, vi } from 'vitest';
import { CDPClient, MAIN_APP_PATTERNS } from './cdp-client';

const originalWebSocket = globalThis.WebSocket;
const originalFetch = globalThis.fetch;

class MockWebSocket {
  static readonly OPEN = 1;
  static readonly CLOSED = 3;
  static instances: MockWebSocket[] = [];

  onopen: ((event: Event) => void) | null = null;
  onclose: ((event: CloseEvent) => void) | null = null;
  onerror: ((event: Event) => void) | null = null;
  onmessage: ((event: MessageEvent) => void) | null = null;
  readyState = MockWebSocket.OPEN;
  sent: string[] = [];
  closed = false;

  constructor(public readonly url: string) {
    MockWebSocket.instances.push(this);
    queueMicrotask(() => {
      this.onopen?.({} as Event);
    });
  }

  send(message: string) {
    this.sent.push(message);
    const parsed = JSON.parse(message) as { id?: number; method?: string };
    if (parsed.method === 'Page.enable' && parsed.id !== undefined) {
      queueMicrotask(() => {
        this.emitMessage({ id: parsed.id, result: {} });
      });
    }
  }

  close() {
    this.closed = true;
    this.readyState = MockWebSocket.CLOSED;
    this.onclose?.({} as CloseEvent);
  }

  emitMessage(payload: unknown) {
    this.onmessage?.({ data: JSON.stringify(payload) } as MessageEvent);
  }
}

describe('CDPClient', () => {
  beforeEach(() => {
    vi.restoreAllMocks();
    vi.useRealTimers();
    (globalThis as Record<string, unknown>).WebSocket = originalWebSocket;
    (globalThis as Record<string, unknown>).fetch = originalFetch;
    MockWebSocket.instances = [];
  });

  test('can be instantiated', () => {
    const client = new CDPClient();
    expect(client).toBeInstanceOf(CDPClient);
  });

  test('connect() attempts WebSocket connection via discovered target', async () => {
    (globalThis as Record<string, unknown>).WebSocket = MockWebSocket;
    (globalThis as Record<string, unknown>).fetch = vi.fn().mockResolvedValue({
      ok: true,
      json: async () => [
        {
          id: 'target-1',
          type: 'page',
          title: 'target',
          url: 'https://example.com',
          webSocketDebuggerUrl: 'ws://127.0.0.1:9222/devtools/page/target-1',
        },
      ],
    });

    const client = new CDPClient();
    const connected = await client.connect(9222);

    expect(connected).toBe(true);
    expect(fetch).toHaveBeenCalledWith('http://127.0.0.1:9222/json');
    expect(MockWebSocket.instances).toHaveLength(1);
    expect(MockWebSocket.instances[0]?.url).toBe('ws://127.0.0.1:9222/devtools/page/target-1');
  });

  test('send() emits JSON-RPC message with method and params', async () => {
    (globalThis as Record<string, unknown>).WebSocket = MockWebSocket;

    const client = new CDPClient();
    const connected = await client.connectToUrl('ws://127.0.0.1:9222/devtools/page/test');
    expect(connected).toBe(true);

    const responsePromise = client.send('Runtime.evaluate', { expression: '1 + 1' });
    const ws = MockWebSocket.instances[0]!;

    expect(ws.sent).toHaveLength(2);
    const firstCall = JSON.parse(ws.sent[0]!);
    const secondCall = JSON.parse(ws.sent[1]!);
    expect(firstCall.method).toBe('Page.enable');
    expect(secondCall).toMatchObject({
      id: 2,
      method: 'Runtime.evaluate',
      params: { expression: '1 + 1' },
    });

    ws.emitMessage({ id: 2, result: { result: { value: 2 } } });
    await expect(responsePromise).resolves.toEqual({ result: { value: 2 } });
  });

  test('disconnect() closes active WebSocket connection', async () => {
    (globalThis as Record<string, unknown>).WebSocket = MockWebSocket;

    const client = new CDPClient();
    await client.connectToUrl('ws://127.0.0.1:9222/devtools/page/test');
    const ws = MockWebSocket.instances[0]!;

    await client.disconnect();

    expect(ws.closed).toBe(true);
    expect(client.isConnected()).toBe(false);
  });

  test('send() rejects with timeout when no response arrives', async () => {
    vi.useFakeTimers();
    (globalThis as Record<string, unknown>).WebSocket = MockWebSocket;

    const client = new CDPClient();
    await client.connectToUrl('ws://127.0.0.1:9222/devtools/page/test');

    const pendingSend = client.send('Runtime.evaluate', { expression: '2 + 2' });
    vi.advanceTimersByTime(90_000);

    await expect(pendingSend).rejects.toThrow(/CDP timeout/);
    vi.useRealTimers();
  });

  test('on() subscribes to CDP events', async () => {
    (globalThis as Record<string, unknown>).WebSocket = MockWebSocket;

    const client = new CDPClient();
    await client.connectToUrl('ws://127.0.0.1:9222/devtools/page/test');
    const ws = MockWebSocket.instances[0]!;

    const handler = vi.fn();
    client.on('Runtime.consoleAPICalled', handler);

    ws.emitMessage({
      method: 'Runtime.consoleAPICalled',
      params: { type: 'log', args: [{ value: 'hello' }] },
    });

    expect(handler).toHaveBeenCalledWith({ type: 'log', args: [{ value: 'hello' }] });
  });
});

describe('MAIN_APP_PATTERNS — never attach to the app UI', () => {
  // Regression: the list hardcoded dev ports 1420/5173 while vite serves 5174, so the app's own
  // window stayed eligible and CDP could attach to it instead of the embedded page — silently
  // reading and acting on the wrong DOM.
  const isAppUi = (url: string) => MAIN_APP_PATTERNS.some((p) => p.test(url));

  test('excludes the app UI on any loopback origin, whatever the dev port', () => {
    expect(isAppUi('http://localhost:5174/')).toBe(true); // the port the app actually uses
    expect(isAppUi('http://localhost:1420/')).toBe(true);
    expect(isAppUi('http://localhost:5173/')).toBe(true);
    expect(isAppUi('http://127.0.0.1:5174/')).toBe(true);
    expect(isAppUi('tauri://localhost/index.html')).toBe(true);
    expect(isAppUi('https://tauri.localhost/')).toBe(true);
  });

  test('does not exclude the external pages the embedded browser drives', () => {
    expect(isAppUi('https://quotes.toscrape.com/')).toBe(false);
    expect(isAppUi('https://www.myopenmath.com/course/course.php?cid=1')).toBe(false);
    expect(isAppUi('https://mail.google.com/')).toBe(false);
    // a host that merely starts with "localhost" is a real site, not the app
    expect(isAppUi('https://localhosting.example.com/')).toBe(false);
  });
});

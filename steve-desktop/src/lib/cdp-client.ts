export interface CDPTarget {
  id: string;
  type: string;
  title: string;
  url: string;
  webSocketDebuggerUrl: string;
}

type EventCallback = (params: Record<string, unknown>) => void;

export const MAIN_APP_PATTERNS = [
  /^tauri:\/\/localhost/,
  /^https:\/\/tauri\.localhost/,
  /^http:\/\/localhost:(1420|5173)/,
];

const SEND_TIMEOUT_MS = 90_000;

export class CDPClient {
  private ws: WebSocket | null = null;
  private nextId = 1;
  private pending = new Map<
    number,
    { resolve: (v: unknown) => void; reject: (e: Error) => void; timer: ReturnType<typeof setTimeout> }
  >();
  private listeners = new Map<string, Set<EventCallback>>();
  private connected = false;

  async connect(port: number = 9222): Promise<boolean> {
    try {
      if (this.ws) await this.disconnect();

      const resp = await fetch(`http://127.0.0.1:${port}/json`);
      if (!resp.ok) return false;

      const targets: CDPTarget[] = await resp.json();
      const target = targets.find(
        (t) =>
          t.type === 'page' &&
          t.url !== 'about:blank' &&
          t.url !== '' &&
          !MAIN_APP_PATTERNS.some((p) => p.test(t.url)),
      );

      if (!target?.webSocketDebuggerUrl) return false;
      return await this.openWebSocket(target.webSocketDebuggerUrl);
    } catch {
      return false;
    }
  }

  async connectToUrl(wsUrl: string): Promise<boolean> {
    try {
      if (this.ws) await this.disconnect();

      const ok = await this.openWebSocket(wsUrl);
      if (!ok) return false;

      await this.send('Page.enable');
      return true;
    } catch {
      return false;
    }
  }

  async disconnect(): Promise<void> {
    this.connected = false;

    for (const [, entry] of this.pending) {
      clearTimeout(entry.timer);
      entry.reject(new Error('Disconnected'));
    }
    this.pending.clear();

    if (this.ws) {
      try {
        this.ws.onopen = null;
        this.ws.onclose = null;
        this.ws.onerror = null;
        this.ws.onmessage = null;
        this.ws.close();
      } catch {
      }
      this.ws = null;
    }
  }

  isConnected(): boolean {
    return this.connected && this.ws !== null && this.ws.readyState === WebSocket.OPEN;
  }

  send(method: string, params?: Record<string, unknown>): Promise<unknown> {
    if (!this.isConnected()) {
      return Promise.reject(new Error('Not connected to CDP'));
    }

    const id = this.nextId++;
    const message = JSON.stringify({ id, method, params });

    return new Promise((resolve, reject) => {
      const timer = setTimeout(() => {
        this.pending.delete(id);
        reject(new Error(`CDP timeout: ${method} (${SEND_TIMEOUT_MS}ms)`));
      }, SEND_TIMEOUT_MS);

      this.pending.set(id, { resolve, reject, timer });
      this.ws!.send(message);
    });
  }

  on(event: string, callback: EventCallback): void {
    let set = this.listeners.get(event);
    if (!set) {
      set = new Set();
      this.listeners.set(event, set);
    }
    set.add(callback);
  }

  off(event: string, callback: EventCallback): void {
    this.listeners.get(event)?.delete(callback);
  }

  private openWebSocket(url: string): Promise<boolean> {
    return new Promise((resolve) => {
      try {
        const ws = new WebSocket(url);
        ws.onopen = () => {
          this.ws = ws;
          this.connected = true;
          resolve(true);
        };
        ws.onerror = () => resolve(false);
        ws.onclose = () => {
          this.connected = false;
        };
        ws.onmessage = (ev: MessageEvent) => this.handleMessage(ev);
      } catch {
        resolve(false);
      }
    });
  }

  private handleMessage(event: MessageEvent): void {
    try {
      const msg = JSON.parse(String(event.data)) as {
        id?: number;
        method?: string;
        result?: unknown;
        error?: { code: number; message: string };
        params?: Record<string, unknown>;
      };

      if (msg.id !== undefined) {
        const entry = this.pending.get(msg.id);
        if (entry) {
          this.pending.delete(msg.id);
          clearTimeout(entry.timer);
          if (msg.error) {
            entry.reject(new Error(`CDP error ${msg.error.code}: ${msg.error.message}`));
          } else {
            entry.resolve(msg.result);
          }
        }
        return;
      }

      if (msg.method) {
        const listeners = this.listeners.get(msg.method);
        if (listeners) {
          for (const callback of listeners) {
            try {
              callback(msg.params ?? {});
            } catch {
            }
          }
        }
      }
    } catch {
    }
  }
}

export const cdp = new CDPClient();

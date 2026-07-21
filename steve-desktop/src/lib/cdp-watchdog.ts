// CDP health watchdog.
//
// The app drives an embedded WebView2 over a CDP debug port. Under heavy
// concurrent agent load, the WebView2 CDP endpoint sometimes "wedges": the
// app process is still alive, but `GET http://127.0.0.1:<port>/json/version`
// hangs indefinitely or returns an empty/garbage response. The OS process
// looks healthy while CDP itself is unresponsive.
//
// This watchdog polls that endpoint on an interval and, after a run of
// consecutive failures, fires `onWedge` exactly once per wedge episode. It
// never throws and never recovers the connection itself — recovery (e.g.
// killing and recreating the webview) is entirely the caller's
// responsibility inside `onWedge`. When a subsequent check succeeds again,
// `onHealthy` fires once to signal the episode is over.

export interface WatchdogOptions {
  port: number;
  intervalMs?: number;
  timeoutMs?: number;
  failuresToTrip?: number;
  onWedge: () => void | Promise<void>;
  onHealthy?: () => void;
  fetchFn?: typeof fetch;
}

export interface HealthState {
  consecutiveFailures: number;
  tripped: boolean;
}

const DEFAULT_INTERVAL_MS = 5000;
const DEFAULT_TIMEOUT_MS = 3000;
const DEFAULT_FAILURES_TO_TRIP = 2;

export function initHealthState(): HealthState {
  return { consecutiveFailures: 0, tripped: false };
}

export function stepHealth(
  state: HealthState,
  healthy: boolean,
  failuresToTrip: number,
): { state: HealthState; justTripped: boolean; justRecovered: boolean } {
  if (healthy) {
    const justRecovered = state.tripped;
    return {
      state: { consecutiveFailures: 0, tripped: false },
      justTripped: false,
      justRecovered,
    };
  }

  const consecutiveFailures = state.consecutiveFailures + 1;
  const justTripped = !state.tripped && consecutiveFailures >= failuresToTrip;

  return {
    state: { consecutiveFailures, tripped: state.tripped || justTripped },
    justTripped,
    justRecovered: false,
  };
}

function abortSignalForTimeout(timeoutMs: number): AbortSignal {
  if (typeof AbortSignal.timeout === 'function') {
    return AbortSignal.timeout(timeoutMs);
  }
  const controller = new AbortController();
  setTimeout(() => controller.abort(), timeoutMs);
  return controller.signal;
}

export async function checkCdpHealth(
  port: number,
  timeoutMs: number,
  fetchFn: typeof fetch = fetch,
): Promise<boolean> {
  try {
    const resp = await fetchFn(`http://127.0.0.1:${port}/json/version`, {
      signal: abortSignalForTimeout(timeoutMs),
    });
    if (!resp.ok) return false;

    const text = await resp.text();
    if (!text) return false;

    const parsed: unknown = JSON.parse(text);
    if (typeof parsed !== 'object' || parsed === null) return false;

    const obj = parsed as Record<string, unknown>;
    return typeof obj.webSocketDebuggerUrl === 'string' || typeof obj.Browser === 'string';
  } catch {
    return false;
  }
}

export function createCdpWatchdog(opts: WatchdogOptions): {
  start(): void;
  stop(): void;
  checkNow(): Promise<boolean>;
} {
  const intervalMs = opts.intervalMs ?? DEFAULT_INTERVAL_MS;
  const timeoutMs = opts.timeoutMs ?? DEFAULT_TIMEOUT_MS;
  const failuresToTrip = opts.failuresToTrip ?? DEFAULT_FAILURES_TO_TRIP;
  const fetchFn = opts.fetchFn ?? fetch;

  let state = initHealthState();
  let timer: ReturnType<typeof setInterval> | null = null;

  async function checkNow(): Promise<boolean> {
    const healthy = await checkCdpHealth(opts.port, timeoutMs, fetchFn);
    const result = stepHealth(state, healthy, failuresToTrip);
    state = result.state;

    if (result.justTripped) {
      try {
        await opts.onWedge();
      } catch {
      }
    }

    if (result.justRecovered) {
      try {
        opts.onHealthy?.();
      } catch {
      }
    }

    return healthy;
  }

  function start(): void {
    if (timer !== null) return;
    timer = setInterval(() => {
      void checkNow();
    }, intervalMs);
  }

  function stop(): void {
    if (timer !== null) {
      clearInterval(timer);
      timer = null;
    }
  }

  return { start, stop, checkNow };
}

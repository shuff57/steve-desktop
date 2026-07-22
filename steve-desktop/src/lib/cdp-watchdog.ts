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
  /** Grace period after start() during which failures never trip. The FIRST (cold) agent-CLI
   *  spawn briefly stalls the WebView2 main thread (~15s of Node module load + MCP config resolve),
   *  which is a transient that recovers on its own — arming the watchdog only after this window
   *  stops that startup spike from raising a false "unresponsive" alarm. Only applies once start()
   *  has been called; direct checkNow() calls (tests) are unaffected. */
  warmupMs?: number;
  onWedge: () => void | Promise<void>;
  onHealthy?: () => void;
  fetchFn?: typeof fetch;
  /** Injectable clock for testing the warmup window. */
  nowFn?: () => number;
}

export interface HealthState {
  consecutiveFailures: number;
  tripped: boolean;
}

const DEFAULT_INTERVAL_MS = 5000;
// The wedge is transient: the agent's own bursts (spawning a python CDP helper, hammering the
// endpoint with commands) briefly starve /json/version, then it recovers on its own — observed
// tripping at ~15s twice, both times recovering with the run completing. So a stuck run must be
// distinguished from a recoverable burst by DURATION: require ~40s of sustained unresponsiveness
// (8 × 5s) before alarming. A truly frozen endpoint stays down well past that; a burst does not.
const DEFAULT_TIMEOUT_MS = 4000;
const DEFAULT_FAILURES_TO_TRIP = 8;
// The cold agent-CLI spawn stalls the main thread for ~15s; wait past that before arming so the
// startup transient never trips. Slightly longer than the ~15s trip window, for margin.
const DEFAULT_WARMUP_MS = 20000;

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
  const warmupMs = opts.warmupMs ?? DEFAULT_WARMUP_MS;
  const now = opts.nowFn ?? (() => Date.now());
  const fetchFn = opts.fetchFn ?? fetch;

  let state = initHealthState();
  let timer: ReturnType<typeof setInterval> | null = null;
  let startedAt: number | null = null;

  async function checkNow(): Promise<boolean> {
    const healthy = await checkCdpHealth(opts.port, timeoutMs, fetchFn);
    // Within the post-start() warmup window, never trip — the cold-spawn stall is transient. Keep
    // the failure counter clear so a warmup blip can't carry over and trip right after warmup ends.
    if (startedAt !== null && now() - startedAt < warmupMs) {
      state = initHealthState();
      return healthy;
    }
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
    startedAt = now(); // arms the warmup window
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

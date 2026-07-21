// Coalesce a high-frequency stream of items into batched flushes, so reactive UI state updates at
// most once per interval instead of on every item.
//
// Why: investigation showed the WebView2 CDP "wedge" is main-thread CPU starvation, not a CDP
// fault. A heavy agent run streams many `agent-cli-progress` events per second; updating Svelte
// state (and re-rendering the activity log) on EVERY event is real main-thread work that
// contributes to that starvation. Batching to one flush per ~150ms removes most of it while the
// user still sees near-live progress.

export interface ThrottledBuffer<T> {
  /** Queue an item; schedules a flush if one isn't already pending. */
  push(item: T): void;
  /** Flush any buffered items immediately (call on stop so nothing is lost). */
  flush(): void;
}

export interface BufferTimers {
  set: (fn: () => void, ms: number) => unknown;
  clear: (handle: unknown) => void;
}

const REAL_TIMERS: BufferTimers = {
  set: (fn, ms) => setTimeout(fn, ms),
  clear: (h) => clearTimeout(h as ReturnType<typeof setTimeout>),
};

export function createThrottledBuffer<T>(
  onFlush: (items: T[]) => void,
  intervalMs = 150,
  timers: BufferTimers = REAL_TIMERS,
): ThrottledBuffer<T> {
  let buf: T[] = [];
  let handle: unknown = null;

  const flush = (): void => {
    if (handle !== null) {
      timers.clear(handle);
      handle = null;
    }
    if (buf.length) {
      const items = buf;
      buf = [];
      onFlush(items);
    }
  };

  const push = (item: T): void => {
    buf.push(item);
    if (handle === null) handle = timers.set(flush, intervalMs);
  };

  return { push, flush };
}

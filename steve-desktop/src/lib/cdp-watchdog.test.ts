import { beforeEach, describe, expect, test, vi } from 'vitest';
import {
  checkCdpHealth,
  createCdpWatchdog,
  initHealthState,
  stepHealth,
  type HealthState,
} from './cdp-watchdog';

describe('stepHealth', () => {
  test('does not trip on a single failure below the threshold', () => {
    let state = initHealthState();
    const r1 = stepHealth(state, false, 2);
    state = r1.state;

    expect(r1.justTripped).toBe(false);
    expect(state.tripped).toBe(false);
    expect(state.consecutiveFailures).toBe(1);
  });

  test('trips only after failuresToTrip consecutive failures', () => {
    let state = initHealthState();
    let r = stepHealth(state, false, 2);
    state = r.state;
    expect(r.justTripped).toBe(false);

    r = stepHealth(state, false, 2);
    state = r.state;
    expect(r.justTripped).toBe(true);
    expect(state.tripped).toBe(true);
  });

  test('does not re-trip while already tripped', () => {
    let state: HealthState = { consecutiveFailures: 2, tripped: true };

    const r1 = stepHealth(state, false, 2);
    state = r1.state;
    expect(r1.justTripped).toBe(false);
    expect(state.tripped).toBe(true);
    expect(state.consecutiveFailures).toBe(3);

    const r2 = stepHealth(state, false, 2);
    expect(r2.justTripped).toBe(false);
  });

  test('recovers on a healthy check and clears tripped', () => {
    const state: HealthState = { consecutiveFailures: 5, tripped: true };
    const r = stepHealth(state, true, 2);

    expect(r.justRecovered).toBe(true);
    expect(r.justTripped).toBe(false);
    expect(r.state.tripped).toBe(false);
    expect(r.state.consecutiveFailures).toBe(0);
  });

  test('a healthy check when not tripped does not report justRecovered', () => {
    const state = initHealthState();
    const r = stepHealth(state, true, 2);

    expect(r.justRecovered).toBe(false);
    expect(r.justTripped).toBe(false);
  });

  test('can trip again after recovering', () => {
    let state = initHealthState();
    state = stepHealth(state, false, 2).state;
    let r = stepHealth(state, false, 2);
    state = r.state;
    expect(r.justTripped).toBe(true);

    r = stepHealth(state, true, 2);
    state = r.state;
    expect(r.justRecovered).toBe(true);
    expect(state.tripped).toBe(false);

    state = stepHealth(state, false, 2).state;
    r = stepHealth(state, false, 2);
    expect(r.justTripped).toBe(true);
  });
});

describe('checkCdpHealth', () => {
  test('healthy JSON with webSocketDebuggerUrl resolves true', async () => {
    const fetchFn = vi.fn().mockResolvedValue({
      ok: true,
      text: async () => JSON.stringify({ webSocketDebuggerUrl: 'ws://127.0.0.1:1234/devtools/x' }),
    });

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      true,
    );
  });

  test('healthy JSON with Browser field resolves true', async () => {
    const fetchFn = vi.fn().mockResolvedValue({
      ok: true,
      text: async () => JSON.stringify({ Browser: 'Chrome/1.0' }),
    });

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      true,
    );
  });

  test('non-ok response resolves false', async () => {
    const fetchFn = vi.fn().mockResolvedValue({
      ok: false,
      text: async () => '',
    });

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      false,
    );
  });

  test('thrown fetch (rejected promise) resolves false, does not throw', async () => {
    const fetchFn = vi.fn().mockRejectedValue(new Error('network down'));

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      false,
    );
  });

  test('empty body resolves false', async () => {
    const fetchFn = vi.fn().mockResolvedValue({
      ok: true,
      text: async () => '',
    });

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      false,
    );
  });

  test('garbage body resolves false', async () => {
    const fetchFn = vi.fn().mockResolvedValue({
      ok: true,
      text: async () => 'not json{{{',
    });

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      false,
    );
  });

  test('valid JSON missing required fields resolves false', async () => {
    const fetchFn = vi.fn().mockResolvedValue({
      ok: true,
      text: async () => JSON.stringify({ someOtherField: 'x' }),
    });

    await expect(checkCdpHealth(1234, 3000, fetchFn as unknown as typeof fetch)).resolves.toBe(
      false,
    );
  });
});

describe('createCdpWatchdog.checkNow', () => {
  beforeEach(() => {
    vi.useRealTimers();
  });

  function unhealthyFetch() {
    return vi.fn().mockResolvedValue({
      ok: false,
      text: async () => '',
    });
  }

  test('fires onWedge exactly once across multiple failing checks', async () => {
    const fetchFn = unhealthyFetch();
    const onWedge = vi.fn();
    const onHealthy = vi.fn();

    const watchdog = createCdpWatchdog({
      port: 9222,
      failuresToTrip: 2,
      onWedge,
      onHealthy,
      fetchFn: fetchFn as unknown as typeof fetch,
    });

    // 1st failure: below threshold, no trip yet.
    await watchdog.checkNow();
    expect(onWedge).not.toHaveBeenCalled();

    // 2nd failure: reaches threshold, trips.
    await watchdog.checkNow();
    expect(onWedge).toHaveBeenCalledTimes(1);

    // 3rd, 4th failures: already tripped, must not fire again.
    await watchdog.checkNow();
    await watchdog.checkNow();
    expect(onWedge).toHaveBeenCalledTimes(1);
    expect(onHealthy).not.toHaveBeenCalled();
  });

  test('warmup window suppresses trips until it elapses (after start)', async () => {
    let t = 1000;
    const onWedge = vi.fn();
    const watchdog = createCdpWatchdog({
      port: 9222,
      failuresToTrip: 2,
      warmupMs: 20000,
      nowFn: () => t,
      onWedge,
      fetchFn: unhealthyFetch() as unknown as typeof fetch,
    });
    watchdog.start(); // arms warmup at t=1000
    watchdog.stop(); // clear the interval; startedAt stays set so checkNow() sees the warmup

    // Inside warmup: repeated failures must not trip.
    t = 5000;
    await watchdog.checkNow();
    await watchdog.checkNow();
    await watchdog.checkNow();
    expect(onWedge).not.toHaveBeenCalled();

    // Past warmup: failures trip normally.
    t = 30000;
    await watchdog.checkNow(); // fail 1
    await watchdog.checkNow(); // fail 2 -> trips
    expect(onWedge).toHaveBeenCalledTimes(1);
  });

  test('checkNow without start() ignores warmup (direct-call path unaffected)', async () => {
    const onWedge = vi.fn();
    const watchdog = createCdpWatchdog({
      port: 9222,
      failuresToTrip: 2,
      warmupMs: 999999,
      onWedge,
      fetchFn: unhealthyFetch() as unknown as typeof fetch,
    });
    await watchdog.checkNow();
    await watchdog.checkNow();
    expect(onWedge).toHaveBeenCalledTimes(1);
  });

  test('onHealthy fires once when a healthy check follows a tripped watchdog', async () => {
    let healthy = false;
    const fetchFn = vi.fn().mockImplementation(async () => ({
      ok: healthy,
      text: async () => (healthy ? JSON.stringify({ Browser: 'Chrome/1.0' }) : ''),
    }));
    const onWedge = vi.fn();
    const onHealthy = vi.fn();

    const watchdog = createCdpWatchdog({
      port: 9222,
      failuresToTrip: 2,
      onWedge,
      onHealthy,
      fetchFn: fetchFn as unknown as typeof fetch,
    });

    await watchdog.checkNow(); // fail 1
    await watchdog.checkNow(); // fail 2 -> trips
    expect(onWedge).toHaveBeenCalledTimes(1);
    expect(onHealthy).not.toHaveBeenCalled();

    healthy = true;
    await watchdog.checkNow(); // recovers
    expect(onHealthy).toHaveBeenCalledTimes(1);
    expect(onWedge).toHaveBeenCalledTimes(1);

    healthy = false;
    await watchdog.checkNow(); // fail 1 again
    await watchdog.checkNow(); // fail 2 again -> trips again
    expect(onWedge).toHaveBeenCalledTimes(2);
    expect(onHealthy).toHaveBeenCalledTimes(1);
  });

  test('swallows a throwing onWedge callback without rejecting checkNow', async () => {
    const fetchFn = unhealthyFetch();
    const onWedge = vi.fn().mockImplementation(() => {
      throw new Error('boom');
    });

    const watchdog = createCdpWatchdog({
      port: 9222,
      failuresToTrip: 1,
      onWedge,
      fetchFn: fetchFn as unknown as typeof fetch,
    });

    await expect(watchdog.checkNow()).resolves.toBe(false);
    expect(onWedge).toHaveBeenCalledTimes(1);
  });

  test('awaits an async onWedge and swallows its rejection', async () => {
    const fetchFn = unhealthyFetch();
    const onWedge = vi.fn().mockRejectedValue(new Error('async boom'));

    const watchdog = createCdpWatchdog({
      port: 9222,
      failuresToTrip: 1,
      onWedge,
      fetchFn: fetchFn as unknown as typeof fetch,
    });

    await expect(watchdog.checkNow()).resolves.toBe(false);
    expect(onWedge).toHaveBeenCalledTimes(1);
  });
});

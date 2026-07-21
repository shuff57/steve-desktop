import { describe, it, expect, vi } from 'vitest';
import { createThrottledBuffer, type BufferTimers } from './throttle-buffer';

/** A controllable fake scheduler: fire() runs the pending callback on demand. */
function fakeTimers() {
  let pending: (() => void) | null = null;
  const timers: BufferTimers = {
    set: (fn) => { pending = fn; return 1; },
    clear: () => { pending = null; },
  };
  return { timers, fire: () => { const p = pending; pending = null; p?.(); }, hasPending: () => pending !== null };
}

describe('createThrottledBuffer', () => {
  it('coalesces many pushes into one flush per interval', () => {
    const { timers, fire } = fakeTimers();
    const onFlush = vi.fn();
    const b = createThrottledBuffer<string>(onFlush, 150, timers);

    b.push('a'); b.push('b'); b.push('c');
    expect(onFlush).not.toHaveBeenCalled(); // nothing flushed yet — one timer pending

    fire();
    expect(onFlush).toHaveBeenCalledTimes(1);
    expect(onFlush).toHaveBeenCalledWith(['a', 'b', 'c']); // all three in ONE flush
  });

  it('starts a new batch after a flush', () => {
    const { timers, fire } = fakeTimers();
    const onFlush = vi.fn();
    const b = createThrottledBuffer<string>(onFlush, 150, timers);

    b.push('a'); fire();
    b.push('b'); b.push('c'); fire();
    expect(onFlush.mock.calls).toEqual([[['a']], [['b', 'c']]]);
  });

  it('flush() emits buffered items immediately and cancels the pending timer', () => {
    const { timers, hasPending } = fakeTimers();
    const onFlush = vi.fn();
    const b = createThrottledBuffer<string>(onFlush, 150, timers);

    b.push('x'); b.push('y');
    expect(hasPending()).toBe(true);
    b.flush();
    expect(onFlush).toHaveBeenCalledWith(['x', 'y']);
    expect(hasPending()).toBe(false);
  });

  it('flush() with nothing buffered does not call onFlush', () => {
    const { timers } = fakeTimers();
    const onFlush = vi.fn();
    createThrottledBuffer<string>(onFlush, 150, timers).flush();
    expect(onFlush).not.toHaveBeenCalled();
  });
});

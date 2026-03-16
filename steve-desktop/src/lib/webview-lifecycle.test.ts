import { describe, expect, it, vi } from 'vitest';
import {
  calculatePostAnimationDelay,
  createDestroyGuard,
  scheduleBoundsUpdateAfterAnimation,
  shouldTriggerSidebarAnimation,
} from './webview-lifecycle';

describe('webview-lifecycle', () => {
  it('shouldTriggerSidebarAnimation mirrors browserCreated state', () => {
    expect(shouldTriggerSidebarAnimation(true)).toBe(true);
    expect(shouldTriggerSidebarAnimation(false)).toBe(false);
  });

  it('calculatePostAnimationDelay clamps at zero', () => {
    expect(calculatePostAnimationDelay(300, 100)).toBe(200);
    expect(calculatePostAnimationDelay(300, 350)).toBe(0);
  });

  it('createDestroyGuard marks destroyed and runs callbacks once', () => {
    const guard = createDestroyGuard();
    const a = vi.fn();
    const b = vi.fn();

    guard.onDestroy([a, b]);
    expect(guard.destroyed).toBe(true);
    expect(a).toHaveBeenCalledTimes(1);
    expect(b).toHaveBeenCalledTimes(1);

    guard.onDestroy([a]);
    expect(a).toHaveBeenCalledTimes(1);
  });

  it('scheduleBoundsUpdateAfterAnimation runs update when not destroyed', async () => {
    const guard = { destroyed: false };
    const updateFn = vi.fn();

    scheduleBoundsUpdateAfterAnimation({
      sidebarTransitionMs: 0,
      updateFn,
      guard,
    });

    await new Promise((resolve) => setTimeout(resolve, 0));
    expect(updateFn).toHaveBeenCalledTimes(1);
  });
});

export function shouldTriggerSidebarAnimation(browserCreated: boolean): boolean {
  return browserCreated;
}

export function calculatePostAnimationDelay(
  sidebarTransitionMs: number,
  elapsedSinceNavigate: number,
): number {
  return Math.max(0, sidebarTransitionMs - elapsedSinceNavigate);
}

export function createDestroyGuard(): {
  destroyed: boolean;
  markDestroyed: () => void;
  onDestroy: (callbacks: Array<() => void>) => void;
} {
  const guard = {
    destroyed: false,
    markDestroyed() {
      guard.destroyed = true;
    },
    onDestroy(callbacks: Array<() => void>) {
      if (guard.destroyed) return;
      guard.destroyed = true;
      for (const cb of callbacks) cb();
    },
  };

  return guard;
}

export interface ScheduleBoundsUpdateOptions {
  sidebarTransitionMs: number;
  updateFn: () => void;
  guard: { destroyed: boolean };
}

export function scheduleBoundsUpdateAfterAnimation(opts: ScheduleBoundsUpdateOptions): () => void {
  const { sidebarTransitionMs, updateFn, guard } = opts;
  const id = setTimeout(() => {
    if (!guard.destroyed) updateFn();
  }, sidebarTransitionMs);
  return () => clearTimeout(id);
}

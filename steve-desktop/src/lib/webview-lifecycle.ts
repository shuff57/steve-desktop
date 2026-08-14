export function shouldTriggerSidebarAnimation(browserCreated: boolean): boolean {
  return browserCreated;
}

/**
 * May a tab's NATIVE webview be on screen right now?
 *
 * A native child webview cannot be layered behind HTML — it paints over whatever the app draws and
 * takes the mouse with it. So while the app is showing a page OTHER than Browse there is no correct
 * place to put one, and the answer is always no, however badly something wants the tab visible.
 *
 * `page_wait` is what wants it: it re-asserts activation on every poll specifically to counter the
 * user navigating the app away mid-run (page-tools-bridge.ts). Honouring that request off-Browse
 * dragged the webview back over the page the user was actually on — every poll, so leaving and
 * returning could not shake it off. The agent's video stalls while the user is elsewhere; that is
 * the real constraint, not something to route around by covering the UI.
 */
export function mayShowWebview(opts: { browseIsOnScreen: boolean; browserCreated: boolean }): boolean {
  return opts.browseIsOnScreen && opts.browserCreated;
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

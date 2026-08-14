export interface WebviewBounds {
  x: number;
  y: number;
  width: number;
  height: number;
}

export interface CalculateWebviewBoundsParams {
  sidebarWidth: number;
  navBarHeight: number;
  panelWidth?: number;
  windowWidth: number;
  windowHeight: number;
  extraTopOffset?: number;
}

/**
 * Where the native webview goes inside the app window.
 *
 * `navBarHeight` of 0 is not a thin toolbar — it means the Browse page's chrome is not on screen
 * to be measured, because App hides the whole holder with `display:none` while another page shows.
 * Every getBoundingClientRect under it then returns 0, and the "content area" computes as the FULL
 * window at y=0: a native child webview painted over the app's own UI, swallowing every click in
 * it. Zero size is how that is refused — the caller already declines to publish empty bounds, so
 * the last good position stands instead of a full-window one.
 */
export function calculateWebviewBounds(params: CalculateWebviewBoundsParams): WebviewBounds {
  const {
    sidebarWidth,
    navBarHeight,
    panelWidth = 0,
    windowWidth,
    windowHeight,
    extraTopOffset = 0,
  } = params;

  if (navBarHeight <= 0) return { x: 0, y: 0, width: 0, height: 0 };

  const x = sidebarWidth;
  const y = navBarHeight + extraTopOffset;
  const width = Math.max(0, windowWidth - sidebarWidth - panelWidth);
  const height = Math.max(0, windowHeight - y);

  return { x, y, width, height };
}

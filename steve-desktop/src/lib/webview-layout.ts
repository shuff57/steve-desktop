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

export function calculateWebviewBounds(params: CalculateWebviewBoundsParams): WebviewBounds {
  const {
    sidebarWidth,
    navBarHeight,
    panelWidth = 0,
    windowWidth,
    windowHeight,
    extraTopOffset = 0,
  } = params;

  const x = sidebarWidth;
  const y = navBarHeight + extraTopOffset;
  const width = Math.max(0, windowWidth - sidebarWidth - panelWidth);
  const height = Math.max(0, windowHeight - y);

  return { x, y, width, height };
}

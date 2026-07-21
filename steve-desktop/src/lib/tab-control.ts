// Marker-pinning: WebView2 exposes multiple debug-port targets that can share the same URL, so a
// spawned CLI agent can't reliably tell them apart by url alone. We stamp each embedded tab's
// window.name with a unique marker on every page load; the agent then matches window.name over
// CDP (Runtime.evaluate) to pin to the exact tab instead of guessing from the target list.

/** The window.name value stamped onto tab `id`'s embedded webview. */
export function tabMarker(id: string): string {
  return `steve-tab-${id}`;
}

/**
 * Self-contained script to inject into a tab so it stamps its own window.name. Idempotent —
 * safe to re-inject on every page load, since window.name resets across cross-origin navigations.
 */
export function markerScript(id: string): string {
  const marker = tabMarker(id);
  return `(function(){try{window.name=${JSON.stringify(marker)};}catch(e){}})();`;
}

export interface TabInfo {
  id: string;
  url: string;
  title: string;
  active: boolean;
  ready: boolean;
  marker: string;
}

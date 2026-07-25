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
  /** Agent session that owns this tab, or null if unowned (a user's manual tab). */
  session: string | null;
}

/**
 * Ownership guard for __steveControl bridge calls: may a caller carrying `callerSessionId`
 * act on a tab owned by `ownerSessionId` (null = unowned)?
 * - No caller id → legacy caller, allow (old prompts/flows keep working).
 * - Own tab → allow. Another session's tab → deny. Unowned tab → deny (concurrent agents must
 *   not grab the user's manual tabs — they open their own via newTab(url, sessionId)).
 */
export function mayAct(
  ownerSessionId: string | null,
  callerSessionId?: string,
): { ok: boolean; reason?: string } {
  if (!callerSessionId) return { ok: true };
  if (ownerSessionId === callerSessionId) return { ok: true };
  if (ownerSessionId) return { ok: false, reason: 'owned by another agent session' };
  return { ok: false, reason: 'not part of your session — open tabs with newTab(url, yourSessionId)' };
}

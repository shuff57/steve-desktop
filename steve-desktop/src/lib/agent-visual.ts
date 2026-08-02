/**
 * One palette for every "an agent is working" surface.
 *
 * These colours were arrived at three times independently — the in-page
 * page-agent overlay, the app's session overlay, and ActionShell's status dot
 * and history cards all mirror upstream page-agent's panel. They agree today
 * purely by coincidence of copying, which is exactly the state that drifts the
 * first time one of them is tweaked. This module is the source of truth.
 *
 * Two consumers, two mechanisms:
 *  - The overlays inject CSS into a foreign page, so they read these values and
 *    inline them into the script they send.
 *  - ActionShell is app CSS and cannot import TS, so it repeats the literals —
 *    guarded by a test that fails if the two ever disagree.
 */

/** Status colours, keyed by what the agent is doing. */
export const AGENT_COLORS = {
  thinking: 'rgb(57, 182, 255)',
  executing: 'rgb(189, 69, 251)',
  awaiting: 'rgb(255, 214, 0)',
  completed: 'rgb(34, 197, 94)',
  error: 'rgb(239, 68, 68)',
  /** History-card accents, which carry a kind rather than a status. */
  observation: 'rgb(147, 51, 234)',
  question: 'rgb(255, 159, 67)',
} as const;

export type AgentColorKey = keyof typeof AGENT_COLORS;

/** The four-stop sweep behind the pill and around the viewport, in cycle order. */
export const AGENT_SWEEP = [
  'rgb(57, 182, 255)',
  'rgb(189, 69, 251)',
  'rgb(255, 87, 51)',
  'rgb(255, 214, 0)',
] as const;

/**
 * Per-session accents, so two concurrent runs are told apart at a glance.
 * Assigned round-robin as sessions start.
 */
export const SESSION_COLORS = ['#34d399', '#60a5fa', '#f472b6', '#fbbf24', '#a78bfa'];

/** `rgb(r, g, b)` → `rgba(r, g, b, alpha)`, for inlining tinted shadows. */
export function withAlpha(color: string, alpha: number): string {
  const m = /^rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/.exec(color);
  return m ? `rgba(${m[1]}, ${m[2]}, ${m[3]}, ${alpha})` : color;
}

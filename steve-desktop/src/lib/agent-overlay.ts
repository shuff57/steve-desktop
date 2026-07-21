import { injectScript } from './browser';

// Visible "an agent is connected and driving this tab" overlay, injected into the webview: a
// pastel-green squircle "agent connected" badge and a pastel-green mouse-pointer-2 cursor with a
// pulsing halo. The cursor is INDEPENDENT of the user's mouse — it only moves when the agent calls
// window.__steveCursorMove(x,y) (which it does before each click), never on the user's real mouse.
// No full-page border ring — the tab highlight (Browser.svelte) carries the "which tab" signal.
//
// A page load clears injected DOM, so Browser.svelte re-injects this on every page-loaded while an
// agent is active. Idempotent via the window.__steveCursor guard.
export const AGENT_OVERLAY_SCRIPT = `(function(){
  if(window.__steveCursor) return; window.__steveCursor=1;
  ['__steveAgentTag','__steveAgentCursor','__steveAgentStyle'].forEach(function(id){var e=document.getElementById(id);if(e)e.remove();});
  var st=document.createElement('style'); st.id='__steveAgentStyle';
  st.textContent='@keyframes steveHalo{0%,100%{transform:scale(.8);opacity:.45}50%{transform:scale(1.15);opacity:.9}}';
  document.documentElement.appendChild(st);
  var tag=document.createElement('div'); tag.id='__steveAgentTag'; tag.textContent='\\u25CF agent connected';
  tag.style.cssText='position:fixed;top:12px;right:12px;background:#a7f3d0;color:#065f46;font:600 12px sans-serif;padding:6px 12px;border-radius:4px;pointer-events:none;z-index:2147483647;box-shadow:0 2px 7px rgba(0,0,0,.22)';
  var cur=document.createElement('div'); cur.id='__steveAgentCursor';
  cur.style.cssText='position:fixed;left:50%;top:50%;pointer-events:none;z-index:2147483647';
  cur.innerHTML='<div style="position:absolute;left:-19px;top:-19px;width:64px;height:64px;border-radius:50%;background:radial-gradient(circle,rgba(52,211,153,.55),rgba(52,211,153,0) 68%);animation:steveHalo 1.1s ease-in-out infinite"></div>'+
    '<svg width="42" height="42" viewBox="0 0 24 24" fill="#34d399" stroke="#065f46" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="position:relative;filter:drop-shadow(0 2px 4px rgba(0,0,0,.5))"><path d="M4.037 4.688a.495.495 0 0 1 .651-.651l16 6.5a.5.5 0 0 1-.063.947l-6.124 1.58a2 2 0 0 0-1.438 1.435l-1.579 6.126a.5.5 0 0 1-.947.063z"/></svg>';
  document.documentElement.appendChild(tag); document.documentElement.appendChild(cur);
  function mv(x,y){cur.style.left=x+'px';cur.style.top=y+'px';}
  function ripple(x,y){var r=document.createElement('div');r.style.cssText='position:fixed;left:'+x+'px;top:'+y+'px;width:12px;height:12px;margin:-6px 0 0 -6px;border-radius:50%;background:#34d399;pointer-events:none;z-index:2147483646;opacity:.85;transition:all .5s';document.documentElement.appendChild(r);requestAnimationFrame(function(){r.style.width='46px';r.style.height='46px';r.style.margin='-23px 0 0 -23px';r.style.opacity='0';});setTimeout(function(){r.remove();},520);}
  window.__steveCursorMove=function(x,y){mv(x,y);ripple(x,y);};
  // The cursor moves ONLY when the agent calls window.__steveCursorMove(x,y). There are NO DOM
  // mouse listeners at all, so it can never follow the USER's mouse OR clicks — the two are
  // indistinguishable in-page (both are trusted events), so the only way to be independent is to
  // be driven solely by the agent. The agent calls it before each click (see the exec prompt);
  // during read-only phases the cursor simply rests where it was.
})();`;

export const AGENT_OVERLAY_REMOVE = `(function(){['__steveAgentTag','__steveAgentCursor','__steveAgentStyle'].forEach(function(id){var e=document.getElementById(id);if(e)e.remove();});window.__steveCursor=0;})();`;

// ROOT-CAUSE FIX for the CDP "wedge": a single unhandled alert()/confirm()/beforeunload on the
// embedded webview blocks the page's message loop, so the agent's CDP commands hang (reproduced:
// 1ms → 4s+ hang after one alert). The embedded WebView2 has no dialog handler. While an agent is
// driving, neutralize page dialogs so nothing can block it: alert/prompt become no-ops, confirm
// returns true (the agent's plan is already human-approved, so proceed), and beforeunload is
// cleared so navigating away from a form never nags. Injected ONLY during agent runs — manual
// browsing keeps normal dialog behavior.
export const DIALOG_SUPPRESS_SCRIPT = `(function(){
  if(window.__steveDialogSuppressed) return; window.__steveDialogSuppressed=1;
  try{window.alert=function(){};window.confirm=function(){return true;};window.prompt=function(){return null;};}catch(_){}
  try{window.onbeforeunload=null;}catch(_){}
  try{window.addEventListener('beforeunload',function(e){e.stopImmediatePropagation();delete e['returnValue'];},true);}catch(_){}
})();`;

/** Dispatched so Browser.svelte can highlight the active tab and re-inject the overlay on nav. */
export interface AgentActiveDetail { active: boolean; tabId: string }

/** Show the connection overlay + suppress dialogs on a tab and announce it (call when a spawn starts). */
export async function showAgentConnected(tabId: string): Promise<void> {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('steve:agent-active', { detail: { active: true, tabId } satisfies AgentActiveDetail }));
  }
  if (tabId) {
    try { await injectScript(DIALOG_SUPPRESS_SCRIPT, tabId); } catch { /* best-effort */ }
    try { await injectScript(AGENT_OVERLAY_SCRIPT, tabId); } catch { /* best-effort */ }
  }
}

/** Remove the overlay and announce disconnect (call when a spawn ends). */
export async function hideAgentConnected(tabId: string): Promise<void> {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('steve:agent-active', { detail: { active: false, tabId } satisfies AgentActiveDetail }));
  }
  if (tabId) { try { await injectScript(AGENT_OVERLAY_REMOVE, tabId); } catch { /* best-effort */ } }
}

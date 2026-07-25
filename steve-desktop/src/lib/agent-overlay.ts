// Visible "an agent is connected and driving this tab" overlay, injected into the webview: a
// squircle "agent connected" badge and a mouse-pointer-2 cursor with a pulsing halo, all in the
// SESSION'S colour so concurrent agent sessions are told apart. The cursor is INDEPENDENT of the
// user's mouse — it only moves when the agent calls window.__steveCursorMove(x,y), never on the
// user's real mouse. A page load clears injected DOM, so Browser.svelte re-injects on every
// page-loaded while an agent is active. Idempotent via the window.__steveCursor guard.

/** Distinct colours for concurrent sessions — assigned round-robin as sessions start. */
export const SESSION_COLORS = ['#34d399', '#60a5fa', '#f472b6', '#fbbf24', '#a78bfa'];

export function agentOverlayScript(color: string): string {
  return `(function(){
  if(window.__steveCursor) return; window.__steveCursor=1;
  ['__steveAgentTag','__steveAgentCursor','__steveAgentStyle'].forEach(function(id){var e=document.getElementById(id);if(e)e.remove();});
  var st=document.createElement('style'); st.id='__steveAgentStyle';
  st.textContent='@keyframes steveHalo{0%,100%{transform:scale(.8);opacity:.45}50%{transform:scale(1.15);opacity:.9}}';
  document.documentElement.appendChild(st);
  var tag=document.createElement('div'); tag.id='__steveAgentTag'; tag.textContent='\\u25CF agent connected';
  tag.style.cssText='position:fixed;top:12px;right:12px;background:${color};color:#fff;font:600 12px sans-serif;padding:6px 12px;border-radius:4px;pointer-events:none;z-index:2147483647;box-shadow:0 2px 7px rgba(0,0,0,.22)';
  var cur=document.createElement('div'); cur.id='__steveAgentCursor';
  // left/top transition = the cursor GLIDES to each target instead of teleporting, so the user sees
  // it travel to the button it is about to click, like a real pointer.
  cur.style.cssText='position:fixed;left:50%;top:50%;pointer-events:none;z-index:2147483647;transition:left .3s cubic-bezier(.4,0,.2,1),top .3s cubic-bezier(.4,0,.2,1)';
  cur.innerHTML='<div style="position:absolute;left:-13px;top:-13px;width:44px;height:44px;border-radius:50%;background:radial-gradient(circle,${color}8c,${color}00 68%);animation:steveHalo 1.1s ease-in-out infinite"></div>'+
    '<svg width="28" height="28" viewBox="0 0 24 24" fill="${color}" stroke="#1f2937" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="position:relative;filter:drop-shadow(0 2px 4px rgba(0,0,0,.5))"><path d="M4.037 4.688a.495.495 0 0 1 .651-.651l16 6.5a.5.5 0 0 1-.063.947l-6.124 1.58a2 2 0 0 0-1.438 1.435l-1.579 6.126a.5.5 0 0 1-.947.063z"/></svg>';
  document.documentElement.appendChild(tag); document.documentElement.appendChild(cur);
  function mv(x,y){cur.style.left=x+'px';cur.style.top=y+'px';}
  function ripple(x,y){var r=document.createElement('div');r.style.cssText='position:fixed;left:'+x+'px;top:'+y+'px;width:12px;height:12px;margin:-6px 0 0 -6px;border-radius:50%;background:${color};pointer-events:none;z-index:2147483646;opacity:.85;transition:all .5s';document.documentElement.appendChild(r);requestAnimationFrame(function(){r.style.width='46px';r.style.height='46px';r.style.margin='-23px 0 0 -23px';r.style.opacity='0';});setTimeout(function(){r.remove();},520);}
  window.__steveCursorMove=function(x,y){mv(x,y);ripple(x,y);};
  // Screenshot feedback: a quick camera flash + "captured" chip so the user SEES the moment the
  // agent grabs an image. The agent calls this right before Page.captureScreenshot (see exec prompt).
  window.__steveScreenshotFlash=function(){
    var f=document.createElement('div');
    f.style.cssText='position:fixed;inset:0;background:#fff;pointer-events:none;z-index:2147483645;opacity:0;transition:opacity .1s ease-out';
    var chip=document.createElement('div');
    chip.textContent='\\uD83D\\uDCF8 captured';
    chip.style.cssText='position:fixed;top:12px;left:50%;transform:translateX(-50%) scale(.9);background:${color};color:#fff;font:600 13px sans-serif;padding:7px 14px;border-radius:20px;pointer-events:none;z-index:2147483647;opacity:0;transition:opacity .18s,transform .18s;box-shadow:0 3px 10px rgba(0,0,0,.3)';
    document.documentElement.appendChild(f); document.documentElement.appendChild(chip);
    requestAnimationFrame(function(){
      f.style.opacity='.75'; chip.style.opacity='1'; chip.style.transform='translateX(-50%) scale(1)';
      setTimeout(function(){f.style.opacity='0';},110);
      setTimeout(function(){chip.style.opacity='0';},900);
      setTimeout(function(){f.remove();chip.remove();},1150);
    });
  };
})();`;
}

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

/** Dispatched so Browser.svelte can highlight the active tab and re-inject the overlay on nav.
 *  `sessionId` is the spawned run's id — it becomes the AgentSession id so tabs the run opens are
 *  owned by that exact run (omitted → Browser.svelte mints one; legacy single-run flows). */
export interface AgentActiveDetail { active: boolean; tabId: string; sessionId?: string }

// Announce a run (dis)connecting from its owner tab. Browser.svelte owns the session model — it
// assigns the session colour and does the actual overlay injection/removal (and colour-follow across
// tabs), because only it knows how many sessions are running and which colours are free.
export async function showAgentConnected(tabId: string, sessionId?: string): Promise<void> {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('steve:agent-active', { detail: { active: true, tabId, sessionId } satisfies AgentActiveDetail }));
  }
}

export async function hideAgentConnected(tabId: string, sessionId?: string): Promise<void> {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('steve:agent-active', { detail: { active: false, tabId, sessionId } satisfies AgentActiveDetail }));
  }
}

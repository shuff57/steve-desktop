import { injectScript } from './browser';

// Visible "an agent is connected and driving this tab" overlay, injected into the webview so the
// user can see it at a glance: a red border ring around the viewport, an "agent connected" badge,
// and a LARGE high-contrast arrow cursor with a pulsing halo that follows the agent's mouse
// (mousemove) and jumps to clicks with a ripple. Screenshot-verified visible.
//
// A page load clears injected DOM, so Browser.svelte re-injects this on every page-loaded while an
// agent is active. Idempotent via the window.__steveCursor guard.
export const AGENT_OVERLAY_SCRIPT = `(function(){
  if(window.__steveCursor) return; window.__steveCursor=1;
  ['__steveAgentOverlay','__steveAgentTag','__steveAgentCursor','__steveAgentStyle'].forEach(function(id){var e=document.getElementById(id);if(e)e.remove();});
  var st=document.createElement('style'); st.id='__steveAgentStyle';
  st.textContent='@keyframes steveHalo{0%,100%{transform:scale(.85);opacity:.55}50%{transform:scale(1.15);opacity:1}}';
  document.documentElement.appendChild(st);
  var ring=document.createElement('div'); ring.id='__steveAgentOverlay';
  ring.style.cssText='position:fixed;inset:0;pointer-events:none;z-index:2147483646;border:4px solid #e5484d;box-shadow:inset 0 0 18px rgba(229,72,77,.65)';
  var tag=document.createElement('div'); tag.id='__steveAgentTag'; tag.textContent='\\u25CF agent connected';
  tag.style.cssText='position:fixed;top:8px;right:10px;background:#e5484d;color:#fff;font:600 13px sans-serif;padding:3px 11px;border-radius:12px;pointer-events:none;z-index:2147483647;box-shadow:0 1px 4px rgba(0,0,0,.4)';
  var cur=document.createElement('div'); cur.id='__steveAgentCursor';
  cur.style.cssText='position:fixed;left:50%;top:50%;pointer-events:none;z-index:2147483647';
  cur.innerHTML='<div style="position:absolute;left:-16px;top:-16px;width:64px;height:64px;border-radius:50%;background:radial-gradient(circle,rgba(229,72,77,.5),rgba(229,72,77,0) 68%);animation:steveHalo 1.1s ease-in-out infinite"></div>'+
    '<svg width="46" height="46" viewBox="0 0 24 24" style="position:relative;filter:drop-shadow(0 2px 4px rgba(0,0,0,.8))"><path d="M4 2 L4 20 L9 15 L12 22 L15 21 L12 14 L19 14 Z" fill="#ff1f26" stroke="#ffffff" stroke-width="2.2"/></svg>';
  document.documentElement.appendChild(ring); document.documentElement.appendChild(tag); document.documentElement.appendChild(cur);
  function mv(x,y){cur.style.left=x+'px';cur.style.top=y+'px';}
  window.__steveCursorMove=mv;
  document.addEventListener('mousemove',function(e){mv(e.clientX,e.clientY);},true);
  ['mousedown','click'].forEach(function(t){document.addEventListener(t,function(e){
    var x=e.clientX,y=e.clientY;
    if(!x&&!y&&e.target&&e.target.getBoundingClientRect){var b=e.target.getBoundingClientRect();x=b.left+b.width/2;y=b.top+b.height/2;}
    mv(x,y);
    var r=document.createElement('div');r.style.cssText='position:fixed;left:'+x+'px;top:'+y+'px;width:12px;height:12px;margin:-6px 0 0 -6px;border-radius:50%;background:#e5484d;pointer-events:none;z-index:2147483647;opacity:.85;transition:all .5s';
    document.documentElement.appendChild(r);
    requestAnimationFrame(function(){r.style.width='46px';r.style.height='46px';r.style.margin='-23px 0 0 -23px';r.style.opacity='0';});
    setTimeout(function(){r.remove();},520);
  },true);});
})();`;

export const AGENT_OVERLAY_REMOVE = `(function(){['__steveAgentOverlay','__steveAgentTag','__steveAgentCursor','__steveAgentStyle'].forEach(function(id){var e=document.getElementById(id);if(e)e.remove();});window.__steveCursor=0;})();`;

/** Dispatched so Browser.svelte can highlight the active tab and re-inject the overlay on nav. */
export interface AgentActiveDetail { active: boolean; tabId: string }

/** Show the connection overlay on a tab and announce it (call when a spawn starts). */
export async function showAgentConnected(tabId: string): Promise<void> {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('steve:agent-active', { detail: { active: true, tabId } satisfies AgentActiveDetail }));
  }
  if (tabId) { try { await injectScript(AGENT_OVERLAY_SCRIPT, tabId); } catch { /* best-effort */ } }
}

/** Remove the overlay and announce disconnect (call when a spawn ends). */
export async function hideAgentConnected(tabId: string): Promise<void> {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('steve:agent-active', { detail: { active: false, tabId } satisfies AgentActiveDetail }));
  }
  if (tabId) { try { await injectScript(AGENT_OVERLAY_REMOVE, tabId); } catch { /* best-effort */ } }
}

// Teach Mode: record the user's real clicks/inputs in the embedded browser and fold them into a
// Workflow (types/site-profile.ts) — the exact shape replay-live.ts already self-heals and
// workflow-skill.ts already round-trips to a SKILL.md. The only new part of the whole feature is
// this capture layer: a script injected into the page reports each interaction, and the page talks
// back through a CDP Runtime binding (no polling). Selector anchors mirror selector-resolve.ts's
// resolvable kinds (data-testid / #id / role=name / [name] / css path) so replay can heal on drift.

import { cdp } from './cdp-client';
import { connectCDP, isConnected } from './cdp-actions';
import type { Workflow, WorkflowStep } from './types/site-profile';

const BINDING = '__steveTeach';

// Injected into every page (current + each navigation). ES5-ish and fully self-guarded: it runs in
// untrusted pages, must never throw into them, and must not double-install. It emits the same step
// shape WorkflowStep uses. Passwords are NEVER captured — the one hard line here.
export const RECORDER_SCRIPT = `(function(){
  if (window.__steveTeachOn) return;
  window.__steveTeachOn = true;
  function esc(s){return String(s).replace(/"/g,'\\\\"');}
  function label(el){
    var t=(el.getAttribute&&el.getAttribute('aria-label'))||el.textContent||'';
    return t.replace(/\\s+/g,' ').trim().slice(0,80);
  }
  function roleOf(el){
    var explicit=el.getAttribute&&el.getAttribute('role');
    if(explicit)return explicit;
    var map={A:'link',BUTTON:'button',INPUT:'textbox',TEXTAREA:'textbox',SELECT:'combobox'};
    return map[el.tagName]||'';
  }
  function cssPath(el){
    var parts=[],node=el;
    while(node&&node.nodeType===1&&parts.length<4){
      var sel=node.tagName.toLowerCase();
      if(node.id){parts.unshift('#'+node.id);break;}
      var p=node.parentNode;
      if(p&&p.children){
        var same=[];for(var i=0;i<p.children.length;i++){if(p.children[i].tagName===node.tagName)same.push(p.children[i]);}
        if(same.length>1){for(var j=0;j<same.length;j++){if(same[j]===node){sel+=':nth-of-type('+(j+1)+')';break;}}}
      }
      parts.unshift(sel);
      node=node.parentNode;
    }
    return parts.join(' > ');
  }
  function anchors(el){
    var out=[];
    var tid=el.getAttribute&&(el.getAttribute('data-testid')||el.getAttribute('data-test-id'));
    if(tid)out.push('[data-testid="'+esc(tid)+'"]');
    if(el.id&&!/\\d{4,}/.test(el.id))out.push('#'+el.id);
    var role=roleOf(el),name=label(el)||(el.getAttribute&&el.getAttribute('placeholder'))||'';
    if(role&&name)out.push('role='+role+'[name="'+esc(name)+'"]');
    var nm=el.getAttribute&&el.getAttribute('name');
    if(nm)out.push('[name="'+esc(nm)+'"]');
    out.push(cssPath(el));
    var seen={},res=[];
    for(var i=0;i<out.length;i++){if(out[i]&&!seen[out[i]]){seen[out[i]]=1;res.push(out[i]);}}
    return res;
  }
  // Weak signals for the heal ranker (fingerprint.ts). Captured here because this is the only
  // moment we KNOW which element the user meant; on a later drift the ranker scores every
  // candidate against these instead of stopping at the first selector that still matches.
  // Never throws into the page — a missing fingerprint just means the old heal path is used.
  function fp(el){
    try{
      var sibs=el.parentNode?Array.prototype.slice.call(el.parentNode.children):[];
      var idx=sibs.indexOf(el);
      var prev='';
      for(var i=idx-1;i>=0;i--){var tx=(sibs[i].textContent||'').replace(/\\s+/g,' ').trim();if(tx){prev=tx.slice(0,80);break;}}
      var o={tag:(el.tagName||'').toLowerCase(),siblingIndex:idx<0?0:idx};
      var role=roleOf(el);if(role)o.role=role;
      var nm=label(el);if(nm)o.name=nm;
      var tx2=(el.textContent||'').replace(/\\s+/g,' ').trim().slice(0,80);if(tx2)o.text=tx2;
      if(el.id)o.id=el.id;
      var hf=el.getAttribute&&el.getAttribute('href');if(hf)o.href=hf;
      if(prev)o.neighborText=prev;
      var cn=el.className;
      if(cn&&cn.baseVal!==undefined)cn=cn.baseVal;
      var cls=String(cn||'').trim().split(/\\s+/);
      var keep=[];for(var j=0;j<cls.length&&keep.length<6;j++){if(cls[j])keep.push(cls[j]);}
      if(keep.length)o.classes=keep;
      var r=el.getBoundingClientRect&&el.getBoundingClientRect();
      if(r&&r.width&&r.height){o.cx=Math.round(r.left+r.width/2);o.cy=Math.round(r.top+r.height/2);o.w=Math.round(r.width);o.h=Math.round(r.height);}
      return o;
    }catch(e){return null;}
  }
  function send(action,el,value){
    try{
      var a=anchors(el);
      if(!a.length)return;
      var step={action:action,selector:a[0],candidates:a.slice(1),
        description:label(el)||(el.getAttribute&&el.getAttribute('placeholder'))||el.tagName.toLowerCase()};
      var f=fp(el);if(f)step.fingerprint=f;
      if(value!=null)step.value=value;
      if(window.__steveTeach)window.__steveTeach(JSON.stringify(step));
    }catch(e){}
  }
  document.addEventListener('click',function(e){
    var el=e.target; if(!el||el.nodeType!==1)return;
    var t=el.closest?el.closest('a,button,[role=button],input[type=submit],input[type=button],label,summary,[onclick]'):null;
    var target=t||el;
    // text-like inputs are captured on 'change' (final value), not on the focusing click
    if(target.tagName==='INPUT'&&/^(text|email|password|number|search|tel|url|date)$/i.test(target.type||'text'))return;
    send('click',target,null);
  },true);
  document.addEventListener('change',function(e){
    var el=e.target; if(!el||el.nodeType!==1)return;
    var tag=el.tagName;
    if(tag==='SELECT'){send('select',el,el.value);return;}
    if(tag==='TEXTAREA'){send('fill',el,el.value);return;}
    if(tag==='INPUT'){
      var ty=(el.type||'text').toLowerCase();
      if(ty==='password')return;                 // never record passwords
      if(ty==='checkbox'||ty==='radio'){send('click',el,null);return;}
      send('fill',el,el.value);
    }
  },true);
})();`;

/**
 * Fold a new step into the running list. Consecutive fills on the SAME field collapse to the last
 * value (keystroke 'change' events would otherwise spam duplicates); everything else appends.
 * Pure so it's the one testable seam of the recorder.
 */
export function foldStep(steps: WorkflowStep[], step: WorkflowStep): WorkflowStep[] {
  const last = steps[steps.length - 1];
  if (last && last.action === 'fill' && step.action === 'fill' && last.selector === step.selector) {
    return [...steps.slice(0, -1), { ...last, value: step.value }];
  }
  return [...steps, step];
}

export function buildWorkflow(name: string, steps: WorkflowStep[], startUrl?: string): Workflow {
  return { name, trigger: startUrl ? `On ${startUrl}` : undefined, steps };
}

/** Live recorder over the embedded browser's CDP session. start() arms, stop() disarms. */
export class TeachRecorder {
  private steps: WorkflowStep[] = [];
  private scriptId: string | null = null;
  private onStep?: (steps: WorkflowStep[]) => void;

  private onBinding = (params: Record<string, unknown>) => {
    if (params.name !== BINDING) return;
    try {
      const step = JSON.parse(String(params.payload)) as WorkflowStep;
      if (!step || typeof step.action !== 'string') return;
      this.steps = foldStep(this.steps, step);
      this.onStep?.(this.steps);
    } catch {
      /* malformed payload — drop it */
    }
  };

  async start(onStep?: (steps: WorkflowStep[]) => void): Promise<void> {
    this.onStep = onStep;
    if (!isConnected() && !(await connectCDP())) {
      throw new Error('Could not connect to the browser — open a page first.');
    }
    await cdp.send('Runtime.enable');
    await cdp.send('Page.enable');
    cdp.on('Runtime.bindingCalled', this.onBinding);
    await cdp.send('Runtime.addBinding', { name: BINDING });
    const res = (await cdp.send('Page.addScriptToEvaluateOnNewDocument', { source: RECORDER_SCRIPT })) as {
      identifier?: string;
    };
    this.scriptId = res?.identifier ?? null;
    await cdp.send('Runtime.evaluate', { expression: RECORDER_SCRIPT }); // arm the page already loaded
  }

  async stop(): Promise<void> {
    cdp.off('Runtime.bindingCalled', this.onBinding);
    if (this.scriptId) {
      await cdp.send('Page.removeScriptToEvaluateOnNewDocument', { identifier: this.scriptId }).catch(() => {});
      this.scriptId = null;
    }
    // removeBinding makes window.__steveTeach undefined, so any listeners still live on the current
    // page just no-op (send() guards on it). The guard flag is cleared so a later start() re-arms.
    await cdp.send('Runtime.removeBinding', { name: BINDING }).catch(() => {});
    await cdp.send('Runtime.evaluate', { expression: 'window.__steveTeachOn=false' }).catch(() => {});
  }

  getSteps(): WorkflowStep[] {
    return this.steps;
  }
}

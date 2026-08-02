/**
 * The in-page overlay the page-agent loop shows while it drives a tab: an
 * ambient edge glow around the viewport plus a status pill at the bottom
 * (indicator dot, current activity, recent steps, Stop).
 *
 * Visual language mirrors upstream page-agent's `packages/ui` Panel and
 * `SimulatorMask` — same geometry (360×40, radius 12, bottom-centred), same
 * palette (blue rgb(57,182,255) / purple rgb(189,69,251) / green / red), same
 * animated blurred gradient behind the pill. Upstream renders its glow through
 * the `ai-motion` package; this draws it with an inset box-shadow instead
 * rather than pulling in a dependency for one effect.
 *
 * It attaches through the loop's existing callbacks, so the loop itself knows
 * nothing about it. Stop is polled out of the page (the button can only set a
 * flag in the tab) and turned into an abort on the controller the caller owns.
 */

import { AGENT_COLORS, AGENT_SWEEP, withAlpha } from './agent-visual';
import type { ToolContext } from './page-agent-tools';
import type { AgentActivity, AgentStatus, PageAgentLoopConfig } from './page-agent-loop';

const ROOT_ID = 'page-agent-overlay';
const STOP_FLAG = '__pageAgentStopRequested';

/** Indicator colours, keyed by the state the pill is showing. */
type PillState = 'thinking' | 'executing' | 'completed' | 'error' | 'stopped';

/**
 * Build the script that creates the overlay if absent, then applies one update.
 *
 * Idempotent on purpose: a navigation wipes the DOM, so every update re-asserts
 * the overlay rather than assuming the previous one survived.
 */
export function overlayUpdateScript(opts: {
  state: PillState;
  text: string;
  history: string[];
  task: string;
  /** Session accent, so two concurrent runs are distinguishable. */
  accent?: string;
  /**
   * Who currently has the wheel ("Claude", "Page agent"). Shown as a chip inside
   * the same pill rather than as a second overlay, so a handover reads as one
   * system changing hands instead of two systems taking turns.
   */
  role?: string;
  /** Step counter like "6/15", when the job knows its length. */
  progress?: string;
}): string {
  const payload = JSON.stringify(opts);
  const [c1, c2, c3, c4] = AGENT_SWEEP;
  const accent = opts.accent ?? AGENT_COLORS.thinking;
  return `(function(){
  var d = ${payload};
  var root = document.getElementById(${JSON.stringify(ROOT_ID)});
  if (!root) {
    root = document.createElement('div');
    root.id = ${JSON.stringify(ROOT_ID)};
    // Ignored by our own DOM extraction and by upstream page-agent's, so the
    // agent can never see or click its own UI.
    root.setAttribute('data-page-agent-ignore', 'true');
    root.setAttribute('data-browser-use-ignore', 'true');
    root.innerHTML = [
      '<style>',
      '#${ROOT_ID}{position:fixed;inset:0;z-index:2147483641;pointer-events:none;font:500 12px/1.3 system-ui,-apple-system,Segoe UI,sans-serif}',
      // Four colours, not two: upstream declares --color-3/4 (orange, amber) but only
      // sweeps blue and purple. Cycling all four reads as a rainbow rather than a
      // blue pulse, which is what makes 'the agent is driving' obvious at a glance.
      '#${ROOT_ID} .pa-glow{position:absolute;inset:0;pointer-events:none;animation:pa-rainbow 6s linear infinite}',
      '@keyframes pa-rainbow{'+
        '0%{box-shadow:inset 0 0 70px 10px ${withAlpha(c1, 0.42)},inset 0 0 170px 34px ${withAlpha(c2, 0.2)}}'+
        '25%{box-shadow:inset 0 0 70px 10px ${withAlpha(c2, 0.42)},inset 0 0 170px 34px ${withAlpha(c3, 0.2)}}'+
        '50%{box-shadow:inset 0 0 70px 10px ${withAlpha(c3, 0.42)},inset 0 0 170px 34px ${withAlpha(c4, 0.2)}}'+
        '75%{box-shadow:inset 0 0 70px 10px ${withAlpha(c4, 0.42)},inset 0 0 170px 34px ${withAlpha(c1, 0.2)}}'+
        '100%{box-shadow:inset 0 0 70px 10px ${withAlpha(c1, 0.42)},inset 0 0 170px 34px ${withAlpha(c2, 0.2)}}'+
      '}',
      '#${ROOT_ID} .pa-stack{position:absolute;bottom:28px;left:50%;transform:translateX(-50%);width:360px;max-width:calc(100vw - 40px);display:flex;flex-direction:column;gap:6px;pointer-events:auto}',
      '#${ROOT_ID} .pa-hist{display:flex;flex-direction:column;gap:4px}',
      // Opaque enough to stay legible on a white page: upstream's translucent
      // white-on-white card is unreadable outside a dark UI.
      '#${ROOT_ID} .pa-hist div{padding:6px 9px;border-radius:8px;color:#fff;background:rgba(2,0,20,.62);border-left:2px solid ${withAlpha(c1, 0.7)};backdrop-filter:blur(10px);box-shadow:0 1px 6px rgba(0,0,0,.35);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}',
      '#${ROOT_ID} .pa-pill{position:relative;height:40px;border-radius:12px}',
      '#${ROOT_ID} .pa-bg{position:absolute;inset:-2px -8px;border-radius:16px;filter:blur(16px);overflow:hidden}',
      '#${ROOT_ID} .pa-bg::before,#${ROOT_ID} .pa-bg::after{content:"";position:absolute;inset:0;background-image:linear-gradient(to bottom left,${c1},${c2},${c3},${c4},${c1});animation:pa-run 2s linear infinite}',
      '#${ROOT_ID} .pa-bg::after{animation-delay:1s;background-image:linear-gradient(to bottom left,${c4},${c3},${c2},${c1},${c4})}',
      '@keyframes pa-run{from{transform:translateX(-100%)}to{transform:translateX(100%)}}',
      '#${ROOT_ID} .pa-head{position:absolute;inset:0;display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:12px;background:rgba(0,0,0,.5);backdrop-filter:blur(10px);box-shadow:0 0 0 2px ${withAlpha(accent, 0.55) === accent ? accent : withAlpha(accent, 0.55)},0 0 5px 1px rgba(255,255,255,.3)}',
      '#${ROOT_ID} .pa-dot{width:6px;height:6px;border-radius:50%;flex:0 0 auto;background:rgba(255,255,255,.5)}',
      '#${ROOT_ID} .pa-dot.thinking{background:${AGENT_COLORS.thinking};animation:pa-pulse .8s ease-in-out infinite}',
      '#${ROOT_ID} .pa-dot.executing{background:${AGENT_COLORS.executing};animation:pa-pulse .6s ease-in-out infinite}',
      '#${ROOT_ID} .pa-dot.completed{background:${AGENT_COLORS.completed}}',
      '#${ROOT_ID} .pa-dot.error,#${ROOT_ID} .pa-dot.stopped{background:${AGENT_COLORS.error}}',
      '@keyframes pa-pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(1.3)}}',
      '#${ROOT_ID} .pa-role{flex:0 0 auto;color:#fff;font-weight:700;font-size:11px;padding:2px 7px;border-radius:6px;background:${withAlpha(accent, 0.28)};box-shadow:inset 0 0 0 1px ${withAlpha(accent, 0.5)};white-space:nowrap}',
      '#${ROOT_ID} .pa-prog{flex:0 0 auto;color:rgba(255,255,255,.75);font-variant-numeric:tabular-nums;font-size:11px}',
      '#${ROOT_ID} .pa-text{flex:1;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}',
      '#${ROOT_ID} .pa-stop{height:24px;padding:0 8px;border:0;border-radius:4px;font:600 11px/1 system-ui;cursor:pointer;background:${withAlpha(AGENT_COLORS.error, 0.2)};color:${AGENT_COLORS.error}}',
      '#${ROOT_ID} .pa-stop:hover{background:rgba(239,68,68,.35)}',
      '#${ROOT_ID} .pa-stop[disabled]{opacity:.5;cursor:default}',
      '</style>',
      '<div class="pa-glow"></div>',
      '<div class="pa-stack">',
      '<div class="pa-hist"></div>',
      '<div class="pa-pill"><div class="pa-bg"></div><div class="pa-head">',
      '<span class="pa-dot"></span><span class="pa-role"></span><span class="pa-prog"></span><span class="pa-text"></span>',
      '<button class="pa-stop" type="button">Stop</button>',
      '</div></div></div>',
    ].join('');
    (document.body || document.documentElement).appendChild(root);
    root.querySelector('.pa-stop').addEventListener('click', function () {
      window[${JSON.stringify(STOP_FLAG)}] = true;
      this.disabled = true;
      this.textContent = 'Stopping';
    });
  }
  root.title = d.task;
  var dot = root.querySelector('.pa-dot');
  dot.className = 'pa-dot ' + d.state;
  root.querySelector('.pa-text').textContent = d.text;
  var roleEl = root.querySelector('.pa-role');
  roleEl.textContent = d.role || '';
  roleEl.style.display = d.role ? '' : 'none';
  var progEl = root.querySelector('.pa-prog');
  progEl.textContent = d.progress || '';
  progEl.style.display = d.progress ? '' : 'none';
  var glow = root.querySelector('.pa-glow');
  glow.style.display = (d.state === 'thinking' || d.state === 'executing') ? '' : 'none';
  var hist = root.querySelector('.pa-hist');
  hist.innerHTML = '';
  d.history.forEach(function (line) {
    var el = document.createElement('div');
    el.textContent = line;
    hist.appendChild(el);
  });
  return !!window[${JSON.stringify(STOP_FLAG)}];
})()`;
}

/**
 * Remove the overlay, clear the stop flag, and take the index stamps back off.
 *
 * The stamps are ours, not the page's: a finished run left ~55 `data-pa-index`
 * attributes on a live MyOpenMath page. Nothing breaks — extraction clears them
 * before restamping — but leaving markup behind on a real teaching site after
 * the agent has gone is residue, and teardown is the place to not do that.
 */
export const OVERLAY_REMOVE_SCRIPT = `(function(){
  var e = document.getElementById(${JSON.stringify(ROOT_ID)});
  if (e) e.remove();
  document.querySelectorAll('[data-pa-index]').forEach(function(el){ el.removeAttribute('data-pa-index'); });
  window[${JSON.stringify(STOP_FLAG)}] = false;
})()`;

/** One activity rendered as the pill's status line. */
export function describeActivity(a: AgentActivity): string {
  switch (a.type) {
    case 'thinking':
      return 'Thinking…';
    case 'executing':
      return `Running ${a.tool}${summariseInput(a.input)}`;
    case 'executed':
      return `${a.tool} → ${oneLine(a.output, 60)} (${(a.duration / 1000).toFixed(1)}s)`;
    case 'error':
      return `Error: ${oneLine(a.message, 70)}`;
  }
}

function summariseInput(input: unknown): string {
  if (input === null || input === undefined) return '';
  const s = typeof input === 'object' ? JSON.stringify(input) : String(input);
  return s === '{}' ? '' : ` ${oneLine(s, 40)}`;
}

function oneLine(s: string, max: number): string {
  const flat = String(s).replace(/\s+/g, ' ').trim();
  return flat.length > max ? flat.slice(0, max - 1) + '…' : flat;
}

/**
 * Wire the overlay to a loop run.
 *
 * Returns the subset of PageAgentLoopConfig the caller spreads into its config,
 * plus a dispose() that tears the overlay down. Pressing Stop in the page
 * aborts `controller`, which the loop already honours through ctx.signal.
 */
export function attachPageAgentOverlay(
  ctx: ToolContext,
  controller: AbortController,
  task: string,
  historyLines = 3,
  /** Session accent (SESSION_COLORS) — rings the pill so two live runs are told apart. */
  accent?: string,
): Pick<PageAgentLoopConfig, 'onStatusChange' | 'onActivity'> & { dispose: () => Promise<void> } {
  const recent: string[] = [];
  let state: PillState = 'thinking';
  let text = 'Starting…';

  const push = async () => {
    try {
      const res = (await ctx.cdpSend('Runtime.evaluate', {
        expression: overlayUpdateScript({ state, text, history: recent.slice(-historyLines), task, accent }),
        returnByValue: true,
      })) as { result?: { value?: boolean } };
      // The button lives in the page; this is the only way its click reaches us.
      if (res.result?.value && !controller.signal.aborted) controller.abort();
    } catch {
      // A navigation mid-update tears down the context — the next step re-asserts it.
    }
  };

  return {
    onStatusChange: (s: AgentStatus) => {
      if (s === 'completed') { state = 'completed'; text = 'Done'; }
      else if (s === 'error') { state = 'error'; text = 'Failed'; }
      else if (s === 'stopped') { state = 'stopped'; text = 'Stopped'; }
      void push();
    },
    onActivity: (a: AgentActivity) => {
      text = describeActivity(a);
      if (a.type === 'thinking') state = 'thinking';
      else if (a.type === 'executing') state = 'executing';
      else if (a.type === 'error') state = 'error';
      if (a.type === 'executed' || a.type === 'error') recent.push(text);
      void push();
    },
    dispose: async () => {
      await ctx.cdpSend('Runtime.evaluate', { expression: OVERLAY_REMOVE_SCRIPT, returnByValue: true })
        .catch(() => undefined);
    },
  };
}

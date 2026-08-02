/**
 * One presence for a whole job, instead of one per run.
 *
 * The pieces were all built correctly and still felt like separate systems,
 * because they had three unrelated lifecycles:
 *
 *   - the tab claim (cursor, tab outline) started at claimTabForRun
 *   - the glow and pill started at attachPageAgentOverlay and were DISPOSED at
 *     the end of every single run, so a task list tore its own UI down and
 *     rebuilt it between every step
 *   - the conductor's own work — Claude reading the page and deciding — drew
 *     nothing at all, so the tab navigated and changed with no agent visible
 *
 * The result reads as "something plugged in, did a thing, and switched off"
 * repeatedly, rather than one system working. This module owns all three for
 * the length of a job: opened once, closed once, and in between it only ever
 * changes ROLE — who currently has the wheel.
 *
 * Two things make it feel continuous rather than merely present:
 *
 *  - a heartbeat re-asserts the overlay on a timer, so it survives navigation
 *    and stays up during long conductor pauses when nothing is calling back
 *  - the role, step counter and task never leave the pill, so handing the wheel
 *    from Claude to the driver model changes a label inside one persistent
 *    chrome instead of destroying one UI and creating another
 */

import { AGENT_COLORS, SESSION_COLORS } from './agent-visual';
import { overlayUpdateScript, OVERLAY_REMOVE_SCRIPT, describeActivity } from './page-agent-overlay';
import type { ToolContext } from './page-agent-tools';
import type { AgentActivity, AgentStatus, PageAgentLoopConfig } from './page-agent-loop';

/** Who has the wheel right now. */
export type AgentRole =
  /** The orchestrator is reading, deciding, or checking — no page action in flight. */
  | 'conductor'
  /** The small driver model is acting in the page. */
  | 'driver'
  /** Open but idle: waiting on a page, a load, or the user. */
  | 'waiting';

export interface AgentSessionOptions {
  /** Job label, shown for the whole session. */
  task: string;
  /** Session accent (SESSION_COLORS). Defaults to the first. */
  accent?: string;
  /** Total steps, when known up front — drives the "3/15" counter. */
  totalSteps?: number;
  /** History lines kept above the pill. */
  historyLines?: number;
  /** Heartbeat period. 0 disables it (tests). */
  heartbeatMs?: number;
}

export interface AgentSession {
  readonly accent: string;
  /** Hand the wheel over. `text` is the status line. */
  setRole(role: AgentRole, text: string): void;
  /** Update the step counter. */
  setProgress(step: number, total?: number): void;
  /** Add a line to the recent-history stack. */
  note(line: string): void;
  /**
   * Hooks for a page-agent run to JOIN this session rather than attach its own
   * overlay. The run drives the role to 'driver' while it acts and hands the
   * wheel back to the conductor when it finishes — it never tears the UI down.
   */
  join(): Pick<PageAgentLoopConfig, 'onActivity' | 'onStatusChange'>;
  /** Close the session: final state, brief pause so it is readable, then remove. */
  end(status: 'done' | 'error' | 'stopped', text?: string): Promise<void>;
}

/** Pill states the overlay script understands. */
type PillState = 'thinking' | 'executing' | 'completed' | 'error' | 'stopped';

const ROLE_STATE: Record<AgentRole, PillState> = {
  conductor: 'thinking',
  driver: 'executing',
  waiting: 'thinking',
};

const ROLE_LABEL: Record<AgentRole, string> = {
  conductor: 'Claude',
  driver: 'Page agent',
  waiting: 'Waiting',
};

/**
 * Open a session presence on the tab `ctx` is attached to.
 *
 * `controller` is aborted if the user presses Stop in the page, exactly as the
 * per-run overlay did — the button lives in the page and polling its flag on the
 * heartbeat is the only way its click reaches the app.
 */
export function beginAgentSession(
  ctx: ToolContext,
  controller: AbortController,
  opts: AgentSessionOptions,
): AgentSession {
  const accent = opts.accent ?? SESSION_COLORS[0];
  const historyLines = opts.historyLines ?? 3;
  const heartbeatMs = opts.heartbeatMs ?? 1500;

  const recent: string[] = [];
  let role: AgentRole = 'conductor';
  let state: PillState = 'thinking';
  let text = 'Starting…';
  let step = 0;
  let total = opts.totalSteps ?? 0;
  let closed = false;

  const push = async () => {
    if (closed) return;
    try {
      const progress = total > 0 ? `${Math.min(step, total)}/${total}` : '';
      const res = (await ctx.cdpSend('Runtime.evaluate', {
        expression: overlayUpdateScript({
          state,
          text,
          history: recent.slice(-historyLines),
          task: opts.task,
          accent,
          role: ROLE_LABEL[role],
          progress,
        }),
        returnByValue: true,
      })) as { result?: { value?: boolean } };
      if (res.result?.value && !controller.signal.aborted) controller.abort();
    } catch {
      // A navigation tears the context down mid-push; the next heartbeat re-asserts.
    }
  };

  // The heartbeat is what makes the presence feel continuous: it re-paints after
  // a navigation wipes the DOM and keeps the pill alive through conductor pauses
  // where no callback fires for tens of seconds.
  const timer =
    heartbeatMs > 0 && typeof setInterval === 'function'
      ? setInterval(() => void push(), heartbeatMs)
      : null;

  void push();

  return {
    accent,
    setRole(next, nextText) {
      role = next;
      state = ROLE_STATE[next];
      text = nextText;
      void push();
    },
    setProgress(nextStep, nextTotal) {
      step = nextStep;
      if (nextTotal !== undefined) total = nextTotal;
      void push();
    },
    note(line) {
      recent.push(line);
      void push();
    },
    join() {
      return {
        onActivity: (a: AgentActivity) => {
          text = describeActivity(a);
          // Only an actual page action counts as the driver holding the wheel;
          // its thinking is still the model's, but it reads as one system if the
          // pill keeps saying who is acting rather than flipping every token.
          if (a.type === 'executing') {
            role = 'driver';
            state = 'executing';
          } else if (a.type === 'thinking') {
            role = 'driver';
            state = 'thinking';
          } else if (a.type === 'error') {
            state = 'error';
          }
          if (a.type === 'executed' || a.type === 'error') recent.push(text);
          void push();
        },
        onStatusChange: (s: AgentStatus) => {
          // A finished RUN is not a finished SESSION: hand the wheel back to the
          // conductor instead of showing a terminal state and disappearing.
          if (s === 'completed') {
            role = 'conductor';
            state = 'thinking';
            text = 'Checking the page…';
          } else if (s === 'error') {
            state = 'error';
            text = 'Step failed — handing back';
          } else if (s === 'stopped') {
            state = 'stopped';
            text = 'Stopped';
          }
          void push();
        },
      };
    },
    async end(status, endText) {
      if (closed) return;
      state = status === 'done' ? 'completed' : status === 'error' ? 'error' : 'stopped';
      text = endText ?? (status === 'done' ? 'Done' : status === 'error' ? 'Failed' : 'Stopped');
      role = 'waiting';
      await push();
      closed = true;
      if (timer) clearInterval(timer);
      // Let the final state be readable before the page goes back to normal.
      await new Promise((r) => setTimeout(r, 1200));
      await ctx
        .cdpSend('Runtime.evaluate', { expression: OVERLAY_REMOVE_SCRIPT, returnByValue: true })
        .catch(() => undefined);
    },
  };
}

/** Status colour for a role, for any app-side chrome that wants to match the pill. */
export function roleColor(role: AgentRole): string {
  return role === 'driver' ? AGENT_COLORS.executing : AGENT_COLORS.thinking;
}

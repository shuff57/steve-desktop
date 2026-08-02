/**
 * The conductor contract: an orchestrator (Claude) hands down an ordered list of
 * steps, a small model executes each one in the page, and a single structured
 * report comes back saying exactly which step failed and why.
 *
 * The split that makes this work: the STEP is written by the orchestrator and
 * carried out by the model, but whether it WORKED is decided by `check` — a JS
 * expression evaluated in the page returning {ok, detail}. No model grades its
 * own homework. A run where the agent cheerfully reports success while the page
 * never changed comes back here as ok:false, which is the whole point.
 */

import { runAgentLoop, type PageAgentLoopConfig } from './page-agent-loop';
import type { AgentSession } from './agent-session';
import type { ToolContext } from './page-agent-tools';

export interface AgentStep {
  /** Stable label for the report — defaults to the step's position. */
  id?: string;
  /** What the model should do, in plain language. */
  task: string;
  /** Navigate here before running the step. */
  url?: string;
  /**
   * JS evaluated in the page after the step, returning JSON `{ok, detail?}`.
   * Absent means the step is unchecked — recorded as such, never as a pass.
   */
  check?: string;
  /** Step ceiling for this step. Defaults to the run's. */
  maxSteps?: number;
}

export interface StepReport {
  index: number;
  id: string;
  task: string;
  /** The verdict the orchestrator should act on: the check, or the agent when unchecked. */
  ok: boolean;
  /** What the model claimed. Kept separate from `ok` on purpose. */
  agentSuccess: boolean;
  agentData: string;
  /** Result of the deterministic check, absent when the step had none. */
  check?: { ok: boolean; detail: string };
  checked: boolean;
  actions: number;
  seconds: number;
}

export interface TaskListReport {
  ok: boolean;
  completed: number;
  total: number;
  /** Index of the first step that failed, or null when all passed. */
  failedAt: number | null;
  steps: StepReport[];
}

export interface RunTaskListOptions
  extends Omit<PageAgentLoopConfig, 'task' | 'onActivity' | 'onStatusChange'> {
  /** Keep going after a failed step instead of stopping. Default false. */
  continueOnFailure?: boolean;
  /** Called as each step finishes, so a UI can show progress live. */
  onStepDone?: (report: StepReport) => void;
  /** Forwarded per step so callers can still drive an overlay. */
  onActivity?: PageAgentLoopConfig['onActivity'];
  onStatusChange?: PageAgentLoopConfig['onStatusChange'];
  /**
   * An open session presence to drive. Given one, the list reports its own
   * progress and hands the wheel back and forth, so the user sees one continuous
   * agent working through N steps rather than a UI that rebuilds every step.
   * The session is NOT closed here — it belongs to whoever opened it.
   */
  session?: AgentSession;
}

/** Evaluate a step's check in the page. A throwing or malformed check is a failure, never a pass. */
async function runCheck(
  ctx: ToolContext,
  expression: string,
): Promise<{ ok: boolean; detail: string }> {
  try {
    const res = (await ctx.cdpSend('Runtime.evaluate', {
      expression,
      returnByValue: true,
      awaitPromise: true,
    })) as { result?: { value?: unknown }; exceptionDetails?: unknown };

    if (res.exceptionDetails) return { ok: false, detail: 'check threw in the page' };

    const value = res.result?.value;
    if (typeof value === 'boolean') return { ok: value, detail: value ? 'ok' : 'check returned false' };
    const parsed = typeof value === 'string' ? JSON.parse(value) : value;
    if (parsed && typeof parsed === 'object' && 'ok' in parsed) {
      const p = parsed as { ok: unknown; detail?: unknown };
      return { ok: !!p.ok, detail: String(p.detail ?? (p.ok ? 'ok' : 'check reported not ok')) };
    }
    return { ok: false, detail: `check returned an unusable value: ${JSON.stringify(value)?.slice(0, 120)}` };
  } catch (e) {
    return { ok: false, detail: `check failed: ${e instanceof Error ? e.message : String(e)}` };
  }
}

/**
 * Run an ordered task list and report on every step.
 *
 * Stops at the first failed step by default: later steps usually assume the
 * earlier ones landed, so running them on a broken page produces noise rather
 * than information. The orchestrator gets `failedAt` and decides what to change.
 */
export async function runTaskList(
  steps: AgentStep[],
  options: RunTaskListOptions,
  ctx: ToolContext,
): Promise<TaskListReport> {
  const reports: StepReport[] = [];
  let failedAt: number | null = null;
  const session = options.session;
  // Joining once, not per step, is the point: the presence outlives every
  // individual run so nothing is torn down between steps.
  const joined = session?.join();

  for (let i = 0; i < steps.length; i++) {
    const step = steps[i];
    const started = Date.now();
    let actions = 0;

    session?.setProgress(i + 1, steps.length);

    if (step.url) {
      session?.setRole('waiting', 'Opening the page…');
      await ctx.navigate(step.url);
      await ctx.waitForLoad();
    }

    session?.setRole('driver', step.task.slice(0, 80));

    const res = await runAgentLoop(
      {
        ...options,
        task: step.task,
        maxSteps: step.maxSteps ?? options.maxSteps,
        onActivity: (a) => {
          if (a.type === 'executing') actions += 1;
          joined?.onActivity?.(a);
          options.onActivity?.(a);
        },
        onStatusChange: (s) => {
          joined?.onStatusChange?.(s);
          options.onStatusChange?.(s);
        },
      },
      ctx,
    );

    // The check is the conductor's judgement, so the pill says so while it runs.
    if (step.check) session?.setRole('conductor', 'Checking the page…');
    const check = step.check ? await runCheck(ctx, step.check) : undefined;
    if (check) session?.note(`${check.ok ? 'PASS' : 'FAIL'} ${step.id ?? `step-${i + 1}`}`);
    const report: StepReport = {
      index: i,
      id: step.id ?? `step-${i + 1}`,
      task: step.task,
      // An unchecked step can only fall back on the model's own word, which is
      // why `checked` travels with the verdict rather than being implied by it.
      ok: check ? check.ok : res.success,
      agentSuccess: res.success,
      agentData: String(res.data ?? '').slice(0, 500),
      check,
      checked: !!check,
      actions,
      seconds: Math.round((Date.now() - started) / 1000),
    };

    reports.push(report);
    options.onStepDone?.(report);

    if (!report.ok) {
      failedAt = failedAt ?? i;
      if (!options.continueOnFailure) break;
    }
  }

  return {
    ok: failedAt === null && reports.length === steps.length,
    completed: reports.filter((r) => r.ok).length,
    total: steps.length,
    failedAt,
    steps: reports,
  };
}

/**
 * Compact the report into something worth handing back to an orchestrator.
 * Full agent chatter is noise; what changes the next decision is which step
 * failed, whether a check or the model said so, and what the page reported.
 */
export function summariseReport(report: TaskListReport): string {
  const lines = report.steps.map((s) => {
    const verdict = s.ok ? 'PASS' : 'FAIL';
    const how = s.checked ? `check: ${s.check?.detail}` : 'UNCHECKED — model self-report only';
    return `${verdict} [${s.id}] ${s.task.slice(0, 70)} — ${how} (${s.actions} actions, ${s.seconds}s)`;
  });
  const head = report.ok
    ? `All ${report.total} steps passed.`
    : `Stopped at step ${(report.failedAt ?? 0) + 1} of ${report.total}.`;
  return [head, ...lines].join('\n');
}

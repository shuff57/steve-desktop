/**
 * The page tool: what the orchestrating CLI calls instead of speaking CDP itself.
 *
 * Claude runs the task; when it needs the page, it calls in here. Everything that goes back out
 * is masked, and everything coming in is rehydrated against the SAME run mask, so a token means
 * one person for the whole task. Taking the CDP port away from the CLI is what makes that a
 * boundary rather than a convention — with no port there is no raw-read path to fall back to.
 *
 * Two grains, and the second is not a preference:
 *
 *   page_task    a sub-task the in-app Re-Act loop carries out on its own model
 *   primitives   read / click / type / navigate, driven by the orchestrator directly
 *
 * `page_task` is the cheap path — one hop instead of one per action. But its loop runs on an
 * OpenAI-compatible endpoint (Ollama), which the Claude engine is not, so that model can be
 * down, rate-limited, or simply weaker than the orchestrator. When the endpoint fails, the task
 * does NOT fail: the tool degrades to the primitives, hands back the masked page, and the
 * orchestrator drives the rest itself. Same masking either way.
 */

import { extractBrowserState } from './page-agent-dom';
import { runPageAgentTask, type Confinement } from './page-agent-run';
import { maskForRun, urlFromHeader } from './page-agent-mask';
import { isTransportFailure } from '../integrations/mom/page-agent-config';
import { DEFAULT_TOOLS, executeTool, type ToolContext } from './page-agent-tools';

/** The page as the orchestrator is allowed to see it. */
export async function readPage(runId: string, ctx: ToolContext): Promise<string> {
  const state = await extractBrowserState(ctx.cdpSend);
  const mask = maskForRun(runId);
  const url = urlFromHeader(state.header);
  return mask.text(`${state.header}\n${state.content}\n${state.footer}`, url);
}

export interface PageTaskResult {
  ok: boolean;
  /** The agent's own account of what it did, masked. */
  report: string;
  /**
   * The page AFTER the work, masked.
   *
   * Sent every time, deliberately. A sub-task agent reporting its own success is the weakest
   * evidence available — one live run reported a student selected while the control had not
   * moved. Returning the state lets the orchestrator check the claim instead of trusting it.
   */
  page: string;
  /** Set when the sub-task model was unreachable and the orchestrator should drive instead. */
  degraded?: string;
}

export interface PageTaskOptions {
  baseURL: string;
  model: string;
  confine?: Confinement;
  map?: string;
  maxSteps?: number;
}

/**
 * Run a sub-task on the page and report back.
 *
 * `runId` keys the mask, so pass the orchestrating run's session id — not a fresh id per call,
 * or the tokens renumber between calls and the orchestrator's instructions land on the wrong
 * person.
 */
export async function handlePageTask(
  runId: string,
  task: string,
  ctx: ToolContext,
  opts: PageTaskOptions,
): Promise<PageTaskResult> {
  const mask = maskForRun(runId);
  let result;
  try {
    result = await runPageAgentTask(
      { task, baseURL: opts.baseURL, model: opts.model, mode: 'execute', mask, confine: opts.confine, map: opts.map, maxSteps: opts.maxSteps },
      ctx,
    );
  } catch (e) {
    // A thrown error never reached the model at all — treat it the same as a dead endpoint.
    return degrade(runId, ctx, e instanceof Error ? e.message : String(e));
  }
  // The loop reports a dead endpoint as a failed run rather than by throwing, so the transport
  // check is on the result text, not only on the catch above.
  if (!result.success && isTransportFailure(result.data)) {
    return degrade(runId, ctx, result.data);
  }
  return {
    ok: result.success,
    report: mask.text(result.data),
    page: await readPage(runId, ctx),
  };
}

/** Hand the page back and tell the orchestrator to drive it directly. */
async function degrade(runId: string, ctx: ToolContext, why: string): Promise<PageTaskResult> {
  return {
    ok: false,
    report: '',
    page: await readPage(runId, ctx),
    degraded:
      `The sub-task model is unavailable (${why}). Nothing was attempted. ` +
      `Do this yourself with page_click / page_type / page_navigate against the element ` +
      `indexes below, calling page_read between actions to see the result.`,
  };
}

/**
 * One primitive action, addressed the way the orchestrator saw the page.
 *
 * The action's parameters are rehydrated before execution, so an instruction naming ⟦STU3⟧ or a
 * URL carrying ⟦PID7⟧ reaches the page as the real value — and the output is masked on the way
 * back, because a tool's own reply can carry a roster (a dropdown's option list is one).
 */
export async function handlePageAction(
  runId: string,
  name: string,
  input: Record<string, unknown>,
  ctx: ToolContext,
): Promise<string> {
  const mask = maskForRun(runId);
  const action = mask.rehydrate({ [name]: input ?? {} });
  const { output } = await executeTool(DEFAULT_TOOLS, ctx, action);
  return mask.text(output);
}

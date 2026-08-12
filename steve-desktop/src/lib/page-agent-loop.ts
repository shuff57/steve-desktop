// Adapted from page-agent (MIT, Copyright (c) 2025 Alibaba Group Holding Limited)
// Re-Act loop pattern adapted from packages/core/src/PageAgentCore.ts
// See LICENSE-page-agent.txt in this directory for full notice.

/**
 * The CDP-driven Re-Act loop driver. This is the core of our vendored
 * page-agent: it runs the observe → think → act cycle, but instead of
 * page-agent's in-page PageController, it uses the app's existing CDP
 * connection to extract DOM state and execute actions.
 *
 * Multi-page support: because the app controls navigation via CDP
 * (Page.navigate + waitForLoad), the loop survives page transitions
 * — the state (task, history) lives in the app, not in the page.
 *
 * The LLM is any OpenAI-compatible endpoint (Ollama, OpenAI, etc.).
 * The default config targets Ollama Cloud's deepseek-v4-flash.
 */

import {
  PAGE_AGENT_SYSTEM_PROMPT,
  assembleUserPrompt,
  type HistoryEntry,
  type BrowserState,
} from './page-agent-prompt';
import {
  DEFAULT_TOOLS,
  describeTools,
  executeTool,
  makeAskUserTool,
  type PageAgentTool,
  type ToolContext,
  type MacroToolOutput,
} from './page-agent-tools';
import { extractBrowserState } from './page-agent-dom';
import { normalizeMacroOutput } from './page-agent-normalize';
import { urlFromHeader, type PageMask } from './page-agent-mask';

export interface PageAgentLoopConfig {
  /** The task sentence — what the agent should accomplish. */
  task: string;
  /** LLM endpoint (OpenAI-compatible). */
  baseURL: string;
  /** Model name. */
  model: string;
  /** API key (use 'NA' for local Ollama). */
  apiKey?: string;
  /** DeepSeek and some Ollama models reject named tool_choice — strip it. */
  disableNamedToolChoice?: boolean;
  /** Skill-specific system instructions, injected as <instructions>. */
  instructions?: string;
  /** Custom tools to add or override. null removes a built-in. */
  customTools?: Record<string, PageAgentTool<any> | null>;
  /**
   * Hard ceiling on steps. Default 40.
   *
   * Keep this generous. It is a runaway backstop, not a budget — a misclick can
   * cost several steps to notice and undo, and a task that legitimately needs
   * more should not be killed for it. `stallLimit` is what ends a run that is
   * going nowhere, and it ends it far sooner than this ever would.
   */
  maxSteps?: number;
  /**
   * Give up after this many consecutive steps that made no progress. Default 4.
   *
   * A step is unproductive when its tool failed, or when it repeats the previous
   * step's action exactly. Observed live: deepseek re-sent the same
   * `select_dropdown_option` nine times against an element that was never going
   * to be found. Raising maxSteps alone just buys a longer version of that.
   */
  stallLimit?: number;
  /** Delay between steps in seconds. Default 0.4. */
  stepDelay?: number;
  /** Called on each status change. */
  onStatusChange?: (status: AgentStatus) => void;
  /** Called on each activity (thinking, executing, etc). */
  onActivity?: (activity: AgentActivity) => void;
  /** Called when a malformed reply had to be repaired — silence would hide a flaky model. */
  onRepair?: (repairs: string[]) => void;
  /**
   * The trust boundary. Without it, everything on the page goes to the model verbatim —
   * on a gradebook or roster that is student data leaving the machine.
   *
   * Applied to the whole assembled prompt rather than to the observation alone, because a
   * tool's output becomes history and history is re-sent every step. Actions come back
   * addressed by token and are rehydrated before they touch the page, so the agent can still
   * act on what it was shown. See createPageMask.
   */
  mask?: PageMask;
  /**
   * Answer a question from the agent. Supplying this adds the ask_user tool;
   * without it the tool is not offered at all, since an unanswerable question
   * would hang the run.
   */
  onAskUser?: (question: string, signal: AbortSignal) => Promise<string>;
  /** Called on each history update. */
  onHistoryUpdate?: (history: HistoryEntry[]) => void;
}

export type AgentStatus = 'idle' | 'running' | 'completed' | 'error' | 'stopped';

export type AgentActivity =
  | { type: 'thinking' }
  | { type: 'executing'; tool: string; input: unknown }
  | { type: 'executed'; tool: string; output: string; duration: number }
  | { type: 'error'; message: string };

export interface ExecutionResult {
  success: boolean;
  data: string;
  history: HistoryEntry[];
}

interface LLMMessage {
  role: 'system' | 'user' | 'assistant' | 'tool';
  content?: string | null;
}

interface LLMToolDef {
  type: 'function';
  function: {
    name: string;
    description: string;
    parameters: Record<string, unknown>;
  };
}

/**
 * Did this step move the task forward?
 *
 * The tools report their own failures in the string they return (that is the
 * only channel the model sees), so this reads them the same way the model does.
 */
export function isUnproductiveOutput(output: string): boolean {
  return /^❌|Unknown tool|not found|No option matching|without a valid index|failed/i.test(
    (output ?? '').trim(),
  );
}

/** A stable key for "the same action again", used to catch a model looping. */
export function actionKeyOf(name: string, input: unknown): string {
  try {
    return `${name}:${JSON.stringify(input ?? null)}`;
  } catch {
    return `${name}:?`;
  }
}

/**
 * Wait-family tools are exempt from the "repeated action = stall" check: re-issuing
 * wait_for_condition after a timeout (try again, or wait through the next segment of a
 * multi-part video) is often the correct next step, not a model going in circles. A tool
 * that reports its own failure via isUnproductiveOutput still counts against stall either way.
 */
const WAIT_TOOL_NAMES = new Set(['wait', 'wait_for_condition']);

/** Does this step's action key match the previous step's, in a way that counts as a stall? */
export function isRepeatedStallAction(name: string, key: string, lastActionKey: string): boolean {
  return key === lastActionKey && !WAIT_TOOL_NAMES.has(name);
}

/**
 * Run the page-agent Re-Act loop until done, a stall, or maxSteps.
 *
 * @param config — task + LLM config + skill narrowing
 * @param ctx — CDP context (from the app's connected cdp client)
 * @returns ExecutionResult with success, data, and full history
 */
export async function runAgentLoop(
  config: PageAgentLoopConfig,
  ctx: ToolContext,
): Promise<ExecutionResult> {
  const maxSteps = config.maxSteps ?? 40;
  const stepDelay = config.stepDelay ?? 0.4;
  const signal = ctx.signal;

  // Build the tool set: defaults + customs (null removes)
  const tools: PageAgentTool<any>[] = [...DEFAULT_TOOLS];
  if (config.customTools) {
    for (const [name, tool] of Object.entries(config.customTools)) {
      if (tool === null) {
        const idx = tools.findIndex((t) => t.name === name);
        if (idx >= 0) tools.splice(idx, 1);
      } else {
        const idx = tools.findIndex((t) => t.name === name);
        if (idx >= 0) tools[idx] = tool;
        else tools.push(tool);
      }
    }
  }

  // Only offered when someone is listening — see onAskUser.
  if (config.onAskUser) tools.push(makeAskUserTool(config.onAskUser));

  const stallLimit = Math.max(2, config.stallLimit ?? 4);

  const history: HistoryEntry[] = [];
  const observations: string[] = [];
  let lastURL = '';
  let totalWaitTime = 0;
  let stall = 0;
  let lastActionKey = '';
  let producedAction = false;

  const setStatus = (s: AgentStatus) => config.onStatusChange?.(s);
  const emitActivity = (a: AgentActivity) => config.onActivity?.(a);
  const emitHistory = () => config.onHistoryUpdate?.([...history]);

  setStatus('running');

  try {
    for (let step = 0; step < maxSteps; step++) {
      signal.throwIfAborted();

      if (step > 0) await new Promise((r) => setTimeout(r, stepDelay * 1000));
      signal.throwIfAborted();

      // --- OBSERVE ---
      emitActivity({ type: 'thinking' });

      const browserState: BrowserState = await extractBrowserState(ctx.cdpSend);

      // Detect URL change
      const currentURL = browserState.header;
      if (currentURL !== lastURL && step > 0) {
        observations.push(`Page navigated to → ${currentURL}`);
        lastURL = currentURL;
        await new Promise((r) => setTimeout(r, 500));
      } else if (step === 0) {
        lastURL = currentURL;
      }

      // Accumulated wait warning
      if (totalWaitTime >= 3) {
        observations.push(
          `You have waited ${totalWaitTime} seconds accumulatively. ` +
            `DO NOT wait any longer unless you have a good reason.`,
        );
      }

      // Remaining steps warning
      const remaining = maxSteps - step;
      if (remaining === 5) {
        observations.push(`⚠️ Only ${remaining} steps remaining. Consider wrapping up or calling done with partial results.`);
      } else if (remaining === 2) {
        observations.push(`⚠️ Critical: Only ${remaining} steps left! You must finish the task or call done immediately.`);
      }

      // Push observations to history
      for (const content of observations) {
        history.push({ type: 'observation', content });
      }
      observations.length = 0;
      if (history.length > 0) emitHistory();

      // --- THINK (call LLM) ---
      const systemPrompt = PAGE_AGENT_SYSTEM_PROMPT + '\n\n' + describeTools(tools);
      const userPrompt = assembleUserPrompt({
        instructions: config.instructions,
        task: config.task,
        maxSteps,
        history,
        browserState,
        // The instructions block is deliberately outside the mask: it is skill prose we wrote,
        // it carries no page data, and masking it renames the controls it tells the agent to
        // click ("Teacher Preview" is two capitalised words).
        mask: config.mask && ((body) => config.mask!.text(body, urlFromHeader(browserState.header))),
      });

      const messages: LLMMessage[] = [
        { role: 'system', content: systemPrompt },
        { role: 'user', content: userPrompt },
      ];

      let macroOutput: MacroToolOutput | null = null;
      try {
        macroOutput = (await callLLM(config, messages, tools)) as MacroToolOutput;
      } catch (error) {
        // A reply we cannot read is not fatal on its own — say so and let the
        // model try again. Only a transport error (HTTP) is worth aborting on,
        // because retrying that just repeats the same refusal.
        const msg = error instanceof Error ? error.message : String(error);
        if (!/no tool call/i.test(msg)) throw error;
        stall += 1;
        if (stall >= stallLimit) {
          // Never produced a usable action: report it transport-shaped so a
          // caller with a model chain moves on instead of blaming the task.
          const data = producedAction ? `Stopped after ${stall} unusable replies.` : msg;
          history.push({ type: 'observation', content: data });
          emitHistory();
          setStatus('error');
          return { success: false, data, history: [...history] };
        }
        observations.push(
          `⚠️ Your last reply contained no action. You MUST call AgentOutput with exactly one action ` +
            `from the tool list — or, if you cannot proceed, the done action with success=false.`,
        );
        continue;
      }

      // --- ACT (execute the tool) ---
      // The model addressed a masked page, so it can only refer to identifiers by token. Put
      // the real values back before anything touches the page — a token typed into a search
      // box or matched against a dropdown option would simply not be found.
      const action = config.mask ? config.mask.rehydrate(macroOutput.action) : macroOutput.action;
      const actionName = Object.keys(action ?? {})[0];
      if (!actionName) {
        history.push({ type: 'observation', content: '❌ LLM returned no action' });
        emitHistory();
        continue;
      }
      producedAction = true;

      const actionInput = action[actionName];
      const reflection = {
        evaluation_previous_goal: macroOutput.evaluation_previous_goal ?? '',
        memory: macroOutput.memory ?? '',
        next_goal: macroOutput.next_goal ?? '',
      };

      emitActivity({ type: 'executing', tool: actionName, input: actionInput });

      const startTime = Date.now();
      const { name, output } = await executeTool(tools, ctx, action);
      const duration = Date.now() - startTime;

      emitActivity({ type: 'executed', tool: name, output, duration });

      // Track wait time
      if (name === 'wait') {
        totalWaitTime += (actionInput as { seconds?: number }).seconds ?? 1;
      } else {
        totalWaitTime = 0;
      }

      // Record the step
      history.push({
        type: 'step',
        stepIndex: step,
        reflection,
        action: { name, input: actionInput, output },
      });
      emitHistory();

      // --- PROGRESS ---
      // Feed the stall back to the model before acting on it. A model that is
      // repeating itself usually has not noticed; told plainly, it often
      // switches approach and finishes. Killing it silently never does.
      const key = actionKeyOf(name, actionInput);
      const repeated = isRepeatedStallAction(name, key, lastActionKey);
      lastActionKey = key;
      stall = isUnproductiveOutput(output) || repeated ? stall + 1 : 0;

      if (stall > 0 && stall < stallLimit) {
        observations.push(
          `⚠️ That step made no progress (${stall} in a row${repeated ? ', and it repeated the previous action exactly' : ''}). ` +
            `Do NOT retry it unchanged — re-read the element list, pick a different element or a different tool, ` +
            `or call done with success=false explaining what is blocking you.`,
        );
      } else if (stall >= stallLimit) {
        const msg = `Stopped after ${stall} consecutive steps with no progress (last action: ${name}).`;
        history.push({ type: 'observation', content: msg });
        emitHistory();
        setStatus('error');
        return { success: false, data: msg, history: [...history] };
      }

      // Check for done
      if (name === 'done') {
        const success = (actionInput as { success?: boolean }).success ?? false;
        const data = (actionInput as { text?: string }).text ?? 'no text provided';
        setStatus(success ? 'completed' : 'error');
        return { success, data, history: [...history] };
      }
    }

    // Max steps exceeded
    const msg = 'Step count exceeded maximum limit';
    history.push({ type: 'observation', content: msg });
    emitHistory();
    setStatus('error');
    return { success: false, data: msg, history: [...history] };
  } catch (error) {
    const isAbort = (error as Error)?.name === 'AbortError';
    const msg = isAbort ? 'Task aborted' : String(error);
    emitActivity({ type: 'error', message: msg });
    setStatus(isAbort ? 'stopped' : 'error');
    return { success: false, data: msg, history: [...history] };
  }
}

/**
 * Call the LLM with the OpenAI-compatible chat completions + tool call API.
 * Handles the MacroTool pattern: we define one "AgentOutput" tool that wraps
 * all the real tools, so the LLM returns a single structured response.
 *
 * This mirrors page-agent's packMacroTool approach from PageAgentCore.ts.
 */
async function callLLM(
  config: PageAgentLoopConfig,
  messages: LLMMessage[],
  tools: PageAgentTool<any>[],
): Promise<MacroToolOutput> {
  // Build the MacroTool schema: one tool that wraps reflection + action
  const toolProperties: Record<string, unknown> = {};
  for (const tool of tools) {
    toolProperties[tool.name] = {
      type: 'object',
      description: tool.description,
    };
  }

  const macroTool: LLMToolDef = {
    type: 'function',
    function: {
      name: 'AgentOutput',
      description: 'You MUST call this tool every step!',
      parameters: {
        type: 'object',
        properties: {
          evaluation_previous_goal: { type: 'string' },
          memory: { type: 'string' },
          next_goal: { type: 'string' },
          action: {
            type: 'object',
            properties: toolProperties,
            description: 'The action to take this step',
          },
        },
        required: ['action'],
      },
    },
  };

  const body: Record<string, unknown> = {
    model: config.model,
    messages,
    tools: [macroTool],
  };

  if (!config.disableNamedToolChoice) {
    body.tool_choice = { type: 'function', function: { name: 'AgentOutput' } };
  }

  const headers: Record<string, string> = { 'Content-Type': 'application/json' };
  if (config.apiKey) {
    headers['Authorization'] = `Bearer ${config.apiKey}`;
  }

  const resp = await fetch(`${config.baseURL}/chat/completions`, {
    method: 'POST',
    headers,
    body: JSON.stringify(body),
  });

  if (!resp.ok) {
    const text = await resp.text().catch(() => '');
    throw new Error(`LLM API error ${resp.status}: ${text}`);
  }

  const data = await resp.json();

  // One repairer, not two: normalizeMacroOutput owns every "the model answered in
  // the wrong shape" case, including the JSON-in-content and scalar-argument ones
  // this function used to handle itself.
  const { output, repairs } = normalizeMacroOutput(data, tools);
  if (repairs.length) config.onRepair?.(repairs);
  return output;
}
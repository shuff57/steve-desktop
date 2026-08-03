/**
 * The capabilities AutomateRunner had and the bare page-agent loop did not.
 *
 * AutomateRunner does not round-trip per action: it spawns one full-shell CLI per phase and that
 * CLI drives the browser itself over Bash+CDP. Moving to the in-app loop is therefore a PORT —
 * each of these has to be rebuilt against the loop's own seams (its tool list, its instructions,
 * its context) rather than moved across.
 *
 * Kept as a library rather than folded into a component so each capability is testable without a
 * browser, and so the existing UI can keep expressing the parts it already does well.
 */

import { DEFAULT_TOOLS, type PageAgentTool, type ToolContext } from './page-agent-tools';
import { runAgentLoop, type ExecutionResult, type PageAgentLoopConfig } from './page-agent-loop';
import { createPageMask } from './page-agent-mask';
import { withinScope } from './site-map';
import { domainFromUrl } from './utils/index';

// ── 3. Scope + domain confinement ──────────────────────────────────────────

export interface Confinement {
  /** The page the run started on: its course/section (or directory) is the fence. */
  startUrl: string;
  /** Directory prefix from deriveFence, for sites with no course parameter. */
  fence?: string | null;
  /** Refuse another host entirely. Off for a run told to span sites. */
  sameDomainOnly?: boolean;
}

/**
 * Why this URL is out of bounds, or null if it is allowed.
 *
 * Returned as prose because the model is the only consumer: a refusal it cannot read is a step it
 * repeats until the stall limit kills the run.
 */
export function confinementRefusal(url: string, c: Confinement): string | null {
  const target = (url ?? '').trim();
  if (!target) return null; // the tool itself reports a missing url
  if (c.sameDomainOnly) {
    const from = domainFromUrl(c.startUrl);
    const to = domainFromUrl(target.startsWith('http') ? target : new URL(target, c.startUrl).toString());
    if (from && to && from !== to) {
      return `❌ Refused: ${to} is a different site. This run is confined to ${from}. Stay on it, or call done with success=false explaining what you needed.`;
    }
  }
  if (!withinScope(target, c.startUrl, c.fence)) {
    return `❌ Refused: that URL is outside the course/section this run started in. Work within the one you were given, or call done with success=false.`;
  }
  return null;
}

/**
 * Replace `navigate` with a confined version.
 *
 * Only the addressable move is gated. A click can also leave the fence, but a click is chosen from
 * elements that are ON the page the run was pointed at, and refusing one would strand the agent
 * mid-flow with no way back. Drift by click shows up in the observation, where the loop already
 * reports the new URL.
 */
export function confineNavigation(
  tools: PageAgentTool<any>[],
  c: Confinement,
): PageAgentTool<any>[] {
  return tools.map((tool) => {
    if (tool.name !== 'navigate') return tool;
    return {
      ...tool,
      execute: async (ctx: ToolContext, params: { url: string }) => {
        const refusal = confinementRefusal(params?.url ?? '', c);
        if (refusal) return refusal;
        return tool.execute(ctx, params);
      },
    };
  });
}

// ── 4. Tab ownership ───────────────────────────────────────────────────────

/**
 * Refuse to drive a tab that is not the one we were pointed at.
 *
 * connectCDP falls back to "first non-loopback page target" when its marker probe finds nothing,
 * and that fallback is not hypothetical: after a UI reload left two orphaned webviews alive, a run
 * aimed at a local gradebook attached to a stale mail-tester.com tab and the model dutifully
 * reported it could not find the dropdown. Nothing failed — it read the wrong page and said so.
 *
 * The marker is the tab's identity (Browser.svelte stamps window.name), so asking the connected
 * target for its own name is the whole check.
 */
export async function assertDrivingTab(
  cdpSend: (method: string, params?: Record<string, unknown>) => Promise<unknown>,
  expectedMarker: string,
): Promise<void> {
  const res = (await cdpSend('Runtime.evaluate', {
    expression: 'window.name',
    returnByValue: true,
  })) as { result?: { value?: unknown } };
  const actual = String(res.result?.value ?? '');
  if (actual !== expectedMarker) {
    throw new Error(
      `Attached to the wrong page — expected the tab you are on, got "${actual || 'an unmarked tab'}". ` +
        `Close stale tabs or restart the app, then try again.`,
    );
  }
}

// ── 2. Plan → mutation scan → approve gate ─────────────────────────────────

/**
 * Tools that can CHANGE the site. Nulled out for a planning pass, which is the whole point of the
 * gate: the plan is produced by an agent that could not have altered anything while writing it.
 */
export const MUTATING_TOOLS = ['click_element_by_index', 'input_text', 'select_dropdown_option'] as const;

export function readOnlyTools(): Record<string, null> {
  return Object.fromEntries(MUTATING_TOOLS.map((n) => [n, null]));
}

export const PLAN_INSTRUCTIONS = `You are PLANNING, not doing. This pass is read-only: the tools
that click, type and select have been removed, so you cannot change anything even by accident.

Look at the page (navigate and scroll to see what you need), then call done with success=true and,
as the text, the numbered steps you INTEND to take. Name the exact control for each step as it
appears in the element list. If the task cannot be done from here, say so instead and use
success=false.`;

/**
 * Restate the task as the planning job.
 *
 * Putting the directive only in <instructions> was not enough. The vendored system prompt tells
 * the model the USER REQUEST is its "ultimate objective" with "the highest priority", so a task
 * phrased as an action still reads as one to obey — gemma4 called done with the text "ok" on its
 * first step, reporting success for a task it had no tools to perform. The plan gate held (the
 * page was untouched), but the plan was worthless. The task itself has to be the planning task.
 */
export function planTask(task: string): string {
  return `PLAN ONLY — do not attempt this task, and do not report it as done.

Study the current page and write the numbered steps you WOULD take to accomplish:

${task}

Your reply is a plan for a human to approve, not a result. Call done with success=true and the
numbered steps as the text.`;
}

/** Tool set for a planning pass: the defaults minus everything that can mutate. */
export function planningTools(): PageAgentTool<any>[] {
  const banned = new Set<string>(MUTATING_TOOLS);
  return DEFAULT_TOOLS.filter((t) => !banned.has(t.name));
}

// ── 1. Site map as instructions ────────────────────────────────────────────

/**
 * Fold a site map into the run's instructions.
 *
 * The CLI read the map off disk; the loop has no filesystem, so the map travels in the prompt.
 * It is truncated because a full map of a live course runs to tens of thousands of tokens and the
 * loop re-sends its prompt every step — an untruncated map is paid for on every observation.
 */
export function withMap(instructions: string | undefined, map: string, limit = 6000): string {
  const trimmed = (map ?? '').trim();
  if (!trimmed) return instructions ?? '';
  const clipped = trimmed.length > limit
    ? trimmed.slice(0, limit) + `\n… (map truncated at ${limit} characters — navigate to see the rest)`
    : trimmed;
  return `${instructions ? instructions + '\n\n' : ''}<site_map>\n${clipped}\n</site_map>`;
}

// ── The composer ───────────────────────────────────────────────────────────

export interface PageAgentRunOptions {
  task: string;
  baseURL: string;
  model: string;
  apiKey?: string;
  /** Read-only planning pass, or the real thing. */
  mode: 'plan' | 'execute';
  confine?: Confinement;
  /** Site map for context. Loaded by the caller — the loop has no filesystem. */
  map?: string;
  /** Extra skill instructions, ahead of the map. */
  instructions?: string;
  /** The approved plan, replayed into an execute pass so it carries out THAT plan. */
  approvedPlan?: string;
  maxSteps?: number;
  /**
   * Watch the CDP endpoint for the duration. WebView2's has wedged under load, and a wedged
   * endpoint means every tool call hangs with nothing said. Supplied by the caller so this
   * module stays free of Tauri.
   */
  watchdog?: { start(): void; stop(): void };
  onStatusChange?: PageAgentLoopConfig['onStatusChange'];
  onActivity?: PageAgentLoopConfig['onActivity'];
  onHistoryUpdate?: PageAgentLoopConfig['onHistoryUpdate'];
}

/**
 * Run one task through the page-agent loop with AutomateRunner's guarantees around it.
 *
 * The mask is not optional and not a parameter: every path through here can be pointed at a
 * gradebook, and a run that could skip it would eventually be the run that did.
 */
export async function runPageAgentTask(
  opts: PageAgentRunOptions,
  ctx: ToolContext,
): Promise<ExecutionResult> {
  const planning = opts.mode === 'plan';

  let tools = planning ? planningTools() : [...DEFAULT_TOOLS];
  if (opts.confine) tools = confineNavigation(tools, opts.confine);

  // The loop takes overrides as a map, so express the tool set that way: nulls remove the
  // mutating tools in a planning pass, and every remaining tool is passed through as the
  // (possibly confined) version built above.
  const customTools: Record<string, PageAgentTool<any> | null> = planning ? readOnlyTools() : {};
  for (const tool of tools) customTools[tool.name] = tool;

  let instructions = opts.instructions;
  if (planning) instructions = `${PLAN_INSTRUCTIONS}\n\n${instructions ?? ''}`.trim();
  if (opts.approvedPlan) {
    instructions = `${instructions ? instructions + '\n\n' : ''}<approved_plan>
A human reviewed and approved these steps. Carry out THESE steps. If the page turns out not to
match the plan, stop and call done with success=false rather than improvising a different change.

${opts.approvedPlan}
</approved_plan>`;
  }
  if (opts.map) instructions = withMap(instructions, opts.map);

  opts.watchdog?.start();
  try {
    return await runAgentLoop(
      {
        task: planning ? planTask(opts.task) : opts.task,
        baseURL: opts.baseURL,
        model: opts.model,
        apiKey: opts.apiKey ?? 'NA',
        instructions: instructions || undefined,
        customTools,
        mask: createPageMask(),
        maxSteps: opts.maxSteps ?? (planning ? 20 : 40),
        onStatusChange: opts.onStatusChange,
        onActivity: opts.onActivity,
        onHistoryUpdate: opts.onHistoryUpdate,
      },
      ctx,
    );
  } finally {
    opts.watchdog?.stop();
  }
}

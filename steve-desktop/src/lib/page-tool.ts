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

/**
 * Serve the run's site map on demand.
 *
 * The map is a human/AI-authored markdown doc about the site — not a page — so it holds no
 * live student data; it is masked anyway because the model-author was itself masked and may
 * have carried tokens into it. Served in the SAME run's mask as every page read, so a token
 * means the same thing in the map and on the page.
 */
export async function handlePageMap(
  runId: string,
  map: string,
  query: string | undefined,
): Promise<string> {
  const slice = mapSliceForQuery(map, query);
  if (!slice) return 'No site map is available for this site yet — map it first, or work from the page directly.';
  const mask = maskForRun(runId);
  const body = slice.text;
  const label = slice.section ? ` (section: ${slice.section})` : '';
  return `Site map${label}:\n\n${mask.text(body)}`;
}

/** The number of section lines to show on either side of the one that matched. */
const MAP_CONTEXT_LINES = 8;

/**
 * Split a markdown mapping doc into headings plus the prose under each, so the
 * orchestrator can ask for the section it needs instead of the whole document.
 */
export function splitMapSections(doc: string): { heading: string; body: string }[] {
  const lines = (doc ?? '').split('\n');
  const sections: { heading: string; body: string[] }[] = [];
  let current: { heading: string; body: string[] } | null = null;
  for (const line of lines) {
    const h = /^#{1,6}\s+(.*)$/.exec(line);
    if (h) {
      current = { heading: h[1].trim(), body: [] };
      sections.push(current);
    } else if (current) {
      current.body.push(line);
    }
  }
  return sections.map((s) => ({ heading: s.heading, body: s.body.join('\n').trim() }));
}

/** The closest section heading to a line number, from a fresh scan of the doc. */
function headingAtLine(doc: string, line: number): string {
  let last = '';
  const lines = doc.split('\n');
  const n = Math.max(0, Math.min(line, lines.length - 1));
  for (let i = 0; i <= n; i++) {
    const h = /^#{1,6}\s+(.*)$/.exec(lines[i] ?? '');
    if (h) last = h[1].trim();
  }
  return last;
}

/** Return the slice of the map most relevant to a query, or null for "no map". */
export function mapSliceForQuery(doc: string, query: string | undefined): { section: string; text: string } | null {
  const trimmed = (doc ?? '').trim();
  if (!trimmed) return null;
  const q = (query ?? '').trim().toLowerCase();
  if (!q) {
    // No query: the whole doc, since a truncated lead-in was all the prompt used to carry anyway.
    return { section: '', text: trimmed };
  }

  const lines = trimmed.split('\n');
  const score = (line: string): number => {
    const l = line.toLowerCase();
    if (!l.includes(q)) return 0;
    // A heading hit is exact; prose carries the query further down the page.
    return /^#{1,6}\s/.test(line) ? 3 : 1;
  };
  let best = -1;
  let bestScore = 0;
  for (let i = 0; i < lines.length; i++) {
    const s = score(lines[i]);
    if (s > bestScore) {
      bestScore = s;
      best = i;
    }
  }
  if (best === -1) return null;

  const from = Math.max(0, best - MAP_CONTEXT_LINES);
  const to = Math.min(lines.length, best + MAP_CONTEXT_LINES + 1);
  return { section: headingAtLine(trimmed, best), text: lines.slice(from, to).join('\n').trim() };
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

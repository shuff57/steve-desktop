/**
 * The app's half of the page tools: it answers what the Rust MCP endpoint asks.
 *
 * Rust serves the HTTP endpoint the spawned CLI talks to, but every handler is here, because the
 * run mask and the CDP client are here. One call arrives as a `page-tool-call` event, is dispatched
 * to `page-tool.ts`, and the answer goes back through the `page_tool_result` command.
 *
 * Two things every reply carries, both from live failures rather than caution:
 *
 * - **The page afterwards.** A run once reported a student selected while the `<select>` had not
 *   moved. An action's own account of itself is the weakest evidence there is, so the state comes
 *   with it — and since element indexes shift after any action, the orchestrator needed a fresh
 *   read anyway. Bundling it is cheaper than click-then-read, not more expensive.
 * - **Masked identifiers.** `readPage` and `handlePageAction` mask on the way out and rehydrate on
 *   the way in, against ONE mask keyed to the run. Per-call masks renumber between calls, so
 *   "open ⟦STU3⟧'s row" would quietly land on a different student.
 */

import { invoke } from '@tauri-apps/api/core';
import { listen } from '@tauri-apps/api/event';
import { handlePageAction, handlePageTask, readPage, type PageTaskOptions, type PageTaskResult } from './page-tool';
import { confinementRefusal } from './page-agent-run';
import { maskForRun } from './page-agent-mask';
import type { ToolContext } from './page-agent-tools';

export interface PageToolsEndpoint {
  port: number;
  token: string;
}

/** As the CLI sees them: Claude Code namespaces an MCP tool `mcp__<server>__<tool>`. */
export const PAGE_TOOL_NAMES = {
  read: 'mcp__page__page_read',
  task: 'mcp__page__page_task',
  click: 'mcp__page__page_click',
  type: 'mcp__page__page_type',
  navigate: 'mcp__page__page_navigate',
} as const;

export interface PageToolsContext {
  /** Keys the mask. Use the run's session id — the same one the spawn and tab ownership use. */
  runId: string;
  ctx: ToolContext;
  /** Config for the sub-task agent behind page_task, including this run's confinement. */
  subTask: PageTaskOptions;
}

/** The `--mcp-config` value. Loopback plus a per-run bearer token: anything else on the machine
 *  could otherwise read the user's gradebook through this endpoint. */
export function mcpConfigFor(endpoint: PageToolsEndpoint): string {
  return JSON.stringify({
    mcpServers: {
      page: {
        type: 'http',
        url: `http://127.0.0.1:${endpoint.port}/mcp`,
        headers: { Authorization: `Bearer ${endpoint.token}` },
      },
    },
  });
}

const PAGE_STATE_HEADING = '\n\n--- the page now ---\n';

function withPage(report: string, page: string): string {
  return `${report}${PAGE_STATE_HEADING}${page}`;
}

function formatTaskResult(r: PageTaskResult): string {
  if (r.degraded) return withPage(r.degraded, r.page);
  return withPage(`${r.ok ? '✅' : '❌'} ${r.report}`, r.page);
}

/** One tool call → the text the CLI gets back. Separated from the event plumbing so it is
 *  testable without Tauri. */
export async function dispatchPageTool(
  name: string,
  args: Record<string, unknown>,
  o: PageToolsContext,
): Promise<string> {
  switch (name) {
    case 'page_read':
      return readPage(o.runId, o.ctx);

    case 'page_task': {
      const task = String(args.task ?? '').trim();
      if (!task) throw new Error('page_task needs a `task` describing what to do on this page.');
      return formatTaskResult(await handlePageTask(o.runId, task, o.ctx, o.subTask));
    }

    case 'page_click':
      return act(o, 'click_element_by_index', { index: indexOf(args) });

    case 'page_type':
      return act(o, 'input_text', { index: indexOf(args), text: String(args.text ?? '') });

    case 'page_navigate': {
      const url = String(args.url ?? '').trim();
      if (!url) throw new Error('page_navigate needs a `url`.');
      // Check the REAL url, not the masked one: a token stands in for a person id, and a confinement
      // test against ⟦PID7⟧ would pass on any URL simply because it does not parse as one.
      const refusal = o.subTask.confine
        ? confinementRefusal(maskForRun(o.runId).rehydrate(url), o.subTask.confine)
        : null;
      if (refusal) return refusal;
      return act(o, 'navigate', { url });
    }

    default:
      throw new Error(`Unknown page tool: ${name}`);
  }
}

function indexOf(args: Record<string, unknown>): number {
  const n = Number(args.index);
  if (!Number.isInteger(n)) throw new Error('`index` must be an element index from page_read.');
  return n;
}

async function act(o: PageToolsContext, tool: string, input: Record<string, unknown>): Promise<string> {
  const output = await handlePageAction(o.runId, tool, input, o.ctx);
  return withPage(output, await readPage(o.runId, o.ctx));
}

/** A page-tool call as one short line for the activity feed. */
export function describePageTool(name: string, args?: Record<string, unknown>): string | null {
  switch (name) {
    case 'page_read':
      return 'reading the page';
    case 'page_task': {
      const t = String(args?.task ?? '').replace(/\s+/g, ' ').trim();
      return t ? `page agent: ${t.slice(0, 80)}` : 'handing a sub-task to the page agent';
    }
    case 'page_click':
      return 'clicking';
    case 'page_type':
      return 'filling a field';
    case 'page_navigate': {
      const u = String(args?.url ?? '').replace(/^https?:\/\//, '');
      return u ? `navigating to ${u.slice(0, 48)}` : 'navigating';
    }
    default:
      return null;
  }
}

/** Tauri rejects with a bare string, not an Error — `instanceof Error` silently loses the message. */
function errText(e: unknown): string {
  return e instanceof Error ? e.message : String(e);
}

export interface PageToolsBridge {
  endpoint: PageToolsEndpoint;
  /** Pass to `run_agent_cli` as `mcpConfig`. */
  mcpConfig: string;
  stop(): Promise<void>;
}

/**
 * Start answering page tools, and return what the CLI needs to reach them.
 *
 * The listener is registered BEFORE the endpoint exists on purpose: Rust queues nothing, so a call
 * arriving with no listener just sits there until its timeout.
 */
export async function startPageToolsBridge(
  o: PageToolsContext & { onActivity?: (line: string) => void },
): Promise<PageToolsBridge> {
  const unlisten = await listen<{ id: number; name: string; arguments?: Record<string, unknown> }>(
    'page-tool-call',
    (ev) => {
      const { id, name } = ev.payload;
      const args = ev.payload.arguments ?? {};
      const line = describePageTool(name, args);
      if (line) o.onActivity?.(line);
      dispatchPageTool(name, args, o)
        .then((output) => invoke('page_tool_result', { id, ok: true, output }))
        .catch((e) => invoke('page_tool_result', { id, ok: false, output: errText(e) }));
    },
  );

  try {
    const endpoint = await invoke<PageToolsEndpoint>('start_page_tools');
    return {
      endpoint,
      mcpConfig: mcpConfigFor(endpoint),
      stop: async () => {
        unlisten();
        await invoke('stop_page_tools').catch(() => undefined);
      },
    };
  } catch (e) {
    unlisten();
    throw new Error(`Could not start the page tools: ${errText(e)}`);
  }
}

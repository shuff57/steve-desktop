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
  screenshot: 'mcp__page__page_screenshot',
  record: 'mcp__page__page_record',
  tabs: 'mcp__page__page_tabs',
  attach: 'mcp__page__page_attach_file',
} as const;

/** The app bridge the CLI used to reach by evaluating JS on the app-UI target over CDP. */
interface SteveControl {
  listTabs(): { id: string; url: string; title: string; active: boolean; session?: string }[];
  newTab(url?: string, sessionId?: string): Promise<string>;
  activate(id: string, sessionId?: string): Promise<void>;
  navigate(id: string, url: string, sessionId?: string): Promise<void>;
  closeTab(id: string, sessionId?: string): Promise<void>;
  login(id: string, sessionId?: string): Promise<boolean>;
}

function steveControl(): SteveControl {
  const c = (globalThis as unknown as { __steveControl?: SteveControl }).__steveControl;
  if (!c) throw new Error('The app tab bridge is not available in this window.');
  return c;
}

export interface PageToolsContext {
  /** Keys the mask. Use the run's session id — the same one the spawn and tab ownership use. */
  runId: string;
  /**
   * The tab to drive, resolved PER CALL rather than captured once.
   *
   * page_tabs can change which tab is active mid-run. A context taken at run start would keep
   * driving the tab the agent has since switched away from — and every read would come back
   * describing a page that is not the one on screen, which reads as the model hallucinating.
   */
  ctx: () => ToolContext | Promise<ToolContext>;
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
  const ctx = await o.ctx();
  switch (name) {
    case 'page_read':
      return readPage(o.runId, ctx);

    case 'page_task': {
      const task = String(args.task ?? '').trim();
      if (!task) throw new Error('page_task needs a `task` describing what to do on this page.');
      return formatTaskResult(await handlePageTask(o.runId, task, ctx, o.subTask));
    }

    case 'page_click':
      return act(o, ctx, 'click_element_by_index', { index: indexOf(args) });

    case 'page_type':
      return act(o, ctx, 'input_text', { index: indexOf(args), text: String(args.text ?? '') });

    case 'page_navigate': {
      const url = String(args.url ?? '').trim();
      if (!url) throw new Error('page_navigate needs a `url`.');
      // Check the REAL url, not the masked one: a token stands in for a person id, and a confinement
      // test against ⟦PID7⟧ would pass on any URL simply because it does not parse as one.
      const refusal = o.subTask.confine
        ? confinementRefusal(maskForRun(o.runId).rehydrate(url), o.subTask.confine)
        : null;
      if (refusal) return refusal;
      return act(o, ctx, 'navigate', { url });
    }

    case 'page_screenshot':
      return `✅ Saved a screenshot to ${await captureToArtifacts(ctx)} — it is in the app's Artifacts gallery.`;

    case 'page_record': {
      const action = String(args.action ?? '');
      if (action === 'start') {
        await invoke('start_recording', { targetUrl: null });
        return '✅ Recording the tab you are driving. Call page_record with action="stop" when the task is done.';
      }
      if (action === 'stop') {
        const path = await invoke<string | null>('stop_recording');
        return path
          ? `✅ Saved the recording to ${path} — it is in the app's Artifacts gallery.`
          : 'Nothing was recording.';
      }
      throw new Error('page_record needs action="start" or action="stop".');
    }

    case 'page_tabs':
      return tabs(o, args);

    case 'page_attach_file':
      return attachFile(o, ctx, indexOf(args), String(args.path ?? ''));

    default:
      throw new Error(`Unknown page tool: ${name}`);
  }
}

/**
 * Flash, capture, save.
 *
 * One tool rather than three steps because the flash is what tells the user a picture of their
 * screen was just taken, and an agent that has to remember a separate call will eventually not.
 */
async function captureToArtifacts(ctx: ToolContext): Promise<string> {
  await ctx
    .cdpSend('Runtime.evaluate', {
      expression: 'window.__steveScreenshotFlash && window.__steveScreenshotFlash()',
    })
    .catch(() => undefined);
  const shot = (await ctx.cdpSend('Page.captureScreenshot', { format: 'png' })) as { data?: string };
  if (!shot?.data) throw new Error('The browser returned no image.');
  const binary = atob(shot.data);
  const bytes = Array.from(binary, (c) => c.charCodeAt(0));
  return invoke<string>('save_artifact', { name: `page-${stamp()}.png`, bytes });
}

/** Sortable, filename-safe, second resolution. */
function stamp(): string {
  return new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
}

/**
 * The tab bridge, with this run's session id supplied by the app rather than by the caller.
 *
 * Ownership is what stops two runs fighting over a tab, so the id can never be an argument: a CLI
 * that could name its own session could name someone else's and drive their tab.
 */
async function tabs(o: PageToolsContext, args: Record<string, unknown>): Promise<string> {
  const c = steveControl();
  const action = String(args.action ?? '');
  const id = args.id === undefined ? '' : String(args.id);
  const needsId = () => {
    if (!id) throw new Error(`page_tabs action="${action}" needs the tab \`id\` from action="list".`);
    return id;
  };
  switch (action) {
    case 'list': {
      const list = c.listTabs().map((t) => ({
        id: t.id,
        url: t.url,
        title: t.title,
        active: t.active,
        yours: t.session === o.runId,
      }));
      return maskForRun(o.runId).text(JSON.stringify(list, null, 2));
    }
    case 'open':
      return `✅ Opened tab ${await c.newTab(String(args.url ?? '') || undefined, o.runId)}. It belongs to this run.`;
    case 'activate':
      await c.activate(needsId(), o.runId);
      return `✅ Tab ${id} is now the one the user sees. page_read reads the active tab.`;
    case 'navigate':
      await c.navigate(needsId(), maskForRun(o.runId).rehydrate(String(args.url ?? '')), o.runId);
      return `✅ Tab ${id} navigated.`;
    case 'close':
      await c.closeTab(needsId(), o.runId);
      return `✅ Closed tab ${id}.`;
    case 'login':
      return (await c.login(needsId(), o.runId))
        ? `✅ Signed in on tab ${id} with the credentials saved on this machine.`
        : `No saved credentials matched that tab's site. Ask the user to sign in themselves.`;
    default:
      throw new Error('page_tabs needs action = list | open | activate | navigate | close | login.');
  }
}

/**
 * Put a file on a file input, the way an attachment is actually added.
 *
 * The OS file picker is a native dialog nothing here can drive, so this is the only path that
 * works — and it is the same call for a .png and a .mp4.
 */
async function attachFile(
  o: PageToolsContext,
  ctx: ToolContext,
  index: number,
  path: string,
): Promise<string> {
  if (!path) throw new Error('page_attach_file needs the absolute `path` of the file to attach.');
  const doc = (await ctx.cdpSend('DOM.getDocument', { depth: 0 })) as { root?: { nodeId?: number } };
  const rootId = doc?.root?.nodeId;
  if (!rootId) throw new Error('Could not read the page.');
  const found = (await ctx.cdpSend('DOM.querySelector', {
    nodeId: rootId,
    selector: `[data-pa-index="${index}"]`,
  })) as { nodeId?: number };
  if (!found?.nodeId) {
    throw new Error(`No element [${index}] on this page — call page_read again for current indexes.`);
  }
  await ctx.cdpSend('DOM.setFileInputFiles', { nodeId: found.nodeId, files: [path] });
  return withPage(
    `✅ Attached ${path} to element [${index}]. Check below that the attachment appeared before sending.`,
    await readPage(o.runId, ctx),
  );
}

function indexOf(args: Record<string, unknown>): number {
  const n = Number(args.index);
  if (!Number.isInteger(n)) throw new Error('`index` must be an element index from page_read.');
  return n;
}

async function act(
  o: PageToolsContext,
  ctx: ToolContext,
  tool: string,
  input: Record<string, unknown>,
): Promise<string> {
  const output = await handlePageAction(o.runId, tool, input, ctx);
  return withPage(output, await readPage(o.runId, ctx));
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
    case 'page_screenshot':
      return 'taking a screenshot';
    case 'page_record':
      return args?.action === 'stop' ? 'stopping the recording' : 'starting a recording';
    case 'page_tabs': {
      const a = String(args?.action ?? '');
      return (
        {
          list: 'checking open tabs',
          open: 'opening a new tab',
          activate: 'switching tabs',
          navigate: 'navigating a tab',
          close: 'closing a tab',
          login: 'logging in',
        }[a] ?? 'using the tabs'
      );
    }
    case 'page_attach_file':
      return 'attaching a file';
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

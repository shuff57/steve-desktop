// Adapted from page-agent (MIT, Copyright (c) 2025 Alibaba Group Holding Limited)
// Source: packages/core/src/tools/index.ts
// See LICENSE-page-agent.txt in this directory for full notice.

/**
 * Tool definitions for the page-agent Re-Act loop. Vendored from page-agent's
 * tool set, but reimplemented as plain TS interfaces (no zod dependency — the
 * app does not use zod) and executed via CDP instead of page-agent's
 * PageController.
 *
 * Each tool has:
 *   - name: the action key the LLM emits in its MacroTool output
 *   - description: what the LLM sees in the prompt
 *   - execute: runs the action via the provided CDP context
 *
 * The loop driver (page-agent-loop.ts) calls execute() with a ToolContext
 * that wraps the app's CDP client. Custom tools (like fill_mom_question)
 * follow the same interface.
 */

export interface ToolContext {
  /** AbortSignal to cooperatively cancel long-running tools. */
  signal: AbortSignal;
  /** The CDP send function — routes to the app's connected cdp client. */
  cdpSend: (method: string, params?: Record<string, unknown>) => Promise<unknown>;
  /** Evaluate a JS expression in the page via CDP Runtime.evaluate. */
  evalInPage: (expression: string) => Promise<unknown>;
  /** Navigate the page to a URL via CDP Page.navigate. */
  navigate: (url: string) => Promise<void>;
  /** Wait for the page to settle after navigation. */
  waitForLoad: () => Promise<void>;
}

export interface PageAgentTool<TParams = Record<string, unknown>> {
  name: string;
  description: string;
  /**
   * The field a bare scalar argument belongs in. Models routinely shorten a
   * one-field action to a scalar (`click_element_by_index: 4`); the normaliser
   * uses this to put it back where it belongs. Upstream infers this from a zod
   * schema — this codebase has none, so tools say it outright.
   */
  primaryParam?: string;
  execute: (ctx: ToolContext, params: TParams) => Promise<string>;
}

// Tools have heterogeneous param shapes ({ index }, { index, text }, { url }, etc.).
// The array holds any PageAgentTool regardless of its TParams — use `any` for the
// element type, matching the codebase's pragmatic style (see cdp-client.ts, browser.ts).

/**
 * Reject a call whose `index` never arrived, naming the shape that was expected.
 *
 * Observed live with minimax-m3: it sent `input_text` without an `index`, got
 * back a bare "Element [undefined] not found", could not tell a wrong argument
 * name from a missing element, and abandoned the task two steps later. A model
 * can only correct a mistake the error names.
 */
function requireIndex(tool: string, shape: string, index: unknown): string | null {
  return Number.isFinite(Number(index))
    ? null
    : `❌ ${tool} was called without a valid index. Expected ${shape}.`;
}

// --- Built-in tools (adapted from page-agent's tools/index.ts) ---

export const doneTool: PageAgentTool<{ text: string; success: boolean }> = {
  name: 'done',
  primaryParam: 'text',
  description:
    'Complete task. Params: { text, success }. Text is your final response to the user — keep it ' +
    'concise unless the user explicitly asks for detail. ALWAYS set success explicitly: ' +
    'success=true when the task was achieved, false when it was not. Omitting it is read as failure.',
  execute: async () => 'Task completed',
};

/**
 * How long has this page been sitting still? Used to credit a wait with time the
 * page has already spent settling — asking for 3s right after a page that has
 * been idle 2s should cost 1s, not 3. Upstream does the same; without it a run
 * pays full price for every wait and the accumulated-wait warning fires early.
 */
async function secondsSinceLastChange(ctx: ToolContext): Promise<number> {
  try {
    const res = (await ctx.cdpSend('Runtime.evaluate', {
      expression: `(function(){
        if (!window.__paLastChange) {
          window.__paLastChange = Date.now();
          var o = new MutationObserver(function(){ window.__paLastChange = Date.now(); });
          o.observe(document.documentElement, {childList:true, subtree:true, attributes:true, characterData:true});
        }
        return (Date.now() - window.__paLastChange) / 1000;
      })()`,
      returnByValue: true,
    })) as { result?: { value?: number } };
    const v = Number(res.result?.value);
    return Number.isFinite(v) && v >= 0 ? v : 0;
  } catch {
    return 0; // unknown means pay full price, never skip the wait entirely
  }
}

export const waitTool: PageAgentTool<{ seconds: number }> = {
  name: 'wait',
  primaryParam: 'seconds',
  description:
    'Wait for x seconds (max 10). For "let this settle" pauses only — for an open-ended wait ' +
    '(a video finishing, a status becoming true, anything that could take minutes) use ' +
    'wait_for_condition instead: repeating this tool to cover a long wait reads as a stall.',
  execute: async (ctx, params) => {
    const asked = Math.max(1, Math.min(10, params.seconds ?? 1));
    const idle = await secondsSinceLastChange(ctx);
    const remaining = Math.max(0, asked - idle);
    if (remaining > 0) await new Promise((r) => setTimeout(r, remaining * 1000));
    return idle > 0.2
      ? `✅ Waited ${asked}s (the page had already been still for ${idle.toFixed(1)}s, so ${remaining.toFixed(1)}s was spent).`
      : `✅ Waited for ${asked} seconds.`;
  },
};

/**
 * wait_for_condition — an open-ended, condition-based wait for tasks like watching a
 * training video through to completion, where the actual duration is unknown up front.
 *
 * `wait` alone cannot cover this: it caps at 10s per call, and re-issuing it enough times
 * to cover several minutes trips the loop's stall detector (page-agent-loop.ts: an
 * identically-repeated action counts as "no progress"), plus the accumulated-wait warning
 * actively tells the model to stop waiting after 3s. This tool polls internally — inside
 * ONE step/history entry, however long the real wait turns out to be (bounded by
 * `timeoutSeconds`) — so the loop's step budget and stall detector never see the individual
 * poll ticks, only the one call.
 */
export const waitForConditionTool: PageAgentTool<{
  condition: string;
  timeoutSeconds?: number;
  pollSeconds?: number;
}> = {
  name: 'wait_for_condition',
  primaryParam: 'condition',
  description:
    'Wait until a JS boolean expression evaluated against the page becomes true, or until ' +
    'timeout — for open-ended waits (a video finishing, a panel appearing, a status flipping) ' +
    'where you do not know how long it will take. Polls internally; costs one step no matter ' +
    'how long the real wait is. Params: { condition: string — a JS expression evaluated in the ' +
    'page and coerced to boolean, e.g. "document.querySelector(\'video\')?.ended === true"; ' +
    'timeoutSeconds?: number (default 120, max 1200); pollSeconds?: number (default 5, how often ' +
    'to recheck) }. If it times out, decide whether to call it again, try something else, or ' +
    'report the task blocked — do not assume timeout means failure.',
  execute: async (ctx, params) => {
    const condition = String(params.condition ?? '').trim();
    if (!condition) {
      return '❌ wait_for_condition needs a condition. Expected { condition: string }.';
    }
    const timeoutSeconds = Math.max(5, Math.min(1200, params.timeoutSeconds ?? 120));
    const pollSeconds = Math.max(1, Math.min(30, params.pollSeconds ?? 5));
    const deadline = Date.now() + timeoutSeconds * 1000;
    let lastError: string | null = null;

    while (Date.now() < deadline) {
      ctx.signal.throwIfAborted();
      try {
        const res = (await ctx.cdpSend('Runtime.evaluate', {
          expression: `Boolean(${condition})`,
          returnByValue: true,
        })) as { result?: { value?: boolean }; exceptionDetails?: unknown };
        if (res.exceptionDetails) {
          lastError = `condition threw: ${JSON.stringify(res.exceptionDetails).slice(0, 200)}`;
        } else if (res.result?.value === true) {
          const waited = Math.round(timeoutSeconds - (deadline - Date.now()) / 1000);
          return `✅ Condition became true after ~${waited}s.`;
        }
      } catch (e) {
        lastError = e instanceof Error ? e.message : String(e);
      }
      const remainingMs = deadline - Date.now();
      if (remainingMs <= 0) break;
      await new Promise((r) => setTimeout(r, Math.min(pollSeconds * 1000, remainingMs)));
    }
    return (
      `⏱️ Timed out after ${timeoutSeconds}s waiting for the condition to become true.` +
      (lastError ? ` Last error: ${lastError}` : '')
    );
  },
};

/**
 * Glide the session cursor to the element this action is about to touch.
 *
 * The overlay's cursor is injected whenever a run claims a tab, but nothing was
 * moving it, so a page-agent run showed a cursor parked in the middle of the
 * screen while things clicked themselves. `__steveCursorMove` is the same entry
 * point the CLI agent uses, so both agents animate identically.
 *
 * Costs one round trip plus the glide time — deliberate: the point is that a
 * human can see WHICH control is about to be used, before it is used.
 */
async function moveCursorTo(ctx: ToolContext, index: number): Promise<void> {
  try {
    const moved = (await ctx.cdpSend('Runtime.evaluate', {
      expression: `(function(){
        var el = document.querySelector('[data-pa-index="${index}"]');
        if (!el || !window.__steveCursorMove) return false;
        el.scrollIntoView({block:'center'});
        var r = el.getBoundingClientRect();
        window.__steveCursorMove(r.left + r.width / 2, r.top + r.height / 2);
        return true;
      })()`,
      returnByValue: true,
    })) as { result?: { value?: boolean } };
    // Only pay the glide when there is a cursor to watch.
    if (moved.result?.value) await new Promise((r) => setTimeout(r, 320));
  } catch {
    // Decoration must never break the action it decorates.
  }
}

export const clickElementByIndexTool: PageAgentTool<{ index: number }> = {
  name: 'click_element_by_index',
  primaryParam: 'index',
  description: 'Click element by index. Params: { index: number }.',
  execute: async (ctx, params) => {
    const bad = requireIndex('click_element_by_index', '{ index: number }', params.index);
    if (bad) return bad;
    await moveCursorTo(ctx, params.index);
    const result = (await ctx.cdpSend('Runtime.evaluate', {
      expression: `(function(){
        var el = document.querySelector('[data-pa-index="${params.index}"]');
        if (!el) return 'Element [${params.index}] not found';
        el.scrollIntoView({block:'center'});
        // Clicking a native <option> does NOTHING — the select keeps its old value. The AX tree
        // lists options as interactive, so a model that plans "click the dropdown, click the
        // option" gets a ✅ for both and reports the task done while the page never moved.
        // Observed live: an approved plan "selected" a student and the select stayed on its
        // placeholder. Do what the click meant instead of reporting a no-op as success.
        if (el.tagName === 'OPTION' && el.parentElement && el.parentElement.tagName === 'SELECT') {
          var sel = el.parentElement;
          sel.value = el.value;
          sel.dispatchEvent(new Event('input',{bubbles:true}));
          sel.dispatchEvent(new Event('change',{bubbles:true}));
          return '✅ Selected "' + (el.text || '').trim() + '" in the dropdown (clicking an option on its own does nothing, so it was selected instead).';
        }
        el.click();
        return '✅ Clicked element [${params.index}]';
      })()`,
      returnByValue: true,
    })) as { result?: { value?: string } };
    return result.result?.value ?? 'Click failed';
  },
};

export const inputTextTool: PageAgentTool<{ index: number; text: string }> = {
  name: 'input_text',
  primaryParam: 'index',
  description: 'Click and type text into an interactive input element. Params: { index: number, text: string }.',
  execute: async (ctx, params) => {
    const bad = requireIndex('input_text', '{ index: number, text: string }', params.index);
    if (bad) return bad;
    await moveCursorTo(ctx, params.index);
    const focusResult = (await ctx.cdpSend('Runtime.evaluate', {
      expression: `(function(){
        var el = document.querySelector('[data-pa-index="${params.index}"]');
        if (!el) return false;
        el.focus();
        el.value = '';
        return true;
      })()`,
      returnByValue: true,
    })) as { result?: { value?: boolean } };

    if (!focusResult.result?.value) return `Element [${params.index}] not found`;

    await ctx.cdpSend('Input.insertText', { text: params.text });
    return `✅ Typed text into element [${params.index}]`;
  },
};

export const selectDropdownOptionTool: PageAgentTool<{ index: number; text: string }> = {
  name: 'select_dropdown_option',
  primaryParam: 'index',
  description:
    'Select dropdown option for interactive element index by the text of the option you want to select. Params: { index: number, text: string }.',
  execute: async (ctx, params) => {
    const bad = requireIndex('select_dropdown_option', '{ index: number, text: string }', params.index);
    if (bad) return bad;
    await moveCursorTo(ctx, params.index);
    const result = (await ctx.cdpSend('Runtime.evaluate', {
      expression: `(function(){
        var el = document.querySelector('[data-pa-index="${params.index}"]');
        if (!el) return 'Element [${params.index}] not found';
        var want = ${JSON.stringify(params.text)};
        // The tool is documented as selecting BY OPTION TEXT, so the model sends
        // the label ("Kiwi") while the option's value is usually a slug ("kiwi").
        // Assigning the label straight to .value leaves the select unchanged and
        // reports success.
        var opts = el.options ? Array.prototype.slice.call(el.options) : [];
        var hit = opts.filter(function(o){ return o.text.trim() === want || o.value === want; })[0]
          || opts.filter(function(o){ return o.text.trim().toLowerCase() === String(want).trim().toLowerCase(); })[0];
        if (!hit && opts.length) return 'No option matching "' + want + '" in element [${params.index}]. Options: ' + opts.map(function(o){return o.text.trim();}).join(' | ');
        el.value = hit ? hit.value : want;
        el.dispatchEvent(new Event('input',{bubbles:true}));
        el.dispatchEvent(new Event('change',{bubbles:true}));
        return '✅ Selected "' + (hit ? hit.text.trim() : want) + '" in element [${params.index}]';
      })()`,
      returnByValue: true,
    })) as { result?: { value?: string } };
    return result.result?.value ?? 'Select failed';
  },
};

export const scrollTool: PageAgentTool<{
  down: boolean;
  num_pages: number;
  pixels?: number;
  index?: number;
}> = {
  name: 'scroll',
  primaryParam: 'num_pages',
  description:
    'Scroll vertically. Without index: scrolls the document. With index: scrolls the container at that index.',
  execute: async (ctx, params) => {
    const direction = params.down ?? true;
    const amount = params.pixels ?? Math.round((params.num_pages ?? 1) * 600);
    const selector = params.index !== undefined
      ? `document.querySelector('[data-pa-index="${params.index}"]')`
      : 'document.scrollingElement || document.body';
    const result = (await ctx.cdpSend('Runtime.evaluate', {
      expression: `(function(){
        var el = ${selector};
        if (!el) return 'Scroll target not found';
        el.scrollBy(0, ${direction ? '' : '-'}${amount});
        return '✅ Scrolled ' + (${direction} ? 'down' : 'up') + ' ' + ${amount} + 'px';
      })()`,
      returnByValue: true,
    })) as { result?: { value?: string } };
    return result.result?.value ?? 'Scroll failed';
  },
};

export const navigateTool: PageAgentTool<{ url: string }> = {
  name: 'navigate',
  primaryParam: 'url',
  description: 'Navigate to a URL. Use when you need to go to a different page.',
  execute: async (ctx, params) => {
    await ctx.navigate(params.url);
    return `✅ Navigated to ${params.url}`;
  },
};

/**
 * ask_user — hand a question back mid-run instead of failing.
 *
 * Built per-run rather than living in DEFAULT_TOOLS: a tool nobody can answer
 * would hang the loop, so it is only offered when a handler exists. In the
 * conductor arrangement the "user" is usually the orchestrator, which is what
 * lets a run pause for a decision rather than burning its remaining steps
 * guessing or calling done with success=false.
 */
export function makeAskUserTool(
  ask: (question: string, signal: AbortSignal) => Promise<string>,
): PageAgentTool<{ question: string }> {
  return {
    name: 'ask_user',
    primaryParam: 'question',
    description:
      'Ask for a decision when the task is ambiguous or you are blocked. Params: { question: string }. ' +
      'Prefer this over guessing. The answer comes back as the result of this action, then you carry on.',
    execute: async (ctx, params) => {
      const question = String(params.question ?? '').trim();
      if (!question) return '❌ ask_user needs a question. Expected { question: string }.';
      try {
        const answer = await ask(question, ctx.signal);
        return `Answer: ${answer}`;
      } catch (e) {
        // A refused or failed answer must not look like an answer.
        return `❌ No answer available: ${e instanceof Error ? e.message : String(e)}`;
      }
    },
  };
}

// --- The full default tool set — skills can extend or blacklist ---

export const DEFAULT_TOOLS: PageAgentTool<any>[] = [
  doneTool,
  waitTool,
  waitForConditionTool,
  clickElementByIndexTool,
  inputTextTool,
  selectDropdownOptionTool,
  scrollTool,
  navigateTool,
];

// `execute_javascript` is intentionally NOT in the default set.
// page-agent itself gates it behind experimentalScriptExecutionTool and
// warns it "may bypass safeguards and data-masking mechanisms." The
// page-agent-runner skill policy keeps it disabled unless a skill
// explicitly opts in.

// --- The MacroTool output schema (what the LLM returns) ---

export interface MacroToolOutput {
  evaluation_previous_goal?: string;
  memory?: string;
  next_goal?: string;
  action: {
    [toolName: string]: Record<string, unknown>;
  };
}

/**
 * Build the tool descriptions block for the LLM prompt.
 * The LLM sees the tool name + description, not the implementation.
 */
export function describeTools(tools: PageAgentTool<any>[]): string {
  return tools
    .map((t) => `- ${t.name}: ${t.description}`)
    .join('\n');
}

/**
 * Find and execute a tool by name from the LLM's MacroTool output.
 * Returns the tool's result string, or an error message.
 */
export async function executeTool(
  tools: PageAgentTool<any>[],
  ctx: ToolContext,
  action: { [toolName: string]: Record<string, unknown> },
): Promise<{ name: string; output: string }> {
  const toolName = Object.keys(action)[0];
  const raw = action[toolName];
  const tool = tools.find((t) => t.name === toolName);

  if (!tool) {
    // Name the valid tools — observed live, a model that emits a malformed
    // action ({"0": "{"}) repeats it every step until told what is legal.
    return {
      name: toolName,
      output: `❌ Unknown tool: ${toolName}. Valid tools: ${tools.map((t) => t.name).join(', ')}`,
    };
  }

  // Scalar args are repaired upstream of here by normalizeMacroOutput, using each
  // tool's primaryParam. Anything still not an object at this point is a caller
  // bug rather than a model quirk, so it is passed through as an empty params
  // object and the tool reports what it wanted.
  const toolParams = (raw !== null && typeof raw === 'object' ? raw : {}) as Record<string, unknown>;

  try {
    const output = await tool.execute(ctx, toolParams);
    return { name: toolName, output };
  } catch (error) {
    const msg = error instanceof Error ? error.message : String(error);
    return { name: toolName, output: `❌ Tool error: ${msg}` };
  }
}
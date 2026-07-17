import { captureWebviewScreenshot, evalScript, getActiveTabId, navigateEmbedded } from './browser';
import { forgetAgentSession, sendAgentRequest } from './agent-api';
import { AGENT_SYSTEM_PROMPT } from './agent-prompt';
import {
  buildRefActionScript,
  captureInteractiveDom,
  capturePageText,
  findFuzzyMatch,
  formatDomForPrompt,
  fuzzyMatchReason,
} from './agent-dom';
import type {
  ActionResult,
  AgentApiResponse,
  AgentConfig,
  AgentMessage,
  AgentMode,
  AgentState,
  BrowserAction,
} from './agent-types';
import { DEFAULT_AGENT_CONFIG } from './agent-types';

export type AgentEventPayloads = {
  state: AgentState;
  thinking: undefined;
  proposing: { action: BrowserAction; reasoning: string };
  executing: { action: BrowserAction };
  result: { action: BrowserAction; result: ActionResult };
  done: { message: string };
  error: { message: string };
  text: { content: string };
};

export interface AgentStartConfig {
  mode: AgentMode;
  initialMessage: string;
  provider?: string;
  model?: string;
  config?: Partial<AgentConfig>;
}

export interface AgentController {
  on<K extends keyof AgentEventPayloads>(
    event: K,
    handler: (payload: AgentEventPayloads[K]) => void,
  ): () => void;
  start(config: AgentStartConfig): Promise<void>;
  stop(): void;
  approve(): void;
  skip(): void;
  getState(): AgentState;
}

function toTarget(params: Record<string, unknown>): { ref?: string; selector?: string } | null {
  const ref = typeof params.ref === 'string' && params.ref ? params.ref : undefined;
  const selector = typeof params.selector === 'string' && params.selector ? params.selector : undefined;
  if (!ref && !selector) return null;
  return { ...(ref ? { ref } : {}), ...(selector ? { selector } : {}) };
}

function toBrowserAction(action: string, params: Record<string, unknown>): BrowserAction | null {
  if (action === 'click') {
    const target = toTarget(params);
    return target ? { type: 'click', ...target } : null;
  }
  if (action === 'fill' || action === 'type') {
    const target = toTarget(params);
    if (!target) return null;
    const value = typeof params.value === 'string' ? params.value : typeof params.text === 'string' ? params.text : '';
    return { type: 'fill', ...target, value };
  }
  if (action === 'navigate' && typeof params.url === 'string') {
    return { type: 'navigate', url: params.url };
  }
  if (action === 'wait' || action === 'waitFor') {
    const condition =
      typeof params.condition === 'string'
        ? params.condition
        : typeof params.selector === 'string'
          ? params.selector
          : '';
    const timeout = typeof params.timeout === 'number' ? params.timeout : typeof params.timeoutMs === 'number' ? params.timeoutMs : undefined;
    return { type: 'wait', condition, timeout };
  }
  if ((action === 'keyboard' || action === 'pressKey') && typeof params.key === 'string') {
    return { type: 'keyboard', key: params.key };
  }
  if (action === 'scroll' && typeof params.direction === 'string') {
    const direction = params.direction;
    if (direction === 'up' || direction === 'down' || direction === 'left' || direction === 'right') {
      return { type: 'scroll', direction };
    }
  }
  if (action === 'iframe_interact' && typeof params.frameSelector === 'string' && params.action && typeof params.action === 'object') {
    const nested = params.action as Record<string, unknown>;
    const nestedType = typeof nested.type === 'string' ? nested.type : typeof nested.action === 'string' ? nested.action : '';
    const nestedAction = toBrowserAction(nestedType, nested);
    if (nestedAction && nestedAction.type !== 'iframe_interact') {
      return { type: 'iframe_interact', frameSelector: params.frameSelector, action: nestedAction };
    }
  }
  return null;
}

const REF_ERRORS: Record<string, string> = {
  stale: 'Element is stale — the page re-rendered. Re-read the DOM and use a fresh ref.',
  norefs: 'No element registry on the page. Re-read the DOM to get fresh refs.',
};

async function runRefAction(ref: string, op: string): Promise<ActionResult> {
  const status = await evalScript(buildRefActionScript(ref, op));
  const normalized = status.replace(/^"|"$/g, '');
  if (normalized === 'ok') return { success: true };
  return { success: false, error: REF_ERRORS[normalized] ?? `Ref action failed (${normalized}): ${ref}` };
}

async function executeAction(action: BrowserAction): Promise<ActionResult> {
  try {
    if (action.type === 'navigate') {
      const tabId = getActiveTabId();
      if (!tabId) return { success: false, error: 'No active tab' };
      await navigateEmbedded(tabId, action.url);
      return { success: true };
    }

    if (action.type === 'click') {
      if (action.ref) return await runRefAction(action.ref, 'el.click();');
      const exists = await evalScript(`!!document.querySelector(${JSON.stringify(action.selector)})`);
      if (exists !== 'true') return { success: false, error: `Element not found: ${action.selector}` };
      await evalScript(`document.querySelector(${JSON.stringify(action.selector)})?.click();`);
      return { success: true };
    }

    if (action.type === 'fill') {
      const write = `if('value'in el){el.value=${JSON.stringify(action.value)};el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));}else{el.textContent=${JSON.stringify(action.value)};}`;
      if (action.ref) return await runRefAction(action.ref, `el.focus();${write}`);
      const script = `(function(){const el=document.querySelector(${JSON.stringify(action.selector)});if(!el)return false;el.focus();if('value'in el){el.value=${JSON.stringify(action.value)};el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));return true;}el.textContent=${JSON.stringify(action.value)};return true;})()`;
      const wrote = await evalScript(script);
      if (wrote !== 'true') return { success: false, error: `Element not found: ${action.selector}` };
      return { success: true };
    }

    if (action.type === 'wait') {
      const timeout = action.timeout ?? 5000;
      const end = Date.now() + timeout;
      while (Date.now() < end) {
        const ok = await evalScript(`(function(){try{return !!document.querySelector(${JSON.stringify(action.condition)});}catch{return false;}})()`);
        if (ok === 'true') return { success: true };
        await new Promise((resolve) => setTimeout(resolve, 100));
      }
      return { success: false, error: `Timeout waiting for condition: ${action.condition}` };
    }

    if (action.type === 'keyboard') {
      await evalScript(`(function(){const target=document.activeElement||document.body;const ev=new KeyboardEvent('keydown',{key:${JSON.stringify(action.key)},bubbles:true});target.dispatchEvent(ev);return true;})()`);
      return { success: true };
    }

    if (action.type === 'scroll') {
      const delta = action.direction === 'up' ? [0, -400] : action.direction === 'down' ? [0, 400] : action.direction === 'left' ? [-400, 0] : [400, 0];
      await evalScript(`window.scrollBy(${delta[0]}, ${delta[1]});`);
      return { success: true };
    }

    const inner = action.action;
    if (action.type === 'iframe_interact') {
      if (inner.type === 'click') {
        const clicked = await evalScript(`(function(){const frame=document.querySelector(${JSON.stringify(action.frameSelector)});if(!frame||!frame.contentWindow||!frame.contentWindow.document)return false;const el=frame.contentWindow.document.querySelector(${JSON.stringify(inner.selector)});if(!el)return false;el.click();return true;})()`);
        if (clicked !== 'true') return { success: false, error: `Element not found in iframe: ${inner.selector}` };
        return { success: true };
      }
      if (inner.type === 'fill') {
        const wrote = await evalScript(`(function(){const frame=document.querySelector(${JSON.stringify(action.frameSelector)});if(!frame||!frame.contentWindow||!frame.contentWindow.document)return false;const el=frame.contentWindow.document.querySelector(${JSON.stringify(inner.selector)});if(!el)return false;if('value'in el){el.value=${JSON.stringify(inner.value)};el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));return true;}el.textContent=${JSON.stringify(inner.value)};return true;})()`);
        if (wrote !== 'true') return { success: false, error: `Element not found in iframe: ${inner.selector}` };
        return { success: true };
      }
      return { success: false, error: 'Unsupported iframe action' };
    }

    return { success: false, error: 'Unsupported action' };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export function createAgentController(): AgentController {
  let state: AgentState = 'idle';
  let running = false;
  let stopped = false;
  let decisionResolver: ((decision: 'approve' | 'skip') => void) | null = null;

  const listeners: { [K in keyof AgentEventPayloads]?: Array<(payload: AgentEventPayloads[K]) => void> } = {};

  const emit = <K extends keyof AgentEventPayloads>(event: K, payload: AgentEventPayloads[K]) => {
    for (const handler of listeners[event] ?? []) handler(payload);
  };

  const setState = (next: AgentState) => {
    state = next;
    emit('state', next);
    if (next === 'thinking') emit('thinking', undefined);
  };

  const waitForDecision = async (): Promise<'approve' | 'skip'> => {
    return await new Promise<'approve' | 'skip'>((resolve) => {
      decisionResolver = resolve;
    });
  };

  return {
    on(event, handler) {
      const arr = listeners[event] ?? [];
      arr.push(handler as never);
      listeners[event] = arr;
      return () => {
        const current = listeners[event] ?? [];
        listeners[event] = current.filter((h) => h !== handler) as never;
      };
    },

    getState() {
      return state;
    },

    async start(config) {
      if (running) return;
      running = true;
      stopped = false;

      const loopConfig: AgentConfig = { ...DEFAULT_AGENT_CONFIG, ...(config.config ?? {}) };
      const history: AgentMessage[] = [
        { role: 'system', content: AGENT_SYSTEM_PROMPT },
        { role: 'user', content: config.initialMessage },
      ];
      // One CLI session per run: turn 1 opens it, the rest resume it so the model keeps
      // the conversation and its prompt cache stays warm.
      const sessionId = crypto.randomUUID();

      let steps = 0;
      let consecutiveFailures = 0;
      let lastActionKey = '';
      let repeatCount = 0;

      while (!stopped) {
        if (steps >= loopConfig.maxSteps) {
          setState('done');
          emit('done', { message: `Reached maximum step limit (${loopConfig.maxSteps})` });
          break;
        }

        setState('thinking');

        let dom = '';
        let screenshot: string | undefined;
        try {
          const elements = await captureInteractiveDom();
          const pageText = await capturePageText().catch(() => '');
          dom = formatDomForPrompt(elements, pageText);
        } catch {
        }

        try {
          screenshot = await captureWebviewScreenshot();
        } catch {
        }

        let response: AgentApiResponse;
        try {
          response = await sendAgentRequest({
            messages: history,
            dom: dom || undefined,
            screenshot,
            provider: config.provider,
            model: config.model,
            sessionId,
          });
        } catch (error: unknown) {
          setState('error');
          emit('error', { message: error instanceof Error ? error.message : String(error) });
          break;
        }

        if ('text' in response) {
          emit('text', { content: response.text });
          history.push({ role: 'assistant', content: response.text });
          setState('done');
          emit('done', { message: response.text });
          break;
        }

        if (response.action === 'done') {
          const success = response.params.success !== false;
          setState(success ? 'done' : 'error');
          emit('done', { message: (response.params.message as string | undefined) ?? 'Task completed' });
          break;
        }

        const action = toBrowserAction(response.action, response.params);
        if (!action) {
          setState('error');
          emit('error', { message: `Invalid agent action: ${response.action}` });
          break;
        }

        const actionKey = JSON.stringify(action);
        if (actionKey === lastActionKey) {
          repeatCount += 1;
          if (repeatCount >= loopConfig.maxSameAction) {
            setState('done');
            emit('done', { message: `Loop detected after ${repeatCount} repeated actions` });
            break;
          }
        } else {
          lastActionKey = actionKey;
          repeatCount = 1;
        }

        setState('proposing');
        emit('proposing', { action, reasoning: response.reasoning ?? '' });

        if (config.mode === 'review') {
          const decision = await waitForDecision();
          decisionResolver = null;
          if (decision === 'skip') {
            history.push({ role: 'user', content: 'Action skipped. Propose a different action.' });
            steps += 1;
            continue;
          }
        }

        setState('executing');
        emit('executing', { action });

        let result = await executeAction(action);

        // Only the selector path needs fuzzy recovery. A failed ref means stale/missing
        // registry, and the next loop iteration re-captures and re-refs anyway.
        if (!result.success && (action.type === 'click' || action.type === 'fill') && !action.ref) {
          try {
            const elements = await captureInteractiveDom();
            const failedSelector = action.selector ?? '';
            const match = findFuzzyMatch(failedSelector, elements);
            if (match) {
              const retryAction: BrowserAction =
                action.type === 'click'
                  ? { type: 'click', ref: match.ref }
                  : { type: 'fill', ref: match.ref, value: action.value };
              result = await executeAction(retryAction);
              if (result.success) {
                const reason = fuzzyMatchReason(failedSelector, match);
                result = { success: true, data: { matchedRef: match.ref, reason } };
              }
            }
          } catch {
          }
        }

        emit('result', { action, result });

        history.push({ role: 'assistant', content: JSON.stringify({ action: action.type, params: action, reasoning: response.reasoning ?? '' }) });
        history.push({ role: 'result', content: JSON.stringify(result), action, result });

        if (!result.success) {
          consecutiveFailures += 1;
          if (consecutiveFailures >= loopConfig.maxConsecutiveFailures) {
            setState('done');
            emit('done', { message: `Too many consecutive failures (${consecutiveFailures})` });
            break;
          }
        } else {
          consecutiveFailures = 0;
        }

        steps += 1;

        if (loopConfig.actionDelayMs > 0) {
          await new Promise((resolve) => setTimeout(resolve, loopConfig.actionDelayMs));
        }
      }

      forgetAgentSession(sessionId);
      running = false;
      decisionResolver = null;
      if (state !== 'done' && state !== 'error' && stopped) {
        setState('idle');
      }
    },

    stop() {
      stopped = true;
      if (decisionResolver) {
        decisionResolver('skip');
        decisionResolver = null;
      }
      if (state !== 'done' && state !== 'error') {
        setState('idle');
      }
    },

    approve() {
      if (decisionResolver) {
        decisionResolver('approve');
      }
    },

    skip() {
      if (decisionResolver) {
        decisionResolver('skip');
      }
    },
  };
}

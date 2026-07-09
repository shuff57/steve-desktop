import { captureWebviewScreenshot, evalScript, getActiveTabId, getEmbeddedUrl, navigateEmbedded } from './browser';
import { matchCredentialsToUrl, loginFieldSelectors, type SiteCredential } from './autofill';
import { totpNow } from './totp';
import { sendAgentRequest } from './agent-api';
import { AGENT_SYSTEM_PROMPT } from './agent-prompt';
import { captureInteractiveDom, findFuzzyMatch, formatDomForPrompt, fuzzyMatchReason } from './agent-dom';
import { cdp } from './cdp-client';
import { isConnected } from './cdp-actions';
import { captureMergedTree } from './merged-tree';
import { redactTree } from './redact-tree';
import { selectorToElementExpr } from './selector-resolve';
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
import type { Redactor } from './redact';
import { selectSkills, skillsToPrompt, type MatchableSkill } from './skill-match';

// Dry-run guard: irreversible/outward actions are verified but never performed.
const DESTRUCTIVE_TARGET = /\b(send|submit|delete|remove|discard|destroy|pay|purchase|place\s*order|confirm)\b/i;
const DRY_RUN_NOTE =
  '\n\nDRY RUN MODE: this is a rehearsal. Walk the entire flow so every step can be ' +
  'checked, but know that destructive actions (send, submit, delete) will be VERIFIED ' +
  '(the target is located) and NOT actually performed. Do not treat a blocked send as failure.';

type AgentEventPayloads = {
  state: AgentState;
  thinking: undefined;
  proposing: { action: BrowserAction };
  executing: { action: BrowserAction };
  result: { action: BrowserAction; result: ActionResult };
  needsApproval: { site?: string };
  approvalDone: { approved: boolean };
  done: { message: string };
  error: { message: string };
  text: { content: string };
};

// How long the agent waits at a Duo/2FA push before giving up (per user choice).
const APPROVAL_TIMEOUT_MS = 3 * 60 * 1000;

/**
 * Is the embedded page currently on a Duo 2FA challenge? Used to gate the
 * agent after login: if a Duo push is required, the loop pauses for a human tap.
 * Covers the Universal Prompt (full-page redirect to *.duosecurity.com), an
 * embedded Duo iframe, and text-based prompts as a fallback.
 */
async function isDuoChallenge(): Promise<boolean> {
  const url = await getEmbeddedUrl(getActiveTabId()).catch(() => '');
  if (/duosecurity\.com/i.test(url)) return true;
  const r = await evalScript(
    `(function(){try{if(/duosecurity\\.com/i.test(location.href))return 'y';` +
      `if(document.querySelector('iframe[src*="duosecurity.com"]'))return 'y';` +
      `var t=(document.body&&document.body.innerText)||'';` +
      `if(/Send Me a Push|Duo Push|Approve.*Duo|Waiting for.*Duo|Check for a Duo Push/i.test(t))return 'y';` +
      `return 'n';}catch(e){return 'n';}})()`,
  ).catch(() => 'n');
  return r === 'y';
}

export interface AgentStartConfig {
  mode: AgentMode;
  initialMessage: string;
  provider?: string;
  model?: string;
  config?: Partial<AgentConfig>;
  /** When set, every model call is redacted/rehydrated at the trust boundary. */
  redactor?: Redactor;
  /** Saved skills to consider; relevant ones are injected into the system prompt. */
  skills?: MatchableSkill[];
  /** Current page URL, used to match skills by url_pattern. */
  pageUrl?: string;
  /** Saved credentials, used by the login action to auth on-device. */
  credentials?: SiteCredential[];
  /** Rehearse without performing destructive actions. Also auto-on if the goal says "dry run". */
  dryRun?: boolean;
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
  /** Manually resume after a Duo/2FA push once the user has tapped Approve. */
  continueApproval(): void;
  getState(): AgentState;
}

function toBrowserAction(action: string, params: Record<string, unknown>): BrowserAction | null {
  if (action === 'click' && typeof params.selector === 'string') {
    return { type: 'click', selector: params.selector };
  }
  if ((action === 'fill' || action === 'type') && typeof params.selector === 'string') {
    const value = typeof params.value === 'string' ? params.value : typeof params.text === 'string' ? params.text : '';
    return { type: 'fill', selector: params.selector, value };
  }
  if (action === 'read' && typeof params.selector === 'string' && typeof params.into === 'string') {
    return { type: 'read', selector: params.selector, into: params.into };
  }
  if (action === 'paste' && typeof params.selector === 'string' && typeof params.from === 'string') {
    return { type: 'paste', selector: params.selector, from: params.from };
  }
  if (action === 'login') {
    return { type: 'login', ...(typeof params.site === 'string' ? { site: params.site } : {}) };
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

/** Restore slot-redaction tokens to real values in selector/value, in place. */
function rehydrateAction(action: BrowserAction, rehydrate: (t: string) => string): void {
  if ('selector' in action) action.selector = rehydrate(action.selector);
  if (action.type === 'fill') action.value = rehydrate(action.value);
  if (action.type === 'iframe_interact') rehydrateAction(action.action, rehydrate);
}

async function executeAction(
  action: BrowserAction,
  register: Map<string, string>,
  dryRun = false,
  credentials: SiteCredential[] = [],
): Promise<ActionResult> {
  try {
    // Log in with saved credentials, filled on-device. The username/password are
    // embedded only in the injected script (local) and the page — never returned.
    if (action.type === 'login') {
      if (!credentials.length) return { success: false, error: 'No saved credentials configured' };
      const url = await getEmbeddedUrl(getActiveTabId()).catch(() => '');
      const cred = matchCredentialsToUrl(url, credentials);
      if (!cred) return { success: false, error: `No saved credential matches ${url || 'this page'}` };
      if (dryRun) return { success: true, data: { dryRun: true, would: 'login', site: cred.site_name } };
      const sel = loginFieldSelectors(url);
      // Current 2FA code, generated on-device from the saved seed. Empty if none.
      const otp = cred.totp_secret ? await totpNow(cred.totp_secret).catch(() => '') : '';
      const otpSel =
        'input[autocomplete="one-time-code"], input[name*="otp" i], input[name*="totp" i], input[id*="otp" i], input[name*="2fa" i], input[name*="verification" i]';
      // Fill username/password when present; on a follow-up 2FA page only the
      // one-time-code field exists, so we submit off whichever field we found.
      const script = `(function(){var u=document.querySelector(${JSON.stringify(sel.usernameSelector)});var p=document.querySelector(${JSON.stringify(sel.passwordSelector)});var o=document.querySelector(${JSON.stringify(otpSel)});if(!p&&!o)return 'no-form';function setv(el,v){var n=Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype,'value');if(n&&n.set)n.set.call(el,v);else el.value=v;el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));}if(u&&p)setv(u,${JSON.stringify(cred.username)});if(p)setv(p,${JSON.stringify(cred.password)});var code=${JSON.stringify(otp)};if(o&&code)setv(o,code);var anchor=p||o;var f=anchor.form||(u&&u.form);if(f){var b=f.querySelector('button[type=submit],input[type=submit]');if(b)b.click();else if(f.requestSubmit)f.requestSubmit();else f.submit();}return 'filled';})()`;
      const r = await evalScript(script);
      if (r === 'no-form') return { success: false, error: 'No login form found on this page' };
      return { success: true, data: { site: cred.site_name } };
    }

    // PII-safe transfer. The value is read into / written from `register`, which
    // lives in the loop (not the page, not the model). It survives tab switches,
    // so a value read on page A pastes into page B. The model only ever sees the
    // slot name and, for read, the value's LENGTH — never the value itself.
    if (action.type === 'read') {
      const el = selectorToElementExpr(action.selector);
      const raw = await evalScript(
        `(function(){var el=${el};if(!el)return JSON.stringify({f:false});var v=('value'in el&&el.value!=null)?String(el.value):(el.textContent||'');return JSON.stringify({f:true,v:v});})()`,
      );
      let parsed: { f: boolean; v?: string };
      try {
        parsed = JSON.parse(typeof raw === 'string' ? raw : String(raw));
      } catch {
        return { success: false, error: `Could not read: ${action.selector}` };
      }
      if (!parsed.f) return { success: false, error: `Element not found: ${action.selector}` };
      const value = parsed.v ?? '';
      register.set(action.into, value);
      return { success: true, data: { stored: action.into, length: value.length } };
    }

    if (action.type === 'paste') {
      if (!register.has(action.from)) return { success: false, error: `No stored value in slot "${action.from}"` };
      const value = register.get(action.from)!;
      const el = selectorToElementExpr(action.selector);
      const script = `(function(){var el=${el};if(!el)return false;el.focus();if('value'in el){el.value=${JSON.stringify(value)};el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));return true;}el.textContent=${JSON.stringify(value)};return true;})()`;
      const wrote = await evalScript(script);
      if (wrote !== 'true') return { success: false, error: `Element not found: ${action.selector}` };
      return { success: true };
    }

    if (action.type === 'navigate') {
      const tabId = getActiveTabId();
      if (!tabId) return { success: false, error: 'No active tab' };
      await navigateEmbedded(tabId, action.url);
      return { success: true };
    }

    if (action.type === 'click') {
      // selectorToElementExpr resolves css, role=name, and xpath= so role-name anchors
      // from the merged AX tree are clickable (raw querySelector can't run role=…).
      const el = selectorToElementExpr(action.selector);
      // Dry run: a destructive click is verified (located + labelled) but NOT performed.
      if (dryRun) {
        const probe = await evalScript(
          `(function(){var el=${el};if(!el)return JSON.stringify({f:false});var t=(el.innerText||el.getAttribute('aria-label')||'').trim();return JSON.stringify({f:true,t:t});})()`,
        );
        let info: { f: boolean; t?: string } = { f: false };
        try { info = JSON.parse(typeof probe === 'string' ? probe : String(probe)); } catch { /* keep default */ }
        const destructive = DESTRUCTIVE_TARGET.test(action.selector) || (!!info.t && DESTRUCTIVE_TARGET.test(info.t));
        if (destructive) {
          return { success: true, data: { dryRun: true, blocked: true, would: 'click', resolved: info.f, target: (info.t ?? action.selector).slice(0, 40) } };
        }
      }
      const clicked = await evalScript(`(function(){var el=${el};if(!el)return false;el.click();return true;})()`);
      if (clicked !== 'true') return { success: false, error: `Element not found: ${action.selector}` };
      return { success: true };
    }

    if (action.type === 'fill') {
      const el = selectorToElementExpr(action.selector);
      const script = `(function(){var el=${el};if(!el)return false;el.focus();if('value'in el){el.value=${JSON.stringify(action.value)};el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));return true;}el.textContent=${JSON.stringify(action.value)};return true;})()`;
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
  // Duo/2FA push gate: resolves true when the page leaves the Duo challenge
  // (auto-detected) or the user clicks Continue; false on stop/timeout.
  let approvalResolve: ((approved: boolean) => void) | null = null;
  let approvalTimer: ReturnType<typeof setTimeout> | null = null;

  const settleApproval = (approved: boolean) => {
    if (!approvalResolve) return;
    const resolve = approvalResolve;
    approvalResolve = null;
    if (approvalTimer) {
      clearTimeout(approvalTimer);
      approvalTimer = null;
    }
    resolve(approved);
  };

  // Poll every 2s until the Duo screen clears, the user taps Continue, or we time out.
  const awaitDuoApproval = (): Promise<boolean> =>
    new Promise<boolean>((resolve) => {
      approvalResolve = resolve;
      const deadline = Date.now() + APPROVAL_TIMEOUT_MS;
      const poll = async () => {
        if (!approvalResolve) return; // already settled (Continue / stop)
        if (stopped) return settleApproval(false);
        let cleared = false;
        try {
          cleared = !(await isDuoChallenge());
        } catch {
          cleared = false;
        }
        if (cleared) return settleApproval(true);
        if (Date.now() >= deadline) return settleApproval(false);
        approvalTimer = setTimeout(poll, 2000);
      };
      approvalTimer = setTimeout(poll, 1500);
    });

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
      // Dry run is explicit (config) or requested in the goal ("dry run …").
      const dryRun = config.dryRun ?? /\bdry[\s-]?run\b/i.test(config.initialMessage);
      // Inject any saved skill relevant to this goal/page into the system prompt.
      const matched = config.skills ? selectSkills(config.skills, { url: config.pageUrl, message: config.initialMessage }) : [];
      const systemContent = AGENT_SYSTEM_PROMPT + skillsToPrompt(matched) + (dryRun ? DRY_RUN_NOTE : '');
      const history: AgentMessage[] = [
        { role: 'system', content: systemContent },
        { role: 'user', content: config.initialMessage },
      ];

      let steps = 0;
      let consecutiveFailures = 0;
      let lastActionKey = '';
      let repeatCount = 0;
      // On-device slot store for PII-safe read→paste. Holds raw values transiently;
      // wiped when the run ends so nothing lingers in memory.
      const register = new Map<string, string>();

      while (!stopped) {
        if (steps >= loopConfig.maxSteps) {
          setState('done');
          emit('done', { message: `Reached maximum step limit (${loopConfig.maxSteps})` });
          break;
        }

        setState('thinking');

        let dom = '';
        let screenshot: string | undefined;
        // Local-only rehydration for the slot-redacted tree: a token (⟦D1⟧) the model
        // echoes into a fill value is restored to the real value HERE, on-device, and
        // typed into the page — the raw PII never leaves the machine.
        let rehydrate: ((t: string) => string) | undefined;
        try {
          if (isConnected()) {
            // Phase 1 representation: DOM⨝AX merged tree, slot-redacted (Phase 0) before it
            // can reach the model. It carries computed AX names, so deny-by-default slot
            // redaction is mandatory here — the value dictionary alone would leak them.
            const { snapshot } = await captureMergedTree(cdp);
            const red = redactTree(snapshot);
            dom = red.redactedText;
            rehydrate = red.rehydrate;
          } else {
            const elements = await captureInteractiveDom();
            dom = formatDomForPrompt(elements);
          }
        } catch {
          // CDP capture failed mid-loop — fall back to injected-JS capture so the app
          // keeps working rather than stalling the agent.
          try {
            const elements = await captureInteractiveDom();
            dom = formatDomForPrompt(elements);
          } catch {
          }
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
          }, config.redactor);
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
        if (rehydrate) rehydrateAction(action, rehydrate);

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
        emit('proposing', { action });

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

        let result = await executeAction(action, register, dryRun, config.credentials ?? []);

        if (!result.success && (action.type === 'click' || action.type === 'fill')) {
          try {
            const elements = await captureInteractiveDom();
            const failedSelector = action.selector;
            const match = findFuzzyMatch(failedSelector, elements);
            if (match) {
              const retryAction: BrowserAction =
                action.type === 'click'
                  ? { type: 'click', selector: match.selector }
                  : { type: 'fill', selector: match.selector, value: action.value };
              result = await executeAction(retryAction, register, dryRun, config.credentials ?? []);
              if (result.success) {
                const reason = fuzzyMatchReason(failedSelector, match);
                result = { success: true, data: { matchedSelector: match.selector, reason } };
              }
            }
          } catch {
          }
        }

        // 2FA gate: if login landed on a Duo push, pause for the human tap.
        // (Passcode/TOTP sites fill + submit in the login action and clear on their own.)
        if (action.type === 'login' && result.success && !dryRun) {
          try {
            if (await isDuoChallenge()) {
              setState('awaiting-approval');
              emit('needsApproval', { site: (result.data as { site?: string } | undefined)?.site });
              const approved = await awaitDuoApproval();
              emit('approvalDone', { approved });
              if (!approved) {
                result = { success: false, error: 'Duo approval not received (timed out or cancelled)' };
              } else if (!stopped) {
                setState('executing');
              }
            }
          } catch {
            // detection/gate failure must not crash the run; fall through with the login result
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

      running = false;
      decisionResolver = null;
      register.clear(); // wipe any transferred PII when the run ends
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
      settleApproval(false); // release a pending Duo gate so the run can unwind
      if (state !== 'done' && state !== 'error') {
        setState('idle');
      }
    },

    continueApproval() {
      settleApproval(true);
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

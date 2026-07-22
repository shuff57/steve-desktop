import type { AgentMessage } from './agent-types';

export type AgentEngine = 'claude' | 'opencode';

/**
 * Maps a provider id (as stored in provider_configs) to the CLI that backs it.
 * Anything not explicitly claude-backed goes to opencode, which can front many
 * providers via its own `provider/model` ids.
 */
export function engineForProvider(providerId?: string): AgentEngine {
  return providerId === 'anthropic' || providerId === 'claude' ? 'claude' : 'opencode';
}

/**
 * Normalises a model id for the CLI's --model/-m flag.
 *
 * opencode expects `provider/model`; the UI lets the user type a bare ollama.com cloud
 * model (e.g. `kimi-k2.6:cloud`), so a bare id gets the `ollama/` prefix. Ids that
 * already carry a provider pass through, as does everything for claude.
 */
export function cliModelArg(engine: AgentEngine, model?: string): string | null {
  const m = model?.trim();
  if (!m) return null;
  if (engine === 'opencode' && !m.includes('/')) return `ollama/${m}`;
  return m;
}

/**
 * Renders the newest turn for a resumed CLI session.
 *
 * Only the delta is sent: the CLI already holds the conversation, so replaying the
 * whole history each turn would duplicate it and defeat the prompt cache. The first
 * turn carries the task, later turns carry the last action's result.
 */
export function buildTurnPrompt(messages: AgentMessage[], dom: string | undefined, isFirstTurn: boolean): string {
  const parts: string[] = [];

  if (isFirstTurn) {
    const task = messages.find((m) => m.role === 'user');
    parts.push(`TASK: ${task?.content ?? ''}`);
  } else {
    const last = [...messages].reverse().find((m) => m.role === 'result');
    if (last) parts.push(`RESULT OF YOUR LAST ACTION: ${last.content}`);
  }

  parts.push(dom?.trim() ? `CURRENT PAGE:\n${dom}` : 'CURRENT PAGE: (no elements captured)');
  parts.push('Respond with exactly one JSON object and nothing else.');

  return parts.join('\n\n');
}

interface ClaudeCliResult {
  result?: string;
  is_error?: boolean;
  subtype?: string;
}

/**
 * Pulls the assistant's text out of a CLI's stdout.
 *
 * `claude -p --output-format json` wraps the reply in an envelope whose `result` holds
 * the text; opencode prints the reply directly. Anything unrecognised is passed through
 * for parseAgentResponse to salvage.
 */
export function extractCliText(engine: AgentEngine, stdout: string): string {
  const raw = stdout.trim();
  if (!raw) throw new Error(`${engine} returned no output`);

  if (engine === 'claude') {
    // Single json envelope (--output-format json): one object holding the result.
    let parsed: ClaudeCliResult | null = null;
    try {
      parsed = JSON.parse(raw) as ClaudeCliResult;
    } catch {
      parsed = null;
    }
    if (parsed) {
      if (parsed.is_error) {
        throw new Error(`claude reported an error (${parsed.subtype ?? 'unknown'}): ${parsed.result ?? ''}`.trim());
      }
      if (typeof parsed.result === 'string') return parsed.result;
      return raw;
    }
    // stream-json (--output-format stream-json): NDJSON, one event per line; the terminal
    // {"type":"result",...} event carries the final text. Scan from the end for it.
    const lines = raw.split('\n').map((l) => l.trim()).filter(Boolean);
    for (let i = lines.length - 1; i >= 0; i--) {
      let ev: (ClaudeCliResult & { type?: string }) | null = null;
      try {
        ev = JSON.parse(lines[i]);
      } catch {
        continue;
      }
      if (ev?.type === 'result') {
        if (ev.is_error) {
          throw new Error(`claude reported an error (${ev.subtype ?? 'unknown'}): ${ev.result ?? ''}`.trim());
        }
        if (typeof ev.result === 'string') return ev.result;
      }
    }
    return raw;
  }

  return raw;
}

/**
 * Compress one stream-json NDJSON line into a short human progress string for the UI, or
 * null for lines not worth showing. Kept here so it's unit-testable off the live stream.
 */
/** Turn a raw browser-driving Bash command (usually a CDP call) into a plain-English snapshot of
 *  what the agent is doing, instead of dumping the raw command. Returns a short verb phrase. */
export function describeBrowserCommand(command: string): string {
  const c = command.toLowerCase();
  // Multi-tab bridge calls (__steveControl.*) ride Runtime.evaluate, so name them before the
  // generic evaluate → "reading the page" catch-all below would swallow them.
  if (c.includes('__stevecontrol.newtab')) return 'opening a new tab';
  if (c.includes('__stevecontrol.login')) return 'logging in';
  if (c.includes('__stevecontrol.activate')) return 'switching tabs';
  if (c.includes('__stevecontrol.listtabs')) return 'checking open tabs';
  if (c.includes('__stevecontrol.closetab')) return 'closing a tab';
  if (c.includes('__stevecontrol.startrecording')) return 'starting a recording';
  if (c.includes('__stevecontrol.stoprecording')) return 'stopping the recording';
  if (c.includes('setfileinputfiles')) return 'attaching a file';
  if (c.includes('page.navigate') || /\bnavigate\b|\bgoto\b/.test(c)) {
    const url = command.match(/https?:\/\/[^\s"'`)]+/);
    return url ? `navigating to ${url[0].replace(/^https?:\/\//, '').slice(0, 48)}` : 'navigating';
  }
  if (c.includes('capturescreenshot')) return 'taking a screenshot';
  if (c.includes('page.reload')) return 'reloading the page';
  if (c.includes('dispatchmouseevent') || c.includes('.click(') || /\bclick\b/.test(c)) return 'clicking';
  if (c.includes('inserttext') || c.includes('dispatchkeyevent') || /\bfill\b|\.value\s*=/.test(c)) return 'filling a field';
  if (c.includes('runtime.evaluate') || c.includes('document.') || c.includes('json/list') || c.includes('innertext')) return 'reading the page';
  if (c.includes('cdp') || c.includes('websocket') || c.includes('devtools')) return 'driving the browser';
  return 'running a command';
}

// Distil one stream-json event into a short, human snapshot for the live activity feed — or null to
// drop it. We deliberately skip session/system events and successful-completion events (the phase
// label already conveys those) and never echo the plan/result markdown here (it is rendered on its
// own), so the feed shows what the agent is DOING, not raw command spam.
export function summarizeCliLine(line: string): string | null {
  let ev: {
    type?: string;
    message?: { content?: { type?: string; name?: string; text?: string; input?: { command?: string } }[] };
    is_error?: boolean;
  };
  try {
    ev = JSON.parse(line);
  } catch {
    return null;
  }
  if (ev.type === 'system') return null; // "session started" noise
  if (ev.type === 'result') return ev.is_error ? 'agent reported an error' : null;
  if (ev.type === 'assistant' && Array.isArray(ev.message?.content)) {
    for (const c of ev.message.content) {
      if (c.type === 'tool_use') {
        if (c.name === 'Bash' && c.input?.command) return describeBrowserCommand(c.input.command);
        return c.name ? `using ${c.name}` : null;
      }
      if (c.type === 'text' && c.text?.trim()) {
        const t = c.text.trim();
        if (/^#{1,6}\s|^\d+\.\s+\*\*\[MUTATES\]/.test(t)) return null; // plan/result markdown, shown separately
        return t.replace(/\s+/g, ' ').slice(0, 110);
      }
    }
  }
  return null;
}

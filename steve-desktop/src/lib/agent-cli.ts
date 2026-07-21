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
  if (ev.type === 'system') return 'session started';
  if (ev.type === 'result') return ev.is_error ? 'agent reported an error' : 'writing site map';
  if (ev.type === 'assistant' && Array.isArray(ev.message?.content)) {
    for (const c of ev.message.content) {
      if (c.type === 'tool_use') {
        if (c.name === 'Bash' && c.input?.command) return `$ ${c.input.command.replace(/\s+/g, ' ').slice(0, 90)}`;
        return c.name ? `tool: ${c.name}` : null;
      }
      if (c.type === 'text' && c.text?.trim()) return c.text.trim().replace(/\s+/g, ' ').slice(0, 110);
    }
  }
  return null;
}

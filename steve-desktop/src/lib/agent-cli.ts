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
    let parsed: ClaudeCliResult;
    try {
      parsed = JSON.parse(raw) as ClaudeCliResult;
    } catch {
      return raw;
    }
    if (parsed.is_error) {
      throw new Error(`claude reported an error (${parsed.subtype ?? 'unknown'}): ${parsed.result ?? ''}`.trim());
    }
    if (typeof parsed.result === 'string') return parsed.result;
    return raw;
  }

  return raw;
}

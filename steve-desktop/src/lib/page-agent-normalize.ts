// Adapted from page-agent (MIT, Copyright (c) 2025 Alibaba Group Holding Limited)
// Source: packages/core/src/utils/autoFixer.ts — normalizeResponse
// See LICENSE-page-agent.txt in this directory for full notice.

/**
 * Repair the shapes models actually emit instead of the one they were asked for.
 *
 * Every case here is a real reply shape, not a hypothetical: upstream collected
 * most of them, and two (JSON-as-content, primitive params) we hit ourselves —
 * they cost gemma4 its first MyOpenMath run and made gpt-oss:20b and
 * minimax-m3 look incapable when they were merely untidy.
 *
 * Adapted rather than copied: upstream validates with zod schemas, which this
 * codebase does not use, so single-field coercion reads an explicit
 * `primaryParam` off the tool instead of inferring the required key.
 */

import type { MacroToolOutput, PageAgentTool } from './page-agent-tools';

interface LLMResponse {
  choices?: {
    message?: {
      content?: string | null;
      tool_calls?: { function?: { name?: string; arguments?: string } }[];
    };
  }[];
}

/** Parse if it is JSON, otherwise hand back what came in. */
function safeJsonParse(input: unknown): unknown {
  if (typeof input !== 'string') return input;
  try {
    return JSON.parse(input.trim());
  } catch {
    return input;
  }
}

/** Pull the outermost {...} out of prose or a ```json fence. */
export function retrieveJsonFromString(str: string): unknown {
  try {
    const fenced = /```(?:json)?\s*([\s\S]*?)```/.exec(str);
    const body = fenced ? fenced[1] : str;
    const match = /({[\s\S]*})/.exec(body);
    return match ? JSON.parse(match[0]) : null;
  } catch {
    return null;
  }
}

const REFLECTION_KEYS = ['action', 'evaluation_previous_goal', 'memory', 'next_goal', 'thinking'];

function looksLikeActionLevel(value: Record<string, unknown>): boolean {
  return !REFLECTION_KEYS.some((k) => k in value);
}

function isRecord(v: unknown): v is Record<string, unknown> {
  return typeof v === 'object' && v !== null;
}

/**
 * Coerce a bare scalar into the tool's primary field.
 * `{"click_element_by_index": 4}` → `{"click_element_by_index": {"index": 4}}`
 */
function coerceActionInput(
  action: Record<string, unknown>,
  tools: PageAgentTool<any>[],
): Record<string, unknown> {
  const name = Object.keys(action)[0];
  if (!name) return action;
  const value = action[name];
  if (isRecord(value)) return action;

  const tool = tools.find((t) => t.name === name);
  // Unknown tools are left alone: executeTool answers them with the list of
  // legal tools, which teaches the model more than throwing here would.
  const key = tool?.primaryParam;
  if (!key) return action;
  return { [name]: { [key]: value } };
}

export interface NormalizeResult {
  output: MacroToolOutput;
  /** Which repairs fired — surfaced so a silently-mangled reply is still visible. */
  repairs: string[];
}

/**
 * Turn whatever the endpoint returned into a MacroToolOutput, or throw if there
 * is genuinely nothing usable in it.
 */
export function normalizeMacroOutput(
  response: unknown,
  tools: PageAgentTool<any>[],
): NormalizeResult {
  const repairs: string[] = [];
  const choice = (response as LLMResponse)?.choices?.[0];
  if (!choice) throw new Error('LLM returned no choices');
  const message = choice.message;
  if (!message) throw new Error('LLM returned no message');

  const toolCall = message.tool_calls?.[0];
  let resolved: unknown;

  if (toolCall?.function?.arguments) {
    resolved = safeJsonParse(toolCall.function.arguments);
    // The model called the ACTION as the tool, rather than wrapping it in AgentOutput.
    if (toolCall.function.name && toolCall.function.name !== 'AgentOutput') {
      repairs.push(`action-as-tool-call (${toolCall.function.name})`);
      resolved = { action: { [toolCall.function.name]: safeJsonParse(resolved) } };
    }
  } else if (message.content) {
    const fromContent = retrieveJsonFromString(message.content);
    if (fromContent === null) throw new Error('LLM returned no tool call');
    repairs.push('json-in-content');
    resolved = fromContent;

    // Content that carries the tool-call envelope rather than its arguments.
    if (isRecord(resolved) && resolved.name === 'AgentOutput') {
      repairs.push('unwrap-agentoutput-envelope');
      resolved = safeJsonParse(resolved.arguments);
    }
    if (isRecord(resolved) && resolved.type === 'function' && isRecord(resolved.function)) {
      repairs.push('unwrap-function-envelope');
      resolved = safeJsonParse((resolved.function as Record<string, unknown>).arguments);
    }
    if (isRecord(resolved) && looksLikeActionLevel(resolved)) {
      repairs.push('action-level-only');
      resolved = { action: resolved };
    }
  } else {
    throw new Error('LLM returned no tool call');
  }

  // Arguments stringified twice over.
  resolved = safeJsonParse(resolved);
  if (!isRecord(resolved)) throw new Error('LLM returned no tool call');
  if (typeof resolved.action === 'string') {
    repairs.push('double-stringified-action');
    resolved.action = safeJsonParse(resolved.action);
  }

  if (isRecord(resolved.action)) {
    const before = JSON.stringify(resolved.action);
    resolved.action = coerceActionInput(resolved.action as Record<string, unknown>, tools);
    if (JSON.stringify(resolved.action) !== before) repairs.push('primitive-action-input');
  }

  // Nothing actionable: wait a beat rather than killing the run. A model stuck
  // here repeats it, and the loop's stall detection ends the run soon enough.
  if (!isRecord(resolved.action) || Object.keys(resolved.action).length === 0) {
    repairs.push('no-action → wait');
    resolved.action = { wait: { seconds: 1 } };
  }

  return { output: resolved as unknown as MacroToolOutput, repairs };
}

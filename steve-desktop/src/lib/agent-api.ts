import { invoke } from '@tauri-apps/api/core';
import type {
  AgentApiRequest,
  AgentApiResponse,
} from './agent-types';
import { AGENT_SYSTEM_PROMPT, parseAgentResponse } from './agent-prompt';
import { buildTurnPrompt, engineForProvider, extractCliText } from './agent-cli';
import { getActiveProvider } from './db';

/**
 * Sessions already opened on the CLI. The first request for an id opens the session and
 * the rest resume it, so the model keeps the conversation and its prompt cache.
 */
const openSessions = new Set<string>();

/** Called when an agent run ends, so a re-run does not try to resume a spent session. */
export function forgetAgentSession(sessionId: string): void {
  openSessions.delete(sessionId);
}

interface SendAgentMessageRequest {
  message: string;
  systemPrompt?: string;
  provider?: string;
  model?: string;
}

interface SendAgentMessageHandlers {
  onStatus?: (data: { status: string }) => void;
  onMessage?: (data: { content: string }) => void;
  onDone?: () => void;
  onError?: (data: { message: string }) => void;
}

/**
 * Asks the local agent CLI for the next browser action.
 *
 * Runs `claude`/`opencode` headlessly through the Rust side rather than calling a
 * provider's HTTP API: it reuses the CLI's own auth, so no API key is stored here.
 * (This used to POST to a hardcoded localhost:3456 that nothing ever served.)
 */
export async function sendAgentRequest(request: AgentApiRequest): Promise<AgentApiResponse> {
  const providerId = request.provider ?? (await getActiveProvider().catch(() => null))?.id;
  const engine = engineForProvider(providerId);

  const sessionId = request.sessionId ?? crypto.randomUUID();
  const isFirstTurn = !openSessions.has(sessionId);

  const stdout = await invoke<string>('run_agent_cli', {
    engine,
    prompt: buildTurnPrompt(request.messages, request.dom, isFirstTurn),
    sessionId,
    resume: !isFirstTurn,
    model: request.model || null,
    systemPrompt: isFirstTurn ? AGENT_SYSTEM_PROMPT : null,
  });

  // Only mark it open once a turn has actually landed, or a failed first turn would
  // leave later turns trying to resume a session that was never created.
  openSessions.add(sessionId);

  return parseAgentResponse(extractCliText(engine, stdout));
}

export async function sendAgentMessage(
  request: SendAgentMessageRequest,
  handlers: SendAgentMessageHandlers = {},
): Promise<void> {
  handlers.onStatus?.({ status: 'thinking' });

  const messages = [
    ...(request.systemPrompt
      ? [{ role: 'system' as const, content: request.systemPrompt }]
      : []),
    { role: 'user' as const, content: request.message },
  ];

  try {
    const response = await sendAgentRequest({
      messages,
      provider: request.provider,
      model: request.model,
    });

    const content =
      'text' in response
        ? response.text
        : response.reasoning
          ? `${response.reasoning}\n\n${JSON.stringify(response)}`
          : JSON.stringify(response);

    handlers.onMessage?.({ content });
    handlers.onDone?.();
  } catch (error: unknown) {
    const message = error instanceof Error ? error.message : String(error);
    handlers.onError?.({ message });
    throw error;
  }
}

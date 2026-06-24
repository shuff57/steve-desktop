import type {
  AgentApiRequest,
  AgentApiResponse,
  AgentActionResponse,
  AgentTextResponse,
} from './agent-types';
import { parseAgentResponse } from './agent-prompt';
import type { Redactor } from './redact';
import { redactRequest, rehydrateResponse } from './agent-redact';

const SERVER_BASE = 'http://localhost:3456';

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

export async function sendAgentRequest(
  request: AgentApiRequest,
  redactor?: Redactor,
): Promise<AgentApiResponse> {
  // Trust boundary: when a redactor is present, no identifier leaves the machine.
  // redactRequest strips messages/dom/snapshot and refuses to send if any known
  // identifier would still leak; the response is rehydrated locally below.
  const outbound = redactor ? redactRequest(request, redactor) : request;

  const response = await fetch(`${SERVER_BASE}/api/agent`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(outbound),
  });

  if (!response.ok) {
    let message = `Agent request failed (HTTP ${response.status})`;
    try {
      const errorData = (await response.json()) as { error?: string };
      message = errorData.error || message;
    } catch {
    }
    throw new Error(message);
  }

  const data = await response.json();

  const parse = (): AgentApiResponse => {
    if (data && typeof data === 'object') {
      if ('action' in data && typeof data.action === 'string') return data as AgentActionResponse;
      if ('text' in data && typeof data.text === 'string') return data as AgentTextResponse;
      if ('content' in data && typeof data.content === 'string') return parseAgentResponse(data.content);
      if ('response' in data && typeof data.response === 'string') return parseAgentResponse(data.response);
    }
    if (typeof data === 'string') return parseAgentResponse(data);
    return { text: JSON.stringify(data) };
  };

  const result = parse();
  return redactor ? rehydrateResponse(result, redactor) : result;
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

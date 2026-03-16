import type {
  AgentApiRequest,
  AgentApiResponse,
  AgentActionResponse,
  AgentTextResponse,
} from './agent-types';
import { parseAgentResponse } from './agent-prompt';

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

export async function sendAgentRequest(request: AgentApiRequest): Promise<AgentApiResponse> {
  const response = await fetch(`${SERVER_BASE}/api/agent`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(request),
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

  if (data && typeof data === 'object') {
    if ('action' in data && typeof data.action === 'string') {
      return data as AgentActionResponse;
    }
    if ('text' in data && typeof data.text === 'string') {
      return data as AgentTextResponse;
    }
    if ('content' in data && typeof data.content === 'string') {
      return parseAgentResponse(data.content);
    }
    if ('response' in data && typeof data.response === 'string') {
      return parseAgentResponse(data.response);
    }
  }

  if (typeof data === 'string') {
    return parseAgentResponse(data);
  }

  return { text: JSON.stringify(data) };
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

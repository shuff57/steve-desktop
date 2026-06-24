// Pure request/response mapping for the model sidecar. Given the agent's
// (already-redacted) request, build the provider-specific HTTP body and parse
// the provider's reply back into the { content } shape parseAgentResponse reads.
// No network here — the server (server/agent-server.ts) does the fetch.

export interface AgentMsg {
  role: string;
  content: string;
}
export interface AgentRequestBody {
  messages: AgentMsg[];
  dom?: string;
  provider?: string;
  model?: string;
}

const MAX_TOKENS = 1024;

function withDom(messages: AgentMsg[], dom: string | undefined): AgentMsg[] {
  return dom ? [...messages, { role: 'user', content: `Current page DOM:\n${dom}` }] : messages;
}

// ── Anthropic Messages API ────────────────────────────────────────────────
export function buildAnthropicBody(body: AgentRequestBody, model: string) {
  const system = body.messages
    .filter((m) => m.role === 'system')
    .map((m) => m.content)
    .join('\n\n');
  const convo = body.messages
    .filter((m) => m.role !== 'system')
    .map((m) => ({ role: m.role === 'assistant' ? 'assistant' : 'user', content: m.content }));
  return {
    model,
    max_tokens: MAX_TOKENS,
    ...(system ? { system } : {}),
    messages: withDom(convo, body.dom),
  };
}

export function parseAnthropicResponse(json: any): { content: string } {
  const content = (json?.content ?? [])
    .filter((b: any) => b?.type === 'text')
    .map((b: any) => b.text)
    .join('');
  return { content };
}

// ── OpenAI / Ollama chat-completions ──────────────────────────────────────
export function buildChatBody(body: AgentRequestBody, model: string) {
  const convo = body.messages.map((m) => ({
    role: m.role === 'result' ? 'user' : m.role,
    content: m.content,
  }));
  return { model, max_tokens: MAX_TOKENS, messages: withDom(convo, body.dom) };
}

export function parseChatResponse(json: any): { content: string } {
  // OpenAI: choices[0].message.content ; Ollama /api/chat: message.content
  const content = json?.choices?.[0]?.message?.content ?? json?.message?.content ?? '';
  return { content };
}

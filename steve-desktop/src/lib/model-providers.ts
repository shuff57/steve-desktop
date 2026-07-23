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
  /** Optional screenshot as a data: URL, for the visual heal tier. Attached to the last user
   *  message as a provider-native image block. Callers must mask it first — an image cannot be
   *  tokenized, so the redaction gate can't clean it after the fact. */
  image?: string;
}

const MAX_TOKENS = 1024;

function withDom(messages: AgentMsg[], dom: string | undefined): AgentMsg[] {
  return dom ? [...messages, { role: 'user', content: `Current page DOM:\n${dom}` }] : messages;
}

/** Split `data:image/jpeg;base64,AAAA` into its parts. Anything else (a remote URL, junk) → null,
 *  so an image we can't vouch for is dropped rather than forwarded. */
export function parseDataUrl(url: string): { mediaType: string; data: string } | null {
  const m = /^data:(image\/[a-z+]+);base64,([A-Za-z0-9+/=]+)$/.exec(url.trim());
  return m ? { mediaType: m[1], data: m[2] } : null;
}

/** Index of the last user-role message — where an attachment belongs. -1 if there is none. */
function lastUserIndex(messages: { role: string }[]): number {
  for (let i = messages.length - 1; i >= 0; i--) if (messages[i].role === 'user') return i;
  return -1;
}

/** Attach `image` to the last user message using `block`'s provider-native shape. Content becomes
 *  an array only for that one message; every other message stays a plain string. */
function withImage<T extends { role: string; content: any }>(
  messages: T[],
  image: string | undefined,
  block: (d: { mediaType: string; data: string }, url: string) => unknown,
): T[] {
  if (!image) return messages;
  const parsed = parseDataUrl(image);
  const at = lastUserIndex(messages);
  if (!parsed || at < 0) return messages;
  const target = messages[at];
  return messages.map((m, i) =>
    i === at ? { ...m, content: [{ type: 'text', text: target.content }, block(parsed, image)] } : m,
  );
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
    messages: withImage(withDom(convo, body.dom), body.image, (d) => ({
      type: 'image',
      source: { type: 'base64', media_type: d.mediaType, data: d.data },
    })),
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
  return {
    model,
    max_tokens: MAX_TOKENS,
    messages: withImage(withDom(convo, body.dom), body.image, (_d, url) => ({
      type: 'image_url',
      image_url: { url },
    })),
  };
}

export function parseChatResponse(json: any): { content: string } {
  // OpenAI: choices[0].message.content ; Ollama /api/chat: message.content
  const content = json?.choices?.[0]?.message?.content ?? json?.message?.content ?? '';
  return { content };
}

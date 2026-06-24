import { Redactor, assertNoLeak } from './redact';
import type { AgentApiRequest, AgentApiResponse } from './agent-types';

// Wiring for the live agent model-call path. Redacts the PII-bearing parts of a
// request (messages + dom + snapshot) before they leave the machine, and refuses
// to send if anything would still leak. Responses come back referring to tokens
// and are rehydrated locally. This is the production analog of model-gate.callModel.

export function redactRequest(request: AgentApiRequest, redactor: Redactor): AgentApiRequest {
  const out: AgentApiRequest = {
    ...request,
    messages: request.messages.map((m) => ({ ...m, content: redactor.redact(m.content).text })),
    dom: request.dom ? redactor.redact(request.dom).text : request.dom,
    snapshot: request.snapshot
      ? (JSON.parse(redactor.redact(JSON.stringify(request.snapshot)).text) as typeof request.snapshot)
      : request.snapshot,
  };
  // defense in depth: never send anything that still contains an identifier
  assertNoLeak(JSON.stringify({ messages: out.messages, dom: out.dom, snapshot: out.snapshot }), redactor);
  return out;
}

export function rehydrateResponse(response: AgentApiResponse, redactor: Redactor): AgentApiResponse {
  if ('text' in response) {
    return { text: redactor.rehydrate(response.text) };
  }
  const params: Record<string, unknown> = {};
  for (const [k, v] of Object.entries(response.params)) {
    params[k] = typeof v === 'string' ? redactor.rehydrate(v) : v;
  }
  return {
    ...response,
    params,
    reasoning: response.reasoning ? redactor.rehydrate(response.reasoning) : response.reasoning,
  };
}

import { Redactor, assertOutbound, type RedactedPayload } from './redact';

// The single choke point for talking to a model. It accepts only a
// RedactedPayload (enforced by the type system) and re-checks at runtime
// (assertOutbound) before anything leaves the machine. The transport is the
// vendor adapter — out of scope to wire a real one this milestone, so a mock /
// local transport plugs in here. Replies come back referring to tokens and are
// rehydrated locally.

export type ModelTransport = (redactedText: string) => Promise<string>;

export async function callModel(
  payload: RedactedPayload,
  redactor: Redactor,
  transport: ModelTransport,
): Promise<string> {
  assertOutbound(payload, redactor);
  const reply = await transport(payload.text);
  return redactor.rehydrate(reply);
}

import type { TreeRedaction } from './redact-tree';

/**
 * Gate for the slot-level (deny-by-default) redaction path. Refuses the call if any
 * redacted value (length >= 3) still appears in the outbound text, then rehydrates the
 * reply locally. Short values are excluded — they're tokenized in their own slot but
 * legitimately recur in chrome text (a grade "A"), so a literal scan would false-positive.
 */
export async function callModelTree(red: TreeRedaction, transport: ModelTransport): Promise<string> {
  for (const value of Object.values(red.map)) {
    if (value.trim().length >= 3 && red.redactedText.includes(value)) {
      throw new Error('Refusing model call: a redacted data value leaked into the outbound payload.');
    }
  }
  const reply = await transport(red.redactedText);
  return red.rehydrate(reply);
}

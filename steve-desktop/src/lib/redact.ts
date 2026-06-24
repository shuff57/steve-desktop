import type { SnapshotResult } from './dom-snapshot-types';

// Deterministic local PII redactor. This is the product's trust boundary: every
// payload bound for a cloud model must pass through here first. No model, no
// heuristics on the redaction path — a plain dictionary swap so the behaviour is
// auditable and provable. See docs/plans/2026-06-23-boring-clicks-robot-design.md.

const BRAND = Symbol('redacted');

export interface RedactedPayload {
  readonly [BRAND]: true;
  /** Text that is safe to send: every known identifier has been tokenized. */
  readonly text: string;
  /** Number of distinct identifiers tokenized in this payload. */
  readonly tokenCount: number;
}

export function isRedacted(value: unknown): value is RedactedPayload {
  return typeof value === 'object' && value !== null && (value as Record<symbol, unknown>)[BRAND] === true;
}

function escapeRegExp(value: string): string {
  return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

export class Redactor {
  // token -> original value (the only place real PII lives during a task)
  private readonly tokenToValue = new Map<string, string>();
  private readonly valueToToken = new Map<string, string>();
  // secrets sorted longest-first so "Jane Doe" wins over "Doe"
  private readonly secrets: string[];

  constructor(secrets: string[]) {
    const unique = Array.from(new Set(secrets.map((s) => s.trim()).filter(Boolean)));
    this.secrets = unique.sort((a, b) => b.length - a.length);
    let n = 0;
    for (const secret of this.secrets) {
      n += 1;
      const token = `⟦S${n}⟧`;
      this.tokenToValue.set(token, secret);
      this.valueToToken.set(secret.toLowerCase(), token);
    }
  }

  /** Replace every occurrence of each known identifier with its stable token. */
  redact(text: string): RedactedPayload {
    let out = text;
    let used = 0;
    for (const secret of this.secrets) {
      const token = this.valueToToken.get(secret.toLowerCase());
      if (!token) continue;
      // ponytail: case-insensitive substring swap; add \b word boundaries for
      // bare numeric IDs if over-redaction of "4471" inside "44710" ever bites.
      const re = new RegExp(escapeRegExp(secret), 'gi');
      if (re.test(out)) {
        out = out.replace(re, token);
        used += 1;
      }
    }
    return Object.freeze({ [BRAND]: true, text: out, tokenCount: used }) as RedactedPayload;
  }

  /** Dictionary lookup — turn tokens back into real values locally. */
  rehydrate(text: string): string {
    let out = text;
    for (const [token, value] of this.tokenToValue) {
      out = out.split(token).join(value);
    }
    return out;
  }

  /** True if the text still contains any known identifier in the clear. */
  leaks(text: string): boolean {
    const lower = text.toLowerCase();
    return this.secrets.some((s) => lower.includes(s.toLowerCase()));
  }

  get map(): Record<string, string> {
    return Object.fromEntries(this.tokenToValue);
  }
}

/**
 * The model-call gate. Refuses anything that isn't a RedactedPayload produced by
 * the redactor, and re-scans (defense in depth) so a tampered payload can't leak.
 */
export function assertOutbound(payload: RedactedPayload, redactor: Redactor): RedactedPayload {
  if (!isRedacted(payload)) {
    throw new Error('Refusing model call: payload was not produced by the redactor (un-redacted).');
  }
  if (redactor.leaks(payload.text)) {
    throw new Error('Refusing model call: redacted payload still contains a known identifier.');
  }
  return payload;
}

/**
 * Throws if `text` still contains any known identifier. Use on a serialized wire
 * body just before it leaves the machine (the live agent path has no single
 * RedactedPayload to hand to assertOutbound).
 */
export function assertNoLeak(text: string, redactor: Redactor): void {
  if (redactor.leaks(text)) {
    throw new Error('Refusing model call: outbound payload still contains a known identifier.');
  }
}

/**
 * Structured field-swap source: pull values from snapshot nodes whose field is
 * flagged as an identifier by discovery. Matches a node when its `name`,
 * `data-field`, or `id` attribute is in `identifierFields`; takes the node's
 * `value` attr (inputs) or its text (cells).
 */
export function identifierValuesFromSnapshot(
  snapshot: SnapshotResult,
  identifierFields: string[],
): string[] {
  const wanted = new Set(identifierFields.map((f) => f.toLowerCase()));
  const values: string[] = [];
  for (const node of snapshot.nodes) {
    const key = (node.attrs['name'] ?? node.attrs['data-field'] ?? node.attrs['id'] ?? '').toLowerCase();
    if (!key || !wanted.has(key)) continue;
    const value = (node.attrs['value'] ?? node.text ?? '').trim();
    if (value) values.push(value);
  }
  return values;
}

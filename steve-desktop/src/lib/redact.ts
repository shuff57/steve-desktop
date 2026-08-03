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

// ── Pattern-detected identifiers ───────────────────────────────────────────
// The dictionary only covers identifiers we enumerated up front. A roster we never loaded, a
// parent's email in a comment, a phone number in a note — those would otherwise reach the model
// in the clear. These shapes are matched structurally, tokenized reversibly (⟦P n⟧, distinct from
// dictionary ⟦S n⟧ so an audit can tell them apart), and refused by leaks() if they ever appear raw.
//
// Deliberately conservative: every pattern here needs an unambiguous anchor (@, or separator-
// delimited digit groups). Bare digit runs are NOT matched by default — see numericIds.
const PII_PATTERNS: readonly RegExp[] = [
  /[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/g, // email
  /\b\d{3}-\d{2}-\d{4}\b/g, // US SSN
  /(?:\+?1[\s.-])?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}(?!\d)/g, // phone, separators required
];

// Bare 6–10 digit runs (a student ID in a table cell). Off by default: page structure is full of
// non-PII numbers — `cid=316341` in a MyOpenMath URL, element coordinates — and tokenizing those
// breaks navigation and selectors. The negative lookarounds skip query-param and path positions
// so that, even when enabled, a URL id is left alone.
export const NUMERIC_ID_PATTERN = /(?<![=/\w-])\d{6,10}(?![\w-])/g;

export interface RedactorOptions {
  /** Also tokenize bare 6–10 digit runs as identifiers. Enable only for text you know is page
   *  DATA (a cell value), never for URLs or selectors. */
  numericIds?: boolean;
}

export class Redactor {
  // token -> original value (the only place real PII lives during a task)
  private readonly tokenToValue = new Map<string, string>();
  private readonly valueToToken = new Map<string, string>();
  // secrets sorted longest-first so "Jane Doe" wins over "Doe"
  private readonly secrets: string[];

  private readonly numericIds: boolean;
  /** Counter for pattern-detected tokens (⟦P n⟧), separate from the dictionary's ⟦S n⟧. */
  private patternCount = 0;

  constructor(secrets: string[], options: RedactorOptions = {}) {
    const unique = Array.from(new Set(secrets.map((s) => s.trim()).filter(Boolean)));
    this.secrets = unique.sort((a, b) => b.length - a.length);
    this.numericIds = options.numericIds ?? false;
    let n = 0;
    for (const secret of this.secrets) {
      n += 1;
      const token = `⟦S${n}⟧`;
      this.tokenToValue.set(token, secret);
      this.valueToToken.set(secret.toLowerCase(), token);
    }
  }

  /** Active PII shapes for this instance. */
  private patterns(): RegExp[] {
    return this.numericIds ? [...PII_PATTERNS, NUMERIC_ID_PATTERN] : [...PII_PATTERNS];
  }

  /** Stable token for a pattern-detected value — the same email always maps to the same token,
   *  so the model sees consistent identity and rehydrate() can put it back. */
  private tokenForPattern(value: string): string {
    const key = value.toLowerCase();
    const existing = this.valueToToken.get(key);
    if (existing) return existing;
    this.patternCount += 1;
    const token = `⟦P${this.patternCount}⟧`;
    this.tokenToValue.set(token, value);
    this.valueToToken.set(key, token);
    return token;
  }

  /** Replace every occurrence of each known identifier with its stable token, then tokenize any
   *  identifier-shaped text the dictionary didn't know about. */
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
    // Pattern pass runs on the dictionary's output, so an enumerated identifier keeps its ⟦S n⟧
    // token and is never double-tokenized.
    for (const pattern of this.patterns()) {
      out = out.replace(new RegExp(pattern.source, pattern.flags), (match) => {
        used += 1;
        return this.tokenForPattern(match);
      });
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

  /** True if the text still contains an identifier in the clear — either an enumerated one or
   *  anything matching a PII shape. The pattern half is what stops an identifier we never
   *  enumerated from reaching a model: assertOutbound/assertNoLeak refuse on it. */
  leaks(text: string): boolean {
    const lower = text.toLowerCase();
    if (this.secrets.some((s) => lower.includes(s.toLowerCase()))) return true;
    return this.patterns().some((p) => new RegExp(p.source, p.flags).test(text));
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

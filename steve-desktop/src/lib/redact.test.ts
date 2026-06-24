import { describe, it, expect } from 'vitest';
import {
  Redactor,
  isRedacted,
  assertOutbound,
  identifierValuesFromSnapshot,
} from './redact';
import type { SnapshotResult } from './dom-snapshot-types';

describe('Redactor', () => {
  it('replaces every occurrence of each secret so none appear in the outbound text', () => {
    const r = new Redactor(['Jane Doe', '4471']);
    const payload = r.redact('Student Jane Doe (ID 4471) — Jane Doe missing assignment 3');

    expect(payload.text).not.toContain('Jane Doe');
    expect(payload.text).not.toContain('4471');
  });

  it('round-trips: rehydrate restores the original text exactly', () => {
    const r = new Redactor(['Jane Doe', '4471']);
    const original = 'Student Jane Doe (ID 4471) — Jane Doe missing assignment 3';
    const payload = r.redact(original);

    expect(r.rehydrate(payload.text)).toBe(original);
  });

  it('matches case-insensitively and rehydrates to the canonical roster value', () => {
    const r = new Redactor(['Jane Doe']);
    const payload = r.redact('jane doe and JANE DOE both here');

    expect(payload.text.toLowerCase()).not.toContain('jane doe');
    // every casing variant maps to one token -> the real identity is restored
    expect(r.rehydrate(payload.text)).toBe('Jane Doe and Jane Doe both here');
  });

  it('redacts a longer secret as a whole even when a shorter secret is a substring', () => {
    const r = new Redactor(['Doe', 'Jane Doe']);
    const payload = r.redact('Jane Doe');

    // "Jane Doe" must map to a single token, not "Jane <tok>"
    expect(payload.text).not.toContain('Jane');
    expect(r.rehydrate(payload.text)).toBe('Jane Doe');
  });

  it('assigns one stable token per distinct secret across calls', () => {
    const r = new Redactor(['Jane Doe', 'John Roe']);
    const a = r.redact('Jane Doe').text;
    const b = r.redact('Jane Doe again').text;

    const tokenA = a.trim();
    expect(b).toContain(tokenA);
    // distinct secrets get distinct tokens
    const jane = r.redact('Jane Doe').text.trim();
    const john = r.redact('John Roe').text.trim();
    expect(jane).not.toBe(john);
  });

  it('produces a branded payload recognised by isRedacted', () => {
    const r = new Redactor(['Jane Doe']);
    const payload = r.redact('Jane Doe');

    expect(isRedacted(payload)).toBe(true);
    expect(isRedacted({ text: 'Jane Doe' })).toBe(false);
    expect(isRedacted('Jane Doe')).toBe(false);
  });
});

describe('assertOutbound — the model-call gate', () => {
  it('throws on a raw string (un-redacted payload)', () => {
    const r = new Redactor(['Jane Doe']);
    expect(() => assertOutbound('Jane Doe is here' as unknown as never, r)).toThrow();
  });

  it('throws on an object that is not a RedactedPayload even if text looks clean', () => {
    const r = new Redactor(['Jane Doe']);
    expect(() => assertOutbound({ text: 'nothing sensitive' } as unknown as never, r)).toThrow();
  });

  it('passes a properly redacted payload through', () => {
    const r = new Redactor(['Jane Doe', '4471']);
    const payload = r.redact('Jane Doe 4471');
    expect(() => assertOutbound(payload, r)).not.toThrow();
  });

  it('throws if a redacted payload still leaks a secret (defense in depth)', () => {
    const r = new Redactor(['Jane Doe']);
    const payload = r.redact('clean text');
    // tamper: inject a secret back into the supposedly-safe payload
    const tampered = { ...payload, text: 'Jane Doe sneaks back in' } as typeof payload;
    expect(() => assertOutbound(tampered, r)).toThrow();
  });
});

describe('identifierValuesFromSnapshot — structured field-swap source', () => {
  const snapshot: SnapshotResult = {
    nodes: [
      { tag: 'td', depth: 1, priority: 'high', text: 'Jane Doe', attrs: { 'data-field': 'studentName' } },
      { tag: 'input', depth: 1, priority: 'critical', text: '', attrs: { name: 'studentId', value: '4471' } },
      { tag: 'td', depth: 1, priority: 'high', text: 'missing assignment 3', attrs: { 'data-field': 'note' } },
    ],
    meta: {
      totalVisited: 3,
      nodesIncluded: 3,
      nodesDropped: 0,
      wasTruncated: false,
      charCount: 0,
      capturedAt: '2026-06-23T00:00:00.000Z',
    },
  };

  it('pulls identifier values from fields flagged as identifiers', () => {
    const values = identifierValuesFromSnapshot(snapshot, ['studentName', 'studentId']);
    expect(values).toContain('Jane Doe');
    expect(values).toContain('4471');
    expect(values).not.toContain('missing assignment 3');
  });

  it('seeds a Redactor that strips those values end-to-end', () => {
    const values = identifierValuesFromSnapshot(snapshot, ['studentName', 'studentId']);
    const r = new Redactor(values);
    const payload = r.redact(JSON.stringify(snapshot));

    expect(payload.text).not.toContain('Jane Doe');
    expect(payload.text).not.toContain('4471');
    expect(r.rehydrate(payload.text)).toContain('Jane Doe');
  });
});

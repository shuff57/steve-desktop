import { describe, it, expect } from 'vitest';
import { auditQTypes, declaredQType } from './transfer-via-agent';

const src = (qtype: string) =>
  `// === NAME - DESCRIPTION: whatever ===\n// === SET QUESTION TYPE TO: ${qtype} ===\n// === COMMON CONTROL ===\n`;

describe('declaredQType', () => {
  it('reads the marker', () => {
    expect(declaredQType(src('multans'))).toBe('multans');
  });

  it('returns null when the marker is absent rather than guessing a default', () => {
    // Guessing 'number' here would reproduce the exact bug this audit exists to catch.
    expect(declaredQType('// no marker anywhere')).toBeNull();
  });
});

describe('auditQTypes', () => {
  it('passes when every stored type matches its source', () => {
    const res = auditQTypes(
      [
        { slot: 1, qsetid: 11, source: src('choices') },
        { slot: 2, qsetid: 12, source: src('matching') },
      ],
      { 11: 'choices', 12: 'matching' },
    );
    expect(res.ok).toBe(true);
    expect(res.mismatched).toEqual([]);
  });

  it('catches the silent qtype drift that renders clean', () => {
    // The real case: 1.2 slot 14 declared `choices`, MOM stored `number`. It rendered
    // with no Eeek!, a widget present and nothing untypeset — a text box where three
    // radio buttons belonged. Only this comparison finds it.
    const res = auditQTypes(
      [
        { slot: 13, qsetid: 13, source: src('multipart') },
        { slot: 14, qsetid: 14, source: src('choices') },
      ],
      { 13: 'multipart', 14: 'number' },
    );
    expect(res.ok).toBe(false);
    expect(res.mismatched).toHaveLength(1);
    expect(res.mismatched[0]).toMatchObject({ slot: 14, declared: 'choices', stored: 'number' });
  });

  it('treats a question it could not read as a failure, never as a pass', () => {
    const res = auditQTypes([{ slot: 1, qsetid: 11, source: src('choices') }], {});
    expect(res.ok).toBe(false);
    expect(res.mismatched[0].stored).toBe('MISSING');
  });
});

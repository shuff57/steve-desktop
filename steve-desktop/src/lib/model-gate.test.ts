import { describe, it, expect, vi } from 'vitest';
import { callModel } from './model-gate';
import { Redactor } from './redact';

describe('callModel — the only door to the model', () => {
  it('passes only redacted text to the transport and rehydrates the reply', async () => {
    const redactor = new Redactor(['Jane Doe', '4471']);
    const seen: string[] = [];
    const transport = vi.fn(async (text: string) => {
      seen.push(text);
      // model replies referring to the token, as designed
      return 'Send reminder to ⟦S1⟧';
    });

    const payload = redactor.redact('Student Jane Doe ID 4471 missing work');
    const reply = await callModel(payload, redactor, transport);

    // nothing the transport saw contains a real identifier
    expect(seen.join('')).not.toContain('Jane Doe');
    expect(seen.join('')).not.toContain('4471');
    // the local reply is rehydrated for acting on the page
    expect(reply).toBe('Send reminder to Jane Doe');
  });

  it('refuses to call the transport with an un-redacted payload', async () => {
    const redactor = new Redactor(['Jane Doe']);
    const transport = vi.fn(async () => 'ok');
    await expect(callModel('Jane Doe raw' as unknown as never, redactor, transport)).rejects.toThrow();
    expect(transport).not.toHaveBeenCalled();
  });
});

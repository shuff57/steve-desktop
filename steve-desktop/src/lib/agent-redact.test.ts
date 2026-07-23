import { describe, it, expect } from 'vitest';
import { redactRequest, rehydrateResponse } from './agent-redact';
import { Redactor } from './redact';
import type { AgentApiRequest, AgentActionResponse } from './agent-types';

describe('redactRequest — gate on the live model-call path', () => {
  const redactor = new Redactor(['Jane Doe', '4471']);
  const request: AgentApiRequest = {
    messages: [
      { role: 'system', content: 'You are an agent.' },
      { role: 'user', content: 'Enter grades for Jane Doe (ID 4471).' },
    ],
    dom: '[1] input "Student Name" value="Jane Doe" (#studentName)',
  };

  it('strips identifiers from messages and dom before they leave', () => {
    const out = redactRequest(request, redactor);
    const serialized = JSON.stringify(out);
    expect(serialized).not.toContain('Jane Doe');
    expect(serialized).not.toContain('4471');
  });

  it('would refuse the un-redacted body (the gate fires on a known identifier)', async () => {
    const { assertNoLeak } = await import('./redact');
    // the original request carries identifiers in the clear -> must be refused
    expect(() => assertNoLeak(JSON.stringify(request), redactor)).toThrow();
    // the redacted body passes the same gate
    const out = redactRequest(request, redactor);
    expect(() => assertNoLeak(JSON.stringify(out), redactor)).not.toThrow();
  });

  it('rehydrates token references in the model response back to real values', () => {
    const response: AgentActionResponse = {
      action: 'fill',
      params: { selector: '#studentName', value: '⟦S1⟧' },
      reasoning: 'fill the name field for ⟦S1⟧',
    };
    const out = rehydrateResponse(response, redactor) as typeof response;
    expect(out.params.value).toBe('Jane Doe');
    expect(out.reasoning).toContain('Jane Doe');
  });

  it('rehydrates text responses too', () => {
    const out = rehydrateResponse({ text: 'Reminder sent to ⟦S1⟧' }, redactor);
    expect(out).toEqual({ text: 'Reminder sent to Jane Doe' });
  });
});

describe('un-enumerated PII on the live request path (stage 2)', () => {
  it("tokenizes a parent email the roster never listed, and rehydrates it locally", () => {
    // The dictionary knows only the student; the email was never enumerated.
    const redactor = new Redactor(['Jane Doe']);
    const request = {
      messages: [{ role: 'user', content: 'Email Jane Doe guardian at pat.reyes@family.example' }],
      dom: '<td>pat.reyes@family.example</td>',
    } as unknown as Parameters<typeof redactRequest>[0];

    const out = redactRequest(request, redactor);

    const wire = JSON.stringify(out);
    expect(wire).not.toContain('pat.reyes@family.example');
    expect(wire).not.toContain('Jane Doe');
    // and the app can still read it back on the way home
    expect(redactor.rehydrate(out.messages[0].content)).toContain('pat.reyes@family.example');
  });
});

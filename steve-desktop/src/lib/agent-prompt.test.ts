import { describe, expect, it } from 'vitest';
import { parseAgentResponse } from './agent-prompt';

describe('parseAgentResponse', () => {
  it('keeps well-formed params intact', () => {
    const parsed = parseAgentResponse('{"action":"click","params":{"ref":"e2"},"reasoning":"pick B"}');
    expect(parsed).toEqual({ action: 'click', params: { ref: 'e2' }, reasoning: 'pick B' });
  });

  it('folds top-level strays into params when the model flattens them', () => {
    // Seen live: a done reply with no params object crashed the loop on params.success.
    const parsed = parseAgentResponse('{"action":"done","success":true,"message":"answered"}');
    expect(parsed).toEqual({ action: 'done', reasoning: undefined, params: { success: true, message: 'answered' } });
  });

  it('falls back to text for non-action replies', () => {
    expect(parseAgentResponse('I cannot see the question.')).toEqual({ text: 'I cannot see the question.' });
  });
});

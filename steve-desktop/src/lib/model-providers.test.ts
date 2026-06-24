import { describe, it, expect } from 'vitest';
import {
  buildAnthropicBody,
  parseAnthropicResponse,
  buildChatBody,
  parseChatResponse,
} from './model-providers';

const messages = [
  { role: 'system', content: 'You are an agent.' },
  { role: 'user', content: 'Click the submit button.' },
  { role: 'assistant', content: '{"action":"click"}' },
  { role: 'result', content: '{"success":true}' },
];

describe('Anthropic mapping', () => {
  it('lifts system messages to the top-level system field', () => {
    const body = buildAnthropicBody({ messages }, 'claude-opus-4-8');
    expect(body.system).toBe('You are an agent.');
    expect(body.model).toBe('claude-opus-4-8');
    expect(body.max_tokens).toBeGreaterThan(0);
    // no system entries left in messages; role 'result' folded to 'user'
    expect(body.messages.every((m) => m.role === 'user' || m.role === 'assistant')).toBe(true);
    expect(body.messages.some((m) => m.role === 'system')).toBe(false);
  });

  it('appends the DOM as a trailing user message', () => {
    const body = buildAnthropicBody({ messages, dom: '[1] button "Submit"' }, 'claude-opus-4-8');
    const last = body.messages[body.messages.length - 1];
    expect(last.role).toBe('user');
    expect(last.content).toContain('[1] button "Submit"');
  });

  it('parses text out of the content blocks', () => {
    const parsed = parseAnthropicResponse({
      content: [
        { type: 'thinking', thinking: '...' },
        { type: 'text', text: '{"action":"click","params":{}}' },
      ],
    });
    expect(parsed.content).toBe('{"action":"click","params":{}}');
  });
});

describe('OpenAI/Ollama chat mapping', () => {
  it('keeps system role and folds result->user for chat-completions', () => {
    const body = buildChatBody({ messages }, 'gpt-4o');
    expect(body.model).toBe('gpt-4o');
    expect(body.messages[0]).toEqual({ role: 'system', content: 'You are an agent.' });
    expect(body.messages.some((m) => m.role === 'result')).toBe(false);
  });

  it('parses OpenAI choices', () => {
    const parsed = parseChatResponse({ choices: [{ message: { content: 'hi' } }] });
    expect(parsed.content).toBe('hi');
  });

  it('parses Ollama message shape', () => {
    const parsed = parseChatResponse({ message: { content: 'hello' } });
    expect(parsed.content).toBe('hello');
  });
});

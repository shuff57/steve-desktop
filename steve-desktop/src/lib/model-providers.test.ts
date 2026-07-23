import { describe, it, expect } from 'vitest';
import {
  buildAnthropicBody,
  parseDataUrl,
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

// ── Image attachment (the visual heal tier) ────────────────────────────────

describe('image attachment', () => {
  const IMG = 'data:image/jpeg;base64,AAAA';
  const body = { messages: [{ role: 'system', content: 'be terse' }, { role: 'user', content: 'which one?' }], image: IMG };

  it('parses only a base64 image data URL', () => {
    expect(parseDataUrl(IMG)).toEqual({ mediaType: 'image/jpeg', data: 'AAAA' });
    expect(parseDataUrl('https://example.com/x.png')).toBeNull();
    expect(parseDataUrl('data:text/html;base64,AAAA')).toBeNull();
  });

  it('attaches an Anthropic image block to the last user message', () => {
    const out = buildAnthropicBody(body, 'claude-opus-4-8');
    expect(out.messages).toHaveLength(1);
    expect(out.messages[0].content).toEqual([
      { type: 'text', text: 'which one?' },
      { type: 'image', source: { type: 'base64', media_type: 'image/jpeg', data: 'AAAA' } },
    ]);
    expect(out.system).toBe('be terse'); // system stays a plain string
  });

  it('attaches an image_url block for chat-completions providers', () => {
    const out = buildChatBody(body, 'gpt-x');
    expect(out.messages[1].content).toEqual([
      { type: 'text', text: 'which one?' },
      { type: 'image_url', image_url: { url: IMG } },
    ]);
  });

  it('leaves the body untouched with no image, or an unusable one', () => {
    expect(buildAnthropicBody({ messages: body.messages }, 'm').messages[0].content).toBe('which one?');
    expect(buildAnthropicBody({ ...body, image: 'http://evil/x.png' }, 'm').messages[0].content).toBe('which one?');
  });
});

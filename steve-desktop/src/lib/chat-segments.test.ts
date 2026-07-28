import { describe, expect, it } from 'vitest';
import { splitChatSegments } from './chat-segments';

describe('splitChatSegments', () => {
  it('returns a single text segment when there is no fence', () => {
    expect(splitChatSegments('just prose')).toEqual([{ kind: 'text', content: 'just prose' }]);
  });

  it('returns nothing for empty or blank content', () => {
    expect(splitChatSegments('')).toEqual([]);
    expect(splitChatSegments('\n\n')).toEqual([]);
  });

  it('splits prose around a fenced block and keeps the language tag', () => {
    const out = splitChatSegments('Here you go:\n\n```markdown\n---\nname: X\n---\n```\n\nSave it.');
    expect(out).toEqual([
      { kind: 'text', content: 'Here you go:' },
      { kind: 'code', content: '---\nname: X\n---', lang: 'markdown' },
      { kind: 'text', content: 'Save it.' },
    ]);
  });

  it('omits lang when the fence has no language tag', () => {
    const out = splitChatSegments('```\nplain\n```');
    expect(out).toEqual([{ kind: 'code', content: 'plain' }]);
  });

  it('keeps a longer inner fence as content instead of closing early', () => {
    const out = splitChatSegments('````markdown\n```\ninner\n```\n````');
    expect(out).toEqual([{ kind: 'code', content: '```\ninner\n```', lang: 'markdown' }]);
  });

  it('treats an unterminated fence as code so a streaming reply is not lost', () => {
    const out = splitChatSegments('Working:\n```markdown\nhalf a ski');
    expect(out).toEqual([
      { kind: 'text', content: 'Working:' },
      { kind: 'code', content: 'half a ski', lang: 'markdown' },
    ]);
  });

  it('never emits html — markup in a reply stays literal text', () => {
    const evil = '<img src=x onerror=alert(1)>';
    const out = splitChatSegments(`look: ${evil}`);
    expect(out).toEqual([{ kind: 'text', content: `look: ${evil}` }]);
  });

  it('handles several blocks in one message', () => {
    const out = splitChatSegments('a\n```js\n1\n```\nb\n```js\n2\n```\nc');
    expect(out.map((s) => s.kind)).toEqual(['text', 'code', 'text', 'code', 'text']);
    expect(out[3]).toEqual({ kind: 'code', content: '2', lang: 'js' });
  });
});

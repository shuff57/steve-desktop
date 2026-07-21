import { describe, expect, it } from 'vitest';
import { buildTurnPrompt, cliModelArg, engineForProvider, extractCliText, summarizeCliLine } from './agent-cli';
import type { AgentMessage } from './agent-types';

describe('engineForProvider', () => {
  it('routes anthropic to the claude CLI', () => {
    expect(engineForProvider('anthropic')).toBe('claude');
  });

  it('routes everything else to opencode', () => {
    expect(engineForProvider('opencode')).toBe('opencode');
    expect(engineForProvider(undefined)).toBe('opencode');
  });
});

describe('extractCliText stream-json', () => {
  const ndjson = [
    '{"type":"system","subtype":"init"}',
    '{"type":"assistant","message":{"content":[{"type":"text","text":"mapping"}]}}',
    '{"type":"result","subtype":"success","result":"# Site map\\nbody"}',
  ].join('\n');

  it('pulls the final result event out of NDJSON', () => {
    expect(extractCliText('claude', ndjson)).toBe('# Site map\nbody');
  });

  it('still handles the single json envelope', () => {
    expect(extractCliText('claude', '{"result":"hi","is_error":false}')).toBe('hi');
  });

  it('throws on a stream error result', () => {
    expect(() => extractCliText('claude', '{"type":"result","is_error":true,"subtype":"max_turns"}')).toThrow(/max_turns/);
  });
});

describe('summarizeCliLine', () => {
  it('summarizes bash tool-use as the command', () => {
    const line = '{"type":"assistant","message":{"content":[{"type":"tool_use","name":"Bash","input":{"command":"curl -s http://127.0.0.1:9223/json/list"}}]}}';
    expect(summarizeCliLine(line)).toBe('$ curl -s http://127.0.0.1:9223/json/list');
  });
  it('maps lifecycle events and ignores noise', () => {
    expect(summarizeCliLine('{"type":"system"}')).toBe('session started');
    expect(summarizeCliLine('{"type":"result","is_error":false}')).toBe('writing site map');
    expect(summarizeCliLine('not json')).toBeNull();
    expect(summarizeCliLine('{"type":"assistant","message":{"content":[]}}')).toBeNull();
  });
});

describe('cliModelArg', () => {
  it('prefixes bare ollama.com cloud ids for opencode', () => {
    expect(cliModelArg('opencode', 'kimi-k2.6:cloud')).toBe('ollama/kimi-k2.6:cloud');
  });

  it('leaves provider-qualified ids and claude ids alone', () => {
    expect(cliModelArg('opencode', 'anthropic/claude-sonnet-5')).toBe('anthropic/claude-sonnet-5');
    expect(cliModelArg('claude', 'claude-sonnet-5')).toBe('claude-sonnet-5');
  });

  it('returns null for empty ids so no --model flag is sent', () => {
    expect(cliModelArg('claude', undefined)).toBeNull();
    expect(cliModelArg('opencode', '  ')).toBeNull();
  });
});

describe('buildTurnPrompt', () => {
  const history: AgentMessage[] = [
    { role: 'system', content: 'SYSTEM PROMPT' },
    { role: 'user', content: 'Answer question 3' },
    { role: 'assistant', content: '{"action":"click","params":{"ref":"e4"}}' },
    { role: 'result', content: '{"success":true}' },
  ];

  it('sends the task on the first turn', () => {
    const prompt = buildTurnPrompt(history, '[e4] label "B"', true);

    expect(prompt).toContain('TASK: Answer question 3');
    expect(prompt).toContain('[e4] label "B"');
  });

  it('sends only the last result on later turns, not the whole history', () => {
    const prompt = buildTurnPrompt(history, '[e4] label "B"', false);

    expect(prompt).toContain('RESULT OF YOUR LAST ACTION: {"success":true}');
    // The CLI session already holds these; resending would duplicate and blow the cache.
    expect(prompt).not.toContain('TASK: Answer question 3');
    expect(prompt).not.toContain('SYSTEM PROMPT');
  });

  it('says so explicitly when the page captured nothing', () => {
    // Better than an empty section: a blind agent should know it is blind.
    expect(buildTurnPrompt(history, '', true)).toContain('(no elements captured)');
    expect(buildTurnPrompt(history, undefined, true)).toContain('(no elements captured)');
  });
});

describe('extractCliText', () => {
  it('unwraps the claude -p --output-format json envelope', () => {
    const stdout = JSON.stringify({
      type: 'result',
      subtype: 'success',
      is_error: false,
      result: '{"action":"click","params":{"ref":"e4"}}',
      total_cost_usd: 0.02,
    });

    expect(extractCliText('claude', stdout)).toBe('{"action":"click","params":{"ref":"e4"}}');
  });

  it('throws when claude reports an error rather than passing it off as an answer', () => {
    const stdout = JSON.stringify({ is_error: true, subtype: 'error_max_turns', result: 'hit limit' });

    expect(() => extractCliText('claude', stdout)).toThrow(/error_max_turns/);
  });

  it('passes opencode stdout through untouched', () => {
    expect(extractCliText('opencode', '  {"action":"done"}  ')).toBe('{"action":"done"}');
  });

  it('falls back to raw text when the envelope is not JSON', () => {
    expect(extractCliText('claude', 'plain text reply')).toBe('plain text reply');
  });

  it('throws on empty output instead of returning a silent empty string', () => {
    expect(() => extractCliText('claude', '   ')).toThrow(/no output/);
  });
});

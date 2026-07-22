import { describe, expect, it } from 'vitest';
import { buildTurnPrompt, cliModelArg, engineForProvider, extractCliText, summarizeCliLine, describeBrowserCommand } from './agent-cli';
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
  it('summarizes a browser-driving bash command as a plain-English action', () => {
    const line = '{"type":"assistant","message":{"content":[{"type":"tool_use","name":"Bash","input":{"command":"python /tmp/cdp.py ws://x \\"Page.navigate https://www.myopenmath.com/course/course.php?cid=316341\\""}}]}}';
    expect(summarizeCliLine(line)).toBe('navigating to www.myopenmath.com/course/course.php?cid=316341');
    const read = '{"type":"assistant","message":{"content":[{"type":"tool_use","name":"Bash","input":{"command":"python /tmp/cdp.py ws://x \\"document.body.innerText\\""}}]}}';
    expect(summarizeCliLine(read)).toBe('reading the page');
  });
  it('drops session/success noise and plan markdown, keeps errors', () => {
    expect(summarizeCliLine('{"type":"system"}')).toBeNull();
    expect(summarizeCliLine('{"type":"result","is_error":false}')).toBeNull();
    expect(summarizeCliLine('{"type":"result","is_error":true}')).toBe('agent reported an error');
    expect(summarizeCliLine('{"type":"assistant","message":{"content":[{"type":"text","text":"# Plan\\n1. Navigate"}]}}')).toBeNull();
    expect(summarizeCliLine('not json')).toBeNull();
    expect(summarizeCliLine('{"type":"assistant","message":{"content":[]}}')).toBeNull();
  });
  it('describeBrowserCommand maps common CDP calls', () => {
    expect(describeBrowserCommand('Page.captureScreenshot')).toBe('taking a screenshot');
    expect(describeBrowserCommand('el.dispatchMouseEvent click')).toBe('clicking');
    expect(describeBrowserCommand('something obscure')).toBe('running a command');
  });
  it('names multi-tab bridge calls (they ride Runtime.evaluate)', () => {
    expect(describeBrowserCommand('await __steveControl.newTab("https://x")')).toBe('opening a new tab');
    expect(describeBrowserCommand('Runtime.evaluate __steveControl.login(id)')).toBe('logging in');
    expect(describeBrowserCommand('__steveControl.activate(id)')).toBe('switching tabs');
  });
  it('summarizes opencode flat events (tool_use/bash, text, step noise)', () => {
    const tool = '{"type":"tool_use","part":{"type":"tool","tool":"bash","state":{"input":{"command":"python cdp.py ws://x \\"Page.navigate https://www.myopenmath.com/x\\""}}}}';
    expect(summarizeCliLine(tool)).toBe('navigating to www.myopenmath.com/x');
    expect(summarizeCliLine('{"type":"tool_use","part":{"type":"tool","tool":"read"}}')).toBe('using read');
    expect(summarizeCliLine('{"type":"text","part":{"type":"text","text":"working on it"}}')).toBe('working on it');
    expect(summarizeCliLine('{"type":"step_start","part":{}}')).toBeNull();
    expect(summarizeCliLine('{"type":"step_finish","part":{}}')).toBeNull();
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

  it('passes non-event opencode stdout through untouched', () => {
    // A bare JSON action (no opencode "text" events) is returned as-is for parseAgentResponse.
    expect(extractCliText('opencode', '  {"action":"done"}  ')).toBe('{"action":"done"}');
  });

  it('concatenates the text parts out of opencode --format json NDJSON', () => {
    const ndjson = [
      '{"type":"step_start","part":{"type":"step-start"}}',
      '{"type":"tool_use","part":{"type":"tool","tool":"bash","state":{"input":{"command":"echo hi"}}}}',
      '{"type":"text","part":{"type":"text","text":"{\\"action\\":"}}',
      '{"type":"text","part":{"type":"text","text":"\\"done\\"}"}}',
      '{"type":"step_finish","part":{"type":"step-finish","reason":"stop"}}',
    ].join('\n');
    expect(extractCliText('opencode', ndjson)).toBe('{"action":"done"}');
  });

  it('falls back to raw text when the envelope is not JSON', () => {
    expect(extractCliText('claude', 'plain text reply')).toBe('plain text reply');
  });

  it('throws on empty output instead of returning a silent empty string', () => {
    expect(() => extractCliText('claude', '   ')).toThrow(/no output/);
  });
});

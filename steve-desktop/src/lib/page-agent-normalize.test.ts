import { describe, expect, test } from 'vitest';
import { normalizeMacroOutput, retrieveJsonFromString } from './page-agent-normalize';
import { DEFAULT_TOOLS } from './page-agent-tools';

/** Wrap a reply the way an OpenAI-compatible endpoint would. */
const asToolCall = (name: string, args: unknown) => ({
  choices: [{ message: { tool_calls: [{ function: { name, arguments: typeof args === 'string' ? args : JSON.stringify(args) } }] } }],
});
const asContent = (content: string) => ({ choices: [{ message: { content } }] });

const norm = (r: unknown) => normalizeMacroOutput(r, DEFAULT_TOOLS);

describe('normalizeMacroOutput — the shapes models actually send', () => {
  test('the well-formed case passes through untouched', () => {
    const { output, repairs } = norm(asToolCall('AgentOutput', { action: { click_element_by_index: { index: 2 } } }));
    expect(output.action).toEqual({ click_element_by_index: { index: 2 } });
    expect(repairs).toEqual([]);
  });

  test('the action called as the tool instead of AgentOutput', () => {
    const { output, repairs } = norm(asToolCall('click_element_by_index', { index: 3 }));
    expect(output.action).toEqual({ click_element_by_index: { index: 3 } });
    expect(repairs.join()).toContain('action-as-tool-call');
  });

  test('JSON in content with no tool_calls — cost gemma4 its first MOM run', () => {
    const { output, repairs } = norm(asContent('{"memory":"x","action":{"done":{"text":"ok","success":true}}}'));
    expect(output.action.done).toEqual({ text: 'ok', success: true });
    expect(repairs).toContain('json-in-content');
  });

  test('content wrapped in the AgentOutput envelope', () => {
    const { output } = norm(asContent(JSON.stringify({ name: 'AgentOutput', arguments: { action: { wait: { seconds: 2 } } } })));
    expect(output.action).toEqual({ wait: { seconds: 2 } });
  });

  test('content wrapped in a function envelope', () => {
    const { output } = norm(asContent(JSON.stringify({ type: 'function', function: { arguments: { action: { wait: { seconds: 3 } } } } })));
    expect(output.action).toEqual({ wait: { seconds: 3 } });
  });

  test('content carrying only the action level', () => {
    const { output, repairs } = norm(asContent('{"navigate":{"url":"https://example.com"}}'));
    expect(output.action).toEqual({ navigate: { url: 'https://example.com' } });
    expect(repairs).toContain('action-level-only');
  });

  test('arguments stringified twice over', () => {
    const { output, repairs } = norm(asToolCall('AgentOutput', JSON.stringify({ action: JSON.stringify({ wait: { seconds: 1 } }) })));
    expect(output.action).toEqual({ wait: { seconds: 1 } });
    expect(repairs).toContain('double-stringified-action');
  });

  test('a bare scalar lands in the tool primary field, per tool', () => {
    expect(norm(asToolCall('AgentOutput', { action: { click_element_by_index: 4 } })).output.action)
      .toEqual({ click_element_by_index: { index: 4 } });
    // navigate takes a url, not an index — the old blanket {index,text} coercion got this wrong.
    expect(norm(asToolCall('AgentOutput', { action: { navigate: 'https://example.com' } })).output.action)
      .toEqual({ navigate: { url: 'https://example.com' } });
    expect(norm(asToolCall('AgentOutput', { action: { wait: 3 } })).output.action)
      .toEqual({ wait: { seconds: 3 } });
  });

  test('an unknown tool is left alone for executeTool to name the legal ones', () => {
    const { output } = norm(asToolCall('AgentOutput', { action: { made_up_tool: 7 } }));
    expect(output.action).toEqual({ made_up_tool: 7 });
  });

  test('no action at all becomes a wait rather than killing the run', () => {
    const { output, repairs } = norm(asContent('{"memory":"still thinking about it","next_goal":"decide"}'));
    expect(output.action).toEqual({ wait: { seconds: 1 } });
    expect(repairs.join()).toContain('no-action');
  });

  test('a reply with nothing usable still throws', () => {
    expect(() => norm(asContent('I am not sure what to do here.'))).toThrow(/no tool call/i);
    expect(() => norm({ choices: [{ message: {} }] })).toThrow(/no tool call/i);
    expect(() => norm({})).toThrow(/no choices/i);
  });
});

describe('retrieveJsonFromString', () => {
  test('finds JSON inside a fence or prose, and reports none when absent', () => {
    expect(retrieveJsonFromString('```json\n{"a":1}\n```')).toEqual({ a: 1 });
    expect(retrieveJsonFromString('Sure! {"a":2} hope that helps')).toEqual({ a: 2 });
    expect(retrieveJsonFromString('no json here')).toBeNull();
  });
});

import { describe, expect, test } from 'vitest';
import { assembleUserPrompt, PAGE_AGENT_SYSTEM_PROMPT } from './page-agent-prompt';
import type { HistoryEntry, BrowserState } from './page-agent-prompt';

describe('page-agent-prompt', () => {
  test('system prompt is non-empty and contains key sections', () => {
    expect(PAGE_AGENT_SYSTEM_PROMPT).toContain('<user_request>');
    expect(PAGE_AGENT_SYSTEM_PROMPT).toContain('<browser_state>');
    expect(PAGE_AGENT_SYSTEM_PROMPT).toContain('<task_completion_rules>');
    expect(PAGE_AGENT_SYSTEM_PROMPT).toContain('done');
  });

  test('assembleUserPrompt builds the expected sections', () => {
    const history: HistoryEntry[] = [
      {
        type: 'step',
        stepIndex: 0,
        reflection: {
          evaluation_previous_goal: '',
          memory: 'Starting',
          next_goal: 'Click submit',
        },
        action: { name: 'click_element_by_index', input: { index: 3 }, output: '✅ Clicked' },
      },
      { type: 'observation', content: 'Page loaded' },
    ];

    const browserState: BrowserState = {
      header: 'Current URL: https://example.com/form',
      content: '[0]<button>Submit</button>',
      footer: '',
    };

    const prompt = assembleUserPrompt({
      instructions: 'Only fill the form.',
      task: 'Submit the form',
      maxSteps: 10,
      history,
      browserState,
    });

    expect(prompt).toContain('<instructions>');
    expect(prompt).toContain('Only fill the form.');
    expect(prompt).toContain('<user_request>');
    expect(prompt).toContain('Submit the form');
    expect(prompt).toContain('<step_info>');
    expect(prompt).toContain('Step 2 of 10'); // history has 1 step, so next is 2
    expect(prompt).toContain('<agent_history>');
    expect(prompt).toContain('Evaluation of Previous Step');
    expect(prompt).toContain('<browser_state>');
    expect(prompt).toContain('[0]<button>Submit</button>');
    expect(prompt).toContain('<sys>Page loaded</sys>');
  });

  test('assembleUserPrompt without instructions omits the section', () => {
    const prompt = assembleUserPrompt({
      task: 'Do something',
      maxSteps: 5,
      history: [],
      browserState: { header: 'URL', content: '', footer: '' },
    });
    expect(prompt).not.toContain('<instructions>');
    expect(prompt).toContain('<user_request>');
  });
});
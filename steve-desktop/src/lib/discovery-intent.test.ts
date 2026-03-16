import { describe, expect, it } from 'vitest';

import {
  MAX_CHAT_TURNS,
  createChatDiscoveryState,
  intentToDiscoveryHints,
  parseChatIntent,
  parseExampleSelections,
  parseFormIntent,
  runChatDiscovery,
  runExampleDiscovery,
  type ChatMessage,
  type ExampleSelection,
  type FormModeInput,
} from './discovery-intent';

describe('parseFormIntent', () => {
  it('converts form input with video player to hints', () => {
    const hints = parseFormIntent({
      hasVideoPlayer: true,
      hasInteractiveForms: false,
      hasNavigation: false,
      hasQuizElements: false,
    });

    expect(hints.pageDescription).toContain('video');
  });

  it('includes required selectors based on flags', () => {
    const hints = parseFormIntent({
      hasVideoPlayer: true,
      hasQuizElements: true,
      hasInteractiveForms: false,
      hasNavigation: false,
    });

    expect(hints.requiredSelectors).toContain('videoPlayer');
    expect(hints.requiredSelectors).toContain('quizElement');
  });

  it('preserves notes and known selector context', () => {
    const hints = parseFormIntent({
      pageDescription: 'Training portal',
      hasVideoPlayer: false,
      hasInteractiveForms: true,
      hasNavigation: true,
      hasQuizElements: false,
      notes: 'The next button appears after each section',
      knownSelectors: JSON.stringify({ navigationControl: 'button.next' }),
    });

    expect(hints.pageDescription).toContain('Training portal');
    expect(hints.extraContext).toContain('next button');
    expect(hints.knownSelectors).toEqual({ navigationControl: 'button.next' });
  });
});

describe('parseChatIntent', () => {
  it('extracts navigation mode from chat', () => {
    const hints = parseChatIntent([
      { role: 'user', content: 'Videos play one after another sequentially', timestamp: '' },
    ]);

    expect(hints.navigationMode).toBe('sequential');
  });

  it('derives selector targets from generic page descriptions', () => {
    const hints = parseChatIntent([
      {
        role: 'user',
        content: 'There is a video player, a quiz after each lesson, and a next button to continue',
        timestamp: '',
      },
    ]);

    expect(hints.requiredSelectors).toContain('videoPlayer');
    expect(hints.requiredSelectors).toContain('quizElement');
    expect(hints.requiredSelectors).toContain('navigationControl');
  });
});

describe('parseExampleSelections', () => {
  it('generalizes selectors from 2 examples of same type', () => {
    const selections = [
      {
        elementType: 'other' as const,
        capturedSelector: 'button.play-btn',
        text: 'Play',
        tag: 'button',
        attrs: { class: 'play-btn' },
      },
      {
        elementType: 'other' as const,
        capturedSelector: 'button.play-btn',
        text: 'Play',
        tag: 'button',
        attrs: { class: 'play-btn' },
      },
    ];

    const result = parseExampleSelections(selections);
    expect(result).toHaveLength(1);
    expect(result[0]?.selector).toBe('button.play-btn');
  });
});

describe('chat and example orchestration', () => {
  it('creates an empty chat discovery state', () => {
    expect(createChatDiscoveryState()).toEqual({
      messages: [],
      turnCount: 0,
      isComplete: false,
      hints: {},
    });
  });

  it('completes chat when max turns are reached without calling AI', async () => {
    const state = {
      messages: [],
      turnCount: MAX_CHAT_TURNS - 1,
      isComplete: false,
      hints: {},
    };

    let called = false;
    const result = await runChatDiscovery(state, 'This page is sequential', async () => {
      called = true;
      return 'unreachable';
    });

    expect(called).toBe(false);
    expect(result.isComplete).toBe(true);
    expect(result.hints.navigationMode).toBe('sequential');
  });

  it('returns generalized selectors from example discovery', () => {
    const selections: ExampleSelection[] = [
      {
        elementType: 'videoPlayer',
        capturedSelector: 'video.player',
        text: '',
        tag: 'video',
        attrs: { class: 'player' },
      },
    ];

    const result = runExampleDiscovery(selections);

    expect(result.generalizedSelectors).toHaveLength(1);
    expect(result.hints.generalizedSelectors).toHaveLength(1);
  });

  it('routes all three intent modes', () => {
    const formInput: FormModeInput = {
      hasVideoPlayer: true,
      hasInteractiveForms: false,
      hasNavigation: false,
      hasQuizElements: false,
    };
    const chatMessages: ChatMessage[] = [
      { role: 'user', content: 'All videos are visible at once', timestamp: '' },
    ];
    const exampleSelections: ExampleSelection[] = [
      {
        elementType: 'navigationControl',
        capturedSelector: 'button.next',
        text: 'Next',
        tag: 'button',
        attrs: { class: 'next' },
      },
    ];

    expect(intentToDiscoveryHints('form', formInput).requiredSelectors).toContain('videoPlayer');
    expect(intentToDiscoveryHints('chat', chatMessages).navigationMode).toBe('batch');
    expect(intentToDiscoveryHints('example', exampleSelections).generalizedSelectors).toHaveLength(1);
  });
});

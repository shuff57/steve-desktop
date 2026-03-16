import { describe, expect, it } from 'vitest';

import { buildDiscoveryPrompt, parseDiscoveryResponse, DISCOVERY_SYSTEM_PROMPT } from './discover';

describe('DISCOVERY_SYSTEM_PROMPT', () => {
  it('does not contain grading terminology', () => {
    expect(DISCOVERY_SYSTEM_PROMPT).not.toMatch(/student|rubric|score.?input|feedback.?box|grading/i);
  });

  it('mentions interactive elements', () => {
    expect(DISCOVERY_SYSTEM_PROMPT).toMatch(/button|link|input|form/i);
  });
});

describe('buildDiscoveryPrompt', () => {
  it('includes URL and snapshot', () => {
    const prompt = buildDiscoveryPrompt('https://example.com', '<div>content</div>');
    expect(prompt).toContain('https://example.com');
    expect(prompt).toContain('content');
  });

  it('appends hints when provided', () => {
    const prompt = buildDiscoveryPrompt('https://example.com', 'snapshot', { pageDescription: 'video player page' });
    expect(prompt).toContain('video player page');
  });
});

describe('parseDiscoveryResponse', () => {
  it('parses valid JSON response', () => {
    const json = JSON.stringify({ buttons: [{ text: 'Play', selector: '#play' }] });
    const result = parseDiscoveryResponse(json);
    expect(result.confidence).not.toBe('low');
    expect(result.profile.interactive).toBeDefined();
  });

  it('returns low confidence on invalid JSON', () => {
    const result = parseDiscoveryResponse('not json');
    expect(result.confidence).toBe('low');
    expect(result.notes).toContain('Failed');
  });

  it('handles markdown-wrapped JSON', () => {
    const json = '```json\n{"buttons": []}\n```';
    const result = parseDiscoveryResponse(json);
    expect(result.confidence).not.toBe('low');
  });
});

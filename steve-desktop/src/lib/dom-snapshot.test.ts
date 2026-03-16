import { describe, expect, it } from 'vitest';

import {
  buildSmartWalkScript,
  formatSnapshotForPrompt,
  parseSmartWalkResult,
} from './dom-snapshot';
import {
  classifyNodePriority,
  isSnapshotNode,
  isValidSnapshotResult,
  type SnapshotResult,
} from './dom-snapshot-types';

describe('buildSmartWalkScript', () => {
  it('returns a self-contained IIFE string', () => {
    const script = buildSmartWalkScript();

    expect(typeof script).toBe('string');
    expect(script.startsWith('(function(){')).toBe(true);
    expect(script).toContain('nodes');
    expect(script).toContain('meta');
    expect(script).not.toMatch(/\bimport\b|\brequire\b/);
  });

  it('embeds caller options and generic attribute detection', () => {
    const script = buildSmartWalkScript({
      maxNodes: 42,
      maxChars: 2048,
      rootSelector: '#player',
      captureBoundingBoxes: true,
    });

    expect(script).toContain('"maxNodes":42');
    expect(script).toContain('"maxChars":2048');
    expect(script).toContain('"rootSelector":"#player"');
    expect(script).toContain('data-');
    expect(script).toContain('aria-');
    expect(script).not.toContain('data-student');
    expect(script).not.toContain('data-score');
  });
});

describe('parseSmartWalkResult', () => {
  it('parses valid JSON', () => {
    const json = JSON.stringify({
      nodes: [
        { tag: 'button', depth: 0, priority: 'high', attrs: {}, text: 'Click me' },
      ],
      meta: {
        totalVisited: 1,
        nodesIncluded: 1,
        nodesDropped: 0,
        wasTruncated: false,
        charCount: 50,
        capturedAt: '2026-03-15T00:00:00Z',
      },
    });

    const result = parseSmartWalkResult(json);

    expect(result.nodes).toHaveLength(1);
    expect(result.nodes[0]?.tag).toBe('button');
    expect(result.meta.totalVisited).toBe(1);
  });

  it('throws a helpful error for invalid JSON', () => {
    expect(() => parseSmartWalkResult('{')).toThrow(/Invalid snapshot JSON/i);
  });

  it('throws when the parsed payload is not a valid snapshot result', () => {
    const invalidJson = JSON.stringify({
      nodes: [{ tag: 'button' }],
      meta: {},
    });

    expect(() => parseSmartWalkResult(invalidJson)).toThrow(/Invalid snapshot result/i);
  });
});

describe('formatSnapshotForPrompt', () => {
  it('formats snapshot metadata and nodes into prompt-friendly text', () => {
    const result: SnapshotResult = {
      nodes: [
        {
          tag: 'button',
          depth: 0,
          priority: 'critical',
          text: 'Play video',
          attrs: { 'data-action': 'watch', role: 'button' },
          selector: 'body > button#play',
          bbox: { x: 10, y: 20, width: 120, height: 40, visible: true },
        },
        {
          tag: 'p',
          depth: 1,
          priority: 'medium',
          text: 'Safety training starts here.',
          attrs: {},
        },
      ],
      meta: {
        totalVisited: 3,
        nodesIncluded: 2,
        nodesDropped: 1,
        wasTruncated: false,
        charCount: 234,
        capturedAt: '2026-03-15T00:00:00Z',
      },
    };

    const formatted = formatSnapshotForPrompt(result);

    expect(formatted).toContain('totalVisited=3');
    expect(formatted).toContain('nodesIncluded=2');
    expect(formatted).toContain('[critical] button');
    expect(formatted).toContain('Play video');
    expect(formatted).toContain('selector=body > button#play');
    expect(formatted).toContain('bbox=10,20 120x40 visible');
  });
});

describe('dom snapshot types', () => {
  it('treats interactive nodes with generic data/aria/role signals as critical', () => {
    expect(classifyNodePriority('button', { 'data-action': 'watch' }, false, 0)).toBe('critical');
    expect(classifyNodePriority('button', { 'aria-label': 'Play video' }, false, 0)).toBe('critical');
    expect(classifyNodePriority('div', { role: 'button' }, false, 0)).toBe('critical');
  });

  it('keeps structural elements high priority and validates snapshot shapes', () => {
    expect(classifyNodePriority('form', {}, false, 2)).toBe('high');
    expect(classifyNodePriority('table', {}, false, 3)).toBe('high');

    const node = { tag: 'div', depth: 0, priority: 'medium', attrs: {} };
    const result = {
      nodes: [node],
      meta: {
        totalVisited: 1,
        nodesIncluded: 1,
        nodesDropped: 0,
        wasTruncated: false,
        charCount: 10,
        capturedAt: '2026-03-15T00:00:00Z',
      },
    };

    expect(isSnapshotNode(node)).toBe(true);
    expect(isValidSnapshotResult(result)).toBe(true);
  });
});

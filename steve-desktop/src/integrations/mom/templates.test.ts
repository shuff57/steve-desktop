import { describe, it, expect } from 'vitest';
import { findTemplate, getTemplates, type MomTemplate } from './templates';

describe('getTemplates', () => {
  it('returns at least one starter template', () => {
    const t = getTemplates();
    expect(t.length).toBeGreaterThan(0);
  });

  it('every template has a family, source path, label, and anstype', () => {
    for (const t of getTemplates()) {
      expect(typeof t.family).toBe('string');
      expect(t.family.length).toBeGreaterThan(0);
      expect(typeof t.sourcePath).toBe('string');
      expect(t.sourcePath.length).toBeGreaterThan(0);
      expect(typeof t.label).toBe('string');
      expect(t.label.length).toBeGreaterThan(0);
      expect(typeof t.anstype).toBe('string');
      expect(t.anstype.length).toBeGreaterThan(0);
    }
  });
});

describe('findTemplate', () => {
  it('returns the matching template by family, or null', () => {
    const t = getTemplates();
    if (t.length > 0) {
      const first: MomTemplate = t[0]!;
      expect(findTemplate(first.family)?.family).toBe(first.family);
    }
    expect(findTemplate('nope-not-a-family')).toBeNull();
  });
});

import { describe, expect, it } from 'vitest';
import { parseSkillFrontmatter } from './skill-parser';

describe('skill-parser', () => {
  it('parses YAML frontmatter name and description', () => {
    const content = `---\nname: test-skill\ndescription: Use when testing\n---\n# Content`;
    const result = parseSkillFrontmatter(content);
    expect(result.name).toBe('test-skill');
    expect(result.description).toBe('Use when testing');
  });

  it('returns empty object for content without frontmatter', () => {
    const result = parseSkillFrontmatter('# No frontmatter here');
    expect(result.name).toBeUndefined();
  });
});

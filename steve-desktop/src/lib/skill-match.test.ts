import { describe, it, expect } from 'vitest';
import { selectSkills, skillsToPrompt } from './skill-match';

const skill = {
  name: 'Email this parent',
  content: '---\nname: Email this parent\ndescription: x\n---\n\nBody: read p1, paste p1.',
  url_pattern: 'https://*.aeries.net/*',
  is_active: 1,
};

describe('skill-match', () => {
  it('matches by name appearing in the message', () => {
    expect(selectSkills([skill], { message: 'please email this parent now' })).toHaveLength(1);
    expect(selectSkills([skill], { message: 'do something unrelated' })).toHaveLength(0);
  });

  it('matches by url_pattern glob', () => {
    expect(selectSkills([skill], { url: 'https://chicousd.aeries.net/teacher/EmergencyContacts.aspx' })).toHaveLength(1);
    expect(selectSkills([skill], { url: 'https://example.com/' })).toHaveLength(0);
  });

  it('skips inactive skills', () => {
    expect(selectSkills([{ ...skill, is_active: 0 }], { message: 'email this parent' })).toHaveLength(0);
  });

  it('renders the body without frontmatter', () => {
    const p = skillsToPrompt([skill]);
    expect(p).toContain('Body: read p1');
    expect(p).not.toMatch(/description: x/);
    expect(skillsToPrompt([])).toBe('');
  });
});

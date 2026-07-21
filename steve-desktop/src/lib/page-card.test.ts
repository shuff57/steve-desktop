import { describe, it, expect, vi } from 'vitest';
import { profileDigest, buildPageCardPrompt, parsePageCard, fetchPageCard, PageCardCache } from './page-card';
import type { SiteProfile } from './types/site-profile';

function profile(over: Partial<SiteProfile> = {}): SiteProfile {
  return {
    url: 'https://example.edu/course/gb.php?cid=301265&uid=41',
    domain: 'example.edu',
    pageName: 'Gradebook',
    profiledAt: '2026-07-20T00:00:00Z',
    interactive: {
      buttons: [{ text: 'Save Grades', selector: '#save' }],
      links: [{ text: '⟦D1⟧', selector: 'a1', href: '/student.php?uid=41' }],
      inputs: [{ label: 'Score', selector: '#s1' }],
      selects: [], checkboxes: [], radios: [], forms: [],
    },
    headings: [{ level: 1, text: 'Gradebook' }],
    summary: { buttons: 1, links: 1, inputs: 1, selects: 0, checkboxes: 0, radios: 0, forms: 1, landmarks: 0, headings: 1 },
    ...over,
  };
}

describe('profileDigest', () => {
  it('uses labels and path, never hrefs', () => {
    const d = profileDigest(profile());
    expect(d).toContain('Save Grades');
    expect(d).toContain('/course/gb.php');
    expect(d).not.toContain('/student.php'); // hrefs excluded wholesale
  });
});

describe('parsePageCard', () => {
  it('parses fenced/prosy replies', () => {
    const card = parsePageCard('Sure!\n```json\n{"pageType":"gradebook-roster","purpose":"p","keyActions":["a"],"automationValue":"high"}\n```');
    expect(card).toEqual({ pageType: 'gradebook-roster', purpose: 'p', keyActions: ['a'], automationValue: 'high' });
  });
  it('rejects garbage and missing fields', () => {
    expect(parsePageCard('no json here')).toBeNull();
    expect(parsePageCard('{"purpose":"p"}')).toBeNull();
  });
  it('clamps bad enum to low and caps actions at 5', () => {
    const card = parsePageCard(JSON.stringify({ pageType: 't', purpose: 'p', keyActions: ['1', '2', '3', '4', '5', '6'], automationValue: 'extreme' }));
    expect(card?.automationValue).toBe('low');
    expect(card?.keyActions).toHaveLength(5);
  });
});

describe('fetchPageCard', () => {
  it('refuses to call the transport when a secret leaks into the prompt', async () => {
    const transport = vi.fn().mockResolvedValue('{}');
    // Secret value that appears verbatim in the digest (a button label here) → gate throws → null.
    const card = await fetchPageCard(profile(), { '⟦D9⟧': 'Save Grades' }, transport);
    expect(card).toBeNull();
    expect(transport).not.toHaveBeenCalled();
  });
  it('returns null on transport error (deterministic fallback)', async () => {
    const card = await fetchPageCard(profile(), {}, vi.fn().mockRejectedValue(new Error('down')));
    expect(card).toBeNull();
  });
  it('returns the parsed card and keeps tokens un-rehydrated', async () => {
    const transport = vi.fn().mockResolvedValue('{"pageType":"roster","purpose":"lists ⟦D1⟧","keyActions":[],"automationValue":"medium"}');
    const card = await fetchPageCard(profile(), { '⟦D1⟧': 'Doe, Jane' }, transport);
    expect(card?.purpose).toContain('⟦D1⟧'); // token must NOT become the student name
    expect(card?.purpose).not.toContain('Doe');
  });
});

describe('PageCardCache', () => {
  it('keys by url template — same family shares one card', () => {
    const c = new PageCardCache();
    c.set('https://x.edu/student.php?uid=41', { pageType: 't', purpose: 'p', keyActions: [], automationValue: 'low' });
    expect(c.has('https://x.edu/student.php?uid=99')).toBe(true);
    expect(c.has('https://x.edu/grades.php?uid=41')).toBe(false);
  });
});

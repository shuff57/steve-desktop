import { isSiteProfile } from './site-profile';

describe('SiteProfile types', () => {
  it('isSiteProfile rejects null', () => {
    expect(isSiteProfile(null)).toBe(false);
  });

  it('isSiteProfile rejects empty object', () => {
    expect(isSiteProfile({})).toBe(false);
  });

  it('isSiteProfile accepts valid profile', () => {
    const profile = {
      url: 'https://example.com',
      domain: 'example.com',
      pageName: 'test-page',
      profiledAt: '2026-03-15T00:00:00.000Z',
      interactive: { buttons: [], links: [], inputs: [], selects: [], checkboxes: [], radios: [], forms: [] },
      summary: { buttons: 0, links: 0, inputs: 0, selects: 0, checkboxes: 0, radios: 0, forms: 0, landmarks: 0, headings: 0 },
    };
    expect(isSiteProfile(profile)).toBe(true);
  });
});

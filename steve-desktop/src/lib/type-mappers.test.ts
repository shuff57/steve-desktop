import type { DiscoveryResult } from './discover';
import type { SiteProfile } from './types/site-profile';
import { discoveryResultToProfile, profileToDiscoveryResult } from './type-mappers';

describe('type-mappers', () => {
  it('converts a discovery result into a complete site profile', () => {
    const result: DiscoveryResult = {
      profile: {
        title: 'Training Portal',
        goal: 'Play the assigned training video',
        interactive: {
          buttons: [{ text: 'Play', selector: 'role=button[name="Play"]' }],
          links: [{ text: 'Next', selector: 'role=link[name="Next"]', href: '/next' }],
          inputs: [{ label: 'Search', selector: 'role=textbox[name="Search"]', type: 'text' }],
          selects: [],
          checkboxes: [],
          radios: [],
          forms: [{ name: 'training-form' }],
        },
        landmarks: {
          main: [{ selector: 'main', label: 'Main content' }],
        },
        headings: [{ level: 1, text: 'Assigned Training' }],
      },
      confidence: 'medium',
    };

    const profile = discoveryResultToProfile(result, 'https://example.com/training', 'example.com', 'training');

    expect(profile.url).toBe('https://example.com/training');
    expect(profile.domain).toBe('example.com');
    expect(profile.pageName).toBe('training');
    expect(profile.title).toBe('Training Portal');
    expect(profile.goal).toBe('Play the assigned training video');
    expect(profile.profiledAt).toMatch(/^\d{4}-\d{2}-\d{2}T/);
    expect(profile.summary).toEqual({
      buttons: 1,
      links: 1,
      inputs: 1,
      selects: 0,
      checkboxes: 0,
      radios: 0,
      forms: 1,
      landmarks: 1,
      headings: 1,
    });
  });

  it('fills missing discovery sections with empty collections', () => {
    const result: DiscoveryResult = {
      profile: {},
      confidence: 'low',
    };

    const profile = discoveryResultToProfile(result, 'https://example.com', 'example.com', 'home');

    expect(profile.interactive).toEqual({
      buttons: [],
      links: [],
      inputs: [],
      selects: [],
      checkboxes: [],
      radios: [],
      forms: [],
    });
    expect(profile.summary).toEqual({
      buttons: 0,
      links: 0,
      inputs: 0,
      selects: 0,
      checkboxes: 0,
      radios: 0,
      forms: 0,
      landmarks: 0,
      headings: 0,
    });
  });

  it('converts a stored site profile back into a discovery result shape', () => {
    const profile: SiteProfile = {
      url: 'https://example.com/training',
      domain: 'example.com',
      pageName: 'training',
      profiledAt: '2026-03-15T00:00:00.000Z',
      title: 'Training Portal',
      interactive: {
        buttons: [{ text: 'Play', selector: 'role=button[name="Play"]' }],
        links: [],
        inputs: [],
        selects: [],
        checkboxes: [],
        radios: [],
        forms: [],
      },
      landmarks: {
        main: [{ selector: 'main', label: 'Main content' }],
      },
      headings: [{ level: 1, text: 'Assigned Training' }],
      summary: {
        buttons: 1,
        links: 0,
        inputs: 0,
        selects: 0,
        checkboxes: 0,
        radios: 0,
        forms: 0,
        landmarks: 1,
        headings: 1,
      },
    };

    const result = profileToDiscoveryResult(profile);

    expect(result.confidence).toBe('high');
    expect(result.profile).toEqual({
      title: 'Training Portal',
      interactive: profile.interactive,
      landmarks: profile.landmarks,
      headings: profile.headings,
      summary: profile.summary,
    });
  });
});

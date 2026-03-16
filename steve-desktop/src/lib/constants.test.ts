import { APP_NAME, APP_SUBTITLE, DB_NAME, SITE_PROFILES_DIR } from './constants';
import { slugify, truncate, domainFromUrl, domainToPath, pageNameFromUrl } from './utils/index';

describe('constants', () => {
  it('has S.T.E.V.E app name', () => {
    expect(APP_NAME).toBe('S.T.E.V.E Desktop');
    expect(APP_SUBTITLE).toContain('Every Video');
  });

  it('uses steve.db', () => {
    expect(DB_NAME).toBe('sqlite:steve.db');
    expect(DB_NAME).not.toContain('ogre');
  });

  it('uses .agents/site-profiles dir', () => {
    expect(SITE_PROFILES_DIR).toBe('.agents/site-profiles');
  });
});

describe('utils', () => {
  it('slugify converts to kebab-case', () => {
    expect(slugify('Hello World')).toBe('hello-world');
    expect(slugify('Fire Extinguisher Safety')).toBe('fire-extinguisher-safety');
  });

  it('truncate shortens long strings', () => {
    expect(truncate('short', 100)).toBe('short');
    expect(truncate('a'.repeat(100), 10)).toBe('aaaaaaa...');
  });

  it('domainFromUrl extracts hostname', () => {
    expect(domainFromUrl('https://example.com/page')).toBe('example.com');
  });

  it('domainToPath replaces dots with dashes', () => {
    expect(domainToPath('example.com')).toBe('example-com');
    expect(domainToPath('butte-keenan.safecolleges.com')).toBe('butte-keenan-safecolleges-com');
  });

  it('pageNameFromUrl extracts last path segment', () => {
    expect(pageNameFromUrl('https://example.com/training/home')).toBe('home');
    expect(pageNameFromUrl('https://example.com/')).toBe('home');
  });
});

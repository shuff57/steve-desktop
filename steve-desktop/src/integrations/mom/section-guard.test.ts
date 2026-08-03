import { describe, it, expect } from 'vitest';
import { sectionOf, sectionMismatch, describeMismatch } from './section-guard';

describe('sectionOf', () => {
  it('reads a bookSHelf section link', () => {
    expect(
      sectionOf('https://oerbookshelf.app/introduction-to-stats/chapter-1-sampling-and-data/1.4_experimental_design_and_ethics'),
    ).toBe('1.4');
  });

  it('reads a manifest file name', () => {
    expect(sectionOf('1-3-frequency-frequency-tables-and-levels-of-measurement.json')).toBe('1.3');
  });

  it('reads a manifest path', () => {
    expect(sectionOf('books/introduction-to-stats-sh/hw/1-2-data-sampling.json')).toBe('1.2');
  });

  it('reads an assignment title', () => {
    expect(sectionOf('1.3 Frequency, Frequency Tables, and Levels of Measurement')).toBe('1.3');
  });

  it('normalises a padded number', () => {
    expect(sectionOf('01-04-something.json')).toBe('1.4');
  });

  it('does not invent a section from an unrelated number', () => {
    // The hyphen form only counts at the START of a segment, so a slug with numbers in it
    // cannot masquerade as a section — a false match here would fire warnings constantly.
    expect(sectionOf('questions/descriptive-stats/top-10-2-tips.php')).toBeNull();
    expect(sectionOf('no numbers here')).toBeNull();
    expect(sectionOf('')).toBeNull();
    expect(sectionOf(null)).toBeNull();
  });
});

describe('sectionMismatch', () => {
  it('reports the real misfile that motivated this', () => {
    // Link on 1.4, destination left on 1.3 from earlier work: the 1.4 question landed as
    // slot 16 of the 1.3 homework and nothing said a word.
    const m = sectionMismatch(
      'https://oerbookshelf.app/introduction-to-stats/chapter-1-sampling-and-data/1.4_experimental_design_and_ethics',
      '1-3-frequency-frequency-tables-and-levels-of-measurement.json',
    );
    expect(m).toEqual({ from: '1.4', into: '1.3' });
    expect(describeMismatch(m!)).toContain('section 1.4');
    expect(describeMismatch(m!)).toContain('section 1.3');
  });

  it('is quiet when the two agree', () => {
    expect(sectionMismatch('.../1.3_frequency', '1-3-frequency.json')).toBeNull();
  });

  it('is quiet when either side has no section number', () => {
    // Absence is not disagreement. Warning on a hand-typed brief or an unnamed
    // destination would train the warning to be ignored.
    expect(sectionMismatch('write me something about sampling', '1-3-frequency.json')).toBeNull();
    expect(sectionMismatch('.../1.4_experimental_design', '')).toBeNull();
    expect(sectionMismatch(null, null)).toBeNull();
  });
});

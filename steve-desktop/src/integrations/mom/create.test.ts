import { describe, it, expect } from 'vitest';
import { slugify, addBookToRegistry, newAssignmentManifest, assignmentPath } from './create';

// The real registry's shape, hand-aligned exactly as it is on disk.
const REGISTRY = `{
  "_purpose": "The books that exist...",
  "books": [
    { "slug": "applied-finite-math",           "title": "Applied Finite Math" },
    { "slug": "introduction-to-stats-sh",      "title": "Introduction to Stats — SH" }
  ]
}
`;

describe('slugify', () => {
  it('turns a title into a path-safe segment', () => {
    expect(slugify('Applied Finite Math')).toBe('applied-finite-math');
  });

  it('collapses punctuation rather than passing it into a path', () => {
    // An em dash or a slash in a slug becomes a directory, or an invalid filename on Windows.
    expect(slugify('Introduction to Stats — SH')).toBe('introduction-to-stats-sh');
    expect(slugify('Ch 7/8: Sets & Counting')).toBe('ch-7-8-sets-counting');
  });

  it('leaves nothing dangling at either end', () => {
    expect(slugify('  Spring 2026!  ')).toBe('spring-2026');
  });
});

describe('addBookToRegistry', () => {
  it('adds the book and keeps the file parseable', () => {
    const out = addBookToRegistry(REGISTRY, 'stats-201', 'Stats 201');
    const parsed = JSON.parse(out);
    expect(parsed.books).toHaveLength(3);
    expect(parsed.books[2]).toEqual({ slug: 'stats-201', title: 'Stats 201' });
  });

  it('leaves the existing entries byte-identical', () => {
    // These files are hand-aligned; a JSON round-trip would rewrite every line.
    const out = addBookToRegistry(REGISTRY, 'stats-201', 'Stats 201');
    expect(out).toContain('{ "slug": "applied-finite-math",           "title": "Applied Finite Math" }');
    expect(out).toContain('"_purpose"');
  });

  it('refuses a duplicate slug', () => {
    // Two books sharing a slug makes every assignment's `book` field ambiguous.
    expect(() => addBookToRegistry(REGISTRY, 'applied-finite-math', 'Another')).toThrow(/already exists/);
  });

  it('handles a registry whose books array is empty', () => {
    const empty = '{\n  "books": []\n}\n';
    expect(JSON.parse(addBookToRegistry(empty, 'a', 'A')).books).toEqual([{ slug: 'a', title: 'A' }]);
  });
});

describe('newAssignmentManifest', () => {
  const base = { book: 'applied-finite-math', kind: 'hw', name: 'Ch 9.1 Matrices', today: '2026-07-30' };

  it('slugs the name and files it under book/kind', () => {
    const { slug } = newAssignmentManifest(base);
    expect(slug).toBe('ch-9-1-matrices');
    expect(assignmentPath(base.book, base.kind, slug)).toBe('applied-finite-math/hw/ch-9-1-matrices.json');
  });

  it('starts with an empty questions array the writer can append to', () => {
    expect(JSON.parse(newAssignmentManifest(base).text).questions).toEqual([]);
  });

  it('does NOT invent a target cid or mom_settings', () => {
    // Those describe a live MyOpenMath assignment. Guessing them writes a plausible-looking lie.
    const parsed = JSON.parse(newAssignmentManifest(base).text);
    expect(parsed.target).toBeUndefined();
    expect(parsed.mom_settings).toBeUndefined();
  });

  it('omits chapter_section when there is none, rather than writing an empty string', () => {
    expect(JSON.parse(newAssignmentManifest(base).text).chapter_section).toBeUndefined();
    const withCh = newAssignmentManifest({ ...base, chapterSection: ' 9-1 ' });
    expect(JSON.parse(withCh.text).chapter_section).toBe('9-1');
  });

  it('refuses a name that slugs to nothing', () => {
    expect(() => newAssignmentManifest({ ...base, name: '!!!' })).toThrow(/needs a name/);
  });
});

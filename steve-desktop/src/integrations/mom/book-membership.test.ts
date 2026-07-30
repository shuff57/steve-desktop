import { describe, it, expect } from 'vitest';
import { setBooksInManifest } from './book-membership';

/**
 * The point of editing text rather than re-serialising is that everything else stays byte-for-byte
 * identical — these manifests are hand-formatted, and a JSON round-trip would bury a one-field
 * change under a rewrite of every line.
 */
describe('setBooksInManifest', () => {
  const withBook = ['{', '  "name": "HW 1",', '  "book": "applied-finite-math",', '  "kind": "hw",', '  "questions": []', '}'].join('\n');

  it('adds books alongside an existing book field', () => {
    const out = setBooksInManifest(withBook, ['applied-finite-math', 'integrated-math-1']);
    expect(out).toContain('"books": ["applied-finite-math", "integrated-math-1"],');
    expect(JSON.parse(out).books).toEqual(['applied-finite-math', 'integrated-math-1']);
  });

  it('leaves every other line untouched', () => {
    const out = setBooksInManifest(withBook, ['x']).split('\n');
    for (const line of ['  "name": "HW 1",', '  "kind": "hw",', '  "questions": []']) {
      expect(out).toContain(line);
    }
  });

  it('replaces an existing books line rather than adding a second', () => {
    const src = '{\n  "name": "HW",\n  "books": ["a", "b"],\n  "kind": "hw"\n}';
    const out = setBooksInManifest(src, ['c']);
    expect(out.match(/"books"/g)?.length).toBe(1);
    expect(JSON.parse(out).books).toEqual(['c']);
  });

  it('inserts after the opening brace when the manifest declares neither key', () => {
    const src = '{\n  "name": "HW",\n  "kind": "hw"\n}';
    const out = setBooksInManifest(src, ['applied-finite-math']);
    expect(JSON.parse(out).books).toEqual(['applied-finite-math']);
    expect(JSON.parse(out).name).toBe('HW');
  });

  it('drops duplicates and blanks', () => {
    const out = setBooksInManifest(withBook, ['a', 'a', '  ', ' b ']);
    expect(JSON.parse(out).books).toEqual(['a', 'b']);
  });

  it('writes an empty array rather than malformed JSON when given nothing', () => {
    const out = setBooksInManifest(withBook, []);
    expect(JSON.parse(out).books).toEqual([]);
  });

  it('keeps the file parseable when books is the last key before the brace', () => {
    const src = '{\n  "name": "HW",\n  "books": ["a"]\n}';
    const out = setBooksInManifest(src, ['b', 'c']);
    expect(() => JSON.parse(out)).not.toThrow();
    expect(JSON.parse(out).books).toEqual(['b', 'c']);
  });
});

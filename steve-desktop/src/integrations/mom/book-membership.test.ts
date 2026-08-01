import { describe, it, expect } from 'vitest';
import { setBooksInManifest, appendQuestionSlot, setQuestionQid } from './book-membership';

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

describe('appendQuestionSlot', () => {
  const withTwo = [
    '{',
    '  "name": "HW",',
    '  "questions": [',
    '    { "slot": 1, "file_path": "questions/a/q1.php", "title": "One" },',
    '    { "slot": 2, "file_path": "questions/a/q2.php", "title": "Two" }',
    '  ]',
    '}',
  ].join('\n');

  it('continues the slot sequence rather than guessing', () => {
    const out = appendQuestionSlot(withTwo, 'questions/a/q3.php', 'Three');
    const qs = JSON.parse(out).questions;
    expect(qs).toHaveLength(3);
    expect(qs[2]).toEqual({ slot: 3, file_path: 'questions/a/q3.php', title: 'Three' });
  });

  it('leaves the existing entries untouched', () => {
    const out = appendQuestionSlot(withTwo, 'questions/a/q3.php', 'Three');
    expect(out).toContain('{ "slot": 1, "file_path": "questions/a/q1.php", "title": "One" },');
    expect(JSON.parse(out).name).toBe('HW');
  });

  it('starts at slot 1 in an empty assignment', () => {
    const out = appendQuestionSlot('{\n  "name": "HW",\n  "questions": []\n}', 'questions/a/q1.php', 'One');
    expect(JSON.parse(out).questions).toEqual([{ slot: 1, file_path: 'questions/a/q1.php', title: 'One' }]);
  });

  it('is not confused by a bracket inside a title string', () => {
    const src = '{\n  "questions": [\n    { "slot": 1, "file_path": "a.php", "title": "Brackets ] and }" }\n  ]\n}';
    const out = appendQuestionSlot(src, 'b.php', 'Next');
    expect(JSON.parse(out).questions).toHaveLength(2);
    expect(JSON.parse(out).questions[0].title).toBe('Brackets ] and }');
  });

  it('escapes a title containing quotes', () => {
    const out = appendQuestionSlot(withTwo, 'questions/a/q3.php', 'He said "hi"');
    expect(JSON.parse(out).questions[2].title).toBe('He said "hi"');
  });

  it('throws rather than corrupting a manifest with no questions array', () => {
    expect(() => appendQuestionSlot('{"name":"x"}', 'a.php', 'A')).toThrow(/no questions array/);
  });
});

describe('setQuestionQid', () => {
  const src = [
    '{',
    '  "name": "HW",',
    '  "questions": [',
    '    { "slot": 1, "file_path": "questions/a/q1.php", "title": "One" },',
    '    { "slot": 2, "file_path": "questions/a/q2.php", "title": "Two" }',
    '  ]',
    '}',
  ].join('\n');

  it('records the id on the matching entry only', () => {
    const qs = JSON.parse(setQuestionQid(src, 'questions/a/q2.php', '1823545')).questions;
    expect(qs[1].qid).toBe('1823545');
    expect(qs[0].qid).toBeUndefined();
  });

  it('overwrites an id that is already there rather than adding a second key', () => {
    const once = setQuestionQid(src, 'questions/a/q1.php', '111');
    const twice = setQuestionQid(once, 'questions/a/q1.php', '222');
    expect(JSON.parse(twice).questions[0].qid).toBe('222');
    expect(twice.match(/"qid"/g)).toHaveLength(1);
  });

  it('survives a title containing a closing brace', () => {
    const tricky = src.replace('"One"', '"Set {a, b} closed"');
    const qs = JSON.parse(setQuestionQid(tricky, 'questions/a/q1.php', '999')).questions;
    expect(qs[0].qid).toBe('999');
    expect(qs[0].title).toBe('Set {a, b} closed');
  });

  it('leaves the manifest untouched when no entry matches', () => {
    expect(setQuestionQid(src, 'questions/a/nope.php', '1')).toBe(src);
  });

  it('matches regardless of path separator', () => {
    const qs = JSON.parse(setQuestionQid(src, 'questions\\a\\q1.php', '42')).questions;
    expect(qs[0].qid).toBe('42');
  });
});

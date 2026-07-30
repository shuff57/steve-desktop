import { describe, it, expect, vi, beforeEach } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: invokeMock }));

import { loadMOMIndex, loadMOMBooks, loadMOMDefaultRoot, isJunkFamily, bookOf, isAssignmentManifest } from './loader';

// The real filesystem walk now lives in Rust (mom_load_index) and is covered by cargo test
// `mom_tests::walks_questions_into_families` against the same fixture. Here we test the pure
// junk rule and that the TS wrapper shapes the command's reply correctly.

describe('isJunkFamily', () => {
  it('flags the Windows artifacts the loader must skip', () => {
    expect(isJunkFamily('nul')).toBe(true);
    expect(isJunkFamily('NUL')).toBe(true);
    expect(isJunkFamily('$APPDATA')).toBe(true);
    expect(isJunkFamily('C:Usersshuff')).toBe(true);
    expect(isJunkFamily('C:UsersshuffAppData')).toBe(true);
  });
  it('does not flag real family names', () => {
    expect(isJunkFamily('frq')).toBe(false);
    expect(isJunkFamily('mcq')).toBe(false);
    expect(isJunkFamily('descriptive-statistics')).toBe(false);
  });
});

describe('loadMOMIndex', () => {
  beforeEach(() => invokeMock.mockReset());

  it('passes the root through and wraps the families the command returns', async () => {
    invokeMock.mockResolvedValue([
      {
        name: 'frq',
        count: 1,
        questions: [{ slug: 'descriptive-statistics', path: '/mom/questions/frq/descriptive-statistics', hasManifest: true }],
      },
    ]);

    const index = await loadMOMIndex('/mom');

    expect(invokeMock).toHaveBeenCalledWith('mom_load_index', { root: '/mom' });
    expect(index.families).toHaveLength(1);
    expect(index.families[0]!.name).toBe('frq');
    expect(index.families[0]!.questions[0]).toMatchObject({
      slug: 'descriptive-statistics',
      hasManifest: true,
    });
  });

  it('returns an empty families array when the command reports nothing (missing questions dir)', async () => {
    invokeMock.mockResolvedValue([]);
    expect((await loadMOMIndex('/no-such-root')).families).toEqual([]);
  });

  it('tolerates a null reply rather than crashing the page', async () => {
    invokeMock.mockResolvedValue(null);
    expect((await loadMOMIndex('/mom')).families).toEqual([]);
  });
});

describe('loadMOMBooks', () => {
  beforeEach(() => invokeMock.mockReset());

  it('parses manifests and maps snake_case fields to the book shape', async () => {
    invokeMock.mockResolvedValue([
      {
        path: 'intro-stats/hw/ch1-day1.json',
        text: JSON.stringify({
          name: 'Homework 1.1',
          kind: 'hw',
          chapter_section: '1.1',
          target: { cid: '99999' },
          questions: [
            { slot: 1, file_path: 'questions/descriptive-stats/q1-mean.php', title: 'Mean', qid: '111', verify_status: 'in-mom' },
          ],
        }),
      },
    ]);

    const books = await loadMOMBooks('/mom');
    expect(invokeMock).toHaveBeenCalledWith('mom_load_books', { root: '/mom' });
    expect(books).toHaveLength(1);
    expect(books[0]).toMatchObject({ name: 'Homework 1.1', kind: 'hw', chapterSection: '1.1', cid: '99999' });
    // The live qid and repo-relative path survive — that's the link back to a question.
    expect(books[0]!.questions[0]).toMatchObject({
      filePath: 'questions/descriptive-stats/q1-mean.php',
      qid: '111',
      verifyStatus: 'in-mom',
    });
  });

  it('skips an unparseable manifest rather than failing the whole load', async () => {
    invokeMock.mockResolvedValue([
      { path: 'bad.json', text: '{ not json' },
      { path: 'ok.json', text: JSON.stringify({ name: 'Fine', questions: [] }) },
    ]);
    const books = await loadMOMBooks('/mom');
    expect(books.map((b) => b.name)).toEqual(['Fine']);
  });

  it('returns [] when there are no books', async () => {
    invokeMock.mockResolvedValue([]);
    expect(await loadMOMBooks('/mom')).toEqual([]);
  });
});

describe('loadMOMDefaultRoot', () => {
  beforeEach(() => invokeMock.mockReset());
  it('returns the resolved path, or empty string', async () => {
    invokeMock.mockResolvedValue('/repo/mom-content');
    expect(await loadMOMDefaultRoot()).toBe('/repo/mom-content');
    invokeMock.mockResolvedValue(null);
    expect(await loadMOMDefaultRoot()).toBe('');
  });
});

/**
 * Grouping the Books pane by course exposed two facts about the real content: the `book` field is
 * missing on six real assignments under applied-finite-math, and four `_scrape-*` working files
 * were being listed as if they were assignments.
 */
describe('bookOf', () => {
  it('uses the declared field when there is one', () => {
    expect(bookOf('applied-finite-math/hw/x.json', 'introduction-to-stats')).toBe('introduction-to-stats');
  });

  it('falls back to the directory, because six real assignments declare no book', () => {
    expect(bookOf('applied-finite-math/hw/ch8.1-sample-spaces-probability.json', undefined)).toBe('applied-finite-math');
  });

  it('treats a blank or non-string field as missing', () => {
    expect(bookOf('applied-finite-math/hw/x.json', '   ')).toBe('applied-finite-math');
    expect(bookOf('applied-finite-math/hw/x.json', 42)).toBe('applied-finite-math');
  });

  it('handles Windows separators from the Rust walk', () => {
    expect(bookOf(String.raw`introduction-to-stats\high-school\hw\a.json`, undefined)).toBe('introduction-to-stats');
  });

  it('returns empty for a manifest sitting directly in books/', () => {
    expect(bookOf('loose.json', undefined)).toBe('');
  });
});

describe('isAssignmentManifest', () => {
  it('rejects the _scrape working files, which have no name and no questions', () => {
    expect(isAssignmentManifest('introduction-to-stats/high-school/practice/_scrape-ch678-inventory.json', { _purpose: 'x' })).toBe(false);
  });

  it('rejects a manifest with no name', () => {
    expect(isAssignmentManifest('a/b/c.json', { kind: 'hw' })).toBe(false);
    expect(isAssignmentManifest('a/b/c.json', { name: '  ' })).toBe(false);
  });

  it('accepts a real assignment', () => {
    expect(isAssignmentManifest('applied-finite-math/hw/ch8.1.json', { name: 'Homework: Ch8.1' })).toBe(true);
  });
});

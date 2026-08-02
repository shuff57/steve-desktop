import { describe, it, expect } from 'vitest';
import {
  parseLibrary,
  serialiseLibrary,
  recordFiled,
  planPush,
  type QuestionLibrary,
} from './question-library';

const entry = (qsetid: string) => ({ qsetid, cid: '334243', filed: '2026-08-02' });

describe('parseLibrary', () => {
  it('reads an index', () => {
    const lib = parseLibrary(JSON.stringify({ 'q/a.php': entry('111') }));
    expect(lib['q/a.php'].qsetid).toBe('111');
  });

  it('treats malformed content as empty rather than throwing mid-push', () => {
    // A push that dies parsing its own index is worse than one that re-files.
    expect(parseLibrary('{not json')).toEqual({});
    expect(parseLibrary('')).toEqual({});
    expect(parseLibrary('[1,2,3]')).toEqual({});
  });

  it('drops entries with no usable qsetid', () => {
    const lib = parseLibrary(JSON.stringify({ 'q/a.php': { cid: '1' }, 'q/b.php': entry('222') }));
    expect(Object.keys(lib)).toEqual(['q/b.php']);
  });
});

describe('serialiseLibrary', () => {
  it('sorts by path so a push produces a reviewable diff', () => {
    const lib: QuestionLibrary = { 'q/z.php': entry('2'), 'q/a.php': entry('1') };
    const keys = Object.keys(JSON.parse(serialiseLibrary(lib)));
    expect(keys).toEqual(['q/a.php', 'q/z.php']);
  });
});

describe('recordFiled', () => {
  it('records a newly filed question', () => {
    const { library, duplicate } = recordFiled({}, 'q/a.php', entry('111'));
    expect(duplicate).toBeUndefined();
    expect(library['q/a.php'].qsetid).toBe('111');
  });

  it('refuses to silently reassign a source to a different library id', () => {
    // Two qsetids for one source IS the duplicate this index exists to prevent,
    // so it has to surface rather than quietly overwrite.
    const lib = { 'q/a.php': entry('111') };
    const { library, duplicate } = recordFiled(lib, 'q/a.php', entry('999'));
    expect(duplicate).toEqual({ existing: '111', incoming: '999' });
    expect(library['q/a.php'].qsetid).toBe('111');
  });

  it('is idempotent when the same id is recorded twice', () => {
    const lib = { 'q/a.php': entry('111') };
    const { duplicate } = recordFiled(lib, 'q/a.php', entry('111'));
    expect(duplicate).toBeUndefined();
  });
});

describe('planPush', () => {
  it('attaches what the manifest already recorded', () => {
    const plan = planPush([{ slot: 1, file_path: 'q/a.php', qid: '111' }], {});
    expect(plan[0]).toMatchObject({ action: 'attach', qsetid: '111', reusedFrom: 'manifest' });
  });

  it('reuses a question a DIFFERENT assignment already filed', () => {
    // The case the manifest structurally cannot see, and the reason a second
    // assignment used to create a duplicate library question.
    const plan = planPush([{ slot: 1, file_path: 'q/a.php', qid: null }], { 'q/a.php': entry('111') });
    expect(plan[0]).toMatchObject({ action: 'attach', qsetid: '111', reusedFrom: 'library' });
  });

  it('files a source nothing has pushed before', () => {
    const plan = planPush([{ slot: 1, file_path: 'q/new.php' }], {});
    expect(plan[0]).toMatchObject({ action: 'file' });
    expect(plan[0].qsetid).toBeUndefined();
  });
});

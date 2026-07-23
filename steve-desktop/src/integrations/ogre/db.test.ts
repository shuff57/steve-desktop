/**
 * ogre-island data access, over the shared steve.db connection.
 *
 * These assert the SQL each accessor issues and how it maps the reply. The schema
 * itself is migrations 9 + 10 in src-tauri/src/lib.rs; `schema.test.ts` guards its
 * shape, and cargo covers the migration run.
 */
import { beforeEach, describe, expect, it, vi } from 'vitest';

const { mockExecute, mockSelect, mockLoad } = vi.hoisted(() => {
  const mockExecute = vi.fn().mockResolvedValue({ rowsAffected: 1, lastInsertId: 7 });
  const mockSelect = vi.fn().mockResolvedValue([]);
  const mockLoad = vi.fn().mockResolvedValue({ execute: mockExecute, select: mockSelect });
  return { mockExecute, mockSelect, mockLoad };
});

vi.mock('@tauri-apps/plugin-sql', () => ({ default: { load: mockLoad } }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: vi.fn() }));

import {
  RUBRIC_SOURCE,
  addGradingSession,
  clearBatchResume,
  getBatchResume,
  getRubric,
  getSiteProfile,
  listGradingSessions,
  listRubrics,
  listSiteProfiles,
  setBatchResume,
} from './db';

beforeEach(() => {
  mockExecute.mockClear();
  mockSelect.mockClear();
  mockSelect.mockResolvedValue([]);
});

describe('site profiles', () => {
  it('lists profiles ordered by name', async () => {
    await listSiteProfiles();
    expect(mockSelect).toHaveBeenCalledWith(expect.stringContaining('FROM site_profiles ORDER BY name'));
  });

  it('gets one profile by id and returns null when absent', async () => {
    expect(await getSiteProfile('sp-1')).toBeNull();
    expect(mockSelect).toHaveBeenCalledWith(expect.stringContaining('WHERE id = $1'), ['sp-1']);

    mockSelect.mockResolvedValueOnce([{ id: 'sp-1', name: 'MyOpenMath' }]);
    expect((await getSiteProfile('sp-1'))?.name).toBe('MyOpenMath');
  });
});

describe('rubrics', () => {
  // Rubrics share the skills table with steve's own skills — every rubric query must
  // filter on source, or the rubric picker would list local/marketplace skills too.
  it('lists only rows with source = rubric', async () => {
    await listRubrics();
    expect(mockSelect).toHaveBeenCalledWith(expect.stringContaining('WHERE source = $1'), [RUBRIC_SOURCE]);
  });

  it('scopes get-by-id to rubrics as well', async () => {
    await getRubric('r-1');
    expect(mockSelect).toHaveBeenCalledWith(expect.stringContaining('AND source = $2'), ['r-1', RUBRIC_SOURCE]);
  });

  it('does not return a non-rubric skill that shares the id', async () => {
    mockSelect.mockResolvedValueOnce([]); // the source filter excluded it
    expect(await getRubric('skill-local-1')).toBeNull();
  });
});

describe('grading history', () => {
  it('returns the new row id', async () => {
    const id = await addGradingSession({ provider_id: 'ollama', model: 'llama3', student_count: 12 });
    expect(id).toBe(7);
    const [sql, params] = mockExecute.mock.calls[0]!;
    expect(sql).toContain('INSERT INTO grading_sessions');
    expect(params.slice(0, 3)).toEqual(['ollama', 'llama3', 12]);
  });

  it('writes NULL for omitted optional stats rather than undefined', async () => {
    await addGradingSession({ provider_id: 'ollama' });
    const [, params] = mockExecute.mock.calls[0]!;
    expect(params).toHaveLength(11);
    expect(params.slice(1)).toEqual([null, null, null, null, null, null, null, null, null, null]);
  });

  it('lists newest first with a bound limit', async () => {
    await listGradingSessions(10);
    expect(mockSelect).toHaveBeenCalledWith(expect.stringContaining('ORDER BY id DESC LIMIT $1'), [10]);
  });
});

describe('batch resume marker', () => {
  it('returns null when a url has never been run', async () => {
    expect(await getBatchResume('https://x/g?cid=1')).toBeNull();
  });

  it('returns the last student when present', async () => {
    mockSelect.mockResolvedValueOnce([{ last_student_name: 'Student 12' }]);
    expect(await getBatchResume('https://x/g?cid=1')).toBe('Student 12');
  });

  it('upserts on url so a second run overwrites rather than duplicates', async () => {
    await setBatchResume('https://x/g?cid=1', 'Student 12');
    const [sql, params] = mockExecute.mock.calls[0]!;
    expect(sql).toContain('ON CONFLICT(url) DO UPDATE');
    expect(params).toEqual(['https://x/g?cid=1', 'Student 12']);
  });

  it('clears the marker for one url', async () => {
    await clearBatchResume('https://x/g?cid=1');
    expect(mockExecute).toHaveBeenCalledWith('DELETE FROM batch_session WHERE url = $1', [
      'https://x/g?cid=1',
    ]);
  });
});

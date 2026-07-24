import { describe, it, expect, vi, beforeEach } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: invokeMock }));

import { momIsland, type MomMethods } from './index';

describe('momIsland', () => {
  it('exposes a mom island with the right id and label', () => {
    expect(momIsland.id).toBe('mom');
    expect(momIsland.label).toBe('MOM');
    expect(momIsland.enabled).toBe(true);
  });

  it('declares the phase-2 + phase-3 methods on the island surface', () => {
    const m = momIsland.methods as MomMethods;
    expect(typeof m.browse).toBe('function');
    expect(typeof m.getQuestion).toBe('function');
    expect(typeof m.getFamily).toBe('function');
    expect(typeof m.listBooks).toBe('function');
    expect(typeof m.getDefaultRoot).toBe('function');
    expect(typeof m.createDraft).toBe('function');
    expect(typeof m.upload).toBe('function');
  });
});

describe('getQuestion manifest tolerance', () => {
  beforeEach(() => invokeMock.mockReset());

  it('returns the question even when the sibling manifest has a foreign shape', async () => {
    // frq manifests are {source, questions:{...}} — incompatible with the {version, questions:[]}
    // parser. A bad manifest must NOT block viewing/rendering the question.
    invokeMock.mockResolvedValue({
      path: '/mom/questions/frq/descriptive-statistics/q1.php',
      contents: '// === COMMON CONTROL ===\n$x=1;',
      manifestText: '{"source":"prompts.txt","questions":{"1":{"status":"completed"}}}',
    });
    const q = await momIsland.methods.getQuestion('frq', 'descriptive-statistics/q1.php', '/mom');
    expect(q.contents).toContain('COMMON CONTROL');
    expect(q.manifest).toEqual({ completed: 0, pending: 0, total: 0 });
  });
});

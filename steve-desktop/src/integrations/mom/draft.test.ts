import { describe, it, expect, vi, beforeEach } from 'vitest';

const { invokeMock } = vi.hoisted(() => ({ invokeMock: vi.fn() }));
vi.mock('@tauri-apps/api/core', () => ({ invoke: invokeMock }));

import { createDraft, isValidSlug } from './draft';

// The copy + "template must stay inside <momRoot>/questions" guard now run in Rust
// (mom_create_draft), where the paths are actually resolved — cargo test covers the slug rule.
// Here: the slug fast-fail and that we hand the command the right arguments.

const OPTS = {
  momRoot: '/mom',
  draftsDir: '/drafts',
  templatePath: 'frq/basics/q1-test.php',
  slug: 'q99-test',
};

describe('isValidSlug', () => {
  it('accepts ordinary slugs', () => {
    expect(isValidSlug('q99-test')).toBe(true);
    expect(isValidSlug('a.b_c-1')).toBe(true);
  });
  it('rejects traversal and junk', () => {
    expect(isValidSlug('../etc/passwd')).toBe(false);
    expect(isValidSlug('-leading')).toBe(false);
    expect(isValidSlug('has space')).toBe(false);
    expect(isValidSlug('')).toBe(false);
  });
});

describe('createDraft', () => {
  beforeEach(() => invokeMock.mockReset());

  it('forwards the draft request and returns the command result', async () => {
    invokeMock.mockResolvedValue({ draftPath: '/drafts/frq/q99-test.php', family: 'frq', slug: 'q99-test' });

    const result = await createDraft('frq', OPTS);

    expect(invokeMock).toHaveBeenCalledWith('mom_create_draft', {
      momRoot: '/mom',
      draftsDir: '/drafts',
      templatePath: 'frq/basics/q1-test.php',
      family: 'frq',
      slug: 'q99-test',
    });
    expect(result.draftPath).toContain('q99-test.php');
    expect(result.family).toBe('frq');
  });

  it('rejects slugs that try to escape the drafts dir, without calling the command', async () => {
    await expect(createDraft('frq', { ...OPTS, slug: '../etc/passwd' })).rejects.toThrow(/invalid slug/);
    expect(invokeMock).not.toHaveBeenCalled();
  });

  // A missing template / escaping path is detected and reported by mom_create_draft in Rust
  // (cargo test mom_tests) — the wrapper just lets that rejection through.
});

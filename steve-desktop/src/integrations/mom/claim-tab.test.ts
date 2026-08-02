import { describe, expect, test, vi, afterEach } from 'vitest';
import { claimTabForRun } from './transfer-via-agent';

const w = globalThis as unknown as { __steveControl?: unknown };

afterEach(() => {
  delete w.__steveControl;
});

describe('claimTabForRun', () => {
  test('claims the tab and releases it again', async () => {
    const claimTab = vi.fn(async () => {});
    const releaseTab = vi.fn(async () => {});
    w.__steveControl = { claimTab, releaseTab };

    const release = await claimTabForRun('tab-1', 'run-A');
    expect(claimTab).toHaveBeenCalledWith('tab-1', 'run-A');
    expect(releaseTab).not.toHaveBeenCalled();

    await release();
    expect(releaseTab).toHaveBeenCalledWith('run-A');
  });

  test('a tab another session holds refuses the claim, so the run never starts', async () => {
    w.__steveControl = {
      claimTab: vi.fn(async () => { throw new Error('Tab tab-1: owned by another agent session'); }),
      releaseTab: vi.fn(async () => {}),
    };
    await expect(claimTabForRun('tab-1', 'run-B')).rejects.toThrow(/another agent session/);
  });

  test('runs without the app bridge instead of refusing to work', async () => {
    // Unit tests and out-of-app drivers have no __steveControl; there is nothing
    // to collide with there, so the run proceeds and release is a no-op.
    const release = await claimTabForRun('tab-1', 'run-C');
    await expect(release()).resolves.toBeUndefined();
  });

  test('a failing release never breaks the run that is finishing', async () => {
    w.__steveControl = {
      claimTab: vi.fn(async () => {}),
      releaseTab: vi.fn(async () => { throw new Error('bridge went away'); }),
    };
    const release = await claimTabForRun('tab-1', 'run-D');
    await expect(release()).resolves.toBeUndefined();
  });
});

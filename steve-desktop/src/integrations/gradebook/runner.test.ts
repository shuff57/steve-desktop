import { describe, it, expect } from 'vitest';
import { runFloorScores, type RunResult, type SubprocessSpawner } from './runner';
import { mkdtempSync, writeFileSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { Readable } from 'node:stream';
import { join } from 'node:path';

/**
 * Build a NodeJS.ReadableStream from a string. Mimics the `stdout`/`stderr`
 * pipes that `child_process.spawn` returns.
 */
function streamFromString(s: string): NodeJS.ReadableStream {
  return Readable.from([Buffer.from(s, 'utf-8')]);
}

/**
 * Fake spawner: returns a fake BunSubprocess that yields canned stdout/stderr
 * and exits with the given code. No real subprocess is launched.
 */
function fakeSpawn(opts: {
  scriptArgs: string[];
  stdout?: string;
  stderr?: string;
  exitCode?: number;
  writeCsvs?: string[]; // filenames inside outDir to create
  outDir?: string;
}): SubprocessSpawner {
  return async (cmd, args) => {
    // Verify the spawner is called with a node-compatible runtime and the
    // right script. process.execPath may be `node` or `bun` depending on how
    // vitest is launched; the script must be the first arg.
    expect(typeof cmd).toBe('string');
    expect(cmd.length).toBeGreaterThan(0);
    expect(args[0]).toMatch(/floor-scores\.mjs$/);
    const outDir = opts.outDir ?? args.find((a) => a.startsWith('--out-dir='))?.split('=')[1];

    // Create the canned CSVs (mimicking what the real script would write).
    if (outDir && opts.writeCsvs) {
      for (const name of opts.writeCsvs) {
        writeFileSync(join(outDir, name), 'student,score\nAlice,8\n', 'utf-8');
      }
    }

    const stdout = opts.stdout ?? '';
    const stderr = opts.stderr ?? '';
    const exitCode = opts.exitCode ?? 0;

    return {
      exitCode,
      stdout: streamFromString(stdout),
      stderr: streamFromString(stderr),
    };
  };
}

describe('runFloorScores', () => {
  let outDir: string;

  beforeEach(() => {
    outDir = mkdtempSync(join(tmpdir(), 'gradebook-test-'));
  });

  it('returns ok=true, stdout text, and CSV paths when the script exits 0', async () => {
    const spawn = fakeSpawn({
      scriptArgs: ['--cid=1', '--aid=2'],
      stdout: 'floor complete\nwrote csv\n',
      exitCode: 0,
      writeCsvs: ['unit1_summary.csv', 'unit1_floors.csv'],
      outDir,
    });

    const result: RunResult = await runFloorScores(
      { cid: 1, aid: 2, outDir },
      { spawn },
    );

    expect(result.ok).toBe(true);
    expect(result.exitCode).toBe(0);
    expect(result.stdout).toContain('floor complete');
    expect(result.csvPaths).toHaveLength(2);
    expect(result.csvPaths.map((p) => p.split(/[\\/]/).pop())).toEqual(
      expect.arrayContaining(['unit1_summary.csv', 'unit1_floors.csv']),
    );

    rmSync(outDir, { recursive: true, force: true });
  });

  it('returns ok=false and surfaces stderr when the script exits non-zero', async () => {
    const spawn = fakeSpawn({
      scriptArgs: ['--cid=1', '--aid=2'],
      stdout: 'partial output\n',
      stderr: 'ERROR: Chrome not running\n',
      exitCode: 1,
    });

    const result = await runFloorScores(
      { cid: 1, aid: 2, outDir },
      { spawn },
    );

    expect(result.ok).toBe(false);
    expect(result.exitCode).toBe(1);
    expect(result.stderr).toContain('Chrome not running');
    expect(result.csvPaths).toEqual([]);

    rmSync(outDir, { recursive: true, force: true });
  });

  it('passes --write-back only when opts.writeBack is true', async () => {
    const observedArgs: string[] = [];
    const spawn: SubprocessSpawner = async (cmd, args) => {
      observedArgs.push(...args);
      return {
        exitCode: 0,
        stdout: streamFromString(''),
        stderr: streamFromString(''),
      };
    };

    await runFloorScores({ cid: 1, aid: 2, outDir, writeBack: true }, { spawn });
    expect(observedArgs).toContain('--write-back');

    observedArgs.length = 0;
    await runFloorScores({ cid: 1, aid: 2, outDir, writeBack: false }, { spawn });
    expect(observedArgs).not.toContain('--write-back');

    rmSync(outDir, { recursive: true, force: true });
  });
});

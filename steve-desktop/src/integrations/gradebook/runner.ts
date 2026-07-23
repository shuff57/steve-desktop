/**
 * Subprocess wrapper for the gradebook playwright scripts. The
 * scripts are unchanged — we just invoke them with the right argv and
 * capture their output. Uses `child_process.spawn` so it runs under
 * both Bun and Node (vitest uses Node).
 */
import { spawn, type ChildProcess } from 'node:child_process';
import { readdirSync, statSync } from 'node:fs';
import { join } from 'node:path';
import { buildFloorArgs, buildScrapeQidsArgs, type FloorScoresOpts, type ScrapeQidsOpts } from './args';

const SCRIPTS_DIR = new URL('./scripts/', import.meta.url).pathname;

export interface RunResult {
  ok: boolean;
  exitCode: number;
  stdout: string;
  stderr: string;
  csvPaths: string[];
  error?: string;
}

export interface ScrapeQidsResult {
  ok: boolean;
  exitCode: number;
  qids: Record<string, string>;
  stdout: string;
  stderr: string;
  error?: string;
}

/**
 * Minimal subprocess shape our runner needs. Node's `ChildProcess` satisfies
 * this; the test fake satisfies it too.
 */
export interface SubprocessLike {
  exitCode: number | null;
  readonly stdout: NodeJS.ReadableStream | null;
  readonly stderr: NodeJS.ReadableStream | null;
}

/**
 * Spawner abstraction: (cmd, args) -> SubprocessLike. Defaults to
 * child_process.spawn with stdout/stderr piped.
 */
export type SubprocessSpawner = (
  cmd: string,
  args: string[],
) => Promise<SubprocessLike> | SubprocessLike;

const defaultSpawn: SubprocessSpawner = (cmd, args) =>
  spawn(cmd, args, { stdio: ['ignore', 'pipe', 'pipe'] });

async function readAll(stream: NodeJS.ReadableStream | null): Promise<string> {
  if (!stream) return '';
  const chunks: Buffer[] = [];
  for await (const chunk of stream) {
    chunks.push(typeof chunk === 'string' ? Buffer.from(chunk) : (chunk as Buffer));
  }
  return Buffer.concat(chunks).toString('utf-8');
}

function findRecentCsvs(outDir: string, withinMs = 10_000): string[] {
  try {
    const now = Date.now();
    return readdirSync(outDir)
      .filter((f) => f.endsWith('.csv'))
      .map((f) => join(outDir, f))
      .filter((p) => {
        try {
          return now - statSync(p).mtimeMs <= withinMs;
        } catch {
          return false;
        }
      });
  } catch {
    return [];
  }
}

function waitForExit(proc: SubprocessLike): Promise<number> {
  return new Promise((resolve) => {
    if (proc.exitCode !== null) {
      resolve(proc.exitCode);
      return;
    }
    const cp = proc as unknown as ChildProcess;
    cp.once('exit', (code) => resolve(code ?? -1));
    cp.once('error', () => resolve(-1));
  });
}

export interface RunOpts {
  spawn?: SubprocessSpawner;
  /** Override the script directory. Defaults to ./scripts/ relative to this file. */
  scriptsDir?: string;
}

export async function runFloorScores(
  opts: FloorScoresOpts,
  runOpts: RunOpts = {},
): Promise<RunResult> {
  const scriptsDir = runOpts.scriptsDir ?? SCRIPTS_DIR;
  const spawnFn = runOpts.spawn ?? defaultSpawn;
  const scriptPath = join(scriptsDir, 'floor-scores.mjs');
  const args = [scriptPath, ...buildFloorArgs(opts)];

  const proc = await spawnFn(process.execPath, args);
  const stdoutP = readAll(proc.stdout);
  const stderrP = readAll(proc.stderr);
  const exitCode = await waitForExit(proc);
  const [stdout, stderr] = await Promise.all([stdoutP, stderrP]);

  const csvPaths = exitCode === 0 && opts.outDir ? findRecentCsvs(opts.outDir) : [];

  return {
    ok: exitCode === 0,
    exitCode,
    stdout,
    stderr,
    csvPaths,
    error: exitCode === 0 ? undefined : `floor-scores exited with code ${exitCode}`,
  };
}

export async function runScrapeQids(
  opts: ScrapeQidsOpts,
  runOpts: RunOpts = {},
): Promise<ScrapeQidsResult> {
  const scriptsDir = runOpts.scriptsDir ?? SCRIPTS_DIR;
  const spawnFn = runOpts.spawn ?? defaultSpawn;
  const scriptPath = join(scriptsDir, 'scrape-qids.mjs');
  const args = [scriptPath, ...buildScrapeQidsArgs(opts)];

  const proc = await spawnFn(process.execPath, args);
  const stdoutP = readAll(proc.stdout);
  const stderrP = readAll(proc.stderr);
  const exitCode = await waitForExit(proc);
  const [stdout, stderr] = await Promise.all([stdoutP, stderrP]);

  // The script prints a `questions: {...}` JSON line. Parse it.
  const qids: Record<string, string> = {};
  if (exitCode === 0) {
    const match = stdout.match(/questions:\s*(\{[^}]+\})/);
    if (match) {
      try {
        Object.assign(qids, JSON.parse(match[1]));
      } catch {
        // leave empty
      }
    }
  }

  return {
    ok: exitCode === 0,
    exitCode,
    qids,
    stdout,
    stderr,
    error: exitCode === 0 ? undefined : `scrape-qids exited with code ${exitCode}`,
  };
}

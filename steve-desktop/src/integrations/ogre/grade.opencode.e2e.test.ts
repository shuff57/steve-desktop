/**
 * Proves the opencode CLI transport works, the same way grade.cli.e2e.test.ts proves it for
 * claude. See that file's header for the full rationale — this one only documents what differs.
 *
 * Differences from the claude engine:
 *  - argv comes from the `"opencode" =>` arm of run_agent_cli in src-tauri/src/lib.rs:
 *    `opencode run --format json` (+ `-m <model>` when a model is given). Grading always runs
 *    with bypassPermissions: false (see grade.ts cliRunner), so `--auto` is never added — matching
 *    what claude does with `--disallowed-tools "*"` in its own non-bypass branch. Unlike claude,
 *    the opencode arm has no explicit tool-lockdown flag for the non-bypass case; this test does
 *    not probe that gap, it only proves the grading round-trip.
 *  - opencode is an npm shim (`opencode.cmd`), not a native .exe, so CreateProcess can't run it
 *    directly — same is_shim problem run_agent_cli solves for `claude` when that's npm-installed
 *    too. This file resolves it on PATH and routes it through cmd.exe /c exactly as the Rust
 *    resolve_on_path/claude_command pair does.
 *  - the model id goes through the real `cliModelArg`, which prefixes a bare id with `ollama/`
 *    for opencode (opencode wants `provider/model`).
 *
 * Opt-in: OGRE_OPENCODE_E2E=1. Talks only to a LOCAL Ollama model (http://localhost:11434) —
 * nothing leaves the machine. The fixture is 30 invented students; never point this at real
 * gradebook data.
 *
 * Environment note: model ids must be `provider/model`. `cliModelArg` prefixes a bare id
 * with `ollama/`, which needs a local ollama provider in ~/.config/opencode/opencode.jsonc.
 * An install authenticated against Ollama Cloud exposes `ollama-cloud/*` instead, so pass a
 * qualified id via OGRE_OPENCODE_E2E_MODEL (e.g. ollama-cloud/gemma4:31b) — ids containing
 * `/` pass through untouched. Note a cloud model leaves the machine; the fixture is
 * synthetic, so that is fine here and would not be for real student work.
 */
import { spawn } from 'node:child_process';
import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import { describe, expect, it } from 'vitest';
import { extractCliText, cliModelArg } from '../../lib/agent-cli';
import { gradeBatch, type GradeProvider, type Student } from './grade';
import type { BatchResult } from './batch';
import type { Rubric } from './grading';

const ENABLED = process.env.OGRE_OPENCODE_E2E === '1';
const STUDENT_COUNT = Number(process.env.OGRE_OPENCODE_E2E_STUDENTS ?? 3);
const CHUNK_SIZE = Number(process.env.OGRE_OPENCODE_E2E_CHUNK ?? STUDENT_COUNT);
const MODEL = process.env.OGRE_OPENCODE_E2E_MODEL ?? 'gemma4:12b';

const fixture = JSON.parse(
  readFileSync(join(__dirname, 'fixtures', 'demo-clt-data.json'), 'utf-8'),
) as { rubric: Rubric; students: { index: number; name: string; response: string }[] };

const students: Student[] = fixture.students
  .slice(0, STUDENT_COUNT)
  .map((s) => ({ name: s.name, responseText: s.response }));

/** Mirrors resolve_on_path in src-tauri/src/lib.rs: PATHEXT-aware PATH search, executable
 *  extension preferred over a bare/POSIX-script match of the same name. */
function resolveOnPath(binary: string): string | null {
  const exts = (process.env.PATHEXT ?? '.COM;.EXE;.BAT;.CMD')
    .split(';')
    .filter(Boolean)
    .map((e) => e.toLowerCase());
  const dirs = (process.env.PATH ?? '').split(';').filter(Boolean);
  for (const dir of dirs) {
    for (const ext of exts) {
      const candidate = join(dir, `${binary}${ext}`);
      if (existsSync(candidate)) return candidate;
    }
    const direct = join(dir, binary);
    if (existsSync(direct)) return direct;
  }
  return null;
}

/** The exact argv run_agent_cli assembles for engine=opencode, bypassPermissions=false, no resume. */
function opencodeArgs(model?: string): string[] {
  const args = ['run', '--format', 'json'];
  // --agent summary is the difference between working and not. opencode's default agent
  // boots the whole coding stack (~29.3K input tokens before the prompt); `summary` carries
  // almost no tools and costs ~4.6K. Measured on this machine:
  //   default 29329 | plan 29609 | title 4945 | summary 4560
  // Without it a grading prompt overruns a local Ollama's 32768 ceiling and truncates.
  args.push('--agent', 'summary');
  const m = cliModelArg('opencode', model);
  if (m) args.push('-m', m);
  return args;
}

function spawnOpencode(prompt: string, model?: string, timeoutMs = 15 * 60_000): Promise<string> {
  return new Promise((resolve, reject) => {
    const bin = resolveOnPath('opencode');
    if (!bin) return reject(new Error('opencode not found on PATH'));
    const isShim = /\.(cmd|bat)$/i.test(bin);
    const sysroot = process.env.SystemRoot ?? 'C:\\Windows';
    const args = opencodeArgs(model);
    const child = isShim
      ? spawn(`${sysroot}\\System32\\cmd.exe`, ['/c', bin, ...args], { stdio: ['pipe', 'pipe', 'pipe'], windowsHide: true })
      : spawn(bin, args, { stdio: ['pipe', 'pipe', 'pipe'], windowsHide: true });

    let stdout = '';
    let stderr = '';
    const timer = setTimeout(() => {
      child.kill();
      reject(new Error(`opencode timed out after ${timeoutMs}ms`));
    }, timeoutMs);

    child.stdout.on('data', (d) => (stdout += d));
    child.stderr.on('data', (d) => (stderr += d));
    child.on('error', (e) => {
      clearTimeout(timer);
      reject(e);
    });
    child.on('close', (code) => {
      clearTimeout(timer);
      if (code !== 0) return reject(new Error(`opencode exited ${code}: ${stderr.slice(0, 800)}\n---stdout---\n${stdout.slice(0, 800)}`));
      resolve(stdout);
    });

    // Prompt on stdin, never argv — same as the Rust side.
    child.stdin.write(prompt);
    child.stdin.end();
  });
}

const provider: GradeProvider = { id: 'ollama', model: MODEL };

describe.runIf(ENABLED)('opencode CLI transport', () => {
  it(
    'grades a batch by spawning the real opencode CLI against a local Ollama model',
    async () => {
      let results: BatchResult[] = [];
      let sawPrompt = '';
      const prompts: string[] = [];
      let chunkStarts = 0;

      const run = async (prompt: string) => {
        sawPrompt = prompt;
        prompts.push(prompt);
        const stdout = await spawnOpencode(prompt, provider.model);
        // Bounded on purpose: a passing run's stdout is tens of KB of event JSON. The
        // failure path already embeds the raw output in its error, which is when you want it.
        console.log(`[opencode-e2e] stdout ${stdout.length} chars, head: ${stdout.slice(0, 200)}`);
        return extractCliText('opencode', stdout);
      };

      for await (const e of gradeBatch(students, fixture.rubric, provider, { chunkSize: CHUNK_SIZE, run })) {
        if (e.type === 'chunk-start') chunkStarts++;
        if (e.type === 'chunk-done') console.log(`[opencode-e2e] chunk ${e.chunkIndex}: ${e.results.length} graded`);
        if (e.type === 'done') results = e.results;
      }

      console.log(`[opencode-e2e] prompt was ${sawPrompt.length} chars`);
      console.log(
        '[opencode-e2e] scores:',
        results.map((r) => `${fixture.students[r.studentIndex]?.name.split(',')[0]}=${r.score}`).join(' '),
      );
      console.log('[opencode-e2e] feedback sample:', results[0]?.feedback.slice(0, 200));

      expect(sawPrompt.length).toBeGreaterThan(8000);

      expect(results.length, 'no results — the CLI run itself failed').toBe(students.length);
      for (const r of results) {
        expect(r.score, `student ${r.studentIndex}`).toBeGreaterThanOrEqual(0);
        expect(r.score, `student ${r.studentIndex}`).toBeLessThanOrEqual(10);
        expect(r.feedback, `student ${r.studentIndex}`).not.toMatch(/error parsing/i);
        expect(r.feedback, `student ${r.studentIndex}`).not.toMatch(/⟦S\d+⟧/);
      }

      expect(results.every((r) => r.feedback.length > 40)).toBe(true);

      const blank = fixture.students.slice(0, STUDENT_COUNT).findIndex((s) => !s.response);
      if (blank >= 0) {
        expect(results[blank]!.score, 'a blank submission must not score like real work').toBeLessThanOrEqual(2);
        expect(Math.max(...results.map((r) => r.score))).toBeGreaterThan(results[blank]!.score);
      }

      expect(results.map((r) => r.studentIndex)).toEqual(students.map((_, i) => i));

      const expectedChunks = Math.ceil(STUDENT_COUNT / CHUNK_SIZE);
      expect(chunkStarts).toBe(expectedChunks);
      if (expectedChunks > 1) {
        expect(prompts[0]).not.toContain('previously graded batch');
        for (let i = 1; i < prompts.length; i++) {
          expect(prompts[i], `chunk ${i} should inherit calibration`).toContain('previously graded batch');
        }
      }
    },
    30 * 60_000,
  );
});

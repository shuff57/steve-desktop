/**
 * Proves the CLI transport actually works.
 *
 * grade.ts reaches a model by `invoke('run_agent_cli')`, and vitest has no Tauri runtime,
 * so that call can never execute here. This test spawns the same binary with the same
 * argument vector the Rust command builds (see run_agent_cli in src-tauri/src/lib.rs) and
 * feeds the prompt on stdin exactly as it does — then puts the output through the real
 * `extractCliText` and the real grading parser.
 *
 * What that leaves unproven is only Tauri's argument marshalling. What it proves is
 * everything that was actually in doubt: the CLI is on PATH and authenticated, a 14KB
 * grading prompt survives stdin, the JSON envelope parses, and a frontier model returns
 * something the batch parser can read.
 *
 * Opt-in: OGRE_CLI_E2E=1. This one leaves the machine — `claude` talks to Anthropic. The
 * fixture is 30 invented students, and the redaction gate tokenizes their names on the
 * way out regardless. Never point this at live gradebook data.
 */
import { spawn } from 'node:child_process';
import { readFileSync } from 'node:fs';
import { join } from 'node:path';
import { randomUUID } from 'node:crypto';
import { describe, expect, it } from 'vitest';
import { extractCliText } from '../../lib/agent-cli';
import { gradeBatch, type GradeProvider, type Student } from './grade';
import type { BatchResult } from './batch';
import type { Rubric } from './grading';

const ENABLED = process.env.OGRE_CLI_E2E === '1';
const STUDENT_COUNT = Number(process.env.OGRE_CLI_E2E_STUDENTS ?? 3);
// Defaults to one chunk. Set below STUDENT_COUNT to exercise chunking + bridge calibration.
const CHUNK_SIZE = Number(process.env.OGRE_CLI_E2E_CHUNK ?? STUDENT_COUNT);

const fixture = JSON.parse(
  readFileSync(join(__dirname, 'fixtures', 'demo-clt-data.json'), 'utf-8'),
) as { rubric: Rubric; students: { index: number; name: string; response: string }[] };

const students: Student[] = fixture.students
  .slice(0, STUDENT_COUNT)
  .map((s) => ({ name: s.name, responseText: s.response }));

/** The exact argv run_agent_cli assembles for engine=claude, no resume, no bypass. */
function claudeArgs(sessionId: string, model?: string): string[] {
  const args = [
    '-p',
    '--output-format',
    'json',
    '--disallowed-tools',
    '*',
    '--strict-mcp-config',
    '--session-id',
    sessionId,
  ];
  if (model?.trim()) args.push('--model', model);
  return args;
}

function spawnClaude(prompt: string, model?: string, timeoutMs = 15 * 60_000): Promise<string> {
  return new Promise((resolve, reject) => {
    const child = spawn('claude.exe', claudeArgs(randomUUID(), model), {
      stdio: ['pipe', 'pipe', 'pipe'],
      windowsHide: true,
    });
    let stdout = '';
    let stderr = '';
    const timer = setTimeout(() => {
      child.kill();
      reject(new Error(`claude timed out after ${timeoutMs}ms`));
    }, timeoutMs);

    child.stdout.on('data', (d) => (stdout += d));
    child.stderr.on('data', (d) => (stderr += d));
    child.on('error', (e) => {
      clearTimeout(timer);
      reject(e);
    });
    child.on('close', (code) => {
      clearTimeout(timer);
      if (code !== 0) return reject(new Error(`claude exited ${code}: ${stderr.slice(0, 500)}`));
      resolve(stdout);
    });

    // Prompt on stdin, never argv — same as the Rust side.
    child.stdin.write(prompt);
    child.stdin.end();
  });
}

const provider: GradeProvider = { id: 'anthropic', model: process.env.OGRE_CLI_E2E_MODEL };

describe.runIf(ENABLED)('CLI transport', () => {
  it(
    'grades a batch by spawning the real claude CLI',
    async () => {
      let results: BatchResult[] = [];
      let sawPrompt = '';
      const prompts: string[] = [];
      let chunkStarts = 0;

      const run = async (prompt: string) => {
        sawPrompt = prompt;
        prompts.push(prompt);
        const stdout = await spawnClaude(prompt, provider.model);
        // The real parser the transport uses — not a hand-rolled one.
        return extractCliText('claude', stdout);
      };

      for await (const e of gradeBatch(students, fixture.rubric, provider, { chunkSize: CHUNK_SIZE, run })) {
        if (e.type === 'chunk-start') chunkStarts++;
        if (e.type === 'chunk-done') console.log(`[cli-e2e] chunk ${e.chunkIndex}: ${e.results.length} graded`);
        if (e.type === 'done') results = e.results;
      }

      console.log(`[cli-e2e] prompt was ${sawPrompt.length} chars`);
      console.log(
        '[cli-e2e] scores:',
        results.map((r) => `${fixture.students[r.studentIndex]?.name.split(',')[0]}=${r.score}`).join(' '),
      );
      console.log('[cli-e2e] feedback sample:', results[0]?.feedback.slice(0, 200));

      // A grading prompt is far larger than an agent turn — worth recording that it fit.
      expect(sawPrompt.length).toBeGreaterThan(8000);

      expect(results.length, 'no results — the CLI run itself failed').toBe(students.length);
      for (const r of results) {
        expect(r.score, `student ${r.studentIndex}`).toBeGreaterThanOrEqual(0);
        expect(r.score, `student ${r.studentIndex}`).toBeLessThanOrEqual(10);
        expect(r.feedback, `student ${r.studentIndex}`).not.toMatch(/error parsing/i);
        expect(r.feedback, `student ${r.studentIndex}`).not.toMatch(/⟦S\d+⟧/);
      }

      // Distinct scores would be ideal but a small sample can legitimately tie; what must
      // hold is that real feedback came back, not a placeholder.
      expect(results.every((r) => r.feedback.length > 40)).toBe(true);

      // Model-behaviour checks, only meaningful once the blank submission is in range.
      // (Student 27 in the fixture submitted nothing.)
      const blank = fixture.students.slice(0, STUDENT_COUNT).findIndex((s) => !s.response);
      if (blank >= 0) {
        expect(results[blank]!.score, 'a blank submission must not score like real work').toBeLessThanOrEqual(2);
        expect(Math.max(...results.map((r) => r.score))).toBeGreaterThan(results[blank]!.score);
      }

      // Roster order must survive chunking and merging.
      expect(results.map((r) => r.studentIndex)).toEqual(students.map((_, i) => i));

      // When the run really is chunked, prove the bridge carried calibration forward —
      // chunk 0 has no prior standard to inherit, every later chunk must.
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

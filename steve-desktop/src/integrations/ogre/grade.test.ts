/**
 * gradeOne() — the FERPA-relevant path. These assert that no student identifier
 * reaches the network, that the reply comes back rehydrated, and that provider shape
 * selection is right.
 */
import { describe, expect, it, vi } from 'vitest';

vi.mock('@tauri-apps/api/core', () => ({ invoke: vi.fn() }));

import {
  gradeBatch,
  gradeOne,
  gradingTimeoutSecs,
  identifiersFor,
  type GradeProvider,
  type GradingEvent,
  type Student,
} from './grade';
import { cliModelArg, engineForProvider } from '../../lib/agent-cli';
import type { Rubric } from './grading';

const RUBRIC: Rubric = {
  maxScore: 10,
  essayPrompt: 'Interpret the residual.',
  checklistItems: [{ category: 'Method', items: ['States the formula'] }],
};

const STUDENT: Student = {
  name: 'Nakamura, Yuki',
  studentId: 'S8842119',
  responseText: 'Yuki Nakamura here. The residual is observed minus predicted, so 2.4.',
};

const OLLAMA: GradeProvider = { id: 'ollama', model: 'llama3' };

/** Captures the prompt that would reach the CLI and replies with canned text. */
function spyRun(reply: string) {
  const seen: string[] = [];
  const run = vi.fn(async (prompt: string) => {
    seen.push(prompt);
    return reply;
  });
  return { run, seen };
}

const OK_REPLY = '{"score": 8, "feedback": "<p>Hi Nakamura, Yuki,</p><p>Good work.</p>"}';

describe('redaction', () => {
  it('sends neither the student name nor id to the model', async () => {
    const { run, seen } = spyRun(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, OLLAMA, { run });

    const wire = seen[0]!;
    expect(wire).not.toContain('Nakamura');
    expect(wire).not.toContain('Yuki');
    expect(wire).not.toContain('S8842119');
  });

  it('still sends the actual work, with identifiers tokenized in place', async () => {
    const { run, seen } = spyRun(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, OLLAMA, { run });

    const wire = seen[0]!;
    expect(wire).toContain('residual is observed minus predicted');
    expect(wire).toContain('Interpret the residual.');
  });

  it('rehydrates the reply locally, so feedback names the real student', async () => {
    const { run } = spyRun(OK_REPLY);
    const out = await gradeOne(STUDENT, RUBRIC, OLLAMA, { run });
    // Greeting is normalized to first name only — proof it round-tripped through rehydrate.
    expect(out.feedback).toContain('<p>Hi Yuki,</p>');
  });

  it('grades a student with no id without tripping the gate', async () => {
    const { run } = spyRun(OK_REPLY);
    const out = await gradeOne({ name: 'Ada Lovelace', responseText: 'answer' }, RUBRIC, OLLAMA, { run });
    expect(out.score).toBe(8);
  });

  // Regression: the gradebook shows "Nakamura, Yuki" but students sign work "Yuki
  // Nakamura". Redacting only the full name let the reversed form through, and
  // assertOutbound could not catch it — it only knows the secrets it was handed.
  it('redacts a name written in the opposite order from the roster', async () => {
    const { run, seen } = spyRun(OK_REPLY);
    await gradeOne(
      { name: 'Nakamura, Yuki', responseText: 'Submitted by Yuki Nakamura on Tuesday.' },
      RUBRIC,
      OLLAMA,
      { run },
    );
    expect(seen[0]!).not.toContain('Yuki');
    expect(seen[0]!).not.toContain('Nakamura');
  });

  it('rehydrates tokens the model echoed back', async () => {
    // What a model really returns: it saw tokens, so it writes tokens.
    const { run } = spyRun('{"score": 8, "feedback": "<p>Hi ⟦S1⟧,</p><p>Nice.</p>"}');
    const out = await gradeOne(STUDENT, RUBRIC, OLLAMA, { run });
    expect(out.feedback).not.toContain('⟦S1⟧');
    expect(out.feedback).toContain('Yuki');
  });
});

describe('identifiersFor', () => {
  it('registers the full name, the id, and each substantial name part', () => {
    expect(identifiersFor({ name: 'Nakamura, Yuki', studentId: 'S88', responseText: '' })).toEqual([
      'Nakamura, Yuki',
      'S88',
      'Nakamura',
      'Yuki',
    ]);
  });

  it('skips parts under 3 chars, which would shred the work being graded', () => {
    // "Li" as a standalone secret would rewrite "qualify", "linear", "public"...
    expect(identifiersFor({ name: 'Li, Wei', responseText: '' })).toEqual(['Li, Wei', 'Wei']);
  });
});

describe('CLI transport', () => {
  it('hands the prompt itself to the runner, not an HTTP body', async () => {
    const { run, seen } = spyRun(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, OLLAMA, { run });

    expect(seen[0]).toContain('You are an expert grading assistant');
    expect(seen[0]).toContain('Interpret the residual.');
  });

  it('routes claude-backed providers to the claude CLI and everything else to opencode', () => {
    expect(engineForProvider('anthropic')).toBe('claude');
    expect(engineForProvider('claude')).toBe('claude');
    expect(engineForProvider('ollama')).toBe('opencode');
    expect(engineForProvider(undefined)).toBe('opencode');
  });

  // run_agent_cli's own default is 180s — fine for an agent turn, fatal mid-batch.
  it('asks for far more time than the CLI default, scaled to the chunk', () => {
    expect(gradingTimeoutSecs(1)).toBe(600);
    expect(gradingTimeoutSecs(20)).toBe(1800);
    expect(gradingTimeoutSecs(10)).toBeGreaterThan(180);
    expect(gradingTimeoutSecs(500)).toBe(3600); // capped
  });

  it('prefixes a bare model id for opencode, which expects provider/model', () => {
    expect(cliModelArg('opencode', 'llama3')).toBe('ollama/llama3');
    expect(cliModelArg('opencode', 'ollama/llama3')).toBe('ollama/llama3');
    expect(cliModelArg('claude', 'claude-opus-4-8')).toBe('claude-opus-4-8');
  });
});

describe('result handling', () => {
  it('scales the score to the rubric max', async () => {
    const { run } = spyRun('{"score": 5, "feedback": "<p>x</p>"}');
    const out = await gradeOne(STUDENT, { ...RUBRIC, maxScore: 4 }, OLLAMA, { run });
    expect(out.score).toBe(2);
  });

  it('surfaces an unparseable reply instead of silently scoring 0 as if graded', async () => {
    const { run } = spyRun('I cannot grade this.');
    const out = await gradeOne(STUDENT, RUBRIC, OLLAMA, { run });
    expect(out.score).toBe(0);
    expect(out.feedback).toMatch(/error parsing/i);
  });

  it('propagates a transport failure rather than reporting a 0 grade', async () => {
    const run = vi.fn().mockRejectedValue(new Error('claude reported an error: not logged in'));
    await expect(gradeOne(STUDENT, RUBRIC, OLLAMA, { run })).rejects.toThrow(/not logged in/);
  });
});

// ── gradeBatch ────────────────────────────────────────────────────────────────

/** Replies with a well-formed grade for every student the prompt asked about. */
function batchRun(scores: number[][]) {
  const seen: string[] = [];
  let call = 0;
  const run = vi.fn(async (prompt: string) => {
    seen.push(prompt);
    const rows = (scores[call++] ?? []).map((score, i) => ({
      studentIndex: i,
      score,
      feedback: `<p>Hi [name],</p><p>result ${i}</p>`,
    }));
    return JSON.stringify(rows);
  });
  return { run, seen };
}

const roster = (n: number): Student[] =>
  Array.from({ length: n }, (_, i) => ({ name: `Name${i} Surname${i}`, responseText: `answer ${i}` }));

async function drain(gen: AsyncGenerator<GradingEvent, unknown, void>) {
  const events: GradingEvent[] = [];
  for await (const e of gen) events.push(e);
  return events;
}

describe('gradeBatch', () => {
  it('grades a small class in a single request', async () => {
    const { run } = batchRun([[8, 6, 4]]);
    const events = await drain(gradeBatch(roster(3), RUBRIC, OLLAMA, { run }));

    expect(run).toHaveBeenCalledTimes(1);
    const done = events.find((e) => e.type === 'done')!;
    expect(done.type === 'done' && done.results.map((r) => r.score)).toEqual([8, 6, 4]);
  });

  it('reports progress per chunk', async () => {
    const { run } = batchRun([[8, 8], [6, 6]]);
    const events = await drain(gradeBatch(roster(4), RUBRIC, OLLAMA, { run, chunkSize: 2 }));

    expect(events.filter((e) => e.type === 'chunk-start')).toHaveLength(2);
    expect(events.filter((e) => e.type === 'chunk-done')).toHaveLength(2);
    expect(events.at(-1)!.type).toBe('done');
  });

  it('returns results in roster order across chunks', async () => {
    const { run } = batchRun([[9, 9], [3, 3]]);
    const events = await drain(gradeBatch(roster(4), RUBRIC, OLLAMA, { run, chunkSize: 2 }));
    const done = events.find((e) => e.type === 'done')!;
    expect(done.type === 'done' && done.results.map((r) => r.studentIndex)).toEqual([0, 1, 2, 3]);
  });

  it('carries calibration from the first chunk into the second', async () => {
    const { run, seen } = batchRun([[9, 2], [7, 7]]);
    await drain(gradeBatch(roster(4), RUBRIC, OLLAMA, { run, chunkSize: 2 }));

    expect(seen[0]).not.toContain('previously graded batch');
    expect(seen[1]).toContain('previously graded batch'); // bridge responses reached chunk 2
  });

  // One student's name must not reach the model via a classmate's response either.
  it('redacts every student in the chunk, not just the one being graded', async () => {
    const { run, seen } = batchRun([[8, 8]]);
    const students: Student[] = [
      { name: 'Nakamura, Yuki', responseText: 'my own answer' },
      { name: 'Okonkwo, Chidi', responseText: 'I worked with Yuki Nakamura on this' },
    ];
    await drain(gradeBatch(students, RUBRIC, OLLAMA, { run }));

    expect(seen[0]).not.toContain('Nakamura');
    expect(seen[0]).not.toContain('Yuki');
    expect(seen[0]).not.toContain('Okonkwo');
    expect(seen[0]).toContain('I worked with'); // the substance survives
  });

  it('rehydrates names into per-student feedback', async () => {
    const { run } = batchRun([[8]]);
    const events = await drain(
      gradeBatch([{ name: 'Nakamura, Yuki', responseText: 'a' }], RUBRIC, OLLAMA, { run }),
    );
    const done = events.find((e) => e.type === 'done')!;
    expect(done.type === 'done' && done.results[0]!.feedback).toContain('Hi Yuki,');
  });

  // The panel accumulates chunk-done events so a later failure does not discard grades
  // already paid for. That only works if the successful chunks are yielded BEFORE the
  // throw — if assertGraded fired before any yield, the UI would show nothing.
  it('yields the chunks that succeeded before a later one fails', async () => {
    let call = 0;
    const run = vi.fn(async () => {
      if (call++ === 0) {
        return JSON.stringify([
          { studentIndex: 0, score: 9, feedback: '<p>Hi [name],</p><p>ok</p>' },
          { studentIndex: 1, score: 7, feedback: '<p>Hi [name],</p><p>ok</p>' },
        ]);
      }
      return 'the model returned prose, not JSON';
    });

    const gen = gradeBatch(roster(4), RUBRIC, OLLAMA, { run, chunkSize: 2 });
    const events: GradingEvent[] = [];
    await expect(async () => {
      for await (const e of gen) events.push(e);
    }).rejects.toThrow(/chunk 1/i);

    const done = events.filter((e) => e.type === 'chunk-done');
    expect(done).toHaveLength(1);
    expect(done[0]!.type === 'chunk-done' && done[0]!.results.map((r) => r.score)).toEqual([9, 7]);
    // ...and no 'done' event, so the caller cannot mistake a partial run for a full one.
    expect(events.some((e) => e.type === 'done')).toBe(false);
  });

  // Stopping is `break` in a for-await, which calls gen.return(). Nothing may run after it.
  it('runs no further chunks once the consumer breaks out', async () => {
    const { run } = batchRun([[9, 9], [5, 5], [4, 4]]);
    const gen = gradeBatch(roster(6), RUBRIC, OLLAMA, { run, chunkSize: 2 });
    const collected: number[] = [];
    for await (const e of gen) {
      if (e.type === 'chunk-done') {
        collected.push(...e.results.map((r) => r.score));
        break;
      }
    }
    expect(run).toHaveBeenCalledTimes(1);
    expect(collected).toEqual([9, 9]);
  });

  it('grades an empty roster without calling the model', async () => {
    const { run } = batchRun([]);
    const events = await drain(gradeBatch([], RUBRIC, OLLAMA, { run }));
    expect(run).not.toHaveBeenCalled();
    const done = events.find((e) => e.type === 'done')!;
    expect(done.type === 'done' && done.results).toEqual([]);
  });
});

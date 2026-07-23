/**
 * gradeOne() — grade a single student's work.
 *
 * The FERPA-relevant path. Student work reaches a model only through
 * `model-gate.callModel`, which refuses to send anything whose known identifiers
 * haven't been tokenized and rehydrates the reply locally. The Redactor is built from
 * the identifiers we know for this student (name, and student id when we have one).
 *
 * Models are reached by spawning the claude/opencode CLI, not by calling a provider's
 * HTTP API — the CLI carries its own login, so no API key lives here. O.G.R.E's
 * providers.js is therefore not ported at all.
 */
import { invoke } from '@tauri-apps/api/core';
import { callModel } from '../../lib/model-gate';
import { Redactor } from '../../lib/redact';
import { cliModelArg, engineForProvider, extractCliText } from '../../lib/agent-cli';
import { buildSingleGradePrompt, parseSingleGradeResponse, type GradeResult, type Rubric } from './grading';
import {
  assertGraded,
  buildBatchPrompt,
  buildBridgeResponses,
  chunkStudents,
  generateScoringAnchors,
  mergeResults,
  parseBatchResponse,
  type BatchResult,
  type BatchStudent,
  type BridgeResponse,
} from './batch';

export interface Student {
  name: string;
  responseText: string;
  /** Gradebook id when the page exposes one — redacted alongside the name. */
  studentId?: string;
  /** The student's own question text, when each saw different randomized values. */
  prompt?: string;
}

export interface GradeProvider {
  /** provider_configs id. 'anthropic'/'claude' route to the claude CLI, else opencode. */
  id?: string;
  /** Model id for the CLI's --model flag. opencode takes `provider/model`. */
  model?: string;
}

/** Takes the redacted prompt, returns the model's raw text. Injected in tests. */
export type ModelRunner = (redactedPrompt: string) => Promise<string>;

/**
 * run_agent_cli defaults to a 180s timeout, which suits a browser-agent turn and is far
 * too short for grading — a local model spends ~35s per student, so even a 10-student
 * chunk blows through it and the batch dies part-graded. Scale the budget with the work
 * and floor it well above the default.
 */
export function gradingTimeoutSecs(studentCount: number): number {
  return Math.min(3600, Math.max(600, studentCount * 90));
}

/**
 * The app's real transport: spawn `claude`/`opencode` headlessly through Rust and reuse
 * the CLI's own login. No API key is stored or sent from here — same reason
 * agent-api.ts stopped POSTing at providers directly.
 *
 * Each grading call is a fresh session. Grading is one-shot, not conversational, and
 * resuming would let one student's work condition the next student's grade.
 */
function cliRunner(provider: GradeProvider, timeoutSecs: number): ModelRunner {
  return async (prompt) => {
    const engine = engineForProvider(provider.id);
    const stdout = await invoke<string>('run_agent_cli', {
      engine,
      prompt,
      sessionId: crypto.randomUUID(),
      resume: false,
      model: cliModelArg(engine, provider.model),
      systemPrompt: null,
      bypassPermissions: false,
      timeoutSecs,
    });
    return extractCliText(engine, stdout);
  };
}

/**
 * Every identifier we know for this student, as separate secrets.
 *
 * The full name alone is not enough. A gradebook shows "Nakamura, Yuki" while the
 * student signs their work "Yuki Nakamura" — the reversed form is a different string,
 * so it survives redaction AND passes assertOutbound (which can only look for secrets
 * it was given). Registering each part closes that.
 *
 * ponytail: parts shorter than 3 chars are deliberately skipped. Redactor does a
 * case-insensitive substring swap with no word boundaries, so a 2-letter surname would
 * shred the work being graded ("Li" turning "qualify" into "qua⟦S1⟧fy"). Such a name is
 * still covered by its full-name form. Give Redactor \b-anchored matching to fix this
 * properly.
 */
export function identifiersFor(student: Student): string[] {
  const out = [student.name, student.studentId ?? ''];
  for (const part of (student.name ?? '').split(/[\s,]+/)) {
    if (part.length >= 3) out.push(part);
  }
  return out.filter(Boolean);
}

export async function gradeOne(
  student: Student,
  rubric: Rubric,
  provider: GradeProvider,
  opts: { instructions?: string; run?: ModelRunner } = {},
): Promise<GradeResult> {
  const run = opts.run ?? cliRunner(provider, gradingTimeoutSecs(1));
  const maxScore = parseFloat(String(rubric.maxScore ?? 10)) || 10;

  const redactor = new Redactor(identifiersFor(student));

  // Build with the raw work, then redact the whole prompt — that way an identifier
  // appearing inside the student's own writing is caught too, not just the header.
  const prompt = buildSingleGradePrompt(rubric, student.responseText, opts.instructions);
  const payload = redactor.redact(prompt);

  const reply = await callModel(payload, redactor, run);

  // studentName is used only to rewrite the greeting — locally, after rehydration.
  return parseSingleGradeResponse(
    reply,
    maxScore,
    rubric.categoryWeights ?? null,
    rubric.categoryMaxPoints ?? null,
    student.name,
  );
}

/** Progress events so a UI can show grading as it happens rather than one long freeze. */
export type GradingEvent =
  | { type: 'chunk-start'; chunkIndex: number; chunkCount: number; students: number }
  | { type: 'chunk-done'; chunkIndex: number; results: BatchResult[] }
  | { type: 'done'; results: BatchResult[] };

/**
 * Grade a whole class. Students are graded together in one model context per chunk,
 * which is what keeps scores comparable between them; past the chunk size, bridge
 * responses carry the standard forward so chunk 2 doesn't drift from chunk 1.
 *
 * Yields progress as it goes. Every chunk redacts against the identifiers of every
 * student in that chunk, so one student's name can't leak via another's response.
 */
export async function* gradeBatch(
  students: Student[],
  rubric: Rubric,
  provider: GradeProvider,
  opts: { chunkSize?: number; run?: ModelRunner } = {},
): AsyncGenerator<GradingEvent, BatchResult[], void> {
  const chunkSize = opts.chunkSize ?? 20;
  const run = opts.run ?? cliRunner(provider, gradingTimeoutSecs(chunkSize));
  const maxScore = parseFloat(String(rubric.maxScore ?? 10)) || 10;
  const anchors = generateScoringAnchors(rubric);

  // Index by position here rather than trusting a caller-supplied index — the whole
  // result mapping keys off it.
  const indexed: BatchStudent[] = students.map((s, i) => ({
    index: i,
    name: s.name,
    response: s.responseText,
    prompt: s.prompt,
  }));

  const chunks = chunkStudents(indexed, chunkSize);
  const collected: BatchResult[][] = [];
  let bridge: BridgeResponse[] | null = null;

  for (const chunk of chunks) {
    yield { type: 'chunk-start', chunkIndex: chunk.chunkIndex, chunkCount: chunks.length, students: chunk.students.length };

    const original = chunk.students.map((bs) => students[bs.index]!);
    const redactor = new Redactor(original.flatMap(identifiersFor));
    const payload = redactor.redact(buildBatchPrompt(rubric, chunk.students, anchors, bridge));

    const reply = await callModel(payload, redactor, run);

    const results = parseBatchResponse(
      reply,
      chunk.students,
      maxScore,
      rubric.categoryWeights ?? null,
      rubric.categoryMaxPoints ?? null,
    );
    // Stop the run rather than let ungraded zeros flow onward into a gradebook.
    assertGraded(results, chunk.chunkIndex);

    collected.push(results);
    bridge = buildBridgeResponses(results, chunk.students, anchors);

    yield { type: 'chunk-done', chunkIndex: chunk.chunkIndex, results };
  }

  const merged = mergeResults(collected);
  yield { type: 'done', results: merged };
  return merged;
}

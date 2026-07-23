/**
 * gradeOne() — grade a single student's work.
 *
 * The FERPA-relevant path. Student work reaches a model only through
 * `model-gate.callModel`, which refuses to send anything whose known identifiers
 * haven't been tokenized and rehydrates the reply locally. The Redactor is built from
 * the identifiers we know for this student (name, and student id when we have one).
 *
 * Provider bodies reuse src/lib/model-providers.ts rather than porting O.G.R.E's
 * providers.js — that module already covers the Anthropic shape and the
 * OpenAI-compatible chat shape, which between them serve Ollama, OpenAI, Gemini's
 * compat endpoint, GitHub Models and RunPod.
 */
import { fetch as tauriFetch } from '@tauri-apps/plugin-http';
import { callModel } from '../../lib/model-gate';
import { Redactor } from '../../lib/redact';
import {
  buildAnthropicBody,
  buildChatBody,
  parseAnthropicResponse,
  parseChatResponse,
} from '../../lib/model-providers';
import { buildSingleGradePrompt, parseSingleGradeResponse, type GradeResult, type Rubric } from './grading';

export interface Student {
  name: string;
  responseText: string;
  /** Gradebook id when the page exposes one — redacted alongside the name. */
  studentId?: string;
}

export interface GradeProvider {
  /** 'anthropic' selects the Messages API shape; anything else uses chat-completions. */
  id: string;
  apiUrl: string;
  apiKey?: string | null;
  model: string;
}

/** Swappable for tests; the default posts through Tauri's HTTP plugin. */
export type HttpPost = (url: string, init: { headers: Record<string, string>; body: string }) => Promise<unknown>;

const defaultPost: HttpPost = async (url, init) => {
  const res = await tauriFetch(url, { method: 'POST', headers: init.headers, body: init.body });
  if (!res.ok) throw new Error(`Model request failed: ${res.status} ${res.statusText}`);
  return res.json();
};

function headersFor(provider: GradeProvider): Record<string, string> {
  const h: Record<string, string> = { 'Content-Type': 'application/json' };
  if (!provider.apiKey) return h;
  if (provider.id === 'anthropic') {
    h['x-api-key'] = provider.apiKey;
    h['anthropic-version'] = '2023-06-01';
  } else {
    h['Authorization'] = `Bearer ${provider.apiKey}`;
  }
  return h;
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
  opts: { instructions?: string; post?: HttpPost } = {},
): Promise<GradeResult> {
  const post = opts.post ?? defaultPost;
  const maxScore = parseFloat(String(rubric.maxScore ?? 10)) || 10;

  const redactor = new Redactor(identifiersFor(student));

  // Build with the raw work, then redact the whole prompt — that way an identifier
  // appearing inside the student's own writing is caught too, not just the header.
  const prompt = buildSingleGradePrompt(rubric, student.responseText, opts.instructions);
  const payload = redactor.redact(prompt);

  const isAnthropic = provider.id === 'anthropic';
  const reply = await callModel(payload, redactor, async (redactedText) => {
    const body = isAnthropic
      ? buildAnthropicBody({ messages: [{ role: 'user', content: redactedText }] }, provider.model)
      : buildChatBody({ messages: [{ role: 'user', content: redactedText }] }, provider.model);
    const json = await post(provider.apiUrl, { headers: headersFor(provider), body: JSON.stringify(body) });
    return (isAnthropic ? parseAnthropicResponse(json) : parseChatResponse(json)).content;
  });

  // studentName is used only to rewrite the greeting — locally, after rehydration.
  return parseSingleGradeResponse(
    reply,
    maxScore,
    rubric.categoryWeights ?? null,
    rubric.categoryMaxPoints ?? null,
    student.name,
  );
}

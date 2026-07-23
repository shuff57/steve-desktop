/**
 * gradeOne() — the FERPA-relevant path. These assert that no student identifier
 * reaches the network, that the reply comes back rehydrated, and that provider shape
 * selection is right.
 */
import { describe, expect, it, vi } from 'vitest';

vi.mock('@tauri-apps/plugin-http', () => ({ fetch: vi.fn() }));

import { gradeOne, identifiersFor, type GradeProvider, type Student } from './grade';
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

const OLLAMA: GradeProvider = { id: 'ollama', apiUrl: 'http://localhost:11434/v1/chat/completions', model: 'llama3' };
const ANTHROPIC: GradeProvider = {
  id: 'anthropic',
  apiUrl: 'https://api.anthropic.com/v1/messages',
  apiKey: 'sk-test',
  model: 'claude-opus-4-8',
};

/** Captures what would go over the wire and replies in the given provider's shape. */
function spyPost(reply: string, shape: 'chat' | 'anthropic' = 'chat') {
  const seen: { url: string; headers: Record<string, string>; body: string }[] = [];
  const post = vi.fn(async (url: string, init: { headers: Record<string, string>; body: string }) => {
    seen.push({ url, ...init });
    return shape === 'anthropic'
      ? { content: [{ type: 'text', text: reply }] }
      : { choices: [{ message: { content: reply } }] };
  });
  return { post, seen };
}

const OK_REPLY = '{"score": 8, "feedback": "<p>Hi Nakamura, Yuki,</p><p>Good work.</p>"}';

describe('redaction', () => {
  it('sends neither the student name nor id to the model', async () => {
    const { post, seen } = spyPost(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, OLLAMA, { post });

    const wire = seen[0]!.body;
    expect(wire).not.toContain('Nakamura');
    expect(wire).not.toContain('Yuki');
    expect(wire).not.toContain('S8842119');
  });

  it('still sends the actual work, with identifiers tokenized in place', async () => {
    const { post, seen } = spyPost(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, OLLAMA, { post });

    const wire = seen[0]!.body;
    expect(wire).toContain('residual is observed minus predicted');
    expect(wire).toContain('Interpret the residual.');
  });

  it('rehydrates the reply locally, so feedback names the real student', async () => {
    const { post } = spyPost(OK_REPLY);
    const out = await gradeOne(STUDENT, RUBRIC, OLLAMA, { post });
    // Greeting is normalized to first name only — proof it round-tripped through rehydrate.
    expect(out.feedback).toContain('<p>Hi Yuki,</p>');
  });

  it('grades a student with no id without tripping the gate', async () => {
    const { post } = spyPost(OK_REPLY);
    const out = await gradeOne({ name: 'Ada Lovelace', responseText: 'answer' }, RUBRIC, OLLAMA, { post });
    expect(out.score).toBe(8);
  });

  // Regression: the gradebook shows "Nakamura, Yuki" but students sign work "Yuki
  // Nakamura". Redacting only the full name let the reversed form through, and
  // assertOutbound could not catch it — it only knows the secrets it was handed.
  it('redacts a name written in the opposite order from the roster', async () => {
    const { post, seen } = spyPost(OK_REPLY);
    await gradeOne(
      { name: 'Nakamura, Yuki', responseText: 'Submitted by Yuki Nakamura on Tuesday.' },
      RUBRIC,
      OLLAMA,
      { post },
    );
    expect(seen[0]!.body).not.toContain('Yuki');
    expect(seen[0]!.body).not.toContain('Nakamura');
  });

  it('rehydrates tokens the model echoed back', async () => {
    // What a model really returns: it saw tokens, so it writes tokens.
    const { post } = spyPost('{"score": 8, "feedback": "<p>Hi ⟦S1⟧,</p><p>Nice.</p>"}');
    const out = await gradeOne(STUDENT, RUBRIC, OLLAMA, { post });
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

describe('provider shape', () => {
  it('uses chat-completions for non-anthropic providers', async () => {
    const { post, seen } = spyPost(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, OLLAMA, { post });

    const body = JSON.parse(seen[0]!.body);
    expect(body.model).toBe('llama3');
    expect(body.messages[0].role).toBe('user');
    expect(seen[0]!.url).toBe(OLLAMA.apiUrl);
    expect(seen[0]!.headers.Authorization).toBeUndefined(); // no key configured
  });

  it('uses the Messages API and its auth headers for anthropic', async () => {
    const { post, seen } = spyPost(OK_REPLY, 'anthropic');
    await gradeOne(STUDENT, RUBRIC, ANTHROPIC, { post });

    expect(seen[0]!.headers['x-api-key']).toBe('sk-test');
    expect(seen[0]!.headers['anthropic-version']).toBe('2023-06-01');
    expect(JSON.parse(seen[0]!.body).max_tokens).toBeGreaterThan(0);
  });

  it('sends a bearer token for keyed chat providers', async () => {
    const { post, seen } = spyPost(OK_REPLY);
    await gradeOne(STUDENT, RUBRIC, { ...OLLAMA, apiKey: 'sk-abc' }, { post });
    expect(seen[0]!.headers.Authorization).toBe('Bearer sk-abc');
  });
});

describe('result handling', () => {
  it('scales the score to the rubric max', async () => {
    const { post } = spyPost('{"score": 5, "feedback": "<p>x</p>"}');
    const out = await gradeOne(STUDENT, { ...RUBRIC, maxScore: 4 }, OLLAMA, { post });
    expect(out.score).toBe(2);
  });

  it('surfaces an unparseable reply instead of silently scoring 0 as if graded', async () => {
    const { post } = spyPost('I cannot grade this.');
    const out = await gradeOne(STUDENT, RUBRIC, OLLAMA, { post });
    expect(out.score).toBe(0);
    expect(out.feedback).toMatch(/error parsing/i);
  });

  it('propagates a transport failure rather than reporting a 0 grade', async () => {
    const post = vi.fn().mockRejectedValue(new Error('Model request failed: 401 Unauthorized'));
    await expect(gradeOne(STUDENT, RUBRIC, OLLAMA, { post })).rejects.toThrow(/401/);
  });
});

import { describe, it, expect } from 'vitest';
import {
  buildAuthorPrompt,
  buildRepairPrompt,
  sectionCommand,
  shouldRetry,
  questionPath,
  MAX_ATTEMPTS,
} from './author';

const req = {
  section: '1.1_definitions_of_statistics_probability_and_key_terms.html',
  family: 'descriptive-stats',
  slug: 'q1-key-terms',
  targetPath: 'C:/mom-content/questions/descriptive-stats/q1-key-terms.php',
};

describe('sectionCommand', () => {
  it('escapes the spaces in the project directory', () => {
    expect(sectionCommand(req.section)).toContain('projects/Introduction%20to%20Stats/html/');
  });

  it('reads through gh, because the book repo is private', () => {
    const cmd = sectionCommand(req.section);
    expect(cmd).toContain('gh api');
    expect(cmd).toContain('base64 -d'); // contents API returns base64
  });
});

describe('buildAuthorPrompt', () => {
  it('names the target file as both the task and a prohibition', () => {
    const p = buildAuthorPrompt(req);
    expect(p).toContain(`save it to: ${req.targetPath}`);
    expect(p).toContain(`Write ONLY ${req.targetPath}`);
  });

  it('tells the agent to read the section BEFORE writing', () => {
    expect(buildAuthorPrompt(req)).toMatch(/Read the section before writing/i);
  });

  it('asks for one question, not a set', () => {
    expect(buildAuthorPrompt(req)).toMatch(/One question, not a set/i);
  });

  /** Each of these cost a real broken question in the bank to learn. */
  it('carries the dialect traps that were found by repairing the bank', () => {
    const p = buildAuthorPrompt(req);
    expect(p).toContain('ANSWER section is CODE');
    expect(p).toContain('Braces EVALUATE');
    expect(p).toContain('`&&` and `||`');
    expect(p).toMatch(/NO trailing semicolon/i);
  });

  it('lists the section markers the parser requires', () => {
    const p = buildAuthorPrompt(req);
    for (const m of ['COMMON CONTROL', 'QUESTION TEXT', '=== ANSWER ===']) expect(p).toContain(m);
  });
});

describe('buildRepairPrompt', () => {
  it('quotes the sandbox errors verbatim, keeping their line numbers', () => {
    const err = 'Caught error while evaluating the code in this question: syntax error on line 94 of Common Control';
    expect(buildRepairPrompt(req.targetPath, [err], 2)).toContain(err);
  });

  it('shows which attempt this is, so the agent knows the budget', () => {
    expect(buildRepairPrompt(req.targetPath, ['x'], 2)).toContain(`attempt 2 of ${MAX_ATTEMPTS}`);
  });

  it('tells the agent to re-read from disk rather than trust its own memory', () => {
    expect(buildRepairPrompt(req.targetPath, ['x'], 2)).toMatch(/Re-read it from disk/i);
  });
});

describe('shouldRetry', () => {
  const fail = (n: number) => ({ attempt: n, errors: ['boom'], ok: false });

  it('stops as soon as an attempt renders clean', () => {
    expect(shouldRetry([fail(1), { attempt: 2, errors: [], ok: true }])).toBe(false);
  });

  it('retries while under the cap', () => {
    expect(shouldRetry([fail(1)])).toBe(true);
    expect(shouldRetry([fail(1), fail(2)])).toBe(true);
  });

  /** Past the cap the retries cost more than they fix — hand it to a human instead. */
  it('gives up at the cap instead of burning turns', () => {
    expect(shouldRetry([fail(1), fail(2), fail(3)])).toBe(false);
  });

  it('does not retry before anything has run', () => {
    expect(shouldRetry([])).toBe(false);
  });
});

describe('questionPath', () => {
  it('builds the bank path from family and slug', () => {
    expect(questionPath('C:/mom-content', 'descriptive-stats', 'q1-key-terms'))
      .toBe('C:/mom-content/questions/descriptive-stats/q1-key-terms.php');
  });

  it('tolerates a trailing separator and a typed .php', () => {
    expect(questionPath('C:/mom-content/', 'stats', 'q1.php')).toBe('C:/mom-content/questions/stats/q1.php');
  });
});

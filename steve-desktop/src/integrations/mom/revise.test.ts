import { readFileSync } from 'node:fs';
import { describe, it, expect } from 'vitest';
import { buildRevisePrompt, buildFollowUpPrompt, MOM_DIALECT_RULES } from './revise';

const REQ = {
  path: 'C:/repo/mom-content/questions/finance/simple-interest/q3.php',
  label: 'finance/simple-interest/q3.php',
  instruction: '  Reword part C, it reads awkwardly  ',
  contents: '// === COMMON CONTROL ===\n$P = 1200\n',
};

describe('buildRevisePrompt', () => {
  it('names the exact file to edit', () => {
    const p = buildRevisePrompt(REQ);
    expect(p).toContain(REQ.path);
    expect(p).toContain('finance/simple-interest/q3.php');
  });

  it('passes the instruction through verbatim, trimmed', () => {
    // Paraphrasing the teacher's words is how a revision quietly becomes a rewrite.
    expect(buildRevisePrompt(REQ)).toContain('Reword part C, it reads awkwardly');
  });

  it('includes the current contents so the agent does not guess', () => {
    expect(buildRevisePrompt(REQ)).toContain('$P = 1200');
  });

  it('states the dialect rules that cause silent MOM failures', () => {
    const p = buildRevisePrompt(REQ);
    for (const rule of MOM_DIALECT_RULES) expect(p).toContain(rule);
  });

  it('carries the substitution trap that a live render already caught', () => {
    // Emitting $questions[0][$answer[0]] in the body renders the literal "Array[1]".
    expect(buildRevisePrompt(REQ)).toContain('Array[1]');
  });

  it('constrains scope to the single file', () => {
    const p = buildRevisePrompt(REQ);
    expect(p).toContain('Edit ONLY that one file');
    expect(p).toContain('do not create new ones');
  });

  it('asks for a one-line summary rather than a code dump', () => {
    expect(buildRevisePrompt(REQ)).toContain('ONE short line');
  });
});

describe('buildFollowUpPrompt', () => {
  it('passes the instruction through verbatim, trimmed', () => {
    expect(buildFollowUpPrompt('  now make part C harder  ')).toContain('now make part C harder');
  });

  it('tells the agent to re-read, because the file is now its own last edit', () => {
    expect(buildFollowUpPrompt('again')).toContain('Re-read it from disk');
  });

  it('does not restate the rules or the contents — the resumed session already has them', () => {
    // Resending them is what makes a follow-up read as a fresh, contradictory request.
    const p = buildFollowUpPrompt('again');
    for (const rule of MOM_DIALECT_RULES) expect(p).not.toContain(rule);
    expect(p).not.toContain('Current contents:');
    expect(p.length).toBeLessThan(buildRevisePrompt(REQ).length);
  });
});

describe('dialect-rules.md stays in step with the code', () => {
  /**
   * Two copies of the rules exist on purpose: the array steers the prompts (pure, no file I/O in a
   * prompt builder), and the markdown is what a human or an agent reads. Two copies drift, so this
   * is the thing that stops them — edit one without the other and this fails by name.
   */
  const doc = readFileSync(
    new URL('../../../mom-content/reference/dialect-rules.md', import.meta.url),
    'utf8',
  );
  const documented = doc
    .split('\n')
    .map((l) => l.trim())
    .filter((l) => l.startsWith('- '))
    .map((l) => l.slice(2));

  it('documents every rule the prompts enforce', () => {
    expect(documented).toEqual(MOM_DIALECT_RULES);
  });

  it('points at the learned-rules file rather than mixing the two', () => {
    // Hand-written rules are reviewed; learned ones are machine-appended. Keeping them in separate
    // files is what makes a bad learned rule easy to spot and delete.
    expect(doc).toContain('learned-rules.md');
  });
});

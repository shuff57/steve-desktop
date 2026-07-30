import { describe, it, expect } from 'vitest';
import { buildRevisePrompt, MOM_DIALECT_RULES } from './revise';

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

import { describe, it, expect } from 'vitest';
import {
  parseLearnedRules,
  renderLearnedRules,
  mergeLearnedRules,
  hasLessons,
  buildReflectPrompt,
  parseProposedRules,
  MAX_LEARNED_RULES,
} from './reflect';
import { rulesBlock, buildAuthorPrompt, buildRepairPrompt } from './author';
import { MOM_DIALECT_RULES } from './revise';

const RULE_A = 'Never call number_format on money; use round($v, 2) so the answer key agrees.';
const RULE_B = 'Declare $anstypes explicitly even when the type header already names it.';

describe('learned-rules file round trip', () => {
  it('survives a write/read cycle', () => {
    expect(parseLearnedRules(renderLearnedRules([RULE_A, RULE_B]))).toEqual([RULE_A, RULE_B]);
  });

  it('ignores the prose around the bullets', () => {
    expect(parseLearnedRules(renderLearnedRules([RULE_A]))).toEqual([RULE_A]);
  });

  it('reads an empty or missing file as no rules, not as a crash', () => {
    // A first run legitimately has no file at all.
    expect(parseLearnedRules('')).toEqual([]);
  });
});

describe('mergeLearnedRules', () => {
  it('adds a genuinely new rule', () => {
    const { rules, added } = mergeLearnedRules([RULE_A], [RULE_B]);
    expect(added).toEqual([RULE_B]);
    expect(rules).toEqual([RULE_A, RULE_B]);
  });

  it('never edits or drops an existing rule', () => {
    // The loop may add to its own instructions; it must not be able to erase one.
    const { rules } = mergeLearnedRules([RULE_A, RULE_B], ['something else entirely about matrices']);
    expect(rules.slice(0, 2)).toEqual([RULE_A, RULE_B]);
  });

  it('rejects a duplicate that has merely been reworded', () => {
    const reworded = RULE_A.toUpperCase().replace(/;/g, ' --');
    const { added, rejected } = mergeLearnedRules([RULE_A], [reworded]);
    expect(added).toEqual([]);
    expect(rejected).toHaveLength(1);
  });

  it('refuses a one-word rule and a whole paragraph', () => {
    const { added } = mergeLearnedRules([], ['semicolons', 'x'.repeat(500)]);
    expect(added).toEqual([]);
  });

  it('stops at the cap instead of growing the prompt forever', () => {
    // Every learned rule costs tokens on every future run, so the ceiling is the point.
    const full = Array.from({ length: MAX_LEARNED_RULES }, (_, i) => `Learned rule number ${i} about a distinct trap.`);
    const { rules, added, rejected } = mergeLearnedRules(full, ['A brand new rule about something else entirely.']);
    expect(rules).toHaveLength(MAX_LEARNED_RULES);
    expect(added).toEqual([]);
    expect(rejected).toHaveLength(1);
  });

  it('strips a leading bullet the model added itself', () => {
    const { added } = mergeLearnedRules([], ['- Precompute scalars before substituting them into text.']);
    expect(added[0]).toBe('Precompute scalars before substituting them into text.');
  });
});

describe('hasLessons', () => {
  it('is false when the first render was clean — that run taught nothing', () => {
    expect(hasLessons([{ attempt: 1, errors: [], ok: true }])).toBe(false);
  });

  it('is true once anything actually failed', () => {
    expect(
      hasLessons([
        { attempt: 1, errors: ['syntax error on line 9 of Common Control'], ok: false },
        { attempt: 2, errors: [], ok: true },
      ]),
    ).toBe(true);
  });

  it('is false for an empty run', () => {
    expect(hasLessons([])).toBe(false);
  });
});

describe('buildReflectPrompt', () => {
  const attempts = [
    { attempt: 1, errors: ['syntax error on line 12 of Common Control'], ok: false },
    { attempt: 2, errors: [], ok: true },
  ];

  it('quotes the sandbox errors it is reasoning about', () => {
    const p = buildReflectPrompt({ attempts, existing: [], handWritten: MOM_DIALECT_RULES, targetPath: 'C:/q.php' });
    expect(p).toContain('syntax error on line 12 of Common Control');
  });

  it('shows the rules already in force so it cannot restate one', () => {
    const p = buildReflectPrompt({ attempts, existing: [RULE_A], handWritten: MOM_DIALECT_RULES, targetPath: 'C:/q.php' });
    for (const r of MOM_DIALECT_RULES) expect(p).toContain(r);
    expect(p).toContain(RULE_A);
  });

  it('makes returning nothing the explicitly correct answer', () => {
    // Without this the model always finds something to say and the file fills with restatements.
    const p = buildReflectPrompt({ attempts, existing: [], handWritten: MOM_DIALECT_RULES, targetPath: 'C:/q.php' });
    expect(p).toMatch(/Return \[\]/);
    expect(p).toMatch(/RIGHT answer most of the time/i);
  });
});

describe('parseProposedRules', () => {
  it('reads a bare JSON array', () => {
    expect(parseProposedRules(`["${RULE_A}"]`)).toEqual([RULE_A]);
  });

  it('tolerates a preamble and a code fence', () => {
    expect(parseProposedRules('Here you go:\n```json\n["' + RULE_A + '"]\n```')).toEqual([RULE_A]);
  });

  it('reads an empty array as "nothing to learn"', () => {
    expect(parseProposedRules('[]')).toEqual([]);
  });

  it('returns nothing rather than throwing on junk', () => {
    // A reflection that cannot be parsed must not fail a question that already renders.
    expect(parseProposedRules('I could not determine a rule.')).toEqual([]);
    expect(parseProposedRules('[not json')).toEqual([]);
  });

  it('drops non-string entries', () => {
    expect(parseProposedRules('["ok rule", 42, null]')).toEqual(['ok rule']);
  });
});

describe('learned rules actually reach the prompts', () => {
  // The whole point of the loop: a rule learned once must steer every later run, or the file is
  // just a diary. These are the assertions that prove the feedback loop is closed.
  const req = {
    family: 'descriptive-stats',
    slug: 'q1',
    targetPath: 'C:/mom-content/questions/descriptive-stats/q1.php',
  };

  it('rulesBlock carries both sets, labelling what was learned', () => {
    const block = rulesBlock([RULE_A]).join('\n');
    expect(block).toContain(MOM_DIALECT_RULES[0]);
    expect(block).toContain(RULE_A);
    expect(block).toContain('Learned from earlier runs');
  });

  it('omits the learned section entirely when nothing has been learned', () => {
    expect(rulesBlock([]).join('\n')).not.toContain('Learned from earlier runs');
    expect(rulesBlock(undefined).join('\n')).not.toContain('Learned from earlier runs');
  });

  it('the WRITE prompt carries them, so the next question avoids the trap', () => {
    expect(buildAuthorPrompt({ ...req, brief: 'x', learned: [RULE_A] })).toContain(RULE_A);
  });

  it('the REPAIR prompt carries them, which is where the guessing happens', () => {
    expect(buildRepairPrompt(req.targetPath, ['boom'], 2, 'C:/mom-content', [RULE_A])).toContain(RULE_A);
  });

  it('the repair prompt sends the agent to the docs rather than guessing again', () => {
    const p = buildRepairPrompt(req.targetPath, ['boom'], 2, 'C:/mom-content');
    expect(p).toMatch(/LOOK IT UP/);
    expect(p).toContain('C:/mom-content/reference/index.md');
  });
});

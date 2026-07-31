import { describe, it, expect } from 'vitest';
import {
  buildAuthorPrompt,
  buildSetPlanPrompt,
  buildRepairPrompt,
  parseSetPlan,
  topLevelArrays,
  sectionCommand,
  shouldRetry,
  hasSource,
  isUrl,
  questionPath,
  questionRefFromPath,
  questionKey,
  questionTitle,
  MAX_ATTEMPTS,
  MAX_REPAIRS,
  REFERENCE_INDEX,
} from './author';

const req = {
  link: '1.1_definitions_of_statistics_probability_and_key_terms.html',
  family: 'descriptive-stats',
  slug: 'q1-key-terms',
  targetPath: 'C:/mom-content/questions/descriptive-stats/q1-key-terms.php',
};

describe('sectionCommand', () => {
  it('escapes the spaces in the project directory', () => {
    expect(sectionCommand(req.link)).toContain('projects/Introduction%20to%20Stats/html/');
  });

  it('reads through gh, because the book repo is private', () => {
    const cmd = sectionCommand(req.link);
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

  it('points at the authoring reference when the root is known', () => {
    const p = buildAuthorPrompt({ ...req, root: 'C:/mom-content' });
    expect(p).toContain(`C:/mom-content/${REFERENCE_INDEX}`);
  });

  it('does not leave a double slash when the root has a trailing separator', () => {
    // The path is handed to the agent as something to open; a broken one just wastes a turn.
    expect(buildAuthorPrompt({ ...req, root: 'C:/mom-content/' })).toContain(
      `C:/mom-content/${REFERENCE_INDEX}`,
    );
  });

  it('omits the reference block entirely when there is no root', () => {
    expect(buildAuthorPrompt(req)).not.toContain('REFERENCE');
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

  /**
   * The agreed budget: the initial write is judged by one render, and five repair rounds follow —
   * six renders in total. Spelled out rather than derived, so silently changing the constant fails
   * here instead of quietly costing (or wasting) turns on every question.
   */
  it('allows the write plus exactly five repair rounds', () => {
    expect(MAX_REPAIRS).toBe(5);
    expect(MAX_ATTEMPTS).toBe(6);
    const failures = Array.from({ length: MAX_ATTEMPTS }, (_, i) => fail(i + 1));
    // Still retrying right up to the last render...
    expect(shouldRetry(failures.slice(0, MAX_ATTEMPTS - 1))).toBe(true);
    // ...and done after it. Past the cap the retries cost more than they fix.
    expect(shouldRetry(failures)).toBe(false);
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

describe('sources', () => {
  it('requires at least one source — otherwise there is nothing to write about', () => {
    expect(hasSource({})).toBe(false);
    expect(hasSource({ link: '  ' })).toBe(false);
    expect(hasSource({ brief: 'a CI question' })).toBe(true);
    expect(hasSource({ imagePath: 'C:/shot.png' })).toBe(true);
  });

  it('omits the section block entirely when no section is given', () => {
    const p = buildAuthorPrompt({ ...req, link: undefined, brief: 'ask about sampling bias' });
    expect(p).not.toContain('gh api');
    expect(p).toContain('ask about sampling bias');
  });

  it('tells the agent to open a supplied example image', () => {
    const p = buildAuthorPrompt({ ...req, link: undefined, imagePath: 'C:/shot.png' });
    expect(p).toContain('C:/shot.png');
    expect(p).toMatch(/open this image/i);
  });

  /** Copying an example verbatim would ship one fixed question to every student. */
  it('requires an imitated example to be randomized, not copied', () => {
    const p = buildAuthorPrompt({ ...req, imagePath: 'C:/shot.png' });
    expect(p).toMatch(/Do not copy its wording or/i);
    expect(p).toMatch(/randomize the values/i);
  });

  it('combines all three sources when all are given', () => {
    const p = buildAuthorPrompt({ ...req, brief: 'make it two-part', imagePath: 'C:/shot.png' });
    expect(p).toContain('gh api');
    expect(p).toContain('make it two-part');
    expect(p).toContain('C:/shot.png');
  });
});

describe('problem-set link', () => {
  it('tells a URL apart from a bookSHelf section file name', () => {
    expect(isUrl('https://example.com/set-3')).toBe(true);
    expect(isUrl('  http://example.com  ')).toBe(true);
    expect(isUrl('1.1_definitions.html')).toBe(false);
  });

  it('sends the agent to the URL, not to gh, when given a link', () => {
    const p = buildAuthorPrompt({ ...req, link: 'https://example.com/problem-set-3' });
    expect(p).toContain('https://example.com/problem-set-3');
    expect(p).not.toContain('gh api');
  });

  /** Mirroring a problem set verbatim would ship one fixed question to every student. */
  it('requires a NEW question when working from an existing set', () => {
    const p = buildAuthorPrompt({ ...req, link: 'https://example.com/set' });
    expect(p).toMatch(/write a NEW question/i);
    expect(p).toMatch(/randomize the values/i);
  });

  it('still uses gh for a bare section file name', () => {
    expect(buildAuthorPrompt({ ...req, link: '1.1_defs.html' })).toContain('gh api');
  });

  it('carries a planned brief into the per-question author prompt', () => {
    const p = buildAuthorPrompt({ ...req, brief: 'Ask about population vs sample' });
    expect(p).toContain('Ask about population vs sample');
  });
});

describe('buildSetPlanPrompt', () => {
  it('asks for a JSON array only and forbids writing files this turn', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family, root: 'C:/mom-content' });
    expect(p).toMatch(/ONLY a JSON array/i);
    expect(p).toMatch(/Do NOT write any question files in this turn/i);
  });

  it('tells the agent to read the section before planning', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family });
    expect(p).toContain(sectionCommand(req.link));
    expect(p).toMatch(/read this bookSHelf section first/i);
  });

  it('points at the authoring reference and the relevant sub-pages', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family, root: 'C:/mom-content' });
    expect(p).toContain(`C:/mom-content/${REFERENCE_INDEX}`);
    expect(p).toMatch(/question-types\//);
    expect(p).toMatch(/macros\//);
    expect(p).toContain('syntax.md');
  });

  it('defaults questions to fill-in-the-blank with randomized values', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family, root: 'C:/mom-content' });
    expect(p).toMatch(/fill-in-the-blank/i);
    expect(p).toMatch(/Randomize/i);
  });

  it('tells the planner to map items to actual source exercises, not invent topics', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family, root: 'C:/mom-content' });
    expect(p).toMatch(/Use ONLY the section's numbered problem set/i);
    expect(p).toMatch(/that EXACT number of questions/i);
    expect(p).toMatch(/Do not invent extra definition/i);
  });

  it('switches to invention wording when mode is invent', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family, root: 'C:/mom-content', mode: 'invent' });
    expect(p).toMatch(/Invent new questions/i);
    expect(p).not.toMatch(/Use ONLY the section's numbered problem set/i);
  });

  it('does not point at the reference when no root is given', () => {
    const p = buildSetPlanPrompt({ link: req.link, family: req.family });
    expect(p).not.toContain(REFERENCE_INDEX);
  });
});

describe('parseSetPlan', () => {
  it('extracts the array from prose-wrapped output', () => {
    const reply = 'Here is the plan:\n```json\n[{"slug":"q1","brief":"one"}]\n```';
    expect(parseSetPlan(reply)).toEqual([{ slug: 'q1', brief: 'one' }]);
  });

  it('uses the last array when the agent emits a preview plus a final array', () => {
    const reply = 'Preview: [{"slug":"bad","brief":"placeholder"}] below.\n\n[{"slug":"q1","brief":"real"}] returned as clean JSON only.';
    expect(parseSetPlan(reply)).toEqual([{ slug: 'q1', brief: 'real' }]);
  });

  it('filters entries missing a slug or a brief', () => {
    const reply = JSON.stringify([
      { slug: 'q1', brief: 'good' },
      { slug: 'q2' },
      { brief: 'missing slug' },
      { slug: '', brief: 'empty slug' },
      { slug: 'q3', brief: '' },
    ]);
    expect(parseSetPlan(reply)).toEqual([{ slug: 'q1', brief: 'good' }]);
  });

  it('dedupes repeated slugs so one file is not silently overwritten twice', () => {
    const reply = JSON.stringify([
      { slug: 'q1', brief: 'first' },
      { slug: 'q1', brief: 'second' },
    ]);
    expect(parseSetPlan(reply)).toEqual([{ slug: 'q1', brief: 'first' }]);
  });

  it('throws when the reply is not a JSON array', () => {
    expect(() => parseSetPlan('just prose')).toThrow(/JSON array/i);
  });

  it('throws when the plan has no usable questions', () => {
    expect(() => parseSetPlan('[{"slug":"","brief":""}]')).toThrow(/no usable questions/i);
  });

  it('strips a .php extension from planned slugs', () => {
    const reply = '[{"slug":"q1.php","brief":"one"}]';
    expect(parseSetPlan(reply)).toEqual([{ slug: 'q1', brief: 'one' }]);
  });
});

describe('topLevelArrays / parseSetPlan bracket handling', () => {
  /**
   * The real failure: an 11-question plan came back with SEVEN bracket pairs, because the briefs
   * describe ranges and options in prose. A non-greedy regex took the last inner fragment and the
   * whole plan was thrown away as "not valid JSON".
   */
  const REAL_SHAPE =
    '[{"slug": "q01-fitness-center-key-terms", "brief": "Randomize the reported mean [between 2.0 and 6.5] days."},' +
    ' {"slug": "q02-ski-lesson-key-terms", "brief": "Options: parameter, data, statistic [correct], variable."}]';

  it('finds one array even when the briefs contain brackets', () => {
    expect(topLevelArrays(REAL_SHAPE)).toHaveLength(1);
  });

  it('parses the plan that the old regex rejected', () => {
    const plan = parseSetPlan(REAL_SHAPE);
    expect(plan.map((p) => p.slug)).toEqual(['q01-fitness-center-key-terms', 'q02-ski-lesson-key-terms']);
  });

  it('ignores brackets inside strings when counting depth', () => {
    expect(topLevelArrays('["a ] not a close", "b [ not an open"]')).toHaveLength(1);
  });

  it('takes the FINAL plan when the agent previews one first', () => {
    const reply =
      'Draft:\n[{"slug": "draft-q", "brief": "a first pass that should be ignored entirely"}]\n' +
      'Final:\n[{"slug": "real-q", "brief": "the answer that actually counts for this run"}]';
    expect(parseSetPlan(reply).map((p) => p.slug)).toEqual(['real-q']);
  });

  it('skips a trailing non-plan array rather than failing on it', () => {
    const reply = '[{"slug": "real-q", "brief": "the plan itself, long enough to survive"}]\nNote: see [1].';
    expect(parseSetPlan(reply).map((p) => p.slug)).toEqual(['real-q']);
  });

  it('still refuses a reply with no array at all', () => {
    expect(() => parseSetPlan('I could not read the section.')).toThrow(/did not return a JSON array/);
  });

  it('handles a fenced array', () => {
    const reply = '```json\n[{"slug": "q1-abc", "brief": "a brief that is comfortably long enough"}]\n```';
    expect(parseSetPlan(reply).map((p) => p.slug)).toEqual(['q1-abc']);
  });
});

describe('questionRefFromPath', () => {
  const ROOT = String.raw`C:\Users\me\GitHub\steve-desktop\steve-desktop\mom-content`;

  it('keeps the .php on the slug, because that is what every reader expects', () => {
    // Dropping it made the writer fail to open the question it had just written.
    const ref = questionRefFromPath(questionPath(ROOT, 'descriptive-stats', 'q02-ski-lesson-age-key-terms'));
    expect(ref).toEqual({ family: 'descriptive-stats', slug: 'q02-ski-lesson-age-key-terms.php' });
  });

  it('round-trips whatever questionPath builds', () => {
    for (const [family, slug] of [
      ['descriptive-stats', 'q1-key-terms'],
      ['linear-programming', 'q10-classify-x'],
    ] as const) {
      expect(questionRefFromPath(questionPath(ROOT, family, slug))).toEqual({ family, slug: `${slug}.php` });
    }
  });

  it('reads a path spelled with either separator', () => {
    const back = questionRefFromPath(String.raw`C:\mom-content\questions\finance\q3.php`);
    const fwd = questionRefFromPath('C:/mom-content/questions/finance/q3.php');
    expect(back).toEqual({ family: 'finance', slug: 'q3.php' });
    expect(fwd).toEqual(back);
  });

  it('accepts a repo-relative path, not just an absolute one', () => {
    expect(questionRefFromPath('questions/finance/q3.php')).toEqual({ family: 'finance', slug: 'q3.php' });
  });

  it('returns null for anything that is not a question file', () => {
    expect(questionRefFromPath('C:/mom-content/books/intro/hw/1-1.json')).toBeNull();
    expect(questionRefFromPath('questions/finance/q3.txt')).toBeNull();
    expect(questionRefFromPath('')).toBeNull();
  });
});

describe('questionKey', () => {
  it('gives one key for the same file spelled two ways', () => {
    // The writer joins with '/' onto a '\' root; the reader returns the OS spelling. If these
    // disagree, one question's conversation splits into two threads.
    const written = questionPath(String.raw`C:\repo\mom-content`, 'descriptive-stats', 'q02-ski');
    const read = String.raw`c:\repo\mom-content\questions\descriptive-stats\Q02-SKI.php`;
    expect(questionKey(written)).toBe(questionKey(read));
  });

  it('keeps different questions apart', () => {
    expect(questionKey('questions/finance/q1.php')).not.toBe(questionKey('questions/finance/q2.php'));
    expect(questionKey('questions/finance/q1.php')).not.toBe(questionKey('questions/draw/q1.php'));
  });

  it('falls back to the normalised path when it is not a question file', () => {
    expect(questionKey(String.raw`C:\odd\place.txt`)).toBe('c:/odd/place.txt');
  });
});

describe('questionTitle', () => {
  const file = (name: string) => `// === NAME - DESCRIPTION: ${name} ===\n// === SET QUESTION TYPE TO: matching ===\n`;

  it('reads the name the question states about itself', () => {
    expect(questionTitle(file('Ski Lesson Age Key Terms - Match the six key terms'))).toBe(
      'Ski Lesson Age Key Terms - Match the six key terms',
    );
  });

  it('collapses a name that wrapped across lines', () => {
    const wrapped = '// === NAME - DESCRIPTION: Approval Poll Key Terms\n//     (Proportion) ===\n';
    expect(questionTitle(wrapped)).toBe('Approval Poll Key Terms // (Proportion)');
  });

  it('returns null when there is no usable name, so the caller can fall back to the slug', () => {
    expect(questionTitle('// === SET QUESTION TYPE TO: matching ===')).toBeNull();
    expect(questionTitle('// === NAME - DESCRIPTION:  ===')).toBeNull();
    expect(questionTitle('')).toBeNull();
  });
});

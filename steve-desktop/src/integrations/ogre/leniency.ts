/**
 * Rubric leniency — the slider that makes a rubric softer or harsher.
 *
 * Ported from `ogre-desktop/src/lib/rubric-leniency.ts`. This is the rule-based path:
 * deterministic, instant, and free. O.G.R.E also had an AI rewrite that round-tripped the
 * rubric through a model; that is deliberately not ported yet — grading calls now cost
 * plan tokens, and a slider that silently spends them on every drag is the wrong default.
 * `rewriteRubric` is the seam where an AI variant would slot in.
 *
 * 50 is centre and returns the rubric untouched. Distance from centre sets intensity,
 * which selects how many substitutions apply — so 60 is a light touch and 100 rewrites
 * with the whole table.
 */

interface Substitution {
  pattern: RegExp;
  replacement: string;
}

/**
 * Applied below 50 — soften what a criterion demands.
 *
 * Order matters and differs from O.G.R.E here: each capitalized pattern is listed BEFORE
 * its /gi twin. O.G.R.E had them the other way round, so the case-insensitive rule fired
 * first and rewrote a sentence-initial "Explains" to lowercase "rigorously demonstrates",
 * leaving the capitalized rule dead. Same substitutions, correct capitalization.
 */
const LENIENT_SUBS: Substitution[] = [
  { pattern: /\bAND\b/g, replacement: 'OR' },
  { pattern: /\bClearly states\b/gi, replacement: 'Mentions' },
  { pattern: /\bPrecisely (states|defines|describes)\b/gi, replacement: 'References' },
  { pattern: /\bexplicitly\b/gi, replacement: '' },
  { pattern: /\bcorrect formula\b/gi, replacement: 'relevant formula or concept' },
  { pattern: /\bCorrect formula\b/g, replacement: 'Relevant formula or concept' },
  { pattern: /\bcomprehensive\b/gi, replacement: 'adequate' },
  { pattern: /\ball key concepts\b/gi, replacement: 'most key concepts' },
  { pattern: /\bwith specific\b/gi, replacement: 'with some' },
  { pattern: /\brigorously\b/gi, replacement: '' },
  { pattern: /\bdemonstrates mastery\b/gi, replacement: 'shows familiarity' },
  { pattern: /\bexact\b/gi, replacement: 'approximate' },
  { pattern: /\bprecise\b/gi, replacement: 'reasonable' },
  { pattern: /\bmust include\b/gi, replacement: 'may include' },
  { pattern: /\brequires\b/gi, replacement: 'benefits from' },
  { pattern: /\bIdentifies\b/g, replacement: 'Recognizes' },
  { pattern: /\bidentifies\b/gi, replacement: 'recognizes' },
  { pattern: /\bshows understanding\b/gi, replacement: 'shows awareness' },
  { pattern: /\bdemonstrates understanding\b/gi, replacement: 'shows awareness' },
  { pattern: /\baccurately\b/gi, replacement: 'generally' },
  { pattern: /\bcorrectly\b/gi, replacement: 'reasonably' },
  { pattern: /\bcorrect\b/gi, replacement: 'reasonable' },
  { pattern: /\bappropriate\b/gi, replacement: 'relevant' },
  { pattern: /\bcomplete\b/gi, replacement: 'partial' },
  { pattern: /\bthorough\b/gi, replacement: 'basic' },
];

/** Applied above 50 — tighten what a criterion demands. Same capitalization-order fix. */
const STRICT_SUBS: Substitution[] = [
  { pattern: /\bOR\b/g, replacement: 'AND' },
  { pattern: /\bMentions\b/g, replacement: 'Precisely states' },
  { pattern: /\bmentions\b/g, replacement: 'precisely states' },
  { pattern: /\bExplains\b/g, replacement: 'Rigorously demonstrates' },
  { pattern: /\bexplains\b/gi, replacement: 'rigorously demonstrates' },
  { pattern: /\breferences\b/gi, replacement: 'explicitly cites with notation' },
  { pattern: /\bsome\b/gi, replacement: 'all' },
  { pattern: /\bshows understanding\b/gi, replacement: 'demonstrates mastery' },
  { pattern: /\bDiscusses\b/g, replacement: 'Formally analyzes' },
  { pattern: /\bdiscusses\b/gi, replacement: 'formally analyzes' },
  { pattern: /\bDescribes\b/g, replacement: 'Precisely defines' },
  { pattern: /\bdescribes\b/gi, replacement: 'precisely defines' },
  { pattern: /\baware(ness)?\b/gi, replacement: 'thorough understanding' },
  { pattern: /\bbasic\b/gi, replacement: 'detailed' },
  { pattern: /\bvague\b/gi, replacement: 'imprecise' },
];

/** Structural lines carry point values and category names — rewriting them breaks scoring. */
function isStructural(trimmed: string): boolean {
  return (
    trimmed.startsWith('---') ||
    trimmed.startsWith('## ') ||
    (trimmed.startsWith('[') && /\]\s*$/.test(trimmed)) ||
    trimmed.length === 0
  );
}

/** Human-readable description of a slider position, for the UI. */
export function describeLeniency(leniency: number): string {
  if (leniency === 50) return 'Original';
  return leniency < 50 ? `${50 - leniency}% more lenient` : `${leniency - 50}% more strict`;
}

/**
 * Rewrite rubric text toward lenient or strict. Deterministic — the same input and
 * slider value always produce the same output, so a grading run is reproducible.
 */
export function rewriteRubric(rubricText: string, leniency: number): string {
  if (leniency === 50 || !rubricText) return rubricText;

  const intensity = Math.abs(leniency - 50) / 50; // 0..1
  const subs = leniency < 50 ? LENIENT_SUBS : STRICT_SUBS;
  const activeSubs = subs.slice(0, Math.ceil(subs.length * intensity));

  return rubricText
    .split('\n')
    .map((line) => {
      if (isStructural(line.trimStart())) return line;
      let result = line;
      for (const sub of activeSubs) result = result.replace(sub.pattern, sub.replacement);
      // Removing words like "explicitly" leaves doubled spaces and orphaned punctuation.
      const indent = /^(\s*)/.exec(result)?.[1] ?? '';
      return indent + result.trimStart().replace(/\s{2,}/g, ' ').replace(/\s+([,.])/g, '$1');
    })
    .join('\n');
}

/**
 * Carry the original category weights back onto a rewritten rubric.
 *
 * Substitutions can touch a line that also carries "[Category 40%]"; weights are scoring
 * configuration, not prose, so they must survive verbatim or the composite score shifts.
 */
export function restoreCategoryWeights(rewritten: string, original: string): string {
  const weightOf = (line: string) => /\[([^\]]*?\d+\s*%[^\]]*?)\]/.exec(line)?.[1];
  const origLines = original.split('\n');
  return rewritten
    .split('\n')
    .map((line, i) => {
      const orig = origLines[i];
      if (!orig) return line;
      const origWeight = weightOf(orig);
      if (!origWeight) return line;
      const nowWeight = weightOf(line);
      return nowWeight && nowWeight !== origWeight ? line.replace(`[${nowWeight}]`, `[${origWeight}]`) : line;
    })
    .join('\n');
}

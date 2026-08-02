/**
 * Make the write/repair loop better at the thing it just got wrong.
 *
 * `MOM_DIALECT_RULES` is the loop's memory: every one of those rules was learned by repairing a real
 * broken question, and each one now stops a whole class of failure before it happens. That learning
 * was manual. This does the same job automatically — after a run that needed repairs, one agent turn
 * reads what the sandbox complained about and what fixed it, and proposes rules in the same shape.
 *
 * Design constraints, because this writes the text that steers later runs:
 *
 * - **Append-only.** It may add rules; it may never edit or delete one. A loop that can rewrite its
 *   own instructions can also erase the rule that was keeping it honest.
 * - **Capped.** Every rule costs tokens on every future run forever. Past the cap the oldest are
 *   kept and the new one is refused, so an unbounded prompt cannot creep in.
 * - **Only on evidence.** A run that succeeded first try teaches nothing; asking anyway invites the
 *   model to invent a plausible rule about a problem that never happened.
 * - **Human-readable, in the repo.** It lands in `reference/` as markdown next to the hand-written
 *   docs, so a wrong rule is visible in a diff and can be deleted by hand.
 */

import { invoke } from '@tauri-apps/api/core';
import { topLevelArrays, type AttemptResult } from './author';

/** Learned rules live beside the hand-written docs, relative to the content root. */
export const LEARNED_RULES_PATH = 'reference/learned-rules.md';

/**
 * Ceiling on learned rules.
 *
 * Twelve, matching the hand-written set: enough that the loop can genuinely learn, small enough that
 * the prompt stays readable. When it fills, that is a signal to prune by hand, not to raise it.
 */
export const MAX_LEARNED_RULES = 12;

const HEADER = [
  '# Learned rules',
  '',
  'Written by the question writer after runs that needed repairs. Each line is a rule the loop',
  'discovered by breaking something and then fixing it, in the same shape as the hand-written rules',
  'it ships with.',
  '',
  'Safe to edit or delete by hand — a wrong rule here makes every later run worse, so prune freely.',
  '',
].join('\n');

/** One rule per `- ` bullet. Anything else in the file is prose and is ignored. */
export function parseLearnedRules(md: string): string[] {
  return md
    .split('\n')
    .map((l) => l.trim())
    .filter((l) => l.startsWith('- '))
    .map((l) => l.slice(2).trim())
    .filter(Boolean);
}

/** Render the file back, header included, so it stays readable after a machine write. */
export function renderLearnedRules(rules: string[]): string {
  return `${HEADER}${rules.map((r) => `- ${r}`).join('\n')}\n`;
}

/**
 * Compare rules for sameness loosely, so a reworded duplicate does not slip in.
 *
 * Punctuation and case are noise here; two rules that differ only in how they are typeset are the
 * same rule, and keeping both spends tokens forever to say one thing twice.
 */
function normalise(rule: string): string {
  return rule.toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
}

/**
 * Fold newly proposed rules into the existing set.
 *
 * Returns the merged list and what was actually taken, so the caller can report honestly rather
 * than claiming it learned something when everything was a duplicate.
 */
export function mergeLearnedRules(
  existing: string[],
  proposed: string[],
): { rules: string[]; added: string[]; rejected: string[] } {
  const seen = new Set(existing.map(normalise));
  const rules = [...existing];
  const added: string[] = [];
  const rejected: string[] = [];

  for (const raw of proposed) {
    const rule = raw.trim().replace(/^[-*]\s*/, '');
    // A one-word "rule" is not actionable, and a paragraph is not a rule.
    if (rule.length < 20 || rule.length > 400) {
      rejected.push(raw);
      continue;
    }
    const key = normalise(rule);
    if (seen.has(key)) {
      rejected.push(raw);
      continue;
    }
    if (rules.length >= MAX_LEARNED_RULES) {
      rejected.push(raw);
      continue;
    }
    seen.add(key);
    rules.push(rule);
    added.push(rule);
  }
  return { rules, added, rejected };
}

/** Did this run actually teach anything? A clean first render did not. */
export function hasLessons(attempts: AttemptResult[]): boolean {
  return attempts.some((a) => !a.ok && a.errors.length > 0);
}

/**
 * Build the reflection prompt.
 *
 * It is given the errors and told to generalise, not to describe. "q3 was missing a semicolon" helps
 * nobody; "every statement in the control block ends without a semicolon" stops the whole class. The
 * instruction to return NOTHING when the existing rules already cover it is the important one —
 * without it the model always finds something to say, and the file fills with restatements.
 */
export function buildReflectPrompt(args: {
  attempts: AttemptResult[];
  existing: string[];
  handWritten: string[];
  targetPath: string;
}): string {
  const failures = args.attempts.filter((a) => !a.ok);
  return [
    'A MyOpenMath question was written and then repaired until it rendered. Your job is to decide',
    'whether that struggle teaches a GENERAL rule worth telling every future run.',
    '',
    `The file: ${args.targetPath}`,
    'Read it as it stands now — that is the version that finally worked.',
    '',
    'What the render sandbox reported on each failed attempt:',
    ...failures.flatMap((a) => [`Attempt ${a.attempt}:`, ...a.errors.map((e) => `  - ${e}`)]),
    '',
    'Rules the writer ALREADY follows — do not restate any of these in any wording:',
    ...args.handWritten.map((r) => `- ${r}`),
    ...(args.existing.length
      ? ['', 'Rules already learned from earlier runs — likewise do not restate:', ...args.existing.map((r) => `- ${r}`)]
      : []),
    '',
    'Reply with ONLY a JSON array of strings, no prose and no code fence:',
    '["a general rule, one sentence, imperative, naming the exact syntax or construct"]',
    '',
    'Return [] if the existing rules already cover it, if the failure was a one-off content mistake',
    'rather than a dialect trap, or if the cause is not clear from the errors. An empty array is the',
    'RIGHT answer most of the time — a wrong rule makes every future run worse, permanently.',
  ].join('\n');
}

/** Read the learned-rules file. Missing or unreadable is normal on a first run. */
export async function loadLearnedRules(root: string): Promise<string[]> {
  if (!root) return [];
  try {
    const md = await invoke<string>('mom_read_text', { root, path: LEARNED_RULES_PATH });
    return parseLearnedRules(md ?? '');
  } catch {
    return [];
  }
}

/** Persist the merged set. */
export async function saveLearnedRules(root: string, rules: string[]): Promise<void> {
  await invoke('mom_write_text', { root, path: LEARNED_RULES_PATH, text: renderLearnedRules(rules) });
}

/**
 * Take the model's reply and pull the JSON array out of it, tolerating a fence or a preamble.
 *
 * Uses the same balance-aware scan as the plan parser, for the same reason: a rule is a sentence
 * about syntax, so it is exactly the kind of string that contains brackets. Matching with a regex
 * would truncate at the first `]` inside a rule and silently learn nothing.
 */
export function parseProposedRules(reply: string): string[] {
  const candidates = topLevelArrays(reply);
  for (let i = candidates.length - 1; i >= 0; i--) {
    try {
      const raw = JSON.parse(candidates[i]);
      if (Array.isArray(raw)) return raw.filter((r) => typeof r === 'string');
    } catch {
      // A reflection that cannot be parsed is not worth failing a successful run over.
    }
  }
  return [];
}

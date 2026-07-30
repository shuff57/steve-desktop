/**
 * Write ONE MyOpenMath question from a bookSHelf section, then keep fixing it until the sandbox
 * renders it clean.
 *
 * The agent writes the `.php` itself through the CLI's own tools — the same arrangement as the
 * revision rail, and the reason no new write command was needed. The app's job is the half the
 * agent cannot do honestly: render the result in the sandbox and judge it (`questionHealth`), then
 * hand any failure back. An agent grading its own output is not evidence.
 *
 * The book lives in a PRIVATE repo, so the section is read with `gh` from inside the agent turn
 * rather than fetched by the app, which has no GitHub credentials.
 */

import { MOM_DIALECT_RULES } from './revise';

/** Where the book lives. The public Pages URL in older manifests is dead — repo is private now. */
export const BOOK_REPO = 'shuff57/bookSHelf';
export const BOOK_PROJECT = 'projects/Introduction to Stats';

/**
 * Attempts before giving up and asking a human.
 *
 * Three, matching the repo's own fix-loop rule. Past that the retries cost more than they fix —
 * the same trap that made a one-question repair eat an afternoon.
 */
export const MAX_ATTEMPTS = 3;

export interface AuthorRequest {
  /** File name under the project's `html/`, e.g. `1.1_definitions_...html`. */
  section: string;
  /** Question family the file belongs in, e.g. `descriptive-stats`. */
  family: string;
  /** File slug without extension, e.g. `q1-key-terms`. */
  slug: string;
  /** Absolute path the agent must write. */
  targetPath: string;
}

/** The `gh` call that reads a private-repo file as text. */
export function sectionCommand(section: string): string {
  const path = `${BOOK_PROJECT}/html/${section}`.replace(/ /g, '%20');
  return `gh api "repos/${BOOK_REPO}/contents/${path}" --jq .content | base64 -d`;
}

/**
 * Prompt for the first attempt.
 *
 * States the target path twice — as the task and as a prohibition — because the agent has full
 * filesystem tools and the question bank is a live teaching asset.
 */
export function buildAuthorPrompt(req: AuthorRequest): string {
  return [
    `Write ONE MyOpenMath question and save it to: ${req.targetPath}`,
    '',
    'SOURCE — read this bookSHelf section first and base the question on what it actually teaches:',
    '```',
    sectionCommand(req.section),
    '```',
    'It is a private repo, so use `gh` exactly as above. Read the section before writing anything.',
    '',
    'The question must test a concept the section genuinely covers. One question, not a set.',
    '',
    'FORMAT — a MOM question file has these markers, in this order:',
    '  // === NAME - DESCRIPTION: <short title> ===',
    '  // === SET QUESTION TYPE TO: <number|choices|multipart> ===',
    '  // === COMMON CONTROL ===',
    '  // === QUESTION TEXT ===',
    '  // === ANSWER ===',
    '',
    'RULES for this dialect — breaking one makes MyOpenMath refuse the question:',
    ...MOM_DIALECT_RULES.map((r) => `- ${r}`),
    '',
    'SCOPE:',
    `- Write ONLY ${req.targetPath}. Do not touch any other file, and do not create others.`,
    '- Do not edit any existing question in the bank.',
    '',
    'When done, reply with ONE short line naming the concept you tested. No preamble, no code block.',
  ].join('\n');
}

/**
 * Prompt for a retry, carrying the sandbox's verdict.
 *
 * The errors are quoted verbatim: they are the renderer's own words about this exact file, and
 * paraphrasing them loses the line numbers that make them actionable.
 */
export function buildRepairPrompt(targetPath: string, errors: string[], attempt: number): string {
  return [
    `The question at ${targetPath} does not render. This is attempt ${attempt} of ${MAX_ATTEMPTS}.`,
    '',
    'The render sandbox reported:',
    ...errors.map((e) => `- ${e}`),
    '',
    'Fix that file so it renders cleanly. Re-read it from disk first — do not assume its contents.',
    '',
    'RULES:',
    ...MOM_DIALECT_RULES.map((r) => `- ${r}`),
    '',
    `Edit ONLY ${targetPath}. Reply with ONE short line describing the fix.`,
  ].join('\n');
}

/** Outcome of one attempt, for the log the user watches. */
export interface AttemptResult {
  attempt: number;
  errors: string[];
  ok: boolean;
}

/**
 * Should the loop try again?
 *
 * Stops on success, and stops at the cap even when still failing — the caller then shows the last
 * errors rather than silently burning turns.
 */
export function shouldRetry(results: AttemptResult[]): boolean {
  const last = results[results.length - 1];
  if (!last || last.ok) return false;
  return results.length < MAX_ATTEMPTS;
}

/** `descriptive-stats` + `q1-key-terms` -> the path the agent must write. */
export function questionPath(root: string, family: string, slug: string): string {
  const clean = slug.replace(/\.php$/i, '');
  return `${root.replace(/[\\/]+$/, '')}/questions/${family}/${clean}.php`;
}

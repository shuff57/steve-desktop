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
 * Repair rounds after the initial write, before giving up and asking a human.
 *
 * Was three; raised to five because a genuinely fixable question was being abandoned while still
 * converging. Past that the retries cost more than they fix — the trap that made one repair eat an
 * afternoon.
 */
export const MAX_REPAIRS = 5;

/**
 * Renders the loop will perform for one question: the one that judges the initial write, plus one
 * after each repair. Counted in RENDERS because the render is the only thing that decides whether a
 * question is good — the agent's own account of its edit is not evidence.
 */
export const MAX_ATTEMPTS = MAX_REPAIRS + 1;

export interface AuthorRequest {
  /**
   * Where the problem set lives: either a full URL, or a bookSHelf section file name under the
   * project's `html/` (e.g. `1.1_definitions_...html`). Optional.
   */
  link?: string;
  /** What to write, in the teacher's words. Optional, but required when there is no section. */
  brief?: string;
  /** An example question to imitate — a local image path the agent can open. Optional. */
  imagePath?: string;
  /** Question family the file belongs in, e.g. `descriptive-stats`. */
  family: string;
  /** File slug without extension, e.g. `q1-key-terms`. */
  slug: string;
  /** Absolute path the agent must write. */
  targetPath: string;
  /** MOM content root. Optional — when given, the agent is pointed at the authoring reference. */
  root?: string;
  /** Rules the loop taught itself on earlier runs. Carried alongside the hand-written ones. */
  learned?: string[];
}

/**
 * The dialect rules a prompt should carry: the hand-written set plus whatever the loop has learned.
 *
 * Learned rules are labelled where they appear rather than blended in, so a bad one is obvious in
 * the prompt and can be traced back to the file it came from.
 */
export function rulesBlock(learned?: string[]): string[] {
  const extra = (learned ?? []).filter((r) => r.trim());
  return [
    ...MOM_DIALECT_RULES.map((r) => `- ${r}`),
    ...(extra.length ? ['', 'Learned from earlier runs in this bank:', ...extra.map((r) => `- ${r}`)] : []),
  ];
}

/**
 * The authoring docs imported from the old standalone repo (macros, answer types, libraries,
 * styles), relative to the content root.
 *
 * Pointed at rather than inlined: it is 28 files, and the agent has file tools. `index.md` is a
 * table of contents, so one path reaches all of it.
 */
export const REFERENCE_INDEX = 'reference/index.md';

/** At least one source is required — otherwise there is nothing to write a question about. */
export function hasSource(req: Pick<AuthorRequest, 'link' | 'brief' | 'imagePath'>): boolean {
  return !!(req.link?.trim() || req.brief?.trim() || req.imagePath?.trim());
}

/** A pasted link is a URL; anything else is treated as a bookSHelf section file name. */
export function isUrl(link: string): boolean {
  return /^https?:\/\//i.test(link.trim());
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
  const sources: string[] = [];
  const link = req.link?.trim();
  if (link && isUrl(link)) {
    sources.push(
      `SOURCE — open this problem set and base the question on what it actually asks: ${link}`,
      'Read it before writing anything. Match its topic and difficulty, but write a NEW question:',
      'randomize the values so each student sees a different version.',
      '',
    );
  } else if (link) {
    sources.push(
      'SOURCE — read this bookSHelf section first and base the question on what it actually teaches:',
      '```',
      sectionCommand(link),
      '```',
      'It is a private repo, so use `gh` exactly as above. Read the section before writing anything.',
      'The question must test a concept the section genuinely covers.',
      '',
    );
  }
  if (req.brief?.trim()) {
    sources.push('WHAT THE TEACHER WANTS (verbatim):', req.brief.trim(), '');
  }
  if (req.imagePath?.trim()) {
    sources.push(
      `EXAMPLE QUESTION — open this image and read it: ${req.imagePath.trim()}`,
      'Reproduce its STRUCTURE and difficulty as a MyOpenMath question. Do not copy its wording or',
      'numbers verbatim — randomize the values so each student sees a different version.',
      '',
    );
  }

  return [
    `Write ONE MyOpenMath question and save it to: ${req.targetPath}`,
    '',
    ...sources,
    'One question, not a set.',
    '',
    'FORMAT — a MOM question file has these markers, in this order:',
    '  // === NAME - DESCRIPTION: <short title> ===',
    '  // === SET QUESTION TYPE TO: <number|choices|multipart> ===',
    '  // === COMMON CONTROL ===',
    '  // === QUESTION TEXT ===',
    '  // === ANSWER ===',
    '',
    'RULES for this dialect — breaking one makes MyOpenMath refuse the question:',
    ...rulesBlock(req.learned),
    '',
    ...(req.root?.trim()
      ? [
          'REFERENCE — the MyOpenMath authoring docs are on disk, indexed at:',
          `  ${req.root.replace(/[\\/]+$/, '')}/${REFERENCE_INDEX}`,
          'Consult `question-types/`, `macros/`, and `syntax.md` before using an answer type, macro, or construct you are unsure of.',
          'A guessed macro signature is the way a question renders fine and still scores wrong.',
          '',
        ]
      : []),
    'SCOPE:',
    `- Write ONLY ${req.targetPath}. Do not touch any other file, and do not create others.`,
    '- Do not edit any existing question in the bank.',
    '',
    'When done, reply with ONE short line naming the concept you tested. No preamble, no code block.',
  ].join('\n');
}

/** One question the set-planner decided the section warrants. */
export interface PlannedQuestion {
  /** File slug, no extension. */
  slug: string;
  /** What that question should ask, in enough detail to write it without the section again. */
  brief: string;
}

/** How the planner should derive the candidate questions from the supplied link. */
export type SetPlanMode = 'exercises' | 'invent';

/**
 * A plan handed to whoever displays it.
 *
 * A plan is a table of ten slugs and briefs, and a 400px rail truncated every one of them — so the
 * writer publishes this and the wide preview pane renders it. The toggles travel with the data
 * rather than being re-implemented by the display, which would put selection in two places.
 */
export interface PlanView {
  planned: PlannedQuestion[];
  /** Slugs currently ticked. */
  selected: string[];
  /** Slugs already on disk in this family — writing one overwrites it. */
  existing: string[];
  toggleOne: (slug: string) => void;
  toggleAll: () => void;
}

/**
 * Ask what a problem set should CONTAIN, before writing any of it.
 *
 * Planning is a separate turn because the alternative — "write every question in this section" in
 * one go — produces a pile the render loop cannot check one at a time. With a plan, each question
 * goes through the same write/render/repair loop a single question does, and a failure is isolated
 * to its own file instead of poisoning the batch.
 */
export function buildSetPlanPrompt(req: { link: string; family: string; root?: string; mode?: SetPlanMode }): string {
  const link = req.link.trim();
  const mode: SetPlanMode = req.mode ?? 'exercises';
  return [
    'Plan a problem set. Do NOT write any question files in this turn.',
    '',
    isUrl(link)
      ? `SOURCE — open and read this problem set: ${link}`
      : ['SOURCE — read this bookSHelf section first:', '```', sectionCommand(link), '```',
         'It is a private repo, so use `gh` exactly as above.'].join('\n'),
    '',
    mode === 'exercises'
      ? [
          'PLANNING MODE — Use ONLY the section\'s numbered problem set / exercises.',
          'Examples, "Try It Now" boxes, definitions, and videos are NOT part of the problem set — ignore them.',
          '',
          'COUNT RULE — First count how many numbered exercises/problems the problem set contains, then plan',
          'that EXACT number of questions. Do not invent extra definition or concept questions. Do not merge',
          'two exercises into one question, and do not skip any exercise.',
          '',
          'SLUG RULE — Use slugs q01-..., q02-..., matching the source problem numbers in order.',
          'If the source has N numbered problems, your JSON array must have exactly N objects.',
        ].join('\n')
      : [
          'PLANNING MODE — Invent new questions. Based on the concepts the source teaches, decide what',
          'questions would best assess those concepts. Each planned item should target one distinct skill.',
        ].join('\n'),
    '',
    'DEFAULT QUESTION FORMAT for each planned item:',
    '- Prefer a fill-in-the-blank (numeric or short string) answer unless the source exercise is',
    '  explicitly multiple-choice or choose-all-that-apply.',
    '- Preserve the same variable roles and structure as the source exercise, but randomize all',
    '  numeric values and other variable parts by default so every student sees a different version.',
    '',
    ...(req.root?.trim()
      ? [
          `The existing bank is at ${req.root.replace(/[\\/]+$/, '')}/questions/${req.family}/.`,
          'Read it first and do not plan a question the bank already has.',
          '',
          'REFERENCE — the MyOpenMath authoring docs are on disk, indexed at:',
          `  ${req.root.replace(/[\\/]+$/, '')}/${REFERENCE_INDEX}`,
          'Consult `question-types/`, `macros/`, and `syntax.md` before naming an answer type, macro, or construct you are unsure of.',
          '',
        ]
      : []),
    'Reply with ONLY a JSON array, no prose and no code fence:',
    '[{"slug": "q1-short-kebab-name", "brief": "one or two sentences: what the question asks, what',
    'is randomized, and what the student must produce"}]',
    '',
    'The brief is the ONLY thing the writer will see for that question — it will not have the',
    'section in front of it — so make each one self-contained.',
  ].join('\n');
}

/**
 * Every complete top-level `[...]` block in `text`, matched by BALANCE, not by regex.
 *
 * A regex cannot do this. `\[[\s\S]*?\]` stops at the first `]`, and the briefs the planner writes
 * routinely contain brackets — a real 11-question plan came back with seven bracket pairs inside
 * the prose, so the "last match" was a fragment of one brief and the whole plan was rejected as
 * invalid JSON. Quotes are tracked so a bracket inside a string never changes the depth.
 */
export function topLevelArrays(text: string): string[] {
  const out: string[] = [];
  let depth = 0, start = -1, inStr = false, esc = false;
  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (inStr) {
      if (esc) esc = false;
      else if (c === '\\') esc = true;
      else if (c === '"') inStr = false;
      continue;
    }
    if (c === '"') inStr = true;
    else if (c === '[') {
      if (depth === 0) start = i;
      depth++;
    } else if (c === ']') {
      if (depth > 0) {
        depth--;
        if (depth === 0 && start >= 0) out.push(text.slice(start, i + 1));
      }
    }
  }
  return out;
}

/**
 * Read the planner's reply.
 *
 * Tolerates a code fence and surrounding prose by taking the outermost bracketed array, because a
 * model that adds "Here is the plan:" should not cost the whole run. Entries missing a slug or a
 * brief are dropped rather than written as `undefined.php`.
 */
export function parseSetPlan(reply: string): PlannedQuestion[] {
  const candidates = topLevelArrays(reply);
  if (!candidates.length) throw new Error('the planner did not return a JSON array');

  // Prefer the LAST array that both parses and looks like a plan: the agent sometimes shows a
  // preview before its final answer, and the final one is the answer.
  let raw: unknown;
  let parsedAny = false;
  for (let i = candidates.length - 1; i >= 0; i--) {
    try {
      const value = JSON.parse(candidates[i]);
      parsedAny = true;
      if (Array.isArray(value) && value.some((v) => v && typeof v === 'object')) {
        raw = value;
        break;
      }
      if (raw === undefined) raw = value;
    } catch {
      // Not JSON — keep looking rather than failing on the first thing shaped like a bracket.
    }
  }
  if (!parsedAny) throw new Error('the planner returned something that is not valid JSON');
  if (!Array.isArray(raw)) throw new Error('the planner did not return a JSON array');

  const seen = new Set<string>();
  const out: PlannedQuestion[] = [];
  for (const item of raw) {
    if (!item || typeof item !== 'object') continue;
    const slug = String((item as PlannedQuestion).slug ?? '')
      .trim()
      .replace(/\.php$/i, '');
    const brief = String((item as PlannedQuestion).brief ?? '').trim();
    // Two entries sharing a slug would have the second silently overwrite the first's file.
    if (!slug || !brief || seen.has(slug)) continue;
    seen.add(slug);
    out.push({ slug, brief });
  }
  if (!out.length) throw new Error('the plan had no usable questions');
  return out;
}

/**
 * Prompt for a retry, carrying the sandbox's verdict.
 *
 * The errors are quoted verbatim: they are the renderer's own words about this exact file, and
 * paraphrasing them loses the line numbers that make them actionable.
 *
 * The reference is named here as well as in the write prompt, and named more insistently: a repair
 * round is exactly where the agent is guessing at a macro signature or an answer type it got wrong
 * the first time. Being told the docs exist only before its first attempt is no use on its third.
 */
export function buildRepairPrompt(
  targetPath: string,
  errors: string[],
  attempt: number,
  root?: string,
  learned?: string[],
): string {
  return [
    `The question at ${targetPath} does not render. This is attempt ${attempt} of ${MAX_ATTEMPTS}.`,
    '',
    'The render sandbox reported:',
    ...errors.map((e) => `- ${e}`),
    '',
    'Fix that file so it renders cleanly. Re-read it from disk first — do not assume its contents.',
    '',
    ...(root?.trim()
      ? [
          'LOOK IT UP before guessing. The MyOpenMath authoring docs are on disk, indexed at:',
          `  ${root.replace(/[\\/]+$/, '')}/${REFERENCE_INDEX}`,
          'If the error names a macro, an answer type or a library, READ its page there and fix the',
          'question against what the docs actually say. Repeating a guess is how a loop burns turns.',
          '',
        ]
      : []),
    'RULES:',
    ...rulesBlock(learned),
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

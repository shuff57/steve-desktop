/**
 * Translate the question rail's own conversation into the shell's presence.
 *
 * ActionShell has drawn a status dot, a header sweep and history cards since it was
 * extracted; MomBrowser bound none of them, so the rail sat inert while the same shell
 * lit up in the browser panel. This is the translation layer, kept out of the component
 * so the part with a real failure mode can be tested.
 */

/** One line of the rail's run log. */
export interface RailLine {
  role: 'user' | 'agent' | 'step' | 'ok' | 'error';
  text: string;
}

/**
 * A card as ActionShell renders it.
 *
 * `meta` is a VISIBLE field — the shell prints it under the text — so it holds real
 * metadata or nothing. It is deliberately not set here: it was briefly used to make the
 * shell's `{#each}` key unique, and every card then displayed a stray index. The shell
 * keys by position now, which is what a sliding window over a log wants anyway.
 */
export interface HistoryCard {
  icon: string;
  text: string;
  type: string;
}

const CARD_ICON: Record<RailLine['role'], string> = {
  user: '💬',
  agent: '🤖',
  step: '⚙',
  ok: '✅',
  error: '⚠',
};

/** Card types the shell has styling for: input/output/question/observation/error/success. */
const CARD_TYPE: Record<RailLine['role'], string> = {
  user: 'input',
  agent: 'output',
  step: 'observation',
  ok: 'success',
  error: 'error',
};

/** Longest card text before truncation — a card is a glance, not the transcript. */
const MAX_TEXT = 160;

/** How many lines the shell shows at once. */
export const HISTORY_WINDOW = 10;

/** Map the tail of a run log to history cards. */
export function toHistoryCards(lines: RailLine[], window = HISTORY_WINDOW): HistoryCard[] {
  const start = Math.max(0, lines.length - window);
  return lines.slice(start).map((l) => ({
    icon: CARD_ICON[l.role] ?? '💬',
    text: l.text.length > MAX_TEXT ? l.text.slice(0, MAX_TEXT - 1) + '…' : l.text,
    type: CARD_TYPE[l.role] ?? 'default',
  }));
}

/** What the rail is doing, in the vocabulary AutomateRunner already uses. */
export interface RailPhase {
  planning: boolean;
  revising: boolean;
  writing: boolean;
  failed: boolean;
  finished: boolean;
  /** Label of the question being revised, when there is one. */
  label?: string | null;
  /** Slug being written, for the default writing text. */
  slug?: string | null;
  /** Most recent step line, the most specific thing known about a write in flight. */
  lastStep?: string | null;
  /**
   * How many questions the last plan produced, or null if no plan has run.
   *
   * A write sets `finished` when it lands a file, but a plan produces no file — so a
   * successful plan used to fall back to idle and the rail said "Ready" the instant it
   * finished, throwing away the one number worth reading.
   */
  plannedCount?: number | null;
}

/**
 * One phase -> one status. The vocabulary is AutomateRunner's on purpose: 'thinking'
 * while a model decides, 'executing' while something is being written, so one glance
 * means the same thing whichever surface it is on.
 */
export function railStatusFor(p: RailPhase): { status: string; text: string } {
  if (p.planning) return { status: 'thinking', text: 'Planning the set…' };
  if (p.revising) return { status: 'executing', text: p.label ? `Revising ${p.label}…` : 'Revising…' };
  if (p.writing) {
    const step = p.lastStep?.trim();
    return {
      status: 'executing',
      text: step ? step.slice(0, 60) : `Writing ${p.slug || 'question'}…`,
    };
  }
  // Failure outranks any success below it: a run that produced a file and THEN failed
  // must not read as Done.
  if (p.failed) return { status: 'error', text: 'Failed' };
  if (p.finished) return { status: 'completed', text: 'Done' };
  if (typeof p.plannedCount === 'number') {
    return {
      status: 'completed',
      text: `Planned ${p.plannedCount} question${p.plannedCount === 1 ? '' : 's'}`,
    };
  }
  return { status: 'idle', text: '' };
}

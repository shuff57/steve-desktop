/**
 * Translate the question rail's own phase into the shell's presence.
 *
 * ActionShell draws a status dot and a pill; MomBrowser bound neither, so the rail sat
 * inert while the same shell lit up in the browser panel. This is the translation layer,
 * kept out of the component so the part with a real failure mode can be tested.
 *
 * It used to also map the rail's log into the shell's history cards. The rail renders that
 * log itself, so the cards drew it a second time; the browser panel still uses them, this
 * surface does not.
 */

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

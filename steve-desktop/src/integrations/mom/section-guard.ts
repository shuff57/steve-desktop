/**
 * Catch a question being written from one section and filed into another.
 *
 * The rail holds two independent settings: the section link it writes FROM, and the
 * assignment it files INTO. The destination persists between runs by design — it is
 * something you set occasionally — so changing the link to a new section leaves the
 * old destination in place, and nothing compared them.
 *
 * Observed live on 2026-08-02: the link was pointed at 1.4 (experimental design), the
 * destination was still 1.3 (frequency tables) from earlier work, and a 1.4 question was
 * filed as slot 16 of the 1.3 homework. Nothing warned, the run reported success, and the
 * only way to notice was reading the manifest diff.
 *
 * This is deliberately advisory. Filing a question into a neighbouring section is a real
 * thing to want; filing it there WITHOUT NOTICING is the failure. The fix is to end the
 * silence, not to add a gate.
 */

/**
 * Pull a `chapter.section` number out of whatever the rail is holding.
 *
 * Handles the three spellings the rail actually deals in:
 *   - a bookSHelf link      `.../1.4_experimental_design_and_ethics`
 *   - a manifest file name  `1-3-frequency-frequency-tables-and-levels-of-measurement.json`
 *   - an assignment title   `1.3 Frequency, Frequency Tables, and Levels of Measurement`
 *
 * Returns a normalised `"1.4"`, or null when there is no section number to find — an
 * absent number is not a mismatch, it is simply no information.
 */
export function sectionOf(text: string | null | undefined): string | null {
  if (!text) return null;

  // A manifest file name leads with `1-3-`; the hyphen form only counts at the start of a
  // path segment, so a slug like `top-10-2-tips` cannot masquerade as a section number.
  const seg = String(text).split(/[\\/]/).pop() ?? '';
  const hyphen = /^(\d{1,2})-(\d{1,2})(?:[-._]|$)/.exec(seg);
  if (hyphen) return `${Number(hyphen[1])}.${Number(hyphen[2])}`;

  // Dotted form, anywhere: links and titles both use it.
  const dotted = /(?:^|[^\d.])(\d{1,2})\.(\d{1,2})(?![\d.])/.exec(String(text));
  if (dotted) return `${Number(dotted[1])}.${Number(dotted[2])}`;

  return null;
}

export interface SectionMismatch {
  /** Section the question is being written from. */
  from: string;
  /** Section the destination assignment belongs to. */
  into: string;
}

/**
 * Compare the source link against the filing destination.
 *
 * Null means "nothing to say": either side missing a section number, or the two agree.
 * Only a confident disagreement — both sides parsed, and different — is reported.
 */
export function sectionMismatch(
  link: string | null | undefined,
  destination: string | null | undefined,
): SectionMismatch | null {
  const from = sectionOf(link);
  const into = sectionOf(destination);
  if (!from || !into || from === into) return null;
  return { from, into };
}

/** One line a human can act on, for the warning strip. */
export function describeMismatch(m: SectionMismatch): string {
  return `Writing from section ${m.from} but filing into a section ${m.into} assignment.`;
}

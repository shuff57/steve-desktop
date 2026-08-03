/**
 * The page agent's trust boundary: nothing identifying leaves the machine, and the agent can
 * still act on what it was shown.
 *
 * The loop hands the WHOLE assembled prompt through `text()` — not just the page observation.
 * Masking only the observation leaks anyway, because a tool's output becomes history and history
 * is re-sent every step: `select_dropdown_option` on a student picker returns the option list,
 * and that list is a roster. One seam, applied last, is the only version that is provable.
 *
 * Tokens are reversible on purpose. A one-way ⟦STU⟧ would make a gradebook unusable — the agent
 * could see a row but never open it — so `rehydrate()` puts the real value back into the ACTION
 * on the way to the page, which never crosses the wire. That is also what keeps
 * `select_dropdown_option`, which targets an option by its text, working on a masked page.
 *
 * What is masked, and when:
 *
 *   always            person ids in paths, queries and DOM ids (/users/12345, ?stu=99,
 *                     #submission_7); emails, phones, SSNs; the comma name form ("Doe, Jane")
 *   people surfaces   the plain name form ("Jane Doe") and bare 6–10 digit id runs
 *   forever after     any value already tokenized once, on every surface
 *
 * The middle tier is gated because the plain form is not a person signal — measured against a
 * live MyOpenMath course, it classified "Course Map", "Late Passes" and the site's own footer as
 * people (see looksLikeRoster in people-pointer.ts). Masking it everywhere would turn "Add
 * Question" into a token and leave the agent unable to read its own page. The last tier is what
 * makes the gate safe: a name discovered on the gradebook stays masked after the agent navigates
 * away, so history cannot launder it.
 *
 * ponytail: bare id runs are matched at 6–10 digits, shared with the crawl path. A 5-digit
 * student id in a table cell is NOT caught by that tier — it is still caught if it appears in a
 * URL, a DOM id, or beside a name. Lower the floor only with a per-run roster to swap against;
 * unanchored, 4–5 digit runs are years, scores and course ids.
 */

import { Redactor, NUMERIC_ID_PATTERN } from './redact';
import { isCommonLabel, maskPersonNames, scrubPersonIds, sweepPattern } from './redact-tree';
import { isPeopleSurface } from './chunked-map';

export interface PageMask {
  /**
   * Mask everything identifying in text bound for the model.
   * @param url the page the text was observed on — decides the people-surface tier.
   */
  text(text: string, url?: string): string;
  /** Put the real values back into an action the model addressed by token. Local only. */
  rehydrate<T>(value: T): T;
  /** token -> value. For an audit line or a UI reveal, never for the wire. */
  readonly map: Record<string, string>;
}

export interface PageMaskOptions {
  /** Known identifiers (a roster, if one is already loaded). Masked on every surface. */
  secrets?: string[];
  /**
   * Mask bare 6–10 digit runs on people surfaces. Default true.
   *
   * Off for runs whose pages are full of structural ids and carry no roster — mom-transfer
   * addresses questions by qsetid, and tokenizing those makes the model reason about ⟦PID3⟧
   * where the skill instructions say 7158619.
   */
  numericIds?: boolean;
}

/**
 * A dehydrated element line whose text is a CONTROL LABEL rather than data — the same
 * allow-list redactTree applies to the DOM, mapped onto page-agent-dom's `[i]<role>name</role>`
 * format. `link` is deliberately absent: a roster row's label is a link.
 */
const CHROME_LINE = /^\[\d+\]<(button|heading|tab|menuitem|menuitemcheckbox|menuitemradio)>/;

/** Strip the loop's label so isPeopleSurface sees a parseable URL. */
export function urlFromHeader(header: string): string {
  return header.replace(/^\s*Current URL:\s*/i, '').trim();
}

/**
 * One mask per RUN, shared by every page-tool call that run makes.
 *
 * This is the piece that lets an orchestrator reason about people it can never see. Claude drives
 * the task; the page agent reads the page, masks it, and reports back in tokens; Claude decides
 * what to do next and sends an instruction that still refers to those tokens; the app rehydrates
 * it locally on the way to the page.
 *
 * That only works if a token means the SAME person for the whole task. A mask per call would
 * renumber everyone on every observation, so "open ⟦STU3⟧'s row" would quietly attach to a
 * different student than the one the orchestrator meant — the worst failure available on this
 * path, because nothing about it looks wrong.
 *
 * The entry holds the run's real identifiers in memory, so it is the one place raw PII lives
 * during a task. `endRunMask` is not optional bookkeeping: it is how that data stops existing.
 */
const byRun = new Map<string, PageMask>();

export function maskForRun(runId: string, opts: PageMaskOptions = {}): PageMask {
  const existing = byRun.get(runId);
  if (existing) return existing;
  const mask = createPageMask(opts);
  byRun.set(runId, mask);
  return mask;
}

/** Drop a finished run's token map. Call it when the run ends, however it ends. */
export function endRunMask(runId: string): void {
  byRun.delete(runId);
}

/** How many runs are holding a token map — for a leak check, not for logic. */
export function activeRunMaskCount(): number {
  return byRun.size;
}

export function createPageMask(opts: PageMaskOptions = {}): PageMask {
  // numericIds stays OFF here: this redactor runs on every surface, and the bare-digit tier is
  // gated on people surfaces below. Emails/phones/SSNs are identifying wherever they appear.
  const redactor = new Redactor(opts.secrets ?? [], { numericIds: false });
  const toToken = new Map<string, string>();
  const toValue = new Map<string, string>();
  const wantNumeric = opts.numericIds ?? true;

  const tokenFor = (kind: 'STU' | 'PID', value: string): string => {
    const v = value.trim();
    if (!v) return value;
    const hit = toToken.get(v);
    if (hit) return hit;
    const token = `⟦${kind}${toToken.size + 1}⟧`;
    toToken.set(v, token);
    toValue.set(token, v);
    // Register the parts of a name as identifiers in their own right. A gradebook renders
    // "Nakamura, Yuki" and the student's own work is signed "Yuki Nakamura" — a different
    // string, which the sweep would not recognise (grade.ts hit this first). Labels the live
    // measurement already knows to be UI wording are skipped, so a student surnamed Page does
    // not blind the agent to the word "page".
    // ponytail: this makes a common surname over-mask. Reversible, so the cost is legibility,
    // not capability; narrow it with a per-run roster if it ever bites.
    if (kind === 'STU') {
      for (const part of v.split(/[\s,]+/)) {
        if (part.length >= 3 && !toToken.has(part) && !isCommonLabel(part)) tokenFor('STU', part);
      }
    }
    return token;
  };

  /** Re-apply every value ever tokenized, whatever surface this text came from. */
  const sweep = (text: string): string => {
    let out = text;
    // Longest first so a full name wins over a surname that is a substring of it.
    for (const [value, token] of [...toToken].sort((a, b) => b[0].length - a[0].length)) {
      if (value.length < 3) continue; // a 2-char value would shred ordinary words
      out = out.replace(sweepPattern(value), token);
    }
    return out;
  };

  const restore = (text: string): string => {
    let out = text;
    for (const [token, value] of toValue) out = out.split(token).join(value);
    return redactor.rehydrate(out);
  };

  return {
    text(text, url) {
      const people = isPeopleSurface(url ?? '');
      let out = scrubPersonIds(text, (id) => tokenFor('PID', id));
      // The plain name form is applied line by line so a control's own label survives it. In the
      // dehydrated element list a `[4]<button>` line is chrome by the same rule redactTree uses
      // (isChromeNode), and chrome is what the agent clicks with: masking "Teacher Preview" as a
      // person leaves it holding a token where its instructions name a button. A link is NOT
      // chrome here — on a roster the row label is a link, and it is the person.
      out = out
        .split('\n')
        .map((line) =>
          maskPersonNames(line, (n) => tokenFor('STU', n), {
            commaOnly: !people || CHROME_LINE.test(line),
          }),
        )
        .join('\n');
      out = redactor.redact(out).text;
      if (people && wantNumeric) {
        out = out.replace(new RegExp(NUMERIC_ID_PATTERN.source, 'g'), (m) => tokenFor('PID', m));
      }
      return sweep(out);
    },

    rehydrate<T>(value: T): T {
      const walk = (v: unknown): unknown => {
        if (typeof v === 'string') return restore(v);
        if (Array.isArray(v)) return v.map(walk);
        if (v && typeof v === 'object') {
          return Object.fromEntries(Object.entries(v as Record<string, unknown>).map(([k, x]) => [k, walk(x)]));
        }
        return v;
      };
      return walk(value) as T;
    },

    get map() {
      return Object.fromEntries(toValue);
    },
  };
}

/**
 * Pull student responses off a MyOpenMath `gradeallq2.php` page.
 *
 * Structure confirmed against a real graded page (28 students, cid=314128):
 *
 *   div.bigquestionwrap                 one per student
 *     div.headerpane > b                "Last, First"
 *     div[id^="qnwrap"].introtext       the student's answer — empty when unanswered
 *     div.scoredetails
 *       input[name^="ud-"]              current score ("N/A" when ungraded)
 *       div.fbbox[id^="fb-"]            existing feedback (TinyMCE contenteditable)
 *
 * The wrapper's `qfilter-*` classes encode state. `qfilter-nowork` is a trap: it means
 * the student showed no *scratch work*, not that they didn't answer — graded students
 * with full marks carry it. Emptiness of `introtext` is the only reliable "no submission"
 * signal, which is why `answered` is derived from the text, not from the classes.
 *
 * Extraction runs in the page (see PAGE_EXTRACT_JS) because the DOM lives in the webview;
 * `parseExtracted` then validates the result here where it can be unit-tested.
 */
import type { Student } from './grade';

export interface ExtractedStudent {
  index: number;
  name: string;
  response: string;
  /** Current gradebook score, or null when ungraded ("N/A" on the page). */
  currentScore: number | null;
  /** Feedback already present, so a re-run can avoid clobbering a human's words. */
  existingFeedback: string;
  /** False when the student submitted nothing — these should never be sent to a model. */
  answered: boolean;
  /** Field name of the score input, e.g. "ud-7150099-0-0-0". The write-back target. */
  scoreField: string | null;
}

/**
 * Evaluated in the page. Kept as a string (not a function reference) because it crosses
 * the CDP boundary — it must not close over anything in this module.
 */
export const PAGE_EXTRACT_JS = `(() => {
  const wraps = Array.from(document.querySelectorAll('div.bigquestionwrap'));
  return {
    url: location.href,
    students: wraps.map((w, i) => {
      const nameEl = w.querySelector('div.headerpane b') || w.querySelector('span.person');
      const ansEl = w.querySelector('div[id^="qnwrap"].introtext');
      const scoreEl = w.querySelector('input[name^="ud-"]');
      const fbEl = w.querySelector('div.fbbox');
      return {
        index: i,
        name: (nameEl && nameEl.textContent || '').replace(/:\\s*$/, '').trim(),
        response: (ansEl && ansEl.innerText || ansEl && ansEl.textContent || '').trim(),
        rawScore: scoreEl ? scoreEl.value : null,
        scoreField: scoreEl ? scoreEl.getAttribute('name') : null,
        existingFeedback: (fbEl && fbEl.textContent || '').trim(),
      };
    }),
  };
})()`;

interface RawRow {
  index: number;
  name: string;
  response: string;
  rawScore: string | null;
  scoreField: string | null;
  existingFeedback: string;
}

/** Validate and normalize what the page handed back. */
export function parseExtracted(raw: unknown): ExtractedStudent[] {
  const rows = (raw as { students?: RawRow[] })?.students;
  if (!Array.isArray(rows)) throw new Error('Extraction returned no student list — is this a gradeallq2 page?');

  return rows.map((r) => {
    const response = (r.response ?? '').trim();
    // "N/A" is the page's ungraded marker; anything non-numeric is treated the same way
    // rather than coerced to 0, which would read as a real zero.
    const parsed = r.rawScore == null ? NaN : parseFloat(r.rawScore);
    return {
      index: r.index,
      name: (r.name ?? '').trim(),
      response,
      currentScore: Number.isFinite(parsed) ? parsed : null,
      existingFeedback: (r.existingFeedback ?? '').trim(),
      answered: response.length > 0,
      scoreField: r.scoreField ?? null,
    };
  });
}

export interface LoadOptions {
  /** Include students who submitted nothing. Off by default — see gradeableFrom. */
  includeUnanswered?: boolean;
  /** Include students who already have a score. Off by default, so re-runs are additive. */
  includeGraded?: boolean;
}

/**
 * Narrow an extraction to the students actually worth sending to a model.
 *
 * Unanswered students are excluded by default: there is nothing to grade, and a blank
 * submission is a gradebook decision (usually 0) rather than an AI one. Already-graded
 * students are excluded so a second pass doesn't overwrite a human's scores — which
 * pairs with the batch_session resume marker for interrupted runs.
 */
export function gradeableFrom(students: ExtractedStudent[], opts: LoadOptions = {}): ExtractedStudent[] {
  return students.filter(
    (s) => (opts.includeUnanswered || s.answered) && (opts.includeGraded || s.currentScore === null),
  );
}

/** Map to the shape gradeBatch consumes. Name carries through for redaction. */
export function toGradingStudents(students: ExtractedStudent[]): Student[] {
  return students.map((s) => ({ name: s.name, responseText: s.response }));
}

/**
 * Read the page through an injected evaluator (the CDP client in the app, a stub in
 * tests). Read-only: this issues no clicks, no navigation, and no form writes.
 */
export async function loadStudents(
  evaluate: (expression: string) => Promise<unknown>,
  opts: LoadOptions = {},
): Promise<ExtractedStudent[]> {
  return gradeableFrom(parseExtracted(await evaluate(PAGE_EXTRACT_JS)), opts);
}

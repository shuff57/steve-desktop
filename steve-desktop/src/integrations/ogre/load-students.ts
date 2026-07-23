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
 * Where the pieces of a student's row live on a grading page. One set per site.
 *
 * `studentSection` is the repeating container; every other selector is resolved WITHIN it,
 * which is what keeps one student's answer from being read for another.
 */
export interface SiteSelectors {
  studentSection: string;
  /** Name element. A comma-separated list is tried in order — pages vary by course. */
  studentName: string;
  response: string;
  scoreInput: string;
  feedbackBox: string;
}

/**
 * MyOpenMath `gradeallq2.php`, confirmed over CDP against a real graded page.
 *
 * These deliberately differ from O.G.R.E's own MyOpenMath profile, which keys off
 * `div[data-lastchange]` and `input[aria-label="Score"]`. Those may well be right for the
 * pages it was written against, but the selectors below are the ones actually verified
 * here, so they stay the default; O.G.R.E's belong in a saved profile if a page needs them.
 */
export const MYOPENMATH_SELECTORS: SiteSelectors = {
  studentSection: 'div.bigquestionwrap',
  studentName: 'div.headerpane b, span.person',
  response: 'div[id^="qnwrap"].introtext',
  scoreInput: 'input[name^="ud-"]',
  feedbackBox: 'div.fbbox',
};

/**
 * Build the page-side extractor for a selector set.
 *
 * Returns a string, not a function: it crosses the CDP boundary and must close over
 * nothing in this module. Selectors are embedded with JSON.stringify so a quote in a
 * saved profile cannot break out of the literal and run as code — profiles are editable,
 * which makes this an injection boundary however friendly the author.
 */
export function buildExtractJs(sel: SiteSelectors = MYOPENMATH_SELECTORS): string {
  const s = (v: string) => JSON.stringify(v);
  return `(() => {
  const wraps = Array.from(document.querySelectorAll(${s(sel.studentSection)}));
  return {
    url: location.href,
    students: wraps.map((w, i) => {
      const nameEl = w.querySelector(${s(sel.studentName)});
      const ansEl = w.querySelector(${s(sel.response)});
      const scoreEl = w.querySelector(${s(sel.scoreInput)});
      const fbEl = w.querySelector(${s(sel.feedbackBox)});
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
}

/** The default extractor, kept as a constant for callers that never change site. */
export const PAGE_EXTRACT_JS = buildExtractJs();

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
  /** Site selectors to read with. Defaults to the confirmed MyOpenMath set. */
  selectors?: SiteSelectors;
}

/** A saved profile, narrowed to what extraction needs. */
export interface ExtractionProfile {
  id: string;
  name: string;
  urlPatterns: string[];
  selectors: SiteSelectors;
}

/**
 * First profile whose patterns appear in the URL, or null.
 *
 * Substring matching, and deny-by-default: an unmatched URL returns null rather than
 * falling back to a profile that happens to be first. Reading a page with the wrong
 * selectors does not fail loudly — it yields empty responses that look like a class who
 * submitted nothing, which is exactly the mistake worth refusing to make silently.
 */
export function matchProfile(url: string, profiles: ExtractionProfile[]): ExtractionProfile | null {
  if (!url) return null;
  const lower = url.toLowerCase();
  return (
    profiles.find((p) =>
      (p.urlPatterns ?? []).some((pat) => pat && lower.includes(pat.toLowerCase())),
    ) ?? null
  );
}

/**
 * Read a `site_profiles` row into the shape extraction needs, or null when its selector
 * JSON is unusable. A profile missing `studentSection` cannot find anyone, so it is
 * rejected here rather than producing an empty roster later.
 */
export function profileFromRow(row: {
  id: string;
  name: string;
  url_patterns: string;
  selectors: string;
}): ExtractionProfile | null {
  const parse = <T>(text: string, fallback: T): T => {
    try {
      return JSON.parse(text) as T;
    } catch {
      return fallback;
    }
  };
  const raw = parse<Partial<SiteSelectors>>(row.selectors, {});
  if (!raw.studentSection) return null;

  return {
    id: row.id,
    name: row.name,
    urlPatterns: parse<string[]>(row.url_patterns, []),
    selectors: {
      studentSection: raw.studentSection,
      // Each field falls back to the confirmed MyOpenMath selector; a profile that only
      // overrides the container is a common and legitimate thing to save.
      studentName: raw.studentName || MYOPENMATH_SELECTORS.studentName,
      response: raw.response || MYOPENMATH_SELECTORS.response,
      scoreInput: raw.scoreInput || MYOPENMATH_SELECTORS.scoreInput,
      feedbackBox: raw.feedbackBox || MYOPENMATH_SELECTORS.feedbackBox,
    },
  };
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
  const js = buildExtractJs(opts.selectors ?? MYOPENMATH_SELECTORS);
  return gradeableFrom(parseExtracted(await evaluate(js)), opts);
}

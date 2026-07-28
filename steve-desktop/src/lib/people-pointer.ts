// People surfaces as POINTERS, not as captured pages.
//
// A gradebook or roster is the one place where the thing a map normally records — the label on
// a control — IS the person. Redacting that field by field has repeatedly proved incomplete: a
// live capture leaked 3 student names, and fixing the visible label still left 2 of them, in
// `[role=button][name="..."]` and in the role-name selector candidate. Every fix was correct
// and none was sufficient, because there is no end to the list of fields a name can sit in.
//
// So do not store the page. Store how to REACH it:
//
//   index      where the surface lives
//   perPerson  the URL shape with a named slot: gradebook.php?cid=316341&stu={studentId}
//   slot       which parameter selects the person, and where to look one up at runtime
//   controls   ACTION labels only ("Export CSV") — never a row label, which is a person
//
// Automation gains something it did not have: the parameter is named, so "open this student's
// grades" is a substitution rather than a scrape. And the leak class disappears by construction
// — there is no captured roster left to scrub.
//
// A file-level boundary is enforceable. Field-level redaction is a permanent game of whack-a-mole.

import { looksLikePersonName } from './redact-tree';
import type { SiteProfile } from './types/site-profile';

/** The person token storage redaction leaves behind in a URL (see redact-tree STUDENT_TOKEN). */
const STUDENT_TOKEN = '⟦STU⟧';

/** What we substitute it with, so the template reads as an instruction rather than a redaction. */
const SLOT = '{studentId}';

/** Query params that select a person — the slot is whichever of these the URL carries. */
const PERSON_PARAM = /(uid|stu|stuid|student|studentid|userid|filteruid|sid|learner)/i;

/**
 * A control label that is a person rather than an action. Row labels are dropped entirely;
 * only genuine actions survive into `controls`.
 */
const PERSON_LABEL = /^[A-Z][a-z]+(?:[-'][A-Za-z]+)?(?:,\s*|\s+)[A-Z][a-z]+/;

/**
 * Does this CAPTURED page look like a list of people, whatever its URL says?
 *
 * The last line of defence, and the only one that needs no vocabulary. A live MyOpenMath course
 * serves `latepasses.php`, `logingrid.php` and `coursereports.php` — all per-student, none
 * recognisable from their names. Any deny list of script names is a list of the surfaces someone
 * already thought of; this one asks what is actually on the page.
 *
 * COMMA FORM ONLY — "Doe, Jane". Roster tables render it and almost nothing else does.
 *
 * The plain form ("Jane Doe") was tried twice and is not a person signal at all; it is the shape
 * of ordinary UI wording, and each threshold only moved which pages it was wrong about:
 *
 *   >= 3 — classified a page of "Course Map" / "Data Sets" / "Late Passes" as a roster.
 *   >= 5 — measured against the live course: MyOpenMath's own footer ("For Instructors",
 *          "About Us", "Forgot Password", "Forgot Username", "Privacy Policy") is exactly five,
 *          so the LOGIN PAGE scored as a roster, and so did 66 of 305 real course pages, which
 *          carry that same footer. A fifth of the site would have had its labels scrubbed.
 *
 * No threshold can separate them either: the footer is 5 person-shaped labels out of 18 (28%),
 * a real gradebook is 31%. The distributions overlap, so the rule goes rather than gets tuned.
 *
 * What this gives up: a roster that renders "Jane Doe" with no comma is not caught HERE. Such a
 * site is still covered by URL — `isPeopleSurface` matches both the script name and any
 * person-selecting query param — which is the signal that caught every MyOpenMath surface anyway.
 */
export function looksLikeRoster(profile: SiteProfile): boolean {
  const commaForm = /^[A-Z][a-z]+(?:[-'][A-Za-z]+)?,\s+[A-Z][a-z]+/;
  const comma = new Set<string>();
  const labels = [
    ...(profile.interactive?.buttons ?? []).map((b) => b.text),
    ...(profile.interactive?.links ?? []).map((l) => l.text),
  ];
  for (const raw of labels) {
    const t = (raw ?? '').trim();
    if (t && looksLikePersonName(t) && commaForm.test(t)) comma.add(t);
  }
  return comma.size >= 3;
}

export interface PeoplePointer {
  /** Short name for the surface, e.g. "gradebook", "listusers". */
  surface: string;
  /** The index page. Navigable as-is; contains no person. */
  index: string;
  /** URL shapes that address ONE person, with {studentId} where the id goes. */
  perPerson: string[];
  /** Which query parameter selects the person, and where to resolve one at runtime. */
  slot: { param: string; resolveFrom: string } | null;
  /** Action labels only. A label that reads as a person is discarded, not tokenized. */
  controls: string[];
  /** How many controls were dropped for reading as people — evidence the filter ran. */
  droppedPersonLabels: number;
  policy: string;
}

const POLICY =
  'Resolve {studentId} at run time from the live page. This record is never sent to a model, ' +
  'and no person is stored here.';

/** The surface name: the script or last path segment, minus its extension. */
export function surfaceName(url: string): string {
  try {
    const segs = new URL(url).pathname.split('/').filter(Boolean);
    return (segs[segs.length - 1] ?? 'people').replace(/\.[a-z]+$/i, '');
  } catch {
    return 'people';
  }
}

/**
 * Turn a stored (already-redacted) URL into a slot template, or null if it addresses nobody.
 *
 * `base` matters: captured hrefs are frequently RELATIVE ("gradebook.php?cid=1&stu=⟦STU⟧"), and
 * without resolving them the template is not navigable and `new URL()` throws, which silently
 * left `slot: null` on the first real pointer written.
 */
export function toTemplate(url: string, base = ''): { template: string; param: string } | null {
  if (!url.includes(STUDENT_TOKEN) && !url.includes(encodeURIComponent(STUDENT_TOKEN))) return null;
  let absolute = url;
  try { absolute = new URL(url, base || undefined).toString(); } catch { /* keep as captured */ }
  // Decoded and encoded both appear, depending on whether the href survived a URL round-trip.
  const template = absolute
    .split(STUDENT_TOKEN).join(SLOT)
    .split(encodeURIComponent(STUDENT_TOKEN)).join(SLOT);
  let param = '';
  try {
    for (const [k, v] of new URL(template).searchParams) {
      if (v.includes(SLOT) || (PERSON_PARAM.test(k) && v.includes(SLOT))) { param = k; break; }
    }
  } catch { /* keep param empty */ }
  return { template, param };
}

/**
 * Build a pointer from a captured people surface.
 *
 * The profile passed in must already be storage-redacted, so its per-person URLs read
 * `?stu=⟦STU⟧` — which is exactly the template we want, one substitution away.
 */
export function buildPeoplePointer(profile: SiteProfile, rosterUrl = ''): PeoplePointer {
  const index = profile.url ?? '';
  const templates = new Map<string, string>(); // template -> param
  for (const l of profile.interactive?.links ?? []) {
    const t = toTemplate(l.href ?? '', index);
    if (t) templates.set(t.template, t.param);
  }
  const controls: string[] = [];
  let dropped = 0;
  for (const c of [...(profile.interactive?.buttons ?? []), ...(profile.interactive?.links ?? [])]) {
    const text = (c.text ?? '').trim();
    if (!text || text.includes('⟦')) continue;
    // A row label on a roster is the person. Drop it — do NOT tokenize it into the pointer.
    if (PERSON_LABEL.test(text)) { dropped++; continue; }
    if (!controls.includes(text)) controls.push(text);
  }
  const firstParam = [...templates.values()].find(Boolean) ?? '';
  return {
    surface: surfaceName(index),
    index,
    perPerson: [...templates.keys()],
    slot: firstParam ? { param: firstParam, resolveFrom: rosterUrl || index } : null,
    controls: controls.slice(0, 40),
    droppedPersonLabels: dropped,
    policy: POLICY,
  };
}

/**
 * Merge a pointer into the set, keyed by index URL so a re-capture updates rather than duplicates.
 */
export function upsertPointer(existing: PeoplePointer[], next: PeoplePointer): PeoplePointer[] {
  const rest = existing.filter((p) => p.index !== next.index);
  return [...rest, next].sort((a, b) => a.surface.localeCompare(b.surface));
}

/**
 * Assert a pointer set carries no person. Cheap enough to run before every write — this file is
 * the thing standing in for a captured roster, so it is worth proving rather than assuming.
 */
export function pointerLeaks(pointers: PeoplePointer[]): string[] {
  const bad: string[] = [];
  for (const p of pointers) {
    for (const c of p.controls) if (PERSON_LABEL.test(c)) bad.push(`${p.surface}: control label reads as a person`);
    for (const t of p.perPerson) {
      if (!t.includes(SLOT)) bad.push(`${p.surface}: per-person URL has no ${SLOT} slot`);
      if (/(uid|stu|studentid|userid)=\d+/i.test(t)) bad.push(`${p.surface}: per-person URL carries a literal id`);
    }
  }
  return bad;
}

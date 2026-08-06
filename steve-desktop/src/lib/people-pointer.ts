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

import { containsPersonName, looksLikePersonName } from './redact-tree';
import type { SiteProfile } from './types/site-profile';

/** The person token storage redaction leaves behind in a URL (see redact-tree STUDENT_TOKEN). */
const STUDENT_TOKEN = '⟦STU⟧';

/** What we substitute it with, so the template reads as an instruction rather than a redaction. */
const SLOT = '{studentId}';

/** Query params that select a person — the slot is whichever of these the URL carries. */
const PERSON_PARAM = /(uid|stu|stuid|student|studentid|userid|filteruid|sid|learner)/i;

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
  const comma = new Set<string>();
  const labels = [
    ...(profile.interactive?.buttons ?? []).map((b) => b.text),
    ...(profile.interactive?.links ?? []).map((l) => l.text),
  ];
  for (const raw of labels) {
    const t = (raw ?? '').trim();
    // COMMA FORM ONLY (see the doc comment above): looksLikePersonName is fully anchored and
    // matches either the plain form or the comma form, never both at once, and the plain form's
    // own grammar (redact-tree.ts) never produces a literal comma — none of its fragments include
    // one. So a comma in a string looksLikePersonName already accepted can only mean it matched
    // via the comma branch; no second regex needed to tell them apart.
    //
    // This used to be a second, hand-duplicated ASCII-only regex here, with the same gap the live
    // mask had before this session's Unicode fix: "García, José" or "van der Berg, Willem" never
    // matched it. That is a real leak path, not just a duplicate — looksLikeRoster is the LAST
    // LINE of defence for a roster whose URL doesn't already read as one (latepasses.php,
    // gbcomments.php); under-detecting here means the page's plain-name masking tier never turns
    // on for it at all (see SiteMapper.svelte's `isPeopleSurface(url) || looksLikeRoster(profile)`).
    if (t && looksLikePersonName(t) && t.includes(',')) comma.add(t);
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
  /**
   * Where the person id goes, and where to resolve one at run time.
   *
   * `in` matters because the two LMSs disagree. MyOpenMath selects a student with a query
   * parameter (`?stu=…`), Canvas with a path segment (`/users/12345`) — and a Canvas pointer came
   * back with `slot: null` because this only ever inspected `searchParams`, leaving the template
   * correct but unusable: it showed the shape without saying what filled it.
   * For `in: 'path'`, `param` is the segment BEFORE the id (`users`), which is what an automation
   * agent needs to recognise the route.
   */
  slot: { param: string; in: 'query' | 'path'; resolveFrom: string } | null;
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
export function toTemplate(url: string, base = ''): { template: string; param: string; in: 'query' | 'path' } | null {
  if (!url.includes(STUDENT_TOKEN) && !url.includes(encodeURIComponent(STUDENT_TOKEN))) return null;
  let absolute = url;
  try { absolute = new URL(url, base || undefined).toString(); } catch { /* keep as captured */ }
  // Decoded and encoded both appear, depending on whether the href survived a URL round-trip.
  const template = absolute
    .split(STUDENT_TOKEN).join(SLOT)
    .split(encodeURIComponent(STUDENT_TOKEN)).join(SLOT);
  try {
    const u = new URL(template);
    for (const [k, v] of u.searchParams) {
      if (v.includes(SLOT) || (PERSON_PARAM.test(k) && v.includes(SLOT))) return { template, param: k, in: 'query' };
    }
    // Canvas addresses people by PATH — /courses/31407/users/12345. The id is a segment, so name
    // the segment BEFORE it ("users"): that is what identifies the route to an automation agent.
    // Decode first: `new URL()` percent-encodes the braces, so the raw pathname holds
    // `%7BstudentId%7D` and a scan for the literal slot silently finds nothing.
    let path = u.pathname;
    try { path = decodeURIComponent(u.pathname); } catch { /* keep as-is if it will not decode */ }
    const segs = path.split('/').filter(Boolean);
    const at = segs.findIndex((s) => s.includes(SLOT));
    if (at > 0) return { template, param: segs[at - 1], in: 'path' };
    if (at === 0) return { template, param: '/', in: 'path' };
  } catch { /* fall through — an unparseable template still yields its shape */ }
  return { template, param: '', in: 'query' };
}

/**
 * Build a pointer from a captured people surface.
 *
 * The profile passed in must already be storage-redacted, so its per-person URLs read
 * `?stu=⟦STU⟧` — which is exactly the template we want, one substitution away.
 */
export function buildPeoplePointer(profile: SiteProfile, rosterUrl = ''): PeoplePointer {
  const index = profile.url ?? '';
  const templates = new Map<string, { param: string; in: 'query' | 'path' }>();
  for (const l of profile.interactive?.links ?? []) {
    const t = toTemplate(l.href ?? '', index);
    if (t) templates.set(t.template, { param: t.param, in: t.in });
  }
  const controls: string[] = [];
  let dropped = 0;
  for (const c of [...(profile.interactive?.buttons ?? []), ...(profile.interactive?.links ?? [])]) {
    const text = (c.text ?? '').trim();
    if (!text || text.includes('⟦')) continue;
    // A row label on a roster is the person. Drop it — do NOT tokenize it into the pointer.
    // Scan rather than require the entire label to be a name: live roster controls may append a
    // grade or action ("Chen, Sarah (85%)"), but the person must still never reach storage.
    if (containsPersonName(text)) { dropped++; continue; }
    if (!controls.includes(text)) controls.push(text);
  }
  const located = [...templates.values()].find((s) => s.param);
  return {
    surface: surfaceName(index),
    index,
    perPerson: [...templates.keys()],
    slot: located ? { param: located.param, in: located.in, resolveFrom: rosterUrl || index } : null,
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
    for (const c of p.controls) if (containsPersonName(c)) bad.push(`${p.surface}: control label reads as a person`);
    for (const t of p.perPerson) {
      if (!t.includes(SLOT)) bad.push(`${p.surface}: per-person URL has no ${SLOT} slot`);
      if (/(uid|stu|studentid|userid)=\d+/i.test(t)) bad.push(`${p.surface}: per-person URL carries a literal id`);
    }
  }
  return bad;
}

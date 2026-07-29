import type { SurveySection } from './chunked-map';

/**
 * A built-in section list for a Canvas course, so the crawl does not pay a model to rediscover
 * what is identical at every Canvas institution.
 *
 * Why this exists: pass 1 spawns an agent for ~4 minutes and up to 900s of budget to report the
 * SECTIONS of a site. On Canvas that answer is known in advance — `/modules`, `/assignments`,
 * `/discussion_topics`, `/users` and the rest are the same surfaces on every Canvas host. The
 * verified 2026-07-29 map of `canvas.butte.edu/courses/31407` (135 pages, all six criteria) is where
 * this table comes from: these are the eleven course-level indexes that crawl actually reached, not
 * a guess from documentation.
 *
 * The profile PROPOSES; capture still verifies. Every seeded index goes through the same
 * `isCrawlableLink`/`withinScope` gate and the same landed-URL assert as an agent-reported one, so a
 * seeded surface that does not exist records a failure instead of inventing a page.
 */

/** Course-level indexes, in the order the document should read. Names match the Canvas nav. */
export const CANVAS_SURFACES: { name: string; suffix: string }[] = [
  { name: 'Modules', suffix: '/modules' },
  { name: 'Pages', suffix: '/pages' },
  { name: 'Assignments', suffix: '/assignments' },
  { name: 'Quizzes', suffix: '/quizzes' },
  { name: 'Discussions', suffix: '/discussion_topics' },
  { name: 'Announcements', suffix: '/announcements' },
  { name: 'Syllabus', suffix: '/assignments/syllabus' },
  { name: 'Files', suffix: '/files' },
  { name: 'Outcomes', suffix: '/outcomes' },
  { name: 'Rubrics', suffix: '/rubrics' },
  { name: 'Collaborations', suffix: '/lti_collaborations' },
];

/**
 * The `https://host/courses/<id>` base of a Canvas URL, or null if this is not one.
 *
 * Matches the course root and any page inside it, since a crawl may be started from anywhere in the
 * course — `/courses/31407/assignments/844633` is still course 31407. The id must be numeric:
 * `/courses/new` is not a course.
 */
export function canvasCourseBase(url: string): string | null {
  try {
    const u = new URL(url);
    const m = u.pathname.match(/^\/courses\/(\d+)(?:\/|$)/);
    return m ? `${u.origin}/courses/${m[1]}` : null;
  } catch {
    return null;
  }
}

/**
 * Sections for a Canvas course, or null to fall back to the survey agent.
 *
 * The course nav decides WHETHER the profile applies, not WHICH surfaces it seeds. That split was
 * measured, not assumed: on the verified course 31407 the nav links eleven surfaces and `/pages` is
 * not among them, yet `/courses/31407/pages` is in the map as a captured section with its own index.
 * A hidden Canvas tab is hidden from STUDENTS; the page still serves for a teacher, which is who
 * this app is. Gating on the nav would therefore have silently dropped a real section.
 *
 * What the nav is good for is proving we are looking at a signed-in Canvas course at all. A logged
 * out Canvas URL redirects to SSO and renders no course nav, so requiring `MIN_SURFACES` matches
 * before trusting the profile is what stops a login wall from seeding eleven sections that all fail.
 * Below that, and for every non-Canvas site, the answer is null and the survey runs as before.
 */
export const MIN_SURFACES = 3;

export function canvasSections(startUrl: string, links: { href: string }[]): SurveySection[] | null {
  const base = canvasCourseBase(startUrl);
  if (!base) return null;

  // Compare by pathname: the nav's hrefs are same-origin and may or may not carry a query.
  const paths = new Set<string>();
  for (const l of links) {
    try {
      paths.add(new URL(l.href, base).pathname.replace(/\/$/, ''));
    } catch {
      /* skip an unparseable href */
    }
  }
  const basePath = new URL(base).pathname;
  // Any course-scoped surface counts as evidence of a rendered nav, including the people surfaces —
  // they are proof of a signed-in course even though the profile never seeds them itself.
  const evidence = [...CANVAS_SURFACES.map((s) => s.suffix), '/users', '/grades']
    .filter((suffix) => paths.has(basePath + suffix)).length;
  if (evidence < MIN_SURFACES) return null;

  return CANVAS_SURFACES.map((s) => ({
    name: s.name,
    indexUrl: base + s.suffix,
    sampleUrl: '', // capture enumerates the members; the profile never claims to know them
    estimatedPages: 1, // advisory only — the real count comes from reading the index
  }));
}

import { describe, it, expect } from 'vitest';
import { canvasCourseBase, canvasSections, CANVAS_SURFACES, MIN_SURFACES } from './canvas-profile';

const nav = (base: string, suffixes: string[]) => suffixes.map((s) => ({ href: base + s }));
const allSuffixes = CANVAS_SURFACES.map((s) => s.suffix);

describe('canvasCourseBase', () => {
  it('finds the course base from the course root and from any page inside it', () => {
    expect(canvasCourseBase('https://canvas.butte.edu/courses/31407')).toBe('https://canvas.butte.edu/courses/31407');
    // A crawl can be started anywhere in the course — this is still course 31407.
    expect(canvasCourseBase('https://canvas.butte.edu/courses/31407/assignments/844633?module_item_id=1904084'))
      .toBe('https://canvas.butte.edu/courses/31407');
  });
  it('rejects a non-numeric id and a non-course URL', () => {
    expect(canvasCourseBase('https://canvas.butte.edu/courses/new')).toBeNull();
    expect(canvasCourseBase('https://canvas.butte.edu/profile')).toBeNull();
    expect(canvasCourseBase('https://www.myopenmath.com/course/course.php?cid=193698')).toBeNull();
    expect(canvasCourseBase('not a url')).toBeNull();
  });
});

describe('canvasSections — the nav proves it is Canvas; the table decides what to seed', () => {
  const base = 'https://canvas.butte.edu/courses/31407';

  it('seeds every surface the course nav offers', () => {
    const s = canvasSections(base, nav(base, allSuffixes))!;
    expect(s).not.toBeNull();
    expect(s.map((x) => x.name)).toEqual(CANVAS_SURFACES.map((x) => x.name));
    expect(s[0].indexUrl).toBe(`${base}/modules`);
    // The profile never claims to know members — capture enumerates them.
    expect(s.every((x) => x.sampleUrl === '')).toBe(true);
  });

  it('seeds a surface the nav does not link — a hidden tab still serves for a teacher', () => {
    // Measured on course 31407: the nav links eleven surfaces and /pages is NOT one of them, yet
    // /courses/31407/pages is in the verified map as a captured section. Gating on the nav would
    // silently drop a real section, so the nav decides WHETHER the profile applies, not WHICH
    // surfaces it seeds.
    const withoutPages = allSuffixes.filter((x) => x !== '/pages');
    const names = canvasSections(base, nav(base, withoutPages))!.map((x) => x.name);
    expect(names).toContain('Pages');
    expect(names).toEqual(CANVAS_SURFACES.map((x) => x.name));
  });

  it('falls back to the survey when no course nav rendered — a login wall must not seed', () => {
    // Logged out, Canvas redirects to SSO and renders no course nav. Seeding eleven sections there
    // would produce eleven failures and a map of a login page.
    expect(canvasSections(base, nav(base, allSuffixes.slice(0, MIN_SURFACES - 1)))).toBeNull();
    expect(canvasSections(base, [])).toBeNull();
  });

  it('counts a people surface as evidence of a signed-in course, without seeding it', () => {
    const s = canvasSections(base, nav(base, ['/users', '/grades', '/modules']))!;
    expect(s).not.toBeNull();
    expect(s.map((x) => x.indexUrl).some((u) => /\/users|\/grades/.test(u))).toBe(false);
  });

  it('returns null for a non-Canvas site, which is most of them', () => {
    const mom = 'https://www.myopenmath.com/course/course.php?cid=193698';
    expect(canvasSections(mom, [{ href: mom }])).toBeNull();
  });

  it('does not seed people surfaces — those are seeded index-only elsewhere', () => {
    // /users and /grades must stay on the peopleSections path, which maps their SHAPE and never
    // walks them per student. A profile that listed them as ordinary sections would undo that.
    const s = canvasSections(base, nav(base, [...allSuffixes, '/users', '/grades']))!;
    expect(s.map((x) => x.indexUrl).some((u) => /\/users|\/grades/.test(u))).toBe(false);
  });

  it('recognises the nav however the href is written', () => {
    // Canvas renders relative hrefs; a trailing slash is still the same surface. Three matches is
    // enough evidence of a rendered nav, and the full surface list is then seeded.
    const s = canvasSections(base, [
      { href: '/courses/31407/modules/' },
      { href: '/courses/31407/pages' },
      { href: 'https://canvas.butte.edu/courses/31407/assignments' },
    ])!;
    expect(s.map((x) => x.name)).toEqual(CANVAS_SURFACES.map((x) => x.name));
  });
});

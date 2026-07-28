import { describe, it, expect } from 'vitest';
import { buildPeoplePointer, toTemplate, surfaceName, upsertPointer, pointerLeaks, looksLikeRoster } from './people-pointer';
import type { SiteProfile } from './types/site-profile';

// Shaped after the real stored gradebook profile: storage redaction has already turned the
// person id into ⟦STU⟧, so the per-person href is one substitution away from a usable template.
const gradebook = (): SiteProfile =>
  ({
    domain: 'www.myopenmath.com',
    url: 'https://www.myopenmath.com/course/gradebook.php?cid=316341',
    pageName: 'gradebook.php',
    profiledAt: '',
    interactive: {
      buttons: [
        { text: 'Export CSV', selector: '#export' },
        { text: 'Item analysis', selector: '#ia' },
        { text: 'Doe, Jane', selector: 'tr:nth-child(2) button' }, // a row label IS the person
      ],
      links: [
        { text: 'Doe, Jane', selector: 'tr:nth-child(2) a', href: 'https://www.myopenmath.com/course/gradebook.php?cid=316341&stu=⟦STU⟧' },
        { text: 'Course home', selector: '#home', href: 'https://www.myopenmath.com/course/course.php?cid=316341' },
      ],
      inputs: [], selects: [], checkboxes: [], radios: [], forms: [],
    },
    summary: '',
  }) as unknown as SiteProfile;

// A name list only ever contains the surfaces someone already thought of. A live MyOpenMath
// course serves latepasses.php, logingrid.php, gbcomments.php, coursereports.php and
// gb-itemresults2.php — every one per-student, none recognisable by name.
describe('looksLikeRoster — classify by what the page holds, not what it is called', () => {
  const page = (labels: string[]): SiteProfile =>
    ({ domain: 'm.com', url: 'https://m.com/course/latepasses.php?cid=1',
       interactive: { buttons: labels.map((t, i) => ({ text: t, selector: `#b${i}` })), links: [], inputs: [] } }) as unknown as SiteProfile;

  it('flags a page carrying several person-shaped labels, whatever its URL', () => {
    expect(looksLikeRoster(page(['Doe, Jane', 'Nguyen, Alice', 'Smith, Robert', 'Save']))).toBe(true);
  });

  it('does not flag ordinary UI wording', () => {
    expect(looksLikeRoster(page(['Course Map', 'Data Sets', 'Export CSV', 'Late Passes']))).toBe(false);
  });

  it('needs three — one or two capitalised pairs are just labels', () => {
    expect(looksLikeRoster(page(['Doe, Jane', 'Course Map']))).toBe(false);
  });

  // Measured on the live site, not invented. This exact footer appears on MyOpenMath's login page
  // and on 66 of 305 real course pages; under the old "5 plain names" rule every one of them
  // scored as a roster and would have had its labels scrubbed and a junk pointer written.
  it('does not flag the site footer, however many capitalised pairs it has', () => {
    const footer = ['For Instructors', 'About Us', 'Forgot Password', 'Forgot Username', 'Privacy Policy'];
    expect(looksLikeRoster(page(footer))).toBe(false);
    expect(looksLikeRoster(page([...footer, 'Course Map', 'Data Sets']))).toBe(false);
  });
});

describe('people pointers — record the route, never the person', () => {
  it('turns a redacted per-person URL into a named slot', () => {
    expect(toTemplate('https://m.com/g.php?cid=1&stu=⟦STU⟧')).toEqual({
      template: 'https://m.com/g.php?cid=1&stu={studentId}',
      param: 'stu',
    });
  });

  it('ignores a URL that addresses nobody', () => {
    expect(toTemplate('https://m.com/course.php?cid=1')).toBeNull();
  });

  // Captured hrefs are routinely relative. Without a base the template is not navigable and
  // `new URL()` throws, which is what left `slot: null` on the first real pointer written.
  it('resolves a relative href against the index, and still finds the slot param', () => {
    expect(toTemplate('gradebook.php?cid=1&stu=⟦STU⟧', 'https://m.com/course/gradebook.php?cid=1')).toEqual({
      template: 'https://m.com/course/gradebook.php?cid=1&stu={studentId}',
      param: 'stu',
    });
  });

  it('handles a percent-encoded token', () => {
    const t = toTemplate('https://m.com/g.php?stu=%E2%9F%A6STU%E2%9F%A7');
    expect(t?.template).toContain('{studentId}');
  });

  it('names the surface from its script', () => {
    expect(surfaceName('https://m.com/course/gradebook.php?cid=1')).toBe('gradebook');
    expect(surfaceName('https://c.edu/courses/31407/users')).toBe('users');
  });

  it('keeps the index URL navigable — the course id is not redacted away', () => {
    expect(buildPeoplePointer(gradebook()).index).toContain('cid=316341');
  });

  it('emits a per-person template with the slot named', () => {
    const roster = 'https://www.myopenmath.com/course/listusers.php?cid=316341';
    const p = buildPeoplePointer(gradebook(), roster);
    expect(p.perPerson).toEqual(['https://www.myopenmath.com/course/gradebook.php?cid=316341&stu={studentId}']);
    expect(p.slot).toEqual({ param: 'stu', resolveFrom: roster });
  });

  it('falls back to its own index when no roster is known', () => {
    expect(buildPeoplePointer(gradebook()).slot?.resolveFrom).toContain('gradebook.php');
  });

  // The whole point: a row label is DROPPED, not tokenized. There is nothing left to scrub.
  it('drops labels that read as a person and keeps real actions', () => {
    const p = buildPeoplePointer(gradebook());
    expect(p.controls).toContain('Export CSV');
    expect(p.controls).toContain('Item analysis');
    expect(p.controls).not.toContain('Doe, Jane');
    expect(p.droppedPersonLabels).toBe(2); // the button and the link both carried the name
    expect(JSON.stringify(p)).not.toContain('Doe');
  });

  it('upserts by index so a re-capture updates instead of duplicating', () => {
    const a = buildPeoplePointer(gradebook());
    expect(upsertPointer([a], buildPeoplePointer(gradebook()))).toHaveLength(1);
  });

  describe('pointerLeaks — prove it before writing it', () => {
    it('passes a clean pointer set', () => {
      expect(pointerLeaks([buildPeoplePointer(gradebook())])).toEqual([]);
    });

    it('catches a literal id that escaped into a template', () => {
      const p = buildPeoplePointer(gradebook());
      p.perPerson = ['https://m.com/g.php?cid=1&stu=7158619'];
      expect(pointerLeaks([p]).join(' ')).toContain('literal id');
    });

    it('catches a person-shaped control label', () => {
      const p = buildPeoplePointer(gradebook());
      p.controls = ['Nguyen, Alice'];
      expect(pointerLeaks([p]).join(' ')).toContain('reads as a person');
    });
  });
});

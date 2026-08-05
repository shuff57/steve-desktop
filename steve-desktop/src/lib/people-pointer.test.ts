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

  // The local `commaForm` regex this function used to carry was ASCII-only, same gap as the live
  // mask before this session's Unicode fix — it is now a substring check on top of
  // looksLikePersonName instead (see the function's own comment for why that's still correct
  // comma-only detection). This is the LAST LINE of defence for a roster whose URL doesn't
  // already read as one; under-detecting here silently drops the whole plain-name masking tier
  // for that page, not just a missed classification.
  it('flags a page whose roster is entirely accented and particle-form comma names', () => {
    expect(looksLikeRoster(page(['García, José', 'van der Berg, Willem', "O'Brien, Katie", 'Save']))).toBe(true);
  });
});

describe('people pointers — record the route, never the person', () => {
  it('turns a redacted per-person URL into a named slot', () => {
    expect(toTemplate('https://m.com/g.php?cid=1&stu=⟦STU⟧')).toEqual({
      template: 'https://m.com/g.php?cid=1&stu={studentId}',
      param: 'stu',
      in: 'query',
    });
  });

  // Canvas puts the person in the PATH. A live run produced the right template and slot:null,
  // because the slot finder only ever read searchParams — the shape without what fills it.
  it('locates a person id held in a path segment, naming the segment before it', () => {
    expect(toTemplate('https://canvas.butte.edu/courses/31407/users/⟦STU⟧')).toEqual({
      template: 'https://canvas.butte.edu/courses/31407/users/{studentId}',
      param: 'users',
      in: 'path',
    });
    expect(toTemplate('https://canvas.butte.edu/courses/31407/assignments/844633/submissions/⟦STU⟧')?.param)
      .toBe('submissions');
  });

  it('carries the path slot through to the pointer', () => {
    const roster = 'https://canvas.butte.edu/courses/31407/users';
    const canvas = {
      domain: 'canvas.butte.edu',
      url: roster,
      interactive: {
        buttons: [{ text: 'Settings', selector: '#s' }],
        links: [{ text: '⟦STU⟧', selector: 'tr a', href: 'https://canvas.butte.edu/courses/31407/users/⟦STU⟧' }],
        inputs: [],
      },
    } as unknown as SiteProfile;
    expect(buildPeoplePointer(canvas, roster).slot).toEqual({ param: 'users', in: 'path', resolveFrom: roster });
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
      in: 'query',
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
    expect(p.slot).toEqual({ param: 'stu', in: 'query', resolveFrom: roster });
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

  // Fix 2: buildPeoplePointer used to gate the row-label drop with a hand-duplicated regex that
  // missed Mc/Mac/O'/particle surnames — a review found "McDonald, Sean" surviving into
  // controls[], a field documented as never holding a person. Now shares redact-tree.ts's
  // looksLikePersonName, so this exercises the SAME fix at the drop site, not just the
  // safety-assertion site below.
  describe('drops Mc/Mac/O\'/particle-shaped labels too, not just plain ones', () => {
    const withButtons = (buttons: string[]): SiteProfile =>
      ({
        domain: 'm.com', url: 'https://m.com/course/gradebook.php?cid=1',
        interactive: { buttons: buttons.map((t, i) => ({ text: t, selector: `#b${i}` })), links: [], inputs: [] },
      }) as unknown as SiteProfile;

    it('drops "McDonald, Sean" — the exact leak a cold review found', () => {
      const p = buildPeoplePointer(withButtons(['McDonald, Sean', 'Export CSV']));
      expect(p.controls).not.toContain('McDonald, Sean');
      expect(p.controls).toContain('Export CSV');
    });

    it('drops a Dutch particle surname', () => {
      const p = buildPeoplePointer(withButtons(['van der Berg, Willem']));
      expect(p.controls).toHaveLength(0);
    });

    it("drops an O'-apostrophe surname", () => {
      const p = buildPeoplePointer(withButtons(["O'Brien, Katie"]));
      expect(p.controls).toHaveLength(0);
    });

    // Unicode fix: [A-Z]/[a-z] were ASCII-only, so an accented name never matched anything, in
    // any tier — the most serious leak found this session. Plain form and comma form both.
    it('drops an accented comma-form name', () => {
      const p = buildPeoplePointer(withButtons(['García, José']));
      expect(p.controls).toHaveLength(0);
    });

    it('drops an accented plain-form name', () => {
      const p = buildPeoplePointer(withButtons(['José García', 'Export CSV']));
      expect(p.controls).not.toContain('José García');
      expect(p.controls).toContain('Export CSV');
    });
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

    // Same fix, the safety-net side: pointerLeaks used to share the identical blind spot as the
    // drop it is meant to catch, so a leaked Mc/particle label would sail through both.
    it('catches a Mc/Mac-shaped control label', () => {
      const p = buildPeoplePointer(gradebook());
      p.controls = ['McDonald, Sean'];
      expect(pointerLeaks([p]).join(' ')).toContain('reads as a person');
    });

    it('catches a particle-shaped control label', () => {
      const p = buildPeoplePointer(gradebook());
      p.controls = ['van der Berg, Willem'];
      expect(pointerLeaks([p]).join(' ')).toContain('reads as a person');
    });

    it("catches an O'-apostrophe control label", () => {
      const p = buildPeoplePointer(gradebook());
      p.controls = ["O'Brien, Katie"];
      expect(pointerLeaks([p]).join(' ')).toContain('reads as a person');
    });

    it('catches an accented control label', () => {
      const p = buildPeoplePointer(gradebook());
      p.controls = ['García, José'];
      expect(pointerLeaks([p]).join(' ')).toContain('reads as a person');
    });
  });
});

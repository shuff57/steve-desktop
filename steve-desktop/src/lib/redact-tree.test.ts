import { describe, it, expect } from 'vitest';
import { redactTree, isChromeNode, redactProfileForStorage, redactUrlForStorage, sweepPattern } from './redact-tree';
import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';

function node(tag: string, text: string, attrs: Record<string, string> = {}): SnapshotNode {
  return { tag, depth: 1, priority: 'medium', text, attrs };
}
function snap(nodes: SnapshotNode[]): SnapshotResult {
  return {
    nodes,
    meta: { totalVisited: nodes.length, nodesIncluded: nodes.length, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: '2026-06-23T00:00:00.000Z' },
  };
}

// Every case below was measured on a live MyOpenMath document, not invented. The item-type column
// of a course listing put "Forum" and "Assessment" into the dictionary as DATA, and substring
// matching then rewrote the site's own vocabulary everywhere it appeared.
describe('sweepPattern — vocabulary matches on word boundaries, identifiers do not', () => {
  const swap = (value: string, text: string) => text.replace(sweepPattern(value), '<T>');

  it('stops a data word from eating URL paths and plurals', () => {
    expect(swap('Forum', 'https://m.com/forums/forums.php?cid=1')).toBe('https://m.com/forums/forums.php?cid=1');
    expect(swap('Assess', '/course/showassess.php?cid=1')).toBe('/course/showassess.php?cid=1');
    expect(swap('assignment', 'Enumerate assignments and links')).toBe('Enumerate assignments and links');
  });

  it('still redacts a name in free text, and as a whole label', () => {
    expect(swap('Doe, Jane', 'Contact Doe, Jane about the quiz')).toBe('Contact <T> about the quiz');
    expect(swap('Doe, Jane', 'Doe, Jane')).toBe('<T>');
  });

  it('does not touch a DIFFERENT person who merely shares a prefix', () => {
    expect(swap('Jane', 'Janet Wilson')).toBe('Janet Wilson');
  });

  // The FERPA-critical half: ids keep substring matching, because an id can be fused into a
  // longer token with no boundary to anchor to.
  it('redacts an identifier even with no word boundary around it', () => {
    expect(swap('7158619', 'gradebook.php?cid=1&stu=7158619')).toBe('gradebook.php?cid=1&stu=<T>');
    expect(swap('7158619', 'user7158619profile')).toBe('user<T>profile');
    expect(swap('a7f3b21', 'xa7f3b21x')).toBe('x<T>x');
  });

  it('handles a value whose own edges are punctuation', () => {
    expect(swap('(Copy)', 'Template (Copy) here')).toBe('Template <T> here');
  });
});

// A live Canvas crawl put 26 real student names on disk — discussion-board post authors. The page
// is course content (correctly not a people surface) and the names are plain "Jane Doe", so
// neither the URL test nor looksLikeRoster could see them. The LINK could: every author is
// <a href="/courses/31407/users/12345">. Verified against the stored crawl: 26 before, 0 after.
describe('person-linked labels — a label on a link that addresses one person IS that person', () => {
  const page = (links: { text: string; href: string }[], extra: Record<string, unknown> = {}) =>
    ({ domain: 'canvas.butte.edu', url: 'https://canvas.butte.edu/courses/31407/discussion_topics/416993',
       interactive: { links, buttons: [], inputs: [] }, ...extra }) as never;
  const store = (p: never) => JSON.stringify(redactProfileForStorage(p, (t) => t, false));

  it('scrubs a discussion author, on a page that is NOT a people surface', () => {
    const out = store(page([
      { text: 'Jane Doe', href: 'https://canvas.butte.edu/courses/31407/users/12345' },
      { text: 'Unit 4 Discussion', href: 'https://canvas.butte.edu/courses/31407/discussion_topics/416994' },
    ]));
    expect(out).not.toContain('Jane Doe');
    expect(out).toContain('⟦STU⟧');
    expect(out).toContain('Unit 4 Discussion'); // content titles must survive
  });

  it('covers the MyOpenMath shape too — a person-selecting query parameter', () => {
    expect(store(page([{ text: 'Doe, Jane', href: '/course/gradebook.php?cid=1&stu=7158619' }]))).not.toContain('Doe');
  });

  it('scrubs the same name everywhere on the page, not just on the link', () => {
    const out = store(page([{ text: 'Jane Doe', href: '/courses/31407/users/12345' }],
      { interactive: { links: [{ text: 'Jane Doe', href: '/courses/31407/users/12345', selector: 'a[title="Jane Doe"]' }], buttons: [], inputs: [] } }));
    expect(out).not.toContain('Jane Doe');
  });

  it('leaves a page with no person-pointing links untouched', () => {
    const out = store(page([{ text: 'Course Syllabus', href: '/courses/31407/assignments/syllabus' }]));
    expect(out).toContain('Course Syllabus');
    expect(out).not.toContain('⟦STU⟧');
  });
});

describe('isChromeNode (allow-list of labels/controls)', () => {
  it('keeps interactive controls, headings, and field labels', () => {
    expect(isChromeNode(node('button', 'Submit'))).toBe(true);
    expect(isChromeNode(node('a', 'Home'))).toBe(true);
    expect(isChromeNode(node('h1', 'Enter Grades'))).toBe(true);
    expect(isChromeNode(node('th', 'Student Name'))).toBe(true);
    expect(isChromeNode(node('label', 'Grade'))).toBe(true);
    expect(isChromeNode(node('div', 'x', { role: 'button' }))).toBe(true);
  });
  it('treats data containers as NOT chrome (so their content is redacted)', () => {
    expect(isChromeNode(node('td', 'Jane Doe'))).toBe(false);
    expect(isChromeNode(node('input', '', { value: '4471' }))).toBe(false);
    expect(isChromeNode(node('p', 'free text note'))).toBe(false);
    expect(isChromeNode(node('span', 'whatever'))).toBe(false);
  });
});

describe('redact() — apply the value dictionary to arbitrary text (redact-before-save)', () => {
  it('tokenizes a roster name embedded in a profile selector, so saved JSON holds no name', () => {
    // A gradebook page where the student name is a data cell.
    const out = redactTree(snap([node('td', 'De Jesus, Angel', { 'data-field': 'studentName' })]));
    // mergedToProfile would persist the name inside a selector like role=…[name="…"].
    const profileJson = JSON.stringify({
      interactive: { inputs: [{ label: 'De Jesus, Angel', selector: 'role=checkbox[name="De Jesus, Angel"]' }] },
    });
    const safe = out.redact(profileJson);
    expect(safe).not.toContain('De Jesus, Angel');
    expect(JSON.parse(safe).interactive.inputs[0].label).toMatch(/⟦D\d+⟧/);
    // Round-trips back on-device if ever needed.
    expect(out.rehydrate(safe)).toContain('De Jesus, Angel');
  });

  it('leaves chrome labels untouched (no known value to swap)', () => {
    const out = redactTree(snap([node('button', 'Save Question')]));
    expect(out.redact('{"text":"Save Question"}')).toBe('{"text":"Save Question"}');
  });
});

describe('redactTree — slot-level, deny-by-default', () => {
  it('redacts a data cell even though its value is in NO secret list (the dictionary would miss it)', () => {
    const out = redactTree(snap([node('td', 'Jane Doe', { 'data-field': 'studentName' })]));
    expect(out.redactedText).not.toContain('Jane Doe');
    expect(out.rehydrate(out.redactedText)).toContain('Jane Doe');
  });

  it('keeps chrome (headings, buttons, column headers, labels) so the model can still act', () => {
    const out = redactTree(snap([
      node('h1', 'Enter Grades'),
      node('th', 'Student Name'),
      node('label', 'Grade'),
      node('button', 'Submit'),
      node('td', 'Jane Doe'),
    ]));
    expect(out.redactedText).toContain('Enter Grades');
    expect(out.redactedText).toContain('Student Name'); // the column label, not a person
    expect(out.redactedText).toContain('Submit');
    expect(out.redactedText).not.toContain('Jane Doe'); // the data
  });

  it('redacts an input value regardless of contents', () => {
    const out = redactTree(snap([node('input', '', { name: 'grade', value: 'A+', type: 'text' })]));
    expect(out.redactedText).not.toContain('A+');
    expect(out.rehydrate(out.redactedText)).toContain('A+');
  });

  it('redacts PII carried in a computed accessible name on a data node', () => {
    const out = redactTree(snap([node('td', '', { role: 'cell', 'aria-label': 'Student Jane Doe, ID 4471' })]));
    expect(out.redactedText).not.toContain('Jane Doe');
    expect(out.redactedText).not.toContain('4471');
  });

  it('redacts unknown PII in free text (deny-by-default), defeating the dictionary entirely', () => {
    const out = redactTree(snap([node('p', "call Jane about Bobby's IEP")]));
    expect(out.redactedText).not.toContain('Jane');
    expect(out.redactedText).not.toContain('Bobby');
  });

  it('gives the same value one stable token within a snapshot, and round-trips', () => {
    const out = redactTree(snap([
      node('td', 'Jane Doe'),
      node('td', 'Jane Doe'),
      node('td', 'A'),
    ]));
    // both "Jane Doe" cells share one token; "A" is different
    const tokens = Object.keys(out.map);
    expect(Object.values(out.map)).toContain('Jane Doe');
    expect(out.rehydrate(out.redactedText)).toContain('Jane Doe');
    expect(tokens.length).toBe(2); // "Jane Doe" + "A"
  });

  it('second-pass dictionary strips a KNOWN identifier even if it appears inside a chrome node', () => {
    const out = redactTree(snap([node('h2', 'Welcome back, Jane Doe')]), { extraSecrets: ['Jane Doe'] });
    expect(out.redactedText).not.toContain('Jane Doe');
  });
});

describe('redactProfileForStorage — the hostname must survive redaction', () => {
  // The real incident: a course named "Math 12" put "Math" in the redactor's value list, and
  // the swap is a case-insensitive substring with no word boundary, so it rewrote the "math"
  // inside myopenmath.com. Every capture saved to a different bogus directory (one per token
  // index) and no site map was ever written where anything could find it.
  const redact = (t: string) => t.replace(/math/gi, '⟦D15⟧').replace(/Jane Doe/g, '⟦D2⟧');

  it('keeps the domain intact even when a page value appears inside it', () => {
    const profile = {
      url: 'https://www.myopenmath.com/course/gradebook.php?cid=306621&uid=7158619',
      domain: 'www.myopenmath.com',
      pageName: 'gradebook.php',
      student: 'Jane Doe',
    };
    const safe = redactProfileForStorage(profile, redact);
    expect(safe.domain).toBe('www.myopenmath.com');
  });

  it('still redacts page content, so nothing is un-redacted to save a filename', () => {
    const profile = { domain: 'www.myopenmath.com', student: 'Jane Doe', note: 'Math 12 roster' };
    const safe = redactProfileForStorage(profile, redact);
    expect(safe.student).not.toContain('Jane');
    expect(safe.note).not.toContain('Math');
  });

  // Measured on a fresh 306-page capture of a live course: 3 distinct student names reached
  // disk in course-gradebook-*.json and course-listusers-*.json. redactTree keeps control
  // labels on purpose (a map of ⟦D7⟧ buttons is useless), but on a roster the label IS the
  // person, so those strings were never in the value dictionary.
  describe('people surfaces — the label is the person', () => {
    const noop = (t: string) => t;
    const roster = () => ({
      domain: 'www.myopenmath.com',
      url: 'https://www.myopenmath.com/course/listusers.php?cid=316341',
      interactive: {
        buttons: [
          { text: 'Doe, Jane', selector: 'tr:nth-child(2) button' },
          { text: 'Export CSV', selector: '#export' },
        ],
        links: [{ text: 'Alice Nguyen', selector: 'tr:nth-child(3) a' }],
        inputs: [{ label: 'Search', selector: '#q' }],
      },
    });

    it('tokenizes name-shaped control labels when the page lists people', () => {
      const safe = redactProfileForStorage(roster(), noop, true);
      expect(safe.interactive.buttons[0].text).toBe('⟦STU⟧');
      expect(safe.interactive.links[0].text).toBe('⟦STU⟧');
    });

    it('keeps real action labels — the map still has to be usable', () => {
      const safe = redactProfileForStorage(roster(), noop, true);
      expect(safe.interactive.buttons[1].text).toBe('Export CSV');
      expect(safe.interactive.inputs[0].label).toBe('Search');
    });

    it('leaves a positional selector alone, so automation can still drive the page', () => {
      const safe = redactProfileForStorage(roster(), noop, true);
      expect(safe.interactive.buttons[0].selector).toBe('tr:nth-child(2) button');
    });

    // Fixing only the visible label left 2 of the 3 measured names on disk: they were in the
    // accessible-name selector and its role-name candidate.
    it('tokenizes a name embedded in a selector or a selector candidate', () => {
      const p = {
        domain: 'm.com',
        url: 'https://m.com/course/listusers.php?cid=1',
        interactive: {
          buttons: [{
            text: 'Doe, Jane',
            selector: '[role=button][name="Doe, Jane"]',
            candidates: [{ type: 'role-name', value: 'Doe, Jane', score: 9 }],
          }],
          links: [], inputs: [],
        },
      };
      const safe = redactProfileForStorage(p, noop, true);
      const json = JSON.stringify(safe);
      expect(json).not.toContain('Doe');
      expect(json).not.toContain('Jane');
      expect(safe.interactive.buttons[0].selector).toContain('role=button');
    });

    it('keeps a gradebook column header that merely looks like two names', () => {
      const p = {
        domain: 'm.com',
        url: 'https://m.com/course/gradebook.php?cid=1',
        interactive: { buttons: [{ text: 'Total Score', selector: '#t' }], links: [], inputs: [] },
      };
      expect(redactProfileForStorage(p, noop, true).interactive.buttons[0].text).toBe('Total Score');
    });

    it('does NOT touch labels on an ordinary content page', () => {
      const safe = redactProfileForStorage(roster(), noop, false);
      expect(safe.interactive.buttons[0].text).toBe('Doe, Jane');
    });
  });

  it('survives a captured value containing a double quote', () => {
    // Live MyOpenMath: redaction ran over SERIALIZED JSON, so a page value carrying a quote ate
    // the JSON's own delimiters -> `"selector":⟦D…⟧` -> JSON.parse threw -> the entire capture
    // failed. The gradebook, coursemap and course home never made it into the map because of it.
    const quoted = (t: string) => t.replace(/"Top" student/g, '⟦D9⟧');
    const profile = {
      domain: 'x.edu',
      note: '"Top" student',
      interactive: { buttons: [{ text: 'Save', selector: '#save', candidates: [{ type: 'id', value: '#save', score: 80 }] }] },
    };
    const safe = redactProfileForStorage(profile, quoted);
    expect(safe.note).toBe('⟦D9⟧');
    expect(safe.interactive.buttons[0].selector).toBe('#save'); // structure survives intact
    expect(safe.interactive.buttons[0].candidates[0].value).toBe('#save');
    expect(() => JSON.parse(JSON.stringify(safe))).not.toThrow();
  });

  it('does not let a short numeric value rewrite ids inside the url', () => {
    // `163` captured as data matched INSIDE the course id 316341, producing cid=3⟦D526⟧41 —
    // a URL that can never load, later reported as unreachable.
    const shortNum = (t: string) => t.replace(/163/g, '⟦D526⟧');
    const profile = { domain: 'www.myopenmath.com', url: 'https://www.myopenmath.com/course/gradebook.php?cid=316341', score: '163' };
    const safe = redactProfileForStorage(profile, shortNum);
    expect(safe.url).toContain('cid=316341'); // id intact → page still navigable
    expect(safe.score).toBe('⟦D526⟧');        // the actual data value still scrubbed
  });

  it('keeps course/category/assignment ids legible — they carry no student information', () => {
    // Each of these was tokenized in a live run because its value appeared as page text, which
    // made the URL unloadable and got the page pruned as dead: cid=⟦D34⟧, cat=⟦D105⟧.
    const r = (t: string) => t.replace(/316341/g, '⟦D34⟧').replace(/105/g, '⟦D105⟧').replace(/21935970/g, '⟦D9⟧');
    const profile = {
      domain: 'www.myopenmath.com',
      url: 'https://www.myopenmath.com/course/gradebook.php?cid=316341&cat=105&aid=21935970&catcollapse=0&folder=0-2',
    };
    const safe = redactProfileForStorage(profile, r);
    for (const part of ['cid=316341', 'cat=105', 'aid=21935970', 'catcollapse=0', 'folder=0-2']) {
      expect(safe.url).toContain(part);
    }
  });

  it('drops a student id from a query even when it never appeared as page text', () => {
    // The dictionary only holds visible text, so an id living solely in a URL would otherwise be
    // stored raw. Student parameters are dropped unconditionally; a 0 means "no filter".
    const noop = (t: string) => t;
    const profile = {
      domain: 'www.myopenmath.com',
      url: 'https://www.myopenmath.com/course/gradebook.php?cid=316341&uid=7158619&filteruid=0',
    };
    const safe = redactProfileForStorage(profile, noop);
    expect(safe.url).not.toContain('7158619');
    expect(safe.url).toContain('cid=316341');
    expect(safe.url).toContain('filteruid=0'); // 0 identifies nobody — stays navigable
  });

  it('still redacts a textual query value in an unrecognized parameter', () => {
    const r = (t: string) => t.replace(/Jane Doe/g, '⟦D2⟧');
    const profile = { domain: 'x.edu', url: 'https://x.edu/p.php?who=Jane Doe&cid=12' };
    const safe = redactProfileForStorage(profile, r);
    expect(safe.url).not.toContain('Jane');
    expect(safe.url).toContain('cid=12');
  });

  it('keeps cid legible (a course id is not personal data) while scrubbing student ids', () => {
    // A live re-map left /msgs/* unloadable as cid=⟦D34⟧: the course id appeared as page TEXT
    // there, so the whole param value matched the dictionary. cid identifies a COURSE, so it
    // stays legible; uid/stu do not.
    const r = (t: string) => t.replace(/316341/g, '⟦D34⟧').replace(/7158619/g, '⟦D7⟧');
    const profile = {
      domain: 'www.myopenmath.com',
      url: 'https://www.myopenmath.com/msgs/msglist.php?cid=316341&uid=7158619',
    };
    const safe = redactProfileForStorage(profile, r);
    expect(safe.url).toContain('cid=316341');   // navigable
    expect(safe.url).not.toContain('7158619');  // student id still gone
  });

  it('scrubs a student id inside a link href instead of storing it raw', () => {
    // hrefs are navigation, but a gradebook link carries stu=/uid=. Exempting them wholesale
    // would write a student id straight into the saved site map.
    const r = (t: string) => t.replace(/7158619/g, '⟦D7⟧');
    const profile = {
      domain: 'www.myopenmath.com',
      interactive: { links: [{ text: 'x', selector: '#a', href: '/course/gradebook.php?cid=316341&uid=7158619' }] },
    };
    const safe = redactProfileForStorage(profile, r);
    const href = safe.interactive.links[0].href;
    expect(href).toContain('/course/gradebook.php'); // path intact
    expect(href).toContain('cid=316341');
    expect(href).not.toContain('7158619');
  });

  it('keeps the url navigable while still redacting its query', () => {
    // The host lives inside the url string too, so the same swap corrupted it: the site map
    // ended up full of https://www.⟦D15⟧.com/... which resolves to nothing.
    const profile = {
      domain: 'www.myopenmath.com',
      url: 'https://www.myopenmath.com/course/gradebook.php?stu=Jane Doe',
    };
    const safe = redactProfileForStorage(profile, redact);
    expect(safe.url).toContain('https://www.myopenmath.com/course/gradebook.php');
    expect(safe.url).not.toContain('Jane');  // query still scrubbed
  });

  it('keeps a structural filename legible even when it was tokenized as content, and names the page after it', () => {
    // The reported bug: `course.php` appeared as data on the page, so the value→token swap
    // rewrote it everywhere — url AND pageName — and the profile saved as d61.json.
    const withFile = (t: string) => redact(t).replace(/course\.php/g, '⟦D61⟧');
    const profile = {
      domain: 'www.myopenmath.com',
      url: 'https://www.myopenmath.com/course/course.php?cid=316341&folder=0',
      pageName: 'course.php',
    };
    const safe = redactProfileForStorage(profile, withFile);
    expect(safe.url).toBe('https://www.myopenmath.com/course/course.php?cid=316341&folder=0');
    expect(safe.pageName).toBe('course-course.php?cid=316341&folder=0'); // full path: last segment alone collided 112 ways // legible AND query-disambiguated
  });

  it('two same-filename pages get distinct names (no collision into one file)', () => {
    const p = (folder: string) =>
      redactProfileForStorage(
        { domain: 'www.myopenmath.com', url: `https://www.myopenmath.com/course/course.php?cid=316341&folder=${folder}`, pageName: 'course.php' },
        redact,
      ).pageName;
    expect(p('0')).not.toBe(p('0-9-1'));
  });
});

describe('redactUrlForStorage', () => {
  const redact = (t: string) => t.replace(/math/gi, '⟦D15⟧').replace(/Jane Doe/g, '⟦D2⟧');

  it('restores scheme+host+path and redacts only the query', () => {
    const r = redactUrlForStorage('https://www.myopenmath.com/course/math.php?a=Jane Doe', redact);
    expect(r.url).toBe('https://www.myopenmath.com/course/math.php?a=⟦D2⟧'); // math.php legible, query scrubbed
    expect(r.pageName).toBe('course-math.php?a=⟦D2⟧');
  });

  it('a query-less url stays fully legible', () => {
    const r = redactUrlForStorage('https://x.edu/a/b/gradebook.php', redact);
    expect(r.url).toBe('https://x.edu/a/b/gradebook.php');
    expect(r.pageName).toBe('a-b-gradebook.php'); // whole path — the last segment alone collided
  });

  it('falls back to a fully redacted string when the url will not parse', () => {
    expect(redactUrlForStorage('not a url with Jane Doe', redact).url).not.toContain('Jane');
  });

  it('keeps keyword query values the content dictionary would otherwise eat', () => {
    // Live defect: a crawl stored /pages/frames/?frame=i&family=⟦D5⟧ because "Cheloniidae"
    // also appeared in the page's table, so the token dictionary rewrote it inside the URL and
    // the page could no longer be loaded. Sibling params survived only by luck of not appearing
    // in the page text.
    const eats = (t: string) => t.replace(/Cheloniidae/g, '⟦D5⟧');
    const r = redactUrlForStorage('https://www.scrapethissite.com/pages/frames/?frame=i&family=Cheloniidae', eats);
    expect(r.url).toBe('https://www.scrapethissite.com/pages/frames/?frame=i&family=Cheloniidae');
  });

  it('still tokenizes free-text and email query values — keyword rule is not a bypass', () => {
    const r = redactUrlForStorage('https://x.edu/s.php?q=Jane Doe', redact);
    expect(r.url).not.toContain('Jane');
    const mail = (t: string) => t.replace(/jane@x\.edu/g, '⟦D9⟧');
    expect(redactUrlForStorage('https://x.edu/s.php?email=jane@x.edu', mail).url).not.toContain('jane@');
  });

  it('tokenizes a person id that lives in the PATH, not the query', () => {
    // Live leak: a Student View crawl of one real Canvas course wrote 53 distinct student ids
    // to disk as /users/<id>. No names came with them — the links are avatars with empty text —
    // but a Canvas user id identifies a student exactly as well as stu= does. redactUrlString
    // returned early on any URL without a query, assuming the path was only structure.
    const id = (t: string) => t;
    expect(redactUrlForStorage('https://canvas.butte.edu/users/9182734', id).url)
      .toBe('https://canvas.butte.edu/users/⟦STU⟧');
    expect(redactUrlForStorage('https://canvas.butte.edu/courses/31407/users/9182734', id).url)
      .toBe('https://canvas.butte.edu/courses/31407/users/⟦STU⟧'); // course id stays legible
  });

  it('tokenizes the user id Canvas hides behind submissions/ and grades/', () => {
    // Second live leak, found by sweeping the stored profiles after the /users/ fix shipped:
    // Canvas addresses a person's work by the person's id too, so 89 copies of one user id sat
    // on disk under people-less nouns. The assignment id in front of it is structure and stays.
    const id = (t: string) => t;
    expect(redactUrlForStorage('https://canvas.butte.edu/courses/31407/assignments/844633/submissions/127333', id).url)
      .toBe('https://canvas.butte.edu/courses/31407/assignments/844633/submissions/⟦STU⟧');
    expect(redactUrlForStorage('https://canvas.butte.edu/courses/31407/grades/127333', id).url)
      .toBe('https://canvas.butte.edu/courses/31407/grades/⟦STU⟧');
    // The whole-course gradebook has no id after it — it is a page, not a person.
    expect(redactUrlForStorage('https://canvas.butte.edu/courses/31407/grades', id).url)
      .toBe('https://canvas.butte.edu/courses/31407/grades');
  });

  it('scrubs a person id EMBEDDED in a selector or candidate', () => {
    // The href beside these was already tokenized, yet 178 raw ids (53 distinct) still reached
    // disk through interactive.links[].candidates[].value — selectors were returned verbatim as
    // "structure, not data". A selector can embed data.
    const profile = {
      domain: 'canvas.butte.edu',
      url: 'https://canvas.butte.edu/courses/31407',
      pageName: 'courses-31407',
      interactive: {
        links: [{
          text: 'x',
          href: 'https://canvas.butte.edu/users/9182734',
          selector: 'a[href="/users/9182734"]',
          candidates: [
            { type: 'href', value: 'a[href="/users/9182734"]' },
            { type: 'css', value: '#student_9182734 .name' },
            { type: 'href', value: 'a[href="/courses/31407/modules/items/1904067"]' },
          ],
        }],
      },
    };
    const out = JSON.stringify(redactProfileForStorage(profile, (t: string) => t));
    expect(out).not.toMatch(/9182734/);           // the person id is gone everywhere
    expect(out).toContain('modules/items/1904067'); // course structure survives
    expect(out).toContain('a[href=');               // the selector shape survives
  });

  it('leaves structural ids in the path alone', () => {
    const id = (t: string) => t;
    // Only a number directly after a person-ish segment is a person.
    expect(redactUrlForStorage('https://canvas.butte.edu/courses/31407/modules/items/1904067', id).url)
      .toBe('https://canvas.butte.edu/courses/31407/modules/items/1904067');
    expect(redactUrlForStorage('https://canvas.butte.edu/courses/31407/assignments/844636', id).url)
      .toBe('https://canvas.butte.edu/courses/31407/assignments/844636');
  });

  it('still tokenizes a student id however structural its value looks', () => {
    const r = redactUrlForStorage('https://x.edu/gradebook.php?cid=316341&stu=8842', (t) => t);
    expect(r.url).toBe('https://x.edu/gradebook.php?cid=316341&stu=⟦STU⟧'); // course legible, student not
  });
});

describe('redactUrlForStorage — page names must not collide across paths', () => {
  const noop = (t: string) => t;
  it('keeps sibling paginated pages distinct (the 112-way collision)', () => {
    // quotes.toscrape mapped 177 pages into 61 files: /page/1/, /tag/love/page/1/ and 110 more
    // all reduced to "1", so each overwrote the last and the map pointed pages at another
    // page's selectors.
    const names = ['/page/1/', '/tag/love/page/1/', '/tag/life/page/1/', '/tag/humor/page/2/']
      .map((p) => redactUrlForStorage('https://quotes.toscrape.com' + p, noop).pageName);
    expect(new Set(names).size).toBe(4);
    expect(names[1]).toBe('tag-love-page-1');
  });
  it('still disambiguates query-based pages and names a root page', () => {
    const a = redactUrlForStorage('https://x.edu/course/course.php?cid=1', noop).pageName;
    const b = redactUrlForStorage('https://x.edu/course/course.php?cid=2', noop).pageName;
    expect(a).not.toBe(b);
    expect(redactUrlForStorage('https://x.edu/', noop).pageName).toBe('home');
  });
  it('truncates a very long path but keeps it unique', () => {
    const long = (n: number) => 'https://x.edu/' + Array.from({ length: 30 }, (_, i) => `seg${i}${n}`).join('/');
    const a = redactUrlForStorage(long(1), noop).pageName;
    const b = redactUrlForStorage(long(2), noop).pageName;
    expect(a.length).toBeLessThanOrEqual(90);
    expect(a).not.toBe(b);
  });
});

import { describe, it, expect } from 'vitest';
import { redactTree, isChromeNode, redactProfileForStorage, redactUrlForStorage, sweepPattern, maskPersonNames, looksLikePersonName } from './redact-tree';
import { looksLikeRoster } from './people-pointer';
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

// Measured directly against the shape the grammar used to miss: PERSON_LABEL required exactly
// two ADJACENT capitalized words, so a lowercase particle broke the adjacency and a run longer
// than two masked only its leading pair, leaking the rest. Every row here is a case that used to
// leak — this table is the same one the reviewer probed against the live mask.
describe('maskPersonNames — particles, long runs, and O\'/Mc/Mac prefixes', () => {
  const maskFull = (s: string) => maskPersonNames(s, () => '⟦STU⟧');

  it('a two-part name still masks whole (unchanged baseline)', () => {
    expect(maskFull('Sarah Chen')).toBe('⟦STU⟧');
  });
  it('a hyphenated given name still masks whole (unchanged baseline)', () => {
    expect(maskFull('Jean-Luc Picard')).toBe('⟦STU⟧');
  });
  it('a particle-joined name masks WHOLE — used to leave "Maria" unmasked entirely', () => {
    expect(maskFull('Maria de la Cruz')).toBe('⟦STU⟧');
  });
  it('the comma form of a particle name masks whole, particles included — used to leak "de la"', () => {
    expect(maskFull('de la Cruz, Maria')).toBe('⟦STU⟧');
  });
  it('a 3-part name masks whole — used to leave the last part unmasked ("⟦STU⟧ An")', () => {
    expect(maskFull('Nguyen Van An')).toBe('⟦STU⟧');
  });
  it('a 4-part name masks whole when a particle justifies the run beyond two words', () => {
    expect(maskFull('Ana Maria de la Cruz')).toBe('⟦STU⟧');
  });
  it('a 4-part name with NO particle masks as two adjacent 2-word runs, not one — this is NOT ' +
    'a leak (every word is still masked), it is just two tokens instead of one: nothing beyond ' +
    'two plain words qualifies as a single run without a particle or O\'/Mc/Mac word (see ' +
    'NAME_RUN\'s doc comment for why that gate exists)', () => {
    expect(maskFull('Ana Maria Rodriguez Lopez')).toBe('⟦STU⟧ ⟦STU⟧');
  });
  it('an O\'-shaped word anywhere in a 3+ run justifies masking the whole run', () => {
    expect(maskFull('Katie O\'Brien Wilson')).toBe('⟦STU⟧');
  });
  it("an O'-apostrophe surname masks whole, in comma form — used to leave \"O'\" unmasked", () => {
    expect(maskFull("O'Brien, Katie")).toBe('⟦STU⟧');
  });
  it('a Mc/Mac-prefixed surname masks whole', () => {
    expect(maskFull('McDonald, Ryan')).toBe('⟦STU⟧');
    expect(maskFull('Ryan MacArthur')).toBe('⟦STU⟧');
  });

  // A cold review found these five FULL LEAKS on a roster URL — the particle list at the time had
  // "van" and "von" but not "der"/"den", and had no Arabic particles at all. The comment two lines
  // above the particle list already cited "van der Berg" as the motivating example while the code
  // could not actually match it — the drift this describe block (and the "vocabulary the doc
  // comment cites" test below) exists to catch.
  it('"Willem van der Berg" (Dutch) masks whole — used to leak entirely', () => {
    expect(maskFull('Willem van der Berg')).toBe('⟦STU⟧');
  });
  it('"Klaus von der Leyen" (German) masks whole — used to leak entirely', () => {
    expect(maskFull('Klaus von der Leyen')).toBe('⟦STU⟧');
  });
  it('"Jan van den Broek" (Dutch) masks whole — used to leak entirely', () => {
    expect(maskFull('Jan van den Broek')).toBe('⟦STU⟧');
  });
  it('"Fatima bint Mohammed" (Arabic) masks whole — used to leak entirely', () => {
    expect(maskFull('Fatima bint Mohammed')).toBe('⟦STU⟧');
  });
  it('"van der Berg, Willem" comma form masks whole — used to leak "van der"', () => {
    expect(maskFull('van der Berg, Willem')).toBe('⟦STU⟧');
  });
  it('Arabic and Scandinavian particles not in the original leak report also work', () => {
    expect(maskFull('Khalid ibn Walid')).toBe('⟦STU⟧');
    expect(maskFull('Omar abu Bakr')).toBe('⟦STU⟧');
    expect(maskFull('Ingrid af Klint')).toBe('⟦STU⟧');
    expect(maskFull('Maria dos Santos')).toBe('⟦STU⟧'); // already-supported particle, unaffected
  });

  // Pins the comment to the code: every name form the NAME_PARTICLE doc comment claims to cover
  // must actually mask. This is the exact drift a review caught — the comment named "van der
  // Berg" as the motivating example while the particle list could not match it, and nothing
  // tested that claim.
  it('every name form the NAME_PARTICLE doc comment claims to cover actually masks', () => {
    const claimed = [
      'Willem van der Berg',   // Dutch/German: van der
      'Jan van den Broek',     // Dutch: van den
      'Maria de la Cruz',      // Portuguese/Spanish/Italian: de la
      'Maria dos Santos',      // Portuguese: dos
      'Maria da Silva',        // Portuguese/Italian: da
      'Marco dello Iacono',    // Italian: dello
      'Marco della Valle',     // Italian: della
      'Marco degli Alberti',   // Italian: degli
      'Ingrid af Klint',       // Scandinavian: af
      'Khalid bin Walid',      // Arabic: bin
      'Fatima bint Mohammed',  // Arabic: bint
      'Khalid ibn Walid',      // Arabic: ibn
      'Omar abu Bakr',         // Arabic: abu
      'Nguyễn Văn An',         // Vietnamese: văn (male middle name marker)
      'Trần Thị Mai',          // Vietnamese: thị (female middle name marker)
    ];
    for (const name of claimed) expect(maskFull(name)).toBe('⟦STU⟧');
  });

  it('rehydrate round-trips a particle name exactly — a broken round-trip is how the ' +
    'profile-JSON corruption bug manifested previously', () => {
    const map = new Map<string, string>();
    const masked = maskPersonNames('Maria de la Cruz', (n) => {
      map.set('⟦STU⟧', n);
      return '⟦STU⟧';
    });
    const rehydrated = masked.split('⟦STU⟧').join(map.get('⟦STU⟧')!);
    expect(rehydrated).toBe('Maria de la Cruz');
  });

  it('rehydrate round-trips a 4-part particle-justified name exactly', () => {
    const map = new Map<string, string>();
    const masked = maskPersonNames('Ana Maria de la Cruz', (n) => {
      map.set('⟦STU⟧', n);
      return '⟦STU⟧';
    });
    const rehydrated = masked.split('⟦STU⟧').join(map.get('⟦STU⟧')!);
    expect(rehydrated).toBe('Ana Maria de la Cruz');
  });

  it('looksLikePersonName agrees with maskPersonNames on the same shapes', () => {
    expect(looksLikePersonName('Maria de la Cruz')).toBe(true);
    expect(looksLikePersonName('Nguyen Van An')).toBe(true);
    expect(looksLikePersonName("O'Brien, Katie")).toBe(true);
  });

  // Caught by direct measurement, not asked for: the particle/long-run grammar's first cut had
  // an unbounded repeated group directly in front of a mandatory NAME_WORD — a textbook
  // catastrophic-backtracking shape (PARTICLE_CONNECTOR's particle repetition, and separately
  // LONG_RUN_PARTICLE's own prefix/suffix). The second one is the scarier of the two: it needed
  // NO particle and NO adversarial input to trigger, just ORDINARY repeated gradebook chrome —
  // "Total Score Last Login Course Home Send Message", over and over, exactly what a real page
  // looks like — which took 5.4s at 24K chars and scaled quadratically. Both are now capped at 4
  // repetitions (redact-tree.ts). Budget is generous on purpose: this only needs to catch a
  // return to quadratic-or-worse scaling, not enforce a specific constant.
  it('does not catastrophically backtrack on repeated chrome text or a long particle run', () => {
    const chrome = 'Total Score Last Login Course Home Send Message '.repeat(2000); // ~96KB, no name at all
    const particles = 'Ana ' + 'de la '.repeat(3000) + 'x'; // no closing name — the failure case
    const t0 = performance.now();
    maskPersonNames(chrome, () => '⟦STU⟧');
    maskPersonNames(particles, () => '⟦STU⟧');
    expect(performance.now() - t0).toBeLessThan(500);
  });

  // The gate this whole revision adds: a run of 3+ capitalized words with NO particle and NO
  // special word is not itself proof of a name — it is indistinguishable from ordinary UI prose
  // by shape alone. This is the pre-existing, deliberately restored limit (see NAME_RUN's doc
  // comment) — only the leading pair masks, same as it did before ANY of this particle work.
  it('a plain 3-word run with no particle or special word only partial-masks (accepted, restored gap)', () => {
    expect(maskFull('Mary Jane Watson')).toBe('⟦STU⟧ Watson');
  });
});

// A first cut of the particle fix let ANY run of 3+ capitalized words mask whole, with no
// particle or special-word requirement — verified by stashing this file back to that version and
// re-running this exact probe. It roughly doubled the app's pre-existing over-masking (0ms on a
// 400-word run, so not a performance concern — a correctness one: the site map an agent navigates
// by went unusable). These six phrases are exactly what a reviewer probed against BOTH versions;
// each expectation below is the CONFIRMED pre-fix baseline, not an assumption that "everything
// survives" — three of these six were already partial-masked before any of this work started.
describe('the widened grammar does not over-mask ordinary UI phrases (regression a reviewer measured)', () => {
  const maskFull = (s: string) => maskPersonNames(s, () => '⟦STU⟧');

  it('"Save And Continue" partial-masks its leading pair — same as before any of this work', () => {
    expect(maskFull('Save And Continue')).toBe('⟦STU⟧ Continue');
  });
  it('"Add New Student" partial-masks its leading pair — same as before any of this work', () => {
    expect(maskFull('Add New Student')).toBe('⟦STU⟧ Student');
  });
  it('"Show Hidden Columns" partial-masks its leading pair — same as before any of this work', () => {
    expect(maskFull('Show Hidden Columns')).toBe('⟦STU⟧ Columns');
  });
  it('"Print Class Roster" is untouched — same as before any of this work', () => {
    expect(maskFull('Print Class Roster')).toBe('Print Class Roster');
  });
  it('"View Student Progress" is untouched — same as before any of this work', () => {
    expect(maskFull('View Student Progress')).toBe('View Student Progress');
  });
  it('"Late Work Policy" is untouched — same as before any of this work', () => {
    expect(maskFull('Late Work Policy')).toBe('Late Work Policy');
  });
});

// The widened grammar must not swallow ordinary gradebook chrome — the failure mode the original
// two-word-only design was measured to avoid (see the LABEL_WORD comment above).
describe('gradebook chrome survives the widened grammar', () => {
  const maskFull = (s: string) => maskPersonNames(s, () => '⟦STU⟧');
  const CHROME = [
    'Total Score', 'Last Login', 'Practice Test', 'Chapter Review',
    'All Students', 'Item Analysis', 'Course Home', 'Send Message',
  ];
  for (const label of CHROME) {
    it(`"${label}" is not masked`, () => {
      expect(maskFull(label)).toBe(label);
    });
  }
});

// The most serious defect found this session: [A-Z]/[a-z] are ASCII-only, so an accented name
// matched NOTHING, in any tier, full stop — not a narrow gap like the particle vocabulary, the
// character class itself. Fixed by switching every fragment to \p{Lu}/\p{Ll} (Unicode
// uppercase/lowercase letter) and adding the 'u' flag to every RegExp built from them — a
// \p{...} escape without 'u' is a SyntaxError, or worse, matches the literal characters "p{Lu}".
// Every row here is a byte-identical leak measured live before the fix.
describe('diacritics — the character class itself, not a vocabulary gap', () => {
  const maskFull = (s: string) => maskPersonNames(s, () => '⟦STU⟧');

  it('"José García" masks whole — used to leak entirely (ASCII-only [A-Z]/[a-z] matched nothing)', () => {
    expect(maskFull('José García')).toBe('⟦STU⟧');
  });
  it('"García, José" comma form masks whole — used to leak entirely', () => {
    expect(maskFull('García, José')).toBe('⟦STU⟧');
  });
  it('"María Fernández" masks whole — used to leak entirely', () => {
    expect(maskFull('María Fernández')).toBe('⟦STU⟧');
  });
  it('"Nguyễn Văn An" masks whole — used to leak entirely (also needed the văn particle, below)', () => {
    expect(maskFull('Nguyễn Văn An')).toBe('⟦STU⟧');
  });
  it('"Zoë Müller" masks whole — used to leak entirely', () => {
    expect(maskFull('Zoë Müller')).toBe('⟦STU⟧');
  });
  it('"François Dubois" masks whole — used to leak entirely', () => {
    expect(maskFull('François Dubois')).toBe('⟦STU⟧');
  });
  it('"Björn Andersson" masks whole — used to leak entirely', () => {
    expect(maskFull('Björn Andersson')).toBe('⟦STU⟧');
  });
  it('"Søren Kierkegaard" masks whole — used to leak entirely', () => {
    expect(maskFull('Søren Kierkegaard')).toBe('⟦STU⟧');
  });

  describe('crosses with everything else this grammar does', () => {
    it('accent + particle', () => {
      expect(maskFull('José de la Cruz')).toBe('⟦STU⟧');
      expect(maskFull('Ángel von der Leyen')).toBe('⟦STU⟧');
    });
    it('accent + infix hyphen', () => {
      expect(maskFull('Ana-Lucía Fernández')).toBe('⟦STU⟧'); // used to leak entirely
    });
    it("accent + apostrophe (O'-shape)", () => {
      expect(maskFull("Renée O'Neill")).toBe('⟦STU⟧'); // used to leak entirely
    });
    it('accent + comma form', () => {
      expect(maskFull('García, José')).toBe('⟦STU⟧');
      // Two-word given side with no particle: same restricted-trailing-chain rule NAME_GROUP
      // applies to an ASCII given side ("Smith, Mary Jane" -> "⟦STU⟧ Jane") — parity, not a new
      // Unicode-specific gap. Confirmed by testing the ASCII equivalent alongside it.
      expect(maskFull('Fernández, María José')).toBe('⟦STU⟧ José');
      expect(maskFull('Smith, Mary Jane')).toBe('⟦STU⟧ Jane');
    });
    it('accent + long run (4 words, no particle) masks as two adjacent runs, not a leak', () => {
      // Same "no particle to justify beyond two words" rule as the ASCII 4-part case
      // ("Ana Maria Rodriguez Lopez" -> "⟦STU⟧ ⟦STU⟧") — parity, not a new Unicode-specific gap.
      expect(maskFull('José María Rodríguez Gómez')).toBe('⟦STU⟧ ⟦STU⟧');
      expect(maskFull('John Michael Smith Jones')).toBe('⟦STU⟧ ⟦STU⟧');
    });
  });

  it('rehydrate round-trips an accented name exactly', () => {
    const map = new Map<string, string>();
    const masked = maskPersonNames('José García', (n) => {
      map.set('⟦STU⟧', n);
      return '⟦STU⟧';
    });
    expect(masked.split('⟦STU⟧').join(map.get('⟦STU⟧')!)).toBe('José García');
  });

  it('looksLikePersonName recognizes accented names', () => {
    expect(looksLikePersonName('José García')).toBe(true);
    expect(looksLikePersonName('García, José')).toBe(true);
    expect(looksLikePersonName('Total Score')).toBe(false); // still spared — LABEL_WORD unaffected
  });

  // Chrome survival and the false-positive-cost probe, re-run after widening the character
  // class itself (the biggest change of this whole session) — both must still hold.
  it('gradebook chrome still survives with the Unicode-aware grammar', () => {
    const CHROME = [
      'Total Score', 'Last Login', 'Practice Test', 'Chapter Review',
      'All Students', 'Item Analysis', 'Course Home', 'Send Message',
    ];
    for (const label of CHROME) expect(maskFull(label)).toBe(label);
  });

  it('does not catastrophically backtrack with \\p{...} escapes at ~600KB (ReDoS re-check)', () => {
    const chrome = 'Total Score Last Login Course Home Send Message '.repeat(2000); // ~96KB
    const accented = 'José María Rodríguez Gómez Fernández '.repeat(2000); // ~76KB, no particle
    const particles = 'José ' + 'de la '.repeat(3000) + 'x'; // no closing name — the failure case
    const t0 = performance.now();
    maskFull(chrome);
    maskFull(accented);
    maskFull(particles);
    expect(performance.now() - t0).toBeLessThan(500);
  });
});

// A cold reviewer went further than the leak table above and found the fix was still incomplete:
// `\p{Lu}\p{Ll}+` requires the accent to be part of the SAME codepoint as its base letter, which
// is only true in NFC. NFD spells an accented letter as the base letter plus a separate
// combining-mark codepoint (`\p{Mn}`) — "José" in NFD is J-o-s-e-plus-a-combining-acute — so the
// adjacency `\p{Ll}+` depends on breaks, and the whole word fails to match. NOT partially: zero
// match, byte-identical output, on a roster URL. NFC and NFD are both valid Unicode for the same
// text (NFD shows up from some copy-paste sources and legacy macOS filesystem APIs); this is not
// malformed input, it is a form the grammar itself has to accept.
describe('NFD (decomposed) input — a different valid encoding of the same text, not a new gap', () => {
  const maskFull = (s: string) => maskPersonNames(s, () => '⟦STU⟧');

  it('an NFD name masks whole, the same as its NFC form', () => {
    const nfc = 'José García';
    const nfd = nfc.normalize('NFD');
    expect(nfd).not.toBe(nfc); // sanity: actually a different byte sequence
    expect(maskFull(nfd)).toBe(maskFull(nfc));
    expect(maskFull(nfd)).toBe('⟦STU⟧');
  });

  it('an NFD comma-form name masks whole', () => {
    expect(maskFull('García, José'.normalize('NFD'))).toBe('⟦STU⟧');
  });

  it('an NFD name sitting next to an ASCII name — both mask, not just the ASCII one', () => {
    const mixed = 'Sarah Chen and ' + 'José García'.normalize('NFD') + ' both submitted';
    const out = maskFull(mixed);
    expect(out).not.toContain('José');
    expect(out).not.toContain('García');
    expect(out).not.toContain('Sarah Chen');
    expect((out.match(/⟦STU⟧/g) ?? []).length).toBe(2); // two distinct people, two tokens
  });

  it('looksLikePersonName recognizes an NFD comma-form name', () => {
    expect(looksLikePersonName('García, José'.normalize('NFD'))).toBe(true);
  });

  // The regression a naive fix (`.normalize('NFC')` on the whole text before matching) would
  // have caused: maskPersonNames hands its `token` callback the MATCHED substring, and
  // page-agent-mask.ts's PageMask stores that verbatim to rehydrate later. Normalizing before
  // matching means that stored value comes back NFC even when the page's real text was NFD —
  // measured directly (masked-then-rehydrated came back NFC, not the original NFD bytes) before
  // choosing the letter-plus-combining-mark grammar instead, which never transforms the input at
  // all. This test is what would have caught the naive version.
  it('rehydrate returns the ORIGINAL NFD string, not a normalized one', () => {
    const nfd = 'José García'.normalize('NFD');
    const map = new Map<string, string>();
    const masked = maskPersonNames(nfd, (n) => {
      map.set('⟦STU⟧', n);
      return '⟦STU⟧';
    });
    const rehydrated = masked.split('⟦STU⟧').join(map.get('⟦STU⟧')!);
    expect(rehydrated).toBe(nfd);
    expect(rehydrated).not.toBe(nfd.normalize('NFC'));
  });

  it('text surrounding a match is untouched, not silently normalized, even when it is itself NFD', () => {
    const nfdChrome = 'Total Score'.normalize('NFD'); // has no accents, but exercises the path
    const input = nfdChrome + ' ' + 'José García'.normalize('NFD') + ' ' + 'Last Login';
    const out = maskFull(input);
    expect(out).toBe('Total Score ⟦STU⟧ Last Login');
  });
});

// The LETTER grammar above fixes combining marks on name WORDS — but the two Vietnamese
// gender-marking particles ("văn", "thị") are hard-coded literals in NAME_PARTICLE, so in NFD the
// breve (U+0306) and the dot below (U+0323) decompose into separate combining-mark codepoints that
// the literal cannot match, and the 3-word run gate fails: "Nguyễn Văn An" partial-masks to
// "⟦STU⟧ An", leaking the given name in clear text. The ASCII "Nguyen Van An" case was fixed the
// same way the "văn" particle was ADDED in the first place (see NAME_PARTICLE's doc comment) — an
// NFD Vietnamese name is the same FERPA leak one normalization form away. NFC and NFD are both
// valid Unicode; both must mask whole, and rehydration must return the ORIGINAL bytes either way.
describe('NFD Vietnamese — the hard-coded văn/thị particles were the last non-grammar literals', () => {
  const maskFull = (s: string) => maskPersonNames(s, () => '⟦STU⟧');

  it('NFD "Nguyễn Văn An" masks WHOLE — used to leak "An"', () => {
    const nfd = 'Nguyễn Văn An'.normalize('NFD');
    expect(nfd).not.toBe('Nguyễn Văn An'); // sanity: actually decomposed bytes
    expect(maskFull(nfd)).toBe('⟦STU⟧');
  });

  it('NFD "Trần Thị Mai" masks whole — used to leak "Mai"', () => {
    expect(maskFull('Trần Thị Mai'.normalize('NFD'))).toBe('⟦STU⟧');
  });

  it('looksLikePersonName returns true for both NFD forms — the people-surface gate itself', () => {
    expect(looksLikePersonName('Nguyễn Văn An'.normalize('NFD'))).toBe(true);
    expect(looksLikePersonName('Trần Thị Mai'.normalize('NFD'))).toBe(true);
  });

  it('rehydrate round-trips an NFD Vietnamese name back to the ORIGINAL NFD bytes', () => {
    const nfd = 'Nguyễn Văn An'.normalize('NFD');
    const map = new Map<string, string>();
    const masked = maskPersonNames(nfd, (n) => {
      map.set('⟦STU⟧', n);
      return '⟦STU⟧';
    });
    const rehydrated = masked.split('⟦STU⟧').join(map.get('⟦STU⟧')!);
    expect(rehydrated).toBe(nfd);
    expect(rehydrated).not.toBe(nfd.normalize('NFC'));
  });

  it('NFC equivalents still mask whole (regression guard)', () => {
    expect(maskFull('Nguyễn Văn An')).toBe('⟦STU⟧');
    expect(maskFull('Trần Thị Mai')).toBe('⟦STU⟧');
  });

  it('a comma-form NFD Vietnamese roster trips looksLikeRoster where the same roster in NFC does', () => {
    const page = (labels: string[]): Parameters<typeof looksLikeRoster>[0] =>
      ({ domain: 'm.com', url: 'https://m.com/course/latepasses.php?cid=1',
         interactive: { buttons: labels.map((t, i) => ({ text: t, selector: `#b${i}` })), links: [], inputs: [] } }) as never;
    const roster = ['Văn, An', 'Thị, Mai', 'Văn, Bình', 'Save'].map((n) => n.normalize('NFD'));
    expect(looksLikeRoster(page(roster))).toBe(true);   // NFD — used to return false, names fully unmasked
    expect(looksLikeRoster(page(roster.map((n) => n.normalize('NFC'))))).toBe(true); // NFC parity
  });
});

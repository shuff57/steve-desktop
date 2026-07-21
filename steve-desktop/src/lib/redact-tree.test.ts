import { describe, it, expect } from 'vitest';
import { redactTree, isChromeNode, redactProfileForStorage, redactUrlForStorage } from './redact-tree';
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
    expect(safe.pageName).toBe('course.php?cid=316341&folder=0'); // legible AND query-disambiguated
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
    expect(r.pageName).toBe('math.php?a=⟦D2⟧');
  });

  it('a query-less url stays fully legible', () => {
    const r = redactUrlForStorage('https://x.edu/a/b/gradebook.php', redact);
    expect(r.url).toBe('https://x.edu/a/b/gradebook.php');
    expect(r.pageName).toBe('gradebook.php');
  });

  it('falls back to a fully redacted string when the url will not parse', () => {
    expect(redactUrlForStorage('not a url with Jane Doe', redact).url).not.toContain('Jane');
  });
});

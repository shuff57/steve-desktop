import { describe, it, expect } from 'vitest';
import { redactTree, isChromeNode } from './redact-tree';
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

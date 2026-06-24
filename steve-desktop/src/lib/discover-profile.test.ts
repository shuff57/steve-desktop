import { describe, it, expect } from 'vitest';
import { isIdentifierField, buildProfileFromSnapshot } from './discover-profile';
import type { SnapshotResult } from './dom-snapshot-types';

describe('isIdentifierField', () => {
  it('flags obvious person identifiers', () => {
    expect(isIdentifierField({ name: 'studentName' })).toBe(true);
    expect(isIdentifierField({ id: 'student_id' })).toBe(true);
    expect(isIdentifierField({ label: 'Last Name' })).toBe(true);
    expect(isIdentifierField({ name: 'ssn' })).toBe(true);
    expect(isIdentifierField({ label: 'Date of Birth' })).toBe(true);
    expect(isIdentifierField({ placeholder: 'Email address' })).toBe(true);
  });

  it('does not flag non-identifier fields', () => {
    expect(isIdentifierField({ name: 'grade' })).toBe(false);
    expect(isIdentifierField({ label: 'Comments' })).toBe(false);
    expect(isIdentifierField({ name: 'search' })).toBe(false);
  });
});

describe('buildProfileFromSnapshot', () => {
  const snapshot: SnapshotResult = {
    nodes: [
      { tag: 'h1', depth: 0, priority: 'high', text: 'Enter Grades', attrs: {} },
      { tag: 'input', depth: 1, priority: 'critical', text: '', attrs: { name: 'studentName', type: 'text', 'aria-label': 'Student Name' } },
      { tag: 'input', depth: 1, priority: 'critical', text: '', attrs: { name: 'grade', type: 'text', 'aria-label': 'Grade' } },
      { tag: 'button', depth: 1, priority: 'critical', text: 'Submit', attrs: { id: 'submit' } },
      { tag: 'a', depth: 1, priority: 'critical', text: 'Home', attrs: { href: '/home' } },
    ],
    meta: { totalVisited: 5, nodesIncluded: 5, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: '2026-06-23T00:00:00.000Z' },
  };

  it('extracts interactive elements', () => {
    const profile = buildProfileFromSnapshot(snapshot, 'https://sis.example.edu/grades');
    expect(profile.interactive.inputs).toHaveLength(2);
    expect(profile.interactive.buttons).toHaveLength(1);
    expect(profile.interactive.links).toHaveLength(1);
    expect(profile.domain).toBe('sis.example.edu');
  });

  it('flags identifier inputs and only those', () => {
    const profile = buildProfileFromSnapshot(snapshot, 'https://sis.example.edu/grades');
    const name = profile.interactive.inputs.find((i) => i.label === 'Student Name');
    const grade = profile.interactive.inputs.find((i) => i.label === 'Grade');
    expect(name?.identifier).toBe(true);
    expect(grade?.identifier).toBe(false);
  });

  it('is a valid SiteProfile with a summary', () => {
    const profile = buildProfileFromSnapshot(snapshot, 'https://sis.example.edu/grades');
    expect(profile.summary.inputs).toBe(2);
    expect(profile.pageName).toBeTruthy();
    expect(profile.profiledAt).toBeTruthy();
  });
});

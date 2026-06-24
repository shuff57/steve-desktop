import { describe, it, expect } from 'vitest';
import { parseSelector, selectorToElementExpr } from './selector-resolve';

describe('parseSelector', () => {
  it('classifies xpath, role, and css', () => {
    expect(parseSelector('xpath=//button[1]').kind).toBe('xpath');
    expect(parseSelector('/html/body/div').kind).toBe('xpath');
    expect(parseSelector('role=button[name="Submit"]').kind).toBe('role');
    expect(parseSelector('#submit').kind).toBe('css');
    expect(parseSelector('.btn.primary').kind).toBe('css');
  });

  it('extracts role and name', () => {
    const p = parseSelector('role=textbox[name="Student Name"]');
    expect(p).toMatchObject({ kind: 'role', role: 'textbox', name: 'Student Name' });
  });
});

describe('selectorToElementExpr', () => {
  it('xpath → document.evaluate', () => {
    const e = selectorToElementExpr('xpath=//button[@id="x"]');
    expect(e).toContain('document.evaluate');
    expect(e).toContain('//button'); // the xpath is embedded
    expect(() => new Function(`return ${e}`)).not.toThrow();
  });

  it('bare /path → document.evaluate', () => {
    expect(selectorToElementExpr('/html/body/div[2]')).toContain('document.evaluate');
  });

  it('role → matches role + accessible name', () => {
    const e = selectorToElementExpr('role=button[name="Submit grades"]');
    expect(e).toContain('getAttribute');
    expect(e).toContain('aria-label');
    expect(e).toContain('Submit grades');
    expect(e).toContain('button');
  });

  it('css → document.querySelector with the selector embedded safely', () => {
    const e = selectorToElementExpr('#submit');
    expect(e).toContain('document.querySelector');
    expect(e).toContain('#submit');
  });

  it('safely embeds quotes in a role name (valid JS, no break-out)', () => {
    const e = selectorToElementExpr('role=link[name="O\'Brien"]');
    expect(e).toContain('Brien');
    expect(() => new Function(`return ${e}`)).not.toThrow(); // JSON-encoded, parses cleanly
  });
});

// @vitest-environment jsdom
import { describe, it, expect, beforeEach } from 'vitest';
import { selectorToCountExpr } from './selector-resolve';

// Evaluated against a real DOM rather than asserted as a string: the verify pass graded 10,991
// selectors "broken" because the probe assumed CSS and threw on every role= anchor. A test that
// only checked the generated text would have passed while the probe stayed wrong.
const count = (sel: string): number => eval(selectorToCountExpr(sel)) as number;

describe('selectorToCountExpr — counts what the action layer would hit', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <button id="save">Save</button>
      <a href="/a">Open</a>
      <div role="button" aria-label="Grade">x</div>
      <table>
        <tr><td><button class="row-act">Grade</button></td></tr>
        <tr><td><button class="row-act">Grade</button></td></tr>
        <tr><td><button class="row-act">Grade</button></td></tr>
      </table>`;
  });

  it('counts a role= anchor — the format 69% of stored selectors use', () => {
    // The whole reason the first verify run was meaningless: querySelectorAll throws on this.
    expect(count('role=button[name="Save"]')).toBe(1);
  });

  it('exposes an ambiguous role selector instead of silently taking the first', () => {
    // 3 roster-style buttons + the aria-label div all answer to "Grade".
    expect(count('role=button[name="Grade"]')).toBe(4);
  });

  it('counts plain CSS', () => {
    expect(count('#save')).toBe(1);
    expect(count('.row-act')).toBe(3);
    expect(count('#nope')).toBe(0);
  });

  it('counts xpath', () => {
    expect(count('xpath=//button[@class="row-act"]')).toBe(3);
  });

  it('returns 0 rather than throwing for a role that matches nothing', () => {
    expect(count('role=button[name="Nonexistent"]')).toBe(0);
  });
});

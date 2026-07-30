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

// `<input>`'s implicit role depends on `type`. Every INPUT used to resolve as `textbox`, so a
// recorded role=checkbox anchor matched nothing — on a gradebook, which is mostly checkboxes.
describe('implicit input roles', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <input type="checkbox" aria-label="Excused">
      <input type="submit" aria-label="Post grades">
      <input type="text" aria-label="Score">
      <input type="date" aria-label="Due">
      <input type="hidden" aria-label="csrf">
      <input type="checkbox" role="switch" aria-label="Late policy">`;
  });

  it('resolves a checkbox as checkbox, not textbox', () => {
    expect(count('role=checkbox[name="Excused"]')).toBe(1);
    expect(count('role=textbox[name="Excused"]')).toBe(0);
  });

  it('resolves submit as button', () => {
    expect(count('role=button[name="Post grades"]')).toBe(1);
  });

  it('still resolves a text input as textbox', () => {
    expect(count('role=textbox[name="Score"]')).toBe(1);
  });

  it('keeps the textbox default for types it has no mapping for', () => {
    expect(count('role=textbox[name="Due"]')).toBe(1);
  });

  it('never matches a hidden input — it has no role', () => {
    expect(count('role=textbox[name="csrf"]')).toBe(0);
  });

  it('lets an explicit role attribute win over the implicit one', () => {
    expect(count('role=switch[name="Late policy"]')).toBe(1);
    expect(count('role=checkbox[name="Late policy"]')).toBe(0);
  });
});

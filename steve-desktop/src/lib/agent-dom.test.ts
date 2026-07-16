/**
 * @vitest-environment jsdom
 */
import { describe, expect, it, beforeEach } from 'vitest';
import { INTERACTIVE_DOM_SCRIPT, buildRefActionScript } from './agent-dom';
import type { InteractiveElement } from './agent-types';

// jsdom has no layout engine, so getBoundingClientRect returns all zeros and the
// capture's visible() check would drop every element. Give everything a real box.
beforeEach(() => {
  Element.prototype.getBoundingClientRect = () =>
    ({ width: 120, height: 24, x: 0, y: 0, top: 0, left: 0, right: 120, bottom: 24, toJSON: () => ({}) }) as DOMRect;
  document.body.innerHTML = '';
  delete (window as unknown as Record<string, unknown>).__steveRefs;
});

// The capture and ref-action scripts ship as strings for injection into the page, so
// eval is the only way to exercise them here. Inputs are the literals in this file.
function runCapture(): InteractiveElement[] {
  return JSON.parse(eval(INTERACTIVE_DOM_SCRIPT) as string) as InteractiveElement[];
}

function resolve(ref: string): string {
  return eval(buildRefActionScript(ref, 'return (el.innerText||el.textContent||"").trim();')) as string;
}

// Real structure, from ~/.claude/skills/steve/sc.py (live-verified against
// butte-keenan.safecolleges.com): assessment options are <label class="question_btn">
// and sc.py answers by clicking the label itself.
const OPTIONS = ['A. Report it immediately', 'B. Ignore it', 'C. Wait a week'];

describe('assessment options are reachable and unambiguous', () => {
  // sc.py does not tell us whether the labels wrap an input. Both shapes must work.
  const shapes = {
    'bare labels': OPTIONS.map((t) => `<label class="question_btn">${t}</label>`).join(''),
    'labels wrapping radios': OPTIONS.map(
      (t) => `<label class="question_btn"><input type="radio" name="q1">${t}</label>`,
    ).join(''),
  };

  for (const [shape, html] of Object.entries(shapes)) {
    describe(shape, () => {
      beforeEach(() => {
        document.body.innerHTML = html;
      });

      it('captures every option', () => {
        const captured = runCapture();
        for (const text of OPTIONS) {
          expect(captured.some((el) => el.text.includes(text))).toBe(true);
        }
      });

      it('gives each option a distinct ref', () => {
        const refs = runCapture()
          .filter((el) => OPTIONS.some((t) => el.text.includes(t)))
          .map((el) => el.ref);

        expect(refs).toHaveLength(OPTIONS.length);
        expect(new Set(refs).size).toBe(OPTIONS.length);
      });

      it('resolves a ref to the option the model actually picked', () => {
        const captured = runCapture();
        const optionB = captured.find((el) => el.text.includes('B. Ignore it'));
        expect(optionB).toBeDefined();

        // The whole point: picking B must reach B, not the first label on the page.
        expect(resolve(optionB!.ref)).toContain('B. Ignore it');
      });
    });
  }
});

describe('buildRefActionScript', () => {
  beforeEach(() => {
    document.body.innerHTML = '<button id="only">Next</button>';
  });

  it('reports stale when the node has been detached', () => {
    const ref = runCapture()[0].ref;
    document.body.innerHTML = '<button id="replacement">Next</button>';

    expect(eval(buildRefActionScript(ref, 'el.click();'))).toBe('stale');
  });

  it('reports norefs when no capture has run', () => {
    delete (window as unknown as Record<string, unknown>).__steveRefs;

    expect(eval(buildRefActionScript('e1', 'el.click();'))).toBe('norefs');
  });

  it('clicks the referenced element and reports ok', () => {
    const ref = runCapture()[0].ref;
    let clicked = false;
    document.getElementById('only')!.addEventListener('click', () => {
      clicked = true;
    });

    expect(eval(buildRefActionScript(ref, 'el.click();'))).toBe('ok');
    expect(clicked).toBe(true);
  });
});

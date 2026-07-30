/**
 * @vitest-environment jsdom
 *
 * Behavioural test for the in-iframe checker, run against HTML the LIVE sandbox actually returned
 * (captured in __fixtures__). The unit tests cover what gets embedded; this covers whether the
 * script wires real answer boxes and gives the right verdict.
 *
 * Wrong answers are asserted first and per part: a checker that always says "correct" would sail
 * through a right-answer-only test, and that is the exact bug worth catching here.
 */
import { describe, it, expect, beforeEach } from 'vitest';
import { readFileSync } from 'fs';
import { join } from 'path';
import { injectChecker } from './answer-check';

const fixture = (name: string) => readFileSync(join(__dirname, '__fixtures__', name), 'utf8');

/** Load HTML + the checker into the jsdom document and run the injected script. */
function mount(html: string) {
  document.documentElement.innerHTML = injectChecker(html)
    .replace(/^[\s\S]*?<body[^>]*>/i, '')
    .replace(/<\/body>[\s\S]*$/i, '');
  // jsdom does not execute scripts inserted via innerHTML; run them explicitly.
  for (const s of Array.from(document.querySelectorAll('script'))) {
    // eslint-disable-next-line no-eval
    window.eval(s.textContent || '');
  }
}

const wraps = () => Array.from(document.querySelectorAll('.momcheck'));
const buttons = () => wraps().map((w) => w.querySelector('button') as HTMLButtonElement);
const verdictAfter = (btn: Element) => btn.parentElement?.lastElementChild?.textContent ?? '';

describe('checker on a real multipart number question', () => {
  beforeEach(() => mount(fixture('rendered-number-multipart.html')));

  it('wires one Check button per answer box', () => {
    expect(document.querySelectorAll('input[type=text]').length).toBe(3);
    expect(buttons().length).toBe(3);
  });

  /**
   * Placement, not just presence. The button belongs WITH its box — the first version inserted it
   * after the input element wherever that fell, which read as "somewhere in the middle".
   */
  it('puts each button in the slot substituted after its own answer box', () => {
    const inputs = Array.from(document.querySelectorAll<HTMLInputElement>('input[type=text]'));
    buttons().forEach((btn, i) => {
      const wrap = btn.parentElement!;
      expect(wrap.getAttribute('data-part')).toBe(String(i)); // came from the source, not the fallback
      // The slot follows this part's input, so the input precedes it in document order.
      expect(inputs[i].compareDocumentPosition(wrap) & Node.DOCUMENT_POSITION_FOLLOWING).toBeTruthy();
    });
  });

  it('says "not correct" for a wrong value in each part', () => {
    const inputs = document.querySelectorAll<HTMLInputElement>('input[type=text]');
    buttons().forEach((btn, i) => {
      inputs[i].value = '1';
      (btn as HTMLButtonElement).click();
      expect(verdictAfter(btn)).toBe('not correct');
    });
  });

  it('says "correct" for the values this render actually produced', () => {
    const meta = document.getElementById('__momcheck')!;
    const inputs = document.querySelectorAll<HTMLInputElement>('input[type=text]');
    buttons().forEach((btn, i) => {
      inputs[i].value = meta.getAttribute(`data-a${i}`)!;
      (btn as HTMLButtonElement).click();
      expect(verdictAfter(btn)).toBe('correct');
    });
  });

  it('accepts a currency-formatted entry, which is how a teacher types money', () => {
    const meta = document.getElementById('__momcheck')!;
    const inputs = document.querySelectorAll<HTMLInputElement>('input[type=text]');
    inputs[1].value = '$' + Number(meta.getAttribute('data-a1')).toLocaleString('en-US');
    (buttons()[1] as HTMLButtonElement).click();
    expect(verdictAfter(buttons()[1])).toBe('correct');
  });

  it('asks for a value instead of judging an empty box', () => {
    (buttons()[0] as HTMLButtonElement).click();
    expect(verdictAfter(buttons()[0])).toBe('enter a value');
  });

  it('honours the declared tolerance rather than demanding an exact string', () => {
    const meta = document.getElementById('__momcheck')!;
    const inputs = document.querySelectorAll<HTMLInputElement>('input[type=text]');
    inputs[0].value = String(Number(meta.getAttribute('data-a0')) + 0.005); // inside abstolerance 0.01
    (buttons()[0] as HTMLButtonElement).click();
    expect(verdictAfter(buttons()[0])).toBe('correct');
  });
});

describe('checker on a real choices question', () => {
  beforeEach(() => mount(fixture('rendered-choices.html')));

  it('treats the four radios as ONE part, not four', () => {
    expect(document.querySelectorAll('input[type=radio]').length).toBe(4);
    expect(buttons().length).toBe(1);
  });

  /**
   * A choices question has no $answerbox marker to substitute a slot after, so this takes the
   * fallback path. Anchoring to the nearest container put the button after the FIRST option's
   * <li> — between option 1 and option 2. It must sit after the whole group.
   */
  it('puts the button after every option, not between two of them', () => {
    const radios = Array.from(document.querySelectorAll<HTMLInputElement>('input[type=radio]'));
    const wrap = buttons()[0].parentElement!;
    for (const r of radios) {
      expect(r.compareDocumentPosition(wrap) & Node.DOCUMENT_POSITION_FOLLOWING).toBeTruthy();
    }
  });

  it('marks a wrong option wrong', () => {
    const want = document.getElementById('__momcheck')!.getAttribute('data-a0')!;
    const wrong = Array.from(document.querySelectorAll<HTMLInputElement>('input[type=radio]'))
      .find((r) => r.value !== want)!;
    wrong.checked = true;
    (buttons()[0] as HTMLButtonElement).click();
    expect(verdictAfter(buttons()[0])).toBe('not correct');
  });

  it('marks the option whose INDEX matches $answer correct', () => {
    const want = document.getElementById('__momcheck')!.getAttribute('data-a0')!;
    document.querySelector<HTMLInputElement>(`input[type=radio][value="${want}"]`)!.checked = true;
    (buttons()[0] as HTMLButtonElement).click();
    expect(verdictAfter(buttons()[0])).toBe('correct');
  });
});

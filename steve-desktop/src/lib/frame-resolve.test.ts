// @vitest-environment jsdom
import { describe, it, expect, beforeEach } from 'vitest';
import { planFrameResolution, pierceQueryExpr, pierceCountExpr, REACHABLE_DOCS_EXPR } from './frame-resolve';

// Same reason `selector-count.test.ts` evaluates instead of asserting on text: the last false-drift bug
// shipped because a probe's generated string looked right and threw when run. These expressions are
// evaluated against a real (jsdom) document tree with real iframes.
const first = (sel: string): Element | null => eval(pierceQueryExpr(sel)) as Element | null;
const count = (sel: string): number => eval(pierceCountExpr(sel)) as number;

/** Append a same-origin iframe to `parent` and write `html` into its document. */
function addFrame(parent: Document, html: string): HTMLIFrameElement {
  const f = parent.createElement('iframe');
  parent.body.appendChild(f);
  const d = f.contentDocument!;
  d.open();
  d.write(`<body>${html}</body>`);
  d.close();
  return f;
}

describe('planFrameResolution — which context a captured node lived in', () => {
  const targets = [
    { targetId: 'PAGE-1', type: 'page' },
    { targetId: 'FRAME-OOP', type: 'iframe' },
  ];

  it('falls back to the main frame when no frameId was captured', () => {
    // Old profiles predate frame tracking; they must keep resolving exactly as they do today.
    expect(planFrameResolution(undefined, 'MAIN', targets)).toEqual({ kind: 'main' });
    expect(planFrameResolution('', 'MAIN', targets)).toEqual({ kind: 'main' });
    expect(planFrameResolution(null, 'MAIN', targets)).toEqual({ kind: 'main' });
  });

  it('uses the main frame when the captured frame IS the main frame', () => {
    expect(planFrameResolution('MAIN', 'MAIN', targets)).toEqual({ kind: 'main' });
  });

  it('attaches to the out-of-process iframe whose target id IS the frame id', () => {
    // CDP reuses the frame id as the target id for an OOPIF, so this is exact, not a URL guess.
    expect(planFrameResolution('FRAME-OOP', 'MAIN', targets)).toEqual({
      kind: 'target',
      targetId: 'FRAME-OOP',
    });
  });

  it('does NOT treat a same-id page target as a frame to attach to', () => {
    // Only type === 'iframe' qualifies; matching a page target would evaluate in the wrong document.
    expect(planFrameResolution('PAGE-1', 'MAIN', targets)).toEqual({ kind: 'pierce' });
  });

  it('pierces for a known frame that has no target of its own (same-origin)', () => {
    expect(planFrameResolution('FRAME-SAME-ORIGIN', 'MAIN', targets)).toEqual({ kind: 'pierce' });
  });

  it('pierces rather than guessing when the main frame id is unknown', () => {
    expect(planFrameResolution('SOME-FRAME', undefined, [])).toEqual({ kind: 'pierce' });
  });
});

describe('pierceQueryExpr — resolving across same-origin frames', () => {
  beforeEach(() => {
    document.body.innerHTML = '';
  });

  it('finds an element in the top document', () => {
    document.body.innerHTML = `<button id="save">Save</button>`;
    expect(first('#save')?.id).toBe('save');
  });

  it('finds an element that only exists inside an iframe — the whole point', () => {
    // This is the Canvas LTI case: today's bare document.querySelector returns null and the step is
    // reported as drift on every single run.
    addFrame(document, `<button id="lti-submit">Submit</button>`);
    expect(document.querySelector('#lti-submit')).toBeNull();
    expect(first('#lti-submit')?.id).toBe('lti-submit');
  });

  it('finds an element nested two frames deep', () => {
    const outer = addFrame(document, '');
    addFrame(outer.contentDocument!, `<input id="deep">`);
    expect(first('#deep')?.id).toBe('deep');
  });

  it('prefers the top document when both match, so an existing match never relocates', () => {
    document.body.innerHTML = `<button class="go" data-where="top">Go</button>`;
    addFrame(document, `<button class="go" data-where="frame">Go</button>`);
    expect(first('.go')?.getAttribute('data-where')).toBe('top');
  });

  it('skips a cross-origin frame instead of aborting the walk', () => {
    // Real pages mix same- and cross-origin frames. If one throwing access killed the walk, the
    // same-origin element after it would be unreachable — a false drift caused by an unrelated frame.
    const hostile = document.createElement('iframe');
    document.body.appendChild(hostile);
    Object.defineProperty(hostile, 'contentDocument', {
      get() {
        throw new Error('cross-origin');
      },
    });
    addFrame(document, `<button id="reachable">OK</button>`);
    expect(first('#reachable')?.id).toBe('reachable');
  });

  it('returns null when nothing matches anywhere', () => {
    addFrame(document, `<span>nope</span>`);
    expect(first('#absent')).toBeNull();
  });

  it('does not loop forever on a frame cycle', () => {
    // The walk dedupes documents; a self-referencing structure must terminate.
    const f = addFrame(document, `<button id="x">x</button>`);
    Object.defineProperty(f, 'contentDocument', { get: () => document });
    expect(first('#x')).toBeNull(); // f's own doc is now shadowed by the top doc
    expect(() => first('#anything')).not.toThrow();
  });
});

describe('pierceCountExpr — counting across frames', () => {
  beforeEach(() => {
    document.body.innerHTML = '';
  });

  it('sums matches in the top document and every reachable frame', () => {
    document.body.innerHTML = `<a class="row">1</a>`;
    addFrame(document, `<a class="row">2</a><a class="row">3</a>`);
    expect(count('.row')).toBe(3);
  });

  it('counts zero without throwing on an invalid selector', () => {
    document.body.innerHTML = `<a class="row">1</a>`;
    expect(count(':::not-a-selector')).toBe(0);
  });
});

describe('REACHABLE_DOCS_EXPR', () => {
  it('yields the top document first and includes each frame document once', () => {
    document.body.innerHTML = '';
    addFrame(document, '');
    addFrame(document, '');
    const docs = eval(REACHABLE_DOCS_EXPR) as Document[];
    expect(docs[0]).toBe(document);
    expect(docs).toHaveLength(3);
    expect(new Set(docs).size).toBe(3);
  });
});

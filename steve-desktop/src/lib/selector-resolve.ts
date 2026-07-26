// Phase 2: make persisted selector kinds replayable. The action layer only knew
// document.querySelector (CSS). A trained workflow now stores role+name and xpath anchors
// too, so we turn any selector into a JS expression that resolves to the element (or null).
// Values are JSON-embedded so quotes/apostrophes can't break out of the expression.

export type SelectorKind = 'xpath' | 'role' | 'css';

export interface ParsedSelector {
  kind: SelectorKind;
  raw: string;
  role?: string;
  name?: string;
  xpath?: string;
}

const ROLE_RE = /^role=([a-z-]+)\[name="([\s\S]*)"\]$/i;

export function parseSelector(selector: string): ParsedSelector {
  const sel = selector.trim();
  if (sel.startsWith('xpath=')) return { kind: 'xpath', raw: sel, xpath: sel.slice('xpath='.length) };
  if (sel.startsWith('/') || sel.startsWith('(/')) return { kind: 'xpath', raw: sel, xpath: sel };
  const m = sel.match(ROLE_RE);
  if (m) return { kind: 'role', raw: sel, role: m[1].toLowerCase(), name: m[2] };
  return { kind: 'css', raw: sel };
}

// Implicit ARIA roles for the common interactive tags (enough for replay anchoring).
const IMPLICIT_ROLE: Record<string, string> = {
  A: 'link', BUTTON: 'button', INPUT: 'textbox', TEXTAREA: 'textbox', SELECT: 'combobox',
  H1: 'heading', H2: 'heading', H3: 'heading', H4: 'heading', H5: 'heading', H6: 'heading',
};

/**
 * Page-side accessible-name approximation, shared by the resolver and the counter so they can
 * never drift apart.
 *
 * Capture records the browser's COMPUTED name (Accessibility.getFullAXTree); resolution has to
 * recreate it from the DOM. Using `aria-label || textContent` was too naive and silently lost
 * real elements — a live diagnosis of one MyOpenMath page found 14 of 83 recorded targets
 * "missing" while every one was present:
 *   - a logo link named by `<img alt="MyOpenMath">`, whose textContent is empty;
 *   - names assembled from several children ("… Opens Externally" in a visually-hidden span),
 *     where the computed name collapses whitespace but textContent keeps newlines and indent.
 * So: aria-label, then aria-labelledby, then the element's own content assembled in document
 * order (text nodes, a nested aria-label, an img/svg `alt`, and CSS ::before/::after generated
 * content — Chromium folds generated content into the name, which is how a plain
 * `<a>Learning objectives</a>` is really named "Learning objectives Opens Externally"), then
 * title. Whitespace is normalized on both sides before comparing.
 */
const ACCESSIBLE_NAME_FN = `function(el){
  var norm=function(s){return String(s||'').replace(/\\s+/g,' ').trim();};
  var v=el.getAttribute('aria-label');
  if(v&&v.trim())return norm(v);
  var lb=el.getAttribute('aria-labelledby');
  if(lb){var ps=[];lb.split(/\\s+/).forEach(function(id){var n=document.getElementById(id);if(n)ps.push(n.textContent||'');});
    if(norm(ps.join(' ')))return norm(ps.join(' '));}
  var parts=[];
  (function walk(n){
    for(var c=n.firstChild;c;c=c.nextSibling){
      if(c.nodeType===3){parts.push(c.nodeValue||'');}
      else if(c.nodeType===1){
        var al=c.getAttribute&&c.getAttribute('alt');
        if(c.tagName==='IMG'||c.tagName==='SVG'){if(al)parts.push(al);}
        else {var la=c.getAttribute&&c.getAttribute('aria-label');
          if(la&&la.trim())parts.push(la); else walk(c);}
      }
    }
  })(el);
  var pseudo=function(w){try{var c=getComputedStyle(el,w).content;
    if(!c||c==='none'||c==='normal')return '';return c.replace(/^["']|["']$/g,'');}catch(_){return '';}};
  var t=norm([pseudo('::before'),parts.join(' '),pseudo('::after')].join(' '));
  return t||norm(el.getAttribute('title'));
}`;

/** Same normalization applied to the recorded name, so the two sides compare like for like. */
const NORMALIZE_FN = `function(s){return String(s).replace(/\\s+/g,' ').trim();}`;

/** A JS expression (string) that evaluates to the matching Element or null. */
export function selectorToElementExpr(selector: string): string {
  const p = parseSelector(selector);

  if (p.kind === 'xpath') {
    return `(document.evaluate(${JSON.stringify(p.xpath)}, document, null, 9, null).singleNodeValue)`;
  }

  if (p.kind === 'role') {
    const implicit = JSON.stringify(IMPLICIT_ROLE);
    return (
      `(function(){var role=${JSON.stringify(p.role)},NAME=${NORMALIZE_FN}(${JSON.stringify(p.name)}),` +
      `AN=${ACCESSIBLE_NAME_FN},IMP=${implicit},els=document.querySelectorAll('*');` +
      `for(var i=0;i<els.length;i++){var el=els[i];` +
      `var r=el.getAttribute('role')||IMP[el.tagName]||'';if(r!==role)continue;` +
      `var n=AN(el);` +
      `if(n===NAME||(NAME&&n.indexOf(NAME)!==-1))return el;}return null;})()`
    );
  }

  return `document.querySelector(${JSON.stringify(p.raw)})`;
}

/**
 * A JS expression (string) evaluating to HOW MANY elements a selector matches.
 *
 * Mirrors selectorToElementExpr's matching rules exactly, because the point is to measure what
 * the action layer would really hit. selectorToElementExpr returns the FIRST match and stops —
 * which is precisely why a count matters: a selector matching 24 roster rows resolves happily
 * and silently acts on row 1. Counting is the only way to tell "works" from "works by luck".
 *
 * Note the role matcher accepts a substring name match, so short names are inherently prone to
 * matching several elements. That is a property of the resolver, not of this counter.
 */
export function selectorToCountExpr(selector: string): string {
  const p = parseSelector(selector);

  if (p.kind === 'xpath') {
    return `(document.evaluate(${JSON.stringify(p.xpath)}, document, null, 7, null).snapshotLength)`;
  }

  if (p.kind === 'role') {
    const implicit = JSON.stringify(IMPLICIT_ROLE);
    return (
      `(function(){var role=${JSON.stringify(p.role)},NAME=${NORMALIZE_FN}(${JSON.stringify(p.name)}),` +
      `AN=${ACCESSIBLE_NAME_FN},IMP=${implicit},els=document.querySelectorAll('*'),n=0;` +
      `for(var i=0;i<els.length;i++){var el=els[i];` +
      `var r=el.getAttribute('role')||IMP[el.tagName]||'';if(r!==role)continue;` +
      `var t=AN(el);` +
      `if(t===NAME||(NAME&&t.indexOf(NAME)!==-1))n++;}return n;})()`
    );
  }

  return `document.querySelectorAll(${JSON.stringify(p.raw)}).length`;
}

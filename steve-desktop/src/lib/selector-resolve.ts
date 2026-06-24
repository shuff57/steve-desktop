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

/** A JS expression (string) that evaluates to the matching Element or null. */
export function selectorToElementExpr(selector: string): string {
  const p = parseSelector(selector);

  if (p.kind === 'xpath') {
    return `(document.evaluate(${JSON.stringify(p.xpath)}, document, null, 9, null).singleNodeValue)`;
  }

  if (p.kind === 'role') {
    const implicit = JSON.stringify(IMPLICIT_ROLE);
    return (
      `(function(){var role=${JSON.stringify(p.role)},name=${JSON.stringify(p.name)},` +
      `IMP=${implicit},els=document.querySelectorAll('*');` +
      `for(var i=0;i<els.length;i++){var el=els[i];` +
      `var r=el.getAttribute('role')||IMP[el.tagName]||'';if(r!==role)continue;` +
      `var n=(el.getAttribute('aria-label')||el.textContent||'').trim();` +
      `if(n===name||n.indexOf(name)!==-1)return el;}return null;})()`
    );
  }

  return `document.querySelector(${JSON.stringify(p.raw)})`;
}

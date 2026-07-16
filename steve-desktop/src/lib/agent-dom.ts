import { evalScript } from './browser';
import type { InteractiveElement } from './agent-types';

// Elements are handed to the model as opaque refs (e1, e2, ...) backed by a page-side
// registry of live node references. Never as CSS selectors: SafeColleges assessment
// options are <label class="question_btn"> with no id and no name, so every option on a
// question serialises to the same selector and querySelector would always return the
// first one. See ~/.claude/skills/steve/sc.py, which enumerates and text-matches instead.
const INTERACTIVE_DOM_SCRIPT = `(function(){
  var MAX_ELEMENTS = 200;
  var out = [];
  var refs = [null];
  var selectors = [
    'button','a[href]','input','textarea','select','label',
    '[role="button"]','[role="link"]','[role="textbox"]',
    '[role="radio"]','[role="checkbox"]','[role="option"]',
    '[onclick]','[contenteditable]'
  ];
  var nodes = document.querySelectorAll(selectors.join(', '));
  function visible(el){
    var s=window.getComputedStyle(el);
    if(s.display==='none'||s.visibility==='hidden'||s.opacity==='0'){return false;}
    var r=el.getBoundingClientRect();
    return r.width>0&&r.height>0;
  }
  for(var idx=0;idx<nodes.length&&out.length<MAX_ELEMENTS;idx++){
    var el=nodes[idx];
    if(!visible(el)) continue;
    refs.push(el);
    out.push({
      ref: 'e'+(refs.length-1),
      tag: el.tagName.toLowerCase(),
      type: el.type||undefined,
      id: el.id||undefined,
      name: el.name||undefined,
      placeholder: el.placeholder||undefined,
      text: (el.textContent||el.innerText||'').trim().substring(0,100),
      value: el.value!==undefined&&el.value!==''?el.value:undefined,
      href: el.href||undefined,
      disabled: !!el.disabled,
      visible: true
    });
  }
  window.__steveRefs = refs;
  return JSON.stringify(out);
})()`;

export { INTERACTIVE_DOM_SCRIPT };

/**
 * Wraps `op` in a lookup of the ref registry. `op` runs with `el` bound to the node and
 * may return its own value; otherwise the script reports 'ok'.
 *
 * Returns one of: 'norefs' (no capture has run), 'stale' (node detached — the page
 * re-rendered, so re-capture), or whatever `op` returns / 'ok'.
 */
export function buildRefActionScript(ref: string, op: string): string {
  const index = Number.parseInt(ref.replace(/^e/, ''), 10);
  if (!Number.isInteger(index) || index < 1) {
    return `(function(){return 'norefs';})()`;
  }
  return `(function(){
  var el=(window.__steveRefs||[])[${index}];
  if(!el) return 'norefs';
  if(!document.contains(el)) return 'stale';
  ${op}
  return 'ok';
})()`;
}

export async function captureInteractiveDom(): Promise<InteractiveElement[]> {
  const raw = await evalScript(INTERACTIVE_DOM_SCRIPT);
  try {
    const parsed = JSON.parse(raw);
    return Array.isArray(parsed) ? (parsed as InteractiveElement[]) : [];
  } catch {
    return [];
  }
}

export function formatDomForPrompt(elements: InteractiveElement[]): string {
  if (!elements.length) return 'No interactive elements found.';
  return elements
    .map((el) => {
      const parts: string[] = [`[${el.ref}]`, el.tag];
      if (el.type && el.type !== 'text') parts[1] += `[${el.type}]`;
      if (el.text) parts.push(`"${el.text}"`);
      if (el.placeholder) parts.push(`placeholder="${el.placeholder}"`);
      if (el.disabled) parts.push('(disabled)');
      return parts.join(' ');
    })
    .join('\n');
}

function extractTextFromSelector(selector: string): string | null {
  const patterns = [
    /:has-text\(["']([^"']+)["']\)/i,
    /:text\(["']([^"']+)["']\)/i,
    /\[(?:title|alt|placeholder|value)=["']([^"']+)["']\]/i,
    /["']([^"']{2,})["']/,
  ];
  for (const pattern of patterns) {
    const match = selector.match(pattern);
    if (match?.[1]) return match[1];
  }
  return null;
}

function extractId(selector: string): string | null {
  return selector.match(/#([\w-]+)/)?.[1] ?? null;
}

function extractName(selector: string): string | null {
  return selector.match(/\[name=["']([^"']+)["']\]/i)?.[1] ?? null;
}

function extractTag(selector: string): string | null {
  return selector.match(/^([a-z][a-z0-9]*)/i)?.[1]?.toLowerCase() ?? null;
}

/**
 * Recovers a target when the model emitted a selector instead of a ref (stored
 * site-profile replay). Matches against captured fields rather than picking substrings
 * out of the selector string; the class and aria-label strategies are gone because
 * captured elements no longer carry a selector to mine.
 */
export function findFuzzyMatch(failedSelector: string, elements: InteractiveElement[]): InteractiveElement | null {
  if (!failedSelector || !elements.length) return null;

  const byText = extractTextFromSelector(failedSelector)?.toLowerCase();
  if (byText) {
    const match = elements.find((el) => el.text?.toLowerCase().includes(byText));
    if (match) return match;
  }

  const id = extractId(failedSelector);
  if (id) {
    const match = elements.find((el) => el.id?.includes(id));
    if (match) return match;
  }

  const name = extractName(failedSelector);
  if (name) {
    const match = elements.find((el) => el.name === name);
    if (match) return match;
  }

  const tag = extractTag(failedSelector);
  if (tag) {
    return elements.find((el) => el.tag === tag && el.visible) ?? null;
  }

  return null;
}

export function fuzzyMatchReason(
  failedSelector: string,
  matchedElement: InteractiveElement,
  strategy: 'text-content' | 'partial-id-or-name' | 'tag-position-heuristic' = 'text-content',
): string {
  const target = matchedElement.id
    ? `#${matchedElement.id}`
    : matchedElement.text
      ? `"${matchedElement.text.substring(0, 40)}"`
      : `<${matchedElement.tag}>[${matchedElement.ref}]`;
  return `Fuzzy matched via ${strategy}: selector "${failedSelector.substring(0, 60)}" → ${target}`;
}

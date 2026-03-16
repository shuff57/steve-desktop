import { evalScript } from './browser';
import type { InteractiveElement } from './agent-types';

const INTERACTIVE_DOM_SCRIPT = `(function(){
  var MAX_ELEMENTS = 200;
  var out = [];
  var selectors = [
    'button','a[href]','input','textarea','select',
    '[role="button"]','[role="link"]','[role="textbox"]',
    '[onclick]','[contenteditable]'
  ];
  var nodes = document.querySelectorAll(selectors.join(', '));
  function getSelector(el){
    if(el.id){return '#'+el.id.replace(/:/g,'\\:').replace(/\./g,'\\.');}
    if(el.name){return el.tagName.toLowerCase()+'[name="'+el.name+'"]';}
    var attrs=['data-testid','aria-label','data-id','data-cy'];
    for(var i=0;i<attrs.length;i++){
      var a=attrs[i];
      var v=el.getAttribute(a);
      if(v){return el.tagName.toLowerCase()+'['+a+'="'+v.replace(/"/g,'\\"')+'"]';}
    }
    if(el.className&&typeof el.className==='string'&&el.className.trim()){
      return el.tagName.toLowerCase()+'.'+el.className.trim().split(/\s+/)[0].replace(/\./g,'\\.');
    }
    return el.tagName.toLowerCase();
  }
  function visible(el){
    var s=window.getComputedStyle(el);
    if(s.display==='none'||s.visibility==='hidden'||s.opacity==='0'){return false;}
    var r=el.getBoundingClientRect();
    return r.width>0&&r.height>0;
  }
  for(var idx=0;idx<nodes.length&&out.length<MAX_ELEMENTS;idx++){
    var el=nodes[idx];
    if(!visible(el)) continue;
    out.push({
      index: out.length+1,
      tag: el.tagName.toLowerCase(),
      type: el.type||undefined,
      id: el.id||undefined,
      name: el.name||undefined,
      placeholder: el.placeholder||undefined,
      text: (el.textContent||el.innerText||'').trim().substring(0,100),
      value: el.value!==undefined&&el.value!==''?el.value:undefined,
      href: el.href||undefined,
      disabled: !!el.disabled,
      visible: true,
      selector: getSelector(el)
    });
  }
  return JSON.stringify(out);
})()`;

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
      const parts: string[] = [`[${el.index}]`, el.tag];
      if (el.type && el.type !== 'text') parts[1] += `[${el.type}]`;
      if (el.text) parts.push(`"${el.text}"`);
      if (el.placeholder) parts.push(`placeholder="${el.placeholder}"`);
      parts.push(`(${el.selector})`);
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

function extractClasses(selector: string): string[] {
  const matches = selector.match(/\.([\w-]+)/g);
  return matches ? matches.map((m) => m.slice(1)) : [];
}

function extractAriaLabel(selector: string): string | null {
  return selector.match(/\[aria-label=["']([^"']+)["']\]/i)?.[1] ?? null;
}

function extractTag(selector: string): string | null {
  return selector.match(/^([a-z][a-z0-9]*)/i)?.[1]?.toLowerCase() ?? null;
}

export function findFuzzyMatch(failedSelector: string, elements: InteractiveElement[]): InteractiveElement | null {
  if (!failedSelector || !elements.length) return null;

  const byText = extractTextFromSelector(failedSelector)?.toLowerCase();
  if (byText) {
    const match = elements.find((el) => el.text?.toLowerCase().includes(byText));
    if (match) return match;
  }

  const id = extractId(failedSelector);
  if (id) {
    const match = elements.find((el) => el.id?.includes(id) || el.selector.includes(`#${id}`));
    if (match) return match;
  }

  const classes = extractClasses(failedSelector);
  for (const cls of classes) {
    const match = elements.find((el) => el.selector.includes(`.${cls}`));
    if (match) return match;
  }

  const ariaLabel = extractAriaLabel(failedSelector)?.toLowerCase();
  if (ariaLabel) {
    const match = elements.find((el) => el.selector.toLowerCase().includes('aria-label') && el.selector.toLowerCase().includes(ariaLabel));
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
  strategy: 'text-content' | 'partial-id-or-class' | 'aria-label' | 'tag-position-heuristic' = 'text-content',
): string {
  const target = matchedElement.id
    ? `#${matchedElement.id}`
    : matchedElement.text
      ? `"${matchedElement.text.substring(0, 40)}"`
      : `<${matchedElement.tag}>[${matchedElement.index}]`;
  return `Fuzzy matched via ${strategy}: selector "${failedSelector.substring(0, 60)}" → ${target}`;
}

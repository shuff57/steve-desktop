// Adapted from page-agent (MIT, Copyright (c) 2025 Alibaba Group Holding Limited)
// DOM dehydration concept adapted from page-controller's dom_tree/ module.
// See LICENSE-page-agent.txt in this directory for full notice.

/**
 * Extracts the page's interactive elements via CDP and dehydrates them into
 * the indexed text format the page-agent system prompt expects:
 *
 *   [0]<button>Submit</button>
 *   \t[1]<input>Search</input>
 *
 * Instead of page-agent's custom DOM tree walker, we use CDP's Accessibility
 * tree (Accessibility.getFullAXTree), which the app already uses elsewhere
 * (cdp-actions.ts references it). This is more robust across Shadow DOM and
 * iframes than a manual querySelectorAll walk.
 *
 * After extraction, each interactive element is stamped with a
 * `data-pa-index` attribute so the tool actions (click_element_by_index,
 * input_text, etc.) can find it by index later in the same page state.
 */

import type { BrowserState } from './page-agent-prompt';

export interface InteractiveElement {
  index: number;
  role: string;
  name: string;
  tag: string;
  backendNodeId?: number;
}

interface AXNode {
  nodeId: string;
  role: { type: string; value?: string };
  name?: { value: string };
  ignored?: boolean;
  backendDOMNodeId?: number;
  childIds?: string[];
  properties?: { name: string; value: { type: string; value: unknown } }[];
}

const INTERACTIVE_ROLES = new Set([
  'button',
  'link',
  'textbox',
  'combobox',
  'listbox',
  'checkbox',
  'radio',
  'slider',
  'tab',
  'menuitem',
  'menuitemcheckbox',
  'menuitemradio',
  'switch',
  'searchbox',
  'spinbutton',
  'treeitem',
  'option',
]);

/**
 * Extract interactive elements from the page via CDP Accessibility tree,
 * stamp them with data-pa-index, and return the dehydrated text + metadata.
 *
 * Requires a connected CDP session. The `cdpSend` function is the same one
 * the loop driver passes via ToolContext.
 */
export async function extractBrowserState(
  cdpSend: (method: string, params?: Record<string, unknown>) => Promise<unknown>,
): Promise<BrowserState> {
  // Get current URL
  const urlResult = (await cdpSend('Runtime.evaluate', {
    expression: 'window.location.href',
    returnByValue: true,
  })) as { result?: { value?: string } };
  const url = urlResult.result?.value ?? 'unknown';

  // Get the accessibility tree
  const axResult = (await cdpSend('Accessibility.getFullAXTree')) as {
    nodes?: AXNode[];
  };
  const nodes = axResult.nodes ?? [];

  // Filter to interactive nodes
  const interactive: InteractiveElement[] = [];
  let index = 0;

  for (const node of nodes) {
    if (node.ignored) continue;
    const role = node.role?.value ?? '';
    if (!INTERACTIVE_ROLES.has(role)) continue;
    if (!node.backendDOMNodeId) continue;

    const name = node.name?.value ?? '';
    interactive.push({
      index,
      role,
      name,
      tag: role,
      backendNodeId: node.backendDOMNodeId,
    });
    index++;
  }

  // Stamp the data-pa-index attributes on the real DOM elements
  let visible = interactive;
  if (interactive.length > 0) {
    await stampByBackendNodeId(cdpSend, interactive);
    const ours = await pruneOwnOverlay(cdpSend);
    if (ours.size > 0) visible = interactive.filter((e) => !ours.has(e.index));
  }

  // Build the dehydrated text
  const content = dehydrateInteractive(visible);

  // The system prompt promises "visible page content", and most tasks need to READ
  // something (a token, a confirmation, an "Eeek!" error) that is not an interactive
  // element. Without this the LLM is blind to everything it cannot click.
  const pageText = await extractPageText(cdpSend);

  return {
    header: `Current URL: ${url}`,
    content,
    footer: pageText ? `\n<page_text>\n${pageText}\n</page_text>` : '',
  };
}

/**
 * Stamp data-pa-index onto the exact nodes the AX tree reported, addressed by
 * backendNodeId.
 *
 * The earlier version re-found each element with querySelector and matched the
 * AX name against `textContent`. That silently dropped every `<select>`: a
 * select's textContent is its option list ("-- pick one --AppleKiwiMango"),
 * which never contains its label, so the loop skipped every candidate and the
 * combobox was listed to the LLM but had no stamp — every
 * `select_dropdown_option` came back "Element [4] not found". Any control whose
 * accessible name comes from a `<label>` rather than its own text had the same
 * hole. backendNodeId is the identity the AX tree already handed us; resolving
 * it is exact and needs no name heuristics.
 */
async function stampByBackendNodeId(
  cdpSend: (method: string, params?: Record<string, unknown>) => Promise<unknown>,
  elements: InteractiveElement[],
): Promise<void> {
  // Clear the previous pass's stamps first. Indexes are positional and are
  // reassigned from scratch every extraction, so a stamp left over from an
  // earlier step is not stale metadata — it is a second element answering to a
  // live index. The tools resolve with querySelector, which returns whichever
  // one comes first in document order, so the agent clicks the wrong control
  // and is told it succeeded. Observed live: "clicked [12] ✅" navigated the tab
  // to a help page because a link earlier in the document still wore that stamp.
  await cdpSend('Runtime.evaluate', {
    expression: `document.querySelectorAll('[data-pa-index]').forEach(function(el){el.removeAttribute('data-pa-index')})`,
    returnByValue: true,
  });

  // DOM.pushNodesByBackendIdsToFrontend needs the DOM agent enabled and a
  // document fetched, or it answers with nodeId 0 for everything.
  await cdpSend('DOM.enable');
  await cdpSend('DOM.getDocument', { depth: -1 });

  const backendIds = elements.map((e) => e.backendNodeId!);
  const pushed = (await cdpSend('DOM.pushNodesByBackendIdsToFrontend', {
    backendNodeIds: backendIds,
  })) as { nodeIds?: number[] };
  const nodeIds = pushed.nodeIds ?? [];

  await Promise.all(
    elements.map((el, i) => {
      const nodeId = nodeIds[i];
      if (!nodeId) return Promise.resolve();
      return cdpSend('DOM.setAttributeValue', {
        nodeId,
        name: 'data-pa-index',
        value: String(el.index),
      }).catch(() => undefined); // a node detached between snapshot and stamp is not fatal
    }),
  );
}

/**
 * Drop our own status overlay from the element list.
 *
 * The overlay carries a Stop button, so left in it is both noise and a live
 * footgun — an agent that clicks index N aborts its own run. Marking the
 * overlay aria-hidden would hide it from the AX tree for free but would also
 * hide Stop from a screen reader, so it is filtered here instead.
 */
async function pruneOwnOverlay(
  cdpSend: (method: string, params?: Record<string, unknown>) => Promise<unknown>,
): Promise<Set<number>> {
  const res = (await cdpSend('Runtime.evaluate', {
    expression: `(function(){
      var out = [];
      document.querySelectorAll('[data-page-agent-ignore] [data-pa-index], [data-browser-use-ignore] [data-pa-index]').forEach(function(el){
        out.push(Number(el.getAttribute('data-pa-index')));
        el.removeAttribute('data-pa-index');
      });
      return out;
    })()`,
    returnByValue: true,
  })) as { result?: { value?: number[] } };
  return new Set(res.result?.value ?? []);
}

/**
 * Dehydrate the interactive element list into the text format the system
 * prompt expects. Mirrors page-agent's page-controller dehydration:
 * [index]<role>name</role>
 * with \t indentation for children (approximate — we use AX tree depth).
 */
function dehydrateInteractive(elements: InteractiveElement[]): string {
  if (elements.length === 0) {
    return '(No interactive elements found on this page)';
  }

  const lines: string[] = [];
  for (const el of elements) {
    const name = el.name || el.tag;
    lines.push(`[${el.index}]<${el.tag}>${name}</${el.tag}>`);
  }
  return lines.join('\n');
}

/**
 * Get just the page text (non-interactive content) for the browser_state.
 * This is a lightweight innerText extraction — the LLM uses it for context.
 */
export async function extractPageText(
  cdpSend: (method: string, params?: Record<string, unknown>) => Promise<unknown>,
): Promise<string> {
  const result = (await cdpSend('Runtime.evaluate', {
    expression: '(document.body.innerText || "").substring(0, 8000)',
    returnByValue: true,
  })) as { result?: { value?: string } };
  return result.result?.value ?? '';
}
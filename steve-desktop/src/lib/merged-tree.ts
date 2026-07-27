import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';
import { classifyNodePriority } from './dom-snapshot-types';
import type { SelectorCandidate, SiteProfile, ButtonElement, LinkElement, InputElement } from './types/site-profile';
import { isIdentifierField } from './discover-profile';

// Phase 1: the better-than-ad-hoc page representation — a DOM-primary tree enriched with
// the accessibility tree, merged by (frameId, backendNodeId). This is browser-use's
// EnhancedDOMTreeNode recipe ported to our CDP. The merge MUST be keyed by frame +
// backendNodeId: backendNodeId is NOT unique across iframes (SafeColleges runs content in
// an iframe), so keying by backendNodeId alone mis-merges AX data across frames.
//
// Pure functions here (flatten / merge / adapt) are unit-tested; the live CDP capture is a
// thin IO wrapper at the bottom.

// ── Normalized shapes ──────────────────────────────────────────────────────
export interface RawDomNode {
  backendNodeId: number;
  frameId: string;
  nodeName: string; // e.g. 'BUTTON', 'TD'
  attributes: Record<string, string>;
  text: string; // concatenated direct text children
}

export interface RawAxNode {
  frameId: string;
  backendDOMNodeId: number;
  role: string;
  name: string;
  ignored?: boolean;
}

export interface MergedNode {
  frameId: string;
  backendNodeId: number;
  tag: string; // lowercased nodeName
  attrs: Record<string, string>;
  text: string;
  role?: string;
  name?: string; // accessible name (AX)
}

// CDP DOM.Node subset we read.
interface CdpNode {
  backendNodeId?: number;
  nodeName: string;
  nodeType: number;
  nodeValue?: string;
  attributes?: string[]; // flat [name, value, name, value, ...]
  children?: CdpNode[];
  contentDocument?: CdpNode & { frameId?: string };
  frameId?: string;
}

function attrsToRecord(flat: string[] | undefined): Record<string, string> {
  const out: Record<string, string> = {};
  if (!flat) return out;
  for (let i = 0; i + 1 < flat.length; i += 2) out[flat[i]] = flat[i + 1];
  return out;
}

/** Flatten a CDP DOM tree into element nodes, tracking the owning frameId across iframes. */
export function flattenDom(root: CdpNode, rootFrameId: string): RawDomNode[] {
  const out: RawDomNode[] = [];

  const walk = (node: CdpNode, frameId: string): void => {
    if (node.nodeType === 1 && typeof node.backendNodeId === 'number') {
      const text = (node.children ?? [])
        .filter((c) => c.nodeType === 3 && c.nodeValue?.trim())
        .map((c) => c.nodeValue!.trim())
        .join(' ');
      out.push({
        backendNodeId: node.backendNodeId,
        frameId,
        nodeName: node.nodeName,
        attributes: attrsToRecord(node.attributes),
        text,
      });
    }
    for (const child of node.children ?? []) walk(child, frameId);
    // descend into an iframe's document under its own frameId
    if (node.contentDocument) {
      walk(node.contentDocument, node.contentDocument.frameId ?? frameId);
    }
  };

  walk(root, rootFrameId);
  return out;
}

/** Join AX role/name onto DOM nodes, keyed by (frameId, backendNodeId). */
export function mergeAxIntoDom(domNodes: RawDomNode[], axNodes: RawAxNode[]): MergedNode[] {
  const key = (f: string, b: number) => `${f}:${b}`;
  const ax = new Map<string, RawAxNode>();
  for (const a of axNodes) {
    if (a.ignored) continue;
    ax.set(key(a.frameId, a.backendDOMNodeId), a);
  }
  return domNodes.map((d) => {
    const a = ax.get(key(d.frameId, d.backendNodeId));
    return {
      frameId: d.frameId,
      backendNodeId: d.backendNodeId,
      // Defensive: external page DOM is untrusted input — a node without a nodeName
      // must not throw and kill the capture (and with it the whole crawl).
      tag: (d.nodeName ?? '').toLowerCase(),
      attrs: d.attributes,
      text: d.text,
      ...(a ? { role: a.role, name: a.name } : {}),
    };
  });
}

/** Ranked selector candidates — role+name is the durable anchor, then id/testid/href/name/class. */
export function mergedToCandidates(node: MergedNode): SelectorCandidate[] {
  const c: SelectorCandidate[] = [];
  if (node.role && node.name) c.push({ type: 'role-name', value: `role=${node.role}[name="${node.name}"]`, score: 90 });
  if (node.attrs['data-testid']) c.push({ type: 'data-testid', value: `[data-testid="${node.attrs['data-testid']}"]`, score: 85 });
  if (node.attrs['id']) c.push({ type: 'id', value: `#${node.attrs['id']}`, score: 80 });
  // A link's destination is its identity — usually the only distinguishing anchor a plain
  // <a class="tag"> on a listing page has.
  if (node.tag === 'a' && node.attrs['href']) c.push({ type: 'href', value: `a[href="${node.attrs['href']}"]`, score: 75 });
  if (node.attrs['name']) c.push({ type: 'name', value: `${node.tag}[name="${node.attrs['name']}"]`, score: 70 });
  if (node.attrs['placeholder']) c.push({ type: 'placeholder', value: `${node.tag}[placeholder="${node.attrs['placeholder']}"]`, score: 50 });
  const cls = node.attrs['class']?.trim().split(/\s+/)[0];
  if (cls) c.push({ type: 'class', value: `.${cls}`, score: 40 });
  return c.sort((a, b) => b.score - a.score);
}

/**
 * How many captured nodes each candidate selector would match — an in-tree approximation of the
 * live document.querySelectorAll count, used to pick a UNIQUE primary selector at capture time.
 * Without it the primary was blindly the highest-scored candidate, and on listing pages that was
 * `.someClass` matching a dozen rows — every one graded "ambiguous" at verify. Approximate on
 * purpose (class membership + exact attr equality; role-name counted by exact role+name): the
 * live probe stays the authority, this only has to be close enough to pick well.
 */
export function candidateMatchCounts(merged: MergedNode[]): Map<string, number> {
  const counts = new Map<string, number>();
  const bump = (k: string) => counts.set(k, (counts.get(k) ?? 0) + 1);
  for (const node of merged) {
    if (node.role && node.name) bump(`role=${node.role}[name="${node.name}"]`);
    if (node.attrs['data-testid']) bump(`[data-testid="${node.attrs['data-testid']}"]`);
    if (node.attrs['id']) bump(`#${node.attrs['id']}`);
    if (node.tag === 'a' && node.attrs['href']) bump(`a[href="${node.attrs['href']}"]`);
    if (node.attrs['name']) bump(`${node.tag}[name="${node.attrs['name']}"]`);
    if (node.attrs['placeholder']) bump(`${node.tag}[placeholder="${node.attrs['placeholder']}"]`);
    for (const cls of (node.attrs['class'] ?? '').trim().split(/\s+/).filter(Boolean)) bump(`.${cls}`);
  }
  return counts;
}

/**
 * Adapt the merged tree to the existing SnapshotResult so the redaction inversion
 * (redact-tree.ts) and replay consume one unified representation. AX role/name are folded
 * into attrs so the slot policy treats a data cell's accessible name as data.
 */
export function mergedToSnapshot(merged: MergedNode[]): SnapshotResult {
  const nodes: SnapshotNode[] = merged.map((m) => {
    const attrs: Record<string, string> = { ...m.attrs };
    if (m.role) attrs['role'] = attrs['role'] ?? m.role;
    if (m.name && !attrs['aria-label']) attrs['aria-label'] = m.name;
    return {
      tag: m.tag,
      depth: 1,
      priority: classifyNodePriority(m.tag, attrs, !!m.text, 0),
      text: m.text,
      attrs,
    };
  });
  return {
    nodes,
    meta: {
      totalVisited: merged.length,
      nodesIncluded: nodes.length,
      nodesDropped: 0,
      wasTruncated: false,
      charCount: nodes.reduce((n, x) => n + (x.text?.length ?? 0), 0),
      capturedAt: new Date().toISOString(),
    },
  };
}

const INTERACTIVE_INPUT_ROLES = new Set(['textbox', 'combobox', 'searchbox', 'spinbutton']);
const INTERACTIVE_TAGS = new Set(['button', 'a', 'input', 'textarea', 'select']);

/** A node a user can act on — drives both coverage stats and the profile's interactive list. */
export function isInteractiveNode(node: MergedNode): boolean {
  const role = node.role ?? '';
  return INTERACTIVE_TAGS.has(node.tag) || role === 'button' || role === 'link' || INTERACTIVE_INPUT_ROLES.has(role);
}

export interface CaptureStats {
  frames: number;
  domNodes: number;
  withRole: number;
  rolePct: number;
  interactive: number;
  withRoleNameSel: number;
  roleNamePct: number;
}

/** Coverage stats over a captured merged tree — how much of the page got AX role/name anchors. */
export function summarizeMerged(merged: MergedNode[]): CaptureStats {
  const pct = (n: number, d: number) => (d ? Math.round((n / d) * 100) : 0);
  const withRole = merged.filter((m) => !!m.role).length;
  const interactiveNodes = merged.filter(isInteractiveNode);
  const withRoleNameSel = interactiveNodes.filter((m) => mergedToCandidates(m).some((c) => c.type === 'role-name')).length;
  return {
    frames: new Set(merged.map((m) => m.frameId)).size,
    domNodes: merged.length,
    withRole,
    rolePct: pct(withRole, merged.length),
    interactive: interactiveNodes.length,
    withRoleNameSel,
    roleNamePct: pct(withRoleNameSel, interactiveNodes.length),
  };
}

/**
 * Build a persistable SiteProfile from the merged tree — each interactive element carries
 * its ranked candidates (role-name first) and inputs are flagged as identifiers. This is
 * the unification: one merged DOM+AX tree feeds both redaction (mergedToSnapshot) and the
 * stored profile/skill.
 */
/**
 * Can following this href actually take you to another page?
 *
 * Missing, empty, bare "#" and javascript: hrefs cannot. A bare "#fragment" CAN (it moves you
 * within the page), so it stays a link — see the note at the call site.
 */
export function isNavigableHref(href: string | undefined): boolean {
  const h = (href ?? '').trim();
  if (!h || h === '#') return false;
  return !/^javascript:/i.test(h);
}

export function mergedToProfile(merged: MergedNode[], url: string): SiteProfile {
  const buttons: ButtonElement[] = [];
  const links: LinkElement[] = [];
  const inputs: InputElement[] = [];
  const treeCounts = candidateMatchCounts(merged);

  for (const node of merged) {
    const role = node.role ?? '';
    const candidates = mergedToCandidates(node);
    // Primary = the best-ranked candidate that is UNIQUE on this page; only when no candidate
    // is unique (e.g. two identical links to the same tag) fall back to the best-ranked one.
    const selector =
      candidates.find((c) => treeCounts.get(c.value) === 1)?.value ?? candidates[0]?.value ?? node.tag;
    const label = node.name || node.text || node.attrs['aria-label'] || '';

    if (node.tag === 'button' || role === 'button') {
      buttons.push({ text: label, selector, candidates, disabled: node.attrs['disabled'] === 'true' });
    } else if (node.tag === 'a' || role === 'link') {
      // An anchor that cannot navigate is an ACTION, not a link. Sites drive tabs and filters
      // with <a href="#"> plus a click handler, and recording those as links made them useless
      // twice over: the crawler skips them (they resolve to the current page) AND the agent
      // never sees them as something to click. scrapethissite's /pages/ajax-javascript/ mapped
      // as an empty shell — 0 buttons — while showing six clickable year tabs on screen.
      //
      // In-page fragments (#section) stay links on purpose: a table of contents is navigation,
      // and reclassifying every one of them would be a much wider change than this fixes.
      if (isNavigableHref(node.attrs['href'])) {
        links.push({ text: label, selector, href: node.attrs['href'], candidates });
      } else {
        buttons.push({ text: label, selector, candidates, disabled: node.attrs['disabled'] === 'true' });
      }
    } else if (['input', 'textarea', 'select'].includes(node.tag) || INTERACTIVE_INPUT_ROLES.has(role)) {
      inputs.push({
        label,
        selector,
        type: node.attrs['type'],
        identifier: isIdentifierField({ label, name: node.attrs['name'], id: node.attrs['id'], placeholder: node.attrs['placeholder'] }),
        candidates,
      });
    }
  }

  let domain = 'unknown';
  let pageName = 'page';
  try {
    const u = new URL(url);
    domain = u.hostname;
    pageName = u.pathname.split('/').filter(Boolean).pop() ?? 'home';
  } catch {
    /* keep defaults */
  }

  return {
    url,
    domain,
    pageName,
    profiledAt: new Date().toISOString(),
    interactive: { buttons, links, inputs, selects: [], checkboxes: [], radios: [], forms: [] },
    summary: {
      buttons: buttons.length, links: links.length, inputs: inputs.length,
      selects: 0, checkboxes: 0, radios: 0, forms: 0, landmarks: 0, headings: 0,
    },
  };
}

// ── Live CDP capture (thin IO wrapper) ─────────────────────────────────────
interface CdpLike {
  send(method: string, params?: Record<string, unknown>): Promise<unknown>;
}

interface AxNodeRaw {
  backendDOMNodeId?: number;
  role?: { value?: string };
  name?: { value?: string };
  ignored?: boolean;
}

/**
 * Capture a merged DOM+AX tree from a live page over CDP. The AX tree is fetched
 * PER FRAME (Chrome 148+ requires an explicit frameId) and each frame's AX nodes are tagged
 * with that frameId, so the (frameId, backendNodeId) merge stays correct across iframes.
 */
export async function captureMergedTree(client: CdpLike): Promise<{ snapshot: SnapshotResult; merged: MergedNode[] }> {
  await client.send('DOM.enable');
  await client.send('Accessibility.enable');
  await client.send('Page.enable');

  const frameTree = (await client.send('Page.getFrameTree')) as { frameTree?: { frame?: { id?: string } } };
  const mainFrameId = frameTree.frameTree?.frame?.id ?? 'main';

  const doc = (await client.send('DOM.getDocument', { depth: -1, pierce: true })) as { root: CdpNode & { frameId?: string } };
  const dom = flattenDom(doc.root, doc.root.frameId ?? mainFrameId);

  // Frames that actually contain nodes (flattenDom tracked child frameIds via contentDocument).
  const frameIds = Array.from(new Set(dom.map((n) => n.frameId)));
  const ax: RawAxNode[] = [];
  for (const frameId of frameIds) {
    const res = (await client.send('Accessibility.getFullAXTree', { frameId })) as { nodes?: AxNodeRaw[] };
    for (const n of res.nodes ?? []) {
      if (typeof n.backendDOMNodeId !== 'number') continue;
      ax.push({ frameId, backendDOMNodeId: n.backendDOMNodeId, role: n.role?.value ?? '', name: n.name?.value ?? '', ignored: n.ignored });
    }
  }

  const merged = mergeAxIntoDom(dom, ax);
  return { snapshot: mergedToSnapshot(merged), merged };
}

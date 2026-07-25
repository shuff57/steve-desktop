import { describe, it, expect } from 'vitest';
import {
  flattenDom,
  mergeAxIntoDom,
  mergedToCandidates,
  candidateMatchCounts,
  mergedToSnapshot,
  mergedToProfile,
  captureMergedTree,
  summarizeMerged,
  type RawAxNode,
  type MergedNode,
} from './merged-tree';
import { redactTree } from './redact-tree';
import { isSiteProfile } from './types/site-profile';

// Minimal CDP DOM.getDocument node shapes (subset of the real protocol).
const cdpRoot = {
  backendNodeId: 1,
  nodeName: 'HTML',
  nodeType: 1,
  attributes: [],
  children: [
    {
      backendNodeId: 2,
      nodeName: 'TD',
      nodeType: 1,
      attributes: ['data-field', 'studentName'],
      children: [{ backendNodeId: 3, nodeName: '#text', nodeType: 3, nodeValue: 'Jane Doe' }],
    },
    {
      backendNodeId: 4,
      nodeName: 'IFRAME',
      nodeType: 1,
      attributes: ['src', 'player.html'],
      contentDocument: {
        backendNodeId: 99,
        nodeName: '#document',
        nodeType: 9,
        frameId: 'frameB',
        children: [
          {
            backendNodeId: 5, // SAME backendNodeId as a node in frameA below
            nodeName: 'BUTTON',
            nodeType: 1,
            attributes: ['id', 'play'],
            children: [{ backendNodeId: 6, nodeName: '#text', nodeType: 3, nodeValue: 'Play' }],
          },
        ],
      },
    },
    {
      backendNodeId: 5, // collides with frameB's button backendNodeId
      nodeName: 'A',
      nodeType: 1,
      attributes: ['href', '/home'],
      children: [{ backendNodeId: 7, nodeName: '#text', nodeType: 3, nodeValue: 'Home' }],
    },
  ],
};

describe('flattenDom', () => {
  it('flattens elements, converts attribute arrays to records, and collects direct text', () => {
    const nodes = flattenDom(cdpRoot, 'frameA');
    const td = nodes.find((n) => n.backendNodeId === 2 && n.frameId === 'frameA');
    expect(td?.nodeName).toBe('TD');
    expect(td?.attributes['data-field']).toBe('studentName');
    expect(td?.text).toBe('Jane Doe');
    expect(nodes.some((n) => n.nodeName === '#text')).toBe(false); // text folded into parent
  });

  it('tracks the iframe frameId so nested nodes belong to the child frame', () => {
    const nodes = flattenDom(cdpRoot, 'frameA');
    const button = nodes.find((n) => n.nodeName === 'BUTTON');
    const link = nodes.find((n) => n.nodeName === 'A');
    expect(button?.frameId).toBe('frameB');
    expect(link?.frameId).toBe('frameA');
    // both share backendNodeId 5 but in different frames
    expect(button?.backendNodeId).toBe(5);
    expect(link?.backendNodeId).toBe(5);
  });
});

describe('mergeAxIntoDom — keyed by (frameId, backendNodeId)', () => {
  it('does NOT merge an AX node from frame A onto a DOM node in frame B (the collision bug)', () => {
    const dom = flattenDom(cdpRoot, 'frameA');
    const ax: RawAxNode[] = [
      { frameId: 'frameA', backendDOMNodeId: 5, role: 'link', name: 'Home' },
      { frameId: 'frameB', backendDOMNodeId: 5, role: 'button', name: 'Play' },
    ];
    const merged = mergeAxIntoDom(dom, ax);
    const link = merged.find((n) => n.frameId === 'frameA' && n.backendNodeId === 5);
    const button = merged.find((n) => n.frameId === 'frameB' && n.backendNodeId === 5);
    expect(link?.role).toBe('link'); // not 'button'
    expect(button?.role).toBe('button'); // not 'link'
    expect(button?.name).toBe('Play');
  });
});

describe('mergedToCandidates — ranked selectors with role+name as a top anchor', () => {
  it('emits a role-name candidate and an id candidate, ranked', () => {
    const cands = mergedToCandidates({
      frameId: 'frameB', backendNodeId: 5, tag: 'button', text: 'Play',
      attrs: { id: 'play' }, role: 'button', name: 'Play',
    });
    const kinds = cands.map((c) => c.type);
    expect(kinds).toContain('role-name');
    expect(kinds).toContain('id');
    // sorted by score descending
    expect(cands[0].score).toBeGreaterThanOrEqual(cands[cands.length - 1].score);
  });

  it('gives links an href candidate — the identity a bare <a class="tag"> actually has', () => {
    const cands = mergedToCandidates({
      frameId: 'f', backendNodeId: 1, tag: 'a', text: 'love', attrs: { href: '/tag/love/', class: 'tag' },
    });
    const href = cands.find((c) => c.type === 'href');
    expect(href?.value).toBe('a[href="/tag/love/"]');
    // identity attrs (id/testid) still outrank it; class ranks below it
    expect(cands.findIndex((c) => c.type === 'href')).toBeLessThan(cands.findIndex((c) => c.type === 'class'));
  });
});

describe('unique-primary selection — the verify false-ambiguity fix', () => {
  // Ten listing rows share class .tag; each link's href is distinct. The primary used to be
  // `.tag` for every one of them (highest-scored candidate available), so verify graded every
  // link ambiguous and called the page drifted. The primary must be the best UNIQUE candidate.
  const row = (n: number, href: string): MergedNode => ({
    frameId: 'f', backendNodeId: n, tag: 'a', text: `t${n}`, attrs: { href, class: 'tag' },
  });

  it('promotes a unique href over a class shared by every row', () => {
    const merged = [row(1, '/tag/love/'), row(2, '/tag/life/'), row(3, '/tag/books/')];
    const p = mergedToProfile(merged, 'https://q.com/');
    expect(p.interactive.links.map((l) => l.selector)).toEqual([
      'a[href="/tag/love/"]', 'a[href="/tag/life/"]', 'a[href="/tag/books/"]',
    ]);
  });

  it('falls back to the best-ranked candidate when nothing is unique (duplicate-destination links)', () => {
    const merged = [row(1, '/tag/love/'), row(2, '/tag/love/')]; // sidebar + in-quote, same dest
    const p = mergedToProfile(merged, 'https://q.com/');
    // both candidates non-unique — keeps the top-ranked one; verify reports it ambiguous, honestly
    expect(p.interactive.links[0].selector).toBe('a[href="/tag/love/"]');
  });

  it('candidateMatchCounts counts class membership across the whole classList, not just the first class', () => {
    const counts = candidateMatchCounts([
      { frameId: 'f', backendNodeId: 1, tag: 'a', text: '', attrs: { class: 'btn primary' } },
      { frameId: 'f', backendNodeId: 2, tag: 'a', text: '', attrs: { class: 'primary' } },
    ]);
    expect(counts.get('.primary')).toBe(2); // second class of node 1 still counted
    expect(counts.get('.btn')).toBe(1);
  });
});

describe('captureMergedTree — per-frame AX over CDP (mocked)', () => {
  it('fetches AX per frame and merges each frame onto its own DOM nodes', async () => {
    const client = {
      async send(method: string, params?: Record<string, unknown>) {
        if (method === 'Page.getFrameTree') return { frameTree: { frame: { id: 'frameA' } } };
        if (method === 'DOM.getDocument') return { root: { ...cdpRoot, frameId: 'frameA' } };
        if (method === 'Accessibility.getFullAXTree') {
          const fid = params?.frameId;
          if (fid === 'frameA') return { nodes: [{ backendDOMNodeId: 5, role: { value: 'link' }, name: { value: 'Home' } }] };
          if (fid === 'frameB') return { nodes: [{ backendDOMNodeId: 5, role: { value: 'button' }, name: { value: 'Play' } }] };
        }
        return {};
      },
    };
    const { merged } = await captureMergedTree(client);
    const link = merged.find((n) => n.frameId === 'frameA' && n.backendNodeId === 5);
    const button = merged.find((n) => n.frameId === 'frameB' && n.backendNodeId === 5);
    expect(link?.role).toBe('link');
    expect(button?.role).toBe('button'); // frameB's AX, not frameA's
  });
});

describe('mergedToProfile — unify into a persistable SiteProfile with candidates + identifier flags', () => {
  const merged: MergedNode[] = [
    { frameId: 'f', backendNodeId: 1, tag: 'button', attrs: { id: 'submit' }, text: 'Submit', role: 'button', name: 'Submit' },
    { frameId: 'f', backendNodeId: 2, tag: 'a', attrs: { href: '/home' }, text: 'Home', role: 'link', name: 'Home' },
    { frameId: 'f', backendNodeId: 3, tag: 'input', attrs: { name: 'studentName', type: 'text' }, text: '', role: 'textbox', name: 'Student Name' },
    { frameId: 'f', backendNodeId: 4, tag: 'input', attrs: { name: 'grade', type: 'text' }, text: '', role: 'textbox', name: 'Grade' },
  ];

  it('builds a valid SiteProfile with role-name candidates and identifier flags', () => {
    const profile = mergedToProfile(merged, 'https://sis.example.edu/grades');
    expect(isSiteProfile(profile)).toBe(true);
    expect(profile.domain).toBe('sis.example.edu');
    expect(profile.interactive.buttons[0].candidates?.some((c) => c.type === 'role-name')).toBe(true);
    expect(profile.interactive.links[0].href).toBe('/home');
    const name = profile.interactive.inputs.find((i) => i.label === 'Student Name');
    const grade = profile.interactive.inputs.find((i) => i.label === 'Grade');
    expect(name?.identifier).toBe(true);
    expect(grade?.identifier).toBe(false);
    expect(name?.candidates?.[0].type).toBe('role-name'); // durable anchor first
  });
});

describe('summarizeMerged — coverage stats for the mapper UI', () => {
  it('counts frames, role coverage, and interactive role-name anchors', () => {
    const merged: MergedNode[] = [
      { frameId: 'frameA', backendNodeId: 1, tag: 'button', attrs: { id: 'go' }, text: 'Go', role: 'button', name: 'Go' },
      { frameId: 'frameB', backendNodeId: 2, tag: 'a', attrs: { href: '/x' }, text: 'X', role: 'link', name: 'X' },
      { frameId: 'frameB', backendNodeId: 3, tag: 'input', attrs: { type: 'text' }, text: '' }, // no role/name
      { frameId: 'frameA', backendNodeId: 4, tag: 'div', attrs: {}, text: 'data' }, // not interactive, no role
    ];
    const s = summarizeMerged(merged);
    expect(s.frames).toBe(2);
    expect(s.domNodes).toBe(4);
    expect(s.withRole).toBe(2);
    expect(s.rolePct).toBe(50);
    expect(s.interactive).toBe(3); // button, a, input
    expect(s.withRoleNameSel).toBe(2); // button + link have role+name; bare input does not
    expect(s.roleNamePct).toBe(67);
  });

  it('does not divide by zero on an empty tree', () => {
    const s = summarizeMerged([]);
    expect(s).toMatchObject({ frames: 0, domNodes: 0, rolePct: 0, roleNamePct: 0 });
  });
});

describe('mergedToSnapshot feeds the redaction inversion', () => {
  it('produces a SnapshotResult whose data slots redactTree strips', () => {
    const dom = flattenDom(cdpRoot, 'frameA');
    const merged = mergeAxIntoDom(dom, [
      { frameId: 'frameA', backendDOMNodeId: 2, role: 'cell', name: 'Jane Doe' },
    ]);
    const snap = mergedToSnapshot(merged);
    const out = redactTree(snap);
    expect(out.redactedText).not.toContain('Jane Doe'); // the cell data is gone
    expect(out.redactedText).toContain('Home'); // chrome link text kept
  });
});

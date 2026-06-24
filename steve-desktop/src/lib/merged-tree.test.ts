import { describe, it, expect } from 'vitest';
import {
  flattenDom,
  mergeAxIntoDom,
  mergedToCandidates,
  mergedToSnapshot,
  mergedToProfile,
  captureMergedTree,
  summarizeMerged,
  type RawAxNode,
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
  const merged = [
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
    const merged = [
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

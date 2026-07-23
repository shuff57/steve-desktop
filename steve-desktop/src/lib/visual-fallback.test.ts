import { describe, it, expect } from 'vitest';
import { tagCandidates, shouldUseVisualFallback, buildVisualPrompt, resolveVisualChoice, overlayScript, OVERLAY_REMOVE, redactTags, assertLegendClean } from './visual-fallback';
import type { SnapshotNode, SnapshotResult } from './dom-snapshot-types';

function n(tag: string, attrs: Record<string, string> = {}, text = '', bbox?: SnapshotNode['bbox']): SnapshotNode {
  return { tag, depth: 1, priority: 'critical', text, attrs, bbox };
}
function snap(nodes: SnapshotNode[]): SnapshotResult {
  return {
    nodes,
    meta: { totalVisited: nodes.length, nodesIncluded: nodes.length, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
  };
}

describe('tagCandidates', () => {
  const page = snap([
    n('div', {}, 'chrome'),
    n('button', { id: 'save' }, 'Save'),
    n('a', { id: 'help', role: 'link' }, 'Help'),
    n('button', { id: 'ghost' }, 'Hidden', { x: 0, y: 0, width: 0, height: 0, visible: false }),
  ]);

  it('numbers only the visible interactive elements, in reading order', () => {
    const tags = tagCandidates(page);
    expect(tags.map((t) => t.id)).toEqual([1, 2]);
    expect(tags[0].selector).toBe('#save');
    expect(tags[1].label).toBe('Help');
  });

  it('caps how many it will tag', () => {
    const many = snap([...Array(50)].map((_, i) => n('button', { id: `b${i}` }, `B${i}`)));
    expect(tagCandidates(many).length).toBeLessThanOrEqual(30);
  });
});

describe('shouldUseVisualFallback — the gate that keeps this last', () => {
  it('never fires while structural tiers still had a chance', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: false, rankedCandidates: 0, labelledCandidates: 0 })).toBe(false);
  });

  it('fires when nothing addressable was found at all', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: true, rankedCandidates: 0, labelledCandidates: 0 })).toBe(true);
  });

  it('fires when controls exist but are anonymous (canvas / broken a11y)', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: true, rankedCandidates: 6, labelledCandidates: 0 })).toBe(true);
  });

  it('does NOT fire when labelled candidates exist — the cheaper tiers are strictly better', () => {
    expect(shouldUseVisualFallback({ structuralTiersMissed: true, rankedCandidates: 6, labelledCandidates: 4 })).toBe(false);
  });
});

describe('buildVisualPrompt / resolveVisualChoice', () => {
  const tags = [
    { id: 1, selector: '#save', label: 'Save' },
    { id: 2, selector: '#help', label: '' },
  ];

  it('shows a numbered legend and constrains the answer space', () => {
    const p = buildVisualPrompt('save the grade', tags);
    expect(p).toContain('[1] Save');
    expect(p).toContain('[2] (no label)');
    expect(p).toContain('ONLY the number');
    expect(p).toContain('save the grade');
  });

  it('maps a chosen number back to the selector we already resolved', () => {
    expect(resolveVisualChoice('1', tags)).toBe('#save');
    expect(resolveVisualChoice('[2]', tags)).toBe('#help');
    expect(resolveVisualChoice(' 1 ', tags)).toBe('#save');
  });

  it('refuses anything that is not a valid tag — never acts on a confused reply', () => {
    expect(resolveVisualChoice('NONE', tags)).toBeNull();
    expect(resolveVisualChoice('99', tags)).toBeNull();
    expect(resolveVisualChoice('#invented-selector', tags)).toBeNull();
    expect(resolveVisualChoice('I think the second one', tags)).toBeNull();
  });
});

describe('overlay drawing (the screenshot the model actually sees)', () => {
  it('draws one removable badge per tag, positioned from live rects', () => {
    const js = overlayScript([{ id: 1, selector: '#save' }, { id: 2, selector: '#help' }]);
    expect(js).toContain('__steveVisualTags');
    expect(js).toContain('#save');
    expect(js).toContain("'['+pairs[i][0]+']'");
    expect(js).toContain('getBoundingClientRect'); // live geometry, not stored bbox
    expect(js).toContain('pointer-events:none'); // never steals the click we are about to make
  });

  it('reports which ids it actually badged, so the legend matches the picture', () => {
    const js = overlayScript([{ id: 1, selector: '#save' }]);
    expect(js).toContain('drawn.push(pairs[i][0])');
    expect(js).toContain('return drawn');
    expect(js).toContain('r.top>=innerHeight'); // off-viewport badges are not in the picture
    expect(js).not.toMatch(/appendChild\(box\);\s*return true/); // never "all of them, trust me"
  });

  it('is idempotent and leaves no marks behind', () => {
    // re-running removes the previous overlay first, and REMOVE clears it outright
    expect(overlayScript([])).toContain("getElementById('__steveVisualTags'); if(old)old.remove()");
    expect(OVERLAY_REMOVE).toContain('remove()');
  });

  it('never throws into the page', () => {
    expect(overlayScript([{ id: 1, selector: 'not[a valid' }])).toContain('try{');
  });
});

describe('privacy masking — the only redaction a screenshot can get', () => {
  const js = overlayScript([{ id: 1, selector: '#save' }]);

  it('blanks every glyph, not just text nodes it could find', () => {
    expect(js).toContain('color:transparent !important');
    expect(js).toContain('-webkit-text-fill-color:transparent !important');
  });

  it('covers input values and placeholders — a live capture leaked a real username without this', () => {
    expect(js).toContain('input,textarea,select,option{color:transparent !important');
    expect(js).toContain('::placeholder');
  });

  it('hides media', () => {
    expect(js).toContain('img,svg,video,picture,iframe,object,embed{visibility:hidden !important}');
  });

  it('exempts the badges, or the model would be sent a picture it cannot read', () => {
    expect(js).toContain('#__steveVisualTags,#__steveVisualTags *{color:#fff !important');
    expect(js).toContain('visibility:visible !important');
  });

  it('uses no coordinates at all — box geometry drifted off the glyphs on a real page', () => {
    expect(js).not.toContain('SHOW_TEXT');
    expect(js).not.toContain('getClientRects');
  });

  it('fails closed — no stylesheet, no screenshot', () => {
    expect(js).toContain("if(!document.getElementById('__steveVisualMask'))return false");
  });

  it('can be turned off only explicitly (used for non-outbound captures)', () => {
    expect(overlayScript([], { mask: false })).toContain('if(false)');
    expect(overlayScript([])).toContain('if(true)');
  });

  it('removal clears the mask too — a page left with invisible text is worse than one with badges', () => {
    expect(OVERLAY_REMOVE).toContain('__steveVisualMask');
    expect(OVERLAY_REMOVE).toContain('__steveVisualTags');
  });
});

describe('legend redaction — the text half of the visual gate', () => {
  const map = { '⟦D1⟧': 'Jane Doe', '⟦D2⟧': 'B+' };

  it('swaps known data values out of labels, longest first', () => {
    const out = redactTags([{ id: 1, selector: '#r1', label: 'Row for Jane Doe' }], map);
    expect(out[0].label).toBe('Row for ⟦D1⟧');
    expect(out[0].selector).toBe('#r1'); // selector untouched — it is what we act on
  });

  it('leaves short values alone — they legitimately recur in chrome text', () => {
    expect(redactTags([{ id: 1, selector: '#g', label: 'B+ grade' }], map)[0].label).toBe('B+ grade');
  });

  it('refuses the call when a data value survived into the prompt', () => {
    expect(() => assertLegendClean('pick the row for Jane Doe', map)).toThrow(/leaked into the legend/);
    expect(() => assertLegendClean('pick the row for ⟦D1⟧', map)).not.toThrow();
  });
});

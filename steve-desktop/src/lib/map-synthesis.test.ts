import { describe, it, expect, vi } from 'vitest';
import { buildSynthesisPrompt, parseSynthesis, fetchMapSynthesis } from './map-synthesis';
import type { SiteMap } from './site-map';

const map: SiteMap = {
  domain: 'x.edu',
  startedAt: '2026-07-20T00:00:00Z',
  pages: [
    { url: 'https://x.edu/gb.php?cid=1', pageName: 'Gradebook', links: [], counts: { buttons: 3, links: 10, inputs: 5 } },
    { url: 'https://x.edu/forum.php?cid=1', pageName: 'Forum', links: [], counts: { buttons: 1, links: 4, inputs: 1 } },
  ],
};

describe('parseSynthesis', () => {
  it('maps indices to urls and drops out-of-range refs', () => {
    const s = parseSynthesis(
      '{"sections":[{"name":"Grading","pages":[0,7]}],"trim":[{"i":1,"reason":"chatter"},{"i":9}],"workflows":["enter grades"]}',
      map,
    )!;
    expect(s.sections).toEqual([{ name: 'Grading', urls: [map.pages[0].url] }]);
    expect(s.trim).toEqual([{ url: map.pages[1].url, reason: 'chatter' }]);
    expect(s.workflows).toEqual(['enter grades']);
  });
  it('rejects garbage and empty results', () => {
    expect(parseSynthesis('nope', map)).toBeNull();
    expect(parseSynthesis('{"sections":[],"trim":[],"workflows":[]}', map)).toBeNull();
  });
});

describe('fetchMapSynthesis', () => {
  it('gate refusal (leaked secret) → null, transport never called', async () => {
    const transport = vi.fn();
    // 'Gradebook' appears in the prompt via the page name.
    expect(await fetchMapSynthesis(map, {}, { '⟦D1⟧': 'Gradebook' }, transport)).toBeNull();
    expect(transport).not.toHaveBeenCalled();
  });
  it('happy path', async () => {
    const s = await fetchMapSynthesis(map, {}, {}, vi.fn().mockResolvedValue('{"sections":[{"name":"A","pages":[1]}],"trim":[],"workflows":[]}'));
    expect(s?.sections[0].urls).toEqual([map.pages[1].url]);
  });
});

describe('buildSynthesisPrompt', () => {
  it('numbers pages and includes card types when present', () => {
    const p = buildSynthesisPrompt(map, {
      [map.pages[0].url]: { pageType: 'gradebook-roster', purpose: '', keyActions: [], automationValue: 'high' },
    });
    expect(p).toContain('0: Gradebook [gradebook-roster, value:high]');
    expect(p).toContain('1: Forum — /forum.php?cid=1');
  });
});

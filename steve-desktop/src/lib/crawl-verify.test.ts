import { describe, it, expect } from 'vitest';
import { gradePage, selectorsToProbe, summarize, targetsOf } from './crawl-verify';
import type { SiteProfile } from './types/site-profile';

function profile(over: Partial<SiteProfile['interactive']> = {}, url = 'https://x.com/g?cid=1'): SiteProfile {
  const interactive = {
    buttons: [], links: [], inputs: [], selects: [], checkboxes: [], radios: [], forms: [],
    ...over,
  } as SiteProfile['interactive'];
  return {
    url,
    domain: 'x.com',
    pageName: 'Gradebook',
    profiledAt: '2026-07-20T00:00:00Z',
    interactive,
    summary: {
      buttons: interactive.buttons.length, links: interactive.links.length, inputs: interactive.inputs.length,
      selects: 0, checkboxes: 0, radios: 0, forms: 0, landmarks: 0, headings: 0,
    },
  };
}

describe('gradePage — does every recorded control still resolve?', () => {
  it('passes a page whose selectors each match exactly one element', () => {
    const p = profile({
      buttons: [{ text: 'Save', selector: '#save' }],
      inputs: [{ label: 'Score', selector: '#score' }],
    });
    const v = gradePage(p, p, { '#save': 1, '#score': 1 });
    expect(v.status).toBe('ok');
    expect(v.checks.every((c) => c.status === 'ok')).toBe(true);
    expect(v.signatureMatch).toBe(true);
  });

  it('flags an AMBIGUOUS selector on the check, without calling the page drifted', () => {
    // A multi-match is a statement about the element's anchorability (dangerous to ACT through —
    // a click hits row 1, maybe the wrong student), not about the page having changed: verify
    // grades the fresh capture against its own live page, so it was multi-match at capture too.
    // It must stay visible on the check and in the summary, but treating it as page drift marked
    // 130/178 pages of a completely static site as drifted.
    const p = profile({ buttons: [{ text: 'Grade', selector: 'tr td:nth-child(2) button' }] });
    const v = gradePage(p, p, { 'tr td:nth-child(2) button': 24 });
    expect(v.checks[0].status).toBe('ambiguous');
    expect(v.checks[0].matches).toBe(24);
    expect(v.status).toBe('ok'); // weak anchor ≠ drift
    expect(summarize([v]).ambiguous).toBe(1); // still surfaced
    expect(summarize([v]).clean).toBe(false); // and still blocks a clean bill
  });

  it('marks a missing selector broken, but healed when a candidate resolves uniquely', () => {
    const p = profile({
      buttons: [
        { text: 'Save', selector: '.btn-7', candidates: [{ type: 'id', value: '#save', score: 9 }] },
        { text: 'Gone', selector: '.btn-9', candidates: [{ type: 'id', value: '#nope', score: 9 }] },
      ],
    });
    const v = gradePage(p, p, { '.btn-7': 0, '#save': 1, '.btn-9': 0, '#nope': 0 });
    expect(v.checks[0]).toMatchObject({ status: 'healed', healedWith: '#save' });
    expect(v.checks[1].status).toBe('broken');
    expect(v.status).toBe('drifted');
  });

  it('does not heal onto an ambiguous candidate', () => {
    const p = profile({
      buttons: [{ text: 'Save', selector: '.gone', candidates: [{ type: 'class', value: '.row-btn', score: 3 }] }],
    });
    const v = gradePage(p, p, { '.gone': 0, '.row-btn': 12 });
    expect(v.checks[0].status).toBe('broken');
  });

  it('reports shape drift even when every selector still resolves', () => {
    // Same controls present, but the page grew a new one — the map is stale, not broken.
    const recorded = profile({ buttons: [{ text: 'Save', selector: '#save' }] });
    const fresh = profile({
      buttons: [{ text: 'Save', selector: '#save' }, { text: 'Delete', selector: '#del' }],
    });
    const v = gradePage(recorded, fresh, { '#save': 1 });
    expect(v.status).toBe('ok');
    expect(v.signatureMatch).toBe(false);
  });

  it('treats a failed capture as an unmatched signature rather than throwing', () => {
    const p = profile({ buttons: [{ text: 'Save', selector: '#save' }] });
    expect(gradePage(p, null, { '#save': 1 }).signatureMatch).toBe(false);
  });
});

describe('selectorsToProbe — one browser round trip per page', () => {
  it('collects primaries and candidates, deduped, skipping empty selectors', () => {
    const p = profile({
      buttons: [{ text: 'Save', selector: '#save', candidates: [{ type: 'id', value: '#save', score: 9 }] }],
      links: [{ text: 'Next', selector: '#next', href: '/n' }, { text: 'Bad', selector: '' }],
    });
    expect(selectorsToProbe(p).sort()).toEqual(['#next', '#save']);
    expect(targetsOf(p)).toHaveLength(2);
  });
});

describe('summarize', () => {
  it('is only clean when nothing broke, healed, or went ambiguous', () => {
    const p = profile({ buttons: [{ text: 'Save', selector: '#save' }] });
    expect(summarize([gradePage(p, p, { '#save': 1 })]).clean).toBe(true);
    // a healed selector still means the stored map is wrong, so it is NOT clean
    const h = profile({
      buttons: [{ text: 'Save', selector: '.b7', candidates: [{ type: 'id', value: '#save', score: 9 }] }],
    });
    const s = summarize([gradePage(h, h, { '.b7': 0, '#save': 1 })]);
    expect(s.healed).toBe(1);
    expect(s.clean).toBe(false);
  });

  it('counts unreachable pages passed in from a failed navigation', () => {
    const bad = { url: 'https://x.com/z', pageName: 'Z', status: 'unreachable' as const, signatureMatch: false, checks: [], error: 'nav timeout' };
    expect(summarize([bad])).toMatchObject({ pages: 1, unreachable: 1, ok: 0, clean: false });
  });
});

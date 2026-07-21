import { describe, it, expect, vi } from 'vitest';
import {
  buildFrontierPrompt, parseFrontierPlan, planFrontier, fallbackPlan, fetchTemplateTiebreak,
  type FrontierCandidate,
} from './crawl-planner';

const cands: FrontierCandidate[] = [
  { url: 'https://x.edu/gb.php?cid=1', label: 'Gradebook' },
  { url: 'https://x.edu/student.php?uid=41', label: 'Row A' },
  { url: 'https://x.edu/student.php?uid=42', label: 'Row B' },
  { url: 'https://x.edu/forum.php?cid=1', label: 'Forum' },
];

describe('parseFrontierPlan invariants', () => {
  it('follow is a subset of candidates; out-of-range and duplicate indices are dropped', () => {
    const plan = parseFrontierPlan('{"follow":[0,99,-1,0,3],"skip":[{"i":1,"reason":"row"}]}', cands)!;
    expect(plan.follow).toEqual([cands[0].url, cands[3].url, cands[2].url]); // 2 unmentioned → appended
    expect(plan.follow.every((u) => cands.some((c) => c.url === u))).toBe(true);
    expect(plan.dropped).toBe(3); // 99, -1, duplicate 0
    expect(plan.skip).toEqual([{ url: cands[1].url, reason: 'row' }]);
  });

  it('a model cannot inject a URL — replies carry indices, urls in text are ignored', () => {
    const hostile = '{"follow":[0],"skip":[],"urls":["https://x.edu/admin/deleteall.php"]}';
    const plan = parseFrontierPlan(hostile, cands)!;
    expect(plan.follow).not.toContain('https://x.edu/admin/deleteall.php');
    expect(plan.follow.every((u) => cands.some((c) => c.url === u))).toBe(true);
  });

  it('silence is not a skip — unmentioned candidates stay followed, in original order', () => {
    const plan = parseFrontierPlan('{"follow":[2],"skip":[]}', cands)!;
    expect(plan.follow).toEqual([cands[2].url, cands[0].url, cands[1].url, cands[3].url]);
  });

  it('garbage → null', () => {
    expect(parseFrontierPlan('not json', cands)).toBeNull();
    expect(parseFrontierPlan('{"skip":[]}', cands)).toBeNull();
  });
});

describe('planFrontier fallbacks', () => {
  it('transport failure → follow all in order', async () => {
    const plan = await planFrontier(cands, {
      goal: 'g', mapSummary: '', secrets: {}, transport: vi.fn().mockRejectedValue(new Error('down')),
    });
    expect(plan).toEqual(fallbackPlan(cands));
  });

  it('secret leaking into prompt → gate refuses → fallback, transport never called', async () => {
    const transport = vi.fn();
    const plan = await planFrontier(cands, {
      goal: 'g', mapSummary: '', secrets: { '⟦D1⟧': 'Gradebook' }, transport,
    });
    expect(plan).toEqual(fallbackPlan(cands));
    expect(transport).not.toHaveBeenCalled();
  });

  it('single candidate → no model call at all', async () => {
    const transport = vi.fn();
    await planFrontier([cands[0]], { goal: 'g', mapSummary: '', secrets: {}, transport });
    expect(transport).not.toHaveBeenCalled();
  });
});

describe('prompt', () => {
  it('lists indices and paths, not full urls with host', () => {
    const p = buildFrontierPrompt(cands, 'map the course', '');
    expect(p).toContain('0: "Gradebook"');
    expect(p).toContain('/student.php?uid=41');
  });
});

describe('fetchTemplateTiebreak', () => {
  it('parses verdicts and falls back to null', async () => {
    expect(await fetchTemplateTiebreak('a', 'b', {}, vi.fn().mockResolvedValue('{"same": true}'))).toBe(true);
    expect(await fetchTemplateTiebreak('a', 'b', {}, vi.fn().mockResolvedValue('{"same": false}'))).toBe(false);
    expect(await fetchTemplateTiebreak('a', 'b', {}, vi.fn().mockResolvedValue('dunno'))).toBeNull();
    expect(await fetchTemplateTiebreak('a', 'b', {}, vi.fn().mockRejectedValue(new Error('x')))).toBeNull();
  });
});

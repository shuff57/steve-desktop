import { describe, it, expect } from 'vitest';
import { summarizeRunResult } from './result-summary';

// The real report from the self-heal demo run — the exact shape the panel must condense.
const REPORT = [
  '# Result',
  '- Read the site map and took the listed Author-detail URL `https://quotes.toscrape.com/writer/Albert-Einstein/` — **DONE**',
  '- Opened that mapped URL in the existing quotes.toscrape.com tab (activated first) — **DONE**, but it returned **404 Not Found** → map drift',
  '- Self-healed: followed the `(about)` link on an Albert Einstein quote block — **DONE**, page loads correctly',
  '- Read Einstein birth date and birthplace from the live author page — **DONE**:',
  '- Screenshot / recording — **SKIPPED** (task did not call for either)',
  '',
  '## Changed',
  '- No site state changed (read-only; navigation and one in-page click only).',
  '- One local file edited: `_sitemap-ai.md`',
  '',
  '## Verdict',
  'Yes — task complete. Albert Einstein: born **March 14, 1879**, in **Ulm, Germany**.',
].join('\n');

describe('summarizeRunResult', () => {
  const s = summarizeRunResult(REPORT);

  it('counts steps by status and strips markdown from their text', () => {
    expect(s.done).toBe(4);
    expect(s.skipped).toBe(1);
    expect(s.failed).toBe(0);
    expect(s.steps[0].text).not.toMatch(/\*\*|`/);
    expect(s.steps[0].text).toContain('Read the site map');
  });

  it('captures the Changed list separately — the audit-relevant part', () => {
    expect(s.changed).toHaveLength(2);
    expect(s.changed[1]).toContain('_sitemap-ai.md');
  });

  it('pulls the verdict as one clean line', () => {
    expect(s.verdict).toContain('task complete');
    expect(s.verdict).toContain('March 14, 1879');
    expect(s.verdict).not.toMatch(/\*\*/);
  });

  it('a step that half-worked is never counted clean (FAILED/SKIPPED win over DONE)', () => {
    const mixed = summarizeRunResult('# Result\n- Tried the thing — **DONE**, then **FAILED** to save\n');
    expect(mixed.failed).toBe(1);
    expect(mixed.done).toBe(0);
  });

  it('ignores prose bullets that carry no status', () => {
    const prose = summarizeRunResult('# Result\n- just a note about the page\n- Did it — DONE\n');
    expect(prose.steps).toHaveLength(1);
  });

  it('flags a read-only run whose only "change" line says nothing changed', () => {
    const ro = summarizeRunResult('# Result\n- Looked — DONE\n## Changed\n- No site state changed (read-only).');
    expect(ro.noChanges).toBe(true);
  });

  it('unparseable report yields empty counts so the UI falls back to raw markdown', () => {
    const s2 = summarizeRunResult('The agent rambled without any structure.');
    expect(s2.steps).toEqual([]);
    expect(s2.changed).toEqual([]);
    expect(s2.verdict).toBe('');
  });
});

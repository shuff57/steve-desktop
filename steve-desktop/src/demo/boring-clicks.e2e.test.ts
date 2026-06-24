import { describe, it, expect, beforeAll } from 'vitest';
import { mkdtempSync, readFileSync, existsSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

import { runBoringClicksDemo, type DemoResult } from './boring-clicks';
import { isSiteProfile } from '../lib/types/site-profile';

// One end-to-end run drives the whole open -> train -> replay loop and every
// pass criterion is asserted against its result.
describe('boring-clicks core loop (e2e)', () => {
  let result: DemoResult;
  let rootDir: string;

  beforeAll(async () => {
    rootDir = mkdtempSync(join(tmpdir(), 'boring-clicks-'));
    result = await runBoringClicksDemo({
      rootDir,
      // page changed since training: #studentName was renamed -> forces self-heal
      renameOnReplay: { '#studentName': '#student_name' },
    });
  });

  it('discovery saves a site-profile JSON under .agents/site-profiles/<domain>/ with interactive elements', () => {
    expect(result.profilePath).toContain(join('.agents', 'site-profiles', 'sis-example-edu'));
    expect(existsSync(result.profilePath)).toBe(true);
    const parsed = JSON.parse(readFileSync(result.profilePath, 'utf8'));
    expect(isSiteProfile(parsed)).toBe(true);
    expect(parsed.interactive.inputs.length).toBeGreaterThan(0);
  });

  it('flags which fields are identifiers', () => {
    const inputs = result.profile.interactive.inputs;
    const nameField = inputs.find((i) => i.label === 'Student Name');
    const gradeField = inputs.find((i) => i.label === 'Grade');
    expect(nameField?.identifier).toBe(true);
    expect(gradeField?.identifier).toBe(false);
  });

  it('persists the trained workflow as a skill that re-runs without re-training', () => {
    expect(existsSync(result.skillPath)).toBe(true);
    const md = readFileSync(result.skillPath, 'utf8');
    expect(md).toContain('name: Enter Grades');
    // replay used the reloaded-from-disk skill, and it completed
    expect(result.summary.workflow).toBe('Enter Grades');
    expect(result.summary.completed).toBe(true);
  });

  it('replay reports each action taken and self-heals the renamed selector', () => {
    const statuses = result.summary.results.map((r) => r.status);
    expect(statuses).toEqual(['recovered', 'done', 'done']);
    const recovered = result.summary.results[0];
    expect(recovered.selectorUsed).toBe('#student_name');
    expect(recovered.detail).toMatch(/re-derived/i);
  });

  it('REDACTOR GATE: no known identifier ever crosses the boundary; reply is rehydrated locally', () => {
    expect(result.outboundCapture.length).toBeGreaterThan(0);
    for (const sent of result.outboundCapture) {
      expect(sent).not.toContain('Jane Doe');
      expect(sent).not.toContain('4471');
      // the inversion win: guardian PII sits in a NON-identifier field, never seeded as a
      // secret — the old value-dictionary would leak it; slot redaction strips it.
      expect(sent).not.toContain('Bobby Smith');
    }
    expect(result.leaked).toBe(false);
    // the rehydrated plan the local runner acts on does carry the real identity
    expect(result.modelPlan).toContain('Jane Doe');
  });

  it('refuses an un-redacted model call (the gate is mandatory)', async () => {
    const { Redactor } = await import('../lib/redact');
    const { callModel } = await import('../lib/model-gate');
    const r = new Redactor(['Jane Doe']);
    await expect(callModel('Jane Doe raw' as unknown as never, r, async () => 'x')).rejects.toThrow();
  });
});

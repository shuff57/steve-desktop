/**
 * End-to-end smoke test: drive `runFloorScores` with a *real* Node script
 * that we create in a temp dir, and assert the runner captures stdout,
 * exit code, and CSV paths. No fake spawner — this exercises the real
 * child_process path.
 *
 * This is the closest thing to the "manual smoke test against MyOpenMath"
 * we can do without a real Chrome + MyOpenMath session. The real scripts
 * use Playwright to drive Chrome; here we substitute a small script that
 * just writes CSVs and exits 0/1.
 */
import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { mkdtempSync, writeFileSync, rmSync, existsSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { runFloorScores } from './runner';

const FAKE_SCRIPT = `#!/usr/bin/env node
// Fake floor-scores.mjs: parses --cid, --aid, --label, --out-dir;
// writes two CSVs and exits 0 (or 1 if --write-back is passed — useful
// for failure tests). ESM-only — Node 22+ defaults .mjs to ESM.
import { writeFileSync } from 'node:fs';
import { join } from 'node:path';

const args = process.argv.slice(2);
function get(name) {
  const f = args.find((a) => a.startsWith('--' + name + '='));
  return f ? f.split('=').slice(1).join('=') : null;
}
const cid = get('cid');
const aid = get('aid');
const label = get('label') ?? 'aid' + aid;
const outDir = get('out-dir');
const writeBack = args.includes('--write-back');
if (!cid || !aid || !outDir) { console.error('missing flags'); process.exit(2); }
if (writeBack) {
  console.error('would push to page — refusing in fake');
  process.exit(1);
}
console.log('fake floor-scores: cid=' + cid + ' aid=' + aid + ' label=' + label);
writeFileSync(join(outDir, label + '_summary.csv'), 'student,score\\nAlice,8\\n');
writeFileSync(join(outDir, label + '_floors.csv'), 'student,credit\\nAlice,1.0\\n');
process.exit(0);
`;

describe('runFloorScores (real subprocess)', () => {
  let scriptsDir: string;
  let outDir: string;

  beforeEach(() => {
    scriptsDir = mkdtempSync(join(tmpdir(), 'gradebook-scripts-'));
    outDir = mkdtempSync(join(tmpdir(), 'gradebook-out-'));
    writeFileSync(join(scriptsDir, 'floor-scores.mjs'), FAKE_SCRIPT, 'utf-8');
  });

  afterEach(() => {
    rmSync(scriptsDir, { recursive: true, force: true });
    rmSync(outDir, { recursive: true, force: true });
  });

  it('captures stdout, exit code 0, and CSVs from a real Node script', async () => {
    const result = await runFloorScores(
      { cid: 306621, aid: 22202268, label: 'unit1', outDir },
      { scriptsDir },
    );

    expect(result.ok).toBe(true);
    expect(result.exitCode).toBe(0);
    expect(result.stdout).toContain('fake floor-scores: cid=306621 aid=22202268 label=unit1');
    expect(result.csvPaths).toHaveLength(2);
    expect(result.csvPaths.map((p) => p.split(/[\\/]/).pop())).toEqual(
      expect.arrayContaining(['unit1_summary.csv', 'unit1_floors.csv']),
    );
    for (const p of result.csvPaths) {
      expect(existsSync(p)).toBe(true);
    }
    if (!result.ok) {
      // diagnostic surface if a future refactor breaks it
      // eslint-disable-next-line no-console
      console.error('DEBUG stderr:', result.stderr);
    }
  });

  it('refuses write-back (exit 1) and surfaces stderr', async () => {
    const result = await runFloorScores(
      { cid: 1, aid: 2, outDir, writeBack: true },
      { scriptsDir },
    );

    expect(result.ok).toBe(false);
    expect(result.exitCode).toBe(1);
    expect(result.stderr).toContain('refusing in fake');
    expect(result.csvPaths).toEqual([]);
  });
});

/**
 * gradebook-island — rehosts the gradebook/playwright-grading scripts as
 * in-app Skills. The scripts run as Node subprocesses via `runner.ts`; the
 * island exposes a typed surface to the rest of the app.
 *
 * The playwright scripts themselves live in the gradebook project (not in
 * this repo) because they need the `playwright` npm package and a persistent
 * Chrome profile. The island locates them via `config.ts`:
 *   - caller's `scriptsDir` arg
 *   - GRADEBOOK_SCRIPTS_DIR env var
 *   - ./scripts/ relative to runner.ts (default; not useful in production)
 *
 * To wire this island into a UI (Skills page, command palette, etc.):
 *   import { gradebookIsland } from './integrations/gradebook';
 *   const result = await gradebookIsland.methods.runFloorScores({
 *     cid: 306621, aid: 22202268, label: 'unit1',
 *     scriptsDir: 'C:/Users/shuff/Documents/GitHub/gradebook/playwright-grading',
 *   });
 */
import { defineIsland } from '../_shared/island';
import {
  runFloorScores as _runFloorScores,
  runScrapeQids as _runScrapeQids,
  type RunResult,
  type ScrapeQidsResult,
} from './runner';
import { resolveScriptsDir, resolveDefaultOutDir, type GradebookConfig } from './config';
import type { FloorScoresOpts, ScrapeQidsOpts } from './args';

/** Full opts the caller can pass, including the path overrides. */
export interface GradebookIslandOpts {
  scriptsDir?: string;
  outDir?: string;
}

export interface GradebookMethods {
  /**
   * Run the floor-grader. Dry-run by default — pass `writeBack: true` to push
   * scores to the page. Returns stdout, stderr, exit code, and any CSVs the
   * script wrote into `outDir`.
   */
  runFloorScores: (opts: FloorScoresOpts & GradebookIslandOpts) => Promise<RunResult>;
  /** Scrape question number -> qid map for an assignment. */
  runScrapeQids: (opts: ScrapeQidsOpts & GradebookIslandOpts) => Promise<ScrapeQidsResult>;
}

function buildConfig(opts: GradebookIslandOpts): GradebookConfig {
  return {
    scriptsDir: resolveScriptsDir(opts),
    outDir: opts.outDir ?? resolveDefaultOutDir(opts),
  };
}

async function runFloorScores(
  opts: FloorScoresOpts & GradebookIslandOpts,
): Promise<RunResult> {
  const cfg = buildConfig(opts);
  return _runFloorScores(
    {
      ...opts,
      outDir: opts.outDir ?? cfg.outDir,
    },
    { scriptsDir: cfg.scriptsDir },
  );
}

async function runScrapeQids(
  opts: ScrapeQidsOpts & GradebookIslandOpts,
): Promise<ScrapeQidsResult> {
  const cfg = buildConfig(opts);
  return _runScrapeQids(opts, { scriptsDir: cfg.scriptsDir });
}

export const gradebookIsland = defineIsland<GradebookMethods>({
  id: 'gradebook',
  label: 'Gradebook',
  methods: {
    runFloorScores,
    runScrapeQids,
  },
});

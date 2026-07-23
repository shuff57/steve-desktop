/**
 * gradebook-island — rehosts the gradebook/playwright-grading scripts as
 * in-app Skills. The scripts run as Bun subprocesses via `runner.ts`; the
 * island exposes a typed surface to the rest of the app.
 */
import { defineIsland } from '../_shared/island';
import { runFloorScores, runScrapeQids, type RunResult, type ScrapeQidsResult } from './runner';
import type { FloorScoresOpts, ScrapeQidsOpts } from './args';

export interface GradebookMethods {
  /** Run the floor-grader. Dry-run by default; opt in with opts.writeBack. */
  runFloorScores: (opts: FloorScoresOpts) => Promise<RunResult>;
  /** Scrape question number -> qid map for an assignment. */
  runScrapeQids: (opts: ScrapeQidsOpts) => Promise<ScrapeQidsResult>;
}

export const gradebookIsland = defineIsland<GradebookMethods>({
  id: 'gradebook',
  label: 'Gradebook',
  methods: {
    runFloorScores,
    runScrapeQids,
  },
});

/**
 * gradebook-island — rehosts the gradebook/playwright-grading scripts as
 * in-app Skills (shell-out to Bun subprocess). Stub in phase 0; methods added
 * in phase 1.
 */
import { defineIsland } from '../_shared/island';

export interface GradebookMethods {
  // Intentionally empty for phase 0. Phase 1 will add:
  // - runFloorScores(opts) -> { ok, stdout, csvPaths, error? }
  // - scrapeQids(opts)     -> { ok, qids, stdout, error? }
}

export const gradebookIsland = defineIsland<GradebookMethods>({
  id: 'gradebook',
  label: 'Gradebook',
  methods: {} as GradebookMethods,
});

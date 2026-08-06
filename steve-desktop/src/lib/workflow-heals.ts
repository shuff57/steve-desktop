import type { Workflow } from './types/site-profile';

/**
 * Copy the durable portion of a replay's self-heal back to its persisted master workflow.
 * Values are intentionally excluded: a replay may resolve fixed tokens or bind per-row data,
 * neither of which may be serialized into the skill.
 */
export function syncHealedAnchors(master: Workflow, replayed: Workflow): void {
  replayed.steps.forEach((replayedStep, i) => {
    const masterStep = master.steps[i];
    if (!masterStep) return;
    masterStep.selector = replayedStep.selector;
    if (replayedStep.candidates) masterStep.candidates = replayedStep.candidates;
  });
}

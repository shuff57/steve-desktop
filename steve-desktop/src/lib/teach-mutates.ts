// Teach's equivalent of AutomateRunner's [MUTATES] tag (cli-automate.ts) — which steps change the
// site vs. only read it, for the approval-card warning badge.

import type { WorkflowAction, WorkflowStep } from './types/site-profile';

const MUTATING_ACTIONS: ReadonlySet<WorkflowAction> = new Set([
  'click',
  'fill',
  'select',
  'navigate',
  'keyboard',
  'answer-quiz',
]);

/** Default classification by action: click/fill/select/navigate/keyboard/answer-quiz change the
 *  site; wait-for/scroll only observe it. */
export function deriveMutates(action: WorkflowAction): boolean {
  return MUTATING_ACTIONS.has(action);
}

/** A step's own `mutates` overrides the derived default when present. */
export function stepMutates(step: WorkflowStep): boolean {
  return step.mutates ?? deriveMutates(step.action);
}

// Fixed-value tokens — the mirror of teach-params.ts. teach-params PARAMETERIZES values that VARY
// per run (a student name bound from a roster row) and drops the literal from the stored skill.
// This promotes values that are FIXED but repeated or worth editing in one place (a course URL, a
// course id, an assignment name) into workflow.values, referenced from steps as "{{key}}".

import type { Workflow } from './types/site-profile';
import { paramSlug } from './teach-params';
import { createPageMask } from './page-agent-mask';

const TOKEN_RE = /^\{\{([a-z0-9_]+)\}\}$/;

/**
 * Would masking change this value at all? A promoted value goes into `workflow.values` — into a
 * saved SKILL.md — with no further redaction pass, so this is the gate standing between a
 * teacher's click and a student's name on disk. It reuses the mask (the app's one definition of
 * "identifying"), rather than a second, narrower name-shape regex that would inevitably drift
 * from it: a two-part name, a comma-name, an email, a phone number, or an SSN are all caught
 * because the mask already catches them. `plainNames: true` because a token's source value is
 * teacher-typed/element data, not a page's own UI chrome, so the plain-name false-positive gate
 * that protects "Total Score"-style labels does not apply here either (page-agent-mask.ts).
 *
 * `url` is the value's real origin (the workflow's start URL) — NOT forced to a people surface.
 * It used to be forced, to keep the bare-student-id tier active regardless of where the value
 * came from, but that made an ordinary bare course id ("334243") indistinguishable from a bare
 * student id and refused both — blocking the feature's main use case (course ids, section
 * numbers) to protect a value that never leaves the machine anyway: `values` is genericized to
 * ⟦V n⟧ before the polish prompt (teach-polish.ts's `tokenizeWorkflowValues`), so this guard is
 * protecting a local file on disk, not the wire. `plainNames: true` still catches a plain-form
 * name regardless of `url` — that tier was never gated on the surface, only the numeric-id one was.
 *
 * Known gap, accepted deliberately: a BARE single name ("Jordan") is not itself a two-word shape,
 * and nothing in this codebase's redaction stack tokenizes lone capitalized words — PERSON_LABEL
 * (redact-tree.ts) requires two, on purpose, because a single capitalized word is indistinguishable
 * from an ordinary short label ("Newton", "Final") without a roster to check against. No detector
 * for it exists anywhere else on this FERPA boundary, including the live path driving real
 * gradebooks today. This function inherits that same limit rather than inventing a new one.
 */
export function isSafeToPromote(value: string, url?: string): boolean {
  return createPageMask().text(value, url, { plainNames: true }) === value;
}

/** Promote a step's literal value to a named token stored on workflow.values, replacing the
 *  step's value with "{{key}}". Refuses a parameterized step — a step is either `param` (varies
 *  per run) or `{{…}}` (fixed), never both — and refuses a value `isSafeToPromote` flags as
 *  identifying; this is the enforcement boundary, not the UI control that offers it. `url` should
 *  be the workflow's start URL — falls back to `workflow.trigger` (which encodes it, "On <url>")
 *  when omitted, same fallback `maskWorkflowForPrompt` uses. Reuses an existing token of the same
 *  name if its stored value already matches, so multiple steps can share one edit. */
export function promoteToToken(workflow: Workflow, stepIndex: number, name: string, url?: string): Workflow {
  const step = workflow.steps[stepIndex];
  if (!step) return workflow;
  if (step.param) throw new Error('Step is parameterized — cannot also hold a fixed token.');
  if (step.value === undefined) return workflow;
  if (!isSafeToPromote(step.value, url ?? workflow.trigger)) {
    throw new Error('Refusing to promote a value that looks identifying — tokens are for URLs, ids and course names, not student data.');
  }
  const existing = workflow.values ?? {};
  const base = paramSlug(name);
  let key = base;
  for (let n = 2; existing[key] !== undefined && existing[key] !== step.value; n++) key = `${base}_${n}`;
  return {
    ...workflow,
    values: { ...existing, [key]: step.value },
    steps: workflow.steps.map((s, i) => (i === stepIndex ? { ...s, value: `{{${key}}}` } : s)),
  };
}

/** Edit a token's stored value in place. */
export function setTokenValue(workflow: Workflow, key: string, value: string): Workflow {
  return { ...workflow, values: { ...workflow.values, [key]: value } };
}

/** Clone the workflow with every step's "{{key}}" value resolved from workflow.values. Throws on
 *  an unresolved key — a silently empty value replayed into a live gradebook is worse than
 *  failing. Composes with teach-params.bindWorkflow: a step's value is either a literal, a bound
 *  param, or a "{{key}}" token — never both param and token on the same step, so the two
 *  resolution passes never touch the same step and can run in either order. */
export function resolveTokens(workflow: Workflow): Workflow {
  return {
    ...workflow,
    steps: workflow.steps.map((s) => {
      if (typeof s.value !== 'string') return s;
      const m = TOKEN_RE.exec(s.value);
      if (!m) return s;
      const v = workflow.values?.[m[1]];
      if (v === undefined) throw new Error(`Unresolved token {{${m[1]}}} — set its value before replay.`);
      return { ...s, value: v };
    }),
  };
}

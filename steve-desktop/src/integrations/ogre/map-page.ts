/**
 * Turn a grading page steve doesn't recognise into a saved grading profile.
 *
 * The teacher, who knows the page is a gradebook, triggers this after "Load students"
 * finds nothing. It captures a structural DOM snapshot of the page, derives the five
 * grading selectors from that structure (deriveGradingSelectors — no text, no model, no
 * PII), and saves a site_profiles row so the page auto-matches from then on.
 *
 * Read-only against the page: one evaluated snapshot expression, no clicks, no writes —
 * the same constraint load-students holds, for the same audit-log reason.
 */
import { buildSmartWalkScript } from '../../lib/dom-snapshot';
import { isValidSnapshotResult, type SnapshotResult } from '../../lib/dom-snapshot-types';
import { deriveGradingSelectors } from './discover-selectors';
import { saveGradingProfile } from './db';
import type { SiteSelectors } from './load-students';

export interface MapPageResult {
  profileId: string;
  selectors: SiteSelectors;
}

/**
 * Capture → derive → save. Throws when the page has no repeated student structure to learn
 * from, rather than saving a guess that would then silently misgrade.
 *
 * `evaluate` is the CDP client in the app, a stub in tests. `url` is the page being mapped
 * (its host+path becomes the profile key).
 */
export async function mapPageForGrading(
  evaluate: (expression: string) => Promise<unknown>,
  url: string,
): Promise<MapPageResult> {
  const raw = await evaluate(buildSmartWalkScript());
  // The in-page script returns the object directly (returnByValue), so validate it as-is
  // rather than re-parsing a JSON string.
  if (!isValidSnapshotResult(raw)) {
    throw new Error('Could not read the page structure — the snapshot came back malformed.');
  }
  const snapshot = raw as SnapshotResult;

  const selectors = deriveGradingSelectors(snapshot);
  if (!selectors) {
    throw new Error(
      "Couldn't find a repeating student layout on this page, so there's nothing to learn. " +
        'Open a page that lists each student’s work (one row per student) and try again.',
    );
  }

  const profileId = await saveGradingProfile(url, selectors);
  return { profileId, selectors };
}

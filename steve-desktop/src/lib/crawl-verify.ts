import type { SiteProfile } from './types/site-profile';
import { structuralSignature } from './site-map';

// Post-crawl verification. A crawl proves a page was *reachable once*; it does not prove the
// selectors it recorded still resolve when the agent comes back to act on them. Selectors
// captured off a live gradebook are often index- or data-dependent (nth-child on a roster row),
// so they work at capture time and silently point at the wrong element later.
//
// This re-visits each mapped page and grades every recorded target. Read-only: it resolves
// selectors and compares shape, it never clicks. Clicking to prove reachability would be a
// write against a live course, which is the one thing the crawl exists to avoid.

export type CheckStatus =
  /** Exactly one element matched — the agent can act on this safely. */
  | 'ok'
  /** Primary selector missed, but a ranked candidate resolved uniquely. Self-heal covers it. */
  | 'healed'
  /** Nothing matched. The agent cannot reach this control. */
  | 'broken'
  /**
   * Several elements matched. Dangerous to ACT through — a click silently hits the first match,
   * which may be a different student's row. But it is a statement about the element's
   * anchorability (no unique selector exists for it), not about the page having changed:
   * verify grades the FRESH capture against its own live page, so a multi-match here was
   * multi-match at capture time too. Reported per element and in the summary; it does not make
   * the page "drifted".
   */
  | 'ambiguous';

export interface ElementCheck {
  kind: 'button' | 'input' | 'link';
  label: string;
  selector: string;
  status: CheckStatus;
  matches: number;
  /** The candidate that rescued a missed primary selector. */
  healedWith?: string;
}

export interface PageVerdict {
  url: string;
  pageName: string;
  /** ok = every target resolves (uniquely or not); drifted = some target BROKE (nothing matched
   *  and no candidate rescued it); unreachable = nav/capture failed. Ambiguous targets are
   *  weak anchors, surfaced per check + in the summary — they never flip a page to drifted,
   *  which used to mark 130/178 pages of a static site as drifted. */
  status: 'ok' | 'drifted' | 'unreachable';
  /** Where we actually landed. A redirect away from `url` means the map's path no longer holds. */
  landedUrl?: string;
  /** Did the page keep the same shape it had during the crawl? */
  signatureMatch: boolean;
  checks: ElementCheck[];
  error?: string;
  /**
   * The page was PROVEN dead — it loaded and served an error (404 / "Not Found"). Only this
   * justifies deleting an unreachable page. A nav/capture timeout leaves this false: on a live
   * MyOpenMath course, load-time flakiness marked the gradebook, roster, course home and
   * coursemap "unreachable", and auto-pruning deleted all four. A page that merely failed to
   * answer in time is kept and flagged for a re-check.
   */
  errorPage?: boolean;
  /**
   * We failed to OBSERVE the page (capture collapsed mid-render), so no verdict about its
   * contents is trustworthy. The pruner skips these entirely — an unverified page is kept.
   */
  unverified?: boolean;
}

/** Does this look like the server's own error page rather than the page we asked for? */
export function isErrorPage(title: string, bodyText: string): boolean {
  const t = `${title}\n${bodyText.slice(0, 400)}`;
  return /\b(404|403|500)\b|\bnot found\b|\bpage (?:not|no longer) available\b|\baccess denied\b/i.test(t);
}

/** Every actionable target a profile recorded, flattened for checking. */
export function targetsOf(
  profile: SiteProfile,
): { kind: ElementCheck['kind']; label: string; selector: string; candidates: string[] }[] {
  const cand = (c?: { value: string }[]) => (c ?? []).map((x) => x.value).filter(Boolean);
  return [
    ...profile.interactive.buttons.map((b) => ({
      kind: 'button' as const,
      label: b.text || '(unlabelled)',
      selector: b.selector,
      candidates: cand(b.candidates),
    })),
    ...profile.interactive.inputs.map((i) => ({
      kind: 'input' as const,
      label: i.label || '(unlabelled)',
      selector: i.selector,
      candidates: cand(i.candidates),
    })),
    ...profile.interactive.links.map((l) => ({
      kind: 'link' as const,
      label: l.text || '(no label)',
      selector: l.selector,
      candidates: cand(l.candidates),
    })),
  ].filter((t) => !!t.selector);
}

/**
 * Grade a page's targets against how many elements each selector matches on the live page.
 * `counts` is built browser-side in a single pass (one round trip for the whole page, not one
 * per selector) and passed in, which keeps this function pure and testable.
 *
 * `subject` is the FRESH capture — its selectors are what an agent would actually act on.
 * `baseline` is the profile stored during the crawl, used only for shape comparison: stored
 * profiles are redacted, so their selectors may carry ⟦tokens⟧ that could never resolve live.
 * structuralSignature's dataFree() strips those tokens, so the shape still compares honestly.
 */
export function gradePage(
  subject: SiteProfile,
  baseline: SiteProfile | null,
  counts: Record<string, number>,
  landedUrl?: string,
): PageVerdict {
  const checks: ElementCheck[] = targetsOf(subject).map((t) => {
    const n = counts[t.selector] ?? 0;
    if (n === 1) return { kind: t.kind, label: t.label, selector: t.selector, status: 'ok', matches: 1 };
    if (n > 1)
      return { kind: t.kind, label: t.label, selector: t.selector, status: 'ambiguous', matches: n };
    const healedWith = t.candidates.find((c) => counts[c] === 1);
    return healedWith
      ? { kind: t.kind, label: t.label, selector: t.selector, status: 'healed', matches: 0, healedWith }
      : { kind: t.kind, label: t.label, selector: t.selector, status: 'broken', matches: 0 };
  });

  const signatureMatch = !!baseline && structuralSignature(baseline) === structuralSignature(subject);
  // A capture that collapsed (page not finished rendering) makes EVERY target look broken. That
  // is a failure to observe, not drift — on a live course it condemned the course home, roster
  // and forums, all of which load fine. Treat an all-broken page whose baseline had real targets
  // as unverified: not drifted, so the pruner leaves it alone.
  const allBroken = checks.length > 0 && checks.every((c) => c.status === 'broken');
  const baselineHadTargets = !!baseline && targetsOf(baseline).length >= 3;
  const captureCollapsed = allBroken && baselineHadTargets;
  const drifted = !captureCollapsed && checks.some((c) => c.status === 'broken');
  return {
    url: subject.url,
    pageName: subject.pageName,
    status: drifted ? 'drifted' : 'ok',
    landedUrl,
    signatureMatch,
    checks,
    ...(captureCollapsed ? { unverified: true } : {}),
  };
}

/** Every distinct selector a page needs resolved — primaries plus their fallback candidates. */
export function selectorsToProbe(profile: SiteProfile): string[] {
  const out = new Set<string>();
  for (const t of targetsOf(profile)) {
    out.add(t.selector);
    for (const c of t.candidates) out.add(c);
  }
  return [...out];
}

export interface PruneDecision {
  url: string;
  pageName: string;
  reason: string;
}

/**
 * Which verified pages to drop so the map lands "good" without a human pass:
 * - unreachable AND proven dead (errorPage) — the server served a 404/error. A page that merely
 *   timed out is KEPT: deletion is irreversible and a slow page is not a bad page.
 * - drifted — a target BROKE and nothing healed it; the page's recorded actions are stale.
 * - no reliable anchors — it has targets but not one resolves uniquely (every check ambiguous);
 *   an agent could not act on anything there without guessing.
 * - duplicate — it redirected to a landed URL another kept page already covers (/page/1/ ≡ /).
 * Pages with NO checks are kept: a content page with nothing interactive is a valid map entry.
 */
export function pagesToPrune(verdicts: PageVerdict[]): PruneDecision[] {
  const out: PruneDecision[] = [];
  const seenLanded = new Set<string>();
  for (const v of verdicts) {
    const landed = (v.landedUrl ?? v.url).replace(/\/+$/, '');
    if (v.unverified) continue; // never delete on a failure to observe
    if (v.status === 'unreachable') {
      if (v.errorPage) out.push({ url: v.url, pageName: v.pageName, reason: 'dead — server returned an error page' });
      continue; // timed out / failed to capture → keep it, never delete on a flake
    }
    if (v.status === 'drifted') {
      out.push({ url: v.url, pageName: v.pageName, reason: 'broken targets' });
      continue;
    }
    if (v.checks.length && !v.checks.some((c) => c.status === 'ok' || c.status === 'healed')) {
      out.push({ url: v.url, pageName: v.pageName, reason: 'no reliable anchors — every target ambiguous' });
      continue;
    }
    if (seenLanded.has(landed)) {
      out.push({ url: v.url, pageName: v.pageName, reason: `duplicate — lands on already-kept ${landed}` });
      continue;
    }
    seenLanded.add(landed);
  }
  return out;
}

export interface VerifySummary {
  pages: number;
  ok: number;
  drifted: number;
  unreachable: number;
  broken: number;
  ambiguous: number;
  healed: number;
  /** True when every page is ok and nothing needed self-heal — the map is fully trustworthy. */
  clean: boolean;
}

export function summarize(verdicts: PageVerdict[]): VerifySummary {
  const count = (s: CheckStatus) =>
    verdicts.reduce((n, v) => n + v.checks.filter((c) => c.status === s).length, 0);
  const broken = count('broken');
  const ambiguous = count('ambiguous');
  const healed = count('healed');
  return {
    pages: verdicts.length,
    ok: verdicts.filter((v) => v.status === 'ok').length,
    drifted: verdicts.filter((v) => v.status === 'drifted').length,
    unreachable: verdicts.filter((v) => v.status === 'unreachable').length,
    broken,
    ambiguous,
    healed,
    clean: broken === 0 && ambiguous === 0 && healed === 0 && verdicts.every((v) => v.status === 'ok'),
  };
}

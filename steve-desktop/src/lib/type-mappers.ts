import type { DiscoveryResult } from './discover';
import type { InteractiveElements, SiteProfile } from './types/site-profile';

function emptyInteractive(): InteractiveElements {
  return {
    buttons: [],
    links: [],
    inputs: [],
    selects: [],
    checkboxes: [],
    radios: [],
    forms: [],
  };
}

function normalizeInteractive(interactive?: Partial<InteractiveElements>): InteractiveElements {
  return {
    buttons: interactive?.buttons ?? [],
    links: interactive?.links ?? [],
    inputs: interactive?.inputs ?? [],
    selects: interactive?.selects ?? [],
    checkboxes: interactive?.checkboxes ?? [],
    radios: interactive?.radios ?? [],
    forms: interactive?.forms ?? [],
  };
}

function countLandmarks(landmarks?: SiteProfile['landmarks']): number {
  if (!landmarks) return 0;
  return Object.values(landmarks).reduce((total, entries) => total + entries.length, 0);
}

export function discoveryResultToProfile(
  result: DiscoveryResult,
  url: string,
  domain: string,
  pageName: string,
): SiteProfile {
  const interactive = normalizeInteractive(result.profile.interactive);
  const landmarks = result.profile.landmarks;
  const headings = result.profile.headings ?? [];

  return {
    url,
    domain,
    pageName,
    profiledAt: new Date().toISOString(),
    title: result.profile.title,
    goal: result.profile.goal,
    interactive,
    workflows: result.profile.workflows,
    frames: result.profile.frames,
    landmarks,
    headings,
    summary: {
      buttons: interactive.buttons.length,
      links: interactive.links.length,
      inputs: interactive.inputs.length,
      selects: interactive.selects.length,
      checkboxes: interactive.checkboxes.length,
      radios: interactive.radios.length,
      forms: interactive.forms.length,
      landmarks: countLandmarks(landmarks),
      headings: headings.length,
    },
  };
}

export function profileToDiscoveryResult(profile: SiteProfile): Partial<DiscoveryResult> {
  return {
    confidence: 'high',
    profile: {
      title: profile.title,
      goal: profile.goal,
      interactive: normalizeInteractive(profile.interactive),
      workflows: profile.workflows,
      frames: profile.frames,
      landmarks: profile.landmarks,
      headings: profile.headings,
      summary: profile.summary,
    },
  };
}

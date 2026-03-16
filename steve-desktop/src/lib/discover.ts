import type { SiteProfile } from './types/site-profile';
import type { SnapshotResult } from './dom-snapshot-types';

export interface DiscoveryHints {
  pageDescription?: string;
  requiredSelectors?: string[];
  generalizedSelectors?: Array<{ selector: string; elementType: string; confidence: number }>;
  extraContext?: string;
  navigationMode?: 'sequential' | 'batch';
  promptAddendum?: string;
}

export interface DiscoveryOptions {
  url: string;
  domSnapshot: string;
  screenshot?: string;
  hints?: DiscoveryHints;
  maxAttempts?: number;
}

export interface DiscoveryResult {
  profile: Partial<SiteProfile>;
  confidence: 'high' | 'medium' | 'low';
  notes?: string;
}

export interface DiscoveryWorkflow {
  result: DiscoveryResult;
  validation: Record<string, { matchCount: number; sampleText: string; valid: boolean }>;
}

export const DISCOVERY_SYSTEM_PROMPT = `You are a web page structure analyzer. Analyze the provided DOM snapshot and screenshot to identify interactive elements on the page.

Extract and return a JSON object with:
- buttons: array of { text, selector, purpose }
- links: array of { text, selector, href }
- inputs: array of { label, selector, type }
- forms: array of { name, selector }
- landmarks: key-value of landmark type to selector
- headings: array of { level, text }
- frames: any iframes detected with their origins

Focus on what users can interact with. Be specific about selectors (prefer role-based over CSS class).`;

export function buildDiscoveryPrompt(url: string, snapshot: string, hints?: DiscoveryHints): string {
  let prompt = `Analyze this web page:\nURL: ${url}\n\nDOM Snapshot:\n${snapshot}`;
  if (hints?.pageDescription) prompt += `\n\nPage context: ${hints.pageDescription}`;
  if (hints?.requiredSelectors?.length) prompt += `\n\nFocus on finding: ${hints.requiredSelectors.join(', ')}`;
  if (hints?.promptAddendum) prompt += `\n\n${hints.promptAddendum}`;
  return prompt;
}

export function parseDiscoveryResponse(json: string): DiscoveryResult {
  try {
    const cleaned = json.replace(/```json\n?/g, '').replace(/```\n?/g, '').trim();
    const parsed = JSON.parse(cleaned);
    return {
      profile: { interactive: parsed as SnapshotResult as unknown as SiteProfile['interactive'] },
      confidence: 'medium',
    };
  } catch {
    return {
      profile: {},
      confidence: 'low',
      notes: 'Failed to parse discovery response',
    };
  }
}

import type { SnapshotResult } from './dom-snapshot-types';
import type { SiteProfile } from './types/site-profile';

// click/fill carry either a ref (preferred — resolved against the page-side registry
// built by the last capture) or a selector. toBrowserAction rejects actions with neither.
// ponytail: both optional rather than a target union, so stored site-profiles keep
// replaying by selector. Collapse to a union once profiles are migrated to refs.
export type BrowserAction =
  | { type: 'click'; ref?: string; selector?: string; description?: string }
  | { type: 'fill'; ref?: string; selector?: string; value: string; description?: string }
  | { type: 'navigate'; url: string; description?: string }
  | { type: 'wait'; condition: string; timeout?: number; description?: string }
  | { type: 'keyboard'; key: string; description?: string }
  | { type: 'scroll'; direction: 'up' | 'down' | 'left' | 'right'; description?: string }
  | {
      type: 'iframe_interact';
      frameSelector: string;
      action: Exclude<BrowserAction, { type: 'iframe_interact' }>;
      description?: string;
    };

export type AgentMode = 'review' | 'auto';
export type AgentState = 'idle' | 'thinking' | 'proposing' | 'executing' | 'done' | 'error';

export interface ActionResult {
  success: boolean;
  error?: string;
  data?: unknown;
}

export interface AgentMessage {
  role: 'user' | 'assistant' | 'action' | 'result' | 'system';
  content: string;
  action?: BrowserAction;
  result?: ActionResult;
  screenshot?: string;
}

export interface AgentConfig {
  maxSteps: number;
  maxTimeMs: number;
  maxSameAction: number;
  maxConsecutiveFailures: number;
  actionDelayMs: number;
}

export const DEFAULT_AGENT_CONFIG: AgentConfig = {
  maxSteps: 30,
  maxTimeMs: 300000,
  maxSameAction: 3,
  maxConsecutiveFailures: 5,
  actionDelayMs: 0,
};

export interface InteractiveElement {
  ref: string;
  tag: string;
  type?: string;
  id?: string;
  name?: string;
  placeholder?: string;
  text: string;
  value?: string;
  href?: string;
  disabled: boolean;
  visible: boolean;
}

export interface AgentApiRequest {
  messages: AgentMessage[];
  dom?: string;
  screenshot?: string;
  provider?: string;
  model?: string;
  snapshot?: SnapshotResult;
  profile?: SiteProfile;
}

export interface AgentActionResponse {
  action: BrowserAction['type'] | 'done';
  params: Record<string, unknown>;
  reasoning?: string;
}

export interface AgentTextResponse {
  text: string;
}

export type AgentApiResponse = AgentActionResponse | AgentTextResponse;

export const DANGEROUS_JS_PATTERNS: string[] = [
  'eval(',
  'Function(',
  '__proto__',
  'import(',
  'fetch(',
  '__lookupGetter__',
  '__lookupSetter__',
  'constructor.constructor',
  'document.write',
  'document.writeln',
];

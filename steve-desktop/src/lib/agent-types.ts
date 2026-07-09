import type { SnapshotResult } from './dom-snapshot-types';
import type { SiteProfile } from './types/site-profile';

export type BrowserAction =
  | { type: 'click'; selector: string; description?: string }
  | { type: 'fill'; selector: string; value: string; description?: string }
  // PII-safe transfer: read a value into an on-device slot, paste it elsewhere by
  // slot name. The value moves device-locally and is NEVER returned to the model.
  | { type: 'read'; selector: string; into: string; description?: string }
  | { type: 'paste'; selector: string; from: string; description?: string }
  // Log in to the current site with saved credentials, filled on-device. The
  // username/password are NEVER returned to the model.
  | { type: 'login'; site?: string; description?: string }
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
export type AgentState = 'idle' | 'thinking' | 'proposing' | 'executing' | 'awaiting-approval' | 'done' | 'error';

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
  index: number;
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
  selector: string;
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

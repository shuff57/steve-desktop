export interface SelectorCandidate {
  type: 'data-testid' | 'id' | 'role-name' | 'name' | 'placeholder' | 'class';
  value: string;
  score: number;
}

export interface ElementContext {
  landmark?: string;
  landmarkLabel?: string;
  form?: string;
  nearestHeading?: string;
}

export interface ButtonElement {
  text: string;
  selector: string;
  purpose?: string;
  candidates?: SelectorCandidate[];
  disabled?: boolean;
  context?: ElementContext;
}

export interface LinkElement {
  text: string;
  selector: string;
  href?: string;
  purpose?: string;
  candidates?: SelectorCandidate[];
  context?: ElementContext;
}

export interface InputElement {
  label: string;
  selector: string;
  type?: string;
  required?: boolean;
  /** Discovery flags fields that look like a person identifier (name/ID/SSN/DOB…). */
  identifier?: boolean;
  candidates?: SelectorCandidate[];
  context?: ElementContext;
}

export interface InteractiveElements {
  buttons: ButtonElement[];
  links: LinkElement[];
  inputs: InputElement[];
  selects: unknown[];
  checkboxes: unknown[];
  radios: unknown[];
  forms: unknown[];
}

export type WorkflowAction = 'click' | 'fill' | 'select' | 'wait-for' | 'answer-quiz' | 'navigate' | 'keyboard' | 'scroll';

export interface WorkflowStep {
  action: WorkflowAction;
  selector?: string;
  /** Ranked alternate anchors (role=name, #id, data-testid, …) tried in order on self-heal. */
  candidates?: string[];
  value?: string;
  /** Variable name for fill/select steps: replay binds this from a roster row instead of `value`.
   *  A parameterized step stores NO literal recorded value (FERPA: skills are de-identified). */
  param?: string;
  key?: string;
  /** Weighted signals captured for this step's element (fingerprint.ts). Additive: steps
   *  recorded before this existed simply heal without it. */
  fingerprint?: import('../fingerprint').ElementFingerprint;
  frame?: string;
  context?: 'outer' | 'iframe';
  condition?: string;
  timeout?: number;
  description?: string;
}

export interface Workflow {
  name: string;
  trigger?: string;
  steps: WorkflowStep[];
}

export interface FrameInfo {
  selector?: string;
  origin?: string;
  crossOrigin?: boolean;
  accessible?: boolean;
  note?: string;
}

export interface LandmarkInfo {
  selector: string;
  label: string;
}

export interface HeadingInfo {
  level: number;
  text: string;
}

export interface ProfileSummary {
  buttons: number;
  links: number;
  inputs: number;
  selects: number;
  checkboxes: number;
  radios: number;
  forms: number;
  landmarks: number;
  headings: number;
}

export interface SiteProfile {
  url: string;
  domain: string;
  pageName: string;
  profiledAt: string;
  title?: string;
  goal?: string;
  interactive: InteractiveElements;
  /** 3–5 durable anchors that answer "is this page still itself?". Verify resolves only these
   *  and escalates to a full re-map when one is missing (key-nodes.ts). Additive — profiles
   *  captured before this simply verify the old, exhaustive way. */
  keyNodes?: { selector: string; label: string }[];
  workflows?: Workflow[];
  frames?: Record<string, FrameInfo>;
  landmarks?: Record<string, LandmarkInfo[]>;
  headings?: HeadingInfo[];
  summary: ProfileSummary;
}

export function isSiteProfile(value: unknown): value is SiteProfile {
  if (typeof value !== 'object' || value === null) return false;
  const obj = value as Record<string, unknown>;
  return (
    typeof obj['url'] === 'string' &&
    typeof obj['domain'] === 'string' &&
    typeof obj['pageName'] === 'string' &&
    typeof obj['profiledAt'] === 'string' &&
    typeof obj['interactive'] === 'object' &&
    typeof obj['summary'] === 'object'
  );
}

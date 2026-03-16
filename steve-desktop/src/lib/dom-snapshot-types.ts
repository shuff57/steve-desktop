export type NodePriority = 'critical' | 'high' | 'medium' | 'low';

export interface BoundingBox {
  x: number;
  y: number;
  width: number;
  height: number;
  visible: boolean;
}

export interface SnapshotNode {
  tag: string;
  depth: number;
  priority: NodePriority;
  text?: string;
  attrs: Record<string, string>;
  selector?: string;
  bbox?: BoundingBox;
}

export interface SnapshotMetadata {
  totalVisited: number;
  nodesIncluded: number;
  nodesDropped: number;
  wasTruncated: boolean;
  charCount: number;
  capturedAt: string;
}

export interface SnapshotResult {
  nodes: SnapshotNode[];
  meta: SnapshotMetadata;
}

export interface SnapshotOptions {
  maxNodes?: number;
  maxChars?: number;
  maxDepth?: number;
  captureBoundingBoxes?: boolean;
  rootSelector?: string;
}

export const LOW_PRIORITY_TAGS: ReadonlySet<string> = new Set([
  'script',
  'style',
  'noscript',
  'meta',
  'link',
  'head',
  'svg',
  'path',
  'g',
  'defs',
  'symbol',
]);

export const HIGH_PRIORITY_TAGS: ReadonlySet<string> = new Set([
  'form',
  'table',
  'tr',
  'td',
  'th',
  'button',
  'select',
  'details',
  'summary',
  'label',
]);

export const CRITICAL_ROLES: ReadonlySet<string> = new Set([
  'button',
  'checkbox',
  'combobox',
  'link',
  'menuitem',
  'option',
  'radio',
  'slider',
  'spinbutton',
  'switch',
  'tab',
  'textbox',
]);

function hasGenericSignal(attrs: Record<string, string>): boolean {
  return Object.keys(attrs).some((key) => key.startsWith('data-') || key.startsWith('aria-') || key === 'role');
}

export function classifyNodePriority(
  tag: string,
  attrs: Record<string, string>,
  hasText: boolean,
  childCount: number,
): NodePriority {
  if (LOW_PRIORITY_TAGS.has(tag)) return 'low';

  const role = (attrs.role ?? '').toLowerCase();
  const hasSignal = hasGenericSignal(attrs);
  const isInteractiveRole = CRITICAL_ROLES.has(role);

  if (tag === 'input' || tag === 'textarea') return 'critical';
  if (isInteractiveRole) return 'critical';
  if (hasSignal && (tag === 'button' || tag === 'a' || tag === 'select')) return 'critical';

  if (HIGH_PRIORITY_TAGS.has(tag)) return 'high';
  if (hasSignal) return 'medium';
  if (hasText || childCount > 0) return 'medium';

  return 'low';
}

export function isSnapshotNode(value: unknown): value is SnapshotNode {
  if (typeof value !== 'object' || value === null) return false;

  const candidate = value as Record<string, unknown>;

  return (
    typeof candidate.tag === 'string' &&
    typeof candidate.depth === 'number' &&
    typeof candidate.priority === 'string' &&
    ['critical', 'high', 'medium', 'low'].includes(candidate.priority) &&
    typeof candidate.attrs === 'object' &&
    candidate.attrs !== null &&
    !Array.isArray(candidate.attrs)
  );
}

export function isValidSnapshotResult(value: unknown): value is SnapshotResult {
  if (typeof value !== 'object' || value === null) return false;

  const candidate = value as Record<string, unknown>;
  const meta = candidate.meta as Record<string, unknown> | undefined;

  return (
    Array.isArray(candidate.nodes) &&
    candidate.nodes.every(isSnapshotNode) &&
    typeof meta === 'object' &&
    meta !== null &&
    typeof meta.totalVisited === 'number' &&
    typeof meta.nodesIncluded === 'number' &&
    typeof meta.nodesDropped === 'number' &&
    typeof meta.wasTruncated === 'boolean' &&
    typeof meta.charCount === 'number' &&
    typeof meta.capturedAt === 'string'
  );
}

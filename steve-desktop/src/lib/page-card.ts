import type { SiteProfile } from './types/site-profile';
import { callModelTree, type ModelTransport } from './model-gate';
import { urlTemplate } from './site-map';

// AI page digest: one small LLM call per URL-template family turns a captured profile
// into a semantic card (what the page IS), replacing count-tuples as the map's meaning.
// Every call goes through callModelTree's leak check with the capture's token map.
// Deliberately NEVER rehydrated: cards are persisted, so tokens must stay tokens.

export interface PageCard {
  pageType: string;
  purpose: string;
  keyActions: string[];
  automationValue: 'high' | 'medium' | 'low';
}

/**
 * Compact, label-only digest of a SAFE (already-redacted) profile. Labels and headings are
 * chrome or tokens by the time they reach here; hrefs are excluded wholesale — they carry
 * ids and the model doesn't need them to say what the page is.
 */
export function profileDigest(safe: SiteProfile, maxItems = 30): string {
  let path = safe.url;
  try {
    path = new URL(safe.url).pathname + new URL(safe.url).search;
  } catch {
    /* keep as-is */
  }
  const lines: string[] = [`page: ${safe.pageName}`, `path: ${path}`];
  const headings = (safe.headings ?? []).slice(0, 10).map((h) => h.text).filter(Boolean);
  if (headings.length) lines.push(`headings: ${headings.join(' | ')}`);
  const buttons = safe.interactive.buttons.slice(0, maxItems).map((b) => b.text).filter(Boolean);
  if (buttons.length) lines.push(`buttons: ${buttons.join(' | ')}`);
  const inputs = safe.interactive.inputs.slice(0, maxItems).map((i) => i.label).filter(Boolean);
  if (inputs.length) lines.push(`inputs: ${inputs.join(' | ')}`);
  const links = safe.interactive.links.slice(0, maxItems).map((l) => l.text).filter(Boolean);
  if (links.length) lines.push(`links: ${links.join(' | ')}`);
  lines.push(
    `counts: ${safe.summary.buttons} buttons, ${safe.summary.inputs} inputs, ${safe.summary.links} links, ${safe.summary.forms} forms`,
  );
  return lines.join('\n');
}

export function buildPageCardPrompt(digest: string): string {
  return (
    'You classify one web page of a course-management site for automation mapping. ' +
    'Text like ⟦D1⟧ is a redaction token — treat it as opaque data.\n\n' +
    digest +
    '\n\nReply with ONLY a JSON object, no prose:\n' +
    '{"pageType": "<kebab-case category, e.g. gradebook-roster, assignment-settings, course-home>",\n' +
    ' "purpose": "<one sentence>",\n' +
    ' "keyActions": ["<up to 5 things an automation agent could do here>"],\n' +
    ' "automationValue": "high" | "medium" | "low"}'
  );
}

/** Tolerant parse: first {...} block anywhere in the reply; anything invalid → null. */
export function parsePageCard(reply: string): PageCard | null {
  const m = reply.match(/\{[\s\S]*\}/);
  if (!m) return null;
  let obj: unknown;
  try {
    obj = JSON.parse(m[0]);
  } catch {
    return null;
  }
  if (typeof obj !== 'object' || obj === null) return null;
  const o = obj as Record<string, unknown>;
  if (typeof o['pageType'] !== 'string' || typeof o['purpose'] !== 'string') return null;
  const value = o['automationValue'];
  const actions = Array.isArray(o['keyActions'])
    ? (o['keyActions'] as unknown[]).filter((a): a is string => typeof a === 'string').slice(0, 5)
    : [];
  return {
    pageType: o['pageType'].slice(0, 60),
    purpose: o['purpose'].slice(0, 300),
    keyActions: actions,
    automationValue: value === 'high' || value === 'medium' || value === 'low' ? value : 'low',
  };
}

/**
 * Ask the model for a page card. All failure modes (leak-check refusal, transport error,
 * unparseable reply) return null — the crawl continues deterministically without a card.
 */
export async function fetchPageCard(
  safe: SiteProfile,
  secrets: Record<string, string>,
  transport: ModelTransport,
): Promise<PageCard | null> {
  try {
    const prompt = buildPageCardPrompt(profileDigest(safe));
    // Identity rehydrate/redact on purpose: the reply is persisted with the card, so any
    // token the model echoes must STAY a token. The leak check on `map` still applies.
    const reply = await callModelTree(
      { redactedText: prompt, map: secrets, rehydrate: (t) => t, redact: (t) => t },
      transport,
    );
    return parsePageCard(reply);
  } catch {
    return null;
  }
}

/** One card per URL-template family — a roster of 200 students is ONE card, not 200 calls. */
export class PageCardCache {
  private byTemplate = new Map<string, PageCard>();

  has(url: string): boolean {
    return this.byTemplate.has(urlTemplate(url));
  }

  get(url: string): PageCard | undefined {
    return this.byTemplate.get(urlTemplate(url));
  }

  set(url: string, card: PageCard): void {
    this.byTemplate.set(urlTemplate(url), card);
  }
}

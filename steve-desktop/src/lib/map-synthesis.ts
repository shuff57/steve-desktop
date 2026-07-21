import type { SiteMap } from './site-map';
import type { PageCard } from './page-card';
import { callModelTree, type ModelTransport } from './model-gate';

// Post-crawl synthesis: one model call over the finished (already-redacted) map produces
// named sections, semantic trim suggestions, and workflow ideas. Same injection-proofing
// as the frontier planner: the model refers to pages by INDEX, never by URL.

export interface MapSynthesis {
  sections: { name: string; urls: string[] }[];
  trim: { url: string; reason: string }[];
  workflows: string[];
}

export function buildSynthesisPrompt(map: SiteMap, cards: Record<string, PageCard | undefined>): string {
  const list = map.pages
    .map((p, i) => {
      let path = p.url;
      try {
        const u = new URL(p.url);
        path = u.pathname + u.search;
      } catch {
        /* keep as-is */
      }
      const card = cards[p.url];
      const kind = card ? ` [${card.pageType}, value:${card.automationValue}]` : '';
      return `${i}: ${p.pageName}${kind} — ${path} — ${p.counts.buttons}btn/${p.counts.inputs}in/${p.links.length}lnk`;
    })
    .join('\n');
  return (
    `A crawl of ${map.domain} mapped these pages (⟦D…⟧ = redaction tokens):\n` +
    list +
    '\n\nOrganize the site for a teacher-automation agent. Reply with ONLY JSON, page ' +
    'references are the numeric indices above:\n' +
    '{"sections": [{"name": "<area, e.g. Gradebook>", "pages": [<indices>]}],\n' +
    ' "trim": [{"i": <index>, "reason": "<why this page adds nothing for automation>"}],\n' +
    ' "workflows": ["<up to 5 automation workflows this site map supports>"]}'
  );
}

/** Indices → urls; anything out of range is dropped. Unparseable → null. */
export function parseSynthesis(reply: string, map: SiteMap): MapSynthesis | null {
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
  const valid = (i: unknown): i is number => typeof i === 'number' && Number.isInteger(i) && i >= 0 && i < map.pages.length;

  const sections: MapSynthesis['sections'] = [];
  for (const s of Array.isArray(o['sections']) ? (o['sections'] as unknown[]) : []) {
    const e = s as Record<string, unknown>;
    if (typeof e?.['name'] !== 'string') continue;
    const urls = (Array.isArray(e['pages']) ? (e['pages'] as unknown[]) : [])
      .filter(valid)
      .map((i) => map.pages[i].url);
    if (urls.length) sections.push({ name: e['name'].slice(0, 60), urls });
  }

  const trim: MapSynthesis['trim'] = [];
  for (const t of Array.isArray(o['trim']) ? (o['trim'] as unknown[]) : []) {
    const e = t as Record<string, unknown>;
    if (!valid(e?.['i'])) continue;
    trim.push({
      url: map.pages[e['i'] as number].url,
      reason: typeof e['reason'] === 'string' ? e['reason'].slice(0, 120) : 'model trim',
    });
  }

  const workflows = (Array.isArray(o['workflows']) ? (o['workflows'] as unknown[]) : [])
    .filter((w): w is string => typeof w === 'string')
    .slice(0, 5);

  if (!sections.length && !trim.length && !workflows.length) return null;
  return { sections, trim, workflows };
}

/** Gated call; every failure returns null so the deterministic trim path stands alone. */
export async function fetchMapSynthesis(
  map: SiteMap,
  cards: Record<string, PageCard | undefined>,
  secrets: Record<string, string>,
  transport: ModelTransport,
): Promise<MapSynthesis | null> {
  try {
    const reply = await callModelTree(
      { redactedText: buildSynthesisPrompt(map, cards), map: secrets, rehydrate: (t) => t, redact: (t) => t },
      transport,
    );
    return parseSynthesis(reply, map);
  } catch {
    return null;
  }
}

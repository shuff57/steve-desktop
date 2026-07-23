/**
 * Build a Rubric from a MyOpenMath question page.
 *
 * Steve's questions already carry their own grading criteria: the authoring template
 * (`.agents/authoring/references/php-patterns.md` in O.G.R.E) emits a `.rubric-container`
 * holding a `<details>` per rubric, in two flavours —
 *
 *   $rubricbutton        student-facing checklist: `<li>` requirements, no answers
 *   $rubricanswerbutton  instructor version: same requirements plus `span.ideal-ans`
 *                        targets and a `div.full-response-box` model narrative
 *
 * The instructor block is the one worth importing, so `pickBlock` prefers it. Category
 * headings carry their own weight — "IQR & Upper Fence (4 pts)" — and their sum is the
 * question's real point value, which is where `maxScore` comes from.
 *
 * `<details>` being collapsed does not matter: this reads textContent, never layout.
 *
 * Read-only, like load-students.ts — one evaluated expression, no clicks, no writes.
 * MyOpenMath keeps audit logs and an import must not appear in them as activity.
 */
import type { Rubric, RubricChecklistItem } from './grading';

/** A requirement line, or a category heading that groups the lines after it. */
export interface RubricNode {
  heading: boolean;
  text: string;
  /** Instructor-only `span.ideal-ans` target. Empty on the student checklist. */
  target: string;
}

export interface RubricBlock {
  summary: string;
  modelText: string;
  hasTargets: boolean;
  nodes: RubricNode[];
}

export interface ExtractedRubric {
  url: string;
  title: string;
  prompt: string;
  blocks: RubricBlock[];
}

/**
 * Evaluated in the page. A string, not a function reference — it crosses the CDP
 * boundary, so it must close over nothing in this module.
 */
export const PAGE_RUBRIC_EXTRACT_JS = `(() => {
  const txt = (el) => (el ? (el.textContent || '') : '').replace(/\\s+/g, ' ').trim();

  const details = Array.from(document.querySelectorAll('.rubric-container details'));
  // Fall back to any <details> — a question authored before the template still has the
  // checklist, just without the wrapper class.
  const targets = details.length ? details : Array.from(document.querySelectorAll('details'));

  const blocks = targets.map((d) => {
    const content = d.querySelector('.rubric-content') || d;
    const nodes = [];
    for (const el of Array.from(content.querySelectorAll('li, h1, h2, h3, h4, h5, h6, b, strong'))) {
      const isLi = el.tagName === 'LI';
      // Bold inside a requirement is emphasis, not a category heading.
      if (!isLi && el.closest('li')) continue;
      // Outer <li> of a nested list: its children carry the actual requirements.
      if (isLi && el.querySelector('li')) continue;
      const target = isLi ? txt(el.querySelector('.ideal-ans')) : '';
      let text = txt(el);
      if (target) text = text.replace(target, '').trim();
      if (!text) continue;
      nodes.push({ heading: !isLi, text: text, target: target });
    }
    return {
      summary: txt(d.querySelector('summary')),
      modelText: txt(d.querySelector('.full-response-box')),
      hasTargets: !!d.querySelector('.ideal-ans'),
      nodes: nodes,
    };
  });

  // The question text is whatever paragraphs sit outside the rubric containers.
  const host = (targets[0] && targets[0].closest('.rubric-container')) || null;
  const scope = (host && host.parentElement) || document.body;
  const prompt = Array.from(scope.querySelectorAll('p'))
    .filter((p) => !p.closest('.rubric-container'))
    .map((p) => txt(p))
    .filter(Boolean)
    .join('\\n\\n');

  return { url: location.href, title: document.title || '', prompt: prompt, blocks: blocks };
})()`;

/** "IQR & Upper Fence (4 pts)" → the 4. */
const POINTS_RE = /\((\d+(?:\.\d+)?)\s*(?:pts?|points?)\)/i;

/**
 * The block worth importing. The instructor version wins on targets or a model
 * response; otherwise the one with the most content, since an unrelated `<details>`
 * on the page (a hint, a formula reference) is usually the thinner one.
 */
export function pickBlock(blocks: RubricBlock[]): RubricBlock | null {
  const withNodes = blocks.filter((b) => b.nodes.some((n) => !n.heading));
  if (withNodes.length === 0) return null;
  const rich = withNodes.filter((b) => b.hasTargets || b.modelText);
  const pool = rich.length ? rich : withNodes;
  return pool.reduce((best, b) => (b.nodes.length > best.nodes.length ? b : best));
}

/** Group requirement lines under the heading that precedes them. */
export function toChecklist(nodes: RubricNode[], fallbackCategory: string): RubricChecklistItem[] {
  const out: RubricChecklistItem[] = [];
  let current: RubricChecklistItem | null = null;

  for (const n of nodes) {
    if (n.heading) {
      const m = POINTS_RE.exec(n.text);
      current = {
        category: n.text.replace(POINTS_RE, '').trim() || fallbackCategory,
        items: [],
        ...(m ? { points: parseFloat(m[1]!) } : {}),
      };
      out.push(current);
      continue;
    }
    if (!current) {
      current = { category: fallbackCategory, items: [] };
      out.push(current);
    }
    // The target is instructor gold — it belongs in the criteria the model reads.
    current.items!.push(n.target ? `${n.text} (Target: ${n.target})` : n.text);
  }

  // A heading with nothing under it is a layout artefact, not a criterion.
  return out.filter((c) => (c.items?.length ?? 0) > 0);
}

export interface ImportedRubric {
  /** Stable across re-imports of the same question, so a re-import updates in place. */
  sourceId: string;
  name: string;
  rubric: Rubric;
}

/**
 * Stable identity for a question page. `qid` when MyOpenMath exposes one, else the
 * path — deliberately not the full href, so a session token or a `cid` reshuffle in
 * the query string doesn't fork a duplicate rubric.
 */
export function sourceIdFor(url: string): string {
  try {
    const u = new URL(url);
    const qid = u.searchParams.get('qid') ?? u.searchParams.get('id');
    return qid ? `${u.host}${u.pathname}?qid=${qid}` : `${u.host}${u.pathname}`;
  } catch {
    return url;
  }
}

export function parseExtractedRubric(raw: unknown): ImportedRubric {
  const data = raw as ExtractedRubric | undefined;
  if (!data || !Array.isArray(data.blocks)) {
    throw new Error('Extraction returned nothing — is a MyOpenMath question open?');
  }

  const block = pickBlock(data.blocks);
  if (!block) {
    throw new Error(
      'No grading checklist found on this page. The rubric lives in a "Click to View Grading Checklist" block; a question authored without one has nothing to import.',
    );
  }

  const checklistItems = toChecklist(block.nodes, block.summary || 'Requirements');
  const points = checklistItems.reduce((sum, c) => sum + (c.points ?? 0), 0);
  const modelText = data.blocks.find((b) => b.modelText)?.modelText ?? '';

  const prompt = (data.prompt ?? '').trim();
  // First sentence of the prompt reads better in a list than a truncated document title.
  const name = (prompt.split(/(?<=[.?!])\s/)[0] ?? '').slice(0, 80).trim() || data.title || 'Imported rubric';

  return {
    sourceId: sourceIdFor(data.url ?? ''),
    name,
    rubric: {
      // Only trust a summed point value when every category declared one; a partial sum
      // would silently grade the question out of the wrong total.
      maxScore: points > 0 && checklistItems.every((c) => c.points != null) ? points : 10,
      essayPrompt: prompt,
      checklistItems,
      ...(modelText ? { modelText } : {}),
      ...(checklistItems.some((c) => c.points != null) ? { weightMode: 'category' } : {}),
    },
  };
}

/**
 * Read the rubric off whatever question page the browser is on. `evaluate` is the CDP
 * client in the app and a stub in tests.
 */
export async function importRubricFromPage(
  evaluate: (expression: string) => Promise<unknown>,
): Promise<ImportedRubric> {
  return parseExtractedRubric(await evaluate(PAGE_RUBRIC_EXTRACT_JS));
}

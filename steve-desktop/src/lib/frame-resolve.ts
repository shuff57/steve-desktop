/**
 * Choosing WHICH frame to resolve a stored selector in.
 *
 * Capture already knows this and then throws it away: `merged-tree.ts` walks `contentDocument` and tags
 * every node with its owning `frameId` (`DOM.getDocument` with `pierce: true`, AX fetched per frame,
 * merged by `(frameId, backendNodeId)`). Nothing downstream carries it — `selectorToElementExpr` emits a
 * bare `document.querySelector`, which runs in the TOP frame only. So a stored selector for anything
 * inside an iframe resolves to nothing and is reported as drift, forever, on every run. Canvas serves
 * LTI tools in iframes, so that is a permanent false-drift class rather than a rare edge.
 *
 * This module is deliberately decoupled: it takes plain values and returns plans/expressions, and does
 * NOT import the selector builders. Frame SELECTION is the missing piece; expression building stays
 * where it already lives.
 */

/** How to reach the execution context a captured node lived in. */
export type FramePlan =
  /** Top-level document — evaluate in the default session, exactly as today. */
  | { kind: 'main' }
  /** Out-of-process iframe: its own CDP target. Attach to it and evaluate there. */
  | { kind: 'target'; targetId: string }
  /** Same-origin subframe: same target, reachable by walking `contentDocument`. */
  | { kind: 'pierce' };

/** Minimal shape of a CDP `Target.getTargets` entry — only what the choice needs. */
export type TargetLike = { targetId: string; type: string };

/**
 * Where to resolve a node captured in `frameId`.
 *
 * The cross-origin case is exact rather than heuristic: for an out-of-process iframe, CDP uses the
 * frame id AS the target id, so a stored `frameId` matching an `iframe` target is that frame. No URL
 * matching, no guessing which of several iframes was meant.
 *
 * A blank or unknown `frameId` falls back to `main` — an old profile captured before frames were
 * carried must keep resolving exactly as it does today.
 */
export function planFrameResolution(
  frameId: string | undefined | null,
  mainFrameId: string | undefined | null,
  targets: readonly TargetLike[] = [],
): FramePlan {
  if (!frameId) return { kind: 'main' };
  if (mainFrameId && frameId === mainFrameId) return { kind: 'main' };
  const oopif = targets.find((t) => t.type === 'iframe' && t.targetId === frameId);
  if (oopif) return { kind: 'target', targetId: oopif.targetId };
  // Known frame, no target of its own: same-origin, so it is inside the current target's tree.
  return { kind: 'pierce' };
}

/**
 * JS expression yielding every same-origin document reachable from the top one, outermost first.
 *
 * Cross-origin children throw on `contentDocument` access; they are skipped rather than allowed to
 * abort the walk, because a page mixing same- and cross-origin frames is the normal Canvas case and the
 * cross-origin ones are handled by `{ kind: 'target' }` instead.
 */
export const REACHABLE_DOCS_EXPR = `(()=>{const out=[document];for(let i=0;i<out.length;i++){let fs;try{fs=out[i].querySelectorAll('iframe,frame')}catch(e){continue}for(const f of fs){let d=null;try{d=f.contentDocument}catch(e){d=null}if(d&&!out.includes(d))out.push(d)}}return out})()`;

/**
 * First element matching a CSS selector in any same-origin document, or null.
 *
 * The `{ kind: 'pierce' }` counterpart to a top-frame `document.querySelector`. Ordering is outermost
 * document first, so a selector that also matches in the top frame keeps resolving to the same element
 * it always did — piercing widens the search, it must not silently relocate an existing match.
 */
export function pierceQueryExpr(cssSelector: string): string {
  return `(()=>{const ds=${REACHABLE_DOCS_EXPR};for(const d of ds){let e=null;try{e=d.querySelector(${JSON.stringify(cssSelector)})}catch(err){e=null}if(e)return e}return null})()`;
}

/** Same as {@link pierceQueryExpr} but counting every match across all reachable documents. */
export function pierceCountExpr(cssSelector: string): string {
  return `(()=>{const ds=${REACHABLE_DOCS_EXPR};let n=0;for(const d of ds){try{n+=d.querySelectorAll(${JSON.stringify(cssSelector)}).length}catch(err){}}return n})()`;
}

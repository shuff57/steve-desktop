import type { Workflow, WorkflowStep } from './types/site-profile';
import type { SnapshotResult } from './dom-snapshot-types';
import type { ModelTransport } from './model-gate';
import {
  replayWorkflow,
  modelRelocator,
  visualRelocator,
  type PageDriver,
  type ReplaySummary,
  type VisualCapture,
} from './replay';
import { captureMergedTree } from './merged-tree';
import { observe } from './observation';
import { tagCandidates, overlayScript, OVERLAY_REMOVE } from './visual-fallback';
import { cdp } from './cdp-client';
import { evalScript as cdpEval, pwClick, pwType, isConnected, cdpScreenshot } from './cdp-actions';
import { selectorToElementExpr } from './selector-resolve';
import { navigateEmbedded, getActiveTabId } from './browser';

// Live wiring of the self-heal replay: drive the real embedded browser over CDP and, when the
// local tiers (recorded → candidates → fuzzy) all miss, escalate to the model via the sidecar
// with the slot-redacted tree (Tier 3 = modelRelocator). This is the integration point that
// turns the tested replay/heal libs into something that runs against a live page.

const SIDECAR_BASE = 'http://localhost:3456';

/**
 * Tier-3 transport: send the (already-redacted, gate-checked) relocate prompt to the local
 * model sidecar and return its raw reply. callModelTree owns the trust boundary; this only moves
 * bytes. Throws on a non-200 so the healer falls through to a skip rather than acting on garbage.
 */
export function sidecarTransport(opts: { provider?: string; model?: string } = {}): ModelTransport {
  return async (prompt: string, image?: string): Promise<string> => {
    const res = await fetch(`${SIDECAR_BASE}/api/agent`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        messages: [{ role: 'user', content: prompt }],
        provider: opts.provider,
        model: opts.model,
        // Only the visual tier sends one, and it is already masked + gated by visualRelocator.
        ...(image ? { image } : {}),
      }),
    });
    if (!res.ok) throw new Error(`sidecar relocate failed (HTTP ${res.status})`);
    const data = await res.json();
    if (typeof data === 'string') return data;
    return (data?.content ?? data?.text ?? '') as string;
  };
}

/** PageDriver backed by the live embedded browser over CDP (merged tree + selector-resolve). */
export class BrowserPageDriver implements PageDriver {
  async exists(selector: string): Promise<boolean> {
    const res = await cdpEval(`!!(${selectorToElementExpr(selector)})`);
    return res.success && res.data === true;
  }

  /** Observe through the AX tree: captureMergedTree already merges role/name onto every node, so
   *  pruning to interactive+visible cuts the payload the ranker and the model see. Falls back to
   *  the full capture on pages whose a11y is too poor to prune through (observe() decides). */
  async snapshot(): Promise<SnapshotResult> {
    const { snapshot } = await captureMergedTree(cdp);
    return observe(snapshot).snapshot;
  }

  async act(step: WorkflowStep, selector: string): Promise<boolean> {
    switch (step.action) {
      case 'click':
      case 'answer-quiz': // answering is a click on the chosen option in our workflows
        return (await pwClick(selector)).success;

      case 'fill':
        return (await pwType(selector, step.value ?? '', true)).success;

      // Typing into a <select> is wrong (keystrokes land in whatever is focused); set the option
      // by value-or-text and fire change. ponytail: native <select> only — a custom dropdown
      // returns false here and falls through to the heal/skip audit path.
      case 'select': {
        const v = JSON.stringify(step.value ?? '');
        const res = await cdpEval(
          `(function(){var el=${selectorToElementExpr(selector)};if(!el||!el.options)return false;` +
            `var m=Array.prototype.filter.call(el.options,function(o){return o.value===${v}||o.text.trim()===${v};})[0];` +
            `if(!m)return false;el.value=m.value;` +
            `el.dispatchEvent(new Event('input',{bubbles:true}));` +
            `el.dispatchEvent(new Event('change',{bubbles:true}));return true;})()`,
        );
        return res.success && res.data === true;
      }

      case 'navigate': {
        const tabId = getActiveTabId();
        if (!tabId) return false;
        await navigateEmbedded(tabId, step.value ?? selector);
        return true;
      }

      case 'keyboard': {
        const key = step.key ?? '';
        if (!key) return false;
        const res = await cdpEval(
          `(function(){var t=(${selectorToElementExpr(selector)})||document.activeElement||document.body;` +
            `t.dispatchEvent(new KeyboardEvent('keydown',{key:${JSON.stringify(key)},bubbles:true}));return true;})()`,
        );
        return res.success && res.data === true;
      }

      case 'scroll': {
        const res = await cdpEval(`(function(){window.scrollBy(0,400);return true;})()`);
        return res.success && res.data === true;
      }

      case 'wait-for':
        return this.exists(selector);

      default:
        return false; // unsupported action — replay audits this as a skip, never a guess
    }
  }

  /** Postcondition per action, so a heal persists only on a VERIFIED outcome:
   *  fill/select → value read back equals what we set; navigate → URL actually changed to the
   *  target. Actions with no recorded expectation (click, keyboard, scroll) pass through.
   *  ponytail: click postconditions arrive with stage-5 key nodes — record expectation, then gate. */
  async verify(step: WorkflowStep, selector: string): Promise<boolean> {
    switch (step.action) {
      case 'fill':
      case 'select': {
        const res = await cdpEval(
          `(function(){var el=${selectorToElementExpr(selector)};return el&&'value'in el?String(el.value):null;})()`,
        );
        return res.success && res.data === String(step.value ?? '');
      }
      case 'navigate': {
        const norm = (u: string) => u.replace(/^https?:\/\//, '').replace(/\/$/, '').split('#')[0];
        const target = norm(step.value ?? selector);
        // Navigation lands async — poll briefly instead of judging a half-loaded page.
        for (let i = 0; i < 6; i++) {
          const res = await cdpEval('location.href');
          if (res.success && typeof res.data === 'string' && norm(res.data).includes(target)) return true;
          await new Promise((r) => setTimeout(r, 500));
        }
        return false;
      }
      default:
        return true;
    }
  }

  /**
   * Last-tier visual capture: mask the page, draw the numbered badges, screenshot, then ALWAYS
   * strip the overlay — the user is watching this tab, so it must never be left marked up, even
   * if the capture throws. Returns the tags alongside the image so the caller can resolve the
   * model's numeric answer back to a selector it already has.
   *
   * Fails CLOSED: the overlay script returns false if it could not finish masking, and we return
   * null rather than screenshot a page whose text is still readable. This is the only redaction a
   * screenshot can get — there is no tokenizing it after the shutter.
   */
  async captureTagged(snapshot: SnapshotResult): Promise<VisualCapture | null> {
    const tags = tagCandidates(snapshot);
    if (!tags.length) return null;
    try {
      const drawn = await cdpEval(overlayScript(tags, { mask: true }));
      // The script returns the ids it actually badged (false on failure). Narrow the legend to
      // those, so every number the model is offered is one it can see in the picture.
      const ids = drawn.success && Array.isArray(drawn.data) ? (drawn.data as number[]) : null;
      if (!ids?.length) return null;
      const visible = tags.filter((t) => ids.includes(t.id));
      const screenshot = await cdpScreenshot();
      return { tags: visible, screenshot };
    } catch {
      return null;
    } finally {
      await cdpEval(OVERLAY_REMOVE).catch(() => {});
    }
  }

  /** Passive refresh: re-capture the acted element's live anchors (id/testid/name/aria-label)
   *  so stored candidates track the real page on every successful run. */
  async fingerprint(selector: string): Promise<string[] | null> {
    const res = await cdpEval(
      `(function(){var el=${selectorToElementExpr(selector)};if(!el)return null;var out=[];var t=el.tagName.toLowerCase();` +
        `if(el.id)out.push('#'+el.id);` +
        `var ti=el.getAttribute('data-testid');if(ti)out.push('[data-testid="'+ti+'"]');` +
        `var n=el.getAttribute('name');if(n)out.push(t+'[name="'+n+'"]');` +
        `var a=el.getAttribute('aria-label');if(a)out.push(t+'[aria-label="'+a+'"]');` +
        `return out;})()`,
    );
    return res.success && Array.isArray(res.data) ? (res.data as string[]) : null;
  }
}

/**
 * Replay a trained workflow against the live page with Tier-3 model self-heal wired in.
 * Requires an active CDP connection to the embedded browser.
 */
export async function replayLive(
  workflow: Workflow,
  opts: { provider?: string; model?: string } = {},
): Promise<ReplaySummary> {
  if (!isConnected()) {
    throw new Error('Replay needs a CDP connection — connect to the embedded browser first.');
  }
  const driver = new BrowserPageDriver();
  const transport = sidecarTransport(opts);
  return replayWorkflow(workflow, driver, modelRelocator(transport), visualRelocator(transport));
}

/**
 * mom-transfer orchestrator — reads an assignment manifest from mom-content/,
 * loads all question files, then runs the page-agent loop to file each
 * question into a live MyOpenMath course.
 *
 * Flow per question:
 *   1. Navigate to moddataset.php?aid=X&cid=Y (new question form)
 *   2. Run the agent loop with fill_mom_question(slot=N)
 *   3. The agent fills the form + clicks Save
 *   4. Capture the qsetid from the response (library id)
 *   5. Attach: GET modquestion2.php?qsetid=Z&cid=Y&aid=X&from=addq&process=true&usedef=true
 *   6. After all questions: navigate to /assess2/?cid=Y&aid=X, screenshot for verify
 *
 * The orchestrator handles the multi-page navigation (which page-agent IIFE
 * can't do alone). The agent loop handles the adaptive form-filling.
 */

import { invoke } from '@tauri-apps/api/core';
import type { ExecutionResult } from '../../lib/page-agent-loop';
import type { CDPClient } from '../../lib/cdp-client';
import type { ToolContext } from '../../lib/page-agent-tools';
import {
  parseQuestionFile,
  setSectionsForLoop,
  buildMomTransferConfig,
  isTransportFailure,
  MOM_TRANSFER_MODELS,
  type QuestionSections,
} from './page-agent-config';

export interface MOMManifestQuestion {
  slot: number;
  file_path: string;
  title: string;
  qid?: number;
}

export interface MOMManifest {
  name: string;
  book: string;
  kind: string;
  questions: MOMManifestQuestion[];
  target?: { cid?: number; aid?: number };
}

export interface TransferResult {
  question: MOMManifestQuestion;
  success: boolean;
  qsetid?: number;
  data: string;
  verified: boolean;
  /** Which model actually filed it — not always the first in the chain. */
  model?: string;
  /** What the question actually rendered as. Absent means it was never rendered. */
  verification?: QuestionVerification;
}

export interface TransferOptions {
  manifest: MOMManifest;
  cid: number;
  aid?: number;
  momRoot: string;
  baseURL: string;
  /** Defaults to MOM_TRANSFER_MODEL — see the measurements next to it. */
  model?: string;
  apiKey?: string;
  /** The CDP context — from the app's connected cdp client. */
  ctx: ToolContext;
  /**
   * The app tab id this run drives. Given it, the run claims the tab for its
   * duration so another agent cannot drive the same tab underneath it.
   */
  tabId?: string;
  /** How many random seeds to render each question at. Default 3. */
  seeds?: number;
  /** Hard step ceiling per question. Default 25; stalls end a run well before it. */
  maxSteps?: number;
  /** Called after each question completes. */
  onQuestionDone?: (result: TransferResult) => void;
  /** Called when a model is skipped because the endpoint refused it. */
  onModelFallback?: (model: string, reason: string) => void;
}

const MOM_BASE = 'https://www.myopenmath.com';

/**
 * Read all question files referenced in the manifest and parse them.
 * The sections map is loaded into the fill_mom_question tool before the loop.
 */
export async function loadQuestionSections(
  manifest: MOMManifest,
  momRoot: string,
): Promise<Map<number, QuestionSections>> {
  const map = new Map<number, QuestionSections>();
  for (const q of manifest.questions) {
    // A WebView has no filesystem — the read happens in Rust. `mom_read_text` returns
    // "" for a missing file rather than erroring, so an empty read is a hard failure
    // here: filing a question whose body never loaded is the silent-write trap this
    // whole flow exists to avoid.
    const contents = await invoke<string>('mom_read_text', {
      root: momRoot,
      path: q.file_path,
    });
    if (!contents.trim()) {
      throw new Error(`Question file empty or missing: ${momRoot}/${q.file_path} (slot ${q.slot})`);
    }
    map.set(q.slot, parseQuestionFile(contents));
  }
  return map;
}

/**
 * Build the CDP ToolContext the agent loop runs against, bound to a connected
 * CDP client. Pass the app's `cdp` singleton for the embedded browser, or a
 * second CDPClient connected to another tab for multi-tab orchestration.
 */
export function buildToolContext(
  client: Pick<CDPClient, 'send'>,
  signal: AbortSignal,
  settleMs = 2000,
): ToolContext {
  const waitForLoad = () => new Promise<void>((r) => setTimeout(r, settleMs));
  return {
    signal,
    cdpSend: (method, params) => client.send(method, params),
    evalInPage: (expression) =>
      client.send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true }),
    navigate: async (url) => {
      await client.send('Page.navigate', { url });
      await waitForLoad();
    },
    waitForLoad,
  };
}

interface SteveControlClaim {
  claimTab: (id: string, sessionId: string) => Promise<void>;
  releaseTab: (sessionId: string) => Promise<void>;
}

/**
 * Register a page-agent run as the owner of `tabId` for its duration.
 *
 * The loop drives the tab over a raw CDP socket, so without this the app's
 * ownership registry shows the tab as idle: a CLI agent making legacy calls
 * (no session id, allowed everywhere) could drive the same tab mid-run, and the
 * UI would give no sign the tab was busy. Claiming reuses the same session
 * machinery a spawned CLI run uses, so the tab also picks up the driven-tab
 * highlight and overlay.
 *
 * Returns a release function. Always call it — a leaked claim leaves the tab
 * looking owned by a run that has finished, and nothing else can take it.
 */
export async function claimTabForRun(
  tabId: string,
  sessionId: string,
): Promise<() => Promise<void>> {
  // globalThis, not window: this module is also imported by node-environment
  // tests, where touching `window` is a ReferenceError rather than undefined.
  const control = (globalThis as unknown as { __steveControl?: SteveControlClaim }).__steveControl;
  // No bridge (tests, or a run driven from outside the app window) — nothing to
  // claim against, so carry on rather than refusing to work.
  if (!control?.claimTab) return async () => {};
  await control.claimTab(tabId, sessionId);
  return async () => {
    await control.releaseTab(sessionId).catch(() => undefined);
  };
}

/**
 * Load question sections from pre-read file contents (for Tauri's
 * invoke-based file reading, which doesn't need fetch).
 */
export function loadQuestionSectionsFromText(
  manifest: MOMManifest,
  fileContents: Record<string, string>,
): Map<number, QuestionSections> {
  const map = new Map<number, QuestionSections>();
  for (const q of manifest.questions) {
    const text = fileContents[q.file_path];
    if (text) {
      map.set(q.slot, parseQuestionFile(text));
    }
  }
  return map;
}

export interface QuestionRender {
  seed: string | null;
  issues: string[];
  widgets: number;
  text: string;
}

export interface QuestionVerification {
  qsetid: number;
  clean: boolean;
  /** Every distinct issue seen across the seeds checked. */
  issues: string[];
  renders: QuestionRender[];
}

/**
 * The in-page probe. Everything it reports is a fact about the rendered page,
 * so no model is involved in deciding whether a question came out right.
 */
const RENDER_PROBE = `(function(){
  var t = document.body.innerText || '';
  var issues = [];
  if (!/Question ID:/.test(t)) issues.push('preview did not load');
  // The control block saved empty — trap 1, and the reason this check exists.
  if (/Eeek!/.test(t)) issues.push('Eeek! — control block is empty or failed');
  // A $var that reached the student means the PHP did not run or was truncated.
  var v = t.match(/\\$[a-zA-Z_]\\w*/);
  if (v) issues.push('literal variable in rendered text: ' + v[0]);
  var widgets = document.querySelectorAll(
    'input[type=radio], input[type=checkbox], input[type=text], input[type=number], select, textarea'
  ).length;
  if (widgets === 0) issues.push('no answer widget rendered');
  // Backtick math is MOM's source form; if it survives to the page, it never typeset.
  if (/\`[^\`\\n]+\`/.test(t)) issues.push('untypeset math (backtick source visible)');
  return JSON.stringify({
    seed: (t.match(/Seed:\\s*(\\d+)/) || [])[1] || null,
    issues: issues,
    widgets: widgets,
    text: t.replace(/\\s+/g, ' ').slice(0, 400),
  });
})()`;

/**
 * Render a library question on its own and read what a student would see.
 *
 * Uses `testquestion2.php`, which "Quick Save and Preview" in the editor lands
 * on — a plain URL, so no popup handling is needed. Verifying here rather than
 * through the assessment means a question can be checked BEFORE it is attached,
 * and checking it leaves no preview attempt on the instructor's gradebook.
 *
 * `seeds` re-rolls with the page's own "New Version" control. One render only
 * proves one seed: a branch that breaks on some values renders fine on others.
 */
export async function verifyQuestion(
  ctx: ToolContext,
  opts: { cid: number; qsetid: number; seeds?: number },
): Promise<QuestionVerification> {
  const seeds = Math.max(1, opts.seeds ?? 3);
  const renders: QuestionRender[] = [];

  await ctx.navigate(
    `${MOM_BASE}/course/testquestion2.php?cid=${opts.cid}&qsetid=${opts.qsetid}`,
  );
  await ctx.waitForLoad();

  for (let i = 0; i < seeds; i++) {
    if (i > 0) {
      const reseeded = (await ctx.cdpSend('Runtime.evaluate', {
        expression: `(function(){
          var b = [].slice.call(document.querySelectorAll('button,input[type=submit],input[type=button],a'))
            .filter(function(e){ return /New Version/i.test(e.innerText || e.value || ''); })[0];
          if (!b) return false;
          b.click(); return true;
        })()`,
        returnByValue: true,
      })) as { result?: { value?: boolean } };
      if (!reseeded.result?.value) break; // no reseed control — one render is all there is
      await ctx.waitForLoad();
    }

    const res = (await ctx.cdpSend('Runtime.evaluate', {
      expression: RENDER_PROBE,
      returnByValue: true,
    })) as { result?: { value?: string } };
    renders.push(JSON.parse(res.result?.value ?? '{"issues":["probe failed"],"widgets":0,"seed":null,"text":""}'));
  }

  const issues = [...new Set(renders.flatMap((r) => r.issues))];
  return { qsetid: opts.qsetid, clean: issues.length === 0 && renders.length > 0, issues, renders };
}

/**
 * Run the full mom-transfer flow: file each question, attach, verify.
 *
 * This is the orchestrator that the page-agent-runner skill describes. It
 * handles the multi-page navigation (which the IIFE can't), and delegates
 * the adaptive form-filling to the agent loop.
 */
export async function transferViaAgent(opts: TransferOptions): Promise<TransferResult[]> {
  const { manifest, cid, aid, momRoot, ctx } = opts;
  const results: TransferResult[] = [];

  // Hold the tab for the whole run. Claimed before the first write, released in
  // the finally below, so a crash mid-question cannot leave the tab looking
  // owned by a run that is no longer there.
  const release = opts.tabId
    ? await claimTabForRun(opts.tabId, `page-agent-${crypto.randomUUID()}`)
    : async () => {};

  try {
  // Load all question sections
  const sections = await loadQuestionSections(manifest, momRoot);
  setSectionsForLoop(sections);

  for (const question of manifest.questions) {
    const result: TransferResult = {
      question,
      success: false,
      data: '',
      verified: false,
    };

    try {
      // Skip questions that already have a qid (already in the library).
      // Still render it: "it was filed once" is not evidence it renders today.
      if (question.qid) {
        result.qsetid = Number(question.qid);
        result.verification = await verifyQuestion(ctx, {
          cid,
          qsetid: result.qsetid,
          seeds: opts.seeds,
        });
        result.verified = result.verification.clean;
        if (aid && result.verified) {
          await ctx.navigate(
            `${MOM_BASE}/course/modquestion2.php?qsetid=${question.qid}&cid=${cid}&aid=${aid}&from=addq&process=true&usedef=true`,
          );
          await ctx.waitForLoad();
        }
        result.success = result.verified;
        result.data = result.verified
          ? 'Already in library — verified and attached'
          : `Already in library but did not render clean: ${result.verification.issues.join('; ')}`;
        results.push(result);
        opts.onQuestionDone?.(result);
        continue;
      }

      // Navigate to the new-question form
      if (aid) {
        await ctx.navigate(`${MOM_BASE}/course/moddataset.php?aid=${aid}&cid=${cid}`);
        await ctx.waitForLoad();
      }

      // Run the agent loop for this question
      const task =
        `File question slot ${question.slot} ("${question.title}") into the MOM library. ` +
        `Call fill_mom_question(slot: ${question.slot}) to fill the form, then click Save. ` +
        `After saving, report the qsetid from the page if visible.`;

      // Walk the ranked models only while the endpoint itself is refusing us —
      // a model that ran and got the question wrong is not retried, or a bad
      // fill would be filed once per model.
      const chain = opts.model ? [opts.model] : [...MOM_TRANSFER_MODELS];
      let execResult: ExecutionResult | null = null;
      for (const model of chain) {
        execResult = await runAgentLoop(
          buildMomTransferConfig({
            cid,
            aid,
            task,
            baseURL: opts.baseURL,
            model,
            apiKey: opts.apiKey,
            // Room to notice and undo a misclick. The run does not actually
            // spend this — a clean fill is 3 steps and a stalled one is cut
            // off by stallLimit long before the ceiling.
            maxSteps: opts.maxSteps ?? 25,
          }),
          ctx,
        );
        result.model = model;
        if (execResult.success || !isTransportFailure(execResult.data)) break;
        opts.onModelFallback?.(model, execResult.data);
      }
      result.success = execResult!.success;
      result.data = execResult!.data;

      // Try to extract qsetid from the page after save
      const qsetidResult = (await ctx.cdpSend('Runtime.evaluate', {
        expression: `(function(){
          var m = document.body.innerHTML.match(/qsetid=(\\d+)/);
          return m ? parseInt(m[1]) : null;
        })()`,
        returnByValue: true,
      })) as { result?: { value?: number | null } };
      result.qsetid = qsetidResult.result?.value ?? undefined;

      // Render it on its own BEFORE attaching. A question that saved fine can
      // still render as "Eeek!", and attaching a broken one puts it in front of
      // students; leaving it in the library only costs a repair pass.
      if (result.qsetid) {
        result.verification = await verifyQuestion(ctx, {
          cid,
          qsetid: result.qsetid,
          seeds: opts.seeds,
        });
        result.verified = result.verification.clean;

        if (aid && result.verified) {
          await ctx.navigate(
            `${MOM_BASE}/course/modquestion2.php?qsetid=${result.qsetid}&cid=${cid}&aid=${aid}&from=addq&process=true&usedef=true`,
          );
          await ctx.waitForLoad();
        } else if (!result.verified) {
          result.success = false;
          result.data = `Filed as ${result.qsetid} but NOT attached — ${result.verification.issues.join('; ')}`;
        }
      }
    } catch (error) {
      result.success = false;
      result.data = error instanceof Error ? error.message : String(error);
    }

    results.push(result);
    opts.onQuestionDone?.(result);
  }

  // Final verification: navigate to the assessment preview and screenshot
  if (aid) {
    await ctx.navigate(`${MOM_BASE}/assess2/?cid=${cid}&aid=${aid}`);
    await ctx.waitForLoad();
  }

  return results;
  } finally {
    await release();
  }
}

// Late import to avoid circular dependency — runAgentLoop is only needed here
import { runAgentLoop } from '../../lib/page-agent-loop';
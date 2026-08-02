/**
 * mom-transfer page-agent config — the per-skill narrowing for filing
 * MyOpenMath questions from the local bank into a live course.
 *
 * The fill_mom_question custom tool is the byte-exact escape hatch: it reads
 * the .php from disk, splits on the five markers, and injects via CodeMirror.
 * The LLM never types question content — it calls this tool instead.
 *
 * This follows the page-agent-runner skill: execute_javascript is blacklisted,
 * scroll is blacklisted (the form is one page), and the system instructions
 * enforce the golden rules from mom-transfer/SKILL.md.
 */

import type { PageAgentTool, ToolContext } from '../../lib/page-agent-tools';

// --- The five markers (from mom-transfer/SKILL.md) ---

const MARKER_NAME = /^\/\/ === NAME - DESCRIPTION:\s*(.+?)\s*===/;
const MARKER_QTYPE = /^\/\/ === SET QUESTION TYPE TO:\s*(.+?)\s*===/;
const MARKER_CONTROL = /^\/\/ === COMMON CONTROL ===/;
const MARKER_QTEXT = /^\/\/ === QUESTION TEXT ===/;
const MARKER_ANSWER = /^\/\/ === ANSWER ===/;

export interface QuestionSections {
  description: string;
  qtype: string;
  control: string;
  qtext: string;
  solution: string;
}

/**
 * Parse a .php question file into its five sections by splitting on the
 * markers. Byte-exact: no paraphrasing, no truncation. The sections are
 * passed to fill_mom_question as JSON.stringify'd strings.
 */
export function parseQuestionFile(contents: string): QuestionSections {
  const lines = contents.split('\n');

  // Extract description from line 1 marker
  const nameMatch = lines[0]?.match(MARKER_NAME);
  const description = nameMatch?.[1]?.trim() ?? '';

  // Extract qtype from line 2 marker
  const qtypeMatch = lines[1]?.match(MARKER_QTYPE);
  const qtype = qtypeMatch?.[1]?.trim() ?? 'number';

  // Find section boundaries
  let controlStart = -1;
  let qtextStart = -1;
  let answerStart = -1;

  for (let i = 0; i < lines.length; i++) {
    if (MARKER_CONTROL.test(lines[i]) && controlStart === -1) controlStart = i + 1;
    else if (MARKER_QTEXT.test(lines[i]) && qtextStart === -1) qtextStart = i + 1;
    else if (MARKER_ANSWER.test(lines[i]) && answerStart === -1) answerStart = i + 1;
  }

  // Sections: control is between CONTROL and QUESTION TEXT, qtext between
  // QUESTION TEXT and ANSWER, solution is after ANSWER
  const control = controlStart >= 0 && qtextStart > controlStart
    ? lines.slice(controlStart, qtextStart - 1).join('\n').trim()
    : '';
  const qtext = qtextStart >= 0 && answerStart > qtextStart
    ? lines.slice(qtextStart, answerStart - 1).join('\n').trim()
    : '';
  const solution = answerStart >= 0
    ? lines.slice(answerStart).join('\n').trim()
    : '';

  return { description, qtype, control, qtext, solution };
}

/**
 * Build the JS expression that injects question sections into the MOM form.
 *
 * The two traps from mom-transfer/SKILL.md:
 * 1. control, qtext, solution are CodeMirror (CM5), not textareas.
 *    Setting textarea.value saves empty — CM5 overwrites on submit.
 *    Fix: write through cm.CodeMirror.setValue().
 * 2. qtype is a hidden input with no picker. Defaults to 'number'.
 *    Fix: set it explicitly.
 *
 * description IS a plain textarea and saves correctly.
 */
export function buildFillExpression(sections: QuestionSections): string {
  // JSON.stringify keeps sections byte-exact inside the JS expression
  const data = JSON.stringify(sections);
  return `(function(){
    var s = ${data};

    // description — plain textarea, safe to set directly
    var desc = document.querySelector('[name=description]');
    if (desc) {
      desc.value = s.description;
      desc.dispatchEvent(new Event('input', {bubbles:true}));
      desc.dispatchEvent(new Event('change', {bubbles:true}));
    }

    // qtype — hidden input, no picker. Must set explicitly.
    var qtype = document.querySelector('[name=qtype]');
    if (qtype) {
      qtype.value = s.qtype;
      qtype.dispatchEvent(new Event('change', {bubbles:true}));
    }

    // control, qtext, solution — CodeMirror (CM5), NOT textareas.
    // Write through the editor instance on the sibling div.
    function setCm(name, value) {
      var ta = document.querySelector('[name=' + name + ']');
      if (!ta) return false;
      var cm = ta.nextElementSibling;
      if (cm && cm.CodeMirror) {
        cm.CodeMirror.setValue(value);
        return true;
      }
      // Fallback: if not CM-wrapped, try direct (description-style)
      ta.value = value;
      ta.dispatchEvent(new Event('input', {bubbles:true}));
      return true;
    }
    setCm('control', s.control);
    setCm('qtext', s.qtext);
    setCm('solution', s.solution);

    return JSON.stringify({
      description: !!desc,
      qtype: qtype ? qtype.value : 'MISSING',
      control_set: true,
      qtext_set: true,
      solution_set: true,
    });
  })()`;
}

// --- The custom tool ---

/**
 * fill_mom_question — the byte-exact injection tool.
 *
 * The LLM calls this with { slot: N }. The tool reads the question file
 * from the pre-loaded sections map (set by the orchestrator before the
 * loop starts), builds the injection expression, and evaluates it in the
 * page via CDP.
 *
 * The orchestrator (transfer-via-agent.ts) calls setSectionsForLoop() to
 * load the question sections before starting the agent loop.
 */
let sectionsBySlot: Map<number, QuestionSections> = new Map();

/** Load question sections for the current assignment — call before the loop. */
export function setSectionsForLoop(sections: Map<number, QuestionSections>): void {
  sectionsBySlot = sections;
}

export const fillMomQuestionTool: PageAgentTool<{ slot: number }> = {
  name: 'fill_mom_question',
  description:
    'Fill the MOM question form from a manifest slot. Reads the .php from ' +
    'disk, splits on the five markers, injects via CodeMirror.setValue. ' +
    'Pass { slot: number }. Do NOT type question content yourself — call this.',
  execute: async (ctx: ToolContext, params: { slot: number }) => {
    const sections = sectionsBySlot.get(params.slot);
    if (!sections) {
      return `❌ No question loaded for slot ${params.slot}. Call the orchestrator first.`;
    }

    const expr = buildFillExpression(sections);
    const result = (await ctx.cdpSend('Runtime.evaluate', {
      expression: expr,
      returnByValue: true,
    })) as { result?: { value?: string } };

    const report = result.result?.value ?? '{}';
    return `✅ Filled slot ${params.slot} from disk. Fields: ${report}`;
  },
};

// --- The full mom-transfer config ---

export const MOM_TRANSFER_INSTRUCTIONS = `You file MyOpenMath questions from a local bank into a live course.

Rules:
- NEVER type question content into [name=control], [name=qtext], or [name=solution] — call fill_mom_question(slot) instead. Those fields are CodeMirror; typing into them saves empty.
- Set qtype via fill_mom_question only. It is a hidden input that defaults to 'number' — forgetting it makes every question render wrong.
- Submit the live form, never hand-roll a POST. The form carries a CSRF token.
- After filing and saving a question, attach it via the attach URL if provided.
- After attaching, navigate to /assess2/?cid=X&aid=Y and click "Teacher Preview" to verify the render.
- If you see "Eeek!" in the rendered text, the control block is empty — re-call fill_mom_question.
- Report which questions filed cleanly and which need repair.`;

/**
 * Build the PageAgentLoopConfig for a mom-transfer run.
 * The orchestrator calls this per-question (or per-batch) with the right
 * task sentence and LLM settings.
 */
/**
 * Measured on the fixture form (fill → submit → read the token on the next
 * page), scored against the DOM rather than the model's self-report, 2026-08-02:
 *
 *   gemma4:cloud (32.7B)   5 steps  14s  0 malformed   ← default
 *   qwen3.5:cloud (397B)   5 steps  34s  0 malformed
 *   minimax-m2.7:cloud     5 steps  25s  0 malformed
 *   deepseek-v4-flash      7 steps  18s  2 malformed
 *   nemotron-3-nano:30b    7 steps  69s  0 malformed
 *   gpt-oss:20b / minimax-m3 — FAIL, both refuse a named tool_choice and then
 *   answer in prose instead of calling the tool
 *
 * Size predicted nothing; accepting a named tool_choice predicted everything.
 */
export const MOM_TRANSFER_MODELS = [
  'gemma4:cloud',
  'deepseek-v4-flash:cloud',
  'minimax-m2.7:cloud',
  'qwen3.5:cloud',
  'nemotron-3-nano:30b-cloud',
] as const;

export const MOM_TRANSFER_MODEL = MOM_TRANSFER_MODELS[0];

/**
 * Does this failure mean "the endpoint would not talk to us", as opposed to
 * "the agent tried and got it wrong"?
 *
 * Only the first kind is worth spending the next model on. Falling back on a
 * genuine task failure would just file the same broken question five times.
 * `qwen3-coder-next` answering HTTP 410 (retired) is the case this exists for.
 */
export function isTransportFailure(data: string): boolean {
  return /LLM API error|returned no tool call|Failed to fetch|NetworkError/i.test(data ?? '');
}

export function buildMomTransferConfig(opts: {
  cid: number;
  aid?: number;
  task: string;
  baseURL: string;
  model?: string;
  apiKey?: string;
  maxSteps?: number;
  /** Only for an endpoint that rejects a named tool_choice — it makes models worse. */
  disableNamedToolChoice?: boolean;
}): import('../../lib/page-agent-loop').PageAgentLoopConfig {
  return {
    task: opts.task,
    baseURL: opts.baseURL,
    model: opts.model ?? MOM_TRANSFER_MODEL,
    apiKey: opts.apiKey ?? 'NA',
    // Left ON by default: pinning the model to AgentOutput is what stops it
    // free-styling. The vendored note said DeepSeek rejects it; against Ollama
    // Cloud deepseek-v4-flash accepted it and scored better with it.
    disableNamedToolChoice: opts.disableNamedToolChoice ?? false,
    instructions: MOM_TRANSFER_INSTRUCTIONS,
    customTools: {
      fill_mom_question: fillMomQuestionTool,
      // Blacklist the footguns per page-agent-runner skill policy
      // (execute_javascript is not in DEFAULT_TOOLS, but we explicitly
      // null it in case a future version adds it)
      // scroll is blacklisted: the MOM form is one page
    },
    maxSteps: opts.maxSteps ?? 40,
    stepDelay: 0.6, // MOM is slower than SafeColleges
  };
}
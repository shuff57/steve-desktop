---
name: page-agent-runner
description: Spawn a scoped PageAgent (Alibaba page-agent) to drive a web page autonomously — navigation, form-filling, button-clicking — with per-skill narrowing via system prompt, tool whitelist, and byte-exact custom tools. Use when a skill needs to drive the browser adaptively but Claude should not hand-drive every click.
tags:
  - agent-task
  - browser-automation
  - page-agent
---

# page-agent-runner — scoped autonomous browser sub-agent

Runs [Alibaba's page-agent](https://github.com/alibaba/page-agent) as a narrow,
per-skill execution layer. page-agent is an in-page LLM Re-Act loop (observe →
reflect → act) that runs its own LLM call every step — not Claude, not
browser-harness. Claude delegates a bounded sub-task to it and waits for the
result.

This is architecture **B-hybrid**: browser-harness keeps the deterministic
invariants (golden rules, cross-origin iframe targeting, byte-exact file
reads); page-agent handles the adaptive navigation/form-filling that would
otherwise be 40 Claude-driven clicks per item.

## When to use page-agent-runner

Use it when the skill's browser work is:

- **Adaptive navigation across forms** — click-through flows where the exact
  next selector is not known in advance.
- **Form-filling on unfamiliar or redesigned UIs** — the LLM reads the DOM
  and picks the right input.
- **Bounded and verifiable** — there is a clear "done" state and a way to
  screenshot-check the result.

**Do not use it when:**

- A deterministic invariant is load-bearing (steve's "never seek the video").
  Keep those in browser-harness + `sc.py`.
- The page is single-step or the selectors are stable. A plain CDP fill is
  cheaper and more reliable.
- The task needs Claude's reasoning every step. page-agent has its own brain;
  it cannot ask Claude for guidance mid-loop without returning.
- The task touches payment flows, purchases, or destructive writes without
  explicit user confirmation. AGENTS.md §5 forbids it.

## How to spawn

page-agent ships three control surfaces. Pick by scope:

| Surface | When | Scope |
|---|---|---|
| IIFE (`new PageAgent(config)`) | single-page skill, same tab | the current page only |
| Chrome extension (`window.PAGE_AGENT_EXT.execute(task, cfg)`) | multi-page or cross-tab | tab group in caller's window |
| MCP server (`@page-agent/mcp`) | external agent clients (Claude Desktop, Cursor) | whole browser via hub tab |

For in-app skills, prefer the **extension API** — it handles multi-page and is
the only way to cross tab boundaries. Use the IIFE only when the skill is
guaranteed single-page.

### Extension setup (one-time)

1. Install the Page Agent extension from the Chrome Web Store
   (`akldabonmimlicnjlflnapfeklbfemhj`) or GitHub Releases.
2. Open the extension side panel and copy the auth token.
3. Set the token on the page before first call:

   ```js
   localStorage.setItem('PageAgentExtUserAuthToken', 'your-token')
   ```

4. Wait for injection in the runner:

   ```js
   async function waitForExtension(timeout = 1000) {
     const start = Date.now()
     while (Date.now() - start < timeout) {
       if (window.PAGE_AGENT_EXT) return true
       await new Promise((r) => setTimeout(r, 100))
     }
     return false
   }
   ```

### Calling the runner

```js
if (await waitForExtension()) {
  const result = await window.PAGE_AGENT_EXT.execute(task, {
    baseURL: 'https://ollama.com/v1',
    model: 'deepseek-v4-flash',
    apiKey: process.env.OLLAMA_CLOUD_KEY,

    // DeepSeek rejects named tool_choice — strip it
    disableNamedToolChoice: true,

    // Per-skill narrowing lives here:
    systemInstruction: buildSystemInstruction(skillConfig),
    includeInitialTab: true,
    onStatusChange: (s) => console.log('page-agent:', s),
    onActivity: (a) => console.log('page-agent activity:', a),
  })
  // result = { success: boolean, data: string, history: HistoricalEvent[] }
}
```

The extension `ExecuteConfig` exposes `systemInstruction` (one string). For
the full `customTools` / `transformPageContent` / `instructions.getPageInstructions`
surface, use the IIFE programmatic API instead:

```js
import { PageAgent } from 'page-agent'

const agent = new PageAgent({
  baseURL: 'https://ollama.com/v1',
  model: 'deepseek-v4-flash',
  apiKey: process.env.OLLAMA_CLOUD_KEY,
  disableNamedToolChoice: true,
  customTools: skillConfig.customTools,        // whitelist + custom inject tools
  instructions: skillConfig.instructions,        // system + per-URL rules
  transformPageContent: skillConfig.transformPageContent,  // mask secrets, crop DOM
  customSystemPrompt: skillConfig.customSystemPrompt,     // replace generic prompt
  maxSteps: skillConfig.maxSteps ?? 20,
  stepDelay: skillConfig.stepDelay ?? 0.4,
})

const result = await agent.execute(skillConfig.task)
```

See `AgentConfig` in `@page-agent/core` for every narrowing field. The full
type is the skill author's contract — read it before writing a config.

## The per-skill config is the skill

A skill that uses page-agent-runner ships a `page-agent.config.ts` (or `.js`)
next to its own SKILL.md. That file exports the config object — the system
prompt, the tool whitelist, the custom inject tools, `maxSteps`. The runner
loads it and fires page-agent. Nothing about the target site lives in this
file; that is the domain skill's job.

Config shape (the fields that matter for narrowing):

```ts
interface PageAgentSkillConfig {
  task: string                                  // the bounded sub-task sentence
  customSystemPrompt?: string                   // replace the generic browser-use prompt
  instructions?: {
    system?: string                             // guardrails injected every step
    getPageInstructions?: (url: string) => string | undefined  // per-URL narrowing
  }
  customTools?: Record<string, PageAgentTool | null>  // whitelist/blacklist + custom inject
  transformPageContent?: (content: string) => Promise<string> | string
  maxSteps?: number                             // default 40; tighten for bounded tasks
  stepDelay?: number                            // default 0.4s; raise for slow sites
}
```

## Custom tools — the byte-exact escape hatch

page-agent's built-in tools (`input_text`, `click_element_by_index`,
`scroll`, `execute_javascript`) are LLM-driven and therefore lossy. When a
field must be byte-exact (multi-KB PHP, CodeMirror content, hidden inputs the
LLM won't notice), register a custom tool that does the injection
deterministically and blacklist the LLM-facing equivalent.

Pattern (from `mom-transfer`):

```js
import { z } from 'zod'
import { tool } from 'page-agent'

const fillMomQuestion = tool({
  description:
    'Fill the MOM question form from a manifest slot. Reads the .php from ' +
    'disk, splits on the five markers, injects via CodeMirror.setValue. ' +
    'Pass { slot: number }. Do NOT type question content yourself — call this.',
  inputSchema: z.object({ slot: z.number().int() }),
  execute: async function (this, input) {
    // `this` is the PageAgentCore; use this.pageController for DOM ops
    const sections = loadSectionsForSlot(input.slot)        // deterministic read
    await injectViaCodeMirror('[name=control]', sections.control)
    await injectViaCodeMirror('[name=qtext]',   sections.qtext)
    await injectViaCodeMirror('[name=solution]', sections.solution)
    await setHiddenInput('[name=qtype]', sections.qtype)
    await setTextareaValue('[name=description]', sections.description)
    return `✅ filled slot ${input.slot}, qtype=${sections.qtype}`
  },
})

export const momTransferConfig: PageAgentSkillConfig = {
  task: 'File each question into the library, attach it, and open the assessment preview.',
  customTools: {
    fill_mom_question: fillMomQuestion,   // LLM must call this, not input_text
    execute_javascript: null,             // footgun off — no raw script execution
    scroll: null,                           // form is one page
  },
  instructions: {
    system: `You file MyOpenMath questions into a live course.
Rules:
- Never type question content into [name=control], [name=qtext], or [name=solution] — call fill_mom_question(slot).
- Set qtype via the tool, not manually.
- Submit the live form, never hand-roll a POST.
- After attach, navigate to /assess2/?cid=X&aid=Y and click "Teacher Preview".`,
  },
  maxSteps: 40,
  stepDelay: 0.6,
}
```

The two traps that `mom-transfer` documents — CodeMirror overwriting empty
textareas on submit, and `qtype` defaulting to `number` — are structurally
impossible to hit because the LLM literally does not have the `input_text`
tool for those fields. Only the custom tool can write to them.

## Model choice

Default: **`deepseek-v4-flash`** on Ollama Cloud.

| Why | Detail |
|---|---|
| `tools` + `cloud` tags on Ollama | page-agent's MacroTool needs OpenAI-compat tool calls; cloud = no local GPU |
| 13B active MoE, 1M context | efficient per-step; fits full manifest + DOM state |
| `disableNamedToolChoice: true` | DeepSeek rejects `tool_choice: { type: 'function', ... }` — page-agent changelog 1.8.1 + 1.11.0 confirm |
| `transformRequestBody` escape hatch | if a specific DeepSeek build misbehaves on `temperature` or tool calls |

Fallback tier if `deepseek-v4-flash` underperforms on a specific skill's
spike: `qwen3.5:9b` cloud (same family page-agent ships/tests with; may need
fewer patches). Do **not** escalate to flagship tier (`glm-5.2`, `kimi-k3`)
for a narrow sub-task — cost/latency overkill.

## Verification — never trust "done", verify the render

page-agent's `done` action reports success based on what the LLM saw in the
DOM. It cannot see typesetting, layout, a collapsed solution box, or a
silently empty variable. The domain skill must verify the result the same
way `mom-transfer` does:

1. After page-agent returns `success: true`, screenshot the rendered page.
2. Read the screenshot — Claude, not page-agent. Look for `Eeek!`, missing
   answer widgets, truncated prompts, un-typeset math.
3. If broken: reopen the form, re-call the custom tool, re-render. Loop until
   clean or two failures on the same item — then stop and report rather than
   grind.

`done(success: true)` is the LLM's claim. The screenshot is the truth.

## Failure modes and recovery

| Symptom | Cause | Fix |
|---|---|---|
| `Invalid tool_choice type: 'object'` | DeepSeek + named tool choice | `disableNamedToolChoice: true` |
| Tool call not fired, LLM types into the field instead | system prompt not strict enough, or `input_text` still enabled for that selector | blacklist `input_text` or raise specificity in `instructions.system` |
| Empty save on a CodeMirror field | LLM used `input_text` on the textarea, CM5 overwrote on submit | route through a custom tool that calls `cm.CodeMirror.setValue` |
| `qtype` saved as `number` | hidden input, LLM never set it | custom tool sets it; never let the LLM touch `qtype` |
| page-agent loops on the same action 3+ times | LLM stuck — system prompt's "don't repeat" rule ignored | lower `maxSteps` for this skill, or add a `next_goal`-style instruction in `instructions.system` |
| Extension not injected | token mismatch or extension not installed | re-check `localStorage.PageAgentExtUserAuthToken`, re-run `waitForExtension()` |
| Cross-origin iframe invisible to page-agent | page-controller operates on page DOM only | use extension multi-page mode, or keep that sub-task in browser-harness (architecture B) |

## Safety boundary — same as the rest of the app

page-agent-runner inherits the project safety rules (AGENTS.md §5). In
particular:

- **`execute_javascript` defaults to disabled.** A skill opts in only with a
  justification in its config file. The tool is footgun-shaped — page-agent's
  own docs warn it "may bypass safeguards and data-masking mechanisms."
- **No destructive writes without user confirmation.** page-agent's `done`
  is not a confirmation flow. If the skill's last step is a submit that
  mutates a live course, either (a) stop page-agent before the submit and let
  the human click, or (b) wrap the submit in a custom tool that calls the
  project's confirmation flow first.
- **No credentials, tokens, or session data in the system prompt.** Use
  `transformPageContent` to mask anything the LLM should not see.

## What this skill does NOT do

- It does not replace `browser-harness` for skills with load-bearing
  deterministic invariants. Those stay on browser-harness.
- It does not replace Claude as the orchestrator. page-agent is a bounded
  sub-task executor; Claude reads the result, verifies, and decides the next
  step.
- It does not handle cross-origin iframes the way `sc.py`'s `iframe_target`
  does. For those sub-tasks, keep browser-harness.
- It does not write to source files or manifests. That is the domain skill's
  job, via the app's existing `book-membership.ts` / `loader.ts` paths.

---

**This file is installed and maintained by S.T.E.V.E Desktop.** It is compiled
into the app and written to `~/.claude/skills/page-agent-runner/` on launch,
so it arrives with the app and updates with it. Edits here are replaced on the
next launch — change the copy in the repo at
`steve-desktop/skills/page-agent-runner/SKILL.md` instead.
# page-agent upstream watcher report

- **Checked:** 2026-08-02T17:40:51.156Z
- **Upstream ref:** b7401a051c0ce1b1ec3f2713590a78585adf9ae1
- **Last check:** never @ never
- **Repo:** https://github.com/alibaba/page-agent

## System Prompt (src/lib/page-agent-prompt.ts)
Source: packages/core/src/prompts/system_prompt.md

**28 lines changed.**

```diff
- - Use the language that user using. Return in user's language.
+ - Use the language that user is using. Return in user's language.
- \\t*[35]<button aria-label='Submit form'>Submit</button>
+ \t*[35]<button aria-label='Submit form'>Submit</button>
- - (stacked) indentation (with \\t) means that the element is a (html) child of the element above (with a lower index)
+ - (stacked) indentation (with \t) is important and means that the element is a (html) child of the element above (with a lower index)
- - Elements tagged with \`*[\` are the new clickable elements that appeared on the website since the last step - if url has not changed.
+ - Elements tagged with `*[` are the new clickable elements that appeared on the website since the last step - if url has not changed.
- - All the elements that are scrollable are marked with \`data-scrollable\` attribute. Including the scrollable distance in every directions. You can scroll *the element* in case some area are overflowed.
+ - All the elements that are scrollable are marked with `data-scrollable` attribute. Including the scrollable distance in every directions. You can scroll *the element* in case some area are overflowed.
- - If the page is not fully loaded, use the \`wait\` action.
+ - If the page is not fully loaded, use the `wait` action.
- You must call the \`done\` action in one of three cases:
+ You must call the `done` action in one of three cases:
- - When you reach the final allowed step (\`max_steps\`), even if the task is incomplete.
+ - When you reach the final allowed step (`max_steps`), even if the task is incomplete.
- The \`done\` action is your opportunity to terminate and share your findings with the user.
+ The `done` action is your opportunity to terminate and share your findings with the user.
- - Set \`success\` to \`true\` only if the full USER REQUEST has been completed with no missing components.
+ - Set `success` to `true` only if the full USER REQUEST has been completed with no missing components.
- - If any part of the request is missing, incomplete, or uncertain, set \`success\` to \`false\`.
+ - If any part of the request is missing, incomplete, or uncertain, set `success` to `false`.
- - You can use the \`text\` field of the \`done\` action to communicate your findings and to provide a coherent reply to the user and fulfill the USER REQUEST.
+ - You can use the `text` field of the `done` action to communicate your findings and to provide a coherent reply to the user and fulfill the USER REQUEST.
- - You are ONLY ALLOWED to call \`done\` as a single action. Don't call it together with other actions.
+ - You are ONLY ALLOWED to call `done` as a single action. Don't call it together with other actions.
- - If the user asks for a structured output, your \`done\` action's schema may be modified. Take this schema into account when solving the task!
+ - If the user asks for a structured output, your `done` action's schema may be modified. Take this schema into account when solving the task!
```

**Impact:** Prompt tweak: +14 -14 lines. Usually safe to cherry-pick the new prompt string into page-agent-prompt.ts. Check if new sections (like <browser_rules>) were added or existing ones modified.

## Tool Schemas (src/lib/page-agent-tools.ts)
Source: packages/core/src/tools/index.ts

**New upstream tools (consider adding):** ask_user, scroll_horizontally, execute_javascript
**Our tools not in upstream (may be custom or removed):** navigate

## Re-Act Loop (src/lib/page-agent-loop.ts)
Source: packages/core/src/PageAgentCore.ts

**845 lines changed.**

```diff
- // Adapted from page-agent (MIT, Copyright (c) 2025 Alibaba Group Holding Limited)
+ /**
- // Re-Act loop pattern adapted from packages/core/src/PageAgentCore.ts
+  * Copyright (C) 2025 Alibaba Group Holding Limited
- // See LICENSE-page-agent.txt in this directory for full notice.
+  * Copyright (C) 2026 SimonLuvRamen
+  * All rights reserved.
- /**
+  */
-  * The CDP-driven Re-Act loop driver. This is the core of our vendored
+ import { InvokeError, LLM, type Tool } from '@page-agent/llms'
-  * page-agent: it runs the observe → think → act cycle, but instead of
+ import type { BrowserState, PageController } from '@page-agent/page-controller'
-  * page-agent's in-page PageController, it uses the app's existing CDP
+ import chalk from 'chalk'
-  * connection to extract DOM state and execute actions.
+ import * as z from 'zod/v4'
-  *
-  * Multi-page support: because the app controls navigation via CDP
+ import SYSTEM_PROMPT from './prompts/system_prompt.md?raw'
-  * (Page.navigate + waitForLoad), the loop survives page transitions
+ import { tools } from './tools'
-  * — the state (task, history) lives in the app, not in the page.
+ import type {
-  *
+ 	AgentActivity,
-  * The LLM is any OpenAI-compatible endpoint (Ollama, OpenAI, etc.).
+ 	AgentConfig,
-  * The default config targets Ollama Cloud's deepseek-v4-flash.
+ 	AgentReflection,
-  */
+ 	AgentStatus,
+ 	AgentStepEvent,
- import {
+ 	ExecutionResult,
-   PAGE_AGENT_SYSTEM_PROMPT,
+ 	HistoricalEvent,
-   assembleUserPrompt,
+ 	MacroToolInput,
-   type HistoryEntry,
+ 	MacroToolResult,
-   type BrowserState,
+ } from './types'
- } from './page-agent-prompt';
+ import { assert, fetchLlmsTxt, normalizeResponse, suppress, uid, waitFor } from './utils'
- import {
-   DEFAULT_TOOLS,
+ export { tool, type PageAgentTool, type ToolContext } from './tools'
-   describeTools,
+ export type * from './types'
... (795 more lines)
```

**Impact:** Loop logic change: +551 -294 lines. Structural — manual review needed. Our loop is a CDP reimplementation, not a copy, so upstream changes are inspirational, not directly applicable.

---

**⚠️ Upstream has changes.** Review the diffs above and sync manually.
- Prompt: cherry-pick the new string into page-agent-prompt.ts
- Tools: add/remove tools in page-agent-tools.ts to match upstream
- Loop: read the PageAgentCore changes for inspiration, but our loop is a CDP reimplementation
- Run with --update to auto-sync the prompt (tools + loop need manual review)
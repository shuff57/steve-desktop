import type { AgentActionResponse, AgentApiResponse, AgentTextResponse } from './agent-types';

export interface ToolDefinition {
  name: string;
  description: string;
  params: Record<string, string>;
}

export const TOOL_DEFINITIONS: ToolDefinition[] = [
  { name: 'click', description: 'Click an element on the page', params: { ref: 'Element ref from the DOM snapshot (e.g. e12)' } },
  {
    name: 'fill',
    description: 'Fill an input, textarea, or editable field with text',
    params: { ref: 'Element ref from the DOM snapshot (e.g. e12)', value: 'Text value to enter' },
  },
  { name: 'navigate', description: 'Navigate browser to URL', params: { url: 'Absolute URL' } },
  {
    name: 'wait',
    description: 'Wait for a page condition before continuing',
    params: { condition: 'Condition description or selector', timeout: 'Optional timeout in milliseconds' },
  },
  { name: 'keyboard', description: 'Press a keyboard key or shortcut', params: { key: 'Key or chord (e.g. Enter, Ctrl+L)' } },
  {
    name: 'scroll',
    description: 'Scroll in a direction',
    params: { direction: 'up|down|left|right' },
  },
  {
    name: 'iframe_interact',
    description: 'Execute an action inside a specific iframe',
    params: {
      frameSelector: 'CSS selector for iframe element',
      action: 'Nested action object (click/fill/navigate/wait/keyboard/scroll)',
    },
  },
  {
    name: 'done',
    description: 'Signal task completion',
    params: { success: 'true if task succeeded', message: 'Completion summary' },
  },
];

export const AGENT_SYSTEM_PROMPT = `You are a browser automation planning agent.
You help users automate interactions in an embedded browser.

You receive conversational history plus page context (DOM snapshots and optional screenshots).
Use that context to choose exactly ONE next action per turn.

Respond with EXACTLY ONE JSON object in one of these forms:
1) Action response:
{"action":"<action_name>","params":{...},"reasoning":"short explanation"}
2) Text response:
{"text":"short conversational response"}

Allowed action names:
- click
- fill
- navigate
- wait
- keyboard
- scroll
- iframe_interact
- done

Action parameter shapes:
- click: {"ref":"e12"}
- fill: {"ref":"e12","value":"..."}
- navigate: {"url":"https://..."}
- wait: {"condition":"...","timeout":5000}
- keyboard: {"key":"Enter"}
- scroll: {"direction":"down"}
- iframe_interact: {"frameSelector":"...","action":{"type":"click","selector":"..."}}
- done: {"success":true,"message":"..."}

The DOM snapshot lists elements as: [e12] button "Next"
The bracketed value is that element's ref. Pass it back verbatim as "ref".

Rules:
1. Always output valid JSON only, no markdown.
2. Target elements by their ref from the snapshot. Never invent a ref, and never send a
   CSS selector for click/fill — many elements on a page share the same selector, so a
   selector can silently act on the wrong one.
3. Refs are only valid for the snapshot they came from. If an action reports the element
   is stale, re-read the snapshot and use the new ref; do not retry the old one.
4. If uncertain, gather context first via wait, scroll, or a precise text response.
5. Handle iframe content explicitly with iframe_interact.
6. Use done when the user goal is complete or blocked.`;

export function parseAgentResponse(rawText: string): AgentApiResponse {
  let text = rawText.trim();

  text = text.replace(/<think>[\s\S]*?<\/think>/gi, '').trim();

  text = text.replace(/&quot;/g, '"').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');

  text = text.replace(/(,)(\s*[}\]])/g, '$2');

  const fenceMatch = text.match(/```(?:json)?\s*([\s\S]*?)\s*```/);
  if (fenceMatch) text = fenceMatch[1].trim();

  const first = text.indexOf('{');
  const last = text.lastIndexOf('}');
  if (first !== -1 && last > first) {
    const candidate = text.slice(first, last + 1);
    try {
      const parsed = JSON.parse(candidate);
      if (parsed.action && typeof parsed.action === 'string') {
        // Models sometimes flatten params to the top level ({"action":"done","success":true}
        // — the prompt's own done example reads that way). Fold strays into params so the
        // loop never dereferences a missing params object.
        const { action, params, reasoning, ...rest } = parsed as AgentActionResponse & Record<string, unknown>;
        return { action, reasoning, params: { ...rest, ...(params ?? {}) } };
      }
      if (parsed.text && typeof parsed.text === 'string') {
        return parsed as AgentTextResponse;
      }
    } catch {
    }
  }

  return { text: rawText };
}

import type { AgentActionResponse, AgentApiResponse, AgentTextResponse } from './agent-types';

export interface ToolDefinition {
  name: string;
  description: string;
  params: Record<string, string>;
}

export const TOOL_DEFINITIONS: ToolDefinition[] = [
  { name: 'click', description: 'Click an element on the page', params: { selector: 'CSS selector to click' } },
  {
    name: 'fill',
    description: 'Fill an input, textarea, or editable field with text',
    params: { selector: 'CSS selector to fill', value: 'Text value to enter' },
  },
  {
    name: 'read',
    description:
      'Read a value from an element into a named on-device slot, WITHOUT seeing the value. Use to move sensitive data (e.g. a contact email) between pages. You get back only the length, never the value.',
    params: { selector: 'CSS selector to read from', into: 'Slot name to store the value in (e.g. p1)' },
  },
  {
    name: 'paste',
    description: 'Write a previously read slot value into an element, on-device. The value is never exposed to you.',
    params: { selector: 'CSS selector to write into', from: 'Slot name to read the value from (e.g. p1)' },
  },
  {
    name: 'login',
    description:
      'Log in to the CURRENT page using the saved credentials for this site. Username and password are filled on-device and submitted — you never see them. Use this when a page shows a login form.',
    params: { site: 'Optional site name hint; usually omit and it matches by URL' },
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
- read
- paste
- login
- navigate
- wait
- keyboard
- scroll
- iframe_interact
- done

Action parameter shapes:
- click: {"selector":"..."}
- fill: {"selector":"...","value":"..."}
- read: {"selector":"...","into":"p1"}
- paste: {"selector":"...","from":"p1"}
- login: {} or {"site":"..."}
- navigate: {"url":"https://..."}
- wait: {"condition":"...","timeout":5000}
- keyboard: {"key":"Enter"}
- scroll: {"direction":"down"}
- iframe_interact: {"frameSelector":"...","action":{"type":"click","selector":"..."}}
- done: {"success":true,"message":"..."}

Rules:
1. Always output valid JSON only, no markdown.
2. Choose selectors grounded in the provided DOM snapshot.
3. If uncertain, gather context first via wait, scroll, or a precise text response.
4. Handle iframe content explicitly with iframe_interact.
5. Avoid repeating the same failed selector; adapt based on error feedback.
6. Use done when the user goal is complete or blocked.
7. To move sensitive data (emails, IDs) between pages, use read then paste by slot
   name — never ask to see the value or put it in a fill. read returns only a length.
8. If a page shows a login form, use the login action — never type a password into a
   fill. Credentials are filled on-device and never shown to you.`;

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
        return parsed as AgentActionResponse;
      }
      if (parsed.text && typeof parsed.text === 'string') {
        return parsed as AgentTextResponse;
      }
    } catch {
    }
  }

  return { text: rawText };
}

import { describePageTool } from './page-tools-bridge';

export type AgentEngine = 'claude' | 'opencode';

/**
 * Maps a provider id (as stored in provider_configs) to the CLI that backs it.
 * Anything not explicitly claude-backed goes to opencode, which can front many
 * providers via its own `provider/model` ids.
 */
export function engineForProvider(providerId?: string): AgentEngine {
  return providerId === 'anthropic' || providerId === 'claude' ? 'claude' : 'opencode';
}

/**
 * Normalises a model id for the CLI's --model/-m flag.
 *
 * opencode expects `provider/model`; the UI lets the user type a bare ollama.com cloud
 * model (e.g. `kimi-k2.6:cloud`), so a bare id gets the `ollama/` prefix. Ids that
 * already carry a provider pass through, as does everything for claude.
 */
export function cliModelArg(engine: AgentEngine, model?: string): string | null {
  const m = model?.trim();
  if (!m) return null;
  if (engine === 'opencode' && !m.includes('/')) return `ollama/${m}`;
  return m;
}

interface ClaudeCliResult {
  result?: string;
  is_error?: boolean;
  subtype?: string;
}

/**
 * Pulls the assistant's text out of a CLI's stdout.
 *
 * `claude -p --output-format json` wraps the reply in an envelope whose `result` holds
 * the text; opencode prints the reply directly. Anything unrecognised is passed through
 * as-is for the caller to make sense of.
 */
export function extractCliText(engine: AgentEngine, stdout: string): string {
  const raw = stdout.trim();
  if (!raw) throw new Error(`${engine} returned no output`);

  if (engine === 'claude') {
    // Single json envelope (--output-format json): one object holding the result.
    let parsed: ClaudeCliResult | null = null;
    try {
      parsed = JSON.parse(raw) as ClaudeCliResult;
    } catch {
      parsed = null;
    }
    if (parsed) {
      if (parsed.is_error) {
        throw new Error(`claude reported an error (${parsed.subtype ?? 'unknown'}): ${parsed.result ?? ''}`.trim());
      }
      if (typeof parsed.result === 'string') return parsed.result;
      return raw;
    }
    // stream-json (--output-format stream-json): NDJSON, one event per line; the terminal
    // {"type":"result",...} event carries the final text. Scan from the end for it.
    const lines = raw.split('\n').map((l) => l.trim()).filter(Boolean);
    for (let i = lines.length - 1; i >= 0; i--) {
      let ev: (ClaudeCliResult & { type?: string }) | null = null;
      try {
        ev = JSON.parse(lines[i]);
      } catch {
        continue;
      }
      if (ev?.type === 'result') {
        if (ev.is_error) {
          throw new Error(`claude reported an error (${ev.subtype ?? 'unknown'}): ${ev.result ?? ''}`.trim());
        }
        if (typeof ev.result === 'string') return ev.result;
      }
    }
    return raw;
  }

  // opencode --format json: NDJSON, one event per line. The assistant's reply is carried in
  // {"type":"text",...,"part":{"text":"..."}} events — concatenate them. Output with no such events
  // (a bare JSON action or plain text) falls back to the raw stdout.
  const lines = raw.split('\n').map((l) => l.trim()).filter(Boolean);
  const texts: string[] = [];
  for (const line of lines) {
    let ev: { type?: string; part?: { text?: string } } | null = null;
    try {
      ev = JSON.parse(line);
    } catch {
      continue;
    }
    if (ev?.type === 'text' && typeof ev.part?.text === 'string') texts.push(ev.part.text);
  }
  return texts.length ? texts.join('').trim() : raw;
}

/**
 * Compress one stream-json NDJSON line into a short human progress string for the UI, or
 * null for lines not worth showing. Kept here so it's unit-testable off the live stream.
 */
/** Turn a raw browser-driving Bash command (usually a CDP call) into a plain-English snapshot of
 *  what the agent is doing, instead of dumping the raw command. Returns a short verb phrase. */
export function describeBrowserCommand(command: string): string {
  const c = command.toLowerCase();
  // Multi-tab bridge calls (__steveControl.*) ride Runtime.evaluate, so name them before the
  // generic evaluate → "reading the page" catch-all below would swallow them.
  if (c.includes('__stevecontrol.newtab')) return 'opening a new tab';
  if (c.includes('__stevecontrol.login')) return 'logging in';
  if (c.includes('__stevecontrol.activate')) return 'switching tabs';
  if (c.includes('__stevecontrol.listtabs')) return 'checking open tabs';
  if (c.includes('__stevecontrol.closetab')) return 'closing a tab';
  if (c.includes('__stevecontrol.startrecording')) return 'starting a recording';
  if (c.includes('__stevecontrol.stoprecording')) return 'stopping the recording';
  if (c.includes('setfileinputfiles')) return 'attaching a file';
  if (c.includes('page.navigate') || /\bnavigate\b|\bgoto\b/.test(c)) {
    const url = command.match(/https?:\/\/[^\s"'`)]+/);
    return url ? `navigating to ${url[0].replace(/^https?:\/\//, '').slice(0, 48)}` : 'navigating';
  }
  if (c.includes('capturescreenshot')) return 'taking a screenshot';
  if (c.includes('page.reload')) return 'reloading the page';
  if (c.includes('dispatchmouseevent') || c.includes('.click(') || /\bclick\b/.test(c)) return 'clicking';
  if (c.includes('inserttext') || c.includes('dispatchkeyevent') || /\bfill\b|\.value\s*=/.test(c)) return 'filling a field';
  if (c.includes('runtime.evaluate') || c.includes('document.') || c.includes('json/list') || c.includes('innertext')) return 'reading the page';
  if (c.includes('cdp') || c.includes('websocket') || c.includes('devtools')) return 'driving the browser';
  // Every branch above assumes the command is driving a browser. The question rail's agent is
  // not — it runs php, git, grep — so all of it landed on the same "running a command", eight
  // identical lines in a row for eight different things. Name the program instead.
  return namedCommand(command) ?? 'running a command';
}

/**
 * A file the agent is writing, pulled out of the stream it is already sending us.
 *
 * The preview pane used to learn about a write by polling the file on disk every 600ms,
 * which meant it sat on "Waiting for the agent to write the file…" for the whole run —
 * the agent touches disk once, near the end, so there was genuinely nothing to poll. The
 * content is in the `Write` tool call itself, and every repair is in the `Edit` calls, so
 * the pane can show the file from the moment the agent commits to it.
 */
export interface FileEdit {
  path: string;
  /** Whole contents, for a Write. Null when this is a replacement. */
  content: string | null;
  /** The replacement an Edit performs, applied against what the pane already holds. */
  replace?: { from: string; to: string };
}

/** One stream line → the file write it performs, or null if it performs none. */
export function extractFileEdit(line: string): FileEdit | null {
  let ev: {
    type?: string;
    message?: { content?: { type?: string; name?: string; input?: Record<string, unknown> }[] };
    part?: { tool?: string; state?: { input?: Record<string, unknown> } };
  };
  try {
    ev = JSON.parse(line);
  } catch {
    return null;
  }
  if (ev.type === 'assistant' && Array.isArray(ev.message?.content)) {
    for (const c of ev.message.content) {
      if (c.type === 'tool_use' && c.name) {
        const hit = fileEditFrom(c.name, c.input);
        if (hit) return hit;
      }
    }
    return null;
  }
  if (ev.type === 'tool_use' && ev.part?.tool) return fileEditFrom(ev.part.tool, ev.part.state?.input);
  return null;
}

function fileEditFrom(tool: string, input?: Record<string, unknown>): FileEdit | null {
  const i = input ?? {};
  const str = (v: unknown) => (typeof v === 'string' ? v : null);
  // opencode spells it filePath; claude spells it file_path.
  const path = str(i.file_path) ?? str(i.filePath);
  if (!path) return null;
  const name = tool.toLowerCase();
  if (name === 'write') {
    const content = str(i.content);
    return content === null ? null : { path, content };
  }
  if (name === 'edit') {
    const from = str(i.old_string) ?? str(i.oldString);
    const to = str(i.new_string) ?? str(i.newString);
    // A replacement with nothing to find cannot be applied to the pane's copy.
    return from ? { path, content: null, replace: { from, to: to ?? '' } } : null;
  }
  return null;
}

/**
 * Apply an edit to what the pane is showing, or null if it cannot be applied cleanly.
 *
 * Null on a miss is the point: an `old_string` that is not in our copy means the pane has
 * drifted from the real file, and guessing would put text on screen that was never written.
 * Better to leave the last known-good contents up and let the disk poll correct it.
 */
export function applyFileEdit(current: string, edit: FileEdit): string | null {
  if (edit.content !== null) return edit.content;
  if (!edit.replace) return null;
  const { from, to } = edit.replace;
  return current.includes(from) ? current.replace(from, to) : null;
}

/** 'webfetch' → 'WebFetch', so opencode's lowercase tool names hit describeTool's cases. */
function titleCaseTool(tool: string): string {
  const known = ['Read', 'Write', 'Edit', 'Glob', 'Grep', 'WebFetch', 'WebSearch', 'Skill', 'ToolSearch'];
  return known.find((k) => k.toLowerCase() === tool.toLowerCase()) ?? tool;
}

/** `php -r '…'` → "running php". First real word of the command, when it looks like a program. */
function namedCommand(command: string): string | null {
  // Deliberately NOT a shell parse — splitting on `;` and `|` cut `php -r "echo 1;"` in half
  // and lost the program. Strip only the two prefixes that reliably precede the real command
  // (a `cd somewhere &&` hop and env assignments), then take the head token.
  const prog = command
    .trim()
    .replace(/^cd\s+(?:"[^"]*"|'[^']*'|\S+)\s*&&\s*/i, '')
    .replace(/^(?:\w+=(?:"[^"]*"|'[^']*'|\S+)\s+)+/, '')
    .split(/\s+/)[0];
  if (!prog) return null;
  const name = prog.replace(/\.(exe|cmd|sh)$/i, '').split(/[/\\]/).pop();
  return name && /^[\w.-]{1,20}$/.test(name) ? `running ${name}` : null;
}

/**
 * One tool call as a phrase with an object in it.
 *
 * A bare "using Read" says nothing a second "using Read" doesn't, which is how the rail
 * produced six identical lines for six different files. The in-page overlay never had this
 * problem because `describeActivity` always appends a summary of the input — this is the
 * same idea, with per-tool knowledge of which argument is the interesting one.
 */
export function describeTool(name: string, input?: Record<string, unknown>): string {
  const s = (v: unknown) => (typeof v === 'string' && v.trim() ? v.trim() : null);
  const tail = (p: string) => p.split(/[/\\]/).filter(Boolean).pop() ?? p;
  const clip = (v: string, max = 44) =>
    v.replace(/\s+/g, ' ').length > max ? `${v.replace(/\s+/g, ' ').slice(0, max - 1)}…` : v.replace(/\s+/g, ' ');
  const i = input ?? {};

  switch (name) {
    case 'Read':
    case 'NotebookEdit': {
      const f = s(i.file_path) ?? s(i.notebook_path);
      return f ? `reading ${tail(f)}` : 'reading a file';
    }
    case 'Write': {
      const f = s(i.file_path);
      return f ? `writing ${tail(f)}` : 'writing a file';
    }
    case 'Edit': {
      const f = s(i.file_path);
      return f ? `editing ${tail(f)}` : 'editing a file';
    }
    case 'Glob':
    case 'Grep': {
      const p = s(i.pattern);
      return p ? `searching for ${clip(p, 32)}` : 'searching the files';
    }
    case 'WebFetch': {
      const u = s(i.url);
      return u ? `fetching ${clip(u.replace(/^https?:\/\//, ''), 40)}` : 'fetching a page';
    }
    case 'WebSearch': {
      const q = s(i.query);
      return q ? `searching the web for ${clip(q, 36)}` : 'searching the web';
    }
    case 'Skill': {
      const k = s(i.skill);
      return k ? `following the ${k} skill` : 'following a skill';
    }
    case 'ToolSearch': {
      const q = s(i.query);
      return q ? `looking for a ${clip(q, 32)} tool` : 'looking for a tool';
    }
    default: {
      // The page tools arrive namespaced (mcp__page__page_click) and would otherwise read as
      // "using mcp__page__page_click" — the exact tool-name spam this function exists to replace.
      const page = name.startsWith('mcp__page__') ? describePageTool(name.slice('mcp__page__'.length), i) : null;
      if (page) return page;
      // Unknown tool: still better with its most descriptive string argument attached.
      const hint = s(i.description) ?? s(i.query) ?? s(i.pattern) ?? s(i.file_path);
      return hint ? `using ${name} — ${clip(hint, 40)}` : `using ${name}`;
    }
  }
}

// Distil one stream-json event into a short, human snapshot for the live activity feed — or null to
// drop it. We deliberately skip session/system events and successful-completion events (the phase
// label already conveys those) and never echo the plan/result markdown here (it is rendered on its
// own), so the feed shows what the agent is DOING, not raw command spam.
export function summarizeCliLine(line: string): string | null {
  let ev: {
    type?: string;
    message?: {
      content?: {
        type?: string;
        name?: string;
        text?: string;
        input?: Record<string, unknown> & {
        command?: string;
        description?: string;
        prompt?: string;
        subagent_type?: string;
      };
      }[];
    };
    part?: {
      text?: string;
      tool?: string;
      state?: { input?: Record<string, unknown> & { command?: string } };
    };
    is_error?: boolean;
  };
  try {
    ev = JSON.parse(line);
  } catch {
    return null;
  }
  if (ev.type === 'system') return null; // "session started" noise
  if (ev.type === 'result') return ev.is_error ? 'agent reported an error' : null;
  if (ev.type === 'assistant' && Array.isArray(ev.message?.content)) {
    for (const c of ev.message.content) {
      if (c.type === 'tool_use') {
        // Transparency: a subagent spawn is a bigger event than a tool call — say so, with its
        // stated purpose, so the user always knows when extra agents are working on their behalf.
        if (c.name === 'Task' || c.name === 'Agent') {
          const what = c.input?.description || c.input?.subagent_type || c.input?.prompt?.slice(0, 60) || '';
          return `⚡ spawned a subagent${what ? `: ${what}` : ''}`;
        }
        if (c.name === 'Bash' && c.input?.command) {
          const cmd = c.input.command;
          if (/\b(claude|opencode)\b(\.exe)?\s/.test(cmd)) return `⚡ spawned a CLI agent process`;
          return describeBrowserCommand(cmd);
        }
        return c.name ? describeTool(c.name, c.input) : null;
      }
      if (c.type === 'text' && c.text?.trim()) {
        const t = c.text.trim();
        const heal = mapHealLine(t);
        if (heal) return heal;
        if (/^#{1,6}\s|^\d+\.\s+\*\*\[MUTATES\]/.test(t)) return null; // plan/result markdown, shown separately
        return t.replace(/\s+/g, ' ').slice(0, 110);
      }
    }
  }
  // opencode --format json events are flat: {type, part:{...}}, not a claude-style envelope.
  if (ev.type === 'step_start' || ev.type === 'step_finish') return null;
  if (ev.type === 'tool_use' && ev.part) {
    if (ev.part.tool === 'bash' && ev.part.state?.input?.command) return describeBrowserCommand(ev.part.state.input.command);
    // opencode lowercases its tool names ('read', 'webfetch'); describeTool is keyed on the
    // claude-style capitalised names, so title-case before looking one up.
    return ev.part.tool
      ? describeTool(titleCaseTool(ev.part.tool), ev.part.state?.input)
      : null;
  }
  if (ev.type === 'text' && ev.part?.text?.trim()) {
    const t = ev.part.text.trim();
    const heal = mapHealLine(t);
    if (heal) return heal;
    if (/^#{1,6}\s|^\d+\.\s+\*\*\[MUTATES\]/.test(t)) return null; // plan/result markdown, shown separately
    return t.replace(/\s+/g, ' ').slice(0, 110);
  }
  return null;
}

/** Recognize the `STEVE_MAP_HEAL: …` transparency marker the automate agent prints when it pauses
 *  to self-heal the site map mid-task, and turn it into a loud activity-log line. Returns null for
 *  any other text. The agent heals the map file in place (its own working memory) — this is what
 *  makes that background work visible instead of silent. */
export function mapHealLine(text: string): string | null {
  const m = text.match(/^STEVE_MAP_HEAL:\s*(.+)$/im);
  return m ? `⚡ self-healing the site map — ${m[1].trim().slice(0, 90)}` : null;
}

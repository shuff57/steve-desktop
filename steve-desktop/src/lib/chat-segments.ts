/**
 * Split a chat message into prose and fenced-code segments for rendering.
 *
 * Deliberately returns PLAIN TEXT, never HTML: chat replies carry model output that can echo
 * markup from a crawled page, and the app's only sanitizer (skill-parser.sanitizeHtml) is a
 * regex blacklist. Rendering these through {@html} would put a prompt-injection sink inside the
 * Tauri webview, so the template prints each segment as text and styles code with CSS instead.
 *
 * ponytail: fences only — no inline `code`, emphasis, or headings. Those read fine as plain
 * text in a chat bubble; add a real markdown pass only if the prose starts looking mangled.
 */

export interface ChatSegment {
  kind: 'text' | 'code';
  /** Segment body. For code, the fence lines and language tag are stripped. */
  content: string;
  /** Language tag from the opening fence, when one was given (code segments only). */
  lang?: string;
}

/** Opening fence: optional indent, three-plus backticks, optional language word. */
const FENCE = /^[ \t]*(`{3,})[ \t]*([A-Za-z0-9_+-]*)[ \t]*$/;

export function splitChatSegments(content: string): ChatSegment[] {
  const segments: ChatSegment[] = [];
  const lines = content.split('\n');

  let text: string[] = [];
  let code: string[] | null = null;
  let fence = '';
  let lang = '';

  const flushText = () => {
    // Trailing blank lines around a fence are layout artifacts, not content.
    const body = text.join('\n').replace(/^\n+|\n+$/g, '');
    if (body) segments.push({ kind: 'text', content: body });
    text = [];
  };

  for (const line of lines) {
    const m = FENCE.exec(line);

    if (code === null) {
      // A fence with a language tag always opens; a bare fence opens too.
      if (m) {
        flushText();
        code = [];
        fence = m[1];
        lang = m[2];
      } else {
        text.push(line);
      }
      continue;
    }

    // Inside a block: only a fence at least as long as the opener, and with no language
    // tag, closes it — so ```` inside a ``` block stays content rather than ending it early.
    if (m && m[1].length >= fence.length && !m[2]) {
      segments.push({ kind: 'code', content: code.join('\n'), ...(lang ? { lang } : {}) });
      code = null;
      lang = '';
      continue;
    }
    code.push(line);
  }

  // An unterminated fence is normal mid-stream: keep what arrived as code, not as lost text.
  if (code !== null) {
    segments.push({ kind: 'code', content: code.join('\n'), ...(lang ? { lang } : {}) });
  } else {
    flushText();
  }

  return segments;
}

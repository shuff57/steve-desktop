import matter from 'gray-matter';
import { Marked } from 'marked';

export interface ParsedSkill {
  name: string;
  description: string;
  content: string;
  author?: string;
  tags?: string[];
  urlPatterns?: string[];
}

type SkillFrontmatter = Partial<Pick<ParsedSkill, 'name' | 'description' | 'author' | 'tags' | 'urlPatterns'>>;

function parseStringArray(value: unknown): string[] | undefined {
  if (!Array.isArray(value)) {
    return undefined;
  }

  return value.map((item) => String(item));
}

export function parseSkillFrontmatter(rawContent: string): SkillFrontmatter {
  if (!rawContent || !rawContent.trim()) {
    return {};
  }

  const { data } = matter(rawContent);
  const frontmatter = data as Record<string, unknown>;

  return {
    name: typeof frontmatter.name === 'string' ? frontmatter.name : undefined,
    description: typeof frontmatter.description === 'string' ? frontmatter.description : undefined,
    author: typeof frontmatter.author === 'string' ? frontmatter.author : undefined,
    tags: parseStringArray(frontmatter.tags),
    urlPatterns: parseStringArray(frontmatter.urlPatterns),
  };
}

export function parseSkillMarkdown(rawContent: string): ParsedSkill {
  if (!rawContent || !rawContent.trim()) {
    return {
      name: '',
      description: '',
      content: rawContent,
    };
  }

  const { data, content: bodyContent } = matter(rawContent);
  const frontmatter = data as Record<string, unknown>;

  let name = typeof frontmatter.name === 'string' ? frontmatter.name : '';
  let description = typeof frontmatter.description === 'string' ? frontmatter.description : '';
  const author = typeof frontmatter.author === 'string' ? frontmatter.author : undefined;
  const tags = parseStringArray(frontmatter.tags);
  const urlPatterns = parseStringArray(frontmatter.urlPatterns);

  if (!name) {
    const headingMatch = bodyContent.match(/^#\s+(.+)$/m);
    if (headingMatch) {
      name = headingMatch[1].trim();
    } else {
      const lines = bodyContent.split('\n');
      const firstLine = lines.find((line) => line.trim().length > 0);
      if (firstLine) {
        name = firstLine.trim();
      }
    }
  }

  if (!description) {
    const paragraphs = bodyContent.split(/\n\s*\n/);
    for (const paragraph of paragraphs) {
      const trimmed = paragraph.trim();
      if (trimmed && !trimmed.startsWith('#')) {
        description = trimmed.replace(/\n/g, ' ');
        break;
      }
    }
  }

  return {
    name,
    description,
    content: rawContent,
    author,
    tags,
    urlPatterns,
  };
}

/**
 * Markdown → HTML for the {@html} previews (skill cards, agent run reports, site-profile
 * reports). Every one of those renders model output that can echo markup from a crawled page,
 * inside a webview holding Tauri IPC — so this is a real trust boundary.
 *
 * Safe by construction rather than by blacklist: the only tags that survive are the ones marked
 * itself emits from markdown syntax, whose text content marked escapes. Raw HTML in the source
 * is escaped to literal text, and link/image URLs are scheme-checked. The previous version
 * pattern-matched known-bad strings, which missed unquoted handlers (<svg onload=x>), tags it
 * had no rule for (<object>, <embed>), and entity-encoded javascript: URLs.
 *
 * Tradeoff: a skill that embeds literal HTML now shows the tags instead of rendering them.
 * That is the intended direction for a preview of untrusted content.
 */
const ESCAPES: Record<string, string> = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;',
};

const escapeHtml = (s: string): string => s.replace(/[&<>"']/g, (c) => ESCAPES[c]);

const SAFE_SCHEMES = new Set(['http', 'https', 'mailto', 'tel']);

/** Decode the entity forms an attribute value would decode to before the browser navigates it. */
function decodeEntities(url: string): string {
  return url
    .replace(/&#x([0-9a-f]+);?/gi, (_, hex) => codePoint(parseInt(hex, 16)))
    .replace(/&#(\d+);?/g, (_, dec) => codePoint(parseInt(dec, 10)))
    .replace(/&colon;/gi, ':')
    .replace(/&Tab;|&NewLine;/gi, '');
}

const codePoint = (n: number): string =>
  Number.isFinite(n) && n >= 0 && n <= 0x10ffff ? String.fromCodePoint(n) : '';

/**
 * Allow relative/anchor URLs and the four schemes a document legitimately links to; send
 * everything else (javascript:, data:, vbscript:, blob:) to '#'.
 */
function safeUrl(href: string): string {
  // Browsers strip ASCII whitespace and control characters inside a URL before resolving it,
  // so "java\tscript:alert(1)" navigates exactly like "javascript:alert(1)". Test the string
  // the browser will act on, not the one that was written.
  const normalized = decodeEntities(href).replace(/[\u0000-\u0020\u00a0]/g, '');
  const scheme = /^([a-zA-Z][a-zA-Z0-9+.-]*):/.exec(normalized);
  if (!scheme) return href; // relative, anchor, or protocol-relative — no scheme to abuse
  return SAFE_SCHEMES.has(scheme[1].toLowerCase()) ? href : '#';
}

// A private instance, so overriding the renderer never leaks into the shared `marked` singleton.
const safeMarked = new Marked({
  renderer: {
    // Raw HTML — block-level and inline both arrive here. Show it, never run it.
    html(token: { raw: string }) {
      return escapeHtml(token.raw);
    },
    link(token: { href: string; title?: string | null; tokens: unknown[] }) {
      const title = token.title ? ` title="${escapeHtml(token.title)}"` : '';
      // @ts-expect-error — marked types `this.parser` loosely on renderer overrides
      const text = this.parser.parseInline(token.tokens);
      return `<a href="${escapeHtml(safeUrl(token.href))}"${title}>${text}</a>`;
    },
    image(token: { href: string; title?: string | null; text: string }) {
      const title = token.title ? ` title="${escapeHtml(token.title)}"` : '';
      return `<img src="${escapeHtml(safeUrl(token.href))}" alt="${escapeHtml(token.text)}"${title}>`;
    },
  },
});

export async function renderSkillPreview(content: string): Promise<string> {
  return await safeMarked.parse(content);
}

import matter from 'gray-matter';
import { marked } from 'marked';

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

export async function renderSkillPreview(content: string): Promise<string> {
  const html = await marked.parse(content);
  return sanitizeHtml(html);
}

function sanitizeHtml(html: string): string {
  let safeHtml = html;

  safeHtml = safeHtml.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
  safeHtml = safeHtml.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi, '');
  safeHtml = safeHtml.replace(/\son\w+\s*=\s*["'][^"']*["']/gi, '');
  safeHtml = safeHtml.replace(/%20on\w+%3D/gi, '');
  safeHtml = safeHtml.replace(/onerror=/gi, '');
  safeHtml = safeHtml.replace(/href\s*=\s*["']\s*javascript:[^"']*["']/gi, 'href="#"');

  return safeHtml;
}

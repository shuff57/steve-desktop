// Pick saved skills relevant to an agent turn and render them as system-prompt
// guidance. A skill matches when its name appears in the user's message, or its
// url_pattern (glob) matches the page the agent is on.

export interface MatchableSkill {
  name: string;
  content: string;
  url_pattern?: string | null;
  is_active?: number;
}

function globToRegExp(glob: string): RegExp {
  const escaped = glob.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*');
  return new RegExp('^' + escaped + '$', 'i');
}

export function selectSkills(skills: MatchableSkill[], opts: { url?: string; message?: string }): MatchableSkill[] {
  const msg = (opts.message ?? '').toLowerCase();
  return skills.filter((s) => {
    if (s.is_active === 0) return false;
    const byName = !!s.name && msg.includes(s.name.toLowerCase());
    const byUrl = !!opts.url && !!s.url_pattern && globToRegExp(s.url_pattern).test(opts.url);
    return byName || byUrl;
  });
}

/** Drop a leading `---\n…\n---` frontmatter block so only the body is injected. */
function stripFrontmatter(md: string): string {
  return md.replace(/^---\n[\s\S]*?\n---\n?/, '').trim();
}

/** Matched skills as guidance to append to the agent system prompt ('' if none). */
export function skillsToPrompt(skills: MatchableSkill[]): string {
  if (!skills.length) return '';
  const blocks = skills.map((s) => `## Skill: ${s.name}\n${stripFrontmatter(s.content)}`);
  return `\n\nThe user has saved skills relevant to this task. Follow the most relevant one exactly:\n\n${blocks.join('\n\n')}`;
}

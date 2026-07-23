/**
 * mom-island loader — read-only filesystem indexer for the MyOpenMath question
 * bank. Walks `<root>/questions/<family>/` recursively, skipping Windows junk
 * at the questions level (nul, $APPDATA, C:Users*). Pure I/O; no Tauri calls.
 */
import { readdir, stat } from 'node:fs/promises';
import { join } from 'node:path';

export interface MOMQuestion {
  slug: string;
  path: string;
  hasManifest: boolean;
}

export interface MOMFamily {
  name: string;
  count: number;
  questions: MOMQuestion[];
}

export interface MOMIndex {
  families: MOMFamily[];
}

/** Family-level folders we must never treat as content. Matches Windows artifacts
 *  from the older gradebook/scripts droppings and the user-reported `nul` quirk. */
const JUNK_FAMILY_RE = /^(nul|\$APPDATA$|C:Users.*)$/i;

export function isJunkFamily(name: string): boolean {
  return JUNK_FAMILY_RE.test(name);
}

/** Async walker: `root/questions/<family>/<slug>/(q*.php | manifest.json)`.
 *  We don't recurse into slug folders — the manifest + php files are siblings.
 *  A question is "any folder containing at least one .php file". */
export async function loadMOMIndex(root: string): Promise<MOMIndex> {
  const questionsDir = join(root, 'questions');
  const families: MOMFamily[] = [];
  let entries: string[];
  try {
    entries = await readdir(questionsDir);
  } catch {
    return { families: [] };
  }

  for (const family of entries) {
    if (isJunkFamily(family)) continue;
    const familyPath = join(questionsDir, family);
    const st = await stat(familyPath).catch(() => null);
    if (!st?.isDirectory()) continue;

    const slugs = await readdir(familyPath).catch(() => []);
    const questions: MOMQuestion[] = [];
    for (const slug of slugs) {
      const slugPath = join(familyPath, slug);
      const slugStat = await stat(slugPath).catch(() => null);
      if (!slugStat?.isDirectory()) continue;
      const files = await readdir(slugPath).catch(() => []);
      const hasPhp = files.some((f) => f.endsWith('.php'));
      if (!hasPhp) continue;
      const hasManifest = files.includes('manifest.json');
      questions.push({ slug, path: slugPath, hasManifest });
    }

    if (questions.length === 0) continue;
    questions.sort((a, b) => a.slug.localeCompare(b.slug));
    families.push({ name: family, count: questions.length, questions });
  }

  families.sort((a, b) => a.name.localeCompare(b.name));
  return { families };
}

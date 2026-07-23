/**
 * Verifies the island boundary rule: no file inside one island folder
 * imports from a *different* island folder. Self-imports (`./` and `../`)
 * are allowed; cross-island imports are not.
 *
 * Reads src/ at test time and walks every .ts / .svelte file. Skips
 * node_modules and .worktrees. Svelte files are scanned for the
 * `from '...'` pattern that appears in `<script>` blocks.
 */
import { describe, it, expect } from 'vitest';
import { readFileSync, readdirSync, statSync } from 'node:fs';
import { join, relative } from 'node:path';

const INTEGRATIONS_ROOT = join(process.cwd(), 'src', 'integrations');
const ISLANDS = ['gradebook', 'mom', 'ogre'];
const IMPORT_RE = /from\s+['"]([^'"]+)['"]/g;
const EXTENSIONS = new Set(['.ts', '.svelte', '.js']);

function* walk(dir: string): Generator<string> {
  for (const entry of readdirSync(dir)) {
    const full = join(dir, entry);
    const st = statSync(full);
    if (st.isDirectory()) {
      if (entry === 'node_modules' || entry === '.worktrees') continue;
      yield* walk(full);
    } else if ([...EXTENSIONS].some((e) => entry.endsWith(e))) {
      yield full;
    }
  }
}

function islandOf(file: string): string | null {
  const rel = relative(INTEGRATIONS_ROOT, file);
  if (rel.startsWith('..')) return null;
  const top = rel.split(/[\\/]/)[0];
  if (ISLANDS.includes(top)) return top;
  return null; // _shared/ or top-level
}

describe('island boundary', () => {
  it('contains no cross-island imports', () => {
    const violations: string[] = [];
    for (const file of walk(INTEGRATIONS_ROOT)) {
      const here = islandOf(file);
      if (!here) continue;
      const text = readFileSync(file, 'utf-8');
      let m: RegExpExecArray | null;
      while ((m = IMPORT_RE.exec(text))) {
        const spec = m[1];
        if (!spec.startsWith('.')) continue;
        // Resolve relative path
        const dir = file.replace(/[\\/][^\\/]+$/, '');
        const target = join(dir, spec);
        const there = islandOf(target);
        if (there && there !== here) {
          violations.push(
            `${relative(process.cwd(), file)} -> ${spec} (imports from island "${there}")`,
          );
        }
      }
    }
    expect(violations, violations.join('\n')).toEqual([]);
  });
});

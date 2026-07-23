/**
 * Resolves the location of the gradebook playwright scripts and the default
 * output directory. The scripts live outside this app (in the gradebook
 * project) because they need the `playwright` npm package and a persistent
 * Chrome profile — neither of which belongs in a Tauri app.
 *
 * Resolution order, for scriptsDir:
 *   1. Caller-provided override (from Tauri config or a Skills page)
 *   2. GRADEBOOK_SCRIPTS_DIR env var
 *   3. ./scripts/ relative to this file (useful in tests + a default for
 *      developers who want to vendor a copy)
 *
 * Resolution order for outDir:
 *   1. Caller-provided override
 *   2. GRADEBOOK_OUT_DIR env var
 *   3. The user's Documents/GitHub/gradebook folder (most common case)
 */
import { join } from 'node:path';
import { homedir } from 'node:os';

export interface GradebookConfig {
  scriptsDir?: string;
  outDir?: string;
}

export function resolveScriptsDir(cfg: GradebookConfig): string {
  return (
    cfg.scriptsDir ??
    process.env.GRADEBOOK_SCRIPTS_DIR ??
    join(new URL('.', import.meta.url).pathname, 'scripts')
  );
}

export function resolveDefaultOutDir(cfg: GradebookConfig): string {
  return (
    cfg.outDir ??
    process.env.GRADEBOOK_OUT_DIR ??
    join(homedir(), 'Documents', 'GitHub', 'gradebook')
  );
}

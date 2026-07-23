import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { mkdtemp, readFile, rm, stat, mkdir, writeFile } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { createDraft } from './draft';

let momRoot = '';
let draftsDir = '';

beforeEach(async () => {
  momRoot = await mkdtemp(join(tmpdir(), 'mom-test-'));
  draftsDir = await mkdtemp(join(tmpdir(), 'mom-drafts-'));
  // Create a family/template skeleton: <root>/questions/frq/basics/q1-test.php
  const dir = join(momRoot, 'questions', 'frq', 'basics');
  await mkdir(dir, { recursive: true });
  await writeFile(join(dir, 'q1-test.php'), '<?php\n$anstypes = "num";\n$questiontext = "Original template";\n');
});

afterEach(async () => {
  await rm(momRoot, { recursive: true, force: true });
  await rm(draftsDir, { recursive: true, force: true });
});

describe('createDraft', () => {
  it('copies the template into the drafts dir and returns its path', async () => {
    const result = await createDraft('frq', {
      momRoot,
      draftsDir,
      templatePath: 'frq/basics/q1-test.php',
      slug: 'q99-test',
    });
    expect(result.draftPath).toContain('q99-test.php');
    const st = await stat(result.draftPath);
    expect(st.isFile()).toBe(true);
    const contents = await readFile(result.draftPath, 'utf-8');
    expect(contents).toContain('Original template');
  });

  it('does not touch the original template', async () => {
    await createDraft('frq', {
      momRoot,
      draftsDir,
      templatePath: 'frq/basics/q1-test.php',
      slug: 'q99-test',
    });
    const original = await readFile(join(momRoot, 'questions', 'frq', 'basics', 'q1-test.php'), 'utf-8');
    expect(original).toBe('<?php\n$anstypes = "num";\n$questiontext = "Original template";\n');
  });

  it('uses the family as a subdirectory under drafts', async () => {
    const result = await createDraft('frq', {
      momRoot,
      draftsDir,
      templatePath: 'frq/basics/q1-test.php',
      slug: 'q99-test',
    });
    expect(result.draftPath).toContain(join(draftsDir, 'frq'));
  });

  it('rejects when the source template is missing', async () => {
    await expect(
      createDraft('frq', {
        momRoot,
        draftsDir,
        templatePath: 'frq/basics/nope.php',
        slug: 'q99-test',
      }),
    ).rejects.toThrow(/template not found/);
  });

  it('rejects slugs that try to escape the drafts dir', async () => {
    await expect(
      createDraft('frq', {
        momRoot,
        draftsDir,
        templatePath: 'frq/basics/q1-test.php',
        slug: '../etc/passwd',
      }),
    ).rejects.toThrow(/invalid slug/);
  });
});

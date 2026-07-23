import { describe, it, expect } from 'vitest';
import { resolveScriptsDir, resolveDefaultOutDir } from './config';

describe('resolveScriptsDir', () => {
  it('returns the override when one is provided', () => {
    expect(resolveScriptsDir({ scriptsDir: '/custom/scripts' })).toBe('/custom/scripts');
  });

  it('returns the env var when no override and GRADEBOOK_SCRIPTS_DIR is set', () => {
    const prev = process.env.GRADEBOOK_SCRIPTS_DIR;
    process.env.GRADEBOOK_SCRIPTS_DIR = '/env/scripts';
    try {
      expect(resolveScriptsDir({})).toBe('/env/scripts');
    } finally {
      if (prev === undefined) delete process.env.GRADEBOOK_SCRIPTS_DIR;
      else process.env.GRADEBOOK_SCRIPTS_DIR = prev;
    }
  });

  it('falls back to a relative default (./scripts/) when nothing is configured', () => {
    const prev = process.env.GRADEBOOK_SCRIPTS_DIR;
    delete process.env.GRADEBOOK_SCRIPTS_DIR;
    try {
      const dir = resolveScriptsDir({});
      expect(dir.endsWith('scripts')).toBe(true);
    } finally {
      if (prev !== undefined) process.env.GRADEBOOK_SCRIPTS_DIR = prev;
    }
  });
});

describe('resolveDefaultOutDir', () => {
  it('returns the override when provided', () => {
    expect(resolveDefaultOutDir({ outDir: '/tmp/out' })).toBe('/tmp/out');
  });

  it('falls back to GRADEBOOK_OUT_DIR or a default', () => {
    const prev = process.env.GRADEBOOK_OUT_DIR;
    process.env.GRADEBOOK_OUT_DIR = '/env/out';
    try {
      expect(resolveDefaultOutDir({})).toBe('/env/out');
    } finally {
      if (prev === undefined) delete process.env.GRADEBOOK_OUT_DIR;
      else process.env.GRADEBOOK_OUT_DIR = prev;
    }
  });
});

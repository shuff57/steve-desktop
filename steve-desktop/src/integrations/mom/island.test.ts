import { describe, it, expect } from 'vitest';
import { momIsland, type MomMethods } from './index';

describe('momIsland', () => {
  it('exposes a mom island with the right id and label', () => {
    expect(momIsland.id).toBe('mom');
    expect(momIsland.label).toBe('MOM');
    expect(momIsland.enabled).toBe(true);
  });

  it('declares the phase-2 + phase-3 methods on the island surface', () => {
    const m = momIsland.methods as MomMethods;
    expect(typeof m.browse).toBe('function');
    expect(typeof m.getQuestion).toBe('function');
    expect(typeof m.getFamily).toBe('function');
    expect(typeof m.listBooks).toBe('function');
    expect(typeof m.getDefaultRoot).toBe('function');
    expect(typeof m.createDraft).toBe('function');
    expect(typeof m.upload).toBe('function');
  });
});

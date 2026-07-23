import { describe, it, expect } from 'vitest';
import { ogreIsland } from './index';

describe('ogreIsland', () => {
  it('exposes an ogre island with the right id and label', () => {
    expect(ogreIsland.id).toBe('ogre');
    expect(ogreIsland.label).toBe('OGRE');
    expect(ogreIsland.enabled).toBe(true);
  });
});

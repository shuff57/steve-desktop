import { describe, it, expect } from 'vitest';
import { momIsland } from './index';

describe('momIsland', () => {
  it('exposes a mom island with the right id and label', () => {
    expect(momIsland.id).toBe('mom');
    expect(momIsland.label).toBe('MOM');
    expect(momIsland.enabled).toBe(true);
  });
});

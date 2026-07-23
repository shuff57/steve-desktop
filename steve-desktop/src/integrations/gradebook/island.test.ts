import { describe, it, expect } from 'vitest';
import { gradebookIsland } from './index';

describe('gradebookIsland', () => {
  it('exposes a gradebook island with the right id and label', () => {
    expect(gradebookIsland.id).toBe('gradebook');
    expect(gradebookIsland.label).toBe('Gradebook');
    expect(gradebookIsland.enabled).toBe(true);
  });
});

import { describe, it, expect } from 'vitest';
import { gradebookIsland, type GradebookMethods } from './index';

describe('gradebookIsland', () => {
  it('exposes a gradebook island with the right id and label', () => {
    expect(gradebookIsland.id).toBe('gradebook');
    expect(gradebookIsland.label).toBe('Gradebook');
    expect(gradebookIsland.enabled).toBe(true);
  });

  it('exposes runFloorScores as a function', () => {
    const m = gradebookIsland.methods as GradebookMethods;
    expect(typeof m.runFloorScores).toBe('function');
  });

  it('exposes runScrapeQids as a function', () => {
    const m = gradebookIsland.methods as GradebookMethods;
    expect(typeof m.runScrapeQids).toBe('function');
  });
});

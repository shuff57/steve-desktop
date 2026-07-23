import { describe, it, expect } from 'vitest';
import { defineIsland } from './island';

describe('defineIsland', () => {
  it('produces an island with id, label, and enabled defaulting to true', () => {
    const i = defineIsland({
      id: 'mom',
      label: 'MOM',
      methods: { browse: async () => [] },
    });
    expect(i.id).toBe('mom');
    expect(i.label).toBe('MOM');
    expect(i.enabled).toBe(true);
  });

  it('honors an explicit enabled: false', () => {
    const i = defineIsland({
      id: 'gradebook',
      label: 'Gradebook',
      enabled: false,
      methods: {},
    });
    expect(i.enabled).toBe(false);
  });

  it('passes methods through by reference', () => {
    const browse = async () => ['q1', 'q2'];
    const i = defineIsland({ id: 'ogre', label: 'OGRE', methods: { browse } });
    expect(i.methods.browse).toBe(browse);
  });
});

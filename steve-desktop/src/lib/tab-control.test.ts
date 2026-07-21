import { describe, expect, it } from 'vitest';
import { tabMarker, markerScript } from './tab-control';

describe('tabMarker', () => {
  it('prefixes the tab id', () => {
    expect(tabMarker('abc-123')).toBe('steve-tab-abc-123');
  });

  it('is stable for the same id', () => {
    expect(tabMarker('x')).toBe(tabMarker('x'));
  });
});

describe('markerScript', () => {
  it('embeds the marker as a JSON string literal', () => {
    const script = markerScript('abc-123');
    expect(script).toContain(JSON.stringify(tabMarker('abc-123')));
  });

  it('sets window.name idempotently inside a try/catch', () => {
    const script = markerScript('t1');
    expect(script).toContain('window.name=');
    expect(script).toContain('try{');
    expect(script).toContain('catch(e){}');
  });

  it('is a self-contained IIFE', () => {
    const script = markerScript('t1');
    expect(script.trim().startsWith('(function(){')).toBe(true);
    expect(script.trim().endsWith('})();')).toBe(true);
  });
});

import { describe, expect, it } from 'vitest';
import { mayAct, tabMarker, markerScript } from './tab-control';

describe('mayAct — tab session ownership', () => {
  it('legacy caller (no session id) may act on anything', () => {
    expect(mayAct(null).ok).toBe(true);
    expect(mayAct('sess-a').ok).toBe(true);
  });

  it('a session may act on its own tab', () => {
    expect(mayAct('sess-a', 'sess-a').ok).toBe(true);
  });

  it("a session may NOT act on another session's tab", () => {
    const v = mayAct('sess-a', 'sess-b');
    expect(v.ok).toBe(false);
    expect(v.reason).toContain('another agent session');
  });

  it('a session may NOT grab an unowned (manual) tab', () => {
    const v = mayAct(null, 'sess-b');
    expect(v.ok).toBe(false);
    expect(v.reason).toContain('newTab');
  });
});

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

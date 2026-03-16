import { describe, expect, it } from 'vitest';
import { calculateWebviewBounds } from './webview-layout';

describe('calculateWebviewBounds', () => {
  it('returns correct bounds with sidebar and panel', () => {
    const bounds = calculateWebviewBounds({
      sidebarWidth: 250,
      navBarHeight: 40,
      panelWidth: 400,
      windowWidth: 1280,
      windowHeight: 900,
    });

    expect(bounds.x).toBe(250);
    expect(bounds.y).toBe(40);
    expect(bounds.width).toBe(630);
    expect(bounds.height).toBe(860);
  });

  it('clamps negative width/height to 0', () => {
    const bounds = calculateWebviewBounds({
      sidebarWidth: 900,
      navBarHeight: 40,
      panelWidth: 400,
      windowWidth: 800,
      windowHeight: 20,
    });

    expect(bounds.width).toBe(0);
    expect(bounds.height).toBe(0);
  });

  it('supports extra top offset', () => {
    const bounds = calculateWebviewBounds({
      sidebarWidth: 60,
      navBarHeight: 50,
      panelWidth: 300,
      windowWidth: 1280,
      windowHeight: 720,
      extraTopOffset: 40,
    });

    expect(bounds).toEqual({
      x: 60,
      y: 90,
      width: 920,
      height: 630,
    });
  });
});

import { describe, expect, test } from 'vitest';
import { readFileSync } from 'fs';
import { AGENT_COLORS, AGENT_SWEEP, withAlpha } from './agent-visual';
import { overlayUpdateScript } from './page-agent-overlay';

describe('agent-visual — one palette, three surfaces', () => {
  test('the in-page overlay renders the shared palette, not its own copy', () => {
    const css = overlayUpdateScript({ state: 'thinking', text: '', history: [], task: '' });
    const cssFlat = css.replace(/\s/g, '');
    expect(css).toContain(AGENT_COLORS.thinking);
    expect(css).toContain(AGENT_COLORS.executing);
    expect(css).toContain(AGENT_COLORS.completed);
    expect(css).toContain(AGENT_COLORS.error);
    // Compare whitespace-free on both sides: the palette carries "rgb(57, 182, 255)"
    // while the generated CSS may or may not keep the spaces.
    for (const stop of AGENT_SWEEP) expect(cssFlat).toContain(stop.replace(/\s/g, ''));
  });

  test('the Svelte surfaces use the same status colours as the overlays', () => {
    // ActionShell is app CSS and cannot import this module, so it repeats the
    // literals. This test is the thing that keeps the copy honest — if someone
    // retunes a status colour in one place, it fails here rather than quietly
    // leaving the two agent surfaces mismatched.
    const flat = (c: string) => c.replace(/\s/g, '');
    const read = (f: string) => readFileSync(f, 'utf8').replace(/\s/g, '');

    const shell = read('src/components/shell/ActionShell.svelte');
    for (const key of ['thinking', 'executing', 'awaiting', 'completed', 'error'] as const) {
      expect(shell, `ActionShell is missing the shared ${key} colour`).toContain(flat(AGENT_COLORS[key]));
    }
    // There used to be a second agent surface here (the browser's pull-down PageAgentBar), which
    // is why this test guards a shared palette at all. It is gone: the page agent is reached
    // through the sidebar agent as page_task, so ActionShell is the only surface left to keep
    // honest. Add any new one here rather than letting it pick its own colours.

    // The header sweep is the SAME animation as the pill's, so it needs all four
    // stops. It shipped with two (blue/purple) while the pill swept four, which
    // made one motion read as two different effects on two surfaces.
    for (const stop of AGENT_SWEEP) {
      expect(shell, `ActionShell's header sweep is missing sweep stop ${stop}`).toContain(flat(stop));
    }
  });

  test('the session accent rings the pill, so concurrent runs differ', () => {
    const a = overlayUpdateScript({ state: 'thinking', text: '', history: [], task: '', accent: 'rgb(1, 2, 3)' });
    const b = overlayUpdateScript({ state: 'thinking', text: '', history: [], task: '', accent: 'rgb(9, 9, 9)' });
    expect(a).not.toBe(b);
    expect(a).toContain('rgba(1, 2, 3, 0.55)');
  });

  test('withAlpha tints an rgb colour and leaves anything else alone', () => {
    expect(withAlpha('rgb(1, 2, 3)', 0.5)).toBe('rgba(1, 2, 3, 0.5)');
    expect(withAlpha('#34d399', 0.5)).toBe('#34d399');
  });
});

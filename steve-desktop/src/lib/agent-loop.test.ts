import { describe, expect, it, vi, beforeEach } from 'vitest';
import type { AgentState, BrowserAction, InteractiveElement } from './agent-types';

// vi.mock factories are hoisted above the module body, so the mocks they close over have
// to be hoisted too — plain top-level consts throw "cannot access before initialization".
const { mockCaptureInteractiveDom, mockFormatDomForPrompt, mockCaptureWebviewScreenshot, mockSendAgentRequest, mockEvalScript } =
  vi.hoisted(() => ({
    mockCaptureInteractiveDom: vi.fn(),
    mockFormatDomForPrompt: vi.fn(),
    mockCaptureWebviewScreenshot: vi.fn(),
    mockSendAgentRequest: vi.fn(),
    mockEvalScript: vi.fn(),
  }));

// Only stub what reaches the browser. buildRefActionScript and findFuzzyMatch stay real
// so the ref plumbing is exercised end to end rather than asserted against a stub.
vi.mock('./agent-dom', async (importOriginal) => ({
  ...(await importOriginal<typeof import('./agent-dom')>()),
  captureInteractiveDom: mockCaptureInteractiveDom,
  formatDomForPrompt: mockFormatDomForPrompt,
}));

vi.mock('./browser', () => ({
  captureWebviewScreenshot: mockCaptureWebviewScreenshot,
  evalScript: mockEvalScript,
  getActiveTabId: vi.fn(() => 'tab-1'),
  navigateEmbedded: vi.fn(),
}));

vi.mock('./agent-api', () => ({
  sendAgentRequest: mockSendAgentRequest,
  forgetAgentSession: vi.fn(),
}));

import { createAgentController } from './agent-loop';

function element(overrides: Partial<InteractiveElement> = {}): InteractiveElement {
  return { ref: 'e1', tag: 'label', text: 'A. Report it', disabled: false, visible: true, ...overrides };
}

/**
 * The scripts evalScript was asked to run, action-first. Each turn also captures page
 * text through evalScript, so indexing the raw call list by position is not stable.
 */
function actionScripts(): string[] {
  return mockEvalScript.mock.calls
    .map((call) => call[0] as string)
    .filter((script) => script.includes('__steveRefs') || script.includes('querySelector'));
}

/** Runs one action, then lets the loop finish. */
async function runOnce(action: { action: string; params: Record<string, unknown> }) {
  const controller = createAgentController();
  const executed: BrowserAction[] = [];
  const results: Array<{ action: BrowserAction; result: { success: boolean; error?: string } }> = [];
  const errors: string[] = [];

  controller.on('executing', ({ action: a }) => executed.push(a));
  controller.on('result', (payload) => results.push(payload as (typeof results)[number]));
  controller.on('error', ({ message }) => errors.push(message));

  mockSendAgentRequest
    .mockResolvedValueOnce({ ...action, reasoning: 'step' })
    .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'complete' });

  await controller.start({ mode: 'auto', initialMessage: 'go' });
  return { executed, results, errors };
}

describe('agent-loop', () => {
  beforeEach(() => {
    // reset, not clear: tests that end the loop early leave unconsumed
    // mockResolvedValueOnce entries, and clearAllMocks does not drain that queue.
    vi.resetAllMocks();
    mockCaptureInteractiveDom.mockResolvedValue([]);
    mockFormatDomForPrompt.mockReturnValue('No interactive elements found.');
    mockCaptureWebviewScreenshot.mockResolvedValue(undefined);
    mockEvalScript.mockResolvedValue('ok');
  });

  it('starts in idle state', () => {
    expect(createAgentController().getState()).toBe('idle');
  });

  it('treats action "none" as a successful done, using reasoning as the message', async () => {
    const controller = createAgentController();
    const done: string[] = [];
    const errors: string[] = [];
    controller.on('done', ({ message }) => done.push(message));
    controller.on('error', ({ message }) => errors.push(message));

    mockSendAgentRequest.mockResolvedValue({ action: 'none', params: {}, reasoning: 'task is complete' });

    await controller.start({ mode: 'auto', initialMessage: 'finish task' });

    expect(errors).toEqual([]);
    expect(done).toEqual(['task is complete']);
    expect(controller.getState()).toBe('done');
  });

  it('moves to thinking when started', async () => {
    const controller = createAgentController();
    const states: AgentState[] = [];
    controller.on('thinking', () => states.push('thinking'));

    mockSendAgentRequest.mockResolvedValue({
      action: 'done',
      params: { success: true, message: 'finished' },
      reasoning: 'complete',
    });

    await controller.start({ mode: 'auto', initialMessage: 'finish task' });

    expect(states).toContain('thinking');
  });

  it('executes a ref-targeted click through the registry', async () => {
    const { executed, results } = await runOnce({ action: 'click', params: { ref: 'e6' } });

    expect(executed[0]).toEqual({ type: 'click', ref: 'e6' });
    expect(results[0].result.success).toBe(true);

    // Resolves via the page-side registry, never querySelector.
    const script = actionScripts()[0];
    expect(script).toContain('__steveRefs');
    expect(script).toContain('[6]');
    expect(script).not.toContain('querySelector');
  });

  it('surfaces a stale ref and does not fuzzy-retry it', async () => {
    mockEvalScript.mockResolvedValue('stale');
    mockCaptureInteractiveDom.mockResolvedValue([element({ ref: 'e9' })]);

    const { results } = await runOnce({ action: 'click', params: { ref: 'e6' } });

    expect(results[0].result.success).toBe(false);
    expect(results[0].result.error).toMatch(/stale/i);
    // A fuzzy retry would issue a second action script; a stale ref must just report and
    // let the next turn re-capture. (Capture count can't tell: the loop captures per turn.)
    expect(actionScripts()).toHaveLength(1);
  });

  it('reports a missing registry rather than clicking blindly', async () => {
    mockEvalScript.mockResolvedValue('norefs');

    const { results } = await runOnce({ action: 'click', params: { ref: 'e6' } });

    expect(results[0].result.success).toBe(false);
    expect(results[0].result.error).toMatch(/registry/i);
  });

  it('rejects a click that carries neither ref nor selector', async () => {
    const { executed, errors } = await runOnce({ action: 'click', params: {} });

    expect(executed).toHaveLength(0);
    expect(errors[0]).toMatch(/invalid agent action/i);
  });

  it('still honours a selector for stored profile replay', async () => {
    mockEvalScript.mockResolvedValue('true');

    const { executed } = await runOnce({ action: 'click', params: { selector: '#start' } });

    expect(executed[0]).toEqual({ type: 'click', selector: '#start' });
    expect(actionScripts()[0]).toContain('querySelector');
  });

  it('fills by ref', async () => {
    const { executed, results } = await runOnce({ action: 'fill', params: { ref: 'e3', value: 'hello' } });

    expect(executed[0]).toEqual({ type: 'fill', ref: 'e3', value: 'hello' });
    expect(results[0].result.success).toBe(true);
    expect(actionScripts()[0]).toContain('__steveRefs');
  });
});

import { describe, expect, it, vi, beforeEach } from 'vitest';
import type { AgentState, BrowserAction } from './agent-types';

const {
  mockCaptureInteractiveDom,
  mockFormatDomForPrompt,
  mockCaptureWebviewScreenshot,
  mockSendAgentRequest,
  mockIsConnected,
  mockCaptureMergedTree,
} = vi.hoisted(() => ({
  mockCaptureInteractiveDom: vi.fn(),
  mockFormatDomForPrompt: vi.fn(),
  mockCaptureWebviewScreenshot: vi.fn(),
  mockSendAgentRequest: vi.fn(),
  mockIsConnected: vi.fn(),
  mockCaptureMergedTree: vi.fn(),
}));

vi.mock('./agent-dom', () => ({
  captureInteractiveDom: mockCaptureInteractiveDom,
  formatDomForPrompt: mockFormatDomForPrompt,
}));

vi.mock('./browser', () => ({
  captureWebviewScreenshot: mockCaptureWebviewScreenshot,
  evalScript: vi.fn(),
  getActiveTabId: vi.fn(() => 'tab-1'),
  navigateEmbedded: vi.fn(),
}));

vi.mock('./agent-api', () => ({
  sendAgentRequest: mockSendAgentRequest,
}));

vi.mock('./cdp-client', () => ({ cdp: {} }));
vi.mock('./cdp-actions', () => ({ isConnected: mockIsConnected }));
vi.mock('./merged-tree', () => ({ captureMergedTree: mockCaptureMergedTree }));
// redact-tree and selector-resolve are pure — left real so the redaction is genuinely exercised.

import { createAgentController } from './agent-loop';
import type { SnapshotResult } from './dom-snapshot-types';

describe('agent-loop', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    mockCaptureInteractiveDom.mockResolvedValue([]);
    mockFormatDomForPrompt.mockReturnValue('No interactive elements found.');
    mockCaptureWebviewScreenshot.mockResolvedValue(undefined);
    mockIsConnected.mockReturnValue(false); // default: injected-JS fallback path
  });

  it('starts in idle state', () => {
    const controller = createAgentController();
    expect(controller.getState()).toBe('idle');
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

  it('executes browser action in auto mode', async () => {
    const controller = createAgentController();
    const executed: BrowserAction[] = [];

    controller.on('executing', ({ action }) => executed.push(action));

    mockSendAgentRequest
      .mockResolvedValueOnce({
        action: 'click',
        params: { selector: '#start' },
        reasoning: 'click start button',
      })
      .mockResolvedValueOnce({
        action: 'done',
        params: { success: true, message: 'done' },
        reasoning: 'task complete',
      });

    await controller.start({ mode: 'auto', initialMessage: 'start video' });

    expect(executed).toHaveLength(1);
    expect(executed[0]).toEqual({ type: 'click', selector: '#start' });
  });

  it('redacts PII out of the merged-tree DOM and rehydrates a token locally before typing', async () => {
    mockIsConnected.mockReturnValue(true);
    // A roster cell carrying PII (data slot) plus the form input (chrome — kept).
    const snapshot: SnapshotResult = {
      nodes: [
        { tag: 'td', depth: 1, priority: 'high', text: 'Jane Doe', attrs: { 'data-field': 'studentName' } },
        { tag: 'input', depth: 1, priority: 'critical', text: '', attrs: { id: 'name', 'aria-label': 'Student Name' } },
      ],
      meta: { totalVisited: 2, nodesIncluded: 2, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
    };
    mockCaptureMergedTree.mockResolvedValue({ snapshot, merged: [] });

    const controller = createAgentController();
    const executed: BrowserAction[] = [];
    controller.on('executing', ({ action }) => executed.push(action));

    // The model reads the redacted cell as a token and tries to type it into the form.
    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'fill', params: { selector: '#name', value: '⟦D1⟧' }, reasoning: 'copy roster name' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'enter the student' });

    // Outbound DOM must carry the token, never the raw name.
    const sentDom = mockSendAgentRequest.mock.calls[0][0].dom as string;
    expect(sentDom).toContain('⟦D1⟧');
    expect(sentDom).not.toContain('Jane Doe');

    // The token is restored on-device before it's typed into the page.
    expect(executed[0]).toEqual({ type: 'fill', selector: '#name', value: 'Jane Doe' });
  });
});

import { describe, expect, it, vi, beforeEach } from 'vitest';
import type { AgentState, BrowserAction } from './agent-types';

const mockCaptureInteractiveDom = vi.fn();
const mockFormatDomForPrompt = vi.fn();
const mockCaptureWebviewScreenshot = vi.fn();
const mockSendAgentRequest = vi.fn();

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

import { createAgentController } from './agent-loop';

describe('agent-loop', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    mockCaptureInteractiveDom.mockResolvedValue([]);
    mockFormatDomForPrompt.mockReturnValue('No interactive elements found.');
    mockCaptureWebviewScreenshot.mockResolvedValue(undefined);
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
});

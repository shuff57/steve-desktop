import { describe, expect, it, vi, beforeEach } from 'vitest';
import type { AgentState, BrowserAction } from './agent-types';

const {
  mockCaptureInteractiveDom,
  mockFormatDomForPrompt,
  mockCaptureWebviewScreenshot,
  mockSendAgentRequest,
  mockIsConnected,
  mockCaptureMergedTree,
  mockEvalScript,
  mockGetEmbeddedUrl,
} = vi.hoisted(() => ({
  mockCaptureInteractiveDom: vi.fn(),
  mockFormatDomForPrompt: vi.fn(),
  mockCaptureWebviewScreenshot: vi.fn(),
  mockSendAgentRequest: vi.fn(),
  mockIsConnected: vi.fn(),
  mockCaptureMergedTree: vi.fn(),
  mockEvalScript: vi.fn(),
  mockGetEmbeddedUrl: vi.fn(),
}));

vi.mock('./agent-dom', () => ({
  captureInteractiveDom: mockCaptureInteractiveDom,
  formatDomForPrompt: mockFormatDomForPrompt,
}));

vi.mock('./browser', () => ({
  captureWebviewScreenshot: mockCaptureWebviewScreenshot,
  evalScript: mockEvalScript,
  getActiveTabId: vi.fn(() => 'tab-1'),
  getEmbeddedUrl: mockGetEmbeddedUrl,
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
    mockEvalScript.mockResolvedValue('true');
    mockGetEmbeddedUrl.mockResolvedValue('');
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

  it('read→paste moves a value on-device without ever exposing it to the model', async () => {
    // read returns the value over evalScript (on-device); paste writes it back.
    mockEvalScript
      .mockResolvedValueOnce(JSON.stringify({ f: true, v: 'parent@example.com' })) // read
      .mockResolvedValueOnce('true'); // paste

    const controller = createAgentController();
    const results: Array<{ ok: boolean; data?: unknown }> = [];
    controller.on('result', ({ result }) => results.push({ ok: result.success, data: result.data }));

    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'read', params: { selector: '#lblPEM', into: 'p1' }, reasoning: 'grab email' })
      .mockResolvedValueOnce({ action: 'paste', params: { selector: '#to', from: 'p1' }, reasoning: 'fill recipient' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'sent' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'email the parent' });

    // read reported only the length back to the loop — never the value.
    expect(results[0]).toEqual({ ok: true, data: { stored: 'p1', length: 'parent@example.com'.length } });

    // paste wrote the real value into the page (on-device evalScript), proving the slot survived.
    const pasteScript = mockEvalScript.mock.calls[1][0] as string;
    expect(pasteScript).toContain('parent@example.com');

    // The email NEVER appears in anything sent to the model across all turns.
    for (const call of mockSendAgentRequest.mock.calls) {
      expect(JSON.stringify(call[0])).not.toContain('parent@example.com');
    }
  });

  it('dry run verifies a destructive click (Send) but never performs it', async () => {
    // The probe (no .click()) reports the button exists; the click script must never run.
    mockEvalScript.mockImplementation(async (script: string) =>
      script.includes('.click()') ? 'true' : JSON.stringify({ f: true, t: 'Send' }),
    );

    const controller = createAgentController();
    const results: Array<{ data?: unknown }> = [];
    controller.on('result', ({ result }) => results.push({ data: result.data }));

    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'click', params: { selector: 'role=button[name="Send"]' }, reasoning: 'send it' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'dry run: email the parent', dryRun: true });

    const performedClick = mockEvalScript.mock.calls.some(([s]) => typeof s === 'string' && s.includes('.click()'));
    expect(performedClick).toBe(false); // Send was NOT clicked
    expect(results[0].data).toMatchObject({ dryRun: true, blocked: true });
  });

  it('dry run still performs a non-destructive click (New mail)', async () => {
    mockEvalScript.mockImplementation(async (script: string) =>
      script.includes('.click()') ? 'true' : JSON.stringify({ f: true, t: 'New mail' }),
    );

    const controller = createAgentController();
    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'click', params: { selector: 'role=button[name="New mail"]' }, reasoning: 'compose' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'dry run', dryRun: true });

    const performedClick = mockEvalScript.mock.calls.some(([s]) => typeof s === 'string' && s.includes('.click()'));
    expect(performedClick).toBe(true); // New mail WAS clicked
  });

  it('login fills saved credentials on-device and never exposes them to the model', async () => {
    mockGetEmbeddedUrl.mockResolvedValue('https://chicousd.aeries.net/teacher/Login.aspx');
    mockEvalScript.mockResolvedValue('filled');
    const credentials = [{ id: 1, site_name: 'Aeries', username: 'teacher1', password: 's3cret!', url_pattern: 'aeries.net' }];

    const controller = createAgentController();
    const results: Array<{ data?: unknown }> = [];
    controller.on('result', ({ result }) => results.push({ data: result.data }));

    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'login', params: {}, reasoning: 'log in' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'log in to aeries', credentials });

    // Credentials reached the page via evalScript (on-device)…
    expect(mockEvalScript.mock.calls.some(([s]) => typeof s === 'string' && s.includes('s3cret!'))).toBe(true);
    // …but NEVER appear in anything sent to the model.
    for (const call of mockSendAgentRequest.mock.calls) {
      const blob = JSON.stringify(call[0]);
      expect(blob).not.toContain('s3cret!');
      expect(blob).not.toContain('teacher1');
    }
    // The result reports only the site name.
    expect(results[0].data).toEqual({ site: 'Aeries' });
  });

  it('pauses for a Duo push after login and resumes when approved', async () => {
    mockGetEmbeddedUrl.mockResolvedValue('https://chicousd.aeries.net/teacher/Login.aspx');
    // Login script returns 'filled'; the Duo-detection script (contains "duosecurity.com") returns 'y'.
    mockEvalScript.mockImplementation(async (s: string) =>
      typeof s === 'string' && s.includes('duosecurity.com') ? 'y' : 'filled',
    );
    const credentials = [{ id: 1, site_name: 'Aeries', username: 'teacher1', password: 's3cret!', url_pattern: 'aeries.net' }];

    const controller = createAgentController();
    const needs: Array<{ site?: string }> = [];
    const dones: boolean[] = [];
    controller.on('needsApproval', (p) => {
      needs.push(p);
      // Simulate the human tapping Approve on the next tick (fallback button path).
      setTimeout(() => controller.continueApproval(), 0);
    });
    controller.on('approvalDone', ({ approved }) => dones.push(approved));

    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'login', params: {}, reasoning: 'log in' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'log in to aeries', credentials });

    expect(needs).toEqual([{ site: 'Aeries' }]); // paused, naming the site
    expect(dones).toEqual([true]); // resumed after approval
  });

  it('dry run login verifies a credential match but does not submit it', async () => {
    mockGetEmbeddedUrl.mockResolvedValue('https://chicousd.aeries.net/teacher/Login.aspx');
    const credentials = [{ id: 1, site_name: 'Aeries', username: 'teacher1', password: 's3cret!', url_pattern: 'aeries.net' }];

    const controller = createAgentController();
    const results: Array<{ data?: unknown }> = [];
    controller.on('result', ({ result }) => results.push({ data: result.data }));

    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'login', params: {}, reasoning: 'log in' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    await controller.start({ mode: 'auto', initialMessage: 'dry run', credentials, dryRun: true });

    expect(mockEvalScript.mock.calls.some(([s]) => typeof s === 'string' && s.includes('s3cret!'))).toBe(false);
    expect(results[0].data).toMatchObject({ dryRun: true, would: 'login', site: 'Aeries' });
  });

  it('auto-detects dry run from the goal text', async () => {
    mockEvalScript.mockImplementation(async (script: string) =>
      script.includes('.click()') ? 'true' : JSON.stringify({ f: true, t: 'Submit' }),
    );
    const controller = createAgentController();
    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'click', params: { selector: '#submit' }, reasoning: 'submit' })
      .mockResolvedValueOnce({ action: 'done', params: { success: true, message: 'done' }, reasoning: 'done' });

    // No dryRun flag — only the words "dry run" in the goal.
    await controller.start({ mode: 'auto', initialMessage: 'Dry-run the submit flow' });

    const performedClick = mockEvalScript.mock.calls.some(([s]) => typeof s === 'string' && s.includes('.click()'));
    expect(performedClick).toBe(false);
  });
});

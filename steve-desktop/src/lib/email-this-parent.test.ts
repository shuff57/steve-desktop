import { describe, expect, it, vi, beforeEach } from 'vitest';
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import type { BrowserAction } from './agent-types';
import { parseSkillMarkdown } from './skill-parser';

// Mock the browser/model edges exactly like agent-loop.test.ts so the loop runs headless.
const { mockSendAgentRequest, mockIsConnected, mockEvalScript } = vi.hoisted(() => ({
  mockSendAgentRequest: vi.fn(),
  mockIsConnected: vi.fn(),
  mockEvalScript: vi.fn(),
}));

vi.mock('./agent-dom', () => ({
  captureInteractiveDom: vi.fn(async () => []),
  formatDomForPrompt: vi.fn(() => 'No interactive elements found.'),
}));
vi.mock('./browser', () => ({
  captureWebviewScreenshot: vi.fn(async () => undefined),
  evalScript: mockEvalScript,
  getActiveTabId: vi.fn(() => 'tab-1'),
  getEmbeddedUrl: vi.fn(async () => ''),
  navigateEmbedded: vi.fn(async () => undefined),
}));
vi.mock('./agent-api', () => ({ sendAgentRequest: mockSendAgentRequest }));
vi.mock('./cdp-client', () => ({ cdp: {} }));
vi.mock('./cdp-actions', () => ({ isConnected: mockIsConnected }));
vi.mock('./merged-tree', () => ({ captureMergedTree: vi.fn() }));

import { createAgentController } from './agent-loop';

const SKILL = readFileSync(
  path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../../../skills/email-this-parent/SKILL.md'),
  'utf-8',
);

describe('email-this-parent skill', () => {
  it('is a valid app skill with the safety invariants', () => {
    const parsed = parseSkillMarkdown(SKILL);
    expect(parsed.name).toBe('Email this parent');
    expect(parsed.urlPatterns?.some((p) => /aeries/.test(p))).toBe(true);
    expect(parsed.urlPatterns?.some((p) => /outlook/.test(p))).toBe(true);
    // The address must move by read→paste, with the verified Aeries selector.
    expect(SKILL).toContain('#ctl00_MainContent_subStuTopEmail_lblPEM');
    expect(SKILL).toMatch(/read.*p1/);
    expect(SKILL).toMatch(/paste.*p1/i);
    // Approval gate + no-fill-recipient rule must be present.
    expect(SKILL).toMatch(/STOP\. Do not send/);
    expect(SKILL).toMatch(/Never.*fill.*recipient/i);
  });

  it('runs the flow on-device: the parent email never reaches the model, and it stops before Send', async () => {
    mockIsConnected.mockReturnValue(false); // skip merged-tree; use injected-DOM path
    // read resolves to the lblPEM value on-device; everything else "succeeds".
    const PARENT = 'parent@example.com';
    mockEvalScript.mockImplementation(async (script: string) =>
      script.includes('lblPEM') ? JSON.stringify({ f: true, v: PARENT }) : 'true',
    );

    const controller = createAgentController();
    const executed: BrowserAction[] = [];
    const results: Array<{ data?: unknown }> = [];
    controller.on('executing', ({ action }) => executed.push(action));
    controller.on('result', ({ result }) => results.push({ data: result.data }));

    // The model drives the skill's sequence, then STOPS with a text response (no Send).
    mockSendAgentRequest
      .mockResolvedValueOnce({ action: 'read', params: { selector: '#ctl00_MainContent_subStuTopEmail_lblPEM', into: 'p1' }, reasoning: 'copy parent email' })
      .mockResolvedValueOnce({ action: 'navigate', params: { url: 'https://outlook.office.com/mail/' }, reasoning: 'open outlook' })
      .mockResolvedValueOnce({ action: 'click', params: { selector: 'role=button[name="New mail"]' }, reasoning: 'compose' })
      .mockResolvedValueOnce({ action: 'paste', params: { selector: '[aria-label="To"]', from: 'p1' }, reasoning: 'recipient' })
      .mockResolvedValueOnce({ action: 'keyboard', params: { key: 'Enter' }, reasoning: 'commit pill' })
      .mockResolvedValueOnce({ action: 'fill', params: { selector: 'input[aria-label="Subject"]', value: 'Math update' }, reasoning: 'subject' })
      .mockResolvedValueOnce({ text: 'Drafted the message to the parent. Review it and tell me to send.' });

    await controller.start({ mode: 'auto', initialMessage: "email this student's parent: math update" });

    // 1) read reported only a length to the loop — never the value.
    expect(results[0].data).toEqual({ stored: 'p1', length: PARENT.length });

    // 2) The address reached the page only via paste (on-device evalScript), never via a fill.
    const fills = executed.filter((a) => a.type === 'fill') as Array<{ value: string }>;
    expect(fills.every((f) => !f.value.includes(PARENT))).toBe(true);
    const pasteWrote = mockEvalScript.mock.calls.some(([s]) => typeof s === 'string' && s.includes(PARENT));
    expect(pasteWrote).toBe(true);

    // 3) The email NEVER appears in anything sent to the model.
    for (const call of mockSendAgentRequest.mock.calls) {
      expect(JSON.stringify(call[0])).not.toContain(PARENT);
    }

    // 4) The flow stopped before sending — no Send click was executed.
    expect(executed.some((a) => a.type === 'click' && /Send/i.test((a as { selector: string }).selector))).toBe(false);
  });
});

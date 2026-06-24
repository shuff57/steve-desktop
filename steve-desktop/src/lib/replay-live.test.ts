import { describe, it, expect, vi, beforeEach } from 'vitest';
import type { Workflow } from './types/site-profile';

const { mockEval, mockPwClick, mockPwType, mockIsConnected, mockCaptureMergedTree, mockNavigate } = vi.hoisted(() => ({
  mockEval: vi.fn(),
  mockPwClick: vi.fn(),
  mockPwType: vi.fn(),
  mockIsConnected: vi.fn(),
  mockCaptureMergedTree: vi.fn(),
  mockNavigate: vi.fn(),
}));

vi.mock('./cdp-actions', () => ({
  evalScript: mockEval,
  pwClick: mockPwClick,
  pwType: mockPwType,
  isConnected: mockIsConnected,
}));
vi.mock('./cdp-client', () => ({ cdp: {} }));
vi.mock('./merged-tree', () => ({ captureMergedTree: mockCaptureMergedTree }));
vi.mock('./browser', () => ({ navigateEmbedded: mockNavigate, getActiveTabId: () => 'tab-1' }));
// replay, model-gate, redact-tree, selector-resolve are real — the wiring is genuinely exercised.

import { sidecarTransport, BrowserPageDriver, replayLive } from './replay-live';

describe('sidecarTransport', () => {
  beforeEach(() => vi.clearAllMocks());

  it('posts the prompt to the sidecar and returns its content', async () => {
    const fetchMock = vi.fn(async (_url: string, _init: { body: string }) => ({
      ok: true,
      json: async () => ({ content: '#submit' }),
    }));
    vi.stubGlobal('fetch', fetchMock);

    const reply = await sidecarTransport({ provider: 'ollama-local' })('find the submit button');

    expect(reply).toBe('#submit');
    const [url, init] = fetchMock.mock.calls[0];
    expect(url).toBe('http://localhost:3456/api/agent');
    expect(JSON.parse(init.body).messages[0].content).toBe('find the submit button');
    vi.unstubAllGlobals();
  });

  it('throws on a non-200 so the healer falls through to a skip', async () => {
    vi.stubGlobal('fetch', vi.fn(async () => ({ ok: false, status: 503 })));
    await expect(sidecarTransport()('x')).rejects.toThrow(/HTTP 503/);
    vi.unstubAllGlobals();
  });
});

describe('BrowserPageDriver.act — action mapping', () => {
  beforeEach(() => vi.clearAllMocks());

  it('maps click to pwClick and fill to pwType(selector, value, clear)', async () => {
    mockPwClick.mockResolvedValue({ success: true });
    mockPwType.mockResolvedValue({ success: true });
    const d = new BrowserPageDriver();

    expect(await d.act({ action: 'click' }, '#go')).toBe(true);
    expect(mockPwClick).toHaveBeenCalledWith('#go');

    expect(await d.act({ action: 'fill', value: 'Jane Doe' }, '#name')).toBe(true);
    expect(mockPwType).toHaveBeenCalledWith('#name', 'Jane Doe', true);
  });

  it('returns false for an unsupported action instead of guessing', async () => {
    expect(await new BrowserPageDriver().act({ action: 'bogus' as never }, '#x')).toBe(false);
  });
});

describe('replayLive — wires the live driver + Tier-3 model heal', () => {
  beforeEach(() => vi.clearAllMocks());

  it('refuses to run without a CDP connection', async () => {
    mockIsConnected.mockReturnValue(false);
    await expect(replayLive({ name: 'wf', steps: [] })).rejects.toThrow(/CDP connection/);
  });

  it('drives replayWorkflow through the real browser driver on the happy path', async () => {
    mockIsConnected.mockReturnValue(true);
    mockEval.mockResolvedValue({ success: true, data: true }); // every exists() check passes
    mockPwClick.mockResolvedValue({ success: true });

    const wf: Workflow = { name: 'Open', steps: [{ action: 'click', selector: '#start', description: 'start' }] };
    const summary = await replayLive(wf);

    expect(summary.results[0].status).toBe('done');
    expect(mockPwClick).toHaveBeenCalledWith('#start');
  });

  it('escalates to the sidecar (Tier 3) when local tiers miss, then acts on the relocated selector', async () => {
    mockIsConnected.mockReturnValue(true);
    // Stale recorded selector: exists() is false for it, true for the relocated one.
    mockEval.mockImplementation(async (expr: string) =>
      expr.includes('#apply') ? { success: true, data: true } : { success: true, data: false },
    );
    mockPwClick.mockResolvedValue({ success: true });
    // Tier-2 fuzzy must miss: snapshot label shares no tokens with the step description.
    mockCaptureMergedTree.mockResolvedValue({
      snapshot: {
        nodes: [{ tag: 'button', depth: 1, priority: 'critical', text: 'Submit Application', attrs: { id: 'apply' } }],
        meta: { totalVisited: 1, nodesIncluded: 1, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
      },
      merged: [],
    });
    // Sidecar relocates to #apply.
    vi.stubGlobal('fetch', vi.fn(async () => ({ ok: true, json: async () => ({ content: '#apply' }) })));

    const wf: Workflow = { name: 'Finish', steps: [{ action: 'click', selector: '#oldBtn', description: 'finalize enrollment' }] };
    const summary = await replayLive(wf);

    expect(summary.results[0].status).toBe('recovered');
    expect(summary.results[0].selectorUsed).toBe('#apply');
    expect(summary.results[0].detail).toMatch(/model|escalat/i);
    expect(mockPwClick).toHaveBeenCalledWith('#apply');
    vi.unstubAllGlobals();
  });
});

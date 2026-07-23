import { describe, it, expect, vi, beforeEach } from 'vitest';
import { uploadToMOM } from './upload';

interface MockCDP {
  connect: ReturnType<typeof vi.fn>;
  send: ReturnType<typeof vi.fn>;
  isConnected: ReturnType<typeof vi.fn>;
}

function makeMockCDP(): MockCDP {
  return {
    connect: vi.fn().mockResolvedValue(true),
    send: vi.fn().mockResolvedValue({ result: { value: true } }),
    isConnected: vi.fn().mockReturnValue(true),
  };
}

describe('uploadToMOM', () => {
  let cdp: MockCDP;

  beforeEach(() => {
    cdp = makeMockCDP();
  });

  it('connects, navigates to modquestion.php, and stops before submit', async () => {
    const ok = await uploadToMOM({
      cdpClient: cdp as unknown as Parameters<typeof uploadToMOM>[0]['cdpClient'],
      cid: 306621,
      controls: 'num',
      questionText: 'Find the mean of 1, 2, 3.',
    });
    expect(ok).toBe(true);

    // First: connect (no CID in URL until navigate; just verify a call).
    expect(cdp.connect).toHaveBeenCalled();
    // Page.navigate to the modquestion URL with cid.
    const navigateCall = cdp.send.mock.calls.find((c) => c[0] === 'Page.navigate');
    expect(navigateCall).toBeDefined();
    const params = navigateCall![1] as { url: string };
    expect(params.url).toContain('modquestion.php');
    expect(params.url).toContain('cid=306621');
  });

  it('sets the question text via Runtime.evaluate after navigate', async () => {
    await uploadToMOM({
      cdpClient: cdp as unknown as Parameters<typeof uploadToMOM>[0]['cdpClient'],
      cid: 1,
      controls: 'num',
      questionText: 'Hello world',
    });
    // The order matters: navigate first, then DOM fills. We assert the relative order
    // by the index of each call.
    const calls = cdp.send.mock.calls;
    const navigateIdx = calls.findIndex((c) => c[0] === 'Page.navigate');
    const qtextIdx = calls.findIndex(
      (c) => c[0] === 'Runtime.evaluate' && (c[1] as { expression?: string })?.expression?.includes('#qtext'),
    );
    const controlsIdx = calls.findIndex(
      (c) => c[0] === 'Runtime.evaluate' && (c[1] as { expression?: string })?.expression?.includes('#controls'),
    );
    expect(navigateIdx).toBeGreaterThanOrEqual(0);
    expect(qtextIdx).toBeGreaterThan(navigateIdx);
    expect(controlsIdx).toBeGreaterThan(navigateIdx);
  });

  it('emits the question text contents into the #qtext expression', async () => {
    await uploadToMOM({
      cdpClient: cdp as unknown as Parameters<typeof uploadToMOM>[0]['cdpClient'],
      cid: 1,
      controls: 'num',
      questionText: 'Compute 2+2',
    });
    const qtextCall = cdp.send.mock.calls.find(
      (c) => c[0] === 'Runtime.evaluate' && (c[1] as { expression?: string })?.expression?.includes('#qtext'),
    );
    expect(qtextCall).toBeDefined();
    const expr = (qtextCall![1] as { expression: string }).expression;
    expect(expr).toContain('Compute 2+2');
  });

  it('emits the controls value into the #controls expression', async () => {
    await uploadToMOM({
      cdpClient: cdp as unknown as Parameters<typeof uploadToMOM>[0]['cdpClient'],
      cid: 1,
      controls: 'choices',
      questionText: 'q',
    });
    const ctrlCall = cdp.send.mock.calls.find(
      (c) => c[0] === 'Runtime.evaluate' && (c[1] as { expression?: string })?.expression?.includes('#controls'),
    );
    expect(ctrlCall).toBeDefined();
    const expr = (ctrlCall![1] as { expression: string }).expression;
    expect(expr).toContain('choices');
  });

  it('does NOT click submit or any final-action element', async () => {
    await uploadToMOM({
      cdpClient: cdp as unknown as Parameters<typeof uploadToMOM>[0]['cdpClient'],
      cid: 1,
      controls: 'num',
      questionText: 'q',
    });
    const allExpressions = cdp.send.mock.calls
      .map((c) => ((c[1] as { expression?: string })?.expression ?? '').toLowerCase())
      .join(' ');
    expect(allExpressions).not.toContain('submit');
    expect(allExpressions).not.toContain('click()');
  });

  it('returns false when the CDP client cannot connect', async () => {
    cdp.connect.mockResolvedValue(false);
    const ok = await uploadToMOM({
      cdpClient: cdp as unknown as Parameters<typeof uploadToMOM>[0]['cdpClient'],
      cid: 1,
      controls: 'num',
      questionText: 'q',
    });
    expect(ok).toBe(false);
  });
});

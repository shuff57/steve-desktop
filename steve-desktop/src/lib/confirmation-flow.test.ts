import { createConfirmationFlow } from './confirmation-flow';

describe('confirmation-flow', () => {
  const selectors = { playButton: 'role=button[name="Play"]', muteButton: 'role=button[name="Mute"]' };
  const validation = {
    playButton: { matchCount: 1, sampleText: 'Play' },
    muteButton: { matchCount: 1, sampleText: 'Mute' },
  };
  const required = ['playButton', 'muteButton'];

  it('starts in confirming phase', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    expect(flow.phase).toBe('confirming');
  });

  it('shows first step on init', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    const state = flow.getState();
    expect(state?.stepIndex).toBe(0);
    expect(state?.totalSteps).toBe(2);
    expect(state?.selector).toBe('role=button[name="Play"]');
  });

  it('advances to next step on accept', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    flow.accept();
    expect(flow.getState()?.stepIndex).toBe(1);
  });

  it('completes after all steps accepted', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    flow.accept();
    flow.accept();
    expect(flow.phase).toBe('complete');
    expect(flow.getState()).toBeNull();
  });

  it('cancels on cancel()', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    flow.cancel();
    expect(flow.phase).toBe('cancelled');
  });

  it('allows refine with new selector', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    flow.refine('#custom-play-btn');
    const confirmed = flow.getConfirmedSelectors();
    expect(confirmed['playButton']).toBe('#custom-play-btn');
  });

  it('supports back navigation', () => {
    const flow = createConfirmationFlow(selectors, validation, required);
    flow.accept();
    flow.back();
    expect(flow.getState()?.stepIndex).toBe(0);
  });
});

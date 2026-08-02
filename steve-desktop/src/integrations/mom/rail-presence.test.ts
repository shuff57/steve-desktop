import { describe, it, expect } from 'vitest';
import { toHistoryCards, railStatusFor, HISTORY_WINDOW, type RailLine } from './rail-presence';

const line = (role: RailLine['role'], text: string): RailLine => ({ role, text });

describe('toHistoryCards', () => {
  it('sets no meta, because the shell PRINTS meta', () => {
    // Regression: meta was used to make the shell's {#each} key unique when a retry loop
    // repeated step text. meta is a visible field, so every card rendered a stray index
    // ("using Read 4"). The shell keys by position now; nothing belongs in meta here.
    const cards = toHistoryCards([line('step', 'Rendering…'), line('step', 'Rendering…')]);
    expect(cards.every((c) => !('meta' in c) || !c.meta)).toBe(true);
  });

  it('shows the last N lines of a longer log', () => {
    const many = Array.from({ length: 14 }, (_, i) => line('step', `step ${i}`));
    const cards = toHistoryCards(many);
    expect(cards).toHaveLength(HISTORY_WINDOW);
    expect(cards[0].text).toBe('step 4');
    expect(cards[cards.length - 1].text).toBe('step 13');
  });

  it('maps each role to a card type the shell has styling for', () => {
    const cards = toHistoryCards([
      line('user', 'write me a question'),
      line('agent', 'here it is'),
      line('step', 'rendering'),
      line('ok', 'saved'),
      line('error', 'boom'),
    ]);
    expect(cards.map((c) => c.type)).toEqual(['input', 'output', 'observation', 'success', 'error']);
  });

  it('truncates a long line rather than letting it run the card', () => {
    const [card] = toHistoryCards([line('agent', 'x'.repeat(400))]);
    expect(card.text).toHaveLength(160);
    expect(card.text.endsWith('…')).toBe(true);
  });

  it('returns nothing for an empty log, so the shell hides the strip', () => {
    expect(toHistoryCards([])).toEqual([]);
  });
});

describe('railStatusFor', () => {
  const base = { planning: false, revising: false, writing: false, failed: false, finished: false };

  it('is idle when nothing is happening', () => {
    expect(railStatusFor(base)).toEqual({ status: 'idle', text: '' });
  });

  it('thinks while planning and executes while writing', () => {
    expect(railStatusFor({ ...base, planning: true }).status).toBe('thinking');
    expect(railStatusFor({ ...base, writing: true }).status).toBe('executing');
  });

  it('prefers the live step line to the generic writing text', () => {
    expect(railStatusFor({ ...base, writing: true, slug: 'q1', lastStep: 'Repairing attempt 2' }).text).toBe(
      'Repairing attempt 2',
    );
    expect(railStatusFor({ ...base, writing: true, slug: 'q1' }).text).toBe('Writing q1…');
  });

  it('names what it is revising', () => {
    expect(railStatusFor({ ...base, revising: true, label: 'stats/q7' }).text).toBe('Revising stats/q7…');
  });

  it('reports failure ahead of completion', () => {
    // A run that failed after producing a file must not read as Done.
    expect(railStatusFor({ ...base, failed: true, finished: true }).status).toBe('error');
  });
});

export type ConfirmationPhase = 'pending' | 'confirming' | 'complete' | 'cancelled';

export interface ConfirmationStepState {
  key: string;
  selector: string | null;
  matchCount: number;
  sampleText: string;
  stepIndex: number;
  totalSteps: number;
}

export interface ConfirmationFlow {
  accept(): void;
  refine(newSelector: string): void;
  cancel(): void;
  back(): void;
  getState(): ConfirmationStepState | null;
  getConfirmedSelectors(): Record<string, string>;
  readonly phase: ConfirmationPhase;
}

export function createConfirmationFlow(
  selectors: Record<string, string | null>,
  validation: Record<string, { matchCount: number; sampleText: string }>,
  requiredKeys: string[],
): ConfirmationFlow {
  const steps = requiredKeys.filter((key) => selectors[key] != null);

  let phase: ConfirmationPhase = steps.length > 0 ? 'confirming' : 'complete';
  let currentIndex = 0;
  const confirmed: Record<string, string> = {};

  const isTerminal = () => phase === 'complete' || phase === 'cancelled';

  const advance = () => {
    currentIndex += 1;
    if (currentIndex >= steps.length) {
      phase = 'complete';
    }
  };

  const buildState = (): ConfirmationStepState => {
    const key = steps[currentIndex];
    const details = validation[key];

    return {
      key,
      selector: selectors[key] ?? null,
      matchCount: details?.matchCount ?? 0,
      sampleText: details?.sampleText ?? '',
      stepIndex: currentIndex,
      totalSteps: steps.length,
    };
  };

  return {
    get phase() {
      return phase;
    },

    accept() {
      if (isTerminal()) return;
      const key = steps[currentIndex];
      const selector = selectors[key];
      if (selector != null) {
        confirmed[key] = selector;
      }
      advance();
    },

    refine(newSelector: string) {
      if (isTerminal()) return;
      confirmed[steps[currentIndex]] = newSelector;
      advance();
    },

    cancel() {
      if (isTerminal()) return;
      phase = 'cancelled';
    },

    back() {
      if (isTerminal()) return;
      if (currentIndex === 0) return;

      currentIndex -= 1;
      delete confirmed[steps[currentIndex]];
    },

    getState() {
      if (isTerminal()) return null;
      return buildState();
    },

    getConfirmedSelectors() {
      return { ...confirmed };
    },
  };
}

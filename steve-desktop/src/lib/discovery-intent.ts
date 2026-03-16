export type IntentMode = 'form' | 'chat' | 'example';

export type RequiredSelectorName =
  | 'videoPlayer'
  | 'interactiveForm'
  | 'navigationControl'
  | 'quizElement';

export interface DiscoveryHints {
  url?: string;
  knownSelectors?: Partial<Record<string, string>>;
  pageDescription?: string;
  extraContext?: string;
  generalizedSelectors?: GeneralizedSelector[];
  requiredSelectors?: RequiredSelectorName[];
  promptAddendum?: string;
  navigationMode?: 'batch' | 'sequential';
  hasVideoPlayer?: boolean;
  hasInteractiveForms?: boolean;
  hasNavigation?: boolean;
  hasQuizElements?: boolean;
}

export interface FormModeInput {
  pageDescription?: string;
  hasVideoPlayer: boolean;
  hasInteractiveForms: boolean;
  hasNavigation: boolean;
  hasQuizElements: boolean;
  notes?: string;
  knownSelectors?: string;
}

export interface ChatMessage {
  role: 'user' | 'assistant';
  content: string;
  timestamp: string;
}

export const MAX_CHAT_TURNS = 5;

export interface ChatDiscoveryState {
  messages: ChatMessage[];
  turnCount: number;
  isComplete: boolean;
  hints: DiscoveryHints;
}

export const DISCOVERY_CHAT_SYSTEM_PROMPT =
  'You are a page structure discovery assistant. Your ONLY purpose is to help ' +
  'identify CSS selectors and page structure for an unknown page.\n\n' +
  'Ask focused questions to understand:\n' +
  '1. What kind of page this is and what the user is trying to do\n' +
  '2. Whether the page contains a video player, interactive forms, quizzes, or navigation controls\n' +
  '3. Whether the workflow is sequential or all content is visible at once\n' +
  '4. Any CSS selectors the user already knows\n\n' +
  'Keep responses short and focused. After gathering enough information, ' +
  "summarize what you've learned.\n" +
  'Do NOT help with general questions — only page structure discovery.';

export interface ExampleSelection {
  elementType: RequiredSelectorName | 'other';
  capturedSelector: string;
  text: string;
  tag: string;
  attrs: Record<string, string>;
}

export interface GeneralizedSelector {
  elementType: ExampleSelection['elementType'];
  selector: string;
  exampleCount: number;
  confidence: number;
}

export interface ExampleDiscoveryResult {
  selections: ExampleSelection[];
  generalizedSelectors: GeneralizedSelector[];
  hints: DiscoveryHints;
}

function stripPositionalPseudos(selector: string): string {
  return selector
    .replace(/:nth-child\([^)]*\)/g, '')
    .replace(/:nth-of-type\([^)]*\)/g, '')
    .replace(/:first-child/g, '')
    .replace(/:last-child/g, '')
    .replace(/:first-of-type/g, '')
    .replace(/:last-of-type/g, '')
    .replace(/\s{2,}/g, ' ')
    .trim();
}

function findCommonAttr(examples: ExampleSelection[], attr: string): string | undefined {
  if (examples.length === 0) return undefined;

  const first = examples[0]?.attrs[attr];
  if (first === undefined) return undefined;

  return examples.every((example) => example.attrs[attr] === first) ? first : undefined;
}

function generalizeFromGroup(examples: ExampleSelection[]): string {
  if (examples.length === 0) return '';

  if (examples.length === 1) {
    return stripPositionalPseudos(examples[0]!.capturedSelector);
  }

  const tags = examples.map((example) => example.tag);
  const commonTag = tags.every((tag) => tag === tags[0]) ? tags[0]! : '';

  const classSets = examples.map((example) => {
    const rawClasses = example.attrs.class ?? '';
    return new Set(rawClasses.split(/\s+/).filter(Boolean));
  });

  const commonClasses =
    classSets[0] && classSets[0].size > 0
      ? [...classSets[0]].filter((className) => classSets.every((set) => set.has(className)))
      : [];

  const commonType = findCommonAttr(examples, 'type');

  let selector = commonTag;
  for (const className of commonClasses) {
    selector += `.${className}`;
  }

  if (commonType) {
    selector += `[type="${commonType}"]`;
  }

  if (!selector) {
    selector = stripPositionalPseudos(examples[0]!.capturedSelector);
  }

  return selector;
}

function buildFlagDescriptions(input: FormModeInput): string[] {
  const descriptions: string[] = [];

  if (input.pageDescription?.trim()) descriptions.push(input.pageDescription.trim());
  if (input.hasVideoPlayer) descriptions.push('The page includes a video player');
  if (input.hasInteractiveForms) descriptions.push('The page includes interactive forms');
  if (input.hasNavigation) descriptions.push('The page includes navigation controls between sections or pages');
  if (input.hasQuizElements) descriptions.push('The page includes quiz or assessment elements');

  return descriptions;
}

function parseKnownSelectors(knownSelectors?: string): Partial<Record<string, string>> | undefined {
  if (!knownSelectors?.trim()) return undefined;

  try {
    const parsed = JSON.parse(knownSelectors);
    return typeof parsed === 'object' && parsed !== null ? (parsed as Record<string, string>) : undefined;
  } catch {
    return undefined;
  }
}

function detectRequiredSelectors(text: string): RequiredSelectorName[] {
  const required = new Set<RequiredSelectorName>();

  if (/\bvideo\b|\bplayer\b|embedded video|watch/i.test(text)) required.add('videoPlayer');
  if (/\bform\b|fill out|input fields?|submit|interactive/i.test(text)) required.add('interactiveForm');
  if (/\bquiz\b|assessment|question|answer choices?|multiple choice/i.test(text)) required.add('quizElement');
  if (/\bnext\b|\bprevious\b|continue|navigation|one after another|sequential|paginated/i.test(text)) {
    required.add('navigationControl');
  }

  return [...required];
}

export function parseFormIntent(input: FormModeInput): DiscoveryHints {
  const descriptions = buildFlagDescriptions(input);
  const requiredSelectors: RequiredSelectorName[] = [];

  if (input.hasVideoPlayer) requiredSelectors.push('videoPlayer');
  if (input.hasInteractiveForms) requiredSelectors.push('interactiveForm');
  if (input.hasNavigation) requiredSelectors.push('navigationControl');
  if (input.hasQuizElements) requiredSelectors.push('quizElement');

  const parsedKnownSelectors = parseKnownSelectors(input.knownSelectors);
  const extraContextParts = [input.notes?.trim()];
  if (input.knownSelectors?.trim() && !parsedKnownSelectors) {
    extraContextParts.push(input.knownSelectors.trim());
  }

  return {
    pageDescription: descriptions.length > 0 ? descriptions.join('. ') + '.' : undefined,
    extraContext: extraContextParts.filter(Boolean).join('\n') || undefined,
    promptAddendum:
      descriptions.length > 0 || input.notes?.trim()
        ? [
            descriptions.length > 0 ? `User indicates: ${descriptions.join('; ')}.` : undefined,
            input.notes?.trim() ? `Additional notes: ${input.notes.trim()}` : undefined,
          ]
            .filter(Boolean)
            .join(' ')
        : undefined,
    knownSelectors: parsedKnownSelectors,
    requiredSelectors: requiredSelectors.length > 0 ? requiredSelectors : undefined,
    hasVideoPlayer: input.hasVideoPlayer,
    hasInteractiveForms: input.hasInteractiveForms,
    hasNavigation: input.hasNavigation,
    hasQuizElements: input.hasQuizElements,
  };
}

export function parseChatIntent(messages: ChatMessage[]): DiscoveryHints {
  const userMessages = messages.filter((message) => message.role === 'user').map((message) => message.content);

  if (userMessages.length === 0) {
    return {};
  }

  const allText = userMessages.join('\n');
  const requiredSelectors = detectRequiredSelectors(allText);

  return {
    extraContext: allText.trim() || undefined,
    requiredSelectors: requiredSelectors.length > 0 ? requiredSelectors : undefined,
    navigationMode: /one\s+after\s+another|one\s+at\s+a\s+time|sequential|paginated/i.test(allText)
      ? 'sequential'
      : /all\s+(videos|content|steps|lessons).*(visible|shown|at once)|batch|all at once/i.test(allText)
        ? 'batch'
        : undefined,
    hasVideoPlayer: /\bvideo\b|\bplayer\b|watch/i.test(allText) || undefined,
    hasInteractiveForms: /\bform\b|fill out|input fields?|submit/i.test(allText) || undefined,
    hasNavigation: /\bnext\b|\bprevious\b|continue|navigation|one after another|sequential|paginated/i.test(allText) || undefined,
    hasQuizElements: /\bquiz\b|assessment|question|answer choices?|multiple choice/i.test(allText) || undefined,
  };
}

export function parseExampleSelections(examples: ExampleSelection[]): GeneralizedSelector[] {
  const byType = new Map<ExampleSelection['elementType'], ExampleSelection[]>();

  for (const example of examples) {
    const group = byType.get(example.elementType) ?? [];
    group.push(example);
    byType.set(example.elementType, group);
  }

  const results: GeneralizedSelector[] = [];
  for (const [elementType, group] of byType.entries()) {
    results.push({
      elementType,
      selector: generalizeFromGroup(group),
      exampleCount: group.length,
      confidence: Math.min(group.length / 3, 1),
    });
  }

  return results;
}

export function createChatDiscoveryState(): ChatDiscoveryState {
  return { messages: [], turnCount: 0, isComplete: false, hints: {} };
}

export async function runChatDiscovery(
  state: ChatDiscoveryState,
  userMessage: string,
  sendMessage: (messages: ChatMessage[]) => Promise<string>,
): Promise<ChatDiscoveryState> {
  if (state.isComplete) return state;

  const userMsg: ChatMessage = {
    role: 'user',
    content: userMessage,
    timestamp: new Date().toISOString(),
  };
  const updatedMessages = [...state.messages, userMsg];
  const turnCount = state.turnCount + 1;

  if (turnCount >= MAX_CHAT_TURNS) {
    return {
      messages: updatedMessages,
      turnCount,
      isComplete: true,
      hints: parseChatIntent(updatedMessages),
    };
  }

  const assistantReply = await sendMessage(updatedMessages);
  const assistantMsg: ChatMessage = {
    role: 'assistant',
    content: assistantReply,
    timestamp: new Date().toISOString(),
  };
  const finalMessages = [...updatedMessages, assistantMsg];

  return {
    messages: finalMessages,
    turnCount,
    isComplete: false,
    hints: parseChatIntent(finalMessages),
  };
}

export function runExampleDiscovery(selections: ExampleSelection[]): ExampleDiscoveryResult {
  const generalizedSelectors = parseExampleSelections(selections);

  return {
    selections,
    generalizedSelectors,
    hints: { generalizedSelectors },
  };
}

export function intentToDiscoveryHints(
  mode: IntentMode,
  payload: FormModeInput | ChatMessage[] | ExampleSelection[],
): DiscoveryHints {
  switch (mode) {
    case 'form':
      return parseFormIntent(payload as FormModeInput);
    case 'chat':
      return parseChatIntent(payload as ChatMessage[]);
    case 'example': {
      const generalizedSelectors = parseExampleSelections(payload as ExampleSelection[]);
      return { generalizedSelectors };
    }
  }
}

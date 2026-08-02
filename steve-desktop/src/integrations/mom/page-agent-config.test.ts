import { describe, expect, test } from 'vitest';
import { parseQuestionFile, buildFillExpression, type QuestionSections } from './page-agent-config';

// A minimal valid question file for testing the five-marker parser
const SAMPLE_PHP = `// === NAME - DESCRIPTION: Test Question - A question for testing ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$a = 1
$b = 2

// === QUESTION TEXT ===

What is $a + $b?
$answerbox

// === ANSWER ===

$solutionguide`;

describe('page-agent-config (mom-transfer)', () => {
  test('parseQuestionFile extracts all five sections', () => {
    const sections = parseQuestionFile(SAMPLE_PHP);
    expect(sections.description).toBe('Test Question - A question for testing');
    expect(sections.qtype).toBe('choices');
    expect(sections.control).toContain('$a = 1');
    expect(sections.control).toContain('$b = 2');
    expect(sections.qtext).toContain('What is $a + $b?');
    expect(sections.qtext).toContain('$answerbox');
    expect(sections.solution).toContain('$solutionguide');
  });

  test('parseQuestionFile defaults qtype to number when marker missing', () => {
    const noQtype = `// === NAME - DESCRIPTION: Test ===
// === COMMON CONTROL ===
$x = 1
// === QUESTION TEXT ===
text
// === ANSWER ===
ans`;
    const sections = parseQuestionFile(noQtype);
    expect(sections.qtype).toBe('number');
  });

  test('parseQuestionFile handles empty sections gracefully', () => {
    const minimal = `// === NAME - DESCRIPTION: Minimal ===
// === SET QUESTION TYPE TO: num ===
// === COMMON CONTROL ===
// === QUESTION TEXT ===
// === ANSWER ===`;
    const sections = parseQuestionFile(minimal);
    expect(sections.description).toBe('Minimal');
    expect(sections.qtype).toBe('num');
    expect(sections.control).toBe('');
    expect(sections.qtext).toBe('');
    expect(sections.solution).toBe('');
  });

  test('buildFillExpression produces valid JS that JSON.stringifies sections', () => {
    const sections: QuestionSections = {
      description: 'Test',
      qtype: 'choices',
      control: '$a = 1',
      qtext: 'What is $a?',
      solution: '$solutionguide',
    };
    const expr = buildFillExpression(sections);
    // Must be an IIFE
    expect(expr).toMatch(/^\(function\(\)/);
    // Must contain the JSON data
    expect(expr).toContain('"description":"Test"');
    expect(expr).toContain('"qtype":"choices"');
    expect(expr).toContain('"control":"$a = 1"');
    // Must reference CodeMirror for control/qtext/solution
    expect(expr).toContain('CodeMirror');
    expect(expr).toContain("setValue");
    // Must set qtype hidden input
    expect(expr).toContain('[name=qtype]');
    // Must set description as textarea
    expect(expr).toContain('[name=description]');
  });

  test('buildFillExpression byte-exact: sections survive JSON round-trip', () => {
    const sections: QuestionSections = {
      description: 'Question with "quotes" and \\ backslash',
      qtype: 'choices',
      control: '$x = array("a", "b")',
      qtext: 'Pick the right `answer`',
      solution: '$solutionguide = "solution"',
    };
    const expr = buildFillExpression(sections);
    // Extract the JSON from the expression and parse it back
    const jsonMatch = expr.match(/var s = (\{[^]*?\});/);
    expect(jsonMatch).toBeTruthy();
    const parsed = JSON.parse(jsonMatch![1]);
    expect(parsed.description).toBe(sections.description);
    expect(parsed.control).toBe(sections.control);
    expect(parsed.qtext).toBe(sections.qtext);
    expect(parsed.solution).toBe(sections.solution);
  });

  test('MOM_TRANSFER_INSTRUCTIONS contains the golden rules', async () => {
    const { MOM_TRANSFER_INSTRUCTIONS } = await import('./page-agent-config');
    expect(MOM_TRANSFER_INSTRUCTIONS).toContain('fill_mom_question');
    expect(MOM_TRANSFER_INSTRUCTIONS).toContain('CodeMirror');
    expect(MOM_TRANSFER_INSTRUCTIONS).toContain('qtype');
    expect(MOM_TRANSFER_INSTRUCTIONS).toContain('CSRF');
  });

  test('buildMomTransferConfig defaults to the measured model, named tool_choice on', async () => {
    const { buildMomTransferConfig, MOM_TRANSFER_MODEL } = await import('./page-agent-config');
    const config = buildMomTransferConfig({
      cid: 334243,
      task: 'File question 1',
      baseURL: 'http://localhost:11434/v1',
      apiKey: 'NA',
    });
    expect(config.model).toBe(MOM_TRANSFER_MODEL);
    // Every model that accepted a named tool_choice passed the live bench;
    // both that refused it failed. Off is the default, opt-in to disable.
    expect(config.disableNamedToolChoice).toBe(false);
    expect(config.customTools?.fill_mom_question).toBeDefined();
    expect(config.stepDelay).toBe(0.6);
    expect(config.maxSteps).toBe(40);
  });

  test('the model chain is ranked as measured, with the two failures dropped', async () => {
    const { MOM_TRANSFER_MODELS, MOM_TRANSFER_MODEL } = await import('./page-agent-config');
    expect([...MOM_TRANSFER_MODELS]).toEqual([
      'gemma4:cloud',
      'deepseek-v4-flash:cloud',
      'minimax-m2.7:cloud',
      'qwen3.5:cloud',
      'nemotron-3-nano:30b-cloud',
    ]);
    expect(MOM_TRANSFER_MODEL).toBe('gemma4:cloud');
    // Both failed the live bench outright — they must not be in the chain.
    expect([...MOM_TRANSFER_MODELS]).not.toContain('gpt-oss:20b-cloud');
    expect([...MOM_TRANSFER_MODELS]).not.toContain('minimax-m3:cloud');
  });

  test('only an endpoint refusal advances the chain, never a wrong answer', async () => {
    const { isTransportFailure } = await import('./page-agent-config');
    expect(isTransportFailure('Error: LLM API error 410: retired')).toBe(true);
    expect(isTransportFailure('Error: LLM returned no tool call')).toBe(true);
    // A real attempt that got it wrong must NOT be retried on the next model,
    // or one bad fill gets filed once per model in the chain.
    expect(isTransportFailure('Step count exceeded maximum limit')).toBe(false);
    expect(isTransportFailure('The question rendered with Eeek!')).toBe(false);
  });

  test('an endpoint that rejects a named tool_choice can still opt out', async () => {
    const { buildMomTransferConfig } = await import('./page-agent-config');
    const config = buildMomTransferConfig({
      cid: 334243,
      task: 'File question 1',
      baseURL: 'http://localhost:11434/v1',
      model: 'some-other-model',
      disableNamedToolChoice: true,
    });
    expect(config.model).toBe('some-other-model');
    expect(config.disableNamedToolChoice).toBe(true);
  });
});
import { describe, expect, test } from 'vitest';
import { actionKeyOf, isUnproductiveOutput, isRepeatedStallAction } from './page-agent-loop';

describe('stall detection', () => {
  // These strings are what the tools actually return; the model sees the same.
  test('a failed tool result counts as no progress', () => {
    expect(isUnproductiveOutput('Element [4] not found')).toBe(true);
    expect(isUnproductiveOutput('❌ Unknown tool: 0. Valid tools: done, wait')).toBe(true);
    expect(isUnproductiveOutput('No option matching "Kiwi" in element [4]')).toBe(true);
    expect(isUnproductiveOutput('❌ input_text was called without a valid index.')).toBe(true);
  });

  test('a working tool result does not', () => {
    expect(isUnproductiveOutput('✅ Clicked element [2]')).toBe(false);
    expect(isUnproductiveOutput('✅ Filled slot 1 from disk.')).toBe(false);
    expect(isUnproductiveOutput('Task completed')).toBe(false);
  });

  test('the action key catches an exact repeat, and only an exact repeat', () => {
    const a = actionKeyOf('select_dropdown_option', { index: 4, text: 'Kiwi' });
    expect(actionKeyOf('select_dropdown_option', { index: 4, text: 'Kiwi' })).toBe(a);
    expect(actionKeyOf('select_dropdown_option', { index: 5, text: 'Kiwi' })).not.toBe(a);
    expect(actionKeyOf('click_element_by_index', { index: 4, text: 'Kiwi' })).not.toBe(a);
  });

  test('a repeated non-wait action counts as a stall', () => {
    const key = actionKeyOf('select_dropdown_option', { index: 4, text: 'Kiwi' });
    expect(isRepeatedStallAction('select_dropdown_option', key, key)).toBe(true);
  });

  test('a repeated wait or wait_for_condition never counts as a stall', () => {
    const waitKey = actionKeyOf('wait', { seconds: 5 });
    expect(isRepeatedStallAction('wait', waitKey, waitKey)).toBe(false);

    const condKey = actionKeyOf('wait_for_condition', { condition: "document.querySelector('video')?.ended" });
    expect(isRepeatedStallAction('wait_for_condition', condKey, condKey)).toBe(false);
  });

  test('a non-repeat is never a stall regardless of tool name', () => {
    expect(isRepeatedStallAction('click_element_by_index', 'a', 'b')).toBe(false);
    expect(isRepeatedStallAction('wait', 'a', 'b')).toBe(false);
  });
});

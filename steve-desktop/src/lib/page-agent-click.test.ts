/**
 * The click tool's injected script, run against a real DOM.
 *
 * A mocked cdpSend can only prove the tool was called; it cannot catch a click that succeeds and
 * changes nothing. This runs the actual injected JS in jsdom, exactly as CDP does in the webview
 * — the same idiom load-students.test.ts uses for its extraction script.
 */
import { describe, expect, test, vi } from 'vitest';
import { JSDOM } from 'jsdom';
import { clickElementByIndexTool, type ToolContext } from './page-agent-tools';

const PAGE = `<!doctype html><html><body>
  <select id="pick" name="pick">
    <option value="" data-pa-index="0">-- pick a student --</option>
    <option value="7158619" data-pa-index="1">Alvarez, Jordan</option>
    <option value="7158621" data-pa-index="2">Okonkwo, Sarah</option>
  </select>
  <button id="go" data-pa-index="3">Export CSV</button>
</body></html>`;

/** A ToolContext whose Runtime.evaluate really evaluates, in a real document. */
function domCtx(): { ctx: ToolContext; doc: Document } {
  const dom = new JSDOM(PAGE, { url: 'https://lms.example/gradebook', runScripts: 'dangerously' });
  const w = dom.window as unknown as {
    eval: (s: string) => unknown;
    document: Document;
    Element: { prototype: Record<string, unknown> };
  };
  // jsdom implements no layout, so it has no scrollIntoView. The tool calls it before every
  // click; without this the script throws and the assertion below would be about jsdom.
  w.Element.prototype.scrollIntoView = () => {};
  const ctx: ToolContext = {
    signal: new AbortController().signal,
    cdpSend: async (_method, params) => ({
      result: { value: w.eval(String((params as { expression: string }).expression)) },
    }),
    evalInPage: async () => undefined,
    navigate: async () => undefined,
    waitForLoad: async () => undefined,
  };
  return { ctx, doc: w.document };
}

describe('clicking an element that cannot be clicked', () => {
  test('clicking an <option> actually selects it', async () => {
    const { ctx, doc } = domCtx();
    const out = await clickElementByIndexTool.execute(ctx, { index: 2 });
    const select = doc.querySelector('#pick') as HTMLSelectElement;
    // The live failure this exists for: the tool reported success and the select never moved,
    // so an approved plan "selected a student" while the page still showed its placeholder.
    expect(select.value).toBe('7158621');
    expect(select.selectedIndex).toBe(2);
    expect(out).toContain('Okonkwo, Sarah');
  });

  test('it fires change, so a page listening for it reacts', async () => {
    const { ctx, doc } = domCtx();
    const select = doc.querySelector('#pick') as HTMLSelectElement;
    const seen: string[] = [];
    select.addEventListener('change', () => seen.push('change'));
    select.addEventListener('input', () => seen.push('input'));
    await clickElementByIndexTool.execute(ctx, { index: 1 });
    expect(seen).toEqual(['input', 'change']);
  });

  test('an ordinary control is still just clicked', async () => {
    const { ctx, doc } = domCtx();
    const button = doc.querySelector('#go') as HTMLButtonElement;
    let clicked = 0;
    button.addEventListener('click', () => (clicked += 1));
    const out = await clickElementByIndexTool.execute(ctx, { index: 3 });
    expect(clicked).toBe(1);
    expect(out).toContain('Clicked element [3]');
  });

  test('a missing element still reports honestly', async () => {
    const { ctx } = domCtx();
    const out = await clickElementByIndexTool.execute(ctx, { index: 99 });
    expect(out).toContain('not found');
  });
});

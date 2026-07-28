// @vitest-environment jsdom
import { describe, expect, it } from 'vitest';
import { renderSkillPreview } from './skill-parser';

/**
 * renderSkillPreview feeds four {@html} sinks (SkillCard, AutomateRunner, SiteProfiles ×2), all
 * showing model output that can echo markup from a crawled page.
 *
 * These assert on the PARSED document rather than on the HTML string: escaped text like
 * "&lt;img onerror=x&gt;" trips any naive /on\w+=/ pattern while being completely inert, so a
 * string test both fails on safe output and passes on unsafe output that spells itself unusually.
 * Parsing asks the question that matters — does a live node or handler exist?
 */
const parse = (html: string): Document => new DOMParser().parseFromString(html, 'text/html');

const DANGEROUS_TAGS = /^(SCRIPT|IFRAME|OBJECT|EMBED|SVG|STYLE|LINK|META|BASE|FORM)$/;
const URL_ATTRS = /^(href|src|action|data|formaction|srcdoc)$/i;
const BAD_SCHEME = /^\s*(javascript|data|vbscript|blob|file):/i;

/** Every live danger in a rendered document: forbidden elements, handlers, executable URLs. */
function liveDangers(html: string): string[] {
  const found: string[] = [];
  for (const el of Array.from(parse(html).querySelectorAll('*'))) {
    if (DANGEROUS_TAGS.test(el.tagName)) found.push(`<${el.tagName.toLowerCase()}>`);
    for (const attr of Array.from(el.attributes)) {
      if (/^on/i.test(attr.name)) found.push(`${el.tagName}[${attr.name}]`);
      if (URL_ATTRS.test(attr.name) && BAD_SCHEME.test(attr.value)) {
        found.push(`${el.tagName}[${attr.name}="${attr.value}"]`);
      }
    }
  }
  return found;
}

describe('renderSkillPreview — injection', () => {
  const attacks: Array<[string, string]> = [
    ['script tag', '<script>alert(1)</script>'],
    ['quoted handler', '<img src="x" onerror="alert(1)">'],
    // The old blacklist only matched handlers whose value was quoted.
    ['unquoted handler', '<img src=x onerror=alert(1)>'],
    ['svg onload, unquoted', '<svg onload=alert(1)>'],
    // The old blacklist had no rule for these tags at all.
    ['object tag', '<object data="javascript:alert(1)"></object>'],
    ['embed tag', '<embed src="javascript:alert(1)">'],
    ['iframe srcdoc', '<iframe srcdoc="<script>alert(1)</script>"></iframe>'],
    ['body onload', '<body onload=alert(1)>'],
    ['details ontoggle', '<details open ontoggle=alert(1)>'],
    ['style block', '<style>@import "evil.css";</style>'],
    ['anchor with javascript href', '<a href="javascript:alert(1)">x</a>'],
    ['form with formaction', '<form><button formaction="javascript:alert(1)">go</button></form>'],
  ];

  for (const [name, payload] of attacks) {
    it(`neutralizes ${name}`, async () => {
      const html = await renderSkillPreview(payload);
      expect(liveDangers(html)).toEqual([]);
    });
  }

  it('shows the escaped markup as visible text rather than dropping it', async () => {
    // Silently deleting content would hide what the agent actually reported.
    const html = await renderSkillPreview('<img src=x onerror=alert(1)>');
    expect(parse(html).body.textContent).toContain('<img src=x onerror=alert(1)>');
  });

  it('sends javascript: links to #', async () => {
    const html = await renderSkillPreview('[click](javascript:alert(1))');
    expect(parse(html).querySelector('a')?.getAttribute('href')).toBe('#');
  });

  it('catches javascript: regardless of case', async () => {
    const html = await renderSkillPreview('[x](JaVaScRiPt:alert(1))');
    expect(parse(html).querySelector('a')?.getAttribute('href')).toBe('#');
  });

  it('catches javascript: split by a control character', async () => {
    // Browsers strip the tab before navigating, so the scheme test must strip it too.
    const html = await renderSkillPreview('[x](java\tscript:alert(1))');
    expect(liveDangers(html)).toEqual([]);
  });

  it('catches entity-encoded javascript:', async () => {
    const html = await renderSkillPreview('[x](&#106;avascript:alert(1))');
    expect(liveDangers(html)).toEqual([]);
  });

  it('blocks data: and vbscript: URLs', async () => {
    expect(liveDangers(await renderSkillPreview('[x](data:text/html,<script>alert(1)</script>)'))).toEqual([]);
    expect(liveDangers(await renderSkillPreview('[x](vbscript:msgbox(1))'))).toEqual([]);
  });

  it('blocks javascript: on an image source', async () => {
    const html = await renderSkillPreview('![x](javascript:alert(1))');
    expect(parse(html).querySelector('img')?.getAttribute('src')).toBe('#');
  });

  it('does not let an image alt break out of its attribute', async () => {
    const html = await renderSkillPreview('![" onerror="alert(1)](https://e.com/a.png)');
    expect(liveDangers(html)).toEqual([]);
  });

  it('does not let a code fence language tag break out of the class attribute', async () => {
    const html = await renderSkillPreview('```js"><img src=x onerror=alert(1)>\ncode\n```');
    expect(liveDangers(html)).toEqual([]);
  });

  it('renders fenced code as inert text', async () => {
    const html = await renderSkillPreview('```html\n<script>alert(1)</script>\n```');
    expect(liveDangers(html)).toEqual([]);
    expect(parse(html).querySelector('pre')?.textContent).toContain('<script>alert(1)</script>');
  });
});

describe('renderSkillPreview — ordinary markdown still renders', () => {
  it('renders headings, emphasis and lists', async () => {
    const doc = parse(await renderSkillPreview('# Title\n\n**bold**\n\n- one\n- two'));
    expect(doc.querySelector('h1')?.textContent).toBe('Title');
    expect(doc.querySelector('strong')?.textContent).toBe('bold');
    expect(doc.querySelectorAll('li')).toHaveLength(2);
  });

  it('keeps safe links and images intact', async () => {
    const a = parse(await renderSkillPreview('[docs](https://example.com/a?b=1&c=2)')).querySelector('a');
    expect(a?.getAttribute('href')).toBe('https://example.com/a?b=1&c=2');
    expect(a?.textContent).toBe('docs');

    const img = parse(await renderSkillPreview('![alt](https://example.com/i.png "t")')).querySelector('img');
    expect(img?.getAttribute('src')).toBe('https://example.com/i.png');
    expect(img?.getAttribute('alt')).toBe('alt');
    expect(img?.getAttribute('title')).toBe('t');
  });

  it('allows relative, anchor and mailto targets', async () => {
    const href = async (md: string) =>
      parse(await renderSkillPreview(md)).querySelector('a')?.getAttribute('href');
    expect(await href('[a](#section)')).toBe('#section');
    expect(await href('[a](./page.md)')).toBe('./page.md');
    expect(await href('[a](mailto:x@y.com)')).toBe('mailto:x@y.com');
  });

  it('renders a realistic skill document', async () => {
    const doc = parse(
      await renderSkillPreview(
        '# Grade quiz\n\nOpens the gradebook.\n\n1. Log in\n2. Open **Quiz 3**\n\n[course](https://myopenmath.com/course.php?id=1)',
      ),
    );
    expect(doc.querySelector('h1')?.textContent).toBe('Grade quiz');
    expect(doc.querySelectorAll('ol li')).toHaveLength(2);
    expect(doc.querySelector('a')?.getAttribute('href')).toBe('https://myopenmath.com/course.php?id=1');
  });
});

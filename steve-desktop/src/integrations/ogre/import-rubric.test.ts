/**
 * The rubric extractor runs against a jsdom page built from the real authoring template
 * (O.G.R.E `.agents/authoring/references/php-patterns.md`, section 5 "FRQ scaffold"):
 * a `.rubric-container` per rubric, the student checklist without answers and the
 * instructor block carrying `span.ideal-ans` targets plus a `div.full-response-box`.
 *
 * Question content here is invented — no real course material in the repo.
 */
import { JSDOM } from 'jsdom';
import { describe, expect, it } from 'vitest';
import {
  PAGE_RUBRIC_EXTRACT_JS,
  importRubricFromPage,
  parseExtractedRubric,
  pickBlock,
  sourceIdFor,
  toChecklist,
} from './import-rubric';

const STUDENT_BLOCK = `
<div class="rubric-container">
  <details>
    <summary>Click to View Grading Checklist</summary>
    <div class="rubric-content">
      <ul>
        <li><label><input type="checkbox"> State the <b>null</b> hypothesis.</label></li>
        <li><label><input type="checkbox"> State the alternative hypothesis.</label></li>
      </ul>
    </div>
  </details>
</div>`;

const INSTRUCTOR_BLOCK = `
<div class="rubric-container">
  <details>
    <summary>Rubric &amp; Model Response</summary>
    <div class="rubric-content">
      <h4>Hypotheses (4 pts)</h4>
      <ul>
        <li>State the null hypothesis.
          <span class="ideal-ans">Target: "H0: mu = 12"</span></li>
        <li>State the alternative hypothesis.
          <span class="ideal-ans">Target: "Ha: mu &lt; 12"</span></li>
      </ul>
      <h4>Conclusion (2 pts)</h4>
      <ul>
        <li>Interpret in context.
          <span class="ideal-ans">Target: "reject H0 at alpha = 0.05"</span></li>
      </ul>
      <div class="full-response-box">Model answer: state <b>H0</b>, then compare p to alpha.</div>
    </div>
  </details>
</div>`;

function page(inner: string, url = 'https://www.myopenmath.com/assess2/?cid=99&aid=88&qid=4242'): unknown {
  const dom = new JSDOM(
    `<html><head><title>MyOpenMath — Question</title></head><body>
       <div>
         <p>Scenario: a bottling line fills 12 oz cans.</p>
         <p>Essay Prompt: Test whether the line is underfilling.</p>
         ${inner}
       </div>
     </body></html>`,
    { url, runScripts: 'dangerously' },
  );
  return dom.window.eval(PAGE_RUBRIC_EXTRACT_JS);
}

describe('rubric import', () => {
  it('prefers the instructor block and folds its targets into the criteria', () => {
    const r = parseExtractedRubric(page(STUDENT_BLOCK + INSTRUCTOR_BLOCK));

    expect(r.rubric.checklistItems?.map((c) => c.category)).toEqual(['Hypotheses', 'Conclusion']);
    expect(r.rubric.checklistItems?.map((c) => c.points)).toEqual([4, 2]);
    // Point values are the question's real total — not the 0-10 the prompt asks for.
    expect(r.rubric.maxScore).toBe(6);
    expect(r.rubric.weightMode).toBe('category');

    const first = r.rubric.checklistItems![0]!.items!;
    expect(first).toHaveLength(2);
    expect(first[0]).toContain('State the null hypothesis.');
    expect(first[0]).toContain('H0: mu = 12');

    expect(r.rubric.modelText).toContain('compare p to alpha');
    expect(r.rubric.essayPrompt).toContain('bottling line');
    expect(r.rubric.essayPrompt).toContain('underfilling');
    // The rubric text itself must not leak back into the prompt.
    expect(r.rubric.essayPrompt).not.toContain('Target:');
  });

  it('falls back to the student checklist when no instructor block exists', () => {
    const r = parseExtractedRubric(page(STUDENT_BLOCK));

    expect(r.rubric.checklistItems).toHaveLength(1);
    expect(r.rubric.checklistItems![0]!.category).toBe('Click to View Grading Checklist');
    expect(r.rubric.checklistItems![0]!.items).toEqual([
      'State the null hypothesis.',
      'State the alternative hypothesis.',
    ]);
    // No declared points anywhere, so the rubric must not invent a total.
    expect(r.rubric.maxScore).toBe(10);
    expect(r.rubric.weightMode).toBeUndefined();
  });

  it('keeps <b> inside a requirement as text, not as a category heading', () => {
    const r = parseExtractedRubric(page(STUDENT_BLOCK));
    expect(r.rubric.checklistItems).toHaveLength(1);
    expect(r.rubric.checklistItems![0]!.items![0]).toBe('State the null hypothesis.');
  });

  it('refuses a page with no checklist rather than saving an empty rubric', () => {
    expect(() => parseExtractedRubric(page('<details><summary>Hint</summary></details>'))).toThrow(
      /no grading checklist/i,
    );
    expect(() => parseExtractedRubric(undefined)).toThrow(/is a MyOpenMath question open/i);
  });

  it('will not sum a partial point total', () => {
    // One category declares points, one does not — summing would grade out of 4 when the
    // question is worth more.
    const items = toChecklist(
      [
        { heading: true, text: 'Hypotheses (4 pts)', target: '' },
        { heading: false, text: 'State H0.', target: '' },
        { heading: true, text: 'Conclusion', target: '' },
        { heading: false, text: 'Interpret.', target: '' },
      ],
      'Requirements',
    );
    expect(items.map((c) => c.points)).toEqual([4, undefined]);

    const r = parseExtractedRubric(
      page(INSTRUCTOR_BLOCK.replace('<h4>Conclusion (2 pts)</h4>', '<h4>Conclusion</h4>')),
    );
    expect(r.rubric.maxScore).toBe(10);
  });

  it('drops a heading with nothing under it', () => {
    expect(toChecklist([{ heading: true, text: 'Empty (3 pts)', target: '' }], 'Requirements')).toEqual([]);
  });

  it('gives the same source id across sessions of the same question', () => {
    const a = sourceIdFor('https://www.myopenmath.com/assess2/?cid=99&aid=88&qid=4242&sess=abc');
    const b = sourceIdFor('https://www.myopenmath.com/assess2/?cid=100&aid=88&qid=4242&sess=zzz');
    expect(a).toBe(b);
    expect(a).toContain('qid=4242');
    expect(sourceIdFor('not a url')).toBe('not a url');
  });

  it('picks the richer block when neither has targets', () => {
    const thin = { summary: 'Hint', modelText: '', hasTargets: false, nodes: [{ heading: false, text: 'a', target: '' }] };
    const fat = {
      summary: 'Checklist',
      modelText: '',
      hasTargets: false,
      nodes: [
        { heading: false, text: 'a', target: '' },
        { heading: false, text: 'b', target: '' },
      ],
    };
    expect(pickBlock([thin, fat])?.summary).toBe('Checklist');
    // A block of headings only is not a rubric.
    expect(pickBlock([{ summary: 'x', modelText: '', hasTargets: false, nodes: [{ heading: true, text: 'h', target: '' }] }])).toBeNull();
  });

  it('reads through an injected evaluator without touching the page', async () => {
    let calls = 0;
    const r = await importRubricFromPage(async (expr) => {
      calls++;
      expect(expr).toBe(PAGE_RUBRIC_EXTRACT_JS);
      return page(INSTRUCTOR_BLOCK);
    });
    expect(calls).toBe(1);
    expect(r.name).toContain('bottling line');
    expect(r.rubric.checklistItems).toHaveLength(2);
  });
});

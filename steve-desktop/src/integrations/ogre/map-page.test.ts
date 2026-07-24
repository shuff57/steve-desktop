/**
 * End-to-end for the map-a-page bridge, exercising the REAL in-page snapshot capture
 * (buildSmartWalkScript) against a jsdom gradebook, then the real derivation. Only the DB
 * write is mocked. This is the integration the unit tests can't cover: that the actual
 * smart-walk output — its attrs, depths, and node ordering — feeds deriveGradingSelectors
 * correctly, not just a hand-built snapshot.
 *
 * The page here is deliberately NOT MyOpenMath-shaped, since that's the whole point: a
 * layout steve's built-in selectors miss, that mapping has to learn from structure alone.
 */
import { JSDOM } from 'jsdom';
import { describe, expect, it, vi } from 'vitest';

// Mock only the DB write; capture and derivation run for real.
vi.mock('./db', () => ({
  saveGradingProfile: vi.fn(async () => 'ogre-site:example.edu/grade'),
}));

import { mapPageForGrading } from './map-page';
import { saveGradingProfile } from './db';

const mockSave = vi.mocked(saveGradingProfile);

/** A three-student roster in a non-MyOpenMath layout: article rows, class-named fields. */
function gradebookPage(): JSDOM {
  const row = (i: number, name: string, ans: string) => `
    <article class="submission-row" data-uid="${i}">
      <header class="submission-head">
        <span class="student-name">${name}</span>
        <span class="submission-meta">submitted</span>
      </header>
      <div class="response-body">${ans}</div>
      <div class="grade-controls">
        <input class="score-field" name="score-${1000 + i}" type="text" value="N/A" />
        <div class="feedback-box" id="fb-${1000 + i}" contenteditable="true"></div>
      </div>
    </article>`;
  return new JSDOM(
    `<html><body>
       <main class="roster">
         <h1>Assignment 3 — grading</h1>
         ${row(0, 'Lovelace, Ada', 'The IQR is 21 and the max exceeds the fence.')}
         ${row(1, 'Nakamura, Yuki', 'Max is above the upper fence, so it is an outlier.')}
         ${row(2, 'Okonkwo, Chidi', 'Compared max to fence: outlier.')}
       </main>
     </body></html>`,
    { url: 'https://example.edu/courses/7/grade', runScripts: 'dangerously' },
  );
}

describe('mapPageForGrading (real capture → derive → save)', () => {
  it('learns a non-MyOpenMath gradebook from its structure and saves it', async () => {
    const dom = gradebookPage();
    const evaluate = async (expr: string) => dom.window.eval(expr);

    const { profileId, selectors } = await mapPageForGrading(evaluate, 'https://example.edu/courses/7/grade');

    // Derived from the real smart-walk output — the repeated <article> is the row.
    expect(selectors.studentSection).toBe('article.submission-row');
    expect(selectors.studentName).toBe('span.student-name');
    expect(selectors.response).toBe('div.response-body');
    // A stable class beats a digit-suffixed name — 'input.score-field', not name^=.
    expect(selectors.scoreInput).toBe('input.score-field');
    expect(selectors.feedbackBox).toBe('div.feedback-box');

    // Saved once, keyed on the page, with exactly the derived selectors.
    expect(mockSave).toHaveBeenCalledOnce();
    expect(mockSave).toHaveBeenCalledWith('https://example.edu/courses/7/grade', selectors);
    expect(profileId).toContain('example.edu');
  });

  it('proves the learned selectors actually extract every student', async () => {
    // The real test of a derived profile: do its selectors pull the roster back out? Run
    // load-students' extractor with them against the same page.
    const dom = gradebookPage();
    const { selectors } = await mapPageForGrading(
      async (expr: string) => dom.window.eval(expr),
      'https://example.edu/courses/7/grade',
    );

    const { buildExtractJs, parseExtracted, gradeableFrom } = await import('./load-students');
    const extracted = parseExtracted(dom.window.eval(buildExtractJs(selectors)));
    const gradeable = gradeableFrom(extracted); // answered + ungraded ("N/A") by default

    expect(gradeable).toHaveLength(3);
    expect(gradeable.map((s) => s.name)).toEqual(['Lovelace, Ada', 'Nakamura, Yuki', 'Okonkwo, Chidi']);
    expect(gradeable[0]!.response).toContain('IQR is 21');
    expect(gradeable.every((s) => s.currentScore === null)).toBe(true); // "N/A" → ungraded, not 0
  });

  it('refuses a page with no repeated student layout', async () => {
    const dom = new JSDOM(
      `<html><body><main><h1>About</h1><p>Just an article.</p><form><input name="q"/></form></main></body></html>`,
      { url: 'https://example.edu/about', runScripts: 'dangerously' },
    );
    await expect(
      mapPageForGrading(async (expr: string) => dom.window.eval(expr), 'https://example.edu/about'),
    ).rejects.toThrow(/repeating student layout/i);
    expect(mockSave).not.toHaveBeenCalledWith('https://example.edu/about', expect.anything());
  });
});

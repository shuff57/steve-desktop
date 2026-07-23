/**
 * The extraction script runs against a jsdom replica of a real MyOpenMath
 * `gradeallq2.php` page — same element/class/attribute shape confirmed over CDP against
 * a live graded question, with invented students so no real work enters the repo.
 */
import { JSDOM } from 'jsdom';
import { describe, expect, it } from 'vitest';
import {
  PAGE_EXTRACT_JS,
  gradeableFrom,
  loadStudents,
  parseExtracted,
  toGradingStudents,
  type ExtractedStudent,
} from './load-students';

interface Row {
  name: string;
  answer: string;
  score: string; // "N/A" when ungraded, as the page renders it
  feedback?: string;
  /** Graded students with full marks really do carry qfilter-nowork on the live page. */
  filters?: string;
}

function pageWith(rows: Row[]): string {
  const blocks = rows
    .map(
      (r, i) => `
    <div class="${r.filters ?? 'qfilter-nowork'} bigquestionwrap">
      <div class="headerpane"><b>${r.name}</b></div>
      <div class="scrollpane">
        <div class="questionwrap questionpane">
          <div id="questionwrap50${i}" class="question">
            <div class="question qscope50${i}">
              <div class="seqsepwrap">
                <p class="seqsep">Part 1 of 2</p>
                <div>
                  <p>A dataset with five-number summary...</p>
                  <div class="rubric-container"><details><summary>Grading Checklist</summary></details></div>
                  <div id="qnwrap50${i}000" class="introtext">${r.answer}</div>
                  <span class="afterquestion"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="scoredetails">
        <span class="person">${r.name}:</span>
        <input id="scorebox50${i}" name="ud-71500${i}-0-0-0" type="text" value="${r.score}" />
        <div id="fb-0-0-71500${i}" class="fbbox">${r.feedback ?? ''}</div>
      </div>
    </div>`,
    )
    .join('');
  return `<!doctype html><html><body><form id="mainform"><div id="qlistwrap">${blocks}</div></form>
    <button id="quicksavebtn">Save</button></body></html>`;
}

/** Run the real page script in a jsdom window, exactly as CDP would in the webview. */
function extractFrom(rows: Row[]): unknown {
  // runScripts is required for window.eval to execute inside the jsdom realm rather
  // than this one. Safe here: the only script evaluated is our own extraction string.
  const dom = new JSDOM(pageWith(rows), {
    url: 'https://www.myopenmath.com/course/gradeallq2.php?cid=1&qid=2',
    runScripts: 'dangerously',
  });
  const w = dom.window as unknown as { eval: (s: string) => unknown };
  return w.eval(PAGE_EXTRACT_JS);
}

const ROWS: Row[] = [
  { name: 'Okonkwo, Chidi', answer: '<p>IQR = 40 - 24 = 16</p><p>Upper fence = 64, so 88 is an outlier.</p>', score: '8', feedback: 'Nice work', filters: 'qfilter-perfect qfilter-100 qfilter-fb qfilter-nowork' },
  { name: 'Lindqvist, Annika', answer: '<p>I think 88 is too big.</p>', score: '2.5' },
  { name: 'Sowande, Bisi', answer: '<p>IQR is 16 and the fence is 64.</p>', score: 'N/A' },
  { name: 'Ferreira, Tomas', answer: '', score: 'N/A', filters: 'qfilter-unans qfilter-nowork' },
];

describe('extraction from a gradeallq2 page', () => {
  const students = parseExtracted(extractFrom(ROWS));

  it('finds every student block', () => {
    expect(students).toHaveLength(4);
  });

  it('reads names from the header pane, without the trailing colon', () => {
    expect(students.map((s) => s.name)).toEqual([
      'Okonkwo, Chidi',
      'Lindqvist, Annika',
      'Sowande, Bisi',
      'Ferreira, Tomas',
    ]);
  });

  it('reads the answer, not the question text around it', () => {
    expect(students[0]!.response).toContain('IQR = 40 - 24 = 16');
    expect(students[0]!.response).not.toContain('five-number summary');
    expect(students[0]!.response).not.toContain('Grading Checklist');
  });

  it('parses a numeric score and treats "N/A" as ungraded, never as zero', () => {
    expect(students[0]!.currentScore).toBe(8);
    expect(students[1]!.currentScore).toBe(2.5); // half points are real
    expect(students[2]!.currentScore).toBeNull();
  });

  it('captures the score field name for a future write-back', () => {
    expect(students[0]!.scoreField).toBe('ud-715000-0-0-0');
  });

  it('captures feedback already on the page', () => {
    expect(students[0]!.existingFeedback).toBe('Nice work');
    expect(students[1]!.existingFeedback).toBe('');
  });

  // The live page marks graded, full-credit students qfilter-nowork — that class means
  // "showed no scratch work", not "submitted nothing". Only empty text means unanswered.
  it('derives answered from the response text, not the qfilter classes', () => {
    expect(students[0]!.answered).toBe(true); // qfilter-nowork but clearly answered
    expect(students[3]!.answered).toBe(false); // genuinely empty
  });

  it('rejects a page that is not a grading page', () => {
    expect(() => parseExtracted({ students: undefined })).toThrow(/not a grading page|no student list/i);
  });
});

describe('gradeableFrom', () => {
  const students = parseExtracted(extractFrom(ROWS));

  it('defaults to ungraded students who actually submitted', () => {
    expect(gradeableFrom(students).map((s) => s.name)).toEqual(['Sowande, Bisi']);
  });

  it('never includes an unanswered student by default — there is nothing to grade', () => {
    expect(gradeableFrom(students).some((s) => !s.answered)).toBe(false);
  });

  it('can include already-graded students when a regrade is explicit', () => {
    const out = gradeableFrom(students, { includeGraded: true });
    expect(out.map((s) => s.name)).toEqual(['Okonkwo, Chidi', 'Lindqvist, Annika', 'Sowande, Bisi']);
  });

  it('can include unanswered students when explicitly asked', () => {
    expect(gradeableFrom(students, { includeUnanswered: true, includeGraded: true })).toHaveLength(4);
  });
});

describe('toGradingStudents', () => {
  it('maps to the shape gradeBatch consumes, keeping the name for redaction', () => {
    const out = toGradingStudents(parseExtracted(extractFrom(ROWS)).slice(0, 1));
    expect(out).toEqual([{ name: 'Okonkwo, Chidi', responseText: expect.stringContaining('IQR') }]);
  });
});

describe('loadStudents', () => {
  it('evaluates the page script and filters in one step', async () => {
    const evaluate = async (expr: string) => {
      expect(expr).toBe(PAGE_EXTRACT_JS);
      return extractFrom(ROWS);
    };
    const out = await loadStudents(evaluate);
    expect(out.map((s) => s.name)).toEqual(['Sowande, Bisi']);
  });

  it('surfaces a page that yielded nothing rather than returning an empty roster', async () => {
    await expect(loadStudents(async () => ({}))).rejects.toThrow(/no student list/i);
  });

  it('returns empty when the page has students but none are gradeable', async () => {
    const allGraded: Row[] = [{ name: 'A, B', answer: '<p>x</p>', score: '5' }];
    const out = await loadStudents(async () => extractFrom(allGraded));
    expect(out).toEqual([]);
  });
});

describe('no student data leaves this module unredacted', () => {
  it('carries the real name through so identifiersFor can tokenize it', () => {
    // Extraction deliberately does NOT redact — redaction happens at the model boundary
    // in grade.ts, which needs the real name to build the Redactor.
    const s: ExtractedStudent[] = parseExtracted(extractFrom(ROWS));
    expect(s[0]!.name).toBe('Okonkwo, Chidi');
  });
});

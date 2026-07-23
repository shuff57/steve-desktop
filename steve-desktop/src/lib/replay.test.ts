import { describe, it, expect } from 'vitest';
import { replayWorkflow, modelRelocator, parseRelocateReply, type PageDriver, type ModelHealer } from './replay';
import type { Workflow, WorkflowStep } from './types/site-profile';
import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';

interface El { selector: string; tag: string; label: string }

function meta() {
  return { totalVisited: 0, nodesIncluded: 0, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: '2026-06-23T00:00:00.000Z' };
}

// A tiny in-memory page. `act` only succeeds if the selector currently exists.
class MockPage implements PageDriver {
  acted: Array<{ selector: string; value?: string }> = [];
  constructor(public els: El[]) {}
  exists(selector: string): boolean {
    return this.els.some((e) => e.selector === selector);
  }
  snapshot(): SnapshotResult {
    const nodes: SnapshotNode[] = this.els.map((e) => ({
      tag: e.tag,
      depth: 1,
      priority: 'critical',
      text: e.tag === 'button' ? e.label : '',
      attrs: { 'aria-label': e.label, id: e.selector.replace(/^#/, '') },
    }));
    return { nodes, meta: meta() };
  }
  act(step: WorkflowStep, selector: string): boolean {
    if (!this.exists(selector)) return false;
    this.acted.push({ selector, value: step.value });
    return true;
  }
}

const workflow: Workflow = {
  name: 'Enter Grades',
  steps: [
    { action: 'fill', selector: '#studentName', value: 'Jane Doe', description: 'student name field' },
    { action: 'fill', selector: '#grade', value: 'A', description: 'grade field' },
    { action: 'click', selector: '#submit', description: 'submit button' },
  ],
};

describe('replayWorkflow', () => {
  it('completes every step on an unchanged page and audits each action', async () => {
    const page = new MockPage([
      { selector: '#studentName', tag: 'input', label: 'Student Name' },
      { selector: '#grade', tag: 'input', label: 'Grade' },
      { selector: '#submit', tag: 'button', label: 'Submit' },
    ]);
    const summary = await replayWorkflow(workflow, page);

    expect(summary.completed).toBe(true);
    expect(summary.results.map((r) => r.status)).toEqual(['done', 'done', 'done']);
    expect(page.acted).toHaveLength(3);
  });

  it('self-heals via a stored candidate anchor before falling back to re-derivation', async () => {
    const page = new MockPage([
      { selector: '#student_name', tag: 'input', label: 'Student Name' },
      { selector: '#grade', tag: 'input', label: 'Grade' },
      { selector: '#submit', tag: 'button', label: 'Submit' },
    ]);
    const wf: Workflow = {
      name: 'Enter Grades',
      steps: [
        // recorded selector is stale; a stored candidate matches
        { action: 'fill', selector: '#studentName', value: 'Jane Doe', description: 'student name field',
          candidates: ['role=textbox[name="Student Name"]', '#student_name'] },
        { action: 'fill', selector: '#grade', value: 'A', description: 'grade field' },
        { action: 'click', selector: '#submit', description: 'submit button' },
      ],
    };
    const summary = await replayWorkflow(wf, page);
    expect(summary.completed).toBe(true);
    expect(summary.results[0].status).toBe('recovered');
    expect(summary.results[0].selectorUsed).toBe('#student_name');
    expect(summary.results[0].detail).toMatch(/candidate/i);
    expect(page.acted[0].selector).toBe('#student_name');
  });

  it('self-heals when a selector no longer matches by re-deriving page state', async () => {
    // #studentName was renamed to #student_name — the recorded selector is stale
    const page = new MockPage([
      { selector: '#student_name', tag: 'input', label: 'Student Name' },
      { selector: '#grade', tag: 'input', label: 'Grade' },
      { selector: '#submit', tag: 'button', label: 'Submit' },
    ]);
    const summary = await replayWorkflow(workflow, page);

    expect(summary.completed).toBe(true);
    const first = summary.results[0];
    expect(first.status).toBe('recovered');
    expect(first.selectorUsed).toBe('#student_name');
    expect(first.detail).toMatch(/re-derived|fuzzy|recovered/i);
    // it actually acted on the recovered element
    expect(page.acted[0].selector).toBe('#student_name');
  });

  it('skips with a reason instead of guessing when no element can be matched', async () => {
    const page = new MockPage([
      { selector: '#grade', tag: 'input', label: 'Grade' },
      { selector: '#submit', tag: 'button', label: 'Submit' },
    ]);
    const summary = await replayWorkflow(workflow, page);

    expect(summary.completed).toBe(false);
    expect(summary.results[0].status).toBe('skipped');
    expect(summary.results[0].detail).toBeTruthy();
    // later steps still run
    expect(summary.results[1].status).toBe('done');
  });

  // A step whose description shares NO tokens with any current label, so tiers 0–2 all miss.
  const tier3Workflow: Workflow = {
    name: 'Finish',
    steps: [{ action: 'click', selector: '#oldBtn', description: 'finalize enrollment' }],
  };

  it('escalates to the model (tier 3) when local heal fails, then rewrites the cache', async () => {
    const page = new MockPage([{ selector: '#apply', tag: 'button', label: 'Submit Application' }]);
    const heal: ModelHealer = async () => '#apply';

    const summary = await replayWorkflow(tier3Workflow, page, heal);

    expect(summary.results[0].status).toBe('recovered');
    expect(summary.results[0].selectorUsed).toBe('#apply');
    expect(summary.results[0].detail).toMatch(/model|escalat/i);
    expect(page.acted[0].selector).toBe('#apply');
    // cache rewritten in place so next replay hits the happy path
    expect(tier3Workflow.steps[0].selector).toBe('#apply');
  });

  it('skips when the model relocates to a selector that does not exist, and never throws on transport failure', async () => {
    const page = new MockPage([{ selector: '#apply', tag: 'button', label: 'Submit Application' }]);
    const wf: Workflow = { name: 'Finish', steps: [{ action: 'click', selector: '#oldBtn', description: 'finalize enrollment' }] };

    const ghost: ModelHealer = async () => '#does-not-exist';
    expect((await replayWorkflow(wf, page, ghost)).results[0].status).toBe('skipped');

    const boom: ModelHealer = async () => { throw new Error('sidecar down'); };
    expect((await replayWorkflow(wf, page, boom)).results[0].status).toBe('skipped');
  });
});

// Driver with a postcondition + live fingerprint — the outcome-gated heal path.
class GatedPage extends MockPage {
  verifyResult = true;
  verifyCalls = 0;
  fingerprints: string[] | null = null;
  verify(): boolean {
    this.verifyCalls++;
    return this.verifyResult;
  }
  fingerprint(): string[] | null {
    return this.fingerprints;
  }
}

describe('outcome-gated heals + passive refresh', () => {
  const staleWf = (): Workflow => ({
    name: 'Enter Grades',
    steps: [
      { action: 'fill', selector: '#studentName', value: 'A', description: 'student name field',
        candidates: ['#student_name'] },
    ],
  });

  it('a heal that passes its postcondition persists: selector rewritten, stale one kept as candidate', async () => {
    const page = new GatedPage([{ selector: '#student_name', tag: 'input', label: 'Student Name' }]);
    const wf = staleWf();
    const summary = await replayWorkflow(wf, page);

    expect(summary.results[0].status).toBe('recovered');
    expect(summary.results[0].verified).toBe(true);
    expect(summary.healed).toBe(true);
    expect(wf.steps[0].selector).toBe('#student_name'); // persisted
    expect(wf.steps[0].candidates).toContain('#studentName'); // stale anchor kept for a flip-back
  });

  it('a heal that FAILS its postcondition must NOT persist', async () => {
    const page = new GatedPage([{ selector: '#student_name', tag: 'input', label: 'Student Name' }]);
    page.verifyResult = false;
    const wf = staleWf();
    const summary = await replayWorkflow(wf, page);

    expect(summary.results[0].status).toBe('skipped');
    expect(summary.results[0].verified).toBe(false);
    expect(summary.results[0].detail).toMatch(/postcondition/i);
    expect(wf.steps[0].selector).toBe('#studentName'); // unchanged — nothing persisted
    expect(summary.healed).toBe(false);
  });

  it('a verify() that throws counts as unverified, not as a pass', async () => {
    const page = new GatedPage([{ selector: '#student_name', tag: 'input', label: 'Student Name' }]);
    page.verify = () => { throw new Error('CDP hiccup'); };
    const wf = staleWf();
    const summary = await replayWorkflow(wf, page);
    expect(summary.results[0].status).toBe('skipped');
    expect(wf.steps[0].selector).toBe('#studentName');
  });

  it('the happy path is outcome-gated too: acted but unverified = skipped', async () => {
    const page = new GatedPage([{ selector: '#grade', tag: 'input', label: 'Grade' }]);
    page.verifyResult = false;
    const summary = await replayWorkflow(
      { name: 'g', steps: [{ action: 'fill', selector: '#grade', value: 'A', description: 'grade field' }] },
      page,
    );
    expect(summary.results[0].status).toBe('skipped');
    expect(summary.completed).toBe(false);
  });

  it('passive refresh: every verified act merges the element\'s fresh live anchors into candidates', async () => {
    const page = new GatedPage([{ selector: '#grade', tag: 'input', label: 'Grade' }]);
    page.fingerprints = ['input[name="grade"]', '#grade'];
    const wf: Workflow = { name: 'g', steps: [{ action: 'fill', selector: '#grade', value: 'A', description: 'grade field' }] };
    const summary = await replayWorkflow(wf, page);

    expect(summary.results[0].status).toBe('done');
    // own selector excluded, fresh anchor stored — the profile refreshed for free
    expect(wf.steps[0].candidates).toEqual(['input[name="grade"]']);
  });

  it('a driver without verify/fingerprint behaves exactly as before (back-compat)', async () => {
    const page = new MockPage([{ selector: '#grade', tag: 'input', label: 'Grade' }]);
    const wf: Workflow = { name: 'g', steps: [{ action: 'fill', selector: '#grade', value: 'A', description: 'grade field' }] };
    const summary = await replayWorkflow(wf, page);
    expect(summary.results[0].status).toBe('done');
    expect(summary.results[0].verified).toBeUndefined();
    expect(summary.healed).toBe(false);
  });
});

describe('modelRelocator — tier-3 trust boundary', () => {
  function piiSnapshot(): SnapshotResult {
    return {
      nodes: [
        { tag: 'td', depth: 1, priority: 'high', text: 'Jane Doe', attrs: { 'data-field': 'studentName' } },
        { tag: 'button', depth: 1, priority: 'critical', text: 'Submit', attrs: { id: 'submit' } },
      ],
      meta: { totalVisited: 2, nodesIncluded: 2, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: 'x' },
    };
  }

  it('sends the model REDACTED structure (no raw PII) and returns the relocated selector', async () => {
    let sent = '';
    const transport = async (text: string) => { sent = text; return '#submit'; };
    const heal = modelRelocator(transport);

    const selector = await heal({ action: 'click', description: 'submit the form' }, piiSnapshot());

    expect(sent).toContain('⟦D1⟧');       // the roster cell crossed as a token
    expect(sent).not.toContain('Jane Doe'); // raw PII never left the machine
    expect(sent).toContain('submit the form'); // author intent is included
    expect(selector).toBe('#submit');      // chrome selector survives redaction
  });

  it('parseRelocateReply strips fences/labels and rejects token echoes', () => {
    expect(parseRelocateReply('```\n#submit\n```')).toBe('#submit');
    expect(parseRelocateReply('selector: role=button[name="Go"]')).toBe('role=button[name="Go"]');
    expect(parseRelocateReply('⟦D1⟧')).toBeNull();
    expect(parseRelocateReply('   ')).toBeNull();
  });
});

import { mkdirSync, writeFileSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

import { buildProfileFromSnapshot } from '../lib/discover-profile';
import { redactTree } from '../lib/redact-tree';
import { callModelTree, type ModelTransport } from '../lib/model-gate';
import { workflowToSkill, skillToWorkflow } from '../lib/workflow-skill';
import { replayWorkflow, type PageDriver, type ReplaySummary } from '../lib/replay';
import { domainToPath, slugify } from '../lib/utils/index';
import type { SiteProfile, Workflow, WorkflowStep } from '../lib/types/site-profile';
import type { SnapshotResult, SnapshotNode } from '../lib/dom-snapshot-types';

// End-to-end proof of the boring-clicks core loop on the local runner:
// open -> train (discover + capture) -> replay (autonomous, self-healing).
// No real browser/model is wired this milestone (out of scope); a fixture
// snapshot stands in for the embedded browser and a mock transport for the cloud
// model. Everything else is the real production code.

export interface Student {
  name: string;
  id: string;
  grade: string;
  /** Guardian name — PII in a NON-identifier field, to prove slot redaction catches it. */
  guardian: string;
}

function meta() {
  return { totalVisited: 0, nodesIncluded: 0, nodesDropped: 0, wasTruncated: false, charCount: 0, capturedAt: new Date().toISOString() };
}

/** A school "enter grades" page snapshot containing real student PII. */
export function gradesPageSnapshot(student: Student): SnapshotResult {
  const nodes: SnapshotNode[] = [
    { tag: 'h1', depth: 0, priority: 'high', text: 'Enter Grades', attrs: {} },
    // roster row — PII lives here, flagged as identifier fields by discovery
    { tag: 'td', depth: 2, priority: 'high', text: student.name, attrs: { 'data-field': 'studentName' } },
    { tag: 'td', depth: 2, priority: 'high', text: student.id, attrs: { 'data-field': 'studentId' } },
    // guardian name in a field discovery does NOT flag as an identifier — the old
    // value-dictionary (seeded only from identifier fields) would leak this; slot redaction catches it.
    { tag: 'td', depth: 2, priority: 'medium', text: student.guardian, attrs: { 'data-field': 'guardian' } },
    { tag: 'td', depth: 2, priority: 'medium', text: 'missing assignment 3', attrs: { 'data-field': 'note' } },
    // the form the teacher fills
    { tag: 'input', depth: 2, priority: 'critical', text: '', attrs: { id: 'studentName', name: 'studentName', type: 'text', 'aria-label': 'Student Name' } },
    { tag: 'input', depth: 2, priority: 'critical', text: '', attrs: { id: 'grade', name: 'grade', type: 'text', 'aria-label': 'Grade' } },
    { tag: 'button', depth: 2, priority: 'critical', text: 'Submit', attrs: { id: 'submit' } },
  ];
  return { nodes, meta: meta() };
}

interface El { selector: string; tag: string; label: string }

/** Stand-in for the embedded browser. `rename` simulates the page changing. */
export class DemoPage implements PageDriver {
  acted: Array<{ selector: string; value?: string }> = [];
  constructor(private els: El[]) {}
  static fromSnapshot(snapshot: SnapshotResult, rename: Record<string, string> = {}): DemoPage {
    const els = snapshot.nodes
      .filter((n) => n.tag === 'input' || n.tag === 'button' || n.tag === 'a')
      .map((n) => {
        const id = n.attrs['id'] ?? '';
        const selector = rename[`#${id}`] ?? (id ? `#${id}` : n.tag);
        return { selector, tag: n.tag, label: n.attrs['aria-label'] ?? n.text ?? '' };
      });
    return new DemoPage(els);
  }
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

export interface DemoResult {
  profilePath: string;
  profile: SiteProfile;
  skillPath: string;
  modelPlan: string;
  outboundCapture: string[];
  leaked: boolean;
  summary: ReplaySummary;
}

export interface DemoOptions {
  rootDir: string;
  student?: Student;
  /** Simulate the page changing before replay, to force self-heal. */
  renameOnReplay?: Record<string, string>;
  transport?: ModelTransport;
}

export async function runBoringClicksDemo(opts: DemoOptions): Promise<DemoResult> {
  const student = opts.student ?? { name: 'Jane Doe', id: '4471', grade: 'A', guardian: 'Bobby Smith' };
  const url = 'https://sis.example.edu/grades';

  // 1) OPEN — the embedded browser shows the page (fixture snapshot here).
  const snapshot = gradesPageSnapshot(student);

  // 2) TRAIN
  // 2a) discovery -> site-profile with identifier flags, saved under .agents/site-profiles/<domain>/
  const profile = buildProfileFromSnapshot(snapshot, url);
  const profileDir = join(opts.rootDir, '.agents', 'site-profiles', domainToPath(profile.domain));
  mkdirSync(profileDir, { recursive: true });
  const profilePath = join(profileDir, `${slugify(profile.pageName)}.json`);
  writeFileSync(profilePath, JSON.stringify(profile, null, 2));

  // 2b) capture a workflow by example, persist it as a re-runnable skill
  const workflow: Workflow = {
    name: 'Enter Grades',
    trigger: 'enter grades for a student',
    steps: [
      { action: 'fill', selector: '#studentName', value: student.name, description: 'student name field' },
      { action: 'fill', selector: '#grade', value: student.grade, description: 'grade field' },
      { action: 'click', selector: '#submit', description: 'submit button' },
    ],
  };
  const skillDir = join(opts.rootDir, '.agents', 'skills', slugify(workflow.name));
  mkdirSync(skillDir, { recursive: true });
  const skillPath = join(skillDir, 'SKILL.md');
  writeFileSync(skillPath, workflowToSkill(workflow, { urlPattern: profile.domain }));

  // 2c) reason about the page via the model — SLOT-LEVEL REDACTOR GATE on the only door out.
  // Deny-by-default: every data slot is tokenized regardless of contents, so PII in a
  // non-identifier field (the guardian) is stripped even though it was never seeded.
  const red = redactTree(snapshot, { extraSecrets: [student.name, student.id] });
  const outboundCapture: string[] = [];
  const transport: ModelTransport =
    opts.transport ?? (async () => 'Plan: fill the form for ⟦D1⟧ (id ⟦D2⟧) and submit.');
  const captureTransport: ModelTransport = async (text) => {
    outboundCapture.push(text); // record exactly what crosses the boundary
    return transport(text);
  };
  const modelPlan = await callModelTree(red, captureTransport);

  // 3) REPLAY — reload the skill cold (no training context) and run it.
  const reloaded = skillToWorkflow(readFileSync(skillPath, 'utf8'));
  const page = DemoPage.fromSnapshot(snapshot, opts.renameOnReplay ?? {});
  const summary = await replayWorkflow(reloaded, page);

  const leaked = outboundCapture.some(
    (t) => t.includes(student.name) || t.includes(student.id) || t.includes(student.guardian),
  );

  return { profilePath, profile, skillPath, modelPlan, outboundCapture, leaked, summary };
}

// Parameterized replay: mark a recorded fill/select step as a variable, then bind a roster of
// rows to it at run time — record "enter grade for one student" once, run it for 30.
// FERPA: marking a step as a parameter DROPS its literal recorded value, so the stored skill
// carries no student data; roster values exist only in memory at run time and go straight to
// the page over CDP (never to a model — the tier-3 heal path is already slot-redacted).

import type { Workflow, WorkflowStep } from './types/site-profile';

/** "Student name" → "student_name". Stable, human-readable param keys. */
export function paramSlug(text: string): string {
  const s = (text || 'value')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '_')
    .replace(/^_+|_+$/g, '')
    .slice(0, 40);
  return s || 'value';
}

/** Toggle a step's parameterization. Setting a param clears the recorded literal value. */
export function setParam(step: WorkflowStep, name: string | null): WorkflowStep {
  if (name === null) {
    const { param, ...rest } = step;
    return rest;
  }
  return { ...step, param: paramSlug(name), value: undefined };
}

/** Ordered unique param names across a workflow. */
export function workflowParams(workflow: Workflow): string[] {
  const seen = new Set<string>();
  for (const s of workflow.steps) if (s.param && !seen.has(s.param)) seen.add(s.param);
  return [...seen];
}

/** Clone the workflow with each parameterized step's value bound from `row`. Throws on a
 *  missing value — silently replaying an empty grade into a live gradebook is worse than failing. */
export function bindWorkflow(workflow: Workflow, row: Record<string, string>): Workflow {
  return {
    ...workflow,
    steps: workflow.steps.map((s) => {
      if (!s.param) return s;
      const v = row[s.param];
      if (v === undefined || v === '') throw new Error(`Missing value for "${s.param}" in this row.`);
      return { ...s, value: v };
    }),
  };
}

export interface Roster {
  headers: string[];
  rows: Record<string, string>[];
}

/**
 * Parse a pasted roster. First line is the header (param names); delimiter is tab when the
 * header contains one (spreadsheet paste), else comma. Minimal quote support for CSV
 * ("Smith, Jane" stays one cell). ponytail: not a full CSV parser — no embedded newlines;
 * swap in a real one if a teacher's export ever needs it.
 */
export function parseRoster(text: string): Roster {
  const lines = text.split(/\r?\n/).map((l) => l.trim()).filter(Boolean);
  if (lines.length < 2) return { headers: [], rows: [] };
  const delim = lines[0].includes('\t') ? '\t' : ',';

  const split = (line: string): string[] => {
    if (delim === '\t') return line.split('\t').map((c) => c.trim());
    const cells: string[] = [];
    let cur = '';
    let inQ = false;
    for (let i = 0; i < line.length; i++) {
      const ch = line[i];
      if (inQ) {
        if (ch === '"' && line[i + 1] === '"') { cur += '"'; i++; }
        else if (ch === '"') inQ = false;
        else cur += ch;
      } else if (ch === '"') inQ = true;
      else if (ch === ',') { cells.push(cur.trim()); cur = ''; }
      else cur += ch;
    }
    cells.push(cur.trim());
    return cells;
  };

  const headers = split(lines[0]).map(paramSlug);
  const rows = lines.slice(1).map((line) => {
    const cells = split(line);
    const row: Record<string, string> = {};
    headers.forEach((h, i) => { row[h] = cells[i] ?? ''; });
    return row;
  });
  return { headers, rows };
}

/** A short label for a roster row in run output — first cell, never invented. */
export function rowLabel(row: Record<string, string>, headers: string[]): string {
  return row[headers[0]] ?? '';
}

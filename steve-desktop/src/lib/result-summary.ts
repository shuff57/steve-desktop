// Structured view of an automate run's result report, so the panel can lead with the verdict and
// what CHANGED (the audit-relevant part) instead of a wall of raw markdown. Best-effort text parse
// of the report shape buildAutomateExecPrompt asks for:
//
//   # Result            — one bullet per step, each DONE / SKIPPED (why) / FAILED (why)
//   ## Changed          — exactly what state was modified
//   ## Verdict          — did the task complete?
//
// An unparseable report yields zero counts and empty lists; the UI then just renders the markdown.

export interface RunStep {
  text: string;
  status: 'done' | 'skipped' | 'failed';
}

export interface ResultSummary {
  steps: RunStep[];
  done: number;
  skipped: number;
  failed: number;
  changed: string[];
  verdict: string;
  /** True when the report says nothing was modified — worth showing plainly on a read-only run. */
  noChanges: boolean;
}

const BULLET = /^[-*]\s+(.*)$/;
const HEADING = /^#{1,3}\s*(.+?):?\s*$/;

/** Strip markdown emphasis/backticks so a chip or one-line summary reads cleanly. */
function plain(text: string): string {
  return text
    .replace(/\*\*|__|`/g, '')
    .replace(/\s+/g, ' ')
    .trim();
}

export function summarizeRunResult(report: string): ResultSummary {
  const steps: RunStep[] = [];
  const changed: string[] = [];
  const verdictLines: string[] = [];
  type Section = 'result' | 'changed' | 'verdict' | 'other';
  let section: Section = 'result';

  for (const raw of report.split(/\r?\n/)) {
    const line = raw.trim();
    if (!line) continue;

    const heading = line.match(HEADING);
    if (heading) {
      const name = heading[1].toLowerCase();
      section = /^changed\b/.test(name)
        ? 'changed'
        : /^verdict\b/.test(name)
          ? 'verdict'
          : /^result\b/.test(name)
            ? 'result'
            : 'other';
      continue;
    }

    if (section === 'verdict') {
      verdictLines.push(plain(line));
      continue;
    }

    const bullet = line.match(BULLET);
    if (!bullet) continue;
    const item = plain(bullet[1]);
    if (!item) continue;

    if (section === 'changed') {
      changed.push(item);
      continue;
    }
    if (section !== 'result') continue;

    // The verdict word lands anywhere in the bullet ("Opened that URL — DONE, but it returned
    // 404"), not just at the end. FAILED wins over SKIPPED wins over DONE so a step that
    // half-worked is never reported as clean.
    const status: RunStep['status'] = /\bFAILED\b/.test(bullet[1])
      ? 'failed'
      : /\bSKIPPED\b/.test(bullet[1])
        ? 'skipped'
        : /\bDONE\b/.test(bullet[1])
          ? 'done'
          : 'done';
    if (!/\b(DONE|SKIPPED|FAILED)\b/.test(bullet[1])) continue; // prose bullet, not a step
    steps.push({ text: item, status });
  }

  // "No site state changed (read-only…)" is a sentence, not a change — treat it as none.
  const noChanges = changed.length > 0 && changed.every((c) => /^no\b.*\bchanged?\b/i.test(c));

  return {
    steps,
    done: steps.filter((s) => s.status === 'done').length,
    skipped: steps.filter((s) => s.status === 'skipped').length,
    failed: steps.filter((s) => s.status === 'failed').length,
    changed,
    verdict: verdictLines.join(' '),
    noChanges,
  };
}

// Structured view of an agent verification report so the modal can lead with what needs review
// (discrepancies + verdict) and collapse the long confirmed list. Best-effort text parse of the
// report format buildCliVerifyPrompt asks for; an unparseable report yields empty lists and the
// UI falls back to rendering the raw markdown.

export interface VerifySummary {
  confirmed: string[];
  discrepancies: string[];
  verdict: string;
}

const BULLET = /^[-*]\s+(.*)$/;

export function summarizeVerifyReport(report: string): VerifySummary {
  const confirmed: string[] = [];
  const discrepancies: string[] = [];
  const verdictLines: string[] = [];
  let inVerdict = false;

  for (const raw of report.split(/\r?\n/)) {
    const line = raw.trim();
    if (/^#{1,3}\s*verdict:?\s*$/i.test(line)) { inVerdict = true; continue; }
    if (/^#{1,3}\s/.test(line)) { inVerdict = false; continue; }
    if (inVerdict) {
      if (line) verdictLines.push(line);
      continue;
    }
    const m = line.match(BULLET);
    if (!m) continue;
    // The verdict word can land anywhere in the bullet ("Inbox #inbox — DISCREPANCY: …"),
    // not just as a prefix. DISCREPANCY wins when both appear.
    const item = m[1].replace(/^`?(CONFIRMED|DISCREPANCY)`?:?\s*/i, '').trim();
    if (/\bDISCREPANCY\b/i.test(m[1])) discrepancies.push(item);
    else if (/\bCONFIRMED\b/i.test(m[1])) confirmed.push(item);
  }

  return { confirmed, discrepancies, verdict: verdictLines.join(' ') };
}

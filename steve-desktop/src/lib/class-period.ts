/**
 * The class period as a time window, and the one question the attendance skill turns on:
 * did this student do anything in MyOpenMath WHILE class was happening?
 *
 * Separate from any page scraping on purpose. "Was there activity" is the easy half; "was it inside
 * the period, in the teacher's own timezone, on the right day" is the half that fails silently and
 * marks a student who was working absent. That belongs in tested code, not in an agent's head.
 */

export interface ClassPeriod {
  /** What the teacher calls it — "Period 3". Carried through to the report. */
  name: string;
  /** Local wall-clock start/end, 24h "HH:MM". Local to the teacher's machine, which is the school. */
  start: string;
  end: string;
}

export interface Window {
  start: Date;
  end: Date;
}

/** Minutes of slack on each end. A student who opens the assignment as the bell rings, or submits
 *  their last answer as it rings again, was in class — the window is a proxy for the period, and a
 *  hard edge on a proxy is how a present student becomes an absence. */
export const DEFAULT_GRACE_MINUTES = 5;

function parseHhMm(hhmm: string): { h: number; m: number } {
  const match = /^(\d{1,2}):(\d{2})$/.exec(hhmm.trim());
  if (!match) throw new Error(`Class period time must be "HH:MM" (24-hour), got "${hhmm}".`);
  const h = Number(match[1]);
  const m = Number(match[2]);
  if (h > 23 || m > 59) throw new Error(`"${hhmm}" is not a real time of day.`);
  return { h, m };
}

/**
 * The period's window on `onDate`, built in LOCAL time.
 *
 * Local is not a shortcut here, it is the requirement: the bell schedule is wall-clock at the
 * school, and the timestamps MyOpenMath renders to a signed-in teacher are wall-clock too. Going
 * through UTC would silently shift the whole window by an hour twice a year, quietly turning a
 * period's worth of real work into "no activity".
 */
export function periodWindow(period: ClassPeriod, onDate: Date, graceMinutes = DEFAULT_GRACE_MINUTES): Window {
  const s = parseHhMm(period.start);
  const e = parseHhMm(period.end);
  const y = onDate.getFullYear();
  const mo = onDate.getMonth();
  const d = onDate.getDate();

  const start = new Date(y, mo, d, s.h, s.m, 0, 0);
  const end = new Date(y, mo, d, e.h, e.m, 0, 0);
  if (end.getTime() <= start.getTime()) {
    throw new Error(`Period "${period.name}" ends (${period.end}) at or before it starts (${period.start}).`);
  }

  start.setMinutes(start.getMinutes() - graceMinutes);
  end.setMinutes(end.getMinutes() + graceMinutes);
  return { start, end };
}

/** Is a single activity timestamp inside the window? Both ends inclusive — the grace already
 *  decided how generous to be, and an exact-boundary hit should not turn on a `<` vs `<=`. */
export function isInWindow(at: Date, w: Window): boolean {
  const t = at.getTime();
  if (Number.isNaN(t)) return false;
  return t >= w.start.getTime() && t <= w.end.getTime();
}

export interface StudentActivity {
  /** The masked token for this student (⟦D1⟧ …), never a real name — see page-agent-mask.ts. */
  student: string;
  /** Every activity timestamp seen for them. An unparseable one must arrive as an Invalid Date
   *  rather than being dropped upstream, so it can be reported instead of silently counting as
   *  "no activity". */
  timestamps: Date[];
}

export interface AttendanceVerdict {
  student: string;
  active: boolean;
  /** Activity that landed inside the window, most recent first. Empty when not active. */
  inWindow: Date[];
  /** Activity seen for this student OUTSIDE the window — the reason to look twice before marking
   *  them absent. Someone who worked all last night and nothing during class is a different
   *  conversation from someone with no activity at all. */
  outsideWindow: Date[];
  /** Timestamps that could not be read. Never counted either way; surfaced so a parsing change on
   *  MyOpenMath's side shows up as "I could not tell" instead of a page full of absences. */
  unreadable: number;
}

/**
 * Split each student's activity against the window.
 *
 * Deliberately returns a verdict per student rather than just a list of absentees: the skill has to
 * SHOW its work before a teacher approves a write into Aeries, and "no activity at all" versus
 * "active, but only at 11pm last night" versus "I couldn't read their timestamps" are three
 * different things that a bare absent-list would flatten into one.
 */
export function judgeAttendance(activity: StudentActivity[], w: Window): AttendanceVerdict[] {
  return activity.map(({ student, timestamps }) => {
    const inWindow: Date[] = [];
    const outsideWindow: Date[] = [];
    let unreadable = 0;
    for (const t of timestamps) {
      if (Number.isNaN(t.getTime())) unreadable++;
      else if (isInWindow(t, w)) inWindow.push(t);
      else outsideWindow.push(t);
    }
    const desc = (a: Date[]) => a.sort((x, y2) => y2.getTime() - x.getTime());
    return {
      student,
      active: inWindow.length > 0,
      inWindow: desc(inWindow),
      outsideWindow: desc(outsideWindow),
      unreadable,
    };
  });
}

/**
 * Who the skill would propose marking absent — and, separately, who it cannot speak for.
 *
 * `unsure` exists so that a student whose timestamps did not parse never lands in `absent` by
 * default. Absence is the destructive direction of this whole workflow: silence from a scraper and
 * silence from a student look identical in the data and are opposite in meaning.
 */
export function proposeAbsences(verdicts: AttendanceVerdict[]): {
  absent: AttendanceVerdict[];
  present: AttendanceVerdict[];
  unsure: AttendanceVerdict[];
} {
  const absent: AttendanceVerdict[] = [];
  const present: AttendanceVerdict[] = [];
  const unsure: AttendanceVerdict[] = [];
  for (const v of verdicts) {
    if (v.active) present.push(v);
    else if (v.unreadable > 0) unsure.push(v);
    else absent.push(v);
  }
  return { absent, present, unsure };
}

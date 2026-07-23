/**
 * Pure argv serializers for the gradebook playwright scripts. Used by
 * `runner.ts` to invoke `floor-scores.mjs` and `scrape-qids.mjs` as Bun
 * subprocesses. No I/O — these are easy to test and easy to reason about.
 */

export interface FloorScoresOpts {
  cid: number;
  aid: number;
  /** When provided, emitted as --label. Used as the CSV output prefix. */
  label?: string;
  /** Path to the work-credit analysis JSON. */
  analysis?: string;
  /** Output directory for CSVs. */
  outDir?: string;
  /** Floor cap as a fraction (e.g. 0.3 = 30%). */
  cap?: number;
  /** Map of question number -> qid, e.g. { 1: '326715749' }. */
  qids?: Record<string, string>;
  /** Chrome profile directory. */
  profile?: string;
  /** Chrome user-data dir. */
  userData?: string;
  /** Dry-run by default. Set true to push scores back to the page. */
  writeBack?: boolean;
  /** Run headless. Defaults to false (headed) for debugging. */
  headless?: boolean;
}

export interface ScrapeQidsOpts {
  cid?: number;
  aid?: number;
  course?: string;
}

function flag(name: string, value: string | number | boolean): string {
  return `--${name}=${value}`;
}

function maybeFlag(name: string, value: string | number | undefined): string[] {
  if (value === undefined || value === null || value === '') return [];
  return [flag(name, value)];
}

export function buildFloorArgs(opts: FloorScoresOpts): string[] {
  const args: string[] = [
    flag('cid', opts.cid),
    flag('aid', opts.aid),
    ...maybeFlag('label', opts.label),
    ...maybeFlag('analysis', opts.analysis),
    ...maybeFlag('out-dir', opts.outDir),
    ...maybeFlag('cap', opts.cap),
    ...maybeFlag('profile', opts.profile),
    ...maybeFlag('user-data', opts.userData),
  ];
  if (opts.qids && Object.keys(opts.qids).length > 0) {
    const qids = Object.entries(opts.qids)
      .map(([k, v]) => `${k}=${v}`)
      .join(',');
    args.push(flag('qids', qids));
  }
  if (opts.writeBack) args.push('--write-back');
  if (opts.headless) args.push('--headless');
  return args;
}

export function buildScrapeQidsArgs(opts: ScrapeQidsOpts): string[] {
  const args: string[] = [];
  if (opts.course) args.push(flag('course', opts.course));
  if (opts.cid !== undefined) args.push(flag('cid', opts.cid));
  if (opts.aid !== undefined) args.push(flag('aid', opts.aid));
  return args;
}

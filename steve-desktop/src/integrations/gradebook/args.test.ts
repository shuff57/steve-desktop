import { describe, it, expect } from 'vitest';
import { buildFloorArgs, buildScrapeQidsArgs, type FloorScoresOpts } from './args';

describe('buildFloorArgs', () => {
  it('serializes cid and aid and label as --flag=value pairs', () => {
    const args = buildFloorArgs({ cid: 306621, aid: 22202268, label: 'unit1' });
    expect(args).toEqual(['--cid=306621', '--aid=22202268', '--label=unit1']);
  });

  it('emits --write-back only when truthy', () => {
    expect(buildFloorArgs({ cid: 1, aid: 2, writeBack: true })).toContain('--write-back');
    expect(buildFloorArgs({ cid: 1, aid: 2, writeBack: false })).not.toContain('--write-back');
  });

  it('emits --qids= when qids map is provided', () => {
    const args = buildFloorArgs({
      cid: 1,
      aid: 2,
      qids: { 1: '326715749', 2: '326715752' },
    });
    expect(args).toContain('--qids=1=326715749,2=326715752');
  });

  it('omits --qids when qids map is empty or missing', () => {
    expect(buildFloorArgs({ cid: 1, aid: 2 })).not.toContain('--qids');
    expect(buildFloorArgs({ cid: 1, aid: 2, qids: {} })).not.toContain('--qids');
  });

  it('emits --cap as decimal fraction', () => {
    expect(buildFloorArgs({ cid: 1, aid: 2, cap: 0.3 })).toContain('--cap=0.3');
  });
});

describe('buildScrapeQidsArgs', () => {
  it('serializes cid and aid only', () => {
    expect(buildScrapeQidsArgs({ cid: 306621, aid: 22202268 })).toEqual([
      '--cid=306621',
      '--aid=22202268',
    ]);
  });

  it('supports --course shortcut key', () => {
    expect(buildScrapeQidsArgs({ course: 'math12-sp26' })).toEqual(['--course=math12-sp26']);
  });
});

describe('FloorScoresOpts defaults', () => {
  it('writeBack defaults to false (dry-run safe)', () => {
    // Confirms the type-level default. If a caller passes undefined, the
    // serializer must not emit --write-back.
    const opts: FloorScoresOpts = { cid: 1, aid: 2 };
    expect(buildFloorArgs(opts)).not.toContain('--write-back');
  });
});

# SPEC (DRAFT) — build and push Chapter 3 Individual Test into master course 334437

**Status:** draft for Steve's review. Nothing here has been built or pushed.

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer (push), mom-question (the one new FRQ)
**Modelled on:** `SPEC-chapter-2-individual-test-PUSH.md` and the Chapter 3 group and practice specs

Read first, all in full: `steve-desktop/skills/mom-transfer/SKILL.md`,
`steve-desktop/mom-content/reference/transfer-rules.md`, `steve-desktop/skills/mom-question/SKILL.md`
(including its **Voice** section).

**Do not touch the message center.** Report at the end.

## Voice (Steve, 2026-10-19)

Direct, plain language. Short concrete word problems stay. Do not rewrite an already-filed question
for voice during the push: flag it in the report. The new FRQ follows the voice from the first draft.

## The assignment

10 slots, **8 × 9 + 14 + 14 = 100**, the same shape as the Chapter 2 individual test. Coverage follows
the 3.7 review, sections 3.1 to 3.5 (3.6 dropped, as in the other two tests).

### Slots 1-8: auto-graded. Slots 1, 3, 5, 7 are filed (attach by `qsetid`). Slots 2, 4, 6, 8 are NEW (drafted 2026-10-19, lint clean, not yet filed or rendered)

| Slot | Section | Sub-topic | File (`questions/probability/…`) | qsetid |
|---|---|---|---|---|
| 1 | 3.1 | conditional via the reduced sample space (two dice) | `q24-conditional-dice-reduced-sample-space.php` | 1874497 |
| 2 | 3.2 | classify three pairs: mutually exclusive, independent or neither | `classify-mutually-exclusive-or-independent.php` | NEW |
| 3 | 3.3 | multiplication rule, joint probability | `multiplication-rule-joint-probability.php` | 1874181 |
| 4 | 3.3 | add exclusive probabilities, then the complement | `add-exclusive-events-then-complement.php` | NEW |
| 5 | 3.4 | both conditional directions, the asymmetry | `q20-class-twoway-asymmetry.php` | 1874605 |
| 6 | 3.4 | read a table of probabilities, test independence | `read-a-table-of-probabilities.php` | NEW |
| 7 | 3.5 | conditional from a Venn | `q36-conditional-from-a-venn.php` | 1874867 |
| 8 | 3.5 | multiply along two tree paths, then add them | `add-the-paths-of-a-tree.php` | NEW |

The four new files are 3 to 4 parts each, scenario varies by seed, text-only (no diagram). File them, read back byte-exact, render on 6+ seeds and answer from the rendered page. `read-a-table-of-probabilities` has a part d whose answer depends on the draw (the independent case is rare but possible): check both branches. Confirm each filed qsetid still resolves in `moddataset.php?id=<qsetid>&cid=334437` before attaching.
Slots 1 and 3 and 5 are in the library but never reviewed against the voice rule: render each on a few seeds,
answer it from the rendered page, and flag anything wordy rather than editing it.

`q36` has an uncommitted SVG label edit in the working tree. Report whether the live copy matches the
working-tree file. Do not re-push it.

### Slot 9: FRQ, filed (`qid` null, never filed)

`questions/frq/probability/q2-sample-space-and-event-setup.php` (3.1). 14 points, `attempts=1`.
Verify it is absent from `reference/question-library.json` before filing. Check its student checklist
and instructor model response against the pattern (lint reports one AI tell at line 114, "not just counted"; recast it) of `q3`/`q4` (rebuilt 2026-10-19); repair it in
place if it differs, the way the practice FRQs were repaired.

### Slot 10: FRQ, NEW (drafted, lint clean, not yet filed)

`questions/frq/probability/q7-addition-rule-overlap-explained.php` (3.3, addition rule). The five
pre-FRQs are already mirrored by `q2` to `q6`, so this one has no pre-FRQ: its categories are new,
in the same 3 / 4 / 3 shape. **Name the Overlap (3)**, **Apply the Addition Rule (4)**, **Explain the
Subtraction (3)**. Three counts (in A, in B, in both), find P(A or B), say why the overlap is
subtracted. It follows the `q5` scaffold: collapsed student checklist, `$rubricanswerbutton` with
green Target lines, Model Narrative Response, `essay`, `editornopaste`, `attempts=1`.
Students have not seen this checklist on a homework, so flag that for Steve.

Lint clean. Still to do: file, read back byte-exact, render 6+ seeds, check Targets against the
numbers shown.

## Zero-overlap gate

None of this test's `file_path` / `qid` values may appear in `chapter-3-group-test.json` or
`chapter-3-practice-test.json`. Checked: no overlap. Held back (unused anywhere): `q38`, `q12`, `q13`, `q15`, `q19`, `q4`, `q17`, `q8`, `q10`, `q23`, `q37`,
`test-mutually-exclusive`, `compare-rates-to-test-independence`, `survey-two-conditionals-full-workup`.

## Course settings

cid **334437**, kind `ind`. Per `reference/intro-stats-assessment-settings.md`, Individual column, and
the Chapter 2 individual test:

| Setting | Value |
|---|---|
| Subtype | Test |
| Attempts / versions | 2 / 1 |
| Penalty | 50% per attempt after the first |
| Early-finish bonus | none |
| Gradebook category | IND, `798370` |
| Late passes | 0 |
| Time limit | 89 min (81 Wednesday), `allowovertime` |
| Passcode | required, set LAST (source: `~/Documents/mom-test-passcodes-2026-27.md`) |
| Display | All questions on one page |
| Scores / answers shown | during / after last attempt |

Undated in the master (`sdatetype=0`, `edatetype=2000000000`).

**Name:** `Chapter 3 Individual Test`.

**A stub already exists:** aid **23444250**, in the Chapter 3 block, next to 23444248 and 23444249.
Attach into the stub; do NOT create a new assessment. Read its settings off
`addassessment2.php?id=23444250&cid=334437` first and change only what differs. Rewrite the student
instructions from the settings read back, with no date. Repoint any stale book link to
`https://oerbookshelf.app/introduction-to-stats/`; do not empty the row.

## Verify

- Slots 1-8: `qtype` off `moddataset.php` still matches (re-attach, not re-file).
- Slots 9-10: byte-exact read-back of all five fields, `qtype` vs the source marker.
- Teacher Preview: answer and submit every part of all 10, polling `.scoreresult`. Work answers from
  the RENDERED page.
- **Done is Score 72/100, Answered 10/10.** The two FRQs are hand-graded and count 0 until marked.
- On the FRQs, open "Show Detailed Solution" and confirm the instructor rubric and model response
  render with no leaked variables.
- Passcode: confirm one is set, never print it. If the passcode file is absent, say so and leave it
  unset.

## Write back

New manifest `books/introduction-to-stats-sh/ind/chapter-3-individual-test.json` (shape of
`chapter-2-individual-test.json`); `target.aid`; `qid` into slots 9-10; the two FRQ entries into
`reference/question-library.json`; then `bun mom-content/reference/sync-index.ts` and confirm
`--check` exits 0. Edit JSON as text. Do not commit.

## Report

Per-question table (slot, title, qid, points, graded correct y/n); final header score; settings
changed vs already correct; new FRQ file, lint, seeds, sample prompt; the live-vs-repo result for
`q36`; anything wordy against the voice rule; **which checks you could not perform.**

## Open questions for Steve

1. **Slot 10 section.** 3.3 addition rule. The group FRQs cover 3.2 and 3.3 (`q3`, `q4`), but `q4` is
   multiplication, so this is a different skill. Say if you want a different section.
2. **After this, the FRQ pool is empty** (`q1` is Chapter 4).

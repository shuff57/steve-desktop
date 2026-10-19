# SPEC (DRAFT) — build and push Chapter 3 Practice Test into master course 334437

**Status:** draft for Steve's review. Nothing here has been built or pushed.

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer (push), mom-question (the two new FRQs)
**Modelled on:** `SPEC-chapter-2-practice-test-PUSH.md` and `SPEC-chapter-3-group-test-PUSH.md`

Read first, all in full: `steve-desktop/skills/mom-transfer/SKILL.md`,
`steve-desktop/mom-content/reference/transfer-rules.md`, `steve-desktop/skills/mom-question/SKILL.md`
(including its **Voice** section).

**Do not touch the message center.** Report at the end.

## Voice (Steve, 2026-10-19)

Direct, plain language. Short concrete word problems stay. Do not rewrite an already-filed question
for voice during the push: flag it in the report. The two new FRQs follow the voice from the first
draft.

## The assignment

14 slots, **12 × 6 + 14 + 14 = 100**, the same shape as the Chapter 3 Group Test and the Chapter 2
practice test. Coverage follows the 3.7 review, sections 3.1 to 3.5 (3.6 is dropped, as in the group
test).

### Slots 1-12: auto-graded, all already filed (attach by `qsetid`, do not re-file)

| Slot | Section | Sub-topic | File (`questions/probability/…`) | qsetid | Health |
|---|---|---|---|---|---|
| 1 | 3.1 | probability of a named card category | `q2-probability-card-draw.php` | 1874487 | ok |
| 2 | 3.1 | king or heart (OR across an overlap, counted from the deck) | `q9-king-or-heart.php` | 1874495 | ok |
| 3 | 3.2 | mutual exclusivity from a two-way table | `q18-genre-twoway-mutex.php` | 1874569 | ok |
| 4 | 3.2 | full two-way workup ending in an independence test | `q21-conditional-2x2-with-independence.php` | 1874571 | ok |
| 5 | 3.3 | test two events for mutual exclusivity | `test-mutually-exclusive.php` | 1874186 | ok |
| 6 | 3.3 | multiplication rule, joint probability | `multiplication-rule-joint-probability.php` | 1874181 | ok |
| 7 | 3.4 | build a two-way table from a word problem | `q29-build-the-table-from-a-word-problem.php` | 1874715 | unchecked |
| 8 | 3.4 | both conditional directions from one table | `q14-twoway-conditional.php` | 1874603 | ok |
| 9 | 3.4 | union and neither from a table | `q30-union-and-neither-from-a-table.php` | 1874716 | unchecked |
| 10 | 3.5 | read four probabilities off a Venn | `q31-venn-four-regions-from-counts.php` | 1874862 | unchecked |
| 11 | 3.5 | two draws without replacement on a tree | `q32-tree-two-draws-without-replacement.php` | 1874868 | unchecked |
| 12 | 3.5 | at least one, two ways | `q39-at-least-one-two-ways.php` | 1874872 | unchecked |

Confirm each qsetid still resolves in `moddataset.php?id=<qsetid>&cid=334437` before attaching.

`q31` and `q32` have uncommitted SVG label edits in the working tree. Before attaching, report
whether each live copy matches the working-tree file (the agent found `q34` and `q35` did). Do not
re-push either one.

### Slots 13-14: FRQ, TWO NEW QUESTIONS TO DRAFT (`qid` null)

Only one Chapter 3 FRQ is unused (`q2`, 3.1), and the individual test needs FRQs too, so Steve
chose to draft two new ones, one per uncovered section. Each is written to match its pre-FRQ
**exactly** (same three categories, same point split, same target sentences), per
`reference/pre-frq-template.md`. The pre-FRQs are already on the 3.4 and 3.5 homeworks, so a student
who worked them has read the prose these reward.

- **Slot 13 (3.4):** `questions/frq/probability/q5-conditional-reading-from-a-table.php`. Mirrors
  `pre-frq-grade-a-conditional-reading.php`. Categories: **Read the Right Counts (3)**, **Compute
  the Conditional (4)**, **State the Direction (3)**. A two-way table of counts, one conditional
  probability to find, and a plain statement of which group the denominator is.
- **Slot 14 (3.5):** `questions/frq/probability/q6-diagram-setup-for-two-draws.php`. Mirrors
  `pre-frq-grade-a-diagram-setup.php`. Categories: **Draw the Structure (3)**, **Fill the Branches or
  Regions (4)**, **Answer the Question Asked (3)**. A without-replacement or Venn scenario, with the
  student setting up the diagram and answering from it. Use a scenario that is NOT the one on slot 12
  of the group test (batteries / name cards / apples) and not the pre-FRQ's own scenario.

Both FRQs follow the shape of the Chapter 3 FRQs already on disk
(`frq/probability/q3-mutually-exclusive-versus-independent.php` and `q4-which-rule-and-why.php`, as
rebuilt 2026-10-19):

- student checklist `$rubric`: collapsed, "Click to View Grading Checklist", a "What to include"
  column, one checkbox per item, the points per category (3 / 4 / 3 = 10);
- instructor `$rubricanswerbutton` in the ANSWER section: "Rubric & Model Response" table with a
  green "Target:" line under every checklist item, a "Model Narrative Response" box, and short
  marking notes;
- `$anstypes = array("essay")`, `$displayformat[0] = 'editornopaste'`, randomized scenario, direct
  plain prompt, attempts = 1 at the question level;
- lint clean (`node reference/question-lint.mjs questions/frq/probability`), rendered on at least 6
  seeds in `testquestion2.php?seed=N`, with the Targets and narrative agreeing with the facts shown;
- verify they are absent from `reference/question-library.json` before filing, then file, read back
  byte-exact, attach.

## Zero-overlap gate

None of this test's 14 `file_path` / `qid` values may also appear in the Chapter 3 Group Test
(`chapter-3-group-test.json`) or the individual test (not built yet). Checked against the group test:
no overlap. Reserve for the individual test (not used here): `q12`, `q13`, `q15`, `q16`, `q17`,
`q19`, `q20`, `q34`, `q36`, `q37`, `q38`, `test-independence-two-ways`, `interpret-conditional-event-in-words`,
`add-then-subtract-the-overlap`, `joint-probabilities-from-a-two-way-table`, `compare-rates-to-test-independence`,
`sample-space-and-complement-of-a-colour`, `two-draws-one-kind-without-replacement`, plus the 3.1 FRQ
`q2`. The practice test's window spans the group test, so a shared question is a leaked answer key.

## Course settings

cid **334437**, kind `practice`. Per `reference/intro-stats-assessment-settings.md`, Practice column,
and the Chapter 2 practice test:

| Setting | Value |
|---|---|
| Subtype | Homework |
| Attempts / versions per question | 3 / 20 |
| Penalty | none |
| Early-finish bonus | 2%, 24h before due (inert while undated) |
| Gradebook category | HW, `798368` in this course |
| Late passes | 2 (Up to 1) |
| Time limit | none |
| Passcode | none |
| Display | All questions on one page |
| Scores / answers shown | during / after last attempt |

Undated in the master (`sdatetype=0`, `edatetype=2000000000`). `allowpractice` disappearing on save is
expected. The FRQ slots carry `attempts=1` at the question level.

**Name:** `Chapter 3 Practice Test`.

**A stub already exists:** aid **23444248**, in the Chapter 3 block (0-2-8-3), next to the group test
stub 23444249 and the individual test stub 23444250. **Attach into the stub; do NOT create a new
assessment.** Read its settings off `addassessment2.php?id=23444248&cid=334437` first and change only
what differs. If it is dated, set it undated. Rewrite the student instructions (`intro`) from the
settings read back, with no date and the real attempt counts.

**Book link:** repoint any stale section link to `https://oerbookshelf.app/introduction-to-stats/`;
do not empty the row (that deletes it).

## Verify, per question

- Slots 1-12: `qtype` off `moddataset.php` still matches (re-attach, not re-file).
- Slots 13-14: byte-exact read-back of all five fields, `qtype` vs the source marker.
- Teacher Preview: ANSWER AND SUBMIT every part of all 14, polling `.scoreresult`. Work answers from the
  RENDERED page.
- **Done is Score 72/100 (102/100 if the early-finish bonus fires), Answered 14/14.** The two FRQs are
  hand-graded and count 0 until marked.
- Slots 7, 9, 10, 11 and 12 and both FRQs are `unchecked` in `reference/question-index.json`. Flag any
  odd behaviour so the regress baseline can be updated.
- On the FRQs, open "Show Detailed Solution" and confirm the instructor rubric and model response
  render with no leaked variables.

## Screenshots

Only for the two new FRQs. Element captures of the whole question block, one shot each, to
`<scratchpad>/chapter-3-practice-slot13.png` and `...slot14.png`. Look at them and say what you see.

## Write back

`target.aid` into a new manifest `books/introduction-to-stats-sh/practice/chapter-3-practice-test.json`
(copy the shape of `chapter-2-practice-test.json`: name, book, kind, slug, status, _note,
coverage_spec, mom_settings, target, questions); `qid` into slots 13-14; the two FRQ entries into
`reference/question-library.json`; then `bun mom-content/reference/sync-index.ts` and confirm
`--check` exits 0. Edit JSON as text. Do not commit.

## Report

Per-question table (slot, title, qid, points, graded correct y/n); final header score; stub aid and
settings changed vs already correct; for each new FRQ the file, lint result, seeds rendered, one sample
prompt and what the screenshot showed; the live-vs-repo result for `q31` and `q32`; anything wordy
against the voice rule; and **which checks you could not perform.** Visual pass/fail on the FRQ
screenshots is Steve's call.

## Open questions for Steve

1. **Individual test FRQs.** After this, only `q2` (3.1) is left. The individual test needs two, so one
   more FRQ has to be drafted (3.2 or 3.3 are uncovered) when you get to it.
2. **Slot 5 and 6 overlap with the group test's idea.** They are different questions, but both cover the
   same 3.3 skill family (test, then multiply). Swap one for an interpretation question if it feels
   repetitive.

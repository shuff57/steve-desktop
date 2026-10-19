# SPEC (DRAFT) — build and push Chapter 3 Group Test into master course 334437

**Status:** draft for Steve's review (3.6 dropped, 2026-10-19). Nothing here has been built or pushed. The Chapter 3 Group Test
stub in the live course is empty (checked 2026-10-19).

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer
**Modelled on:** `SPEC-chapter-2-group-test-PUSH.md` and `books/introduction-to-stats-sh/group/chapter-2-group-test.json`

Read first, both in full:
- `steve-desktop/skills/mom-transfer/SKILL.md`
- `steve-desktop/mom-content/reference/transfer-rules.md`

**Do not touch the message center.** Do not read or write `.msgbox`, do not run `msg.mjs`. Report to
stdout at the end.

## Source of the coverage list: bookSHelf 3.7 Chapter 3 Review

`bookSHelf/docs/introduction-to-stats/chapter-3-probability-topics/learn/review/gen.js` builds 27
problems in six sets. This test follows the first five, **3.6 is dropped** (Steve, 2026-10-19: no
3.6 questions, none in the FRQs either). The two slots 3.6 would have taken go to 3.3 and 3.4, the
review's two largest sets:

| Review set | Section | Review problems | Test slots |
|---|---|---|---|
| Probability words | 3.1 terminology | 1-4 | 1-2 |
| Independent or mutually exclusive | 3.2 | 5-9 | 3-4 |
| The two basic rules | 3.3 multiplication and addition | 10-14 | 5-7 |
| Contingency tables | 3.4 | 15-18 | 8-10 |
| Trees and Venn diagrams | 3.5 with / without replacement | 19-21 | 11-12 |
| ~~Theoretical and empirical~~ | ~~3.6~~ | ~~22-27~~ | dropped |

The review's "explain in your own words" problems on independence and mutual exclusivity (p-05, 07)
are the model for the FRQ slots. Its 3.6 problems (p-22, 26) are not used.

## The assignment

14 slots, **12 × 6 + 14 + 14 = 100** (12 auto from 3.1-3.5, 2 FRQs). Same shape as Chapter 2 so the gradebook weights match.

### Slots 1-12: auto-graded, all already filed (attach by `qsetid`, do not re-file)

| Slot | Sub-topic | File (`questions/…`) | qsetid | Health |
|---|---|---|---|---|
| 1 | 3.1 probability of AND / OR / complement from sets | `probability/q25-prob-set-ops-dice.php` | 1874494 | ok |
| 2 | 3.1 name the conditioning event | `probability/q11-identify-conditioning-event.php` | 1874496 | ok |
| 3 | 3.2 mutually exclusive or not | `probability/q7-mutually-exclusive-yesno.php` | 1874568 | ok |
| 4 | 3.2 multiplication rule for independent events | `probability/q26-independence-multiplication-rule.php` | 1874570 | ok |
| 5 | 3.3 multiplication rule, joint probability | `probability/multiplication-rule-joint-probability.php` | 1874181 | ok |
| 6 | 3.3 addition rule, union probability | `probability/addition-rule-union-probability.php` | 1874184 | ok |
| 7 | 3.3 test two events for independence | `probability/test-independence-two-ways.php` | 1874183 | ok |
| 8 | 3.4 complete a two-way table | `probability/q27-complete-the-twoway-table.php` | 1874711 | unchecked |
| 9 | 3.4 three denominators from one table | `probability/q28-three-denominators-one-table.php` | 1874713 | unchecked |
| 10 | 3.4 union and neither from a table | `probability/q30-union-and-neither-from-a-table.php` | 1874716 | unchecked |
| 11 | 3.5 with versus without replacement | `probability/q33-with-versus-without-replacement.php` | 1874869 | unchecked |
| 12 | 3.5 read a given tree | `probability/q35-read-a-given-tree.php` | 1874866 | unchecked |

Confirm each qsetid still resolves in `moddataset.php?id=<qsetid>&cid=334437` before attaching.

### Slots 13-14: FRQ (`qid` null, never filed): file then attach

Verify both are still absent from `reference/question-library.json` first. A double-file does not undo
cleanly. All three Chapter 3 FRQs currently read `qsetid: null` in `reference/question-index.json`.

- **Slot 13:** `questions/frq/probability/q3-mutually-exclusive-versus-independent.php` (FRQ for 3.2;
  matches review problems 5 and 7). 14 points.
- **Slot 14:** `questions/frq/probability/q4-which-rule-and-why.php` (FRQ for 3.3; addition versus
  multiplication, and which rate belongs in the product). 14 points.

Both are `unchecked` health. Render and answer them as for any new FRQ. Both carry `attempts=1` at the
question level, as the Chapter 2 FRQs do.

`q2-sample-space-and-event-setup.php` (3.1) is held back for the Chapter 3 Individual Test.

## Voice (Steve, 2026-10-19)

Questions are **direct and in plain language.** Say what is asked, with no setup padding, no
cleverness, no vocabulary the course has not taught. That is not a reason to strip the word
problems: a short, concrete context (a survey, a deck of cards, a commute) stays, because applying
the rule to a situation is the skill being tested. Cut the wordiness, keep the scenario.

How this applies here:
- All 12 auto slots are existing, filed questions. **Do not rewrite them for voice** as part of the
  push (a rewrite changes the filed qsetid's text under the homework that uses it). If one reads
  badly against this rule, list it in the report and leave it.
- The two FRQs are filed fresh, so they are the place to check: read both prompts and their student
  checklists against this rule before filing, and flag anything wordy rather than editing the `.php`.
- Any new Chapter 3 question written later (the extra FRQs) follows this voice from the start.

## Zero-overlap gate

None of this test's `file_path` / `qid` values may also appear in a Chapter 3 practice test or
individual test. Neither exists yet. Reason, as for Chapter 2: the practice test's window spans the
group test, so a shared question is a leaked answer key.

Reserve for the other two tests (not used here), so the three can be built disjoint:
`q29`, `q34`, `q36`, `q37`, `q38`, `q39`, `test-mutually-exclusive`,
`q12`, `q13`, `q17`, `q18`, `q21`, plus the 3.1 FRQ `q2`.

## Course settings

cid **334437**, kind `group`. Same settings as the Chapter 2 Group Test, per
`reference/intro-stats-assessment-settings.md`, Group Test column: subtype Test, 3 attempts / 1
version, no penalty, no early-finish bonus, gradebook category GROUP (`798369` in this course), late
passes 0, time limit 89 min regular / 81 min Wednesday with `allowovertime`, all questions on one
page, scores during / answers after last attempt. Undated in the master (`sdatetype=0`,
`edatetype=2000000000`).

**Passcode:** required, from `~/Documents/mom-test-passcodes-2026-27.md`. Set it LAST, since a later
settings save can clear it. Do not invent a code.

**Name:** `Chapter 3 Group Test`.

**A stub already exists** in the live course, in Chapter 3's block, with the name Chapter 3 Group Test.
**Its aid is not recorded anywhere yet**: read it off the live course tree (the stub Steve opened on
2026-10-19), do not guess. Per the Chapter 2 precedent, **do NOT create a new assessment via
`copyfrom`/`addassessment2.php`**: attach into the existing stub. Read its current settings off
`addassessment2.php?id=<aid>&cid=334437` first and change only fields that differ. If it is dated,
set it undated.

**Book link:** check what the stub carries. If it holds a stale section-specific link, repoint it to
`https://oerbookshelf.app/introduction-to-stats/`. Do not empty the row (that deletes it and shifts
the others up).

## Verify, per question

- Slots 1-12: `qtype` off `moddataset.php` still matches (re-attach, never re-file).
- Slots 13-14, once filed: byte-exact read-back (em dash → `--`, strip leading newline),
  `qtype` against the source marker.
- Teacher Preview: ANSWER AND SUBMIT every part of all 14, polling `.scoreresult` before the next.
- Work every answer from the RENDERED page: `choices` shuffle per seed.
- **Done is `100/100`, Answered `14/14`.**
- Slots 8-12 and both FRQs are `unchecked` in `reference/question-index.json`. Flag any odd
  behaviour so the regress baseline can be updated.
- `git status` shows uncommitted edits to `q31`, `q32`, `q34`, `q35`, `q36`, `q38`. **`q35` is slot
  12.** Confirm the edit is finished and committed before it is attached, or the filed copy and the
  repo copy will disagree.

## Screenshots

Only for the two newly filed FRQs (slots 13-14). FULL PAGE, ONE SHOT: `Emulation.setDeviceMetricsOverride`
to `scrollHeight` + margin, single `Page.captureScreenshot`, then clear the override. No stitching.
Save to `steve-desktop/scratchpad/chapter-3-group-slot<N>.png`.

## Write back

Write `books/introduction-to-stats-sh/group/chapter-3-group-test.json` (copy the Chapter 2 manifest's
shape: `name`, `book`, `kind`, `slug`, `status`, `_note`, `coverage_spec`, `mom_settings`, `target`,
`questions`). Then `target.aid`; `qid` into slots 13-14; new `question-library.json` entries for the
two FRQs; then `bun mom-content/reference/sync-index.ts` and confirm `--check` exits clean.
Edit the JSON as text, in place.

## Report to stdout

Per-question table: slot, title, qid, points, graded correct y/n. Final header score, the placement
used, the passcode source read from (never the code itself), and **which checks you could not
perform.** Visual pass/fail on the screenshots is not yours to call.

Do not edit any `.php`.

## Open questions for Steve

1. **FRQ pool is too small for three tests.** Only three Chapter 3 FRQs exist (`q2`, `q3`, `q4`);
   `q1` is expected value (Chapter 4). Chapter 2 gave each test two FRQs from a pool of six. Three
   tests need six. None cover 3.4 contingency tables or 3.5 trees and Venn. Author more, or let the
   practice test run FRQ-free?
2. **Are `q3` and `q4` the right two for the group test?** `q2` (3.1) is held for the individual test.
3. **Pre-FRQ items** (`pre-frq-grade-a-*`, 3.1 to 3.5) are excluded on purpose: they are grade-a-sample
   practice, and the Chapter 2 tests did not use them either.

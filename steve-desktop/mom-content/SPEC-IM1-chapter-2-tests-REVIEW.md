# SPEC — Integrated Math 1 Chapter 2: the three tests, as built

**Status:** pushed live 2026-10-19 (course 334437). Not committed until Steve asks.

**Book:** integrated-math-1. **Pages:** `bookSHelf/docs/integrated-math-1/chapter-2-linear-functions/2.1` to `2.7`.
**Convention:** IM1 Chapter 0. All auto-graded, no FRQs, group-select bundles of interchangeable variants
(a bundle scores once). Every item in a test is worth the same, and each test totals 100.

## Shape

Five blocks of 20 points: A = 2.1 + 2.2, B = 2.3, C = 2.4 + 2.5, D = 2.6, E = 2.7.

| Assessment | aid | Items | Points each | Total | Questions / bundles |
|---|---|---|---|---|---|
| Chapter 2 Group Test | 23996077 | 20 | 5 | 100 | 24 / 4 |
| Chapter 2 Practice Test (was "Chapter 2 Review") | 23996071 | 25 | 4 | 100 | 29 / 4 |
| Chapter 2 Individual Test | 23996080 | 10 | 10 | 100 | 11 / 1 |

Teacher Preview: group 100/100, individual 100/100, practice 102/100 (the 2% early bonus).

## Content

- 38 new questions in `questions/im1/` (qsetids 1940482 to 1940536): 26 written from the pages for the gaps
  the legacy tests never covered (2.4, 2.5, flat and vertical lines, constant growth, standard form,
  choosing a method), plus 12 graphing questions (2.3, 2.4, 2.6, 2.7) with a draw answer.
- Kept legacy variants were audited; seven with defects were left out of every test (435934, 435937,
  1474980, 1474981, 1462424, 1462431, 13874). Zero variant overlap across the three tests, checked by script.
- Draw questions set `$reltolerance[k] = 0.2` so a line with the slope or intercept off by one earns 0.
  One extra dot off the line costs 50%. Dots on the line are free.
- Seven new questions are filed but in no test: 1940486, 1940495, 1940499, 1940504, 1940505, 1940507, 1940509.

## Settings changed

Legacy settings were all Homework-style. Group: versions 20 to 1, category GROUP, no late passes, no early
bonus. Individual: versions 20 to 1, attempts 3 to 2, 50% penalty, category IND, no late passes, no early
bonus. Practice: renamed only. Intros rewritten from the settings read back.

## Left for Steve

- Passcodes on group and individual (empty; the passcode file is not on this machine).
- 45 editable questions in the three tests carry 1 to 3 non-interactive Papermorph Video help buttons (oerbookshelf.app). About 19 legacy questions owned by others keep their YouTube links (read-only in MOM).
- Group and practice have no assessment-level resource links (the stale stats 2.1 link was removed from group, 2026-10-20). Individual still has it.
- Time limit empty, as on the Chapter 0 tests.
- Not tested: student view, timer, passcode entry, phone width, wrong-answer grading on the fresh
  multi-part questions.
- Lint: one old finding in `multiplying-versus-adding-graph-and-crossover.php:60`.

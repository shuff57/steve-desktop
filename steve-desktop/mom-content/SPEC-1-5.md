# SPEC — §1.5 Data Collection Experiment (Intro Stats -SH, lab)

Write **sixteen** IMathAS question files into a new family `mom-content/questions/data-collection/`
(sibling of `data-sampling/`; write `AGENTS.md` there too). This is a **lab**, the second in the
-SH book (4.4 is the precedent — read
`books/introduction-to-stats-sh/lab/4-4-discrete-distribution-playing-card-experiment.json` for the
manifest shape and its pre-FRQ for the lab pre-FRQ pattern).

**The source is the lab itself**, not a problem set: read the bookSHelf section
`bookSHelf/docs/introduction-to-stats/chapter-1-sampling-and-data/1.5_data_collection_experiment.html`
first. Its four parts: (1.5.1) systematic-sampling instructions + Try It 1.5.1 (positions on a
25-name list), (1.5.2) the Movie Survey + Try It 1.5.2 (Kenji's ten values → build all three
columns), (1.5.3) the two lab tables (ungrouped 0..7+, grouped 0-1/2-3/4-5/6-7+) + the four
at-most/more-than questions + Try It 1.5.3 (Camila vs Rosa), (1.5.4) the three discussion
questions. Repackage each worked example's own solution steps into its `$solutionguide`.

## The dataset is RANDOMIZED — per student, per question (Steve's call 2026-08-26)

MOM versions each question independently, so a shared class dataset across slots is impossible.
**Each of slots 4–13 draws its own random dataset and is fully self-contained**: the prompt
states the summary (a class of 60 students collected these counts) and shows its own tables.
Slots 7–10's numbers therefore differ from slot 4's — each question stands alone, and every
answer is precomputed from the question's own draw.

### The generator (shared, verbatim, in every question that shows the data)

Every frequency is a multiple of 3, which makes every relative frequency a multiple of 1/20 —
**exact at 2 and 4 decimals by construction**, so the displayed column IS the true column and the
cumulative column finishes at exactly 1. No rounding drift is possible.

```
$k0 = rand(1, 3)
$k1 = rand(1, 3)
$k2 = rand(1, 3)
$k3 = rand(1, 3)
$k4 = rand(1, 3)
$k5 = rand(1, 3)
$k6 = rand(1, 8) where ($k0 + $k1 + $k2 + $k3 + $k4 + $k5 + $k6 >= 14 && $k0 + $k1 + $k2 + $k3 + $k4 + $k5 + $k6 <= 19)
$k7 = 20 - $k0 - $k1 - $k2 - $k3 - $k4 - $k5 - $k6
$f0 = 3 * $k0 ... $f7 = 3 * $k7       // counts, sum exactly 60
$r0 = $k0 / 20 ... $r7 = $k7 / 20     // relative frequencies, exact to 2 dp
$c0 = $k0 / 20; $c1 = ($k0 + $k1) / 20; ... $c6 = ($k0 + ... + $k6) / 20; $c7 = 1
$g0 = $f0 + $f1; $g1 = $f2 + $f3; $g2 = $f4 + $f5; $g3 = $f6 + $f7
$gc0 = $g0 / 60 ... $gc3 = $g3 / 60   // grouped relative; grouped cum: $c1, $c3, $c5, 1
```

Feasibility of the `where` on $k6: sum(k0..k5) in [6, 18], so the required k6 interval
[14 - sum, 19 - sum] always intersects [1, 8]; the condition depends only on k6's own draw, so
retries always converge, and k7 in [1, 6] follows. **Invariant (enumerate all 3^6 × 8
combinations): every frequency is a multiple of 3, the eight counts sum to 60, every relative
frequency and cumulative is a multiple of 1/20, and the bottom cumulative is exactly 1.** No
`else` clause, no negative count.

The four answers per draw: at most 2 = `c2`, at most 3 = `c3`, more than 2 = `1 - c2`, more
than 3 = `1 - c3`.

**The `which table` choices are structurally identical across the four questions AND across
every dataset draw** — option 0 = ungrouped table + reason, 1 = grouped table + reason, 2 =
either table + reason, 3 = neither + reason. The correct index is fixed by the cut's position,
which grouping makes constant:

| Question | Value | Correct table | Answer index |
|---|---|---|---|
| at most 2 | `c2` | ungrouped only (2 and 3 share a row in grouped) | 0 |
| at most 3 | `c3` | either (cut on the 2-3/4-5 boundary) | 2 |
| more than 2 | `1 - c2` | ungrouped only | 0 |
| more than 3 | `1 - c3` | either (cut on the boundary) | 2 |

Each option's reason text interpolates the draw's own value, so an option never quotes a number
the draw did not produce.

## The sixteen (roster is final)

All fresh (nothing in the bank covers any of these — searched `data-sampling/`,
`descriptive-stats/` for positions/cyclic/systematic mechanics and the at-most/more-than
table-choice pattern; the hurricane-table files are 1.3 material and stay in 1.3).
15 x 6 + 10 = **100**. Lab settings per `reference/intro-stats-assessment-settings.md`:
3 attempts x 20 versions, no penalty, no early finish, GROUP gradebook category, 2 late passes,
no time limit.

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `systematic-sampling-positions.php` | 1 | 6 |
| 2 | `why-twelve-distinct-marks.php` | 2 | 6 |
| 3 | `systematic-versus-srs.php` | 2 | 6 |
| 4 | `ungrouped-table-blanks.php` | 3 | 6 |
| 5 | `cumulative-relative-frequency-named-row.php` | 1 | 6 |
| 6 | `grouped-table-blanks.php` | 3 | 6 |
| 7 | `at-most-two-which-table.php` | 2 | 6 |
| 8 | `at-most-three-which-table.php` | 2 | 6 |
| 9 | `more-than-two-which-table.php` | 2 | 6 |
| 10 | `more-than-three-which-table.php` | 2 | 6 |
| 11 | `grouping-hides-the-question.php` | 2 | 6 |
| 12 | `bottom-row-cumulative-check.php` | 3 | 6 |
| 13 | `camila-vs-rosa-settlement.php` | 3 | 6 |
| 14 | `why-switch-tables.php` | 2 | 6 |
| 15 | `grouping-the-data-differently.php` | 2 | 6 |
| 16 | `pre-frq-grade-a-table-comparison.php` | 3 | 10 |

## Shared conventions

- Five markers, in order. Type picker **Multipart** even for single-part questions.
- The answer key and every derived scalar in COMMON CONTROL. `$solutionguide` emitted from the
  ANSWER section as `$solutionguide`.
- **`numfunc` for every ratio answer** (accepts `0.55`, `55%`, `11/20`), `$abstolerance = 0.00011`
  for 4-dp values so an exact fraction and its 4-dp round both grade; **`number` with
  `$answerformat = "integer"` for counts**.
- **`ntuple` for the 12 positions** — `$displayformat[0] = "set"`, `$answerformat[0] = "anyorder"`,
  prompt shows braces, order does not matter. The wrap arithmetic is the lab's: add 4, and when the
  position exceeds N subtract N once.
- `$noshuffle[N] = "all"` on every choices/multans part.
- Prose rule: **no apostrophes anywhere** (single-quoted `$solutionguide`, double-quoted choice
  strings — reword every possessive). No parentheses in student-visible text (MathJax eats them).
- Solution guides: Step 1 / Step 2 / Step 3 + Answer, repackaging the section's own worked steps
  against the randomized values. Each guide ends with the lab's own lesson line where the section
  has one.
- Name each question for the skill it tests (no `qNN-` prefix). Description ≤ 244 chars.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```
Exit 0 with no new findings for the sixteen files (the family is new and must be clean; any
pre-existing findings are elsewhere and not yours).

**Also do a local math sweep** — a throwaway node/python script that enumerates every random
draw per question (e.g. every N × start pair, every blank-row pair, every m × d pair) and
asserts the per-question invariant. The MOM sandbox seeds deterministically, so a POST loop
cannot vary the draws — the local enumeration is what actually proves every seed. Report the
combination count per question.

**Render each question** through the sandbox (POST the file contents to the sandbox, read the
page body — HTTP 200 proves nothing), fix to clean, then file into the manifest. Then rebuild
the index and replay the regression.

## Report

Per file: invariant swept + combination count, the lint output, and which checks you could not
perform. An honest short list beats sixteen files where two were rushed.
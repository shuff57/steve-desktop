# SPEC — IM1 1.2 "Representing a Growing Pattern"

Written 2026-08-31. **This is a spec, not a push.** Nothing here has been created in MyOpenMath.

Read first: `~/.claude/skills/mom-question/SKILL.md` (authoring),
`mom-content/reference/transfer-rules.md` (pushing), and
`mom-content/reference/find-the-mistake-template.md` (the last slot).

## What this is

`Integrated Math 1`, chapter 1, lesson 1.2 — **Representing a Growing Pattern**.
Book source: `bookSHelf/projects/Integrated Math 1/html/1.2_representing_a_growing_pattern.html`.
Calendar: day 10, Wk 5 · **Fri Sep 11** (period 6 is even-day only).

Course: **cid 340140** (`6 Integrated Math 1 -2627`). IM1 has no separate master course — it is
both the filing home and the taught course, so `target.cid` and `filing.cid` are both `340140`.

## Course state — this is net-new

**No `1.x` lesson assignment exists in IM1.** Measured against the live course, 2026-08-31: 138
assessments, running `0.1`–`0.5` and then `2.1.1` onward. Chapter 1 holds only:

| assessment | aid | questions | what it actually is |
|---|---|---|---|
| HW Solving Math Puzzles | 23498446 | 13 | legacy, CPM problems `1-9`, `1-10` |
| HW Function Machines | 23498447 | 9 | legacy, CPM problems `1-38`, `1-39`, `1-41` |
| HW Functions | 23498448 | 9 | legacy, CPM problem `1-49` "identifying patterns and growth rate" |
| Chapter 1 Group Test | 23498453 | 80 | generic import — opens on Order of Operations |
| Chapter 1 Individual Test | 23498454 | 80 | **the same 80 questions** |
| Chapter 1 Review | 23498475 | 80 | **the same 80 questions again** |

So chapter 1 in MOM predates the current book. The three legacy `HW` assignments are the closest
thing to 1.1–1.5 and are named by the old CPM ordering, not by the book's sections. **Do not
delete or repurpose them in this job** — decide their fate separately (see Open decisions).

There is **no 1.2 stub**, so step 1 of the push is creating the assessment, not filling one.

## Coverage — one slot per sub-topic, taken from the book's own headings

| # | sub-topic | book |
|---|---|---|
| 1 | The border pattern, re-read as a table | 1.2.1 |
| 2 | Adding pattern: figure → table | 1.2.2 step 1 |
| 3 | Adding pattern: name the pattern of change | 1.2.2 step 2 |
| 4 | Adding pattern: write the recursive rule | 1.2.2 step 3 |
| 5 | Adding pattern: write the explicit rule | 1.2.2 step 4 |
| 6 | Adding pattern: the graph | 1.2.2 step 5 |
| 7 | Multiplying pattern: figure → table | 1.2.3 |
| 8 | Multiplying pattern: recursive **and** explicit rule | 1.2.3 |
| 9 | Multiplying pattern: the graph, and how it differs | 1.2.3 |
| 10 | Telling the two apart from a table | 1.2.4 |
| 11 | Telling the two apart — the gap is the information | 1.2.4 |
| 12 | Graphs of situations: read a point off the graph | 1.2.5 |
| 13 | Graphs of situations: where the graph meets the axes | 1.2.5 |
| 14 | Moving between all four representations | 1.2.6 |
| 15 | **Find the mistake** (last slot) | — |

Fifteen slots, inside the 10–15 band and clear of the ten-question floor.

## Tile and block patterns are a REQUIREMENT, not a preference

Steve, 2026-08-31. A growing pattern in this lesson is **a figure made of tiles or dots that a
student can look at and count**. A question that opens with a bare table of numbers and asks for
the rule is testing a different skill and does not belong in slots 2, 3, 7 or 14.

The book's whole spine is *picture → table → rule → graph*, in that order, and the picture is the
step students actually find hard. Every question in the picture-facing slots must show the figure.
Where a question is genuinely about reading a table (slots 10, 11) or a graph (12, 13), a table or
graph is correct — the requirement is about not skipping the figure where the figure is the point.

## Stock — what already exists, by qsetid

**Linear tile patterns: eleven, already in the MOM library**, currently attached to `2.1.1 Growth
in Linear Functions HW` (aid 23498456). They are MyOpenMath shared-library questions — `qid` with
no `file_path` — so they attach directly and must **never** be re-authored:

| qsetid | title |
|---|---|
| 342944 / 342945 / 342946 / 342947 / 343676 / 344390 | Tile Pattern — fill in table, find equation #1–#6 |
| 342752 | Linear tile pattern — find equation from info |
| 342755 | Tile pattern, given f2 and f5, find table and equation |
| 342773 | Tile pattern equation given, predict tiles from figure # |
| 342861 | Tile Pattern to Equation #7 |
| 343616 | Tile pattern, given 2 terms, find slope, equation, tiles in figure n |

**Graph reading: three**, also library-only — `2509`, `1639`, `139228` (evaluate function notation
from a graph). Candidates for slots 12–13.

**Exponential: eight**, from `5.1.1 Representing Exponential Growth` (block `0-7-1-1`) — `249895`,
`249897`, `249906`, `660898`, `660900`, `352009`, `746623`, `772286`. Useful for slots 10–11.

## Correction to the stock survey (2026-08-31, after authoring)

Two claims above were wrong and are corrected here rather than edited out, because both were
reached the same way &mdash; by reading the 2.1.1 *manifest* instead of the bank:

1. **The tile patterns ARE banked locally.** `questions/im1/` holds six
   `tile-pattern-fill-in-table-find-equation-*.php`, pulled from MOM and re-filed under Steve's own
   qsetids `1471375`-`1474982`. The 2.1.1 manifest points at the MyOpenMath originals
   (`342944`&hellip;) and carries no `file_path`, which is what made them look library-only.
2. **Those six do not render a figure.** Every one references an undefined `$tile` variable &mdash;
   the uploaded image did not survive the pull, and nothing defines it. Treat them as BROKEN for
   figure purposes until repaired.

**The template that actually works is `block-pattern-4x-k-find-number-of-blocks-*.php`**, which
builds its figure in code with `showasciisvg("setBorder(); initPicture(); rect(...)")`. That is what
the four new questions copy, and it is the answer to "use tile/block patterns": the figure is
generated and randomised, not uploaded.

## The gap, stated plainly

**Nothing in the bank shows a multiplying pattern as a figure.** Every exponential question found
is table-first. Slots **7, 8 and 9** — the entire 1.2.3 half of the lesson, the doubling dot
pattern the book uses to contrast with adding — have no source and no library match.

Those three must be **authored** under `mom-question`, drawing the figure (see
`questions/draw/` for the drawn-SVG pattern). That is the real work in this assignment; the rest
is assembly. Slot 15's find-the-mistake must be authored too, per
`reference/find-the-mistake-template.md`.

Do not paper over the gap by substituting a table question into slots 7-9. That silently deletes
the lesson's central comparison.

**DONE 2026-08-31.** All four authored, filed into the global class `334437`, attached to
`IM1 1.2 Representing a Growing Pattern` (aid **23987455**), and every one answered and graded
correct in Teacher Preview &mdash; header `Score: 4.08/4, Answered: 4/4`.

| slot | file | qsetid | covers |
|---|---|---|---|
| 7 | `questions/im1/multiplying-pattern-figure-to-table.php` | 1893402 | problems 9, 12 |
| 8 | `questions/im1/multiplying-pattern-recursive-and-explicit-rules.php` | 1893403 | problems 9, 10 |
| 9 | `questions/im1/multiplying-versus-adding-graph-and-crossover.php` | 1893404 | problem 11 |
| 15 | `questions/im1/find-the-mistake-in-a-growing-pattern-rule.php` | 1893405 | problem 21 |

The find-the-mistake is problem 21 verbatim: the X pattern's two diagonals share a centre block, so
`2(2kt+1)` counts it twice and the corrected rule is `4kt+1`. Its error habit &mdash; *counting a
shared piece once per part* &mdash; is new to the used-so-far table.

## Conventions — IM1 is NOT the stats book

Checked across all 57 IM1 `hw` manifests, 2026-08-31:

- **One point per question.** Totals run 6–23 and equal the question count. The "every assignment
  totals exactly 100" rule is a `introduction-to-stats-sh` rule and does **not** apply here. This
  assignment is 15 questions, 15 points.
- **Find-the-mistake, never a pre-FRQ.** IM1 has no FRQs to mirror. One per assignment, last slot.
  Worth knowing: only **1 of 57** IM1 assignments currently carries one, so this is a standard
  being adopted, not one already in force.
- Manifests carry a **`block`** field — the MOM course-page folder path (`2.1.1` is `0-6-3-2`,
  `5.1.1` is `0-7-1-1`). **Read chapter 1's block off the live course at push time; do not guess
  it.** A collapsed block renders none of its children, which is how a populated course reads as
  empty.
- Both `target` and `filing` are `cid 340140`.

## Build order

```
1. author   slots 7, 8, 9 (multiplying pattern as a FIGURE) + slot 15 (find the mistake)
2. audit    qtext-audit / anstypes-audit / usecheck  -- all three, they fail loudly now
3. manifest books/integrated-math-1/hw/1-2-representing-a-growing-pattern.json
4. push     create the assessment (no stub exists), file the 4 new, attach all 15
5. points   1 each; total 15; read back from a FRESH navigation
6. verify   Teacher Preview -- answer every question, confirm each grades correct
```

Step 1 is the only slow part. Steps 3–6 follow `transfer-rules.md` exactly.

## Open decisions — Steve's, not the pusher's

1. **Do the eleven linear tile questions move, or get shared?** The book now introduces tile
   patterns in **1.2**, but they are currently 2.1.1's content. Attaching the same qsetid to both
   is legal and creates no duplicate — but a student then meets the identical questions twice, in
   September and again in chapter 2. The alternatives are to split them (say six to 1.2, five to
   2.1.1) or to re-point 2.1.1 at different material. **This changes what 2.1.1 is**, so it is not
   a call the 1.2 push should make on its own.
2. **What happens to the three legacy `HW` assignments** (`23498446`, `23498447`, `23498448`)?
   They hold real chapter-1 work under old CPM numbering. Rename to match 1.1/1.3/1.4, retire, or
   leave.
3. **The three 80-question chapter 1 tests are identical to each other** and open on Order of
   Operations. That is a separate defect, out of scope here, but it means chapter 1 currently has
   no real test at any of the three levels.

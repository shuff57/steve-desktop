# SPEC — Register Integrated Math 1 (MOM course 340140)

**Book:** integrated-math-1
**Skill:** mom-transfer

Read a live MyOpenMath course and write down what is in it.

**This is a READ-ONLY survey of MyOpenMath.** You create nothing in MOM, you edit nothing in MOM,
you click no Save button on any MOM page, you submit no form. Every MOM request you make is a GET.
The only writes you make are to files on disk, listed under "What you write".

If any path or URL in this spec does not resolve, **STOP and say so in your reply.** Do not guess a
path, a course id, or a repo name. A run that invents a path burns the session and produces nothing.

## Absolute paths

```
content root   C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content
book registry  C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/_books.json
library map    C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/reference/question-library.json
shape example  C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/introduction-to-stats-sh/hw/1-1-definitions-of-statistics-probability-and-key-terms.json
your output    C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/integrated-math-1/
```

## The work

For each of the **83 assessments** listed at the bottom of this spec:

1. GET `https://www.myopenmath.com/course/addquestions2.php?aid=<AID>&cid=340140`
2. Extract every question row from the "Questions in Assessment" table
3. Write one manifest JSON

The course crawl is **already done** — the aid list below is complete and verified. Do not crawl
`course.php` to rediscover it. Do not add an assessment that is not in the list, and do not skip one
that is.

## Extracting a question row

The page renders a table whose columns are `Order | Description | Features | ID | Preview | Type |
Avg Time | Points | Actions`. Per row:

| field | where it comes from |
|---|---|
| `slot` | the `Order` cell — `Q1`, `Q2`, … → integer `1`, `2`, … |
| `title` | the `Description` cell, trimmed |
| `qid` | the `ID` cell. Equivalently the `id=` on that row's `moddataset.php?id=<qsetid>&aid=…` link |
| `points` | the `Points` cell, as a number |

Ignore the `template=true&makelocal=…` variant link — each question has **two** `moddataset.php`
links and only the one without `template=true` carries the real qsetid. Taking both double-counts
every question.

Verified live 2026-08-16 on aid 22313212: 6 questions, ids 468549 / 468616 / 753577 / 766115 /
1178549 / 1178548, types multipart×4 + draw×2, 1 point each. **Use that assessment as your
smoke test before looping** — if your extractor does not return exactly those 6 rows with those
ids, fix the extractor before touching the other 82.

An assessment with zero questions is legitimate. Write the manifest with `"questions": []` and
report it in your reply. Do not skip it.

## Traps that have already bitten on this exact site

Read these. They are not hypothetical; each one cost a real run.

- **IMathAS emits unquoted HTML attributes** (`name=qtype`, `type=radio`). A regex expecting
  `name="qtype"` matches nothing and condemns every question. Prefer the DOM over raw-HTML regex.
  **When a check fails 100% of a population, suspect the check before the data.**
- **A long loop inside one `js()` call blows the CDP read timeout while the work keeps running.**
  One page per `js()` call. Never blind-retry a timeout — re-read state first.
- **`browser-harness`'s `js()` cannot carry a backslash escape or a regex literal.** No `\n`, no
  `/(\d+)/`. Build newlines with `String.fromCharCode(10)`, split on plain strings, and do all real
  pattern work in Python. The reported error points at the escape, not at anything wrong with the page.
- **A CSS selector containing `=` must be quoted**: `a[href*="aid="]`, never `a[href*=aid=]` — the
  latter throws `not a valid selector`.
- **The first DOM extraction after a load can race MathJax** and come back missing rows. Any
  single-shot read needs a second extraction, or a check that the row count is non-zero before
  accepting it.
- **An expired MOM session serves the login form AT the content URL.** A dead session therefore
  looks like a successful survey of an empty course. If a page has an `input[type=password]` or no
  "Questions in Assessment" heading, **STOP and report it** — do not write an empty manifest.
- Several of these assessments warn *"This assessment has already been taken."* That warning is
  expected and harmless on a GET. It is not an error and not a reason to stop. Just never act on it.

## Browser

Drive through **browser-harness**. `new_tab(url)` ONCE at the start, then `goto_url(url)` +
`wait_for_load()` for every navigation after. One tab, navigated — not a tab per page. 83 sequential
page loads through one tab.

## Track assignment — apply this rule mechanically, do not improvise

Match against the assessment name, case-insensitive, **first match wins, in this order**:

| # | name contains | track |
|---|---|---|
| 1 | `group` | `group` |
| 2 | `individual`, `final test`, `mock final` | `ind` |
| 3 | `practice`, `review`, `mock` | `practice` |
| 4 | anything else | `hw` |

Rule 1 beats rule 3, so "Unit 7 Group Quiz" is `group`, not `practice`. If a name genuinely fits
none of these you have made an error — the fallback is `hw`, and every fallback is fine. Report the
track counts; do not invent a fifth track.

## Matching qsetid back to a disk file

`reference/question-library.json` maps `questions/<family>/<file>.php` → `{ qsetid, cid, filed, qtype }`.
Build the reverse map (qsetid → path) **once, in Python, from that file**. Then per slot:

- qsetid present in the map → `"file_path": "questions/..."`
- qsetid absent → `"file_path": null` and `"unbanked": true`

**Never guess a file_path from a title match.** A wrong file_path silently points a future authoring
pass at the wrong source, which is worse than a null. Most IM3 questions are MyOpenMath library
questions (ids like `468549`, `1178549`) rather than entries from this bank (ids like `1867369`), so
**expect the large majority to come back unbanked.** That is the correct, expected result — it is
not a failure, and it is not a reason to loosen the match.


## USE THE PROVIDED EXTRACTOR — do not write your own

`addquestions2.php` renders **two different table layouts**, and the IM3 run silently recorded
8 assessments as having ZERO questions because its extractor only handled one of them. 147
questions were lost and the run's own self-check passed. Do not repeat this.

A validated extractor is on disk. Read it and use it verbatim:

```
C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/reference/extract-assessment-questions.js
```

It is a single JS expression for `js(...)`. It returns
`{empty, rows:[{order, qid, title, points, qtype, features}], cols, note}`.

What it handles that a naive extractor does not:

- **Layout A** (plain): `Order` is a text cell (`Q1`), `Points` is a text cell.
- **Layout B** (checkbox): there is an extra leading `Select` column; `Order` is a **`<select>`
  whose innerText is every option** (`Q1
Q2
Q3…`) so a plain `Q1` text match finds nothing;
  `Points` is an **`<input>`** so the cell text is empty and the value must be read from `.value`;
  and the header reads `Points
Default:`, not `Points`.
- **The library search table on the same page has far more `moddataset` links than the real one**,
  so "the biggest table" picks the wrong one. It anchors on the `Questions in Assessment` heading
  and takes the first following table.
- **Question groups, TWO shapes.** Both have a header row with NO qid and no `moddataset` link:
  - *pool rows carry an order* (`Q1-1`, `Q1-2`, …) — seen in IM3.
  - *pool rows carry NO order at all*; their first cell is an `Ungroup` action link — **this is the
    IM1 shape.** An extractor that requires an Order cell drops the entire pool and keeps only the
    header. That is how `Chapter 6 Practice Test` looked like 1 question instead of 21.
  The extractor emits pool rows with `order: null` and `in_group: "<header order>"`.
  Per-item points live in the header's `Features` cell as `1ea`.

**IM1 uses the checkbox layout (B) throughout** — the layout that silently produced zeros on the
IM3 run. Do not assume the plain layout.

Verified 2026-08-16, all seven shapes:

| course | aid | shape | rows |
|---|---|---|---|
| 285887 | 22313212 | plain | 6 |
| 285887 | 22100096 | checkbox | 6 |
| 285887 | 22100099 | checkbox, large | 60 |
| 285887 | 22437996 | group, `Q1-n` pool | 22 |
| 285887 | 19999830 | genuinely empty | 0 |
| 340140 | 23498440 | checkbox (IM1) | 15 |
| 340140 | 23498498 | group, `Ungroup` pool (IM1) | 21 |

**Smoke-test on `23498440` (expect 15 rows, first id `349431`) and `23498498` (expect 21: one
group header `Q1-15` with `qid: null`, then 20 pool rows) BEFORE looping over the other 81.**
If either returns a different count, STOP — do not proceed and do not "fix" the expectation.

## Question shape

```json
{ "slot": 1, "order": "Q1-1", "file_path": null, "unbanked": true, "qid": "138733",
  "group": true, "title": "...", "points": 1, "features": "1ea", "qtype": "multans" }
```

- `slot` — 1..n in page order, contiguous, INCLUDING group header rows.
- `order` — include **only** when it differs from `Q<slot>` (group pool rows `Q1-1`). Omit entirely for `Ungroup`-style pool rows, which have no order.
- `in_group` — on a pool row, the enclosing group header's order string.
- `group: true` — only on a group header row (the one with `qid: null`).
- `points` — the number MOM shows, or `null`. **Do not invent a points value.** Grouped
  assessments legitimately show none per row.
- `features` — include only when non-empty.

A zero-question assessment is legitimate ONLY when the page literally says
`No Questions currently in assessment`. If you get zero rows without that message, your
extraction failed — retry, and if it still returns zero, STOP and report that aid.

## Manifest shape

One file at `mom-content/books/integrated-math-1/<track>/<slug>.json`.

`<slug>` = name lowercased, every run of non-alphanumeric characters collapsed to a single `-`,
leading/trailing `-` stripped. So `7.2.1 & 7.2.2 Determining Missing Parts of Triangles` →
`7-2-1-7-2-2-determining-missing-parts-of-triangles`. If two assessments in the same track collapse
to the same slug (there are two `Practice Test` and two `Chapter 1 Group Test`-style names in this
course), append `-2`, `-3`, … in aid order, and **list every collision in your reply.**

```json
{
  "name": "Common Angles in the Unit Circle",
  "book": "integrated-math-1",
  "kind": "hw",
  "slug": "common-angles-in-the-unit-circle",
  "target": { "cid": "340140", "aid": "22313212" },
  "filing": { "cid": "340140", "aid": "22313212" },
  "created_at": "2026-08-16",
  "block": "0-4",
  "questions": [
    { "slot": 1, "file_path": null, "unbanked": true, "qid": "468549",
      "title": "Unit Circle Identifying angles", "points": 1 }
  ]
}
```

Read the `introduction-to-stats-sh` example named above first and match its key order and
indentation. Write **no `.md` sidecar** — those are hand-authored blueprints, not survey output.

## Register the book

Add to `books/_books.json`, preserving the existing three entries, the `_purpose` key, and the
file's formatting:

```json
{ "slug": "integrated-math-1", "title": "Integrated Math 1" }
```

## What you write

**Only** these:

```
mom-content/books/_books.json                  (append ONE entry)
mom-content/books/integrated-math-1/**         (new)
```

Do **not** touch `reference/question-index.json` — it is derived and gets rebuilt by
`bun mom-content/reference/sync-index.ts` by whoever reviews this. Do not touch
`reference/question-library.json` (nothing was filed, so nothing changed). Do not edit any question
`.php`. Do not run `git add` or `git commit`.

## Self-check before you reply — report every number

1. **Manifest count == 83.** If not, list which aids are missing. This is the gate; a run that
   writes 82 has failed even if all 82 are perfect.
2. Every written `.json` parses.
3. Per-folder counts match exactly:

   | block | expected |
   |---|---|
   | `0-5` | 1 |
   | `0-6-1` | 1 |
   | `0-6-1-2` | 1 |
   | `0-6-1-3` | 1 |
   | `0-6-1-4` | 1 |
   | `0-6-1-5` | 1 |
   | `0-6-1-6` | 1 |
   | `0-6-2` | 1 |
   | `0-6-2-10` | 3 |
   | `0-6-2-4` | 1 |
   | `0-6-2-5` | 1 |
   | `0-6-2-6` | 1 |
   | `0-6-2-7` | 1 |
   | `0-6-2-9` | 1 |
   | `0-6-3` | 2 |
   | `0-6-3-2` | 1 |
   | `0-6-3-3` | 1 |
   | `0-6-3-4` | 1 |
   | `0-6-3-5` | 1 |
   | `0-6-3-8` | 3 |
   | `0-6-4` | 5 |
   | `0-6-4-4` | 1 |
   | `0-6-4-5` | 1 |
   | `0-6-4-6` | 1 |
   | `0-6-4-7` | 1 |
   | `0-6-4-8` | 1 |
   | `0-6-4-9` | 1 |
   | `0-6-5` | 3 |
   | `0-7` | 3 |
   | `0-7-1-1` | 1 |
   | `0-7-1-2` | 1 |
   | `0-7-1-3` | 1 |
   | `0-7-1-4` | 1 |
   | `0-7-1-5` | 1 |
   | `0-7-1-6` | 1 |
   | `0-7-1-7` | 1 |
   | `0-7-1-8` | 1 |
   | `0-7-1-9` | 1 |
   | `0-7-2` | 11 |
   | `0-7-3` | 10 |
   | `0-7-5` | 10 |
   | `0-7-6` | 1 |

4. Slots within each manifest are 1..n contiguous, no gaps, no duplicate `slot`.
5. No duplicate `qid` within a single manifest. Duplicates **across** manifests are legitimate —
   report them, never remove them.
6. Count of slots with `file_path: null`, and the count with a real path.
7. `_books.json` still contains its three original entries plus the one new one.
8. Total question count across all 83 manifests.

## Reply

Reply through the message center with `--re <id of the message that sent you here>`. Include:

- a table: track | #assignments | #questions | #unbanked
- the per-block counts from check 3, next to the expected column
- every slug collision you had to disambiguate
- every assessment that came back with zero questions
- **one unpinned design decision you had to make that this spec did not settle**
- **explicitly state which checks you could NOT perform, and why**

Do not claim you registered an assessment whose `addquestions2.php` page you did not actually load.

---

## The 83 assessments

| aid | block | name |
|---|---|---|
| 23498439 | 0-5 | Entering Answers |
| 23498487 | 0-7 | Chapter 5 & Chapter 8 Practice Test |
| 23498488 | 0-7 | Chapter 5 & Chapter 8 Group Test |
| 23498489 | 0-7 | Chapter 5 & Chapter 8 Individual Test |
| 23498445 | 0-6-1 | Chapter 0 Group Quiz |
| 23498450 | 0-6-2 | HW Rewriting Expressions with Exponents |
| 23498459 | 0-6-3 | 2.3.1 Writing the Equation of a Line Given the Slope and a Point HW |
| 23498460 | 0-6-3 | 2.3.2 Writing the Equation of a Line Through Two Points HW |
| 23498464 | 0-6-4 | 3.1.1 & 3.1.2 Transformations, Reflections, and Rotations |
| 23498465 | 0-6-4 | 3.1.3 Slopes of Parallel and Perpendicular Lines |
| 23498472 | 0-6-4 | Review |
| 23498473 | 0-6-4 | Chapter 3 Group Test |
| 23498474 | 0-6-4 | Chapter 3 Individual Test |
| 23498475 | 0-6-5 | Chapter 1 Review |
| 23498476 | 0-6-5 | Chapter 2 Review |
| 23498477 | 0-6-5 | Chapter 3 Review |
| 23498478 | 0-7-1-1 | 5.1.1 Representing Exponential Growth |
| 23498479 | 0-7-1-2 | 8.1.1 Investigating y=b^x |
| 23498480 | 0-7-1-3 | 5.1.2 & 8.1.4 Rebound Ratios & Exponential Decay |
| 23498481 | 0-7-1-4 | 8.1.2 & 8.1.5 Multiple Representations of Exponential Functions |
| 23498482 | 0-7-1-5 | 8.2.1 Curve Fitting |
| 23498483 | 0-7-1-6 | 5.2.1 Generating Sequences |
| 23498484 | 0-7-1-7 | 5.2.2 Generalizing Arithmetic Sequences |
| 23498485 | 0-7-1-8 | 5.3.1 Comparing Growth in Tables and Graphs |
| 23498486 | 0-7-1-9 | 5.3.2 Using Multipliers to Solve Problems |
| 23498490 | 0-7-2 | 6.1.1 Working with Multi-Variable Equations |
| 23498491 | 0-7-2 | 6.1.2 Summary of Solving Equations |
| 23498492 | 0-7-2 | 6.1.4 Solving Word Problems by Writing Equations |
| 23498493 | 0-7-2 | 6.2.1 Equal Values Method |
| 23498494 | 0-7-2 | 6.2.2 Substitution Method |
| 23498495 | 0-7-2 | 6.3.1 Elimination Method |
| 23498496 | 0-7-2 | 6.3.2 More Elimination |
| 23498497 | 0-7-2 | 6.4.1 & 6.4.2 Choosing a Strategy for Solving a System |
| 23498498 | 0-7-2 | Chapter 6 Practice Test |
| 23498499 | 0-7-2 | Chapter 6 Group Test |
| 23498500 | 0-7-2 | Chapter 6 Individual Test |
| 23498501 | 0-7-3 | 7.1.1 Determining Similarity & Defining Congruence |
| 23498502 | 0-7-3 | 7.1.2 Conditions for Triangle Congruence |
| 23498503 | 0-7-3 | 7.1.3 & 7.1.4 Justifying Triangle Congruence Using Flowcharts |
| 23498504 | 0-7-3 | 7.1.5 More Conditions for Triangle Congruence |
| 23498505 | 0-7-3 | 7.1.7 More Congruence Flowcharts |
| 23498506 | 0-7-3 | 7.2.1 Studying Quadrilaterals on a Coordinate Grid |
| 23498507 | 0-7-3 | Coordinate Geometry and Midpoints |
| 23498508 | 0-7-3 | Chapter 7 Practice Test |
| 23498509 | 0-7-3 | Chapter 7 Group Test |
| 23498510 | 0-7-3 | Chapter 7 Individual Test |
| 23498511 | 0-7-5 | 9.1.1 Solving Linear, One-Variable Inequalities |
| 23498512 | 0-7-5 | 9.1.2 More Solving Inequalities |
| 23498513 | 0-7-5 | 9.1.3 Solving Absolute Value Equations and Inequalities |
| 23498514 | 0-7-5 | 9.2.1 Graphing Two-Variable Inequalities |
| 23498515 | 0-7-5 | 9.2.2 Graphing Linear and Nonlinear Inequalities |
| 23498516 | 0-7-5 | 9.3.1 Systems of Inequalities |
| 23498517 | 0-7-5 | 9.3.2 More Systems of Inequalities |
| 23498518 | 0-7-5 | Chapter 9 Practice Test |
| 23498519 | 0-7-5 | Chapter 9 Group Test |
| 23498520 | 0-7-5 | Chapter 9 Individual Test |
| 23498521 | 0-7-6 | Final Review |
| 23498440 | 0-6-1-2 | 0.1 Use the Language of Algebra (homework) |
| 23498441 | 0-6-1-3 | 0.2 Integers (homework) |
| 23498442 | 0-6-1-4 | 0.3 Fractions (homework) |
| 23498443 | 0-6-1-5 | 0.4 Decimals (homework) |
| 23498444 | 0-6-1-6 | 0.5 Properties of Real Numbers (homework) |
| 23498446 | 0-6-2-4 | HW Solving Math Puzzles |
| 23498447 | 0-6-2-5 | HW Function Machines |
| 23498448 | 0-6-2-6 | HW Functions |
| 23498449 | 0-6-2-7 | HW Domain and Range |
| 23498451 | 0-6-2-9 | HW Zero and Negative Exponents |
| 23498452 | 0-6-2-10 | Practice Test |
| 23498453 | 0-6-2-10 | Chapter 1 Group Test |
| 23498454 | 0-6-2-10 | Chapter 1 Individual Test |
| 23498455 | 0-6-3-2 | 2.1.1 Growth in Linear Functions HW |
| 23498456 | 0-6-3-3 | 2.1.2 Comparing Slopes |
| 23498457 | 0-6-3-4 | 2.1.3 Slope HW |
| 23498458 | 0-6-3-5 | 2.1.4 Slope and mx+b HW |
| 23498461 | 0-6-3-8 | Practice Test |
| 23498462 | 0-6-3-8 | Chapter 2 Group Test |
| 23498463 | 0-6-3-8 | Chapter 2 Individual Test |
| 23498466 | 0-6-4-4 | 3.1.4 Defining Rigid Transformations |
| 23498467 | 0-6-4-5 | 3.1.6 Symmetry and Angles |
| 23498468 | 0-6-4-6 | 3.2.1 & 3.2.2 Exploring the Area Model |
| 23498469 | 0-6-4-7 | 3.2.3 Multiplying Polynomials and the Distributive Property |
| 23498470 | 0-6-4-8 | 3.3.1 Multiple Methods for Solving Equations |
| 23498471 | 0-6-4-9 | 3.3.2 Fraction Busters |

Blocks `0-8` (`Formatting Templates`, hidden — 3 assessments) and `0-9` (`XXXXXXXX`, hidden — 52
assessments, an archived OpenStax Intermediate Algebra course) are **deliberately excluded**.
Do not register them. Do not crawl them.

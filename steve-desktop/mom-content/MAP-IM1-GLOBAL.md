# MAP - Global class structure for Integrated Math 1

Written 2026-08-31. **This is a map, not a build.** Nothing here has been created in the global class.

## Where each number comes from

| source | what it defines |
|---|---|
| `bookSHelf/projects/Integrated Math 1/book-outline.md` | the AUTHORITATIVE chapter and section numbering + titles |
| `Syllabus - IM1 2026-2027.md` | the taught spine: Chapters 0-8 only, with date ranges |
| live IM1 course `cid 340140` | what content actually exists today, and under which old numbering |

The book is the target. The live course is the supply.

## The live course carries THREE numbering systems, and they collide

This is the single most important thing on this page. `chapter 5` in the live course means two
unrelated things depending on which assignment you look at:

| system | example | what it is |
|---|---|---|
| CPM three-level | `5.1.1 Representing Exponential Growth` | the real IM1 teaching spine |
| OpenStax algebra | `5.1 Add and Subtract Polynomials (Homework)` | leftovers from a different textbook |
| book (target) | `Chapter 5 - Systems of Equations` | what the section SHOULD be called |

So a rename done by chapter number alone would merge polynomial homework into systems of
equations. Every move below is keyed to the assignment **aid**, never to its number.

## The chapter shift

**Authoritative source: `_cpm_spine.md` in bookSHelf, not this file.** The book stopped
sharing CPM's chapter numbers on 2026-08-17, when three CPM chapters became lettered
appendices. What is left is a clean one-to-one map:

```
CPM 1 Functions                          -> book 1
CPM 2 Linear Functions                   -> book 2
CPM 3 Transformations and Solving        -> book 3
CPM 4 Modeling Two-Variable Data         -> Appendix B   (not taught)
CPM 5 Sequences                          -> book 4       <-- shift
CPM 6 Systems of Equations               -> book 5       <-- shift
CPM 7 Congruence and Coordinate Geometry -> book 6       <-- shift
CPM 8 Exponential Functions              -> book 7       <-- shift
CPM 9 Inequalities                       -> book 8       <-- shift
CPM 10 Functions and Data                -> Appendix C   (not taught)
CPM 11 Constructions and Synthesis       -> Appendix D   (not taught)
```

Chapter 0 (Foundations) is this book's own, with no CPM counterpart.

**CORRECTION, 2026-09-01.** An earlier draft of this page claimed chapter 7 was a SPLIT fed
by both CPM 5.1 and CPM 8.x. That was wrong, and it was reached by reasoning about topic
names instead of reading `_cpm_spine.md`. CPM 5.1 (Representing Exponential Growth, Rebound
Ratios, the Bouncing Ball) maps to **book 4.1 and 4.2**, inside Sequences: exponentials
arrive informally as sequences in CPM 5 and formally as functions in CPM 8. Nothing is
split. The push follows the spine.

## The global class is ALREADY organised by course (measured 2026-08-31)

`course.php?cid=334437&folder=0` resolves the open question that the first draft of this map could
not: the global class is a **multi-course** container, and it already has an **empty Integrated
Math 1 block waiting**.

```
Global Question Class  334437
|
+- 0-2  Introduction to Stats
|    +- 0-2-4  Welcome to Introduction to Statistics
|    +- 0-2-5  Textbook, Learning objectives, and Chapter summaries
|    +- 0-2-6  Semester 1      28 items, chapter sub-blocks 0-2-6-1 .. 0-2-6-5
|    +- 0-2-7  Semester 2      chapter sub-blocks 0-2-7-1 .. 0-2-7-7
|
+- 0-3  Integrated Math 1      <-- EMPTY
+- 0-4  Integrated Math 3      <-- EMPTY
|
+- (loose at root) Entering Answers
+- (loose at root) 8.5 Additional Information and Full Hypothesis Test Examples   <-- stray stats item
+- (loose at root) IM1 1.2 Representing a Growing Pattern                          <-- MISPLACED
```

So "does the global class hold IM1" was never a question - the block was made for it. Two
consequences:

1. **`IM1 1.2 Representing a Growing Pattern` (aid 23987455) is in the wrong place.** It sits loose
   at the course root instead of inside `0-3`. It was created there because the new-assessment form
   was opened with `block=0`. Move it once the chapter blocks exist.
2. **The assessment name should lose its `IM1 ` prefix** once it lives under the Integrated Math 1
   block, to match how the stats assignments are named (`1.1 Definitions of Statistics...`, not
   `Stats 1.1 ...`). The block supplies the course, the name supplies the section.

Also loose at root: `8.5 Additional Information and Full Hypothesis Test Examples`, a stats item
that belongs in `0-2-7`. Not this job, but it is the same class of mistake.

## BUILT - the block scaffold, as it stands 2026-09-01

Thirteen blocks under `0-3`. Titles use a **colon**, not an em dash (Steve, mid-build).

| folder id | block |
|---|---|
| `0-3-1` | Welcome to Integrated Math 1 |
| `0-3-2` | Textbook, Learning objectives, and Chapter summaries |
| `0-3-3` | **Semester 1** |
| `0-3-3-1` | Chapter 0: Foundations |
| `0-3-3-2` | Chapter 1: Functions |
| `0-3-3-3` | Chapter 2: Linear Functions |
| `0-3-3-4` | Chapter 3: Transformations and Solving |
| `0-3-4` | **Semester 2** |
| `0-3-4-1` | Chapter 4: Sequences |
| `0-3-4-2` | Chapter 5: Systems of Equations |
| `0-3-4-3` | Chapter 6: Congruence and Coordinate Geometry |
| `0-3-4-4` | Chapter 7: Exponential Functions |
| `0-3-4-5` | Chapter 8: Inequalities |

The first attempt came out with Semester 2 displayed before Semester 1 and four em-dash
duplicate chapters that a delete had reported as removed without removing. The whole of
`0-3` was purged and rebuilt in one pass, so the folder ids above are NOT the ones an
earlier version of this page listed.

### A new BLOCK inserts at the TOP of its parent; a new ASSESSMENT appends at the BOTTOM

Two different rules on the same page, and getting them backwards silently reverses a whole
chapter. Blocks are created in REVERSE display order. Assessments are created in FORWARD
order, which is why every chapter below could be built by pushing its assignments in
book-section order.

### Deleting a block or an assessment

`deleteblock.php?cid=&id=<folder>&bid=<blockid>&remove=ask` for a block,
`deleteassessment.php?id=<aid>&cid=&remove=ask` for an assessment. Then **click the
button**: `form button[name="remove"][value="really"]`.

Two traps, both of which produced a silent no-op that got logged as a success:

1. **`form.submit()` drops the submit button's own `name=value`**, so `remove=really` is
   never sent. The page redirects and nothing is deleted. Click the button.
2. **Matching the button on `e.value` matches the string `"really"`, not the label.** A
   matcher looking for a "yes, delete" label against `e.value || e.textContent` therefore
   never fires. That is what left four duplicate chapter blocks in place while the run
   reported them deleted.

**`bid` is stable; the folder id is POSITIONAL and renumbers when anything before it is
deleted.** Delete last-first, re-read between deletes, and guard on the block NAME read off
the confirm screen before submitting.

## BUILT 2026-09-01 - the assignments

80 assessments, every one verified on question count, qsetid ORDER and points total, and
every one render-checked in Teacher Preview with zero `Eeek!` and zero PHP errors.

| block | chapter | items |
|---|---|---|
| `0-3-3-1` | Chapter 0: Foundations | 5 hw + group quiz |
| `0-3-3-2` | Chapter 1: Functions | 7 hw + review, practice, group, individual |
| `0-3-3-3` | Chapter 2: Linear Functions | 6 hw + 4 tests |
| `0-3-3-4` | Chapter 3: Transformations and Solving | 8 hw + 4 tests |
| `0-3-4` | Semester 2 (loose) | Final Review |
| `0-3-4-1` | Chapter 4: Sequences | 6 hw + 3 tests (chapters 4 and 7 combined) |
| `0-3-4-2` | Chapter 5: Systems of Equations | 8 hw + 3 tests |
| `0-3-4-3` | Chapter 6: Congruence and Coordinate Geometry | 7 hw + 3 tests |
| `0-3-4-4` | Chapter 7: Exponential Functions | 3 hw |
| `0-3-4-5` | Chapter 8: Inequalities | 4 hw + 3 tests |

Names were **renumbered onto the book** (Steve, 2026-09-01), so "Equal Values Method" is
`5.3`, not `6.2.1`. Each manifest keeps its CPM name under `source.name`.

**Chapter 1 was the exception, and was renamed 2026-09-01.** It still carried the legacy
`HW <Title>` names after the build. The six homework assignments are now
`1.1 Solving Math Puzzles`, `1.3 Function Machines`, `1.3 Functions`, `1.4 Domain and Range`,
`1.5 Rewriting Expressions with Exponents`, `1.5 Zero and Negative Exponents`; `1.2 Representing
a Growing Pattern` was already correct and the four tests keep `Chapter 1 <Kind>`, matching
chapters 2, 3 and 5.

The section number did NOT have to be inferred from the title -- **every manifest already carried a
`book_url` naming its book section**, and all seven agreed with the CPM->book mapping
(`1.1_working_problems_as_a_team`, `1.3_inputs_outputs_and_what_makes_a_function` twice,
`1.5_working_with_exponents` twice, and so on). Read `book_url` before reasoning about a title;
it is the cheapest authoritative answer in the repo, and this map has already been wrong once
from arguing about topic names.

Renaming writes ONE field. `addassessment2.php?id=<aid>&cid=<cid>` -- `?id=`, never `?aid=`, which
is a create form that saves a duplicate -- poll `[name=name]` to populate, guard on the expected
old name so a re-run is a no-op, set through the native setter plus `input`/`change` because the
form is Vue, re-send `assmpassword` (a save clears it), then click the single `Save Changes`
submit. Verify from a fresh navigation that `sdate`/`stime`/`edate`/`etime`, the passcode and
`extreflinks[]` are unchanged, and that the block still holds the same number of items -- a
duplicate is the failure this last check catches.

### Question groups

A group ("Select 1 from a group of 4") CANNOT be attached by URL: `modquestion2.php`
attaches one question. The working recipe is to attach every member flat in manifest order,
set per-question points through `itemarray[i][4]` plus `submitChanges()`, then reload and
call the page's own `groupSelected()` once per group with that group's row checkboxes
ticked, **highest row index first**, because grouping renumbers every row behind it. One
final `submitChanges()` persists the lot.

A group scores ONCE, because `groupSelected()` takes the group's points from the first
member, so an assignment's points total sits below its question count wherever groups are
used. That is correct, not a defect.

### Points are NOT all 1

`usedef=true` on the attach gives the LIBRARY default, which is 1. Several assignments are
not 1 point per question (Zero and Negative Exponents is 2, several tests run 2 or 3), so
the manifest's own points have to be replayed afterwards, or the totals come out wrong
while every count still looks right.

## Still outstanding

1. **Book links, chapters 2-8.** bookSHelf has only chapters 0 and 1 written, so there is
   no page to link. Those 68 assignments still carry the **stats 2.1** Book link inherited
   from the `hw` template. Steve's call 2026-09-01 was to skip it for now; it is a scripted
   sweep once the chapters publish. Chapters 0 and 1 are correctly linked.
2. **`8.1 Solving Linear, One-Variable Inequalities`** was not pushed. Its manifest holds a
   single question and that question is a local `.php` with no qsetid, so it needs a real
   filing step through `moddataset.php`, not an attach.
3. **`9.2.2 Graphing Linear and Nonlinear Inequalities`** and **`9.3.2 More Systems of
   Inequalities`** were not pushed: both manifests are empty.
4. **1.2 carries eight sequence questions.** Slots 1, 3, 4, 5, 6, 10, 11 and 12 are
   sequence questions, and two of them teach *arithmetic sequence*, *common difference* and
   *nth term*, which is Chapter 4 vocabulary the book has not introduced at 1.2. Steve,
   2026-09-01: do not use the term "sequence" in 1.2. Removing them leaves seven questions,
   below the ten-question floor, so replacements have to be authored in the book's own
   language (pattern, constant difference, constant ratio, explicit rule), each with a
   figure.

## Defects inherited from the live course, not caused by the push

- **Chapter 5's practice, group and individual tests each put all 20 questions in ONE
  group**, so each renders as a single question worth a single point.
- **Chapter 1's Review, Group Test and Individual Test are the same 65 questions**, three
  times over.
- The three legacy `HW ...` assignments in Chapter 1 still carry CPM-era names rather than
  book section numbers. Whether they become 1.1/1.3/1.4/1.5 is a separate decision.

## Original scaffold plan (superseded by the table above)

Copying the stats pattern exactly, with the semester split taken from the syllabus date ranges:

```
0-3  Integrated Math 1
 +- Welcome to Integrated Math 1
 +- Textbook, Learning objectives, and Chapter summaries
 +- Semester 1
 |   +- Chapter 0 - Foundations                        Aug 17 - Sep 04
 |   +- Chapter 1 - Functions                          Sep 09 - Sep 29
 |   +- Chapter 2 - Linear Functions                   Oct 01 - Oct 27
 |   +- Chapter 3 - Transformations and Solving        Oct 29 - Dec 02
 +- Semester 2
     +- Chapter 4 - Sequences                          Jan 04 - Jan 25
     +- Chapter 5 - Systems of Equations               Jan 27 - Feb 24
     +- Chapter 6 - Congruence and Coordinate Geometry Feb 26 - Mar 31
     +- Chapter 7 - Exponential Functions              Apr 02 - Apr 22
     +- Chapter 8 - Inequalities                       Apr 24 - end
```

**13 blocks**, then the 79 assessments land inside them. Build the blocks FIRST - a collapsed block
renders none of its children, so 79 assessments dropped at root is both unusable and, per
`transfer-rules.md`, the exact shape that makes a populated course read as empty to a later crawl.

## Section-by-section map

`aid` is the live IM1 assessment that supplies the content. A blank aid is a genuine gap.

### Chapter 0 - Foundations

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `0.1` | Use the Language of Algebra | 0.1 Use the Language of Algebra (homework) | 23498440 |
| `0.2` | Integers | 0.2 Integers (homework) | 23498441 |
| `0.3` | Fractions | 0.3 Fractions (homework) | 23498442 |
| `0.4` | Decimals | 0.4 Decimals (homework) | 23498443 |
| `0.5` | Properties of Real Numbers | 0.5 Properties of Real Numbers (homework) | 23498444 |

### Chapter 1 - Functions

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `1.1` | Working Problems as a Team | HW Solving Math Puzzles | 23498446 |
| `1.2` | Representing a Growing Pattern | HW Function Machines | 23498447 |
| `1.3` | Inputs, Outputs, and What Makes a Function | HW Functions | 23498448 |
| `1.4` | Domain and Range | HW Domain and Range | 23498449 |
| `1.5` | Working with Exponents | HW Rewriting Expressions with Exponents | 23498450 |

### Chapter 2 - Linear Functions

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `2.1` | Recognizing Growth in Linear Functions | 2.1.1 Growth in Linear Functions HW | 23498455 |
| `2.2` | Comparing Change to Find Slope | 2.1.2 Comparing Slopes | 23498456 |
| `2.3` | Slope-Intercept Form | 2.1.4 Slope and mx+b HW | 23498458 |
| `2.4` | Linear Models and Rate of Change | 2.1.3 Slope HW | 23498457 |
| `2.5` | Writing Equations and Working with Units | **none** | - |
| `2.6` | Writing the Equation of a Line | 2.3.1 Writing the Equation of a Line Given the Slope a | 23498459 |
| `2.7` | From Graphs and Tables | 2.3.2 Writing the Equation of a Line Through Two Point | 23498460 |

### Chapter 3 - Transformations and Solving

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `3.1` | Moving Figures in the Plane | 3.1.1 & 3.1.2 Transformations, Reflections, and Rotati | 23498464 |
| `3.2` | Slopes of Parallel and Perpendicular Lines | 3.1.3 Slopes of Parallel and Perpendicular Lines | 23498465 |
| `3.3` | Defining and Applying Rigid Transformations | 3.1.4 Defining Rigid Transformations | 23498466 |
| `3.4` | Symmetry | 3.1.6 Symmetry and Angles | 23498467 |
| `3.5` | From Algebra Tiles to the Generic Area Model | 3.2.1 & 3.2.2 Exploring the Area Model | 23498468 |
| `3.6` | Multiplying Polynomials and the Distributive Property | 3.2.3 Multiplying Polynomials and the Distributive Pro | 23498469 |
| `3.7` | Strategies for Solving Equations | 3.3.1 Multiple Methods for Solving Equations | 23498470 |

### Chapter 4 - Sequences

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `4.1` | Recognizing Exponential Growth | 5.1.1 Representing Exponential Growth | 23498478 |
| `4.2` | Modeling Exponential Decay | 5.1.2 & 8.1.4 Rebound Ratios & Exponential Decay | 23498480 |
| `4.3` | Generating and Generalizing Sequences | 5.2.1 Generating Sequences | 23498483 |
| `4.4` | Comparing Growth and Using Multipliers | 5.3.1 Comparing Growth in Tables and Graphs | 23498485 |
| `4.5` | Sequences as Functions | 5.2.2 Generalizing Arithmetic Sequences | 23498484 |

### Chapter 5 - Systems of Equations

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `5.1` | Rewriting and Solving Multi-Variable Equations | 6.1.1 Working with Multi-Variable Equations | 23498490 |
| `5.2` | From Word Problems to Equations | 6.1.4 Solving Word Problems by Writing Equations | 23498492 |
| `5.3` | Equal Values and Substitution | 6.2.1 Equal Values Method | 23498493 |
| `5.4` | Systems and Multiple Representations | 6.2.2 Substitution Method | 23498494 |
| `5.5` | Solving Systems by Elimination | 6.3.1 Elimination Method | 23498495 |
| `5.6` | Systems, Solutions, and Graphs | 6.3.2 More Elimination | 23498496 |
| `5.7` | Choosing a Method for a System | 6.4.1 & 6.4.2 Choosing a Strategy for Solving a System | 23498497 |

### Chapter 6 - Congruence and Coordinate Geometry

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `6.1` | Congruence and the Triangle Conditions | 7.1.1 Determining Similarity & Defining Congruence | 23498501 |
| `6.2` | Flowchart Proof | 7.1.3 & 7.1.4 Justifying Triangle Congruence Using Flo | 23498503 |
| `6.3` | Further Congruence Conditions | 7.1.5 More Conditions for Triangle Congruence | 23498504 |
| `6.4` | Congruence Through Rigid Transformations | 7.1.7 More Congruence Flowcharts | 23498505 |
| `6.5` | Studying Quadrilaterals on a Grid | 7.2.1 Studying Quadrilaterals on a Coordinate Grid | 23498506 |
| `6.6` | Distance, Midpoint, and Shapes on a Grid | Coordinate Geometry and Midpoints | 23498507 |

### Chapter 7 - Exponential Functions

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `7.1` | Representing Exponential Functions | 8.1.1 Investigating y=b^x | 23498479 |
| `7.2` | Exponential Growth and Decay | 5.3.2 Using Multipliers to Solve Problems | 23498486 |
| `7.3` | Connecting the Representations | 8.1.2 & 8.1.5 Multiple Representations of Exponential  | 23498481 |
| `7.4` | Fitting Curves to Data | 8.2.1 Curve Fitting | 23498482 |
| `7.5` | Solving Exponential Systems Graphically | **none** | - |

### Chapter 8 - Inequalities

| book | title | live IM1 assignment | aid |
|---|---|---|---|
| `8.1` | Solving Inequalities in One Variable | 9.1.1 Solving Linear, One-Variable Inequalities | 23498511 |
| `8.2` | Absolute Value Equations and Inequalities | 9.1.3 Solving Absolute Value Equations and Inequalitie | 23498513 |
| `8.3` | Graphing Inequalities in Two Variables | 9.2.1 Graphing Two-Variable Inequalities | 23498514 |
| `8.4` | Systems of Inequalities | 9.3.1 Systems of Inequalities | 23498516 |
| `8.5` | Solving Problems with Inequalities | 9.1.2 More Solving Inequalities | 23498512 |

## What the map does NOT claim

- The pairings are by **topic**, read off the assignment titles. They are a proposal, not a
  verified content match - nobody has opened these assignments and checked the questions inside.
- Two sections have no supply at all and need authoring: **2.5 Writing Equations and Working with
  Units** and **7.5 Solving Exponential Systems Graphically**.
- Appendices A-D exist in the book but are **not in the syllabus year-at-a-glance**, so they are
  out of scope until Steve says otherwise.

## The 88 live assessments this map does not use

Mostly a different textbook, plus scaffolding and duplicates. Listed so nothing is silently lost:

**OpenStax algebra leftovers** (36)

- `23498527` 11.1 Distance and Midpoint Formula (homework)
- `23498530` 9.7 Graph Quadratic Functions Using Transformations (homework)
- `23498531` 3.6 Graphs of functions (homework) (OLD)
- `23498534` 9.1 Solve Quadratic Equations by Square Root Property
- `23498535` 9.2 Solve Quadratic Equations by Completing the Square
- `23498536` 9.3 Solve Quadratic Equations by Quadratic Formula
- `23498537` 9.6 Graph Quadratic Using Properties (homework)
- `23498539` 8.1 Simplify Expressions With Roots
- `23498540` 8.2 Simplify Radical Expressions
- `23498541` 8.3 Simplify Rational Exponents (homework)
- `23498542` 8.4 Add, Subtract, and Multiply Radical Expressions
- `23498543` 8.5 Divide Radical Expressions (homework)
- `23498544` 8.6 Solve Radical Equations (homework)
- `23498545` 8.7 Use Radicals in Functions (online homework)
- `23498548` 7.1 Multiply and Divide Rational Expressions (homework)
- `23498549` 7.2 Add and Subtract Rational Expressions (homework)
- `23498550` 7.4 Solve Rational Equations (Homework)
- `23498552` 6.1 GCF and Factor by Grouping (Homework)
- `23498553` 6.2 Factor Trinomials (homework)
- `23498554` 6.3 Factor Special Products (homework)
- `23498555` 6.5 Polynomial Equations (homework)
- `23498557` 5.1 Add and Subtract Polynomials (Homework)
- `23498558` 5.2 Properties of Exponents and Scientific Notation (Homework)
- `23498559` 5.3 Multiply Polynomials (Homework)
- `23498560` 5.4 Dividing Polynomials (Homework)
- `23498563` 4.1 Systems of Linear Equations (Homework)
- `23498565` 3.1 Graph Linear Equations in Two Variables (Homework)
- `23498566` 3.2 Slope of a Line (Homework)
- `23498567` 3.3 Find the Equation of a line (homework)
- `23498568` 3.5 Relations and Functions (homework)
- `23498569` 3.6 Graphs of functions (homework)
- `23498571` 2.1 Use a General Strategy to Solve Linear Equations (Homework)
- `23498572` 2.3 Solving a Formula for a Specific Variable (homework)
- `23498573` 2.5 Solve Linear Inequalities (Homework)
- `23498574` 2.6 Solve Compound Inequalities (Homework)
- `23498575` 2.7 Solve Absolute Value Inequalities (Homework)

**tests, quizzes and reviews** (42)

- `23498445` Chapter 0 Group Quiz
- `23498452` Practice Test
- `23498453` Chapter 1 Group Test
- `23498454` Chapter 1 Individual Test
- `23498461` Practice Test
- `23498462` Chapter 2 Group Test
- `23498463` Chapter 2 Individual Test
- `23498472` Review
- `23498473` Chapter 3 Group Test
- `23498474` Chapter 3 Individual Test
- `23498475` Chapter 1 Review
- `23498476` Chapter 2 Review
- `23498477` Chapter 3 Review
- `23498487` Chapter 5 & Chapter 8 Practice Test
- `23498488` Chapter 5 & Chapter 8 Group Test
- `23498489` Chapter 5 & Chapter 8 Individual Test
- `23498498` Chapter 6 Practice Test
- `23498499` Chapter 6 Group Test
- `23498500` Chapter 6 Individual Test
- `23498508` Chapter 7 Practice Test
- `23498509` Chapter 7 Group Test
- `23498510` Chapter 7 Individual Test
- `23498518` Chapter 9 Practice Test
- `23498519` Chapter 9 Group Test
- `23498520` Chapter 9 Individual Test
- `23498521` Final Review
- `23498523` AAA Practice Quiz
- `23498524` AAA Quiz Setting Master
- `23498528` Review Problems: Chapter 11
- `23498529` Chapter 11 Quiz
- `23498532` Final Exam Practice
- `23498533` Exam 3 Practice
- `23498538` Chapter 9 Quiz
- `23498546` Chapter 8 Quiz
- `23498547` Exam 2 Practice
- `23498551` Chapter 7 Quiz
- `23498556` Chapter 6 Quiz
- `23498561` Chapter 5 Quiz
- `23498562` Exam 1 Practice
- `23498564` Chapter 4 Quiz
- `23498570` Chapter 3 Quiz (Spring 2020)
- `23498576` Chapter 2 Quiz

**scaffolding / duplicates / unclear** (10)

- `23498439` Entering Answers
- `23498451` HW Zero and Negative Exponents
- `23498471` 3.3.2 Fraction Busters
- `23498491` 6.1.2 Summary of Solving Equations
- `23498502` 7.1.2 Conditions for Triangle Congruence
- `23498515` 9.2.2 Graphing Linear and Nonlinear Inequalities
- `23498517` 9.3.2 More Systems of Inequalities
- `23498522` AAA Homework Setting Master
- `23498525` demonstration
- `23498526` Sample Questions

## Assessment scaffold the global class needs

Per chapter, matching the stats book house pattern: one assignment per section, then a practice
test, a group test and an individual test.

| chapter | sections | + practice | + group | + individual | total |
|---|---|---|---|---|---|
| 0 Foundations | 5 | 1 | 1 | 1 | 8 |
| 1 Functions | 5 | 1 | 1 | 1 | 8 |
| 2 Linear Functions | 7 | 1 | 1 | 1 | 10 |
| 3 Transformations and Solving | 7 | 1 | 1 | 1 | 10 |
| 4 Sequences | 5 | 1 | 1 | 1 | 8 |
| 5 Systems of Equations | 7 | 1 | 1 | 1 | 10 |
| 6 Congruence and Coordinate Geometry | 6 | 1 | 1 | 1 | 9 |
| 7 Exponential Functions | 5 | 1 | 1 | 1 | 8 |
| 8 Inequalities | 5 | 1 | 1 | 1 | 8 |
| **total** | **52** | 9 | 9 | 9 | **79** |

That is **79 assessments** to stand up the global class for IM1. Chapter 1 already has
`1.2 Representing a Growing Pattern` built (aid 23987455, 15 questions, verified 2026-08-31).

## Open decisions

1. ~~Does the global class hold IM1 at all?~~ **RESOLVED 2026-08-31** - it is a multi-course
   container and block `0-3 Integrated Math 1` already exists and is empty. See above.
2. **Blocks.** IM1 manifests carry a `block` path (`0-6-3-2`). The global class needs its own
   chapter folders before 79 assessments land in it, or the course page is a flat wall.
3. **The OpenStax leftovers.** Retire, or keep as a supplemental bank?
4. **The three 80-question Chapter 1 tests** in the live course are identical to each other and
   open on Order of Operations. They are not usable as chapter tests.

## Filling the chapters: the Copy From recipe (proven step-by-step, 2026-08-31)

MyOpenMath copies an assessment **with its questions** between courses, so filling a chapter is a
copy, not a rebuild. Every step below was driven and confirmed individually.

```
1. https://www.myopenmath.com/course/copyitems.php?cid=334437&btf=<DESTINATION BLOCK>
      btf is the destination. Reach it from the block's own course page (Copy From...)
      or just put the folder id in the URL: btf=0-3-2-2 lands items in Chapter 1.

2. click every "+" to expand the course tree

3. radio  input[name="tree-selection"][value="c340140"]     <- 6 Integrated Math 1 -2627
   then   "Select Course Items"

4. the picker renders ~556 checkboxes, name="checked[]".
   Values are ITEM ids, not aids, so select by the row's label text.
   Chapter 1s six resolve to 45732863 - 45732868:
      45732863  HW Solving Math Puzzles
      45732864  HW Function Machines
      45732865  HW Functions
      45732866  HW Domain and Range
      45732867  HW Rewriting Expressions with Exponents
      45732868  HW Zero and Negative Exponents

5. "Copy Items"  ->  POSTs to copyitems.php?cid=334437&action=copy&btf=<block>
```

**Checking a BLOCK checkbox selects its children**, so tick only the item rows unless a whole block
is wanted.

### Why this is not automated here

The whole flow is one long sequence - navigate, expand, select course, submit, wait for 556
checkboxes, tick, submit, wait - and the run-code channel died silently at the final click on every
attempt. **Nothing was created**: the global class held 96 assessments before and after, and the
highest aid is still `23987455` (the 1.2 built earlier), so there is no half-finished copy to clean
up. Confirmed twice.

Doing it in the browser by hand is about a minute per chapter and cannot half-succeed the way the
scripted version did. The per-chapter destination blocks are in the table above; the source
assignments are in the section-by-section map.

**Do not blind-retry a copy that returns no output.** Check the assessment count first. The same
"silent success, then a duplicate on retry" pattern already produced four duplicate chapter blocks
earlier in this build.

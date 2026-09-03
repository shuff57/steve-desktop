# Chapter 0 Group Test - Foundations

**Book:** Integrated Math 1 (master course cid 334437)  
**Kind:** Group Test - all auto-graded, no FRQs  
**Scope:** Chapter 0, sections 0.1-0.5  
**Block:** `0-3-3-1` Chapter 0: Foundations  
**Status:** PUSHED 2026-09-02; trimmed to the 15-item cap and Q11 swapped the same day  
**AID:** 24015116 (course 334437, block 0-3-3-1)  
**Manifest:** `books/integrated-math-1/group/chapter-0-group-test.json`

## Summary

| | |
|---|---|
| Scored questions | **15**, 1 point each = **15 points** |
| Group-select bundles | **14** (only Q6 is a lone question) |
| Questions attached | **39** |
| Manifest slots | **53** (39 questions + 14 group markers) |
| Read-back gate | 39 questions / 14 groups / 15 points |
| Estimated time | ~45 min, fits one period with discussion overhead |

A group scores **once**, which is why 39 attached questions total only 15 points.

## Where the questions come from

Chapter 0 is this book's own chapter - CPM has no counterpart, and the live source course
`340140` has no Chapter 0 Group Test to pull. It only has the Chapter 0 Group Quiz
(`aid 23498445`, already in the repo).

So this is **composed, not pulled**. Every `qid` below is a MyOpenMath shared-library qsetid
that is already attached to a pushed Chapter 0 homework (`books/integrated-math-1/hw/0-*.json`).
Per `transfer-rules.md`: a question used by two assignments is **filed once and attached twice**.
Nothing here needs filing or copying - attach by qsetid and stop.

## Coverage against the homework

Scored items are allocated in proportion to each section's homework weight, so the test
does not over-index on 0.5 the way a straight topic list would.

| Section | HW questions | Share | Scored items |
|---|---|---|---|
| 0.1 Use the Language of Algebra | 15 | 17% | 2 |
| 0.2 Integers | 22 | 25% | 4 |
| 0.3 Fractions | 13 | 15% | 2 |
| 0.4 Decimals | 7 | 8% | 2 |
| 0.5 Properties of Real Numbers | 31 | 35% | 5 |
| **Total** | **88** | | **15** |

## Deliberate overlap with the Chapter 0 Group Quiz

The quiz leans hard on 0.3 and 0.5 and skips 0.1, 0.2 and rounding entirely. Where the two
assessments touch the same skill, this test picks a **different variant** so it is not a rerun:

| Skill | Quiz used | This test uses |
|---|---|---|
| Translating words to expressions | `a more than b times`, `a less than b times` | `difference between`, `quotient`, `sum`, `subtraction` |
| Absolute value | abs(a-b) - abs(c-d) evaluation | abs(a) vs -a vs -abs(a) ordering |
| Distributing | `a(bx+c)` integer coefficients | decimal and fractional coefficients |
| Number line | graph `a/n` (a fraction) | graph a decimal, `dx = 0.2` |
| Dividing fractions | fraction-by-variable | one-variable, two-variable, `ax` by `c/d` |

Genuinely new to this test: order of operations, signed multiply/divide, subtraction with
negatives, `(-a)^b` vs `-a^b`, rounding, naming the properties, and division by zero.

## Question roster


### 0.1 Use the Language of Algebra

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q1 | Order of operations, multi-step chain | group of 3 | `340765`, `340768`, `340770` | multipart |
| Q2 | Translate words into an algebraic expression | group of 4 | `266919`, `266920`, `266921`, `266922` | numfunc |

### 0.2 Integers

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q3 | Absolute value and ordering on the number line | group of 4 | `340059`, `340058`, `334919`, `334920` | multipart |
| Q4 | Signs under multiplication and division | group of 3 | `350388`, `334922`, `334925` | multipart |
| Q5 | Subtraction with negatives | group of 3 | `266929`, `340056`, `274967` | multipart |
| Q6 | (-a)^b versus -a^b | single | `261763` | multipart |

### 0.3 Fractions

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q7 | Multiplying fractions | group of 3 | `224293`, `270731`, `270732` | calculated |
| Q8 | Dividing fractions | group of 3 | `281134`, `281135`, `336023` | numfunc |

### 0.4 Decimals

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q9 | Round to tenth, hundredth, thousandth | group of 2 | `267164`, `267166` | multipart |
| Q10 | Place a decimal on a number line | group of 2 | `271473`, `271476` | draw |

### 0.5 Properties of Real Numbers

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q11 | Distribute with decimal coefficients | group of 2 | `347513`, `347517` | numfunc |
| Q12 | Distribute / factor with fractional coefficients | group of 2 | `332698`, `271481` | numfunc |
| Q13 | Subtracting algebraic expressions | group of 2 | `197559`, `267009` | numfunc |
| Q14 | Zero as numerator versus zero as denominator | group of 2 | `271480`, `267008` | calculated/number |
| Q15 | Multiply a fraction by its denominator | group of 3 | `333581`, `333585`, `333586` | numfunc |

## What the push actually did

```
1. addassessment2.php   copyfrom=23258795, copydates unchecked      -> aid 24015116
        |               ...but it landed at the COURSE ROOT, not in the block
        v
2. moveitem.php         course-item 46708308, block 0 -> 0-3-3-1    (via the page's own
        |                                                            moveitem(), see below)
        v
3. settings             gradebook category GROUP (798369), undated
        |
        v
4. attach by qsetid     41 GETs in manifest slot order - nothing filed, nothing copied
        |               proved on ONE before looping the other 40
        v
5. points               SKIPPED, deliberately. itemarray[i][4] was 9999 = "use assessment
        |               default" and defpts is 1, which is already the intent.
        v
6. groups               15 x groupSelected(), HIGHEST INDEX FIRST, then submitChanges()
        |
        v
7. read back            41 questions / 15 groups / 16 points -- PASSED on a fresh navigation
```

### Three things that went wrong, and what fixed them

| Symptom | Cause | Fix |
|---|---|---|
| Assessment appeared at the course root | the create form's action carries `block=` EMPTY with `folder=0`; the `btf=0-3-3-1` hint is not honoured on create | `moveitem.php` afterwards - same way `IM1 1.2` ended up loose |
| `moveitem` POST returned HTTP **200** reading "Your submission has been blocked" | there is no `csrfp-token` input to read; the CSRFP library signs requests via its own ajax hook | call the page's own `moveitem()` instead of hand-rolling the POST |
| A batch of attaches reported `{}` and did nothing | `playwright-cli eval` does NOT await - an async IIFE returns `JSON.stringify(Promise)` === `"{}"`, which reads as a clean empty success | synchronous `XMLHttpRequest`, never `fetch` |

And one that only *looked* wrong: `submitChanges()` set both `#submitnotice` and `#statusmsg` to
**"error: not saved"** while the save had in fact landed. A fresh navigation showed 56 rows and 15
groups. Retrying on that message would have double-grouped the assessment.

## Verified

- **39 questions / 14 groups / 15 points**, read back on a fresh navigation, order matching the
  manifest exactly, no duplicates, no extras.
- **Teacher Preview renders 15 questions** - one per group-select, which is the point of the format.
  Header reads `Score: 0/15`. Zero `Eeek!`, zero leaked section markers, no literal `$variable`,
  every question carrying its answer widget.
- **Looked at, full page.** MathJax typesets on every item, the fraction and number-line questions
  included; the `draw` number-line tool renders its axis and Draw/Clear controls.

## NOT verified

The questions were rendered, **not answered**. `mom-transfer` asks for every question to be filled,
submitted and seen graded, and that was not done. The gate exists to catch *filing* defects - empty
CodeMirror, wrong `qtype`, truncation - none of which can happen on a push that files nothing. But a
wrong answer key inside one of MOM's own shared questions would still be invisible here, and could
not be repaired from this repo anyway: with no `file_path` there is no source to fix and re-push.
These are the same qsetids already assigned as Chapter 0 homework.

## Sizing - the 15-item cap

Built at 16, trimmed to **15** on Steve's call the same day. There is no group-test question cap
written down anywhere in the repo; 15 is what every *deliberately authored* group test already does:

| Group test | Scored items |
|---|---|
| Applied Finite - Test A W1-5 | 15 |
| IM3 Chapter 1 | 15 |
| IM3 Chapter 7 | 15 |
| Intro to Stats (HS) Chapter 1 | 15 |
| IM3 Unit 7 quiz, v1 and v2 | 14 |

The IM1 chapter tests pulled from CPM are **not** a guide: Chapter 6 reads as 1 scored item because
all 20 of its questions sit in a single group, a defect `MAP-IM1-GLOBAL.md` already records.

**What was cut:** the old Q2, order of operations at a single value (qsetids `266918`, `270702`).
Q1 already drills that skill harder, as a 3-part chain where the same numbers are grouped three
different ways. 0.1 therefore goes from 3 scored items to 2. Removing it used the page's own
`removegrp()`; both qsetids remain in MOM's shared library and stay attached to 0.1 homework.

## Q11 swapped out

The commutative/associative bundle (`274963` / `336030` / `274965`) was replaced with the abstract
**multiply-a-fraction-by-its-denominator** bundle (`333581` / `333585` / `333586`), a skill nothing
else on the test touched. It renders as e.g. `((b+9)/(x+10))(x+10) =`, with the "Assume x + 10 != 0"
guard.

Two things to know about how it landed:

- **An attach APPENDS.** The new bundle is therefore the **last** scored item, not slot 11. That was
  accepted rather than reordered: 0.5 already occupies the tail of the test, so the item stays inside
  its own section and the roster still reads in section order. Reordering would mean driving the
  per-row `moveitem2` selects for no gain.
- **The removed questions are not destroyed.** Removing from an assessment removes the *instance*;
  all three qsetids remain in MOM's shared library and stay attached to 0.5 homework.

While swapping it, the old label was found to be wrong: it read "Name the property", but none of
those three variants asks a student to name anything - they give `42y + 29c = 54` and ask for
`29c + 42y`, which is *applying* the property, `qtype: number`. Moot now, but the same mislabel is
worth watching for elsewhere.

## Open decisions for Steve
- **Attempts: DONE.** Set to **3 attempts / 1 version**, the documented Group Test row in
  `intro-stats-assessment-settings.md`. It arrived from the hw template as 3 / **20**. Read back
  fresh: `defattempts=3`, `defregens=1`; the per-version penalty field disappears at 1 version,
  which is what that table records as "(absent)".
- **Passcode.** Not set. Per `transfer-rules.md` the passcode must be the LAST write, because every
  later settings save submits `assmpassword` empty and silently clears it.
- **Book link.** The Resources box carries the template's inherited link. Chapter 0 *does* exist in
  bookSHelf, so this one can point at `chapter-0-foundations` for real - unlike chapters 2-8, which
  are stuck with the stats 2.1 link because their book pages do not exist yet.

# Chapter 0 Practice Test

**Book:** Integrated Math 1 (master course cid 334437)  
**Kind:** Practice Test - all auto-graded, no FRQs  
**Scope:** Chapter 0, sections 0.1-0.5  
**Block:** `0-3-3-1` Chapter 0: Foundations  
**Status:** PUSHED 2026-09-02  
**AID:** 24020166 (course 334437, block 0-3-3-1)  
**Manifest:** `books/integrated-math-1/practice/chapter-0-practice-test.json`

## Summary

| | |
|---|---|
| Scored questions | **21**, 1 point each = **21 points** |
| Group-select bundles | **21** |
| Questions attached | **83** |
| Manifest slots | **104** |
| Read-back gate | 83 questions / 21 groups / 21 points |
| Estimated time | ~55 min, homework-paced |

A group scores **once**, which is why the attached count is far above the points total.

## Where the questions come from

Every `qid` is a MyOpenMath shared-library qsetid **already attached to a pushed Chapter 0
homework**. A push therefore attaches by qsetid and files nothing - same as the Chapter 0 Group
Test. Nothing here needs authoring to be deployable.

## The design rule these two follow

`MAP-IM1-GLOBAL.md` records a real defect inherited from the live course:

> Chapter 1's Review, Group Test and Individual Test are the same 65 questions, three times over.

Chapters 2 and 3 do the same. That makes an individual test a memory check of the group test
rather than an assessment. Chapter 0 is built the other way round:

```
Chapter 0 pool - 83 shared-library qsetids
        |
        +-- GROUP TEST      39 questions   (already live, aid 24015116)
        |
        +-- INDIVIDUAL      29 questions   ZERO overlap with the group test, by construction
        |
        +-- PRACTICE        83 questions   ALL of them - it is the rehearsal for both
```

Verified by the build script, not asserted: the individual test shares no qsetid with the group
test, and the practice test covers 83 of 83.

## Coverage

| Section | HW questions | Share | Scored items |
|---|---|---|---|
| 0.1 Use the Language of Algebra | 15 | 17% | 4 |
| 0.2 Integers | 22 | 25% | 6 |
| 0.3 Fractions | 13 | 15% | 3 |
| 0.4 Decimals | 7 | 8% | 2 |
| 0.5 Properties of Real Numbers | 31 | 35% | 6 |
| **Total** | **88** | | **21** |

## Question roster

### 0.1 Use the Language of Algebra

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q1 | How to enter algebraic answers (warm-up) | group of 2 | `349431`, `350292` | multipart |
| Q2 | Order of operations | group of 5 | `340765`, `340768`, `340770`, `266918`, `270702` | multipart/number |
| Q3 | Exponents and evaluating expressions | group of 3 | `270705`, `270706`, `266916` | number |
| Q4 | Translate words into an expression | group of 7 | `266919`, `266920`, `266921`, `266922`, `274272`, `274276`, `281097` | multipart/numfunc |

### 0.2 Integers

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q5 | Absolute value and ordering | group of 4 | `340059`, `340058`, `334919`, `334920` | multipart |
| Q6 | Absolute value expressions | group of 3 | `270708`, `270709`, `270711` | number |
| Q7 | Signs under multiplication and division | group of 3 | `350388`, `334922`, `334925` | multipart |
| Q8 | Subtraction with negatives | group of 3 | `266929`, `340056`, `274967` | multipart |
| Q9 | Order of operations with negatives, and (-a)^b | group of 4 | `172789`, `172795`, `270720`, `261763` | multipart/number |
| Q10 | Evaluate an expression at integer values | group of 2 | `270728`, `266930` | number |

### 0.3 Fractions

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q11 | Multiplying fractions | group of 4 | `224293`, `270731`, `270732`, `221656` | calculated |
| Q12 | Dividing fractions | group of 5 | `281134`, `281135`, `336023`, `336021`, `336022` | numfunc |
| Q13 | Fraction powers and sums | group of 3 | `270738`, `242873`, `333599` | calculated/numfunc |

### 0.4 Decimals

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q14 | Round to tenth, hundredth, thousandth | group of 2 | `267164`, `267166` | multipart |
| Q15 | Place a number on a number line | group of 5 | `270745`, `271472`, `271475`, `271473`, `271476` | draw |

### 0.5 Properties of Real Numbers

| # | Skill | Bundle | Variants (qsetid) | Type |
|---|---|---|---|---|
| Q16 | Distribute, integer and decimal coefficients | group of 6 | `172809`, `172820`, `172821`, `172823`, `347513`, `347517` | numfunc |
| Q17 | Distribute with fractional coefficients | group of 4 | `334539`, `334540`, `332698`, `271481` | numfunc |
| Q18 | Add and subtract algebraic terms | group of 4 | `197559`, `267009`, `267006`, `271477` | number/numfunc |
| Q19 | Commutative and associative properties | group of 4 | `274963`, `274965`, `336027`, `336030` | number |
| Q20 | Multiply a fraction by its denominator, simplify | group of 6 | `333581`, `333585`, `333586`, `333588`, `333591`, `333594` | numfunc |
| Q21 | Zero, cancellation and exponent laws | group of 4 | `271480`, `267008`, `267007`, `271478` | calculated/number/numfunc |

## Why this one covers everything

It is the rehearsal. A student who works the practice
test has seen every question that can appear on either the group test or the individual test,
which is what a practice test is for - and it is the only one of the three where full overlap
is a feature.

It leads with the two **answer-entry format questions** (`349431`, `350292`). Those teach MOM
answer entry rather than mathematics, which is exactly why they belong here and are deliberately
kept off the graded individual test.

## Settings when pushed

| | |
|---|---|
| Gradebook category | HW (`798368`) - practice is graded as practice work |
| Attempts per question | 3 |
| Versions per question | 20 |
| Dates | none - the master course is undated |

## Push procedure

```
1. addassessment.php?block=0-3-3-1&tb=b&cid=334437   copyfrom the template
        |    EXPECT IT TO LAND AT THE COURSE ROOT - the form action carries block= empty
        v
2. moveitem.php    call the PAGE'S OWN moveitem() after setting #blockselect;
        |          a hand-rolled POST is unsigned and answers 200 "submission blocked"
        v
3. settings        gradebook category, attempts/versions, undated
        |
        v
4. attach          83 GETs in slot order, sync XHR (eval does not await fetch)
        |
        v
5. groups          groupSelected() HIGHEST INDEX FIRST, selecting by checkbox VALUE PREFIX
        |          (qc<n> ids are NOT itemarray indices once anything is grouped)
        v
6. read back       83 questions / 21 groups / 21 points, then render
```

Every line above is a trap this chapter already hit once; see `reference/transfer-rules.md`.

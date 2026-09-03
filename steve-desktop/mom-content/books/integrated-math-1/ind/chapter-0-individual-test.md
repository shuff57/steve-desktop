# Chapter 0 Individual Test

**Book:** Integrated Math 1 (master course cid 334437)  
**Kind:** Individual Test - all auto-graded, no FRQs  
**Scope:** Chapter 0, sections 0.1-0.5  
**Block:** `0-3-3-1` Chapter 0: Foundations  
**Status:** PUSHED 2026-09-02  
**AID:** 24020352  
**Manifest:** `books/integrated-math-1/ind/chapter-0-individual-test.json`

## Summary

| | |
|---|---|
| Scored questions | **10** |
| Points | **11** (1 each, find-the-mistake 2) |
| Questions attached | **19** |
| Group-select bundles | **5** |
| Newly authored | **5** (4 multipart + 1 find-the-mistake) |
| Read-back gate | 19 questions / 5 groups / 11 points - PASSED |
| Graded in Teacher Preview | all 5 authored questions at full marks |
| Settings | IND (`798370`), 2 attempts, 1 version, 50% penalty, undated |

## Why five questions had to be written

The first draft of this test had **zero multipart items** - every question was a single-answer
drill. That was not a choice. `assessment-presets.json` asks an individual test for
"~8 high-impact FULL multipart items ... no narrow drill questions", and Chapter 0 has only five
non-formatting multipart questions in its entire pool - all five already used by the Group Test.
The zero-overlap rule and the multipart rule could not both be met from the pool as it stood, so
the pool was extended.

| qsetid | Question | Section | Covers |
|---|---|---|---|
| `1894925` | Order of Operations, Grouping and Translation | 0.1 | evaluate, re-evaluate with grouping added, then translate the grouped form |
| `1894926` | Signed Numbers, Absolute Value and Exponents | 0.2 | absolute value, then `-a^2` against `(-a)^2` on the same digits |
| `1894927` | Fraction Operations | 0.3 | multiply, divide and power, all on one fraction pair |
| `1894928` | Distribute, Combine and Simplify | 0.5 | expand, subtract a parenthesised expression, clear a denominator |
| `1894930` | Find the Mistake in Simplifying a Fraction | 0.5 | audit a worked simplification: step, corrected value, habit |

Half the scored items are now full multipart processes rather than drills.

## Zero overlap with the Group Test

`MAP-IM1-GLOBAL.md` records that Chapter 1, 2 and 3 each ship their Review, Group Test and
Individual Test as **the same questions three times over**, which makes an individual test a
memory check rather than an assessment. This one shares no qsetid with the Group Test, verified
by the build script rather than asserted.

## Question roster

### 0.1 Use the Language of Algebra

| # | Skill | Bundle | qsetid | Type |
|---|---|---|---|---|
| Q1 | Evaluate, re-group, then translate (full process) | **authored** | `1894925` | multipart |
| Q2 | Translate a statement into an expression | group of 2 | `274272`, `274276` | numfunc |

### 0.2 Integers

| # | Skill | Bundle | qsetid | Type |
|---|---|---|---|---|
| Q3 | Absolute value, then -a^2 against (-a)^2 (full process) | **authored** | `1894926` | multipart |
| Q4 | Order of operations with negatives | group of 3 | `172789`, `172795`, `270720` | number |

### 0.3 Fractions

| # | Skill | Bundle | qsetid | Type |
|---|---|---|---|---|
| Q5 | Multiply, divide and power one fraction pair (full process) | **authored** | `1894927` | multipart |
| Q6 | Dividing fractions by a variable | group of 2 | `336021`, `336022` | numfunc |

### 0.4 Decimals

| # | Skill | Bundle | qsetid | Type |
|---|---|---|---|---|
| Q7 | Place a fraction on a number line | group of 3 | `270745`, `271472`, `271475` | draw |

### 0.5 Properties of Real Numbers

| # | Skill | Bundle | qsetid | Type |
|---|---|---|---|---|
| Q8 | Expand, subtract, then clear a denominator (full process) | **authored** | `1894928` | multipart |
| Q9 | Distribute +/-a(bx +/- c) | group of 4 | `172809`, `172820`, `172821`, `172823` | numfunc |
| Q10 | FIND THE MISTAKE - simplifying a fraction | **authored** | `1894930` | multipart |

## Verified

- **Gate:** 19 questions / 5 groups / 11 points, read back on a fresh navigation.
- **Filing:** byte-exact read-back on all five fields of each authored question.
- **Render:** 10 questions, zero `Eeek!`, zero marker leaks, every widget present, looked at full page.
- **Graded:** every authored question answered and submitted in Teacher Preview at full marks -
  Q1 1.02/1, Q3 1.02/1, Q5 1.02/1, Q8 1.02/1, Q10 2.04/2.

Numeric parts were answered from MOM's own **View Key**. Every *expression* part was worked out
by hand from the rendered prompt, because View Key does not reveal an expression answer - so
those are checked against the question as a student sees it, not replayed from the file that
generated the key. That distinction is what caught the defect below.

## One defect, found by reading the render

Q5 shipped with a **wrong solution guide**. It claimed all three answers land in lowest terms;
the seed constraints only guarantee that for the multiply and the power. On the live seed `7/6`
and `5/8` the divide gives `56/30`, which reduces to `28/15`. Forcing all four values pairwise
coprime over 2..9 is close to infeasible (`a=7, b=6` admits only `5`), so the divide answer is
now reduced explicitly with `gcd()` and the guide shows that reduction as a step.

**Byte-exactness and a clean render both passed this question before the defect was seen.** Only
reading the rendered arithmetic caught it - which is exactly what `mom-transfer` says those gates
cannot do. qsetid `1894927` was UPDATED in place, never re-filed.

## The render sandbox was unavailable

`mom.huffpalmer.fyi` could not be reached: the network runs a TLS-inspecting proxy whose leaf is
issued by `CN=205.155.196.228`, and that root is trusted nowhere on the machine - node reports
`SELF_SIGNED_CERT_IN_CHAIN`, Chrome reports a navigation error, and exporting the Windows trust
store does not help because the interception root is not in it. The host itself is up (Cloudflare,
11ms). So the usual write-render-repair loop was replaced by filing into the live course and
verifying there, which is the stronger check anyway.

# SPEC — Chapter 1 Review, sub-day set (Integrated Math 1)

**Book:** integrated-math-1
**Skill:** mom-question

## Scope

Write **25 brand-new** IMathAS question files into `mom-content/questions/im1/` (existing family —
check filenames there first so nothing collides). This is a stand-alone review assignment for a
day Steve is out (Fri 2026-09-25), covering CPM Chapter 1 / book Chapter 1 (Functions), sections
1.1–1.5. It is deliberately **not** a repeat of `books/integrated-math-1/practice/chapter-1-review.json`
(aid 23995944 in the master course) — that manifest's ~65 questions are almost entirely
`unbanked: true` shared-library items with `file_path: null`, so there is no local source to copy
the structure from, and none of the 25 below may reuse one of its `qid`s. Write originals.

No bookSHelf HTML page exists yet for IM1 chapters 2+ (per `MAP-IM1-GLOBAL.md`), so there is no
single source document to pull numbered problems from the way the stats specs do. The five
sections below are standard, well-defined Algebra 1 content — write directly from the per-question
spec, not from a textbook page.

**No pre-FRQ / find-the-mistake question in this set.** IM1 assignments normally carry one
find-the-mistake item, but building that correctly needs `find-the-mistake-template.md` read in
full and is being deliberately deferred given the Friday deadline. All 25 slots below are plain
auto-graded questions. Flag this omission in the report so Steve can ask for one later if he wants it.

## The twenty-five (all fresh — nothing in `chapter-1-review.json` or elsewhere in the bank has a
local `.php` for any of these; only `block-pattern-*`, `x-a-b-one-step-*` and
`simplify-exponents-all-rules.php` are locally authored IM1 files today, and none overlaps a spec
below)

Five sections of five. **Every question worth exactly 4 points. 25 x 4 = 100.**

### 1.1 Order of Operations (order-of-operations family, mixed operations)

| Slot | File | Parts | Spec |
|---|---|---|---|
| 1 | `order-of-operations-mixed-parentheses.php` | 1 | `number`. `a * (b + c) - d` with small positive ints (a 2-9, b,c 2-9, d 1-20); answer is the integer value. |
| 2 | `order-of-operations-with-exponents.php` | 1 | `number`. `a + b^2 - c * d`, b in 2-4 so b^2 stays readable, a/c/d positive ints. |
| 3 | `order-of-operations-negative-integers.php` | 1 | `number`. `-a + (-b) * c - d`, all of a,b,c,d positive ints 1-12 so the expression exercises negative-times-positive and subtracting a negative. |
| 4 | `order-of-operations-nested-groups.php` | 1 | `number`. `(a + (b - c)) * d - e`, choose b > c so the inner group stays an easy-to-read positive or force b < c on half the seeds (use `rand(0,1)` switch) so the student also practices a negative inner value. |
| 5 | `order-of-operations-absolute-value.php` | 1 | `number`. `abs(a - b) + abs(c - d) * e`, pick ranges so at least one of the two differences is negative before the absolute value on every seed (e.g. a<b always, c>d always) so the |.| is never a no-op. |

### 1.2 Growing Patterns & Function Machines

| Slot | File | Parts | Spec |
|---|---|---|---|
| 6 | `function-machine-single-operation.php` | 3 | `multipart`, `number` each part. Rule "multiply by `m`, then add `b`" (m 2-6, b 1-10, both fixed per seed). Give three different inputs (small ints); student computes each output. |
| 7 | `growing-pattern-write-the-rule.php` | 2 | `multipart`. Figures 1, 2, 3 of a linear (arithmetic) block pattern: start count `a` (3-8), common difference `d` (2-5). Part 1: `number`, how many blocks in figure `n` for a given small n (5-8). Part 2: `numfunc`, the rule as `y = d*x + (a - d)` — accept the simplified linear expression in `x`. |
| 8 | `growing-pattern-find-next-figure.php` | 2 | `multipart`, `number` each part. Show figures 1-3's block counts of an arithmetic sequence (start `a` 2-10, diff `d` 1-6). Ask for figure 4 and figure 5 counts. |
| 9 | `function-machine-two-step-inverse.php` | 2 | `multipart`. Rule "double the input, then subtract `b`" (b 1-10). Part 1 `number`: evaluate at a given input. Part 2 `number`: find the input that produces a given output (the inverse) — choose the given output so the inverse is always a clean integer (pick output = 2*input0 - b for a chosen integer input0). |
| 10 | `identify-linear-vs-nonlinear-pattern.php` | 1 | `choices`, 4 options. Show two labeled numeric sequences (Pattern A: arithmetic with constant difference `d`; Pattern B: a perfect-square-based sequence with growing gaps), ask which pattern grows by adding the SAME amount each time. Randomize which label (A/B) is the linear one. |

### 1.3 Inputs, Outputs, and What Makes a Function

| Slot | File | Parts | Spec |
|---|---|---|---|
| 11 | `evaluate-function-given-rule.php` | 2 | `multipart`, `number` each part. `f(x) = a*x + b` (a 2-9 nonzero, b -10..10). Evaluate at two different given x values. |
| 12 | `is-it-a-function-from-table.php` | 1 | `choices` (yes/no + 2 distractor phrasings), 50% of seeds build a table with a repeated x-value mapping to two different y-values (not a function), the other 50% build a genuine function (each x once). Vary which via `rand(0,1)`. |
| 13 | `is-it-a-function-from-ordered-pairs.php` | 1 | `multans` or `choices`. Show 4-5 ordered pairs; ask whether the set represents a function. Same 50/50 repeated-x construction as slot 12 but pairs instead of a table, so it is not a copy. |
| 14 | `vertical-line-test-concept.php` | 1 | `choices`, 4 options, conceptual (no randomized numbers needed beyond which option is shuffled to correct index) — describe a graph verbally ("a vertical line crosses the graph in exactly one point everywhere" vs "...crosses in two points somewhere" etc.) and ask which description means the graph IS a function. |
| 15 | `function-notation-evaluate-and-solve.php` | 2 | `multipart`. `f(x) = a*x + b`. Part 1 `number`: evaluate `f(c)` for given c. Part 2 `number`: solve `f(x) = d` for x, choosing `d` so x comes out a clean integer (pick d = a*x0 + b for a chosen integer x0). |

### 1.4 Domain and Range

| Slot | File | Parts | Spec |
|---|---|---|---|
| 16 | `domain-and-range-from-a-table.php` | 2 | `multipart`. Show a 4-5 row input/output table with distinct x-values. Part 1 `string` or `ntuple` (`$displayformat="set"`, `$answerformat="anyorder"`): list the domain. Part 2 same shape: list the range. |
| 17 | `domain-and-range-interval-from-description.php` | 2 | `multipart`. Describe a graph verbally as running from x = `p` to x = `q` (p<q) with y running from `r` to `s` (r<s). Part 1 `string`: domain as an inequality `p <= x <= q` (accept equivalent phrasing via a `$answer` pattern or a `choices` fallback if `string` grading proves too strict — verify against the render). Part 2: range similarly. |
| 18 | `domain-and-range-discrete-points.php` | 2 | `multipart`. List 4-5 discrete (x,y) points with distinct x-values. Same domain/range-as-a-set pattern as slot 16 but from a point list, not a table — different presentation, not a copy. |
| 19 | `identify-restricted-domain-real-world.php` | 1 | `choices`, 4 options. A real-world scenario (number of whole tickets sold `t`, `0 <= t <= N` where N is fixed per seed) — ask which domain description is correct (distractors: allows negative, allows non-integer, wrong upper bound). |
| 20 | `range-from-a-function-and-a-domain-set.php` | 1 | `string` or `ntuple` (`anyorder` set). `f(x) = a*x + b`, given a small explicit domain set of 3-4 integers, compute the range set. |

### 1.5 Working with Exponents

| Slot | File | Parts | Spec |
|---|---|---|---|
| 21 | `zero-and-negative-exponent-rules.php` | 2 | `multipart`, `numfunc` each part (accepts a fraction or its decimal). Part 1: evaluate `a^0` for some nonzero base bundled with a distractor coefficient, e.g. `c * a^0` so the question is not trivially "always 1". Part 2: evaluate `a^-n` (n 1-3, a 2-5) as a fraction. |
| 22 | `simplify-product-of-powers.php` | 1 | `numfunc` or `string`. Simplify `a^m * a^n` for a fixed base variable (e.g. `x`) with given integer exponents m,n (can be negative); answer is `x^(m+n)` — grade the resulting exponent as a `number` sub-part instead if `string` proves fragile (verify against render, pick the type that actually grades right). |
| 23 | `simplify-power-of-a-power.php` | 1 | Same answer shape as slot 22: `(x^m)^n` -> exponent `m*n`. |
| 24 | `simplify-quotient-of-powers.php` | 1 | Same shape: `x^m / x^n` -> exponent `m-n`, allow the result to be negative on some seeds. |
| 25 | `evaluate-expression-with-negative-exponents.php` | 1 | `numfunc`. Fully numeric: `a^-m + b^0 * c` with small a,b,c,m so the value is exact and not absurdly large. |

## Shared conventions

- Five markers, in order, per `mom-content/reference/dialect-rules.md`. Type picker set from
  `SET QUESTION TYPE TO:` — `multipart` for anything with 2+ parts even if some individual parts
  are simple `number`.
- Randomize every seed-visible number with `rand()`/`diffrands()` per `learned-rules.md` — no two
  students should see identical values. Verify with the combined grep
  `diffrands|randsfrom|randfrom|jointrandfrom|rand\(` returning a hit per file.
- `$abstolerance` set appropriately on any fractional `numfunc` answer.
- No dashes as punctuation, no AI-tell phrasing (see `mom-question` SKILL.md's list) anywhere in
  question text or `$solutionguide`. Every part gets a `$solutionguide` with the worked steps.
- No `qNN-` filename prefixes. Name each file for the skill it tests, matching the table above.
- Family: all 25 go in `mom-content/questions/im1/` (the family already used by the three existing
  local IM1 files).

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions/im1
node mom-content/_push/qtext-audit.mjs mom-content/questions/im1/<each new file>
node mom-content/_push/anstypes-audit.mjs mom-content/questions/im1/<each new file>
node mom-content/_push/usecheck.mjs mom-content/questions/im1/<each new file>
```

Exit clean (zero new findings) on all four for all 25 files. Pre-existing findings elsewhere in
the bank are not yours to fix.

**Do a local math sweep per question** — enumerate the seed ranges given above (a small
node/python loop) and assert the per-question invariant (e.g. slot 4's inner group really goes
negative on some seed, slot 9's inverse really comes out an integer on every seed, slot 12/13 are
genuinely 50/50 function vs not). Report the combination count swept per file.

**Render each question** through the sandbox (`mom.huffpalmer.fyi` — POST the file contents, read
the returned page body; HTTP 200 alone proves nothing, IMathAS prints its own errors into the
body). Fix to clean, then re-render. Up to 5 repair rounds per file before flagging it as blocked
rather than grinding further.

## What NOT to do

- Do not touch `books/integrated-math-1/practice/*.json` or any other manifest. Filing these 25
  into a manifest and pushing to MyOpenMath is a separate step (`SPEC-CH1-REVIEW-PUSH.md`,
  `mom-transfer` skill) done after this authoring pass is reviewed.
- Do not touch `mom-content/reference/question-library.json`.
- Do not invent a `target.cid` anywhere — these are local files only at this stage.
- Do not reuse any `qid` from `chapter-1-review.json`.

## Report

One row per file: slot, filename, qtype, part count, points (4, fixed), the invariant swept +
combination count, lint/audit results, render result (clean / repaired N times / blocked). State
plainly which of the 25 you did not personally render and confirm clean — never claim success you
did not observe. State the total file count written and confirm it is 25.

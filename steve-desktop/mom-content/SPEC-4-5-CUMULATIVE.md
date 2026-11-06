# SPEC — §4.5 Cumulative Probability Functions (rebuild)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

The book's §4.5 was rewritten on 2026-10-31 to **Cumulative Probability Functions** (discrete
cumulative tables, step graphs, cut-offs, median and quartiles). The live MOM assignment
`aid 23444255` in `cid 334437` is still the older **continuous-uniform** set (ten questions on
density, area, `P(x = c) = 0`, `U(a, b)`). This spec replaces that set with ten autograded questions
plus one pre-FRQ, 100 points, aimed at the rewritten section.

**Source of truth:** `bookSHelf`, branch `feat/stats-investigation-first`, commit `8c7b631c0`
(`projects/Introduction to Stats/remastered/Chapter_4_Numbered/4.5_continuous_probability_functions.md`).
The section has 12 numbered problems and this set covers all of them.

**Search before writing.** `reference/question-index.json` and a grep of `questions/` found no
cumulative-distribution question for a discrete random variable. The bank does have
`descriptive-stats/*cumulative*` and `descriptive-stats/quartiles-from-a-frequency-table.php`, but
those work on frequency tables of data, not on a probability distribution. `probability/binomial/q4`
reads a running total for a binomial, and `probability/discrete-pdf/q1/q2` build PDF tables. None
covers `F(x)`, cut-offs, or `P(a <= X <= b)` from a cumulative table, so the ten autograded slots are
new. The pre-FRQ is authored-first: no FRQ in `questions/frq/` covers cumulative distributions.

## The eleven slots (100 points)

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `review-complement-and-cumulative-total.php` | 3 | 8 |
| 2 | `build-the-cumulative-column.php` | 3 | 9 |
| 3 | `read-three-probabilities-from-cumulative.php` | 3 | 9 |
| 4 | `phrase-to-inequality-to-probability.php` | 3 | 9 |
| 5 | `quiz-cumulative-tail-and-interval.php` | 3 | 9 |
| 6 | `median-and-quartiles-from-cumulative.php` | 3 | 9 |
| 7 | `cutoffs-from-cumulative.php` | 2 | 9 |
| 8 | `row-versus-running-total.php` | 3 | 8 |
| 9 | `median-and-mean-compare.php` | 3 | 9 |
| 10 | `missing-value-cumulative-median.php` | 3 | 9 |
| 11 | `pre-frq-grade-a-cumulative-reasoning.php` | 3 | 12 |

Total: 8+9+9+9+9+9+9+8+9+9+12 = **100**.

## Problem map (the section's own exercises)

| Section problem | Slot |
|---|---|
| 4.5.1 complement | 1(a) |
| 4.5.2 table that does not add to 1; last running total | 1(b), 1(c) |
| 4.5.3 build the cumulative table; `P(X <= 2)` | 2, 3 |
| 4.5.4 `P(X > 1)`, `P(1 <= X <= 2)`, `P(X < 2)` | 3 |
| 4.5.5 four-question quiz: `F(2)`, `P(X >= 3)`, `P(2 <= X <= 3)` | 5 |
| 4.5.6 books: median, first quartile, third quartile | 6 |
| 4.5.7 texts: cut-offs for 0.6 and 0.9 | 7 |
| 4.5.8 row read as a running total | 8 |
| 4.5.9 median, mean, compare | 9 |
| 4.5.10 cut-offs and the jump at `x = 2` | 7, 8(c) |
| 4.5.11 missing value, cumulative table, median | 10 |
| 4.5.12 mean, median, largest chance | 9(c) |
| phrase drill: at most / at least / more than / fewer than / no more than / no fewer than | 4 |

**Step-graph drawing.** Problem 4.5.10(d) asks the student to draw the step graph. MOM's `draw`
type has no step-graph format (`polygon` joins points but does not close, and a step needs repeated
points at each jump), so this set assesses the graph by its jump instead: slot 8(c) asks for the
height of the jump at `x = 2`, which is `P(X = 2)`. The book retains the drawing exercise.

## Word problems: phrase to inequality

The teacher asked for word problems that use **at most, at least, more than, fewer than, no more
than, no fewer than**, so students practise converting the phrase to an inequality before reading
the total. Slot 4 is dedicated to that drill. Slots 3, 5 and the pre-FRQ also phrase the events in
words. The rule the questions teach:

| Phrase | Inequality | Read from a cumulative table |
|---|---|---|
| at most `k` / no more than `k` | `X <= k` | `F(k)` |
| fewer than `k` / less than `k` | `X < k` | `F(k - 1)` |
| at least `k` / no fewer than `k` | `X >= k` | `1 - F(k - 1)` |
| more than `k` | `X > k` | `1 - F(k)` |
| between `a` and `b`, inclusive | `a <= X <= b` | `F(b) - F(a - 1)` |

Every seed in slot 4 carries the phrase, its `k`, the precomputed probability, and the inequality
index, so the prompt and the key cannot drift.

## Per-question invariants

All tables are exact decimals and sum to 1. Every cumulative column is non-decreasing and ends at 1.
The sweep loops every seed and asserts the invariant named here.

1. **Review.** (a) `1 - p`; (b) validity index matches the printed row sum; (c) the cumulative
   column never decreases and ends at 1. Invariant: the printed three rows sum to the stated sum,
   and the answer index matches.
2. **Build the column.** `F(1)`, `F(2)`, `F(3)` from a four-row PDF. Invariant: `F(k)` is the sum of
   rows `0..k`, and `F(3) = 1`.
3. **Read three probabilities.** From a cumulative table: `P(X > 1) = 1 - F(1)`,
   `P(1 <= X <= 2) = F(2) - F(0)`, `P(X < 2) = F(1)`. Invariant: all three in `[0, 1]`.
4. **Phrase to inequality.** Phrase A and phrase B on one cumulative table, then the inequality for
   phrase A. Invariant: the precomputed probability equals the rule in the table above for every
   phrase type.
5. **Quiz.** `F(2)`, `P(X >= 3) = 1 - F(2)`, `P(2 <= X <= 3) = F(3) - F(1)`. Invariant: the three
   answers are consistent with the printed rows.
6. **Median and quartiles.** Cut-off rule: the smallest `x` with `F(x) >= p`. Invariant:
   `Q1 < median < Q3`, and each is the first cumulative entry reaching 0.25, 0.5, 0.75.
7. **Cut-offs.** Smallest `x` with `F(x) >= 0.6` and `>= 0.9`. Invariant: each answer is the first
   entry reaching `p`.
8. **Row versus running total.** A student claims `P(X <= 2) = P(X = 2)`. Invariant: the row value
   and the total are different on every seed, and the key uses each in the right part.
9. **Median and mean.** Mean from `sum x P(x)`, median by the cut-off rule, then a choice about
   whether the mean is the value with the largest chance. Invariant: the key matches the printed
   rows, including the seed where the mean and the largest-chance value coincide.
10. **Missing value.** Fill the missing row from sum-to-1, then `F(2)` and the median. Invariant: the
    filled table sums to exactly 1 and the median is the first entry reaching 0.5.
11. **Pre-FRQ.** Categories **Build the Cumulative Table (3 pts) / Read the Probability from the
    Total (4 pts) / Interpret the Result in Context (5 pts)**. Dropped category: **READ THE
    PROBABILITY FROM THE TOTAL** (not used by any earlier pre-FRQ). Invariant: part (a) names the
    full response, part (b) grades a different response, the dropped response fails exactly one
    category, and each sentence is category-pure.

## Dialect rules

Read `reference/dialect-rules.md` and `reference/learned-rules.md`. Short form: no trailing
semicolons; one statement per line; math is AsciiMath in backticks; `$answer[i]` in COMMON CONTROL;
`choices` answers are indices; `$noshuffle[i] = "all"` on every choices part; `numfunc` with
`abstolerance = 0.005`; no `essay`; no `pow()`, `number_format()`, `array_rand()`; precompute every
answer; QUESTION TEXT interpolates `$var` directly (never `' . $var . '`).

## Self-check before reporting

```bash
node mom-content/reference/question-lint.mjs mom-content/questions/probability/cumulative
```

Every file must come back with zero findings. Then run the seed sweep, which loops every index of
every parallel array and asserts the invariant above, and report the combination count.

Static checks before the push:

```bash
node mom-content/_push/anstypes-audit.mjs
node mom-content/_push/qtext-audit.mjs
node mom-content/_push/usecheck.mjs
```

## Push contract (for the mom-transfer run)

Rebuild `aid 23444255` in `cid 334437` **in place** so the due date and student links survive.
Remove the ten old continuous questions with the app's own Remove control, file the eleven new
questions, set points per the table (100 total), and answer every part in **one continuous Teacher
Preview session**. Match `choices` and `multans` options by label text, never by DOM index. Record
the ten new qids and the verification in the manifest's `_pushed` note. The old `q1..q9` uniform
files stay on disk; they are detached, not deleted, and the 4.6 lab still uses `q10..q18`.

# SPEC — §8.3 Probability Distribution Needed for Hypothesis Testing (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/stats-tests/hypothesis-testing/`
(the files already there are the pattern — **read `q16-full-ht-one-mean-fresh.php` and
`q8-ht-one-proportion.php` before starting** — plus `questions/stats-tests/AGENTS.md` and
`questions/probability/AGENTS.md`). Match the family shape: white-card UI, blue-chip part labels,
`jointrandfrom`/parallel-array scenarios with precomputed answers, `numfunc` for numeric parts,
`choices` with `$noshuffle[N] = "all"`, no `essay`, type picker Multipart.

**Scope note:** §8.3 is *Probability Distribution Needed for Hypothesis Testing* — matching a
test to its distribution (Table 8.3.1), the two questions that make the choice (mean or
proportion? then is `sigma` known?), the assumptions each test requires (simple random sample,
approximately normal population for the t-test, `np > 5` and `nq > 5` for the proportion test),
and reading a scenario to decide the distribution before any arithmetic. The two reuses carry the
t-test mechanics and the proportion-test mechanics; the new files carry the distribution-choice
decision itself, the sigma-known/sigma-unknown reading, the condition checks, and the failure
diagnosis.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed in 334437, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/hypothesis-testing/q16-full-ht-one-mean-fresh.php` | t-test machinery: t-statistic, df, p-value, decision |
| 2 | `questions/stats-tests/hypothesis-testing/q8-ht-one-proportion.php` | proportion-test machinery on the normal curve |

New files to author (slots 3–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q32-which-distribution.php` | 2 | 10 |
| 4 | `q33-sigma-known-vs-unknown.php` | 2 | 10 |
| 5 | `q34-test-conditions.php` | 3 | 10 |
| 6 | `q35-np-nq-check.php` | 2 | 10 |
| 7 | `q36-proportion-condition-failure.php` | 2 | 10 |
| 8 | `q37-binomial-vs-normal.php` | 2 | 9 |
| 9 | `q38-mean-or-proportion.php` | 2 | 9 |
| 10 | `pre-frq-grade-a-distribution-selection.php` | 3 | 12 |

Points: 10 + 10 + 10 + 10 + 10 + 10 + 10 + 9 + 9 + 12 = **100**.

Manifest: `books/introduction-to-stats-sh/hw/8-3-probability-distribution-needed-for-hypothesis-testing.json`, kind `hw`.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your eight files (the hypothesis-testing family is clean; the
17 pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a
matching `$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article
before an interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count.

## The dialect rules (non-negotiable)

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**. Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (homework carries no free response).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic).
  Never `number`.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.
- **`loadlibrary("stats")` at the top of COMMON CONTROL** in every question that calls a
  normal/t macro — without it MOM rejects the call. The two reuses already load it; the new
  files only call macros where the question computes (q35, q36).
- **Tolerances match the family:** `abstolerance = 0.005` for probabilities and proportions,
  `reltolerance = 0.01` for test statistics.
- **1-arg normal forms only:** `normalcdf(z)` and `invnormalcdf(p)` — standardize first.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q32-which-distribution.php — 2 parts
A test scenario (contexts in threes: σ known mean, σ unknown mean, proportion). Parts: (a)
`choices` — the distribution the test runs on (normal / Student's t / normal-for-a-proportion);
(b) `choices` — the deciding fact (σ was handed to you / σ was withheld and s stands in / the
parameter is a proportion). **Invariant: (a) and (b) are constant per scenario and consistent.**
The guide: two questions get you from the problem to the curve — is the claim about an average or
a percentage, and if it is a mean, do you know σ? Pick the wrong curve and every number after it
is measured against the wrong ruler. Mirrors 8.3.1, Table 8.3.1 and Problem Set 8.3 P1–P3.

### q33-sigma-known-vs-unknown.php — 2 parts
A mean test where the scenario either hands you σ or hands you s (contexts in threes and threes).
Parts: (a) `choices` — which spread you were given (σ or s); (b) `choices` — the distribution
that follows (normal if σ given, t if s given). **Invariant: (a) and (b) are constant per
scenario and consistent.** The guide: when you estimate the spread from the same numbers you used
to estimate the center, you have introduced a second source of uncertainty that a normal curve
does not account for — that substitution is exactly what the t distribution exists for. Mirrors
8.3.2, Table 8.3.1 and Problem Set 8.3 P2/P3/P4/P5.

### q34-test-conditions.php — 3 parts
A test scenario (contexts in threes, one per distribution). Parts: (a) `choices` — the
requirement that is at risk in THIS scenario (SRS for the t-test, np > 5 and nq > 5 for the
proportion test, SRS for the normal-mean test); (b) `choices` — whether the requirement is met
(the scenario says so one way or the other); (c) `choices` — why the condition exists (it is what
makes the distribution legal in the first place). **Invariant: all three answers are constant
per scenario and (b) matches the actual check.** The guide: the condition is not a formality —
for the proportion test it is what lets a binomial count stand in for a normal curve, and for
the t-test the population has to be approximately normal because with small n there is no CLT to
lean on. Mirrors 8.3.2/8.3.3 assumptions and Problem Set 8.3 P7/P8/P9.

### q35-np-nq-check.php — 2 parts
A proportion test with `n` and `p0` (contexts in threes, include scenarios that FAIL so the
answer varies). Parts: (a) `numfunc` — `n * p0`; (b) `numfunc` — `n * (1 - p0)`; (c) `choices` —
may the normal approximation be used (both > 5)? **Invariant: (a) = n*p0 and (b) = n*(1-p0)
exactly, (c) matches the actual check on every seed.** The guide: the condition is computed under
the null — `np0` and `nq0`, not the sample proportions — because the normal curve is standing in
for the binomial distribution the null hypothesis claims. Mirrors 8.3.3, the `np > 5` check in
8.6.3's Try It Now, and Problem Set 8.3 P6/P9/P10.

### q36-proportion-condition-failure.php — 2 parts
A proportion test that FAILS the condition (contexts in threes, `np0 < 5`). Parts: (a) `choices`
— why the normal approximation cannot be used (the binomial is too lopsided for a normal curve to
trace); (b) `choices` — what the correct approach is (the test has to run on the binomial
distribution / exact binomial probabilities — the normal shortcut is simply not available).
**Invariant: both answers are constant across seeds.** The guide: `np < 5` means you expect fewer
than five successes, so the binomial distribution is too skewed for the normal curve to be a fair
stand-in — the interval or test will not have the coverage it claims. Mirrors Problem Set 8.3
P10 and the "exact p-value" note in the chapter.

### q37-binomial-vs-normal.php — 2 parts
A binomial-count scenario (contexts in threes). Parts: (a) `choices` — what the raw count
follows (a binomial distribution); (b) `choices` — when it may be treated as normal (when the
condition passes — both `np` and `nq` above 5). **Invariant: both answers are constant across
seeds.** The guide: each observation is a single success-or-failure trial, and the count across
`n` independent trials is binomial; the normal curve is only a stand-in when the binomial has
enough mass on both sides of its center. Mirrors Problem Set 8.3 P11 and 8.3.3.

### q38-mean-or-proportion.php — 2 parts
A claim (contexts in threes and threes: one average claim, one percentage claim). Parts: (a)
`choices` — is the parameter a mean or a proportion; (b) `choices` — which row of Table 8.3.1
the test lives in (normal for mean / t for mean / normal for proportion, once the σ question is
answered). **Invariant: (a) and (b) are constant per scenario and consistent.** The guide: an
average of a measured quantity is `mu`; a share of a group that has some yes-or-no trait is `p` —
decide that before you touch the calculator, because it picks the whole row of the table. Mirrors
8.3.1 and Problem Set 8.3 P1/P12.

### pre-frq-grade-a-distribution-selection.php — 3 parts
Authored-first pre-FRQ (no distribution-selection FRQ exists anywhere in `questions/frq/` — per
`mom-content/reference/pre-frq-template.md` the pre-FRQ is written anyway and defines the
scenario and rubric a later FRQ should match). Categories, 10 pts: **Name the Distribution (3)**
— the curve the test runs on; **State the Deciding Facts (4)** — what in the problem statement
chose the curve (σ given, σ withheld, parameter is a proportion); **Check the Conditions (3)** —
the np/nq check or the SRS/normality requirement the test needs before any arithmetic. Dropped
category: **Check the Conditions** — a student can name the distribution and the deciding facts
without ever verifying the assumptions, and the section's own "pick the wrong one and every
number after it is measured against the wrong ruler" is exactly the step a plausible answer
skips. This is DIFFERENT from every earlier dropped category (2.3 Percentile, 2.4 Contextual
Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1
Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 4.1 State the
Values, 4.2 Verify the Sum, 4.3 Name the Parameters, 5.1 State the Theoretical Value, 5.2 State
the Empirical Value, 6.1 State the Sum's Parameters, 6.2 Apply the Continuity Correction, 7.1
Confidence Level Meaning, 7.2 Assessing the Claim, 7.3 Statistical Decision, 7.4 Build the
Interval, 8.1 Real-World Example, 8.2 Name the Probabilities). Invariant structure per
`pre-frq-template.md`: `array("choices", "multans", "choices")`, `$scoremethod[1] =
"allornothing"`, four responses built by concatenating one sentence per category then dropping
one, part (b) grades a DIFFERENT response than part (a) names. **Invariant: no response earns a
category it is supposed to be missing (category purity).** Scope the CSS with a `.qscope83`
class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eight you did not finish, if any.

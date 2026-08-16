# SPEC — §8.5 Additional Information and Full Hypothesis Test Examples (Intro Stats -SH)

Write **twelve** IMathAS question files into `mom-content/questions/stats-tests/hypothesis-testing/`
(the files already there are the pattern — **read `q7-ht-one-mean.php`,
`q8-ht-one-proportion.php` and `q17-full-ht-one-mean-fresh-2.php` before starting**, plus
`questions/stats-tests/AGENTS.md` and `questions/probability/AGENTS.md`). Match the family shape:
white-card UI, blue-chip part labels, `jointrandfrom`/parallel-array scenarios with precomputed
answers, `numfunc` for numeric parts, `choices` with `$noshuffle[N] = "all"`, no `essay`, type
picker Multipart.

**Scope note:** §8.5 is *Additional Information and Full Hypothesis Test Examples* — reading the
level of significance, deciding left/right/two-tailed from the alternative alone, and carrying
full hypothesis tests end to end for a single population mean (z with σ known, t with σ unknown)
and a single population proportion, plus stating the Type I and Type II errors for a test in the
wording of the problem it came from. The two reuses carry the full t-test and the fresh-context
t-test; the new files carry the alpha reading, the tail-from-alternative decision, the z-test and
proportion-test runs, the mean-vs-proportion procedure choice, and the test-statistic-to-decision
chain.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed in 334437, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/hypothesis-testing/q7-ht-one-mean.php` | full one-mean test: H0/Ha, tail, z/t, p-value, decision |
| 2 | `questions/stats-tests/hypothesis-testing/q17-full-ht-one-mean-fresh-2.php` | full one-mean t-test from a fresh story context |

New files to author (slots 3–12):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q46-read-the-significance-level.php` | 2 | 10 |
| 4 | `q47-tail-from-alternative.php` | 2 | 10 |
| 5 | `q48-full-z-test-mean.php` | 4 | 10 |
| 6 | `q49-full-proportion-test.php` | 4 | 10 |
| 7 | `q50-mean-or-proportion-procedure.php` | 3 | 10 |
| 8 | `q51-test-stat-to-decision.php` | 3 | 9 |
| 9 | `q52-type-errors-for-a-full-test.php` | 2 | 9 |
| 10 | `q68-draw-the-picture.php` | 2 | 10 |
| 11 | `q69-two-tailed-picture.php` | 2 | 10 |
| 12 | `pre-frq-grade-a-full-ht-workflow.php` | 3 | 12 |

Points: 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 12 = **100**. (Rebalanced 2026-08-14 to the every-assignment-is-100 rule.)

Manifest: `books/introduction-to-stats-sh/hw/8-5-additional-information-and-full-hypothesis-test-examples.json`, kind `hw`.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the hypothesis-testing family is clean; the
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
  normal/t macro — without it MOM rejects the call. `tcdf` for t p-values, `normalcdf` (1-arg,
  standardized) for z p-values.
- **Tolerances match the family:** `abstolerance = 0.005` for p-values,
  `reltolerance = 0.01` for test statistics.
- Precompute the p-value in COMMON CONTROL (`tcdf`/`normalcdf`) and the decision string with a
  `where` clause — never interpolate `$answer[i]` into prose, it renders as a digit.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q46-read-the-significance-level.php — 2 parts
A test with a stated significance level (contexts in threes, levels 1%, 5%, 10%). Parts: (a)
`numfunc` — `alpha` as a decimal (0.01, 0.05, 0.10); (b) `choices` — what that alpha commits
you to (the probability of rejecting `H_0` when it is true, chosen BEFORE the data are
collected). **Invariant: (a) is the precomputed decimal exactly, (b) is constant across seeds.**
The guide: "the level of significance is 1%" means `alpha = 0.01`; if no level is given the
convention is 0.05, and choosing alpha after seeing the p-value would turn the procedure into a
rubber stamp. Mirrors 8.5.1, Definition 8.5.1 and Problem Set 8.5 P10–P13.

### q47-tail-from-alternative.php — 2 parts
An alternative hypothesis (contexts in threes: `mu < 9`, `mu > 6`, `p != 0.25`). Parts: (a)
`choices` — the tail the test runs on (left / right / two); (b) `choices` — the reasoning (the
symbol in `H_a` is what names the test; the `<=`/`>=` in `H_0` is not what decides the tail).
**Invariant: both answers are constant per scenario and consistent with `H_a`.** The guide:
`<` puts the whole p-value in the left tail, `>` in the right, `!=` splits it evenly between
both — and "the `<=` in `H_0` is not what decides the tail — the alternative is." Mirrors 8.5.2
and Problem Set 8.5 P1–P3.

### q48-full-z-test-mean.php — 4 parts
A one-mean test with σ KNOWN (contexts in threes, clean arithmetic). Parts: (a) `numfunc` — the
test statistic `z = (xbar - mu0) / (sigma/sqrt(n))`; (b) `numfunc` — the p-value from
`normalcdf` (tail matched to `H_a`, doubled for `!=`); (c) `choices` — the decision at the
stated alpha (reject / fail to reject); (d) `choices` — the conclusion worded about the claim.
**Invariant: (a) and (b) are the precomputed values exactly, (c) matches the p-vs-alpha
comparison, (d) matches the decision.** The guide: with σ given the test statistic is a z-score
built from the standard error `sigma/sqrt(n)` — the `sqrt(n)` in the denominator converts "how
far below" into "how surprising", which is what makes a test different from eyeballing an
average. Mirrors 8.5 Example 8.5.1/8.5.2/8.5.3 and the Television Survey machinery.

### q49-full-proportion-test.php — 4 parts
A one-proportion test (contexts in threes, `np0 > 5` and `nq0 > 5`, clean arithmetic). Parts:
(a) `numfunc` — the test statistic `z = (p' - p0) / sqrt(p0 q0 / n)`; (b) `numfunc` — the
p-value (tail matched to `H_a`); (c) `choices` — the decision at alpha; (d) `choices` — the
conclusion worded about the claim. **Invariant: (a) and (b) are the precomputed values exactly,
(c) matches the comparison, (d) matches the decision.** The guide: the standard error uses the
null proportion `p0`, not the sample `p'` — the curve is standing in for the binomial
distribution the null claims, so the null's spread is the one that matters. Mirrors 8.5 Example
8.5.2 and Problem Set 8.5 P6–P9.

### q50-mean-or-proportion-procedure.php — 3 parts
One scenario per context (contexts in threes and threes and threes). Parts: (a) `choices` —
which procedure the scenario calls for (one-mean z, one-mean t, or one-proportion z); (b)
`choices` — the deciding facts (σ given / σ withheld / parameter is a proportion); (c)
`numfunc` — the first number of that procedure (the standard error, or the sample proportion).
**Invariant: (a) and (b) are constant per scenario, (c) is the precomputed value exactly.**
The guide: the whole section exists to make the choice automatic — the parameter picks the row
of the table, the σ question picks the column, and only then does the arithmetic start. Mirrors
8.3's Table 8.3.1 applied in the 8.5 full-test examples.

### q51-test-stat-to-decision.php — 3 parts
A t-test with the raw statistics given (contexts in threes, `n`, `xbar`, `s`, `mu0`, `alpha`,
tail). Parts: (a) `numfunc` — the test statistic `t = (xbar - mu0)/(s/sqrt(n))`; (b) `numfunc`
— the p-value from `tcdf`; (c) `choices` — the decision. **Invariant: (a) and (b) are the
precomputed values exactly, (c) matches the comparison.** The guide: the t-distribution
accounts for estimating σ with s — with few degrees of freedom its fatter tails mean a bigger
p-value for the same test statistic, and reading the t-score against a normal table only ever
fails in the direction that manufactures findings. Mirrors 8.5 Example 8.5.4/8.5.5 and Problem
Set 8.5 P6–P9.

### q52-type-errors-for-a-full-test.php — 2 parts
A full-test scenario with its hypotheses stated (contexts in threes, from the 8.5 example
families). Parts: (a) `choices` — the Type I error stated in context; (b) `choices` — the Type
II error stated in context. **Invariant: both answers are constant per scenario and match the
stated hypotheses.** The guide: the errors are tied to the decisions — a Type I error can only
happen on a run where you rejected, and stating the null first matters because swap `H_0` and
`H_a` and you swap the errors along with them. Mirrors 8.5's learning objective and Problem Set
8.2 P14–P16 applied to full-test scenarios.

### q68-draw-the-picture.php — 2 parts
A one-tailed test (contexts in threes, left and right). Parts: (a) `choices` — where the curve
is centered (at the value the null hypothesis claims); (b) `choices` — where the shaded region
sits (the single tail beyond the observed test statistic, on the side the alternative names).
**Invariant: both answers are constant per scenario and consistent with `H_a`.** The guide:
draw one bell-shaped curve over a horizontal axis, centered at the value the null claims, then
mark the observed sample statistic and shade the tail beyond it — the picture is the p-value
made visible. Mirrors 8.5 Problem Set P4 and the "draw a graph and label it" step in every
8.6 survey.

### q69-two-tailed-picture.php — 2 parts
A two-tailed test (contexts in threes). Parts: (a) `choices` — why the shading is split
(because `H_a` carries `!=`, the test is in both tails); (b) `choices` — what the two shaded
regions together represent (the two-tailed p-value — twice the one-tailed area). **Invariant:
both answers are constant across seeds.** The guide: mark both cutoffs — the test statistic and
its mirror on the other side — because `!=` picks no direction, so the p-value collects area
in both tails, which is why the same data is harder to call significant when the question is
posed that way. Mirrors 8.5 Problem Set P5 and the Language Survey's two-tailed shading.

### pre-frq-grade-a-full-ht-workflow.php — 3 parts
The pre-FRQ mirror of `questions/frq/inference-for-proportions/q14-full-ht-workflow-context.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Hypotheses (2)**,
**Conditions (3)**, **Test stat + p-value (3)**, **Decision + Conclusion (2)**. Drop **"Test
stat + p-value"** — a student can write the hypotheses and reach the decision and conclusion
without ever showing the arithmetic, and the section's own "the pieces are put together here"
is exactly the step a plausible answer skips (the computation is the one category the others
imply but never demand). This is DIFFERENT from every earlier dropped category (2.3 Percentile,
2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
Direction, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters, 5.1 State the
Theoretical Value, 5.2 State the Empirical Value, 6.1 State the Sum's Parameters, 6.2 Apply the
Continuity Correction, 7.1 Confidence Level Meaning, 7.2 Assessing the Claim, 7.3 Statistical
Decision, 7.4 Build the Interval, 8.1 Real-World Example, 8.2 Name the Probabilities, 8.3 Check
the Conditions, 8.4 Interpretation of Evidence). **Invariant: read each of the four responses
against every rubric line — no response earns a category it is supposed to be missing
(category-purity), and every number inside a response is generated from the same variables as
the scenario.** Scope the CSS with a `.qscope85` class since the pre-FRQ shares the assignment
page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

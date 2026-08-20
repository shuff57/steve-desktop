# SPEC — §8.4 Rare Events, the Sample, Decision and Conclusion (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **thirteen** IMathAS question files into `mom-content/questions/stats-tests/hypothesis-testing/`
(the files already there are the pattern — **read `q5-pvalue-decision.php`,
`q10-conclusion-in-context.php` and `q16-full-ht-one-mean-fresh.php` before starting**, plus
`questions/stats-tests/AGENTS.md` and `questions/probability/AGENTS.md`). Match the family shape:
white-card UI, blue-chip part labels, `jointrandfrom`/parallel-array scenarios with precomputed
answers, `numfunc` for numeric parts, `choices` with `$noshuffle[N] = "all"`, no `essay`, type
picker Multipart.

**Scope note:** §8.4 is *Rare Events, the Sample, Decision and Conclusion* — the rare-event rule
(an outcome unlikely under `H_0` counts as evidence against it), what a p-value measures, the
`alpha > p-value` decision rule, the equality edge case, and the conclusion written in plain
sentences about the original claim. The two reuses carry the p-value-versus-alpha decision and
the conclusion-in-context phrasing; the new files carry the rare-event reasoning, the p-value
meaning, the decision rule with its edge case, the p-value from a z-score, the tail picture, and
the conclusion wording against the claim.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed in 334437, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/hypothesis-testing/q5-pvalue-decision.php` | reject / fail-to-reject from a p-value at alpha |
| 2 | `questions/stats-tests/hypothesis-testing/q10-conclusion-in-context.php` | conclusion phrasing that links the decision to the claim |

New files to author (slots 3–13):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q39-rare-event-reasoning.php` | 3 | 10 |
| 4 | `q40-what-a-p-value-measures.php` | 3 | 10 |
| 5 | `q41-pvalue-decision-edge.php` | 2 | 10 |
| 6 | `q42-pvalue-from-z.php` | 2 | 10 |
| 7 | `q43-pvalue-two-scenarios.php` | 2 | 10 |
| 8 | `q44-tail-picture.php` | 2 | 9 |
| 9 | `q45-conclusion-vs-claim.php` | 2 | 9 |
| 10 | `q65-sort-the-test-values.php` | 2 | 10 |
| 11 | `q66-s-vs-sigma.php` | 2 | 10 |
| 12 | `q67-sampling-distribution-under-h0.php` | 2 | 10 |
| 13 | `pre-frq-grade-a-pvalue-interpretation.php` | 3 | 12 |

Points: 8 + 8 + 8 + 8 + 7 + 7 + 7 + 7 + 7 + 7 + 7 + 7 + 12 = **100**. (Rebalanced 2026-08-14 to the every-assignment-is-100 rule.)

Manifest: `books/introduction-to-stats-sh/hw/8-4-rare-events-the-sample-decision-and-conclusion.json`, kind `hw`.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your eleven files (the hypothesis-testing family is clean; the
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
- **`loadlibrary("stats")` at the top of COMMON CONTROL** in every question that calls a normal
  macro — without it MOM rejects the call.
- **The 1-arg forms only:** `normalcdf(z)` — the 4-arg form is NOT accepted on this MOM
  instance. Standardize first, then call the 1-arg form.
- **Tolerances match the family:** `abstolerance = 0.005` for p-values.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q39-rare-event-reasoning.php — 3 parts
A rare-event story (contexts in threes: the $100-bill basket, the red-marble jar, a lottery
draw — each with a stated probability of the event). Parts: (a) `numfunc` — the probability of
the observed event under the stated assumption; (b) `choices` — what observing the rare event
gives you (a reason to doubt the assumption — NOT proof it is wrong); (c) `choices` — why (a
rare event almost never happens by chance alone, so the assumption that made it rare is the
thing that has to give way). **Invariant: (a) is the precomputed probability exactly, (b) and
(c) are constant across seeds.** The guide: nothing in a hypothesis test measures whether a
claim is true — it measures how surprised you should be by your data if the claim were true, and
enough surprise makes the assumption move. Mirrors 8.4.1, Try It Now 8.4.1 and the prize-in-the-
basket story.

### q40-what-a-p-value-measures.php — 3 parts
A completed test with its p-value (contexts in threes, p-values like 0.03, 0.0142, 0.165).
Parts: (a) `choices` — the correct meaning of the p-value (the probability of a sample result
this extreme or more so, IF `H_0` were true); (b) `choices` — what the p-value is NOT (the
probability that `H_0` is true, or the probability the claim is false); (c) `choices` — the
shaded region it stands for (the tail(s) of the sampling distribution beyond the observed test
statistic). **Invariant: all three answers are constant across seeds.** The guide: the p-value
is computed assuming the null is true — it measures the surprise, not the truth; a p-value of
0.0142 means that if the true mean really were the claimed value, a sample average this low or
lower would turn up about 1.4% of the time. Mirrors 8.4.2 and Try It Now 8.4.2.

### q41-pvalue-decision-edge.php — 2 parts
A test where the p-value is close to alpha, including at least one scenario with
`p-value = alpha` exactly (contexts in threes). Parts: (a) `choices` — the correct decision
(apply the rule rigidly: reject only when `alpha > p-value`); (b) `choices` — the conclusion
worded about the claim. **Invariant: (a) and (b) are constant per scenario and consistent with
the actual comparison.** The guide: equality is not "greater than" — when `alpha = p-value` the
rule lands in the do-not-reject branch; the p-value close to alpha does not change the rule, it
only changes how much confidence you have in the decision. Mirrors 8.4.2, Try It Now 8.4.2 and
Problem Set 8.4 P7/P8, and the "engineered to land exactly there" note in 8.6.1.

### q42-pvalue-from-z.php — 2 parts
A z-test with a test statistic (contexts in threes, z values like -2.19, 1.86, -1.70). Parts:
(a) `numfunc` — the one-tailed p-value `normalcdf(z)` (or `1 - normalcdf(z)` for the right
tail); (b) `choices` — whether that p-value is doubled for a two-tailed test. **Invariant: (a)
is the precomputed probability exactly, (b) matches the tail in the scenario.** The guide: the
alternative hypothesis decides which tail the p-value collects — `neq` splits the p-value
evenly between both tails, so a two-tailed p-value is twice the one-tailed area, which is why the
same data is harder to call significant when the question is posed that way. Mirrors 8.4.2 and
Problem Set 8.4 P6.

### q43-pvalue-two-scenarios.php — 2 parts
Two p-values for the SAME test (contexts in threes, e.g. 0.03 vs 0.40, both compared to
alpha = 0.05). Parts: (a) `choices` — which p-value leads to rejecting `H_0` and why (the one
below alpha); (b) `choices` — what a larger p-value at the same alpha means for confidence in
the decision (the decision is the same, the confidence is not — a p-value of 0.001 earns more
confidence than 0.04 even though both reject). **Invariant: both answers are constant per
scenario.** The guide: the two numbers carry more information than the yes-or-no answer they
produce — the comparison gives the decision, and the distance between them gives the confidence.
Mirrors 8.5.1's "by a hair and with room to spare" figure and Problem Set 8.4 P7/P9.

### q44-tail-picture.php — 2 parts
A test with a stated alternative (contexts in threes: left, right, two). Parts: (a) `choices`
— which tail the p-value lives in (left / right / both, from the symbol in `H_a`); (b)
`choices` — the shape of the shaded region (the single tail beyond the test statistic / both
tails). **Invariant: both answers are constant per scenario and consistent with `H_a`.**
The guide: the symbol in the alternative names the test — `<` puts the whole p-value in the
left tail, `>` in the right, `!=` splits it — and the `<=`/`>=` in the null is never what
decides the tail. Mirrors 8.5.2 and Problem Set 8.4 P4/P6.

### q45-conclusion-vs-claim.php — 2 parts
A completed test with its decision (contexts in threes). Parts: (a) `choices` — the conclusion
worded correctly about the population and the claim (e.g. "at the 5% significance level there
is sufficient evidence to conclude the mean is less than 4 hours" — NOT "the claim is false"
and NOT "we accept H0"); (b) `choices` — what the conclusion is NOT entitled to say (the test
did not prove the null, and it did not measure whether the claim is true). **Invariant: both
answers are constant per scenario.** The guide: the conclusion is the part a reader checks
first, and it is the one place where a correct calculation can still earn a wrong answer —
write it about the population the sample represents, in plain sentences about the original
claim. Mirrors 8.4.3 and Problem Set 8.4 P9.

### q65-sort-the-test-values.php — 2 parts
A full-test scenario with the numbers given (contexts in threes: the jail-time burglars setup,
the student-loan setup, the depression setup). Parts: (a) `choices` — which of the listed
numbers the test statistic actually uses (the claimed value, the sample mean, the sample size,
and the population standard deviation when it is "somehow known"); (b) `choices` — which listed
number is the distractor (the sample's own standard deviation — it describes the sample's
spread, not the population's, and does not go in when σ is known). **Invariant: both answers
are constant per scenario.** The guide: pull each value out of the problem statement and sort
them — the survey mean is the sample mean, the "somehow known" value is the population standard
deviation, and the survey's own spread is a different quantity that does not belong in the
test statistic. Mirrors 8.4 Problem Set P13/P14.

### q66-s-vs-sigma.php — 2 parts
A test where both `s` and `sigma` appear (contexts in threes, from the 8.4 problem-set
families). Parts: (a) `choices` — which spread the test statistic uses and why (σ, when it is
known — it is the actual spread of the whole population, not an estimate built from the sample);
(b) `choices` — what `s` would be used for instead (estimating the spread when σ is unknown —
the situation that calls for the t distribution). **Invariant: both answers are constant per
scenario.** The guide: `s_x` is an estimate of the spread built from just the sample, while σ
is the actual spread of the whole population — when the problem hands you σ, the better one is
the real one. Mirrors 8.4 Problem Set P15.

### q67-sampling-distribution-under-h0.php — 2 parts
A one-mean test with σ known (contexts in threes, clean arithmetic). Parts: (a) `numfunc` —
the standard error `sigma/sqrt(n)`; (b) `choices` — the sampling distribution of `X-bar` under
`H_0` (normal, centered at the null value `mu0`, with the standard error as its spread).
**Invariant: (a) is the precomputed value exactly, (b) is constant per scenario.** The guide:
the distribution in the table is not the distribution of the raw data — it is the sampling
distribution of the point estimate, the pattern you would see if you took sample after sample
and plotted the estimate each time; under `H_0` it is centered at the claimed value. Mirrors
8.4 Problem Set P16 and 8.3's Table 8.3.1.

### pre-frq-grade-a-pvalue-interpretation.php — 3 parts
The pre-FRQ mirror of `questions/frq/inference-for-means/q2-single-mean-interpreting-p-value.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Statistical Decision
(4)**, **Conclusion in Context (3)**, **Interpretation of Evidence (3)**. Drop **"Interpretation
of Evidence"** — a student can compare the p-value to alpha and write the conclusion in context
without ever explaining what the p-value measures in repeated sampling, and the section's own
"the p-value measures the surprise, not the truth" is exactly the step a plausible answer skips.
This is DIFFERENT from §7.3's pre-FRQ (which dropped Statistical Decision) and from every
earlier dropped category (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact,
2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two,
3.3 Second Factor, 3.4 State the Direction, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name
the Parameters, 5.1 State the Theoretical Value, 5.2 State the Empirical Value, 6.1 State the
Sum's Parameters, 6.2 Apply the Continuity Correction, 7.1 Confidence Level Meaning, 7.2
Assessing the Claim, 7.4 Build the Interval, 8.1 Real-World Example, 8.2 Name the Probabilities,
8.3 Check the Conditions). **Invariant: read each of the four responses against every rubric
line — no response earns a category it is supposed to be missing (category-purity), and every
number inside a response is generated from the same variables as the scenario.** Scope the CSS
with a `.qscope84` class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eleven you did not finish, if any.

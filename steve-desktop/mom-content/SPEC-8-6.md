# SPEC — §8.6 Hypothesis Testing of a Single Mean and Single Proportion (Intro Stats -SH) — LAB

Write **eleven** IMathAS question files into `mom-content/questions/stats-tests/hypothesis-testing/`
(read `q16-full-ht-one-mean-fresh.php` and `q8-ht-one-proportion.php` first — the z/t and
proportion machinery — plus `questions/stats-tests/AGENTS.md`, `questions/probability/AGENTS.md`
and `mom-content/reference/pre-frq-template.md`). Match the family shape: white-card UI, blue-chip
part labels, `jointrandfrom` parallel arrays, `numfunc` with the family tolerances,
`$noshuffle[N] = "all"` on every `choices` part, no `essay`, precompute every answer, type
picker Multipart. Questions that call a normal/t macro start COMMON CONTROL with
`loadlibrary("stats")`.

**Scope note:** §8.6 is a LAB, not a reading — the student runs three complete hypothesis tests
on data the class collects itself: the Television Survey (one mean, σ known = 2, normal
distribution), the Language Survey (one proportion, claimed 42.3%, normal-for-a-proportion), and
the Jeans Survey (one mean, σ unknown, n = 8, Student t with df = 7). The MOM assignment checks
the numbers on the section's OWN Try It Now demonstrations, which are fixed — so every answer is
deterministic and precomputable. The student still does the hands-on surveying in class; this
checks the numbers. Kind is `lab` (3 attempts × 20 versions, no penalty, no early-finish bonus,
GROUP gradebook category, 2 late passes, no time limit — per
`reference/intro-stats-assessment-settings.md`).

## The ten (roster is final — do not reorder, do not rebalance)

All eleven are new:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q53-lab-name-the-distribution.php` | 3 | 10 |
| 2 | `q54-tv-test-statistic.php` | 3 | 10 |
| 3 | `q55-tv-decision-and-conclusion.php` | 2 | 10 |
| 4 | `q56-language-condition-check.php` | 2 | 10 |
| 5 | `q57-language-test-statistic.php` | 3 | 10 |
| 6 | `q58-language-decision-and-conclusion.php` | 2 | 10 |
| 7 | `q59-jeans-test-statistic.php` | 3 | 10 |
| 8 | `q60-jeans-wrong-curve.php` | 2 | 10 |
| 9 | `q61-lab-honesty-check.php` | 3 | 10 |
| 10 | `q70-lab-state-the-hypotheses.php` | 3 | 10 |
| 11 | `pre-frq-grade-a-lab-hypothesis-test.php` | 3 | 10 |

Total: 10×9 + 10 = **100**. (Rebalanced 2026-08-14 to the every-assignment-is-100 rule; lab pre-FRQs are 10 points per the 4.4-7.4 labs.)

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your eleven files (the hypothesis-testing family is clean; the
17 pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a
matching `$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article
before an interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself**: loop every `rand()` combination and assert the invariant
named per question below. Report the combination count. Precompute every answer by hand from the
fixed dataset.

## The dialect rules (non-negotiable)

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**. Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (labs carry no free response either — the discussion questions
  are answered in class, not graded here).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic).
  Never `number`.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.
- **`loadlibrary("stats")` at the top of COMMON CONTROL** in every question that calls a
  normal/t macro — without it MOM rejects the call.
- **Tolerances match the family:** `abstolerance = 0.005` for p-values and proportions,
  `reltolerance = 0.01` for test statistics.
- The fixed Try It Now values are constants — write them as literals in the arrays, do not
  recompute them from a dataset you generate.

## The ten

The fixed demonstration values (the section's own Try It Now solutions — every answer below is
computed from THESE, so it is deterministic):

**Television Survey (Try It Now 8.6.2):** `mu0 = 4` hours, `sigma = 2`, `n = 30`, `xbar = 3.2`,
`alpha = 0.05`, left-tailed. SE = 2/sqrt(30) = 0.3651, z = (3.2 − 4)/0.3651 = −2.19,
p-value = P(Z < −2.19) ≈ 0.0142. Since 0.0142 < 0.05, reject H0.

**Language Survey (Try It Now 8.6.3):** `p0 = 0.423`, `n = 25`, `x = 14`, `alpha = 0.05`,
two-tailed. np0 = 10.575 ≥ 5, n(1−p0) = 14.425 ≥ 5. p' = 14/25 = 0.56,
SE = sqrt(0.423·0.577/25) = 0.0988, z = (0.56 − 0.423)/0.0988 = 1.39,
p-value = 2·P(Z > 1.39) = 2(0.0823) = 0.165. Since 0.165 > 0.05, do not reject.

**Jeans Survey (Try It Now 8.6.4):** `mu0 = 3` pairs, `n = 8`, `xbar = 3.5`, `s = 0.76`,
`alpha = 0.05`, right-tailed, df = 7. SE = 0.76/sqrt(8) = 0.2687, t = (3.5 − 3)/0.2687 = 1.86,
p-value = P(T7 > 1.86) ≈ 0.053. Since 0.053 > 0.05, do not reject. Read against the WRONG
(normal) curve: P(Z > 1.86) ≈ 0.031, which IS below 0.05 and would have rejected.

### q53-lab-name-the-distribution.php — 3 parts
The lab's opening distribution decision (Try It Now 8.6.1). Parts: (a) `choices` — the
Television Survey's distribution (normal — the problem hands you `sigma = 2`); (b) `choices` —
the Language Survey's distribution (normal-for-a-proportion — the parameter is p, and the claim
is "different from 42.3%"); (c) `choices` — the Jeans Survey's distribution (Student t with
df = 7 — σ withheld, n = 8 small). **Invariant: all three answers are constant on every seed.**
The guide: deciding the distribution first is the step that makes the rest of the arithmetic
mean anything — nothing downstream warns you when you picked the wrong curve. Mirrors Try It Now
8.6.1.

### q54-tv-test-statistic.php — 3 parts
The Television Survey's numbers (Try It Now 8.6.2). Parts: (a) `numfunc` — the standard error
2/sqrt(30) ≈ **0.3651**; (b) `numfunc` — the test statistic (3.2 − 4)/0.3651 ≈ **−2.19**; (c)
`numfunc` — the p-value ≈ **0.0142**. **Invariant: ~ 0.3651, ~ −2.19, ~ 0.0142 on every seed.**
The guide: with σ given the test statistic is a z-score built from the standard error of the
sample mean; the `sqrt(n)` in the denominator converts "how far below" into "how surprising" —
3.6 hours below 4 is far more surprising coming from 80 students than from 8. Mirrors Try It
Now 8.6.2 steps 2–4.

### q55-tv-decision-and-conclusion.php — 2 parts
The Television Survey's decision (Try It Now 8.6.2 steps 5–6). Parts: (a) `choices` — the
decision (reject H0 — 0.0142 < 0.05); (b) `choices` — the conclusion (at the 5% significance
level there is sufficient evidence to conclude that the mean hours of television watched per
day by students in this class is less than four — worded about the population this convenience
sample represents). **Invariant: both answers are constant on every seed.** The guide: the
conclusion is about the population the sample represents, and a p-value of 0.0142 means that if
the true mean really were 4 hours, a sample average this low or lower would turn up about 1.4%
of the time. Mirrors Try It Now 8.6.2 answer.

### q56-language-condition-check.php — 2 parts
The Language Survey's condition (Try It Now 8.6.3 step 1). Parts: (a) `numfunc` — np0 =
25(0.423) = **10.575**; (b) `numfunc` — n(1−p0) = 25(0.577) = **14.425**. **Invariant: 10.575
and 14.425 on every seed.** The guide: the condition is computed under the null — np0 and nq0,
not the sample proportions — because the normal curve is standing in for the binomial
distribution the null claims; both clear 5, so the normal approximation is legitimate. Mirrors
Try It Now 8.6.3 step 1.

### q57-language-test-statistic.php — 3 parts
The Language Survey's numbers (Try It Now 8.6.3 steps 3–5). Parts: (a) `numfunc` — the sample
proportion 14/25 = **0.56**; (b) `numfunc` — the standard error sqrt(0.423·0.577/25) ≈
**0.0988**; (c) `numfunc` — the test statistic (0.56 − 0.423)/0.0988 ≈ **1.39**. **Invariant:
~ 0.56, ~ 0.0988, ~ 1.39 on every seed.** The guide: the standard error uses the null
proportion, and "different from" is two-sided — the p-value will collect area in both tails,
which is why the same data is harder to call significant when the question is posed that way.
Mirrors Try It Now 8.6.3 steps 3–4.

### q58-language-decision-and-conclusion.php — 2 parts
The Language Survey's decision (Try It Now 8.6.3 steps 5–6). Parts: (a) `choices` — the
decision (do not reject H0 — the two-tailed p-value 0.165 > 0.05); (b) `choices` — the
conclusion (at the 5% significance level there is not sufficient evidence to conclude that the
proportion of students at this school who speak a language other than English at home differs
from 42.3%). **Invariant: both answers are constant on every seed.** The guide: 56% against
42.3% looks like a large gap and still is not significant — 25 students is a small sample, and
the two-tailed test spends its evidence on both directions at once; had the question been
one-tailed the p-value would have been 0.082, visibly closer. Mirrors Try It Now 8.6.3 answer.

### q59-jeans-test-statistic.php — 3 parts
The Jeans Survey's numbers (Try It Now 8.6.4 steps 2–4). Parts: (a) `numfunc` — the standard
error 0.76/sqrt(8) ≈ **0.2687**; (b) `numfunc` — the test statistic (3.5 − 3)/0.2687 ≈ **1.86**
on the t distribution with df = 7; (c) `numfunc` — the p-value ≈ **0.053**. **Invariant: ~
0.2687, ~ 1.86, ~ 0.053 on every seed.** The guide: with σ unknown you estimate it from the
same eight numbers you used to estimate the center — that substitution is the entire reason the
distribution changes, and the t distribution accounts for it with fatter tails; at df = 7 the t
curve needs 1.895 to clear the 5% mark where the normal is satisfied by 1.645. Mirrors Try It
Now 8.6.4 steps 2–4.

### q60-jeans-wrong-curve.php — 2 parts
The Jeans Survey's wrong-curve lesson (Try It Now 8.6.4 step 6). Parts: (a) `numfunc` — the
p-value the SAME test statistic 1.86 would have produced against the (wrong) normal curve ≈
**0.031**; (b) `choices` — what the wrong curve would have led you to conclude (reject H0 —
which is the opposite of the correct decision, and the direction that manufactures findings).
**Invariant: ~ 0.031 on every seed, (b) is constant.** The guide: reading a t-score against a
normal table does not produce an error message — it produces a smaller p-value than the data
supports, a mistake that only ever fails in the direction that manufactures findings; nothing
about the arithmetic looks wrong, the only thing separating a published finding from a
non-finding was picking the right curve. Mirrors Try It Now 8.6.4 step 6 and the answer.

### q61-lab-honesty-check.php — 3 parts
The lab's honesty discussion (8.6.1's two honesty problems). Parts: (a) `choices` — why the
conclusion is limited (the class is a convenience sample, so the conclusion describes the
population actually sampled from, not the whole school); (b) `choices` — why self-reported data
is soft (people round, estimate, and shade answers toward what sounds normal, so a small effect
may measure reporting rather than behavior); (c) `choices` — whether either problem invalidates
the arithmetic (no — the arithmetic is identical and the practice is real; it limits what the
conclusion is a conclusion about). **Invariant: all three answers are constant on every seed.**
The guide: a test run on a convenience sample can be executed perfectly and still not support
the sweeping claim it looks like it supports — record the number you were given and note where
you suspect the reporting is soft. Mirrors 8.6.1's two honesty paragraphs.

### q70-lab-state-the-hypotheses.php — 3 parts
The lab's step-1 for all three surveys (the `H_0`: ____ / `H_a`: ____ blanks in 8.6.2–8.6.4).
Parts: (a) `choices` — the Television Survey's pair (`H_0: mu = 4`, `H_a: mu < 4` — the claim
on trial is the national average, and "lower" points left); (b) `choices` — the Language
Survey's pair (`H_0: p = 0.423`, `H_a: p != 0.423` — "different from" is two-sided); (c)
`choices` — the Jeans Survey's pair (`H_0: mu = 3`, `H_a: mu > 3` — "higher than" points
right). **Invariant: all three answers are constant on every seed.** The guide: the null
hypothesis is the claim on trial, not the one you want — your guess goes in `H_a`, the null
gets the benefit of the doubt, and your data has to be surprising enough under that assumption
to overturn it. Mirrors 8.6.2–8.6.4's hypothesis blanks and Try It Now 8.6.2/8.6.3/8.6.4 step 1.

### pre-frq-grade-a-lab-hypothesis-test.php — 3 parts
Authored-first pre-FRQ (no lab FRQ to mirror; the single-mean-hypotheses, type-I/II,
distribution-selection, p-value-interpretation and full-HT-workflow pre-FRQs are claimed by
§8.1/§8.2/§8.3/§8.4/§8.5). Categories, 10 pts: **State the Hypotheses (3)** — H0 and Ha in
symbols for the named survey; **Run the Test (4)** — the test statistic and p-value on the
correct distribution; **Write the Conclusion (3)** — the decision and the plain-language
conclusion about the population actually sampled. Dropped category: **Run the Test** — a
student can state the hypotheses and write a conclusion for a given result without ever
computing the test statistic, and the lab's own "the distribution is a decision you make before
you compute anything" is exactly the step a plausible answer skips (the arithmetic is the one
category the others imply but never demand). This is DIFFERENT from the 4.4/4.6/5.3/6.4 lab
pre-FRQs' dropped categories (State the Theoretical Value / State the Empirical Value / Compare
and Explain the Gap), from §7.1/§7.2/§7.3/§7.4's (Confidence Level Meaning / Assessing the Claim
/ Statistical Decision / Build the Interval), and from §8.1–§8.5's (Real-World Example / Name
the Probabilities / Check the Conditions / Interpretation of Evidence / Test stat + p-value) —
the template forbids repeating a dropped category; note the difference in the manifest `_note`.
Invariant structure per `pre-frq-template.md`: `array("choices", "multans", "choices")`,
`$scoremethod[1] = "allornothing"`, four responses built by concatenating one sentence per
category then dropping one, part (b) grades a DIFFERENT response than part (a) names.
**Invariant: no response earns a category it is supposed to be missing (category purity), and
every number inside a response is generated from the same variables as the scenario.** Scope the
CSS with `.qscope86`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the eleven you did not finish, if any.

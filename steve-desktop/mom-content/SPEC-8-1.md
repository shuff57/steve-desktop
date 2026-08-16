# SPEC — §8.1 Null and Alternative Hypotheses (Intro Stats -SH)

Write **twelve** IMathAS question files into `mom-content/questions/stats-tests/hypothesis-testing/`
(the files already there are the pattern — **read `q15-ho-ha-fresh-context.php`,
`q6-null-alt-hypotheses.php` and `q16-full-ht-one-mean-fresh.php` before starting**, plus
`questions/stats-tests/AGENTS.md` and `questions/probability/AGENTS.md`). Match the family shape:
white-card UI, blue-chip part labels, `jointrandfrom`/parallel-array scenarios with precomputed
answers, `numfunc` for numeric parts, `choices` with `$noshuffle[N] = "all"`, no `essay`, type
picker Multipart.

**Scope note:** §8.1 is *Null and Alternative Hypotheses* — the two hypotheses, choosing the
symbols (`=`, `≠`, `≤`, `<`, `≥`, `>`), writing the pair from a claim about a population mean or a
population proportion, naming the random variable in words, and the two decisions a test can end in
(reject / do not reject — never "accept"). The two reuses carry hypothesis-writing and
symbol/tail selection; the new files carry the parameter identification, the random-variable
description, the symbol-pair mechanics, claim-to-hypotheses for means and proportions, the
decisions, and the choose-the-correct-pair exercise.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed in 334437, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/hypothesis-testing/q15-ho-ha-fresh-context.php` | parameter + H0/Ha + left/right/two-tailed from a story |
| 2 | `questions/stats-tests/hypothesis-testing/q6-null-alt-hypotheses.php` | identify H0/Ha and the test type |

New files to author (slots 3–12):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q18-name-the-parameter.php` | 2 | 10 |
| 4 | `q19-describe-the-random-variable.php` | 2 | 10 |
| 5 | `q20-hypothesis-symbol-pairs.php` | 3 | 10 |
| 6 | `q21-claims-to-hypotheses.php` | 2 | 10 |
| 7 | `q22-proportion-claim-hypotheses.php` | 2 | 10 |
| 8 | `q23-the-two-decisions.php` | 3 | 9 |
| 9 | `q24-choose-the-correct-pair.php` | 1 | 9 |
| 10 | `q62-sort-the-numbers.php` | 2 | 10 |
| 11 | `q63-parameter-for-each-statement.php` | 3 | 10 |
| 12 | `pre-frq-grade-a-single-mean-hypotheses.php` | 3 | 12 |

Points: 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 8 + 12 = **100**. (Rebalanced 2026-08-14 to the every-assignment-is-100 rule.)

Manifest: `books/introduction-to-stats-sh/hw/8-1-null-and-alternative-hypotheses.json`, kind `hw`.

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
- Hypothesis notation in the family renders as `H_0`/`H_a` inside backticks, or `H&#8320;`/
  `H&#8321;` in HTML text — copy whichever the file you are mirroring uses. Keep the pair on one
  line in `choices` options (`H_0: mu = 4.5` / `H_a: mu > 4.5`).
- **Tolerances match the family:** `abstolerance = 0.005` wherever a number is graded.
- No normal/t macros are needed in this section — hypotheses are writing, not arithmetic. Do not
  call `loadlibrary("stats")` unless a question actually calls a macro.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q18-name-the-parameter.php — 2 parts
A claim about a mean or a proportion (contexts in threes and threes). Parts: (a) `choices` — the
parameter (`mu` for a mean claim, `p` for a proportion claim); (b) `choices` — the claimed value
written as a parameter statement (`mu = 58,000`, `p = 0.83`). **Invariant: (a) and (b) are constant
per scenario and match the claim wording.** The guide: a statement about an average uses `mu`; a
statement about a percentage or a chance uses `p` — decide the parameter before touching the
symbols. Mirrors 8.1 Problem Set 1–6 and Example 8.1.1.

### q19-describe-the-random-variable.php — 2 parts
Same claims as q18 (different contexts). Parts: (a) `choices` — the random variable in words (the
sample mean of the measurement / the sample proportion of the trait); (b) `choices` — whether the
test is about an average or a share. **Invariant: both answers are constant per scenario.**
The guide: the random variable is the sample statistic that varies from sample to sample — name the
units, the group, and the sample size when you describe it. Mirrors 8.1 Problem Set 1, 3, 5.

### q20-hypothesis-symbol-pairs.php — 3 parts
A claim with a direction (contexts in threes: "higher", "less than", "different from"). Parts: (a)
`choices` — the correct `H_0` symbol (`=`, `<=`, `>=`); (b) `choices` — the correct `H_a` symbol
(`!=`, `>`, `<`); (c) `choices` — the tail the test runs on (left, right, two). **Invariant: (a),
(b), (c) are constant per scenario and consistent with each other.** The guide: read the table
across, not down — each row is a matched pair, and the two entries must partition the number line;
the equal sign always lives in `H_0`, never in `H_a`. Mirrors 8.1 Try It Now 8.1.2 and
Table 8.1.1.

### q21-claims-to-hypotheses.php — 2 parts
A mean claim with a direction (contexts in threes). Parts: (a) `choices` — the full `H_0` statement
in symbols; (b) `choices` — the full `H_a` statement. **Invariant: both answers are constant per
scenario and the pair is contradictory.** The guide: the status quo goes in the null with its
equality; the researcher's claim goes in the alternative; both the `<=`/`>=` form and the `=`
form are accepted for the null — the arithmetic uses the boundary value either way, so what matters
is consistency inside the problem. Mirrors 8.1 Problem Set 2, 4, 9 and Example 8.1.3.

### q22-proportion-claim-hypotheses.php — 2 parts
A proportion claim with a direction (contexts in threes, claimed values like 0.83, 0.42, 0.095,
0.30). Parts: (a) `choices` — `H_0` in symbols; (b) `choices` — `H_a` in symbols. **Invariant:
both answers are constant per scenario.** The guide: same machinery as the mean — the claim on
trial goes in the null, the direction word ("more than", "fewer than", "different from") goes in
the alternative. Mirrors 8.1 Problem Set 6, 7, 10, 12, 13 and Try It Now 8.1.1.

### q23-the-two-decisions.php — 3 parts
One scenario per context (contexts in threes). Parts: (a) `choices` — what "do not reject `H_0`"
means (the sample did not settle the question — NOT that the claim is true); (b) `choices` — why a
test can never "accept" `H_0` (the procedure is built to protect the null, so weak evidence only
tells you the sample did not settle it); (c) `choices` — what "reject `H_0`" means (the sample
favors the alternative). **Invariant: all three answers are constant across seeds.** The guide:
a jury that acquits has not declared the defendant innocent — "do not reject" is never the same
sentence as "accept", and that wording is the part a reader checks first. Mirrors 8.1.1's two
decisions and Problem Set 8.1's decision items.

### q24-choose-the-correct-pair.php — 1 part
A claim (contexts in threes). Part: (a) `choices` — the correct `H_0`/`H_a` pair, with four
distractor pairs built from the classic errors: a sample statistic in place of the parameter
(`x-bar` instead of `mu`), the wrong claimed value (the sample mean instead of the claim), the
wrong direction (left when the claim says right), and a pair that overlaps. **Invariant: the
correct answer is constant per scenario and each distractor is wrong for exactly one reason.**
The guide: hypotheses are always statements about a population parameter, the claimed value comes
from the claim not the sample, and the direction word picks the alternative. Mirrors 8.1 Problem
Set 14.

### q62-sort-the-numbers.php — 2 parts
A full-test scenario with the numbers given (contexts in threes: the jail-time burglars setup,
the student-loan setup, the depression setup). Parts: (a) `choices` — which of the listed
numbers the test actually uses (the claimed value, the sample mean, the sample size, and the
spread that applies — NOT the sample's own standard deviation when σ is known); (b) `choices` —
which number is the distractor and why (the sample standard deviation when the population
standard deviation is "somehow known" — the test statistic uses σ, not s). **Invariant: both
answers are constant per scenario.** The guide: sort the numbers you need from the ones you do
not — the sample size, the sample mean, and the population standard deviation belong to the
calculation; the sample's own spread is a different quantity and does not go in when σ is known.
Mirrors 8.1 Problem Set 8 and 8.4 Problem Set P13–P15.

### q63-parameter-for-each-statement.php — 3 parts
Three statements about the same study (contexts in threes, one mean claim, one proportion
claim, one mixed pair). Parts: (a) `choices` — the parameter for the first statement; (b)
`choices` — the parameter for the second; (c) `choices` — the parameter for the third.
**Invariant: all three answers are constant per scenario and each matches its own statement.**
The guide: a statement about an average uses a population mean `mu`; a statement about a
percentage or a chance uses a population proportion `p` — decide each one from its own wording,
not from the study's topic. Mirrors 8.1 Problem Set 11.

### pre-frq-grade-a-single-mean-hypotheses.php — 3 parts
The pre-FRQ mirror of `questions/frq/inference-for-means/q1-single-mean-concept-and-hypotheses.php`
— read that file first and take the target strings from it (per
`mom-content/reference/pre-frq-template.md`, which also holds the invariant structure:
`array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`, four responses
built by concatenating one sentence per category then dropping one, part (b) grades a DIFFERENT
response than part (a) names). Rubric categories from the mirror FRQ: **Purpose of the Test (3)**,
**Real-World Example (3)**, **Hypotheses (4)**. Drop **"Real-World Example"** — a student can state
the purpose and write the hypotheses without ever applying them to a concrete scenario, and the
section's own "get the pair wrong and every calculation after it answers the wrong question" is
exactly the step a plausible answer skips. Not in the used table (2.3 Percentile, 2.4 Contextual
Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample
Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure,
4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters, 5.1 State the Theoretical Value,
5.2 State the Empirical Value, 6.1 State the Sum's Parameters, 6.2 Apply the Continuity
Correction, 7.1 Confidence Level Meaning, 7.2 Assessing the Claim, 7.3 Statistical Decision, 7.4
Build the Interval). **Invariant: read each of the four responses against every rubric line — no
response earns a category it is supposed to be missing (category-purity), and every number inside
a response is generated from the same variables as the scenario.** Scope the CSS with a
`.qscope81` class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

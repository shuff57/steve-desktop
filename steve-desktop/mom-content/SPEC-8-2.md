# SPEC — §8.2 Outcomes and the Type I and Type II Errors (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **eleven** IMathAS question files into `mom-content/questions/stats-tests/hypothesis-testing/`
(the files already there are the pattern — **read `q11-type-i-ii-context.php`,
`q9-reject-nuanced.php` and `q16-full-ht-one-mean-fresh.php` before starting**, plus
`questions/stats-tests/AGENTS.md` and `questions/probability/AGENTS.md`). Match the family shape:
white-card UI, blue-chip part labels, `jointrandfrom`/parallel-array scenarios with precomputed
answers, `numfunc` for numeric parts, `choices` with `$noshuffle[N] = "all"`, no `essay`, type
picker Multipart.

**Scope note:** §8.2 is *Outcomes and the Type I and Type II Errors* — the four outcomes of a
hypothesis test (the 2×2 table), stating a Type I error and a Type II error in the context of a
specific claim, `alpha` and `beta` in words, the power of the test `1 - beta`, and judging which
of the two errors carries the heavier consequence. The two reuses carry error-stating in context
and the rigid reject/fail-to-reject decision; the new files carry the four-outcome table, the
two errors stated against a flipped null, alpha/beta/power naming, the beta-from-power
arithmetic, and the consequence judgment.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed in 334437, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/stats-tests/hypothesis-testing/q11-type-i-ii-context.php` | Type I and Type II errors stated in context |
| 2 | `questions/stats-tests/hypothesis-testing/q9-reject-nuanced.php` | rigid reject / fail-to-reject rule near alpha, no "accept" |

New files to author (slots 3–11):

| Slot | File | Parts | Points |
|---|---|---|---|
| 3 | `q25-the-four-outcomes.php` | 4 | 10 |
| 4 | `q26-type-i-error-in-context.php` | 2 | 10 |
| 5 | `q27-type-ii-error-in-context.php` | 2 | 10 |
| 6 | `q28-alpha-beta-power.php` | 3 | 10 |
| 7 | `q29-which-error-costs-more.php` | 2 | 10 |
| 8 | `q30-power-and-beta.php` | 2 | 9 |
| 9 | `q31-error-consequences.php` | 2 | 9 |
| 10 | `q64-both-errors-template.php` | 2 | 10 |
| 11 | `pre-frq-grade-a-type-i-ii-errors.php` | 3 | 12 |

Points: 9 + 9 + 9 + 9 + 9 + 9 + 8 + 8 + 9 + 9 + 12 = **100**. (Rebalanced 2026-08-14 to the every-assignment-is-100 rule.)

Manifest: `books/introduction-to-stats-sh/hw/8-2-outcomes-and-the-type-i-and-type-ii-errors.json`, kind `hw`.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your nine files (the hypothesis-testing family is clean; the
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
- Hypothesis notation: `H_0`/`H_a` inside backticks, `H&#8320;`/`H&#8321;` in HTML text.
- **Tolerances match the family:** `abstolerance = 0.005` for probabilities.
- No normal/t macros are needed in this section — errors are stated in words, not computed from
  curves. Do not call `loadlibrary("stats")` unless a question actually calls a macro.

## The eight new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` parallel arrays (4–6
scenarios per question). Precompute the answers.

### q25-the-four-outcomes.php — 4 parts
A scenario with a stated `H_0` (contexts in threes). Parts: (a) `choices` — the outcome when you
do not reject and `H_0` is true (correct); (b) `choices` — the outcome when you reject and `H_0`
is true (Type I error); (c) `choices` — the outcome when you do not reject and `H_0` is false
(Type II error); (d) `choices` — the outcome when you reject and `H_0` is false (correct, whose
probability is the power). **Invariant: all four answers are constant per scenario and match the
table.** The guide: cross the two truths with the two decisions and you get four combinations;
the two errors sit on opposite diagonals, and each error is tied to a specific decision — a Type
I error can only happen on a run where you rejected. Mirrors 8.2.1 and Table 8.2.1.

### q26-type-i-error-in-context.php — 2 parts
A scenario with a null hypothesis (contexts in threes). Parts: (a) `choices` — the Type I error
stated in context (reject the null when it is true, worded about the claim); (b) `choices` —
which decision the Type I error is tied to (reject — it can only happen on a run where you
rejected). **Invariant: both answers are constant per scenario.** The guide: say out loud "I
rejected it, and it was true" — the trick that never fails; a Type I error is a true null that
got rejected anyway, and alpha is how often you allow it. Mirrors 8.2.1 Try It Now 8.2.1 and
Problem Set 8.2 P1/P2/P5.

### q27-type-ii-error-in-context.php — 2 parts
Same scenarios as q26 (different contexts or the same family). Parts: (a) `choices` — the Type
II error stated in context (fail to reject a false null); (b) `choices` — which decision it is
tied to (do not reject). **Invariant: both answers are constant per scenario.** The guide: "I
did not reject it, and it was false" — a real effect the test walked past. Mirrors 8.2.1 and
Problem Set 8.2 P1/P2/P5.

### q28-alpha-beta-power.php — 3 parts
A test with stated alpha (contexts in threes, `alpha` 0.01/0.05/0.10). Parts: (a) `choices` —
what `alpha` is the probability of (a Type I error — rejecting a true null); (b) `choices` —
what `beta` is the probability of (a Type II error); (c) `choices` — what the power of the test
measures (the probability of rejecting `H_0` when it is false). **Invariant: all three answers
are constant across seeds.** The guide: `alpha` and `beta` are the two ways a test can be wrong,
and power is `1 - beta` — the probability of catching a real effect. Mirrors 8.2.2 and Problem
Set 8.2 P3/P4.

### q29-which-error-costs-more.php — 2 parts
A high-stakes scenario (contexts in threes: FDA drug review, surgery decision, fire alarm).
Parts: (a) `choices` — which error carries the heavier consequence in THIS scenario; (b)
`choices` — a one-sentence reason (the consequence that follows for the patient / the public /
the patient again). **Invariant: both answers are constant per scenario and the reason matches
the chosen error.** The guide: the judgment behind every significance level is which mistake you
can least afford — a Type I error is the alarm going off while you make toast; a Type II error
is the alarm staying silent during a real fire, and they are not equally bad. Mirrors 8.2.4,
Try It Now 8.2.4 and Problem Set 8.2 P6/P12/P13.

### q30-power-and-beta.php — 2 parts
A test with stated power (contexts in threes, powers like 0.981, 0.88, 0.70). Parts: (a)
`numfunc` — `beta = 1 - power`; (b) `choices` — what the power value means in context (the
chance the test catches the real effect when one exists). **Invariant: (a) = 1 - power exactly,
(b) is constant per scenario.** The guide: power and `beta` are complements that have to sum to
1 — at power 0.40, twelve of twenty real effects are missed. Mirrors Problem Set 8.2 P4/P7 and
the power definition in 8.2.3.

### q31-error-consequences.php — 2 parts
A scenario where the null is flipped between contexts (contexts in threes, including at least
one where `H_0` is the cautious statement — "the drug is unsafe" — so the errors swap places).
Parts: (a) `choices` — the Type I error in context; (b) `choices` — the Type II error in
context. **Invariant: both answers are constant per scenario AND match the flipped null — the
same context with the null swapped must produce swapped errors.** The guide: naming the null
first matters so much here — swap `H_0` and `H_a` and you swap the two errors along with them,
including which one you were trying hardest to avoid. Mirrors 8.2 Problem Set P10/P13 and the
"notice that the null hypothesis has flipped" note.

### q64-both-errors-template.php — 2 parts
A scenario with a stated null (contexts in threes, from the 8.2 problem-set families). Parts:
(a) `choices` — the Type I error stated in context; (b) `choices` — the Type II error stated in
context. **Invariant: both answers are constant per scenario and match the stated null.** The
guide: use the same template for every part — each statement is the null hypothesis; the Type I
error says the claim was true and we rejected it, the Type II error says the claim was false and
we did not. Mirrors 8.2 Problem Set P11 and the "trick that never fails" in 8.2.1.

### pre-frq-grade-a-type-i-ii-errors.php — 3 parts
Authored-first pre-FRQ (no Type I/II FRQ exists anywhere in `questions/frq/` — per
`mom-content/reference/pre-frq-template.md` the pre-FRQ is written anyway and defines the
scenario and rubric a later FRQ should match). Categories, 10 pts: **State Both Errors in
Context (4)** — the Type I and Type II errors worded about the claim, tied to their decisions;
**Name the Probabilities (3)** — `alpha` as the probability of the Type I error and `beta` as
the probability of the Type II error; **Judge Which Error Costs More (3)** — the heavier
consequence and a one-sentence reason. Dropped category: **Name the Probabilities** — a student
can state both errors in context and judge their consequences without ever attaching the
lettered probabilities, and the section's own "the trick that never fails" is exactly the step a
plausible answer skips. This is DIFFERENT from every earlier dropped category (2.3 Percentile,
2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
Direction, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters, 5.1 State the
Theoretical Value, 5.2 State the Empirical Value, 6.1 State the Sum's Parameters, 6.2 Apply the
Continuity Correction, 7.1 Confidence Level Meaning, 7.2 Assessing the Claim, 7.3 Statistical
Decision, 7.4 Build the Interval, 8.1 Real-World Example). Invariant structure per
`pre-frq-template.md`: `array("choices", "multans", "choices")`, `$scoremethod[1] =
"allornothing"`, four responses built by concatenating one sentence per category then dropping
one, part (b) grades a DIFFERENT response than part (a) names. **Invariant: no response earns a
category it is supposed to be missing (category purity).** Scope the CSS with a `.qscope82`
class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the nine you did not finish, if any.

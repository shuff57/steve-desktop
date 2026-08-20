# SPEC — §4.2 Mean or Expected Value and Standard Deviation (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **six** IMathAS question files into `mom-content/questions/probability/expected-value/`
(read `q5-ev-game-of-chance-2.php`, `q3-ev-fill-missing-prob.php`, `q2-ev-insurance.php` and
`q22-expected-value-of-discrete-rv.php` first — the four reused files carry the section's EV core
and are the pattern; also read `questions/probability/expected-value/AGENTS.md`,
`questions/probability/AGENTS.md`, and `pre-frq-grade-an-ev-decision.php` so the new pre-FRQ is
distinct from it). Match the family shape: white-card UI, blue-chip part labels, `jointrandfrom`
parallel arrays with precomputed answers, `numfunc` for numeric parts, `choices` with
`$noshuffle[N] = "all"`, `abstolerance = 0.005`, no `essay` parts.

**Scope note:** §4.2 is *mean/expected value AND standard deviation of a discrete distribution*.
The four reuses drill the EV half. The six new files carry the other half — the **standard
deviation column `(x − μ)²·P(x)`**, the **Law of Large Numbers**, **catching a wrongly-filled
table**, **same-mean-different-σ comparison**, **completing an EV table** — plus one pre-FRQ.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged; 4.1 also slots them, which is
fine — a question serves any number of assignments, and these are the section's canonical EV
items):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/probability/q22-expected-value-of-discrete-rv.php` | sum-to-1 + E(X) + E(X²) |
| 2 | `questions/probability/expected-value/q3-ev-fill-missing-prob.php` | fill missing prob via sum-to-1, then E(X) |
| 3 | `questions/probability/expected-value/q5-ev-game-of-chance-2.php` | EV, 100-play total, fair? |
| 4 | `questions/probability/expected-value/q2-ev-insurance.php` | EV of an insurance policy |

New files (slots 5–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 5 | `q6-sd-of-distribution.php` | 3 | 9 |
| 6 | `q7-law-of-large-numbers.php` | 3 | 10 |
| 7 | `q8-find-the-mistake-in-table.php` | 3 | 9 |
| 8 | `q9-sd-compare-two-distributions.php` | 3 | 10 |
| 9 | `q10-complete-the-ev-table.php` | 3 | 10 |
| 10 | `pre-frq-grade-a-long-run-interpretation.php` | 3 | 12 |

Total: 4×10 (reuses) + 9 + 10 + 9 + 10 + 10 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your five files. Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article` — each hit singular and consonant-sounded); no marker text
quoted inside a comment.

**Also seed-sweep each one yourself** with a throwaway node script: loop every combination your
`rand()` calls can produce and assert the invariant named per question below. Report the number of
combinations checked. One render proves one seed; the sweep proves the rest.

## The non-negotiable dialect rules

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**.
- Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (homework carries no free response).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic);
  `abstolerance = 0.005`. Never `number`.
- **Precompute every answer**; never let MOM compute `E(X)` or `sqrt` via macros.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The six new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` with 4–6 parallel-array
scenarios, exactly as the family does. Precompute the answers.

### q6-sd-of-distribution.php — 3 parts
A legitimate distribution table (4–5 values, probabilities summing to 1, clean decimals). Parts:
(a) `numfunc` — the expected value `mu = sum x*P(x)`; (b) `numfunc` — the variance
`Var(X) = sum (x - mu)^2 * P(x)`; (c) `numfunc` — the standard deviation `sigma = sqrt(Var(X))`.
**Invariant: on every seed, `mu` is the precomputed weighted sum, `Var` is the precomputed weighted
squared deviation, and `sigma = sqrt(Var)` to 4 decimals. Pick scenarios whose `mu` is a clean
decimal and whose `Var` is a clean square or clean decimal so `sigma` is exact or one clean
decimal.** The solution guide shows the deviation column filled in, exactly like the section's
Example 4.4.

### q7-law-of-large-numbers.php — 3 parts
Conceptual. Part (a) `choices`: which statement best describes the Law of Large Numbers — the
long-run relative frequency approaches the theoretical probability as trials grow. Part (b)
`choices`: given a 10-flip run that landed 7 heads, what does probability promise about the next
flip? Part (c) `number`: a fair coin is flipped 24,000 times (the Karl Pearson example) — the
expected number of heads is `n * p`. **Invariant: the two `choices` answers are constant across
seeds (only the context text varies, in threes), and part (c) is `n * p` for the stated `n` and
`p`.**

### q8-find-the-mistake-in-table.php — 3 parts
An expected value table with exactly ONE mistake — either a `P(x)` column that does not sum to 1
(one row wrong), or an `x * P(x)` product that is wrong (one product misfilled). Parts: (a)
`choices` — is the mistake in the `P(x)` column or the `x * P(x)` column? (b) `number` — the sum
of the `P(x)` column; (c) `number` — the correct value that should be in the wrong cell. **Invariant:
on every seed, exactly one cell is wrong, the column sums are as stated, and part (c) is the
corrected value of the single wrong cell.** The solution guide names the row, what was written,
and what it should be. Mirrors the section's 4.2.4/4.2.5.

### q9-sd-compare-two-distributions.php — 3 parts
Two distributions of the SAME random variable with the SAME mean but different spreads (one
tight, one wide — e.g. x = 0,1,2,3,4 vs x = 0,2,4 with appropriate probabilities). Parts: (a)
`numfunc` — `sigma` of distribution A; (b) `numfunc` — `sigma` of distribution B; (c) `choices` —
which distribution has outcomes clustered more tightly around the mean (the one with smaller
`sigma`). **Invariant: on every seed, A and B have the same `mu` to 4 decimals, their `sigma`s
differ, and part (c)'s answer matches the smaller `sigma`.** The solution guide's point: the
expected value says where the distribution balances; the standard deviation says how spread out it
is — two distributions can share `mu` and behave completely differently (section's Context Pause).

### q10-complete-the-ev-table.php — 3 parts
An expected value table with the `x * P(x)` column left blank (mirrors 4.2.1/4.2.2: x and P(x)
given, products to fill). Parts: (a) `numfunc` — the missing product for a named row; (b)
`numfunc` — the sum of the `x * P(x)` column, which is `mu`; (c) `choices` — what the `x * P(x)`
column sums to and why (the expected value — the long-run average). **Invariant: on every seed,
part (a) equals `x_k * P(x_k)` for the named row, part (b) is the precomputed `mu`, and part (c)
is constant.** The solution guide fills the whole column like the section's Try It Now 4.3.

### pre-frq-grade-a-long-run-interpretation.php — 3 parts
The pre-FRQ mirror of `questions/frq/probability/q1-expected-value-decision.php` — read that file
first and take the target strings from it (per `mom-content/reference/pre-frq-template.md`, which
also holds the invariant structure: `array("choices", "multans", "choices")`,
`$scoremethod[1] = "allornothing"`, four responses built by concatenating one sentence per
category then dropping one, part (b) grades a DIFFERENT response than part (a) names). Rubric
categories: **Compute Expected Value (6)** and **Decision and Interpretation (4)** from the mirror
FRQ, plus a third category the mirror's rubric implies but never states, to make the dropped
category meaningful. Drop **"Verify the Probabilities Sum to 1"** (the section's stated habit:
"always check that the P(x) column sums to 1; if it does not, either a probability is missing or
one is wrong" — a student can compute `E(X)` and state the decision without ever checking the
column is legitimate). This is DIFFERENT from the 4.1 pre-FRQ's dropped category (State the Values
and Probabilities — pairing X with its probabilities) — the two pre-FRQs must not teach the same
lesson. Check the used table (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact,
2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two,
3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1 State the Values) — none
is this. **Invariant: read each of the four responses against every rubric line — no response
earns a category it is supposed to be missing (category-purity), and every number inside a
response is generated from the same variables as the scenario.** Scope the CSS with a `.qscope42`
class since the pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the five you did not finish, if any.

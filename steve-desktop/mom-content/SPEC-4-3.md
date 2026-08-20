# SPEC — §4.3 Binomial Distribution (Intro Stats -SH)

**Book:** introduction-to-stats-sh
**Skill:** mom-question

Write **ten** IMathAS question files into `mom-content/questions/probability/binomial/` (new
folder — read `questions/probability/AGENTS.md` and `questions/probability/expected-value/AGENTS.md`
first for the family conventions: white-card UI, blue-chip part labels, `jointrandfrom` parallel
arrays, `numfunc` with `abstolerance = 0.005`, `$noshuffle[N] = "all"` on every `choices` part,
no `essay`, precompute every answer, type picker Multipart). Also read
`mom-content/reference/pre-frq-template.md` before writing the pre-FRQ.

**Scope note:** §4.3 is the binomial distribution — the three conditions, `X ~ B(n, p)` notation,
`P(X = x) = C(n,x) p^x q^(n-x)`, `mu = np`, `sigma = sqrt(npq)`, translating "at least / at most /
more than / less than", and recognizing when an experiment is NOT binomial (the without-replacement
tell). The bank has ZERO binomial questions — every slot is authored fresh. No pre-FRQ exists to
mirror (the bank's FRQs cover sampling distributions, inference, and expected value, not binomial);
per the pre-FRQ rule "no FRQ to mirror is not an exemption" — this pre-FRQ is authored first and
defines the shape a later binomial FRQ should match.

## The ten (roster is final — do not reorder, do not rebalance)

All ten are new. Slots 1–9 auto-graded, slot 10 the pre-FRQ:

| Slot | File | Parts | Points |
|---|---|---|---|
| 1 | `q1-binomial-conditions.php` | 3 | 9 |
| 2 | `q2-binomial-notation.php` | 3 | 9 |
| 3 | `q3-binomial-exact-prob.php` | 1 | 10 |
| 4 | `q4-binomial-at-most.php` | 1 | 10 |
| 5 | `q5-binomial-at-least.php` | 1 | 10 |
| 6 | `q6-binomial-mean-sd.php` | 2 | 10 |
| 7 | `q7-binomial-translate.php` | 3 | 9 |
| 8 | `q8-not-binomial.php` | 2 | 10 |
| 9 | `q9-binomial-compare-outcomes.php` | 2 | 11 |
| 10 | `pre-frq-grade-a-binomial-setup.php` | 3 | 12 |

Total: 9+9+10+10+10+10+9+10+11+12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your ten files (the expected-value family is clean; the 17
pre-existing findings are elsewhere and not yours). Checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[`; no `$answer[]` after the QUESTION TEXT marker; no article before an
interpolated noun (kind `article`); no marker text quoted inside a comment.

**Also seed-sweep each one yourself** with a throwaway node script: loop every `rand()` combination
and assert the invariant named per question below. Report the combination count. Precompute every
binomial probability in the scenario arrays — never call `binompdf`/`binomcdf` (a wrong macro call
renders an empty box with no error).

## The dialect rules (non-negotiable)

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**. Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`.**
- **No `essay` parts anywhere** (homework carries no free response).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic);
  `abstolerance = 0.005`. Never `number`.
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()` or carry display
  strings. Type picker is **Multipart** even for single-part questions.

## The ten

Contexts come in 3–5 parallel-array scenarios per question; the invariant decides the count.

### q1-binomial-conditions.php — 3 parts
A described experiment (3–4 contexts). Parts: (a) `choices` — does it have a fixed number of
trials? (b) `choices` — does each trial have exactly two outcomes with constant success
probability? (c) `choices` — are the trials independent? (Include one context that FAILS the
independence condition — e.g. drawing without replacement — so the answer varies by scenario.)
**Invariant: on every seed, the three answers match the scenario's actual structure, and exactly
one scenario is NOT binomial (so the question teaches the test, not just the checklist).**

### q2-binomial-notation.php — 3 parts
A scenario giving n and p in prose. Parts: (a) `numfunc` — n; (b) `numfunc` — p; (c) `numfunc` —
q = 1 − p. **Invariant: on every seed, q = 1 − p exactly, and the scenario states n and p the
same way the section does (e.g. "70% of students do homework on time, class of 50").** The
solution guide writes `X ~ B(n, p)` and reads it back.

### q3-binomial-exact-prob.php — 1 part
`P(X = k)` for a binomial scenario, precomputed. **Invariant: on every seed the answer equals
`C(n,k) * p^k * q^(n-k)` to 4 decimals.** Use small n (5–12) and clean p so the arithmetic is
checkable; show the formula in the guide.

### q4-binomial-at-most.php — 1 part
`P(X <= k)` cumulative, precomputed. **Invariant: the answer equals the sum of `P(X = x)` for
x = 0..k to 4 decimals.** The guide shows the running total, exactly like the section's
"at most 12" example.

### q5-binomial-at-least.php — 1 part
`P(X >= k)` via the complement `1 - P(X <= k-1)`, precomputed. **Invariant: the answer equals
`1 -` the cumulative up to `k-1` to 4 decimals.** The guide teaches the complement as the route
worth learning.

### q6-binomial-mean-sd.php — 2 parts
Given n and p. Parts: (a) `numfunc` — `mu = np`; (b) `numfunc` — `sigma = sqrt(npq)`.
**Invariant: mu = np exactly; sigma = sqrt(npq) to 4 decimals.** Pick n·p clean (integer or one
decimal).

### q7-binomial-translate.php — 3 parts
Three different wordings of the same scenario's probability question (e.g. "at least 40", "more
than 3", "at most 12"). Parts: (a)–(c) `choices` — which inequality symbol each phrase
translates to. **Invariant: the answers are constant across seeds (the phrases are fixed; only the
context text varies), and each is the symbol the section's Context Pause names.** The guide's
point: mistranslating the phrase is the most common way a correct formula produces a wrong answer.

### q8-not-binomial.php — 2 parts
A scenario where trials are NOT independent (drawing without replacement, or a shrinking pool).
Parts: (a) `choices` — is this a binomial experiment? (b) `choices` — which condition fails?
**Invariant: the answers are constant across seeds (every scenario fails independence).** The
guide explains: the probability of a success changes from trial to trial, so condition 3 fails.

### q9-binomial-compare-outcomes.php — 2 parts
A binomial scenario with two nearby k values on the same side of the mean. Parts: (a) `numfunc` —
`P(X = k1)`; (b) `numfunc` — `P(X = k2)`, where k1 is nearer the mean. **Invariant: on every
seed, `P(X = k1) > P(X = k2)` — the value nearer the mean is more likely — and both are
precomputed to 4 decimals.** The guide justifies numerically, like the section's cancer example.

### pre-frq-grade-a-binomial-setup.php — 3 parts
Authored-first pre-FRQ (no mirror). Categories, 12 pts: **Name the Parameters (2)** —
state n and p and write `X ~ B(n, p)`; **State the Probability Question (5)** — translate the
English into `P(X ... k)`; **Compute and Interpret (5)** — evaluate and read the result in
context. Dropped category: **Name the Parameters** (a student can write the probability question
and compute it without ever stating n and p — the section's "decide what one trial is, what
counts as a success, how many trials there are" step, which the other two categories imply but
never demand). Not in the used table (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier
Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the
Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1 State the Values,
4.2 Verify the Sum). Invariant structure per `pre-frq-template.md`: `array("choices", "multans",
"choices")`, `$scoremethod[1] = "allornothing"`, four responses built by concatenating one
sentence per category then dropping one, part (b) grades a DIFFERENT response than part (a)
names. **Invariant: read each response against every rubric line — no response earns a category
it is supposed to be missing (category purity), and every number inside a response is generated
from the same variables as the scenario.** Scope the CSS with `.qscope43`.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the ten you did not finish, if any.

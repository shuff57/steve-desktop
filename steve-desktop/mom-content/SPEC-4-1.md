# SPEC — §4.1 Probability Distribution Function for a Discrete Random Variable (Intro Stats -SH)

Write **five** IMathAS question files into `mom-content/questions/probability/expected-value/`
(the files already there are the pattern to copy — **read `q4-ev-discrete-rv.php`,
`q3-ev-fill-missing-prob.php` and `q22-expected-value-of-discrete-rv.php` before starting**, plus
`questions/probability/expected-value/AGENTS.md` and `questions/probability/AGENTS.md`). Match
their shape — white-card UI, blue-chip part labels, `jointrandfrom` parallel arrays with
precomputed answers, `numfunc` for numeric parts — and their solution-guide voice.

**Scope note:** §4.1 of the bookSHelf Intro Stats (OpenStax numbering) is *probability
distribution function (PDF) for a discrete random variable* — define the random variable, list its
values, the two legitimacy characteristics, read `P(x = k)` / `P(x > k)` / `P(x ≥ k)` off a table,
and build a PDF table from a prose description. Expected value appears in the section's problem
set but is not the focus — that is §4.2. The five reuses already cover EV; the five new files
must carry the **4.1 core**: defining, checking, reading and building distributions.

## The ten (roster is final — do not reorder, do not rebalance)

Reuses (all `health=ok`, never filed, `qid=null` — attach unchanged, do not rewrite):

| Slot | File | Covers |
|---|---|---|
| 1 | `questions/probability/q22-expected-value-of-discrete-rv.php` | sum-to-1 check + E(X) + E(X²) |
| 2 | `questions/probability/expected-value/q3-ev-fill-missing-prob.php` | sum-to-1 to fill a gap, then E(X) |
| 3 | `questions/probability/expected-value/q4-ev-discrete-rv.php` | validity check + E(X) |
| 4 | `questions/probability/expected-value/q5-ev-game-of-chance-2.php` | EV, 100-play total, fair? |
| 5 | `questions/probability/expected-value/q2-ev-insurance.php` | EV of an insurance policy |

New files to author (slots 6–10):

| Slot | File | Parts | Points |
|---|---|---|---|
| 6 | `q1-define-random-variable.php` | 3 | 8 |
| 7 | `q2-legitimacy-check.php` | 3 | 10 |
| 8 | `q3-read-probabilities-from-table.php` | 3 | 10 |
| 9 | `q4-build-pdf-from-description.php` | 3 | 10 |
| 10 | `pre-frq-grade-an-ev-decision.php` | 3 | 12 |

Points: 5×10 + 8 + 10 + 10 + 10 + 12 = **100**.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your five files. It checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[` (plural — it silently leaves a part with no key); no `$answer[]`
after the QUESTION TEXT marker (that section is the SOLUTION field, not the key); no article in
front of an interpolated noun (kind `article` — check each hit is singular and consonant-sounded);
no marker text quoted inside a comment. The article check shipped three times before it was a
lint; the lint only lists the sites, so look at each one.

**Also seed-sweep each one yourself** with a throwaway node script: loop every combination your
`rand()` calls can produce and assert the invariant named per question below. One render proves one
seed; the sweep is what proves the rest. Report the number of combinations checked.

## The non-negotiable dialect rules

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section.
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**.
- Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun.
- **Every `choices`/`select` part carries `$noshuffle[N] = "all"`** — MOM shuffles choices by
  default and a fixed `$answer[N]` index then points at the wrong option.
- **No `essay` parts anywhere** (homework carries no free response).
- **`numfunc` for numeric answers** (MathQuill — accepts decimals, fractions, arithmetic);
  `abstolerance = 0.005` matches the family. Never `number`.
- **Precompute every answer**; never let MOM compute `E(X)` via macros (no built-in).
- `pow()` is blocked; use `^`. `number_format()` is blocked; use `round()`.
- Type picker is **Multipart** even for single-part questions.

## The five new

Each is `multipart` and auto-graded. Randomization via `jointrandfrom` with 4–6 parallel-array
scenarios, exactly as the family does. Precompute the answers.

### q1-define-random-variable.php — 3 parts
A prose scenario (contexts in threes). Part (a) `choices`: which sentence correctly defines the
random variable `X`. Part (b) `choices`: which set lists the values `X` can take. Part (c)
`number`: how many possible values `X` has. **Invariant: on every seed, the listed values match
the scenario, and part (c) equals the count of values in the correct list of part (b).**

### q2-legitimacy-check.php — 3 parts
A distribution table, some legitimate and some not. Part (a) `choices`: does the table satisfy
BOTH characteristics (each `P(x)` in [0,1] and the sum exactly 1)? Part (b) `number`: the sum of
the `P(x)` column. Part (c) `choices`: which characteristic fails, if any — with a "both hold"
option. **Invariant: on every seed, part (a) is consistent with part (b) — legitimate iff sum is
exactly 1 and no entry exceeds 1 — and part (c)'s named failure actually fails.**

### q3-read-probabilities-from-table.php — 3 parts
A legitimate table with 5–6 values. Parts: `P(x = k)`, `P(x > k)`, `P(x ≥ k)` for chosen `k`s
(mix strict and inclusive on purpose). All `number`. **Invariant: on every seed the three answers
are sums of the printed rows, and `P(x > k) ≤ P(x ≥ k)` with equality iff the row `x = k` has
probability 0 — pick `k`s where they differ.**

### q4-build-pdf-from-description.php — 3 parts
A prose description with percentages (contexts in threes, one value buried in the last clause).
Part (a) `number`: the missing/buried probability (found by summing the others and subtracting
from 1). Part (b) `number`: `E(X)` of the built table. Part (c) `choices`: what the `P(x)`
column must sum to. **Invariant: on every seed the percentages sum to 1, part (a) is
1 − (sum of the others), and part (b) is the precomputed `E(X)` of the completed table.**

### pre-frq-grade-an-ev-decision.php — 3 parts
The pre-FRQ mirror of `questions/frq/probability/q1-expected-value-decision.php` — read that file
first and take the target strings from it (per `mom-content/reference/pre-frq-template.md`, which
also holds the invariant structure: `array("choices", "multans", "choices")`, `$scoremethod[1] =
"allornothing"`, four responses built by concatenating one sentence per category then dropping
one, part (b) grades a DIFFERENT response than part (a) names). Rubric categories from the mirror
FRQ: **Compute Expected Value (6)** and **Decision and Interpretation (4)** — the pre-FRQ adds a
third category that the mirror's rubric implies but never states, to make the dropped category
meaningful. Drop **"State the Values and Probabilities"** (the checklist's first line: a student
can show the arithmetic and the decision without ever listing `X` and `P(X)` separately — the 
mirror FRQ's first requirement, and the one a plausible answer skips). This dropped category is
not in the used table (2.3 Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6
Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3
Second Factor, 3.4 State the Direction, 3.5 Draw the Structure). **Invariant: read each of the
four responses against every rubric line — no response earns a category it is supposed to be
missing (category-purity), and every number inside a response is generated from the same
variables as the scenario.** Scope the CSS with a `.qscope` class (e.g. `.qscope41`) since the
pre-FRQ shares the assignment page with the reuses.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you
could not check. State plainly which of the five you did not finish, if any — an honest short list
beats five files where two were rushed.

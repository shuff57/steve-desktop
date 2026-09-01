# The "Find the Mistake" pattern (IM1 / IM3)

A **find-the-mistake** question is the IM1/IM3 replacement for the stats pre-FRQ. Where a pre-FRQ
shows a rubric and four sample *responses*, this shows **one sample piece of work** — a solution
written out by a hypothetical student — and asks the student to audit it: which step went wrong,
what the correct value is, and (usually) why the error happened.

The pedagogical point is the same as the pre-FRQ's: the cheapest way to teach a procedure is to
make the student *grade* it rather than perform it. IM1/IM3 carry no FRQs and no pre-FRQs (Steve's
call — those are a stats pattern), so this is the auto-graded error-spotting item every IM1/IM3
assignment gets instead.

## The rule

**One per assignment, never zero.** Steve's call for IM1/IM3. The default is one, and one only —
two never, unless a chapter capstone genuinely carries two different error-habit targets (and say
which two in the spec if so).

**"The section has no question to mirror" is not an exemption.** The find-the-mistake question is
written against the *skill* of the section, not against a banked question: take the section's
topic, write a worked solution that contains one planted error, and have the student find it. The
worked solution is authored fresh from the section's skill, so there is always something to audit.

## Placement and naming

- **Last slot of the assignment** (same slot policy as pre-FRQs). Usually worth 8–10 points (it is
  shorter than a pre-FRQ: three parts, no rubric grading).
- `questions/<family>/find-the-mistake-in-<topic>.php` — `find-the-mistake-in-linear-inequality.php`,
  `find-the-mistake-in-sequence-formula.php`. File the family's existing folder
  (`questions/im1/`, `questions/im3/`, or the topic folder).
- Description line: `Find the Mistake in <Topic> - One step of the worked solution contains an
  error; identify the step, correct the value, and name the habit the error targets`.

## The invariant structure

Verified on the inequalities example. Deviating from any of this is a defect, not a style choice:

```
$anstypes = array("choices", "numfunc", "choices")   // three parts, always
$answer[0]  = <index of the wrong step>              // which step has the error
$answer[1]  = <the correct value for that step>      // numeric
$answer[2]  = <index of the habit that caused it>    // why the error is likely
```

- **Part (a)** — which numbered step of the student's work is wrong. `choices`, one of the steps.
  All other steps are correct; only one differs from the true solution.
- **Part (b)** — the correct value/expression for the wrong step (a number, or the corrected line).
  This is what forces the student to actually redo the step, not just read it.
- **Part (c)** — the *habit* the error models (the sign-flip on division by a negative, adding
  instead of subtracting, an off-by-one in indexing, …). Four choices, one right; distractors are
  real errors a student would actually make, not filler.

## Building the worked solution

Write the solution as a numbered list of steps, exactly as a student would show work. **Only one
step carries the error**; the others are genuinely correct.

```
Step 1: 3x + 5 <= 2x + 9       (the setup — always correct)
Step 2: 3x - 2x <= 9 - 5       (correct: 3x - 2x = x, 9 - 5 = 4)
Step 3: x <= 4                 (correct result)
```

Then make the wrong version differ in exactly one of those steps:

```
Step 2 (wrong): 3x - 2x <= 9 + 5    <- "moved the 5 to the other side and added"
Step 3 (from that): x <= 14
```

Every number inside the worked solution has to be generated from the same variables the scenario
uses, so the error is real on every seed and the corrected value is unambiguous. Precompute the
wrong line and the corrected line per scenario in `jointrandfrom` — never compute the wrong value
at runtime (a typo in the formula is a wrong answer on some seeds).

## Traps

- **`$answer[1]` must be the corrected value, not the wrong one.** The whole point of part (b) is
  that the student produces the true value. Get that wrong once and the question grades the error
  as correct.
- **Do not plant a second error.** Every other step must be genuinely correct on every seed.
  Re-read the worked solution against its own algebra before filing — a second planted error makes
  part (a) ambiguous.
- **The error must be plausible and of the classic kind.** A step is "wrong" only if it is the
  mistake the section actually teaches students to avoid (sign flip, wrong operation, off-by-one).
  A typo (e.g. "3" written where "4" belongs) teaches nothing; the habit is the lesson.
- **Part (b) tolerance.** If the corrected value is exact, use `number`/`numfunc` with
  `$abstolerance` loose enough for a human-equivalent entry. If the corrected answer is an
  expression (e.g., a rewritten line), prefer `choices` for it instead of free text — MOM has no
  "enter an equivalent expression" for arbitrary text.
- **Run the lint.** `node mom-content/reference/question-lint.mjs mom-content/questions` — it
  catches the `$answer[s]` typo and article errors, same as the pre-FRQ rule. Also do the
  render-and-verify pass on a real course; the sandbox cannot exercise a `choices` question the way
  the live preview can.

## Used so far

| Assignment | Family | Error habit | Notes |
|---|---|---|---|
| (example) 9.1.1 Solving Linear, One-Variable Inequalities | `questions/inequalities/` | sign flip on division by a negative | authored 2026-08-20, not yet pushed |
| IM1 1.2 Representing a Growing Pattern | `questions/im1/` | counting a SHARED piece once per part (double-counted overlap) | authored 2026-08-31, filed as qsetid 1893405, graded correct in Teacher Preview |

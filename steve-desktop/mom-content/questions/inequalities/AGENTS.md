# Inequalities Questions — Solving Linear Inequalities

**Parent:** `../../AGENTS.md`
**Files:** 2 autograded questions covering solving a two-sided linear inequality with sign-flip awareness and auditing a worked solution for the sign-flip error

## OVERVIEW

The inequalities family has two items: solving `ax + b <= cx + d` algebraically and identifying whether the solution direction flips, and a find-the-mistake question where the student audits a worked solution that commits the sign-flip error. The latter is the IM1/IM3 replacement for the stats pre-FRQ — see `mom-content/reference/find-the-mistake-template.md`.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-solve-linear-inequality.php` | 2 | number, choices (select) | Boundary value and direction (x <= k or x >= k) for a randomized two-sided linear inequality |
| `find-the-mistake-in-linear-inequality.php` | 3 | choices, choices, choices | A worked solution commits the sign flip; name the wrong step, pick the corrected inequality, name the habit |

## CONVENTIONS

1. **Auto-graded only.** `number` for the boundary value, `choices` with `displayformat="select"` for the inequality direction.
2. **Six parallel-array scenarios.** Odd-indexed scenarios produce a sign flip; even-indexed do not. This guarantees students see both cases across attempts.
3. **Sign-flip callout.** When `$coeff_diff < 0`, the solution guide injects a yellow warning box (`background:#fff3cd; border-left:4px solid #f59e0b`) explaining the flip. This is built into `$flip_note` in CC.
4. **Display helpers.** `$lhs` and `$rhs` are built with sign-handling (`$b >= 0` / `else abs($b)`) so the rendered inequality never shows `"+ -"`.
5. **Solution guide.** `$solutionguide` uses the standard `.sol-wrap details/summary` collapsible with two algebraic steps plus the final result.
6. **Find-the-mistake rule.** The find-the-mistake question must keep `(a - c) < 0` on every scenario (the planted error only exists when division flips the sign), must show exactly three steps with only Step 3 wrong, and the corrected solution in the guide must use the flipped direction.

## ADDING A NEW INEQUALITIES QUESTION

1. Decide whether the new item is compound (e.g., `a < ax + b < c`) or involves absolute value; copy `q1` as a starting shell.
2. Construct scenarios from boundary first; verify `(d - b) / (a - c)` is a clean integer for each row.
3. Add numbered subsection comments in CC and place `loadlibrary("stats")` + `$anstypes` at the top of CC.
4. Set question type to **Multipart** in MOM.

## GOTCHAS

- `$dir_choices` builds the dropdown labels dynamically (`"x <= " . $boundary` and `"x >= " . $boundary`). If you add a third direction option, update both `$dir_choices` and `$answer[1]` index.
- `$abstolerance[0] = 0.01` is set for the boundary number, but all current boundaries are exact integers -- this is just defensive tolerance for future non-integer boundaries.
- The AsciiMath backtick notation (`` `$lhs <= $rhs` ``) is used in both the question display and solution steps. Test that `$lhs` and `$rhs` do not contain characters that break AsciiMath parsing (e.g., bare `<` should be inside backticks, not in raw HTML context).
